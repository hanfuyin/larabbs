<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {

        DB::table('replies')->truncate();

        $users  = User::all()->pluck('id')->toArray();
        $faker = app(Faker\Generator::class);

        DB::table('topics')->select('id')->orderBy('id', 'asc')->chunk(50, function ($topics) use ($users, $faker)
        {
            foreach ($topics as $topic)
            {
                $topic_id = $topic->id;
                $replys = factory(Reply::class)->times(random_int(0, 100))->make()->each(function ($reply, $index) use ($users, $topic_id, $faker)
                {
                    $reply->user_id = $faker->randomElement($users);
                    $reply->topic_id = $topic_id;
                });
                Reply::insert($replys->toArray());
            }
        });
    }

}

