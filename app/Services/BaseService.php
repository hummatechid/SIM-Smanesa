<?php

namespace App\Services;

use App\Helpers\CallMonth;

class BaseService {
    use CallMonth;

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

    public function getDataStatistikYear(array|object $data): array | object
    {
            
        $result = [];
        $data = collect($data)->map(function ($item) use (&$result) {
            // get date
            $date = explode("T", $item->created_at);
    
            // get month
            $month = (int)explode("-", $date[0])[1];
    
            // Increment the total value for the month
            if (!isset($result[$this->fullMonth($month)])) {
                $result[$this->fullMonth($month)] = 0;
            }
    
            $result[$this->fullMonth($month)] += 1;
    
            return $result;
        });
    
        return $result;
    }

    public function getDataStatistikMonth(array|object $data): array | object
    {
        
        $result = [];

        $data = collect($data)->map(function ($item) use (&$result) {
            // get date
            $dates = explode("T", $item->created_at);

            // get date & month
            $date = (int)explode("-", $dates[0])[2];
            $month = (int)explode("-", $dates[0])[1];
            $year = (int)explode("-", $dates[0])[0];
    
            // Increment the total value for the month
            if (!isset($result[$date])) {
                $result[$date] = 0;
            }
    
            $result[$date] += 1;
    
            return $result;
        });
    
        return $result;
    }
}