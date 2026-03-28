<?php

namespace App\Services;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;

use App\Services\Interfaces\PermissionServiceInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * Class PermissionService
 * @package App\Services
 */
class PermissionService implements PermissionServiceInterface
{

    protected $permissionRepository;

    public function __construct(
        PermissionRepository $permissionRepository,

    ){
        $this->permissionRepository = $permissionRepository;
    }

    private function paginateSelect(){
        return ['id', 'name', 'canonical'];
    }

    public function paginate($request){

        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $perpage = $request->integer('perpage');
        $permissions = $this->permissionRepository->pagination($this->paginateSelect(),
            $condition,
            $perpage,
            ['path' => 'permission/index']);
        return $permissions;
    }

    public function create($request){
        DB::beginTransaction();
        try{
            $payload = $request->except(['_token','send']);
            $payload['user_id'] = Auth::id();
            $permission = $this->permissionRepository->create($payload);
            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }

    }
    public function update($id, $request){
        DB::beginTransaction();
        try{

            $payload = $request->except(['_token','send']);

            $permission = $this->permissionRepository->update($id, $payload);
            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }

    private function convertBirthdayDate($birthday = ''){
        $carbonDate = Carbon::createFromFormat('Y-m-d', $birthday);
        $birthday = $carbonDate->format('Y-m-d H:i:s');
        return $birthday;
    }

    public function destroy($id){
        DB::beginTransaction();
        try{
            $permission = $this->permissionRepository->forceDelete($id);
            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }

    public function updateStatus($post = []){
        DB::beginTransaction();
        try{
            $payload[$post['field']] = (($post['value'] == 1)?2:1);
            $permission = $this->permissionRepository->update($post['modelId'],$payload);
            // $this->changelanguageStatus($post, $payload[$post['field']]);
            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }

    public function updateStatusAll($post = []){
        DB::beginTransaction();
        try{
            $payload[$post['field']] = $post['value'];
            $flag = $this->permissionRepository->updateByWhereIn('id', $post['id'], $payload);

            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }

}
