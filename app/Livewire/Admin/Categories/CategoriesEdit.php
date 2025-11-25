<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CategoriesEdit extends Component
{
    use WithFileUploads;

    // Category ID - ACTUAL DATABASE ID
    public $categoryId;

    public $category;

    // Category fields
    public $parent_id;

    public $level = '';

    public $category_name;

    public $category_image;

    public $category_discount = 0;

    public $description;

    public $url;

    public $meta_title;

    public $meta_description;

    public $meta_keywords;

    public $status = true;

    public $category_banner;

    public $banner_status = false;

    // Existing images
    public $existing_image;

    public $existing_banner;

    // Dynamic categories
    public $mainCategories = [];

    public $subCategories = [];

    protected function rules()
    {
        return [
            'category_name' => 'required|string|max:255',
            // FIXED: Now using actual ID from database
            'url' => 'required|string|max:255|unique:categories,url,'.$this->category->id,
            'parent_id' => $this->level > 0 ? 'required|exists:categories,id' : 'nullable',
            'level' => 'required|integer|min:0|max:2',
            'category_image' => 'nullable|image|max:2048',
            'category_banner' => 'nullable|image|max:2048',
            'category_discount' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'status' => 'boolean',
            'banner_status' => 'boolean',
        ];
    }

    protected $messages = [
        'category_name.required' => 'Category name is required',
        'url.required' => 'URL/Slug is required',
        'url.unique' => 'This URL is already taken',
        'level.required' => 'Please select a category level',
        'parent_id.required' => 'Parent category is required for sub categories',
        'category_image.image' => 'File must be an image',
        'category_image.max' => 'Image size must not exceed 2MB',
        'category_banner.image' => 'File must be an image',
        'category_banner.max' => 'Banner size must not exceed 2MB',
    ];

    // FIXED: Mount method
    // public function mount($url)
    // {
    //     // Load category by URL
    //     $this->category = Category::where('url', $url)->firstOrFail();

    //     // Set the ACTUAL ID from database
    //     $this->categoryId = $this->category->id;

    //     // Fill all fields with existing data
    //     $this->parent_id = $this->category->parent_id;
    //     $this->level = $this->category->level;
    //     $this->category_name = $this->category->category_name;
    //     $this->category_discount = $this->category->category_discount ?? 0;
    //     $this->description = $this->category->description;
    //     $this->url = $this->category->url;
    //     $this->meta_title = $this->category->meta_title;
    //     $this->meta_description = $this->category->meta_description;
    //     $this->meta_keywords = $this->category->meta_keywords;
    //     $this->status = (bool) $this->category->status;
    //     $this->banner_status = (bool) $this->category->banner_status;

    //     // Existing images
    //     $this->existing_image = $this->category->category_image;
    //     $this->existing_banner = $this->category->category_banner;

    //     // Load categories for dropdown
    //     $this->loadCategories();

    //     dd($this->category);
    // }

    public function mount($url)
    {
        // URL ko ID nahi, slug samjho
        $this->category = Category::where('url', $url)->firstOrFail();

        // ID save karlo update ke liye
        $this->categoryId = $this->category->id;

        $this->parent_id = $this->category->parent_id;
        $this->level = $this->category->level;
        $this->category_name = $this->category->category_name;
        $this->category_discount = $this->category->category_discount;
        $this->description = $this->category->description;
        $this->url = $this->category->url;
        $this->meta_title = $this->category->meta_title;
        $this->meta_description = $this->category->meta_description;
        $this->meta_keywords = $this->category->meta_keywords;
        $this->status = $this->category->status;
        $this->banner_status = $this->category->banner_status;

        $this->existing_image = $this->category->category_image;
        $this->existing_banner = $this->category->category_banner;

        $this->loadCategories();
    }

    public function updatedLevel($value)
    {
        // Only reset parent_id if level actually changed
        if ($value != $this->category->level) {
            $this->parent_id = null;
        }

        $this->loadCategories();
    }

    private function loadCategories()
    {
        if ($this->level == 1) {
            // Sub Category: Show Main Categories
            $this->mainCategories = Category::where('level', 0)
                ->where('status', true)
                ->where('id', '!=', $this->categoryId) // Exclude self
                ->orderBy('category_name')
                ->get();

        } elseif ($this->level == 2) {
            // Sub-Sub Category: Show Sub Categories
            $this->subCategories = Category::where('level', 1)
                ->where('status', true)
                ->where('id', '!=', $this->categoryId) // Exclude self
                ->with('parent')
                ->orderBy('category_name')
                ->get();
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function update()
    {
        // Parent validation
        if ($this->level > 0 && ! $this->parent_id) {
            $this->addError('parent_id', 'Parent category is required for this level');

            return;
        }

        $this->validate();

        try {
            $data = [
                'parent_id' => $this->level == 0 ? null : $this->parent_id,
                'level' => $this->level,
                'category_name' => $this->category_name,
                'category_discount' => $this->category_discount ?? 0,
                'description' => $this->description,
                'url' => $this->url,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'status' => $this->status,
                'banner_status' => $this->banner_status,
            ];
            // dd();

            // Image update
            if ($this->category_image) {
                if ($this->existing_image && Storage::disk('public')->exists($this->existing_image)) {
                    Storage::disk('public')->delete($this->existing_image);
                }

                $imageName = time().'_'.Str::slug($this->category_name).'.'.$this->category_image->extension();
                $imagePath = $this->category_image->storeAs('categories/images', $imageName, 'public');
                $data['category_image'] = $imagePath;
            }

            // Banner update
            if ($this->category_banner) {
                if ($this->existing_banner && Storage::disk('public')->exists($this->existing_banner)) {
                    Storage::disk('public')->delete($this->existing_banner);
                }

                $bannerName = time().'_banner_'.Str::slug($this->category_name).'.'.$this->category_banner->extension();
                $bannerPath = $this->category_banner->storeAs('categories/banners', $bannerName, 'public');
                $data['category_banner'] = $bannerPath;
            }

            // Update in database
            $this->category->update($data);

            $this->dispatch('show-toast', type: 'success', message: 'Category Updated Successfully!');

            return redirect()->route('admin.categories');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: '.$e->getMessage());
        }
    }

    public function saveDraft()
    {
        $this->validate([
            'category_name' => 'required|string|max:255',
            'url' => 'required|string|max:255|unique:categories,url,'.$this->category->id,
            'level' => 'required|integer|min:0|max:2',
        ]);

        try {
            $data = [
                'parent_id' => $this->level == 0 ? null : $this->parent_id,
                'level' => $this->level,
                'category_name' => $this->category_name,
                'category_discount' => $this->category_discount ?? 0,
                'description' => $this->description,
                'url' => $this->url,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'status' => false, // Draft
                'banner_status' => $this->banner_status,
            ];

            if ($this->category_image) {
                if ($this->existing_image && Storage::disk('public')->exists($this->existing_image)) {
                    Storage::disk('public')->delete($this->existing_image);
                }
                $imageName = time().'_'.Str::slug($this->category_name).'.'.$this->category_image->extension();
                $imagePath = $this->category_image->storeAs('categories/images', $imageName, 'public');
                $data['category_image'] = $imagePath;
            }

            if ($this->category_banner) {
                if ($this->existing_banner && Storage::disk('public')->exists($this->existing_banner)) {
                    Storage::disk('public')->delete($this->existing_banner);
                }
                $bannerName = time().'_banner_'.Str::slug($this->category_name).'.'.$this->category_banner->extension();
                $bannerPath = $this->category_banner->storeAs('categories/banners', $bannerName, 'public');
                $data['category_banner'] = $bannerPath;
            }

            $this->category->update($data);

            $this->dispatch('show-toast', type: 'success', message: 'Category Saved as Draft!');

            return redirect()->route('admin.categories');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: '.$e->getMessage());
        }
    }

    public function deleteExistingImage()
    {
        if ($this->existing_image && Storage::disk('public')->exists($this->existing_image)) {
            Storage::disk('public')->delete($this->existing_image);
            $this->category->update(['category_image' => null]);
            $this->existing_image = null;
            $this->dispatch('show-toast', type: 'success', message: 'Image deleted successfully!');
        }
    }

    public function deleteExistingBanner()
    {
        if ($this->existing_banner && Storage::disk('public')->exists($this->existing_banner)) {
            Storage::disk('public')->delete($this->existing_banner);
            $this->category->update(['category_banner' => null]);
            $this->existing_banner = null;
            $this->dispatch('show-toast', type: 'success', message: 'Banner deleted successfully!');
        }
    }

    public function render()
    {
        return view('livewire.admin.categories.categories-edit');
    }
}
