<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class UsersController extends Controller
{
    public function decodeController(): DecodeController
    {
        return new DecodeController();
    }
    public function Logger(): LogActivity
    {
        return new LogActivity();
    }
    public function checkUser($value, $column = 'id', $operator = '='): Builder
    {
        return user::query()->where($column,$operator, $value);
    }
    public function createUpdate(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->get('data'));
            $data = json_decode($decode, true);
            $user = $this->checkUser($data['id']);
            if($data['id'] === null){
                $check = $this->checkUser($data['username'],'username');
                if($check->exists()){
                    return response()->json([
                        'status' => false,
                        'message' => 'username sudah ada'
                    ]);
                }
                $check = $this->checkUser($data['email'],'email');
                if($check->exists()){
                    return response()->json([
                        'status' => false,
                        'message' => 'email sudah ada'
                    ]);
                }
            }
            $subject = $user->exists() ? 'Update User' : 'Create User';
            $method = $user->exists() ? 'update' : 'create';
            $user = $user->exists() ? $user->first() : new user();
            $user->setAttribute('name', $data['name']);
            $user->setAttribute('username', $data['username']);
            $user->setAttribute('email', $data['email']);
            $user->setAttribute('polda_id', $data['polda_id']);
            if($data['id'] === null){
                $user->setAttribute('password', $data['password']);
            }else if($data['password'] !== null){
                $user->setAttribute('password', $data['password']);
            }
            $user->setAttribute('role_id', $data['role_id']);
            $user->save();
            $this->Logger()->logUser($subject, $user->getAttribute('id'), $method);
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
    public function getUserById($id): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($id);
            $data = json_decode($decode, true);
            $user = user::query()->find($data['id']);
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
    public function getUser(): JsonResponse
    {
        try {
            $user = user::all();
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
    public function getUserPage(Request $request): JsonResponse
    {
        try {
            $decode = $this->decodeController()->decodeBase64($request->query('data'));
            $data = json_decode($decode, true);
            $user = $this->checkUser('%'.strtolower($data['search']).'%','name','like')
                ->select('*')
                ->with('polda','role')
                ->orWhereRelation('polda','name','like','%'.strtolower($data['search']).'%')
                ->orWhereRelation('role','name','like','%'.strtolower($data['search']).'%')
                ->orWhere('username','like', '%'.strtolower($data['search']).'%')
                ->orWhere('email','like','%'.strtolower($data['search']).'%');
            $encode = $this->decodeController()->encodeBase64($user->paginate($data['perPage'],['*'],'',$data['page'])->toJson());
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
            $user = user::query()->find($data);
            $this->Logger()->logUser('Delete User', $user->getAttribute('id'), 'delete');
            $user->delete();
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
