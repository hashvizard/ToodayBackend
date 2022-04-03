<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('feed.videos') as  $value) {
            \App\Models\Post::create([
                'user_id'=> User::all()->random()->id,
                'city_id' => 80,
                'videoUrl' =>$value['sources'],
                'photoUrl'=>$value['thumb'],
                'location'=>$value['subtitle'],
                'description'=>$value['description'],
            ]);
         }
    }
}
