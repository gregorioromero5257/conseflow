<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          Buscar Salidas articulo
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
        <div class="form-row">
          <div class="col-md-2 mb-3">
            <label>Tipo</label>
            <select class="form-control" v-model="tipo">
              <option value="1">Proyecto</option>
              <option value="2">Articulo</option>
            </select>
          </div>
          <template v-if="tipo == 1">
          <div class="col-md-6 mb-3">
            <label>Proyecto</label>
              <v-select :options="listaProyectos"  v-validate="'required'" v-model="proyecto_id" label="name" name="proyecto" data-vv-name="proyecto" ></v-select> <!---->
          </div>
          <div class="col-md-1 mb-3">
            <label>&nbsp;</label><br>
            <button class="btn btn-dark" @click="listarComprasText()">Buscar</button>
          </div>
          <div class="col-md-1 mb-3">
            <label>&nbsp;</label><br>
            <button class="btn btn-success" @click="descargarExcel()">Descargar</button>
          </div>
        </template>
        <template v-if="tipo == 2">
          <div class="col-md-6 mb-3">
            <label>&nbsp;Artículo</label>
            <input type="text" class="form-control col" v-model="articulo_text">
          </div>
          <div class="col-md-1 mb-3">
            <label>&nbsp;</label><br>
            <button class="btn btn-dark ml-2" @click="listarComprasText()">Buscar</button>
          </div>
        </template>
        </div>


        <v-client-table :options="options" :columns="columns" :data="tableData">
          <template slot="ids" slot-scope="props">
            <button class="btn btn-sm btn-success" @click="descargar(props.row,props.row.tipo)"><i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i> </button>
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
      tipo : 0,
      datos_r : null,
      listaArticulos : [],
      listaProyectos : [],
      listaProveedores : [],
      columns : ['ids','tipo','oc_folio','nombre_corto','folio','nombre','desc','cantidad_salida','unidad','empleado_solicita',"p_salida"],
      tableData : [],
      proyecto : '',
      proveedor : '',
      articulo : '',
      articulo_text : '',
      proyecto_id : '',
      options : {
        headings: {
          "ids":"Acciones",
          'folio' : 'Folio',
          'desc' : 'Descripción',
          'cantidad_salida' : 'Cantidad',
          'p_salida' : 'Proyecto Salida',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        // sortable: ['tipo','nombre_corto','folio','nombre','desc','unidad'],
        // filterable: ['tipo','nombre_corto','folio','nombre','desc','unidad'],
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
      // axios.get('articulos').then(response => {
      //   this.listaArticulos = [];
      //   response.data.forEach((item, i) => {
      //     this.listaArticulos.push({
      //       id : item.id,
      //       name : item.nombre + ' ' + item.descripcion
      //     });
      //   });
      // })
      // .catch(function (error) {
      //   console.log(error);
      // });
      axios.get('obtener/proyectos').then(response => {
          me.listaProyectos = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    listarCompras(){

    },

    descargar(data,tipo) {
      if (tipo === 'Taller') {
        window.open('descargar-salida-new/' + data.ids, '_blank');
      }
      if (tipo === 'Sitio') {
        window.open('descargar-salidasitio-new/' + data.ids, '_blank');
      }
      if (tipo === 'Resguardo') {
        window.open('descargar/salida/resguardo/' + data.empleado_id, '_blank');
      }

    },


    listarComprasText(){
      if (this.tipo == 2) {
        axios.post('buscar/articulo/salidas',{
          articulo : this.articulo_text,
        }).then(response => {
          this.tableData = response.data;
        }).catch(e => {
          console.error(e);
        });
      }else if (this.tipo == 1) {
        axios.post('buscar/proyecto/salidas',{
          proyecto : this.proyecto_id.id,
        }).then(response => {
          this.tableData = response.data;
        }).catch(e => {
          console.error(e);
        });
      }

    },

    descargarExcel(){
      window.open("descargar/salidas/excel/" + this.proyecto_id.id,"_blank");
    },






  },
  mounted(){
    this.getData();
  }
}
</script>
