<?php

namespace App\Livewire\Admin\PageContent;

use App\Models\PageContent;
use Livewire\Component;

class PageEdit extends Component
{
    public $pageId;
    public $type = '';
    public $title = '';
    public $slug = '';
    public $content = '';
    public $status = 1;

    protected $listeners = ['update-quill-content' => 'updateQuillContent'];

    public function mount($slug)
    {
        $page = PageContent::where('slug', $slug)->first();
        $this->pageId = $page->id;
        $this->type = $page->type;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->content = $page->content;
        $this->status = (int) $page->status; // Ensure 0/1
    }

    public function updateQuillContent($model, $content)
    {
        if ($model === 'content') {
            $this->content = $content;
        }
    }

    public function updatedTitle($value)
    {
        $this->slug = str()->slug($value);
    }

    // ✅ Handle checkbox: unchecked = 0
    public function updatedStatus($value)
    {
        $this->status = $value ? 1 : 0;
    }

    public function update()
    {
        $this->validate([
            'type' => 'required|in:shipping_info,return_policy,privacy_policy,terms_conditions',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:page_contents,slug,' . $this->pageId,
            'content' => 'required|string|min:10',
            'status' => 'required|in:0,1',
        ]);

        $page = PageContent::findOrFail($this->pageId);
        $page->update([
            'type' => $this->type,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => (int) $this->status,
        ]);

        $this->dispatch('show-toast', type: 'success', message: 'Page content updated successfully!');
        return redirect()->route('admin.page-content');
    }

    public function render()
    {
        return view('livewire.admin.page-content.page-edit');
    }
}