<template >
<main class="main">
  <div class="container-fluid">
    <br>
    <div class="card" v-show="!detalle">
      <vue-element-loading :active="isLoading"/>
      <div class="card-header">
        <i class="fa fa-align-justify"></i> &nbsp;Traspasos
        <button type="button" @click="abrirModal('traspaso','registrar')" class="btn btn-dark float-sm-right">
          <i class="fas fa-plus"></i>&nbsp;Nuevo
        </button>

      </div>
      <div class="card-body">
        <v-client-table :columns="columns" :data="data" :options="options" ref="myTable">
            <template slot="traspasos.id" slot-scope="props">
          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
          <div class="btn-group dropup" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
              <div v-show="props.row.contador == 0">
                <button type="button" @click="abrirModal('traspaso','actualizar',props.row.traspasos)" class="dropdown-item">
                  <i class="icon-pencil"></i>&nbsp;Actualizar traspaso
                </button>
              </div>
            <button type="button" @click="detalles(props.row.traspasos)" class="dropdown-item">
              <i class="fas fa-eye"></i>&nbsp; Ver detalles traspaso
            </button>
            <div v-show="props.row.contador != 0 && props.row.traspasos.estado == 1">
            <button type="button" @click="enviar(props.row.traspasos)" class="dropdown-item">
              <i class="fas fa-eye"></i>&nbsp; Enviar
            </button>
            </div>
            </div>
          </div>
          </div>
          </template>

          <template slot="traspasos.estado" slot-scope="props">
            <div v-if="props.row.traspasos.estado == 1">
              <span class="btn btn-outline-info">Nuevo</span>
            </div>
            <div v-if="props.row.traspasos.estado == 2">
              <span class="btn btn-outline-warning">En transito</span>
            </div>
            <div v-if="props.row.traspasos.estado == 3">
              <span class="btn btn-outline-success">Recibido</span>
            </div>
          </template>

        </v-client-table>
      </div>
    </div>

    <!--Inicio del modal agregar/actualizar Traspasos-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <vue-element-loading :active="isLoading"/>
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div>
            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModal"></h4>
              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- {{traspaso.empleado_transporta}} -->
              <!-- {{traspaso.empleado_transporta}} -->
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="fecha_salida">Fecha de salida</label>
                <div class="col-md-9">
                  <input type="date" name="fecha_salida" v-model="traspaso.fecha_salida"
                  class="form-control" placeholder="Fecha de salida" autocomplete="off" id="fecha_salida">
                  <span v-show="span.fecha_salida" class="text-danger">El campo fecha es requerido</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="empleado_transporta">Empleado que transporta</label>
                <div class="col-md-9">
                  <v-select :options="optionsvs" v-model="traspaso.empleado_transporta" name="empleado_transporta"
                  label="name" ></v-select>
                  <span v-show="span.empleado_transporta" class="text-danger">El campo empleado es requerido</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="stock">Stock</label>
                <div class="col-md-9">
                  <select class="form-control" id="stock"  v-bind:disabled="vacio > 0" name="stock"  v-model="traspaso.stock_id" >
                    <option v-for="item in listaStocks" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                  </select>
                  <span v-show="span.stock" class="text-danger">El campo stock es requerido</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="ubicacion">Destino</label>
                <div class="col-md-9">
                  <select class="form-control" id="ubicacion" name="ubicacion"  v-model="traspaso.tipo_ubicacion_id" >
                    <option v-for="item in listaUbicacion" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                  </select>
                  <span v-show="span.ubicacion" class="text-danger">El campo destino es requerido</span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="comentarios">Comentarios</label>
                <div class="col-md-9">
                  <input type="text" v-validate="'required|max:100'" name="comentarios" v-model="traspaso.comentarios"
                  class="form-control" placeholder="Comentarios" autocomplete="off" id="comentarios">
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <!-- <vue-element-loading :active="isLoading"/> -->
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="validacion(); guardar(1);"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal Traspasos-->
    <div v-show="detalle">
      <detalle ref="detalle" @detalle:click="maestro"></detalle>
    </div>

  </div>
</main>
</template>

<script>
const Detalle = r => require.ensure([], () => r(require('./DetalleTraspasos.vue')), 'alm');
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return {
      detalle : false,
      isLoading :  false,
      tituloModal : '',
      tipoAccion : 0,
      modal : 0,
      vacio : 0,
      columns : ['traspasos.id','traspasos.nombre_empledo','traspasos.origen','traspasos.destino','traspasos.fecha_salida','traspasos.stoke_nombre','traspasos.comentarios','traspasos.estado'],
      optionsvs: [],
      listaStocks: [],
      listaUbicacion: [],
      validado : 1,
      traspaso : {
        id : 0,
        fecha_salida : '',
        empleado_transporta : null,
        stock_id : 0,
        comentarios : '',
        tipo_ubicacion_id : 0,
      },
      span: {
        fecha_salida :false,
        empleado_transporta : false,
        stock: false,
        ubicacion : false,
      },
      options: {
        headings: {
          'traspasos.id' : 'Acción',
          'traspasos.fecha_salida' : 'Fecha salida',
          'traspasos.nombre_empledo': 'Empleado transporta',
          'traspasos.stoke_nombre': 'Stock',
          'traspasos.origen' : 'Origen',
          'traspasos.comentarios' : 'Comentarios',
          'traspasos.estado' : 'Estado',
          'traspasos.destino' : 'Destino',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
      data : [],

    }
  },
  components : {
    'detalle' : Detalle,
  },
  methods : {
    getData(){
      let me = this;
      me.isLoading = true;
      axios.get('/traspaso').then(response => {
        me.data = response.data;
        me.isLoading = false;
      }).catch(function (error){
        console.error(error);
      });
      axios.get('/ubicaciontraspaso').then(response => {
        me.listaUbicacion = response.data;
      }).catch(function (error){
        console.error(error);
      })
    },

    validacion(){
      if (this.traspaso.fecha_salida == '') { this.validado = 1; this.span.fecha_salida = true; }else { this.span.fecha_salida = false; this.validado =0; }
      if (this.traspaso.empleado_transporta == null) { this.validado = 1; this.span.empleado_transporta = true; }else { this.span.empleado_transporta = false; this.validado =0; }
      if (this.traspaso.stock_id == 0) { this.validado = 1; this.span.stock = true; }else { this.span.stock = false; this.validado =0; }
      if (this.traspaso.tipo_ubicacion_id == 0) { this.validado = 1; this.span.ubicacion = true; }else { this.span.ubicacion = false; this.validado =0; }

    },

    getlistas() {
      let me= this;
      axios.get('/empleado').then(response => {
        for (var i = 0; i < response.data.length; i++) {
          this.optionsvs.push(
            {
              id:response.data[i].empleado.id,
              name:response.data[i].empleado.nombre+' '+response.data[i].empleado.ap_paterno+' '+response.data[i].empleado.ap_materno,
            });
          }
        })
        .catch(function (error) {
          console.log(error);
        });
        axios.get('/stock').then(response => {
          me.listaStocks = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
      },

    abrirModal(modelo, accion, data = []){
      switch (modelo) {
        case 'traspaso':
        {
          switch (accion) {
            case 'registrar':{
              Utilerias.resetObject(this.traspaso);
              this.modal = 1;
              this.tituloModal = "Registrar traspaso";
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar' : {
              Utilerias.resetObject(this.traspaso);
              this.modal = 1;
              this.validado = 0;
              this.tituloModal = "Actualizar traspaso";
              this.tipoAccion = 2;
               this.traspaso.id = data['id'];
               this.traspaso.fecha_salida = data['fecha_salida'];
               this.traspaso.empleado_transporta = { id : data['empleado_transporta_id'], name: data['nombre_empledo'],}
               this.traspaso.stock_id = data['stock_id'];
               this.traspaso.comentarios = data['comentarios'];
               this.traspaso.tipo_ubicacion_id = data['tipo_ubicacion_id'];
              break;
            }
          }
        }
      }
    },

    guardar(nuevo){
      if (this.validado == 0) {

           this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? '/traspaso' : '/traspaso/'+this.traspaso.id,
            data: {
              'fecha_salida': this.traspaso.fecha_salida,
              'empleado_transporta_id': this.traspaso.empleado_transporta.id,
              'stock_id': this.traspaso.stock_id,
              'comentarios': this.traspaso.comentarios,
              'tipo_ubicacion_id' : this.traspaso.tipo_ubicacion_id,
            }
          }).then(function (response) {
             me.isLoading = false;
             me.getData();
             me.cerrarModal();
             toastr.success('Correcto', nuevo ? 'Traspaso creado correctamente' : 'Traspaso actualizado correctamente');

          }).catch(function (error) {
            console.log(error);
          });
        }
    },

    detalles(data){
     let me = this;
     me.detalle = true;
     var ChildDetalle = this.$refs.detalle;
     ChildDetalle.cargardetalle(data);
   },

    cerrarModal(){
      this.modal = 0;
    },

    maestro(){
      let me = this;
      me.detalle = false;
      me.getData();
      me.getlistas();
    },

    enviar(data){
      let me = this;
      axios.get('/enviartraspaso/'+ data.id).then(response => {
        me.getData();
        toastr.success('Correcto','Traspaso enviado correctamente!!!');
      }).catch(error => {
        console.error(error);
      });
    },

    verpartidasvalidacion(data){
      let me = this;
      axios.get('/verpartidasvalidacion/'+data.id).then(response => {
        me.vacio = response.data;
      }).catch(error => {
        console.error(error);
      });
    },



  },
  mounted(){
    this.getData();
    this.getlistas();
  }
}
</script>
