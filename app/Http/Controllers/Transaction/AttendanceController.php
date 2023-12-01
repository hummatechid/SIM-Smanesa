<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\AttendanceRepository;
use App\Services\MasterTransaction\AttendanceService;

class AttendanceController extends Controller
{
    private $attendanceRepository, $attendanceService;

    public function __construct(AttendanceService $attendanceService, AttendanceRepository $attendanceRepository)
    {
        $this->attendanceService = $attendanceService;
        $this->attendanceRepository = $attendanceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->attendanceService->getPageData('attendance-overview', '');
        return view('admin.pages.attendance.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
