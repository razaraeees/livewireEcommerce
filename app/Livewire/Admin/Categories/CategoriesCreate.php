<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriesCreate extends Component
{
    use WithFileUploads;

    // Category fields
    public $parent_id;
    public $level = ''; // Empty by default to force selection
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

    // Dynamic categories based on level
    public $mainCategories = [];
    public $subCategories = [];

    protected $rules = [
        'category_name' => 'required|string|max:255',
        'url' => 'required|string|unique:categories,url|max:255',
        'parent_id' => 'nullable|exists:categories,id',
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

    public function mount()
    {
        $this->loadCategories();
    }

    // Load categories based on level selection
    public function updatedLevel($value)
    {
        // Reset parent_id when level changes
        $this->parent_id = null;
        
        $this->loadCategories();

        // Add validation for parent_id when level > 0
        if ($value > 0) {
            $this->rules['parent_id'] = 'required|exists:categories,id';
        } else {
            $this->rules['parent_id'] = 'nullable';
        }
    }

    private function loadCategories()
    {
        if ($this->level == 1) {
            // For Sub Category: Load Main Categories (level 0)
            $this->mainCategories = Category::where('level', 0)
                ->where('status', true)
                ->orderBy('category_name')
                ->get();
        } elseif ($this->level == 2) {
            // For Sub-Sub Category: Load Sub Categories (level 1)
            $this->subCategories = Category::where('level', 1)
                ->where('status', true)
                ->with('parent')
                ->orderBy('category_name')
                ->get();
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        // Additional validation for parent_id based on level
        if ($this->level > 0 && !$this->parent_id) {
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

            // Upload category image
            if ($this->category_image) {
                $imageName = time() . '_' . Str::slug($this->category_name) . '.' . $this->category_image->extension();
                $imagePath = $this->category_image->storeAs('categories/images', $imageName, 'public');
                $data['category_image'] = $imagePath;
            }

            // Upload category banner
            if ($this->category_banner) {
                $bannerName = time() . '_banner_' . Str::slug($this->category_name) . '.' . $this->category_banner->extension();
                $bannerPath = $this->category_banner->storeAs('categories/banners', $bannerName, 'public');
                $data['category_banner'] = $bannerPath;
            }

            Category::create($data);

            $this->dispatch('show-toast', type: 'success', message: 'Category Created Successfully!');
            
            return redirect()->route('admin.categories');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function saveDraft()
    {
        $this->validate([
            'category_name' => 'required|string|max:255',
            'url' => 'required|string|unique:categories,url|max:255',
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
                'status' => false, // Draft means inactive
                'banner_status' => $this->banner_status,
            ];

            // Upload category image
            if ($this->category_image) {
                $imageName = time() . '_' . Str::slug($this->category_name) . '.' . $this->category_image->extension();
                $imagePath = $this->category_image->storeAs('categories/images', $imageName, 'public');
                $data['category_image'] = $imagePath;
            }

            // Upload category banner
            if ($this->category_banner) {
                $bannerName = time() . '_banner_' . Str::slug($this->category_name) . '.' . $this->category_banner->extension();
                $bannerPath = $this->category_banner->storeAs('categories/banners', $bannerName, 'public');
                $data['category_banner'] = $bannerPath;
            }

            Category::create($data);

            $this->dispatch('show-toast', type: 'success', message: 'Category Saved as Draft!');
            
            return redirect()->route('admin.categories');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.categories.categories-create');
    }
}