<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Credito
          <!-- <button type="button" @click="abrirModal('compra','registrar')" class="btn btn-primary float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button> -->
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">

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
  data (){
    return {
      tableData : [],
      columns : ['razon_social','monto_debe','monto_disponible','monto_credito'],
      options: {
        headings: {
          razon_social: 'Proveedor',
          },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['razon_social'],
        filterable: ['razon_social'],
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  computed:
  {
  },
  methods :
  {
    getData()
    {
      let me = this;
      axios.get('/credito').then(response => {
        me.tableData = response.data;
      }).catch(function (error){
        console.error(error);
      });
    }

  },
  mounted()
  {
    this.getData();

  }
}
</script>
