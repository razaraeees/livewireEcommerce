<?php

namespace App\Livewire\Admin\Annoucements;

use App\Models\Annoucement;
use Livewire\Component;

class AnnouceCreate extends Component
{
    public $title = '';
    public $message = '';
    public $type = 'info';
    public $is_active = true;
    public $start_at;
    public $end_at;

    protected $listeners = ['update-quill-content' => 'updateQuillContent'];

   public function updateQuillContent($model, $content)
    {
    if ($model === 'message') {
        $this->message = $content;
    }
    }


    public function store()
    {
        $this->validate([
            'title' => 'nullable|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|in:info,success,warning,danger',
            'is_active' => 'boolean',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        Annoucement::create([
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
        ]);

        $this->reset();
        $this->dispatch('reset-quill');
        $this->dispatch('show-toast', type: 'success', message: 'Announcement Created Successfully!');

        return redirect()->route('admin.annoucement');
    }

    public function saveDraft()
    {
        $this->validate([
            'title' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Annoucement::create([
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'is_active' => false,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
        ]);

        $this->reset();
        $this->dispatch('reset-quill');
        $this->dispatch('show-toast', type: 'success', message: 'Announcement saved as draft!');
    }

    public function render()
    {
        return view('livewire.admin.annoucements.annouce-create');
    }
}