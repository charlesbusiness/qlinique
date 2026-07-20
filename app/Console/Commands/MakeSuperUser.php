<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeSuperUser extends Command
{
    protected $signature = 'make:super-user';

    protected $description = 'Create the super admin user from config/super-user.php (.env)';

    public function handle(): int
    {
        $email = config('super-user.email');
        $password = config('super-user.password');

        if (empty($email) || empty($password)) {
            $this->error('SUPER_USER_EMAIL and SUPER_USER_PASSWORD must be set in .env');

            return Command::FAILURE;
        }

        $existing = User::where('email', $email)->first();

        if ($existing) {
            $this->warn("Super user already exists ({$email}). Skipping.");

            return Command::SUCCESS;
        }

        User::create([
            'name' => config('super-user.name', 'Super Admin'),
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'super_admin',
            'staff_id' => config('super-user.staff_id', 'STAFF-001'),
            'phone' => config('super-user.phone'),
            'email_verified_at' => now(),
        ]);

        $this->info("Super user created: {$email}");

        return Command::SUCCESS;
    }
}
