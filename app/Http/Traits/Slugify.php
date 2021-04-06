<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Slugify {

    public function getSlugifyAttribute() {
        return $this->slugify;
    }

    public function getLogicalNameAttribute() {
        return Str::slug($this->attributes[$this->getSlugifyAttribute()]);
    }
}
