<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    public function store()
    {
        $this->resolveAuthorization();

        $accessToken = auth()->user()->accessToken;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken->access_token,
            'Accept' => 'application/json',
        ])->post('http://api.codersfree.test/v1/posts', [
            'name' => 'Nombre test',
            'slug' => 'nombre-test',
            'extract' => 'estos es un extracto de prueba',
            'body' => 'esto es un body de prueba',
            'category_id' => 1,
            'user_id' => $accessToken->service_id,
        ]);

        $response = $response->json();

        return $response;

    }
}
