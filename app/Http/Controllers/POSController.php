<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class POSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        //fetch products
        if(isset($_GET['category']) && $_GET['category']!="") // fetch products based on category
            $products = Product::where('category', '=', $_GET["category"])->latest()->get();
        else if(isset($_GET["search"]) && $_GET["search"]!="") // fetch products based on searched key
            $products = Product::where('title', 'LIKE', '%'.$_GET["search"].'%')->latest()->get();
        else
            $products = Product::latest()->get();
        
        // fetch categories
        $categories = Category::latest()->get();

        return Inertia::render('POS', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
