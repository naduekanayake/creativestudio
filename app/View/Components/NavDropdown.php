<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavDropdown extends Component
{
    public function __construct(
        public string $label,
        public string $icon = 'circle',
    ) {}

    public function render()
    {
        return view('components.nav-dropdown');
    }
}