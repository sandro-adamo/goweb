<!DOCTYPE html>
<html>
    <head>
        <title>Pivot Demo</title>
        <!-- external libs from cdnjs -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

        <!-- PivotTable.js libs from ../dist -->
        <link rel="stylesheet" type="text/css" href="/pivottable-master/dist/pivot.css">
        <script type="text/javascript" src="/pivottable-master/dist/pivot.js"></script>
        <style>
            body {font-family: Verdana;}
        </style>

        <!-- optional: mobile support with jqueryui-touch-punch -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

        <!-- for examples only! script to show code to user -->
        <script type="text/javascript" src="show_code.js"></script>
    </head>
    <body>
        <script type="text/javascript">
    // This example is the most basic usage of pivotUI()

    $(function(){
        $("#output").pivotUI(
            [
                {agrupamento: 'AH01', grife: "AH", modelo: 'AH1020', cor: 1},
                {agrupamento: 'AH01', grife: "AH", modelo: 'AH1020', cor: 1},
                {agrupamento: 'AH01', grife: "AH", modelo: 'AH1020', cor: 1}
            ],
            {
                rows: ["color"],
                cols: ["shape"]
            }
        );
     });
        </script>

        <p><a href="index.html">&laquo; back to PivotTable.js examples</a></p>

        <div id="output" style="margin: 30px;"></div>

    </body>
</html>
