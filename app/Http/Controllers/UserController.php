<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AdminMiddleware;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(AdminMiddleware::class);
    }

    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $users = User::where('id', '<>', '1')->orderBy('id')->get();
        }else {
            $users = User::orderBy('id')->get();
        }
        return view('user.index', ['users' => $users]);
    }

    // public function update(Request $request, User $user)

    // // Handles SuperAdmin being able to edit, update and delete other users.
    // {
    //     if (Auth::user()->isAdmin() && $user->isSuper()) {
    //         return redirect()->route('user.index');
    //     }

    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email,' . $user->id,
    //         'role' => 'required|in:user,admin',
    //     ]);

    //     $user->name = $request->name;
    //     $user->email = $request->email;
    //     $user->role = $request->role;

    //     $user->save();


    //     return redirect()->route('user.index')->with('success', 'User updated successfully.');
    // }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $authUser = Auth::user();

        if ($user->id == 1 && $authUser->id != 1) {
            return redirect()->back()->with('error', 'You cannot edit the Super Admin.');
        }

        if ($authUser->role == 'admin' && $user->role == 'admin' && $authUser->id != 1) {
            return redirect()->back()->with('error', 'Admins cannot edit other admins.');
        }

        $user->update($request->only(['name', 'email', 'role']));
        return redirect()->route('admin')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        try {
            if (Auth::user()->isAdmin() && $user->isSuper()) {
                return redirect()->route('admin');
            }
            $user->delete();
            return redirect()->route('admin')->with('success', 'User deleted successfully.');
        } catch (\Exception $error) {

        }

    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found.');
        }

        return view('user.edit', compact('user'));
    }

    public function verifyManually($id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->role == 'user') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('admin')->with('success', 'User has been verified.');
    }
}
