<?php

namespace App\Http\Controllers;

use App\Models\polda;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PoldaController extends Controller
{
    public function decodeController(): DecodeController
    {
        return new DecodeController();
    }
    public function Logger(): LogActivity
    {
        return new LogActivity();
    }
    public function checkPolda($value, $column = 'id', $operator = '='): Builder
    {
        return polda::query()->where($column,$operator, $value);
    }
    public function createUpdate(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $data = json_decode($decode, true);
            $polda = $this->checkPolda($data['id']);
            $subject = $polda->exists() ? 'Update Polda' : 'Create Polda';
            $method = $polda->exists() ? 'update' : 'create';
            $polda = $polda->exists() ? $polda->first() : new polda();
            $polda->setAttribute('name', $data['name']);
            $polda->setAttribute('kota', $data['kota']);
            $polda->setAttribute('alamat', $data['alamat']);
            $polda->setAttribute('lokasi_id', $data['lokasi_id']);
            $polda->save();
            $this->Logger()->logPolda($subject, $polda->getAttribute('id'), $method);
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
    public function getPoldaById($id): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $polda = polda::query()->find($data['id']);
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
    public function getPolda(): JsonResponse
    {
        try {
            $polda = polda::query()->orderBy('name')->get();
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
    public function getPoldaPage(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->query('data'));
            $data = json_decode($decode, true);
            $polda = $this->checkPolda('%'.strtolower($data['search']).'%','poldas.name','like')
                ->select('poldas.*','lokasis.name as lokasi_name')
                ->leftJoin('lokasis','poldas.lokasi_id','lokasis.id')
                ->orWhere('poldas.kota','like', '%'.strtolower($data['search']).'%')
                ->orWhere('poldas.alamat','like', '%'.strtolower($data['search']).'%');
            $encode = $this->decodeController()->encodeBase64($polda->paginate($data['perPage'],['*'],'',$data['page'])->toJson());
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
            $polda = polda::query()->find($data);
            $this->Logger()->logPolda('Delete Polda', $polda->getAttribute('id'), 'delete');
            $polda->delete();
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
