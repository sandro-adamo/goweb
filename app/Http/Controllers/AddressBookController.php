<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AddressBook;

class AddressBookController extends Controller
{
    
   	public function index() {

   		$addressbook = AddressBook::paginate(10);

   		return view('address-book.lista')->with('addressbook', $addressbook);

   	}
}
