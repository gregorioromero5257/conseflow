<template>
<main class="main">
  <div class="container-fluid">
    <div class="card" v-show="!detalle">
      <div class="card-header">
          <i class="fa fa-align-justify"></i> &nbsp;Traspasos en transito
      </div>
      <div class="card-body">
        <vue-element-loading :active="isLoading"/>
        <v-client-table :data="data" :options="options" :columns="columns">
          <template slot="id" slot-scope="props">
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <div class="btn-group dropup" role="group">
          <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
          </button>
          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
          <button type="button" @click="detalles(props.row)" class="dropdown-item">
            <i class="fas fa-eye"></i>&nbsp; Ver detalles traspaso
          </button>
          <button type="button" @click="finalizar(props.row)" class="dropdown-item">
            <i class="fas fa-check-circle"></i>&nbsp; Finalizar
          </button>
          </div>
        </div>
        </div>
        </template>

        <template slot="estado" slot-scope="props">
          <div v-if="props.row.estado == 2">
            <span class="btn btn-outline-warning">En transito</span>
          </div>
          <div v-if="props.row.estado == 3">
            <span class="btn btn-outline-success">Recibido</span>
          </div>
        </template>

        </v-client-table>
      </div>
    </div>
    <div v-show="detalle" >
      <detalle ref="detalle" @detalle:click="maestro"></detalle>
    </div>
  </div>
</main>
</template>

<script>
const Detalle = r => require.ensure([], () => r(require('./DetalleTransito.vue')), 'alm');
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return {
      detalle : false,
      data : [],
      columns : ['id','fecha_salida','nombre_empledo','destino','stoke_nombre','comentarios','estado'],
      options: {
        headings: {
          'id' : 'Acción',
          'fecha_salida' : 'Fecha salida',
          'nombre_empledo': 'Empleado transporta',
          'stoke_nombre': 'Stock',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
      isLoading : false,

    }
  },
  components : {
    'detalle' : Detalle,
  },
  methods :{

    getData(){
      let me = this;
      me.isLoading = true;
      axios.get('/traspasosentransito').then(response => {
        me.data = response.data;
        me.isLoading = false;
      }).catch(error => {
        console.error(error);
      });
    },

    detalles(data){
      this.detalle  = true;
      var ChildDetalle = this.$refs.detalle;
      ChildDetalle.cargarDetalle(data);
    },

    maestro(){
      this.detalle = false;
    },

     finalizar(data){
       let me = this;
       axios.get('/veratendidos/'+data.id).then(response => {
         console.log(response);
         if (response.data > 0) {
           toastr.warning('Atencion','No se puede finalizar hasta completar!!!')
         }
         else {
           axios.get('/finalizar/'+data.id).then(response => {
             me.getData();
           }).catch(error => {
             console.error(error);
           });
         }
       }).catch(error => {
         console.error(error);
       });


     }

  },
  mounted(){
    this.getData();
  }

}
</script>
