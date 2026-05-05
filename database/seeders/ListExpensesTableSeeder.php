<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListExpensesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('list_expenses')->delete();
        
        \DB::table('list_expenses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => '5010101001',
            'name' => 'Salaries and Wages (Regular)',
                'short' => 'Basic Salary',
                'is_active' => 1,
                'category_id' => 55,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            1 => 
            array (
                'id' => 2,
                'code' => '5010201001',
                'name' => 'Personal Economic Relief Allowance',
                'short' => 'PERA',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            2 => 
            array (
                'id' => 3,
                'code' => '5010202000',
                'name' => 'Representation Allowance',
                'short' => 'RA',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            3 => 
            array (
                'id' => 4,
                'code' => '5010204001',
                'name' => 'Clothing/Uniform Allowance',
                'short' => 'Clothing',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            4 => 
            array (
                'id' => 5,
                'code' => '5010214001',
                'name' => 'Laundry Allowance',
                'short' => '',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            5 => 
            array (
                'id' => 6,
                'code' => '5010299036',
                'name' => 'Honoraria and Premiums',
                'short' => '',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            6 => 
            array (
                'id' => 7,
                'code' => '5010299032',
                'name' => 'Transportation Allowance',
                'short' => 'TA',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            7 => 
            array (
                'id' => 8,
                'code' => '5010299033',
                'name' => 'Subsistence Allowance',
                'short' => 'TA',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            8 => 
            array (
                'id' => 10,
                'code' => '5010299034',
                'name' => 'Year-End Bonus',
                'short' => 'TA',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            9 => 
            array (
                'id' => 11,
                'code' => '5010299035',
                'name' => 'Mid-Year Bonus',
                'short' => 'TA',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            10 => 
            array (
                'id' => 12,
                'code' => '5010299037',
                'name' => 'Cash Gift',
                'short' => 'TA',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            11 => 
            array (
                'id' => 13,
                'code' => '5010299038',
                'name' => 'Longevity Pay',
                'short' => 'TA',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            12 => 
            array (
                'id' => 14,
                'code' => '5010299039',
                'name' => 'Productivity Enhancement Incentive',
                'short' => 'TA',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            13 => 
            array (
                'id' => 15,
                'code' => '5010299040',
                'name' => 'Retirement and Life Insurance Premiums',
                'short' => 'TA',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            14 => 
            array (
                'id' => 17,
                'code' => '5010299041',
                'name' => 'Pag-Ibig',
                'short' => 'TA',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            15 => 
            array (
                'id' => 18,
                'code' => '5010299042',
                'name' => 'PhilHealth',
                'short' => 'TA',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            16 => 
            array (
                'id' => 19,
                'code' => '5010299043',
                'name' => 'Terminal Leave Benefits',
                'short' => 'TA',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            17 => 
            array (
                'id' => 21,
                'code' => '5010299044',
                'name' => 'Lump-sum for Step Increments',
                'short' => 'Length of Service',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            18 => 
            array (
                'id' => 22,
                'code' => '5010299045',
                'name' => 'Loyalty Award',
                'short' => '',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            19 => 
            array (
                'id' => 23,
                'code' => '5010299046',
                'name' => 'Employees Compensation Insurance Premium',
                'short' => 'ECIP',
                'is_active' => 1,
                'category_id' => 56,
                'class_id' => 50,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            20 => 
            array (
                'id' => 24,
                'code' => '1',
                'name' => 'Local',
                'short' => NULL,
                'is_active' => 1,
                'category_id' => 57,
                'class_id' => 51,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
            21 => 
            array (
                'id' => 25,
                'code' => '2',
                'name' => 'Foreign',
                'short' => NULL,
                'is_active' => 1,
                'category_id' => 57,
                'class_id' => 51,
                'created_at' => '2026-05-03 12:43:02',
                'updated_at' => '2026-05-03 12:43:02',
            ),
        ));
        
        
    }
}