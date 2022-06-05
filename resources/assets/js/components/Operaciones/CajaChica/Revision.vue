<template>
  <main class="main">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">
          <v-client-table :data="tableData" :columns="columns" :options="options">
          </v-client-table>
        </div>
      </div>
    </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default{
  data(){
    return {
      tableData = [],
      columns : [],
      options: {
          headings: {
              'id': 'Acciones',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          filterByColumn: true,
          texts:config.texts
      },
    }
  },
  methods : {
    getData(){
      axios.get('caja/chica/comprobacion/validar').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },
  },
  mounted(){

  },
}
</script>
