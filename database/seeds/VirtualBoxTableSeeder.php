<?php

use Illuminate\Database\Seeder;

class VirtualBoxTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $cnt = 0;
        for ($i = 0; $i < $cnt; $i++) {
            \App\VirtualBox::create();
        }
    }
}