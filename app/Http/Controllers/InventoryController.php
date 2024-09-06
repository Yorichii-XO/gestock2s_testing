<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        // Fetch inventories with associated product data
        $inventories = Inventory::with('product')->get();
        return response()->json($inventories);
    }

    public function show($id)
    {
        return Inventory::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location' => 'required|string|max:25',
            'capacity' => 'required|integer',
            'current_stock' => 'required|integer',
            'quantity' => 'required|integer',
            'product_id' => 'required|exists:products,id',
        ]);

        $inventory = Inventory::create($validated);
        return response()->json($inventory, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'location' => 'sometimes|required|string|max:255',
            'capacity' => 'sometimes|required|integer',
            'current_stock' => 'sometimes|required|integer',
            'quantity' => 'sometimes|required|integer',
            'product_id' => 'sometimes|required|exists:products,id',
        ]);

        $inventory = Inventory::findOrFail($id);
        $inventory->update($validated);
        return response()->json($inventory, 200);
    }

    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();
        return response()->json(null, 204);
    }

    /**
     * Update inventory stock after placing an order
     */
    public function updateStockAfterOrder(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $inventory = Inventory::findOrFail($id);

        // Check if enough stock is available
        if ($inventory->current_stock < $validated['quantity']) {
            return response()->json(['error' => 'Insufficient stock available'], 400);
        }

        // Update inventory quantity
        $inventory->current_stock -= $validated['quantity'];
        $inventory->save();

        return response()->json($inventory, 200);
    }
}
