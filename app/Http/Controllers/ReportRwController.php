<?php

namespace App\Http\Controllers;

use App\Exports\PolisiRwExport;
use App\Models\foto_rw;
use App\Models\polda;
use App\Models\report_rw;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ReportRwController extends Controller
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

    public function checkRw(): Builder|report_rw
    {
        return report_rw::query();
    }

    public function createUpdate(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $data = json_decode($decode, true);
            $rw = $this->checkRw()->where('id', $data['id']);
            $subject = $rw->exists() ? 'Update Report Rw' : 'Create Report Rw';
            $method = $rw->exists() ? 'update' : 'create';
            $rw = $rw->exists() ? $rw->first() : new report_rw();
            $rw->setAttribute('polda_id', $data['polda_id']);
            $rw->setAttribute('personil_jml', $data['personil_jml']);
            $rw->setAttribute('personil_satu', $data['personil_sat']);
            $rw->setAttribute('sambang', $data['sambang']);
            $rw->setAttribute('pemecahaan', $data['pemecahaan']);
            $rw->setAttribute('informasi', $data['informasi']);
            $rw->setAttribute('penanganan', $data['penanganan']);
            $rw->setAttribute('keterangan', $data['keterangan']);
            $rw->setAttribute('foto', '');
            $rw->setAttribute('tanggal', Carbon::parse($data['tanggal'])->format('Y-m-d'));
            $rw->setAttribute('waktu', Carbon::parse($data['waktu'])->format('H:i'));
            $rw->save();
            if ($data['id'] === null) {
                $checkPolda = polda::query()->where('id', '=', $data['polda_id'])->first();
                $giat = $this->giatController()->insertRw($checkPolda->getAttribute('lokasi_id'), '', 1, $data['tanggal']);
                if ($giat->getOriginalContent()['status'] === false) {
                    $rw->delete();
                    return response()->json([
                        'status' => false,
                        'message' => 'Gagal menyimpan giat, ' . $giat->getOriginalContent()['message']
                    ]);
                }
            }
            $this->Logger()->logRw($subject, $rw->getAttribute('id'), $method);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan',
                'content' => $rw
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getRwById($id): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $rw = $this->checkRw()->find($data);
            $encode = $this->decodeController()->encodeBase64($rw->toJson());
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

    public function getRw(): JsonResponse
    {
        try {
            $data = $this->checkRw()->get();
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

    public function getRwPage(Request $request): JsonResponse
    {
        try {
            $checkAuth = Auth::user()['polda_id'];
            $decode = $this->decodeController()->decodeBase64($request->query('data'));
            $data = json_decode($decode, true);
            $search_item = '%' . strtolower($data['search']) . '%';
            $rw = $this->checkRw()
                ->select("id","polda_id","personil_jml","personil_satu","sambang","pemecahaan","informasi","penanganan","keterangan","tanggal","waktu","created_at","updated_at")
                ->with('polda')
                ->withCount('foto')
                ->where(function (Builder $query) use ($search_item) {
                    $query->where('sambang', 'like', $search_item)
                        ->orWhereRelation('polda', 'name', 'like', $search_item)
                        ->orWhere('pemecahaan', 'like', $search_item)
                        ->orWhere('informasi', 'like', $search_item)
                        ->orWhere('penanganan', 'like', $search_item);
                });
            if ($checkAuth !== 0) {
                $rw = $rw->where('polda_id', '=', $checkAuth);
            } else {
                $rw = $rw->orWhereRelation('polda', 'name', 'like', $search_item);
            }
            $rw = $rw->orderBy('tanggal', 'desc');
            $encode = $this->decodeController()->encodeBase64($rw->paginate($data['perPage'], ['*'], '', $data['page'])->toJson());
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
            $count = report_rw::query()
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

    public function destroy($id): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $sapu = $this->checkRw()->find($data);
            $checkPolda = polda::query()->where('id', '=', $sapu->getAttribute('polda_id'))->first();
            $giat = $this->giatController()->insertRw($checkPolda->getAttribute('lokasi_id'), '', 0, $sapu->getAttribute('tanggal'));
            if ($giat->getOriginalContent()['status'] === false) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan giat, ' . $giat->getOriginalContent()['message']
                ]);
            }
            foto_rw::query()->where('report_rw_id', '=', $sapu->getAttribute('id'))->delete();
            $this->Logger()->logRw('Delete Report Rw', $sapu->getAttribute('id'), 'delete');
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
            $fileName = 'LAPORAN GIAT POLISI RW ';
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
            return Excel::download(new PolisiRwExport($tglName, $data['polda_id'] , $data['polda_name'], $daerah, $tglDari, $tglSampai, $data['formatExport']), $fileName, $format);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
