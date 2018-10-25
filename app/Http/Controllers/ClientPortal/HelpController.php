<?php

namespace App\Http\Controllers\ClientPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends Controller
{

    function index(Request $request){
        $client = $request->clientPortal;
        return view('client-portal.help.index', ['user' => \Auth::user(),
                                                           'client'=>$client
            ]);
    }

    function documentation(Request $request){

        return view('client-portal.help.documentation', ['user' => \Auth::user()]);
    }

}