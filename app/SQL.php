<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SQL extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'PRODDTA.F4102';
    
}
