<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(User::all());
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:Users,email',
            'password' => 'required|string|max:20',
            'role' => 'required|string|max:255',
        ]);

        $User = User::create($validated);
        return response()->json($User, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:Users,email,' . $id,
            'password' => 'sometimes|required|string|max:20',
            'role' => 'sometimes|required|string|max:255',
        ]);

        $User = User::findOrFail($id);
        $User->update($validated);
        return response()->json($User, 200);
    }

    public function destroy($id)
    {
        $User = User::findOrFail($id);
        $User->delete();
        return response()->json(null, 204);
    }
}