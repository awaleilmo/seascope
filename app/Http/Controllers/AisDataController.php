<?php

namespace App\Http\Controllers;

use App\Models\AisData;
use App\Models\AisStatic;
use Illuminate\Http\Request;

class AisDataController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'msg_type' => 'required|integer',
            'repeat' => 'required|integer',
            'mmsi' => 'required|integer',
            'status' => 'nullable|integer',
            'turn' => 'nullable|numeric',
            'speed' => 'nullable|numeric',
            'accuracy' => 'nullable|boolean',
            'lon' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'course' => 'nullable|numeric',
            'heading' => 'nullable|integer',
            'second' => 'nullable|integer',
            'maneuver' => 'nullable|integer',
            'spare_1' => 'nullable|string',
            'raim' => 'nullable|boolean',
            'radio' => 'nullable|integer',
        ]);

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
