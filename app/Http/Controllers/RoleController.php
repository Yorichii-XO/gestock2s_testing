<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(Role::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $role = Role::create($data);
    
        return response()->json($role, 201);
    }
    
    public function show(Role $role)
    {
        return response()->json($role);
    }

    public function update(Request $request, $id)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $role = Role::findOrFail($id);
    $role->update($data);

    return response()->json($role);
}


    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json(null, 204);
    }
}
