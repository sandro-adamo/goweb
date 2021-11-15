<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Item;

class ReportsController extends Controller
{


	
	
	public function statustop() {
		
		  	$statustop = \DB::select("
		Select agrup, format(disponivel/total,2) disponivel, format(15_dias/total,2) dias15, format(30_dias/total,2) dias30, 
format(producao/total,2) Producao, format(esgotado/total,2) Esgotado
from(


select Agrup, sum(Producao) Producao, sum(15_dias) 15_dias, sum(30_dias) 30_dias, sum(Disponivel) Disponivel, sum(esgotado) Esgotado,
sum(Producao)+sum(15_dias)+sum(30_dias)+sum(Disponivel)+sum(esgotado) as Total
from(
select Agrup, statusatual,
case when statusatual = 'AGUARDAR PRODUÇÃO' then count(secundario) else 0 end as 'Producao',
case when statusatual = 'AGUARDAR IMPORTAÇÃO 15 DIASO' then count(secundario) else 0 end as '15_dias',
case when statusatual = 'AGUARDAR IMPORTAÇÃO 30 DIAS' then count(secundario) else 0 end as '30_dias',
case when statusatual = 'DISPONÍVEL' then count(secundario) else 0 end as 'Disponivel',
case when statusatual = 'ESGOTADO' then count(secundario) else 0 end as 'ESGOTADO'
from itens
where clasmod like 'linha a+%'
and agrup <> 'agregados'
and tipoarmaz not in ('o','i')
group by Agrup, statusatual) as base
group by agrup) as base2


");


    	return view('produtos.reports.reports')->with('statustop', $statustop);


    }
	
	
	
	
	


}
