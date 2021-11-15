<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JDE extends Model
{
    //

	public static function connect() {


	   	function AddWSSUsernameToken($client, $username, $password) {

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

		$context = stream_context_create([
		    'ssl' => [
		        // set some SSL/TLS specific options
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		    ]
		]);
						//http://10.30.240.212:9142/PD920
        //$url        = "https://189.125.137.61:7162/PY920/ItemServiceManager?wsdl"; 
        $url        = "https://10.30.240.212:9143/PD920/ItemServiceManager?wsdl"; 
        $client     = new \SoapClient($url, array("trace" => 1, 'encoding'=>'ISO-8859-1', 'stream_context' => $context)); 
        AddWSSUsernameToken($client, 'GOWEB', 'd6SHzwSu');
		

		 

        return $client;

	}


	public static function connectAddressBook() {


	   	function AddWSSUsernameToken($client, $username, $password) {

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

		$context = stream_context_create([
		    'ssl' => [
		        // set some SSL/TLS specific options
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		    ]
		]);

        //$url        = "https://189.125.137.61:7162/PY920/ItemServiceManager?wsdl"; 
        $url        = "https://10.30.240.212:9143/PD920/AddressBookManager?wsdl"; 
        $client     = new \SoapClient($url, array("trace" => 1, 'encoding'=>'ISO-8859-1', 'stream_context' => $context)); 
        AddWSSUsernameToken($client, 'GOWEB', 'd6SHzwSu');


        return $client;

	}

}
