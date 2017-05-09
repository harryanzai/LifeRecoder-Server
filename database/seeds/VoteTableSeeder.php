<?php

use Illuminate\Database\Seeder;

class VoteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            factory(\App\Models\Vote::class,60)->create();
        }catch (Exception $e){}

    }
}
