@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Perfis de Acesso
@append 

@section('conteudo')
 {{Session::get('representantes')}}
@stop