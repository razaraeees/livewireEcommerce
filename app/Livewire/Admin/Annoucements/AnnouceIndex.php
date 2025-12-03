<?php

namespace App\Livewire\Admin\Annoucements;

use App\Models\Annoucement;
use Livewire\Component;
use Livewire\WithPagination;

class AnnouceIndex extends Component
{
    use WithPagination;

    public $search = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $annoucement = Annoucement::find($id);
        if ($annoucement) {
            $annoucement->delete();
            session()->flash('success', 'Annoucement deleted successfully!');
        }
    }

    public function render()
    {
        $annoucements = Annoucement::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('message', 'like', '%' . $this->search . '%')
                    ->orWhere('type', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.annoucements.annouce-index', compact('annoucements'));
    }
}
