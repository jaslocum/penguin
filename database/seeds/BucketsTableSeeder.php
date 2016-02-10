<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
class BucketsTableSeeder extends Seeder
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

        // default bucket to store images for this server
        DB::table('buckets')->insert([
            'id' => 1,
            'category_id' => '1',
            'key' => 'penguin'
        ]);

    }

}

