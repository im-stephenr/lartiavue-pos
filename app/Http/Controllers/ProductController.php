<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //fetch products
        $products = Product::latest()->get();
        // fetch categories
        $categories = Category::latest()->get();
        
        return Inertia::render('Products', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
       
        $incomingFields = $request->validate([
            'title' => ['required','min:2','unique:products'],
            'price' => ['required'],
            'category' => ['required'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], 
        ]);
         // Checking/Saving uploaded photo to photos folder under app/public/photos
        if($request->hasFile('image'))
        {
            // WHEN UPLOADING A PHOTO TO THE SERVER RUN A command php artisan storage:link so it will be accessible to pages
           $incomingFields['image'] = Storage::disk('public')->put('photos', $request->image); // data that will be save in database will be automatically the pathname/filename
        }
        $incomingFields["status"] = "In stock";

        Product::create($incomingFields);
        return redirect()->intended('products');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, String $id)
    {
       $imageRules = [];
        // if there's an uploaded image, add image rules to image to prevent errors if there's no uploaded file
        if($request->hasFile('image')){
            $imageRules = ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];
        }
        $incomingFields = $request->validate([
            'title' => ['required','min:2',Rule::unique('products','title')->ignore($id, 'id')], // adding Rule class so it will still accept the same title on updating data
            'price' => ['required'],
            'category' => ['required'],
            'status' => ['required'],
            'image' => array_merge(['nullable'], $imageRules)
        ]);

         // Checking/Saving uploaded photo to photos folder under app/public/photos
        if($request->hasFile('image'))
        {
            $incomingFields['image'] = Storage::disk('public')->put('photos', $request->image); // data that will be save in database will be automatically the pathname/filename
        }else
        {
            // If image has been removed or null set the image to null in database
             if($request->image=="") $incomingFields['image'] = null;
            //  get the image from database and remove from folder
            $previous_image = Product::where('id', $id)->value('image');
            if($previous_image!="")
            {
                // $previous_image_exploded = explode("/", $previous_image);
                // remove the image from folder
                Storage::disk('public')->delete($previous_image);
            }
        }


        $product = Product::findOrFail($id);
        $product->update($incomingFields);
        return redirect()->intended('products');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(String $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        
        return redirect()->intended('products');

    }
}
