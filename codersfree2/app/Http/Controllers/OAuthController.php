<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    public function redirect(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id' => config('services.codersfree.client_id'),
            'redirect_uri' => route('callback'),
            'response_type' => 'code',
            'scope' => 'read-post create-post update-post delete-post',
            'state' => $state,
        ]);

        return redirect('http://api.codersfree.test/oauth/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        $state = $request->session()->pull('state');

        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class
        );

        return $request->all();

        $response = Http::asForm()->post('http://api.codersfree.test/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.codersfree.client_id'),
            'client_secret' => config('services.codersfree.client_secret'),
            'redirect_uri' => route('callback'),
            'code' => $request->code,
        ]);

        return $response->json();
    }
}
