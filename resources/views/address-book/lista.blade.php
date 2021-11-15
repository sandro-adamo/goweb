@extends('layout.principal')

@section('title')
<i class="fa fa-book"></i> Address Book
@append 

@section('conteudo')
<?php
//if latitude and longitude are submitted
if(!empty($_POST['latitude']) && !empty($_POST['longitude'])){
    //send request and receive json data by latitude and longitude
    $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($_POST['latitude']).','.trim($_POST['longitude']).'&sensor=false';
    $json = @file_get_contents($url);
    $data = json_decode($json);
    $status = $data->status;
    
    //if request status is successful
    if($status == "OK"){
        //get address from json data
        $location = $data->results[0]->formatted_address;
    }else{
        $location =  '';
    }
    
    //return address to ajax 
    echo $location;
}
?>

<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
      <div class="row">
        <div class="col-md-6">
          <input type="text" name="pesquisa" class="form-control">
        </div>
        <div class="col-md-2">
          <button class="btn btn-default btn-flat"><i class="fa fa-search"></i> Pesquisar</button>
        </div>
        <div class="col-md-4">
        </div>
      </div>      
      <br>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th width="5%">Código</th>
            <th width="5%">Tipo</th>
            <th width="55%">Nome</th>
            <th width="15%">CNPJ</th>
            <th width="20%">Cidade</th>
            <th width="5%">Estado</th>
          </tr>
        </thead>
        <tbody>

          @foreach ($addressbook as $pessoa)
          <tr>
            <td align="center">{{$pessoa->id}}</td>
            <td align="center">{{$pessoa->tipo}}</td>
            <td><a href="">{{$pessoa->razao}}</a></td>
            <td align="center">{{$pessoa->cnpj}}</td>
            <td align="center">{{$pessoa->municipio}}</td>
            <td align="center">{{$pessoa->uf}}</td>
          </tr>
          @endforeach 
        </tbody>
      </table>
      <div class="col-md-12" align="center">{{$addressbook->links()}}</div>
    </div>
  </div>
  @stop