<?php

namespace App\Http\Controllers;

use App\Services\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{   
    private $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = $this->homeService->getPageData("dashboard", "Dashboard", [
            "data" => $this->homeService->countCard(),
            "data_violation" => ["name"=>"Nama","kelas" => "Kelas","poin" => "Total Point" ,"total"=>"Jumlah Pelanggaran"],
            "data_late" => ["name"=>"Nama","kelas" => "Kelas","total"=>"Jumlah Keterlambatan"],
            "data_presence" => ["name"=>"Nama","kelas" => "Kelas","date"=>"Jam Kehadiran"],
        ], null, "Dashboard");

        return view('admin.pages.blank.index', $data);
    }
}
