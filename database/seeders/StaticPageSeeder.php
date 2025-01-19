<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('static_pages')->insert([
            [
                'title' => json_encode([
                    'en' => 'About us',
                    'ar' => 'من نحن',
                ]),
                'content' => json_encode([
                    'en' => 'About us content',
                    'ar' => 'محتوى من نحن',
                ]),
                'slug' => 'about-us',
            ],
            [
                'title' => json_encode([
                    'en' => 'Contact us',
                    'ar' => 'اتصل بنا',
                ]),
                'content' => json_encode([
                    'en' => 'Contact us content',
                    'ar' => 'محتوى اتصل بنا',
                ]),
                'slug' => 'contact-us',
            ],
            [
                'title' => json_encode([
                    'en' => 'Terms and conditions',
                    'ar' => 'الشروط والأحكام',
                ]),
                'content' => json_encode([
                    'en' => 'Terms and conditions content',
                    'ar' => 'محتوى الشروط والأحكام',
                ]),
                'slug' => 'terms-and-conditions',
            ],
            [
                'title' => json_encode([
                    'en' => 'Privacy policy',
                    'ar' => 'سياسة الخصوصية',
                ]),
                'content' => json_encode([
                    'en' => 'Privacy policy content',
                    'ar' => 'محتوى سياسة الخصوصية',
                ]),
                'slug' => 'privacy-policy',
            ]
        ]);

    }
}
