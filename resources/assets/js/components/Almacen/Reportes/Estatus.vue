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
                  <v-select :options="listaProyectos"  v-validate="'required'" v-model="proyecto_id" label="name" name="proyecto" data-vv-name="proyecto" ></v-select> <!---->
                </div>
                <div class="col-md-2">&nbsp;</div>
                <br>

                <div class="col-md-2"><button class="btn btn-primary" @click="aumentar()">Buscar</button></div>

              </div>

            </div>
          </div>
        </div>



      </div>
      <div class="row">
        <div class="col-8">
          <div class="card" >
            <chart-total :chart-data="chartData6" :options="options6"></chart-total>
          </div>
        </div>
        <div class="col-4">
          <div class="card">
            <table class="table table-hover table-sm table-bordered table-responsive">
              <thead class="table-light">
                <tr>
                  <th scope="col" rowspan="2" style="text-align:center" width="100%">PORCENTANJES</th>
                </tr>
              </thead>
              <tbody>
              <div>
                <tr><td>Entradas</td><td>{{datos != null ? ((parseFloat(datos[1])*100)/(parseFloat(datos[0]))).toFixed(2):0}} %</td></tr>
                <tr><td>Salidas</td><td>{{datos != null ? ((parseFloat(datos[2])*100)/(parseFloat(datos[0]))).toFixed(2):0}} %</td></tr>
              </div>
              </tbody>
            </table>
          </div>
        </div>
        <!-- <div class="col-4">
          <div class="card">
              <table class="table table-hover table-sm table-bordered table-responsive">
                <thead class="table-light">
                  <tr>
                    <th scope="col" rowspan="3" style="text-align:center" width="100%">Totales con IVA</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(c, key) in listCI" >
                    <td> <button type="button" :style="{backgroundColor:backgroundColorCI[key]}" class="form-control">&nbsp;&nbsp;</button> </td>
                    <td> {{tutulos[key]}}</td>
                    <td> $ {{(parseFloat(c)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}}</td>
                  </tr>
                </tbody>
              </table>
          </div>
        </div> -->
      </div>

    </div>
  </main>
</template>

<script>
import chartTotal from './chartBar.vue';
import chartUno from './chartBar.vue';
import chartDos from './chartBar.vue';
import chartTres from './chartBar.vue';
import Utilerias from '../../Herramientas/utilerias.js';

export default {
  name: 'app',
  components: {
    chartTotal,
    chartUno,
    chartDos,
    chartTres,
  },
  data() {
    return {
      datos : null,
      backgroundColorCI: ['#C28535', '#8AAE56', '#B66C46','#1A9EB6','#1AB65A'],
      tutulos: ['Cotización','Pago Cliente','OCs', 'Facturas','Proveedores'],
      listCI : null,
      listSI : null,
      resultado_proveedores : '',
      usd_compras_mxn : '',
      usd_factura_mxn : '',
      usd_pago_mxn : '',
      tipo_cambio_promedio : '',
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
          position: 'bottom',
        },
        title: {
          display: true,
          text: 'Compras-Facturas'
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true
            }
          }]
        }
      },
      options2: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: true,
          position: 'bottom',
        },
        title: {
          display: true,
          text: 'Compras-Pagos'
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true
            }
          }]
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
          text: 'Facturas-Pagos'
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true
            }
          }]
        }
      },

      options6: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: true,
          position: 'bottom',
        },
        title: {
          display: true,
          text: 'Estatus OC-Entrada-Salida'
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
          text: 'Montos totales sin IVA'
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true
            }
          }]
        }
      },
      chartData1: null,
      chartData2: null,
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
      else {

        let me=this;
        this.bgcoloruno =  Utilerias.setRandomColor();
        this.bgcolordos =  Utilerias.setRandomColor();

        axios.get('/compras/status/pro/' + this.proyecto_id.id).then(response => {
          var listUsd = [];
          var oc = response.data[0];
          var entrada = response.data[1];
          var salida = response.data[2];
          me.datos = [oc,entrada,salida];
          // response.data.map(function(value, key) {
          //   oc = value[0];
          //
          //   listUsd.push(
          //     (value)
          //   );
          //
          // });

          me.listCI = listUsd;
          me.datacollection6 = {
            labels: ['OC','Entradas','Salidas'],
            datasets: [
              {
                label: '',
                backgroundColor: ['#C28535', '#8AAE56', '#B66C46'],
                data: [oc,entrada,salida]
              },
            ]
          };
          me.chartData6 = me.datacollection6;


        })
        .catch(function (error) {
          console.log(error);
        });


      }



    },

    // var colores=[];
    //
    // function getRandomColor() {
    //   var num=(Math.floor(Math.random()*4)*4).toString(16);
    //   var letters = ['0','F',num];
    //   var color = '#';
    //
    //   for (var i = 0; i < 3; i++ ) {
    //     let pos=Math.floor(Math.random() * letters.length);
    //     color += letters[pos];
    //     letters.splice(pos,1);
    //   }
    //
    //   //para evitar que se repitan colores
    //   if(colores.includes(color))
    //   return getRandomColor();
    //   else
    //   colores.push(color)
    //
    //   var str = "<div style='background-color:"+color+"'><button id='b1'>hola</button></div>"
    //   document.getElementById('colores').innerHTML+=str;
    //   return color;
    // }
    //
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
