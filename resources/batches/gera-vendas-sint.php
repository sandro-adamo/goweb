<?php

	ini_set('display_errors', 1);
	ini_set('memory_limit',"512M");
	ini_set('max_execution_time',"3000");

	$conn = mysqli_connect("127.0.0.1", "portalgo", "d6SHzwSu", "go");


    $grifes = mysqli_query($conn, "select codgrife from itens group by codgrife");

    mysqli_query($conn, "truncate table vendas_sint");

    foreach ($grifes as $grife) {
        
        echo $grife["codgrife"].'<br>';

        $query1 = mysqli_query($conn, "select agrup, modelo, curto, item secundario, '' as codrep, '' rep, '' fantasia, '' codsup, '' sup, '' coddir, '' dir, '' uf, 
    sum(ult_30dd) ult_30dd, sum(ult_60dd) ult_60dd, sum(ult_90dd) ult_90dd, sum(ult_120dd) ult_120dd, sum(ult_150dd) ult_150dd,
    sum(ult_180dd) ult_180dd, sum(ult_210dd) ult_210dd, sum(ult_240dd) ult_240dd, sum(ult_270dd) ult_270dd, sum(ult_300dd) ult_300dd,
    sum(ult_330dd) ult_330dd, sum(ult_360dd) ult_360dd, sum(a_180dd) a_180dd, sum(qtde) vendastt
    from (

    select agrup, modelo, curto, item, 
    sum(ult_30dd) ult_30dd, sum(ult_60dd) ult_60dd, sum(ult_90dd) ult_90dd, sum(ult_120dd) ult_120dd, sum(ult_150dd) ult_150dd,
    sum(ult_180dd) ult_180dd, sum(ult_210dd) ult_210dd, sum(ult_240dd) ult_240dd, sum(ult_270dd) ult_270dd, sum(ult_300dd) ult_300dd,
    sum(ult_330dd) ult_330dd, sum(ult_360dd) ult_360dd, sum(a_180dd) a_180dd, sum(qtde) qtde

    from (

    select b.agrup, b.modelo,  b.id curto, item, 
    case when  cast(datediff(now(), data) as unsigned integer) between 0 and 30 then sum(qtde) else 0 end as ult_30dd,
    case when  cast(datediff(now(), data) as unsigned integer) between 30.01 and 60 then sum(qtde) else 0 end as ult_60dd,
    case when  cast(datediff(now(), data) as unsigned integer) between 60.01 and 90 then sum(qtde) else 0 end as ult_90dd,
    case when  cast(datediff(now(), data) as unsigned integer) between 90.01 and 120 then sum(qtde) else 0 end as ult_120dd,
    case when  cast(datediff(now(), data) as unsigned integer) between 120.01 and 150 then sum(qtde) else 0 end as ult_150dd,
    case when  cast(datediff(now(), data) as unsigned integer) between 150.01 and 180 then sum(qtde) else 0 end as ult_180dd,
    case when  cast(datediff(now(), data) as unsigned integer) between 180.01 and 210 then sum(qtde) else 0 end as ult_210dd,
    case when  cast(datediff(now(), data) as unsigned integer) between 210.01 and 240 then sum(qtde) else 0 end as ult_240dd,
    case when  cast(datediff(now(), data) as unsigned integer) between 240.01 and 270 then sum(qtde) else 0 end as ult_270dd,
    case when  cast(datediff(now(), data) as unsigned integer) between 270.01 and 300 then sum(qtde) else 0 end as ult_300dd,
    case when  cast(datediff(now(), data) as unsigned integer) between 300.01 and 330 then sum(qtde) else 0 end as ult_330dd,
    case when  cast(datediff(now(), data) as unsigned integer) between 330.01 and 360 then sum(qtde) else 0 end as ult_360dd,
    case when  cast(datediff(now(), data) as unsigned integer) between 0 and 180 then sum(qtde) else 0 end as a_180dd,

    sum(qtde) qtde 
    from vendas_ssa a
    left join itens b on a.id_item = b.id
    where b.id <> '' and codgrife = '$grife[codgrife]'
    group by  b.id , item, b.agrup, b.modelo,data
    ) as sele2
    group by  curto, agrup, modelo,  item


    union all

    select b.agrup, b.modelo, b.id as curto, b.secundario as item, sum(ult_30dd) ult_30dd, sum(ult_60dd) ult_60dd, sum(ult_90dd) ult_90dd, sum(ult_120dd) ult_120dd, sum(ult_150dd) ult_150dd,
    sum(ult_180dd) ult_180dd, sum(ult_210dd) ult_210dd, sum(ult_240dd) ult_240dd, sum(ult_270dd) ult_270dd, sum(ult_300dd) ult_300dd,
    sum(ult_330dd) ult_330dd, sum(ult_360dd) ult_360dd, sum(a_180dd) a_180dd, sum(vendastt) qtde


    from vendas_jde a
    left join itens b on a.curto = b.id
    where codgrife = '$grife[codgrife]'
    group by b.agrup, b.modelo, b.id , b.secundario



    ) as selez

    group by agrup, modelo, curto, item");


        while ($line = mysqli_fetch_assoc($query1)) {

                
    			$query = "INSERT INTO `vendas_sint`( `agrup`, `modelo`, `curto`, `secundario`, `codrep`, `rep`, `fantasia`, `codsup`, `sup`, `coddir`, `dir`, `uf`, `ult_30dd`, `ult_60dd`, `ult_90dd`, `ult_120dd`, `ult_150dd`, `ult_180dd`, `ult_210dd`, `ult_240dd`, `ult_270dd`, `ult_300dd`, `ult_330dd`, `ult_360dd`, `a_180dd`, `vendastt`) VALUES (";

                    foreach ($line as $key => $coluna) {

                            if ($key == 2 or $key == 52 or $key == 53 or $key == 54) {
                                if ($coluna == '') {
                                    $query .= "0,";
                                } else {
                                    $query .= "'$coluna',";
                                }
                            } else {
                                $query .= "'$coluna',";
                            }
                    }

                    $query = substr_replace($query, '', -1);
                    $query .= ")";

                    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));



        }

    }
