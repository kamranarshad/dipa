<?php

namespace App\Http\Livewire;

use App\Models\Classification;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class AddBudget extends ModalComponent
{
    public array|\Illuminate\Database\Eloquent\Collection $classifications;

    public string $classification = "";
    public string $amount = "";

    public function mount()
    {
        $this->classifications = Classification::all();
    }

    public function submit()
    {
        $this->closeModalWithEvents([
            Budget::getName() => ['childModalEvent', [$this->classification, $this->amount]]
        ]);
    }

    public function render()
    {
        return view('livewire.add-budget');
    }
}
