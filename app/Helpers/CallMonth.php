<?php

namespace App\Helpers;


trait CallMonth {

    public function shortMonth(int $index): string
    {
        $month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        return $month[$index - 1];
    }

    public function fullMonth(int $index, string $type = "indonesia"): string
    {
        switch ($type){
            case 'indonesia':
                $month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            case 'english':
                $month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            default:
                $month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        }

        return $month[$index - 1];
    }

    public function allMonth(string $type = "indonesia"): array
    {
        switch ($type){
            case 'indonesia':
                $month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            case 'english':
                $month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            default:
                $month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        }

        return $month;
    }

    public function daterPerMonth(int $month, int $year): int
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

}