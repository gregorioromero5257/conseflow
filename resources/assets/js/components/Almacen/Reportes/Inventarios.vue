<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Inventario
          <div class="form-group row">
            <div class="col-md-3">
              <label>Fecha inicial</label>
              <input type="date" class="form-control" v-model="date_one" ref="date_one">
            </div>
            <div class="col-md-3">
              <label>Fecha final</label>
              <input type="date" class="form-control" v-model="date_two"  ref="date_two">
            </div>

            <div class="col-md-1"><br><br>
              <button type="button" @click="buscar()" class="btn btn-outline-primary">
                <i class="far fa-find"></i>&nbsp;Buscar
              </button>
            </div>
            <div class="col-md-2"><br><br>
              <button class="btn btn-success btn-block" @click="GenerarP()">Exportar por proyecto</button>
            </div>
            <div class="col-md-2"><br><br>
              <button class="btn btn-success btn-block" @click="Generar()">Exportar general</button>
            </div>
          </div>

        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <!-- Como usar un if cuando se tiene tres condiciones-->
            <template slot="precio_unitario" slot-scope="props">
                ${{new Intl.NumberFormat("en-US").format(props.row.precio_unitario)}}
            </template>

            <span slot="grupo" slot-scope="props">
                {{props.row.grupo == null ? 'Sin grupo' : props.row.grupo }}
          </span>

          </v-client-table>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->

    <!--Fin del modal-->
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data (){
    return {
      url: '/almacen/inventario',
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      isLoading: false,
      columns: ['articulo','descripcion','codigo','marca','unidad','lt_cantidad','precio_unitario','almacen','stand','nivel','ubicacion','stock','grupo','categoria'],
      tableData: [],
      date_one : '',
      date_two : '',
      options: {
        headings: {

          unidad:'Unidad de medida',
          cantidad:'Cantidad',
          almacen: 'Almacen',
          stand: 'Stand',
          lote: 'Lote',
          nivel: 'Nivel',
          ubicacion: 'Ubicacion',
          articulo: 'Articulo',
          descripcion: 'Descripcion',
          codigo: 'Codigo',
          marca: 'Marca',
          stock: 'Stock',
          unidad: 'Unidad',
          grupo:  'Grupo',
          categoria: 'Categoria',
          lt_cantidado: 'Cantidad',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['lote','articulo','descripcion','codigo','marca','unidad','cantidad','almacen','stand','nivel','ubicacion','stock','grupo','categoria'],
        filterable: ['lote','articulo','descripcion','codigo','marca','unidad','cantidad','almacen','stand','nivel','ubicacion','stock','grupo','categoria'],
        filterByColumn: true,
        texts:config.texts,
        groupBy:'grupo',
      },
    }
  },
  computed:{
  },
  methods : {
    buscar() {
      let me=this;
      axios.get('/almacen/inventario/buscar/'+this.date_one+'&'+this.date_two).then(response => {
        me.tableData = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    descargar() {
      window.open('descargar-inventario/'+this.date_one+'&'+this.date_two,'_blank');
    },
    GenerarP(){
      window.open('descargar-inventarios-por-proyecto','_blank');
    },
    Generar(){
      window.open('descargar-inventarios-general','_blank');
    },

  },
  mounted() {
    // this.getData();

  }
}
</script>
