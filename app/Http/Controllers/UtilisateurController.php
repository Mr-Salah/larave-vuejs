<?php

namespace App\Http\Controllers;

use App\Utilisateur;
use Illuminate\Http\Request;
use App\Http\Resources\Utilisateur as UtilisateurResource ;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $utls = Utilisateur::all();

        return $utls;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('home',["show"=>"add"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $utl = new Utilisateur();

         $utl->name = $request->input("name");
         $utl->lastname = $request->input("lastname");
         $utl->email = $request->input("email");
         $utl->biographie = $request->input("biographie");
         $utl->url = $request->img->store('imgs');

         $utl->save();
        
         return 'ok from server | store ';

        //return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Utilisateur  $utilisateur
     * @return \Illuminate\Http\Response
     */
    public function show(Utilisateur $utilisateur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Utilisateur  $utilisateur
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Utilisateur::find($id);              
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Utilisateur  $utilisateur
     * @return \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {
        $utl = Utilisateur::find($id);

        $utl->name = $request->input("name");
        $utl->lastname = $request->input("lastname");
        $utl->email = $request->input("email");
        $utl->biographie = $request->input("biographie");

        if($request->hasfile('url'))
            $utl->url = $request->url->store('imgs');

        $utl->save();


        return "ok  from serve | update id =  ".$request->input("email");
        // return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Utilisateur  $utilisateur
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $utilisateur =Utilisateur::find($id);

        $utilisateur->delete();

        return "ok from serve | delete id = $id ";    
    }


    public function login(Request $request)
    {
        $user = DB::table('users')->where('email', $request->input('email'))->first();

        if ($user != null && Hash::check($request->input('password'), $user->password ))
            return json_encode($user);

        return json_encode(null);
    }
}
