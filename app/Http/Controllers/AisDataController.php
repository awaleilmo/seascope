<?php

namespace App\Http\Controllers;

use App\Models\AisData;
use App\Models\AisStatic;
use Illuminate\Http\Request;

class AisDataController extends Controller
{
    public function store(Request $request)
    {

        $data = $request->all();

        AisData::create($data);

        if ($request->has('shipname') || $request->has('ship_type')) {
            AisStatic::updateOrCreate(
                ['mmsi' => $request->mmsi],
                [
                    'shipname' => $request->shipname,
                    'ship_type' => $request->ship_type,
                    'to_bow' => $request->to_bow,
                    'to_stern' => $request->to_stern,
                    'to_port' => $request->to_port,
                    'to_starboard' => $request->to_starboard,
                ]
            );
        }

        return response()->json(['message' => 'AIS data stored successfully'], 201);
    }

    public function index()
    {
        $aisData = AisData::with('staticInfo')->orderBy('created_at', 'desc')->limit(100)->get();
        return response()->json($aisData);
    }

    public function show($mmsi)
    {
        $aisData = AisData::where('mmsi', $mmsi)->orderBy('created_at', 'desc')->get();
        $aisStatic = AisStatic::where('mmsi', $mmsi)->first();
        return response()->json(['ais_data' => $aisData, 'ais_static' => $aisStatic]);
    }
}
