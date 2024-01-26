<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\UploadImage;
use App\Http\Controllers\Controller;
use App\Repositories\Settings\GeneralSettingRepository;
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

    private $attendanceRepository, $attendanceService, $studentRepository, $generalSettingRepository;

    public function __construct(AttendanceService $attendanceService, AttendanceRepository $attendanceRepository, StudentRepository $studentRepository,
    GeneralSettingRepository $generalSettingRepository)
    {
        $this->attendanceService = $attendanceService;
        $this->attendanceRepository = $attendanceRepository;
        $this->studentRepository = $studentRepository;
        $this->generalSettingRepository = $generalSettingRepository;
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

    public function timeSetting()
    {
        $data = $this->attendanceService->getPageData('attendance-setting', '', [], [], "Atur Jam Hadir & Pulang");
        return view('admin.pages.attendance.setting', $data);
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

    public function scanAttendanceCamera()
    {
        $data = $this->attendanceService->getPageData('attendance-scan', 'Scan Kehadiran', [], [], 'Scan Kehadiran');
        return view('admin.pages.scanner.scanner', $data);
    }

    public function report()
    {
        $tahun = \App\Models\Attendance::selectRaw('YEAR(created_at) as tahun')->orderBy('tahun','ASC')->groupBy('tahun')->get();
        if(count($tahun) == 0) $tahun = [date('Y')];
        $group_data = [
            'years' => $tahun,
            'months' => $this->months,
            'grades' => $this->grades,
            'classes' => \App\Models\Student::select('nama_rombel')->orderBy('nama_rombel',"ASC")->groupBy('nama_rombel')->get()
        ];
        $data = $this->attendanceService->getPageData('attendance-report', 'Laporan Presensi', $group_data, [], "Laporan Presensi");
        return view('admin.pages.attendance.report', $data);
    }

    public function getDatatablesData(Request $request)
    {
        if($request->date) $date = $request->date;
        else $date = today();

        $data = $this->attendanceRepository->getDataDate($date);
        $data = $data->filter(function($item){
            return $item->present_at;
        })->sortByDesc('present_at');

        $settings = $this->generalSettingRepository->getDataDateSetting(now());

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('student', function($item) {
                return '<a href="'.route('student.show', $item->student->id).'" class="text-reset">'.$item->student->full_name . ' ('.$item->student->nisn.')</a>';
            })->addColumn('class', function($item) {
                return $item->student->nama_rombel;
            })->addColumn('present_at', function($item) {
                return Carbon::parse($item->present_at)->format('H:i');
            })->addColumn('status', function($item) use ($settings) {
                $masuk = Carbon::parse($item->present_at)->format('H:i');
                // return $item->status;
                if($item->status == "masuk"){
                    if($masuk < ($settings ? $settings->start_time : "07:15")) {
                        return '<span class="badge bg-success">Tepat Waktu</span>';
                    } else {
                        return '<span class="badge bg-secondary">Terlambat</span>';
                    } 
                } else if($item->status == "izin"){
                    return '<span class="badge bg-warning">Izin</span>';
                } else if($item->status == "sakit"){
                    return '<span class="badge bg-info">Sakit</span>';
                } else {
                    return '<span class="badge bg-danger">Tanpa Keterangan</span>';
                }
            })->addColumn('action', function($item) {
                return view('admin.pages.attendance.datatable-presence', ['item' => $item, 'student' => $item->student]);
            })
            ->rawColumns(['status', 'action', 'student'])
            ->make(true);
    }

    public function getDatatablesLimit()
    {
        $data = $this->attendanceRepository->getTodayAttendance(10);
        $data = $data->sortByDesc("present_at");

        $settings = $this->generalSettingRepository->getDataDateSetting(now());

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('student', function($item) {
                return '<a href="'.route('student.show', $item->student->id).'" class="text-reset">'.$item->student->full_name . ' ('.$item->student->nisn.')</a>';
            })->addColumn('class', function($item) {
                return $item->student->nama_rombel;
            })->addColumn('present_at', function($item) {
                return Carbon::parse($item->present_at)->format('d/m/Y H:i');
            })->addColumn('status', function($item) use ($settings){
                $masuk = Carbon::parse($item->present_at)->format('H:i');
                // return $item->status;
                if($item->status == "masuk"){
                if($masuk < ($settings ? $settings->start_time : "07:15")) {
                        return '<span class="badge bg-success">Tepat Waktu</span>';
                    } else {
                        return '<span class="badge bg-secondary">Terlambat</span>';
                    } 
                } else if($item->status == "izin"){
                    return '<span class="badge bg-warning">Izin</span>';
                } else if($item->status == "sakit"){
                    return '<span class="badge bg-info">Sakit</span>';
                } else {
                    return '<span class="badge bg-danger">Tanpa Keterangan</span>';
                }
            })->addColumn('action', function($item) {
                return view('admin.pages.attendance.datatable-presence', ['item' => $item, 'student' => $item->student]);
            })
            ->rawColumns(['status', 'student'])
            ->make(true);
    }
    
    public function getDatatablesPermit()
    {
        $data = $this->attendanceRepository->getTodayAbsent();
        $data = $data->sortByDesc("present_at");

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('student', function($item) {
                return '<a href="'.route('student.show', $item->student->id).'" class="text-reset">'.$item->student->full_name . ' ('.$item->student->nisn.')</a>';
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
            ->rawColumns(['status', 'student'])
            ->make(true);
    }

    public function getReportDatatablesData(Request $request)
    {
        switch($request->type){
            case "monthly":
                if(!$request->year) $year = date('Y');
                else $year = $request->year;
        
                $data = $this->attendanceRepository->getDataMonth($year,$request->month,["student"]);
                break;
            case "yearly":
                $data = $this->attendanceRepository->getDataYears($request->year,["student"]);
                break;
            case "custom":
                $check_date = explode("-",$request->date);
                if(count($check_date) > 3){
                    if(!$request->date){
                        $date_from = date('Y-m-d');
                        $date_to = date('Y-m-d');
                    }else {
                        $date_from = Carbon::parse($check_date[0])->format('Y-m-d');
                        $date_to = Carbon::parse($check_date[1])->hour(23)
                        ->minute(59)
                        ->second(0)
                        ->format('Y-m-d H:i:s');
                    }
                    $data = $this->attendanceRepository->getDataCustomDate($date_from,$date_to,["student"]);
                }else if(count($check_date) == 3){
                    $data = $this->attendanceRepository->getDataDate($request->date,["student"]);
                } else {
                    if(!$request->year) $year = date('Y');
                    else $year = $request->year;
                    if(!$request->month) $month = date('m');
                    else $month = $request->month;
                    $data = $this->attendanceRepository->getDataMonth($year,$month,["student"]);
                }
                break;
            default:
                if(!$request->year) $year = date('Y');
                else $year = $request->year;
                if(!$request->month) $month = date('m');
                else $month = $request->month;
                $data = $this->attendanceRepository->getDataMonth($year,$month,["student"]);
                break;
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
    public function updateSetting(Request $request)
    {
        if(!$request->attendance) return redirect()->back()->with("error","Jam kedatangan harus di inputkan");
        if(!$request->departure) return redirect()->back()->with("error","Jam kepulangan harus di inputkan");

        $data = [
            "time_start" => $request->attendance,
            "time_end" => $request->departure,
        ];
        $settings = $this->generalSettingRepository->getDataDateSetting($request->date);
        if($settings){
            $settings->update($data);
        }else {
            $data["date"] = $request->date;
            $this->generalSettingRepository->create($data);
        }
        return redirect()->back()->with("success","Berhasil set jam kedatangan dan kepulangan pada tanggal ".$request->date);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createPermit(Request $request)
    {
        if($request->status) $status = $request->status;
        else $status = "izin";

        if($request->date) $now = $request->date;
        else $now = now();

        $year = date('Y');
        $data = $this->attendanceRepository->getDataDateWithCondition($now, ["student"], "student_id", $request->student_id, "first");
        
        if(!$data) return redirect()->back()->with("error","Data absensi siswa ini tidak ada");

        // set image
        $path = 'images/permit/'.$year.'/'.$data->student->tingkat_pendidikan.'/'.$data->student->nama_rombel;
        !is_dir($path) && mkdir($path, 0777, true);
        
        
        if($request->file('permit_file')) {
            $file = $request->file('permit_file');
            $fileData = $this->uploads($file,$path);
            $photo = $fileData["filePath"].".".$fileData["fileType"];
            
            // type data update or create
            if($data->present_at) $type = "update";
            else $type = "create";
        } else {
            $photo = null;

            // type data update or create
            $type = "update";
        }

        $dataChange = [
            "status" => $status,
        ];

        // make data for change data
        if($type == "create") $dataChange["present_at"] = $now;
        if($photo) $dataChange["permit_file"] = $photo;
        if($type == "update" && $status == "alpha") $dataChange["present_at"] = null;
        
        if($type == "create" && $data->present_at) return redirect()->back()->with("error","Siswa ini telah absensi");
        
        $data->update($dataChange);

        if($type == "create") $message = "Berhasil absensi izin untuk ". $data->student->full_name;
        else $message = "Berhasil merubah data absensi untuk ". $data->student->full_name; 

        return redirect()->back()->with("success",$message);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function syncAttendanceToday(Request $request)
    {
        $checkData = $this->attendanceRepository->getDataDate(today());
        if(count($checkData) > 0){
            return redirect()->back()->with("error","Data absensi hari ini sudah tersedia, tidak bisa melakukan sinkronisasi lagi!");
        }

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
        
        $settings = $this->generalSettingRepository->getDataDateSetting(now());

        // if have status validation
        if($request->time) $time = $request->time;
        else $time = ($settings ? $settings->start_time : "07:15");

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

        $settings = $this->generalSettingRepository->getDataDateSetting(now());
        
        // if have status validation
        if($request->time) $time = $request->time;
        else $time = ($settings ? $settings->start_time : "07:15");

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
