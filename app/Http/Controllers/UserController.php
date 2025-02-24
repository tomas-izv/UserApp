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

    public function destroy($id)
{
    $authUser = Auth::user(); // Logged-in user
    $user = User::findOrFail($id); // User being deleted

    // Prevent deleting the super admin
    if ($user->id === 1) {
        return redirect()->route('admin')->with('error', 'Super Admin cannot be deleted.');
    }

    // If the logged-in user is an admin (not superadmin), prevent them from deleting other admins
    if ($authUser->isAdmin() && !$authUser->isSuper() && $user->isAdmin()) {
        return redirect()->route('admin')->with('error', 'Admins cannot delete other admins.');
    }

    // If everything is correct, delete the user
    $user->delete();

    return redirect()->route('admin')->with('success', 'User deleted successfully.');
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
