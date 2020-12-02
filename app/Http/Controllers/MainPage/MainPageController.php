<?php

namespace App\Http\Controllers\MainPage;

use App\AboutUs;
use App\Boarder;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Massage;
use Illuminate\Http\Request;

class MainPageController extends ApiController
{
    public function mainPageInformation(){

        $massages = Massage::all();
        $aboutUs = AboutUs::all();
        $boarders = Boarder::all();

        $data['massages'] = $massages ;
        $data['aboutUs'] = $aboutUs;
        $data['boarders'] = $boarders;

        return $this->showList($data);
    }
}
