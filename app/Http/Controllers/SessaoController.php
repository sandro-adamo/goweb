<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessaoController extends Controller
{


    public function alteraCodigos(Request $request) {

        $representantes = '';

        foreach ($request->reps as $rep) {

            $representantes .= $rep.',';

        }

        $representantes = substr($representantes,0,-1);
        \Session::put('representantes', $representantes);

        return redirect()->back();
    }

}

