<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('city.cities') as  $value) {
           \App\Models\City::create([
               'city'=> $value
           ]);
        }

   /*      foreach (config('feed') as  $value) {
            \App\Models\Post::create([
                "user_id"=> $value['user_id'],
                "city_id"=> $value['city_id'],
                "videoUrl"=>$value['videoUrl'] ,
                "photoUrl"=> $value['photoUrl'],
                "location"=> $value['location'],
                "description"=> $value['description']
            ]);
         }
 */
        Post::insert(config('feed'));
    }
}
