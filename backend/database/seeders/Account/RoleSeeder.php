<?php

namespace Database\Seeders\Account;

use App\Models\Account\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    const DATA = [
        [
            'id' => 1,
            'name' => "Admin",
            'slug' => "admin",
        ],
        [
            'id' => 2,
            'name' => "Customer",
            'slug' => "customer",
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();
            foreach (self::DATA as $data) {
                Role::updateOrCreate(
                    [
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'slug' => $data['slug']
                    ],
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
