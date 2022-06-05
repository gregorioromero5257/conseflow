<template>
  <main class="main">
    <div class="container-fluid">

      <br>
      <div class="card" v-show="!detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Proyectos
          <button type="button" @click="abrirModal('requisicion','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
          <div class="card-body">
              <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
                  <template slot="condicion" slot-scope="props" >
                      <template v-if="props.row.condicion == 1">
                       <button type="button" class="btn btn-outline-success">Activo</button>
                      </template>
                      <template v-if="props.row.condicion == 0">
                      <button type="button" class="btn btn-outline-danger">Terminado</button>
                      </template>
                      <template v-if="props.row.condicion == 2">
                      <button type="button" class="btn btn-outline-warning">Pausa</button>
                      </template>
                      <template v-if="props.row.condicion == 3">
                      <button type="button" class="btn btn-outline-dark">Rechazado</button>
                      </template>
                  </template>
                  <template slot="id" slot-scope="props">
                  <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                  <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                      <button type="button" @click="verrequisiciones(props.row.id)"
                       class="dropdown-item" >
                          <i class="fas fa-eye"></i>&nbsp; Ver requisiciones
                      </button>

                  </div>
                  </div>
                  </div>

                  </template>
              </v-client-table>
          </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->

      <div v-show="detalle">
        <requisiciones ref="requisiciones" @requisiciones:click="maestro"></requisiciones>
      </div>
      <div v-show="modal">
        <!-- @modal:click="grabar($event)" -->
          <modal ref="modal" @modal:nuevo="verrequisiciones($event)"></modal>
      </div>

    </div>
  </main>
</template>

<script>
const Requisiciones = r => require.ensure([], () => r(require('./Requisiciones.vue')), 'proyecto');
const Modal = r => require.ensure([], () => r(require('./Modal.vue')), 'proyecto');
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      detalle : false,
      modal : false,
      columns: ['id','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin','condicion' ],
      tableData: [],
      options: {
          headings: {
              'id': 'Acciones',
              'nombre': 'Nombre',
              'nombre_corto': 'Nombre corto',
              'clave': 'Ord Comp',
              'ciudad': 'Ciudad',
              'fecha_inicio': 'F. Inicio',
              'fecha_fin': 'F. Fin',
              'condicion':'Condición',
              'updated_at' : 'Ultima Actualización',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['nombre','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin','condicion'],
          filterable: ['nombre','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin','condicion'],
          filterByColumn: true,
          texts:config.texts
      },
    }
  },
  components :{
    'requisiciones' : Requisiciones,
    'modal' : Modal,
  },
  methods : {
    getData(){
      let me = this;
      axios.get('/proyecto-listar').then(response => {
          me.tableData = response.data;
      })
      .catch(function (error) {
          console.log(error);
      });
    },

    verrequisiciones(data){
      this.detalle = true;
      var ChilRequisiciones = this.$refs.requisiciones;
      ChilRequisiciones.getData(data);
    },

    maestro(){
      this.detalle = false;
    },

    abrirModal(estado, accion){
      this.modal = true;
      var ChildModal = this.$refs.modal;
      ChildModal.abrirModal(estado, accion);
    },

    cerrarModalNuevo(){
      let me = this;
      me.getData();
      me.modal = false;
    }
  },
  mounted(){
    this.getData();
  }
}
</script>
