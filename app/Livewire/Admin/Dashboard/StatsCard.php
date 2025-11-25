<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;

class StatsCard extends Component
{

    public $title;
    public $value;
    public $subtitle;
    public $icon;
    public $bgColor;
    public $textColor;


    public function mount($title, $value, $subtitle, $icon, $textColor, $bgColor){
        $this->title = $title;
        $this->value = $value;
        $this->subtitle = $subtitle;
        $this->icon = $icon;
        $this->textColor = $textColor;
        $this->bgColor = $bgColor;
    }

    public function render()
    {
        return view('livewire.admin.dashboard.stats-card');
    }
}
