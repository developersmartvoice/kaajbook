<?php

namespace Modules\Translation\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Translation\Models\Translation;

class TranslationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // --
        // Translations table.
        Translation::truncate();
        $data = [
            [
                'name' => 'English',
                'language' => 'en',
                'icon' => 'United-States.png',
                'status' => 1
            ],
            [
                'name' => 'Chinese',
                'language' => 'zh',
                'icon' => 'China.png',
                'status' => 1
            ],
            [
                'name' => 'Czech',
                'language' => 'cs',
                'icon' => 'Czech-Republic.png',
                'status' => 1
            ],
            [
                'name' => 'Dutch',
                'language' => 'nl',
                'icon' => 'Netherlands.png',
                'status' => 1
            ],
            [
                'name' => 'French',
                'language' => 'fr',
                'icon' => 'France.png',
                'status' => 1
            ],
            [
                'name' => 'German',
                'language' => 'de',
                'icon' => 'Germany.png',
                'status' => 1
            ],
            [
                'name' => 'Italian',
                'language' => 'it',
                'icon' => 'Italy.png',
                'status' => 1
            ],
            [
                'name' => 'Japanese',
                'language' => 'ja',
                'icon' => 'Japan.png',
                'status' => 1
            ],
            [
                'name' => 'Portuguese',
                'language' => 'pt',
                'icon' => 'Portugal.png',
                'status' => 1
            ],
            [
                'name' => 'Russian',
                'language' => 'ru',
                'icon' => 'Russia.png',
                'status' => 1
            ],
            [
                'name' => 'Spanish',
                'language' => 'es',
                'icon' => 'Spain.png',
                'status' => 1
            ],
            [
                'name' => 'Turkish',
                'language' => 'tr',
                'icon' => 'Turkey.png',
                'status' => 1
            ],
            [
                'name' => 'Vietnamese',
                'language' => 'vi',
                'icon' => 'Vietnam.png',
                'status' => 1
            ]
        ];
        Translation::insert($data);
    }
}
