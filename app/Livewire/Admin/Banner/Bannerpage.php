<?php

namespace App\Livewire\Admin\Banner;

use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Bannerpage extends Component 
{
    use WithPagination; // Yeh line add karo

    public $search = '';
    public $type = '';

    public function delete($id)
    {
        $banner = Banner::find($id);

        if ($banner) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            if ($banner->mob_banner_image) {
                Storage::disk('public')->delete($banner->mob_banner_image);
            }
            if ($banner->banner_video) {
                Storage::disk('public')->delete($banner->banner_video);
            }

            $banner->delete();
        }

        $this->dispatch('show-toast', type: 'success', message: 'Banner Delete Successfully!');
    }

    public function render()
    {
        $banners = Banner::query();

        // Search by title
        if (!empty($this->search)) {
            $banners->where('title', 'like', "%{$this->search}%");
        }

        // Filter by type
        if (!empty($this->type)) {
            $banners->where('type', $this->type);
        }

        $banners = $banners->orderBy('id', 'desc')->paginate(10);

        // Dono methods work karenge:
        // Method 1:
        return view('livewire.admin.banner.bannerpage', [
            'banners' => $banners
        ]);
        
        // Ya Method 2:
        // return view('livewire.admin.banner.bannerpage')
        //     ->with('banners', $banners);
    }
}