<?php

namespace App\Http\Controllers;

use App\Repositories\PenggunaRepository;
use App\Repositories\StudentRepository;
use App\Repositories\TeacherRepository;
use App\Services\HomeService;
use App\Services\MasterData\PenggunaService;
use Illuminate\Http\Request;

class HomeController extends Controller
{   
    private $homeService, $penggunaService;
    private $studentRepository, $teacherRepository ,$penggunaRepository;

    public function __construct(
        HomeService $homeService,
        PenggunaService $penggunaService,
        StudentRepository $studentRepository,
        TeacherRepository $teacherRepository,
        PenggunaRepository $penggunaRepository
    )
    {
        $this->homeService = $homeService;
        $this->penggunaService = $penggunaService;
        $this->studentRepository = $studentRepository;
        $this->teacherRepository = $teacherRepository;
        $this->penggunaRepository = $penggunaRepository;
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

    public function landingPage()
    {
        $student_count = $this->studentRepository->randomData("count");
        $teacher_count = $this->teacherRepository->randomData("count");
        $user_count = $this->penggunaRepository->randomData("count");
        $data = [
            "count_student" => $student_count,
            "count_teacher" => $teacher_count,
            "count_admin" => $user_count,
        ];
        return view('admin.pages.blank.landing-page', $data);
    }
}
