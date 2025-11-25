<?php

namespace App\Livewire\Admin\Variants;

use App\Models\Variant;
use Livewire\Component;
use Livewire\WithPagination;

class Variants extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['delete'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        try {
            $variants = Variant::findOrFail($id);
            $variants->delete();
            
            // session()->flash('success', 'variants deleted successfully!');
            $this->dispatch('show-toast', type: 'success', message: 'Variants deleted Successfully!');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());

            // session()->flash('error', 'Error deleting variants: ' . $e->getMessage());
        }
    }
    public function render()
    {
         $variants = Variant::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->with('variantValues') // Eager load relationship
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.variants.variants', [
            'variants' => $variants,
        ]);
    }
}
