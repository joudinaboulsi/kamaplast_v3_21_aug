<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\CareersRepository;
use Auth;

class CareersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CareersRepository $careersRepository)
    {
        $this->careersRepository = $careersRepository;
        $this->middleware('auth:admin');
    }


    // Shows the content of careers page
    public function index()
    {
        // Return about page content header
        $careers = $this->careersRepository->show();

        return view('cms/pages/careers/index', array('careers' => $careers));
    }


    // add a new career
    public function add(Request $request)
    {
        $this->careersRepository->add($request); 
        return redirect()->back();
    }


    // get the content from the id
    public function getCareersById(Request $request)
    {
        $info = $this->careersRepository->getCareerFromId($request->input('id'));

        // return the info to the ajax call
        return response()->json($info);   
    }
    


    // edit content
    public function edit(Request $request)
    {
        $this->careersRepository->update($request); 
        return redirect()->back();
    }



    // delete content
    public function delete(Request $request)
    {
        // Deleting a news tag
        $this->careersRepository->delete($request); 
        // return the info to the ajax call
        return response()->json();
    }







}
