<template >
  <main class="main">

<div class="container-fluid">

  <div class="card">
    <div class="card-header">
        <i class="fa fa-align-justify"></i> &nbsp;Trapasos perdidos o dañados
      <!-- <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
        <i class="fa fa-arrow-left"></i>&nbsp;Atras
      </button> -->
    </div>
    <div class="card-body">

      <v-client-table :columns="columns" :data="data" :options="options" ref="myTable">
        <template slot="id" slot-scope="props" >
      <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
      <div class="btn-group dropup" role="group">
        <button id="btnGroupDrop1" type="button" v-bind:disabled="props.row.estado > 0"
        class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        <!-- <button  type="button" @click="abrirModalA('articulo','almacenar',props.row)" class="dropdown-item">
          <i class="icon-pencil"></i>&nbsp;Almacenar
        </button>
        <button  type="button" @click="daniado(props.row)" class="dropdown-item">
          <i class="icon-pencil"></i>&nbsp;Dañado
        </button>
        <button  type="button" @click="perdido(props.row)" class="dropdown-item">
          <i class="icon-close"></i>&nbsp;Perdido
        </button> -->

        </div>
      </div>
      </div>

    </template>
    <template slot="estado" slot-scope="props">

      <div v-if="props.row.estado == 2">
        <span class="btn btn-outline-danger">Dañado</span>
      </div>
      <div v-if="props.row.estado == 3">
        <span class="btn btn-outline-danger">Perdido</span>
      </div>
    </template>

      </v-client-table>

    </div>
  </div>



</div>
</main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      columns : ['id','fechatraspaso','cantidad','nombre_stock','anombre','acodigo','adescripcion','amarca','aunidad','estado'],
      data : [],
      options: {
        headings: {
          'id' : 'Acciones',
          'anombre': 'Nombre',
          'acodigo': 'Codigo',
          'adescripcion' : 'Descripción',
          'amarca' : 'Marca',
          'aunidad' : 'Unidad',
          'nombre_stock' : 'Stock',
          'fechatraspaso' : 'Fecha del traspaso',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
      listaNivel : [],
      listaStand : [],
      listaAlmaceness : [],
      span : false,
      almacen : {
        id : 0,
        stand_id : 0,
        nivel_id : 0,
      },
      detalles : null,
      detalles_articulo : null,
      modala : 0,
    }
  },
  methods : {

    cargarDetalle(){
      let me = this;
      // me.detalles = data;
      axios.get('/traspasosdaoper').then(response => {
        me.data = response.data;
      }).catch(function (error){
        console.error(error);
      });
    },


  },
  mounted() {
this.cargarDetalle();
  }
}
</script>
