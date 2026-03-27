<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Services\Interfaces\LanguageServiceInterface as LanguageService;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;

class LanguageController extends Controller
{

    protected $languageService;

    protected $languageRepository;

    public function __construct(
        LanguageService $languageService,
        LanguageRepository $languageRepository
    ){
        $this->languageService = $languageService;

        $this->languageRepository = $languageRepository;
    }

    public function index(Request $request){

        $languages = $this->languageService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Language'
        ];
        $config['seo'] = config('apps.language');

        $template = 'backend.language.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'languages'));
    }

    public function create(){

        $config = $this->configData();
        $config['seo'] = config('apps.language');
        $config['method'] = 'create';
        $template = 'backend.language.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StoreLanguageRequest $request){
        if($this->languageService->create($request)){
            return redirect()->route('language.index')->with('success', 'Thêm mới nhóm thành viên thành công.');
        }
        return redirect()->route('language.index')->with('error', 'Thêm mới nhóm thành viên không thành công.');
    }

    public function edit($id){
        $languages = $this->languageRepository->findById($id);

        $config = $this->configData();
        $config['seo'] = config('apps.language');
        $config['method'] = 'edit';
        $template = 'backend.language.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'languages'));
    }

    public function update($id, UpdateLanguageRequest $request){
        if($this->languageService->update($id, $request)){
            return redirect()->route('language.index')->with('success','Cập nhật nhóm thành công.');
        }
        return redirect()->route('language.index')->with('error', 'Cập nhật nhóm không thành công.');
    }

    public function delete($id){
        $languages = $this->languageRepository->findById($id);
        $config['seo'] = config('apps.language');
        $template = 'backend.language.delete';
        return view('backend.dashboard.layout', compact('template','config', 'languages'));
    }

    public function destroy($id){
        if($this->languageService->destroy($id)){
            return redirect()->route('language.index')->with('success','Xóa nhóm thành công.');
        }
        return redirect()->route('language.index')->with('error', 'Xóa nhóm không thành công.');
    }

    public function switchBackendLanguage($id){
        $language = $this->languageRepository->findById($id);
        if($this->languageService->switch($id)){
            session(['app_locale' => $language->canonical]);
            \App::setLocale($language->canonical);
        };
        return redirect()->back();

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
                'backend/library/finder.js'
            ]
        ];
    }
}
