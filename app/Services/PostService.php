<?php

namespace App\Services;
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;
use App\Services\BaseService;


use App\Services\Interfaces\PostServiceInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;

/**
 * Class PostService
 * @package App\Services
 */
class PostService extends BaseService implements PostServiceInterface
{

    protected $postRepository;
    protected $routerRepository;
    protected $language;

    public function __construct(
        PostRepository $postRepository,
        RouterRepository $routerRepository,
    ){
        $this->language = $this->currentLanguage();
        $this->postRepository = $postRepository;
        $this->routerRepository = $routerRepository;
        $this->controllerName = 'Post';
    }

    private function paginateSelect(){
        return [
            'posts.id',
            'posts.publish',
            'posts.image',
            'tb2.name',
            'tb2.canonical',
            'posts.order'
        ];
    }

    public function paginate($request){

        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $condition['where'] = [
            ['tb2.language_id', '=', $this->language],

        ];
        $perpage = $request->integer('perpage');
        $postCatalogues = $this->postRepository->pagination($this->paginateSelect(),
            $condition,
            $perpage,
            ['path' => 'post.index'],
            ['posts.id','DESC'],
            [
                ['post_language as tb2', 'tb2.post_id', '=', 'posts.id'],
                ['post_catalogue_post as tb3', 'posts.id', '=', 'tb3.post_id'],
            ],
            ['post_catalogues'],
            $this->whereRaw($request)
        );

        return $postCatalogues;
    }

    public function create($request){
        DB::beginTransaction();
        try{
            $post = $this->createForPost($request);
            if($post->id > 0){
                $this->updateLanguageForPost($post, $request);
                $this->updateCatalogueForPost($post, $request);
                $this->createRouter($post, $request, $this->controllerName);
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
        return ['follow', 'publish','image', 'album', 'post_catalogue_id'];
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

    private function catalogue($request){
        $catalogue = (array) $request->input('catalogue', []);

        $catalogue = array_filter($catalogue, function ($id) {
            return !empty($id) && intval($id) > 0;
        });

        if (!empty($request->post_catalogue_id) && intval($request->post_catalogue_id) > 0) {
            $catalogue[] = intval($request->post_catalogue_id);
        }

        return array_unique($catalogue);
    }

    public function update($id, $request){
        DB::beginTransaction();
        try{
            $postCatalogues = $this->postRepository->findById($id);

            if($this->uploadPost($postCatalogues, $request)){
                $this->updateLanguageForPost($postCatalogues, $request);
                $this->updateCatalogueForPost($postCatalogues, $request);
                $this->updateRouter($postCatalogues, $request, $this->controllerName);
            }

            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollBack();
            echo $e->getMessage();die();
            return false;
        }
    }

    private function createForPost($request){
        $payload = $request->only($this->payload());

        $payload['album'] = $this->formatAlbum($request);
        $payload['user_id'] = Auth::id();
        $post = $this->postRepository->create($payload);
        return $post;
    }

    private function uploadPost($post, $request){
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($request);
        return $this->postRepository->update($post->id, $payload);
    }

    private function updateLanguageForPost($post, $request){
        $payloadLanguages = $request->only($this->payloadLanguage());
        $payloadLanguages = $this->formatLanguagePayload($payloadLanguages, $post->id);
        $post->languages()->detach([$this->language, $post->id]);
        return $this->postRepository->createPivot($post, $payloadLanguages,'languages');
    }

    private function updateCatalogueForPost($post, $request){
        $post->post_catalogues()->sync($this->catalogue($request));
    }

    private function formatLanguagePayload($payload, $postId){
        $payload['canonical'] = Str::slug($payload['canonical']);
        $payload['language_id'] = $this->language;
        $payload['post_id'] = $postId;
        return $payload;
    }

    private function whereRaw($request){
        $rawCondition = [];
        if($request->integer('post_catalogue_id') > 0){
            $rawCondition['whereRaw'] = [
                [
                    'tb3.post_catalogue_id IN (
                        SELECT id
                        FROM post_catalogues
                        WHERE lft >= (SELECT lft FROM post_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM post_catalogues as pc WHERE pc.id = ?)
                    )',
                    [$request->integer('post_catalogue_id'), $request->integer('post_catalogue_id')]
                ]
            ];
        }
        return $rawCondition;
    }

    private function convertBirthdayDate($birthday = ''){
        $carbonDate = Carbon::createFromFormat('Y-m-d', $birthday);
        $birthday = $carbonDate->format('Y-m-d H:i:s');
        return $birthday;
    }

    public function destroy($id){
        DB::beginTransaction();
        try{
            $post = $this->postRepository->delete($id);

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
            $post = $this->postRepository->update($post['modelId'],$payload);
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
            $flag = $this->postRepository->updateByWhereIn('id', $post['id'], $payload);
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
