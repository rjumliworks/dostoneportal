<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BudgetItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('budget_items')->delete();
        
        \DB::table('budget_items')->insert(array (
            0 => 
            array (
                'id' => 1,
                'amount' => '23196000.00',
                'expense_id' => 1,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            1 => 
            array (
                'id' => 2,
                'amount' => '720000.00',
                'expense_id' => 2,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            2 => 
            array (
                'id' => 3,
                'amount' => '480000.00',
                'expense_id' => 3,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            3 => 
            array (
                'id' => 4,
                'amount' => '480000.00',
                'expense_id' => 7,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            4 => 
            array (
                'id' => 5,
                'amount' => '210000.00',
                'expense_id' => 4,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            5 => 
            array (
                'id' => 6,
                'amount' => '1560000.00',
                'expense_id' => 8,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            6 => 
            array (
                'id' => 7,
                'amount' => '234000.00',
                'expense_id' => 5,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            7 => 
            array (
                'id' => 8,
                'amount' => '7647000.00',
                'expense_id' => 6,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            8 => 
            array (
                'id' => 9,
                'amount' => '1318000.00',
                'expense_id' => 13,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            9 => 
            array (
                'id' => 10,
                'amount' => '1933000.00',
                'expense_id' => 10,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            10 => 
            array (
                'id' => 11,
                'amount' => '1933000.00',
                'expense_id' => 11,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            11 => 
            array (
                'id' => 12,
                'amount' => '150000.00',
                'expense_id' => 12,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            12 => 
            array (
                'id' => 13,
                'amount' => '150000.00',
                'expense_id' => 14,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            13 => 
            array (
                'id' => 14,
                'amount' => '2783000.00',
                'expense_id' => 15,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            14 => 
            array (
                'id' => 15,
                'amount' => '72000.00',
                'expense_id' => 17,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            15 => 
            array (
                'id' => 16,
                'amount' => '556000.00',
                'expense_id' => 18,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            16 => 
            array (
                'id' => 17,
                'amount' => '36000.00',
                'expense_id' => 23,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            17 => 
            array (
                'id' => 18,
                'amount' => '58000.00',
                'expense_id' => 21,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            18 => 
            array (
                'id' => 19,
                'amount' => '10000.00',
                'expense_id' => 22,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            19 => 
            array (
                'id' => 20,
                'amount' => '4400000.00',
                'expense_id' => 24,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
            20 => 
            array (
                'id' => 21,
                'amount' => '330000.00',
                'expense_id' => 25,
                'budget_id' => 1,
                'created_at' => '2026-05-05 12:45:12',
                'updated_at' => '2026-05-05 12:45:12',
            ),
        ));
        
        
    }
}