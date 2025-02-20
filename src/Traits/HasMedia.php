<?php

namespace Ankushtyagi\MediaLibrary\Traits;

use Ankushtyagi\MediaLibrary\MediaLibrary;
use Ankushtyagi\MediaLibrary\Models\Media;

trait HasMedia
{
    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function addMedia($file)
    {
        $media = app(MediaLibrary::class)->upload($file);
        $this->media()->save($media);
        return $media;
    }

    public function getFirstMedia(string $collection = 'default')
    {
        return $this->media()->where('collection', $collection)->first();
    }

    public function getAllMedia(string $collection = 'default')
    {
        return $this->media()->where('collection', $collection)->get();
    }

    public function clearMediaCollection(string $collection = 'default')
    {
        $this->media()->where('collection', $collection)->get()->each->delete();
    }
}
