<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialMediaLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('social_media_links')->insert([
            [
                'name' => json_encode([
                    'en' => 'Facebook',
                    'ar' => 'فيسبوك',
                ]),
                'link' => 'https://www.facebook.com',
                'slug' => 'facebook',
            ],
            [
                'name' => json_encode([
                    'en' => 'Twitter',
                    'ar' => 'تويتر',
                ]),
                'link' => 'https://www.twitter.com',
                'slug' => 'twitter',
            ],
            [
                'name' => json_encode([
                    'en' => 'Instagram',
                    'ar' => 'انستجرام',
                ]),
                'link' => 'https://www.instagram.com',
                'slug' => 'instagram',
            ],
            [
                'name' => json_encode([
                    'en' => 'LinkedIn',
                    'ar' => 'لينكد إن',
                ]),
                'link' => 'https://www.linkedin.com',
                'slug' => 'linkedin',
            ]
        ]);

    }
}
