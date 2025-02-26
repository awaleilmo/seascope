<?php

namespace App\Http\Controllers;

use App\Exports\SambangExport;
use App\Models\foto_sambang;
use App\Models\polda;
use App\Models\report_sambang;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ReportSambangController extends Controller
{
    public function decodeController(): DecodeController
    {
        return new DecodeController();
    }

    public function Logger(): LogActivity
    {
        return new LogActivity();
    }

    public function giatController(): GiatController
    {
        return new GiatController();
    }

    public function checkSambang($value, $column = 'id', $operator = '='): Builder
    {
        return report_sambang::query()->where($column, $operator, $value);
    }

    public function createUpdate(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $data = json_decode($decode, true);
            $sambang = $this->checkSambang($data['id']);
            $subject = $sambang->exists() ? 'Update Sambang ' . $data['tipe'] : 'Create Sambang ' . $data['tipe'];
            $method = $sambang->exists() ? 'update' : 'create';
            $sambang = $sambang->exists() ? $sambang->first() : new report_sambang();
            $sambang->setAttribute('polda_id', $data['polda_id']);
            $sambang->setAttribute('personil_jml', $data['personil_jml']);
            $sambang->setAttribute('personil_sat', $data['personil_sat']);
            $sambang->setAttribute('lokasi', $data['lokasi']);
            $sambang->setAttribute('jarak', $data['jarak']);
            $sambang->setAttribute('uraian', $data['uraian']);
            $sambang->setAttribute('jml_peserta', $data['jml_peserta']);
            $sambang->setAttribute('keterangan', $data['keterangan']);
            $sambang->setAttribute('tipe', $data['tipe']);
            $sambang->setAttribute('foto', '');
            $sambang->setAttribute('tanggal', Carbon::parse($data['tanggal'])->format('Y-m-d'));
            $sambang->setAttribute('waktu', Carbon::parse($data['waktu'])->format('H:i'));
            $sambang->save();
            if ($data['id'] === null) {
                $checkPolda = polda::query()->where('id', '=', $data['polda_id'])->first();
                $giat = $this->giatController()->insertSambang($checkPolda->getAttribute('lokasi_id'), '', 1, $data['tanggal']);
                if ($giat->getOriginalContent()['status'] === false) {
                    $sambang->delete();
                    return response()->json([
                        'status' => false,
                        'message' => 'Gagal menyimpan giat, ' . $giat->getOriginalContent()['message']
                    ]);
                }
            }
            $this->Logger()->logSambang($subject, $sambang->getAttribute('id'), $method);
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $sambang
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getSambangById($id): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $sapu = report_sambang::query()->find($data['id']);
            $encode = $this->decodeController()->encodeBase64($sapu->toJson());
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $encode
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getSambang(): JsonResponse
    {
        try {
            $sapu = report_sambang::all();
            $encode = $this->decodeController()->encodeBase64($sapu->toJson());
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $encode
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getSambangBinaanPage(Request $request): JsonResponse
    {
        try {
            $checkAuth = Auth::user()['polda_id'];
            $decode = $this->decodeController()->decodeBase64($request->query('data'));
            $data = json_decode($decode, true);
            $search_item = '%' . strtolower($data['search']) . '%';
            $sapu = report_sambang::query()
                ->where('tipe', '=', '1')
                ->select("id", "polda_id", "personil_jml", "personil_sat", "lokasi", "jarak", "uraian", "jml_peserta", "keterangan", "tipe", "tanggal", "waktu", "created_at", "updated_at")
                ->with('polda')
                ->withCount('foto')
                ->where(function ($query) use ($search_item) {
                    $query->where('jarak', 'like', $search_item)
                        ->orWhere('lokasi', 'like', $search_item)
                        ->orWhere('jml_peserta', 'like', $search_item)
                        ->orWhere('uraian', 'like', $search_item);
                });
            if ($checkAuth !== 0) {
                $sapu = $sapu->where('polda_id', '=', $checkAuth);
            } else {
                $sapu = $sapu->orWhereRelation('polda', 'name', 'like', $search_item);
            }
            $sapu = $sapu->orderBy('tanggal', 'desc');
            $encode = $this->decodeController()->encodeBase64($sapu->paginate($data['perPage'], ['*'], '', $data['page'])->toJson());
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $encode
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getSambangSentuhanPage(Request $request): JsonResponse
    {
        try {
            $checkAuth = Auth::user()['polda_id'];
            $decode = $this->decodeController()->decodeBase64($request->query('data'));
            $data = json_decode($decode, true);
            $search_item = '%' . strtolower($data['search']) . '%';
            $sapu = report_sambang::query()
                ->where('tipe', '=', '2')
                ->select("id", "polda_id", "personil_jml", "personil_sat", "lokasi", "jarak", "uraian", "jml_peserta", "keterangan", "tipe", "tanggal", "waktu", "created_at", "updated_at",)
                ->with('polda')
                ->withCount('foto')
                ->where(function ($query) use ($search_item) {
                    $query->where('jarak', 'like', $search_item)
                        ->orWhere('lokasi', 'like', $search_item)
                        ->orWhere('jml_peserta', 'like', $search_item)
                        ->orWhere('uraian', 'like', $search_item);

                });
            if ($checkAuth !== 0) {
                $sapu = $sapu->where('polda_id', '=', $checkAuth);
            } else {
                $sapu = $sapu->orWhereRelation('polda', 'name', 'like', $search_item);
            }
            $sapu = $sapu->orderBy('tanggal', 'desc');
            $encode = $this->decodeController()->encodeBase64($sapu->paginate($data['perPage'], ['*'], '', $data['page'])->toJson());
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $encode
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getSambangPantauanPage(Request $request): JsonResponse
    {
        try {
            $checkAuth = Auth::user()['polda_id'];
            $decode = $this->decodeController()->decodeBase64($request->query('data'));
            $data = json_decode($decode, true);
            $search_item = '%' . strtolower($data['search']) . '%';
            $sapu = report_sambang::query()
                ->where('tipe', '=', '3')
                ->select("id", "polda_id", "personil_jml", "personil_sat", "lokasi", "jarak", "uraian", "jml_peserta", "keterangan", "tipe", "tanggal", "waktu", "created_at", "updated_at",)
                ->with('polda')
                ->withCount('foto')
                ->where(function ($query) use ($search_item) {
                    $query->where('jarak', 'like', $search_item)
                        ->orWhere('lokasi', 'like', $search_item)
                        ->orWhere('jml_peserta', 'like', $search_item)
                        ->orWhereRelation('polda', 'name', 'like', $search_item)
                        ->orWhere('uraian', 'like', $search_item);

                });
            if ($checkAuth !== 0) {
                $sapu = $sapu->where('polda_id', '=', $checkAuth);
            } else {
                $sapu = $sapu->orWhereRelation('polda', 'name', 'like', $search_item);
            }
            $sapu = $sapu->orderBy('tanggal', 'desc');
            $encode = $this->decodeController()->encodeBase64($sapu->paginate($data['perPage'], ['*'], '', $data['page'])->toJson());
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $encode
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $sapu = report_sambang::query()->find($data);
            $checkPolda = polda::query()->where('id', '=', $sapu->getAttribute('polda_id'))->first();
            $giat = $this->giatController()->insertSambang($checkPolda->getAttribute('lokasi_id'), '', 0, $sapu->getAttribute('tanggal'));
            if ($giat->getOriginalContent()['status'] === false) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan giat, ' . $giat->getOriginalContent()['message']
                ]);
            }
            foto_sambang::query()->where('report_sambang_id', $sapu->getAttribute('id'))->delete();
            $this->Logger()->logSambang('Delete Sambang ' . $sapu->getAttribute('tipe'), $sapu->getAttribute('id'), 'delete');
            $sapu->delete();
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

    public function checkCount(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $dataDecode = json_decode($decode, true);
            if ($dataDecode['polda_id'] === '' || $dataDecode['polda_id'] === null) {
                return response()->json([
                    'status' => false,
                    'message' => 'Polda tidak boleh kosong'
                ]);
            }
            $tglDari = Carbon::parse($dataDecode['tglDari'])->format('Y-m-d');
            $tglSampai = Carbon::parse($dataDecode['tglSampai'])->format('Y-m-d');
            $count = report_sambang::query()
                ->where('polda_id', '=', $dataDecode['polda_id'])
                ->where('tipe', $dataDecode['tipe'])
                ->where('tanggal', '>=', $tglDari)
                ->where('tanggal', '<=', $tglSampai)
                ->count();
            if ($count === 0) {
                $mess = 'Data tidak ditemukan';
                if ($dataDecode['periode'] === '0') {
                    $mess = 'Data ' . $dataDecode['polda_name'] .
                        ' pada Tgl ' . $dataDecode['tgl'] .
                        ' Hari ' . $dataDecode['namaHari'] .
                        ' Bulan ' . $dataDecode['namaBulan'] .
                        ' Tahun ' . $dataDecode['tahun'] .
                        ' tidak ditemukan';
                }
                if ($dataDecode['periode'] === '1') {
                    $mess = 'Data ' . $dataDecode['polda_name'] .
                        ' pada Bulan ' . $dataDecode['namaBulan'] .
                        ' Tahun ' . $dataDecode['tahun'] .
                        ' tidak ditemukan';
                }
                if ($dataDecode['periode'] === '2') {
                    $mess = 'Data ' . $dataDecode['polda_name'] .
                        ' pada Tahun ' . $dataDecode['tahun'] .
                        ' tidak ditemukan';
                }
                return response()->json([
                    'status' => false,
                    'message' => $mess
                ]);
            }
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

    public function download(Request $request): BinaryFileResponse|JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $data = json_decode($decode, true);
            $lokasi =
                polda::query()
                    ->select('lokasis.name as lokasi_name')
                    ->leftJoin('lokasis', 'lokasis.id', 'poldas.lokasi_id')
                    ->where('poldas.id', '=', $data['polda_id'])
                    ->first();
            $tglDari = Carbon::parse($data['tglDari'])->format('Y-m-d');
            $tglSampai = Carbon::parse($data['tglSampai'])->format('Y-m-d');
            $tglName = '';
            $fileName = 'LAPORAN GIAT SAMBANG NUSA PRESISI ';
            if ($data['periode'] === '0') {
                $fileName .= 'HARI ' . $data['namaHari'] . ' TANGGAL ' . $data['tgl'] . ' ' . $data['namaBulan'] . ' TAHUN ' . $data['tahun'];
                $tglName = 'HARI ' . $data['namaHari'] . ' TANGGAL ' . $data['tgl'] . ' ' . $data['namaBulan'] . ' TAHUN ' . $data['tahun'];
            }
            if ($data['periode'] === '1') {
                $fileName .= 'BULAN ' . $data['namaBulan'] . ' TAHUN ' . $data['tahun'];
                $tglName = 'BULAN ' . $data['namaBulan'] . ' TAHUN ' . $data['tahun'];
            }
            if ($data['periode'] === '2') {
                $fileName .= 'TAHUN ' . $data['tahun'];
                $tglName = 'TAHUN ' . $data['tahun'];
            }
            $fileName .= '.' . $data['formatExport'];
            $format = null;
            $daerah = '';
            if ($data['formatExport'] === 'xlsx') {
                $format = \Maatwebsite\Excel\Excel::XLSX;
            }
            if ($data['formatExport'] === 'csv') {
                $format = \Maatwebsite\Excel\Excel::CSV;
            }
            if ($data['formatExport'] === 'xls') {
                $format = \Maatwebsite\Excel\Excel::XLS;
            }
            if ($data['formatExport'] === 'pdf') {
                $format = \Maatwebsite\Excel\Excel::MPDF;
            }
            if (str_contains(strtoupper($lokasi->getAttribute('lokasi_name')), 'DAERAH')) {
                $daerah = strtoupper($lokasi->getAttribute('lokasi_name'));
            } else {
                $daerah = 'DAERAH ' . strtoupper($lokasi->getAttribute('lokasi_name'));
            }
            return Excel::download(new SambangExport($tglName, $data['polda_id'], $data['polda_name'], $daerah, $tglDari, $tglSampai, $data['tipe'], $data['formatExport']), $fileName, $format);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
