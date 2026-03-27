<?php

namespace App\Services;

use App\Services\Interfaces\BaseServiceInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;

/**
 * Class languageService
 * @package App\Services
 */
class BaseService implements BaseServiceInterface
{

    protected $routerRepository;
    protected $controllerName;
    public function __construct(
    RouterRepository $routerRepository,

    ){
    $this->routerRepository = $routerRepository;
    }

    public function currentLanguage(){
        return 1;
    }

    public function formatAlbum($request){
        return ($request->input('album') && !empty($request->input('album'))) ? json_decode($request->input('album')) : '';
    }

    public function nestedset(){
        $this->nestedset->Get('level ASC, order ASC');
        $this->nestedset->Recursive(0, $this->nestedset->Set());
        $this->nestedset->Action();
    }

    public function formatRouterPayload($model, $request, $controllerName){
        $router = [
            'canonical' => $request->input('canonical'),
            'module_id' => $model->id,
            'controllers' => 'App\Http\Controller\Frontend\\'.$controllerName.'Controller'
        ];
        return $router;
    }

    public function createRouter($model, $request, $controllerName){
        $router = $this->formatRouterpayload($model, $request, $controllerName);
        $this->routerRepository->create($router);
    }

    public function updateRouter($model, $request, $controllerName){
        $payload = $this->formatRouterpayload($model, $request, $controllerName);
        $condition = [
            ['module_id','=', $model->id],
            ['controllers','=', 'App\Http\Controller\Frontend\\'.$controllerName.'Controller'],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        $flag = $this->routerRepository->update($router->id, $payload);
        return $flag;
    }
}
