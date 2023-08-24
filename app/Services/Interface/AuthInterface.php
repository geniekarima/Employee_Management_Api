<?php

namespace App\Services\Interface;


interface AuthInterface
{
    public function login($request, $usertype);
    public function logout($request);
    public function getUser();
    public function addEmployee($request, $usertype);
}
