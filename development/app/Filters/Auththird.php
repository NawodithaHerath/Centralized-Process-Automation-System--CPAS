<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auththird implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
            if(! session()->get('isLoggedIn') || ( session()->get('userclass') != 50 &&  session()->get('userclass') != 40)){
                return redirect()->to('http://localhost/Development/development/public/logout');
    }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}