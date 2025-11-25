<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
   use WithPagination;

    public $search = '';
    public $filterStatus = '';
    protected $paginationTheme = 'bootstrap';

    public function delete($id)
    {
        $category = Category::find($id);

        if ($category) {
            $hasChildren = Category::where('parent_id', $id)->exists();
            
            if ($hasChildren) {
                $this->dispatch('show-toast', type: 'error', message: 'Cannot delete category with subcategories!');
                return;
            }

            // Delete category image
            if ($category->category_image) {
                Storage::disk('public')->delete($category->category_image);
            }

            // Delete category banner
            if ($category->category_banner) {
                Storage::disk('public')->delete($category->category_banner);
            }

            $category->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Category Deleted Successfully!');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::query()->with('parent');

        // Search filter
        if (!empty($this->search)) {
            $categories->where(function($query) {
                $query->where('category_name', 'like', "%{$this->search}%")
                      ->orWhere('url', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        // Status filter
        if ($this->filterStatus !== '') {
            $categories->where('status', $this->filterStatus);
        }

        $categories = $categories->orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin.categories.categories', [
            'categories' => $categories
        ]);
    }
}
