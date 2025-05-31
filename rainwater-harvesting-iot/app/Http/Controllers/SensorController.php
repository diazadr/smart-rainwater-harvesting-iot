<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Sensor;

class SensorController extends Controller
{


public function index(Request $request)
{
    $query = Sensor::query();

    if ($request->has('filter') && $request->filter != '') {
        $query->where('nama_sensor', $request->filter);
    }

    $sensor = $query->latest()->paginate(10)->withQueryString();

    return view('sensor.index', compact('sensor'));
}


    public function create()
    {
        return view('sensor.create');
    }

    public function edit($id)
    {
        $sensor = Sensor::findOrFail($id);

        return view('sensor.edit', [
            "sensor" => $sensor,
        ]);
    }

    public function store(Request $request)
    {
        // $requestData = '';



        // if ($request->data == '') {
        //     $requestData = 'Tidak ada data';
        // } else {
        //     $requestData = $request->data;
        // }
        $request->validate([
            "nama_sensor" => ['required'],
            "data" => ['required', 'numeric'],
            "topic" => ['required'],
        ], [
            "nama_sensor.required" => "Nama sensor harus diisi",
            "data.required" => "Data harus diisi",
            "data.numeric" => "Data harus berupa angka",
            "topic.required" => "Topic harus diisi",
        ]);

        $sensorData = [
            "nama_sensor" => $request->nama_sensor,
            "data" => $request->data,
            "topic" => $request->topic,
        ];
        // DB::table('sensors')->insert($sensorData);

        $sensor = Sensor::create($sensorData);
        return redirect('/sensor')->with('success', 'Berhasil menambahkan data');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "nama_sensor" => ['required'],
            "data" => ['required', 'numeric'],
            "topic" => ['required'],
        ]);

        $sensorData = [
            'nama_sensor' => $request->nama_sensor,
            'data' => $request->data,
            'topic' => $request->topic,
        ];

        // DB::table('sensors')
        // ->where('id', $id)
        // ->update($sensorData);
        $sensor = Sensor::findOrFail($id);
        $sensor->update($sensorData);

        return redirect('/sensor')->with('success', 'Berhasil mengubah data');
    }

    public function delete(Request $request, $id)
    {
        // DB::table('sensors')
        // ->where('id', $id)
        // ->delete();
        $sensor = Sensor::findOrFail($id);
        $sensor->delete();
        return redirect('sensor');
    }
}
