<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'fazer_transferencia',
                'guard_name' => 'web'
            ],
            [
                'name' => 'receber_transferencia',
                'guard_name' => 'web'
            ]
        ];

        foreach ($permissions as $permission){
            Permission::insert($permission);
        }
    }
}
