<?php

namespace App\Services;

class BaseService {
    public $repository;
    public string $pageTitle, $mainUrl, $mainMenu, $subMenu;

    /**
     * Get data to showing in the page data
     *
     * @param string $subTitle
     * @param array $optionalData
     * @return array
     */
    public function getPageData(string|null $subMenu = null, string $subTitle = "" ,array $optionalData = []) :array
    {
        if($subMenu) $this->subMenu = $subMenu;
        
        $data = array_merge([
            "page_title" => $this->pageTitle,
            "main_url" => $this->mainUrl,
            "sub_title" => $subTitle,
            "main_menu" => $this->mainMenu,
            "sub_menu" => $this->subMenu
        ], $optionalData);
        return $data;
    }
}