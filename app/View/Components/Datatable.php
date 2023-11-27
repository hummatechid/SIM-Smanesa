<?php

namespace App\View\Components;

use Illuminate\View\{Component, View};

class Datatable extends Component {

    public function __construct
    (
        public string $cardTitle, 
        public string $dataUrl, 
        public array $tableColumns,
        public string|null $tableId = null,
        public int $defaultOrder = 1,
        public string|null $dataAddUrl = null,
        public string|array|null $deleteOption = null,
        public bool $usingAlert = true,
    ) { }

    public function render() :View
    {
        return view('components.datatable');
    }
}