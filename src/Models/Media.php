<?php

namespace Ankushtyagi\MediaLibrary\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'name',
        'file_name',
        'mime_type',
        'size',
        'disk',
        'collection',
        'path',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function conversions()
    {
        return $this->hasMany(MediaConversion::class);
    }

    public function model()
    {
        return $this->morphTo();
    }

    public function getUrl()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getConversionUrl(string $conversion)
    {
        $conversionModel = $this->conversions()->where('name', $conversion)->first();
        return $conversionModel ? Storage::disk($this->disk)->url($conversionModel->path) : null;
    }

    public function delete()
    {
        // Delete all conversions
        foreach ($this->conversions as $conversion) {
            Storage::disk($this->disk)->delete($conversion->path);
            $conversion->delete();
        }

        // Delete original file
        Storage::disk($this->disk)->delete($this->path);

        return parent::delete();
    }
}
