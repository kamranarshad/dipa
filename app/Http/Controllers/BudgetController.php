<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Account;
use App\Models\Budget;
use App\Models\Classification;
use Auth;
use Illuminate\Http\Request;
use Redirect;
use Response;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::with(['budgets', 'budgets.classification'])
            ->whereUserId(Auth::user()->id)
            ->get();

        return Response::view('dashboard.budget.index', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::whereUserId(Auth::user()->id)->get();
        $classifications = Classification::all();

        return Response::view('dashboard.budget.create', [
            'accounts'        => $accounts,
            'classifications' => $classifications,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function show(Budget $budget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function edit(Budget $budget)
    {
        $accounts = Account::whereUserId(Auth::user()->id)->get();
        $classifications = Classification::all();

        return Response::view('dashboard.budget.edit', [
            'budget'          => $budget,
            'accounts'        => $accounts,
            'classifications' => $classifications,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBudgetRequest  $request
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        $budget->update([
            'amount'            => $request->get('amount'),
            'classification_id' => $request->get('classification'),
        ]);

        return Redirect::action([BudgetController::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Budget $budget)
    {
        $budget->delete();

        return Redirect::action([BudgetController::class, 'index']);
    }
}
