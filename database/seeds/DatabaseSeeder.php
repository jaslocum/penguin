<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //$this->call(UserTableSeeder::class);
        $this->call(BucketTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(ImageTableSeeder::class);

    }
}

