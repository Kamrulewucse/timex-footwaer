<?php

namespace App\Http\Controllers;

use App\Model\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $product_categories = ProductCategory::all();

        return view('purchase.product_category.all', compact('product_categories'));
    }

    public function add()
    {
        return view('purchase.product_category.add');
    }

    public function addPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories',
            'status' => 'required'
        ]);

        $product_category = new ProductCategory();
        $product_category->name = $request->name;
        $product_category->type = 2;
        $product_category->description = $request->description;
        $product_category->status = $request->status;
        $product_category->save();

        return redirect()->route('product_category')->with('message', 'Product category add successfully.');
    }

    public function edit(ProductCategory $product_category)
    {
        return view('purchase.product_category.edit', compact('product_category'));
    }

    public function editPost(ProductCategory $product_category, Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:255',Rule::unique('product_categories')->ignore($product_category)],
            'status' => 'required'
        ]);

        $product_category->name = $request->name;
        $product_category->description = $request->description;
        $product_category->status = $request->status;
        $product_category->save();

        return redirect()->route('product_category')->with('message', 'Product category edit successfully.');
    }
}

