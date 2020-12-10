<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Event;
use App\Events\SendMail;
class EventController extends Controller
{
    //
    public function index(){
        Event::dispatch(new SendMail(3));
        dd('hi');
    }
}
