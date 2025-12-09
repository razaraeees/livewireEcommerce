<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    session()->flash('toast', [
        'type' => 'success',
        'message' => 'Login successful!',
    ]);
    return redirect()->route('admin.dashboard');
};

?>

<div class="min-vh-100 d-flex">
    <!-- Left Side - Login Form -->
    <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center px-4 py-5 bg-white">
        <div class="w-100" style="max-width: 450px;">

            <!-- Form Header -->
            <div class="mb-5">
                <h2 class="display-5 fw-bold  mb-3">Sign in</h2>
                <p class="text-muted">Sign in to your account to start using Diginotive</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Login Form -->
            <form wire:submit="login">
                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label fw-medium text-dark">
                        Email
                    </label>
                    <input wire:model="form.email" id="email" type="email" name="email" required autofocus
                        autocomplete="username" class="form-control form-control-lg" placeholder="Enter your email"
                        style="border-radius: 0.5rem;" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-medium text-dark">
                        Password
                    </label>
                    <div class="position-relative">
                        <input wire:model="form.password" id="password" type="password" name="password" required
                            autocomplete="current-password" class="form-control form-control-lg"
                            placeholder="••••••••••" style="border-radius: 0.5rem; padding-right: 3rem;" />
                    </div>
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input wire:model="form.remember" id="remember" type="checkbox" class="form-check-input"
                            name="remember">
                        <label class="form-check-label text-muted" for="remember">
                            Keep Me Signed In
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate
                            class="text-decoration-none text-dark fw-medium">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn w-100 text-white"
                    style="border-radius: 0.5rem; padding: 0.875rem; background-color: #4e7661;">
                    Sign In
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-4 text-center">
                <small class="text-muted">Copyright 2025, Diginotive Rights Reserved.</small>
            </div>
        </div>
    </div>

    <!-- Right Side - Geometric Design -->
    <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center position-relative overflow-hidden"
        style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #000000 100%);">

        <!-- Geometric Pattern Background -->
        <div class="position-absolute w-100 h-100" style="opacity: 0.2;">
            <svg class="w-100 h-100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="geometric" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                        <path d="M 50,0 L 100,50 L 50,100 L 0,50 Z" fill="none" stroke="white" stroke-width="0.5" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#geometric)" />
            </svg>
        </div>

        <!-- Animated Concentric Circles -->
        <div class="position-relative">
            <div class="position-relative d-flex align-items-center justify-content-center"
                style="width: 400px; height: 400px;">
                <!-- Circles -->
                <div class="position-absolute rounded-circle border border-white" style="inset: 0; opacity: 0.1;"></div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 20px; opacity: 0.1;">
                </div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 40px; opacity: 0.1;">
                </div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 60px; opacity: 0.1;">
                </div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 80px; opacity: 0.1;">
                </div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 100px; opacity: 0.1;">
                </div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 120px; opacity: 0.1;">
                </div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 140px; opacity: 0.1;">
                </div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 160px; opacity: 0.1;">
                </div>
                <div class="position-absolute rounded-circle border border-white" style="inset: 180px; opacity: 0.1;">
                </div>

                <!-- Center Logo -->
                <div class="position-relative d-flex align-items-center justify-content-center shadow-lg"
                    style="width: 100px; height: 100px; background: linear-gradient(135deg, #22d3ee 0%, #3b82f6 100%); border-radius: 1rem; transform: rotate(45deg); z-index: 10;">
                    <div style="transform: rotate(-45deg);">
                        <svg width="56" height="56" fill="white" viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Text -->
        <div class="position-absolute text-center px-5" style="bottom: 5rem; left: 0; right: 0;">
            <h2 class="display-4 fw-bold text-white mb-3">
                Unlock your<br />
                <span class="d-inline-block"
                    style="background: linear-gradient(90deg, #22d3ee 0%, #3b82f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    Diginotive Admin Dashboard
                </span>
            </h2>
        </div>

        <!-- Decorative Corner Elements -->
        <div class="position-absolute rounded-circle"
            style="top: 0; right: 0; width: 256px; height: 256px; background: linear-gradient(135deg, rgba(34, 211, 238, 0.1) 0%, transparent 100%); border-radius: 0 0 0 100%;">
        </div>
        <div class="position-absolute rounded-circle"
            style="bottom: 0; left: 0; width: 256px; height: 256px; background: linear-gradient(45deg, rgba(59, 130, 246, 0.1) 0%, transparent 100%); border-radius: 0 100% 0 0;">
        </div>
    </div>
</div>
