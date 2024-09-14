<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        $data=[];

        helper(['form']);

        if($this->request->getMethod()== 'post'){
            //Let's do the validation here
            $rules =[
                'Email' => 'required|min_length[8]|max_length[50]|valid_email',
                'Password'=> 'required|min_length[8]|max_length[255]|validateUser[email,password]',
            ];
            $errors = [
                'Password' =>[
                    'validateUser' => ' Email or Password don\'t match'
                ]
            ];

        if(! $this->validate($rules,$errors)){
            $data['validation'] = $this->validator;
        }else{
            //store the user in our database
            $model = new UserModel();
            $user = $model->where('Email',$this->request->getVar('Email'))
                                ->first();
            $this->setUserSession($user);
            $session = session();
            if ($user['userclass'] == 100){
            //$session->setFlashData('success','successful Registration');
                return redirect()->to('http://localhost/Development/development/public/dashboard');
                
            }elseif($user['userclass'] == 80){
                return redirect()->to('http://localhost/Development/development/public/AuditFieldWork/'.$user['EmpNo']);

            }elseif($user['userclass'] == 50){
                return redirect()->to('http://localhost/Development/development/public/AuditReplying/'.$user['EmpNo']);
                
            }elseif($user['userclass'] == 40){
                return redirect()->to('http://localhost/Development/development/public/AuditReplying/'.$user['EmpNo']);
            }
            elseif($user['userclass'] == 30){
                return redirect()->to('http://localhost/Development/development/public/AuditReplyingOfficerDashboard/'.$user['EmpNo']);
            }

            }
        }
        echo view('templates/header',$data);
        echo view('users/login');
        echo view('templates/footer');
    }


    private function setUserSession($user){
        $data = [
            'EmpNo' => $user['EmpNo'],
            'FirstName' => $user['FirstName'],
            'LastName' => $user['LastName'],
            'Email' => $user['Email'],
            'userclass' => $user['userclass'],
            'isLoggedIn' => true,
        ];
        session()->set($data);
        return true;
    }

    public function register(){

        $data=[];
        helper(['form']);

        if($this->request->getMethod()== 'post'){
            //Let's do the validation here
            $rules =[
                'FirstName'=>'required|min_length[3]|max_length[20]',
                'LastName'=>'required|min_length[3]|max_length[20]',
                'Email' => [
                            'rules'=>'required|min_length[8]|max_length[50]|valid_email|is_unique[employee.email]',
                            'errors'=> ['is_unique'=>' Already user email is available']],
                'Password'=> 'required|min_length[8]|max_length[255]',
                'Password_Confirm' =>
                                ['rules'=>'matches[Password]',
                                'errors'=> ['matches'=>' Password Confirmation does not match with Password field']],
            ];

        if(! $this->validate($rules)){
            $data['validation'] = $this->validator;
        }else{
            //store the user in our database
            $model = new UserModel();
            $newdata = [
                'FirstName' => $this->request->getVar('FirstName'),
                'LastName' => $this->request->getVar('LastName'),
                'Email' => $this->request->getVar('Email'),
                'Password' => $this->request->getVar('Password'),
                'userclass' =>"", 
            ];
            $model->save($newdata);
            $session = session();
            $session->setFlashdata('success','Successfull Registration');
            return redirect()->to('http://localhost/Development/development/public/');
        }

        }
        
        echo view('templates/header',$data);
        echo view('users/register');
        echo view('templates/footer');

    }

    public function profile(){
        $data=[];
        helper(['form']);
        $model = new UserModel();

        if($this->request->getMethod()== 'post'){
            //Let's do the validation here
            $rules =[
                'FirstName'=>'required|min_length[3]|max_length[20]',
                'LastName'=>'required|min_length[3]|max_length[20]',
            ];

            if($this->request->getPost('Password') != ''){
                $rules['Password'] = 'required|min_length[8]|max_length[255]';
                $rules['Password_Confirm'] = 'matches[Password]';
            }

        if(! $this->validate($rules)){
            $data['validation'] = $this->validator;
        }else{
            //store the user in our database

            $newdata = [
                'EmpNo' => session()->get('EmpNo'),
                'FirstName' => $this->request->getPost('FirstName'),
                'LastName' => $this->request->getPost('LastName'),
                'Email' => $this->request->getPost('Email'),
            ];
            if($this->request->getPost('Password') != ''){
                $newdata['Password'] = $this->request->getPost('Password');
            }

            // $model->save($newdata);
            $model->where('Email', $newdata['Email'])->set($newdata)->update();
            session()->setFlashdata('success','Successfuly Updated');
            return redirect()->to('http://localhost/Development/development/public/profile');
         }

        }

        $data['user'] = $model->where('EmpNo',session()->get('EmpNo'))->first();

        echo view('templates/header',$data);
        echo view('users/profile');
        echo view('templates/footer');

    }

    public function logout(){
        session()->destroy();
        return redirect()->to('http://localhost/Development/development/public/users');
    }



}
