<?php

namespace App\Services\Interface;


interface AuthInterface
{
    public function login($request);
    public function logout($request);
    public function getUser();
    public function addEmployee($request, $usertype);
    public function resendOtp($request);
    public function verifyOtp($request);
    public function resetPassword($request);
}
