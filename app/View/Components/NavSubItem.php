<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavSubItem extends Component
{
    public function __construct(
        public string $href,
        public string $label,
    ) {}

    public function render()
    {
        return view('components.nav-sub-item');
    }
}