<?php

namespace App\Livewire\Admin\Inquiries;

use App\Models\Inquiries;
use Livewire\Component;
use Livewire\WithPagination;

class InquiriesIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 20;
    public $selectedInquiries = null;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewDetail($id)
    {
        $this->selectedInquiries = Inquiries::find($id);
    }

    public function markAsRead($id)
    {
        $inquiry = Inquiries::find($id);
        if ($inquiry) {
            $inquiry->update(['status' => 'read']);
            $this->selectedInquiries = $inquiry->fresh();
            $this->dispatch('show-toast', type: 'success', message: 'Inquiry marked as read successfully!');
        }
    }

    public function delete($id)
    {
        $inquiry = Inquiries::find($id);
        if ($inquiry) {
            $inquiry->delete();
            $this->selectedInquiries = null;
            $this->dispatch('show-toast', type: 'success', message: 'Inquiry deleted successfully!');
            
            // Modal close karne ke liye
            $this->dispatch('closeModal');
        }
    }

    public function render()
    {
        $inquiry = Inquiries::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('subject', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.inquiries.inquiries-index', [
            'inquiry' => $inquiry
        ]);
    }
}