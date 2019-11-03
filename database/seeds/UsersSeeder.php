<?php

use App\Constants\ConstUser;
use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $email = $this->command->ask('Admin email', 'admin@kuvia.io');
        $password = $this->command->secret('Admin user password');
        if (strlen($password) < 12) {
            $this->command->error('min admin pw length 12 chars, yours was ' . strlen($password));
            exit(1);
        }
        User::unguard();
        $user = User::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Kuvia Admin',
                'email' => $email,
                'password' => bcrypt($password),
            ]
        );
        $user->assignRole(ConstUser::ROLE_ADMIN);
    }
}
