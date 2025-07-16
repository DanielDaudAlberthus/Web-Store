<?php
declare(strict_types=1); //agar semua tipe data harus didefinisikan

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\Tag as TagsTag;

class Tag extends TagsTag
{
    public function products(){
        return $this->morphedByMany(Product::class, 'taggable');
    }
}