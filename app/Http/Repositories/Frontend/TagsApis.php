<?php 

namespace App\Http\Repositories\Frontend;

use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\QueryException;

class TagsApis {
    
    /*----------------------------------
    get collections
    ------------------------------------*/
    public function getCollections($limit)
    {
        $query = 'SELECT *
                  FROM tags 
                  WHERE is_collection = 1
                  AND type_id = 1
                  AND hidden = 0';

        // if we want to limit the number of categories to be display, $limit = [value]
      if($limit != false)
      {
         $query = $query.' LIMIT 0, :limit';
         $bind[':limit'] = $limit;

          $q = \DB::select(
            \DB::raw($query),$bind
        );
      }
      else // if we don't want to limit the number of categories to be display, $limit = false
      {
        //execute the built query without bind
        $q = \DB::select(
            \DB::raw($query)
        );    
      }      

        return $q;     
    }



     /*----------------------------------
    get tag details from tag_id
    ------------------------------------*/
    public function getTagDetailsFromId($tag_id)
    {
       $q = \DB::select("SELECT * 
                          FROM tags 
                          WHERE tag_id=:tag_id
                          AND hidden=0",
            array(':tag_id' => $tag_id));

        return $q;
    }

}
