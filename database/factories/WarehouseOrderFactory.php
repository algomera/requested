<?php

	namespace Database\Factories;

	use App\Models\ProductionOrder;
	use App\Models\User;
	use Illuminate\Database\Eloquent\Factories\Factory;

	/**
	 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WarehouseOrder>
	 */
	class WarehouseOrderFactory extends Factory
	{
		/**
		 * Define the model's default state.
		 *
		 * @return array<string, mixed>
		 */
		public function definition(): array
		{
			$user = fake()->boolean === false ? null : User::all()->shuffle()->first()->id;
			return [
				'reason' => fake()->sentence,
				'user_id' => $user,
				'system' => $user === null ? 0 : 1
			];
		}
	}
