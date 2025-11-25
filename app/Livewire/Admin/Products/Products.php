<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Products extends Component
{
    use WithPagination;

    // Public properties for filters
    public $search = '';
    public $filterStatus = '';
    public $filterCategory = '';
    public $filterBrand = '';
    
    protected $paginationTheme = 'bootstrap';

    // Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function updatingFilterBrand()
    {
        $this->resetPage();
    }

    // Reset all filters
    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterCategory = '';
        $this->filterBrand = '';
        $this->resetPage();
    }

    // Toggle product status
    public function toggleStatus($slug)
    {
        $product = Product::where('product_slug', $slug)->firstOrFail();
        $product->update(['status' => !$product->status]);
        
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Product status updated successfully!'
        ]);
    }

    // Toggle featured status
    public function toggleFeatured($slug)
    {
        $product = Product::where('product_slug', $slug)->firstOrFail();
        $product->update(['is_featured' => !$product->is_featured]);
        
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Product featured status updated successfully!'
        ]);
    }

    // Delete product with all images and variants
    public function deleteProduct($slug)
    {
        $product = Product::where('product_slug', $slug)->firstOrFail();
        
        // Delete thumbnail image
        if ($product->thumbnail_image && Storage::disk('public')->exists($product->thumbnail_image)) {
            Storage::disk('public')->delete($product->thumbnail_image);
        }
        
        // Delete gallery images
        if ($product->images) {
            $images = json_decode($product->images, true);
            if (is_array($images)) {
                foreach ($images as $image) {
                    if (Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }
        }
        
        // Delete variants and their images
        if ($product->variants) {
            foreach ($product->variants as $variant) {
                // Delete variant image
                if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                    Storage::disk('public')->delete($variant->image);
                }
                
                // Delete variant
                $variant->delete();
            }
        }
        
        // Finally delete the product
        $product->delete();
        
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Product deleted successfully!'
        ]);
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->whereRaw(
                    "MATCH(product_name, product_code) AGAINST(? IN NATURAL LANGUAGE MODE)", 
                    [$this->search]
                );
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterCategory, function ($query) {
                $query->where('category_id', $this->filterCategory);
            })
            ->when($this->filterBrand, function ($query) {
                $query->where('brand_id', $this->filterBrand);
            })
            ->with(['category', 'brand', 'variants'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Category::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();

        return view('livewire.admin.products.products', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands
        ]);
    }
}