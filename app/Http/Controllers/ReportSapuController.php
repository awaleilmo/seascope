<?php

namespace App\Http\Controllers;

use App\Exports\SapuExport;
use App\Models\foto_sapu;
use App\Models\polda;
use App\Models\report_sapu;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ReportSapuController extends Controller
{
    public function decodeController(): DecodeController
    {
        return new DecodeController();
    }
    public function Logger(): LogActivity
    {
        return new LogActivity();
    }

    public function fotoSapuController(): FotoSapuController
    {
        return new FotoSapuController();
    }

    public function giatController(): GiatController
    {
        return new GiatController();
    }

    public function checkSapu($value, $column = 'id', $operator = '='): Builder
    {
        return report_sapu::query()->where($column, $operator, $value);
    }

    public function createUpdate(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $data = json_decode($decode, true);
            $sapu = $this->checkSapu($data['id']);
            $subject = $sapu->exists() ? 'Update Report Sapu' : 'Create Report Sapu';
            $method = $sapu->exists() ? 'update' : 'create';
            $sapu = $sapu->exists() ? $sapu->first() : new report_sapu();
            $sapu->setAttribute('polda_id', $data['polda_id']);
            $sapu->setAttribute('personil_ket', $data['personil_ket']);
            $sapu->setAttribute('personil_jml', $data['personil_jml']);
            $sapu->setAttribute('personil_sat', $data['personil_sat']);
            $sapu->setAttribute('lokasi', $data['lokasi']);
            $sapu->setAttribute('sampah_organik_jml', $data['sampah_organik_jml']);
            $sapu->setAttribute('sampah_organik_ket', $data['sampah_organik_ket']);
            $sapu->setAttribute('sampah_anorganik_jml', $data['sampah_anorganik_jml']);
            $sapu->setAttribute('sampah_anorganik_ket', $data['sampah_anorganik_ket']);
            $sapu->setAttribute('keterangan', $data['keterangan']);
            $sapu->setAttribute('foto', '');
            $sapu->setAttribute('tanggal', Carbon::parse($data['tanggal'])->format('Y-m-d'));
            $sapu->setAttribute('waktu', Carbon::parse($data['waktu'])->format('H:i'));
            $sapu->save();
            if ($data['id'] === null) {
                $checkPolda = polda::query()->where('id', '=', $data['polda_id'])->first();
                $giat = $this->giatController()->insertSampah($checkPolda->getAttribute('lokasi_id'), '', 1, $data['tanggal']);
                if ($giat->getOriginalContent()['status'] === false) {
                    $sapu->delete();
                    return response()->json([
                        'status' => false,
                        'message' => 'Gagal menyimpan giat, ' . $giat->getOriginalContent()['message']
                    ]);
                }
            }
            $this->Logger()->logSapu($subject, $sapu->getAttribute('id'), $method);
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $sapu
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getSapuById($id): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $sapu = report_sapu::query()->find($data['id']);
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

    public function getSapu(): JsonResponse
    {
        try {
            $sapu = report_sapu::all();
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

    public function getSapuPage(Request $request): JsonResponse
    {
        try {
            $checkAuth = Auth::user()['polda_id'];
            $decode = $this->decodeController()->decodeBase64($request->query('data'));
            $data = json_decode($decode, true);
            $search_item = '%' . strtolower($data['search']) . '%';
            $sapu = report_sapu::query()
                ->select("id","polda_id","personil_ket","personil_jml","personil_sat","lokasi","sampah_organik_jml","sampah_organik_ket","sampah_anorganik_jml","sampah_anorganik_ket","keterangan","foto","tanggal","waktu","created_at","updated_at")
                ->with('polda')
                ->withCount('foto')
                ->where(function ($query) use ($search_item) {
                    $query->where('lokasi', 'like', $search_item)
                        ->orWhere('sampah_organik_jml', 'like', $search_item)
                        ->orWhere('sampah_organik_ket', 'like', $search_item)
                        ->orWhere('sampah_anorganik_jml', 'like', $search_item)
                        ->orWhere('sampah_anorganik_ket', 'like', $search_item);
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
            $sapu = report_sapu::query()->find($data);
            $checkPolda = polda::query()->where('id', '=', $sapu->getAttribute('polda_id'))->first();
            $giat = $this->giatController()->insertSampah($checkPolda->getAttribute('lokasi_id'), '', 0, $sapu->getAttribute('tanggal'));
            if ($giat->getOriginalContent()['status'] === false) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan giat, ' . $giat->getOriginalContent()['message']
                ]);
            }
            foto_sapu::query()->where('report_sapu_id', $sapu->getAttribute('id'))->delete();
            $this->Logger()->logSapu('Delete Report Sapu ', $sapu->getAttribute('id'), 'delete');
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
            if($dataDecode['polda_id'] === '' || $dataDecode['polda_id'] === null){
                return response()->json([
                    'status' => false,
                    'message' => 'Polda tidak boleh kosong'
                ]);
            }
            $tglDari = Carbon::parse($dataDecode['tglDari'])->format('Y-m-d');
            $tglSampai = Carbon::parse($dataDecode['tglSampai'])->format('Y-m-d');
            $count = report_sapu::query()
                ->where('polda_id', '=', $dataDecode['polda_id'])
                ->where('tanggal', '>=', $tglDari)
                ->where('tanggal', '<=', $tglSampai)
                ->count();
            if($count === 0){
                $mess = 'Data tidak ditemukan';
                if($dataDecode['periode'] === '0'){
                    $mess = 'Data '.$dataDecode['polda_name'].
                        ' pada Tgl '.$dataDecode['tgl'].
                        ' Hari '. $dataDecode['namaHari'].
                        ' Bulan '.$dataDecode['namaBulan'].
                        ' Tahun '.$dataDecode['tahun'].
                        ' tidak ditemukan';
                }
                if($dataDecode['periode'] === '1'){
                    $mess = 'Data '.$dataDecode['polda_name'].
                        ' pada Bulan '.$dataDecode['namaBulan'].
                        ' Tahun '.$dataDecode['tahun'].
                        ' tidak ditemukan';
                }
                if($dataDecode['periode'] === '2'){
                    $mess = 'Data '.$dataDecode['polda_name'].
                        ' pada Tahun '.$dataDecode['tahun'].
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
            $fileName = 'LAPORAN GIAT SAPU BERSIH PANTAI ';
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
            return Excel::download(new SapuExport($tglName, $data['polda_id'] , $data['polda_name'], $daerah, $tglDari, $tglSampai, $data['formatExport']), $fileName, $format);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
