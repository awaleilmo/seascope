<?php

namespace App\Http\Controllers;

use App\Models\lokasi;
use App\Models\polda;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class LokasiController extends Controller
{
    public function decodeController(): DecodeController
    {
        return new DecodeController();
    }
    public function Logger(): LogActivity
    {
        return new LogActivity();
    }
    public function checkLokasi($value, $column = 'name', $operator = '='): Builder
    {
        return lokasi::query()->where($column,$operator, $value);
    }
    public function createUpdate(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $data = json_decode($decode, true);
            $lokasi = $this->checkLokasi($data['id'],'id');
            $subject = $lokasi->exists() ? 'Update Lokasi' : 'Create Lokasi';
            $method = $lokasi->exists() ? 'update' : 'create';
            $lokasi = $lokasi->exists() ? $lokasi->first() : new lokasi();
            $lokasi->setAttribute('name', $data['name']);
            $lokasi->setAttribute('alias', $data['alias']);
            $lokasi->save();
            $this->Logger()->logLokasi($subject, $lokasi->getAttribute('id'), $method);
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
    public function getLokasiById($id): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $lokasi = lokasi::query()->find($data['id']);
            $encode = $this->decodeController()->encodeBase64($lokasi->toJson());
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
    public function getLokasi(): JsonResponse
    {
        try {
            $lokasi = lokasi::query()->orderBy('name')->get();
            $encode = $this->decodeController()->encodeBase64($lokasi->toJson());
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
    public function getLokasiPage(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->query('data'));
            $data = json_decode($decode, true);
            $lokasi = $this->checkLokasi('%'.strtolower($data['search']).'%','name','like');
            $lokasi->orWhere('alias','%'.strtolower($data['search']).'%')->orderBy('name');
            $encode = $this->decodeController()->encodeBase64($lokasi->paginate($data['perPage'],['*'],'',$data['page'])->toJson());
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
            $polda = polda::query()->where('lokasi_id', $data);
            if($polda->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Tidak Dapat Dihapus, Data Terkait Ada Di Tabel Polda'
                ]);
            }
            $lokasi = $this->checkLokasi($data,'id');
            $this->Logger()->logLokasi('Delete Lokasi', $data, 'delete');
            $lokasi->delete();
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
}
