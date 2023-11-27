<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShowUser extends Component
{

    public function __construct
    (
        public string $cardTitle,
        public string $editUrl,
        public string $backUrl,
        public $dataUser,
        public string|null $formFor = null,
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.show-user');
    }
}
