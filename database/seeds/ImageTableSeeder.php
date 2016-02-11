<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ImageTableSeeder extends Seeder
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

        DB::table('image')->insert([
            'id' => 1,
            'size' => 29802,
            'bucket_id' => 1,
            'deleted' => false,
            'description' => 'penguin image',
            'filename' => 'penguinWithAttitude.jpg',
            'md5' => 'c4ca4238a0b923820dcc509a6f75849b',
            'mime' => 'image/jpg'
        ]);

    }
}
