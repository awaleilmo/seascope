<?php

namespace App\Http\Controllers;

use App\Exports\PerpustakaanExport;
use App\Models\foto_perpustakaan;
use App\Models\polda;
use App\Models\report_perpustakaan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ReportPerpustakaanController extends Controller
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
    public function checkPerpustakaan(): Builder|report_perpustakaan
    {
        return report_perpustakaan::query();
    }
    public function createUpdate(Request $request): JsonResponse
    {
        try{
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $data = json_decode($decode, true);
            $perpustakaan = $this->checkPerpustakaan()->where('id', $data['id']);
            $subject = $perpustakaan->exists() ? 'Update Report Perpustakaan' : 'Create Report Perpustakaan';
            $method = $perpustakaan->exists() ? 'update' : 'create';
            $perpustakaan = $perpustakaan->exists() ? $perpustakaan->first() : new report_perpustakaan();
            $perpustakaan->setAttribute('polda_id', $data['polda_id']);
            $perpustakaan->setAttribute('personil_jml', $data['personil_jml']);
            $perpustakaan->setAttribute('personil_sat', $data['personil_sat']);
            $perpustakaan->setAttribute('lokasi', $data['lokasi']);
            $perpustakaan->setAttribute('jml_peserta', $data['jml_peserta']);
            $perpustakaan->setAttribute('asal_peserta', $data['asal_peserta']);
            $perpustakaan->setAttribute('hasil', $data['hasil']);
            $perpustakaan->setAttribute('keterangan', $data['keterangan']);
            $perpustakaan->setAttribute('foto', '');
            $perpustakaan->setAttribute('tanggal', Carbon::parse($data['tanggal'])->format('Y-m-d'));
            $perpustakaan->setAttribute('waktu', Carbon::parse($data['waktu'])->format('H:i'));
            $perpustakaan->save();
            if ($data['id'] === null) {
                $checkPolda = polda::query()->where('id', '=', $data['polda_id'])->first();
                $giat = $this->giatController()->insertPerpustakaan($checkPolda->getAttribute('lokasi_id'),'',1,$data['tanggal']);
                if ($giat->getOriginalContent()['status'] === false) {
                    $perpustakaan->delete();
                    return response()->json([
                        'status' => false,
                        'message' => 'Gagal menyimpan giat, ' . $giat->getOriginalContent()['message']
                    ]);
                }
            }
            $this->Logger()->logPerpustakaan($subject, $perpustakaan->getAttribute('id'), $method);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
                'content' => $perpustakaan
            ]);
        }catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function getPerpustakaanById($id): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $sapu = $this->checkPerpustakaan()->find($data);
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
    public function getPerpustakaan(): JsonResponse
    {
        try {
            $data = $this->checkPerpustakaan()->get();
            $encode = $this->decodeController()->encodeBase64($data->toJson());
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
    public function getPerpustakaanPage(Request $request): JsonResponse
    {
        try {
            $checkAuth = Auth::user()['polda_id'];
            $decode = $this->decodeController()->decodeBase64($request->query('data'));
            $data = json_decode($decode, true);
            $search_item = '%' . strtolower($data['search']) . '%';
            $perpustakaan = $this->checkPerpustakaan()
                ->select("id","polda_id","personil_jml","personil_sat","lokasi","jml_peserta","asal_peserta","hasil","keterangan","tanggal","waktu","created_at","updated_at")
                ->with('polda')
                ->withCount('foto')
                ->where(function (Builder $query) use ($search_item) {
                    $query->where('lokasi', 'like', $search_item)
                        ->orWhere('jml_peserta', 'like', $search_item)
                        ->orWhere('asal_peserta', 'like', $search_item)
                        ->orWhere('hasil', 'like', $search_item);
                });
            if ($checkAuth !== 0) {
                $perpustakaan = $perpustakaan->where('polda_id', '=', $checkAuth);
            } else {
                $perpustakaan = $perpustakaan->orWhereRelation('polda', 'name', 'like', $search_item);
            }
            $perpustakaan = $perpustakaan->orderBy('tanggal', 'desc');
            $encode = $this->decodeController()->encodeBase64($perpustakaan->paginate($data['perPage'], ['*'], '', $data['page'])->toJson());
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
            $sapu = $this->checkPerpustakaan()->find($data);
            $checkPolda = polda::query()->where('id', '=', $sapu->getAttribute('polda_id'))->first();
            $giat = $this->giatController()->insertPerpustakaan($checkPolda->getAttribute('lokasi_id'), '', 0, $sapu->getAttribute('tanggal'));
            if ($giat->getOriginalContent()['status'] === false) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan giat, ' . $giat->getOriginalContent()['message']
                ]);
            }
            foto_perpustakaan::query()->where('report_perpustakaan_id', '=', $sapu->getAttribute('id'))->delete();
            $this->Logger()->logPerpustakaan('Delete Report Perpustakaan', $sapu->getAttribute('id'),'delete');
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
            $count = report_perpustakaan::query()
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
            $fileName = 'LAPORAN GIAT KAPAL PERPUSTAKAAN ';
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
            return Excel::download(new PerpustakaanExport($tglName, $data['polda_id'] , $data['polda_name'], $daerah, $tglDari, $tglSampai, $data['formatExport']), $fileName, $format);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
