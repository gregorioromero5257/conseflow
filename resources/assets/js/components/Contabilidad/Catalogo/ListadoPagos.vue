<template>
  <main class="main">

    <div class="card">
      <div class="card-header">

      </div>
      <div class="card-body">
        <v-client-table :data="tableData" :options="options" :columns="columns">
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button" @click="mostrarPagos(props.row)" class="dropdown-item">
                    <i class="fas fa-eye"></i>&nbsp; Mostrar Pagos
                  </button>
                </div>
              </div>
            </div>
          </template>
        </v-client-table>
      </div>
    </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return {
      tableData : [],
      columns : [],
      options: {
        headings: {
          id: 'Acciones',
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
  methods:{
    getData(){
      axios.get('get/all/pagos/oc').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },
  },
  mounted(){
    this.getData();
  }
}

</script>
