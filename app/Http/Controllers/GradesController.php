<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Item;

class GradesController extends Controller
{
	
	public static function listaGrades() {


		$modeloagregado = \DB::connection('go')->select("
select grife, codgrife, agrup, count(modelo) modelos, sum(itens) itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado, 
sum(am3cores) am3cores, sum(b2cores) b2cores, sum(c1cor) c1cor, sum(d0cor) d0cor from (

	select grife, codgrife, agrup, modelo, clasmod, colmod, (itens) as itens, (imediata) imediata, (futura) futura, (producao) producao, (esgotado) esgotado,
		case when imediata+futura >= 3 then 1 else 0 end as am3cores,
		case when imediata+futura  = 2 then 1 else 0 end as b2cores,
		case when imediata+futura  = 1 then 1 else 0 end as c1cor,
        case when imediata+futura  = 0 then 1 else 0 end as d0cor
        
	from(

		select grife, codgrife, agrup, modelo, clasmod, colmod, sum(itens) as itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado
		
		from (
		 
			select grife, codgrife, agrup, modelo, clasmod, colmod, 1 as itens,
				case when ultstatus = 'ENTREGA IMEDIATA' then 1 else 0 end as imediata,
				case when ultstatus like '%DIAS' then 1 else 0 end as futura,
				case when ultstatus like '%PROD%' then 1 else 0 end as producao,
				case when ultstatus like '%ESGOTADO%' then 1 else 0 end as esgotado
			from (
						
				select grife, codgrife, itens.agrup, itens.modelo, itens.secundario, colmod, clasmod, ultstatus, saldos.disp_vendas
				from itens 
				left join saldos on saldos.curto = itens.id
				where
				
				 itens.secundario not like '%semi%' and (clasmod like 'linha%' or clasmod like 'novo%') 				 
				 and saldos.disp_vendas > 10
                and codgrife in ('AH','AT','BG','EV','JO','HI','SP','TC','JM','NG','GU','MM','ST','AM','MC','CT','BC','BV','SM') 
			) as fim2
		) as fim3 group by grife, codgrife, agrup, modelo, clasmod, colmod
	) as fim4 
) as fim5 group by grife, codgrife, agrup order by grife, agrup
");
		
		
	return view('produtos.grades.grades')->with('modeloagregado', $modeloagregado);
		

	}
	

	

public static function listaGradesColecoes($agrup) {


		$itensagregado = \DB::connection('go')->select("

	select agrupamento, colecao, sum(grade_colecao) grade_colecao, sum(atual) atual, sum(saidas) saidas, sum(entradas) entradas, sum(futuro) futuro,
    sum(masc) masc, sum(fem) fem, sum(unis) unis, sum(t50) t50, sum(t5153) t5153, sum(t5456) t5456, sum(t57) t57, sum(casual) casual, sum(fashion) fashion, sum(luxo) luxo, sum(sport) sport,
    sum(metal) metal, sum(acetato) acetato, sum(plastico) plastico, sum(fechado) fechado, sum(nylon) nylon, sum(ballgrife) ballgrife,
	sum(adulto) adulto, sum(young) young, sum(infantil) infantil
    from(
		 select agrupamento, colecao, (atual)+(entradas)-(saidas) as grade_colecao, (atual) atual, (saidas) saidas, (entradas) entradas, (futuro) futuro,
		 
		 case when (atual)+(entradas)-(saidas) > 0 then (masculino) else 0 end as  masc, 
		 case when (atual)+(entradas)-(saidas) > 0 then(feminino) else 0 end as  fem, 
		 case when (atual)+(entradas)-(saidas) > 0 then(unisex) else 0 end as unis,
		 case when (atual)+(entradas)-(saidas) > 0 then t50 else 0 end as t50, 
		 case when (atual)+(entradas)-(saidas) > 0 then t5153 else 0 end as t5153, 
		 case when (atual)+(entradas)-(saidas) > 0 then t5456 else 0 end as t5456, 
		 case when (atual)+(entradas)-(saidas) > 0 then t57 else 0 end as t57,
		 case when (atual)+(entradas)-(saidas) > 0 then casual else 0 end as casual, 
		 case when (atual)+(entradas)-(saidas) > 0 then fashion else 0 end as fashion, 
		 case when (atual)+(entradas)-(saidas) > 0 then luxo else 0 end as luxo, 
		 case when (atual)+(entradas)-(saidas) > 0 then sport else 0 end as sport,
		 case when (atual)+(entradas)-(saidas) > 0 then metal else 0 end as metal, 
		 case when (atual)+(entradas)-(saidas) > 0 then acetato else 0 end as acetato, 
		 case when (atual)+(entradas)-(saidas) > 0 then plastico else 0 end as plastico,
		 case when (atual)+(entradas)-(saidas) > 0 then fechado else 0 end as fechado,
		 case when (atual)+(entradas)-(saidas) > 0 then nylon else 0 end as nylon,
		 case when (atual)+(entradas)-(saidas) > 0 then ballgrife else 0 end as ballgrife,
		 case when (atual)+(entradas)-(saidas) > 0 then adulto else 0 end as adulto,
		 case when (atual)+(entradas)-(saidas) > 0 then young else 0 end as young,
		 case when (atual)+(entradas)-(saidas) > 0 then infantil else 0 end as infantil
		 
		 from (
		 
			select *,
			case when colecao = saida then 1 else 0 end as 'saidas' , 
			case when colecao = colmod then 1 else 0  end as 'entradas', 
			case when colmod > colecao then 1 else 0  end as 'futuro' ,
			case when (colmod < saida or saida is null and colmod < colecao )then 1 else 0  end as 'atual',
			
			case when idade = 'adulto' then 1 else 0 end as adulto,
			case when idade in ('young','teen') then 1 else 0 end as young,
			case when idade in ('kids','infantil') then 1 else 0 end as infantil,
			
			case when genero = 'masculino' then 1 else 0 end as masculino,
			case when genero = 'feminino' then 1 else 0 end as feminino,
			case when genero = 'unisex' then 1 else 0 end as unisex,
			
			case when tamanho <= 50.9 then 1 else 0 end as t50,
			case when tamanho between 51 and 53.9 then 1 else 0 end as t5153,
			case when tamanho between 54 and 56.9 then 1 else 0 end as t5456,
			case when tamanho >= 57 then 1 else 0 end as t57,
			
			case when estilo like '%casual%' or estilo like '%cool%' or estilo like '%colors%' or estilo like '%classico%' or estilo like '%essence%' 
            or estilo like '%lifestyle%' or estilo like '%urban%' then 1 else 0 end as casual,
			case when estilo like '%fashion%' or estilo like '%glam%' then 1 else 0 end as fashion,
			case when estilo like '%luxo%' then 1 else 0 end as luxo,
			case when estilo like '%sport%' or estilo like '%performance%' then 1 else 0 end as sport,

			 case when material like '%metal%' or material like '%inoxidav%' or material like '%titan%' then 1 else 0 end as metal,
			 case when material like '%acetato%' then 1 else 0 end as acetato,
			 case when material like '%plastico%' then 1 else 0 end as plastico,
			 
			 case when fixacao like '%fechado%' then 1 else 0 end as fechado,
			 case when fixacao like '%nylon%' then 1 else 0 end as nylon,
			 case when fixacao like '%3 pecas%' then 1 else 0 end as ballgrife

			from (
				select distinct agrup agrupamento, colmod colecao from itens where colmod not in ('ATITUDE POINT','CANCELADO','COLEÃÃO EUROPA') 
				 and colmod >= '2020 01' 
				and agrup = '$agrup'
			) as base
				
				left join (
							select distinct agrup, modelo, clasmod, colmod, 
							(select genero from itens g where g.modelo = itens.modelo order by genero desc limit 1) genero,
							(select tamolho from itens t where t.modelo = itens.modelo order by genero desc limit 1) tamanho,
							(select estilo from itens e where e.modelo = itens.modelo order by genero desc limit 1) estilo,
							(select material from itens m where m.modelo = itens.modelo order by genero desc limit 1) material,
							(select fixacao from itens f where f.modelo = itens.modelo order by genero desc limit 1) fixacao, 
							(select idade from itens i where i.modelo = itens.modelo order by genero desc limit 1) idade, 
							(select colecao from ciclos where ciclos.modelo = itens.modelo order by created_at limit 1) as saida				
							from itens  
							where agrup = '$agrup' and 
							clasmod in ('linha a++','linha a+','linha a','linha a-','novo','.')
				) as modelos
				on modelos.agrup = base.agrupamento
				
			 -- where colecao = '2020 01' -- and modelo = 'at5432'

		) as fim1
	) as fim2
 group by agrupamento, colecao    order by colecao asc

		"); 
		

		
		
		$modeloagregado = \DB::connection('go')->select("


select codgrife, grife, agrup, sum(modelos_grade) modelos_grade, sum(modelos_ent) modelos_ent, sum(modelos_sai) modelos_sai from (
	select modelo, codgrife, grife, agrup, 
	case when inicio < '2020 04' and final > '2020 01' then count(modelo) else 0 end as modelos_grade,
	case when inicio = '2020 04' then count(modelo) else 0 end as modelos_ent ,
	case when final  = '2020 04' then count(modelo) else 0 end as modelos_sai

	from (
			select * from (
					select distinct itens.codgrife,  itens.grife, itens.agrup, itens.modelo, itens.colmod inicio, itens.clasmod, ciclos.colecao final
					
					from itens
					left join ciclos on ciclos.modelo = itens.modelo
					
					 where agrup = '$agrup'
				   --  where (clasmod like 'linha%' or clasmod = 'novo' or clasmod = '.')
			) as fim
	 ) as fim2
	 
	where inicio <= '2020 04' and (final >= '2020 04' or final is null)
	   
	group by modelo, codgrife, grife, agrup, inicio, final
) as fim3

group by codgrife, grife, agrup		

");
			

		return view('produtos.grades.gradescolecoes')->with('modeloagregado', $modeloagregado)->with('itensagregado', $itensagregado);
	
}


	
	
public static function listaGradesItens($agrup) {


		$itensagregado = \DB::connection('go')->select("


select grife, codgrife, agrup, modelo, clasmod, colmod, (itens) as itens, (imediata) imediata, (futura) futura, (producao) producao, (esgotado) esgotado,
case when imediata+futura >= 3 then 1 else 0 end as am3cores,
case when imediata+futura  = 2 then 1 else 0 end as b2cores,
case when imediata+futura  = 1 then 1 else 0 end as c1cor,
case when imediata+futura  = 0 then 1 else 0 end as d0cor

from(

select grife, codgrife, agrup, modelo, clasmod, colmod, sum(itens) as itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado

from (
		 
			select grife, codgrife, agrup, modelo, clasmod, colmod, 1 as itens,
				case when ultstatus = 'ENTREGA IMEDIATA' then 1 else 0 end as imediata,
				case when ultstatus like '%DIAS' then 1 else 0 end as futura,
				case when ultstatus like '%PROD%' then 1 else 0 end as producao,
				case when ultstatus like '%ESGOTADO%' then 1 else 0 end as esgotado
			from (
						
				select grife, codgrife, itens.agrup, itens.modelo, itens.secundario, colmod, clasmod, ultstatus, saldos.disp_vendas
				from itens 
				left join saldos on saldos.curto = itens.id
				
				where itens.secundario not like '%semi%' and clasmod in ('novo') 
				
                and itens.agrup like '$agrup'  
                and codgrife in ('AH','AT','BG','EV','JO','HI','SP','TC','JM','NG','GU','MM','ST','AM','MC','CT','BC','BV','SM') 
			) as fim2
		) as fim3 group by grife, codgrife, agrup, modelo, clasmod, colmod
	) as fim4 

		"); 
		

		
		
		$modeloagregado = \DB::connection('go')->select("
select grife, codgrife, agrup, count(modelo) modelos, sum(itens) itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado, 
sum(am3cores) am3cores, sum(b2cores) b2cores, sum(c1cor) c1cor, sum(d0cor) d0cor from (

select grife, codgrife, agrup, modelo, clasmod, colmod, (itens) as itens, (imediata) imediata, (futura) futura, (producao) producao, (esgotado) esgotado,
case when imediata+futura >= 3 then 1 else 0 end as am3cores,
case when imediata+futura  = 2 then 1 else 0 end as b2cores,
case when imediata+futura  = 1 then 1 else 0 end as c1cor,
case when imediata+futura  = 0 then 1 else 0 end as d0cor

from(

select grife, codgrife, agrup, modelo, clasmod, colmod, sum(itens) as itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado

from (
		 
			select grife, codgrife, agrup, modelo, clasmod, colmod, 1 as itens,
				case when ultstatus = 'ENTREGA IMEDIATA' then 1 else 0 end as imediata,
				case when ultstatus like '%DIAS' then 1 else 0 end as futura,
				case when ultstatus like '%PROD%' then 1 else 0 end as producao,
				case when ultstatus like '%ESGOTADO%' then 1 else 0 end as esgotado
			from (
						
				select grife, codgrife, itens.agrup, itens.modelo, itens.secundario, colmod, clasmod, ultstatus, saldos.disp_vendas
				from itens 
				left join saldos on saldos.curto = itens.id
				
				where itens.secundario not like '%semi%' and clasmod in ('linha a++', 'linha a+', 'linha a', 'novo')  
                and itens.agrup like '$agrup' 
                and codgrife in ('AH','AT','BG','EV','JO','HI','SP','TC','JM','NG','GU','MM','ST','AM','MC','CT','BC','BV','SM') 
			) as fim2
		) as fim3 group by grife, codgrife, agrup, modelo, clasmod, colmod
	) as fim4 
) as fim5 group by grife, codgrife, agrup

		");
			

		return view('produtos.grades.gradesitens')->with('modeloagregado', $modeloagregado)->with('itensagregado', $itensagregado);
	
}
	
	
	
	
public static function listaGradesModelos(Request $request, $agrup) {

	 if(isset($request->cores))
	 		{$where = 'where filtro =';} else {$where ='where filtro >=';}


		$itensagregado1 = \DB::connection('go')->select("

select * from (
select grife, codgrife, agrup, modelo, clasmod, right(clasmod,2) classif, colmod, (itens) as itens, 
case when imediata+futura >= 3 then 3 else imediata+futura end as filtro,  (imediata) imediata, (futura) futura, (producao) producao, (esgotado) esgotado,
case when imediata+futura >= 3 then 1 else 0 end as am3cores,
case when imediata+futura  = 2 then 1 else 0 end as b2cores,
case when imediata+futura  = 1 then 1 else 0 end as c1cor,
case when imediata+futura  = 0 then 1 else 0 end as d0cor,
(select sum(qtde) from vendas_jde vds left join itens on itens.id = vds.id_item where itens.modelo = fim4.modelo and DATEDIFF(now(),dt_venda) <= 30) as qtde_30,
(select sum(qtde) from vendas_jde vds left join itens on itens.id = vds.id_item where itens.modelo = fim4.modelo ) as qtde_tt

from(

select grife, codgrife, agrup, modelo, clasmod, colmod, sum(itens) as itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado

from (
		 
			select grife, codgrife, agrup, modelo, clasmod, colmod, 1 as itens,
				case when ultstatus = 'ENTREGA IMEDIATA' then 1 else 0 end as imediata,
				case when ultstatus like '%DIAS' then 1 else 0 end as futura,
				case when ultstatus like '%PROD%' then 1 else 0 end as producao,
				case when ultstatus like '%ESGOTADO%' then 1 else 0 end as esgotado
			from (
						
				select grife, codgrife, itens.agrup, itens.modelo, itens.secundario, colmod, clasmod, ultstatus, saldos.disp_vendas
				from itens 
				left join saldos on saldos.curto = itens.id
				
				where itens.secundario not like '%semi%' and clasmod in ('linha a++', 'linha a+', 'linha a', 'novo')  
                and itens.agrup like '$agrup' 
				
                and codgrife in ('AH','AT','BG','EV','JO','HI','SP','TC','JM','NG','GU','MM','ST','AM','MC','CT','BC','BV','SM') 
			) as fim2
		) as fim3 group by grife, codgrife, agrup, modelo, clasmod, colmod
	) as fim4 
) as fim5 $where '$request->cores' order by colmod desc

		"); 
		

		
		
		$modeloagregado1 = \DB::connection('go')->select("
select grife, codgrife, agrup, count(modelo) modelos, sum(itens) itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado, 
sum(am3cores) am3cores, sum(b2cores) b2cores, sum(c1cor) c1cor, sum(d0cor) d0cor from (

select grife, codgrife, agrup, modelo, clasmod, colmod, (itens) as itens, (imediata) imediata, (futura) futura, (producao) producao, (esgotado) esgotado,
case when imediata+futura >= 3 then 1 else 0 end as am3cores,
case when imediata+futura  = 2 then 1 else 0 end as b2cores,
case when imediata+futura  = 1 then 1 else 0 end as c1cor,
case when imediata+futura  = 0 then 1 else 0 end as d0cor

from(

select grife, codgrife, agrup, modelo, clasmod, colmod, sum(itens) as itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado

from (
		 
			select grife, codgrife, agrup, modelo, clasmod, colmod, 1 as itens,
				case when ultstatus = 'ENTREGA IMEDIATA' then 1 else 0 end as imediata,
				case when ultstatus like '%DIAS' then 1 else 0 end as futura,
				case when ultstatus like '%PROD%' then 1 else 0 end as producao,
				case when ultstatus like '%ESGOTADO%' then 1 else 0 end as esgotado
			from (
						
				select grife, codgrife, itens.agrup, itens.modelo, itens.secundario, colmod, clasmod, ultstatus, saldos.disp_vendas
				from itens 
				left join saldos on saldos.curto = itens.id
				
				where itens.secundario not like '%semi%' 
				and clasmod in ('linha a++', 'linha a+', 'linha a', 'novo')    
                and itens.agrup like '$agrup'  
                and codgrife in ('AH','AT','BG','EV','JO','HI','SP','TC','JM','NG','GU','MM','ST','AM','MC','CT','BC','BV','SM') 
			) as fim2
		) as fim3 group by grife, codgrife, agrup, modelo, clasmod, colmod
	) as fim4 
) as fim5 group by grife, codgrife, agrup

		");
			

		return view('produtos.grades.gradesmodelos')->with('modeloagregado1', $modeloagregado1)->with('itensagregado1', $itensagregado1);
	
	
	
	}

	
	
public static function listaGradesColdet(Request $request, $agrup) {

	 if(isset($request->cores))
	 		{$where = 'where filtro =';} else {$where ='where filtro >=';}


		$itensagregado = \DB::connection('go')->select("

	select * from (	 
			select *,
            case when colecao = saida then 'saidas'
				when colecao = entrada then 'entradas'
                when entrada > colecao then 'futuro'
                when (entrada < saida or saida is null and entrada < colecao ) then 'atual'
                else 'erro' end as status_mala,
                
			case when colecao = saida then 1 else 0 end as 'saidas' , 
			case when colecao = entrada then 1 else 0  end as 'entradas', 
			case when entrada > colecao then 1 else 0  end as 'futuro' ,
			case when (entrada < saida or saida is null and entrada < colecao )then 1 else 0  end as 'atual',
			
			case when idade = 'adulto' then 1 else 0 end as adulto,
			case when idade in ('kids','infantil') then 1 else 0 end as infantil,
			
			case when genero = 'masculino' then 1 else 0 end as masculino,
			case when genero = 'feminino' then 1 else 0 end as feminino,
			case when genero = 'unisex' then 1 else 0 end as unisex,
			
			case when tamanho <= 50.9 then 1 else 0 end as t50,
			case when tamanho between 51 and 53.9 then 1 else 0 end as t5153,
			case when tamanho between 54 and 56.9 then 1 else 0 end as t5456,
			case when tamanho >= 57 then 1 else 0 end as t57,
			
			case when estilo like '%casual%' or estilo like '%cool%' or estilo like '%colors%' or estilo like '%classico%' or estilo like '%essence%' 
            or estilo like '%lifestyle%' or estilo like '%urban%' then 1 else 0 end as casual,
			case when estilo like '%fashion%' or estilo like '%glam%' then 1 else 0 end as fashion,
			case when estilo like '%luxo%' then 1 else 0 end as luxo,
			case when estilo like '%sport%' or estilo like '%performance%' then 1 else 0 end as sport,

			 case when material like '%metal%' or material like '%inoxidav%' or material like '%titan%' then 1 else 0 end as metal,
			 case when material like '%acetato%' then 1 else 0 end as acetato,
			 case when material like '%plastico%' then 1 else 0 end as plastico,
			 
			 case when fixacao like '%fechado%' then 1 else 0 end as fechado,
			 case when fixacao like '%nylon%' then 1 else 0 end as nylon,
			 case when fixacao like '%3 pecas%' then 1 else 0 end as ballgrife

			from (
				select distinct agrup agrupamento, colmod colecao from itens where colmod not in ('ATITUDE POINT','CANCELADO','COLEÃÃO EUROPA') 
			    and colmod = '$request->colecao' 
				and agrup = '$agrup'
			) as base
				
				left join (
							select distinct agrup, itens.modelo, clasmod, colmod entrada,
							(select genero from itens g where g.modelo = itens.modelo order by genero desc limit 1) genero,
							(select tamolho from itens t where t.modelo = itens.modelo order by genero desc limit 1) tamanho,
							(select estilo from itens e where e.modelo = itens.modelo order by genero desc limit 1) estilo,
							(select material from itens m where m.modelo = itens.modelo order by genero desc limit 1) material,
							(select fixacao from itens f where f.modelo = itens.modelo order by genero desc limit 1) fixacao, 
							(select idade from itens i where i.modelo = itens.modelo order by genero desc limit 1) idade, 
							(select colecao from ciclos where ciclos.modelo = itens.modelo order by created_at limit 1) as saida				
							from itens  
							
							
							where agrup = '$agrup' and 
							
							clasmod in ('linha a++','linha a+','linha a','linha a-','novo','.') 
				) as modelos
				on modelos.agrup = base.agrupamento
) as fim1

left join (
select model, sum(disp_vendas) disp_vendas, sum(etq_total) etq_total from (
	select modelo model , (disp_vendas) disp_vendas, 
	case when fornecedor like 'kering%' then 
	(ifnull(disp_vendas,0)+ifnull(cet,0)+ifnull(em_beneficiamento,0)+ifnull(saldo_parte,0)+ifnull(producao,0)) 
	else (ifnull(disp_vendas,0)+ifnull(cet,0)+ifnull(em_beneficiamento,0)+ifnull(saldo_parte,0)+ifnull(estoque,0)+ifnull(producao,0)) end as etq_total

	from saldos 
	left join itens on itens.id = saldos.curto 
	left join producoes_sint prod on itens.id = prod.id 
	where agrup = '$agrup'	
	) as sld
group by model
) as saldos on saldos.model = fim1.modelo


left join (
select modelo model, sum(qtde_30dd) qtde_30dd, sum(qtde_180dd) qtde_180dd, sum(qtde_total) qtde_total from (
	select modelo,
	case when datediff(now(), dt_venda)  <= 30 then (qtde) else 0 end as 'qtde_30dd' ,
	case when datediff(now(), dt_venda)  <= 180 then (qtde) else 0 end as 'qtde_180dd',
	(qtde) qtde_total

	from vendas_jde vds 
	left join itens on itens.id = vds.id_item
	where ultstatus not in ('980','984') and codtipoitem = '006' and agrup = '$agrup'	
) as vds group by modelo
) as vendas on vendas.model = fim1.modelo

where futuro = 0 
order by status_mala, modelo

		"); 
			
		 if(isset($request->cores))
		 		{$where = 'where filtro =';} else {$where ='where filtro >=';}


			$itensagregado1 = \DB::connection('go')->select("

	select * from (
	select grife, codgrife, agrup, modelo, clasmod, right(clasmod,2) classif, colmod, (itens) as itens, estilo, 
	case when imediata+futura >= 3 then 3 else imediata+futura end as filtro,  (imediata) imediata, (futura) futura, (producao) producao, (esgotado) esgotado,
	case when imediata+futura >= 3 then 1 else 0 end as am3cores,
	case when imediata+futura  = 2 then 1 else 0 end as b2cores,
	case when imediata+futura  = 1 then 1 else 0 end as c1cor,
	case when imediata+futura  = 0 then 1 else 0 end as d0cor,
	(select sum(qtde) from vendas_jde vds left join itens on itens.id = vds.id_item where itens.modelo = fim4.modelo and DATEDIFF(now(),dt_venda) <= 30) as qtde_30,
	(select sum(qtde) from vendas_jde vds left join itens on itens.id = vds.id_item where itens.modelo = fim4.modelo ) as qtde_tt

	from(

	select grife, codgrife, agrup, modelo, clasmod, colmod, estilo, sum(itens) as itens, sum(imediata) imediata, sum(futura) futura, sum(producao) producao, sum(esgotado) esgotado

	from (
			 
				select grife, codgrife, agrup, modelo, clasmod, colmod, 1 as itens, estilo,
					case when ultstatus = 'ENTREGA IMEDIATA' then 1 else 0 end as imediata,
					case when ultstatus like '%DIAS' then 1 else 0 end as futura,
					case when ultstatus like '%PROD%' then 1 else 0 end as producao,
					case when ultstatus like '%ESGOTADO%' then 1 else 0 end as esgotado
				from (
							
					select grife, codgrife, itens.agrup, itens.modelo, itens.secundario, colmod, clasmod, ultstatus, saldos.disp_vendas, estilo
					from itens 
					left join saldos on saldos.curto = itens.id
			
					
					where itens.secundario not like '%semi%' and clasmod like 'linha%'  -- and colmod = '$request->colecao'
	                and itens.agrup like '$agrup'  
					
	                and codgrife in ('AH','AT','BG','EV','JO','HI','SP','TC','JM','NG','GU','MM','ST','AM','MC','CT','BC','BV','SM') 
				) as fim2
			) as fim3 group by grife, codgrife, agrup, modelo, clasmod, colmod, estilo
		) as fim4 
	) as fim5 $where '$request->cores' order by estilo, modelo desc

			"); 



			return view('produtos.grades.gradescoldet')->with('itensagregado', $itensagregado)->with('agrup',$agrup)->with('itensagregado1', $itensagregado1);

	
	
	
	}
	
	
	
	
	

}
