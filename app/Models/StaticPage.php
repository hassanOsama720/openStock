<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class StaticPage extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'content'];

    protected $fillable = ['title', 'content', 'slug'];
}
