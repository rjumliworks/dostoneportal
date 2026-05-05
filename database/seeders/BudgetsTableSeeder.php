<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BudgetsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('budgets')->delete();
        
        \DB::table('budgets')->insert(array (
            0 => 
            array (
                'id' => 1,
                'type_id' => 50,
                'target_id' => 1,
                'year' => '2026',
                'received_at' => '2026-05-05',
                'created_at' => '2026-05-05 12:42:39',
                'updated_at' => '2026-05-05 12:42:39',
            ),
            1 => 
            array (
                'id' => 2,
                'type_id' => 51,
                'target_id' => 1,
                'year' => '2026',
                'received_at' => '2026-05-05',
                'created_at' => '2026-05-05 12:42:39',
                'updated_at' => '2026-05-05 12:42:39',
            ),
            2 => 
            array (
                'id' => 3,
                'type_id' => 51,
                'target_id' => 1,
                'year' => '2026',
                'received_at' => '2026-05-05',
                'created_at' => '2026-05-05 12:42:39',
                'updated_at' => '2026-05-05 12:42:39',
            ),
        ));
        
        
    }
}