<?php

namespace App\Livewire\Admin\User;

use App\Models\User as ModelsUser;
use Livewire\Component;
use Livewire\WithPagination;

class User extends Component
{
    use WithPagination;
    
    public $search = '';
    public $statusFilter = 'all';
    public $perPage = 20;
    public $selectedUser = null;
    
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function toggleStatus($userId)
    {
        $user = ModelsUser::find($userId);
        if ($user) {
            $user->user_status = !$user->user_status;
            $user->save();
            
            $this->dispatch('show-toast', type: 'success', message: 'Customer Status Updated Successfully!');
        }
    }

    public function viewUserDetails($userId)
    {
        $this->selectedUser = ModelsUser::find($userId);
    }

    public function render()
    {
        $customerUser = ModelsUser::query()
            ->where('role', 'customer');

        if (!empty($this->search)) {
            $customerUser->where(function($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter !== 'all') {
            $customerUser->where('user_status', $this->statusFilter == 'active' ? 1 : 0);
        }

        $customerUser = $customerUser->orderBy('id', 'desc')->paginate($this->perPage);

        return view('livewire.admin.user.user', [
            'customerUser' => $customerUser
        ]);
    }
}