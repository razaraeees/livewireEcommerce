<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            session()->flash('toast', [
                'type' => 'success',
                'message' => 'Your email has been successfully verified!',
            ]);

            return redirect()->intended(route('admin.dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

        }
        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Your email has been successfully verified!',
        ]);

         return redirect()->route('admin.dashboard')
        ->with('toast', [
            'type' => 'success',
            'message' => 'Your email has been successfully verified!',
        ]);
    }
}
