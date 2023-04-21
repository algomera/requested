<?php

	namespace App\Http\Livewire\Pages\Users;

	use App\Models\User;
	use LivewireUI\Modal\ModalComponent;
	use Spatie\Permission\Models\Permission;
	use Spatie\Permission\Models\Role;

	class Create extends ModalComponent
	{
		public $first_name, $last_name, $phone, $email, $password, $password_confirmation, $new_role, $new_permissions = [];
		public $selectedTab = 'informations';
		protected $rules = [
			'first_name' => 'required',
			'last_name'  => 'required',
			'phone'      => 'required',
			'email'      => 'required',
			'password'   => 'required|confirmed',
			'new_role'   => 'required',
		];

		public function save() {
			$this->validate();
			$user = User::create([
				'first_name' => $this->first_name,
				'last_name'  => $this->last_name,
				'phone'      => $this->phone,
				'email'      => $this->email,
				'password'   => bcrypt($this->password)
			]);
			$user->assignRole($this->new_role);
			if ($this->new_role === 'warehouseman') {
				$user->syncPermissions($this->new_permissions);
			}
			$this->emitTo('pages.users.index', 'user-created');
			$this->closeModal();
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Utente Creato'),
				'subtitle' => __('L\'utente è stato creato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.users.create', [
				'roles'       => Role::all(),
				'permissions' => Permission::all(),
			]);
		}
	}
