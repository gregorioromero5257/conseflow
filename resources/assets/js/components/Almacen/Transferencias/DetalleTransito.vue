<template >
<div class="container-fluid">

  <div class="card">
    <div class="card-header">
        <i class="fa fa-align-justify"></i> &nbsp;Detalles del traspaso en transito
      <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
        <i class="fa fa-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <div class="card-body">

      <v-client-table :columns="columns" :data="data" :options="options" ref="myTable">
        <template slot="id" slot-scope="props" >
      <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
      <div class="btn-group dropup" role="group">
        <button id="btnGroupDrop1" type="button" v-bind:disabled="props.row.estado > 0"
        class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        <button  type="button" @click="abrirModalA('articulo','almacenar',props.row)" class="dropdown-item">
        <i class="fas fa-box-open"></i>&nbsp;Almacenar
        </button>
        <button  type="button" @click="daniado(props.row)" class="dropdown-item">
          <i class="fas fa-unlink"></i>&nbsp;Dañado
        </button>
        <button  type="button" @click="perdido(props.row)" class="dropdown-item">
          <i class="icon-close"></i>&nbsp;Perdido
        </button>

        </div>
      </div>
      </div>

    </template>
    <template slot="estado" slot-scope="props">
      <div v-if="props.row.estado == 0">
        <span class="btn btn-outline-warning">Sin almacenar</span>
      </div>
      <div v-if="props.row.estado == 1">
        <span class="btn btn-outline-success">Almacenado</span>
      </div>
      <div v-if="props.row.estado == 2">
        <span class="btn btn-outline-danger">Dañado</span>
      </div>
      <div v-if="props.row.estado == 3">
        <span class="btn btn-outline-danger">Perdido</span>
      </div>
    </template>

      </v-client-table>

    </div>
  </div>

  <!--Inicio del modal agregar/actualizar articulos-->
  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modala}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dark modal-lg" role="document">
      <div class="modal-content">
        <div>
          <vue-element-loading :active="isLoading"/>
          <div class="modal-header">
            <h4 class="modal-title">&nbsp;Seleccione el Almacén a guardar</h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
          <!-- <v-client-table ref="myTable" :data="dataTableArticulo" :columns="columnsarticulos" :options="optionsaarticulos" @row-click="seleccionarArticulo">
          </v-client-table> -->

          <div class="form-group row">
            <label class="col-md-3 form-control-label" for="almacen_id">Almacen</label>
            <div class="col-md-9">
              <select class="form-control" id="almacen_id"  name="almacen_id"  v-model="almacen.id" @change="almac"  data-vv-as="Stand">
                <option v-for="item in listaAlmaceness" :value="item.id" :key="item.id">{{ item.nombre }}</option>
              </select>
              <span class="text-danger" v-show="span">El campo almacen es requerido</span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3 form-control-label" for="stand_id">Stand</label>
            <div class="col-md-9">
              <select class="form-control" id="stand_id"  name="stand_id"  v-model="almacen.stand_id" @change="niv" data-vv-as="Stand">
                <option v-for="item in listaStand" :value="item.id" :key="item.id">{{ item.nombre }}</option>
              </select>
              <span class="text-danger">{{ errors.first('grupo_id') }}</span>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 form-control-label" for="nivel_id">Nivel</label>
            <div class="col-md-9">
              <select class="form-control" id="nivel_id"  name="nivel_id"  v-model="almacen.nivel_id"  data-vv-as="Nivel">
                <option v-for="item in listaNivel" :value="item.id" :key="item.id">{{ item.nombre }}</option>
              </select>
              <span class="text-danger">{{ errors.first('grupo_id') }}</span>
            </div>
          </div>


          </div>
          <div class="modal-footer">
            <vue-element-loading :active="isLoading"/>
            <button type="button" class="btn btn-secondary" @click="almacenaren();"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal agregar articulo-->

</div>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      columns : ['id','cantidad','nombre_stock','anombre','acodigo','adescripcion','amarca','aunidad','estado'],
      data : [],
      options: {
        headings: {
          'id' : 'Acciones',
          'anombre': 'Nombre',
          'acodigo': 'Codigo',
          'adescripcion' : 'Descripción',
          'amarca' : 'Marca',
          'aunidad' : 'Unidad',
          'nombre_stock' : 'Stock',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
      listaNivel : [],
      listaStand : [],
      listaAlmaceness : [],
      span : false,
      almacen : {
        id : 0,
        stand_id : 0,
        nivel_id : 0,
      },
      detalles : null,
      detalles_articulo : null,
      modala : 0,
      isLoading : false,
    }
  },
  methods : {

    cargarDetalle(data){
      let me = this;
      me.detalles = data;
      axios.get('/partidatraspasos/'+data.id).then(response => {
        me.data = response.data;
      }).catch(function (error){
        console.error(error);
      });
    },

    maestro(){
      this.$emit('detalle:click');
    },

    abrirModalA(modelo, accion, data = []){
      switch(modelo){
        case "articulo":
        {
          switch(accion){
            case 'almacenar':
            {
              // let me= this;
              this.modala = 1;
              this.almacen.id = 0;
              this.isLoading = true;
              let me=this;
              me.detalles_articulo = data;
              axios.get('/almacen/ver').then(response => {
                me.listaAlmaceness = response.data;
                me.isLoading = false;
              })
              console.log(data);
              break;
            }
          }
        }
      }
    },

    cerrarModal(){
      this.modala = 0;
    },

    /**
     * [almac Metodo de consulta a la BD ]
     * @return {Response} [description]
     */
    almac(){
      let me = this;
      // this.isLoading = true;
      axios.get('almacen/verstand/' + this.almacen.id).then(response => {
        me.listaStand = response.data;
        // me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    /**
     * [niv Metodo de consulta a la BD ]
     * @return {Response} [description]
     */
    niv(){
      let me = this;
      // this.isLoading = true;
      axios.get('almacen/vernivel/' + this.almacen.stand_id).then(response => {
        me.listaNivel = response.data;
        // me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    almacenaren(){
      if (this.almacen.id == 0) { this.span = true; }
      else {
        this.span = false;
        this.isLoading = true;
        let me = this;
        axios.post('/almacenartraspaso',{
          id_partida_traspaso : this.detalles_articulo.id,
          id_traspaso : this.detalles.id,
          id_almacen : this.almacen.id,
          stand_id : this.almacen.stand_id,
          nivel_id : this.almacen.nivel_id,
        }).then(response => {
          me.isLoading = false;
          me.cargarDetalle(me.detalles);
          me.modala = 0;
        }).catch(error => {
          console.error(error);
        });
      }
    },

    perdido(data){
      let me = this;
      axios.get('/perdido/'+data.id).then(response => {
        me.cargarDetalle(me.detalles);

      }).catch(error => {
        console.error(error);
      });
    },

    daniado(data){
      let me = this;
      axios.get('/daniado/'+data.id).then(response => {
        me.cargarDetalle(me.detalles);

      }).catch(error => {
        console.error(error);
      });
    },



  },
  mounted() {

  }
}
</script>
