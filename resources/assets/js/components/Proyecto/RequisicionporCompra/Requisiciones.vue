<template>
  <div>

        <!-- Ejemplo de tabla Listado -->
      <div class="card" v-show="!detallerequisiciones">
        <vue-element-loading :active="isLoading"/>
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Requisiciones por Orden de Compra
          <!-- <button type="button" @click="abrirModal('requisicion','registrar')" class="btn btn-primary float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button> -->
          <button type="button" @click="maestroInicial()" class="btn btn-secondary float-sm-right" >
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <i class="fas fa-grip-horizontal"></i>&nbsp;Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <template v-if="props.row.formato_requisiciones == null || props.row.formato_requisiciones == '' ">
                      <a v-show="PermisosCrud.Download" class="dropdown-item" @click="descargar(props.row)" href="#">
                        <i class="fas fa-file-pdf"></i>&nbsp;Descargar Formato de Requisición
                      </a>
                      <a class="dropdown-item" @click="descargarnew(props.row)" href="#">
                        <i class="fas fa-file-pdf"></i>&nbsp;Descargar Requisicion por Compra
                      </a>
                    </template>
                    <template v-if="props.row.formato_requisiciones == null || props.row.formato_requisiciones == '' ">
                      <a v-show="PermisosCrud.Upload" class="dropdown-item" @click="subirFormato(props.row)" href="#">
                        <i class="fas fa-file-upload"></i>&nbsp;Subir Requisición
                      </a>
                    </template>
                    <template v-if="props.row.formato_requisiciones != null && props.row.formato_requisiciones != '' ">
                      <a v-show="PermisosCrud.Download" class="dropdown-item" @click="descargarFormato(props.row.formato_requisiciones,1)" href="#">
                        <i class="fas fa-file-download"></i>&nbsp;Descargar Requisición Firmada
                      </a>
                    </template>
                    <template v-if="props.row.condicion == 0 ">
                    </template>
                    <a v-show="PermisosCrud.Create" @click="cargardetalle(props.row)" class="dropdown-item" href="#">
                      <i class="fas fa-eye"></i>&nbsp;Agregar Partidas
                    </a>
                    <div v-if="props.row.estado_id == 0 || props.row.estado_id == 4">
                      <a v-show="PermisosCrud.Update" type="button" @click="abrirModal('requisicion','actualizar',props.row)" class="dropdown-item" href="#">
                        <i class="icon-pencil"></i>&nbsp;Actualizar requisición
                      </a>
                    </div>

                    <div v-if="(props.row.estado_id == 0 || props.row.estado_id == 4 || props.row.estado_id == 7) ">
                      <a v-show="PermisosCrud.Delete" href="#" @click="cerrarRequisicion(props.row)" class="dropdown-item">
                        <i class="far fa-window-close"></i>&nbsp;Cerrar requisición
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </template>

            <template slot="estado_id" slot-scope="props">
              <template v-if="props.row.estado_id == 0">
                <button type="button" class="btn btn-uno"><i class="fas fa-plus"></i>&nbsp;Nuevo</button>
              </template>
              <template v-if="props.row.estado_id == 1">
                <button type="button" class="btn btn-dos"><i class="fa fa-exclamation-circle"></i>&nbsp;En espera (Validación)</button>
              </template>
              <template v-if="props.row.estado_id == 2">
                <button type="button" class="btn btn-tres"><i class="fa fa-exclamation-triangle"></i>&nbsp;En espera (Autorización)</button>
              </template>
              <template v-if="props.row.estado_id == 3">
                <button type="button" class="btn btn-cuatro"><i class="fa fa-check-circle"></i>&nbsp;Autorizado(En Almacén)</button>
              </template>
              <template v-if="props.row.estado_id == 6">
                <button type="button" class="btn btn-nueve"><i class="fa fa-check-circle"></i>&nbsp;Autorizado(En Compras)</button>
              </template>
              <template v-if="props.row.estado_id == 4">
                <button type="button" class="btn btn-ocho"><i class="fa fa-times"></i>&nbsp;No autorizado</button>
              </template>
              <template v-if="props.row.estado_id == 5">
                <button type="button" class="btn btn-siete"><i class="fa fa-caret-square-o-left"></i>&nbsp;Recibido (Compras)</button>
              </template>
              <template v-if="props.row.estado_id == 7">
                <button type="button" class="btn btn-seis"><i class="fa fa-caret-square-o-left"></i>&nbsp;Por Equivalente</button>
              </template>
              <template v-if="props.row.estado_id == 8">
                <button type="button" class="btn btn-cinco"><i class="fa fa-caret-square-o-left"></i>&nbsp;En Espera (Calidad)</button>
              </template>
            </template>
          </v-client-table>
        </div>
      <!-- </div> -->
      <!-- Fin ejemplo de tabla Listado -->

</div>
<!-- Inicio componente detalle -->
<div v-show="widgets.detallerequisiciones">
  <detallerequisiciones ref="detallerequisiciones" @detallerequisiciones:change="maestro"></detallerequisiciones>
</div>
<!-- Fin componente detalle  -->
<div v-show="modal">
  <!-- @modal:click="grabar($event)" -->
    <modal ref="modal" @modal:click="grabar($event)"></modal>
</div>

</div>
</template>
<script>
const Detalle = r => require.ensure([], () => r(require('../Requisiciones/Detalle.vue')), 'proyecto');
const Modal = r => require.ensure([], () => r(require('../Requisiciones/Modal.vue')), 'proyecto');
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data (){
    return {
      PermisosCrud : {},
      url: '/requisicion',
      detallerequisiciones: false,
      principal : null,
      modal : false,
      doc_req : [],
      optionsvs : [],//
      widgets : {
        detallerequisiciones : false,
      },
      /***/
      requisicion: {
        id :0,
        folio	 :'',
        area_solicita_id : '',
        formato_requisiciones : '',
        fecha_solicitud: '',
        descripcion_uso	 : '',
        tipo_compra: '',
        proyecto_id: 0,
        estado_id : 0,
        solicita_empleado_id : '',
        autoriza_empleado_id : '',
        valida_empleado_id : '',
        recibe_empleado_id : '',
        condicion :0,
        Area : '',
      },
      /****/
      listaEmpleados: [],
      listaAreasSol : [],//
      listaProyecto : [],//
      modal : 0,//
      tituloModal : '',//
      tipoAccion : 0,//
      tipoAcciondos : 1,
      disabled: 0,
      isLoading: false,
      columns: [ 'id','folio','nombreCorto','fecha_orden','observaciones','estado_id'],
      tableData: [],
      options: {
        headings: {
          'id': 'Acción',
          'folio': 'Folio',
          'nombreCorto': 'Nombre proyecto',
          'fecha_orden': 'Fecha solicitud',
          'estado_id' :'Estado',
          'observaciones' : 'Descripción',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['folio','nombreCorto','fecha_orden','observaciones','estado_id'],
        filterable: ['folio','nombreCorto','fecha_orden','observaciones','estado_id'],
        filterByColumn: true,
        listColumns: {
                        'estado_id': [{
                            id: 0,
                            text: 'Nuevo'
                        },
                        {
                            id: 1,
                            text: 'En espera (Validación)'
                        },
                        {
                            id: 2,
                            text: 'En espera (Autorización)'
                        },
                        {
                            id: 3,
                            text: 'Autorizado(En Almacén)'
                        },
                        {
                            id: 4,
                            text: 'No autorizado'
                        },
                        {
                            id: 5,
                            text: 'Recibido (Compras)'
                        },
                        {
                            id: 6,
                            text: 'Autorizado(En Compras)'
                        },
                        {
                            id: 7,
                            text: 'Por Equivalente'
                        },
                        {
                            id: 8,
                            text: 'En Espera (Calidad)'
                        },
                        ]

                    },
        texts:config.texts
      },

    }
  },
  computed:{
  },
  components : {
    'detallerequisiciones' : Detalle,
    'modal' : Modal,
  },
  methods : {

      grabar(nuevo){
        let me = this;
        me.getData(nuevo);
      },

      /**
      * [getData description]
      * @return {[type]} [description]
      */
      getData(data) {
        let me=this;
        this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
        me.isLoading = true;
        me.principal = data;
        axios.get('/listadorequisicionporcompra/' + data).then(response => {
          me.tableData = response.data;
          me.isLoading = false;
        })
        .catch(function (error) {
          console.log(error);
        });
      },

      /**
      * [actdesact description]
      * @param  {[type]} id      [description]
      * @param  {[type]} activar [description]
      * @return {[type]}         [description]
      */
      actdesact(id,activar){
        if(activar){
          this.swal_titulo = 'Esta seguro de activar esta requisición?';
          this.swal_tle = 'Activado';
          this.swal_msg = 'El registro ha sido activado con éxito.';
        }else{
          this.swal_titulo = 'Esta seguro de desactivar esta requisición?';
          this.swal_tle = 'Desactivado!';
          this.swal_msg = 'El registro ha sido desactivado con éxito.';
        }
        swal({
          title: this.swal_titulo,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Aceptar!',
          cancelButtonText: 'Cancelar',
          confirmButtonClass: 'btn btn-success',
          cancelButtonClass: 'btn btn-danger',
          buttonsStyling: false,
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let me = this;
            axios.get(me.url+'/'+id+'/edit').then(function (response) {
              if(activar){
                toastr.success(me.swal_tle,me.swal_msg,'success');
              }else{
                toastr.error(me.swal_tle,me.swal_msg,'error');
              }
              me.getData(me.principal);
            }).catch(function (error) {
              console.log(error);
            });
          } else if (
            result.dismiss === swal.DismissReason.cancel
          ) {
          }
        })
      },

      ///
      /**
      * [cerrarModal description]
      * @return {} [description]
      */
      cerrarModal(){
        this.modal=0;
        this.tituloModal='';
        Utilerias.resetObject(this.requisicion);
      },

      /**
      * [abrirModal description]
      * @param  {String} modelo    [description]
      * @param  {String} accion    [description]
      * @param  {Array}  [data=[]] [description]
      * @return {[type]}           [description]
      */
      abrirModal(estado, accion, data){
        this.modal = true;
        var ChildModal = this.$refs.modal;
        ChildModal.abrirModal(estado, accion, data);
      },



      /**
      * [cargardetalle description]
      * @param  {Array}  [dataEmpleado=[]] [description]
      * @return {[type]}                   [description]
      */
      cargardetalle(data = []) {
        this.widgets.detallerequisiciones = true;
        var childDetalle = this.$refs.detallerequisiciones;
        childDetalle.cargardetalle(data);
        this.detallerequisiciones = true;
      },

      /**
      * [maestro description]
      * @return {[type]} [description]
      */
      maestro(){
        this.widgets.detallerequisiciones = false;
        this.detallerequisiciones = false;
        this.tipoAccionpr =0;
        this.isLoading = false;
      },

      /******************/
      /**
      * [cancelar description]
      * @return {[type]} [description]
      */
      cancelar(){
        this.tipoAccionpr =0;
      },
      /**********/

      /**
      * [abrirBusquedaArticulo description]
      * @return {[type]} [description]
      */
      abrirBusquedaArticulo() {
        this.tipoAccionpr = 1;
        this.modal = 1;
      },

      /**
      * [descargar description]
      * @param  {[type]} data [description]
      * @return {[type]}      [description]
      */
      descargar(data) {
        window.open('descargar-requisicion/' + data.id, '_blank');
      },

      descargarnew(data) {
        window.open('descargar-requisicionporcompra/' + data.id, '_blank');
        let me = this;
        me.getData(data.proyecto_id);
      },

      descargarFormato(archivo,id){
        let me=this;
        axios({
          url: '/descargaRequi',
          method: 'POST',
          data: {'metodo': id, 'archivo':archivo},
          responseType: 'blob', // importante
        }).then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
          document.body.appendChild(link);
          link.click();
          //--Llama el metodo para borrar el archivo local una ves descargado--//
          axios({
            url: '/descargaRequi',
            method: 'POST',
            data: {'metodo': 0, 'archivo':archivo},
          })
          .then(response => {
          })
          .catch(function (error) {
            console.log(error);
          });
          //--fin del metodo borrar--//
        });
      },

      subirFormato(data){
        swal({
          title: 'Requisición Firmada',
          input: 'file',
          inputAttributes: {
            'accept': 'application/pdf',
            'aria-label': 'Upload your profile picture'
          },
          confirmButtonText: 'Subir Archivo',
          showCancelButton: true,
          inputValidator: (file) => {
            return !file && 'Este Campo no puede estar Vacío'
          }
        }).then((file) => {
          let me = this;
          if(file.value) {
            let formData = new FormData();

            formData.append('identificador',1);
            formData.append('adjunto', file.value);
            formData.append('proyecto_id',data.proyecto_id);
            formData.append('id',data.id);

            axios.post(me.url,formData
            ).then(function (response) {
              if (response.data.status) {
                toastr.success('Archivo Subido Correctamente');
                me.getData(me.principal);
              }
              else {
                swal({
                  type: 'error',
                  html: response.data.errors.join('<br>'),
                });
              }
            }).catch(function (error) {
              console.log(error);
            });
          }
          else if (file.dismiss === swal.DismissReason.cancel) {
          }
        })
      },

      /**
      * [cerrarRequisicion description]
      * @param  {Array}  [data=[]] [description]
      * @return {[type]}           [description]
      */
      cerrarRequisicion(data = [])
      {
        let me = this;
        axios.get('/vercantidadpartidas/' + data.id ).then(response => {
          // console.log(response.data.length);
          if (response.data.length == 0) {
            toastr.warning('Atención','Esta requisición no puede se cerrada hasta tener artículos registrados');
          }
          else {
            axios.get('/finalizarequisicion/'+data.id).then(function (response){
              toastr.success('Correcto','Requisición cerrada correctamente');
              me.getData(me.principal);
            }).catch(function (error){
              console.error(error);
            });
          }
        }).catch(function (error){
          console.error(error);
        })
      },

      maestroInicial(){
        this.$emit('requisiciones:click');
      }
    },
    mounted() {
    }
  }
  </script>
<style>

  .btn-uno {
    color: rgb(255, 255, 255);
    background-color: #f6a152;
    border-color: #f6a152;
    border-radius: 2px;
    }
  .btn-dos {
    color: rgb(0, 0, 0);
    background-color: #e9b443;
    border-color: #e9b443;
    border-radius: 2px;
    }
    /* no */
  .btn-tres {
    color: rgb(0, 0, 0);
    background-color: #8bbbef;
    border-color: #8bbbef;
    border-radius: 2px;
    }

  .btn-cuatro {
  color: rgb(255, 255, 255);
  background-color: #40d0d0;
  border-color: #40d0d0;
  border-radius: 2px;
  }
  .btn-cinco {
  color: rgb(255, 255, 255);
  background-color: #0d4da3;
  border-color: #0d4da3;
  border-radius: 2px;
  }
  /* no */
  .btn-seis {
  color: rgb(255, 255, 255);
  background-color: #0d98a3;
  border-color: #0d98a3;
  border-radius: 2px;
  }
  .btn-siete {
  color: rgb(255, 255, 255);
  background-color: #25a30d;
  border-color: #25a30d;
  border-radius: 2px;
  }
  .btn-ocho {
  color: rgb(255, 255, 255);
  background-color: #d04040;
  border-color: #d04040;
  border-radius: 2px;
  }
   .btn-nueve {
  color: rgb(0, 0, 0);
  background-color: #40d088;
  border-color: #40d088;
  border-radius: 2px;
  }

</style>
