<?php

namespace Database\Seeders;

use App\Models\CompanyModels\CompanyUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyUser::create([
            'first_name' => 'ahmad',
            'last_name' => 'zaza',
            'username' => 'zaza98',
            'phone_number' => '0991744294',
            'email' => 'ahmadzazaz98@gmail.com',
            'password' => Hash::make('12345678'),
            'user_type' => 1,
        ]);
    }
}