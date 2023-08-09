<?php

namespace App\Services\Interface;

use Illuminate\Http\Request;

interface AuthInterface
{
    public function login($request, $usertype);
    public function logout($request);
    public function getUser();
    public function addEmployee($request, $usertype);
}
