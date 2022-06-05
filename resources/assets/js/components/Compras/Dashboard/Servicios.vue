<template>
  <div class="card border-dark">
    <div class="card-header bg-dark text-white">
      <i class="fa fa-align-justify"> </i> Servicios pendientes:
      <!-- <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
        <i class="icon-arrow-left"></i>&nbsp;Atras
      </button> -->
    </div>
    <div class="card-body">

      <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
        <template slot="precio_unitario" slot-scope="props">
            ${{new Intl.NumberFormat("en-US").format(props.row.precio_unitario)}}
        </template>

        <template slot="id" slot-scope="props">
          <button type="button" class="btn btn-outline-success" @click="terminado(1,props.row.id)">
    <i class="fas fa-check-square"></i> Si&nbsp;&nbsp;
          </button>
          <!-- <button id="btnGroupDrop1" type="button" class="btn btn-outline-danger" @click="terminado(0,props.row.id)">
    <i class="fas fa-window-close"></i> No
          </button> -->
        </template>


      </v-client-table>

    </div>
  </div>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data (){
    return{
      tableData : [],
      columns : ['nombre_proyeto','folio','nombre_servicio','proveedor_marca','precio_unitario','fecha_requerido','id'],
      options : {
        headings: {
          'nombre_proyeto' : 'Proyecto',
          'nombre_servicio' : 'Servicio',
          'proveedor_marca': 'Proveedor',
          'fecha_requerido': 'Fecha',
          'precio_unitario': 'Costo',
          'id' : 'Finalizado',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts,
      }
    }
  },
  methods : {
    cargarservicios(){
      let me = this;
      axios.get('/serviciospendientes').then(response => {
        me.tableData = response.data;
      }).catch(error => {
        console.error(error);
      });
    },

    terminado(estado, id){
      let me = this;
      axios.post('/terminarservicio',{
        id : id,
        estado : estado,
      }).then(response => {
        me.cargarservicios();
        toastr.success('Atención', estado == 0 ? 'Servicio no finalizado' : 'Servicio finalizado');
      }).catch(error => {
        console.error(error);
      });
    }

  }
}
</script>
