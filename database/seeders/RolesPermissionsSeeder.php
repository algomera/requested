<?php

	namespace Database\Seeders;

	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;
	use Spatie\Permission\Models\Permission;
	use Spatie\Permission\Models\Role;

	class RolesPermissionsSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 */
		public function run(): void {
			// Roles
			$adminRole = Role::create([
				'name'  => 'admin',
				'label' => 'Amministratore'
			]);
			$warehousemanRole = Role::create([
				'name'  => 'warehouseman',
				'label' => 'Magazziniere'
			]);
			// PERMISSIONS
			$permissions = [
				'Permesso 1',
				'Permesso 2'
			];
			foreach ($permissions as $permission) {
				Permission::create([
					'name' => $permission
				]);
			}

			// ASSIGN PERMISSIONS TO ROLE
//			$warehousemanRole->givePermissionTo([]);
		}
	}
