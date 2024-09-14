<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AuditReplyingModel;

class AuthcommentReplying implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    
    {
        // Do something here
        $EmpNo = session()->get('EmpNo');
        $newauditid = session()->get('auditid');
        $auditreplying = new AuditReplyingModel();
        
        $auditcreationData = $auditreplying->allCommentNeedReplying("auditcreation",array("auditid"=>$newauditid));
         $manager = $auditcreationData[0]['manager'];
         if(!session()->get('isLoggedIn') || session()->get('userclass') != 50 ||  $manager != $EmpNo){

            return redirect()->to('http://localhost/Development/development/public/logout');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}