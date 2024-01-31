<?php

namespace App\Http\Controllers\Master;

use App\Helpers\UploadImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Http\Requests\UserRequest;
use App\Repositories\TeacherRepository;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use App\Services\MasterData\TeacherService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    use UploadImage;

    private $teacherService, $userService;
    private $teacherRepository, $userRepository, $roleRepository;

    public function __construct(
        TeacherService $teacherService,
        UserService $userService,
        TeacherRepository $teacherRepository,
        UserRepository $userRepository,
        RoleRepository $roleRepository
    ) {
        $this->teacherService = $teacherService;
        $this->userService = $userService;
        $this->teacherRepository = $teacherRepository;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->teacherService->getPageData('teacher-list', "", [], null, "GTK");
        return view('admin.pages.master-data.teacher.index', $data);
    }

    public function getDatatablesData(Request $request)
    {
        dd($request->all(),$request->status, $request->status != "semua");
        if($request->status && $request->status != "semua") {
            if($request->status == 1) $status = true;
            else $status = false;
            $data = $this->teacherRepository->OneConditionOneRelation("is_dapodik", $status, ["user" => function ($q) {
                $q->with("roles");
            }]);
        } else {
            $data = $this->teacherRepository->relationship(["user" => function ($q) {
                $q->with("roles");
            }]);
        }

        if ($request->role == "pimpinan") {
            $data = $data->filter(function ($item) {
                return count($item->user->roles) > 1;
            });
        } else if ($request->role == "non-pimpinan") {
            $data = $data->filter(function ($item) {
                return count($item->user->roles) == 1;
            });
        }

        return $this->teacherService->getDataDatatable($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data_role = $this->roleRepository->getCustomColumnValue('name', 'guru');
        $data = $this->teacherService->getPageData('teacher-add', '', ['data_role' => $data_role], [], 'Tambah GTK');

        return view('admin.pages.master-data.teacher.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeacherRequest $teacherRequest, UserRequest $userRequest)
    {
        // validate data request pengguna
        $validateDataTeacher = $teacherRequest->validated();

        // validate data request user
        $validateDataUser = $userRequest->validated();

        try {
            DB::beginTransaction();

            // set image
            $path = 'images/teacher/';
            if (!is_dir($path)) {
                Storage::makeDirectory($path);
            }
            
            if ($teacherRequest->hasFile('photo')) {
                $file = $teacherRequest->file('photo');
                $fileData = $this->uploads($file, $path);
                $validateDataTeacher["photo"] = $fileData["filePath"] . "." . $fileData["fileType"];
            }

            // get roles guru
            $roles = $this->userRepository->getRole("name", "guru");

            // store data user
            $validateDataUser["password"] = bcrypt($validateDataUser["password"]);
            $validateDataUser["role_id"] = $roles->id;
            $user = $this->userRepository->create($validateDataUser);

            // set user_id
            $validateDataTeacher["user_id"] = $user->id;
            $validateDataTeacher["is_dapodik"] = 0;

            // store data pengguna
            $this->teacherRepository->create($validateDataTeacher);

            // asign role user
            $user->assignRole("guru");

            DB::commit();
            return redirect()->route('teacher.index')->with('success', "Data guru berhasil dibuat");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teacher = $this->teacherRepository->getOneById($id);
        $data = $this->teacherService->getPageData('teacher-list', '', [
            'teacher' => $teacher,
        ], [], "Detail");
        return view('admin.pages.master-data.teacher.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher = $this->teacherRepository->byIdWithRole($id);
        $data_role = $this->roleRepository->getCustomColumnValue('name', 'guru');
        $data = $this->teacherService->getPageData('teacher-list', '', [
            'teacher' => $teacher,
            'data_role' => $data_role
        ], [
            "Detail" => route('teacher.show', $id)
        ], "Ubah Data");
        return view('admin.pages.master-data.teacher.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherRequest $teacherRequest, UserRequest $userRequest, string $id)
    {
        // validate data request teacher
        $validateDataPengguna = $teacherRequest->validated();

        // validate data request user
        $validateDataUser = $userRequest->validated();

        // check has data or not
        $pengguna = $this->teacherRepository->getOneById($id);

        if (!$pengguna) return redirect()->back()->with('error', 'Teacher tidak ditemukan');

        try {
            DB::beginTransaction();

            // set image
            $path = 'images/teacher/';
            // !is_dir($path) &&
            //     mkdir($path, 0777, true);
            if (!is_dir($path)) {
                Storage::makeDirectory($path);
            }
            if ($teacherRequest->photo) {
                if ($pengguna->photo) $this->deleteImage($pengguna->photo);
                $file = $teacherRequest->file('photo');
                $fileData = $this->uploads($file, $path);
                $validateDataPengguna["photo"] = $fileData["filePath"] . "." . $fileData["fileType"];
            }

            // store data pengguna
            $this->teacherRepository->update($id, $validateDataPengguna);

            // store data user
            $user = $this->userRepository->update($pengguna->user_id, $validateDataUser);

            DB::commit();
            return redirect()->route('teacher.index')->with('success', "Data guru berhasil di rubah");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing password the specified resource.
     */
    public function editPassword(string $id)
    {
        $teacher = $this->teacherRepository->byIdWithRole($id);
        $data_role = $this->roleRepository->getCustomColumnValue('name', 'guru');
        $data = $this->teacherService->getPageData('teacher-list', '', [
            'teacher' => $teacher,
            'data_role' => $data_role
        ], [
            "Detail" => route('teacher.show', $id),
        ], "Ubah Password");
        return view('admin.pages.master-data.teacher.edit-password', $data);
    }

    /**
     * Method PATCH for update password.
     */
    public function updatePassword(Request $request, string $id)
    {
        $teacher = $this->teacherRepository->getOneById($id);

        $update = $this->userService->changePassword(
            $teacher->user_id,
            $request->password,
            $request->old_password,
            $request->password_confirmation
        );

        if ($request->type == "api") {
            return response()->json($update);
        } else {
            if ($update["success"] == true) {
                return redirect()->route('teacher.show', $id)->with("success", $update["message"]);
            } else {
                return redirect()->back()->with("error", $update["message"]);
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function softDestroy(string $id)
    {
        // check data pengguna
        $teacher = $this->teacherRepository->getOneById($id);

        if (!$teacher) {
            return response()->json([
                "success" => false,
                "message" => "Data guru tidak ditemukan"
            ], 404);
        }

        // check data user
        $user = $this->userRepository->getOneById($teacher->user_id);

        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "Data user tidak ditemukan"
            ], 404);
        }

        try {
            // delete data
            $this->teacherRepository->softDelete($id);
            $this->userRepository->softDelete($user->id);
            return response()->json([
                "success" => true,
                "message" => "Data guru berhasil di hapus"
            ], 201);
        } catch (\Throwable $th) {
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
        $teacher = $this->teacherRepository->getOneById($id);

        if (!$teacher) {
            return response()->json([
                "success" => false,
                "message" => "Data guru tidak ditemukan"
            ], 404);
        }

        // check data user
        $user = $this->userRepository->getOneById($teacher->user_id);

        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "Data user tidak ditemukan"
            ], 404);
        }

        try {
            // delete image
            if ($teacher->photo) $this->deleteImage($teacher->photo);

            // delete data
            $this->teacherRepository->delete($id);
            $this->userRepository->delete($user->id);
            return response()->json([
                "success" => true,
                "message" => "Data guru berhasil di hapus permnanent"
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }

    /**
     * sync student from dapodik
     *
     * @return JsonResponse
     */
    public function syncTeacher(): JsonResponse
    {
        $npsn = config('app.web_service_npsn');
        $key = config('app.web_service_key');
        $teachers = $this->teacherService->fetchTeacherFromDapodik($npsn, $key);

        if ($teachers == null) {
            return response()->json([
                'message' => $teachers
            ], 500);
        }

        $this->teacherService->handleSyncTeacher($teachers['rows']);

        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * assign role for user teacher.
     */
    public function assignRole(Request $request, string $id)
    {
        $role = $request->role ?? "pimpinan";

        $teacher = $this->teacherRepository->getOneById($id);
        $user = $this->userRepository->getOneById($teacher->user_id);
        $user->assignRole($role);

        return redirect()->back()->with("success", "Berhasil menambahkan " . $teacher->full_name . " ke " . $role);
    }

    /**
     * assign role for user teacher.
     */
    public function removeRole(Request $request, string $id)
    {
        $role = $request->role ?? "pimpinan";

        $teacher = $this->teacherRepository->getOneById($id);
        $user = $this->userRepository->getOneById($teacher->user_id);
        $user->removeRole($role);


        return redirect()->back()->with("success", "Berhasil mengeluarkan " . $teacher->full_name . " dari " . $role);
    }
}
