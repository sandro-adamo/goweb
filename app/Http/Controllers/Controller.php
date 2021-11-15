<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;




    public function AddWSSUsernameToken($client, $username, $password) {

        $wssNamespace = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd";

        $username = new \SoapVar($username, 
            XSD_STRING, 
            null, null, 
            'Username', 
            $wssNamespace);

        $password = new \SoapVar($password, 
            XSD_STRING, 
            null, null, 
            'Password', 
            $wssNamespace);

        $usernameToken = new \SoapVar(array($username, $password), 
            SOAP_ENC_OBJECT, 
            null, null, 'UsernameToken', 
            $wssNamespace);

        $usernameToken = new \SoapVar(array($usernameToken), 
            SOAP_ENC_OBJECT, 
            null, null, null, 
            $wssNamespace);

        $wssUsernameTokenHeader = new \SoapHeader($wssNamespace, 'Security', $usernameToken);

        $client->__setSoapHeaders($wssUsernameTokenHeader); 

    }   





}
