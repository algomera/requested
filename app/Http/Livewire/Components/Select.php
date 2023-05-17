<?php

	namespace App\Http\Livewire\Components;

	use Illuminate\Support\Arr;
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
		public $return, $disabled, $required, $name, $label, $hint, $append = 'heroicon-o-chevron-down', $prepend, $iconColor;

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
			if ($this->selected) {
				if (count($this->titleToShow) == 1) {
					$relationName = $this->titleToShow[0];
					$this->oldTitle = $this->model::where($this->return, $this->selected)->first()->{$relationName};
				} elseif (count($this->titleToShow) == 2) {
					$relationName = $this->titleToShow[0];
					$relationAttribute = $this->titleToShow[0];
					$this->oldTitle = $this->model::where($this->return, $this->selected)->first()->{$relationName}->{$relationAttribute};
				}
			}
		}

		public function selectItem($title, $item)
		{
			$this->selected = $item[$this->return];
			$this->title2 = $title;
			$this->oldTitle = $title;
			$this->emit('itemSelected', $item[$this->return]);
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
