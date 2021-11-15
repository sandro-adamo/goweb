



      $(document).ready(function() {

          var periodo = $("#periodo").val();
          console.log(periodo);

          $.ajax({

            url: '/dashboard/cliente',
            dataType: 'json',

            success: function(result) {
                
                var fideliza = new Array;
                var recup = new Array;
                var novos = new Array;
                var nfideli = new Array;
                var arecup = new Array;
                var rotulos = new Array;
                var sem_venda = new Array;
  
                $.each(result, function(index, val) {
                   rotulos.push(val.grife);
                   fideliza.push(val.fidelizados);
                   nfideli.push(val.n_fidelizados); 
                   arecup.push(val.a_recuperar); 
                   recup.push(val.recuperados); 
                   novos.push(val.novos); 
                   sem_venda.push(val.sem_vendas); 
                });

              var ctx = document.getElementById('myChart3').getContext('2d');
              var myChart3 = new Chart(ctx, {
                  type: 'bar',
                  data: {
                    datasets: [{
                      label: 'Fidelizados',
                      data:fideliza,
                      steppedLine: false,
                      borderDash: [0, 0],
                      pointRadius: 5,
                      pointHoverRadius: 10,
                      showLine: false,
                      fill: false,
                      order: 1,
                      backgroundColor: 'rgba(34,139,34, 1)',
                      borderColor: 'rgb(34,139,34)'
                      
                    }, {
                      label: 'Não Fidelizados',
                      data: nfideli,
                      steppedLine: false,
                      borderDash: [0, 0],
                      pointRadius: 5,
                      pointHoverRadius: 10,
                      showLine: false,
                      fill: false,
                      order: 2,
                      backgroundColor: 'rgba(255,140,0, 1)',
                      borderColor: 'rgb(255,140,0)'
                    }, {
                      label: 'A Recuperar',
                      data: arecup,
                      steppedLine: false,
                      borderDash: [0, 0],
                      pointRadius: 5,
                      pointHoverRadius: 10,
                      showLine: false,
                      fill: false,
                      order: 3,
                      backgroundColor: 'rgba(178,34,34, 1)',
                      borderColor: 'rgb(178,34,34)'
                    }, {
                      label: 'Recuperados',
                      data: recup,
                      steppedLine: false,
                      borderDash: [0, 0],
                      pointRadius: 5,
                      pointHoverRadius: 10,
                      showLine: false,
                      fill: false,
                      order: 4,
                      backgroundColor: 'rgba(30,144,255, 1)',
                      borderColor: 'rgb(30,144,255)'
                    },{
                      label: 'Sem Vendas',
                      data: sem_venda,
                      steppedLine: false,
                      borderDash: [0, 0],
                      pointRadius: 5,
                      pointHoverRadius: 10,
                      showLine: false,
                      fill: false,
                      order: 5,
                      backgroundColor: 'rgba(128,128,128, 1)',
                      borderColor: 'rgb(128,128,128)'
                      }, {
                      label: 'Novos',
                      data: novos,
                      steppedLine: false,
                      borderDash: [0, 0],
                      pointRadius: 5,
                      pointHoverRadius: 10,
                      showLine: false,
                      fill: false,
                      order: 6,
                      backgroundColor: 'rgba(255,215,0)',
                      borderColor: 'rgb(255,215,0)'
                    }],
                    labels: rotulos,
                  },
                  options:{
                    scales: {
       
                    },
                    layout: {
                      padding: {
                          left: 0,
                          right: 0,
                          top: 0,
                          bottom: 0
                          }
                      },
                      elements: {
                        point: {
                          pointStyle: 'rectRounded'
                        }
                      },
                  }
                  });
 //Chart;

          }//Function

        }) //Ajax;

          $.ajax({

            url: '/dashboard/vendas?periodo='+periodo,
            dataType: 'json',

            success: function(result) {

              var dados = [ result[0].d1 , result[0].d2, result[0].d3, result[0].d4, result[0].d5, result[0].d6, result[0].d7, result[0].d8, result[0].d9, result[0].d10, result[0].d11, result[0].d12, result[0].d13, result[0].d14, result[0].d15, result[0].d16, result[0].d17, result[0].d18, result[0].d19, result[0].d20, result[0].d21, result[0].d22, result[0].d23, result[0].d24, result[0].d25, result[0].d26, result[0].d27, result[0].d28, result[0].d29, result[0].d30, result[0].d31 ];
              var rotulos = [ 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31 ];
                

              var ctx = document.getElementById('myChart2').getContext('2d');
              var myChart2 = new Chart(ctx, {
                  type: 'line',
                  data: {
                      labels: rotulos,
                      datasets: [{
                          label: 'Vendas Dia' ,
                          data: dados,
                          backgroundColor: [
                              'rgba(153, 102, 255, 0.2)' // roxo

                          ],
                          borderColor: [
                              'rgba(153, 102, 255, 1)'// roxo

                          ],
                          borderWidth: 1
                      }]
                  },
                  options: {
                      scales: {
                          yAxes: [{
                              ticks: {
                                  beginAtZero: true
                              }
                          }]
                      }
                  }
              });
            }
          });

          $.ajax({

            url: '/dashboard/carteira',
            dataType: 'json',

            success: function(result) {

              var dados = new Array;
              var rotulos = new Array;

              $.each(result, function(index, val) {
                 rotulos.push(val.situacao);
                 dados.push(val.clientes); 
              });

              var ctx = document.getElementById('myChart').getContext('2d');
              var myChart = new Chart(ctx, {
                  type: 'bar',
                  data: {
                      labels: rotulos,
                      datasets: [{
                          label: 'Fidelizados' ,
                          data: dados,
                          backgroundColor: [
                              'rgba(153, 102, 255, 0.2)', // roxo
                              'rgba(255, 206, 86, 0.2)', // amarelo
                              'rgba(75, 192, 192, 0.2)', // verde
                              'rgba(255, 99, 132, 0.2)', // vermelho
                              'rgba(54, 162, 235, 0.2)', // azul
                              'rgba(255, 159, 64, 0.2)'
                          ],
                          borderColor: [
                              'rgba(153, 102, 255, 1)', // roxo
                              'rgba(255, 206, 86, 1)', // amarelo
                              'rgba(75, 192, 192, 1)', // verde
                              'rgba(255, 99, 132, 1)', // vermelho
                              'rgba(54, 162, 235, 1)', // azul
                              'rgba(255, 159, 64, 1)'
                          ],
                          borderWidth: 1
                      }]
                  },
                  options: {
                      scales: {
                          yAxes: [{
                              ticks: {
                                  beginAtZero: true
                              }
                          }]
                      }
                  }
              });
            }
          });


          // $.ajax({ 

          //   url: '/dashboard/carteira',
          //   dataType: 'json',

          //   success: function(result) {

          //     var dados = new Array;

          //     $.each(result, function(index, val) {
          //        var linha = [ val.situacao, val.clientes];
          //        dados.push(linha); 
          //     });
          //        console.log(dados);

          //     var chart = c3.generate({
          //         bindto: '#myChawwrt',
          //         data: {
          //             columns: [ ['teste',10], ['teste2',20] ],
          //             type: 'bar'
          //         }
                                    
          //     });              

          //   }
          // });
          $.ajax({ 

            url: '/dashboard/indGrife',
            dataType: 'json',

            success: function(result) {
              var chart = c3.generate({
                  bindto: '#chart',
                  data: {
                      columns: [
                          ['grifes 12meses', result[0].ind12]
                      ],
                      type: 'gauge',
                  },
                  gauge: {
                    label: {
                     format: function(value, ratio) {
                       return value;
                     },

          //        show: false // to turn off the min/max labels.
                    },
                    min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
                    max: 8, // 100 is default
          //          units: ' %',
                    //width: 39 // for adjusting arc thickness
                  },
                  color: {
                    pattern: ['#FF0000', '#F6C600', '#60B044'], // the three color levels for the percentage values.
                    threshold: {
                      unit: 'value', // percentage is default
          //            max: 200, // 100 is default
                      values: [2, 4, 9] // alternate first value is 'value'
                    }
                  },
                  size: {
                    height: 140
                  }        

              });

              var chart1 = c3.generate({
                  bindto: '#chart1',
                  data: {
                      columns: [
                          ['grifes mes', result[0].ind18]
                      ],
                      type: 'gauge',
                  },
                  gauge: {
                    label: {
                     format: function(value, ratio) {
                       return value;
                     },
                     extents: function (value, isMax) {
                         return value ;
                     },
          //        show: false // to turn off the min/max labels.
                    },
                    min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
                    max: 8, // 100 is default
          //          units: ' %',
                    //width: 39 // for adjusting arc thickness
                  },
                  color: {
                    pattern: ['#FF0000', '#F6C600', '#60B044'], // the three color levels for the percentage values.
                    threshold: {
                      unit: 'value', // percentage is default
          //            max: 200, // 100 is default
                      values: [2, 4, 9] // alternate first value is 'value'
                    }
                  },
                  size: {
                    height: 140
                  }                   
              });              

            }
          });



          $.ajax({ 

            url: '/dashboard/indFrequencia',
            dataType: 'json',

            success: function(result) {
              var chart2 = c3.generate({
                  bindto: '#chart2',
                  data: {
                      columns: [
                          ['frequencia 12meses', result[0].ind12]
                      ],
                      type: 'gauge',
                  },
                  gauge: {
                    label: {
                     format: function(value, ratio) {
                       return value;
                     },

          //        show: false // to turn off the min/max labels.
                    },
                    min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
                    max: 6, // 100 is default
          //          units: ' %',
                    //width: 39 // for adjusting arc thickness
                  },
                  color: {
                    pattern: ['#FF0000', '#F6C600', '#60B044'], // the three color levels for the percentage values.
                    threshold: {
                      unit: 'value', // percentage is default
          //            max: 200, // 100 is default
                      values: [2, 4, 9] // alternate first value is 'value'
                    }
                  },
                  size: {
                    height: 140
                  }        


              });

              var chart3 = c3.generate({
                  bindto: '#chart3',
                  data: {
                      columns: [
                          ['frequencia mes', result[0].ind18]
                      ],
                      type: 'gauge',
                  },
                  gauge: {
                    label: {
                     format: function(value, ratio) {
                       return value;
                     },
                     extents: function (value, isMax) {
                         return value ;
                     },
          //        show: false // to turn off the min/max labels.
                    },
                    min: 0, // 0 is default, //can handle negative min e.g. vacuum / voltage / current flow / rate of change
                    max: 6, // 100 is default
          //          units: ' %',
                    //width: 39 // for adjusting arc thickness
                  },
                  color: {
                    pattern: ['#FF0000', '#F6C600', '#60B044'], // the three color levels for the percentage values.
                    threshold: {
                      unit: 'value', // percentage is default
          //            max: 200, // 100 is default
                      values: [2, 4, 9] // alternate first value is 'value'
                    }
                  },
                  size: {
                    height: 140
                  }                   
              });              

            }
          });

          $.ajax({ 

            url: '/dashboard/frequencia',
            dataType: 'json',

            success: function(result) {


                var valores =  [];

                $.each(result, function(index, value) {

                  var teste = [];

                  teste.push(value.tempo);
                  teste.push(value.a1);
                  teste.push(value.a2);
                  teste.push(value.a3);
                  teste.push(value.a4);
                  teste.push(value.a5);
                  teste.push(value.a6);
                  
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

          $.ajax({ 

            url: '/dashboard/grifeMensal',
            dataType: 'json',

            success: function(result) {


                var valores =  [];

                $.each(result, function(index, value) {

                  var teste = [];

                  teste.push(value.grife);
                  teste.push(value.a1);
                  teste.push(value.a2);
                  teste.push(value.a3);
                  teste.push(value.a4);
                  teste.push(value.a5);
                  teste.push(value.a6);
                  teste.push(value.a7);
                  teste.push(value.a8);
                  teste.push(value.a9);
                  teste.push(value.a10);
                  teste.push(value.a11);
                  teste.push(value.a12);
                  teste.push(value.a13);

                  valores.push(teste);

                });


                var chart9 = c3.generate({
                  bindto: '#chart9',

                  data: {
                    columns: valores,
                    axes: {
                      data1: 'y',
                      data2: 'y2'
                    }
                  },
                  axis: {

                    y: {
                      tick : 500
                    }

                  }
                });

            }
          });



          $.ajax({ 

            url: '/dashboard/orcamentosMensal',
            dataType: 'json',

            success: function(result) {


                var valores =  [];

                $.each(result, function(index, value) {

                  var teste = [];

                  teste.push(value.grife);
                  teste.push(value.jan);
                  teste.push(value.fev);
                  teste.push(value.mar);
                  teste.push(value.abr);
                  teste.push(value.mai);
                  teste.push(value.jun);
                  teste.push(value.jul);
                  teste.push(value.ago);
                  teste.push(value.spt);
                  teste.push(value.oct);
                  teste.push(value.nov);
                  teste.push(value.dez);

                  valores.push(teste);


                });

//console.log(valores);
                var chart15 = c3.generate({
                  bindto: '#chart15',

                  data: {
                    columns: valores,
                    axes: {
                      data1: 'y',
                      data2: 'y2'
                    }
                  },
                  axis: {

                    y: {
                      tick : 500
                    }

                  }
                });

            }
          });
    
});

