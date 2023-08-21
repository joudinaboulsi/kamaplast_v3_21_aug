<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\StockRepository;
use Auth;

class StockController extends Controller
{
   
    public function __construct(StockRepository $stockRepository)
    {
        $this->stockRepository = $stockRepository;
        $this->middleware('auth:admin');
    }

    
    //Shows the stock integration page
    public function index()
    {
        return view('cms/stocks/index');
    }


    // update stock from excel file
    public function updateStock(Request $request)
    {
       if($request->hasFile('excel'))
       {   
         $file = $request->file('excel');
         
          //Display File Extension
          $extension = $file->getClientOriginalExtension();

          if($extension != 'csv')
          {
              $response['status'] = 'error';
              $response['message'] = 'Only .csv files are allowed to be uploaded';
          }

          else
          {
             // get the file name
             $filename = $file->getClientOriginalName();

             // create file path
             $destinationPath = 'excel';
             $path = $destinationPath.'/'.$filename;

             //Upload File to server
             $file->move($destinationPath, 'stock.csv');
            
             // open the uploaded file and loop it         
             $handle = fopen('excel/stock.csv', "r");
             $header = true;

             while ($csvLine = fgetcsv($handle, 1000, ",")) 
             {
                 if ($header) {
                     $header = false;
                 } else {
                    // update the stock of every iteration
                     $this->stockRepository->updateStockValue($csvLine[0], $csvLine[1]);
                 }
             }
             
            // delete file from server 
           // unlink('excel/stock.csv');

            $response['status'] = 'success';
            $response['message'] = 'Stock successfully updated';

            return view('cms/stocks/index', array('response' => $response));
          }
       }

       else 
       {
        $response['status'] = 'error';
        $response['message'] = 'Please upload a .cvs file for stock integration';
       }

       return view('cms/stocks/index', array('response' => $response));

    }


}