<?php

namespace App\Services;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Services\BaseService;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;


use App\Services\Interfaces\PostCatalogueServiceInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Classes\Nestedsetbie;

/**
 * Class PostService
 * @package App\Services
 */
class PostCatalogueService extends BaseService implements PostCatalogueServiceInterface
{

    protected $postCatalogueRepository;
    protected $routerRepository;
    protected $nestedset;
    protected $language;

    public function __construct(
        PostCatalogueRepository $postCatalogueRepository,
        RouterRepository $routerRepository,
    ){
        $this->language = $this->currentLanguage();
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->routerRepository = $routerRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' => $this->language
        ]);

    }

    private function paginateSelect(){
        return [
            'post_catalogues.id',
            'post_catalogues.publish',
            'post_catalogues.level',
            'post_catalogues.image',
            'tb2.name',
            'tb2.canonical',
            'post_catalogues.order'
        ];
    }

    private function createCatalogue($request){
        $payload = $request->only($this->payload());

        $payload['album'] = $this->formatAlbum($request);
        $payload['user_id'] = Auth::id();
        $post = $this->postCatalogueRepository->create($payload);
        return $post;
    }

    private function updateLanguageForCatalogue($post, $request){
        $payloadLanguages = $this->formatLanguagePayload($post, $request);
        $post->languages()->detach([$this->language, $post->id]);
        $language = $this->postCatalogueRepository->createPivot($post, $payloadLanguages, 'languages');

        return $language;
    }

    private function formatLanguagePayload($post, $request){
        $payloadLanguages = $request->only($this->payloadLanguage());
        $payloadLanguages['canonical'] = Str::slug($payloadLanguages['canonical']);
        $payloadLanguages['language_id'] = $this->language;
        $payloadLanguages['post_catalogue_id'] = $post->id;
        return $payloadLanguages;
    }

    private function updateRouter($post, $request){
        $this->formatRouterpayload($post, $request);
        $this->routerRepository->create($router);
    }

    private function formatRouterpayload($post, $request){
        $router = [
            'canonical' => $request->input('canonical'),
            'module_id' => $post->id,
            'controllers' => 'App\Http\Controller\Frontend\PostCatalogueController'
        ];
        return $router;
    }

    public function paginate($request){

        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $condition['where'] = [
            ['tb2.language_id', '=', $this->language]
        ];
        $perpage = $request->integer('perpage');
        $postCatalogues = $this->postCatalogueRepository->pagination($this->paginateSelect(),
        $condition,
        $perpage,
        ['path' => 'post.catalogue.index'],
        ['post_catalogues.lft','ASC'],
        [
            ['post_catalogue_language as tb2', 'tb2.post_catalogue_id', '=', 'post_catalogues.id']
        ]);

        return $postCatalogues;
    }

    public function create($request){
        DB::beginTransaction();
        try{
            $post = $this->createCatalogue($request);
            if($post->id > 0){
                $this->updateLanguageForCatalogue($post, $request);
                $this->updateRouter($post, $request);
                $this->nestedset();
            }



            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }

    }

    private function payload(){
        return ['parent_id', 'follow', 'publish','image', 'album'];
    }
    private function payloadLanguage(){
        return  ['name',
        'description',
        'content',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'canonical'];

    }

    public function update($id, $request){
        DB::beginTransaction();
        try{
            $postCatalogues = $this->postCatalogueRepository->findById($id);
            $payload = $request->only($this->payload());
            if(isset($payload['album'])){
                $payload['album'] = json_encode($payload['album']);
            }

            $post = $this->postCatalogueRepository->update($id, $payload);
            if($post == TRUE){
                $payload = $request->only($this->payloadLanguage());
                $payload['language_id'] = $this->language;
                $payload['post_catalogue_id'] = $id;
                $postCatalogues->languages()->detach([$payload['language_id'], $id]);
                $response = $this->postCatalogueRepository->createPivot($postCatalogues, $payload, 'languages');

                $payloadRouter = [
                    'canonical' => $payloadLanguages['canonical'],
                    'module_id' => $postCatalogues->id,
                    'controllers' => 'App\Http\Controller\Frontend\PostCatalogueController'
                ];
                $condition = [
                    ['module_id','=', $id],
                    ['controllers','=', 'App\Http\Controller\Frontend\PostCatalogueController'],
                ];
                $router = $this->routerRepository->findByCondition($condition);
                $this->routerRepository->update($router->id, $payloadRouter);

                $this->nestedset();
            }

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
            $post = $this->postCatalogueRepository->delete($id);
            $this->nestedset->Get('level ASC, order ASC');
            $this->nestedset->Recursive(0, $this->nestedset->Set());
            $this->nestedset->Action();

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
            $post = $this->postCatalogueRepository->update($post['modelId'],$payload);
            // $this->changepostStatus($post, $payload[$post['field']]);
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
            $flag = $this->postCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
            // $this->changepostStatus($post, $post['value']);
            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }

    // private function changepostStatus($post, $value){

    //     DB::beginTransaction();
    //     try{
    //         $array = [];
    //         if(isset($post['modelId'])){
    //             $array[] = $post['modelId'];
    //         }else{
    //             $array = $post['id'];
    //         }
    //         $payload[$post['field']] = $value;

    //         $this->postRepository->updateByWhereIn('post_catalogue_id', $array, $payload);
    //         DB::commit();
    //         return true;
    //     }catch(\Exception $e){
    //         DB::rollBack();
    //         echo $e->getMessage();die();
    //         return false;
    //     }
    // }
}
