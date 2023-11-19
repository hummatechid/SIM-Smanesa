<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\ViolationTypeRequest;
use Illuminate\Http\Request;
use App\Models\ViolationType;
use App\Repositories\ViolationTypeRepository;
use App\Services\MasterData\ViolationTypeService;

class ViolationTypeController extends Controller
{
    private $violationTypeModel, $violationTypeService;

    public function __construct(ViolationTypeRepository $violationTypeModel, ViolationTypeService $violationTypeService)
    {
        $this->violationTypeModel = $violationTypeModel;
        $this->violationTypeService = $violationTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataPage = $this->violationTypeService->getPageData();

        return view('admin.pages.master-data.violation-type.index', $dataPage);
    }

    public function getDatatablesData()
    {
        return $this->violationTypeService->getDataDatatable();
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
    public function store(ViolationTypeRequest $request)
    {
        // validate data request
        $validateData = $request->validated();
        
        try {
            // store data
            $this->violationTypeModel->create($validateData);
            return redirect()->route('violation-type.index')->with('success', "Tipe pelanggaran berhasil dibuat");
        } catch(\Throwable $th){
            return redirect()->back()->with('error',$th->getMessage());
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
    public function update(ViolationTypeRequest $request, string $id)
    {
        // validate data request
        $validateData = $request->validated();
        
        $violation = $this->violationTypeModel->getOneById($id);

        if(!$violation) return redirect()->back()->with('error', "Data tipe pelanggaran tidak ditemukan");

        try {
            // store data
            $this->violationTypeModel->update($id,$validateData);
            return redirect()->route('violation-type.index')->with('success', "Tipe pelanggaran berhasil di update");
        } catch(\Throwable $th){
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    /**
     * Soft remove the specified resource from storage.
     */
    public function softDestroy(string $id)
    {
        $violation = $this->violationTypeModel->getOneById($id);

        if(!$violation) {
            return response()->json([
                "success" => false,
                "message" => "Data tipe pelanggaran tidak ditemukan"
            ], 404);
        }
        
        try {
            // store data
            $this->violationTypeModel->softDelete($id);
            return response()->json([
                "success" => true,
                "message" => "Tipe pelanggaran berhasil di hapus"
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
        $violation = $this->violationTypeModel->getOneById($id);

        if(!$violation){
            return response()->json([
                "success" => false,
                "message" => "Data tipe pelanggaran tidak ditemukan"
            ], 404);
        }

        try {
            // store data
            $this->violationTypeModel->delete($id);
            return response()->json([
                "success" => true,
                "message" => "Tipe pelanggaran berhasil di hapus permanent"
            ], 201);
        } catch(\Throwable $th){
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }
}
