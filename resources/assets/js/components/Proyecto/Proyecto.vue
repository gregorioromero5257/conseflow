<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card">
        <!-- Modal PDF-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalPDF}" style="display: none;" data-focus-on="input:first">
          <div class="modal-dialog modal-dark modal-el">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" >Documento P.O.</h4>
                <button type="button" class="close" @click="cerrarModalPDF()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-row">
                  <div class="col-md-4">
                    <div class="form-row">
                      <div class="form-group col-md-8">
                        <label><b>Documento</b></label>
                      </div>
                      <div class="form-group col-md-4">
                        <label><b>ACCIONES</b></label>
                      </div>
                    </div>
                    <li v-for="(vi, index) in tableDocumentosProyectos" class="list-group-item">
                      <div class="form-row">

                        <div class="form-group col-md-8">
                          <label>{{vi.documento}}</label>
                        </div>
                        <div class="form-group col-md-4">

                          <a @click="deleteu(vi)">
                            <span class="fas fa-trash" arial-hidden="true"></span>
                          </a>
                          <a @click="verdocumentos(vi)">
                            <span class="fas fa-eye" arial-hidden="true"></span>
                          </a>
                        </div>
                      </div>
                    </li>
                  </div>
                  <div class="col-md-8">
                    <!-- <div style="text-align: center;"> -->
                      <iframe :src="this.nombre_archivo" width="100%" height="700" frameborder="0"></iframe>
                    <!-- </div> -->
                  </div>
                </div>



                <!-- {{this.nombre_archivo}} -->

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  @click="cerrarModalPDF()">Cerrar</button>
              </div>
            </div>
          </div>
        </div>

        <boton-nuevo v-show="PermisosCrud.Create" v-bind:arreglo="arreglo" v-on:abrir="abrirModal($event,args)"></boton-nuevo>
        <div >
          <download-excel
          class   = "btn btn-default"
          :data   = "json_data"
          :fields = "json_fields"
          name    = "proyectos.xls">
          <button v-show="PermisosCrud.Download" type="button" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel"></i>&nbsp;Exportar a Excel
          </button>
        </download-excel>
      </div>
      <div class="card-body">
        <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
          <template slot="condicion" slot-scope="props" >
            <template v-if="props.row.condicion == 1">
              <button type="button" class="btn btn-outline-success">Activo</button>
            </template>
            <template v-if="props.row.condicion == 0">
              <button type="button" class="btn btn-outline-danger">Terminado</button>
            </template>
            <template v-if="props.row.condicion == 2">
              <button type="button" class="btn btn-outline-warning">Pausa</button>
            </template>
            <template v-if="props.row.condicion == 3">
              <button type="button" class="btn btn-outline-dark">Rechazado</button>
            </template>
          </template>
          <template slot="adicional" slot-scope="props">
            <template v-if="props.row.adicional == 0">
              <button type="button" class="btn btn-find" data-toggle="tooltip" data-placement="top" :title="props.row" >
                Principal
              </button>
            </template>
            <template v-if="props.row.adicional == 1">
              <button type="button" class="btn btn-greg" data-toggle="tooltip" data-placement="top" :title="props.row" >
                Adicional
              </button>
            </template>
          </template>
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                  <button v-show="PermisosCrud.Upload" class="dropdown-item" @click="subirDocumento(props.row,1)">
                    <i class="fas fa-file-upload"></i>&nbsp;Subir Documento P.O.
                  </button>

                  <!-- <template v-if="props.row.documento_po != null">
                  <a v-show="PermisosCrud.Upload" class="dropdown-item" @click="subirDocumento(props.row,2)" href="#">
                  <i class="fas fa-file-upload"></i>&nbsp;Actualizar Documento P.O.
                </a>
              </template> -->

              <button v-show="PermisosCrud.Download" class="dropdown-item" @click="abrirModalPDF(props.row.id)" >
                <i class="fas fa-eye"></i>&nbsp;Visualizar Documento P.O.
              </button>

              <button v-show="PermisosCrud.Update" type="button" @click="abrirModal(['proyecto','actualizar',props.row])" class="dropdown-item" >
                <i class="icon-pencil"></i>&nbsp; Actualizar
              </button>
              <template v-if="props.row.condicion == 1">
                <button v-show="PermisosCrud.Delete" class="dropdown-item" @click="actdesact(props.row.id,0)" >
                  <i class="fas fa-flag"></i>&nbsp; Terminar
                </button>
                <button v-show="PermisosCrud.Read" class="dropdown-item" @click="pausar(props.row.id)" >
                  <i class="fas fa-pause"></i>&nbsp; Pausar
                </button>
                <button v-show="PermisosCrud.Delete" class="dropdown-item" @click="rechazar(props.row.id)" >
                  <i class="fas fa-backspace"></i>&nbsp; Rechazar
                </button>
              </template>
              <template v-else>
                <button class="dropdown-item" @click="actdesact(props.row.id,1)" >
                  <i class="icon-check"></i>&nbsp; Activar
                </button>
              </template>
            </div>
          </div>
        </div>

      </template>
    </v-client-table>
  </div>
</div>
<!-- Fin ejemplo de tabla Listado -->
</div>
<!--Inicio del modal agregar/actualizar-->
<div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">

  <div class="modal-dialog modal-dark modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" v-text="tituloModal"></h4>
        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
          <div class="form" v-if="tipoAccion==1">
            <div class="form-group row">
              <label class="col-md-2 " for="text-input">Nombre</label>
              <div class="col-md-4">
                <input type="text" v-validate="'required'" :maxlength="500" name="nombre" v-model="nombre" class="form-control" placeholder="Nombre">
                <span class="text-danger">{{ errors.first('nombre') }}</span>
              </div>
              <label class="col-md-2 " for="text-input">Agrupador</label>
              <div class="col-md-2">
                <!-- <input type="text" v-validate="'required'" name="nombre_corto" v-model="nombre_corto" class="form-control" placeholder="Nombre Corto"> -->
                <input class="form-control form-check-input" type="checkbox" v-model="agrupador" id="agrupador" @change="deshabilitarAgrupador($event)">
                <!-- <span class="text-danger">{{ errors.first('nombre_corto') }}</span> -->
              </div>
            </div>
            <template v-if="deshabilitar">
              <div class="form-group row">
                <label class="col-md-2 " for="text-input">Orden Compra</label>
                <div class="col-md-4">
                  <input type="text" v-validate="'required|max:30'" name="oc" v-model="clave"  class="form-control" placeholder="Orden compra">
                  <span class="text-danger">{{ errors.first('oc') }}</span>
                </div>
                <label class="col-md-2 " for="text-input">Monto Total de la Orden Compra</label>
                <div class="col-md-4">
                  <input type="text" v-validate="'required|max:14|decimal:2'" name="monto_total" v-model="monto_total" class="form-control" placeholder="Monto Total de la Orden de Compra">
                  <span class="text-danger">{{ errors.first('monto_total') }}</span>
                </div>

              </div>


              <div class="form-group row">
                <label class="col-md-2 " for="text-input">Nombre Corto</label>
                <div class="col-md-4">
                  <input type="text" v-validate="'required|max:60'" name="nombre_corto" v-model="nombre_corto" class="form-control" placeholder="Nombre Corto">
                  <span class="text-danger">{{ errors.first('nombre_corto') }}</span>
                </div>
                <label class="col-md-2 " for="text-input">Ciudad</label>
                <div class="col-md-4">
                  <input type="text" v-validate="'required|max:125'" name="ciudad" v-model="ciudad" class="form-control" placeholder="Ciudad">
                  <span class="text-danger">{{ errors.first('ciudad') }}</span>
                </div>

              </div>
              <div class="form-group row">
                <label class="col-md-2 " for="text-input">Fecha Inicio</label>
                <div class="col-md-4">
                  <input type="date" v-validate="'required'" name="fecha_inicio" v-model="fecha_inicio" class="form-control" placeholder="Fecha de inicio">
                  <span class="text-danger">{{ errors.first('fecha_inicio') }}</span>
                </div>

                <label class="col-md-2 " for="text-input">Fecha Fin</label>
                <div class="col-md-4">
                  <input type="date" v-validate="'required'" name="fecha_fin" v-model="fecha_fin" class="form-control" placeholder="Fecha de finalización" @change="validar">
                  <span class="text-danger">{{ errors.first('fecha_fin') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2 " for="text-input">Subcategoria</label>
                <div class="col-md-10">
                  <select class="form-control" id="subcategoria_id" name="subcategoria_id" v-model="subcategoria_id" v-validate="'excluded:0'" data-vv-as="Subcategoria">
                    <option v-for="item in listaSubcategorias" :value="item.id" :key="item.id">{{item.nombre}} </option>
                  </select>
                  <span class="text-danger">{{ errors.first('subcategoria_id') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2 " for="text-input">Cliente</label>
                <div class="col-md-10">
                  <select class="form-control" id="cliente_id"  name="cliente_id" v-model="cliente_id" v-validate="'excluded:0'" data-vv-as="Cliente">
                    <option v-for="item in listaClientes" :value="item.id" :key="item.id">{{item.nombre}} </option>
                  </select>
                  <span class="text-danger">{{ errors.first('cliente_id') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2 " for="text-input">PM Cliente</label>
                <div class="col-md-10">
                  <input type="text" v-validate="'required|max:250'" name="pm_cliente" v-model="pm_cliente" class="form-control" placeholder="PM Cliente">
                  <span class="text-danger">{{ errors.first('pm_cliente') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2 " for="text-input">PM Interno</label>
                <div class="col-md-10">
                  <input type="text" v-validate="'required|max:250'" name="pm_interno" v-model="pm_interno" class="form-control" placeholder="PM Interno">
                  <span class="text-danger">{{ errors.first('pm_interno') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-2 " for="text-input">Asignar</label>
                <div class="col-md-2">
                  <!-- <input type="text" v-validate="'required'" name="nombre_corto" v-model="nombre_corto" class="form-control" placeholder="Nombre Corto"> -->
                  <input class="form-control form-check-input" type="checkbox" v-model="adicional" id="adicional">
                  <!-- <span class="text-danger">{{ errors.first('nombre_corto') }}</span> -->
                </div>
                <label class="col-md-2 " for="text-input">Proyecto Principal</label>
                <div class="col-md-6">
                  <select class="form-control" id="proyecto_id" name="proyecto_id" v-model="proyecto_id" data-vv-name="Proyecto" :disabled="!adicional">
                    <!--<option v-for="item in tableData" :value="item.id" :key="item.id">{{item.nombre_corto}} </option>-->
                    <option v-for="item in listarProyAgrp" :value="item" :key="item.id">{{item.nombre}} </option>
                  </select>
                </div>
              </div>
            </template>
          </div>

          <div class="form" v-if="tipoAccion==2">
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="text-input"></label>
              <div class="col-md-9">
                <input type="hidden" v-model="id" class="form-control">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 " for="text-input">Nombre</label>
              <div class="col-md-4">
                <input type="text" v-validate="'required'" :maxlength="500" name="nombre" v-model="nombre" class="form-control" placeholder="Nombre">
                <span class="text-danger">{{ errors.first('nombre') }}</span>
              </div>
              <label class="col-md-2 " for="text-input">Orden compra</label>
              <div class="col-md-4">
                <input type="text" v-validate="'required|max:30'" name="oc" v-model="clave" class="form-control" placeholder="Orden compra">
                <span class="text-danger">{{ errors.first('oc') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 " for="text-input">Monto Total de la Orden Compra</label>
              <div class="col-md-4">
                <input type="text" v-validate="'required|max:14|decimal:2'" name="monto_total" v-model="monto_total" class="form-control" placeholder="Monto Total">
                <span class="text-danger">{{ errors.first('monto_total') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 " for="text-input">Nombre Corto</label>
              <div class="col-md-4">
                <input type="text"  name="nombre_corto" v-model="nombre_corto" class="form-control" placeholder="Nombre Corto">
                <span class="text-danger">{{ errors.first('nombre_corto') }}</span>
              </div>
              <label class="col-md-2 " for="text-input">Ciudad</label>
              <div class="col-md-4">
                <input type="text" v-validate="'required|max:125'" name="ciudad" v-model="ciudad" class="form-control" placeholder="Ciudad">
                <span class="text-danger">{{ errors.first('ciudad') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 " for="text-input">Fecha Inicio</label>
              <div class="col-md-4">
                <input type="date" v-validate="'required'" name="fecha_inicio" v-model="fecha_inicio" class="form-control" placeholder="Fecha de inicio">

              </div>

              <label class="col-md-2 " for="text-input">Fecha Fin</label>
              <div class="col-md-4">
                <input type="date" v-validate="'required'" name="fecha_fin" v-model="fecha_fin" class="form-control" placeholder="Fecha de finalización" @change="validar">

              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 " for="text-input">Subcategoria</label>
              <div class="col-md-10">
                <select class="form-control" id="subcategoria_id" name="subcategoria_id" v-model="subcategoria_id" v-validate="'excluded:0'" data-vv-as="Subcategoria">
                  <option v-for="item in listaSubcategorias" :value="item.id" :key="item.id">{{item.nombre}} </option>
                </select>
                <span class="text-danger">{{ errors.first('subcategoria_id') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 " for="text-input">Cliente</label>
              <div class="col-md-10">
                <select class="form-control" id="cliente_id"  name="cliente_id" v-model="cliente_id" v-validate="'excluded:0'" data-vv-as="Cliente">
                  <option v-for="item in listaClientes" :value="item.id" :key="item.id">{{item.nombre}} </option>
                </select>
                <span class="text-danger">{{ errors.first('cliente_id') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 " for="text-input">PM Cliente</label>
              <div class="col-md-10">
                <input type="text" v-validate="'required|max:250'" name="pm_cliente" v-model="pm_cliente" class="form-control" placeholder="PM Cliente">
                <span class="text-danger">{{ errors.first('pm_cliente') }}</span>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-2 " for="text-input">PM Interno</label>
              <div class="col-md-10">
                <input type="text" v-validate="'required|max:250'" name="pm_interno" v-model="pm_interno" class="form-control" placeholder="PM Interno">
                <span class="text-danger">{{ errors.first('pm_interno') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 " for="text-input">Proyecto Principal</label>
              <div class="col-md-2">
                <!-- <input type="text" v-validate="'required'" name="nombre_corto" v-model="nombre_corto" class="form-control" placeholder="Nombre Corto"> -->
                <input class="form-control form-check-input" type="checkbox" v-model="adicional" id="adicional">
                <!-- <span class="text-danger">{{ errors.first('nombre_corto') }}</span> -->
              </div>
              <label class="col-md-2 " for="text-input">Proyecto</label>
              <div class="col-md-6">
                <select class="form-control" id="proyecto_id" name="proyecto_id" v-model="proyecto_id" data-vv-name="Proyecto" :disabled="!adicional">
                  <option v-for="item in tableData" :value="item.id" :key="item.id">{{item.nombre_corto}} </option>
                </select>
              </div>
            </div>
          </div>
          <div v-show="error" class="form-group row div-error">
            <div class="text-center text-error">
              <div v-for="error in errorMostrarMsj" :key="error" v-text="error">

              </div>
            </div>
          </div>
        </form>
      </div>
      <template v-if="deshabilitar">
        <div class="modal-footer">
          <vue-element-loading :active="isLoading"/>
          <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
          <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="registrar()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
          <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="actualizar()"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
        </div>
      </template>
      <template>
        <div class="modal-footer" v-if="!deshabilitar">
          <button type="button" class="btn btn-secondary" @click="guardarAgrupador()"><i class="fas fa-save"></i> Guardar</button>
        </div>
      </template>


    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!--Fin del modal-->
</main>
</template>

<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  //NOTA muy importante para exportar a excel
  computed:{
    updated: function(){
      return this.exportar(this.$refs.myTable.allFilteredData);
    },
  },
  data (){
    return {
      proyecto_documento_id : 0,
      PermisosCrud : {},
      modalPDF: 0,
      nombre_archivo: '',
      nombre_archivo2: '',
      url: '/proyecto',
      modal : 0,
      listaClientes: 0,
      listaSubcategorias: [],
      isLoading : false,
      swal_titulo:'',
      swal_msg :'',
      swal_tle :'',

      id:0,
      nombre : '',
      nombre_corto: '',
      clave : '',
      monto_total: 0,
      ciudad : '',
      fecha_inicio : '',
      fecha_fin : '',
      cliente_id : 0,
      proyecto_id: 0,
      condicional: 0,
      pm_cliente: '',
      pm_interno: '',
      adicional: false,
      agrupador: false,
      listarProyAgrp:[],
      subcategoria_id: 0,
      deshabilitar: true,
      tituloModal : '',
      tipoAccion : 0,
      fechavalidar: 0,
      error : 0,
      errorMostrarMsj : [],
      tableDocumentosProyectos : [],
      arreglo :{
        titulo: 'Proyectos',
        emit:   'abrirModal',
        modulo: 'proyecto',
        accion: 'registrar',
      },
      json_fields : {
        'nombre' : 'String',
        'nombre_corto': 'String',
        'clave' : 'String',
        'monto_total' : 'String',
        'ciudad' : 'String',
        'condicion' : 'String',
        'fecha_inicio' : 'String',
        'fecha_fin' : 'String',
        'updated_at' : 'String'
      },
      json_data : [],
      json_meta: [
        [{
          "key": "charset",
          "value": "utf-8"
        }]
      ],
      columns: [
        'id',
        'nombre',
        'nombre_corto',
        'clave',
        'monto_total',
        'ciudad',
        'fecha_inicio',
        'fecha_fin',
        'adicional',
        'nombre_proyecto',
        'condicion',
        'updated_at'
      ],
      tableData: [],
      options: {
        headings: {
          'id': 'Acciones',
          'nombre': 'Nombre',
          'nombre_corto': 'Nombre corto',
          'clave': 'Ord Comp',
          'monto_total': 'Monto Total de OC',
          'ciudad': 'Ciudad',
          'fecha_inicio': 'F. Inicio',
          'fecha_fin': 'F. Fin',
          'condicion':'Condición',
          'adicional': 'P/A',
          'updated_at' : 'Ultima Actualización',
          'nombre_proyecto' : 'Proyecto Padre'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: [
          'nombre',
          'nombre_corto',
          'clave',
          'monto_total',
          'ciudad',
          'fecha_inicio',
          'fecha_fin',
          'condicion',
          'adicional',
          'updated_at'
        ],
        filterable: [
          'nombre',
          'nombre_corto',
          'clave',
          'monto_total',
          'ciudad',
          'fecha_inicio',
          'fecha_fin',
          'condicion',
          'adicional',

        ],
        filterByColumn: true,
        listColumns: {
          'condicion': [{
            id: 1,
            text: 'Activo'
          },
          {
            id: 0,
            text: 'Terminado'
          },
          {
            id: 2,
            text: 'Pausa'
          },
          {
            id: 3,
            text: 'Rechazado'
          }
        ],
        'adicional': [{
          id: 1,
          text: 'Adicional'
        },
        {
          id: 0,
          text: 'Principal'
        },]

      },

      texts:config.texts
    },
  }
},
methods : {
  validar(){
    var a = this.fecha_inicio;
    var b = this.fecha_fin;
    var fechaInicio = new Date(a).getTime();
    var fechaFin    = new Date(b).getTime();
    var diff = fechaFin - fechaInicio;
    var diferencia =diff/(1000*60*60*24);
    if (diferencia < 0) {
      this.fechavalidar = 1;
      toastr.error('La fecha de finalizacion del Proyecto no debe ser menor a la fecha de Inicio');
    }
    else{
      this.fechavalidar = 0;

    }
  },
  horaActual(){
    var hoy = new Date ();
    var dd = hoy.getDate();
    var mm = hoy.getMonth()+1; //hoy es 0!
    var yyyy = hoy.getFullYear();
    if(dd<10) {  dd='0'+dd   }
    if(mm<10) {  mm='0'+mm   }
    hoy = yyyy+'-'+mm+'-'+dd;
    this.fecha_inicio = hoy;
  },
  listar(){
    let me=this;
    this.PermisosCrud = Utilerias.getCRUD(this.$route.path)
    axios.get('/proyecto-listar-todos').then(response => {
      me.tableData = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });

    axios.get('/clientes').then(response => {
      me.listaClientes = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });

    axios.get('/listaAgrupador').then(response=>{
      me.listarProyAgrp = response.data;
      console.log(response.data);
    }).catch(function(error){
      console.log(error);
    });

    // axios.get('/proyecto/sum/').then(response => {
    //     me.listaClientes = response.data;
    // })
    // .catch(function (error) {
    //     console.log(error);
    // });
  },

  subirDocumento(data,metodo){
    swal({
      title: 'Documento P.O.',
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

        formData.append('metodo',metodo);
        formData.append('documento_po', file.value);
        formData.append('id',data.id);

        axios.post('/proyecto/subir/documento/',formData
      ).then(function (response) {
        if (response.data.status) {
          if (metodo == 1) {
            toastr.success('Archivo Subido Correctamente');
          }
          if (metodo == 2) {
            toastr.success('Archivo Actualizado Correctamente');
          }
          me.listar();
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


registrar(){
  if (this.adicional == true && this.proyecto_id == 0) {
    toastr.error('Seleccionar proyecto padre.');
    return;
  }

  if (this.fechavalidar == 0) {
    this.$validator.validate().then(result => {
      if (result) {
        this.isLoading = true;
        let me = this;
        axios.post(me.url,{
          'metodo' : 0,
          'nombre' : this.nombre,
          'nombre_corto': this.nombre_corto,
          'clave' : this.clave,
          'monto_total' : this.monto_total,
          'ciudad' : this.ciudad,
          'fecha_inicio' : this.fecha_inicio,
          'fecha_fin' : this.fecha_fin,

          'cliente_id': this.cliente_id,
          'pm_cliente': this.pm_cliente,
          'pm_interno': this.pm_interno,
          'adicional': this.adicional,
          'proyecto_id': (this.adicional) ? this.proyecto_id.id : null,
          'proyecto_subcategorias_id': this.subcategoria_id,
        }).then(function (response) {
          me.isLoading = false;
          me.cerrarModal();
          me.listar();
          toastr.success('Proyecto Registrado Correctamente')
        }).catch(function (error) {
          console.log(error);
        });
      }
    });
  }
},
actualizar(){
  if (this.fechavalidar == 0) {
    this.$validator.validate().then(result => {
      if (result) {
        this.isLoading = true;

        let me = this;
        axios.put(me.url+'/'+this.id,{
          'nombre' : this.nombre,
          'nombre_corto': this.nombre_corto,
          'clave' : this.clave,
          'monto_total' : this.monto_total,
          'ciudad' : this.ciudad,
          'fecha_inicio' : this.fecha_inicio,
          'fecha_fin' : this.fecha_fin,
          'cliente_id': this.cliente_id,
          'pm_cliente': this.pm_cliente,
          'pm_interno': this.pm_interno,
          'adicional': this.adicional,
          'proyecto_id': (this.adicional) ? this.proyecto_id : null,
          'proyecto_subcategorias_id': this.subcategoria_id,
        }).then(function (response) {
          me.isLoading = false;

          me.cerrarModal();
          me.listar();
          toastr.success('Proyecto Actualizado Correctamente')
        }).catch(function (error) {
          console.log(error);
        });
      }
    });
  }
},
getListaSubcategorias() {
  let me=this;
  axios.get('/prosubcategoria/getlist').then(response => {
    me.listaSubcategorias = response.data;
  })
  .catch(function (error) {
    console.log(error);
  });
},
actdesact(id,activar){
  if(activar){
    this.swal_titulo = 'El proyecto ha finalizado, desea activarlo nuevamente?';
    this.swal_tle = 'Activado';
    this.swal_msg = 'Proyecto activado con éxito.';
  }else{
    this.swal_titulo = 'Esta seguro que el proyecto concluyó?';
    this.swal_tle = 'Desactivado!';
    this.swal_msg = 'Proyecto finalizado.';
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
        toastr.options = {
          "closeButton": false,
          "closeButton": false,
          "debug": false,
          "newestOnTop": true,
          "progressBar": true,
          "positionClass": "toast-top-center",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }
        me.listar();
      }).catch(function (error) {
        console.log(error);
      });
    } else if (
      result.dismiss === swal.DismissReason.cancel
    ) {

    }
  })
},
pausar(id){
  this.swal_titulo = 'Esta seguro de pausar el proyecto?';
  this.swal_tle = 'Pausa!';
  this.swal_msg = 'Proyecto pausado.';

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
      axios.get('proyecto-pausar/'+id).then(function (response) {
        toastr.error(me.swal_tle,me.swal_msg,'error');

        toastr.options = {
          "closeButton": false,
          "closeButton": false,
          "debug": false,
          "newestOnTop": true,
          "progressBar": true,
          "positionClass": "toast-top-center",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }
        me.listar();
      }).catch(function (error) {
        console.log(error);
      });
    } else if (
      result.dismiss === swal.DismissReason.cancel
    ) {
    }
  })
},
rechazar(id){
  this.swal_titulo = 'Esta seguro de rechazar el proyecto?';
  this.swal_tle = 'Rechazado!';
  this.swal_msg = 'Proyecto rechazado.';

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
      axios.get('proyecto-rechazar/'+id).then(function (response) {
        toastr.error(me.swal_tle,me.swal_msg,'error');

        toastr.options = {
          "closeButton": false,
          "closeButton": false,
          "debug": false,
          "newestOnTop": true,
          "progressBar": true,
          "positionClass": "toast-top-center",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }
        me.listar();
      }).catch(function (error) {
        console.log(error);
      });
    } else if (
      result.dismiss === swal.DismissReason.cancel
    ) {
    }
  })
},
exportar(datos){
  var condicion = '';
  var sexo = '';
  this.json_data=[];
  this.json_data.push(
    {
      'nombre':'Nombre',
      'nombre_corto':'Nombre Corto',
      'clave':'Clave',
      'monto_total':'Monto Total',
      'ciudad':'Ciudad',
      'condicion':'Estado',
      'fecha_fin':'F. Inicio',
      'fecha_inicio':'F. Fin',
      'updated_at':'Actualizado por ultima vez'
    });
    datos.forEach(dato =>{
      this.json_data.push(
        {
          'nombre':dato.nombre,
          'nombre_corto':dato.nombre_corto,
          'clave':dato.clave,
          'monto_total' :dato.monto_total,
          'ciudad':dato.ciudad,
          'fecha_inicio':dato.fecha_inicio,
          'condicion':condicion,
          'fecha_fin':dato.fecha_fin,
          'updated_at':dato.updated_at
        });
      });
      return this.json_data;
    },
    cerrarModal(){
      this.modal=0;
      this.tituloModal='';
    },


    abrirModalPDF(id){
      this.proyecto_documento_id = id;
      axios.get('/proyecto-obtener-documentos/'+ id).then(response => {
        //console.log(response.data);
        this.tableDocumentosProyectos = response.data;
      }).catch(error => {
        console.error(error);
      });
      this.modalPDF = 1;
    },

    cerrarModalPDF(){
      var method = 2;
      axios.get('/proyecto-delete-temporal/'+ this.proyecto_documento_id).then(response => {
        //console.log(response.data);
      }).catch(error => {
        console.error(error);
      });
      this.nombre_archivo = '';
      // this.nombre_archivo2 = '';
      this.modalPDF = 0;
    },
    deshabilitarAgrupador(data){
      //console.log(data);

      if(data.target.checked){
        this.deshabilitar = false;

      }else{
        this.deshabilitar = true;
      }
    },

    guardarAgrupador(){
      this.isLoading = true;
      let me = this;
      axios({
        method: 'POST',
        url: '/guardarAgrupador',
        data:{
          'nombre' : this.nombre,
        }
      }).then(function (response){
        me.isLoading= false;
        me.tableData=response.data;
        me.cerrarModal();
        me.listar();
        toastr.success('Proyecto Registrado Correctamente')
      }).catch(function (error) {
        console.log(error);
      });
    },




    abrirModal(modelo, accion=[]){
      var data =  modelo[2];
      switch(modelo[0]){
        case "proyecto":
        {
          switch(modelo[1]){
            case 'registrar':
            {
              this.modal = 1;

              this.tituloModal = 'Registrar Proyecto';
              this.proyecto = '';
              this.nombre = '';
              this.nombre_corto = '';
              this.clave = '';
              this.monto_total = '';
              this.ciudad = '';
              this.fecha_inicio = '';
              this.fecha_fin = '';
              this.horaActual();
              this.tipoAccion = 1;
              this.cliente_id = 0;
              this.pm_cliente = '';
              this.pm_interno = '';
              this.adicional = false;
              this.proyecto_id = 0;
              this.subcategoria_id = 0;
              break;
            }
            case 'actualizar':
            {
              this.modal=1;
              this.tituloModal='Actualizar Proyecto';
              this.id = data.id,
              this.nombre = data.nombre,
              this.nombre_corto = data.nombre_corto,
              this.clave = data.clave,
              this.monto_total = data.monto_total,
              this.ciudad = data.ciudad,
              this.fecha_inicio = data.fecha_inicio,
              this.fecha_fin = data.fecha_fin,
              this.tipoAccion=2;
              this.cliente_id = data.cliente_id;
              this.pm_cliente = data.pm_cliente;
              this.pm_interno = data.pm_interno;
              this.adicional = data.adicional;
              this.proyecto_id = data.proyecto_id;
              this.subcategoria_id = data.proyecto_subcategorias_id;
              break;
            }
          }
        }
      }
    },

    deleteu(data){
      Swal.fire({
        title: 'Esta seguro de eliminar?',
        text: "No se podra revertir!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
      }).then((result) => {
        if (result.value) {
          axios.get('delete/documento/proyecto/' + data.id).then(response => {
            toastr.success('Eliminado Correctamente !!!');
            this.abrirModalPDF(this.proyecto_documento_id);
          }).catch(e => {
            console.error(e);
          });
        }
      });
    },

    verdocumentos(data){
      // console.log(data);
      axios.post('proyecto-obtenerarchivos/',{
        id : data.id,
      }).then(response => {
        console.log(response.data);
        this.nombre_archivo = 'temp/' + response.data;
      }).catch(e => {
        console.error(e);
      })
    }

  },
  mounted() {
    this.listar();
    this.horaActual();
    this.getListaSubcategorias();

  }
}
</script>
<style>

.btn-greg {
  color: #fff;
  background-color: #2095d8;
  border-color: #2095d8;; }

  .modal-el {
    max-width: 1200px;
  }

  </style>
