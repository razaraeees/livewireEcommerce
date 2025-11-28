<?php


namespace App\Livewire\Admin\Banner;

use App\Models\Banner;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class BannerEdit extends Component
{
    use WithFileUploads;

    public $banner_id;

    // Form Fields
    public $title;
    public $type;
    public $tagline;
    public $description;
    public $link;
    public $alt;
    public $status = 0;
    public $banner_video_status = 0;

    // Old Files (existing in database)
    public $old_image;
    public $old_mobile_image;
    public $old_banner_video;

    // New Files (uploaded by user)
    public $image;
    public $mobile_image;
    public $banner_video;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:Main Hero Banner,Middle Banner,Annoucement Banner,Offer Banner',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'link' => 'nullable|url|max:255',
            'alt' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner_video' => 'nullable|mimes:mp4,mov,avi|max:50000'
        ];
    }

    protected $messages = [
        'title.required' => 'Banner name is required.',
        'type.required' => 'Please select a banner type.',
        'type.in' => 'Invalid banner type selected.',
        'alt.required' => 'Banner alt text is required for SEO.',
        'link.url' => 'Please enter a valid URL.',
        'image.image' => 'Desktop banner must be an image file.',
        'image.max' => 'Desktop banner size should not exceed 2MB.',
        'mobile_image.image' => 'Mobile banner must be an image file.',
        'mobile_image.max' => 'Mobile banner size should not exceed 2MB.',
        'banner_video.mimes' => 'Video must be in mp4, mov, or avi format.',
        'banner_video.max' => 'Video size should not exceed 50MB.',
    ];

    public function mount($id = null)
    {
        if (!$id) {
            session()->flash('error', 'Banner ID is missing!');
            return redirect()->route('admin.banner');
        }

        try {
            $banner = Banner::findOrFail($id);

            $this->banner_id = $banner->id;

            // Load existing data
            $this->title = $banner->title;
            $this->type = $banner->type;
            $this->tagline = $banner->tagline;
            $this->description = $banner->description;
            $this->link = $banner->link;
            $this->alt = $banner->alt;
            
            // Convert to integer (0 or 1) for checkboxes
            $this->status = $banner->status == 1 ? 1 : 0;
            $this->banner_video_status = ($banner->banner_video_status ?? 0) == 1 ? 1 : 0;

            // Store old file paths
            $this->old_image = $banner->image;
            $this->old_mobile_image = $banner->mobile_image;
            $this->old_banner_video = $banner->banner_video;

        } catch (\Exception $e) {
            session()->flash('error', 'Banner not found!');
            return redirect()->route('admin.banner');
        }
    }

    // Delete Existing Desktop Image from Storage and Database
    public function deleteExistingImage()
    {
        try {
            if ($this->old_image && Storage::disk('public')->exists($this->old_image)) {
                // Delete from storage
                Storage::disk('public')->delete($this->old_image);
            }

            // Update database - set image to null
            Banner::where('id', $this->banner_id)->update(['image' => null]);

            // Clear the old_image variable
            $this->old_image = null;

            $this->dispatch('show-toast', type: 'success', message: 'Desktop image deleted successfully!');
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error deleting image: ' . $e->getMessage());
        }
    }

    // Delete Existing Mobile Image from Storage and Database
    public function deleteExistingMobileImage()
    {
        try {
            if ($this->old_mobile_image && Storage::disk('public')->exists($this->old_mobile_image)) {
                // Delete from storage
                Storage::disk('public')->delete($this->old_mobile_image);
            }

            // Update database - set mobile_image to null
            Banner::where('id', $this->banner_id)->update(['mobile_image' => null]);

            // Clear the old_mobile_image variable
            $this->old_mobile_image = null;

            $this->dispatch('show-toast', type: 'success', message: 'Mobile image deleted successfully!');
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error deleting mobile image: ' . $e->getMessage());
        }
    }

    // Delete Existing Video from Storage and Database
    public function deleteExistingVideo()
    {
        try {
            if ($this->old_banner_video && Storage::disk('public')->exists($this->old_banner_video)) {
                // Delete from storage
                Storage::disk('public')->delete($this->old_banner_video);
            }

            // Update database - set banner_video to null
            Banner::where('id', $this->banner_id)->update(['banner_video' => null]);

            // Clear the old_banner_video variable
            $this->old_banner_video = null;

            $this->dispatch('show-toast', type: 'success', message: 'Video deleted successfully!');
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error deleting video: ' . $e->getMessage());
        }
    }

    // Remove new uploaded image (before save)
    public function removeImage()
    {
        $this->image = null;
    }

    // Remove new uploaded mobile image (before save)
    public function removeMobileImage()
    {
        $this->mobile_image = null;
    }

    // Remove new uploaded video (before save)
    public function removeVideo()
    {
        $this->banner_video = null;
    }

    public function update()
    {
        // Validate form
        try {
            $this->validate();
            
        } catch (\Exception $e) {
            
            throw $e;
        }

        try {


            // Handle Desktop Image
            if ($this->image) {
                
                // Delete old image if exists
                if ($this->old_image && Storage::disk('public')->exists($this->old_image)) {
                    Storage::disk('public')->delete($this->old_image);
                   
                }
                $imagePath = $this->image->store('banners', 'public');
                
            } else {
                $imagePath = $this->old_image;
                
            }

            // Handle Mobile Image
            if ($this->mobile_image) {
               
                // Delete old mobile image if exists
                if ($this->old_mobile_image && Storage::disk('public')->exists($this->old_mobile_image)) {
                    Storage::disk('public')->delete($this->old_mobile_image);
                   
                }
                $mobilePath = $this->mobile_image->store('banners', 'public');
                
            } else {
                $mobilePath = $this->old_mobile_image;
                
            }

            // Handle Video
            if ($this->banner_video) {
               
                // Delete old video if exists
                if ($this->old_banner_video && Storage::disk('public')->exists($this->old_banner_video)) {
                    Storage::disk('public')->delete($this->old_banner_video);
                   
                }
                $videoPath = $this->banner_video->store('banners/videos', 'public');
                
            } else {
                $videoPath = $this->old_banner_video;
            }


            // Update Banner in database
            $updated = Banner::where('id', $this->banner_id)->update([
                'title' => $this->title,
                'type' => $this->type,
                'tagline' => $this->tagline,
                'description' => $this->description,
                'link' => $this->link,
                'alt' => $this->alt,
                'status' => $this->status ? 1 : 0,
                'banner_video_status' => $this->banner_video_status ? 1 : 0,
                'image' => $imagePath,
                'mob_banner_image' => $mobilePath,
                'banner_video' => $videoPath,
            ]);

            // Show success message
            // session()->flash('success', 'Banner Updated Successfully!');
            $this->dispatch('show-toast', type: 'success', message: 'Banner Updated Successfully!');


            // Redirect to banner list
            return redirect()->route('admin.banner');

        } catch (\Exception $e) {
           
            
            $this->dispatch('show-toast', type: 'error', message: 'Error updating banner: ' . $e->getMessage());
            
            // Don't redirect on error, stay on page
            return;
        }
    }

    public function render()
    {
        return view('livewire.admin.banner.banner-edit');
    }
}