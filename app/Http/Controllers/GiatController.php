<?php

namespace App\Http\Controllers;

use App\Models\giat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class GiatController extends Controller
{
    public function checkLokasi($lokasi_id): Builder
    {
        return giat::query()->where('lokasi_id', $lokasi_id);
    }
    public function insertData($lokasi_id, $keterangan, $addRemove, $column, $tanggal): Builder|giat|null
    {
        $date = Carbon::parse($tanggal)->format('Y-m-d');
        $cek = $this->checkLokasi($lokasi_id)->where('tahun',  Carbon::parse($date)->format('Y'))->where('bulan', Carbon::parse($date)->format('m'));
        $giat = $cek->exists() ? $cek->first() : new giat();
        $hasil = $cek->exists() ? ($addRemove === 1 ? $cek->first()->getAttribute($column) + 1 : $cek->first()->getAttribute($column) - 1) : 1;
        $giat->setAttribute('lokasi_id', $lokasi_id);
        $giat->setAttribute($column, $hasil);
        $giat->setAttribute('keterangan', $keterangan);
        $giat->setAttribute('tahun', Carbon::parse($date)->format('Y'));
        $giat->setAttribute('bulan', Carbon::parse($date)->format('m'));
        return $giat;
    }
    public function insertSambang($lokasi_id, $keterangan = '', $addRemove = 1, $tanggal): JsonResponse
    {
        try {
            $giat = $this->insertData($lokasi_id, $keterangan, $addRemove, 'sambang', $tanggal);
            $giat->save();
            return response()->json([
                'status' => true,
                'message' => 'success'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function insertRw($lokasi_id, $keterangan = '', $addRemove = 1, $tanggal): JsonResponse
    {
        try {
            try {
                $giat = $this->insertData($lokasi_id, $keterangan, $addRemove, 'rw', $tanggal);
                $giat->save();
                return response()->json([
                    'status' => true,
                    'message' => 'success'
                ]);
            } catch (Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ]);
            }
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function insertPerpustakaan($lokasi_id, $keterangan = '', $addRemove = 1, $tanggal): JsonResponse
    {
        try {
            $giat = $this->insertData($lokasi_id, $keterangan, $addRemove, 'perpustakaan', $tanggal);
            $giat->save();
            return response()->json([
                'status' => true,
                'message' => 'success'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function insertKlinik($lokasi_id, $keterangan = '', $addRemove = 1, $tanggal): JsonResponse
    {
        try {
            $giat = $this->insertData($lokasi_id, $keterangan, $addRemove, 'klinik', $tanggal);
            $giat->save();
            return response()->json([
                'status' => true,
                'message' => 'success'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function insertSampah($lokasi_id, $keterangan = '', $addRemove = 1, $tanggal): JsonResponse
    {
        try {
            $giat = $this->insertData($lokasi_id, $keterangan, $addRemove, 'sampah', $tanggal);
            $giat->save();
            return response()->json([
                'status' => true,
                'message' => 'success'
            ]) ;
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
