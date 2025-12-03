<?php

namespace App\Livewire\Admin\Annoucements;

use App\Models\Annoucement;
use Livewire\Component;

class AnnouceEdit extends Component
{

    public $announcementId;
    public $title;
    public $message;
    public $type;
    public $is_active;
    public $start_at;
    public $end_at;

    protected $listeners = ['update-quill-content' => 'updateQuillContent'];

    public function mount($id)
    {
        $announcement = Annoucement::findOrFail($id);

        $this->announcementId = $announcement->id;
        $this->title          = $announcement->title;
        $this->message        = $announcement->message;
        $this->type           = $announcement->type;
        $this->is_active      = $announcement->is_active;
        $this->start_at       = optional($announcement->start_at)->format('Y-m-d\TH:i');
    $this->end_at         = optional($announcement->end_at)->format('Y-m-d\TH:i');
    }

    public function updateQuillContent($model, $content)
    {
        if ($model === 'message') {
            $this->message = $content;
        }
    }

    public function update()
    {
        $this->validate([
            'title'     => 'nullable|string|max:255',
            'message'   => 'required|string',
            'type'      => 'required|string|in:info,success,warning,danger',
            'is_active' => 'boolean',
            'start_at'  => 'nullable|date',
            'end_at'    => 'nullable|date|after_or_equal:start_at',
        ]);

        $announcement = Annoucement::findOrFail($this->announcementId);

        $announcement->update([
            'title'     => $this->title,
            'message'   => $this->message,
            'type'      => $this->type,
            'is_active' => $this->is_active,
            'start_at'  => $this->start_at,
            'end_at'    => $this->end_at,
        ]);

        $this->dispatch('reset-quill');
        $this->dispatch('show-toast', type: 'success', message: 'Announcement Updated Successfully!');

        return redirect()->route('admin.annoucement');
    }
    public function render()
    {
        return view('livewire.admin.annoucements.annouce-edit');
    }
}
