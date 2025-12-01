<?php

namespace App\Livewire\Admin\Ratings;

use App\Models\Rating as UserRating;
use Livewire\Component;
use Livewire\WithPagination;

class Rating extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 20;
    public $statusFilter = 'all';
    public $selectedReview;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['reviewDeleted' => '$refresh'];

    // Reset pagination when search changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Reset pagination when status filter changes
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    // View review detail
    public function viewDetail($reviewId)
    {
        // Add dispatch event to show the modal after data is fetched
        $this->selectedReview = UserRating::with(['user', 'product'])
            ->find($reviewId);

        if ($this->selectedReview) {
            $this->dispatch('showReviewDetailModal');
        }
    }

    // Approve review
    public function approveReview($reviewId)
    {
        $review = UserRating::find($reviewId);

        if ($review) {
            $review->update(['status' => 1]); // Set status to 1 (Approved)

            $this->dispatch('show-toast', type: 'success', message: 'Review Approved successfully!');


            // Refresh the modal data if it's the selected review
            if ($this->selectedReview && $this->selectedReview->id == $reviewId) {
                $this->selectedReview = UserRating::with(['user', 'product'])->find($reviewId);
            }
        }
    }

    // Reject review
    public function rejectReview($reviewId)
    {
        $review = UserRating::find($reviewId);

        if ($review) {
            $review->update(['status' => 0]); // Set status to 0 (Pending/Rejected)

            $this->dispatch('show-toast', type: 'success', message: 'Review rejected successfully!');


            // Refresh the modal data if it's the selected review
            if ($this->selectedReview && $this->selectedReview->id == $reviewId) {
                $this->selectedReview = UserRating::with(['user', 'product'])->find($reviewId);
            }
        }
    }

    // Delete review
    public function delete($reviewId)
    {
        try {
            $review = UserRating::find($reviewId);

            if ($review) {
                $review->delete();


                $this->dispatch('show-toast', type: 'success', message: 'Review deleted successfully!');


                // Close modal if the deleted review was selected
                if ($this->selectedReview && $this->selectedReview->id == $reviewId) {
                    $this->selectedReview = null;
                    $this->dispatch('closeModal');
                }

                $this->dispatch('reviewDeleted');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting review: ' . $e->getMessage());
        }
    }

    // Toggle review status (Kept this for reference, but new methods are used for Approve/Reject)
    public function toggleStatus($reviewId)
    {
        $review = UserRating::find($reviewId);

        if ($review) {
            $review->update(['status' => !$review->status]);
            $this->dispatch('show-toast', type: 'success', message: 'Review status updated successfully!');
        }
    }

    public function render()
    {
        $reviews = UserRating::with(['user', 'product'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->whereHas('user', function($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('product', function($productQuery) {
                        $productQuery->where('product_name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('review', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function($query) {
                if ($this->statusFilter === 'approved') {
                    $query->where('status', 1);
                } elseif ($this->statusFilter === 'pending') {
                    $query->where('status', 0);
                }
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.ratings.rating', [
            'reviews' => $reviews
        ]);
    }
}