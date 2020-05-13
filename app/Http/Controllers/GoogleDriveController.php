<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleDriveController extends Controller
{
    //
    public function __construct(Google_Client $client)
    {
        $this->middleware(function ($request, $next) use ($client) {
            $client->refreshToken(Auth::user()->access_token);
            $this->drive = new Google_Service_Drive($client);
            return $next($request);
        });
    }
}
