<template>
  <!-- <main class="main"> -->
  <!-- <div class="container-fluid"> -->
  <div>
    <!-- Ejemplo de tabla Listado -->

    <br>
    <div class="card" v-if="detalle == 1">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Compras
        <!-- <button type="button" @click="descargarequimodal()" class="btn btn-dark float-sm-right">
        <i class="fas fa-file-pdf"></i>&nbsp;Descargar Requisiciones
        </button> -->
      <button type="button" @click="maestroPrincipal()" class="btn btn-secondary float-sm-right">
        <i class="fas fa-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <div class="card-body">
      <vue-element-loading :active="isLoading"/>

      <v-server-table :columns="columns" :url="'compras/busqueda/'+objeto" :options="options" ref="myTable">
        <template slot="id" slot-scope="props">
          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" data-placement="top" title="Acciones" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-grip-horizontal"></i>{{texto_b}}
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                <template v-if="props.row.condicion == 1 || props.row.condicion == 2 ">
                  <a class="dropdown-item" @click="descargarn(props.row)" href="#">
                    <i class="fas fa-file-pdf"></i>&nbsp;Descargar Formato
                  </a>
                </template>
                <template v-if="props.row.condicion == 2 && props.row.estado_id == 2 && props.row.ordenes_formato == null">
                  <a v-show="PermisosCrud.Upload" class="dropdown-item" @click="subirFactura(props.row)" href="#">
                    <i class="fas fa-file-upload"></i>&nbsp;Subir Factura
                  </a>
                </template>
                <template v-if="props.row.ordenes_formato != null">
                  <a v-show="PermisosCrud.Download" class="dropdown-item" @click="descargarFactura(props.row.ordenes_formato,1)" href="#">
                    <i class="fas fa-file-pdf"></i>&nbsp;Descargar Factura Firmada
                  </a>
                </template>

                <template v-if="props.row.condicion == 0">
                </template>

                <div v-if="props.row.condicion > 0">
                  <a type="button" v-show="props.row.estado_id == 3 && PermisosCrud.Update" @click.prevent="abrirModal('compra','actualizar',props.row)" class="dropdown-item" href="#">
                    <i class="icon-pencil"></i>&nbsp;Actualizar compra
                  </a>
                </div>
                <button type="button" @click="ver_facturas(props.row)" class="dropdown-item" >
                  <i class="fas fa-file-pdf"></i>&nbsp; Ver Facturas
                </button>
                <div v-if="props.row.condicion != 2">
                  <a href="#" @click="finalizarCompra(props.row)" v-show="props.row.estado_id == 3 && PermisosCrud.Delete" class="dropdown-item">
                    <i class="far fa-window-close"></i>&nbsp;Finalizar compra
                  </a>
                </div>
              </div>
            </div>
          </div>
        </template>
        <template slot="descargar" slot-scope="props">
         <button v-show="PermisosCrud.Create" class="btn btn-outline-dark" @click="cargardetalle(props.row)" >
            <i class="fas fa-list"></i>&nbsp;Partidas
          </button>
        </template>
        <template slot="format" slot-scope="props">
          <template v-if="props.row.condicion == 1 || props.row.condicion == 2 ">
            <button v-show="PermisosCrud.Download" class="btn btn-outline-dark" @click="descargarn(props.row)" >
              <i class="fas fa-file-pdf"></i>&nbsp;&nbsp;<i class="fas fa-download"></i>
            </button>
          </template>
        </template>
        <template slot="condicion" slot-scope="props" >
          <template v-if="props.row.condicion == 1">
            <span class="btn btn-outline-success">Activo</span>
          </template>
          <template v-if="props.row.condicion == 0">
            <span class="btn btn-outline-danger">Dado de Baja</span>
          </template>
          <template v-if="props.row.condicion == 2">
            <span class="btn btn-outline-warning">Cerrada</span>
          </template>
        </template>
        <template slot="estado_id" slot-scope="props" >
          <template v-if="props.row.estado_id == 3">
            <span class="btn btn-outline-danger">No programado</span>
          </template>
          <template v-if="props.row.estado_id == 2">
            <span class="btn btn-outline-warning">Programada</span>
          </template>
          <template v-if="props.row.estado_id == 1">
            <span class="btn btn-outline-success">Pagado</span>
          </template>
        </template>
        <template slot="moneda" slot-scope="props">
          <template v-if="props.row.moneda == 1">
            <span class="btn btn-outline-success">USD</span>
          </template>
          <template v-if="props.row.moneda == 2">
            <span class="btn btn-outline-success">MXN</span>
          </template>
          <template v-if="props.row.moneda == 3">
            <span class="btn btn-outline-success">EUR</span>
          </template>
        </template>
      </v-server-table>
    </div>
  </div>

  <div v-show="widgets.cardprincipal">
    <cardprincipal ref="cardprincipal" @cardprincipal:click="cerrar($event)"
    @cardprincipalcerrarmodal:click="cerrar()" @cardprincipaluno:click="cerrarprincipal($event)"
    @comprascerrarmodal:click="cerrarModalActualizar()"></cardprincipal>
  </div>

  <!-- Fin ejemplo de tabla Listado -->
  <div v-show="widgets.partidas">
    <partidas ref="partidas" @partidas:click="maestro($event)"></partidas>
  </div>

  <div v-show="widgets.partidasdos">
    <partidasdos ref="partidasdos" @partidas:click="maestro($event)"></partidasdos>
  </div>

  <div v-show="widgets.facturas">
    <facturas ref="facturas" @atras:facturas="cerrarfacturas()"></facturas>
  </div>

  <!-- Modal -->
  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_ver_requisiciones}" style="display: none;" data-focus-on="input:first">
    <div class="modal-dialog modal-dark modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" >Requisiciones</h4>
          <button type="button" class="close" @click="cerrarModalRequi()" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"  @click="cerrarModalRequi()">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Fin modal -->




</div>
<!-- </div>

</main> -->
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const CardPrincipal = r => require.ensure([], () => r(require('./CardPrincipal.vue')), 'compras');
const Partidas = r => require.ensure([], () => r(require('./Partidas.vue')), 'compras');
const PartidasDos = r => require.ensure([], () => r(require('./PartidasDos.vue')), 'compras');
const Facturas = r => require.ensure([], () => r(require('./Facturas.vue')), 'compras');


export default {
  data (){
    return {
      texto_b : '',
      PermisosCrud : {},
      url: '/compras',
      empleado: null,
      objeto : 1,
      detalle : 0,
      nuevo : null,
      principal : false,
      estadomodalimpuesto : null,
      impuestocatalogo : [],
      mostrareinpuesto : 0,
      modal_ver_requisiciones : 0,
      widgets : {
        cardprincipal : false,
        partidas : false,
        partidasdos : false,
        facturas :false,
      },
      compra: {
        id :0,
        folio	 :'',
        condicion : 0,
        condicion_pago :'',
        periodo_entrega :'',
        lugar_entrega: '',
        total :'',
      },
      validado : 0,
      listaEmpleados: [],
      listaCotizaciones : [],
      listaProvedores :[],
      listaCondicionPago : [],
      modal : 0,
      modalb : 0,
      tituloModal : '',
      tipoAccion : 0,
      tipoAccionpr : 0,
      disabled: 0,
      isLoading: false,
      isLoadingg: false,
      isLoadingDetalle: false,
      columns: ['id','descargar','folio','nombre_condicion_pago','periodo_entrega','total','moneda','proveedor_razon_social','format','condicion'],
      tableData: [],
      options: {
        headings: {
          'id': 'Acciones',
          'folio': 'Folio',
          'nombre_condicion_pago': 'Condición de Pago',
          'periodo_entrega': 'Período de Entrega',
          // 'lugar_entrega': 'Lugar de Entrega',
          'total': 'Total',
          'proveedor_razon_social': 'Proveedor',
          'condicion' : 'Condición',
          'estado_id' : 'Estado',
          'descargar' : '',
          'format' : 'Descargar',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['folio','nombre_condicion_pago','periodo_entrega','total','moneda','proveedor_razon_social'],
        filterable: ['folio','nombre_condicion_pago','periodo_entrega','total','moneda','proveedor_razon_social'],
        filterByColumn: true,
        texts:config.texts,
        columnsDisplay : {},
        listColumns: {
          moneda: [{
            id: 1,
            text: 'Dolares'
          },
          {
            id: 2,
            text: 'Moneda Nacional'
          },
          {
            id: 3,
            text: 'Euros'
          }
        ],
        nombre_condicion_pago: [{
          id: 1,
          text: 'Crédito'
        },
        {
          id: 2,
          text: 'Contado'
        },
      ],
      },
      requestAdapter : function (data) {
        var arr = [];
        arr.push({
          'folio' : data.query.folio,
          'condicion_pago_id' : data.query.nombre_condicion_pago,
          'periodo_entrega' : data.query.periodo_entrega,
          'lugar_entrega' : data.query.lugar_entrega,
          'total': data.query.total,
          'moneda' : data.query.moneda,
          'p.razon_social': data.query.proveedor_razon_social,
        });
        data.query = arr[0];
    return data;
  },

    },
  }
},
components: {
  'cardprincipal': CardPrincipal,
  'partidas' : Partidas,
  'partidasdos' : PartidasDos,
  'facturas' : Facturas,

},
methods : {
  verBotton(data) {
    console.log(data);
    this.texto_b = 'Acciones';
  },
  /**
  * [getData metodo de consultas a la BD]
  * @return {Response} [Objeto almacenado en tableData]
  */
  getData(data) {
    let me=this;
    // me.objeto = 0;
    me.objeto = data;
    me.detalle = 1;
    // me.$refs.myTable.refresh();
    me.PermisosCrud = Utilerias.getCRUD(me.$route.path);

    // this.isLoading = true;
    // axios.get('/compras/' + data).then(response => {
    //   me.tableData = response.data;
    //   me.isLoading = false;
    // })
    // .catch(function (error) {
    //   console.log(error);
    // });
  },

  /**
  * [actdesact activar o desactivar orden de compra]
  * @param  {Int} id      [description]
  * @param  {Int} activar [0 = no y 1 = si]
  * @return {Response}           [status = true]
  */
  actdesact(id,activar){
    if(activar){
      this.swal_titulo = 'Esta seguro de activar esta compra?';
      this.swal_tle = 'Activado';
      this.swal_msg = 'El registro ha sido activado con éxito.';
    }else{
      this.swal_titulo = 'Esta seguro de desactivar esta compra?';
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

  cerrar(d){

    $("#input_impuesto").html('');
    this.principal = false;
    this.detalle = 0;
    this.nuevo = null;
    let me = this;
    me.widgets.cardprincipal = false;
  },

  /**
  * [agregarinpuestos description]
  * @param  {Int} num [description]
  * @param  {Int} id  [description]
  * @return {Response}     [description]
  */
  agregarinpuestos(num,id){
    me.widgets.requisicionesalmacen = true;
    var childrequisicionesalmacen = this.$refs.requisicionesalmacen;
    childrequisicionesalmacen.cargarcompras();
  },

  /**
  * [abrirEdicionR description]
  * @param  {Array}  [data=[]] [description]
  * @return {[type]}           [description]
  */
  abrirEdicionR(data = []){
    this.tipoAccionpr=2;
    this.partidascompras.articulo_id = data['articulo_id'];
    this.partidascompras.peso = data['peso'];
    this.partidascompras.equivalente = data['equivale'];
    this.partidascompras.fecha_requerido = data['frequerido'];
  },

  subirFactura(data){
    swal({
      title: 'Factura Firmada',
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

        formData.append('metodo','SubirFormato');
        formData.append('ordenes_formato', file.value);
        formData.append('id',data.id);

        axios.post('/subfactu',formData
      ).then(function (response) {
        if (response.data.status) {
          toastr.success('Archivo Subido Correctamente');
          me.getData(data.proyecto_id);
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


descargarFactura(archivo,id){
  let me=this;
  axios({
    url: me.url+ '/' + archivo,
    method: 'PUT',
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
      url: me.url + '/' + archivo,
      method: 'PUT',
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



/**
* [abrirModal description]
* @param  {[String]} modelo    [description]
* @param  {[String]} accion    [description]
* @param  {Array}  [data=[]] [description]
* @return {}           [description]
*/
abrirModal(modelo, accion, data = []){
  console.log(data,'--');
  let me = this;
  this.detalle = 2;
  me.widgets.cardprincipal = true;

  var childcardprincipal = this.$refs.cardprincipal;
  childcardprincipal.cargarcardprincipal(modelo, accion, data);

},

cerrarprincipal(id){
  let me = this;
  me.getData(id);
},

/**
* [cargardetalle description]
* @param  {Array}  [dataEmpleado=[]] [description]
* @return {[Response]}                   [description]
*/
cargardetalle(dataEmpleado = []) {
  let me = this;
  if(dataEmpleado.conrequisicion === 0){
    me.widgets.partidas = true;
    var childpartidas = this.$refs.partidas;
    childpartidas.cargarpartidas(dataEmpleado);
    this.detalle = 0;
  }else if (dataEmpleado.conrequisicion === 1) {
    me.widgets.partidasdos = true;
    var childpartidasdos = this.$refs.partidasdos;
    childpartidasdos.cargarpartidas(dataEmpleado);
    this.detalle = 0;
  }

},

maestro(data){
  let me= this;
  this.detalle = 0;
  me.widgets.partidas = false;
  me.widgets.partidasdos = false;
  me.isLoading = false;
  me.getData(data);

},

maestroPrincipal(){
  this.$emit('compras:maestro');
},

abrirBusquedaArticulo() {
  this.tipoAccionpr = 1;
  this.modal = 1;
},

cerrarModal(){
  this.modala = 0;
},

/**
* [descargar description]
* @param  {[type]} data [description]
* @return {[type]}      [description]
*/
descargar(data) {
  window.open('descargar-compran/' + data.id, '_blank');
  let me = this;
  me.getData(data.proyecto_id);
},
descargarn(data) {
  window.open('descargar-compran/' + data.id, '_blank');
  let me = this;
  me.getData(data.proyecto_id);
},

/**
* [finalizarCompra description]
* @param  {Array}  [data=[]] [description]
* @return {[type]}           [description]
*/
finalizarCompra(data = [])
{
  Swal.fire({
    title: 'Esta seguro(a) de Finalizar?',
    text: "Esta opción no se podrá desahacer!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si',
    cancelButtonText : 'No',
  }).then((result) => {
    if (result.value) {
      let me = this;

      var total = data['total'];
      var id = data['id'];
      if (total == 0 || total == null) {
        toastr.warning('Atención','Esta compra no puede se cerrada hasta tener artículos registrados');
      }
      else{
        axios.get('/comprasobtener/'+id).then(response => {
          var compra = parseFloat(response.data[0].compra);
          var limitecredito = parseFloat(response.data[0].limitecredito);
          if (compra > limitecredito) {
            //se exede el limite de credito
            toastr.info('Información','La compra exede el límite de crédito del proveedor se realizara como un pago de contado');
            axios.post('/credito',{
              ordenes_comp_id : data['id'],
              estado : 1,
            }).then(function (response){
              if (response.data.status) {
                if (response.data.status === 'error') {
                  swal({
                    type: 'error',
                    html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
                  });
                }else {
                  toastr.success('Correcto','Compra cerrada correctamente');
                  me.getData(data['proyecto_id']);
                }
              }else {
                swal({
                  type: 'error',
                  html: response.data.errors,
                });
              }
            }).catch(function (error){
              console.error(error);
            });
          }
          else {
            axios.post('/credito',{
              ordenes_comp_id : data['id'],
              estado : 2,
            }).then(function (response){
              if (response.data.status) {
                if (response.data.status === 'error') {
                  swal({
                    type: 'error',
                    html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
                  });
                }else {
                  toastr.success('Correcto','Compra cerrada correctamente');
                  me.getData(data['proyecto_id']);
                }
              }else {
                swal({
                  type: 'error',
                  html: response.data.errors,
                });
              }
            }).catch(function (error){
              console.error(error);
            });
          }
          this.$refs.myTable.refresh();
        }).catch(function (error){
          console.error(error);
        });
      }
    }
  })

},

cerrarModalActualizar(){
  this.detalle = 1;
},

ver_facturas(data){
  this.widgets.facturas = true;
  this.detalle = 0;
  var childfacturas = this.$refs.facturas;
  childfacturas.cargar(data, this.listaProvedores, this.UsoFactura);
},

cerrarfacturas(){
  this.widgets.facturas = false;
  this.detalle = 1;
},

descargarequimodal()
{
  this.modal_ver_requisiciones = 1;
},

cerrarModalRequi()
{
  this.modal_ver_requisiciones = 0;
}

},
mounted() {
  // this.getData();
  // this.getArticulosRequisicion();
}
}
</script>
