<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

state('token')->locked();

state([
    'email' => fn() => request()->string('email')->value(),
    'password' => '',
    'password_confirmation' => '',
]);

rules([
    'token' => ['required'],
    'email' => ['required', 'string', 'email'],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
]);

$resetPassword = function () {
    $this->validate();

    $status = Password::reset($this->only('email', 'password', 'password_confirmation', 'token'), function ($user) {
        $user
            ->forceFill([
                'password' => Hash::make($this->password),
                'remember_token' => Str::random(60),
            ])
            ->save();

        event(new PasswordReset($user));
    });

    if ($status != Password::PASSWORD_RESET) {
        $this->addError('email', __($status));

        return;
    }

    Session::flash('status', __($status));

    $this->redirectRoute('login', navigate: true);
};

?>

<div>
    <div class="min-vh-100 d-flex">
        <!-- Left Side - Reset Password Form -->
        <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center px-4 py-5 bg-white">
            <div class="w-100" style="max-width: 450px;">

                <!-- Form Header -->
                <div class="mb-5">
                    <h2 class="display-5 fw-bold mb-3">Reset Password</h2>
                    <p class="text-muted">Enter your email and create a new secure password for your account.</p>
                </div>

                <!-- Reset Password Form -->
                <form wire:submit="resetPassword">
                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="form-label fw-medium text-dark">
                            Email
                        </label>
                        <input wire:model="email" id="email" type="email" name="email" required autofocus
                            autocomplete="username" class="form-control form-control-lg" placeholder="Enter your email"
                            style="border-radius: 0.5rem;" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-medium text-dark">
                            New Password
                        </label>
                        <div class="position-relative" x-data="{ show: false }">
                            <input wire:model="password" id="password" :type="show ? 'text' : 'password'"
                                name="password" required autocomplete="new-password"
                                class="form-control form-control-lg" placeholder="••••••••••"
                                style="border-radius: 0.5rem; padding-right: 3rem;" />
                            <button type="button"
                                class="btn position-absolute top-50 end-0 translate-middle-y text-muted border-0 bg-transparent"
                                @click="show = !show" style="z-index: 10; padding-right: 1rem;">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                    </path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-medium text-dark">
                            Confirm Password
                        </label>
                        <div class="position-relative" x-data="{ show: false }">
                            <input wire:model="password_confirmation" id="password_confirmation"
                                :type="show ? 'text' : 'password'" name="password_confirmation" required
                                autocomplete="new-password" class="form-control form-control-lg"
                                placeholder="••••••••••" style="border-radius: 0.5rem; padding-right: 3rem;" />
                            <button type="button"
                                class="btn position-absolute top-50 end-0 translate-middle-y text-muted border-0 bg-transparent"
                                @click="show = !show" style="z-index: 10; padding-right: 1rem;">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                    </path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn w-100 text-white"
                        style="border-radius: 0.5rem; padding: 0.875rem; background-color: #4e7661;">
                        Reset Password
                    </button>
                </form>

                <!-- Footer -->
                <div class="mt-5 text-center">
                    <small class="text-muted">Copyright 2025, Diginotive Rights Reserved.</small>
                </div>
            </div>
        </div>

        <!-- Right Side - Animated Security Design -->
        <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center position-relative overflow-hidden"
            style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #000000 100%);">

            <!-- Animated Background Particles -->
            <div class="position-absolute w-100 h-100" style="opacity: 0.1;">
                <div class="position-absolute rounded-circle bg-white"
                    style="width: 300px; height: 300px; top: -100px; right: -100px; animation: float 6s ease-in-out infinite;">
                </div>
                <div class="position-absolute rounded-circle bg-white"
                    style="width: 200px; height: 200px; bottom: -50px; left: -50px; animation: float 8s ease-in-out infinite;">
                </div>
                <div class="position-absolute rounded-circle bg-white"
                    style="width: 150px; height: 150px; top: 50%; left: 20%; animation: float 7s ease-in-out infinite;">
                </div>
            </div>

            <!-- Main Content -->
            <div class="position-relative text-center px-5">

                <!-- Security Icon with Shield -->
                <div class="mb-5">
                    <div class="d-inline-flex align-items-center justify-content-center position-relative"
                        style="width: 200px; height: 200px;">
                        <!-- Rotating Circle -->
                        <div class="position-absolute border border-white rounded-circle"
                            style="width: 200px; height: 200px; opacity: 0.2; animation: rotate 20s linear infinite;">
                        </div>
                        <div class="position-absolute border border-white rounded-circle"
                            style="width: 160px; height: 160px; opacity: 0.3; animation: rotate 15s linear infinite reverse;">
                        </div>

                        <!-- Shield Icon -->
                        <div class="position-relative d-flex align-items-center justify-content-center rounded-4 shadow-lg"
                            style="width: 120px; height: 120px; background: rgba(255, 255, 255, 0.95); z-index: 10;">
                            <svg width="70" height="70" fill="#667eea" viewBox="0 0 24 24">
                                <path
                                    d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Text Content -->
                <h2 class="display-5 fw-bold text-white mb-3">
                    Secure Password Reset
                </h2>
                <p class="text-white fs-5 mb-5" style="opacity: 0.9; max-width: 400px; margin: 0 auto;">
                    Your security is our priority. Create a strong password to keep your account safe.
                </p>

                <!-- Security Features -->
                <div class="row g-3 justify-content-center">
                    <div class="col-auto">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3 backdrop-blur">
                            <svg width="24" height="24" fill="white" viewBox="0 0 24 24" class="mb-2">
                                <path
                                    d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                            </svg>
                            <div class="text-white small fw-medium">Encrypted</div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3 backdrop-blur">
                            <svg width="24" height="24" fill="white" viewBox="0 0 24 24" class="mb-2">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                            </svg>
                            <div class="text-white small fw-medium">Verified</div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3 backdrop-blur">
                            <svg width="24" height="24" fill="white" viewBox="0 0 24 24" class="mb-2">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                            </svg>
                            <div class="text-white small fw-medium">Protected</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Wave Decoration -->
            <div class="position-absolute bottom-0 w-100" style="opacity: 0.1;">
                <svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                    <path fill="white"
                        d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                    </path>
                </svg>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .backdrop-blur {
            backdrop-filter: blur(10px);
        }
    </style>

</div>
