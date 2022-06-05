<template>
  <!-- <main class="main"> -->
  <!-- <div class="container-fluid"> -->
  <div>
    <!-- Ejemplo de tabla Listado -->

    <br>
    <div class="card" v-show="detalle == 0">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Compras
        <!-- <button type="button" @click="abrirModal('compra','registrar')" class="btn btn-dark float-sm-right">
        <i class="icon-plus"></i>&nbsp;Nuevo
      </button> -->
      <button type="button" @click="maestroPrincipal()" class="btn btn-secondary float-sm-right">
        <i class="fas fa-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <div class="card-body">
      <vue-element-loading :active="isLoading"/>
       <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
         <template slot="condicion" slot-scope="props" >
  <template v-if="props.row.condicion == 1">
    <span class="btn btn-outline-success">Activo</span>
  </template>
  <template v-if="props.row.condicion == 0">
    <span class="btn btn-outline-danger">Dado de Baja</span>
  </template>
  <template v-if="props.row.condicion == 2">
    <span class="btn btn-outline-warning">Cerrada</span>
  </template>
</template>
<template slot="estado_id" slot-scope="props" >
  <template v-if="props.row.estado_id == 3">
    <span class="btn btn-outline-danger">No programado</span>
  </template>
  <template v-if="props.row.estado_id == 2">
    <span class="btn btn-outline-warning">Programada</span>
  </template>
  <template v-if="props.row.estado_id == 1">
    <span class="btn btn-outline-success">Pagado</span>
  </template>
</template>
       </v-client-table>
</div>
</div>



<!-- Fin ejemplo de tabla Listado -->



</div>
<!-- </div>

</main> -->
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);


export default {
  data (){
    return {
      PermisosCrud : {},
      url: '/comprasporproveedor',
      empleado: null,
     detalle : 0,
      objeto : null,
    
      validado : 0,
      listaEmpleados: [],
      listaCotizaciones : [],
      listaProvedores :[],
      listaCondicionPago : [],
      modal : 0,
      modalb : 0,
      tituloModal : '',
      tipoAccion : 0,
      tipoAccionpr : 0,
      disabled: 0,
      isLoading: false,
      isLoadingg: false,
      isLoadingDetalle: false,
      columns: ['folio','condicion_pago_id','fecha_orden','periodo_entrega','lugar_entrega','total','estado_id','condicion'],
      tableData: [],
      options: {
        headings: {
          'folio': 'Folio',
          'condicion_pago_id': 'Condición de Pago',
          'fecha_orden' : 'Fecha Orden',
          'periodo_entrega': 'Período de Entrega',
          'lugar_entrega': 'Lugar de Entrega',
          'total': 'Total',
          'condicion' : 'Condición',
          'estado_id' : 'Estado',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['folio','condicion_pago_id','fecha_orden','periodo_entrega','lugar_entrega','total','estado_id'],
        filterable: ['folio','condicion_pago_id','fecha_orden','periodo_entrega','lugar_entrega','total','estado_id'],
        filterByColumn: true,
        listColumns: {
          estado_id: [{
            id: 1,
            text: 'Pagado'
          },
          {
            id: 2,
            text: 'Programada'
          },
          {
            id: 3,
            text: 'No programada'
          }
        ]
      },
        texts:config.texts
      },
    }
  },
  
  methods : {

    getData(data) {
      this.objeto = data;
      this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
      this.isLoading = true;
      let me=this;
      axios.get('/comprasporproveedor/' + data).then(response => {
        me.tableData = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });
    },


    maestro(data){
      let me= this;
      this.detalle = 0;
      me.widgets.partidas = false;
      me.isLoading = false;
      me.getData(data);

    },
   maestroPrincipal(){
      this.$emit('compras:maestro');
    },

  },
  mounted() {
    // this.getData();
    // this.getArticulosRequisicion();
  }
}
</script>
