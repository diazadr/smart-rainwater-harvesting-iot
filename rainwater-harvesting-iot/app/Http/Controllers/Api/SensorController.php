<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Sensor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class SensorController extends Controller
{
    public function index()
    {
        try {

            $sensors = Sensor::all();

            return response()->json([
                "status" => "success",
                "code" => Response::HTTP_OK,
                "message" => "Berhasil mendapatkan data sensor",
                "data" => $sensors,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([

                "status" => "failed",
                "code" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => "Gagal mendapatkan data sensor",
                "data" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $sensor = Sensor::findOrFail($id);

            return response()->json([
                "status" => "success",
                "code" => Response::HTTP_OK,
                "message" => "Berhasil mendapatkan data sensor dengan id " . $id,
                "data" => $sensor,
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "status" => "failed",
                "code" => Response::HTTP_NOT_FOUND,
                "message" => "Gagal mendapatkan data sensor dengan id " . $id,
                "data" => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "failed",
                "code" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => "Gagal mendapatkan data sensor dengan id " . $id,
                "data" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
public function store(Request $request)
{
    $topic = $request->input('topic');
    $data = $request->input('data');

    // Deteksi nama_sensor dari topic
    if (str_contains($topic, 'suhu')) {
        $nama_sensor = 'suhu';
    } elseif (str_contains($topic, 'kelembapan')) {
        $nama_sensor = 'kelembapan';
    } elseif (str_contains($topic, 'tabung/jarak')) {
        $nama_sensor = 'jarak';
    } elseif (str_contains($topic, 'pompa/status')) {
        $nama_sensor = 'pompa';
    } elseif (str_contains($topic, 'tabung/status')) {
        $nama_sensor = 'status_tabung';
    } elseif (str_contains($topic, 'serial_number')) {
        $nama_sensor = 'status_koneksi';
    } else {
        $nama_sensor = 'lainnya';
    }

    Sensor::create([
        'nama_sensor' => $nama_sensor,
        'data' => $data,
        'topic' => $topic,
    ]);

    return response()->json(['message' => 'Sensor data stored successfully.']);
}


    public function update(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), [
            "nama_sensor" => ['required', 'min:2'],
            "data" => ['required'],
            "topic" => ['required'],
        ], [
            "nama_sensor.required" => "Nama sensor harus diisi",
            "nama_sensor.min" => "Nama sensor minimal 2 karakter",
            "data.required" => "Data harus diisi",
            "topic.required" => "Topic harus diisi"
        ]);

        try {
            if (!$validatedData->fails()) {
                $sensor = Sensor::findOrFail($id);
                $updateSensor = $sensor->update([
                    "nama_sensor" => $request->nama_sensor,
                    "data" => $request->data,
                    "topic" => $request->topic,
                ]);
                if ($updateSensor) {
                    return response()->json([
                        "status" => "success",
                        "code"  => Response::HTTP_OK,
                        "message" => "Berhasil mengubah data dengan id " . $id,
                        "data" => $sensor,
                    ]);
                } else {
                    return response()->json([
                        "status" => "fail",
                        "code"  => Response::HTTP_INTERNAL_SERVER_ERROR,
                        "message" => "Gagal mengubah data sensor dengan id " . $id,
                        "data" => null,
                    ]);
                }
            } else {
                throw new ValidationException($validatedData);
            }
        } catch (ValidationException $e) {
            return response()->json([
                "status" => "failed",
                "code"  => Response::HTTP_UNPROCESSABLE_ENTITY,
                "message" => "Gagal mengubah data sensor dengan id " . $id,
                "data" => $sensor,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "failed",
                "code"  => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => "Gagal mengubah data sensor dengan id " . $id,
                "data" => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $sensor = Sensor::findOrFail($id);
            $deleted = $sensor->delete();

            if ($deleted) {
                return response()->json([
                    "status" => "success",
                    "code"  => Response::HTTP_OK,
                    "message" => "Berhasil menghapus data sensor dengan id " . $id,
                    "data" => $sensor,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => "failed",
                "code"  => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => "Gagal mengubah data sensor dengan id " . $id,
                "data" => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
