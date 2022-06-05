<template>
  <main class="main">
    <div class="container-fluid">
      <br>
      <div class="card" v-show="!detalle && !detallePago">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Registro de pagos no recurrentes
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button" @click="mostrarPagos(props.row)" class="dropdown-item">
                      <i class="fas fa-eye"></i>&nbsp; Mostrar Pagos
                    </button>
                  </div>
                </div>
              </div>

            </template>
            <template slot="FolioOrdendeCompra" slot-scope="props">
              {{props.row.FolioOrdendeCompra.split('-')[ (props.row.FolioOrdendeCompra.split('-')).length - 1]}}
            </template>
            <template slot="monto" slot-scope="props">
              {{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(props.row.monto)}}
            </template>
            <template slot="condicion" slot-scope="props" >
              <template v-if="props.row.condicion == 3">
                <button type="button" class="btn btn-outline-info">Agendado</button>
              </template>
              <template v-if="props.row.condicion == 2">
                <button type="button" class="btn btn-outline-warning">Pendiente</button>
              </template>
              <template v-if="props.row.condicion == 1">
                <button type="button" class="btn btn-outline-danger">Sin Asignar</button>
              </template>
              <template v-if="props.row.condicion == 4">
                <button type="button" class="btn btn-outline-primary">Autorizado para pagar</button>
              </template>
              <template v-if="props.row.condicion == 7">
                <button type="button" class="btn btn-outline-success">Con pagos</button>
              </template>
              <template v-if="props.row.condicion == 0">
                <button type="button" class="btn btn-outline-success">Pagado</button>
              </template>
            </template>
          </v-client-table>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->

      <!-- Listado de pagos de la orden de compra -->
      <br>
      <div class="card" v-show="detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Pagos de la orden - {{ pagoNoRecurrente.FolioOrdendeCompra }} - {{ pagoNoRecurrente.NombreProveedor }}
          <button  type="button" @click="abrirModal('registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>&nbsp;
          <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">

          <v-client-table :columns="columnsPagos" :data="listaPagosCompras" :options="optionsPagos" ref="myTablePagos">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button" @click="abrirModal('actualizar',props.row)" class="dropdown-item">
                      <i class="icon-pencil"></i>&nbsp; Actualizar Pago
                    </button>
                  </div>
                </div>
              </div>
            </template>

            <template slot="pagado" slot-scope="props" >
              <template v-if="props.row.pagado == 1">
                <button type="button" class="btn btn-outline-success">Pagado</button>
              </template>
              <template v-if="props.row.pagado == 0">
                <button type="button" class="btn btn-outline-warning">Pendiente</button>
              </template>
            </template>
            <template slot="moneda" slot-scope="props" >
              <template v-if="props.row.moneda == 1">
                <b>USD</b>
              </template>
              <template v-if="props.row.moneda == 2">
                <b>MXN</b>
              </template>
              <template v-if="props.row.moneda == 3">
                <b>EUR</b>
              </template>
            </template>

            <template slot="descarga" slot-scope="props">
              <template v-if="props.row.adjunto != null">
                <button type="button" class="form-control" @click="descargarfactura(props.row.adjunto)">
                  <i class="fas fa-file-download"></i>&nbsp;Pago
                </button>
              </template>
            </template>

            <template slot="poliza" slot-scope="props">
              <template v-if="props.row.adjunto != null">
                <button type="button" class="form-control" @click="descargarfactura(props.row.poliza)">
                  <i class="fas fa-file-download"></i>&nbsp;Poliza
                </button>
              </template>
            </template>

          </v-client-table>

        </div>
      </div>
      <!-- Fin Listado de pagos de la orden de compra -->

    </div>
    <!--Inicio del modal agregar/actualizar-->
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
              <!-- {{pagoNoRecurrente}} -->
              <div class="form-row">
                <div class="col-md-4 mb-3">
                  <label>Fecha</label>
                  <input type="date" class="form-control" v-model="pago.fecha" v-validate="'required'" data-vv-name="Fecha">
                  <span class="text-danger">{{errors.first('Fecha')}}</span>
                </div>
                <div class="col-md-2 mb-3">
                  <label>Suc.</label>
                  <input type="text" class="form-control" v-model="pago.suc">
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-10 mb-3">
                  <label>Descripción</label>
                  <input type="text" class="form-control" v-model="pago.descripcion">
                </div>

              </div>
              <div class="form-row" >
                <div class="col-md-4 mb-3">
                  <label>Cargo</label>
                  <input type="text" class="form-control" v-validate="'decimal:2'" data-vv-name="Cargo" v-model="pago.cargo">
                  <span class="text-danger">{{errors.first('Cargo')}}</span>
                </div>
                <div class="col-md-2 mb-3">
                  <label>Moneda</label>
                  <select class="form-control" id="tipo_moneda"  name="tipo_moneda" v-model="pago.moneda"
                  data-vv-as="Tipo de moneda" v-validate="'excluded:0'" @change="valor">
                  <option value="0" selected>---</option>
                  <option value="1" >USD</option>
                  <option value="2" >MXN</option>
                  <option value="3" >EUR</option>
                </select>
                <span class="text-danger">{{ errors.first('tipo_moneda') }}</span>
              </div>
              <div class="col-md-2 mb-3" v-if="pago.moneda != 2">
                <label>Tipo de cambio</label>
                <input type="text" v-validate="'decimal:4'" class="form-control" id="tipo_moneda"  name="tipo_cambio"
                v-model="pago.tipo_cambio"  data-vv-as="Tipo de cambio" >
                <span class="text-danger">{{ errors.first('tipo_cambio') }}</span>
              </div>
            </div>

            <div class="form-row">
              <div class="col-md-2 mb-3">
                <label>Referencia</label>
                <input type="text" class="form-control" v-model="pago.referencia">
              </div>
              <div class="col-md-8 mb-3">
                <label>Concepto</label>
                <input type="text" class="form-control" v-model="pago.concepto">
              </div>
            </div>

            <!-- <div class="form-row">
            <div class="col-md-4">
            <label>Tipo</label>
            <input type="text" class="form-control" v-model="pago.tipo">
          </div>
        </div> -->

        <div class="form-row">
          <div class="col-md-4 mb-3">
            <label>Tipo</label>
            <v-select id="orden_compra" :options="listaTipos"
            v-model="pago.tipo" label="name">
          </v-select>
        </div>
      </div>

      <div class="form-row">
        <div class="col-md-4 mb-3">
          <label>Banco Ordenante:</label>
          <input type="text" class="form-control" v-model="pago.banco_ordenante" data-vv-name="Banco Ordenante" >
          <span class="text-danger">{{errors.first('Banco Ordenante')}}</span>
        </div>

      </div>

      <div class="form-row">
        <div class="form-group col-md-2">
          <div class="form-check checkbox" >
            <input type="checkbox" v-model="imagen">
            <label class="form-check-label" >
              Imagen
            </label>
          </div>
        </div>
      </div>


      <template v-if="imagen">
        <div class="form-row">
          <div class="form-group col-md-10">
            <label>&nbsp;Adjunto</label>
            <vue-editor id="editor" @text-change="agregandoImg" v-model="editorContent"  :editor-toolbar="customToolbar">
            </vue-editor>
          </div>
        </div>
      </template>
      <template v-if="!imagen">
        <div class="form-row">
          <div class="form-group col-md-10">
            <label>&nbsp;Adjunto</label>
            <input type="file" name="adjunto" data-vv-name="adjunto" class="form-control" id="adjunto" ref="adjunto" @change="subirAdjunto()">
          </div>
        </div>
      </template>
      <div class="form-group row">
        <label class="col-md-3 form-control-label" for="num_poliza"># Poliza</label>
        <div class="col-md-4">
          <input class="form-control" placeholder="# Poliza" v-model="num_poliza" data-vv-as="num_poliza">
          <span class="text-danger">{{ errors.first('num_poliza') }}</span>
        </div>
      </div>
      <div class="form-group row">



        <template v-if="poliza != ''">

          <label class="col-md-3 form-control-label">Poliza</label>
          <div class="col-md-2">
            <button type="button" class="form-control" @click="poliza = ''">
              <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
            </button>
          </div>

          <div class="col-md-2">
            <button type="button" class="form-control" @click="descargarArch(poliza)">
              <i class="fas fa-download"></i>&nbsp;Descargar
            </button>
          </div>

        </template>
        <template v-if="poliza === ''">
          <label class="col-md-3 form-control-label" for="pdf">Poliza</label>
          <div class="col-md-6">
            <input type="file" class="form-control" name="pdf"  @change="onChangeComprobantePoliza($event)">
          </div>
        </template>
      </div>

      <!-- </template> -->

    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
      <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarPNoRecurrentes(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
      <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarPNoRecurrentes(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
import { VueEditor } from "vue2-editor";
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      editorContent: "",
      customToolbar: [["bold", "italic", "underline"], [{ list: "ordered" }, { list: "bullet" }], ["image"]],
      PermisosCrud : {},
      url: '/pagosnorecurrentes',
      detalle: false,
      detallePago: false,
      imagen  : true,
      num_poliza : '',
      poliza : '',
      pago : {
        id : 0,
        proyecto : '',
        proveedor_acredor : '',
        banco : '',
        fecha : '',
        hora: '',
        suc : '',
        descripcion : '',
        cargo : '',
        moneda : '',
        referencia : '',
        concepto : '',
        adjunto : '',
        metodo : '',
        tipo_cambio : '',
        banco_ordenante : '',
      },
      pagoCompra: {
        id: 0,
        titulo: '',
        descripcion: '',
        fecha: '',
        tipo_moneda: '',
        tipo_cambio: 0,
        cantidad: 0,
      },
      listaTipos : [],
      pagoNoRecurrente: {},
      PnoRecurrentes: {
        id: 0,
        proveedor_id: 0,
        ordenes_comp_id: 0,
        proyecto_id: 0,
        monto:'',
      },
      listaPagosRecu: [],
      listaProveedores: [],
      listaOrdenes:[],
      listaProyectos: [],
      listaPagosCompras: [],
      columnsPagos: ['id','fecha','descripcion','cargo','moneda','referencia','concepto','proveedor_acredor','proyecto','banco','descarga','poliza'],
      optionsPagos: {
        headings: {
          id: 'Acciones',
          titulo: 'Titulo',
          descripcion: 'Descripción',
          cantidad: 'Cantidad',
          tipo_moneda: 'Moneda',
          tipo_cambio: 'Tipo cambio',
          fecha: 'Fecha',
          proveedor_acredor: 'Proveedor/Acreedor'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha','hora','descripcion','cargo','referencia','concepto','proveedor_acredor','proyecto','banco'],
        filterable: ['fecha','hora','descripcion','cargo','referencia','concepto','proveedor_acredor','proyecto','banco'],
        filterByColumn: true,
        texts:config.texts
      },
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      isLoading: false,
      columns: ['id','razon_social','FolioOrdendeCompra','fecha_orden','NombreProyecto','monto','condicion_pago_nombre','condicion'],
      tableData: [],
      options: {
        headings: {
          id: 'Acciones',
          NombreProveedor: 'Proveedor',
          FolioOrdendeCompra : 'OC',
          NombreProyecto : 'Proyecto',
          bancos : 'Banco',
          numcuenta : '# Cuenta',
          fecha_orden : 'Fecha',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        // sortable: ['NombreProveedor','razon_social','FolioOrdendeCompra','NombreProyecto','monto','condicion_pago_nombre','bancos','numcuenta','clabe'],
        // filterable: ['NombreProveedor','razon_social','FolioOrdendeCompra','NombreProyecto','monto','condicion_pago_nombre','bancos','numcuenta','clabe'],
        filterByColumn: true,
        listColumns: {
          'condicion': [
            {
              id: 7,
              text: 'Con Pagos'
            },
            {
              id: 4,
              text: 'Autorizado para pagar'
            },
          ]
        },
        texts:config.texts
      },
    }
  },
  components: {
    VueEditor,
  },
  methods : {


    agregandoImg(file) {
      // alert('here');
      // console.log('file', file);
      // var formData = new FormData();
      // formData.append('image', file)
      if (file.ops[0].delete) {
        this.pago.adjunto = '';
      }
      if (this.pago.adjunto == '') {
        this.pago.adjunto = file.ops[0].insert.image;
      }else {
        toastr.warning('Solo puede adjuntar una imagen');
      }
      // console.log('editor', Editor);
      // console.log('cursor', cursorLocation);
      // console.log('reset', resetUploader);
    },

    onSelect(e){
      console.log(e);
    },
    onCut(e){
      console.log(e);
    },
    onPaste(e){
      console.log(e);
    },
    onCopy(e){
      console.log(e);
    },


    getData() {

      let size = Object.keys(this.$route.query).length;
      if (size > 0) {
        this.detalle = true;
        axios.get(`pagoNoRecurrentepago/${this.$route.query.id}`).then(response => {
          this.mostrarPagos(response.data);
          location.href='dashboard#/pagos/norecurrentes/';
          // me.listaPagosCompras = response.data;
        }).catch(e => {
          console.error(e);
        })
      }else {
        let me=this;
        // this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
        axios.get(me.url).then(response => {
          me.tableData = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
      }

      axios.get('get/tipos/facturapago').then(response => {
        this.listaTipos = [];
        response.data.forEach((item, i) => {
          this.listaTipos.push({
            id : item.id,
            name : item.nombre
          });
        });

      }).catch(error => {
        console.error(error);
      });

    },
    getListaPNoRecurrentes() {
      let me=this;
      axios.get('proveedores').then(response => {
        me.listaProveedores = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getListaOrdenesComp() {
      let me=this;
      axios.get('/compras/ver').then(response => {
        me.listaOrdenes = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getListaProyectos()
    {let me=this;
      axios.get('proyecto').then(response => {
        me.listaProyectos = response.data;
      })
      .catch(function (error){
        console.log(error);
      });
    },
    guardarPNoRecurrentes(nuevo){
      // let files = this.$refs.dropZone.getAcceptedFiles();
      // console.log(files);
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;

          let formData = new FormData();
          formData.append('metodo', this.pago.metodo);
          formData.append('id',this.pago.id);
          formData.append('proyecto',this.pago.proyecto);
          formData.append('proveedor_acredor',this.pago.proveedor_acredor);
          formData.append('banco',this.pago.banco);
          formData.append('fecha',this.pago.fecha);
          formData.append('tipo',this.pago.tipo == null ? 0: this.pago.tipo.id);
          formData.append('suc',this.pago.suc);
          formData.append('pago_no_recurrente_id',this.pagoNoRecurrente.id);
          formData.append('descripcion',this.pago.descripcion);
          formData.append('cargo',this.pago.cargo);
          formData.append('moneda',this.pago.moneda);
          formData.append('referencia',this.pago.referencia);
          formData.append('adjunto', this.pago.adjunto);
          formData.append('concepto',this.pago.concepto);
          formData.append('tipo_cambio',this.pago.moneda == 2 ? 1 :this.pago.tipo_cambio);
          formData.append('num_poliza',this.num_poliza);
          formData.append('poliza',this.poliza);
          formData.append('banco_ordenante',this.pago.banco_ordenante);

          axios.post('pagoscompras/registrar',formData).then(function (response) {
            me.isLoading = false;
            me.cerrarModal();
            if (response.data.status) {
              me.getData();
              if (!nuevo) {
                toastr.success('Pago Actualizado Correctamente');
              }else {
                toastr.success('Pago Agregado Correctamente');
              }


            } else {
              swal({
                type: 'error',
                html: response.data.errors.join('<br>'),
              });
            }
            me.mostrarPagos(me.pagoNoRecurrente);
            me.imagen = true;
            me.pago.adjunto = '';
          }).catch(function (error) {
            console.log(error);
            me.isLoading = false;
            swal({
              type: 'error',
              html: 'Ocurrio algun error... ;('
            });
          });
        }
      });
    },
    actdesact(id,activar){
      if(activar){
      }else{
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
            me.getData();
          }).catch(function (error) {
            console.log(error);
          });
        } else if (
          result.dismiss === swal.DismissReason.cancel
        ) {

        }
      })
    },
    mostrarPagos(dataPago) {
      this.detalle = true;
      this.pagoNoRecurrente = dataPago;
      this.pago.proyecto = dataPago.NombreProyecto;
      this.pago.proveedor_acredor = dataPago.razon_social;
      this.pago.banco = dataPago.bancos;
      let me=this;
      axios.get('pagoscompras/' + this.pagoNoRecurrente.id).then(response => {
        me.listaPagosCompras = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    maestro() {
      // this.detalle = false;
      location.href='dashboard#/pagos/norecurrentes/';
      this.detalle = false;
      this.getData();
    },
    cerrarModal(){
      this.modal=0;
      this.tituloModal='';
      let me = this;
      Utilerias.resetObject(me.pago);
    },
    subirAdjunto(){
      this.pago.adjunto = this.$refs.adjunto.files[0];
    },
    onChangeComprobantePoliza(e){
      this.poliza = e.target.files[0];
    },
    descargarfactura(archivo){
      console.log(archivo);
      if (typeof(this.pago.adjunto) === 'object') {
        toastr.warning('ATENCION','No se puede descargar un archivo que aun no a sido subido');
      }else {
        let me=this;
        axios({
          url: 'descargarfacturareporte/' + archivo ,
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
          axios.get('eliminareportegastos/' + archivo)
          .then(response => {
          })
          .catch(function (error) {
            console.log(error);
          });
          //--fin del metodo borrar--//
        });
      }
    },
    actualizaT(){
      this.pago.adjunto = '';
    },
    abrirModal(accion, data = []){
      switch(accion){
        case 'registrar':
        {
          this.modal = 1;
          this.tituloModal = 'Registrar Nuevo Pago';
          // Utilerias.resetObject(this.pago);
          this.pago.moneda = this.pagoNoRecurrente.moneda;
          this.pagoCompra.tipo_cambio = 1;
          this.tipoAccion = 1;
          this.pago.metodo = 'Nuevo';
          this.pago.fecha = '';
          this.pago.suc = '';
          this.pago.descripcion = '';
          this.pago.cargo = '';
          this.pago.referencia = '';
          this.pago.concepto = '';
          this.pago.adjunto = '';
          this.poliza = '';
          this.num_poliza = '';
          break;
        }
        case 'actualizar':
        {
          Utilerias.resetObject(this.pago);
          this.pago.metodo = 'Actualizar';
          this.modal=1;
          this.tituloModal='Actualizar Pago';
          this.tipoAccion=2;
          this.pago.id = data['id'];
          this.pago.proyecto = data['proyecto'];
          this.pago.proveedor_acredor = data['proveedor_acredor'];
          this.pago.banco = data['banco'];
          this.pago.fecha = data['fecha'];
          this.pago.hora = data['hora'];
          this.pago.suc = data['suc'];
          this.pago.descripcion = data['descripcion'];
          this.pago.cargo = data['cargo'];
          this.pago.moneda = data['moneda'];
          this.pago.referencia = data['referencia'];
          this.pago.concepto = data['concepto'];
          this.pago.adjunto = data['adjunto'];
          this.pago.tipo_cambio = data['tipo_cambio'];
          this.pago.tipo = {id : data['id_tipo'], name : data['nombre_tipo']};
          this.num_poliza = data['num_poliza'];
          this.poliza = data['poliza'];
          this.pago.banco_ordenante = data['banco_ordenante'];
          break;
        }
      }
    },

    valor(){
      if (this.pago.moneda == 1 ||this.pago.moneda == 3 )  {
        this.pago.tipo_cambio = '';
      }
      if (this.pago.moneda == 2) {
        this.pago.tipo_cambio = '1';
      }
    }
  },
  mounted() {
    this.getData();
    this.getListaPNoRecurrentes();
    this.getListaProyectos();
    this.getListaOrdenesComp();
  },
  computed: {
    isDisabled() {
      return this.pago.moneda == 2;
    }
  }
}
</script>
