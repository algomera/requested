<?php

	namespace App\Http\Livewire\Components;

	use Illuminate\Support\Arr;
	use Livewire\Component;

	class Select extends Component
	{
		public $items;
		public $query = null;
		public $selected = null;
		public $event, $to;
		public $title;
		public $title2;
		public $oldTitle;
		public $subtitle;
		public $titleToShow;
		public $subtitleToShow;
		public $searchFields = [];
		public $return, $disabled, $required, $name, $label, $hint, $append = 'heroicon-o-chevron-down', $prepend, $iconColor;

		public function mount($title, $subtitle = null, $items = null)
		{
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
					$this->oldTitle = $this->items->find($this->selected)->{$this->title};
				} elseif (count($this->titleToShow) == 2) {
					$relationName = $this->titleToShow[0];
					$relationAttribute = $this->titleToShow[1];
					$this->oldTitle = $this->items->find($this->selected)->{$relationName}->{$relationAttribute};
				}
			}
		}

		public function selectItem($title, $item, $event, $to = null)
		{
			$this->selected = $item[$this->return];
			$this->title2 = $title;
			$this->oldTitle = $title;
			$this->emitUp($event, $item[$this->return], $to);
		}

		public function render()
		{
			if ($this->query) {
				$query = $this->query;
				$filtered = $this->items->filter(function ($item) use ($query) {
					if (count($this->titleToShow) == 1) {
						$titleMatch = str_contains(strtolower($item[$this->title]), strtolower($query));
						$subtitleMatch = str_contains(strtolower($item[$this->subtitle]), strtolower($query));
					} elseif (count($this->titleToShow) == 2) {
						$relationName = $this->titleToShow[0];
						$relationAttribute = $this->titleToShow[1];
						$titleMatch = str_contains(strtolower($item[$relationName][$relationAttribute]), strtolower($query));
						$subtitleMatch = str_contains(strtolower($item[$relationName][$relationAttribute]), strtolower($query));
					}

					// Restituisci true se c'è una corrispondenza in uno dei campi
					return $titleMatch || $subtitleMatch;
				});
			} else {
				$filtered = $this->items;
			}
			return view('livewire.components.select', [
				'filtered' => $filtered
			]);
		}
	}
