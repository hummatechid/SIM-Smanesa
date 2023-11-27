<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Services\ViolationService;
use App\Repositories\{StudentRepository, ViolationTypeRepository};
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    private $violationService, $studentRepository, $violationTypeRepository;

    public function __construct(ViolationService $violationService, StudentRepository $studentRepository, ViolationTypeRepository $violationTypeRepository)
    {
        $this->violationService = $violationService;
        $this->studentRepository = $studentRepository;
        $this->violationTypeRepository = $violationTypeRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->violationService->getPageData('violation');
        return view('admin.pages.violation.index', $data);
    }

    public function getDatatablesData()
    {
        return $this->violationService->getDataDatatable();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = $this->studentRepository->getAll();
        $violation_types = $this->violationTypeRepository->getAll();
        $data = $this->violationService->getPageData('violation', '', ['users' => $user, 'violation_types' =>$violation_types]);
        return view('admin.pages.violation.create', $data);
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
