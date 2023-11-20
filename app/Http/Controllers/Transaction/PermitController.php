<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermitRequest;
use App\Repositories\MasterData\PermitRepository;
use App\Repositories\PenggunaRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermitController extends Controller
{
    private $penggunaRepository, $teacherRepository;
    private $permitRepository;

    public function __construct(
        PermitRepository $permitRepository,
        PenggunaRepository $penggunaRepository,
        TeacherRepository $teacherRepository
    )
    {
        $this->permitRepository = $permitRepository;
        $this->penggunaRepository = $penggunaRepository;
        $this->teacherRepository = $teacherRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(PermitRequest $request)
    {
        // validation data for request
        $validateData = $request->validated();

        // get data user
        $userAuth = Auth::user();

        // get data user from pengguna or teacher
        $user = $this->penggunaRepository->getOneById($userAuth->id);
        if(!$user) $user = $this->teacherRepository->getOneById($userAuth->id);

        // set user created
        $validateData["created_by"] = $user->id;
        $validateData["status"] = "pending";

        try{
            // store data 
            $this->permitRepository->create($validateData);

            return redirect()->route('permit.index')->with('success', "Berhasil membuat surat izin");
        }catch(\Throwable $th){
            return redirect()->back()->with("error",$th->getMessage())->withInput();
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
        // check have data permit or not
        $permit = $this->permitRepository->getOneById($id);

        if(!$permit) return redirect()->back()->with('error',"Data izin tidak ditemukan")->withInput();

        // check request status or not
        if(!$request->status) return redirect()->back()->with('error', 'Anda tidak mengirimkan sebuah tanggapan, mohon cek ulang')->withInput();

        // get data user
        $userAuth = Auth::user();

        // get data user from pengguna or teacher
        $user = $this->penggunaRepository->getOneById($userAuth->id);
        if(!$user) $user = $this->teacherRepository->getOneById($userAuth->id);

        //set data update
        $dataUpdate = [
            "status" => $request->status,
            "updated_by" => $user->id
        ];

        if($request->status == "accepted") $dataUpdate["accepted_by"] = $user->id;

        try{
            // store data 
            $permit->update($dataUpdate);

            return redirect()->route('permit.index')->with('success', "Berhasil memberikan tanggapan di surat izin");
        }catch(\Throwable $th){
            return redirect()->back()->with("error",$th->getMessage())->withInput();
        }
    }

    /**
     * Soft remove the specified resource from storage.
     */
    public function softDestroy(string $id)
    {
        // check have data permit or not
        $permit = $this->permitRepository->getOneById($id);

        if(!$permit) {
            return response()->json([
                "success" => false,
                "message" => "Surat izin tidak ditemukan"
            ], 404);
        }

        try{
            // delete data
            $this->permitRepository->softDelete($id);
            
            return response()->json([
                "success" => true,
                "message" => "Berhasil menghapus surat izin"
            ], 201);
        }catch(\Throwable $th){
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
        // check have data permit or not
        $permit = $this->permitRepository->getOneById($id);
        if(!$permit) $permit = $this->permitRepository->getOneById($id, true);

        if(!$permit) {
            return response()->json([
                "success" => false,
                "message" => "Surat izin tidak ditemukan"
            ], 404);
        }

        try{
            // delete data
            $this->permitRepository->softDelete($id);
            
            return response()->json([
                "success" => true,
                "message" => "Berhasil menghapus surat izin permanent"
            ], 201);
        }catch(\Throwable $th){
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }
}
