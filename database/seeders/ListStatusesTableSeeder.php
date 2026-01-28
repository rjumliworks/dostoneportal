<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('list_statuses')->delete();
        
        \DB::table('list_statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Not Available',
                'classification' => 'n/a',
                'type' => 'n/a',
                'color' => 'n/a',
                'bg' => 'n/a',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Active',
                'classification' => 'Status',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-success',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Retired',
                'classification' => 'Status',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-info',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Resigned',
                'classification' => 'Status',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Terminated',
                'classification' => 'Status',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-danger',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Dismissed',
                'classification' => 'Status',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-danger',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'End of Contract',
                'classification' => 'Status',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-dark',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Deceased',
                'classification' => 'Status',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-dark',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Absconded',
                'classification' => 'Status',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Active',
                'classification' => 'Contract',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Promoted',
                'classification' => 'Contract',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Salary Increased',
                'classification' => 'Contract',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Terminated',
                'classification' => 'Contract',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Resigned',
                'classification' => 'Contract',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Absorbed',
                'classification' => 'Contract',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Ended',
                'classification' => 'Contract',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Draft',
                'classification' => 'Payroll',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-info',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Pending',
                'classification' => 'Payroll',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Completed',
                'classification' => 'Payroll',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-success',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Cancelled',
                'classification' => 'Payroll',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-danger',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Available',
                'classification' => 'Vehicle',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-success',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'On Travel',
                'classification' => 'Vehicle',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-danger',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Maintenance',
                'classification' => 'Vehicle',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'Pending',
                'classification' => 'Request',
                'type' => 'n/a',
                'color' => 'text-white',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'Recommended',
                'classification' => 'Request',
                'type' => 'n/a',
                'color' => 'text-secondary',
                'bg' => 'bg-primary',
                'icon' => 'ri-radio-button-fill',
                'is_active' => 1,
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'Approved',
                'classification' => 'Request',
                'type' => 'n/a',
                'color' => 'text-primary',
                'bg' => 'bg-info',
                'icon' => 'ri-checkbox-circle-line',
                'is_active' => 1,
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'Ongoing',
                'classification' => 'Request',
                'type' => 'n/a',
                'color' => 'text-info',
                'bg' => 'bg-secondary',
                'icon' => 'ri-question-fill',
                'is_active' => 1,
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'Completed',
                'classification' => 'Request',
                'type' => 'n/a',
                'color' => 'text-success',
                'bg' => 'bg-success',
                'icon' => 'ri-checkbox-circle-fill',
                'is_active' => 1,
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'Cancelled',
                'classification' => 'Request',
                'type' => 'n/a',
                'color' => 'text-danger',
                'bg' => 'bg-danger',
                'icon' => 'ri-close-circle-line',
                'is_active' => 1,
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'Disapproved',
                'classification' => 'Request',
                'type' => 'n/a',
                'color' => 'text-dark',
                'bg' => 'bg-dark',
                'icon' => 'ri-close-circle-fill',
                'is_active' => 1,
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'Pending',
                'classification' => 'Overtime',
                'type' => 'n/a',
                'color' => 'text-warning',
                'bg' => 'bg-warning',
                'icon' => 'ri-close-circle-fill',
                'is_active' => 1,
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'Approved',
                'classification' => 'Overtime',
                'type' => 'n/a',
                'color' => 'text-warning',
                'bg' => 'bg-success',
                'icon' => 'ri-close-circle-fill',
                'is_active' => 1,
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'Disapproved',
                'classification' => 'Overtime',
                'type' => 'n/a',
                'color' => 'text-warning',
                'bg' => 'bg-danger',
                'icon' => 'ri-close-circle-fill',
                'is_active' => 1,
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'Ongoing',
                'classification' => 'Overtime',
                'type' => 'n/a',
                'color' => 'text-warning',
                'bg' => 'bg-info',
                'icon' => 'ri-close-circle-fill',
                'is_active' => 1,
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'Completed',
                'classification' => 'Overtime',
                'type' => 'n/a',
                'color' => 'text-success',
                'bg' => 'bg-success',
                'icon' => 'ri-close-circle-fill',
                'is_active' => 1,
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'Pending',
                'classification' => 'Tag',
                'type' => 'n/a',
                'color' => 'text-warning',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'Joined',
                'classification' => 'Tag',
                'type' => 'n/a',
                'color' => 'text-success',
                'bg' => 'bg-success',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'Left',
                'classification' => 'Tag',
                'type' => 'n/a',
                'color' => 'text-danger',
                'bg' => 'bg-danger',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'Rejected',
                'classification' => 'Tag',
                'type' => 'n/a',
                'color' => 'text-danger',
                'bg' => 'bg-danger',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'Removed',
                'classification' => 'Tag',
                'type' => 'n/a',
                'color' => 'text-dark',
                'bg' => 'bg-dark',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'For Review',
                'classification' => 'Request',
                'type' => 'n/a',
                'color' => 'text-dark',
                'bg' => 'bg-dark',
                'icon' => 'ri-close-circle-fill',
                'is_active' => 1,
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'Pending',
                'classification' => 'Document',
                'type' => 'n/a',
                'color' => 'text-warning',
                'bg' => 'bg-warning',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'Approved',
                'classification' => 'Document',
                'type' => 'n/a',
                'color' => 'text-success',
                'bg' => 'bg-success',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'Active',
                'classification' => 'Visitor',
                'type' => 'n/a',
                'color' => 'text-success',
                'bg' => 'bg-success',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'Inactive',
                'classification' => 'Visitor',
                'type' => 'n/a',
                'color' => 'text-danger',
                'bg' => 'bg-danger',
                'icon' => 'n/a',
                'is_active' => 1,
            ),
        ));
        
        
    }
}