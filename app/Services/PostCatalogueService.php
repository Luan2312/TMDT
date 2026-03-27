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
    protected $controllerName = 'PostCatalogue';

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


    public function paginate($request){
        $perpage = $request->integer('perpage');
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
            'where' => [
                ['tb2.language_id', '=', $this->language]
            ]
        ];

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
                $this->createRouter($post, $request, $this->controllerName);
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

    public function update($id, $request){
        DB::beginTransaction();
        try{
            $postCatalogues = $this->postCatalogueRepository->findById($id);
            $post = $this->updateCatalogue($postCatalogues, $request);
            if($post == TRUE){
                $this->updateLanguageForCatalogue($postCatalogues, $request);
                $this->updateRouter($postCatalogues, $request, $this->controllerName);
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

    private function updateCatalogue($postCatalogues, $request){

        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        $post = $this->postCatalogueRepository->update($postCatalogues->id, $payload);

        return $post;
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

    private function convertBirthdayDate($birthday = ''){
        $carbonDate = Carbon::createFromFormat('Y-m-d', $birthday);
        $birthday = $carbonDate->format('Y-m-d H:i:s');
        return $birthday;
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
