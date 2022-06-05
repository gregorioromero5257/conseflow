<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card" v-show="!detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Formato para resguardo de vehículo.
        </div>
        <div class="card-body">

          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-file-pdf"></i>&nbsp; Formatos PDF
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button" class="dropdown-item" @click="descargar1(props.row.id)" >
                      <i class="far fa-file-pdf"></i>&nbsp;Vale de Resguardo</button>

                  </div>
                </div>
              </div>
            </template>
          </v-client-table>

        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->

      <!-- Listado de componentes hijos -->
      <div class="card" v-show="detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> CV - {{ empleado == null ? '': nombre + ' ' + ap_paterno + ' ' + ap_materno }}
          <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">

          </button>
        </div>

      </div>
      <!-- Fin de listado componentes -->
    </div>
  </main>
</template>

<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);


export default {
  data (){
    return {

      detalle: false,
      empleado: null,
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      isLoading: false,
      columns: [
        'id',
        'nombre',
        'ap_paterno',
        'ap_materno',

      ],
      tableData: [],
      options: {
        headings: {
          'id': 'Formatos',
          'nombre': 'Nombre',
          'ap_paterno': 'A Paterno',
          'ap_materno': 'A Materno',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['nombre', 'ap_paterno', 'ap_materno'],
        filterable: ['nombre', 'ap_paterno', 'ap_materno'],
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
      axios.get('/listaasignacion').then(response => {
        me.tableData = response.data;
        console.log(response.data);
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    descargar1(data) {
      console.log(data);
      window.open('descargar-valeresguardoT/' + data, '_blank');

    },

    maestro(){
      this.$refs.myTable.setFilter({
        'nombre': '', 'ap_paterno': '', 'ap_materno': '',
      });
      this.detalle = false;
    },
    setTab(tabName) {
      this.activeTab = tabName;
    }
  },
  mounted() {
    this.getData();
  }
}
</script>
