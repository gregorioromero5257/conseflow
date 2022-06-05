<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card" v-show="!detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Formatos para Contrato
        </div>
        <div class="card-body">

          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="empleado.id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-file-pdf"></i>&nbsp; Formatos PDF
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button" class="dropdown-item" @click="descargar1(props.row.empleado)" >
                      <i class="far fa-file-pdf"></i>&nbsp;Carta de no enfermedades</button>
                    <button type="button" class="dropdown-item" @click="descargar2(props.row.empleado)" >
                      <i class="far fa-file-pdf"></i>&nbsp;Carta Infonavit, Infonacot</button>
                    <button type="button" class="dropdown-item" @click="descargar3(props.row.empleado)" >
                      <i class="far fa-file-pdf"></i>&nbsp;Carta Compromiso</button>
                    <button type="button" class="dropdown-item" @click="descargar4(props.row.empleado)" >
                      <i class="far fa-file-pdf"></i>&nbsp;Formato de renuncia voluntaria.</button>
                    <button type="button" class="dropdown-item" @click="descargar5(props.row.empleado)" >
                      <i class="far fa-file-pdf"></i>&nbsp;Hoja de control de expedientes.</button>
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
          <i class="fa fa-align-justify"></i> CV - {{ empleado == null ? '': empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }}
          <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">

          </button>
        </div>

      </div>
      <!-- Fin de listado componentes -->
    </div>
  </main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);


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
        'empleado.id',
        'empleado.nombre',
        'empleado.ap_paterno',
        'empleado.ap_materno',
        'puesto.nombre',
        'departamento.nombre',
      ],
      tableData: [],
      options: {
        headings: {
          'empleado.id': 'Formatos',
          'empleado.nombre': 'Nombre',
          'empleado.ap_paterno': 'A Paterno',
          'empleado.ap_materno': 'A Materno',
          'puesto.nombre': 'Puesto',
          'departamento.nombre': 'Departamento',
          'empleado.condicion':'condicion',
          'empleado.updated_at' : 'Ultima Actualización',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
        filterable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
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
      axios.get('/empleado').then(response => {
        me.tableData = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    descargar1(data) {
      console.log(data);
      window.open('descargar-canoen/' + data.id, '_blank');

    },
    descargar2(data) {
      console.log(data);
      window.open('descargar-cainin/' + data.id, '_blank');

    },
    descargar3(data) {
      console.log(data);
      window.open('descargar-cacomp/' + data.id, '_blank');

    },
    descargar4(data) {
      console.log(data);
      window.open('descargar-voluntaria/' + data.id, '_blank');

    },

    descargar5(data) {
      console.log(data);
      window.open('descargar-exp/' + data.id, '_blank');

    },

    maestro(){
      this.$refs.myTable.setFilter({
        'empleado.nombre': '', 'empleado.ap_paterno': '', 'empleado.ap_materno': '', 'puesto.nombre': '', 'departamento.nombre': ''
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
