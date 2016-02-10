<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ImagesTableSeeder extends Seeder
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

        DB::table('images')->insert([
            'id' => 1,
            'bucket_id' => 1,
            'deleted' => false,
            'description' => 'penguin image',
            'filename' => 'penguinWithAttitude.jpg',
            'md5' => '1679091c5a880faf6fb5e6087eb1b2dc',
            'mime' => 'image/jpg'
        ]);

    }
}
