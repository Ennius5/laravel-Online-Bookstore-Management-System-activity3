<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(){
        $featuredBooks = \App\Models\Book::with('category')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $categories = \App\Models\Category::withCount('books')->get(); // It's like using "std::cout << "Hello";" instead of "cout << "Hello";"
        return view('home', compact('featuredBooks', 'categories'));
    }
}
