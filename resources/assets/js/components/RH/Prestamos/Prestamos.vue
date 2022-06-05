<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->

      <br>
      <div class="card" v-show="!detalle && !detallepago">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Préstamos
          <button v-show="PermisosCRUD.Create" type="button" @click="abrirModal('descuentos','registrar')" class="btn btn-dark float-sm-right">
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
              <button v-show="PermisosCRUD.Update" type="button" @click="cargarprestamo(props.row.empleado)" class="dropdown-item">
                <i class="fas fa-eye"></i>&nbsp; Ver prestamos del Empleado
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
          <i class="fa fa-align-justify"></i> Préstamos pertenecientes a:  {{ empleado == null ? '' : empleado.nombre +' '+empleado.ap_paterno +' '+empleado.ap_materno }}
          <!-- <button type="button" @click="abrirModal('descuentos','registrar',empleado.id)" class="btn btn-primary float-sm-right">
          <i class="fas fa-plus"></i>&nbsp;Nuevo
        </button> -->
        <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
          <i class="fa fa-arrow-left"></i>&nbsp;Atras
        </button>
      </div>
      <div class="card-body">
        <vue-element-loading :active="isLoadingDetalle"/>
        <v-client-table :columns="columnsdescuento" :data="tableDatadescuento" :options="optionsdescuento" ref="myTabledescuento">
          <template slot="prestamo.id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
            <button type="button" @click="abrirModal('descuentos','actualizar',props.row.prestamo)" class="dropdown-item">
              <i class="icon-pencil"></i>&nbsp; Actualizar Observaciones
            </button>
            <button type="button" @click="cargarpago(props.row.prestamo)" class="dropdown-item">
              <i class="fas fa-eye"></i>&nbsp; Ver pagos
            </button>
            <button type="button" @click="descargar(props.row.prestamo.adjunto, props.row.prestamo.id)" v-if="(props.row.prestamo.adjunto == '' || props.row.prestamo.adjunto == null)" class="dropdown-item">
              <i class="fas fa-cloud-download-alt"></i>&nbsp; Descargar Solicitud de Prestamo
            </button>
            <button type="button" @click="descargar(props.row.prestamo.adjunto, props.row.prestamo.id)" v-if="(props.row.prestamo.adjunto != '' && props.row.prestamo.adjunto != null)" class="dropdown-item">
              <i class="fas fa-file-download"></i>&nbsp; Descargar Adjunto
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
        <i class="fa fa-align-justify"></i> Pagos pertenecientes a: {{ empleado == null ? '' : empleado.nombre +' '+empleado.ap_paterno +' '+empleado.ap_materno }} de la fecha: {{prestamo.fecha}}
        <button type="button" @click="abrirModalPago('pago','registrar',pagosdescuentos.descuento_id,pagosdescuentos.numero_pago,pagosdescuentos.cantidad)" class="btn btn-dark float-sm-right" :disabled="pagoboton === 1">
          <i class="fas fa-plus"></i>&nbsp;Nuevo pago
        </button>
        <button type="button" @click="maestrouno()" class="btn btn-secondary float-sm-right">
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
            <button type="button" @click="abrirModalPago('pago','actualizar',pagosdescuentos.descuento_id,pagosdescuentos.numero_pago,pagosdescuentos.cantidad,props.row)" class="dropdown-item">
              <i class="icon-pencil"></i>&nbsp; Actualizar
            </button>
            </div>
          </div>
          </div>
          </template>
        </v-client-table>
        <v-client-table :columns="columnstotal" :data="tableDatapagostotal" :options="optionspagost" ref="myTablepagos">
          <template slot="id" slot-scope="props">
          </template>
        </v-client-table>
      </div>
    </div>
  </div>
  <!--Inicio del modal agregar/actualizar-->
  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalpago}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
            <input type="text" name="prestamo_id" v-model="pagosdescuentos.prestamo_id" class="form-control" id="prestamo_id" hidden>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="fecha_pago">Fecha</label>
              <div class="col-md-9">
                <input type="date"  name="fecha_pago" v-model="pagosdescuentos.fecha_pago" class="form-control" placeholder="Fecha" autocomplete="off" id="fecha_pago">
                <span class="text-danger">{{ errors.first('fecha_pago') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="cantidad_a_pagar">Cantidad</label>
              <div class="col-md-9">
                <input type="text" v-validate="'decimal:2'"  name="cantidad_a_pagar" v-model="pagosdescuentos.cantidad_a_pagar" class="form-control" placeholder="Cantidad" autocomplete="off" id="cantidad_a_pagar">
                <span class="text-danger">{{ errors.first('cantidad_a_pagar') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="numero_pago">Numero de pago</label>
              <div class="col-md-9">
                <input type="number" name="numero_pago" v-model="pagosdescuentos.numero_pago" disabled class="form-control"  id="numero_pago" >
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
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="empleado_id" v-bind:hidden="hiden == 1">Empleado</label>
              <div class="col-md-9">
                <v-select :options="optionsvs" v-model="prestamo.empleado_id" label="name"  @input="empleadovalidacion" v-bind:hidden="hiden == 1"></v-select>
                <span class="text-danger">{{ errors.first('empleado_id') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="fecha">Fecha</label>
              <div class="col-md-9">
                <input type="date" name="fecha" v-model="prestamo.fecha" v-bind:disabled="habilitarinputs.habilitarfecha == 1" class="form-control" @change ="fechavalidacion" placeholder="Fecha" autocomplete="off" id="fecha">
                <span class="text-danger">{{ errors.first('fecha') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="cantidad">Cantidad</label>
              <div class="col-md-9">
                <input type="text" v-validate="'decimal:2'" name="cantidad" v-model="prestamo.cantidad" v-bind:disabled="habilitarinputs.habilitarcantidad == 1"
                 class="form-control" @blur="cantidadvalidacion" placeholder="Cantidad" autocomplete="off" id="cantidad">
                <span class="text-danger">{{ errors.first('cantidad') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="numero_pago">Número de pagos</label>
              <div class="col-md-9">
                <input type="number" name="numero_pago" v-model="prestamo.numero_pago" class="form-control" placeholder="Número de pago" autocomplete="off" id="numero_pago" v-bind:disabled="habilitarinputs.habilitarnumeropago == 1">
                <span class="text-danger">{{ errors.first('numero_pago') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="observaciones">Observaciones</label>
              <div class="col-md-9">
                <textarea name="observaciones" v-model="prestamo.observaciones" class="form-control" v-bind:disabled="habilitarinputs.habilitarobservaciones == 1"  autocomplete="off" id="observaciones"></textarea>
                <span class="text-danger">{{ errors.first('observaciones') }}</span>
              </div>
            </div>
            <div class="for row" v-if="tipoAccion==2">
              <label class="col-md-3 form-control-label" for="adjunto">Adjunto A:</label>
              <div class="col-md-6">
                <label v-for="item in tableDatadescuento" v-if="(prestamo.id == item.prestamo.id && item.prestamo.adjunto == null)" :class="ClassL_a">
                  <i class="fas fa-cloud-upload-alt"></i>&nbsp;{{BtnL_a2}}
                  <input type="file" id="file_adjunto" style="display: none;" class="form-control" v-on:change="onChangeAdjunto">
                </label>

                <label v-for="item in tableDatadescuento" v-if="(prestamo.id == item.prestamo.id && item.prestamo.adjunto != null)" class="btn btn-success">
                  <i class="fas fa-file-download"></i>&nbsp;Descargar
                  <button type="button" style="display: none;" @click="descargar(item.prestamo.adjunto,0)">
                  </button>
                </label>

                <label v-for="item in tableDatadescuento" v-if="(prestamo.id == item.prestamo.id && item.prestamo.adjunto != null)" :class="ClassL_a">
                  <i class="fas fa-retweet"></i>&nbsp;{{BtnL_a}}
                  <input type="file" id="file_adjunto" style="display: none;" class="form-control" v-on:change="onChangeAdjunto">
                </label>
              </div>
            </div>
            <!-- </form> -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary"  v-bind:disabled="habilitarinputs.habilitarguardar == 1" @click="guardarprestamo(1)">Guardar</button>
            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarprestamo(0)">Actualizar</button>
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
      PermisosCRUD: {},
      url: '/prestamo',
      empleado: null,
      prestamod : null,
      detalle: false,
      detallepago : false,
      pagoboton : 0,
      prestamo: {
        id: 0,
        cantidad: '',
        fecha : '',
        total : 0,
        tipo_descuento_id : 0,
        empleado_id : '',
        adjunto :'',
        numero_pago :'',
        observaciones :'',
      },
      Metodo: '',
      ClassL_a: 'btn btn-info',
      BtnL_a: 'Actualizar',
      BtnL_a2: 'Subir Archivo',
      pagosdescuentos:{
        id: 0,
        fecha_pago : '',
        cantidad_a_pagar : '',
        numero_pago : 0,
        prestamo_id : 0,
      },
      pagoscount : 0,
      habilitarinputs :{
        habilitarfecha : 1,
        habilitarcantidad : 1,
        habilitarnumeropago : 1,
        habilitarobservaciones: 1,
        habilitaradjunto : 1,
        habilitarguardar : 1,
      },
      optionsvs: [    ],
      valuevs : [],
      listaEmpleados: [],
      listaTipoDescuento: [],
      tableDatapagostotal : [],
      modal : 0,
      modalpago : 0,
      hiden : 0,
      tituloModal : '',
      tipoAccion : 0,
      disabled: 0,
      disabled_input : 1,
      isLoading: false,
      isLoadingDetalle: false,
      isLoadingDetallepago: false,
      columns: ['empleado.id','empleado.nombre','empleado.ap_paterno','empleado.ap_materno','puesto.nombre','departamento.nombre',],
      tableData: [],
      columnsdescuento: ['prestamo.id','prestamo.fecha','prestamo.cantidad','c','prestamo.observaciones','prestamo.adjunto', ],
      columnspagos: ['id','numero_pago','cantidad_a_pagar','fecha_pago', ],
      columnstotal: [ 'totald','totalp','totalr'],
      tableDatadescuento: [],
      tableDatapagos: [],
      totalrestante: 12,
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
          'prestamo.id': 'Acciones',
          'prestamo.fecha': 'Fecha',
          'prestamo.cantidad': 'Cantidad',
          'prestamo.total': 'Total',
          'c': 'Número de pagos',
          'prestamo.observaciones' : 'Observaciones',
          'prestamo.adjunto': 'Adjunto  ',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['prestamo.fecha','prestamo.cantidad','prestamo.total','c','prestamo.observaciones'],
        filterable: ['prestamo.fecha','prestamo.cantidad','prestamo.total','c','prestamo.observaciones'],
        filterByColumn: true,
        listColumns: {
          condicion: config.columnCondicion
        },
        texts:config.texts
      },
      optionspagos: {
        headings: { 'id' :'Acciones' ,'numero_pago': 'Número pago'},
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
        headings: { 'totald':'Total prestamo','totalp': 'Total pagado','totalr': 'Total restante' },
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
      this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
      axios.get('/pagoprestamo').then(response => {
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
            url: nuevo ? '/pagoprestamo' : '/pagoprestamo'+'/'+this.pagosdescuentos.id,
            data: {
              'fecha_pago':this.pagosdescuentos.fecha_pago,
              'cantidad_a_pagar':this.pagosdescuentos.cantidad_a_pagar,
              'numero_pago':this.pagosdescuentos.numero_pago,
              'prestamo_id':this.pagosdescuentos.prestamo_id,
              'id':this.pagosdescuentos.id,
            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {

              if(!nuevo){
                toastr.success('Pago Actualizado Correctamente');
                me.cerrarModal();
                me.cargarpago(me.prestamod);
                this.hiden = 0;
              }
              else
              {
                toastr.success('Pago Registrado Correctamente');
                me.cerrarModal();
                me.cargarpago(me.prestamod);
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
        guardarprestamo(nuevo){
          var arreglo =this.prestamo.empleado_id;
          this.$validator.validate().then(result => {
            if (result) {
              this.isLoading = true;
              let me = this;
              let formData = new FormData();

              formData.append('metodo', this.Metodo);
              formData.append('fecha', this.prestamo.fecha);
              formData.append('cantidad', this.prestamo.cantidad);
              if (nuevo == 1) {formData.append('empleado_id', this.prestamo.empleado_id.id);}
              if (nuevo == 0) {formData.append('empleado_id', this.prestamo.empleado_id.id);}
              formData.append('numero_pago', this.prestamo.numero_pago);
              formData.append('adjunto', this.prestamo.adjunto);
              formData.append('observaciones', this.prestamo.observaciones);
              formData.append('id', this.prestamo.id);

              axios.post(me.url,formData).then(function (response) {
                me.isLoading = false;
                if (response.data.status) {
                  if(!nuevo){
                    toastr.success('Prestamo Actualizado Correctamente');
                    me.cerrarModal();
                    me.cargarprestamo(me.empleado);
                    this.hiden = 0;
                  }
                  else
                  {
                    toastr.success('Prestamo Registrado Correctamente');
                    me.cerrarModal();
                    me.getData();
                    me.cargarprestamo(arreglo);
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
            }
          });
        },
        descargar(archivo, id){
          if (archivo == '' || archivo == null) {
            window.open('prestamo-solicitud/' + id, '_blank');
          }
          let me=this;
          axios({
              url: me.url+ '/' + archivo + '/edit',
              method: 'GET',
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
                url: me.url+ '/' + archivo,
                method: 'DELETE',
              }).then(response => {
              })
              .catch(function (error) {
                      console.log(error);
              });
              //--fin del metodo borrar--//
          });
        },
        onChangeAdjunto(e){
          var nombreL_a = e.target.files[0].name;
          var extension =  nombreL_a.split('.').pop();
          if (extension == 'pdf')
          {
              this.ClassL_a = 'btn btn-warning';
              this.prestamo.adjunto = e.target.files[0];
              this.BtnL_a = 'Archivo Cargado';
              this.BtnL_a2 = 'Archivo Cargado';
          }
          else
          {
              this.ClassL_a = 'btn btn-danger';
              this.BtnL_a = 'Archivo Invalido(Omitido)';
              this.BtnL_a2 = 'Archivo Invalido(Omitido)';
          }
        },
        cerrarModal(){
          this.ClassL_a = 'btn btn-info';
          this.BtnL_a = 'Actualizar';
          this.BtnL_a2 = 'Subir Archivo';
          if (this.tipoAccion == 2)
          {
            $("#file_adjunto").val('');
            //document.getElementById('file_adjunto').value='';
          }
          this.modal=0;
          this.modalpago = 0 ;
          this.tituloModal='';
          this.hiden =0;
          this.prestamo.empleado_id = '';
          this.habilitarinputs.habilitarfecha = 1;
          this.habilitarinputs.habilitarcantidad = 1;
          this.habilitarinputs.habilitarnumeropago = 1;
          this.habilitarinputs.habilitarobservaciones = 1;
          this.habilitarinputs.habilitaradjunto = 1;
          this.habilitarinputs.habilitarguardar = 1;
        },
        abrirModal(modelo, accion, data = []){
          switch(modelo){
            case "descuentos":
            {
              switch(accion){
                case 'registrar':
                {
                  this.modal = 1;
                  this.Metodo = 'Nuevo';
                  this.tituloModal = 'Registrar préstamo';
                  Utilerias.resetObject(this.prestamo);
                  this.tipoAccion = 1;
                  this.disabled=0;
                  this.disabled_input = 0;
                  break;
                }
                case 'actualizar':
                {
                  Utilerias.resetObject(this.prestamo);
                  this.modal=1;
                  this.Metodo = 'Actualizar';
                  this.tituloModal= 'Actualizar préstamo';
                  this.tipoAccion=2;
                  this.disabled=1;
                  this.hiden = 1;
                  this.disabled_input = 1;
                  this.habilitarinputs.habilitarobservaciones = 0;
                  this.habilitarinputs.habilitaradjunto = 0;
                  this.prestamo.id=data['id'];
                  this.prestamo.empleado_id = data['empleado_id'];
                  this.prestamo.fecha = data['fecha'];
                  this.prestamo.adjunto=data['adjunto'];
                  this.prestamo.cantidad=data['cantidad'];
                  this.prestamo.numero_pago = data['numero_pago'];
                  this.prestamo.observaciones = data['observaciones'];
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
                  this.pagosdescuentos.fecha_pago = data['fecha_pago'];
                  this.pagosdescuentos.cantidad_a_pagar = data['cantidad_a_pagar'];
                  this.pagosdescuentos.numero_pago = data['numero_pago'];
                  this.pagosdescuentos.descuento_id=data['descuento_id'];
                  break;
                }
              }
            }
          }
        },
        cargarprestamo(dataEmpleado = []) {
          this.detalle = true;
          this.isLoadingDetalle = true;
          let me=this;
          this.empleado = dataEmpleado;
          this.prestamo.e= dataEmpleado.nombre+' '+dataEmpleado.ap_paterno+' '+dataEmpleado.ap_materno;
          axios.get(me.url + '/' + dataEmpleado.id + '/' +'prestamo').then(response => {
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
          this.pagosdescuentos.fecha_pago = hoy;
          this.prestamo.fecha = hoy;
        },
        cargarpago(dataEmpleado = []) {
          this.detallepago = true;
          this.detalle = false;
          this.isLoadingDetallepago = true;
          let me=this;
          this.prestamod = dataEmpleado;
          this.prestamo.fecha = dataEmpleado.fecha;
          var total = parseFloat(dataEmpleado.cantidad);
          var resultado =(total / dataEmpleado.numero_pago);
          this.pagosdescuentos.cantidad_a_pagar = resultado.toFixed(2);
          this.pagosdescuentos.prestamo_id = dataEmpleado.id;
          this.pagosdescuentos.numero_pago = 1;
          axios.get('/pagoprestamo/'+dataEmpleado.id)  .then(response => {
            me.tableDatapagos = response.data.pago;
            me.tableDatapagostotal = response.data.detalle;
            if (response.data.detalle[0].totalp === response.data.pago[0].Pcantidad
              ||dataEmpleado.numero_pago === response.data.pago.length) {
                this.pagoboton = 1;
              }else {this.pagoboton =0; }
              this.pagosdescuentos.numero_pago = response.data.pago.length + 1;
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
            let me = this;
            me.cargarprestamo(me.empleado);
            this.detallepago = false;
            this.detalle=true
            this.pagoboton =0;
          },
          empleadovalidacion(){
            var id_empleado =this.prestamo.empleado_id.id;
            let me = this;
            axios.get('/prestamo/'+id_empleado +'/politica').then(response=> {
              var url =response.request.responseURL;
              var respuestaurl = url.split('/');
              if (respuestaurl[4] === 'undefined') {
                console.log('cargando info.........');
              }else {
                if (response.data == '' ) {//empleado sin prestamo no aplica validadcion
                  this.habilitarinputs.habilitarfecha = 0;
                  this.habilitarinputs.habilitarcantidad = 0;
                }//fin empleado sin prestamo
                else {//inicio validacion
                  this.habilitarinputs.habilitarfecha = 1;
                  this.habilitarinputs.habilitarcantidad = 1;
                  this.habilitarinputs.habilitarnumeropago = 1;
                  this.habilitarinputs.habilitarobservaciones = 1;
                  this.habilitarinputs.habilitaradjunto = 1;
                  this.habilitarinputs.habilitarguardar = 1;
                  for (var i = 0; i < response.data.length; i++) {
                    var total_pagado = parseFloat(response.data[i].total[0].total);
                    var total_deuda = parseFloat(response.data[i].prestamo.cantidad);
                    var total_restante = total_deuda - total_pagado;
                  }
                  //valida si no se tiene un adeudo anteriro
                  if(total_restante == 0 ){//si no se tiene adeudo
                    this.habilitarinputs.habilitarfecha = 0;
                    this.prestamo.fecha = '';
                    this.prestamo.cantidad = '';
                  }
                  else{//si se tiene adeudo
                    var mensaje = 'No se puede otrogar otro préstamo a este empleado hasta completar el existente!';
                    me.mensajevalidacion(mensaje);
                    me.cerrarModal();
                  }
                }
                // fin validando
              }
            })
            .catch(function (error){
              console.log(error);
            });
          },
          fechavalidacion(){

            var id_empleado =this.prestamo.empleado_id.id;
            let me = this;
            axios.get('/prestamo/'+ me.prestamo.empleado_id.id +'/politica').then(response=> {
              for (var i = 0; i < response.data.length; i++) {
                for (var j = 0; j < response.data[i].pagosprestamos.length; j++) {
                  var fecha_pago = response.data[i].pagosprestamos[j].fecha_pago;
                }
              }
              var a = me.prestamo.fecha;
              var fechaInicio = new Date(fecha_pago).getTime();
              var fechaFin    = new Date(a).getTime();
              var diff = fechaFin - fechaInicio;
              var diferencia =diff/(1000*60*60*24);
              if (diferencia <= 120) {
                var mensaje = 'Espere 4 meses para el siguiente prestamo';
                me.mensajevalidacion(mensaje);
                me.cerrarModal();
              }else{
                me.habilitarinputs.habilitarcantidad = 0;
              }
            })
            .catch(function (error){
              console.log(error);
            });
          },
          cantidadvalidacion(){

            var id_empleado =this.prestamo.empleado_id.id;
            var cantidad = this.prestamo.cantidad;
            let me = this;
            axios.get('/prestamo/'+ me.prestamo.empleado_id.id +'/politica').then(response=> {
              if (response.data.length == 0) {
                me.habilitarinputs.habilitarnumeropago = 0;
                me.habilitarinputs.habilitarobservaciones =0;
                me.habilitarinputs.habilitaradjunto = 0;
                me.habilitarinputs.habilitarguardar = 0;
                // var msm = 'No se puede genarar el prestamo, revise sus contrato y sueldo del empleado';
                // me.mensajevalidacion(msm);
                // me.cerrarModal();
              }
              else {
                if(response.data[0].sueldo.length == 0){
                  var msm = 'No se puede asignar una cantidad hasta definir el sueldo del empleado correspondiente';
                  me.mensajevalidacion(msm);
                  me.cerrarModal();
                }
                else {
                  for (var i = 0; i < response.data.length; i++) {
                  var pagomensual= response.data[i].sueldo[0].sueldo_mensual;
                  }
                var porcentaje = (pagomensual/100)*80;
                if (cantidad > porcentaje) {
                  var mensaje = 'No se puede otorgar el préstamo requierido debido a que la cantidad solicitada excede el máximo establecido';
                  me.mensajevalidacion(mensaje);
                  me.habilitarinputs.habilitarnumeropago = 1;
                  me.habilitarinputs.habilitarobservaciones =1;
                  me.habilitarinputs.habilitaradjunto = 1;
                  me.habilitarinputs.habilitarguardar = 1;
                }
                else {
                  me.habilitarinputs.habilitarnumeropago = 0;
                  me.habilitarinputs.habilitarobservaciones =0;
                  me.habilitarinputs.habilitaradjunto = 0;
                  me.habilitarinputs.habilitarguardar = 0;
                }
                }
              }



            })
            .catch(function (error){
              console.log(error);
            });
          },

          mensajevalidacion(mensaje){
            swal({
              title: mensaje,
              type: 'warning',
              confirmButtonColor: '#e83f1b',
              confirmButtonText: 'Aceptar!',
              confirmButtonClass: 'btn btn-success',
              buttonsStyling: false,
              reverseButtons: true
            })
          }
        },
        mounted() {
          this.getData();
          this.getLista();


        }
      }
      </script>
