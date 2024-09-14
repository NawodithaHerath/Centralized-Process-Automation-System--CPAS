<?php

namespace App\Controllers;

use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\CityModel;

class Country extends BaseController
{
    public function index()
    {
        $data = [
            'meta_titile' => 'New Country',
            'title' => ' This is new Country'
        ];

        if($this->request->getMethod()=='post'){
            $model = new CountryModel();
            $model->save($_POST);
        }

        echo view('templates/header',$data);
        echo view('new_country');
        
    }

    public function state()
    {
        $data = [
            'meta_titile' => 'New Country',
            'title' => ' This is new Country'
        ];

        if($this->request->getMethod()=='post'){
            $model = new StateModel();
            $model->save($_POST);
        }

        echo view('templates/header',$data);
        echo view('new_state');
        
    }

    public function city()
    {
        $data = [
            'meta_titile' => 'New Country',
            'title' => ' This is new Country'
        ];

        if($this->request->getMethod()=='post'){
            $model = new CityModel();
            $model->save($_POST);
        }

        echo view('templates/header',$data);
        echo view('new_city');
        
    }
}
