<?php

namespace App\View\Components;

use Illuminate\View\{Component, View};

class Datatable extends Component {

    public function __construct
    (
        public string $cardTitle, 
        public string $dataUrl, 
        public array $tableColumns,
        public string $tableId = 'table',
        public int $defaultOrder = 0,
        public string $arrangeOrder = 'asc',
        public string|null $dataAddUrl = null,
        public string|array|null $deleteOption = null,
        public bool $usingAlert = true,
        public string $dataAddType = 'new_page',
        public array $dataAddSettings = [],
        public string $dataAddBtn = '',
        public bool $withMultipleSelect = false,
        public string $multipleSelectAll = "",
        public bool $paggingTable = true,
        public bool $searchableTable = true,
        public bool $serverSide = true,
        public bool $infoTable = true,
        public array $withCustomGroups = [],
        public array $customExportButton = [],
        public string $customExportTitle = "",
        public bool $orderable = true,
    ) { }

    public function render() :View
    {
        return view('components.datatable');
    }
}