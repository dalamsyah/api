<?php

namespace App\Http\Controllers;
use App\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MasterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function insertUsers($password){

        $password = Crypt::encrypt($password)

        return $password;
    }

    public function getLapangan(){
        //$data = Lapangan::all();
        return $results = app('db')->select("SELECT * FROM lapangan");
    }
    public function show($id){
        $data = Lapangan::where('id',$id)->get();
        return response ($data);
    }

    //
}
