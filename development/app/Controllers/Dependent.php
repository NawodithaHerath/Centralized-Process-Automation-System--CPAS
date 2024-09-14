<?php

namespace App\Controllers;

use App\Models\CommonModel;

class Dependent extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new CommonModel();
    }

    public function index()
    {

        $country = $this->model->selectData('country');
        $data['country'] = $country;
        return view('dependent',$data);
        
    }

    public function countries(){
        $countryData = $this->model->selectData("country");
        $output = "<option value='0'>Select Country</option>";
        foreach($countryData as $country){
            $output .= "<option value='$country->country_id'>$country->country_name</option>";
        }
        echo json_encode($output);

    }

    public function states(){
        $country_id = $this->request->getPost("cId");
        $stateData = $this->model->selectData("state",array("country_id"=>$country_id));
        $output = "<option value='0'>Select State/Province</option>";
        foreach($stateData as $state){
            $output .= "<option value='$state->state_id'>$state->state_name</option>";
        }
        echo json_encode($output);

    }

    public function cities(){
        $state_id = $this->request->getPost("sId");
        $cityData = $this->model->selectData("city",array("state_id"=>$state_id));
        $output = "<option value='0'>Select City</option>";
        foreach($cityData as $city){
            $output .= "<option value='$city->city_id'>$city->city_name</option>";
        }
        echo json_encode($output);

    }

}
