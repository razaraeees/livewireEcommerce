<?php

namespace App\Livewire\Admin\PageContent;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PageContent as PageContentModel;

class PageContent extends Component
{
    use WithPagination;

    public $search = '';

    public function delete($id)
    {
        try{
            PageContentModel::findOrFail($id)->delete();
            $this->dispatch('show-toast', type: 'success', message: 'Page Content Deleted Successfully!');

        }catch(\Exception $e){
            $this->dispatch('show-toast', type: 'error', message: 'Page Content Not Deleted!');
        }
    }



    public function render()
    {
        $items = PageContentModel::when($this->search, function ($query) {
            $query->where('title', 'like', "%{$this->search}%")
                  ->orWhere('slug', 'like', "%{$this->search}%")
                  ->orWhere('type', 'like', "%{$this->search}%");
        })
        ->latest()
        ->paginate(10);

        return view('livewire.admin.page-content.page-content', compact('items'));
    }
}
