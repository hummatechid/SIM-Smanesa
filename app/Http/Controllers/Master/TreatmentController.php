<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\TreatmentRequest;
use App\Repositories\TreatmentRepository;
use App\Services\MasterData\TreatmentService;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    private $treatmentRepository, $treatmentService;

    public function __construct(TreatmentRepository $treatmentRepository, TreatmentService $treatmentService)
    {
        $this->treatmentRepository = $treatmentRepository;
        $this->treatmentService = $treatmentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataPage = $this->treatmentService->getPageData();

        return view('admin.pages.master-data.treatment.index', $dataPage);
    }

    public function getDatatablesData()
    {
        return $this->treatmentService->getDataDatatable();
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
    public function store(TreatmentRequest $request)
    {
        // validate data request
        $validateData = $request->validated();
        
        try {
            // store data
            $this->treatmentRepository->create($validateData);
            return redirect()->route('treatment.index')->with('success', "Jenis tindak lanjut berhasil dibuat");
        } catch(\Throwable $th){
            return redirect()->back()->with('error',$th->getMessage())->withInput();
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
    public function update(TreatmentRequest $request, string $id)
    {
        // validate data request
        $validateData = $request->validated();
        
        $violation = $this->treatmentRepository->getOneById($id);

        if(!$violation) return redirect()->back()->with('error', "Data jenis tindak lanjut tidak ditemukan")->withInput();

        try {
            // store data
            $this->treatmentRepository->update($id,$validateData);
            return redirect()->route('treatment.index')->with('success', "Jenis tindak lanjut berhasil di update");
        } catch(\Throwable $th){
            return redirect()->back()->with('error',$th->getMessage())->withInput();
        }
    }

    /**
     * Soft remove the specified resource from storage.
     */
    public function softDestroy(string $id)
    {
        $violation = $this->treatmentRepository->getOneById($id);

        if(!$violation) {
            return response()->json([
                "success" => false,
                "message" => "Data jenis tindak lanjut tidak ditemukan"
            ], 404);
        }
        
        try {
            // store data
            $this->treatmentRepository->softDelete($id);
            return response()->json([
                "success" => true,
                "message" => "Jenis tindak lanjut berhasil di hapus"
            ], 201);
        } catch(\Throwable $th){
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $treatment = $this->treatmentRepository->getOneById($id);
        if(!$treatment) $treatment = $this->treatmentRepository->getOneById($id,true);

        if(!$treatment){
            return response()->json([
                "success" => false,
                "message" => "Data jenis tindak lanjut tidak ditemukan"
            ], 404);
        }

        try {
            // store data
            $this->treatmentRepository->delete($id);
            return response()->json([
                "success" => true,
                "message" => "Jenis tindak lanjut berhasil di hapus permanent"
            ], 201);
        } catch(\Throwable $th){
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }
}