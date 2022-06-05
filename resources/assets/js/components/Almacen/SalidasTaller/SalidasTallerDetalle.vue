<template>

<!-- Ejemplo de tabla Listado -->
<div class="card">
  <div class="card-header">
    <i class="fa fa-align-justify"></i> Salidas
    <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
      <i class="fas fa-arrow-left"></i>&nbsp;Atras
    </button>
  </div>
  <div class="card-body">
    <vue-element-loading :active="isLoading"/>

    <v-client-table :columns="columnspartida" :data="tableDataPartida" :options="optionspartida" ref="myTable">
      <template slot="id" slot-scope="props">
        <button v-show="PermisosCRUD.Delete" type="button" class="btn btn-outline-dark" @click="eliminar(props.row.id)" >
          <i class="fas fa-trash"></i>&nbsp;
        </button>
      </template>
    </v-client-table>


  </div>
  <!-- Nuevo y editar partidas requisiciones -->
  <div class="card" ref="formLote">
    <vue-element-loading />
    <div class="card-header">
    </div>
    <div class="card-body">
      <div class="row justify-content-center">
        <h4  class="col-sm-12 form-control-label">Registro de salidas a : {{titulosalidas}}</h4></div><hr><br>
        <form>
          <!-- <input type="text" class="form-control" id="stocke_id" name="stocke_id" v-model="partidasalida.stocke_id" placeholder="Stoke"  > -->
          <input type="text" class="form-control" id="salida_id" name="salida_id" v-model="partidasalida.salida_id" placeholder="Salida" hidden>
          <input type="text" class="form-control" id="lote_id" name="lote_id" v-model="partidasalida.lote_id" placeholder="Lote" hidden>
          <input type="text" class="form-control" id="tiposalida_id" name="tiposalida_id" v-model="partidasalida.tiposalida_id" placeholder="tiposalida_id" hidden>
          <input type="text" class="form-control" id="lote_temporal_id" name="lote_temporal_id" v-model="partidasalida.lote_temporal_id" hidden>

          <!-- <input type="text" class="form-control" id="proveedore_id" name="proveedore_id" v-model="" hidden> -->
          <div class="form-group row">
            <label for="inputArticulo" class="col-md-1 form-control-label">Artículo</label>
            <div class="col-md-8">
              <div class="input-group mb-3">
                <input type="text" class="form-control"  v-model="partidasalida.articulo" placeholder="Articulo" readonly>
                <div class="input-group-append">
                  <button class="btn btn-secondary" v-bind:disabled="desabilitarBuscarM" type="button" @click="abrirModalA('articulo','registrar')">Buscar</button>
                </div>
              </div>
            </div>
          </div>
          <!-- {{lotes_TGP}}
          {{partidasalida.lotes_tgp}} -->
          <div class="form-group row">
            <label for="inputArticulo" class="col-md-1 form-control-label">Lote</label>
            <div class="col-md-4">
              <div class="input-group mb-3">
                <input type="text" class="form-control"  v-model="partidasalida.lote_nombre_input" placeholder="Lote" >
                <div class="input-group-append">
                  <button class="btn btn-secondary" type="button" @click="buscarLoteNombre()">Buscar</button>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="input-group mb-3">
                <select class="form-control" v-model="partidasalida.lotes_tgp" v-validate="'excluded:0'" v-bind:disabled="!desabilitarBuscarM" data-vv-as="Resguardo" @change = "seleccionarLote()">
                  <option value="0">---Seleccione---</option>
                  <option v-for="item in lotes_TGP" :value="item" :key="item.id">{{ item.nombre }}</option>
                </select>
                <!-- <input type="text" class="form-control"  v-model="partidasalida.articulo" placeholder="Articulo" readonly> -->
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-1 form-control-label" for="cantidad_existente">Cantidad existente</label>
            <div class="col-md-4">
              <input type="text" name="cantidad_existente"  v-model="partidasalida.cantidad_existente" class="form-control" readonly>
              <span class="text-danger">{{ errors.first('cantidad_existente') }}</span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-1 form-control-label" for="cantidad">Cantidad</label>
            <div class="col-md-4">
              <input type="text" name="cantidad"  v-model="partidasalida.cantidad" v-bind:class="'form-control '+clasescantidad" @blur="validacioncantidad" placeholder="Cantidad" autocomplete="off" id="cantidad">
              <span class="text-danger">{{ errors.first('cantidad') }}</span>
            </div>
          </div>


        </form>
      </div>
      <div class="card-footer">
        <button type="button" class="btn btn-secondary" @click="cancelar()"><i class="fas fa-times"></i>&nbsp;Cancelar</button>
        <button type="button" v-if="tipoAccionpr==1" class="btn btn-dark" @click="validacioncantidaduno(); guardarPR();"><i class="fas fa-plus"></i>&nbsp;Agregar</button>
      </div>
    </div>
    <!-- Fin  de Nuevo y editar detalle requisicion correspondiente ala tabla partidas_requisiciones -->

    <!--Inicio del modal agregar/actualizar articulos-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modala}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div>

            <!-- <vue-element-loading :active="isLoadingg"/> -->
            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModala"></h4>
              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="accordion" id="accordion">

                <div class="card">
                  <div class="card-header bg-dark justify-content-center" id="headingUno">
                    <h5 class="mb-0">
                      <button class="btn btn-dark collapsed" type="button" data-toggle="collapse" data-target="#collapseDos" aria-expanded="false" aria-controls="collapseDos">
                        <b> Apartados </b>
                      </button>
                      <button class="btn btn-dark collapsed" type="button" data-toggle="collapse" data-target="#collapseUno" aria-expanded="false" aria-controls="collapseUno">
                        <b>General y Proyecto</b>
                      </button>
                    </h5>
                  </div>
                  <div id="collapseUno" class="collapse" aria-labelledby="headingUno" data-parent="#accordion">
                    <v-client-table ref="myTable" :data="dataTableArticulo" :columns="columnsa" :options="optionsa" @row-click="seleccionarArticulo">
                    </v-client-table>
                  </div>
                  <div id="collapseDos" class="collapse" aria-labelledby="headingDos" data-parent="#accordion">
                    <v-client-table ref="myTable" :data="dataTableArticulodos" :columns="columnsados" :options="optionsa" @row-click="seleccionarArticulodos">
                    </v-client-table>
                  </div>
                </div>

              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal agregar articulo-->

  </div>

  <!-- Fin ejemplo de tabla Listado -->


</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return {
      titulosalidas : '',
      validado : 0,
      partidasalida:{
        stocke_id :'',
        salida_id : '',
        lote_id : '',
        cantidad : '',
        cantidad_existente : '',
        articulo : '',
        tiposalida_id:'',
        lote_temporal_id : 0,
        lote_nombre_input : '',
        lotes_tgp : 0,
      },
      clasescantidad : '',
      desabilitarBuscarM : false,
      lotes_TGP : [],
      tipoAccionpr : 0,
      modala : 0,
      salidas : null,
      dataTableArticulo : [],
      dataTableArticulodos : [],
      tituloModala : '',
      isLoading : false,
      columnspartida : ['id','cantidad','anombre','acodigo','adescripcion','amarca','aunidad','alnombre','sknombre'],
      columnsa : ['cantidad','proyecton','anombre','acodigo','adescripcion','amarca','aunidad'],
      columnsados : ['cantidad','lote_nombre','nombre_stock','anombre','acodigo','adescripcion','amarca','aunidad'],
      tableDataPartida : [],
      optionsa: {
        headings: {
          'id' : 'Acciones',
          'anombre': 'Nombre',
          'acodigo': 'Codigo',
          'adescripcion' : 'Descripción',
          'amarca' : 'Marca',
          'aunidad' : 'Unidad',
          'lote_nombre' : 'Lote',
          'proyecton' : 'Proyecto',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        //  sortable: ['empleado.folio'],
        //  filterable: ['empleado.folio'],
        filterByColumn: true,
        texts:config.texts
      },
      optionspartida: {
        headings: {
          id: 'Acciones',
          cantidad: 'Cantidad requerida',
          anombre : 'Nombre',
          acodigo : 'Codígo',
          adescripcion : 'Descripción',
          amarca : 'Marca',
          aunidad : 'Unidad',
          alnombre : 'Nombre almacén',
          sknombre : 'Proyecto'

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['id','cantidad','anombre','acodigo','adescripcion','amarca','aunidad'],
        filterable: ['id','cantidad','anombre','acodigo','adescripcion','amarca','aunidad'],
        filterByColumn: true,
        listColumns: {

        },
        texts:config.texts
      },
    }
  },
  methods : {
    cargardetallesalida(data = [])
    {
      this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
      this.salidas = data;
      let me = this;
      this.isLoading = true;
      this.partidasalida.salida_id = data.id;
      this.partidasalida.tiposalida_id = data.tiposalida_id;
      this.titulosalidas = data.salidan;
      axios.get('/partidasalida/' + data.id+'&'+data.tiposalida_id +'/ver' ).then(response => {
        console.log(response,'respoesta de busqueda');
        me.tableDataPartida = response.data;
        this.isLoading = false;
      })
      .catch(function (error){
        console.error();
      });
    },

    validacioncantidaduno(){
      if (this.partidasalida.cantidad == '') {
        swal('Error!','Complete los campos','warning' )
        this.validado = 0;
        this.clasescantidad = ' is-invalid';
      }
      else {
        this.validado = 1;
      }
    },

    validacioncantidad(){
      if (parseFloat(this.partidasalida.cantidad) > parseFloat(this.partidasalida.cantidad_existente)) {
        swal('Error!','No se puede pedir una cantidad mayor a la existente!','warning' )
        this.partidasalida.cantidad = '';
        this.validado = 0;
      }
      else {
        this.validado = 1;
      }
    },

    /**
    * [seleccionarArticulo selecciona la fila de la tabla articulos y carga los datos o los inputs correspondientes]
    * @param  {Array} data [description]
    * @return {[type]}      [description]
    */
    seleccionarArticulo(data){
      let me = this;
      this.partidasalida.lote_id = data.row.id;
      this.partidasalida.articulo = data.row.adescripcion;
      this.partidasalida.cantidad_existente = data.row.cantidad;
      this.partidasalida.lote_temporal_id = 0;
      this.modala = 0;
    },

    /**
    * [seleccionarArticulo selecciona la fila de la tabla articulos y carga los datos o los inputs correspondientes]
    * @param  {Array} data [description]
    * @return {[type]}      [description]
    */
    seleccionarArticulodos(data){
      let me = this;
      // console.log(data);
      this.partidasalida.lote_id = data.row.id;
      this.partidasalida.articulo = data.row.adescripcion;
      this.partidasalida.cantidad_existente = data.row.cantidad;
      this.partidasalida.cantidad = data.row.cantidad;
      this.partidasalida.lote_temporal_id = data.row.lt_id;
      this.modala = 0;
    },

    /**
    * [abrirModalA Abre el modal donde se eligen los articulos para la salida ya sea del stock general o de un proyecto especifico]
    * @param  {String} modelo [description]
    * @param  {String} accion [description]
    * @return {[type]}        [description]
    */
    abrirModalA(modelo, accion){
      switch(modelo){
        case "articulo":
        {
          switch(accion){
            case 'registrar':
            {
              let me= this;
              me.cancelar();
              this.modala = 1;
              this.tipoAccionpr = 1;
              this.tituloModala = 'Registrar artículo a la salida ';
              axios.get('/partidasalida/'+me.salidas.proyecto_id).then(response => {
                me.dataTableArticulo = response.data;
              })
              .catch(function (error) {
                console.log(error);
              });

              axios.get('/getlotetemporal/'+me.salidas.proyecto_id).then(response => {
                me.dataTableArticulodos = response.data;
              }).catch(function (error){
                console.error(error);
              });

              break;
            }
          }
        }
      }
    },

    /**
    * [guardarPR guarda las partidas de la salidas a taller y verifica que el artículo sea o no suseptible a resguardo]
    * @return {[type]} [description]
    */
    guardarPR(){
      let me= this;
      me.isLoading = true;
      if (this.validado == 1) {
        /**/
        axios.post('/registroresguardo', {
          salida_id: this.partidasalida.salida_id,
          tiposalida_id : this.partidasalida.tiposalida_id,
          lote_id: this.partidasalida.lote_id,
          cantidad : this.partidasalida.cantidad,
        })
        .then(function (response) {
        })
        .catch(function (error) {
          console.log(error);
        });
        /**/
        if (this.partidasalida.lote_temporal_id != 0) {
          axios.post('/partidasalidaapartados', {
            lote_temporal_id : this.partidasalida.lote_temporal_id,
            cantidad : this.partidasalida.cantidad,
            salida_id: this.partidasalida.salida_id,
            tiposalida_id : this.partidasalida.tiposalida_id,
            lote_id: this.partidasalida.lote_id,
          }).then(function (response){
            me.cargardetallesalida(me.salidas);
            me.cancelar();
            toastr.success('Correcto','Partida agregada correctamente');
            me.isLoading = false;
          }).catch(function (error){
            console.error(error);
          });
        }
        else {
          axios.post('/partidasalida', {
            salida_id: this.partidasalida.salida_id,
            tiposalida_id : this.partidasalida.tiposalida_id,
            lote_id: this.partidasalida.lote_id,
            cantidad : this.partidasalida.cantidad,
          })
          .then(function (response) {
            me.cargardetallesalida(me.salidas);
            me.cancelar();
            toastr.success('Correcto','Partida agregada correctamente');
            me.isLoading = false;
          })
          .catch(function (error) {
            console.log(error);
          });
        }
      }
    },

    buscarLoteNombre(){
      let me = this;
      // console.log(me.salidas);
      this.desabilitarBuscarM = true;
      this.partidasalida.lotes_tgp = 0;
      axios.post('/buscarlotenombre',{
        'lotenombreinput' : this.partidasalida.lote_nombre_input,
        'proyecto_id' : me.salidas.proyecto_id,
      }).then(response => {
        this.lotes_TGP = response.data;

      }).catch(error => {
        console.log(error);
      });
    },

    seleccionarLote(){
      if(this.partidasalida.lotes_tgp.nombre != 'Apartado por requisicion'){
        axios.get('/obtenerarticulog/'+ this.partidasalida.lotes_tgp.id).then(response => {
          this.tipoAccionpr = 1;
          this.partidasalida.lote_id = response.data.id;
          this.partidasalida.articulo = response.data.adescripcion;
          this.partidasalida.cantidad_existente = response.data.cantidad;
          this.partidasalida.lote_temporal_id = 0;
        }).catch(error => {
          console.log(error);
        });

      }

      else {
        this.tipoAccionpr = 1;
        axios.get('/obtenerarticuloa/'+ this.partidasalida.lotes_tgp.id).then(response => {
          this.partidasalida.lote_id = response.data.id;
          this.partidasalida.articulo = response.data.adescripcion;
          this.partidasalida.cantidad_existente = response.data.cantidad;
          this.partidasalida.lote_temporal_id = response.data.lt_id;
        }).catch(error => {
          console.log(error);
        });

      }
    },

    maestro(){
      this.$emit('salidastallerdetalle:change');
      this.cancelar();
    },

    cancelar(){
      this.partidasalida.cantidad = '';
      this.tipoAccionpr = 0;
      this.partidasalida.articulo = '';
      this.partidasalida.cantidad_existente = '';
      this.partidasalida.lote_nombre_input = '';
      this.partidasalida.lotes_tgp = 0;
      this.desabilitarBuscarM = false;
    },

    cerrarModal(){
      this.modala = 0;
      this.tituloModal='';
    },

    eliminar(id){
      let me = this;
      axios.get('eliminar/partida/salida/' + id).then(response => {
        me.cargardetallesalida(me.salidas);
        me.cancelar();
        toastr.success('Correcto','Partida eliminada correctamente');
        // me.isLoading = false;
      }).catch(error => {
        console.error(error);
      });
    }
  },
  mounted () {

  }
}
</script>
