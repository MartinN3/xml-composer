<?php

namespace App\Http\Controllers;

use App\Validate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ValidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //Zeptat se jakej request
        //Ziskat data podle requestu
        //vratit data
      $systemName = request('systemName');
      $validationRequest = request('validationRequest');

      if ( null === $systemName ) {
        die('Unspecified `systemName` in request');
      }

      $validate = new Validate();

      switch ($validationRequest) {
         case 'validateLength':
           $validate->short_description();
           return back();   
           break;
         case 'test':
            $validate->test();
            return response()->download(storage_path(). '\app\export.xml', "export.xml");
            break;
         
         default:
           # code...
           break;
       } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
