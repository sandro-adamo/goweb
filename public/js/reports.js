



      $(document).ready(function() {

          $.ajax({ 

            url: '/reports/statustop',
            dataType: 'json',

            success: function(result) {


                var valores =  [];

                $.each(result, function(index, value) {

                  var teste = [];

                  teste.push(value.agrup);
                  teste.push(value.disponivel);
                  teste.push(value.dias15);
                  teste.push(value.dias30);
                  teste.push(value.producao);
                  teste.push(value.esgotado);
                  
                  
                  valores.push(teste);

                });

               // valores += ']';

                var chart5 = c3.generate({
                  bindto: '#chart5',
                  data: {
                    columns: valores ,
                    type: 'area-spline'
                  },
                  size: {
                    height: 180
                  }        
                });
                //OnSuccess(result);

            }

          });

             

        
      });

