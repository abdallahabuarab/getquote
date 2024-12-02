<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserPanelController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search', '');

        $users = User::whereDoesntHave('provider')
            ->search($searchTerm)
            ->paginate(10, ['*'], 'users_page');

        $providers = User::whereHas('provider')
            ->search($searchTerm)
            ->paginate(10, ['*'], 'providers_page');

        return view('portal.users.index', compact('users', 'providers'));
    }

    public function create()
    {
        return view('portal.users.create');
    }

    public function store(Request $request)
    {
        $request->validate ([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);


        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('userspa.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('portal.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate ([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);



        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('userspa.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('userspa.index')->with('success', 'User deleted successfully.');
    }
}
