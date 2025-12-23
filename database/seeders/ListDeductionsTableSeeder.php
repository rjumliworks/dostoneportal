<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListDeductionsTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('list_deductions')->delete();
        
        \DB::table('list_deductions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Philhealth',
                'subtype' => 'n/a',
                'is_regular' => 1,
                'is_contribution' => 1,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Pag-ibig I',
                'subtype' => 'n/a',
                'is_regular' => 1,
                'is_contribution' => 1,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Pag-ibig II',
                'subtype' => 'n/a',
                'is_regular' => 1,
                'is_contribution' => 1,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'HDMF Housing Loan',
                'subtype' => 'n/a',
                'is_regular' => 1,
                'is_contribution' => 0,
                'is_loan' => 1,
                'is_enrollable' => 1,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Multi-Purpose Loan',
                'subtype' => 'Pag-ibig Loan',
                'is_regular' => 1,
                'is_contribution' => 0,
                'is_loan' => 1,
                'is_enrollable' => 1,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Calamity Loan',
                'subtype' => 'Pag-ibig Loan',
                'is_regular' => 1,
                'is_contribution' => 0,
                'is_loan' => 1,
                'is_enrollable' => 1,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Current Month',
                'subtype' => 'GSIS Life',
                'is_regular' => 1,
                'is_contribution' => 1,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Prior Year',
                'subtype' => 'GSIS Life',
                'is_regular' => 1,
                'is_contribution' => 0,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Policy Loan',
                'subtype' => 'n/a',
                'is_regular' => 1,
                'is_contribution' => 0,
                'is_loan' => 0,
                'is_enrollable' => 1,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Multi-Purpose Loan',
                'subtype' => 'n/a',
                'is_regular' => 1,
                'is_contribution' => 0,
                'is_loan' => 1,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'SIKAT MDABP',
                'subtype' => 'n/a',
                'is_regular' => 1,
                'is_contribution' => 1,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'SSS Contribution',
                'subtype' => 'n/a',
                'is_regular' => 1,
                'is_contribution' => 1,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'AMAPHIL',
                'subtype' => 'n/a',
                'is_regular' => 1,
                'is_contribution' => 1,
                'is_loan' => 0,
                'is_enrollable' => 1,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Withholding Tax',
                'subtype' => 'n/a',
                'is_regular' => 1,
                'is_contribution' => 1,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'SSS Contribution',
                'subtype' => 'n/a',
                'is_regular' => 0,
                'is_contribution' => 1,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Pag-Ibig Contribution',
                'subtype' => 'n/a',
                'is_regular' => 0,
                'is_contribution' => 1,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Pag-Ibig II',
                'subtype' => 'n/a',
                'is_regular' => 0,
                'is_contribution' => 1,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Philhealth',
                'subtype' => 'n/a',
                'is_regular' => 0,
                'is_contribution' => 1,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'ECC-SSS',
                'subtype' => 'n/a',
                'is_regular' => 0,
                'is_contribution' => 1,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Withholding Tax',
                'subtype' => 'n/a',
                'is_regular' => 0,
                'is_contribution' => 0,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Adjustment',
                'subtype' => 'n/a',
                'is_regular' => 0,
                'is_contribution' => 0,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Adjustment',
                'subtype' => 'n/a',
                'is_regular' => 1,
                'is_contribution' => 0,
                'is_loan' => 0,
                'is_enrollable' => 0,
                'created_at' => '2025-06-20 06:50:44',
                'updated_at' => '2025-06-20 06:50:44',
            ),
        ));

        
    }
}