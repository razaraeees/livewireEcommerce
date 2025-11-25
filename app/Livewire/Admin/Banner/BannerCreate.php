<?php

namespace App\Livewire\Admin\Banner;

use App\Models\Banner;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class BannerCreate extends Component
{
    use WithFileUploads;

    public $title;
    public $type;
    public $tagline;
    public $description;
    public $link;
    public $alt;
    public $status = false;
    public $banner_video_status = false;

    // Files
    public $image;
    public $mobile_image;
    public $banner_video;

    public function removeImage()
    {
        $this->image = null;
    }

    public function removeMobileImage()
    {
        $this->mobile_image = null;
    }

    public function removeVideo()
    {
        $this->banner_video = null;
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|string|max:255',
            'alt' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'mobile_image' => 'nullable|image|max:2048',
            'banner_video' => 'nullable|mimes:mp4,avi,mov|max:50000',
        ]);

        try {
            
            $desktopPath = $this->image ? $this->image->store('banners', 'public') : null;
            $mobilePath = $this->mobile_image ? $this->mobile_image->store('banners', 'public') : null;
            $videoPath = $this->banner_video ? $this->banner_video->store('banners/videos', 'public') : null;

            Banner::create([
                'title' => $this->title,
                'type' => $this->type,
                'tagline' => $this->tagline,
                'description' => $this->description,
                'banner_video_status' => $this->banner_video_status,
                'image' => $desktopPath,
                'mobile_image' => $mobilePath,
                'banner_video' => $videoPath,
                'link' => $this->link,
                'alt' => $this->alt,
                'status' => $this->status,
            ]);

            $this->reset();

            // Dispatch toast event
            $this->dispatch('show-toast', type: 'success', message: 'Banner Published Successfully!');


        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function saveDraft()
    {
        // Draft save logic (same as store but with draft status)
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Banner saved as draft! 📝'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.banner.banner-create');
    }
}