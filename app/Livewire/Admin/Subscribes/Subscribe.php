<?php

namespace App\Livewire\Admin\Subscribes;

use App\Models\NewsletterSubscribers;
use Livewire\Component;
use Livewire\WithPagination;

class Subscribe extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 20;
    public $selectedSubscriber;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewDetail($subscriberId)
    {
        $this->selectedSubscriber = NewsletterSubscribers::find($subscriberId);
    }

    public function render()
    {
        $subscribe = NewsletterSubscribers::query()
            ->when($this->search, function($query) {
                $query->where('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.subscribes.subscribe', [
            'subscribe' => $subscribe
        ]);
    }
}