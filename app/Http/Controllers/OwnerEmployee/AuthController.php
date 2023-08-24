<?php

namespace App\Http\Controllers\OwnerEmployee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Services\Interface\AuthInterface;
use App\Http\Requests\EmployeeAddRequest;

use App\Traits\Base;
use Exception;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    use Base;
    //
    private $authRepository;

    public function __construct(AuthInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(LoginRequest $request)
    {
        $data = $this->authRepository->login($request, request()->header('app-role'));
        return $data->success ? Base::success($data->message, $data->data, 'success', $data->type) : Base::error($data->message, $data->data, 'error', $data->type);
    }
    public function logout(Request $request)
    {
        $data = $this->authRepository->logout($request);
        return $data->success ? Base::success($data->message, $data->data, 'success', $data->type) : Base::error($data->message, $data->data, 'error', $data->type);
    }

    public function getUser()
    {
        return $this->authRepository->getUser();
    }
    public function addEmployee(EmployeeAddRequest $request)
    {
        $data = $this->authRepository->addEmployee($request, request()->header('app-role'));
        return $data->success ? Base::success($data->message, $data->data, 'success', $data->type) : Base::error($data->message, $data->data, 'error', $data->type);
    }

}
