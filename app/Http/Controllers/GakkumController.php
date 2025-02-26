<?php

namespace App\Http\Controllers;

use App\Exports\GakkumExport;
use App\Models\Gakkum;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class GakkumController extends Controller
{
    public function decodeController(): DecodeController
    {
        return new DecodeController();
    }

    public function Logger(): LogActivity
    {
        return new LogActivity();
    }

    public function page(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->query('data'));
            $data = json_decode($decode, true);


            $gakkum = Gakkum::where('no_lp', 'like', '%' . $data['search'] . '%');
            // check if $data['search'] = date sating is valid
            if (strtotime($data['search'])) {
                $data['search'] = date('Y-m-d', strtotime($data['search']));
            }
            $gakkum->orWhere('tanggal_lp', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('hasil_tangkapan', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('jenis_kasus', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('kronologis', 'like', '%' . $data['search'] . '%');

            $gakkum->orWhere('tersangka_nama', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('tersangka_nik', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('tersangka_warganegara', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('tersangka_suku', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('tersangka_jk', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('tersangka_tempat_tgl_lahir', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('tersangka_umur', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('tersangka_agama', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('tersangka_pekerjaan', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('tersangka_alamat', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('saksi_nama', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('saksi_nik', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('saksi_warganegara', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('saksi_suku', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('saksi_jk', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('saksi_tempat_tgl_lahir', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('saksi_umur', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('saksi_agama', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('saksi_pekerjaan', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('saksi_alamat', 'like', '%' . $data['search'] . '%');

            $gakkum->orWhere('korban', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('melanggar_pasal', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('barang_bukti', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('kerugian', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('penyidik', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('penanganan_perkara', 'like', '%' . $data['search'] . '%');
            $gakkum->orWhere('keterangan', 'like', '%' . $data['search'] . '%');

            $encode = $this->decodeController()->encodeBase64($gakkum->paginate($data['perPage'], ['*'], '', $data['page'])->toJson());
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

            // Validation
            if ($data['id'] === null) {
                $check = Gakkum::where('no_lp', $data['no_lp'])->count();
                if ($check) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Nomor LP sudah ada!'
                    ]);
                }

                $gakkum = new Gakkum();
            } else {
                $gakkum = Gakkum::find($data['id']);
            }

            $subject = $data['id'] ? 'Update Gakkum' : 'Create Gakkum';
            $method = $data['id'] ? 'update' : 'create';

            $gakkum->no_lp = $data['no_lp'];
            $gakkum->tanggal_lp = $data['tanggal_lp'];
            $gakkum->hasil_tangkapan = $data['hasil_tangkapan'];
            $gakkum->jenis_kasus = $data['jenis_kasus'];
            $gakkum->kronologis = $data['kronologis'];

            $gakkum->tersangka_nama = json_encode($data['tersangka_nama']);
            $gakkum->tersangka_nik = json_encode($data['tersangka_nik']);
            $gakkum->tersangka_warganegara = json_encode($data['tersangka_warganegara']);
            $gakkum->tersangka_suku = json_encode($data['tersangka_suku']);
            $gakkum->tersangka_jk = json_encode($data['tersangka_jk']);
            $gakkum->tersangka_tempat_tgl_lahir = json_encode($data['tersangka_tempat_tgl_lahir']);
            $gakkum->tersangka_umur = json_encode($data['tersangka_umur']);
            $gakkum->tersangka_agama = json_encode($data['tersangka_agama']);
            $gakkum->tersangka_pekerjaan = json_encode($data['tersangka_pekerjaan']);
            $gakkum->tersangka_alamat = json_encode($data['tersangka_alamat']);

            $gakkum->korban = $data['korban'];

            $gakkum->saksi_nama = json_encode($data['saksi_nama']);
            $gakkum->saksi_nik = json_encode($data['saksi_nik']);
            $gakkum->saksi_warganegara = json_encode($data['saksi_warganegara']);
            $gakkum->saksi_suku = json_encode($data['saksi_suku']);
            $gakkum->saksi_jk = json_encode($data['saksi_jk']);
            $gakkum->saksi_tempat_tgl_lahir = json_encode($data['saksi_tempat_tgl_lahir']);
            $gakkum->saksi_umur = json_encode($data['saksi_umur']);
            $gakkum->saksi_agama = json_encode($data['saksi_agama']);
            $gakkum->saksi_pekerjaan = json_encode($data['saksi_pekerjaan']);
            $gakkum->saksi_alamat = json_encode($data['saksi_alamat']);

            $gakkum->melanggar_pasal = $data['melanggar_pasal'];
            $gakkum->barang_bukti = json_encode($data['barang_bukti']);
            $gakkum->kerugian = $data['kerugian'];
            $gakkum->penyidik = $data['penyidik'];
            $gakkum->penanganan_perkara = $data['penanganan_perkara'];
            $gakkum->keterangan = $data['keterangan'];
            $gakkum->save();

            $this->Logger()->logGakkum($subject, $gakkum->id, $method);

            return response()->json([
                'status' => true,
                'data' => json_encode($data),
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

            Gakkum::destroy($data);

            $this->Logger()->logGakkum('Delete Gakkum', $data, 'delete');

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

        $title = 'DATA PENEGAKKAN PERKARA SUBDIT GAKKUM.' . $data['formatExport'];
        $nama_file = 'LAPORAN '. $title.'.' . $data['formatExport'];

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
            $periode = $date1->translatedFormat('d F Y') . ' ~ ' . $date2->translatedFormat('d F Y');;
        }

        if($data['periode'] == "2") {
            $periode = 'TAHUN ' . $data['tahun'];
        }

        return Excel::download(
            new GakkumExport(
                Gakkum::query(),
                $data['formatExport'],
                $periode,
                $data['tglDari'],
                $data['tglSampai'],
                $title
            ),
            $nama_file,
            $type
        );
    }
}
