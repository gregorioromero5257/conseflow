<template >

  <main class="main">
  <div class="container-fliud">

    <div class="card" v-show="detalle == 1">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Proveedores
       
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

                    <button type="button" @click="verdetalles(props.row)"
                     class="dropdown-item" >
                        <i class="fas fa-eye"></i>&nbsp; Ver ordenes de compra
                    </button>

                </div>
                </div>
                </div>

                </template>
            </v-client-table>
        </div>
    </div>

    <div v-show="detalle == 2">
      <compras ref="compras" @compras:maestro="maestro"></compras>
    </div>
    
  </div>
  </main>

</template>


<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Compras = r => require.ensure([], () => r(require('./Compras.vue')), 'compras');


export default {
  data(){
    return {
      detalle : 1,
      detalles : false,
      columns: ['id','nombre','razon_social','direccion','banco_transferencia','numero_cuenta','clabe','dia_credito','limite_credito'],
      tableData: [],
      options: {
          headings: {
              'id': 'Acciones',
          'nombre': 'Nombres',
          'razon_social': 'Razón Social',
          'direccion': 'Dirección',
          'condicion': 'Estado',
          'numero_cuenta' : 'Número cuenta',
          'dia_credito' : 'Días de credito',
          'limite_credito' : 'Límite de crédito',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['nombre','razon_social','direccion','banco_transferencia','numero_cuenta','clabe','condicion'],
          filterable: ['nombre','razon_social','direccion','banco_transferencia','numero_cuenta','clabe','condicion'],
          filterByColumn: true,
          texts:config.texts
      },

    }
  },
  components: {
    'compras' : Compras,
    

  },
  methods : {
    getData(){
      let me = this;
      axios.get('/proveedores').then(response => {
          me.tableData = response.data;
      })
      .catch(function (error) {
          console.log(error);
      });
    },

    verdetalles(data){
      this.detalle = 2;
      var childCompras = this.$refs.compras;
      childCompras.getData(data.id);
    },

    maestro(){
      this.detalle = 1;
    },

    /**
     * [abrirModal description]
     * @param  {[String]} modelo    [description]
     * @param  {[String]} accion    [description]
     * @param  {Array}  [data=[]] [description]
     * @return {}           [description]
     */
      abrirModal(modelo, accion, data = []){
        let me = this;
        this.detalle = 3;
        var childcardprincipal = this.$refs.cardprincipal;
        childcardprincipal.cargarcardprincipal(modelo, accion, data, me.tableData);
      },

      cerrar(data){
        this.detalle = 1;
      }

  },
  mounted() {
this.getData();
  }
}
</script>
