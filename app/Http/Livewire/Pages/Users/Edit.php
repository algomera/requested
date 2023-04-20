<?php

	namespace App\Http\Livewire\Pages\Users;

	use App\Models\User;
	use LivewireUI\Modal\ModalComponent;
	use Spatie\Permission\Models\Permission;
	use Spatie\Permission\Models\Role;

	class Edit extends ModalComponent
	{
		public $user;
		public $password;
		public $new_role;
		public $new_permissions = [];
		public $tabs = [
			'informations' => 'Informazioni',
			'roles'        => 'Ruoli/Permessi',
		];
		public $selectedTab = 'informations';

		protected function rules() {
			return [
				'user.first_name' => 'required',
				'user.last_name'  => 'required',
				'user.phone'      => 'required',
				'user.email'      => 'required',
				'password'        => $this->password ? 'required' : 'nullable'
			];
		}

		public function mount(User $user) {
			$this->user = $user;
			$this->new_role = $user->role->name;
			$this->new_permissions = $user->getAllPermissions()->pluck('id');
		}

		public function save() {
			$this->validate();
			$this->user->update([
				'password' => $this->password ? bcrypt($this->password) : $this->user->password
			]);
			if($this->new_role !== $this->user->role->name) {
				$role = Role::findByName($this->new_role);
				$this->user->syncRoles($role);
			}
			$this->user->syncPermissions($this->new_permissions);
			$this->emitTo('pages.users.index', 'user-updated');
			$this->closeModal();
			$this->dispatchBrowserEvent('open-notification', [
				'title'    => __('Utente Aggiornato'),
				'subtitle' => __('L\'utente è stato aggiornato con successo!'),
				'type'     => 'success'
			]);
		}

		public function render() {
			return view('livewire.pages.users.edit', [
				'roles' => Role::all(),
				'permissions' => Permission::all(),
			]);
		}
	}
