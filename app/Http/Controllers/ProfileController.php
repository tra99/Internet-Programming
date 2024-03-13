<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
/**
 * @OA\Info(
 *     title="Full Mark pls",
 *     version="1.0.0",
 *     description="Description of your API",
 *     @OA\Contact(
 *         email="contact@example.com"
 *     ),
 *     @OA\License(
 *         name="MIT License",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 */
class ProfileController extends Controller
{
    /**
     * Get a list of users.
     *
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get a list of users",
     *     @OA\Response(response="200", description="Successful operation")
     * )
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    // public function store(Request $request): RedirectResponse
    // {
    //     // Use validation to ensure correct data
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|unique:users,email',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     // Create a new user instance
    //     $user = User::create($validatedData);

    //     // Optionally send a verification email if needed
    //     // ...

    //     // Login or redirect to appropriate location after creation
    //     Auth::login($user);

    //     return Redirect::to('/')->with('status', 'user-created');
    // }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
