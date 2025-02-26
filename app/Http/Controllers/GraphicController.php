<?php

namespace App\Http\Controllers;

use App\Models\giat;
use App\Models\polda;
use App\Models\report_klinik;
use App\Models\report_perpustakaan;
use App\Models\report_rw;
use App\Models\report_sambang;
use App\Models\report_sapu;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class GraphicController extends Controller
{
    public function decodeController(): DecodeController
    {
        return new DecodeController();
    }

    public function giatGraphicByTahunAndLokasi(Request $request)
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $dtJson = json_decode($decode, true);
            $sampah = $this->sampahCount($dtJson);
            $klinik = $this->klinikCount($dtJson);
            $perpustakaan = $this->perpustakaanCount($dtJson);
            $rw = $this->rwCount($dtJson);
            $sambang = $this->sambangCount($dtJson);
            $result = [[
                'sambang' => $sambang->getAttribute('sambang'),
                'rw' => $rw->getAttribute('rw'),
                'perpustakaan' => $perpustakaan->getAttribute('perpustakaan'),
                'klinik' => $klinik->getAttribute('klinik'),
                'sampah' => $sampah->getAttribute('sampah'),
                'total' => $sampah->getAttribute('sampah') + $klinik->getAttribute('klinik') + $perpustakaan->getAttribute('perpustakaan') + $rw->getAttribute('rw') + $sambang->getAttribute('sambang'),
                'tahun' => $dtJson['tahun']
            ]];
//            $data = giat::query()
//                ->selectSub('sum(sambang)', 'sambang')
//                ->selectSub('sum(rw)', 'rw')
//                ->selectSub('sum(perpustakaan)', 'perpustakaan')
//                ->selectSub('sum(klinik)', 'klinik')
//                ->selectSub('sum(sampah)', 'sampah')
//                ->addSelect('tahun')
//                ->where('tahun', '=', $dtJson['tahun']);
//            if ($dtJson['lokasi_id'] != '%%') {
//                $data->addSelect('lokasi_id')
//                    ->where('lokasi_id', '=', $dtJson['lokasi_id']);
//            }
//            if ($dtJson['bulan'] != '%%') {
//                $data->addSelect('bulan')
//                    ->where('bulan', 'like', $dtJson['bulan']);
//            }
            $encode = $this->decodeController()->encodeBase64(json_encode($result));
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $encode
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'content' => null
            ]);
        }
    }

    public function sampahCount($data): report_sapu
    {
        $dta = report_sapu::query()
            ->selectRaw('count(*) as sampah')
            ->whereYear('tanggal', '=', $data['tahun']);
        if ($data['polda_id'] != '%%') {
            $dta->where('polda_id', '=', $data['polda_id']);
        }
        if ($data['bulan'] != '%%') {
            $dta->whereMonth('tanggal', '=', $data['bulan']);
        }
        return $dta->first();
    }

    public function klinikCount($data): report_klinik
    {
        $dta = report_klinik::query()
            ->selectRaw('count(*) as klinik')
            ->whereYear('tanggal', '=', $data['tahun']);
        if ($data['polda_id'] != '%%') {
            $dta->where('polda_id', '=', $data['polda_id']);
        }
        if ($data['bulan'] != '%%') {
            $dta->whereMonth('tanggal', '=', $data['bulan']);
        }
        return $dta->first();
    }

    public function perpustakaanCount($data): report_perpustakaan
    {
        $dta = report_perpustakaan::query()
            ->selectRaw('count(*) as perpustakaan')
            ->whereYear('tanggal', '=', $data['tahun']);
        if ($data['polda_id'] != '%%') {
            $dta->where('polda_id', '=', $data['polda_id']);
        }
        if ($data['bulan'] != '%%') {
            $dta->whereMonth('tanggal', '=', $data['bulan']);
        }
        return $dta->first();
    }

    public function rwCount($data): report_rw
    {
        $dta = report_rw::query()
            ->selectRaw('count(*) as rw')
            ->whereYear('tanggal', '=', $data['tahun']);
        if ($data['polda_id'] != '%%') {
            $dta->where('polda_id', '=', $data['polda_id']);
        }
        if ($data['bulan'] != '%%') {
            $dta->whereMonth('tanggal', '=', $data['bulan']);
        }
        return $dta->first();
    }

    public function sambangCount($data): report_sambang
    {
        $dta = report_sambang::query()
            ->selectRaw('count(*) as sambang')
            ->whereYear('tanggal', '=', $data['tahun']);
        if ($data['polda_id'] != '%%') {
            $dta->where('polda_id', '=', $data['polda_id']);
        }
        if ($data['bulan'] != '%%') {
            $dta->whereMonth('tanggal', '=', $data['bulan']);
        }
        return $dta->first();
    }

    public function sampahGraphicByTahunAndPolda(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $dtJson = json_decode($decode, true);
            $data = report_sapu::query()
                ->selectSub('sum(sampah_organik_jml)', 'organik')
                ->selectSub('sum(sampah_anorganik_jml)', 'anorganik')
                ->selectRaw('YEAR(tanggal) as tahun')
                ->whereYear('tanggal', '=', $dtJson['tahun']);
            if ($dtJson['polda_id'] != '%%') {
                $data->addSelect('polda_id')
                    ->where('polda_id', '=', $dtJson['polda_id']);
            }
            if ($dtJson['bulan'] != '%%') {
                $data->selectRaw('MONTH(tanggal) as bulan')
                    ->whereMonth('tanggal', '=', $dtJson['bulan']);
            }
            $encode = $this->decodeController()->encodeBase64($data->first()->toJson());
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $encode
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'content' => null
            ]);
        }
    }

    public function klinikGraphicByTahunAndPolda(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $dtJson = json_decode($decode, true);
            $data = report_klinik::query()
                ->selectSub('sum(jml_peserta)', 'jml_peserta')
                ->selectRaw('YEAR(tanggal) as tahun')
                ->whereYear('tanggal', '=', $dtJson['tahun']);
            if ($dtJson['polda_id'] != '%%') {
                $data->addSelect('polda_id')
                    ->where('polda_id', '=', $dtJson['polda_id']);
            }
            if ($dtJson['bulan'] != '%%') {
                $data->selectRaw('MONTH(tanggal) as bulan')
                    ->whereMonth('tanggal', '=', $dtJson['bulan']);
            }
            $encode = $this->decodeController()->encodeBase64($data->first()->toJson());
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $encode
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'content' => null
            ]);
        }
    }

    public function perpustakaanGraphicByTahunAndPolda(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $dtJson = json_decode($decode, true);
            $data = report_perpustakaan::query()
                ->selectSub('sum(jml_peserta)', 'jml_peserta')
                ->selectRaw('YEAR(tanggal) as tahun')
                ->whereYear('tanggal', '=', $dtJson['tahun']);
            if ($dtJson['polda_id'] != '%%') {
                $data->addSelect('polda_id')
                    ->where('polda_id', '=', $dtJson['polda_id']);
            }
            if ($dtJson['bulan'] != '%%') {
                $data->selectRaw('MONTH(tanggal) as bulan')
                    ->whereMonth('tanggal', '=', $dtJson['bulan']);
            }
            $encode = $this->decodeController()->encodeBase64($data->first()->toJson());
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $encode
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'content' => null
            ]);
        }
    }

    public function programUnggulanPerPolda(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $dtJson = json_decode($decode, true);
            $query = polda::query()
                ->withCount(
                    [
                        'sambang' => function (Builder $query) use ($dtJson) {
                            $query->whereYear('tanggal', '=', $dtJson['tahun']);
                            if ($dtJson['bulan'] != '%%') {
                                $query->whereMonth('tanggal', '=', $dtJson['bulan']);
                            }
                        },
                        'klinik' => function (Builder $query) use ($dtJson) {
                            $query->whereYear('tanggal', '=', $dtJson['tahun']);
                            if ($dtJson['bulan'] != '%%') {
                                $query->whereMonth('tanggal', '=', $dtJson['bulan']);
                            }
                        },
                        'perpustakaan' => function (Builder $query) use ($dtJson) {
                            $query->whereYear('tanggal', '=', $dtJson['tahun']);
                            if ($dtJson['bulan'] != '%%') {
                                $query->whereMonth('tanggal', '=', $dtJson['bulan']);
                            }
                        },
                        'rw' => function (Builder $query) use ($dtJson) {
                            $query->whereYear('tanggal', '=', $dtJson['tahun']);
                            if ($dtJson['bulan'] != '%%') {
                                $query->whereMonth('tanggal', '=', $dtJson['bulan']);
                            }
                        },
                        'sapu' => function (Builder $query) use ($dtJson) {
                            $query->whereYear('tanggal', '=', $dtJson['tahun']);
                            if ($dtJson['bulan'] != '%%') {
                                $query->whereMonth('tanggal', '=', $dtJson['bulan']);
                            }
                        }
                    ])
                ->with('lokasi')
                ->orderBy('kota');
            if ($dtJson['polda_id'] != '%%') {
                $query->where('id', '=', $dtJson['polda_id']);
            }
            $encode = $this->decodeController()->encodeBase64($query->get()->toJson());
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $encode
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'content' => null
            ]);
        }
    }

}
