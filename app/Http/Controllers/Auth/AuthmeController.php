<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthmeController extends Controller
{
    //
    public function index() {
        $user = Auth::guard()->user();
        if ($user) { 
            return response()->json([ 
                'user' => $user
            ]);
        }
    }
}
