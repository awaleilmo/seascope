<?php

namespace App\Http\Controllers;

use App\Models\TargetPU;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class TargetPUController extends Controller
{
    public function decodeController(): DecodeController
    {
        return new DecodeController();
    }
    public function Logger(): LogActivity
    {
        return new LogActivity();
    }

    public function checkModel(): Builder
    {
        return TargetPU::query();
    }

    public function createUpdate(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $data = json_decode($decode, true);
            $model = $this->checkModel()->where('id', $data['id']);
            $subject = $model->exists() ? 'Update TargetPU' : 'Create TargetPU';
            $method = $model->exists() ? 'update' : 'create';
            $model = $model->exists() ? $model->first() : new TargetPU();
            $model->setAttribute('klinik', $data['klinik']);
            $model->setAttribute('perpustakaan', $data['perpustakaan']);
            $model->setAttribute('rw', $data['rw']);
            $model->setAttribute('sambang', $data['sambang']);
            $model->setAttribute('sapu', $data['sapu']);
            $model->setAttribute('tahun', $data['tahun']);
            $model->save();
            $this->Logger()->logTarget($subject, $model->getAttribute('id'), $method);
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $model
            ]);
        } catch (Throwable $Throwable) {
            return response()->json([
                'status' => false,
                'message' => $Throwable->getMessage(),
                'content' => null
            ]);
        }
    }

    public function getModelByTahun(Request $request): JsonResponse
    {
        try{
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $data = json_decode($decode, true);
            $model = $this->checkModel()->where('tahun', $data['tahun']);
            $encode = $this->decodeController()->encodeBase64($model->first()->toJson());
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $encode
            ]);
        }catch (Throwable $Throwable) {
            return response()->json([
                'status' => false,
                'message' => $Throwable->getMessage(),
                'content' => null
            ]);
        }
    }

    public function getModelPage(Request $request): JsonResponse
    {
        try{
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $data = json_decode($decode, true);
            $search_item = '%' . strtolower($data['search']) . '%';
            $model = $this->checkModel()->where('tahun', 'like', $search_item);
            $encode = $this->decodeController()->encodeBase64($model->paginate($data['perPage'], ['*'], '', $data['page'])->toJson());
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $encode
            ]);
        }catch (Throwable $Throwable) {
            return response()->json([
                'status' => false,
                'message' => $Throwable->getMessage(),
                'content' => null
            ]);
        }
    }

    public function destroy($id): JsonResponse
    {
        try{
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $model = $this->checkModel()->find($data);
            $this->Logger()->logTarget('Delete TargetPU', $data, 'delete');
            $model->delete();
            return response()->json([
                'status' => true,
                'message' => 'success'
            ]);
        }catch (Throwable $Throwable) {
            return response()->json([
                'status' => false,
                'message' => $Throwable->getMessage(),
                'content' => null
            ]);
        }
    }
}
