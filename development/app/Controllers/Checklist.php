<?php

namespace App\Controllers;

use App\Models\ChecklistModel;
use App\Models\MainCheckArea;
use App\Models\FirstSubCheckArea;
use App\Models\CheckingItem;
use App\Models\CommentsModel;


class Checklist extends BaseController
{
    public function index(){
        $data = [];

        echo view('templates/header',$data);
        echo view('checklist/checklistcontrol');
        echo view('templates/footer');

    }

    public function mainChecklist()
    {
        $data=[];

        helper(['form']);

        if($this->request->getMethod()== 'post'){
            $model = new ChecklistModel();
            $rules = [
                'checklist_id'=>[
                   'rules'=> 'required|min_length[11]',
                   'label'=> 'Checklist ID',
                   'errors'=> [
                        'required'=>'Checklist ID should be added',
                   ]
                ],
                'audit_category'=>[
                    'rules'=> 'in_list[branch,department]',
                    'label'=> 'Audit Category ',
                    'errors'=> [
                         'in_list'=>'Audit Category should be Selected',
                    ]
                    ],
                'audit_type'=>[
                    'rules'=> 'in_list[onsite,offsite]',
                    'label'=> 'Audit Type',
                    'errors'=> [
                             'in_list'=>'Audit Type should be Selected',
                        ]
                        ],
                'audit_area'=>[
                    'rules'=> 'in_list[operation,credit,general]',
                    'label'=> 'Audit Type',
                    'errors'=> [
                             'in_list'=>'Audit Area should be Selected',
                        ]
                        ],
                            'checklist_id'=>[
                   'rules'=> 'required|min_length[11]',
                   'label'=> 'Checklist ID',
                   'errors'=> [
                        'required'=>'Checklist ID should be added',
                        ]
                        ],
                    'checklist_description'=>[
                    'rules'=> 'required|min_length[5]',
                    'label'=> 'Checklist Description',
                    'errors'=> [
                                 'required'=>'Checklist description  should be added',
                            ]
                         ],
                ];

                if(! $this->validate($rules)){
                    $data['validation'] = $this->validator;
                }else{

            $newdata = [
                'checklist_id' => $this->request->getVar('checklist_id'),
                'audit_category' => $this->request->getVar('audit_category'),
                'audit_type' => $this->request->getVar('audit_type'),
                'audit_area' => $this->request->getVar('audit_area'),
                'checklist_description' => $this->request->getVar('checklist_description'),
                'created_by' => session()->get('EmpNo'),  
            ];
            $model->save($newdata);
            $session = session();
            $session->setFlashdata('success','Successfull Checklist Creation');
            return redirect()->to('http://localhost/Development/development/public/mainChecklist');
        }
    }

        $model = new ChecklistModel();

        $data['mainchecklist'] = $model->findAll();
        
        echo view('templates/header',$data);
        echo view('checklist/main');
        echo view('checklist/mainsummary');
        echo view('templates/footer');
    }

    public function mainChecklistEdit($id = null)
    {
     
        $data=[];

        helper(['form']);

        if($this->request->getMethod()== 'post'){
            $model = new ChecklistModel();
            $rules = [
                'checklist_id'=>[
                   'rules'=> 'required|min_length[11]',
                   'label'=> 'Checklist ID',
                   'errors'=> [
                        'required'=>'Checklist ID should be added',
                   ]
                ],
                'audit_category'=>[
                    'rules'=> 'in_list[branch,department]',
                    'label'=> 'Audit Category ',
                    'errors'=> [
                         'in_list'=>'Audit Category should be Selected',
                    ]
                    ],
                'audit_type'=>[
                    'rules'=> 'in_list[onsite,offsite]',
                    'label'=> 'Audit Type',
                    'errors'=> [
                             'in_list'=>'Audit Type should be Selected',
                        ]
                        ],
                'audit_area'=>[
                    'rules'=> 'in_list[operation,credit,general]',
                    'label'=> 'Audit Type',
                    'errors'=> [
                             'in_list'=>'Audit Area should be Selected',
                        ]
                        ],
                'checklist_description'=>[
                    'rules'=> 'required|min_length[5]',
                    'label'=> 'Checklist Description',
                    'errors'=> [
                                'required'=>'Checklist description  should be added',
                        ]
                        ],
                ];
                if(! $this->validate($rules)){
                    $data['validation'] = $this->validator;

                    $data['mainChecklistEdit'] = $model->find($id);
                    echo view('templates/header',$data);
                    echo view('checklist/updatemain');
                    echo view('templates/footer');
                }else{
                    $data = [
                'checklist_id' => $this->request->getPost('checklist_id'),
                'audit_category' => $this->request->getPost('audit_category'),
                'audit_type' => $this->request->getPost('audit_type'),
                'audit_area' => $this->request->getPost('audit_area'),
                'checklist_description' => $this->request->getVar('checklist_description'),
                'updated_by' => session()->get('EmpNo'), 
            ];

            $model->update($id, $data);
            $session = session();
            $session->setFlashdata('success','Successfull Checklist Upgration');
            return redirect()->to('http://localhost/Development/development/public/mainChecklist');
        } 

         } else {

        $model = new ChecklistModel();
        $data['mainChecklistEdit'] = $model->find($id);
        echo view('templates/header',$data);
        echo view('checklist/updatemain');
        echo view('templates/footer');
        }
    }
    
    public function mainChecklistDelete($id = null){
       
        $model1 = new MainCheckArea();
        
        $subarea = $model1->where('checklist_id',$id)->first();
        if ($subarea){
            $session = session();
            $session->setFlashdata('Unable','Sub Main Area availble. Before delete Main Checklist, Delete sub Main Area of Main Checklist');
            return redirect()->to('http://localhost/Development/development/public/mainChecklist');

        }else{
        $model2 = new ChecklistModel();
        $model2->delete($id);
        $model2->where('checklist_id',$id)->delete();
        $session = session();
        $session->setFlashdata('success','Successfull Checklist Delete');
        return redirect()->to('http://localhost/Development/development/public/mainChecklist');
    }
    }

    public function mainAraAdding($id = null){


        $data=[];

        helper(['form']);

        if($this->request->getMethod()== 'post'){
            $rules = [
                'checklist_id'=>[
                   'rules'=> 'required',
                   'label'=> 'Mainarea ID',
                   'errors'=> [
                        'required'=>'Checklist ID should be added',
                   ]
                ],
                'mainarea_id'=>[
                    'rules'=> 'required|min_length[3]|validateMainArea[mainarea_id]',
                    'label'=> 'Main Audit Area ',
                    'errors'=> [
                         'required'=>'Main Audit Area ID should be entered',
                         'validateMainArea' => 'Main Checklist Area Id should be new one'
                    ]
                    ],
                    'mainarea_description'=>[
                        'rules'=> 'required|min_length[5]',
                        'label'=> 'Main Audit Area ',
                        'errors'=> [
                             'required'=>'Main Audit Area Description should be entered',
                             'min_length'=> 'Proper Audit Are description should be entered'
                        ]
                        ]
                ];
                if(! $this->validate($rules)){
                    $data['validation'] = $this->validator;
                }else{
                    $data = [
                    'checklist_id' => $this->request->getPost('checklist_id'),
                    'mainarea_id' => $this->request->getPost('mainarea_id'),
                    'mainarea_description' => $this->request->getPost('mainarea_description'),
                    'created_by' => session()->get('EmpNo'),
                ];
                $model = new MainCheckArea();
                $model->save($data);
                $session = session();
                $session->setFlashdata('success','Successfully Main Area Added ');
                // return redirect()->to('http://localhost/Development/development/public/mainChecklistEdit/'.$id);
                }
                }   
        $model1 = new ChecklistModel();
        $model2 = new MainCheckArea();
        $data['checklist_id'] = $id  ;
        $data['maincheckarea'] = $model2->where('checklist_id',$id)->findall(); 
        echo view('templates/header',$data);
        echo view('checklist/maincheckarea');
        echo view('checklist/mainCheckAreaSummary');
        echo view('templates/footer');
    }

    public function mainAraEdit($id){

        $data = [];
        helper(['form']);
        
        if($this->request->getMethod()== 'post'){
            $rules = [
                'checklist_id'=>[
                   'rules'=> 'required',
                   'label'=> 'Mainarea ID',
                   'errors'=> [
                        'required'=>'Checklist ID should be added',
                   ]
                ],
                'mainarea_id'=>[
                    'rules'=> 'required|min_length[3]|validateMainAreaAvailable[mainarea_id]',
                    'label'=> 'Main Audit Area ',
                    'errors'=> [
                         'required'=>'Main Audit Area ID should be entered',
                         'validateMainArea' => 'Main Checklist Area Id should be new one',
                         'validateMainAreaAvailable' => 'Correct Main Are ID should be entered'
                    ]
                    ],
                    'mainarea_description'=>[
                        'rules'=> 'required|min_length[5]',
                        'label'=> 'Main Audit Area ',
                        'errors'=> [
                             'required'=>'Main Audit Area Description should be entered',
                             'min_length'=> 'Proper Audit Are description should be entered'
                        ]
                        ]
                ];  
                if(! $this->validate($rules)){
                    $data['validation'] = $this->validator;
                }else{
                    $data = [
                        'checklist_id' => $this->request->getPost('checklist_id'),
                        'mainarea_id' => $this->request->getPost('mainarea_id'),
                        'mainarea_description' => $this->request->getPost('mainarea_description'),
                        'updated_by' => session()->get('EmpNo'),
                    ];
                    $model = new MainCheckArea();
                    // $model->update($id,$data);
                    $model->where('mainarea_id', $data['mainarea_id'])->set($data)->update();
                    $session = session();
                    $session->setFlashdata('success','Successfully Main Area Upgration ');

                }
        }

        $model2 = new MainCheckArea();
        $data['maincheckarea'] = $model2->where('mainarea_id',$id)->first(); 
        echo view('templates/header',$data);
        echo view('checklist/maincheckareaEdit');
        echo view('templates/footer');
    }

    public function mainAreaDeleted($id){

        
        $model1 = new MainCheckArea();
        $model2 = new FirstSubCheckArea();
        
        $subarea = $model2->where('mainarea_id',$id)->first();
        if ($subarea){
            $session = session();
            $session->setFlashdata('Unable','First  Sub Check Area availble. Before delete Main Area , Delete First Sub Check    Area of Main Area');
            $data = $model1->where('mainarea_id',$id)->first();
            return redirect()->to('http://localhost/Development/development/public/mainAraAdding/'.$data['checklist_id']);

        }else{
            $data = $model1->where('mainarea_id',$id)->first();
            $model1->where('mainarea_id', $data['mainarea_id'])->delete();
            $session = session();
            $session->setFlashdata('success','Successfull Checklist Delete');
            return redirect()->to('http://localhost/Development/development/public/mainAraAdding/'.$data['checklist_id']);
        }
    }

    public function firstSubArea($id){
        $data = [];
        helper(['form']);
        
        if($this->request->getMethod()== 'post'){
            $rules = [
                'mainarea_id'=>[
                   'rules'=> 'required',
                   'label'=> 'Mainarea ID',
                   'errors'=> [
                        'required'=>'Mainarea ID should be added',
                   ]
                ],
                'firstsubarea_id'=>[
                    'rules'=> 'required|min_length[10]|FirstCheckArea[firstsubarea_id]',
                    'label'=> 'Main Audit Area ',
                    'errors'=> [
                         'required'=>'First Sub Check Area ID should be entered',
                         'min_length' => 'Proper First Sub Check Area ID should be entered',
                         'FirstCheckArea' => 'First Sub Check Area ID should be new one'
                    ]
                    ],
                    'firstsubarea_description'=>[
                        'rules'=> 'required|min_length[10]',
                        'label'=> 'Main Audit Area ',
                        'errors'=> [
                             'required'=>'First Sub Check Area Description should be entered',
                             'min_length'=> 'Proper First Sub Check Area description should be entered'
                        ]
                        ]
                ];  
                if(! $this->validate($rules)){
                    $data['validation'] = $this->validator;
                }else{
                    $data = [
                    'mainarea_id' => $this->request->getPost('mainarea_id'),
                    'firstsubarea_id' => $this->request->getPost('firstsubarea_id'),
                    'firstsubarea_description' => $this->request->getPost('firstsubarea_description'),
                    'created_by' => session()->get('EmpNo'),
                ];
                $model = new FirstSubCheckArea();
                $model->save($data);
                $session = session();
                $session->setFlashdata('success','Successfully first check sub area Added ');
                }
            }
        $model1 = new MainCheckArea();
        $model2 = new FirstSubCheckArea();
        $data['mainarea_id'] = $id  ;
        $data['firstsubarea'] = $model2->where('mainarea_id',$id)->findall();
        $data['maincheckarea'] = $model1->where('mainarea_id',$id)->first(); 

        echo view('templates/header',$data);
        echo view('checklist/firstSubArea');
        echo view('checklist/firstCheckAreaSummary');
        echo view('templates/footer');
    }
    public function firstSubAreaEdit($id){
        

        $data = [];
        helper(['form']);
        if($this->request->getMethod()== 'post'){
            $rules = [
                'mainarea_id'=>[
                   'rules'=> 'required',
                   'label'=> 'Mainarea ID',
                   'errors'=> [
                        'required'=>'Mainarea ID should be added',
                   ]
                ],
                'firstsubarea_id'=>[
                    'rules'=> 'required|min_length[10]|FirstCheckAreaAvailable[firstsubarea_id]',
                    'label'=> 'Main Audit Area ',
                    'errors'=> [
                         'required'=>'First Sub Check Area ID should be entered',
                         'min_length' => 'Proper First Sub Check Area ID should be entered',
                         'FirstCheckAreaAvailable' => 'First Sub Check Area ID should be an existing one'
                    ]
                    ],
                    'firstsubarea_description'=>[
                        'rules'=> 'required|min_length[10]',
                        'label'=> 'Main Audit Area ',
                        'errors'=> [
                             'required'=>'First Sub Check Area Description should be entered',
                             'min_length'=> 'Proper First Sub Check Area description should be entered'
                        ]
                        ]
                ];  
                if(! $this->validate($rules)){
                    $data['validation'] = $this->validator;
                }else{
                    $data = [
                    'mainarea_id' => $this->request->getPost('mainarea_id'),
                    'firstsubarea_id' => $this->request->getPost('firstsubarea_id'),
                    'firstsubarea_description' => $this->request->getPost('firstsubarea_description'),
                    'updated_by' => session()->get('EmpNo'),
                ];                   
                 $model = new FirstSubCheckArea();
                // $model->update($id,$data);
                $model->where('firstsubarea_id', $data['firstsubarea_id'])->set($data)->update();
                $session = session();
                $session->setFlashdata('success','Successfully First Sub Check Area Upgration ');

                }
            }
            $model1 = new MainCheckArea();
            $model2 = new FirstSubCheckArea();
            $data['firstsubarea_id'] = $id  ;
            $data['firstsubcheckarea'] = $model2->where('firstsubarea_id',$id)->first();
            $data['maincheckarea'] = $model1->where('mainarea_id',$data['firstsubcheckarea']['mainarea_id'])->first();
            echo view('templates/header',$data);
            echo view('checklist/firstSubAreaEdit');
            echo view('templates/footer');

        }

        public function firstSubAreaDeleted($id){
            echo $id;

            $model1 = new FirstSubCheckArea();
            $model2 = new CheckingItem();
            
            $subarea = $model2->where('firstsubarea_id',$id)->first();
            if ($subarea){
                $session = session();
                $session->setFlashdata('Unable','First  Sub Check Area availble. Before delete Main Area , Delete First Sub Check Area of Main Area');
                $data = $model1->where('firstsubarea_id',$id)->first();
                return redirect()->to('http://localhost/Development/development/public/firstSubArea/'.$data['mainarea_id']);
    
            }else{
                $data = $model1->where('firstsubarea_id',$id)->first();
                $model1->where('firstsubarea_id', $data['firstsubarea_id'])->delete();
                $session = session();
                $session->setFlashdata('success','Successfull Checklist Delete');
                return redirect()->to('http://localhost/Development/development/public/firstSubArea/'.$data['mainarea_id']);
            }

        }


        public function checkingItem($id){

            $data = [];
            helper(['form']);

            if($this->request->getMethod()== 'post'){
                $rules = [
                    'firstsubarea_id'=>[
                       'rules'=> 'required',
                       'label'=> 'Firstsubarea ID',
                       'errors'=> [
                            'required'=>'First Subarea ID should be added',
                       ]
                    ],
                    'checkingitem_id'=>[
                        'rules'=> 'required|min_length[10]|checkingItem[checkingitem_id]',
                        'label'=> 'Checking Item ',
                        'errors'=> [
                             'required'=>'Checking Item ID should be entered',
                             'min_length' => 'Proper checking item ID should be entered',
                             'checkingItem' => 'Checking item ID should be new one'
                        ]
                        ],
                        'checkingitem_description'=>[
                            'rules'=> 'required|min_length[10]',
                            'label'=> 'Main Audit Area ',
                            'errors'=> [
                                 'required'=>'Checking item description should be entered',
                                 'min_length'=> 'Proper checking item description should be entered'
                            ]
                            ]
                    ];  
                    if(! $this->validate($rules)){
                        $data['validation'] = $this->validator;
                    }else{
                        $data = [
                            'firstsubarea_id' => $this->request->getPost('firstsubarea_id'),
                            'checkingitem_id' => $this->request->getPost('checkingitem_id'),
                            'checkingitem_description' => $this->request->getPost('checkingitem_description'),
                            'created_by' => session()->get('EmpNo'),
                        ];
                            $model = new CheckingItem();
                            $model->save($data);
                            $session = session();
                            $session->setFlashdata('success','Successfully checking item added ');
                    }
                }
            $model1 = new FirstSubCheckArea();
            $model2 = new CheckingItem();
            $data['firstsubarea_id']=$id;

                $data['checkingitem'] = $model2->where('firstsubarea_id',$id)->findall();
                $data['firstsubarea'] = $model1->where('firstsubarea_id',$id)->first();     
            
            echo view('templates/header',$data);
            echo view('checklist/checkingItem');
            echo view('checklist/checkingitemSummary');
            echo view('templates/footer');
            
        }

        public function checkingItemEdit($id){
            $data = [];
            helper(['form']);

            if($this->request->getMethod()== 'post'){
                $rules = [
                    'firstsubarea_id'=>[
                       'rules'=> 'required',
                       'label'=> 'Firstsubarea ID',
                       'errors'=> [
                            'required'=>'First Subarea ID should be added',
                       ]
                    ],
                    'checkingitem_id'=>[
                        'rules'=> 'required|min_length[10]|checkingItemAvailable[checkingitem_id]',
                        'label'=> 'Checking Item ',
                        'errors'=> [
                             'required'=>'Checking Item ID should be entered',
                             'min_length' => 'Proper checking item ID should be entered',
                             'checkingItemAvailable' => 'Checking item ID should be an existing one'
                        ]
                        ],
                        'checkingitem_description'=>[
                            'rules'=> 'required|min_length[10]',
                            'label'=> 'Main Audit Area ',
                            'errors'=> [
                                 'required'=>'Checking item description should be entered',
                                 'min_length'=> 'Proper checking item description should be entered'
                            ]
                            ]
                    ];  
                    if(! $this->validate($rules)){
                        $data['validation'] = $this->validator;
                    }else{

                        $data = [
                            'firstsubarea_id' => $this->request->getPost('firstsubarea_id'),
                            'checkingitem_id' => $this->request->getPost('checkingitem_id'),
                            'checkingitem_description' => $this->request->getPost('checkingitem_description'),
                            'updated_by' => session()->get('EmpNo'),
                        ];

                        $model = new CheckingItem();
                        // $model->update($id,$data);
                        $model->where('checkingitem_id', $data['checkingitem_id'])->set($data)->update();
                        $session = session();
                        $session->setFlashdata('success','Successfully Checking Item Upgration');
                    }
                }

                $model1 = new FirstSubCheckArea();
                $model2 = new CheckingItem();
                
                $data['checkingitem_id'] = $id;  ;
                $data['checkingitem'] = $model2->where('checkingitem_id',$id)->first();
                $data['firstsubcheckarea'] = $model1->where('firstsubarea_id',$data['checkingitem']['firstsubarea_id'])->first();

            
            echo view('templates/header',$data);
            echo view('checklist/checkingItemEdit');
            echo view('templates/footer');

        }

        public function checkingItemDeleted($id){            
            $commentcheckitem = new CommentsModel();
            $checkingitemset  = $commentcheckitem->where('checkingitem_id',$id)->first();
            if(isset($checkingitemset)){
                $model1 = new CheckingItem();
                $data = $model1->where('checkingitem_id',$id)->first();
                $session = session();
                $session->setFlashdata('Unable','Comments were already added for this checking item');
                return redirect()->to('http://localhost/Development/development/public/checkingItem/'.$data['firstsubarea_id']);
            }else{
                echo "Unable',''Successfull Checklist Delete";
                $model1 = new CheckingItem();
                $data = $model1->where('checkingitem_id',$id)->first();
                $model1->where('checkingitem_id', $data['checkingitem_id'])->delete();
                $session = session();
                $session->setFlashdata('success','Successfull Checklist Delete');
                return redirect()->to('http://localhost/Development/development/public/checkingItem/'.$data['firstsubarea_id']);
            }
           

        }

}