<?php

	namespace Database\Seeders;

	use App\Models\User;
	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;
	use Spatie\Permission\Models\Role;

	class UserSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 */
		public function run(): void {

			$admin = User::factory()->create([
				'name'  => 'Admin',
				'email' => 'admin@example.test',
			]);
			$admin->assignRole(Role::findByName('admin'));
			$warehouseman = User::factory()->create([
				'name'  => fake()->name,
				'email' => fake()->safeEmail
			]);
			$warehouseman->assignRole(Role::findByName('warehouseman'));
		}
	}
