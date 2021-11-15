@extends('layout.principal')

@section('titulo', 'Volume estoque') 
<i class="fa fa-dashboard"></i> Volume estoque
@section('title')
@append 

@section('conteudo')


        <div class="box box-body box-widget">   
			<h2>Volume estoque X meta</h2>
            
			</br>
   		<table class="table table-condensed table-bordered" >
		
			<thead>
		<tr class="bg-primary">
        <th align="center">Agrup</th>
		<th align="center">B/C</th>
		<th align="center"><18_a-</th>
		<th align="center">18_19_a-</th>
		<th align="center">19_1</th>
		<th align="center">20_01</th>
		<th align="center">20_08</th>
		<th align="center">21_01</th>
		
		<th align="center">Sld_tt</th>
		<th align="center">Meta_1</th>
		<th align="center">Sld_1_fim</th>
			<th align="center">21_08</th>
		<th align="center">Meta_2</th>
		<th align="center">Sld_2_fim</th
			
		
		
		
        </tr> 
			</thead>
	   <tbody>
		@foreach ($volume as $vol)
		<tr >
		<td align="center">{{$vol->Agrupamento}}</td>
		<td align="center">{{number_format($vol->BC)}}</td>
		<td align="center">{{number_format($vol->a2018_a)}}</td>
		<td align="center">{{number_format($vol->a2018_19_a)}}</td>
		<td align="center">{{number_format($vol->a2019_1)}}</td>
		<td align="center">{{number_format($vol->a2020_01)}}</td>
		<td align="center">{{number_format($vol->a2020_08)}}</td>
		<td align="center">{{number_format($vol->a2021_01)}}</td>
		
		<td align="center">{{number_format($vol->Sld_total)}}</td>
		<td align="center">{{number_format($vol->Meta_1_semestre)}}</td>
		
		@if ($vol->Sld_1_fim_semestre<0)
							<td align="center" style='color:red;'>	{{number_format($vol->Sld_1_fim_semestre)}}</td>
							@else
							<td align="center">	{{number_format($vol->Sld_1_fim_semestre)}}	</td>				 
							 @endif
			
		<td align="center">{{number_format($vol->a2021_08)}}</td>	
		<td align="center">{{number_format($vol->Meta_2_semestre)}}</td>
		
		@if ($vol->Sld_2_fim_semestre<0)
							<td align="center" style='color:red;'>	{{number_format($vol->Sld_2_fim_semestre)}}</td>
							@else
							<td align="center">	{{number_format($vol->Sld_2_fim_semestre)}}	</td>				 
							 @endif
		</tr>
		@endforeach
	</tbody>
		
	</table>
</div>
		


							 
							 
							 
@stop