@extends('layout.principal')

@section('title')
<i class="fa fa-file-o"></i> Fornecedor


@section('conteudo')

@if (Session::has('alert-success'))
  <div class="callout callout-success">{{Session::get("alert-success")}}</div>
@endif 


<div class="row" >
  <div class="col-md-12" >

    <div class="box box-widget">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-industry"></i> Fornecedor</h3>
        <span class="pull-right"></span>
      </div>
      <div class="box-body"> 


      <div class="box box-alert">
   <h3 class="box-title">Dados</h3>
          <div class="row">
            <label class="col-md-1 control-label">Razao </label> 
            <div class="col-md-4">
            Xiamem pos optical ltda
            </div>

            <label class="col-md-1 control-label">Nome</label> 
            <div class="col-md-2">
             Xiamen Pos
            </div>

             <label class="col-md-1 control-label">CNPJ</label> 
            <div class="col-md-1">
             111111111111
            </div>
          </div>

          <div class="row">
           
            
            <label class="col-md-1 control-label">Cidade</label> 
            <div class="col-md-2">
            Xiamen
            </div>
            <label class="col-md-1 control-label">Pais</label> 
            <div class="col-md-2">
            China
            </div>
            <label class="col-md-1 control-label">Bairro</label> 
            <div class="col-md-2">
            OUHAI DISTRICT
            </div>
          </div>
          <div class="row">
              <label class="col-md-1 control-label">Endereço</label> 
            <div class="col-md-5">
             NO. 689 JINGYU ROAD,LOUQIAO STREET
            </div>

            <label class="col-md-2 control-label">ZIP code</label> 
            <div class="col-md-2">
            
            </div>
          </div>
        </div>
         
            
         
            <div class="box box-danger">
              <h3 class="box-title">Informações financeiras</h3>
      
             <div class="row"> 

            
            <label class="col-md-2 control-label">Banco</label> 
            <div class="col-md-2">
             San Paolo
            </div>
            <label class="col-md-2 control-label">Num Banco Iban</label> 
            <div class="col-md-2">
            BXT0709
            </div>

              <label class="col-md-2 control-label">Swift </label> 
            <div class="col-md-2  ">
             45678856789
            </div>
            </div>

              <div class="row">            
            <label class="col-md-2 control-label">Moeda</label> 
            <div class="col-md-2">
             U$
            </div>
            <label class="col-md-2 control-label">Forma pagamento</label> 
            <div class="col-md-2">
            Tranferência
            </div>

              <label class="col-md-2 control-label">Icoterm </label> 
            <div class="col-md-2  ">
             CIP GRU
            </div>
            </div>

          <div class="row">            
            <label class="col-md-2 control-label">Prazo pagamento</label> 
            <div class="col-md-2">
             60 dias após awb
            </div>

            <label class="col-md-2 control-label">Cobra amostra</label> 
            <div class="col-md-2">
             Sim
            </div>
            </div>

           <div class="row"> 

            
            <label class="col-md-2 control-label">Telefone</label> 
            <div class="col-md-2">
             0577-86282789
            </div>
            <label class="col-md-2 control-label">E-mail</label> 
            <div class="col-md-2">
            may@posoptical.cn
            </div>

              <label class="col-md-2 control-label">Contato </label> 
            <div class="col-md-2  ">
             May
            </div>
            </div>
            
            </div>

            <div class="box box-success">
          <h3 class="box-title">Informações comerciais</h3>
          
             <div class="row"> 

            
            <label class="col-md-2 control-label">Telefone</label> 
            <div class="col-md-2">
             0577-86282789
            </div>
            <label class="col-md-2 control-label">E-mail</label> 
            <div class="col-md-2">
            may@posoptical.cn
            </div>

              <label class="col-md-2 control-label">Contato </label> 
            <div class="col-md-2  ">
             May
            </div>
            </div>
             <div class="row"> 
              <label class="col-md-2 control-label">Especialidade</label> 
            <div class="col-md-2">
             Injetado, acetato
            </div>
            <label class="col-md-2 control-label">E-Grife</label> 
            <div class="col-md-2">
            Atitude, Speedo
            </div>

              <label class="col-md-2 control-label">Qualidade </label> 
            <div class="col-md-2  ">
             Média
            </div>
            </div>

            <div class="row"> 
              <label class="col-md-2 control-label">Tempo produção</label> 
            <div class="col-md-2">
             2 meses
            </div>
            <label class="col-md-2 control-label">Volume produção</label> 
            <div class="col-md-2">
            200k
            </div>

              <label class="col-md-2 control-label">Qualidade </label> 
            <div class="col-md-2  ">
             Média
            </div>
            </div>

         </div>
         </div>
         </div>
         
       <br>
   
      
 <div class="col-md-6" >
 
<div class="box box-widget">
      <div class="box-header with-border">
        <div class="box box-primary">
         
      <h3 class="box-title">Score</h3>

          <table class="table table-striped" >
            <tr>
            <td  class="col-md-5" align="pull-left"><div></div><td>
            <td class="col-md-4" >
         
            </td>
            <td class="col-md-3"  align="pull-right">Média<td>

            </tr>
              <tr>
            <td class="col-md-5" align="pull-left"><div>Tempo de entrega</div><td>
            <td class="col-md-4" >
            <i class="fa fa-star"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            </td>
            <td class="col-md-3"  align="pull-right">30 dias<td>

            </tr>

              <tr>
            <td  class="col-md-5" align="pull-left"><div>Cancelamento</div><td>
            <td class="col-md-4" >
            <i class="fa fa-star"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            </td>
            <td class="col-md-3"  align="pull-right">10.000<td>

            </tr>
              <tr>
            <td  class="col-md-5" align="pull-left"><div>% Defeitos</div><td>
            <td class="col-md-4" >
            <i class="fa fa-star"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            </td>
            <td class="col-md-3"  align="pull-right">40%<td>

            </tr>
              <tr>
            <td class="col-md-5" align="pull-left"><div>Preço</div><td>
            <td class="col-md-4">
            <i class="fa fa-star"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            </td>
            <td class="col-md-3" align="pull-right">U$4,00<td>

            </tr>
              <tr>
            <td class="col-md-5" align="pull-left"><div>Forma pagamento</div><td>
            <td class="col-md-4" >
            <i class="fa fa-star"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            </td>
            <td class="col-md-3" align="pull-right">90 dias<td>

            </tr>
              <tr>
            <td style="color:white" class="col-md-5" align="pull-left"><div>Capacidade de produção</div><td>
            <td class="col-md-4" style="color:white">
            <i class="fa fa-star"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            </td>
            <td class="col-md-3" style="color:white" align="pull-right">10k mês<td>

            </tr>
              <tr>
            <td style="color:white" class="col-md-5" align="pull-left"><div>Comunicação</div><td>
            <td class="col-md-4" style="color:white">
            <i class="fa fa-star"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            <i class="fa fa-star-o"> </i>
            </td>
            <td class="col-md-3" style="color:white" align="pull-right">5 dias<td>

            </tr>
           


            </table>
    </div>
  </div>
</div>
</div>

<div class="col-md-6" >
 
<div class="box box-widget">
      <div class="box-header with-border">
 <div class="box box-warning">
<h3 class="box-title">Invoices</h3>

          
           <table class="table table-striped" >
            <tr>
            <td>Invoice</td>
            <td>Valor</td>
            <td>Data</td>
            <td>Pagamento</td>
            <td>Previsão</td>
            </tr>

             <tr>
            <td>ZA4567</td>
            <td>U$20.000</td>
            <td>20/10/2020</td>
            <td>Em aberto</td>
            <td>25/10/2020</td>
            </tr>
          </table>

            </div>
      </div>
    </div>
  </div>


   

@stop
