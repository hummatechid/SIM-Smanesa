<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CreateUser extends Component
{
    
    public function __construct
    (
        public string $cardTitle,
        public string $formAction,
        public string $backUrl,
        public string $formMethod = "POST",
        public string|null $formFor = null
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.create-user');
    }
}
