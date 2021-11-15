@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Perfil de Vendas
@append 

@section('conteudo')
@php

  $condcom = \DB::connection('goweb')->select("select * from precos");

@endphp
<h6>
<div class="row">
  <div class="col-md-12">
    <div class="box box-widget box-body">
<form action="" method="get">
      <div class="row">
        <div class="col-md-6">
          <input type="text" name="busca" class="form-control">
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-default btn-flat"><i class="fa fa-search"></i> Pesquisar</button>
        </div>
        <div class="col-md-4">
        </div>
      </div>      
</form>
      <br>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Cliente</th>
            <th>AH</th>
            <th>AT</th>
            <th>BG</h>
            <th>HI</th>
            <th>EV</th>
            <th>JO</th>
            <th>SP</th>
            <th>TC</th>
            <th>PU</th>
            <th>GU</th>
            <th>SL</th>
            <th>MB</th>
            <th>Condição Comercial</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($clientes as $cliente)
          <tr>
            <td><a href="/ecommerce/{{$cliente->id}}">{{$cliente->id}} - {{$cliente->razao}} - {{$cliente->cliente}}</a></td>
            <td align="center"><input type="checkbox" name="AH" class="seleGrife" data-value="{{$cliente->id}}" value="1" @if ($cliente->AH <> '') checked @endif></td>
            <td align="center"><input type="checkbox" name="AT" class="seleGrife" data-value="{{$cliente->id}}" value="1" @if ($cliente->AT <> '') checked @endif></td>
            <td align="center"><input type="checkbox" name="BG" class="seleGrife" data-value="{{$cliente->id}}" value="1" @if ($cliente->BG <> '') checked @endif></td>
            <td align="center"><input type="checkbox" name="HI" class="seleGrife" data-value="{{$cliente->id}}" value="1" @if ($cliente->HI <> '') checked @endif></td>
            <td align="center"><input type="checkbox" name="EV" class="seleGrife" data-value="{{$cliente->id}}" value="1" @if ($cliente->EV <> '') checked @endif></td>
            <td align="center"><input type="checkbox" name="JO" class="seleGrife" data-value="{{$cliente->id}}" value="1" @if ($cliente->JO <> '') checked @endif></td>
            <td align="center"><input type="checkbox" name="SP" class="seleGrife" data-value="{{$cliente->id}}" value="1" @if ($cliente->SP <> '') checked @endif></td>
            <td align="center"><input type="checkbox" name="TC" class="seleGrife" data-value="{{$cliente->id}}" value="1" @if ($cliente->TC <> '') checked @endif></td>
            <td align="center"><input type="checkbox" name="PU" class="seleGrife" data-value="{{$cliente->id}}" value="1" @if ($cliente->PU <> '') checked @endif></td>
            
             <td align="center"><input type="checkbox" name="GU" class="seleGrife" data-value="{{$cliente->id}}" value="1" @if ($cliente->GU <> '') checked @endif></td>
              <td align="center"><input type="checkbox" name="ST" class="seleGrife" data-value="{{$cliente->id}}" value="1" @if ($cliente->ST <> '') checked @endif></td>
               <td align="center"><input type="checkbox" name="MM" class="seleGrife" data-value="{{$cliente->id}}" value="1" @if ($cliente->MM <> '') checked @endif></td>
 
            <td>
              <select name="condcom" class="form-control">
                @foreach ($condcom as $a)

                  <option> {{$a->descricao}}</option>

                @endforeach
              </select>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
</div>
</div>
</h6>
@stop