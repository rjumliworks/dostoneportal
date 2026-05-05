<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BudgetAllocationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('budget_allocations')->delete();
        
        \DB::table('budget_allocations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'amount' => '450000.00',
                'expense_id' => 24,
                'project_id' => 1,
                'budget_item_id' => 20,
                'created_at' => '2026-05-05 13:05:44',
                'updated_at' => '2026-05-05 13:05:44',
            ),
            1 => 
            array (
                'id' => 2,
                'amount' => '600000.00',
                'expense_id' => 25,
                'project_id' => 1,
                'budget_item_id' => 20,
                'created_at' => '2026-05-05 13:05:44',
                'updated_at' => '2026-05-05 13:05:44',
            ),
        ));
        
        
    }
}