<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Precio de venta por artículo.

        </div>
        <div class="card-body">
          <v-server-table :columns="columns" url="/busquedapv" :options="options" ref="myTable">

       </v-server-table>
  </div>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>

  </main>
</template>

<script>
var config = require('../../Herramientas/config-vuetables-client').call(this);
// const Precio = r => require.ensure([], () => r(require('../Precio.vue')), 'alm');

export default {
  components: {

    // 'precio' : Precio,
   },
  data (){
    return {
      columns: ['nombre', 'codigo','descripcion', 'marca', 'unidad','precioventa','preciounitario'],
      tableData: [],
      options: {
        headings: {
          "nombre": 'Nombre',
          "codigo": 'Código',
          "descripcion": 'Descripción',
          "marca": 'Marca',
          "unidad": 'Unidad',
          'precioventa': 'Precio de venta de artículo',
          'preciounitario' : 'Precio unitario de artículo'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['nombre', 'codigo','descripcion', 'marca', 'unidad'],
        filterable: ['nombre', 'codigo','descripcion', 'marca', 'unidad'],
        filterByColumn: true,
        listColumns: {
          },
      texts:config.texts
    },
  }
},
computed:{
},
methods : {
  getData() {
    let me=this;
    // axios.get('/precioventa').then(response => {
    //   me.tableData = response.data;
    //
    // })
    // .catch(function (error) {
    //   console.log(error);
    // });
    // axios.get('/precioventa').then(response => {
    //   // me.tableData = response.data;
    //
    //
    // })
    // .catch(function (error) {
    //   console.log(error);
    // });
  },
  getLista(){
    let me=this;

  },

},
mounted() {
  // this.getData();
  this.getLista();
  // this.verprecioventa();
},
}
</script>
