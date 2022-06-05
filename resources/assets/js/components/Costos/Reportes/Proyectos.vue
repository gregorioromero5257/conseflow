<template>
  <main class="main">
    <div class="container-fluid">
      <br>
      <div class="row">
        <div class="col-6">
          <div class="card">
            <div class="card-header">Proyectos</div>
            <div class="card-body">

              <div class="form-group row">
                <label class="col-md-2 form-control-label" for="tipo_partida">Proyecto</label>
                <div class="col-md-8">
                  <!-- <select class="form-control" id="tipo_partida"  name="tipo_partida" v-model="proyecto_id" >
                  <option value="0" selected></option>
                  <option v-for="item in listaProyectos" :value="item.id" :key="item.id">{{ item.nombre_corto }}</option>
                </select> -->
                <v-select :options="listaProyectos"  v-validate="'required'" v-model="proyecto_id" label="name" name="proyecto" data-vv-name="proyecto" ></v-select> <!---->

                <!-- <span class="text-danger">{{ errors.first('tipo_partida') }}</span> -->
              </div>
              <div class="col-md-2"><button class="btn btn-primary" @click="aumentar()">Buscar</button></div>
            </div>
            <h3 class="vue-title">Monto Total del Proyecto: (OC) <br>$&nbsp; {{listSuma}}</h3>
            <table class="table table-hover table-sm table-bordered table-responsive">
              <thead class="table-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Tipo partida</th>
                  <th scope="col">Nombre</th>
                  <th scope="col">Cotizado</th>
                  <th scope="col">Ejecutado</th>
                  <th scope="col">Avance</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, key) in registros" :value="item.id" :key="item.id">
                  <td>{{ key + 1}}</td>
                  <td>{{ item.tipo_partida }}</td>
                  <td>{{ item.nombre }}</td>
                  <td class="text-right">$&nbsp;{{ item.monto_cotizado }}</td>
                  <td class="text-right">$&nbsp;{{ item.monto_ejecutado }}</td>
                  <td class="text-right">%&nbsp;{{ (item.monto_ejecutado / item.monto_cotizado) * 100}} </td>
                </tr>
                <tr>
                  <td colspan="2"></td>
                  <td>&nbsp;</td>
                  <td class="text-right"><b>$&nbsp;{{t_cotizado}}</b></td>
                  <td class="text-right"><b>$&nbsp;{{listejec}}</b></td>
                </tr>
              </tbody>
            </table>
            <h4 class="vue-title">Utilidad: <br>$&nbsp;{{utilidad}}</h4>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="card">
              <chart-total :chart-data="chartData6" :options="options6"></chart-total>
          <!-- <chart-utilidad :chart-data="chartData5" :options="options5"></chart-utilidad> -->
        </div>
        <div class="card">
          <chart-comparar-montos :chart-data="chartData4" :options="options4"></chart-comparar-montos>
        </div>
        <div class="card">
          <chart-totales :chart-data="chartData3" :options="options3"></chart-totales>
        </div>

      </div>

      <div class="col-12">
        <div class="card">
          <chart-cotizado :chart-data="chartData1" :options="options1"></chart-cotizado>
        </div>
        <div class="card">
          <chart-ejecutado :chart-data="chartData2" :options="options2"></chart-ejecutado>
        </div>
      </div>
    </div>
  </div>
</main>
</template>

<script>
import chartUtilidad from './chartDoughnut.vue';
import chartCotizado from './chartPie.vue';
import chartEjecutado from './chartPie.vue';
import chartCompararMontos from './chartBar.vue';
import chartTotales from './chartBar.vue';
// import chartLine from './chartLine.vue';
import chartTotal from './chartBar.vue';
import Utilerias from '../../Herramientas/utilerias.js';

export default {
  name: 'app',
  components: {
    chartCotizado,
    chartEjecutado,
    chartCompararMontos,
    chartTotales,
    chartUtilidad,
    chartTotal,
  },
  data() {
    return {
      totalEjecutado: [],
      listaProyectos: [],
      listSuma: 0,
      listejec: 0,
      t_cotizado: 0,
      total_ejecutado: 0,
      utilidad: 0,
      proyecto_id: 0,
      registros: [],
      options1: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: true,
          position: 'left',
        },
        title: {
          display: true,
          text: 'Cotizado'
        },

      },
      options2: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: true,
          position: 'left',
        },
        title: {
          display: true,
          text: 'Ejecutado'
        }
      },
      options3: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: true,
          position: 'bottom',
        },
        title: {
          display: true,
          text: 'Montos por Partida'
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true
            }
          }]
        }
      },
      options4: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: true,
          position: 'bottom',
        },
        title: {
          display: true,
          text: 'Montos Totales'
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true
            }
          }]
        }
      },
      options5: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: true,
          position: 'bottom',
        },
        title: {
          display: true,
          text: 'Montos Totales'
        },

      },
      options6: {
        title: {
          display: true,
          text: 'Montos Totales'
        },
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          xAxes: [{
            stacked: true,
            categoryPercentage: 0.5,
            barPercentage: 1
          }],
          yAxes: [{
            stacked: true
          }]
        }
      },
      chartData1: null,
      chartData2: null,
      chartData3: null,
      chartData4: null,
      chartData5: null,
      chartData6: null,

    }
  },
  methods: {
    aumentar() {

      if (this.proyecto_id.id == 0)
      {
        toastr.error('Seleccione un proyecto');
      }

      let me=this;
      this.bgcolor =  Utilerias.setRandomColor();



      axios.get('/partidascostos/' + this.proyecto_id.id).then(response => {
        // console.log(response.data);

        var listCotizado =[];
        var listEjecutado = [];
        var listLabels = [];
        var listBg = [];
        var totalCotizado = [];
        var totalEjecutado = [];



        me.registros = response.data;

        response.data.map(function(value, key) {
          listLabels.push(value.tipo_partida);
          listCotizado.push(value.monto_cotizado);
          listEjecutado.push(value.monto_ejecutado);
          listBg.push(me.bgcolor[key]);
        });
        // console.log(listBg);
        totalCotizado.push(listCotizado.reduce(function(a,b){return parseFloat(a) + parseFloat(b)}));
        totalEjecutado.push(listEjecutado.reduce(function(a,b){return parseFloat(a) + parseFloat(b)}));

        // console.log(totalCotizado);
        // console.log(totalEjecutado);





        me.datacollection3 = {
          labels: listLabels,
          datasets: [
            {
              label: 'Cotizado',
              backgroundColor: me.bgcolor[0],
              data: listCotizado
            }, {
              label: 'Ejecutado',
              backgroundColor: me.bgcolor[1],
              data: listEjecutado
            }
          ]
        };
        me.chartData3 = me.datacollection3;


        me.datacollection4 = {
          labels: ['Total'],
          datasets: [
            {
              label: 'Cotizado',
              backgroundColor:
              [
                'rgba(255, 99, 132,0.2)'
              ],
              borderColor: [
                'rgba(255,99,132,1)'
              ],
              borderWidth: 1,

              data: totalCotizado
            }, {
              label: 'Ejecutado',
              backgroundColor:
              [
                'rgba(54, 162, 235,0.2)'
              ],
              borderColor: [
                'rgba(54, 162, 235,1)'
              ],
              borderWidth: 1,
              data: totalEjecutado
            }
          ]
        };
        me.chartData4 = me.datacollection4;
        // edit
        me.datacollection1 = {
          labels: listLabels,
          datasets: [
            {
              label: 'Montos',
              backgroundColor: listBg,
              data: listCotizado,
            }
          ]
        };
        me.chartData1 = me.datacollection1;
        // console.log(me.datacollection1);

        me.datacollection2 = {
          labels: listLabels,
          datasets: [
            {
              label: 'Montos',
              backgroundColor: listBg,
              data: listEjecutado
            }
          ]
        };
        me.chartData2 = me.datacollection2;

      })
      .catch(function (error) {
        console.log(error);
      });
      axios.get('/proyecto/sum/' + this.proyecto_id.id).then(response => {
        me.listSuma = response.data.resultado;
        me.t_cotizado = response.data.total_cotizado;
        me.listejec = response.data.total_ejecutado;
    //    me.utilidad = response.data.utilidad;
        //console.log(response.data);
        var totalCot = parseFloat(me.t_cotizado);
        var totalPro = parseFloat(me.listSuma);
        var totalEje = parseFloat(me.listejec);
        var totalUti = totalCot -totalEje;
        me.utilidad = totalCot -totalEje;
        var myColors = [];
        var myData = [totalUti];

        $.each(myData, function(index,value)
        {
          if(value<0){
            myColors[index]="red";
          }else{
            myColors[index]='rgba(54, 162, 235,0.2)';
          }
        })


        me.datacollection5 = {
          labels: ["Utilidad","Monto Total","Total Ejecutado"],
          datasets: [
            {

              data: [myData,totalPro,totalEje],
              backgroundColor: [
                myColors,
                'rgba(255, 99, 132,0.2)',
                'rgba(153, 102, 255, 0.2)'],
                borderColor: [
                  'rgba(54, 162, 235,1)',
                  'rgba(255,99,132,1)',
                  'rgba(153, 102, 255, 1)'
                ],
                borderWidth:1

              },



            ]
          };
          me.chartData5 = me.datacollection5;
          me.datacollection6 = {
            labels: ['Cotizado', 'Ejecutado/Utilidad'],
            datasets: [
              {
                label: 'Cotizado',
                backgroundColor: '#f87979',
                data: [totalCot, 0, 0]
              },
              {
                label: 'Ejecutado',
                backgroundColor: '#3D5B96',
                data: [0, totalEje, 0]
              },
              {
                label: 'Utilidad',
                backgroundColor: '#1EFFFF',
                data: [0, totalUti, 0]
              }
            ]
          };
          me.chartData6 = me.datacollection6;

        })
        .catch(function (error) {
          console.log(error);
        });


      },
      getData() {
        let me=this;
        axios.get('/principales').then(response => {
          //  me.listaProyectos = response.data;
          for (var i = 0; i < response.data.length; i++) {
            this.listaProyectos.push({
              id: response.data[i].id,
              name : response.data[i].nombre_corto,
            });
          }
        })
        .catch(function (error) {
          console.log(error);
        });

      },

    },
    mounted() {
      this.getData();
    }
  }
  </script>
