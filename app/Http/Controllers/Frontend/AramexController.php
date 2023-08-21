<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Repositories\Frontend\CheckoutApis;


class AramexController extends Controller
{   

    public function __construct(CheckoutApis $checkoutApis)
    {
        $this->checkoutApis = $checkoutApis;  
    }


    // get aramex rate calculated from the gross weight ONLY
   	public function getAramexRate($gross_weight, $total_quantity)
    {
        $params = array(
            'ClientInfo'        => array(
                        'AccountCountryCode'  => 'LB',
                        'AccountEntity'     => 'BEY',
                        'AccountNumber'         => getenv("ARAMEX_ACCOUNT_ID"),
                        'AccountPin'            => getenv("ARAMEX_ACCOUNT_PIN"),
                        'UserName'        => getenv("ARAMEX_USERNAME"),
                        'Password'        => getenv("ARAMEX_PASSWORD"),
                        'Version'       => 'v1.0'
                      ),
                        
            'Transaction'       => array(
                          'Reference1'      => '001' 
                        ),
                        
            'OriginAddress'     => array(
                          'City'          => 'Beirut',
                          'CountryCode'        => 'LB',
                        ),
                        
            'DestinationAddress'  => array(
                          'City'          => 'Beirut',
                          'CountryCode'     => 'LB',
                        ),
            'ShipmentDetails'   => array(
                          'PaymentType'            => 'P',
                          'ProductGroup'           => 'DOM',
                          'ProductType'            => 'ONP',
                          'ActualWeight'       => array('Value' => $gross_weight, 'Unit' => 'KG'),
                          'ChargeableWeight'       => array('Value' => $gross_weight, 'Unit' => 'KG'),
                          'NumberOfPieces'     => $total_quantity
                        )
          );
       
          $soapClient = new \SoapClient(getenv("APP_URL").'/aramex-rates-calculator-wsdl.wsdl', array('trace' => 1));
          $results = $soapClient->CalculateRate($params); 

          return $results->TotalAmount->Value;
    }



    // get aramex cities
    public function getAramexCities()
    {
        $soapClient = new \SoapClient(getenv("APP_URL").'/aramex-locations-api-wsdl.wsdl', array('trace' => 1));

          $params = array(
          'ClientInfo'        => array(
                        'AccountCountryCode'  => 'LB',
                        'AccountEntity'     => 'BEY',
                        'AccountNumber'         => getenv("ARAMEX_ACCOUNT_ID"),
                        'AccountPin'            => getenv("ARAMEX_ACCOUNT_PIN"),
                        'UserName'        => getenv("ARAMEX_USERNAME"),
                        'Password'        => getenv("ARAMEX_PASSWORD"),
                        'Version'       => 'v1.0'
                      ),


          'Transaction'       => array(
                        'Reference1'      => '001',
                        'Reference2'      => '002',
                        'Reference3'      => '003',
                        'Reference4'      => '004',
                        'Reference5'      => '005'
                     
                      ),
          'CountryCode'     => 'LB',

          'State'       => NULL

          );
        
        // calling the method and printing results
        try {
          $list_of_cities = $soapClient->FetchCities($params);
          
          return $list_of_cities->Cities->string;

        } catch (SoapFault $fault) {
          die('Error : ' . $fault->faultstring);
        }
    }




     // Create aramex shipment
    public function createAramexShipment($cart_data)
    {
      // get last aramex Foregin ref 
      $last_foreign_ref = $this->checkoutApis->getLastAramexForeignRef();
      $foreign_ref = $last_foreign_ref+1; // increment the last forein ref

      $description = ''; 
      $gross_weight = 0;
      $total_quantity = 0;
      $item = array();

      foreach($cart_data['items'] as $c)
      {
        $description = $description.$c->product_name. ', '; // concatenate the description
        $gross_weight = $gross_weight + ($c->weight * $c->quantity); // calculating the gross weight of the order 
        $total_quantity = $total_quantity + $c->quantity; // calculating the items total quantity of the order
      }
      // remove the last coma from the concatenated string
      $description = rtrim($description, ', ');



      // --------- ARAMEX SHIPPING API --------------------//    
      $soapClient = new \SoapClient(getenv("APP_URL").'/aramex-shipping-services-api-wsdl.wsdl', array('trace' => 1));
     
      $params = array(
          'Shipments' => array(
            'Shipment' => array(
                'Shipper' => array(
                        'Reference1'  => '',
                        'Reference2'  => '',
                        'AccountNumber' => getenv("ARAMEX_ACCOUNT_ID"),
                        'PartyAddress'  => array(
                          'Line1'         => 'Beirut- Basta Al Tahta- Facing Haoud Al Wilaya Park',
                          'Line2'         => '',
                          'Line3'         => '',
                          'City'          => 'Beirut',
                          'StateOrProvinceCode' => '',
                          'PostCode'        => '',
                          'CountryCode'     => 'LB'
                        ),
                        'Contact'   => array(
                          'Department'      => '',
                          'PersonName'      => 'Malak Kabalan',
                          'Title'         => '',
                          'CompanyName'     => 'Kamaplast',
                          'PhoneNumber1'      => '+961 1 66 80 80',
                          'PhoneNumber1Ext'   => '',
                          'PhoneNumber2'      => '+961 7 22 22 00',
                          'PhoneNumber2Ext'   => '',
                          'FaxNumber'       => '',
                          'CellPhone'       => '+961 71 11 80 80',
                          'EmailAddress'      => 'info@kama-plast.com',
                          'Type'          => ''
                        ),
                ),
                            
                'Consignee' => array(
                        'Reference1'  => session('notes'),
                        'Reference2'  => '',
                        'AccountNumber' =>'',
                        'PartyAddress'  => array(
                          'Line1'         => session('address'),
                          'Line2'         => session('city').' - '.session('apartment').' - '. session('postal_code'),
                          'Line3'         => '',
                          'City'          => session('city'),
                          'StateOrProvinceCode' => '',
                          'PostCode'        => '',
                          'CountryCode'     => 'LB'
                        ),
                        
                        'Contact'   => array(
                          'Department'      => '',
                          'PersonName'      => session('fullname'),
                          'Title'         => '',
                          'CompanyName'     => session('company'),
                          'PhoneNumber1'      => session('address_phone'),
                          'PhoneNumber1Ext'   => '',
                          'PhoneNumber2'      => '',
                          'PhoneNumber2Ext'   => '',
                          'FaxNumber'       => '',
                          'CellPhone'       => '',
                          'EmailAddress'      => session('email'),
                          'Type'          => ''
                        ),
                ),
                
                'ThirdParty' => array(
                        'Reference1'  => '',
                        'Reference2'  => '',
                        'AccountNumber' => '',
                        'PartyAddress'  => array(
                          'Line1'         => '',
                          'Line2'         => '',
                          'Line3'         => '',
                          'City'          => '',
                          'StateOrProvinceCode' => '',
                          'PostCode'        => '',
                          'CountryCode'     => ''
                        ),
                        'Contact'   => array(
                          'Department'      => '',
                          'PersonName'      => '',
                          'Title'         => '',
                          'CompanyName'     => '',
                          'PhoneNumber1'      => '',
                          'PhoneNumber1Ext'   => '',
                          'PhoneNumber2'      => '',
                          'PhoneNumber2Ext'   => '',
                          'FaxNumber'       => '',
                          'CellPhone'       => '',
                          'EmailAddress'      => '',
                          'Type'          => ''             
                        ),
                ),
                
                'Reference1'        => $foreign_ref.'-'.time(),
                'Reference2'        => '',
                'Reference3'        => '',
                'ForeignHAWB'       => $foreign_ref,
                'TransportType'       => 0,
                'ShippingDateTime'      =>time(),
                'DueDate'         => time(),
                'PickupLocation'      => 'Reception',
                'PickupGUID'        => '',
                'Comments'          => session('notes'),
                'AccountingInstrcutions'  => '',
                'OperationsInstructions'  => '',
                
                'Details' => array(
                        'Dimensions' => array(
                          'Length'        => 10,
                          'Width'         => 10,
                          'Height'        => 10,
                          'Unit'          => 'cm',
                          
                        ),
                        
                        'ActualWeight' => array(
                          'Value'         => $gross_weight,
                          'Unit'          => 'Kg'
                        ),
                        
                        'ProductGroup'      => 'DOM',
                        'ProductType'     => 'ONP',
                        'PaymentType'     => 'C',
                        'PaymentOptions'    => '',
                        'Services'        => '',
                        'NumberOfPieces'    => $total_quantity,
                        'DescriptionOfGoods'  => $description,
                        'GoodsOriginCountry'  => 'LB',
                        
                        'CashOnDeliveryAmount'  => array(
                          'Value'         => 0,
                          'CurrencyCode'      => ''
                        ),
                        
                        'InsuranceAmount'   => array(
                          'Value'         => 0,
                          'CurrencyCode'      => ''
                        ),
                        
                        'CollectAmount'     => array(
                          'Value'         => 0,
                          'CurrencyCode'      => ''
                        ),
                        
                        'CashAdditionalAmount'  => array(
                          'Value'         => 0,
                          'CurrencyCode'      => ''             
                        ),
                        
                        'CashAdditionalAmountDescription' => '',
                        
                        'CustomsValueAmount' => array(
                          'Value'         => 0,
                          'CurrencyCode'      => ''               
                        ),
                        
                        'Items'         => array(
                          
                        )
                ),
            ),
        ),
        
           'ClientInfo'        => array(
                        'AccountCountryCode'  => 'LB',
                        'AccountEntity'     => 'BEY',
                        'AccountNumber'         => getenv("ARAMEX_ACCOUNT_ID"),
                        'AccountPin'            => getenv("ARAMEX_ACCOUNT_PIN"),
                        'UserName'        => getenv("ARAMEX_USERNAME"),
                        'Password'        => getenv("ARAMEX_PASSWORD"),
                        'Version'       => 'v1.0'
                      ),

          'Transaction'       => array(
                        'Reference1'      => '',
                        'Reference2'      => '', 
                        'Reference3'      => '', 
                        'Reference4'      => '', 
                        'Reference5'      => '',                  
                      ),
          
          'LabelInfo'       => array(
                        'ReportID'        => 9201,
                        'ReportType'      => 'URL',
          ),
      );
      
      $params['Shipments']['Shipment']['Details']['Items'][] = array(
        'PackageType'   => 'Fourniture',
        'Quantity'    => $total_quantity,
        'Weight'    => array(
            'Value'   => $gross_weight,
            'Unit'    => 'Kg',    
        ),
        'Comments'    => '',
        'Reference'   => ''
      );

      // create shipment calling aramex API
      $auth_call = $soapClient->CreateShipments($params);

      // if aramex API didn't return any error
      if($auth_call->HasErrors == false)
      {
        $response['status'] = 'success';
        $response['data'] = $auth_call;
        
        return $response;
      }

      else
      {
        $response['status'] = 'error';
        $response['msg'] = $auth_call->Shipments->ProcessedShipment->Notifications->Notification->Message;

        return $response;

      }
    }







}