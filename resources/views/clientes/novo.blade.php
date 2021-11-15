@extends('layout.principal')

@section('title')
<i class="fa fa-group"></i> Novo Cadastro
@append 

@section('conteudo')

@if (Session::has('alert-warning'))
    <div class="callout callout-warning">
        {{Session::get('alert-warning')}}
    </div>
@endif

@if (isset($novo_cadastro->situacao) && $novo_cadastro->situacao == 'Pendente')
    <div class="callout callout-warning">
        Pendencia Cadastral
    </div>
@endif

<form action="/clientes/grava" method="post" class="form-horizontal">
  @csrf
<div class="row">
  <div class="col-md-9">

    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-edit"></i> Dados do Cliente</h3>
      </div>
      <div class="box-body">
{{-- 


        <div class="form-group">
          <label class="col-md-2 control-label"></label>
          <div class="col-md-10">
            <input type="checkbox" name="tipo" value="Franquia"> Franquia
            <input type="checkbox" name="tipo" value="Franquia" style="margin-left: 15px;"> Própria
            <input type="checkbox" name="tipo" value="Franquia" style="margin-left: 15px;"> Possuí outras lojas
          </div>
        </div> --}}

        <div class="form-group">
          <label class="col-md-2 control-label">CNPJ</label>
          <div class="col-md-4">
            <input type="text" class="form-control" name="cnpj" required="" value="{{$novo_cadastro->cnpj}}">
          </div>
          <label class="col-md-2 control-label">I.E.</label>
          <div class="col-md-4">
            <input type="text" class="form-control" name="ie" required="" value="{{$novo_cadastro->ie}}">
          </div>
        </div>


        <div class="form-group">
          <label class="col-md-2 control-label">Razão Social</label>
          <div class="col-md-10">
            <input type="text" class="form-control" name="razao" required="" value="{{$novo_cadastro->razao}}">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Fantasia</label>
          <div class="col-md-10">
            <input type="text" class="form-control" name="fantasia" required="" value="{{$novo_cadastro->fantasia}}">
          </div>
        </div>


        <div class="form-group">
          <label class="col-md-2 control-label"></label>
          <div class="col-md-10">
            <hr style="margin: 0;">
          </div>
        </div>
{{-- 
        <div class="form-group">
          <label class="col-md-2 control-label">Ramo Atividade</label>
          <div class="col-md-10">
            <input type="text" class="form-control" name="razao">
          </div>
        </div> --}}


        <div class="form-group">
          <label class="col-md-2 control-label">Inicio</label>
          <div class="col-md-4">
            <input type="date" class="form-control" name="dt_inicio"  required="" value="{{$novo_cadastro->dt_inicio}}">
          </div>
          <label class="col-md-2 control-label">Suframa</label>
          <div class="col-md-4">
            <input type="text" class="form-control" name="suframa" value="{{$novo_cadastro->suframa}}">
          </div>
        </div>


      </div>
    </div>


    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-map"></i> Endereço da Loja</h3>
      </div>
      <div class="box-body">

        <div class="form-group">
          <label class="col-md-2 control-label">CEP</label>
          <div class="col-md-2">
            <input type="text" class="form-control cep" name="cep" required="" value="{{$novo_cadastro->cep}}">
          </div>
          <label class="col-md-1 control-label">Cidade</label>
          <div class="col-md-4">
            <input type="text" class="form-control" name="cidade" required="" value="{{$novo_cadastro->cidade}}">
          </div>
          <label class="col-md-1 control-label">Estado</label>
          <div class="col-md-2">
            <input type="text" name="estado" class="form-control" required="" value="{{$novo_cadastro->estado}}">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Endereço</label>
          <div class="col-md-7">
            <input type="text" class="form-control" name="endereco" required="" value="{{$novo_cadastro->endereco}}">
          </div>
          <label class="col-md-1 control-label">Nº</label>
          <div class="col-md-2">
            <input type="text" class="form-control" name="numero" required="" value="{{$novo_cadastro->numero}}">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Complemento</label>
          <div class="col-md-4">
            <input type="text" class="form-control" name="complemento" required="" value="{{$novo_cadastro->complemento}}">
          </div>
          <label class="col-md-2 control-label">Bairro</label>
          <div class="col-md-4">
            <input type="text" class="form-control" name="bairro" required="" value="{{$novo_cadastro->bairro}}">
          </div>
        </div>


      </div>
    </div>


{{-- 
    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-map"></i> Endereço de Cobrança</h3>
      </div>
      <div class="box-body">

      

        <div class="form-group">
          <label class="col-md-2 control-label"></label>
          <div class="col-md-10"> 
            <input type="checkbox" name="mesmo_endereco" value="1"> O mesmo endereço da loja
          </div>
        </div>


        <div class="form-group">
          <label class="col-md-2 control-label">CEP</label>
          <div class="col-md-2">
            <input type="text" class="form-control cep" name="cep_cobranca">
          </div>
          <label class="col-md-1 control-label">Cidade</label>
          <div class="col-md-4">
            <input type="text" class="form-control" name="cidade_cobranca">
          </div>
          <label class="col-md-1 control-label">Estado</label>
          <div class="col-md-2">
            <select name="estado_cobranca" class="form-control">
              <option value=""></option>
              <option>AM</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Endereço</label>
          <div class="col-md-7">
            <input type="text" class="form-control" name="endereco_cobranca">
          </div>
          <label class="col-md-1 control-label">Nº</label>
          <div class="col-md-2">
            <input type="text" class="form-control" name="numero_cobranca">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Complemento</label>
          <div class="col-md-4">
            <input type="text" class="form-control" name="endereco_cobranca">
          </div>
          <label class="col-md-2 control-label">Bairro</label>
          <div class="col-md-4">
            <input type="text" class="form-control" name="bairro_cobranca">
          </div>
        </div>


      </div>
    </div>
 --}}



    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-envelope"></i> Contatos</h3>
      </div>
      <div class="box-body">
   

        <div class="form-group">
          <label class="col-md-2 control-label">Proprietário</label>
          <div class="col-md-10">
            <input type="text" class="form-control" name="proprietario" required="" value="{{$novo_cadastro->proprietario}}">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">Celular</label>
          <div class="col-md-3">
            <input type="text" class="form-control celular" name="celular_proprietario" required="" value="{{$novo_cadastro->celular_proprietario}}">
          </div>
          <label class="col-md-1 control-label">E-Mail</label>
          <div class="col-md-6">
            <input type="email" class="form-control" name="email_proprietario" required="" value="{{$novo_cadastro->email_proprietario}}">
          </div>
        </div>


        <div class="form-group">
          <label class="col-md-2 control-label"></label>
          <div class="col-md-10">
            <hr style="margin: 0;">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Gerente</label>
          <div class="col-md-10">
            <input type="text" class="form-control" name="gerente" required="" value="{{$novo_cadastro->gerente}}">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">Celular</label>
          <div class="col-md-3">
            <input type="text" class="form-control celular" name="celular_gerente" required="" value="{{$novo_cadastro->celular_gerente}}">
          </div>
          <label class="col-md-1 control-label">E-Mail</label>
          <div class="col-md-6">
            <input type="email" class="form-control" name="email_gerente" required="" value="{{$novo_cadastro->email_gerente}}">
          </div>
        </div>


        <div class="form-group">
          <label class="col-md-2 control-label"></label>
          <div class="col-md-10">
            <hr style="margin: 0;">
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-2 control-label">Financeiro</label>
          <div class="col-md-10">
            <input type="text" class="form-control" name="financeiro" required="" value="{{$novo_cadastro->financeiro}}">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-2 control-label">Celular</label>
          <div class="col-md-3">
            <input type="text" class="form-control celular" name="celular_financeiro" required="" value="{{$novo_cadastro->celular_financeiro}}">
          </div>
          <label class="col-md-1 control-label">E-Mail</label>
          <div class="col-md-6">
            <input type="email" class="form-control" name="email_financeiro" required="" value="{{$novo_cadastro->email_financeiro}}">
          </div>
        </div>


      </div>
    </div>




    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-envelope"></i> Obervações</h3>
      </div>
      <div class="box-body">
        <textarea class="form-control" rows="6" name="observacoes">{{$novo_cadastro->obs}}</textarea>
      </div>
    </div>

  </div>

  <div class="col-md-3">

    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-gears"></i> Controle</h3>
      </div>

      <div class="box-body">
        <div class="form-group">
            <label for="" class="col-md-3 control-label">ID</label>
            <div class="col-md-9">
                <input type="text" name="id" class="form-control" readonly value="{{$novo_cadastro->id}}">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-md-3 control-label">Situação</label>
            <div class="col-md-9">
                <input type="text" name="situacao" class="form-control" readonly value="{{$novo_cadastro->situacao}}">
            </div>
        </div>
      </div>

      <div class="box-footer">
          @if ($novo_cadastro->situacao == 'Novo') 
              <a href="" class="btn btn-danger btn-flat pull-left"><i class="fa fa-trash"></i> Excluir Cadastro</a>
          @endif
            <button type="submit" class="btn btn-primary btn-flat pull-right"><i class="fa fa-save"></i> Gravar</button>
      </div>
    </div>


    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-file"></i> Anexos</h3>
      </div>

      <div class="box-body">
        <input type="file" name="foto" class="form-control">
      </div>
    </div>


  </div>


</div>
</form>
@stop