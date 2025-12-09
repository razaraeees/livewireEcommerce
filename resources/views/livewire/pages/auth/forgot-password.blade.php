<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state(['email' => '']);

rules(['email' => ['required', 'string', 'email']]);

$sendPasswordResetLink = function () {
    $this->validate();

    $status = Password::sendResetLink(
        $this->only('email')
    );

    if ($status != Password::RESET_LINK_SENT) {
        $this->addError('email', __($status));

        return;
    }

    $this->reset('email');

    Session::flash('status', __($status));
};

?>

<div class="min-vh-100 d-flex">
    <!-- Left Side - Forgot Password Form -->
    <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center px-4 py-5 bg-white">
        <div class="w-100" style="max-width: 450px;">
            
            <!-- Form Header -->
            <div class="mb-5">
                <h2 class="display-5 fw-bold mb-3">Forgot Password?</h2>
                <p class="text-muted">No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Forgot Password Form -->
            <form wire:submit="sendPasswordResetLink">
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
                        autofocus
                        class="form-control form-control-lg"
                        placeholder="Enter your email"
                        style="border-radius: 0.5rem;"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="btn btn w-100 text-white mb-4"
                    style="border-radius: 0.5rem; padding: 0.875rem; background-color: #4e7661;"
                >
                    Email Password Reset Link
                </button>

                <!-- Back to Login -->
                <div class="text-center">
                    <a href="{{ route('login') }}" wire:navigate class="text-decoration-none text-dark fw-medium">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" class="me-1" style="margin-bottom: 2px;">
                            <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                        </svg>
                        Back to Login
                    </a>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-5 text-center">
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
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Text -->
        <div class="position-absolute text-center px-5" style="bottom: 5rem; left: 0; right: 0;">
            <h2 class="display-4 fw-bold text-white mb-3">
                Reset Your Password<br/>
                <span class="d-inline-block" style="background: linear-gradient(90deg, #22d3ee 0%, #3b82f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    Secure & Easy
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