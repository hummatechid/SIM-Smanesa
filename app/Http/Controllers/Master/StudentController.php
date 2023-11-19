<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Repositories\StudentRepository;
use App\Services\MasterData\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private StudentRepository $repository;
    private StudentService $service;

    public function __construct(StudentRepository $repository, StudentService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.pages.student.index');
    }

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
