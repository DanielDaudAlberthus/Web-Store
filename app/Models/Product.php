<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;

class Product extends Model implements HasMedia
{
    //
    use InteractsWithMedia, HasTags;
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('cover') //nambahin collection cover
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();// langsung ga pake queue (dibuat langsung)
    }
}
