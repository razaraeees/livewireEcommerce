<?php

namespace App\Livewire\Admin\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class ProfileIndex extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public $name;

    #[Validate('required|email|max:255')]
    public $email;

    #[Validate('nullable|date')]
    public $dob;

    #[Validate('nullable|string|max:500')]
    public $address;

    #[Validate('nullable|image|max:2048|mimes:jpeg,png,jpg,gif')]
        public $image;

    public $current_image;

    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->dob = $user->dob ?? '';
        $this->address = $user->address ?? '';
        $this->current_image = $user->image ?? '';
    }

    public function saveProfile()
    {
        $this->validate();

        $user = Auth::user();

        $user->name = $this->name;
        $user->email = $this->email;
        $user->dob = $this->dob;
        $user->address = $this->address;

        if ($this->image) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            $imagePath = $this->image->store('profile-images', 'public');
            $user->image = $imagePath;
            $this->current_image = $imagePath;

            $this->image = null;
        }

        $user->save();

        $this->dispatch('profile-image-updated', image: $user->image);
        $this->dispatch('show-toast', type: 'success', message: 'Profile updated successfully!');
    }

    public function removeImage()
    {
        // Sirf nayi selected image ko clear karein, purani saved image as it is rahe
        $this->image = null;
    }

    public function changePassword()
    {
        $validated = $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        $this->dispatch('show-toast', type: 'success', message: 'Password Changed Successfully!');
        $this->dispatch('close-password-modal');
    }

    // public function deactivateAccount()
    // {
    //     $user = Auth::user();
    //     $user->user_status = 0;
    //     $user->save();

    //     Auth::logout();
    //     request()->session()->invalidate();
    //     request()->session()->regenerateToken();

    //     session()->flash('message', 'Account deactivated successfully.');

    //     return $this->redirect(route('login'), navigate: true);
    // }

    public function render()
    {
        return view('livewire.admin.profile.profile-index');
    }
}
