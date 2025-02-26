<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

class RoleController extends Controller
{
    public function decodeController(): DecodeController
    {
        return new DecodeController();
    }
    public function Logger(): LogActivity
    {
        return new LogActivity();
    }
    public function checkRole($value, $column = 'name', $operator = 'like'): Builder
    {
        return role::query()->where($column,$operator, $value);
    }
    public function createUpdate(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $data = json_decode($decode, true);
            $role = $this->checkRole('%'.strtolower($data['name']).'%');
            if($role->exists() && $data['id'] === null){
                return response()->json([
                    'status' => false,
                    'message' => 'Data Sudah Ada'
                ]);
            }
            $role = $this->checkRole($data['id'],'id','=');
            $subject = $role->exists() ? 'Update Role' : 'Create Role';
            $method = $role->exists() ? 'update' : 'create';
            $role = $role->exists() ? $role->first() : new role();
            $role->setAttribute('name', $data['name']);
            $role->setAttribute('sub', $data['sub']);
            $role->save();
            $this->Logger()->logRole($subject, $role->getAttribute('id'), $method);
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
    public function getRoleById($id): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $role = role::query()->find($data['id']);
            $encode = $this->decodeController()->encodeBase64($role->toJson());
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
    public function getRole(): JsonResponse
    {
        try {
            $role = role::query()->whereNot('name','superadmin')->get();
            $encode = $this->decodeController()->encodeBase64($role->toJson());
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
    public function getRolePage(Request $request): JsonResponse
    {
        try{
            $decode = $this->decodeController()->decodeBase64($request->query('data'));
            $data = json_decode($decode, true);
            $role = $this->checkRole('%'.strtolower($data['search']).'%');
            $role->whereNotIn('name',['superadmin','admin']);
            $encode = $this->decodeController()->encodeBase64($role->paginate($data['perPage'],['*'],'',$data['page'])->toJson());
            return response()->json([
                'status' => true,
                'message' => 'success',
                'content' => $encode
            ]);
        }catch (Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'content' => null
            ]);
        }
    }
    public function destroy($id): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $user = User::query()->where('role_id',$data);
            if($user->exists()){
                return response()->json([
                    'status' => false,
                    'message' => 'Data Tidak Dapat Dihapus, Role Sedang Digunakan'
                ]);
            }
            $role = role::query()->find($data);
            $this->Logger()->logRole('Delete Role', $role->getAttribute('id'), 'delete');
            $role->delete();
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
