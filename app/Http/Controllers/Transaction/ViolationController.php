<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Services\ViolationService;
use App\Repositories\{StudentRepository, ViolationTypeRepository};
use App\Repositories\MasterTransaction\ViolationRepository;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    private $violationService, $studentRepository, $violationTypeRepository, $violationRepository;

    public function __construct(ViolationService $violationService,
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
        if(!is_array($request->student_id)){
            return redirect()->back()->with("error","Siswa tidak boleh kosong");
        }

        // check data violation type
        if(!isset($request->violation_type_id)){
            return redirect()->back()->with("error","Tipe pelanggaran tidak boleh kosong");
        }

        // foreach data student
        foreach($request->student_id as $student_id){
            // set default score
            $score = 0;

            // get data student from student_id
            $student = $this->studentRepository->getOneById($student_id);

          // get data violation_type outside the loop
            $violation_type = $this->violationTypeRepository->getOneById($request->violation_type_id);

            // create data violation
            $data = [
                "student_id" => $student_id,
                "violation_type_id" => $violation_type->id,
                "score" => $violation_type->score
            ];

            // save data violation
            $this->violationRepository->create($data);

            // push score violation to total score
                $score += $violation_type->score;

            // update score violation student
            $student->score += $score;
            $student->save();
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
}
