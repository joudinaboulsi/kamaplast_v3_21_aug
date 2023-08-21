<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {   
        $this->CartCount();  
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

     // count the number of items in the cart
    public function cartCount()
    {  
        // call the composeSidebar method in the following path
        view()->composer('*', 'App\Http\ViewComposers\CartComposer@cartCount');
    }


}
