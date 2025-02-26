<?php

namespace App\Http\Controllers;

use App\Models\foto_sapu;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class FotoSapuController extends Controller
{
    public function decodeController(): DecodeController
    {
        return new DecodeController();
    }
    public function Logger(): LogActivity
    {
        return new LogActivity();
    }
    public function checkFoto (): Builder|foto_sapu
    {
        return foto_sapu::query();
    }
    public function createUpdate(Request $request): JsonResponse
    {
        try {
            $check = $this->checkFoto()->where('report_sapu_id', $request->get('id'))->where('urutan', $request->get('urutan'));;
            $subject = $check->exists() ? 'Update Foto Sapu' : 'Create Foto Sapu';
            $method = $check->exists() ? 'update' : 'create';
            $data = $check->exists() ? $check->first() : new foto_sapu();
            $data->setAttribute('report_sapu_id', $request->get('id'));
            $data->setAttribute('foto', $request->get('file'));
            $data->setAttribute('urutan', $request->get('urutan'));
            $data->save();
            $this->Logger()->logFotoSapu($subject, $data->getAttribute('id'), $method);
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
            $polda = $this->checkFoto()->where('report_sapu_id', $data)->orderBy('urutan')->get();
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
            $data = $this->checkFoto()->where('report_sapu_id', $dataId)
                ->where('urutan', $dataUrutan);
            $this->Logger()->logFotoSapu('Delete Foto Sapu', $data->first()->getAttribute('id'), 'delete');
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
