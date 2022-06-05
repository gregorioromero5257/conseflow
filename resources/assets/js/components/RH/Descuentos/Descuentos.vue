<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->

      <br>
      <div class="card" v-show="!detalle && !detallepago">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Descuentos
          <button type="button" @click="abrirModal('descuentos','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="empleado.id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
              <button type="button" @click="cargardescuento(props.row.empleado)" class="dropdown-item">
                <i class="fas fa-eye"></i>&nbsp; Ver Descuentos
              </button>
              </div>
              </div>
              </div>
            </template>
          </v-client-table>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
      <!-- Listado de escolaridades del empleado -->
      <br>
      <div class="card" v-show="detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Descuentos pertenecientes a:  {{ empleado == null ? '' : empleado.nombre +' '+empleado.ap_paterno +' '+empleado.ap_materno }}
          <!-- <button type="button" @click="abrirModal('descuentos','registrar',empleado.id)" class="btn btn-primary float-sm-right">
          <i class="fas fa-plus"></i>&nbsp;Nuevo
        </button> -->
        <button type="button" @click="maestro()" class="btn btn-outline-info float-sm-right">
          <i class="fa fa-arrow-left"></i>&nbsp;Atras
        </button>
      </div>
      <div class="card-body">
        <vue-element-loading :active="isLoadingDetalle"/>
        <v-client-table :columns="columnsdescuento" :data="tableDatadescuento" :options="optionsdescuento" ref="myTabledescuento">
          <template slot="descuento.id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
            <button type="button" @click="abrirModal('descuentos','actualizar',props.row.descuento)" class="dropdown-item">
              <i class="icon-pencil"></i>&nbsp; Actualizar Descuento
            </button>
            <button type="button" @click="cargarpago(props.row.descuento)" class="dropdown-item">
              <i class="fas fa-eye"></i>&nbsp; Ver pagos
            </button>
              </div>
              </div>
            </div>
          </template>
        </v-client-table>
      </div>
    </div>
    <!-- Fin Listado de escolaridades del empleado -->
    <div class="card" v-show="detallepago">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Pagos pertenecientes a: {{descuento.e}} de la fecha: {{descuento.fecha}}
        <button type="button" @click="abrirModalPago('pago','registrar',pagosdescuentos.descuento_id,pagosdescuentos.numero_pagos,pagosdescuentos.cantidad)" class="btn btn-dark float-sm-right" :disabled="pagoboton === 1">
          <i class="fas fa-plus"></i>&nbsp;Nuevo pago
        </button>
        <button type="button" @click="maestrouno()" class="btn btn-outline-info float-sm-right">
          <i class="fa fa-arrow-left"></i>&nbsp;Atras
        </button>
      </div>
      <div class="card-body">
        <vue-element-loading :active="isLoadingDetallepago"/>
        <v-client-table :columns="columnspagos" :data="tableDatapagos" :options="optionspagos" ref="myTablepagos">
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
            <button type="button" @click="abrirModalPago('pago','actualizar',pagosdescuentos.descuento_id,pagosdescuentos.numero_pagos,pagosdescuentos.cantidad,props.row)" class="dropdown-item">
              <i class="icon-pencil"></i>&nbsp; Actualizar Pago
            </button>
              </div>
              </div>
            </div>
          </template>
        </v-client-table>
        
      </div>
    </div>
  </div>
  <!--Inicio del modal agregar/actualizar-->
  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalpago}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dark modal-lg" role="document">
      <div class="modal-content">
        <div>
          <vue-element-loading :active="isLoading"/>
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
            <input type="text" name="descuento_id" v-model="pagosdescuentos.descuento_id" class="form-control" id="descuento_id" hidden>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="fecha">Fecha</label>
              <div class="col-md-9">
                <input type="date"  name="fecha" v-model="pagosdescuentos.fecha" class="form-control" placeholder="Fecha" autocomplete="off" id="fecha">
                <span class="text-danger">{{ errors.first('fecha') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="cantidad">Cantidad</label>
              <div class="col-md-9">
                <input type="number"  name="cantidad" v-model="pagosdescuentos.cantidad" class="form-control" placeholder="Unidad" autocomplete="off" id="cantidad">
                <span class="text-danger">{{ errors.first('cantidad') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="numero_pago">Numero de pago</label>
              <div class="col-md-9">
                <input type="number" name="numero_pago" v-model="pagosdescuentos.numero_pagos" class="form-control"  id="numero_pago" disabled>
                <span class="text-danger">{{ errors.first('numero_pago') }}</span>
              </div>
            </div>
            <!-- </form> -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarpago(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarpago(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal-->
  <!--Inicio del modal agregar/actualizar-->
  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dark modal-lg" role="document">
      <div class="modal-content">
        <div>
          <vue-element-loading :active="isLoading"/>
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="empleado_id" v-bind:hidden="hiden == 1">Empleado</label>
              <div class="col-md-9">
                <v-select :options="optionsvs" v-model="descuento.empleado_id" label="name" v-validate="'required'" v-bind:hidden="hiden == 1"></v-select>
                <span class="text-danger">{{ errors.first('empleado_id') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="fecha">Fecha</label>
              <div class="col-md-9">
                <input type="date" v-validate="'required'" name="fecha" v-model="descuento.fecha" class="form-control" placeholder="Fecha" autocomplete="off" id="fecha">
                <span class="text-danger">{{ errors.first('fecha') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="unidad">Unidad</label>
              <div class="col-md-9">
                <input type="number" v-validate="'required'" name="unidad" v-model="descuento.unidad" class="form-control" placeholder="Unidad" autocomplete="off" id="unidad">
                <span class="text-danger">{{ errors.first('unidad') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="total">Total</label>
              <div class="col-md-9">
                <input type="number" v-validate="'required'" name="total" v-model="descuento.total" class="form-control" placeholder="Total" autocomplete="off" id="total">
                <span class="text-danger">{{ errors.first('total') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="tipo_descuento_id">Tipo Descuento</label>
              <div class="col-md-9">
                <select class="form-control" id="tipo_descuento_id"  name="tipo_descuento_id" v-model="descuento.tipo_descuento_id" v-validate="'excluded:0'" data-vv-as="Tipo Descuento" >
                  <option v-for="item in listaTipoDescuento" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                </select>
                <span class="text-danger">{{ errors.first('tipo_descuento_id') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="numero_pago">Número de pagos</label>
              <div class="col-md-9">
                <input type="number" name="numero_pago" v-model="descuento.numero_pago" class="form-control" placeholder="Número de pago" autocomplete="off" id="numero_pago" :disabled="disabled_input === 1">
                <span class="text-danger">{{ errors.first('numero_pago') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="observaciones">Observaciones</label>
              <div class="col-md-9">
                <textarea name="observaciones" v-model="descuento.observaciones" class="form-control"  autocomplete="off" id="observaciones"></textarea>
                <span class="text-danger">{{ errors.first('observaciones') }}</span>
              </div>
            </div>
            <!-- </form> -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardardescuento(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardardescuento(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal-->
</main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data (){
    return {
      url: '/descuento',
      empleado: null,
      descuentod: null,
      detalle: false,
      detallepago : false,
      pagoboton : 0,
      descuento: {
        id: 0,
        fecha: '',
        unidad : 0,
        total : 0,
        tipo_descuento_id : 0,
        empleado_id : '',
        numero_pago :0,
        observaciones :'',
        e : '',
      },
      pagosdescuentos:{
        id: 0,
        fecha : '',
        cantidad : '',
        numero_pagos : 0,
        descuento_id : 0,
      },
      pagoscount : 0,
      optionsvs: [],
      valuevs : [],
      listaEmpleados: [],
      listadescuento: [],
      listaTipoDescuento: [],
      tableDatapagostotal : [],
      modal : 0,
      modalpago : 0,
      hiden : 0,
      tituloModal : '',
      tipoAccion : 0,
      disabled: 0,
      disabled_input : 0,
      isLoading: false,
      isLoadingDetalle: false,
      isLoadingDetallepago: false,
      columns: ['empleado.id','empleado.nombre','empleado.ap_paterno','empleado.ap_materno','puesto.nombre','departamento.nombre',],
      tableData: [],
      columnsdescuento: ['descuento.id','descuento.fecha','descuento.unidad','descuento.total','c','descuento.observaciones','descuento.descuento', ],
      columnspagos: ['id','numero_pago','cantidad','fecha', ],
      columnstotal: [ 'totald','totalp','totalr'],
      tableDatadescuento: [],
      tableDatapagos: [],
      options: {
        headings: {
          'empleado.id': 'Acciones',
          'empleado.nombre': 'Nombre',
          'empleado.ap_paterno': 'Apellido Paterno',
          'empleado.ap_materno': 'Apellido Materno',
          'puesto.nombre': 'Puesto',
          'departamento.nombre': 'Departamento',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
        filterable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
        filterByColumn: true,
        texts:config.texts
      },
      optionsdescuento: {
        headings: {
          'descuento.id': 'Acciones',
          'descuento.fecha': 'Fecha',
          'descuento.unidad': 'Unidad',
          'descuento.total': 'Total',
          'c': 'Número de pagos',
          'descuento.observaciones' : 'Observaciones',
          'descuento.descuento': 'Tipo de Descuento',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['descuento.fecha'],
        filterable: ['descuento.fecha'],
        filterByColumn: true,
        listColumns: {
          condicion: config.columnCondicion
        },
        texts:config.texts
      },
      optionspagos: {
        headings: { 'id':'Acciones','numero_pago':'Número pago' },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha','unidad','total','descuento'],
        filterable: ['fecha','descuento'],
        filterByColumn: true,
        listColumns: {
          condicion: config.columnCondicion
        },
        texts:config.texts
      },
      optionspagost: {
        headings: { 'totald':'Total Descuento','totalp': 'Total pagado','totalr': 'Total restante' },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: [],
        filterable: [],
        filterByColumn: false,
        texts:config.texts
      },
    }
  },
  computed:{
  },
  methods : {
    getData() {
      let me=this;
      axios.get('/pagodescuento').then(response => {
        me.tableData = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getLista() {

      let me=this;
      axios.get('/empleado').then(response => {
        me.listaEmpleados = response.data;

      })
      .catch(function (error) {
        console.log(error);
      });
      axios.get('/empleado').then(response => {
        for (var i = 0; i < response.data.length; i++) {
          this.optionsvs.push(
            {
              id:response.data[i].empleado.id,
              name:response.data[i].empleado.nombre+' '+response.data[i].empleado.ap_paterno+' '+response.data[i].empleado.ap_materno,
              nombre:response.data[i].empleado.nombre,
              ap_paterno:response.data[i].empleado.ap_paterno,
              ap_materno:response.data[i].empleado.ap_materno,
              puesto:response.data[i].puesto.nombre
            });          }
          })

          .catch(function (error) {
            console.log(error);
          });
          axios.get('/tipodescuento').then(response => {
            me.listaTipoDescuento = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
        },
        guardarpago(nuevo){
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? '/pagodescuento' : '/pagodescuento'+'/'+this.pagosdescuentos.id,
            data: {
              'fecha':this.pagosdescuentos.fecha,
              'cantidad':this.pagosdescuentos.cantidad,
              'numero_pago':this.pagosdescuentos.numero_pago,
              'descuento_id':this.pagosdescuentos.descuento_id,
              'id':this.pagosdescuentos.id,
            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {

              if(!nuevo){
                toastr.success('Pago Actualizado Correctamente');
                me.cerrarModal();
                me.cargarpago(me.descuentod);
                this.hiden = 0;
              }
              else
              {
                toastr.success('Pago Registrado Correctamente');
                me.cerrarModal();
                me.cargarpago(me.descuentod);
              }
            } else {
              swal({
                type: 'error',
                html: response.data.errors.join('<br>'),
              });
            }
          }).catch(function (error) {
            console.log(error);
          });
        },
        guardardescuento(nuevo){
          var arreglo =this.descuento.empleado_id;
          this.$validator.validate().then(result => {
            if (result) {
              this.isLoading = true;
              let me = this;
              axios({
                method: nuevo ? 'POST' : 'PUT',
                url: nuevo ? me.url : me.url+'/'+this.descuento.id,
                data: {
                  'fecha':this.descuento.fecha,
                  'unidad':this.descuento.unidad,
                  'total':this.descuento.total,
                  'tipo_descuento_id':this.descuento.tipo_descuento_id,
                  'empleado_id':this.descuento.empleado_id.id,
                  'id':this.descuento.id,
                  'numero_pago': this.descuento.numero_pago,
                  'observaciones': this.descuento.observaciones
                }
              }).then(function (response) {
                me.isLoading = false;
                if (response.data.status) {
                  if(!nuevo){
                    toastr.success('Descuento Actualizado Correctamente');
                    me.cerrarModal();
                    me.cargardescuento(me.empleado);
                    this.hiden = 0;
                  }
                  else
                  {
                    toastr.success('Descuento Registrado Correctamente');
                    me.cerrarModal();
                    me.cargardescuento(arreglo);
                  }
                } else {
                  swal({
                    type: 'error',
                    html: response.data.errors.join('<br>'),
                  });
                }
              }).catch(function (error) {
                console.log(error);
                me.isLoading = false;
              });
            }
          });
        },
        cerrarModal(){
          this.modal=0;
          this.modalpago = 0 ;
          this.tituloModal='';
          this.hiden =0;
        },
        abrirModal(modelo, accion, data = []){
          switch(modelo){
            case "descuentos":
            {
              switch(accion){
                case 'registrar':
                {
                  this.modal = 1;
                  this.tituloModal = 'Registrar Descuento';
                  Utilerias.resetObject(this.descuento);
                  this.tipoAccion = 1;
                  this.disabled=0;
                  this.disabled_input =0;
                  break;
                }
                case 'actualizar':
                {
                  Utilerias.resetObject(this.descuento);
                  this.modal=1;
                  this.tituloModal= 'Actualizar Descuento';
                  this.tipoAccion=2;
                  this.disabled=1;
                  this.disabled_input = 1;
                  this.hiden = 1;
                  this.descuento.id=data['id'];
                  this.descuento.empleado_id = data['empleado_id'];
                  this.descuento.tipo_descuento_id = data['tipo_descuento_id'];
                  this.descuento.fecha = data['fecha'];
                  this.descuento.unidad=data['unidad'];
                  this.descuento.total=data['total'];
                  this.descuento.numero_pago = data['numero_pago'];
                  this.descuento.observaciones = data['observaciones'];
                  break;
                }
              }
            }
          }
        },
        abrirModalPago(modelo, accion, id,npago,cantidad,data = []){
          switch(modelo){
            case "pago":
            {
              switch(accion){
                case 'registrar':
                {
                  this.modalpago = 1;
                  this.tituloModal = 'Registrar Pago';
                  this.pagosdescuentos.descuento_id = id;
                  this.pagosdescuentos.numero_pago = npago;
                  this.pagosdescuentos.cantidad = cantidad;
                  let me = this;
                  me.horaActual();
                  this.tipoAccion = 1;
                  this.disabled=0;
                  break;
                }
                case 'actualizar':
                {
                  this.modalpago=1;
                  this.tituloModal= 'Actualizar Pago';
                  this.tipoAccion=2;
                  this.pagosdescuentos.id=data['id'];
                  this.pagosdescuentos.fecha = data['fecha'];
                  this.pagosdescuentos.cantidad = data['cantidad'];
                  this.pagosdescuentos.numero_pagos = data['numero_pago'];
                  this.pagosdescuentos.descuento_id=data['descuento_id'];
                  break;
                }
              }
            }
          }
        },
        cargardescuento(dataEmpleado = []) {
          this.detalle = true;
          this.isLoadingDetalle = true;
          let me=this;
          this.empleado = dataEmpleado;
          this.descuento.e= dataEmpleado.nombre+' '+dataEmpleado.ap_paterno+' '+dataEmpleado.ap_materno;
          axios.get(me.url + '/' + dataEmpleado.id + '/' +'descuento').then(response => {
            me.tableDatadescuento = response.data;
            me.isLoadingDetalle = false;
          })
          .catch(function (error) {
            console.log(error);
          });
        },
        horaActual(){
          var hoy = new Date ();
          var dd = hoy.getDate();
          var mm = hoy.getMonth()+1; //hoy es 0!
          var yyyy = hoy.getFullYear();
          if(dd<10) {  dd='0'+dd  }
          if(mm<10) {  mm='0'+mm  }
          hoy = yyyy+'-'+mm+'-'+dd;
          this.pagosdescuentos.fecha = hoy;
          this.descuento.fecha = hoy;
        },
        cargarpago(dataEmpleado = []) {
          this.detallepago = true;
          this.detalle = false;
          this.isLoadingDetallepago = true;
          let me=this;
          this.descuentod = dataEmpleado;
          this.descuento.fecha = dataEmpleado.fecha;
          var total = parseFloat(dataEmpleado.total);
          var resultado =(total / dataEmpleado.numero_pago);
          this.pagosdescuentos.cantidad = resultado.toFixed(2);
          this.pagosdescuentos.descuento_id = dataEmpleado.id;
          this.pagosdescuentos.numero_pagos = 1;
          axios.get('/pagodescuento/'+dataEmpleado.id)  .then(response => {
            me.tableDatapagos = response.data.pago;
            me.tableDatapagostotal = response.data.detalle;
            if (response.data.detalle[0].totalp === response.data.pago[0].Dtotal
              ||dataEmpleado.numero_pago === response.data.pago.length) {
                this.pagoboton = 1;
              }else {this.pagoboton =0; }
              this.pagosdescuentos.numero_pagos = response.data.pago.length + 1;
              me.isLoadingDetallepago = false;
            })
            .catch(function (error) {
              me.isLoadingDetallepago = false;
            });
          },
          maestro(){
            this.detalle = false;
            this.pagoboton =0;
          },
          maestrouno(){
            let me= this;
            me.cargardescuento(me.empleado);
            this.detallepago = false;
            this.detalle=true
            this.pagoboton =0;
          }
        },
        mounted() {
          this.getData();
          this.getLista();
        }
      }
      </script>
