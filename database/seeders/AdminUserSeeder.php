<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Create basic permissions if they don't exist
        $permissions = [
            'manage users',
            'manage payouts',
            'manage support',
            'view analytics',
            'manage plans'
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Assign permissions to admin role
        $adminRole->syncPermissions($permissions);
        
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@eros-portal.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@eros-portal.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        
        // Assign admin role to user
        $admin->assignRole('admin');
        
        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@eros-portal.com');
        $this->command->info('Password: admin123');
    }
}