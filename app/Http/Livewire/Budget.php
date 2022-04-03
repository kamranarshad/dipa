<?php

namespace App\Http\Livewire;

use App\Http\Controllers\BudgetController;
use App\Models\Account;
use App\Models\Classification;
use Auth;
use Livewire\Component;
use Log;

class Budget extends Component
{
    public array $budget = [];
    public string $account = "";

    public array|\Illuminate\Database\Eloquent\Collection $accounts;
    public array|\Illuminate\Database\Eloquent\Collection $classifications;

    public $listeners = [
        'childModalEvent'
    ];

    public function mount()
    {
        $user = Auth::user();

        $this->classifications = Classification::all();
        $this->accounts = Account::whereUserId($user->id)->get();
    }

    public function childModalEvent(string $classification, string $amount)
    {
        $this->budget[] = [
            'classification' => $this->classifications->find($classification),
            'amount' => $amount
        ];
    }

    public function submit()
    {
        foreach ($this->budget as $budget)
        {
            \App\Models\Budget::create([
                'user_id'           => Auth::user()->id,
                'account_id'        => $this->account,
                'classification_id' => $budget['classification']['id'],
                'amount'            => $budget['amount'],
            ]);
        }

        return \Redirect::action([BudgetController::class, 'index']);
    }

    public function render()
    {
        return view('livewire.budget');
    }
}
