<?php

namespace App\Livewire\Admin\PageContent;

use App\Models\PageContent;
use Livewire\Component;

class PageCreate extends Component
{
    public $type = '';
    public $title = '';
    public $slug = '';
    public $content = '';
    public $status = 1; // Default: active

    protected $listeners = ['update-quill-content' => 'updateQuillContent'];

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

    // ✅ Critical Fix: Handle checkbox state
    public function updatedStatus($value)
    {
        // Jab bhi status update ho, ensure it's 1 or 0
        $this->status = $value ? 1 : 0;
    }

    public function store()
    {
        $this->validate([
            'type' => 'required|in:shipping_info,return_policy,privacy_policy,terms_conditions',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:page_contents,slug',
            'content' => 'required|string|min:10',
            'status' => 'required|in:0,1', // Now it will be 0 or 1
        ]);

        PageContent::create([
            'type' => $this->type,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => (int) $this->status,
        ]);

        $this->reset();
        $this->dispatch('reset-quill');
        $this->dispatch('show-toast', type: 'success', message: 'Page content created successfully!');
    }

    public function render()
    {
        return view('livewire.admin.page-content.page-create');
    }
}