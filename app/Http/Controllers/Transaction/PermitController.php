<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermitRequest;
use App\Notifications\PermitNotification;
use App\Repositories\MasterTransaction\PermitRepository;
use App\Repositories\PenggunaRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\StudentRepository;
use App\Repositories\UserRepository;
use App\Services\MasterTransaction\PermitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NotificationChannels\Fcm\FcmMessage;

class PermitController extends Controller
{
    private $penggunaRepository, $teacherRepository, $permitRepository, $studentRepository, $userRepository;
    private $permitService;

    public function __construct(
        PermitService $permitService,
        PermitRepository $permitRepository,
        PenggunaRepository $penggunaRepository,
        TeacherRepository $teacherRepository,
        StudentRepository $studentRepository,
        UserRepository $userRepository,
    )
    {
        $this->permitService = $permitService;
        $this->permitRepository = $permitRepository;
        $this->penggunaRepository = $penggunaRepository;
        $this->teacherRepository = $teacherRepository;
        $this->studentRepository = $studentRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->permitService->getPageData('permit-list', '', [], null, 'Izin');
        return view('admin.pages.permit.index', $data);
    }

    public function getDatatablesData(Request $request)
    {
        return $this->permitService->getDataDatatable($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->permitService->getPageData('permit-add', '', [
            'students' => $this->studentRepository->getAll()
        ], [], "Tambah Izin");

        return view('admin.pages.permit.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermitRequest $request)
    {
        // check validaiton 
        if(!is_array($request->student_id)) return redirect()->back()->with("error","Data siswa tidak boleh kosong")->withInput();
        foreach($request->student_id as $key => $value){
            if(!$value) return redirect()->back()->with("error","Data siswa tidak boleh kosong")->withInput();
        }

        // validation data for request
        $validateData = $request->validated();

        // get data user
        $user = Auth::user();

        // set user created
        $validateData["created_by"] = $user->id;
        $validateData["status"] = "pending";

        $pimpinan = $this->userRepository->getAllUserInOneRole("pimpinan", "mobile");

        try{
            // set message notification
            $title = "Pengajuan Izin Keluar";
            $message = "Siswa / siswi anda mengajukan permintaan untuk keluar dari lingkungan sekolah !";
            $type = "basic";

            // store data 
            // foreach($request->student_id as $student_id){
            //     $validateData["student_id"] = $student_id;
            //     $this->permitRepository->create($validateData);
            // }

            // send message
            foreach($pimpinan as $pimpin){
                while (count($request->student_id)){
                    $notification_id = $pimpin->device_token;
                    $id = $pimpin->id;
                    // dd($pimpinan);

                    $pimpin->notify(new PermitNotification());

                    // Membuat objek FcmMessage
                    // $fcmMessage = FcmMessage::create()
                    //     ->token($notification_id)
                    //     ->data(['title' => $title, 'body' => $message, 'id' => $id, 'type' => $type]);

                    // Membuat notifikasi dan mengirimnya
                    // $permitNotification = new PermitNotification();
                    // $permitNotification->toFcm(null);

                    // Menggunakan laravel-firebase:^3.0, menyertakan proyek Firebase (optional)
                    // $permitNotification->fcmProject(null, $fcmMessage);
                }
            }

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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // check have data permit or not
        $permit = $this->permitRepository->getOneById($id);

        if(!$permit) return redirect()->back()->with('error',"Data izin tidak ditemukan");

        // check request status or not
        if(!$request->status) return redirect()->back()->with('error', 'Anda tidak mengirimkan sebuah tanggapan, mohon cek ulang')->withInput();

        // get data user
        $user = Auth::user();

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

    /**
     * Update data when selected.
     */
    public function updateManyData(Request $request)
    {
        // array save data
        $data = [];

        // check request status or not
        if(!$request->status) return response()->json([
            "status" => "error",
            "message" => "Status penerimaan harus diisi"
        ], 400);

        // check id is array data
        if(is_array($request->selected_id)){
            // foreach array id
            foreach($request->selected_id as $id){
                // check have data permit or not
                $permit = $this->permitRepository->getOneById($id);
    
                // check data has or notfoung
                if(!$permit) continue;
                else $data[] = $id;
    
                // get data user
                $user = Auth::user();
    
                //set data update
                $dataUpdate = [
                    "status" => $request->status,
                    "updated_by" => $user->id
                ];
    
                if($request->status == "accepted") $dataUpdate["accepted_by"] = $user->id;

                // update data
                $permit->update($dataUpdate);
            }
            $sukses = count($data);
            $error = count($request->selected_id) - $sukses;
            return response()->json([
                "status" => "error",
                "message" => "Berhasil memberikan tanggapan di surat izin, dengan ". $sukses ." sukses, dan ". $error ." error"
            ], 200); 
        }else {
            // if data id not array
            return response()->json([
                "status" => "error",
                "message" => "Tidak ada surat izin yang dipilih"
            ], 400);
        }
    }


    /**
     * Api For Moble
     * Permit with status
     *  
     * */
    public function listToday(Request $request)
    { 
        try{
            if($request->status){
                $list = $this->permitRepository->listTodayWithCondition("status",$request->status);
            } else {
                $list = $this->permitRepository->listToday();
            }
    
            if($request->sort) $list = $list->sortByDesc($request->sort);
            if($request->limit) $list = $list->take($request->limit);
    
            return response()->json([
                "status" => "success",
                "messages" => "Berhasil memuat data surat izin",
                "data" => $list
            ], 200);
        }catch(\Throwable $th){
            return response()->json([
                "status" => "error",
                "messages" => $th->getMessage(),
                "data" => null
            ], 500);
        }
    }

    /**
     * Api For Moble
     * Permit detail
     *  
     * */
    public function detailList(Request $request, string $id)
    { 
        try{
            //set relationship;
            $relation = ["student"];
            $permit = $this->permitRepository->relationship($relation, "first");

            if(!$permit) {
                return response()->json([
                    "status" => "error",
                    "messages" => "Surat izin tidak ditemukan",
                    "data" => null
                ], 404);
            }

            // get data user created permit
            $user_created = $this->penggunaRepository->getOneById($permit->created_by);
            if(!$user_created) $user_created = $this->teacherRepository->getOneById($permit->created_by);

            // check data user created
            if(!$user_created) {
                return response()->json([
                    "status" => "error",
                    "messages" => "Data user yang membuat tidak ditemukan",
                    "data" => null
                ], 404);
            } else {
                $permit->user_created = $user_created;
            }

            // get data user acepted
            $user_accepted = $this->penggunaRepository->getOneById($permit->created_by);
            $permit->user_accepted = $user_accepted;
    
            return response()->json([
                "status" => "success",
                "messages" => "Berhasil memuat data surat izin",
                "data" => $permit
            ], 200);
        }catch(\Throwable $th){
            return response()->json([
                "status" => "error",
                "messages" => $th->getMessage(),
                "data" => null
            ], 500);
        }
    }

    /**
     * Api For Mobile
     * Student detail list permit
     *  
     * */
    public function studentList(Request $request)
    { 
        // get data student
        $student = $this->studentRepository->getOneById($request->student_id);
        if(!$student) {
            return response()->json([
                "status" => "error",
                "messages" => "Siswa tidak ditemukan",
                "data" => null
            ], 404);
        }

        try{
            
            //set relationship get data permit
            $relation = [];
            $permit = $this->permitRepository->oneConditionOneRelation("student_id",$request->student_id, $relation, "get");
            
            $student->permit = $permit;

            return response()->json([
                "status" => "success",
                "messages" => "Berhasil memuat data surat izin",
                "data" => $student
            ], 200);
        }catch(\Throwable $th){
            return response()->json([
                "status" => "error",
                "messages" => $th->getMessage(),
                "data" => null
            ], 500);
        }
    }

    /**
     * Api For Mobile
     * Update status permit
     * 
     */
    public function updateStatus(Request $request)
    {
        // check request id
        if(!$request->id) {
            return response()->json([
                "status" => "error",
                "messages" => "Dimohon check ulang pengiriman id",
                "data" => null
            ], 400);
        }

        // check request status
        $status = ["accepted","rejected","back"];
        if(!$request->status) {
            return response()->json([
                "status" => "error",
                "messages" => "Dimohon check ulang pengiriman status",
                "data" => null
            ], 400);
        }

        // check status has or not
        if(!in_array($request->status, $status)) {
            return response()->json([
                "status" => "error",
                "messages" => "Status tidak terdaftar, silahkan cek ulang",
                "data" =>  $request->status
            ], 400);
        }

        // check data user
        if(!$request->user_id) {
            return response()->json([
                "status" => "error",
                "messages" => "Data user harus dikirim, silahkan cek ulang.",
                "data" => null
            ], 400);
        }

        $data = [
            "status" => $request->status,
            "updated_by" => $request->user_id
        ];

        if($request->status == "accepted") $data["accepted_by"] = $request->user_id;

        $permit = $this->permitRepository->getOneById($request->id);

        // check permit
        if(!$permit){
            return response()->json([
                "status" => "error",
                "messages" => "Surat izin tidak ditemukan",
                "data" => null
            ], 404);   
        }

        // update data
        $permit->update($data);

        return response()->json([
            "status" => "success",
            "messages" => "Berhasil memberikan tanggapan",
            "data" => $permit
        ], 200);
    }

    public function showAccListPage()
    {
        return view('admin.pages.permit.acc');
    }
     
}
