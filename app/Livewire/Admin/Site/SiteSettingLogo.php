<?php

namespace App\Livewire\Admin\Site;

use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class SiteSettingLogo extends Component
{
    use WithFileUploads;

    public $favicon;
    public $footer_logo;
    public $website_logo;
    public $admin_logo;
    public $siteSetting;

    public function mount()
    {
        $this->siteSetting = SiteSetting::first();
    }

    public function store()
    {
        $this->validate([
            'favicon' => 'nullable|image|max:2048',
            'footer_logo' => 'nullable|image|max:2048',
            'website_logo' => 'nullable|image|max:2048',
            'admin_logo' => 'nullable|image|max:2048',
        ]);

        $data = [];

        // Handle Favicon
        if ($this->favicon) {
            // Delete old favicon if exists
            if ($this->siteSetting && $this->siteSetting->favicon) {
                Storage::disk('public')->delete($this->siteSetting->favicon);
            }
            $data['favicon'] = $this->favicon->store('logos', 'public');
        }

        // 
        if ($this->website_logo) {
            if ($this->siteSetting && $this->siteSetting->website_logo) {
                Storage::disk('public')->delete($this->siteSetting->website_logo);
            }
            $data['website_logo'] = $this->website_logo->store('logos', 'public');
        }

        // Handle Footer Logo
        if ($this->footer_logo) {
            if ($this->siteSetting && $this->siteSetting->footer_logo) {
                Storage::disk('public')->delete($this->siteSetting->footer_logo);
            }
            $data['footer_logo'] = $this->footer_logo->store('logos', 'public');
        }

        // Handle Admin Logo
        if ($this->admin_logo) {
            if ($this->siteSetting && $this->siteSetting->admin_logo) {
                Storage::disk('public')->delete($this->siteSetting->admin_logo);
            }
            $data['admin_logo'] = $this->admin_logo->store('logos', 'public');
        }

        // Create or Update
        if ($this->siteSetting) {
            $this->siteSetting->update($data);

            $this->dispatch('show-toast', type: 'success', message: 'Site Setting Delete Successfully!');

            // session()->flash('success', 'Brand settings updated successfully!');
        } else {
            SiteSetting::create($data);
            $this->dispatch('show-toast', type: 'success', message: 'Site Setting Create Successfully!');

            // session()->flash('success', 'Brand settings created successfully!');
        }

        // Reset file inputs
        $this->reset(['favicon', 'footer_logo', 'website_logo', 'admin_logo']);
        
        // Refresh data
        $this->siteSetting = SiteSetting::first();
    }

    // Remove methods for existing images
    public function removeFavicon()
    {
        if ($this->siteSetting && $this->siteSetting->favicon) {
            Storage::disk('public')->delete($this->siteSetting->favicon);
            $this->siteSetting->update(['favicon' => null]);
            $this->siteSetting = SiteSetting::first();

            $this->dispatch('show-toast', type: 'success', message: 'Favicon removed successfully!');
            // session()->flash('success', '');
        }
    }

    public function removeWebsiteLogo()
    {
        if ($this->siteSetting && $this->siteSetting->website_logo) {
            Storage::disk('public')->delete($this->siteSetting->website_logo);
            $this->siteSetting->update(['website_logo' => null]);
            $this->siteSetting = SiteSetting::first();
            $this->dispatch('show-toast', type: 'success', message: 'Website logo removed successfully!');
            // session()->flash('success', 'Website logo removed successfully!');
        }
    }

    public function removeFooterLogo()
    {
        if ($this->siteSetting && $this->siteSetting->footer_logo) {
            Storage::disk('public')->delete($this->siteSetting->footer_logo);
            $this->siteSetting->update(['footer_logo' => null]);
            $this->siteSetting = SiteSetting::first();
            $this->dispatch('show-toast', type: 'success', message: 'Footer logo removed successfully!');
            // session()->flash('success', 'Footer logo removed successfully!');
        }
    }

    public function removeAdminLogo()
    {
        if ($this->siteSetting && $this->siteSetting->admin_logo) {
            Storage::disk('public')->delete($this->siteSetting->admin_logo);
            $this->siteSetting->update(['admin_logo' => null]);
            $this->siteSetting = SiteSetting::first();
            $this->dispatch('show-toast', type: 'success', message: 'Admin logo removed successfully!');
            // session()->flash('success', 'Admin logo removed successfully!');
        }
    }

    public function render()
    {
        return view('livewire.admin.site.site-setting-logo');
    }
}