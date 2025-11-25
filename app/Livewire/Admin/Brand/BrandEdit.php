<?php

namespace App\Livewire\Admin\Brand;

use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class BrandEdit extends Component
{
    use WithFileUploads;

    public $brand;
    public $name;
    public $slug;
    public $description;
    public $status = true;
    public $image;
    public $oldImage;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:brands,slug',
        'description' => 'nullable|string',
        'status' => 'boolean',
        'image' => 'nullable|image|max:2048',
    ];

    public function mount($slug)
    {
        $this->brand = Brand::where('slug', $slug)->firstOrFail();
        
        $this->name = $this->brand->name;
        $this->slug = $this->brand->slug;
        $this->description = $this->brand->description;
        $this->status = $this->brand->status;
        $this->oldImage = $this->brand->image;
    }

    public function generateSlug()
    {
        if (!empty($this->name)) {
            $this->slug = strtolower($this->name);
            $this->slug = preg_replace('/\s+/', '-', $this->slug);
            $this->slug = preg_replace('/[^\w-]+/u', '', $this->slug);
        }
    }

    public function updatedName()
    {
        $this->generateSlug();
    }

    public function removeImage()
    {
        $this->image = null;
    }

    public function deleteExistingImage()
    {
        try {
            // Delete image from storage
            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }

            // Update brand record
            $this->brand->update([
                'image' => null
            ]);

            // Update component state
            $this->oldImage = null;

            $this->dispatch('show-toast', type: 'success', message: 'Image deleted successfully!');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error deleting image: ' . $e->getMessage());
        }
    }

    public function saveDraft()
    {
        $this->updateBrand(true);
    }

    public function update()
    {
        $this->updateBrand(false);
    }

    private function updateBrand($isDraft = false)
    {
        // Update validation rules to ignore current brand
        $this->rules['slug'] = 'required|string|max:255|unique:brands,slug,' . $this->brand->id;

        $this->validate();

        try {
            // Handle image upload
            $imagePath = $this->oldImage;
            if ($this->image) {
                // Delete old image if exists
                if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                    Storage::disk('public')->delete($this->oldImage);
                }
                // Store new image
                $imagePath = $this->image->store('brands', 'public');
            }

            // Update brand
            $this->brand->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'status' => $this->status,
                'image' => $imagePath,
            ]);

            // Update oldImage reference if new image was uploaded
            if ($this->image) {
                $this->oldImage = $imagePath;
                $this->image = null;
            }

            $message = $isDraft ? 'Brand saved as draft successfully!' : 'Brand updated successfully!';
            $this->dispatch('show-toast', type: 'success', message: $message);

            if (!$isDraft) {
                // Redirect to brand list or show success message
                return redirect()->route('admin.brand');
            }

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error updating brand: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.brand.brand-edit');
    }
}