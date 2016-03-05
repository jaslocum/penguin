<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
class BucketTableSeeder extends Seeder
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

        // default bucket to store server application images
        DB::table('bucket')->insert([
            'id' => 1,
            'category_id' => '1',
            'key' => 'penguin',
            'description' => 'image server bucket'
        ]);

    }

}

