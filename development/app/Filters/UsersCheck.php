<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Userscheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        // If segment 1 == users
        // we have to redirect the request to the second segment
        $uri = service('uri');
        if($uri->getSegment(1) == 'users'){
            if($uri->getSegment(2) == '')
                $segment = 'http://localhost/Development/development/public/';
            else
                $segment ='http://localhost/Development/development/public/'. $uri->getSegment(2);
             return redirect()->to('$segment');
            
        
        }
        
    

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}