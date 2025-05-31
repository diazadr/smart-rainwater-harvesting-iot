<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;

class DashboardController extends Controller
{
    public function index() {
        $devices = Device::all();

        return view('dashboard.index', [
            "devices" => $devices,
        ]);
    }
}
