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
        public string $dataAddType = 'new_page',
        public array $dataAddSettings = [],
        public bool $withMultipleSelect = false,
        public array $withCustomGroups = []
    ) { }

    public function render() :View
    {
        return view('components.datatable');
    }
}