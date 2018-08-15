<?php

use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = app(UserService::class);

        if (!User::where('name', 'Santiago Blanco')->first()) {
            $user1 = User::create([
                'name' => 'Santiago Blanco',
                'lastname' => 'Blanco',
                'email' => 'santiago.blanco.vilchez@gmail.com',
                'password' => bcrypt('123456'),
            ]);


            $user2 = User::create([
                'name' => 'John',
                'lastname' => 'Doe',
                'email' => 'nimblexworm@gmail.com',
                'password' => bcrypt('123456'),
            ]);


            $service->create($user1,'password', 'Administrador', false);
            $service->create($user2,'password', 'Miembro', false);

        }

    }
}
