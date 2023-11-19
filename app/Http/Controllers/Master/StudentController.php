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
                'message' => 'failed'
            ]);
        }

        $this->service->handleSyncStudent($students['rows']);

        return response()->json([
            'message' => 'success'
        ]);
    }
}
