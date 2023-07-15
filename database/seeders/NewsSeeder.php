<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
          News::factory(10)->create();

        // DB::table('news')->insert([
        //     'title' => Str::random(10),
        //     'description' => Str::random(10),
        //     'img' =>'',
        // ]);
    }
}
