<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CategoriesTableSeeder extends Seeder
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

        DB::table('categories')->insert([
            'category_id' => '1',
            'category_rec_id' => 'penguin',
            'id' => 1,
        ]);

    }

}
