<?php

namespace Database\Seeders\Account;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    const DATA = [
        [
            'id' => 1,
            'name' => "Admin",
            'email' => "admin@warung.com",
            'password' => 'AdminWarung',
            'role_id' => 1,
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
                User::updateOrCreate(
                    [
                        'id' => $data['id'],
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => Hash::make($data['password']),
                        'role_id' => $data['role_id'],
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
