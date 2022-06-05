<template>
  <main class="main">
    <div class="container-fluid">

      <br>
      <div class="card" v-show="!detalle">
          <div class="card-header">
            <i class="fa fa-align-justify"></i> Proyectos
          </div>
              <div class="card-body">
                  <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
                        
                      <template slot="id" slot-scope="props">
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group dropup" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                    </button>
                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <button type="button" @click="verrequisiciones(props.row.id)" class="dropdown-item" >
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
   
    </div>
  </main>
</template>

<script>
const Requisiciones = r => require.ensure([], () => r(require('./Requisiciones.vue')), 'compras');
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      detalle : false,
      modal : false,
      columns: ['id','nombre','nombre_corto','clave','ciudad'],
      tableData: [],
      options: {
          headings: {
              'id': 'Acciones',
              'nombre': 'Nombre',
              'nombre_corto': 'Nombre corto',
              'clave': 'Ord Comp',
              'ciudad': 'Ciudad',
            
             
              'updated_at' : 'Ultima Actualización',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['nombre','nombre_corto','clave','ciudad'],
          filterable: ['nombre','nombre_corto','clave','ciudad'],
          filterByColumn: true,
          texts:config.texts
      },
    }
  },
  components :{
    'requisiciones' : Requisiciones,
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
