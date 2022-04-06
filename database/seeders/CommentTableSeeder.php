<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;
class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach(range(0,50) as $i) {
            \App\Models\Comment::create([
                'user_id'=> User::all()->random()->id,
                'post_id' => 10,
                'comment' =>$faker->paragraph()
            ]);
        }

    }
}
