@extends('layout.principal')



@section('title')
<form action="" method="get" class="form-horizontal">
<div class="row ">
  <div class="col-md-2">
    <button type="button" data-toggle="modal" data-target="#modalFiltros" class="btn btn-default btn-flat"><i class="fa fa-filter"></i> Filtros</button>
  </div>
  <div class="col-md-6">

  </div>
</div>
</form>
@append 

@section('conteudo')



      <div class="row">
        <div class="col-md-12">
          <div class="box box-widget">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users"></i> Carteira</h3>
            
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               <div class="col-md-4">
              <div class="table">
               <table class="table table-bordered text-center table-responsive">
                    <thead class="table-striped">
                        <tr>
                        <th>Tipo</th>
                        <th>Descrição</th>
                       
                        </tr>
                    </thead>
                    <tbody>
                        

                       
                            <tr>
                                <td>Fidelizados</td>
                                <td>Comprou nos ultimos 6 meses</td>
                            </tr>
                            <tr>
                                <td>Novos</td>
                                <td>Primeira compra na GO</td>
                            </tr>
                             <tr>
                                <td>Recuperados</td>
                                <td>Comprou agora, mas não tinha comprado maior que 12 meses</td>
                            </tr>
                            <tr>
                                <td>Não fidelizados</td>
                                <td>Não comprou nos ultimos 6 meses</td>
                            </tr>
                            <tr>
                                <td>A recuperar</td>
                                <td>Não comprou a mais que 12 meses</td>
                            </tr>
                             <tr>
                                <td>Sem venda</td>
                                <td>Não comprou da GO desde Jul/2018</td>
                            </tr>
                  </tbody>
              </table>

            </div>
            </div>
          </div>

              
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
         ['Grife', 'Fidelizados', 'Novos', 'Recuperados', 'Não fidelizados', 'A recuperar', 'Sem vendas'],
       @foreach ($carteiraGrife as $cats) 

            @php  
            $i = 0;
            $grife = $cats->grife;
            $fidelizados = $cats->fidelizados;
      $n_fidelizados = $cats->n_fidelizados;
      $a_recuperar = $cats->a_recuperar;
      $recuperados = $cats->recuperados;
      $sem_vendas = $cats->sem_vendas;
      $novos = $cats->novos;

       
      @endphp
      ['{{$grife}}', {{$fidelizados}},{{$novos}}, {{$recuperados}},{{$n_fidelizados}},{{$a_recuperar}},{{$sem_vendas}}],
    
       
          
  @endforeach

        ]);

        var options = {
          chart: {
            title: 'Carteira por PDV',
            subtitle: 'Status carteira por PDV',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('pdv'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
        </script>
      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
         ['Grife', 'Fidelizados', 'Novos', 'Recuperados', 'Não fidelizados', 'A recuperar', 'Sem vendas'],
       @foreach ($carteiraGrifeSub as $cats) 

            @php  
            $i = 0;
            $grife = $cats->grife;
            $fidelizados = $cats->fidelizados;
      $n_fidelizados = $cats->n_fidelizados;
      $a_recuperar = $cats->a_recuperar;
      $recuperados = $cats->recuperados;
      $sem_vendas = $cats->sem_vendas;
      $novos = $cats->novos;

       
      @endphp
      ['{{$grife}}', {{$fidelizados}},{{$novos}}, {{$recuperados}},{{$n_fidelizados}},{{$a_recuperar}},{{$sem_vendas}}],
    
       
          
  @endforeach

        ]);

        var options = {
          chart: {
            title: 'Carteira por SUb grupo',
            subtitle: 'Status carteira por Sub grupo',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('sub'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
  </head>
  <body>
     <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li ><a href="#tab_1" data-toggle="tab">PDV</a></li>
        <li ><a href="#tab_2" data-toggle="tab">Sub grupo</a></li>
       </ul>


      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">


    <div id="pdv" style="width: 800px; height: 500px;"></div>
     <div class="box-footer">
                <div id="tabela">
                    <table class="table table-bordered text-center table-responsive">
                    <thead class="table-striped">
                        <tr>
                        <th>Grifes</th>
                        <th>Fidelizados</th>
                        <th>Novos</th>
                        <th>Recuperados</th>
                        <th>Não Fidelizados</th>
                        <th>A Recuperar</th>
                        
                        <th>Sem Vendas</th>
                        
                        <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        

                        @foreach ($carteiraGrife as $catgri)
                            <tr>
                                <th scope='row'>{{$catgri->grife}}</th>
                                <td><a href="/carteira/detalhe/1/{{$catgri->grife}}/cli/xx">{{$catgri->fidelizados}}</a></td>
                                <td><a href="/carteira/detalhe/3/{{$catgri->grife}}/cli/xx">{{$catgri->novos}}</a></td>
                                <td><a href="/carteira/detalhe/2/{{$catgri->grife}}/cli/xx">{{$catgri->recuperados}}</a></td>

                                <td><a href="/carteira/detalhe/4/{{$catgri->grife}}/cli/xx">{{$catgri->n_fidelizados}}</a></td>
                                <td><a href="/carteira/detalhe/5/{{$catgri->grife}}/cli/xx">{{$catgri->a_recuperar}}</a></td>
                                
                                <td><a href="/carteira/detalhe/6/{{$catgri->grife}}/cli/xx">{{$catgri->sem_vendas}}</a></td>
                                
                                <td><a href="/carteira/detalhe/1,2,3,4,5,6/{{$catgri->grife}}/cli/xx">{{$catgri->total}}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
                </div>

  </div>
  
  
        <div class="tab-pane active" id="tab_2">
    <div id="sub" style="width: 800px; height: 500px;"></div>

      <div class="box-footer">
                <div id="tabela">
                    <table class="table table-bordered text-center table-responsive">
                    <thead class="table-striped">
                        <tr>
                        <th>Grifes</th>
                        <th>Fidelizados</th>
                        <th>Novos</th>
                        <th>Recuperados</th>
                        <th>Não Fidelizados</th>
                        <th>A Recuperar</th>
                        
                        <th>Sem Vendas</th>
                        
                        <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                     

                        @foreach ($carteiraGrifeSub as $catgriS)
                            <tr>
                                <th scope='row'>{{$catgriS->grife}}</th>
                                <td><a href="/carteira/detalhe/1/{{$catgriS->grife}}/sub/xx">{{$catgriS->fidelizados}}</a></td>
                                <td><a href="/carteira/detalhe/3/{{$catgriS->grife}}/sub/xx">{{$catgriS->novos}}</a></td>
                                <td><a href="/carteira/detalhe/2/{{$catgriS->grife}}/sub/xx">{{$catgriS->recuperados}}</a></td>

                                <td><a href="/carteira/detalhe/4/{{$catgriS->grife}}/sub/xx">{{$catgriS->n_fidelizados}}</a></td>
                                <td><a href="/carteira/detalhe/5/{{$catgriS->grife}}/sub/xx">{{$catgriS->a_recuperar}}</a></td>
                                
                                <td><a href="/carteira/detalhe/6/{{$catgriS->grife}}/sub/xx">{{$catgriS->sem_vendas}}</a></td>
                                
                                <td><a href="/carteira/detalhe/1,2,3,4,5,6/{{$catgri->grife}}/sub/xx">{{$catgriS->total}}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
    </div>
  </div>
  </body>
               <!-- /.users-list -->
              <!--  <div class="chart">
                <canvas id="myChart3" style="height:230px"></canvas>

              </div> -->
            <!-- /.box-body -->
            </div>
            
            <!-- /.box-footer -->
          </div>
          <!--/.box -->
       </div>
       <input class="btn btn-success" type="button" value="Criar PDF" id="btnImprimir" onclick="getPDF()" />
    </div>

    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js" integrity="sha384-THVO/sM0mFD9h7dfSndI6TS0PgAGavwKvB5hAxRRvc0o9cPLohB0wb/PTA7LdUHs" crossorigin="anonymous"></script>
    <script src="https://superal.github.io/canvas2image/canvas2image.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

    <script>
        function getPDF()  {
       html2canvas(document.getElementById("tabela"),{
        onrendered:function(canvas){
            
        var dia = new Date();
        var convert = dia.toLocaleDateString();
        var img = canvas.toDataURL("image/png");
        var doc = new jsPDF('l', 'cm'); 
        doc.addImage(img,'PNG',0.5,0.5);
        doc.save('reporte_' + convert + '.pdf');
       }
    }); 
}
    </script>
    

@stop