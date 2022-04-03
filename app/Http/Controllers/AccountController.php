<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Jobs\AddUserAccount;
use App\Jobs\AddUserCard;
use App\Models\Account;
use App\Models\Card;
use App\Models\Classification;
use App\Models\Transaction;
use App\Models\UserAccessToken;
use App\Supports\Services\TrueLayerService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redirect;
use Response;
use Session;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Transaction::with('account')
            ->select([
                '*',
                'payment_at' => Transaction::select('payment_at')->orderBy('payment_at', 'desc')->limit(1)
            ])
            ->groupBy('account_id')
            ->get();

        return Response::view('dashboard.account.index', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function create(TrueLayerService $trueLayerService)
    {
        if (! Session::has('truelayer.callback')) {
            return Redirect::action([AccountController::class, 'index']);
        }

        if (! Session::has('truelayer.exchange') && Session::has('truelayer.callback')) {
            $response = Session::get('truelayer.callback');

            $exchangeResponse = $trueLayerService->getAccessToken($response['code']);

            Session::put('truelayer.exchange', $exchangeResponse);
            Session::put('truelayer.cards', $trueLayerService->getCards($exchangeResponse->accessToken));
            Session::put('truelayer.accounts', $trueLayerService->getAccounts($exchangeResponse->accessToken));
        }

        return Response::view('dashboard.account.create', [
            'cards'    => Session::get('truelayer.cards'),
            'accounts' => Session::get('truelayer.accounts'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAccountRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreAccountRequest $request)
    {
        $exchangeResponse = Session::get('truelayer.exchange');

        $accountUser = UserAccessToken::create([
            'user_id'       => Auth::user()->id,
            'token'         => $exchangeResponse->accessToken,
            'refresh_token' => $exchangeResponse->refreshToken,
            'expired_at'    => Carbon::now()->addSeconds($exchangeResponse->expiresIn)->format('Y-m-d H:i:s'),
        ]);

        AddUserCard::dispatch($accountUser, Session::get('truelayer.cards'), $request->get('cards'));
        AddUserAccount::dispatch($accountUser, Session::get('truelayer.accounts'), $request->get('accounts'));

        Session::pull('truelayer');

        return Redirect::action([AccountController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        $account->load('budgets');

        $topClassifications = Classification::balance()->limit(4)->get();
        $transactions = Transaction::with('classifications')->whereAccountId($account->id)->pending()->paginate();
        $pendingTransactions = Transaction::with('classifications')->whereAccountId($account->id)->notPending()->get();

        return Response::view('dashboard.account.show', [
            'budgets'             => $account->budgets,
            'account'             => $account,
            'transactions'        => $transactions,
            'pendingTransactions' => $pendingTransactions,
            'topClassifications'  => $topClassifications,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        //
    }
}
