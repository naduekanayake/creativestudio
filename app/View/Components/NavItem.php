<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavItem extends Component
{
    public function __construct(
        public string $href,
        public string $label,
        public string $icon = 'circle',
    ) {}

    public function render()
    {
        return view('components.nav-item');
    }
}