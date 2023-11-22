<?php

namespace App\Http\Controllers\Master;

use App\Helpers\UploadImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\PenggunaRequest;
use App\Http\Requests\UserRequest;
use App\Repositories\PenggunaRepository;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use App\Services\MasterData\PenggunaService;
use Illuminate\Support\Facades\DB;

class PenggunaController extends Controller
{
    use UploadImage;

    private $penggunaService;
    private $penggunaRepository, $userRepository, $roleRepository;

    public function __construct(
        PenggunaService $penggunaService,
        PenggunaRepository $penggunaRepository,
        UserRepository $userRepository,
        RoleRepository $roleRepository
    )
    {
        $this->penggunaService = $penggunaService;
        $this->penggunaRepository = $penggunaRepository;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->penggunaService->getPageData("user-list", "List Pengguna");

        return view("admin.pages.master-data.user.index", $data);
    }

    public function getDatatablesData()
    {
        return $this->penggunaService->getDataDatatable();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data_role = $this->roleRepository->getWhereNotIn('name', ['guru']);
        $data = $this->penggunaService->getPageData('user-add', 'Tambah Pengguna', ['data_role' => $data_role]);

        return view('admin.pages.master-data.user.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PenggunaRequest $requestPengguna, UserRequest $userRequest)
    {
        // validate data request pengguna
        $validateDataPengguna = $requestPengguna->validated();
         
        // validate data request user
        $validateDataUser = $userRequest->validated();
        
        // check role has been has or not
        $role = $this->userRepository->getRole('id',$userRequest->role_id);

        if(!$role) return redirect()->back()->with('error', 'Role tidak ditemukan')->withInput();

        try {
            DB::beginTransaction();

            // storage photo
            $path = storage_path('images/pengguna/');
            !is_dir($path) &&
            mkdir($path, 0777, true);
            if($requestPengguna->photo) {
                $file = $requestPengguna->file('photo');
                $fileData = $this->uploads($file,$path);
                $validateDataPengguna["photo"] = $fileData["filePath"].$fileData["fileType"];
            }

            // store data user
            $validateDataUser["password"] = bcrypt($validateDataUser["password"]); 
            $user = $this->userRepository->create($validateDataUser);

            // set user_id
            $validateDataPengguna["user_id"] = $user->id;

            // store data pengguna
            $this->penggunaRepository->create($validateDataPengguna);
            
            // asign role user
            $user->assignRole($role->name);

            DB::commit();
            return redirect()->route('pengguna.index')->with('success', "Data pengguna berhasil dibuat");
        } catch(\Throwable $th){
            DB::rollBack();
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
    public function update(PenggunaRequest $requestPengguna, UserRequest $userRequest, string $id)
    {
        // validate data request pengguna
        $validateDataPengguna = $requestPengguna->validated();
         
        // validate data request user
        $validateDataUser = $userRequest->validated();

        // check has data or not
        $pengguna = $this->penggunaRepository->getOneById($id);

        if(!$pengguna) return redirect()->back()->with('error', 'Pengguna tidak ditemukan')->withInput();
        
        // check role has been has or not
        $role = $this->userRepository->getRole('id',$userRequest->role_id);

        if(!$role) return redirect()->back()->with('error', 'Role tidak ditemukan')->withInput();

        try {
            DB::beginTransaction();

            // set image
            $path = storage_path('images/pengguna/');
            !is_dir($path) &&
            mkdir($path, 0777, true);
            if($requestPengguna->photo) {
                $this->deleteImage($pengguna->photo);
                $file = $requestPengguna->file('photo');
                $fileData = $this->uploads($file,$path);
                $validateDataPengguna["photo"] = $fileData["filePath"].$fileData["fileType"];
            }
            
            // store data pengguna
            $this->penggunaRepository->update($id, $validateDataPengguna);

            // store data user
            if($validateDataUser["password"]) $validateDataUser["password"] = bcrypt($validateDataUser["password"]); 
            $user = $this->userRepository->update($pengguna->user_id, $validateDataUser);

            // asign role user
            $user->assignRole($role->name);

            DB::commit();
            return redirect()->route('pengguna.index')->with('success', "Data pengguna berhasil di rubah");
        } catch(\Throwable $th){
            DB::rollBack();
            return redirect()->back()->with('error',$th->getMessage())->withInput();
        }
    }

    /**
     * Soft remove the specified resource from storage.
     */
    public function softDestroy(string $id)
    {
        // check data pengguna
        $pengguna = $this->penggunaRepository->getOneById($id);

        if(!$pengguna) {
            return response()->json([
                "success" => false,
                "message" => "Data pengguna tidak ditemukan"
            ], 404);
        }

        // check data user
        $user = $this->userRepository->getOneById($pengguna->user_id);

        if(!$user) {
            return response()->json([
                "success" => false,
                "message" => "Data user tidak ditemukan"
            ], 404);
        }
        
        try {

            // delete data
            $this->penggunaRepository->softDelete($id);
            $this->userRepository->softDelete($user->id);
            return response()->json([
                "success" => true,
                "message" => "Pengguna berhasil di hapus"
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
        // check data pengguna
        $pengguna = $this->penggunaRepository->getOneById($id);
        if(!$pengguna) $pengguna = $this->penggunaRepository->getOneById($id, true);

        if(!$pengguna) {
            return response()->json([
                "success" => false,
                "message" => "Data pengguna tidak ditemukan"
            ], 404);
        }

        // check data user
        $user = $this->userRepository->getOneById($pengguna->user_id);

        if(!$user) {
            return response()->json([
                "success" => false,
                "message" => "Data user tidak ditemukan"
            ], 404);
        }
        
        try {
            // delete image
            if($pengguna->photo) $this->deleteImage($pengguna->photo);

            // delete data
            $this->penggunaRepository->delete($id);
            $this->userRepository->delete($user->id);
            return response()->json([
                "success" => true,
                "message" => "Pengguna berhasil di hapus Permanent"
            ], 201);
        } catch(\Throwable $th){
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }
}
