@php

    $tabelas = \DB::select("show tables");

@endphp

<!DOCTYPE html>
<html>
    <head>
        <title>Pivot Demo</title>
        <!-- external libs from cdnjs -->
 {{--        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="/css/bootstrap.min.css">

        <!-- PivotTable.js libs from ../dist -->
        <link rel="stylesheet" type="text/css" href="/pivottable-master/dist/pivot.css">
        <script type="text/javascript" src="/pivottable-master/dist/pivot.js"></script>


         --}}
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/4.1.2/papaparse.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.js"></script>
        <script src="https://cdn.plot.ly/plotly-basic-latest.min.js"></script>

        <!-- PivotTable.js libs from ../dist -->
        <link rel="stylesheet" type="text/css" href="/pivottable-master/dist/pivot.css">
        <script type="text/javascript" src="/pivottable-master/dist/pivot.js"></script>
        <script type="text/javascript" src="/pivottable-master/dist/d3_renderers.js"></script>
        <script type="text/javascript" src="/pivottable-master/dist/plotly_renderers.js"></script>
        <script type="text/javascript" src="/pivottable-master/dist/export_renderers.js"></script>


        <style>
            body {font-family: Verdana;}
        </style>

        <!-- optional: mobile support with jqueryui-touch-punch -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

        <!-- for examples only! script to show code to user -->
        <script type="text/javascript" src="show_code.js"></script>
    </head>
    <body>
        <form action="" method="post" id="frm">@csrf
        <div class="row">
            <div class="col-md-2">
                <select id="tabela" name="tabela">
                    <option></option>
                    @foreach ($tabelas as $table)
                        <option>{{$table->Tables_in_go}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <textarea name="select" id="select" class="form-control"></textarea>
            </div>
            <div class="col-md-1">
                <button type="submit">Executar</button>
            </div>
        </div>
        </form>


        <script type="text/javascript">
    // This example is the most basic usage of pivotUI()

            $(function(){
                Papa.parse("https://raw.githubusercontent.com/nicolaskruchten/Rdatasets/master/datasets.csv", {
                    download: true,
                    header: true,
                    skipEmptyLines: true,
                    complete: function(parsed){
                        var csvlist_arr = parsed.data;
                        var pkg = $("<optgroup>", {label: ""});
                        for(var i in csvlist_arr)
                        {
                            var dataset = csvlist_arr[i];
                            if(dataset.Package != pkg.attr("label"))
                            {
                                pkg = $("<optgroup>", {label: dataset.Package}).appendTo($("#csv"));
                            }
                            pkg.append($("<option>", {value: dataset.Package+"/"+dataset.Item}).text(dataset.Item +":" +dataset.Title));
                        }
                        $("#csv").chosen();
                        var renderers = $.extend(
                            $.pivotUtilities.renderers,
                            $.pivotUtilities.plotly_renderers,
                            $.pivotUtilities.d3_renderers,
                            $.pivotUtilities.export_renderers
                            );
                        $("#csv").bind("change", function(event){
                            $("#output").empty().text("Loading...")
                            var val = $(this).val();
                            Papa.parse("https://raw.githubusercontent.com/nicolaskruchten/Rdatasets/master/csv/"+val+".csv", {
                                download: true,
                                skipEmptyLines: true,
                                complete: function(parsed){
                                $("#doc").empty().append(
                                    $("<a>",{target:"_blank", href:"http://nicolas.kruchten.com/Rdatasets/doc/"+val+".html"}).html("Dataset documentation &raquo;")
                                    );
                                    $("#output").pivotUI(parsed.data, {
                                        hiddenAttributes: [""],
                                        renderers: renderers }, true);
                                }
                            });
                        });
                    }
                });
            });

            $("#frm").submit(function(event) {
                event.preventDefault();

                var tabela = $("#tabela").val();
                var select = $("#select").val();

                $(function(){
                    $.ajax({

                        url: '/api/pivot',
                        data: {
                            tabela: tabela,
                            select: select
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function(result) {
                            $("#output").pivotUI(result);
                            $("#carregando").css('display', 'none');
                        }
                    });
                 });                

            });

        </script>

        <p id="carregando">carregando...</p>

        <div id="output" style="margin: 30px;"></div>

    </body>
</html>
