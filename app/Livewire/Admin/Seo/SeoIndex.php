<?php

namespace App\Livewire\Admin\Seo;

use App\Models\SeoSetting;
use Livewire\Component;
use Livewire\WithPagination;

class SeoIndex extends Component
{
    use WithPagination;
    public $search;


    public function delete($id)
    {
        $page = SeoSetting::find($id);

        if ($page) {
            $page->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Seo Setting Deleted Successfully!');
        }
    }

    public function render()
    {
        $pages = SeoSetting::when($this->search, function ($query) {
                return $query->where('page_name', 'like', '%' . $this->search . '%')
                             ->orWhere('page_url', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.seo.seo-index', compact('pages'));
    }
}
