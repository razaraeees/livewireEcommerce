<?php

namespace App\Livewire\Admin\Brand;

use App\Models\Brand;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class BrandCreate extends Component
{

    use WithFileUploads;

    public $name;
    public $slug;
    public $description;
    public $status = true;
    public $image;
    
    public function removeImage()
    {
        $this->image = null;
    }

    public function generateSlug()
    {
        if (!empty($this->name)) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // dd(request)
        try {
            
            $imagePath = $this->image ? $this->image->store('brands', 'public') : null;

            Brand::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'image' => $imagePath,
                'status' => $this->status,
            ]);

            $this->reset();

            // Dispatch toast event
            $this->dispatch('show-toast', type: 'success', message: 'Brands Created Successfully!');


        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.brand.brand-create');
    }
}
