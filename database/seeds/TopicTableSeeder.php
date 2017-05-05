<?php

use Illuminate\Database\Seeder;

class TopicTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topics = [
            '早安故事',
            '晚安故事',
            '关于爱情',
            '品生活',
            '美食',
            '旅行',
            '诗和远方',
            '感悟',
            '他/她'
        ];

        $faker = \Faker\Factory::create('zh_CN');

        foreach ($topics as $topic){
            \App\Models\Topic::create([
                'name' => $topic,
                'bio' => $faker->sentence

            ]);
        }
    }
}
