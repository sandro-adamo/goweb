<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class MetasController extends Controller
{


	public function Metas() {

		$metas = \DB::select("select agrup, 
sum(meta_janeiro) as meta_janeiro,janeiro as realizado_janeiro,
sum(meta_fevereiro) as meta_fevereiro, fevereiro as realizado_fevereiro,
sum(meta_marco) as meta_marco, marco as realizado_marco,
sum(meta_abril) as meta_abril, abril as realizado_abril,
sum(meta_maio) as meta_maio, maio as realizado_maio,
sum(meta_junho) as meta_junho, junho as realizado_junho,
sum(meta_julho) as meta_julho, julho as realizado_julho,
sum(meta_agosto) as meta_agosto, agosto as realizado_agosto,
sum(meta_setembro) as meta_setembro, setembro as realizado_setembro,
sum(meta_outubro) as meta_outubro, outubro as realizado_outubro,
sum(meta_novembro) as meta_novembro, novembro as realizado_novembro,
sum(meta_dezembro) as meta_dezembro, dezembro as realizado_dezembro,
janeiro,  fevereiro, marco,  abril,  maio,  junho,  julho,
 agosto,  setembro,  outubro,  novembro,  dezembro




from(
select vendas_2019.agrup, 
case when  metas.mes = 1 then metas.meta end as meta_janeiro,
case when  metas.mes = 2 then metas.meta end as meta_fevereiro,
case when  metas.mes = 3 then metas.meta end as meta_marco,
case when  metas.mes = 4 then metas.meta end as meta_abril,
case when  metas.mes = 5 then metas.meta end as meta_maio,
case when  metas.mes = 6 then metas.meta end as meta_junho,
case when  metas.mes = 7 then metas.meta end as meta_julho,
case when  metas.mes = 8 then metas.meta end as meta_agosto,
case when  metas.mes = 9 then metas.meta end as meta_setembro,
case when  metas.mes = 10 then metas.meta end as meta_outubro,
case when  metas.mes = 11 then metas.meta end as meta_novembro,
case when  metas.mes = 12 then metas.meta end as meta_dezembro,
sum(janeiro) as janeiro, sum(fevereiro) as fevereiro,sum(marco) as marco, sum(abril) as abril, sum(maio) as maio, sum(junho) as junho, sum(julho) as julho,
sum(agosto) as agosto, sum(setembro) as setembro, sum(outubro) as outubro, sum(novembro) as novembro, sum(dezembro) as dezembro

from vendas_2019
left join metas on metas.agrup = vendas_2019.agrup
where metas.ano = 2019 
group by vendas_2019.agrup, metas.meta, metas.ano, metas.mes
) as tt1

group by agrup,janeiro,  fevereiro, marco,  abril,  maio,  junho,  julho,
 agosto,  setembro,  outubro,  novembro,  dezembro");

		return view('produtos.metas.metas')->with('metas', $metas);

	}



}
