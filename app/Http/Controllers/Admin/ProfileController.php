<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url|max:500',
            'github_url' => 'nullable|url|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo && ! str_starts_with($user->photo, 'http')) {
                Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('profile', 'public');
            $validated['photo'] = $path;
        } else {
            unset($validated['photo']);
        }

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = $request->password;
        }

        $user->update($validated);

        return redirect()->route('admin.profile.edit')->with('success', 'Perfil atualizado.');
    }
}
