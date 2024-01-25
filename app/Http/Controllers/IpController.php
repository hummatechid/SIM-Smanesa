<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IpController extends Controller
{
    public function changeIp(Request $request)
    {
        $check = DB::statement("SELECT count(*) FROM mysql.user WHERE user = 'admin' and host = '" . $request->ip_public . "'");

        if ($check == 0) {
            DB::statement("CREATE USER 'admin'@'" . $request->ip_public . "' IDENTIFIED BY 'password'");
            DB::statement("GRANT ALL PRIVILEGES ON *.* TO 'admin'@'" . $request->ip_public . "'");
            DB::statement("FLUSH PRIVILEGES");
        }

        return redirect()->back();
    }
}
