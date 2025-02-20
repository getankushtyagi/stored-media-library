// README.md
# Laravel Media Library

A powerful media library manager for Laravel applications.

## Installation

```bash
composer require ankushtyagi/laravel-media-library
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --provider="Ankushtyagi\MediaLibrary\MediaLibraryServiceProvider" --tag="config"
```

Run the migrations:

```bash
php artisan migrate
```

## Usage

Add the HasMedia trait to your model:

```php
use Ankushtyagi\MediaLibrary\Traits\HasMedia;

class Post extends Model
{
    use HasMedia;
}
```

Basic usage:

```php
// Upload file
$post->addMedia($request->file('image'));

// Get media URL
$url = $post->getFirstMedia()->getUrl();

// Get conversion URL
$thumbUrl = $post->getFirstMedia()->getConversionUrl('thumb');
```