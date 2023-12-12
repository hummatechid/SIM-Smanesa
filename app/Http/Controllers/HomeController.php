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
            "data_violation" => ["Nama","Jumlah Poin","Jumlah Pelanggaran"],
            "data_late" => ["Nama","Jumlah terlambat"],
            "data_presence" => ["Nama","Jam Kehadiran"],
        ]);

        return view('admin.pages.blank.index', $data);
    }
}
