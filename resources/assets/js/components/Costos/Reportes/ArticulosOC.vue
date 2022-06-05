<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          Buscar OC articulo
        </div>
        <div class="card-body">
          <!-- <div class="form-group row"> -->
            <!-- <label class="col-md-1 form-control-label" for="tipo_partida"><br>Articulos</label>
            <div class="col-md-10">
              <label>&nbsp;</label>
              <v-select id="proyectos" label="name"
              :options="listaArticulos" v-model="articulo">
            </v-select>
            </div>

          <div class="col-md-1"><br><button class="btn btn-dark" @click="listarCompras()">Buscar</button></div> -->
          <!-- <div class="col-md-1"><br><button class="btn btn-success" @click="generarExcel()">Exportar</button></div> -->
          <!-- {{proyecto}} -->
        <!-- </div> -->
        <div class="form-group row">
          <label class="col-md-1 form-control-label" for="tipo_partida"><br>Articulos</label>
          <div class="col-md-10">
            <label>&nbsp;</label>
            <input type="text" class="form-control" v-model="articulo_text">
          </div>

        <div class="col-md-1"><br><button class="btn btn-dark" @click="listarComprasText()">Buscar</button></div>
        <!-- <div class="col-md-1"><br><button class="btn btn-success" @click="generarExcel()">Exportar</button></div> -->
        <!-- {{proyecto}} -->
      </div>

        <v-client-table :options="options" :columns="columns" :data="tableData">
          <template slot="ids" slot-scope="props">

            <button class="btn btn-sm btn-danger" @click="descargar(props.row)"><i class="fas fa-file-pdf"></i> Descargar OC</button>
          </template>
          <template slot="folio" slot-scope="props">
            {{(props.row.folio).replace('CONSERFLOW-020-','')}}
          </template>
	<template slot="moneda" slot-scope="props">
	    <template v-if="props.row.moneda == 1">
              USD
          </template>
          <template v-if="props.row.moneda == 2">
              MXN
          </template>
          <template v-if="props.row.moneda == null">
                  MXN
              </template>
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
      datos_r : null,
      listaArticulos : [],
      listaProveedores : [],
      columns : ['ids','folio','nombre','desc','unidad','comentario','fecha_orden','precio_unitario','moneda','razon_social'],
      tableData : [],
      proyecto : '',
      proveedor : '',
      articulo : '',
      articulo_text : '',
      options : {
        headings: {
          'folio' : 'OC',
          'desc' : 'Descripción',
          'razon_social' : 'Proveedor',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['folio','nombre','desc','unidad','comentario','fecha_orden','precio_unitario'],
        filterable: ['folio','nombre','desc','unidad','comentario','fecha_orden','precio_unitario'],
        filterByColumn: true,
        texts:config.texts
      },


    }
  },
  watch: {
    proyecto(nuevoValor, valorAnterior){
      if (nuevoValor != '') {
        this.proveedor = '';
      }
    },
    proveedor(nuevoValor, valorAnterior){
      if (nuevoValor != '') {
        this.proyecto = '';
      }
    },
  },
  methods:{
    getData(){
      let me = this;
      axios.get('articulos').then(response => {
        this.listaArticulos = [];
        response.data.forEach((item, i) => {
          this.listaArticulos.push({
            id : item.id,
            name : item.nombre + ' ' + item.descripcion
          });
        });
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    listarCompras(){

    },

    listarComprasText(){
      axios.post('buscar/articulo/oc',{
        articulo : this.articulo_text,
      }).then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    descargar(data) {
      window.open('descargar-compran/' + data.ids, '_blank');
    },




  },
  mounted(){
    this.getData();
  }
}
</script>
