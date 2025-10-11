<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [];

        // Generate 1000 unique permissions
        for ($i = 1; $i <= 1000; $i++) {
            $name = 'permission_' . $i;
            $permissions[] = [
                'name' => $name,
                'slug' => Str::slug($name),
                'guard_name' => 'web',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert all at once for efficiency
        DB::table('permissions')->insert($permissions);
    }
}
