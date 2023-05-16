<?php

	namespace App\Http\Livewire\Components;

	use Livewire\Component;

	class Select extends Component
	{
		public $query = null;
		public $selected = null;
		public $model = null;
		public $title;
		public $title2;
		public $oldTitle;
		public $subtitle;
		public $titleToShow;
		public $subtitleToShow;
		public $searchFields = [];
		public $data, $disabled, $required, $name, $label, $hint, $append = 'heroicon-o-chevron-up-down', $prepend, $iconColor;

		public function mount($model, $title, $subtitle = null)
		{
			$this->model = $model;
			$this->title = $title;
			$this->subtitle = $subtitle;
			$this->titleToShow = explode('.', $title);
			$this->subtitleToShow = explode('.', $subtitle);
			if ($title) {
				$this->searchFields[] = $title;
			}
			if ($subtitle) {
				$this->searchFields[] = $subtitle;
			}
		}

		public function selectItem($title, $item)
		{
			$this->selected = $item[$this->data];
			$this->title2 = $title;
			$this->oldTitle = $title;
			$this->emit('item-selected', $item[$this->data]);
		}

		public function render()
		{
			$items = $this->model::query();
			if ($this->query) {
				$items->search($this->query, $this->searchFields);
			}
			return view('livewire.components.select', [
				'items' => $items->get()
			]);
		}
	}
