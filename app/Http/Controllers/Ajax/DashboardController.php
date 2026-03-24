<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\UserServiceInterface as UserService;

class DashboardController extends Controller
{
    protected $userService;
    public function __construct(
        UserService $userService
    )
        {
            $this->userService = $userService;
        }

    public function changeStatus(Request $request){
        $post = $request->input();
        $serviceInterfacenamspace = '\App\Services\\' . ucfirst($post['model']) . 'Service';
        if (class_exists($serviceInterfacenamspace)){
            $serviceInstace = app($serviceInterfacenamspace);
        }

        $flag = $serviceInstace->updateStatus($post);
        return response()->json(['flag' => $flag]);
    }

    public function changeStatusAll(Request $request){
        $post = $request->input();
        $serviceInterfacenamspace = '\App\Services\\' . ucfirst($post['model']) . 'Service';
        if (class_exists($serviceInterfacenamspace)){
            $serviceInstace = app($serviceInterfacenamspace);
        }

        $flag = $serviceInstace->updateStatusAll($post);
        return response()->json(['flag' => $flag]);
    }

}
