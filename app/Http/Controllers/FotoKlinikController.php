<?php

namespace App\Http\Controllers;

use App\Models\foto_klinik;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class FotoKlinikController extends Controller
{
    public function decodeController(): DecodeController
    {
        return new DecodeController();
    }
    public function Logger(): LogActivity
    {
        return new LogActivity();
    }
    public function checkFoto (): Builder|foto_klinik
    {
        return foto_klinik::query();
    }
    public function createUpdate(Request $request): JsonResponse
    {
        try {
            $check = $this->checkFoto()->where('report_klinik_id', $request->get('id'))->where('urutan', $request->get('urutan'));
            $subject = $check->exists() ? 'Update Foto Klinik' : 'Create Foto Klinik';
            $method = $check->exists() ? 'update' : 'create';
            $data = $check->exists() ? $check->first() : new foto_klinik();
            $data->setAttribute('report_klinik_id', $request->get('id'));
            $data->setAttribute('foto', $request->get('file'));
            $data->setAttribute('urutan', $request->get('urutan'));
            $data->save();
            $this->Logger()->logFotoKlinik($subject, $data->getAttribute('id'), $method);
            return response()->json([
                'status' => true,
                'message' => 'success',
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
    public function getFotoById($id): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $polda = $this->checkFoto()->where( 'report_klinik_id','=', $data)->orderBy('urutan')->get();
            $encode = $this->decodeController()->encodeBase64($polda->toJson());
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

    public function destroy($id, $urutan): JsonResponse
    {
        try {
            $decodeId = $this->decodeController()->decodeBase64($id);
            $decodeUrutan = $this->decodeController()->decodeBase64($urutan);
            $dataId = json_decode($decodeId, true);
            $dataUrutan = json_decode($decodeUrutan, true);
            $data = $this->checkFoto()->where('report_klinik_id', $dataId)
                ->where('urutan', $dataUrutan);
            $this->Logger()->logFotoKlinik('Delete Foto Klinik', $data->first()->getAttribute('id'), 'delete');
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'success',
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
