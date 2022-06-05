<template>
  <div class="card ">
    <div class="card-header">
      <i class="fa fa-align-justify"> </i> Servicios realizados del proyecto: {{datos == null ? '' :datos.nombre}}
      <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
        <i class="icon-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <div class="card-body">

      <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
        <!-- <template slot="id" slot-scope="props">
          <button type="button" class="btn btn-outline-success" @click="terminado(1,props.row.id)">
    <i class="fas fa-check-square"></i> Si&nbsp;&nbsp;
          </button> -->
          <!-- <button id="btnGroupDrop1" type="button" class="btn btn-outline-danger" @click="terminado(0,props.row.id)">
    <i class="fas fa-window-close"></i> No
          </button> -->
        <!-- </template> -->


      </v-client-table>
  <h6><b>Total del proyecto : ${{total}}</b></h6>
    </div>
  </div>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data() {
    return {
      datos : null,
      tableData : [],
      columns : ['nombre_proyeto','nombre_servicio','proveedor_marca','precio_unitario','fecha_requerido'],
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
      },
    }
  },
  computed : {
    total () {
      return this.tableData.reduce((sum, item) => {
        return (sum + parseFloat(item.precio_unitario))
      }, 0)
    }
  },
  methods : {
    getData(data){
      let me = this;
      me.datos = data;
      axios.get('/serviciosfinalizados/' + data.id).then(response => {
        this.tableData = response.data;
      }).catch(error => {
        console.error(error);
      });
    },

    maestro(){
      this.$emit('detalles:maestro');
    }

  }
}
</script>
