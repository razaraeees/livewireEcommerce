<?php

namespace App\Livewire\Admin\Brand;

use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Brands extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function delete($id)
    {
        $brand = Brand::find($id);

        if ($brand) {
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }

            $brand->delete();
        }

        $this->dispatch('show-toast', type: 'success', message: 'Brand Deleted Successfully!');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $brands = Brand::query();

        if (!empty($this->search)) {
            $brands->where('name', 'like', "%{$this->search}%");
        }

        $brands = $brands->orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin.brand.brands', [
            'brands' => $brands
        ]);
    }
}