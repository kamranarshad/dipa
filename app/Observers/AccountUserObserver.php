<?php

namespace App\Observers;

use App\Models\UserAccessToken;

class AccountUserObserver
{
    /**
     * Handle the AccountUser "created" event.
     *
     * @param  \App\Models\UserAccessToken  $accountUser
     * @return void
     */
    public function created(UserAccessToken $accountUser)
    {
        //
    }

    /**
     * Handle the AccountUser "updated" event.
     *
     * @param  \App\Models\UserAccessToken  $accountUser
     * @return void
     */
    public function updated(UserAccessToken $accountUser)
    {
        //
    }

    /**
     * Handle the AccountUser "deleted" event.
     *
     * @param  \App\Models\UserAccessToken  $accountUser
     * @return void
     */
    public function deleted(UserAccessToken $accountUser)
    {
        //
    }

    /**
     * Handle the AccountUser "restored" event.
     *
     * @param  \App\Models\UserAccessToken  $accountUser
     * @return void
     */
    public function restored(UserAccessToken $accountUser)
    {
        //
    }

    /**
     * Handle the AccountUser "force deleted" event.
     *
     * @param  \App\Models\UserAccessToken  $accountUser
     * @return void
     */
    public function forceDeleted(UserAccessToken $accountUser)
    {
        //
    }
}
