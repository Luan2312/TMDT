<?php

namespace App\Repositories;
use App\Repositories\Interfaces\RouterRepositoryInterface;
use App\Models\Router;

/**
 * Class languageService
 * @package App\Repositories
 */
class RouterRepository extends BaseRepository implements RouterRepositoryInterface
{
    protected $model;

    public function __construct(
        Router $model,

    ){
        $this->model = $model;
    }

}
