<?php

namespace App\Livewire\Admin\Attributes;

use App\Models\Attribute;
use Livewire\Component;
use Livewire\WithPagination;

class Attributes extends Component
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
            $attribute = Attribute::findOrFail($id);
            $attribute->delete();
            
            // session()->flash('success', 'Attribute deleted successfully!');
            $this->dispatch('show-toast', type: 'success', message: 'Attribute deleted Successfully!');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());

            // session()->flash('error', 'Error deleting attribute: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $attributes = Attribute::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->with('attributeValue') // Eager load relationship
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.attributes.attributes', [
            'attributes' => $attributes,
        ]);
    }
}