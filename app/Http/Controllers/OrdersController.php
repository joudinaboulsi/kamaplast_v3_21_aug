<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\OrdersRepository;
use App\Http\Controllers\S3bucketController;
use Auth;

class OrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(S3bucketController $s3bucketController,OrdersRepository $ordersRepository)
    {
      $this->OrdersRepository = $ordersRepository;
      $this->S3bucketController = $s3bucketController;
      $this->middleware('auth:admin');
    }
    
    //Shows the list of orders
    public function index()
    {
      return view('cms/orders/index');
    }

    //Shows the list of orders
    public function loadOrdersTable()
    {
      // Returns the list of orders
      $orders = $this->OrdersRepository->show();
      return datatables($orders)->make(true);
    }

    //Shows the details od an order
    public function showDetails($order_id)
    {
      //Returns the details of specific order
      $order_details = $this->OrdersRepository->showDetails($order_id);

      //Returns the list of Payment Statuses
      $payment_status = $this->OrdersRepository->getPaymentStatuses();

      //Returns the list of Shipping Statuses
      $shipping_status = $this->OrdersRepository->getShippingStatuses();

      //Returns the list of Order Items
      $order_items = $this->OrdersRepository->getOrderItems($order_id);

      //Returns the list of addresses of the order user
      $addrList = $this->OrdersRepository->getAddrList($order_id);

      return view('cms/orders/order-details', array('order_details' => $order_details, 'payment_status' => $payment_status, 'shipping_status' => $shipping_status, 'order_items' => $order_items, 'addrList' => $addrList));
    }



    //Allows to update Shipping Address of an order
    public function updateShippingAddress(Request $request)
    {
        // Adding an address to user
        $this->OrdersRepository->updateShippingAddress($request); 
        return redirect()->back();
    }

    
    //Allows to update order payment and delivery statuses
    public function updateOrder(Request $request)
    {
        // updating an order
        $this->OrdersRepository->updateOrder($request); 
        return redirect()->back();
    }



}
