<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\User;
use App\Models\Category;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('topics')->truncate();
        $user_ids = User::all()->pluck('id')->toArray();
        $category_ids = Category::all()->pluck('id')->toArray();

        $faker = app(Faker\Generator::class);

        foreach (range(0,20) as $index)
        {
            $topics = factory(Topic::class)->times(200)->make()->each(function ($topic, $index) use ($user_ids, $category_ids, $faker)
            {
                $topic->user_id = $faker->randomElement($user_ids);
                $topic->category_id = $faker->randomElement($category_ids);
            });

            Topic::insert($topics->toArray());
        }

    }

}

