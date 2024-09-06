<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        // Include the 'inventory' relationship
        $products = Product::with('inventory')->get();
        return response()->json($products);
    }
    public function show($id) {
        $product = Product::with('inventory')->find($id);
        if ($product) {
            // Assume inventory has a 'quantity' field
            $quantity = $product->inventory ? $product->inventory->quantity : 0; // Default to 0 if no inventory found
            $product->quantity = $quantity; // Adding quantity dynamically
            return response()->json($product, 200);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
    
    


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // Validate image
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
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            ]);
        
            // Handle the image upload if a new image is provided
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('images', 'public');
                $product->image = $imagePath;
            }
        
            // Make sure to only update the fields that are present in the request
            $product->update($request->only(['name', 'category_id', 'supplier_id', 'price','image']));
        
            return response()->json($product);
        
        
    
    
    }
    

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}