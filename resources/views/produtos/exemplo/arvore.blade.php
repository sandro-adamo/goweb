@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Perfis de Acesso
@append 

@section('conteudo')

Representantes:
{{Session::get('representantes')}}
<br>


Supervisores:
{{Session::get('supervisores')}}
<br>


Diretores:
{{Session::get('diretores')}}
<br>

@stop