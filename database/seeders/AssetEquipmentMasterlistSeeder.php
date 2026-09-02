<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

/**
 * DOST-IX Masterlist of Equipment (ISO 9000), as of 27 August 2026.
 *
 * Reads database/data/equipment_masterlist.json and upserts into
 * asset_equipments + asset_equipment_details.
 *
 * Idempotent: matches on old_code, so re-running updates instead of duplicating.
 *
 *   php artisan db:seed --class=AssetEquipmentMasterlistSeeder
 */
class AssetEquipmentMasterlistSeeder extends Seeder
{
    /** Category label => type_id in your lookup table (list_data, type=Asset). */
    protected array $typeMap = [
        'Air Conditioner'       => 145, // list_data: "Air Conditioner"
        'All-in-One Computer'   => 138, // list_data: "All-in-One Computer"
        'Desktop Computer'      => 140, // list_data: "Desktop Computer"
        'Generator Set'         => 143, // list_data: "Genset"
        'LCD Projector'         => 142, // list_data: "LCD Projector"
        'Laptop Computer'       => 139, // list_data: "Laptop"
        'Mini Computer'         => 226, // list_data: "Mini Computer"
        'Motor Vehicle'         => 137, // list_data: "Vehicle"
        'Server Computer'       => 141, // list_data: "Server Computer"
    ];

    protected int $statusId = 61;   // e.g. 'Serviceable'
    protected int $userId    = 1;   // encoder

    public function run(): void
    {
        $path = database_path('seeders/data/equipment_masterlist.json');

        abort_unless(is_file($path), 500, "Missing data file: {$path}");

        $rows = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        $now  = Carbon::now();

        $next = (int) (DB::table('asset_equipments')
            ->where('code', 'like', 'DOSTIX-EQ-%')
            ->selectRaw("COALESCE(MAX(CAST(SUBSTRING_INDEX(code, '-', -1) AS UNSIGNED)), 0) AS n")
            ->value('n'));

        DB::transaction(function () use ($rows, $now, &$next) {
            foreach ($rows as $row) {
                $typeId = $this->typeMap[$row['category']] ?? null;

                $equipment = DB::table('asset_equipments')
                    ->where('old_code', $row['old_code'])
                    ->first();

                $attributes = [
                    'name'        => $row['name'],
                    'type_id'     => $typeId,
                    'remarks'     => $row['remarks'],
                    'status_id'   => $this->statusId,
                    'user_id'     => $this->userId,
                    'acquired_at' => $row['acquired_at'],
                    'updated_at'  => $now,
                ];

                if ($equipment) {
                    $id = $equipment->id;
                    DB::table('asset_equipments')->where('id', $id)->update($attributes);
                } else {
                    $id = DB::table('asset_equipments')->insertGetId($attributes + [
                        'code'       => sprintf('DOSTIX-EQ-%04d', ++$next),
                        'old_code'   => $row['old_code'],
                        'created_at' => $now,
                    ]);
                }

                DB::table('asset_equipment_details')->updateOrInsert(
                    ['equipment_id' => $id],
                    [
                        'brand'         => $row['brand'],
                        'model'         => $row['model'],
                        'price'         => $row['price'],
                        'specification' => json_encode($row['specification'], JSON_UNESCAPED_UNICODE),
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ]
                );
            }
        });

        $this->command->info(count($rows) . ' equipment records imported.');
    }
}
