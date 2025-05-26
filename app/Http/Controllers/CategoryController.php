<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Show list of categories
        $categories = Category::latest()->get();
        // This will render Categories.vue and pass categories as props so you should add defineProps({categories:Array}) inside vue page file
        return Inertia::render('Categories', [
            'categories' => $categories
        ]);
    }

  
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $incomingFields = $request->validate([
            'title' => ['required', 'unique:categories']
        ]);

        $save = Category::create($incomingFields);
        return redirect()->intended('categories');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $incomingFields = $request->validate([
            'title' => ['required', 'unique:categories']
        ]);

        // Check if the ID Exist in database table
        $category = Category::findOrFail($id);
        // Update if exist
        $category->update($incomingFields);

        return redirect()->intended('categories');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        
        return redirect()->intended('categories');
    }
}
