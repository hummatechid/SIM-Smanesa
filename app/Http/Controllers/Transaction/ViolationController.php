<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Repositories\{StudentRepository, ViolationTypeRepository};
use App\Repositories\MasterTransaction\ViolationRepository;
use App\Services\MasterTransaction\ViolationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViolationController extends Controller
{
    private $violationService, $studentRepository, $violationTypeRepository, $violationRepository;

    public function __construct(
        ViolationService $violationService,
        StudentRepository $studentRepository,
        ViolationTypeRepository $violationTypeRepository,
        ViolationRepository $violationRepository
    )
    {
        $this->violationService = $violationService;
        $this->studentRepository = $studentRepository;
        $this->violationTypeRepository = $violationTypeRepository;
        $this->violationRepository = $violationRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $this->studentRepository->getAll();
        $violation_types = $this->violationTypeRepository->getAll();
        $data = $this->violationService->getPageData('violation', '', ['users' => $user, 'violation_types' => $violation_types], null, "Pelanggaran");
        return view('admin.pages.violation.index', $data);
    }

    public function getDatatablesData(Request $request)
    {
        if($request->student_id) $data = $this->violationRepository->OneConditionOneRelation("student_id",str_replace("?","",$request->student_id),["violationType","student","user_created","user_updated"]);
        else $data = $this->violationRepository->relationship(["student","violationType","user_created","user_updated"]);

        return $this->violationService->getDataDatatable($data);
    }

    public function getReportDatatablesData(Request $request)
    {
        if(isset($request->max_render) && $request->max_render == true) {
            ini_set('MAX_EXECUTION_TIME', 3600);
            set_time_limit(0);
        }

        $data = \App\Models\Student::orderBy('full_name', 'ASC');

        switch($request->type){
            case "monthly":
                if(!$request->year) $year = date('Y');
                else $year = $request->year;

                
                $start_date = Carbon::parse($year.'-'.$request->month.'-01')->startOfMonth();
                $end_date = Carbon::parse($year.'-'.$request->month.'-01')->endOfMonth();
                
                break;
            case "yearly":
                $start_date = Carbon::parse($request->year.'-01-01')->startOfYear();
                $end_date = Carbon::parse($request->year.'-01-01')->endOfYear();

                break;
            case "custom":
                if(!$request->date){
                    $start_date = date('Y-m-d');
                    $end_date = date('Y-m-d');
                } else {
                    $check_date = explode(" s/d ",$request->date);
                    if(count($check_date) > 1){
                        $start_date = Carbon::parse($check_date[0])->startOfDay();
                        $end_date = Carbon::parse($check_date[1])->endOfDay();
                    }else if(count($check_date) == 1){
                        if($check_date){
                            $start_date = date('Y-m-d');
                            $end_date = date('Y-m-d');
                        } else {
                            $start_date = Carbon::parse($request->date)->startOfDay();
                            $end_date = Carbon::parse($request->date)->endOfDay();
                        }
                    } else {
                        if(!$request->year) $year = date('Y');
                        else $year = $request->year;
                        if(!$request->month) $month = date('m');
                        else $month = $request->month;
                        
                        $start_date = Carbon::parse($year.'-'.$month.'-01')->startOfMonth();
                        $end_date = Carbon::parse($year.'-'.$month.'-01')->endOfMonth();
                    }
                }
                break;
            default:
                if(!$request->year) $year = date('Y');
                else $year = $request->year;
                if(!$request->month) $month = date('m');
                else $month = $request->month;

                $start_date = Carbon::parse($year.'-'.$month.'-01')->startOfMonth();
                $end_date = Carbon::parse($year.'-'.$month.'-01')->endOfMonth();
                break;
        }

        $data = $data->withCount([
            'violation as total_violation' => function($q) use($start_date, $end_date) {
                $q->whereBetween('created_at', [$start_date, $end_date]);
            }
        ])->withSum([
            'violation as total_score_violation' => function($q) use($start_date, $end_date) {
                $q->whereBetween('created_at', [$start_date, $end_date]);
            }
        ], 'score');

        if($request->data == "per_class"){
            $class = $request->class;
            $data->where('nama_rombel', $class);
        }else if($request->data == "per_grade"){
            $grade = $request->grade;
            $data = $data->where('tingkat_pendidikan', $grade);
        }

        return $this->violationService->getReportDataDatatableV2($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = $this->studentRepository->getAll();
        $violation_types = $this->violationTypeRepository->getAll();
        $data = $this->violationService->getPageData('violation', '', ['users' => $user, 'violation_types' =>$violation_types], [], 'Tambah Pelanggaran');
        return view('admin.pages.violation.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // check data student
        if(!isset($request->student_id) || !is_array($request->student_id)){
            return redirect()->back()->with("error","Data siswa tidak boleh ada yang kosong");
        }
        foreach($request->student_id as $key => $value){
            if(!$value) return redirect()->back()->with("error","Data siswa tidak boleh ada yang kosong");
        }
        
        // check data violation type
        if(!isset($request->violation_type_id) || !is_array($request->violation_type_id)){
            return redirect()->back()->with("error","Data pelanggaran tidak boleh ada yang kosong");
        }
        foreach($request->violation_type_id as $key => $value){
            if(!$value) return redirect()->back()->with("error","Data pelanggaran tidak boleh ada yang kosong");
        }

        $user = auth()->user();

        try{
            DB::beginTransaction();
            // foreach data student
            foreach($request->student_id as $student_id){
                // set default score
                $score = 0;
    
                // get data student from student_id
                $student = $this->studentRepository->getOneById($student_id);
    
              // get data violation_type outside the loop
                $violationTypes = $this->violationTypeRepository->getWhereIn("id",$request->violation_type_id);
    
                // foreach data violation_type
                foreach ($violationTypes as $violation_type) {
                    // set score violation
                    $scoreViolation = $violation_type->score;
    
                    // create data violation
                    $data = [
                        "student_id" => $student_id,
                        "violation_type_id" => $violation_type->id,
                        "score" => $scoreViolation,
                        "created_by" => $user->id
                    ];
    
                    // save data violation
                    $this->violationRepository->create($data);
    
                    // push score violation to total score
                    $score += $scoreViolation;
                }
    
                // update score violation student
                $student->score += $score;
                $student->save();
            }

            DB::commit();
        } catch(\Throwable $th){
            DB::rollBack();
            return redirect()->back()->with("error",$th->getMessage())->withInput();
        }

        return redirect()->route('violation.index')->with("success","Berhasil memberikan poin pelanggaran terhadap siswa");
    }

    public function report()
    {
        $tahun = \App\Models\Violation::selectRaw('YEAR(created_at) as tahun')->orderBy('tahun','ASC')->groupBy('tahun')->get();
        if(count($tahun) == 0) $tahun = [date('Y')];
        $group_data = [
            'years' => $tahun,
            'months' => $this->months,
            'grades' => $this->grades,
            'classes' => \App\Models\Student::select('nama_rombel')->orderBy('nama_rombel',"ASC")->groupBy('nama_rombel')->get()
        ];
        $data = $this->violationService->getPageData('violation-report', 'Laporan Pelanggaran',$group_data, [], "Laporan Presensi");
        return view('admin.pages.violation.report', $data);
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
        if(!$request->violation_type_id) return redirect()->back()->with("error","Pilih pelanggaran terlebih dahulu");
        if(!$request->student_id) return redirect()->back()->with("error","Pilih siswa terlebih dahulu");

        $violation = $this->violationRepository->getOneById($id);      
        if(!$violation) return redirect()->back()->with("error","Pelanggaran tidak ditemukan");
        
        $student = $this->studentRepository->getOneById($violation->student_id);
        if(!$student) return redirect()->back()->with("error","Siswa tidak ditemukan");

        $user = auth()->user();

        // update new score
        $student->score -= $violation->score;
        $student->save();
        
        // get data selected
        $selected_violation = $this->violationTypeRepository->getOneById($request->violation_type_id);
        $selected_student = $this->studentRepository->getOneById($request->student_id);
        
        // update violation
        $violation->violation_type_id = $request->violation_type_id;
        $violation->student_id = $request->student_id;
        $violation->score = $selected_violation->score;
        $violation->updated_by = $user->id;
        
        // update score new student
        $selected_student->score += $selected_violation->score;

        // save data
        $selected_student->save();
        $violation->save();

        return redirect()->route('violation.index')->with("success","Data pelanggaran berhasil dirubah");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $violation = $this->violationRepository->getOneById($id);      
        if(!$violation){
            return response()->json([
                "status" => "error",
                "message" => "Pelanggaran tidak ditemukan"
            ], 404);
        }

        $student = $this->studentRepository->getOneById($violation->student_id);
        if(!$student) {
                return response()->json([
                "status" => "error",
                "message" => "Siswa tidak ditemukan"
            ], 404);

        }

        // update new score
        $student->score -= $violation->score;
        $student->save();
        
        $violation->delete();

        return response()->json([
            "status" => "error",
            "message" => "Data pelanggaran berhasil dihapus permanent"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function softDestroy(string $id)
    {
        $violation = $this->violationRepository->getOneById($id);      
        if(!$violation){
            return response()->json([
                "status" => "error",
                "message" => "Pelanggaran tidak ditemukan"
            ], 404);
        }

        $student = $this->studentRepository->getOneById($violation->student_id);
        if(!$student) {
            return response()->json([
                "status" => "error",
                "message" => "Siswa tidak ditemukan"
            ], 404);
        }

        // update new score
        $student->score -= $violation->score;
        $student->save();
        
        $violation->deleted_at = now();
        $violation->save();

        return response()->json([
            "status" => "error",
            "message" => "Data pelanggaran berhasil dihapus"
        ], 200);
    }

    /**
     * Api for list violation
     * @GET
     */
    public function listViolation(Request $request){
        // set default get data per year
        if($request->year){
            $year = $request->year;
            $data = $this->violationRepository->getDataYears($year, ["student","violationType"]);
        } else {
            $data = $this->violationRepository->relationship(["student","violationType"]);
            $year = null;
        }
        
        // if data get per month
        if($request->month){
            if(!$year) $year = date('Y');
            $data = $this->violationRepository->getDataMonth($year, $request->month, ["student","violationType"]);
        }

        return response()->json([
            "message" => "Berhasil menampilkan data",
            "data" => $data
        ], 200);
    }

    /**
     * Api for data statistik violation
     * @GET
     */
    public function listViolationStatistik(Request $request){

        //student and violation type
        $student_id = $request->student_id;
        $violation_type_id = $request->violation_type_id;

        // set default get data per year
        if($request->year){
            // get data per year
            $year = $request->year;
            $data = $this->violationRepository->getDataYears($year, ["student","violationType"]);

            // filter data if student and violation has
            $data = $data->filter(function ($item) use ($student_id, $violation_type_id){
                $student = $student_id == null || $item->student_id == $student_id;
                $violation = $violation_type_id == null || $item->violation_type_id == $violation_type_id;

                return $student && $violation;
            });
            $data = $this->violationService->getDataStatistikYear($data);
        } else {
            $data = $this->violationRepository->relationship(["student","violationType"]);

            // filter data if student and violation has
            $data = $data->filter(function ($item) use ($student_id, $violation_type_id){
                $student = $student_id == null || $item->student_id == $student_id;
                $violation = $violation_type_id == null || $item->violation_type_id == $violation_type_id;

                return $student && $violation;
            });
            $data = $this->violationService->getDataStatistikYear($data);
            $year = null;
        }
        
        // if data get per month
        if($request->month){
            // get data per month
            if(!$year) $year = date('Y');
            $data = $this->violationRepository->getDataMonth($year, $request->month, ["student","violationType"]);

            // filter data if student and violation has
            $data = $data->filter(function ($item) use ($student_id, $violation_type_id){
                $student = $student_id == null || $item->student_id == $student_id;
                $violation = $violation_type_id == null || $item->violation_type_id == $violation_type_id;

                return $student && $violation;
            });
            $data = $this->violationService->getDataStatistikMonth($data, (int)$request->month, $year);
        }

        return response()->json([
            "message" => "Berhasil menampilkan data statistik",
            "data" => $data
        ], 200);
    }

    /**
     * Api get data student much violation
     * METHOD: GET
     */
    public function listMustStudent(Request $request)
    {
        $data = $this->violationRepository->relationship(["student"]);

        if($request->limit) $limit = intval($request->limit);
        else $limit = 0;

        $result = $this->violationService->getDataGroupStudent($data, "desc", $limit);
        
        return response()->json([
            "message" => "Berhasil mengambil data",
            "data" => $result
        ], 200);
    }
}
