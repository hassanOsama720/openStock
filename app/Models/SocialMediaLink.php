<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SocialMediaLink extends Model
{
    use HasTranslations;
    protected $fillable = ['name', 'link', 'slug'];

    public array $translatable = ['name'];
}
