<template>
<main class="main">
<div class="container-fliud">

  <div class="card" v-show="!detalle">
    <div class="card-header">
      <i class="fa fa-align-justify"></i> Servicios realizados
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

                  <button type="button" @click="verdetalles(props.row)"
                   class="dropdown-item" >
                      <i class="fas fa-eye"></i>&nbsp; Ver servicios
                  </button>

              </div>
              </div>
              </div>

              </template>
          </v-client-table>
      </div>
  </div>

  <div v-show="detalle">
    <detalles ref="detalles" @detalles:maestro="maestro"></detalles>
  </div>

</div>
</main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const DetalleServicios = r => require.ensure([], () => r(require('./Detalles.vue')), 'compras');

export default {
  data(){
    return {
      detalle :false,
      detalles : false,
      columns: ['id','nombre','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin','condicion' ],
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
    'detalles' : DetalleServicios,
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

    verdetalles(data){
      // console.log(id);
      this.detalle = true;
      var childDetalle = this.$refs.detalles;
      childDetalle.getData(data);
    },

    maestro(){
      this.detalle = false;
    }
  },
  mounted (){
    this.getData();
  }
}
</script>
