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
use App\Services\UserService;
use Illuminate\Support\Facades\DB;

class PenggunaController extends Controller
{
    use UploadImage;

    private $penggunaService, $userService;
    private $penggunaRepository, $userRepository, $roleRepository;

    public function __construct(
        PenggunaService $penggunaService,
        UserService $userService,
        PenggunaRepository $penggunaRepository,
        UserRepository $userRepository,
        RoleRepository $roleRepository
    )
    {
        $this->penggunaService = $penggunaService;
        $this->userService = $userService;
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
            $path = 'images/pengguna/';
            !is_dir($path) &&
            mkdir($path, 0777, true);
            if($requestPengguna->photo) {
                $file = $requestPengguna->file('photo');
                $fileData = $this->uploads($file,$path);
                $validateDataPengguna["photo"] = $fileData["filePath"].".".$fileData["fileType"];
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
            return redirect()->route('user.index')->with('success', "Data user berhasil dibuat");
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
        $user = $this->penggunaRepository->getOneById($id);
        $data = $this->penggunaService->getPageData('user-list', '', [
            'user' => $user,
        ]);
        
        return view('admin.pages.master-data.user.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = $this->penggunaRepository->byIdWithRole($id);
        $data_role = $this->roleRepository->getWhereNotIn('name', ['guru']);
        $data = $this->penggunaService->getPageData('user-list', '', [
            'user' => $user,
            'data_role' => $data_role
        ]);
        return view('admin.pages.master-data.user.edit', $data);
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

        if(!$pengguna) return redirect()->back()->with('error', 'User tidak ditemukan')->withInput();
        
        // check role has been has or not
        $role = $this->userRepository->getRole('id',$userRequest->role_id);

        if(!$role) return redirect()->back()->with('error', 'Role tidak ditemukan')->withInput();

        try {
            DB::beginTransaction();

            // set image
            $path = 'images/pengguna/';
            !is_dir($path) &&
            mkdir($path, 0777, true);
            if($requestPengguna->photo) {
                if($pengguna->photo) $this->deleteImage($pengguna->photo);
                $file = $requestPengguna->file('photo');
                $fileData = $this->uploads($file,$path);
                $validateDataPengguna["photo"] = $fileData["filePath"].".".$fileData["fileType"];
            }
            
            // store data pengguna
            $this->penggunaRepository->update($id, $validateDataPengguna);

            // store data user
            $user = $this->userRepository->update($pengguna->user_id, $validateDataUser);

            // asign role user
            $user->assignRole($role->name);

            DB::commit();
            return redirect()->route('user.index')->with('success', "Data user berhasil di rubah");
        } catch(\Throwable $th){
            DB::rollBack();
            return redirect()->back()->with('error',$th->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editPassword(string $id)
    {
        $user = $this->penggunaRepository->byIdWithRole($id);
        $data_role = $this->roleRepository->getWhereNotIn('name', ['guru']);
        $data = $this->penggunaService->getPageData('user-list', '', [
            'user' => $user,
            'data_role' => $data_role
        ]);
        return view('admin.pages.master-data.user.edit-password', $data);
    }

    /**
     * Method PATCH for update password.
     */
    public function updatePassword(Request $request, string $id)
    {
        // set data for one data
        $pengguna = $this->penggunaRepository->getOneById($id);
        if(!$pengguna){
            $pengguna = $this->userRepository->getOneById($id);
            $pengguna->user_id = $id;
        }

        $update = $this->userService->changePassword(
            $pengguna->user_id,
            $request->password,
            $request->old_password,
            $request->password_confirmation
        );

        if($request->type == "api"){
            return response()->json($update);
        } else {
            if($update["success"] == true){
                return redirect()->route('teacher.show', $id)->with("success", $update["message"]);
            } else {
                return redirect()->back()->with("error", $update["message"]);
            }
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
                "message" => "Data user tidak ditemukan"
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
                "message" => "User berhasil di hapus"
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
                "message" => "Data user tidak ditemukan"
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
                "message" => "User berhasil di hapus Permanent"
            ], 201);
        } catch(\Throwable $th){
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }
}
