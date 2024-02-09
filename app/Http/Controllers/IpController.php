<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IpController extends Controller
{
    public function changeIp(Request $request)
    {
        try{
            // $check = DB::select("SELECT * FROM mysql.user WHERE user = 'admin' and host = '" . $request->ip_public . "'");
            DB::statement("DELETE FROM mysql.user WHERE host <> 'localhost'");
    
            DB::statement("CREATE USER 'admin'@'" . $request->ip_public . "' IDENTIFIED BY 'password'");
            DB::statement("GRANT ALL PRIVILEGES ON *.* TO 'admin'@'" . $request->ip_public . "'");
            DB::statement("FLUSH PRIVILEGES");

            return redirect()->back()->with('success', 'IP Publik berhasil didaftarkan');
        }catch(\Throwable $th){
            return redirect()->back()->with('error', 'Kesalahan dalam mendaftarkan IP '. $th->getMessage());
        }

    }
}
