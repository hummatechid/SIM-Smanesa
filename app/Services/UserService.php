<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService {

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    public function changePassword(string $id, string $new_password, string $old_password, string $password_confirm): array
    {
        $user = $this->repository->getOneById($id);
        // check user
        if(!$user) return ["success" => false, "message" => "User tidam ditemukan", "code" => 404];
        
        // check old password
        if(!$old_password || $old_password == "") return ["success" => false, "message" => "Password lama harus diisi", "code" => 400];
        
        // check new password
        if(!$new_password || $new_password == "") return ["success" => false, "message" => "Password baru harus diisi", "code" => 400];
        
        // check new password and password confirm
        if($new_password != $password_confirm) return ["success" => false, "message" => "Password baru tidak sama dengan password konfirmasi", "code" => 400];
        
        // check old password and password user
        if(!Hash::check($old_password, $user->password)) return ["success" => false, "message" => "Password lama salah, silahkan cek ulang", "code" => 400];

        $user->update([
            "password" => bcrypt($new_password)
        ]);
        return ["success" => true, "message" => "Berhasil merubah password", "code" => 200];
    }

}