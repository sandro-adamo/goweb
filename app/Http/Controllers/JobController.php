<?php

namespace App\Http\Controllers;

use Elasticsearch;
use Illuminate\Http\Request;
use App\Job;

class JobController extends Controller
{


        public function executa($id) {

        	$job = Job::find($id);

        	if (file_exists($job->script)) {

                	include ($job->script);

                	//echo exec('php7.2 '.$job->script);
                } else {
                	echo 'arquivo nao existe';
                }

                return redirect('/integracao');

        }

        public function atualizaVendasCML() {

                $truncate = \DB::select("truncate table vendas_cml");

                $query = \DB::select("

INSERT INTO vendas_cml (tipo, cliente, grife_jde, ano, mes, qtde, valor, repres, supervisor, diretor) 


        select tipo, cliente, grife_jde, ano, mes, qtde, valor, repres, 
                                case when fim3.id_supervisor is null then
                                (select distinct ab_id.id from carteira 
                                left join addressbook abcart on abcart.id = carteira.cli
                                left join addressbook ab_id on carteira.sup = ab_id.fantasia and ab_id.tipo = 're'
                                where abcart.cliente = fim3.cliente  /*and carteira.grife in ('AH','AT','BG','EV','HI','JO','SP','TC','NG')*/ limit 1  ) else fim3.id_supervisor end as supervisor, 
                                
                                case when fim3.id_diretor is null then
                                (select distinct ab_id.id from carteira 
                                left join addressbook abcart on abcart.id = carteira.cli
                                left join addressbook ab_id on carteira.dir = ab_id.fantasia and ab_id.tipo = 're'
                                where abcart.cliente = fim3.cliente  /*and carteira.grife in ('AH','AT','BG','EV','HI','JO','SP','TC','NG')*/ limit 1  ) else fim3.id_diretor end as diretor 

        from (
         
        select * from (
                                select *, (select rep from carteira  left join addressbook ab on ab.id = cli  where ab.cliente = fim.cliente and carteira.grife = grife_jde limit 1 ) repres
                                
                                        from (

                                                select '2017' as tipo, cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, ano, mes, sum(qtde) qtde, sum(valor) valor  
                                                from vendas_2017 vdas17  left join  addressbook abcli on abcli.id = vdas17.cli_jde
                                                /*where grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') */
                                                group by  cliente, grife_jde, ano, mes

                                        union all

                                                select '2018' as tipo, cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, ano, mes, sum(qtde) qtde, sum(valor) valor  
                                                from vendas_2018 vdas18  left join  addressbook abcli on abcli.id = vdas18.cli_jde
                                                /*where grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') */
                                                group by  cliente, grife_jde, ano, mes

                                        union all

                                                select '12m' as tipo, cliente, case when grife_jde in ('EV','NG') then 'EV' else grife_jde end as grife_jde, ano, mes, sum(qtde) qtde, sum(valor) valor  
                                                from vendas_12meses vdas12  left join  addressbook abcli on abcli.id = vdas12.cli_jde
                                                /*where grife_jde in ('AH','AT','BG','EV','HI','JO','SP','TC','NG') */
                                                group by  cliente, grife_jde, ano, mes

                                ) as fim
                ) as fim2
                left join ( select id, id_supervisor, id_diretor from addressbook ab ) as cart  on cart.id = repres 
        ) as fim3
        ");

        }

}
