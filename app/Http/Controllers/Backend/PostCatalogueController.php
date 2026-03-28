<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Services\Interfaces\PostCatalogueServiceInterface as PostCatalogueService;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Http\Requests\StorePostCatalogueRequest;
use App\Http\Requests\UpdatePostCatalogueRequest;
use App\Http\Requests\DeletePostCatalogueRequest;
use App\Classes\Nestedsetbie;

class PostCatalogueController extends Controller
{

    protected $postCatalogueService;

    protected $postCatalogueRepository;
    protected $language;

    public function __construct(
        PostCatalogueService $postCatalogueService,
        PostCatalogueRepository $postCatalogueRepository
    ){
        $this->postCatalogueService = $postCatalogueService;
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->nestedset = new Nestedsetbie([
            'table' => 'post_catalogues',
            'foreignkey' => 'post_catalogue_id',
            'language_id' => 1
        ]);
        $this->language = $this->currentLanguage();
    }

    public function index(Request $request){
        $this->authorize('modules', 'post.catalogue.index');

        $postCatalogues = $this->postCatalogueService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'PostCatalogue'
        ];
        $config['seo'] = __('messages.postCatalogue');
        $template = 'backend.post.catalogue.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'postCatalogues'));
    }

    public function create(){
        $this->authorize('modules', 'post.catalogue.create');
        $config = $this->configData();
        $config['seo'] = config('apps.postcatalogue');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout',
        compact(
            'template',
            'config',
            'dropdown'));
    }

    public function store(StorePostCatalogueRequest $request){
        if($this->postCatalogueService->create($request)){
            return redirect()->route('post.catalogue.index')->with('success', 'Thêm mới nhóm thành viên thành công.');
        }
        return redirect()->route('post.catalogue.index')->with('error', 'Thêm mới nhóm thành viên không thành công.');
    }

    public function edit($id){
        $this->authorize('modules', 'post.catalogue.update');
        $postCatalogues = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = config('apps.postcatalogue');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();

        $album = json_decode($postCatalogues->album);
        $template = 'backend.post.catalogue.store';
        return view('backend.dashboard.layout',
        compact(
            'template',
            'config',
            'postCatalogues',
            'dropdown',
            'album'));
    }

    public function update($id, UpdatePostCatalogueRequest $request){
        if($this->postCatalogueService->update($id, $request)){
            return redirect()->route('post.catalogue.index')->with('success','Cập nhật nhóm thành công.');
        }
        return redirect()->route('post.catalogue.index')->with('error', 'Cập nhật nhóm không thành công.');
    }

    public function delete($id){
        $this->authorize('modules', 'post.catalogue.destroy');
        $postCatalogues = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $config['seo'] = config('apps.postcatalogue');
        $template = 'backend.post.catalogue.delete';
        return view('backend.dashboard.layout', compact('template','config', 'postCatalogues'));
    }

    public function destroy(DeletePostCatalogueRequest $request, $id){

        if($this->postCatalogueService->destroy($id)){
            return redirect()->route('post.catalogue.index')->with('success','Xóa nhóm thành công.');
        }
        return redirect()->route('post.catalogue.index')->with('error', 'Xóa nhóm không thành công.');
    }

    private function configData(){
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',

            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',
                'backend/plugins/ckfinder/ckfinder.js',
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
            ]
        ];
    }
}
