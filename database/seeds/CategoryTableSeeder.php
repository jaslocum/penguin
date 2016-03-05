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
            'category' => 'image',
            'description' => 'category for images accessed by image id only',
            'mime' => 'image/jpg, image/jpeg, image/png',
            'max_size_MB' => '5'
        ]);
    }
}
