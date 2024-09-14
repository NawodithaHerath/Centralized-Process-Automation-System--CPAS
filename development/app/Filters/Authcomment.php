<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AuditTeamMember;

class Authcomment implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    
    {
        // Do something here
        $EmpNo = session()->get('EmpNo');
        $auditid = session()->get('auditid');
        $asssingaudit =  $auditid.'_'.$EmpNo;
        $assignavailable = new AuditTeamMember();
        $auditcreationdetails = $assignavailable->where('auditassignedid',$asssingaudit)->first();

        if(! session()->get('isLoggedIn') || session()->get('userclass') != 80 || !$auditcreationdetails){

            return redirect()->to('http://localhost/Development/development/public/logout');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}