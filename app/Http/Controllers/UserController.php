<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:6'], // password is encrypted automatically // 'confirmed' will automatically check for password_confirmation and check if they match
        ]);

        $user = User::create($incomingFields);

        Auth::login($user);

        return redirect()->route('dashboard');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);


        // If success regenerate session and redirect to dashboard
        // $request->remember is from login Remember me and will be save under users table remember_token
        if(Auth::attempt($incomingFields, $request->remember)){
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        // if fail send errors
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');


    }

    public function profile()
    {
        // Fetch trasaction history
        // Sales History
        $salesHistory = Sale::latest()->get();
        return Inertia::render('ManageProfile', [
            'salesHistory' => $salesHistory
        ]);
    }

    public function update(Request $request, String $id)
    {
        $imageRules = [];
        // if there's an uploaded image, add image rules to image to prevent errors if there's no uploaded file
        if($request->hasFile('image')){
            $imageRules = ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];
        }
        $incomingFields = $request->validate([
            'name' => ['required', 'min:5'],
            'email' => ['required', 'email', Rule::unique('users','email')->ignore($id, 'id')],
            'image' => array_merge(['nullable'], $imageRules),
        ]);

        // if there's a file, store it to photos/users
        if($request->hasFile('image')){
            $incomingFields["image"] = Storage::disk('public')->put('photos/users', $request->image);
            // Remove the previous image
            $previous_image = User::where('id', $id)->value('image');
            if($previous_image!="" || $previous_image!=null)
            {
                // remove the image from folder
                Storage::disk('public')->delete($previous_image);
            }
        }

        $user = User::findOrFail($id);
        $user->update($incomingFields);

        return redirect()->intended('users');
    }

    public function updatePassword(Request $request, String $id){
        $incomingFields = $request->validate([
            'password' => ['required', 'min:6', 'confirmed'], // password is encrypted automatically // 'confirmed' will automatically check for password_confirmation and check if they match
        ]);

       $user = User::findOrFail($id);
       $user->update($incomingFields);

       return redirect()->intended('users');
    }

     public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }


  
}
