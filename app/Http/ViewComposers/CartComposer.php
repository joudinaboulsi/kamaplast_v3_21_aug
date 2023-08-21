<?php

namespace App\Http\ViewComposers;

use App\Http\Repositories\Frontend\ShoppingCartApis;
use Auth;

class CartComposer
{
  
    public function __construct(ShoppingCartApis $shoppingCart)
    {
        $this->shoppingCart = $shoppingCart;
    }

    // count the number of items in the online and offline cart
    public function cartCount($view)
    {  

    	if(Auth::check())
    		$count = $this->shoppingCart->cartCount();
    	else
    		$count = \Cart::content()->count();
    		
        // share the count variable with all the views
        $view->with('count', $count);
    }

}