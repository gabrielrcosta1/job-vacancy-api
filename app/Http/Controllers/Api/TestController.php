<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function store(Request $request){
        
        $request->validate([
            'title' => 'required|string',
        ]);

        // Retorne algo para confirmar a execução, como uma resposta JSON
        return response()->json(['message' => 'Job created successfully!'], 201);

        
    }
}
