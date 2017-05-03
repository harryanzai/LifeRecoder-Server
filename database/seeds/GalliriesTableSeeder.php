<?php

use Illuminate\Database\Seeder;

class GalliriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Gallery::class,60)->create();
    }
}
