<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Repositories\MasterTransaction\AttendanceRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;
use App\Services\MasterTransaction\AttendanceService;
use Carbon\Carbon;
use stdClass;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    private $attendanceRepository, $attendanceService, $studentRepository;

    public function __construct(AttendanceService $attendanceService, AttendanceRepository $attendanceRepository, StudentRepository $studentRepository)
    {
        $this->attendanceService = $attendanceService;
        $this->attendanceRepository = $attendanceRepository;
        $this->studentRepository = $studentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today_attendance = $this->attendanceRepository->getTodayCountAttendance();
        $students = $this->studentRepository->getAll();

        $data = $this->attendanceService->getPageData('attendance-overview', '', [
            'count_attendance' => $today_attendance,
            'students' => $students
        ], null, "Presensi");
        return view('admin.pages.attendance.index', $data);
    }

    public function presence()
    {
        $students = $this->studentRepository->getAll();

        $data = $this->attendanceService->getPageData('attendance-manage', '', ['students' => $students], [], "Manajemen Presensi");
        return view('admin.pages.attendance.presence', $data);
    }

    public function scanAttendance()
    {
        $data = $this->attendanceService->getPageData('attendance-scan', 'Scan Kehadiran', [], [], 'Scan Kehadiran');
        return view('admin.pages.scanner.index', $data);
    }

    public function report()
    {
        $data = $this->attendanceService->getPageData('attendance-report', 'Laporan Presensi', [], [], "Laporan Presensi");
        return view('admin.pages.attendance.report', $data);
    }

    public function getDatatablesData()
    {
        $data = $this->attendanceRepository->getTodayAttendance();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('student', function($item) {
                return $item->student->full_name;
            })->addColumn('present_at', function($item) {
                return Carbon::parse($item->present_at)->format('H:i');
            })->addColumn('status', function($item) {
                $masuk = Carbon::parse($item->present_at)->format('H:i');
                // return $item->status;
                if($masuk < '07:00') {
                    return '<span class="badge bg-success">Tepat Waktu</span>';
                } else {
                    return '<span class="badge bg-danger">Terlambat</span>';
                } 
            })
            ->rawColumns(['status'])
            ->make(true);
    }
    public function getDatatablesLimit()
    {
        $data = $this->attendanceRepository->getTodayAttendance(10);

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('student', function($item) {
                return $item->student->full_name;
            })->addColumn('present_at', function($item) {
                return $item->present_at->format('h:i');
            })->addColumn('status', function($item) {
                // return $item->status;
                if($item->status == "masuk") {
                    return '<span class="badge bg-success">Tepat Waktu</span>';
                } else {
                    return '<span class="badge bg-danger">Terlambat</span>';
                } 
            })
            ->rawColumns(['status'])
            ->make(true);
    }
    
    public function getDatatablesPermit()
    {
        $data = $this->attendanceRepository->getTodayAbsent();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('student', function($item) {
                return $item->student->full_name;
            })->addColumn('status', function($item) {
                if($item->status == "izin") {
                    return '<span class="badge bg-primary">Izin</span>';
                } elseif($item->status == "Sakit") {
                    return '<span class="badge bg-warning">Sakit</span>';
                } else {
                    return '<span class="badge bg-danger">Absen</span>';
                } 
            })->addColumn('action', function($item) {
                if($item->status == "permit") {
                    return view('admin.pages.attendance.datatable-presence', ['item' => $item]);
                } else {
                    return "-";
                }
            })
            ->rawColumns(['status'])
            ->make(true);
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
        switch ($request->type){
            case "api": 
                $absensi = $this->attendanceService->storeAttendanceApi($request->nipd);
                return $absensi;
            default: 
                $absensi = $this->attendanceService->storeAttendance($request->nipd, $request->status);
                return $absensi;
        }
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

    /**
     * Api list attendeces.
     * @GET
     */
    public function listAttendences(Request $request)
    {
        $data = $this->attendanceRepository->getAll();
        
        if($request->status) {
            $status = $request->status;
            $data->filter(function ($item) use ($status){
                return $item->status == $status;
            });
        }

        if($request->student_id) {
            $student_id = $request->student_id;
            $data->filter(function ($item) use ($student_id){
                return $item->student_id == $student_id;
            });
        }

        if($request->violation_type_id) {
            $violation_type_id = $request->violation_type_id;
            $data->filter(function ($item) use ($violation_type_id){
                return $item->violation_type_id == $violation_type_id;
            });
        }
        
        return response()->json([
            "message" => "Berhasil menmapilkan data",
            "data" => $data
        ]);
    }

    /**
     * Api get list new presence
     * METHOD @GET
     */
    public function newAttendences(Request $request)
    {
        if($request->limit) $limit = $request->limit;
        else $limit = 10;

        if($request->status){
            $data = $this->attendanceRepository->oneConditionOneRelation('status',$request->status,["student"]);   
            $data = $data->sortByDesc("present_at")->take($limit); 
        } else {
            $data = $this->attendanceRepository->limitOrderBy('present_at',"desc",$limit,["student"]);   
        }

        $result = [];
        $i = 1;
        foreach($data as $item){
            $index = $i++;
            $name = $item->student->full_name;
            $kelas = $item->student->nama_rombel;
            $present = Carbon::parse($item->present_at)->format('H:m:s');
            
            // set response data
            $item = new stdClass();
            $item->DT_RowIndex = $index;
            $item->name = $name;
            $item->date = $present;
            $item->kelas = $kelas;
            $result[] = $item;
        }

        return response()->json([
            "message" => "Berhasil menmapilkan data",
            "data" => $result
        ]);
    }

    /**
     * Api get list new presence
     * METHOD @GET
     */
    public function studentLateAttendances(Request $request)
    {
        // if limit
        if($request->limit) $limit = $request->limit;
        else $limit = 0;
        
        // if have status validation
        if($request->status) $status = $request->status;
        else $status = "masuk";
        
        // if have status validation
        if($request->time) $time = $request->time;
        else $time = "07:00";

        // date now
        $now = date('Y-m-d');

        // get data
        $data = $this->attendanceRepository->getDataDate($now, ["student"]);

        // filter data
        $result = $this->attendanceService->studentLate($data, $limit, $status, $time);

        return response()->json([
            "message" => "Berhasil menmapilkan data",
            "data" => $result
        ]);
    }

    /**
     * Api get list new presence
     * METHOD @GET
     */
    public function studentMustLate(Request $request)
    {
        // if limit
        if($request->limit) $limit = $request->limit;
        else $limit = 0;
        
        // if have status validation
        if($request->status) $status = $request->status;
        else $status = "masuk";
        
        // if have status validation
        if($request->time) $time = $request->time;
        else $time = "07:00";

        // get data
        $data = $this->attendanceRepository->oneNotNullConditionOneRelation("present_at",["student"]);
        
        // filter data
        $result = $this->attendanceService->studentMustLate($data, $limit, $status, $time);

        return response()->json([
            "message" => "Berhasil menmapilkan data",
            "data" => $result
        ]);
    }
}
