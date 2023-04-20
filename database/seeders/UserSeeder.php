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
			// Admin
			User::factory()->create([
				'first_name' => 'Admin',
				'last_name'  => null,
				'email'      => 'admin@example.test',
			])->assignRole(Role::findByName('admin'));
			// Magazzinieri
			User::factory(5)->create()->each(function ($u) {
				$u->assignRole(Role::findByName('warehouseman'));
			});
		}
	}
