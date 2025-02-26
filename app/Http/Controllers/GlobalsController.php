<?php

namespace App\Http\Controllers;
use App\Exports\GlobalExport;
use App\Models\Globals;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;


class GlobalsController extends Controller
{
    public function Logger(): LogActivity
    {
        return new LogActivity();
    }

    public function decodeController(): DecodeController
    {
        return new DecodeController();
    }

    public function checkGlobal($value, $column = 'id', $operator = '='): Builder
    {
        return Globals::query()->where($column,$operator, $value);
    }

    public function getGlobal(): JsonResponse
    {
        try {
            $user = Globals::all();
            $encode = $this->decodeController()->encodeBase64($user->toJson());
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

    public function getPage(Request $request):JsonResponse {
        try {
            $decode = $this->decodeController()->decodeBase64($request->query('data'));
            $data = json_decode($decode, true);

            $global = Globals::where('id_no_lp', 'like', '%' . $data['search'] . '%');

                if (strtotime($data['search'])) {
                    $data['search'] = date('Y-m-d', strtotime($data['search']));
                }

            $global->orWhere('tanggal_lp', 'like', '%' . $data['search'] . '%');
            $global->orWhere('hasil_tangkapan', 'like', '%' . $data['search'] . '%');
            $global->orWhere('jenis_kasus', 'like', '%' . $data['search'] . '%');
            $global->orWhere('kronologi', 'like', '%' . $data['search'] . '%');
            $global->orWhere('tersangka_nama', 'like', '%' . $data['search'] . '%');
            $global->orWhere('korban', 'like', '%' . $data['search'] . '%');
            $global->orWhere('saksi_nama', 'like', '%' . $data['search'] . '%');
            $global->orWhere('melanggar_pasal', 'like', '%' . $data['search'] . '%');
            $global->orWhere('barang_bukti', 'like', '%' . $data['search'] . '%');
            $global->orWhere('kerugian', 'like', '%' . $data['search'] . '%');
            $global->orWhere('ba_serah_terima', 'like', '%' . $data['search'] . '%');
            $global->orWhere('keterangan', 'like', '%' . $data['search'] . '%');

            $encode = $this->decodeController()->encodeBase64($global->paginate($data['perPage'],['*'],'',$data['page'])->toJson());

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

    public function createUpdate(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $data = json_decode($decode, true);
            $global = $this->checkGlobal($data['id']);
            $global = $global->exists() ? $global->first() : new Globals();
            $subject = $data['id'] ? 'Update Global' : 'Create Global';
            $method = $data['id'] ? 'update' : 'create';
            $global->setAttribute('id_no_lp', $data['id_no_lp']);
            $global->setAttribute('tanggal_lp', $data['tanggal_lp']);
            $global->setAttribute('hasil_tangkapan', $data['hasil_tangkapan']);
            $global->setAttribute('jenis_kasus', $data['jenis_kasus']);
            $global->setAttribute('kronologi', $data['kronologi']);
            $global->setAttribute('tersangka_nama', $data['tersangka_nama']);
            $global->setAttribute('tersangka_nik', $data['tersangka_nik']);
            $global->setAttribute('tersangka_warganegara', $data['tersangka_warganegara']);
            $global->setAttribute('tersangka_suku', $data['tersangka_suku']);
            $global->setAttribute('tersangka_jk', $data['tersangka_jk']);
            $global->setAttribute('tersangka_tempat_tgl_lahir', $data['tersangka_tempat_tgl_lahir']);
            $global->setAttribute('tersangka_umur', $data['tersangka_umur']);
            $global->setAttribute('tersangka_agama', $data['tersangka_agama']);
            $global->setAttribute('tersangka_pekerjaan', $data['tersangka_pekerjaan']);
            $global->setAttribute('tersangka_alamat', $data['tersangka_alamat']);
            $global->setAttribute('korban', $data['korban']);
            $global->setAttribute('saksi_nama', $data['saksi_nama']);
            $global->setAttribute('saksi_nik', $data['saksi_nik']);
            $global->setAttribute('saksi_warganegara', $data['saksi_warganegara']);
            $global->setAttribute('saksi_suku', $data['saksi_suku']);
            $global->setAttribute('saksi_jk', $data['saksi_jk']);
            $global->setAttribute('saksi_tempat_tgl_lahir', $data['saksi_tempat_tgl_lahir']);
            $global->setAttribute('saksi_umur', $data['saksi_umur']);
            $global->setAttribute('saksi_agama', $data['saksi_agama']);
            $global->setAttribute('saksi_pekerjaan', $data['saksi_pekerjaan']);
            $global->setAttribute('saksi_alamat', $data['saksi_alamat']);
            $global->setAttribute('melanggar_pasal', $data['melanggar_pasal']);
            $global->setAttribute('barang_bukti', $data['barang_bukti']);
            $global->setAttribute('kerugian', $data['kerugian']);
            $global->setAttribute('ba_serah_terima', $data['ba_serah_terima']);
            $global->setAttribute('keterangan', $data['keterangan']);
            $global->setAttribute('created_by', $data['created_by']);
            $global->setAttribute('updated_by', $data['updated_by']);
            $global->save();

            $this->Logger()->logKGlobal($subject, $global->getAttribute('id'), $method);
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

    public function getById($id): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $user = Globals::query()->find($data['id']);
            $encode = $this->decodeController()->encodeBase64($user->toJson());
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
            $user = Globals::query()->find($data);
            $user->delete();
            $this->Logger()->logKGlobal('Delete Global ', $user->getAttribute('id'), 'delete');
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
        $decode = $this->decodeController()->decodeBase64($request->get('data'));
        $data = json_decode($decode, true);

        $nama_file = 'LAPORAN DATA PERKARA PENEGAKKAN HUKUM KAPAL PATROLI DAN SUBDIT GAKKUM.' . $data['formatExport'];
        $type = \Maatwebsite\Excel\Excel::MPDF;

        $periode = '-';

        if($data['formatExport'] == 'pdf') {
            $type = \Maatwebsite\Excel\Excel::MPDF;
        }

        if($data['formatExport'] == 'xlsx') {
            $type = \Maatwebsite\Excel\Excel::XLSX;
        }
        if($data['formatExport'] == 'xls') {
            $type = \Maatwebsite\Excel\Excel::XLS;
        }

        Carbon::setLocale('id');

        if($data['periode'] == "0") {
            $date = Carbon::parse($data['tglDari']);
            $periode = $date->translatedFormat('d F Y');
        }

        if($data['periode'] == "1") {
            if($data['bulan']== "1"){
                $bulanPlus = (int)$data['bulan'] +1;
                $endDate = Carbon::create($data['tahun'], $bulanPlus)->lastOfMonth()->format('Y-m-d');
            }


            $date1 = Carbon::parse($data['tglDari'], 'Asia/Jakarta');
            $date2 = Carbon::parse($endDate, 'Asia/Jakarta');
            $periode = $date1->translatedFormat('d F Y') . ' - ' . $date2->translatedFormat('d F Y');
        }

        if($data['periode'] == "2") {
            $periode = 'TAHUN ' . $data['tahun'];
        }

        return Excel::download(new GlobalExport($data['formatExport'], $periode, $data['tglDari'], $data['tglSampai']), $nama_file, $type);
    }
}
