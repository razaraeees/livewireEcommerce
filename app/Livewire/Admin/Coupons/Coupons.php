<?php

namespace App\Livewire\Admin\Coupons;

use App\Models\Coupon;
use Livewire\Component;
use Livewire\WithPagination;

class Coupons extends Component
{
    use WithPagination;

    public $search = '';

    // Reset pagination when search changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Delete Coupon
    public function delete($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            
            // Detach all relationships
            $coupon->categories()->detach();
            $coupon->brands()->detach();
            $coupon->products()->detach();
            
            // Delete coupon
            $coupon->delete();

            $this->dispatch('show-toast', type: 'success', message: 'Coupon Deleted Successfully!');

            
        } catch (\Exception $e) {

            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $couponDate = Coupon::when($this->search, function($query) {
                return $query->where('coupon_code', 'like', '%' . $this->search . '%')
                            ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.coupons.coupons', [
            'couponDate' => $couponDate
        ]);
    }
}