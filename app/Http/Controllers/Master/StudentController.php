<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Repositories\StudentRepository;
use App\Services\MasterData\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    private StudentRepository $repository;
    private StudentService $service;

    public function __construct(StudentRepository $repository, StudentService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * show index page
     *
     * @return View
     */
    public function index(): View
    {
        $data = $this->service->getPageData('student-list');
        return view('admin.pages.master-data.student.index', $data);
    }

    public function show(string $id): View
    {
        $student_data = ['student' => $this->repository->getOneById($id)];
        $data = $this->service->getPageData('null', '', $student_data, [], "Detail Siswa");

        return view('admin.pages.master-data.student.show', $data);
    }

    /**
     * get data for datatables
     *
     * @return mixed
     */
    public function getDatatablesData()
    {
        return $this->service->getDataDatatable();
    }

    /**
     * sync student from dapodik
     *
     * @return JsonResponse
     */
    public function syncStudents(): JsonResponse
    {
        $npsn = config('app.web_service_npsn');
        $key = config('app.web_service_key');
        $students = $this->repository->fetchStudentFromDapodik($npsn, $key);

        if($students == null) {
            return response()->json([
                'message' => $students
            ]);
        }

        $this->service->handleSyncStudent($students['rows']);

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function detailOneStudent(string $id){
        $student = $this->repository->getOneById($id);

        return response()->json([
            "message" => "Sukses mengambil data student",
            "data" => $student
        ]);
    }
    
    public function detailManyStudent(Request $request)
    {
        // check request ids
        if(!$request->ids){
            return response()->json([
                "message" => "Data ids harus dikirim, dan harus berupa array",
                "data" => null
            ], 400);
        }

        // get data
        $student = $this->repository->getWhereIn("id",$request->ids);
    
        return response()->json([
            "message" => "Sukses mengambil data student",
            "data" => $student
        ]);
    }
}
