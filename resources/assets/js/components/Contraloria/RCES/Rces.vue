<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card" v-show="!detalle">
        <div class="card-header">

        </div>
        <div class="card-body">

          <div class="form-row">
            <div class="col-md-4 mb-3">
              <label>Proyectos</label>
              <v-select :options="listaProyectos" v-validate="'required'" v-model="proyecto_id"
              label="name" name="proyecto" data-vv-name="proyecto"
              @input="getProyecto()" ></v-select> <!---->
            </div>
          </div>

          <v-client-table :data="tableData" :options="options" :columns="columns">
            <template slot="id" slot-scope="props">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group dropup" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-grip-horizontal"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button type="button" v-show="props.row.proveedore_csct_id != '1'" @click="partidas(props.row)" class="dropdown-item">
                                <i class="fas fa-eye"></i>&nbsp; Detalle
                            </button>
                        </div>
                    </div>
                </div>
            </template>
          </v-client-table>
        </div>
      </div>

      <div class="card" v-show="detalle">
        <div class="card-header">
          {{data_detalle.folio}}
          <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="col-md-4 mb-3">
              <label>PORCENTAJE ENTRADAS</label><br>
              <span class="btn btn-outline-secondary">{{porcentaje}} %</span>
            </div>
            <template v-if="fecha_entrega != ''">
              <div class="col-md-4 mb-3">
                <label>FECHA ENTREGA</label><br>
                <span class="btn btn-outline-secondary">{{fecha_entrega}} </span>
              </div>
            </template>
          </div>
          <hr>
          <div class="form-row">
            <div class="col-md-4">
              <label>Generar Req/OC</label><br>
              <button class="btn btn-outline-primary" @click="generarRO()"> <i class="fas fa-check"></i> </button>
            </div>
          </div>
          <hr>
          <v-client-table :data="tableDataDetalle" :options="optionsDetalle" :columns="columnsDetalle">
            <template slot="id" slot-scope="props" >
              <template v-if="props.row.res_id == NULL">
                <button class="btn btn-outline-success" @click="CrearEntrada(props.row.id)">Crear E/S</button>
              </template>
              <template v-else>
                <button class="btn btn-outline-primary" >Creado</button>
              </template>
            </template>
            <template slot="porcentaje" slot-scope="props">
                <template v-if="props.row.cantidad_entrada == 0">
                  <span class="btn btn-outline-success">  100 % </span>
                </template>
                <template v-if="props.row.cantidad_entrada == props.row.cantidad">
                  <span class="btn btn-outline-danger">  0 % </span>
                </template>
                <template v-if="props.row.cantidad_entrada != props.row.cantidad && props.row.cantidad_entrada != 0 ">
                  <span class="btn btn-outline-warning">  {{Math.round((props.row.cantidad_entrada / props.row.cantidad) * 100)}} % </span>
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

export default{
  data(){
    return{
      detalle : false,
      listaProyectos : [],

      porcentaje : '',
      fecha_entrega : '',
      data_detalle : '',

      proyecto_id : '',

      tableData : [],
      columns : ['id','folio','rs_cfw','rs_csct'],
      options: {
        headings: {
          'rs_cfw' : 'Proveedor CFW',
          'rs_csct' : 'Proveedor CSCT'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },

      tableDataDetalle : [],
      columnsDetalle : ['id','ad','comentario','cantidad','porcentaje'],
      optionsDetalle: {
        headings: {
          'ad' : 'Descripción',
          'porcentaje' : '% Entrada'
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
  methods :{
    getData(){
      axios.get('get/proyectos/rces').then(response => {
        this.listaProyectos = response.data;
      }).catch(e => {
        console.error(e);
      });

    },

    getProyecto(){
      if (this.proyecto_id != '') {

      axios.get('get/proyecto/rces/' + this.proyecto_id.id).then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });

      }
    },


    partidas(data){
      this.data_detalle = data;
      this.detalle = true;
      axios.get('get/partidas/oc/rces/' + data.id ).then(response =>{
        this.porcentaje = Math.round(response.data.porcentaje, 2);
        this.tableDataDetalle = response.data.partidas;
        this.fecha_entrega = response.data.calculo;
        console.log(response.data);
      }).catch(e => {
        console.error(e);
      });
      // console.log(data);
    },

    maestro(){
      this.detalle = false;
      this.porcentaje = '';
      // this.data_detalle = [];
      this.fecha_entrega = '';
    },

    generarRO(){
      axios.get('generar/requisicion/ordenc/csct/' + this.data_detalle.id).then(response => {
        if (response.data.estado == 1) {
          var msm ='Requisición y Orden de Compra generados consultar resultados en ERP CSCT';
          var title = 'Correcto';
          this.mensaje(msm, title);

        }else if (response.data.estado == 2) {
          var msm ='Requisición y Orden de Compra ya generados consultar resultados en ERP CSCT';
          var title = 'Atención';
          this.mensaje(msm, title);

        }else if (response.data.estado == 3) {
          var msm ='No se ha podido crear !!!';
          var title = 'Error';
          this.mensaje(msm, title);
        }

      }).catch(e => {
        console.error(e);
      });
    },

    mensaje(msn, title_){
      Swal.fire({
        title: title_,
        text: msn,
        footer: '<a href="https://syscsct.conserflow.com" target="_blank">syscsct.conserflow.com</a>'
      });
    },

    CrearEntrada(id){
      axios.get('generar/entrada/salida/csct/' + id).then(response => {

        var msm ='Entrada y Salida generados consultar resultados en ERP CSCT';
        var title = 'Correcto';
        this.mensaje(msm, title);
        this.partidas(this.data_detalle);
      }).catch(e => {
        console.error(e);
      });
    }
  },
  mounted(){
    this.getData();
  }
}

</script>
