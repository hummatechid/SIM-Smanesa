<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditPassword extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $updateUrl = "",
        public string $backUrl = "",
        public string $formId = "form",
        public string $cardTitle = "Ubah Password",

    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.edit-password');
    }
}
