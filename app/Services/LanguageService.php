<?php

namespace App\Services;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;

use App\Services\Interfaces\LanguageServiceInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * Class languageService
 * @package App\Services
 */
class LanguageService implements LanguageServiceInterface
{

    protected $languageRepository;

    public function __construct(
        LanguageRepository $languageRepository,

    ){
        $this->languageRepository = $languageRepository;
    }

    private function paginateSelect(){
        return ['id', 'name', 'canonical', 'publish', 'image'];
    }

    public function paginate($request){

        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $perpage = $request->integer('perpage');
        $languages = $this->languageRepository->pagination($this->paginateSelect(),
            $condition,
            $perpage,
            ['path' => 'language/index']);
        return $languages;
    }

    public function create($request){
        DB::beginTransaction();
        try{
            $payload = $request->except(['_token','send']);
            $payload['user_id'] = Auth::id();
            $language = $this->languageRepository->create($payload);
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

            $language = $this->languageRepository->update($id, $payload);
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
            $language = $this->languageRepository->forceDelete($id);
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
            $language = $this->languageRepository->update($post['modelId'],$payload);
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
            $flag = $this->languageRepository->updateByWhereIn('id', $post['id'], $payload);
            // $this->changelanguageStatus($post, $post['value']);

            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }

    public function switch($id){
        
        DB::beginTransaction();
        try{
            // $language = $this->languageRepository->findById($id);
            $this->languageRepository->update($id, ['current' => 1]);
            $payload = ['current' => 0];
            $where =  [
                ['id', '!=', $id]
            ];
            $this->languageRepository->updateByWhere($where, $payload);

            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }

    // private function changelanguageStatus($post, $value){

    //     DB::beginTransaction();
    //     try{
    //         $array = [];
    //         if(isset($post['modelId'])){
    //             $array[] = $post['modelId'];
    //         }else{
    //             $array = $post['id'];
    //         }
    //         $payload[$post['field']] = $value;

    //         $this->languageRepository->updateByWhereIn('language_catalogue_id', $array, $payload);
    //         DB::commit();
    //         return true;
    //     }catch(\Exception $e){
    //         DB::rollBack();
    //         echo $e->getMessage();die();
    //         return false;
    //     }
    // }
}
