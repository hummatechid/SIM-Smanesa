<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\UploadImage;
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
    use UploadImage;

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
        $attendances = $this->attendanceRepository->getTodayAttendance();
        $today_attendance = $this->attendanceRepository->getTodayCountAttendance();
        $students = $this->studentRepository->getAll();
        $today_attendance->present = $this->attendanceService->countPresentStudent($attendances, "present");
        $today_attendance->late = $this->attendanceService->countPresentStudent($attendances, "late");
        $today_attendance->permit = $this->attendanceRepository->countByStatusAttendancesToday("izin");
        $today_attendance->absent = $this->attendanceRepository->countByStatusAttendancesToday("alpha");

        $data = $this->attendanceService->getPageData('attendance-overview', '', [
            'count_attendance' => $today_attendance,
            'students' => $students,
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
        $data = $this->attendanceRepository->getDataDate(today());
        $data = $data->filter(function($item){
            return $item->present_at;
        });

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('student', function($item) {
                return $item->student->full_name;
            })->addColumn('present_at', function($item) {
                return Carbon::parse($item->present_at)->format('H:i');
            })->addColumn('status', function($item) {
                $masuk = Carbon::parse($item->present_at)->format('H:i');
                // return $item->status;
                if($item->status == "masuk"){
                    if($masuk < '07:00') {
                        return '<span class="badge bg-success">Tepat Waktu</span>';
                    } else {
                        return '<span class="badge bg-danger">Terlambat</span>';
                    } 
                } else if($item->status == "izin"){
                    return '<span class="badge bg-warning">Izin</span>';
                } else if($item->status == "sakit"){
                    return '<span class="badge bg-info">Sakit</span>';
                } else {
                    return '<span class="badge bg-danger">Tanpa Keterangan</span>';
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

    public function getReportDatatablesData(Request $request)
    {
        switch($request->type){
            case "monthly":
                if(!$request->year) $year = date('Y');
                else $year = $request->year;
                $data = $this->attendanceRepository->getDataMonth($year,$request->month,["student"]);
            case "yearly":
                $data = $this->attendanceRepository->getDataYears($request->year,["student"]);
            case "custom":
                if(!$request->date){
                    $date_from = date('Y-m-d');
                    $date_to = date('Y-m-d');
                }else {
                    $date_from = str_split("-",$request->date)[0];
                    $date_to = str_split("-",$request->date)[1];
                }
                $data = $this->attendanceRepository->getDataCustomDate($date_from,$date_to,["student"]);
            default:
                if(!$request->year) $year = date('Y');
                else $year = $request->year;
                if(!$request->month) $month = date('m');
                else $month = $request->month;
                $data = $this->attendanceRepository->getDataMonth($year,$month,["student"]);
        }
        if($request->data == "per_class"){
            $class = $request->class;
            $data = $data->filter(function($item) use ($class){
                return $item->student->nama_rombel == $class;
            });
        }else if($request->data == "per_grade"){
            $grade = $request->grade;
            $data = $data->filter(function($item) use ($grade){
                return $item->student->tingkat_pendidikan == $grade;
            });
        }

        return $this->attendanceService->getReportDataDatatableV2($data);
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
     * Store a newly created resource in storage.
     */
    public function createPermit(Request $request)
    {
        if($request->status != "izin" || $request->status != "masuk") $status = "izin";
        else $status = $request->status;

        $now = now();
        $year = date('Y');
        $data = $this->attendanceRepository->getDataDateWithCondition($now, ["student"], "student_id", $request->student_id, "first");
        
        if(!$data) return redirect()->back()->with("error","Data absensi siswa ini tidak ada");
        
        if($data->present_at) return redirect()->back()->with("error","Siswa ini sudah melakukan absensi");

        // set image
        $path = 'images/permit/'.$year.'/'.$data->student->tingkat_pendidikan.'/'.$data->student->nama_rombel;
        !is_dir($path) && mkdir($path, 0777, true);
        
        if($data->present_at) return redirect()->back()->with("error","Siswa ini telah absensi");
        
        if($request->permit_file) {
            $file = $request->file('permit_file');
            $fileData = $this->uploads($file,$path);
            $photo = $fileData["filePath"].".".$fileData["fileType"];
        } else {
            $photo = "";
        }


        $data->update([
            "present_at" => $now,
            "status" => $status,
            "permit_file" => $photo
        ]);

        return redirect()->back()->with("success","Berhasil absensi izin untuk ". $data->student->full_name);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function syncAttendanceToday(Request $request)
    {
        $students = $this->studentRepository->getAll();
        foreach($students as $student) $this->attendanceRepository->create(
            ["status" => "alpha", "student_id" => $student->id]
        );

        return redirect()->back()->with("success","Berhasil sinkron data absensi hari ini");
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
