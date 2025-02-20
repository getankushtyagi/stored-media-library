<?php

namespace Ankushtyagi\MediaLibrary\Models;

use Illuminate\Database\Eloquent\Model;

class MediaConversion extends Model
{
    protected $fillable = [
        'name',
        'path',
        'size'
    ];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}