<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('products.categories', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'unique:categories,name']]);
        Category::create($data);

        return back()->with('success', 'Category added.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Category removed.');
    }
}
