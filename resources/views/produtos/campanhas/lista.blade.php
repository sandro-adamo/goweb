@extends('produtos/painel/index')

@section('title')
<i class="fa fa-map"></i> Campanhas {{$item}}
@append 

@section('conteudo')


<h3>{{$item}}</h3>


<form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
  @csrf
  <div class="row">
    <div class="col-md-12">
      <div class="box box-widget box-body">
        <div class="row">
          <div class="col-md-6">
            <input type="file" name="arquivo" class="form-control">
          </div>
          <div class="col-md-2">
            <button class="btn btn-default btn-flat"><i class="fa fa-upload"></i> Upload</button>
          </div>
          <div class="col-md-4">
          </div>
        </div>      
        <br>
      </div>
    </div>
  </div>

  <div class="row">

    @foreach ($campanhas as $campanha) 

      @if ($campanha->arquivo <> '')
        <div class="col-md-3"> 
          <div>{{$campanha->secundario.' - '.$campanha->statusatual}}</div>          
          <img src="/storage/{{$campanha->arquivo}}" class="img-responsive">
         
        </div>
      @endif

    @endforeach

  </div>
</form>
@stop