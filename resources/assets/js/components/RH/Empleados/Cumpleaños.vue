<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Empleados que cumplen años este mes.
          <button type="button" @click="descargar()" class="btn btn-dark float-sm-right">
            <i class="far fa-smile-beam"></i>&nbsp;Cumpleaños del mes
          </button>

        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <!-- Como usar un if cuando se tiene tres condiciones-->
            <template slot="fech_nac" slot-scope="props" >
              <template v-if="props.row.fech_nac">
                <button type="button" class="btn btn-outline-success">{{props.row.fech_nac}}</button>
              </template>
            
            </template>
          </v-client-table>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->

    <!--Fin del modal-->
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';

var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {

    data (){
    return {
      url: '/cumpleañosmes',
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      isLoading: false,
      columns: ['nombre','ap_paterno','ap_materno','fech_nac'],
      tableData: [],
      options: {
        headings: {

          nombre: 'Nombres',
          ap_paterno: 'Apellido paterno',
          ap_materno: 'Apellido materno',
          fech_nac: 'Fecha de Nacimiento',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['nombre','ap_paterno','ap_materno'],
        filterable: ['nombre','ap_paterno','ap_materno'],
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  computed:{
  },
  methods : {
    getData() {
      let me=this;
      axios.get(me.url).then(response => {
        me.tableData = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    descargar() {
      window.open('cumplemespdf', '_blank');
    },

  },
  mounted() {
    this.getData();

  }
}
</script>
