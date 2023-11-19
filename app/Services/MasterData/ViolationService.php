<?php

namespace App\Services\MasterData;

use App\Http\Requests\ViolationTypeRequest;
use App\Repositories\ViolationTypeRepository;

class ViolationService {
    private $violationTypeRepository;
    private $pageTitle = "Jenis Pelanggaran";
    private $mainUrl = "violation-type";

    public function __construct(ViolationTypeRepository $violationTypeRepository)
    {
        $this->violationTypeRepository = $violationTypeRepository;
    }

    public function getPageData(string $subTitle ,array $optionalData = [])
    {
        $data = array_merge([
            "page_title" => $this->pageTitle,
            "main_url" => $this->mainUrl,
            "sub_title" => $subTitle
        ], $optionalData);
        return $data;
    }
}