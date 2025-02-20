<?php

namespace Ankushtyagi\MediaLibrary;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Ankushtyagi\MediaLibrary\Models\Media;

class MediaLibrary
{
    protected $config;
    
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function upload(UploadedFile $file, string $collection = 'default', array $options = [])
    {
        $path = $this->generatePath($file);
        $disk = $options['disk'] ?? config('media-library.disk');
        
        // Store original file
        $originalPath = Storage::disk($disk)->putFileAs(
            $path,
            $file,
            $this->generateFileName($file)
        );

        // Create media record
        $media = Media::create([
            'name' => $file->getClientOriginalName(),
            'file_name' => basename($originalPath),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'disk' => $disk,
            'collection' => $collection,
            'path' => $originalPath,
            'metadata' => $this->extractMetadata($file),
        ]);

        // Generate conversions if it's an image
        if ($this->isImage($file)) {
            $this->handleImageConversions($media, $options['conversions'] ?? []);
        }

        return $media;
    }

    protected function generatePath(UploadedFile $file): string
    {
        return 'media/' . date('Y/m/d');
    }

    protected function generateFileName(UploadedFile $file): string
    {
        return uniqid() . '_' . $file->getClientOriginalName();
    }

    protected function isImage(UploadedFile $file): bool
    {
        return str_starts_with($file->getMimeType(), 'image/');
    }

    protected function handleImageConversions(Media $media, array $conversions)
    {
        foreach ($conversions as $name => $settings) {
            $image = Image::make(Storage::disk($media->disk)->path($media->path));
            
            if (!empty($settings['width']) || !empty($settings['height'])) {
                $image->resize($settings['width'] ?? null, $settings['height'] ?? null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            if (!empty($settings['quality'])) {
                $image->quality($settings['quality']);
            }

            $conversionPath = $this->generateConversionPath($media, $name);
            Storage::disk($media->disk)->put($conversionPath, $image->encode());

            $media->conversions()->create([
                'name' => $name,
                'path' => $conversionPath,
                'size' => Storage::disk($media->disk)->size($conversionPath),
            ]);
        }
    }

    protected function generateConversionPath(Media $media, string $conversion): string
    {
        return dirname($media->path) . '/conversions/' . $conversion . '_' . basename($media->path);
    }

    protected function extractMetadata(UploadedFile $file): array
    {
        $metadata = [
            'original_name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
        ];

        if ($this->isImage($file)) {
            list($width, $height) = getimagesize($file->getPathname());
            $metadata['dimensions'] = [
                'width' => $width,
                'height' => $height
            ];
        }

        return $metadata;
    }
}