<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\layout;

layout('layouts.guest');

$sendVerification = function () {
    if (Auth::user()->hasVerifiedEmail()) {


        $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);

        return;
    }

    Auth::user()->sendEmailVerificationNotification();

    Session::flash('status', 'verification-link-sent');
};

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/admin/login', navigate: true);
};

?>

<div>
    <div class="min-vh-100 d-flex">
        <!-- Left Side - Verification Message -->
        <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center px-4 py-5 bg-white">
            <div class="w-100" style="max-width: 450px;">

                <!-- Email Icon -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                        style="width: 80px; height: 80px; background: linear-gradient(135deg, #22d3ee 0%, #3b82f6 100%);">
                        <svg width="40" height="40" fill="white" viewBox="0 0 24 24">
                            <path
                                d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                        </svg>
                    </div>
                </div>

                <!-- Header -->
                <div class="mb-4">
                    <h2 class="display-5 fw-bold text-center mb-3">Verify Your Email</h2>
                    <p class="text-muted text-center mb-0" style="line-height: 1.7;">
                        Thanks for signing up! Before getting started, could you verify your email address by clicking
                        on the link we just emailed to you? If you didn't receive the email, we will gladly send you
                        another.
                    </p>
                </div>

                <!-- Success Message -->
                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success d-flex align-items-center mb-4" role="alert"
                        style="border-radius: 0.75rem; border: none; background: #d1fae5; color: #065f46;">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" class="me-2">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                        </svg>
                        <div>
                            A new verification link has been sent to the email address you provided during registration.
                        </div>
                    </div>
                @endif

                <!-- Buttons -->
                <div class="d-flex flex-column gap-3 mb-4">
                    <!-- Resend Button -->
                    <button wire:click="sendVerification" type="button" class="btn btn-dark btn-lg w-100 fw-medium"
                        style="border-radius: 0.5rem; padding: 0.875rem;">
                        Resend Verification Email
                    </button>

                    <!-- Logout Button -->
                    <button wire:click="logout" type="button" class="btn btn-outline-secondary text-white w-100 "
                        style="border-radius: 0.5rem; padding: 0.875rem; background-color: #4e7661;">
                        Log Out
                    </button>
                </div>

                <!-- Help Text -->
                <div class="text-center">
                    <small class="text-muted">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24" class="me-1"
                            style="margin-bottom: 2px;">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                        </svg>
                        Check your spam folder if you don't see the email
                    </small>
                </div>

                <!-- Footer -->
                <div class="mt-5 text-center">
                    <small class="text-muted">Copyright 2025, Diginotive Rights Reserved.</small>
                </div>
            </div>
        </div>

        <!-- Right Side - Email Illustration Design -->
        <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center position-relative overflow-hidden"
            style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #000000 100%);">

            <!-- Animated Background Dots -->
            <div class="position-absolute w-100 h-100" style="opacity: 0.1;">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="dots" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                            <circle cx="20" cy="20" r="2" fill="white" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#dots)" />
                </svg>
            </div>

            <!-- Main Content -->
            <div class="position-relative text-center px-5">

                <!-- Email Envelope Illustration -->
                <div class="mb-5 position-relative">
                    <div class="d-inline-block position-relative">
                        <!-- Envelope -->
                        <div class="position-relative d-flex align-items-center justify-content-center rounded-4 shadow-lg"
                            style="width: 200px; height: 200px; background: rgba(255, 255, 255, 0.95); z-index: 10;">
                            <svg width="100" height="100" fill="#0ea5e9" viewBox="0 0 24 24">
                                <path
                                    d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                            </svg>
                        </div>

                        <!-- Floating Notification Badge -->
                        <div class="position-absolute bg-danger rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px; top: -10px; right: -10px; animation: pulse 2s infinite; z-index: 20;">
                            <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                                <path
                                    d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z" />
                            </svg>
                        </div>
                    </div>

                </div>

                <!-- Text Content -->
                <h2 class="display-5 fw-bold text-white mb-3">
                    Check Your Inbox
                </h2>
                <p class="text-white fs-5 mb-5" style="opacity: 0.9; max-width: 400px; margin: 0 auto;">
                    We've sent you a verification link. Click it to activate your account and get started!
                </p>

                <!-- Features -->
                <div class="row g-3 justify-content-center">
                    <div class="col-auto">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3 text-center" style="min-width: 100px;">
                            <div class="fs-1 mb-2">⚡</div>
                            <div class="text-white small fw-medium">Instant</div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3 text-center" style="min-width: 100px;">
                            <div class="fs-1 mb-2">🔒</div>
                            <div class="text-white small fw-medium">Secure</div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3 text-center" style="min-width: 100px;">
                            <div class="fs-1 mb-2">✓</div>
                            <div class="text-white small fw-medium">Easy</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative Wave -->
            <div class="position-absolute bottom-0 w-100" style="opacity: 0.1;">
                <svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                    <path fill="white"
                        d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,208C672,213,768,203,864,181.3C960,160,1056,128,1152,133.3C1248,139,1344,181,1392,202.7L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                    </path>
                </svg>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }
    </style>
</div>
