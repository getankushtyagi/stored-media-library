<?php

namespace Ankushtyagi\MediaLibrary\Facades;

use Illuminate\Support\Facades\Facade;

class MediaLibrary extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'media-library';
    }
}