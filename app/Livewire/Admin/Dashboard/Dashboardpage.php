<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboardpage extends Component
{
    
    // #[Layout('adminlayout.layout')]
    public function render()
    {
        return view('livewire.admin.dashboard.dashboardpage');
    }
    
}
