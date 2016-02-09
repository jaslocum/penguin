<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Model::unguard();

        DB::table('category')->insert([
            'id' => 1,
            'category' => 'penguin',
            'description' => 'category to hold images for this image server',
            'mime' => 'image/jpg, image/jpeg, image/png',
            'max_size_MB' => '5'
        ]);

    }
}
