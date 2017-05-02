<?php

use Illuminate\Database\Seeder;

class MoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $statuses = [
            '认真',
            '生气',
            '郁闷',
            '激动',
            '憔悴',
            '平静',
            '悲伤',
            '开心',
            '生气',
            '感谢',
            '感动',
            '失落',
            '甜蜜'
        ];


        foreach ($statuses as $mood){
            \App\Models\Mood::create([
                'status' => $mood
            ]);
        }

    }
}
