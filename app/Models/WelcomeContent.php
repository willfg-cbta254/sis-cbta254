<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeContent extends Model
{
    protected $table = 'welcome_contents';

    protected $fillable = [
        'hero_badge',
        'hero_title',
        'hero_description',
        'hero_image',
        'hero_btn_text',
        'hero_btn_secondary_text',
        'hero_slides',
        'meta_title',
        'meta_description',
        'careers',
        'gallery',
        'announcements',
        'contact_address',
        'contact_email',
        'contact_phone',
        'footer_description',
    ];

    protected function casts(): array
    {
        return [
            'hero_slides' => 'array',
            'careers' => 'array',
            'gallery' => 'array',
            'announcements' => 'array',
        ];
    }
}
