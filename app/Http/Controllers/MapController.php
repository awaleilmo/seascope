<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Throwable;

class MapController extends Controller
{
    public function gempa()
    {
        try {
            $data = file_get_contents("https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.json") or die ("Gagal ambil!");
            $json = json_decode($data);
            $chunk = array_chunk($json->Infogempa->gempa, 6, false);
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $chunk[0]
            ]);
        }catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'content' => null
            ]);
        }
    }

    public function inFlow($baseRun, $dTime, $depth)
    {
        try {
            $data = file_get_contents('https://maritim.bmkg.go.id/pusmar/api23/arr_req/inaflows/cur/'.$baseRun.'/'.$dTime.'/'.$depth);
            $json = json_decode($data);
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => json_decode($json)
            ]);
        }catch (Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'content' => null
            ]);
        }
    }

    public function inaWaves($baseRun, $dTime)
    {
        try {
            $data = file_get_contents('https://maritim.bmkg.go.id/pusmar/api23/arr_req/inawaves/dir/202408070000/202408070000');
            $json = json_decode($data);
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => json_decode($json)
            ]);
        }catch (Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'content' => null
            ]);
        }
    }
}
