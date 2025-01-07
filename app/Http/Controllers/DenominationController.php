<?php

namespace App\Http\Controllers;

use App\Models\Denomination;
use Illuminate\Http\Request;

class DenominationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|integer',
            'multiplier' => 'required|integer',
        ]);

        Denomination::create($request->all());

        return response()->json(['message' => 'Data stored successfully'], 200);
    }
}
