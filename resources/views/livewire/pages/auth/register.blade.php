<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state([
    'name' => '',
    'email' => '',
    'password' => '',   
    'password_confirmation' => ''
]);

rules([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
]);
    
$register = function () {
    $validated = $this->validate();

    $validated['password'] = Hash::make($validated['password']);

    event(new Registered($user = User::create($validated)));

    Auth::login($user);

    // Check if user needs to verify email
    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }

    return redirect()->route('admin.dashboard');
};

?>

<div class="min-vh-100 d-flex">
    <!-- Left Side - Register Form -->
    <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center px-4 py-5 bg-white">
        <div class="w-100" style="max-width: 450px;">
            
            <!-- Form Header -->
            <div class="mb-5">
                <h2 class="display-5 fw-bold mb-3">Create Account</h2>
                <p class="text-muted">Sign up to get started with Diginotive</p>
            </div>

            <!-- Register Form -->
            <form wire:submit="register">
                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-medium text-dark">
                        Full Name
                    </label>
                    <input 
                        wire:model="name" 
                        id="name" 
                        type="text" 
                        name="name" 
                        required 
                        autofocus 
                        autocomplete="name"
                        class="form-control form-control-lg"
                        placeholder="Enter your full name"
                        style="border-radius: 0.5rem;"
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label fw-medium text-dark">
                        Email
                    </label>
                    <input 
                        wire:model="email" 
                        id="email" 
                        type="email" 
                        name="email" 
                        required 
                        autocomplete="username"
                        class="form-control form-control-lg"
                        placeholder="Enter your email"
                        style="border-radius: 0.5rem;"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-medium text-dark">
                        Password
                    </label>
                    <input 
                        wire:model="password" 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        class="form-control form-control-lg"
                        placeholder="••••••••••"
                        style="border-radius: 0.5rem;"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-medium text-dark">
                        Confirm Password
                    </label>
                    <input 
                        wire:model="password_confirmation" 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        class="form-control form-control-lg"
                        placeholder="••••••••••"
                        style="border-radius: 0.5rem;"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="btn btn-dark btn-lg w-100 fw-medium mb-3"
                    style="border-radius: 0.5rem; padding: 0.875rem;"
                >
                    Create Account
                </button>

                <!-- Login Link -->
                <div class="text-center">
                    <span class="text-muted">Already have an account?</span>
                    <a href="{{ route('login') }}" wire:navigate class="text-decoration-none text-dark fw-medium ms-1">
                        Sign In
                    </a>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-4 text-center">
                <small class="text-muted">Copyright 2025, Diginotive Rights Reserved.</small>
            </div>
        </div>
    </div>

    <!-- Right Side - Geometric Design -->
    <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center position-relative overflow-hidden" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #000000 100%);">
        
        <!-- Geometric Pattern Background -->
        <div class="position-absolute w-100 h-100" style="opacity: 0.2;">
            <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="geometric" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                        <path d="M 50,0 L 100,50 L 50,100 L 0,50 Z" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#geometric)" />
            </svg>
        </div>

        <!-- Animated Concentric Circles -->
        <div class="position-relative">
            <div class="position-relative d-flex align-items-center justify-content-center" style="width: 400px; height: 400px;">
                <!-- Circles -->
                <div class="position-absolute rounded-circle border border-white" style="inset: 0; opacity: 0.1;"></div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 20px; opacity: 0.1;"></div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 40px; opacity: 0.1;"></div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 60px; opacity: 0.1;"></div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 80px; opacity: 0.1;"></div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 100px; opacity: 0.1;"></div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 120px; opacity: 0.1;"></div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 140px; opacity: 0.1;"></div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 160px; opacity: 0.1;"></div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 180px; opacity: 0.1;"></div>
                
                <!-- Center Logo -->
                <div class="position-relative d-flex align-items-center justify-content-center shadow-lg" 
                     style="width: 100px; height: 100px; background: linear-gradient(135deg, #22d3ee 0%, #3b82f6 100%); border-radius: 1rem; transform: rotate(45deg); z-index: 10;">
                    <div style="transform: rotate(-45deg);">
                        <svg width="56" height="56" fill="white" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Text -->
        <div class="position-absolute text-center px-5" style="bottom: 5rem; left: 0; right: 0;">
            <h2 class="display-4 fw-bold text-white mb-3">
                Join Us Today<br/>
                <span class="d-inline-block" style="background: linear-gradient(90deg, #22d3ee 0%, #3b82f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    Start Your Journey
                </span>
            </h2>
        </div>

        <!-- Decorative Corner Elements -->
        <div class="position-absolute rounded-circle" 
             style="top: 0; right: 0; width: 256px; height: 256px; background: linear-gradient(135deg, rgba(34, 211, 238, 0.1) 0%, transparent 100%); border-radius: 0 0 0 100%;"></div>
        <div class="position-absolute rounded-circle" 
             style="bottom: 0; left: 0; width: 256px; height: 256px; background: linear-gradient(45deg, rgba(59, 130, 246, 0.1) 0%, transparent 100%); border-radius: 0 100% 0 0;"></div>
    </div>
</div>