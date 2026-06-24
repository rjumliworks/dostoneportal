<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListEventsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('list_events')->delete();
        
        \DB::table('list_events')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Holiday',
                'type' => 'Official',
                'color' => 'text-white',
                'bg' => 'bg-dark',
                'icon' => 'n/a',
                'fields' => '{"tsr": false, "info": false, "title": true, "venue": false, "samples": false, "conforme": false, "customer": false, "employees": false, "quotation": false}',
                'is_out' => 0,
                'is_active' => 1,
                'created_at' => '2026-06-24 13:56:52',
                'updated_at' => '2026-06-24 13:56:52',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Work Suspension',
                'type' => 'Official',
                'color' => 'text-white',
                'bg' => 'bg-danger',
                'icon' => 'n/a',
                'fields' => '{"tsr": false, "info": false, "title": true, "venue": false, "samples": false, "conforme": false, "customer": false, "employees": false, "quotation": false}',
                'is_out' => 0,
                'is_active' => 1,
                'created_at' => '2026-06-24 13:56:52',
                'updated_at' => '2026-06-24 13:56:52',
            ),
        ));
        
        
    }
}