<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $incomingFields = $request->validate([
            'cashReceive' => ['required'],
            'change' => ['required'],
            'total' => ['required'],
            'orderDetails' => ['required'],
            'cashier' => ['required'],
        ]);

         $incomingFields['orderDetails'] = json_encode($incomingFields['orderDetails']);

        Sale::create($incomingFields);
        return redirect()->intended('pos');
    }
}
