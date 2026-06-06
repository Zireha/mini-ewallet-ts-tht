<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function show(Request $request)
    {
        return WalletResource::make($request->user()->wallet);
    }
}
