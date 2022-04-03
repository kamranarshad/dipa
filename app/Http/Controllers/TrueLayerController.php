<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrueLayerRequest;
use Illuminate\Http\Request;
use Redirect;
use Session;
use Validator;

class TrueLayerController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'scope' => 'required',
        ]);

        if ($validator->fails())
        {
            return Redirect::action([AccountController::class, 'index'])
                ->with('error', 'Could not auth with bank provider');
        }

        Session::put('truelayer.callback', $request->all());

        return Redirect::action([AccountController::class, 'create']);
    }
}
