<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Book;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        // //list down all that's in the Category Table with the amounts
        // $categories = Category::all();
        // $books = Book::all();
        // $categoryAmount =[];
        // $categoryAmounts=[] ;
        // //take the categories into a special array
        // foreach($categories as $category){
        //     $categoryAmount = [$category->name, 0];
        //     $categoryAmounts[] = $categoryAmount;
        // }
        // //run through the books
        // foreach($books as $book){
        //     //run through the category arrays and increment if book belongs to  them
        //     foreach($categoryAmounts as $ca){
        //         if ($book->category == $ca[0]){
        //             $ca[1]++;
        //             break;
        //         }
        //     }
        // } USE ELOQUENT!

        $categories= Category::withCount('books')->get();
        // $books = Book::all();//NOT GOOD
        return view ('categories.index', ['categories' => $categories, /*'books' => $books]*/]);



    }

    public function getSelectedBooksbyCategory($categoryId)
        {
        $category = Category::with('books')->findOrFail($categoryId);

        return response()->json([
            'books' => $category->books
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
     $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
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
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
     // Check if category has books
        if ($category->books()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with associated books'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
        //
    }


        public function books(Category $category)
    {
        $books = $category->books()->get();
        return response()->json(['books' => $books]);
    }
}
