<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthReportAudit implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
            if(! session()->get('isLoggedIn') || (session()->get('userclass') != 100 && session()->get('userclass') != 80 )){
                return redirect()->to('http://localhost/Development/development/public/logout');
    }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}