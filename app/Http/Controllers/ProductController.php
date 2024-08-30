<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::all());
    }
    public function show($id)
    {
        $product = Product::with('inventory')->find($id);
    
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    
        return response()->json($product);
    }
    


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validate image
        ]);
    
        // Handle the image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $originalFilename = $image->getClientOriginalName();
            $imagePath = $image->storeAs('images', $originalFilename, 'public'); // Store with the original filename
        }
    
        // Store product information
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'price' => $request->price,
            'image' => $imagePath, // Store the image path
        ]);
    
        return response()->json($product, 201);
    }
    
    
   
public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'sometimes|required|string',
        'category_id' => 'sometimes|required|exists:categories,id',
        'supplier_id' => 'sometimes|required|exists:suppliers,id',
        'price' => 'sometimes|required|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validate image
    ]);

    // Handle the image upload if a new image is provided
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $image->store('products', 'public');
        $product->image = $imagePath;
    }

    $product->update($request->only(['name', 'category_id', 'supplier_id', 'quantity', 'price', 'image']));

    return response()->json($product);
}

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}