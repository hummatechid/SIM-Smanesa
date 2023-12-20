<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Repositories\{StudentRepository, ViolationTypeRepository};
use App\Repositories\MasterTransaction\ViolationRepository;
use App\Services\MasterTransaction\ViolationService;
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

        DB::beginTransaction();
        try{
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
                        "score" => $scoreViolation
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
