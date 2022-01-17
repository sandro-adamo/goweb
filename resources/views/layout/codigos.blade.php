
@php
	$sql = '';
	//admin
	if (\Auth::user()->id_perfil == 1 or \Auth::user()->id_perfil == 2) {

		$sql = "where 1";

	}

	//rep 
	if (\Auth::user()->id_perfil == 4) {
		$id_addressbook = \Auth::user()->id_addressbook;
		$sql = " where rep = $id_addressbook";
	}

	//diretor
	if (\Auth::user()->id_perfil == 5) {
		$id_addressbook = \Auth::user()->id_addressbook;
		$sql = " where coddir = $id_addressbook";
		
	}

	//supervisor
	if (\Auth::user()->id_perfil == 6) {
		$id_addressbook = \Auth::user()->id_addressbook;
		$sql = " where codsuper = $id_addressbook";
	}

	if ($sql <> '') { 

		$codigos = \DB::select("
	
	select codrep,
		coddir, 
			case when dir.nome = '' then left(dir.razao,3) else left(dir.nome,3) end as diretor,
		codsuper, 
			case when sup.nome = '' then left(sup.razao,10) else left(sup.nome,10) end as supervisor,
		codrep, 
			case when rep.nome = '' then rep.razao else rep.nome end as representante,
        regioes, grifes
	from (
		select rep as codrep, codsuper, coddir, group_concat(distinct regiao,'' order by regiao) regioes, group_concat(distinct grife, '' order by grife) grifes
		from go.carteira
		$sql 
-- and status = 1 
        group by rep, codsuper,coddir
	) as carteira
	left join go.addressbook rep on rep.id = carteira.codrep
	left join go.addressbook sup on sup.id = carteira.codsuper
	left join go.addressbook dir on dir.id = carteira.coddir
	order by rep.razao");

		$representantes = Session::get('representantes');
		$array_rep = explode(',', $representantes);
	}
@endphp



<form action="/codigos" method="post">
	@csrf
  <div class="modal fade" id="modalCodigos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Selecione os códigos</h4>
        </div>
		  <div class="col-md-12">
        <div class="modal-body">
			
	        	<table class="table tabela2">
	        		<thead>
	        			<tr>
	        				<th><input type="checkbox" class="seleAll"></th>
	        				<th>codrep</th>
							<th>Representante</th>
	        				<th>Supervisão</th>
	        				<th>Diretoria</th>
							<th>Regioes</th>
							<th>Grifes</th>
	        			</tr>
	        		</thead>
	        		<tbody>
	        		@isset($codigos)
		        		@foreach ($codigos as $codigo)
		        			<tr>
		        				<td width="1%">
		        					<input type="checkbox" class="seleItem" name="reps[]" value="{{$codigo->codrep}}" @if (in_array($codigo->codrep, $array_rep)) checked="" @endif>
		        				</td>
								<td>{{$codigo->codrep}} </td>
								<td>{{$codigo->representante}}</td>
		        				<td>{{$codigo->supervisor}}</td>
		        				<td>{{$codigo->diretor}}</td>
								<td>{{$codigo->regioes}}</td>
								<td>{{$codigo->grifes}}</td>
		        			</tr>
		        		@endforeach 
		        	@endisset
	        		</tbody>
	        	</table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </div>
		  </div>
    </div>
  </div>
</form>