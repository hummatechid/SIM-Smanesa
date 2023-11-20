<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MasterData\TeacherService;

class TeacherController extends Controller
{
    private $teacherService;
    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->teacherService->getPageData('teacher-list');
        return view('admin.pages.master-data.teacher.index', $data);
    }

    public function getDatatablesData()
    {
        return $this->teacherService->getDataDatatable();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->teacherService->getPageData('teacher-add');
        return view('admin.pages.master-data.teacher.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
