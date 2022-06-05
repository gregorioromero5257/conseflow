<template>
  <main class="main">
    <div class="container-fluid">
      <!--Inicio card principal  -->
      <div class="card" v-show="detalle == 0">
        <vue-element-loading :active="isLoading" />
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Entradas
          <button v-show="PermisosCRUD.Create" type="button" @click="abrirModal('entrada','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="fecha" slot-scope="props">
              {{fecha(props.row.fecha)}}
            </template>
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>&nbsp;
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                    <template>
                      <button type="button" class="dropdown-item" @click="descargar(props.row)">
                        <i class="fas fa-file-pdf"></i>&nbsp;Descargar Entrada
                      </button>
                      <!-- <button type="button" class="dropdown-item" @click="descargarnuevo(props.row)">
                        <i class="fas fa-file-pdf"></i>&nbsp;Descargar Entrada Nuevo
                      </button> -->
                    </template>

                    <template>
                      <button type="button" @click="cargardetalle(props.row)" class="dropdown-item">
                        <i class="fas fa-eye"></i>&nbsp; Ver Detalles
                      </button>
                    </template>

                    <button type="button" @click="abrirModal('entrada','actualizar',props.row)" class="dropdown-item">
                      <i class="icon-pencil"></i>&nbsp;Actualizar
                    </button>
                    <template v-if="props.row.condicion">
                      <button type="button" class="dropdown-item" @click="actdesact(props.row.id, 0)">
                        <i class="icon-trash"></i>&nbsp;Desactivar
                      </button>
                    </template>
                    <template v-else>
                      <button type="button" class="dropdown-item" @click="actdesact(props.row.id, 1)">
                        <i class="icon-check"></i>&nbsp; Activar
                      </button>
                    </template>
                  </div>
                </div>
              </div>
            </template>
            <template slot="descarga" slot-scope="props">
              <button type="button" class="btn btn-outline-dark" @click="descargarnuevo(props.row)">
                  <i class="fas fa-download"></i>&nbsp;<i class="fas fa-file-pdf"></i>
              </button>
            </template>
            <template slot="condicion" slot-scope="props">
              <template v-if="props.row.condicion == 1">
                <button type="button" class="btn btn-outline-success">Activo</button>
              </template>
              <template v-if="props.row.condicion == 0">
                <button type="button" class="btn btn-outline-danger">Dado de Baja</button>
              </template>

            </template>
            <template slot="estado" slot-scope="props">
              <template v-if="props.row.estado == 1">
                <button type="button" class="btn btn-outline-info">Nuevo</button>
              </template>
              <template v-if="props.row.estado == 2">
                <button type="button" class="btn btn-outline-success">Finalizada</button>
              </template>
              <template v-if="props.row.estado == 3">
                <button type="button" class="btn btn-outline-warning">En revisión</button>
              </template>

            </template>
          </v-client-table>
        </div>
      </div>
      <!-- Fin del card principal -->



    <!--Inicio del modal agregar/actualizar Entradas-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <vue-element-loading :active="isLoading" />
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

              <div class="form-group row" v-show="tipoAccion != 2">
                <label class="col-md-3 form-control-label" for="orden_compra_id">Orden de compra</label>
                <div class="col-md-9">
                  <v-select id="orden_compra" :options="listaOrdenesC"  v-validate="'required'"
                  v-model="orden_compra" label="folio" name="folio"
                  data-vv-name="folio" >
                </v-select>
                <span class="text-danger">{{ errors.first('orden_compra_id') }}</span>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="fecha">Fecha</label>
              <div class="col-md-9">
                <input type="date" v-validate="'required'" name="fecha" v-model="entrada.fecha" class="form-control" placeholder="Fecha de Entrada" autocomplete="off" id="fecha">
                <span class="text-danger">{{ errors.first('fecha') }}</span>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="comentarios">Comentarios</label>
              <div class="col-md-9">
                <input type="text" name="comentarios" v-validate="'required'" v-model="entrada.comentarios" class="form-control" placeholder="Comentarios" autocomplete="off" id="comentarios">
                <span class="text-danger">{{ errors.first('comentarios') }}</span>
              </div>
            </div>



          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarEntrada(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarEntrada(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal Entradas-->

  <!--Inicio del modal agregar partidas oc-->
  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_partidas_oc}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <!-- <vue-element-loading :active="isLoadingg" /> -->
    <div class="modal-dialog modal-dark modal-lg" role="document">
      <div class="modal-content">
        <div>

          <div class="modal-header">
            <h4 class="modal-title">Agregar partidas de OC {{orden_compra.folio}}</h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id">
            <div class="modal-body">
              <v-server-table ref="myTable" :url="'entradas/partidas/oc/'+orden_compra.id" :columns="columnsa" :options="optionsa" @row-click="seleccionarArticulo2">
                <template slot="cantidad_entrada" slot-scope="props">
                  <input type="text" class="form-control" @keyup.enter="guardarCantidad(props)">
                </template>
                <template slot="almacen" slot-scope="props">
                  <button type="button" class="btn btn-primary btn-sm" @click="abrirModalB(props.row)">
                    <i class="fas fa-boxes"></i>&nbsp;Almacenar En ...</button>
                </template>
              </v-server-table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal agregar partidas oc-->

  <!--Inicio del modal agregar almacen-->
  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalb}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dark modal-lg" role="document">
      <div class="modal-content">
        <vue-element-loading :active="isLoading" />
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModalb"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="almacen_id">Almacen</label>
              <div class="col-md-9">
                <select class="form-control" id="almacen_id" name="almacen_id" v-model="almacen.id" @change="almac" data-vv-as="Stand">
                  <option v-for="item in listaAlmaceness" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                </select>
                <span class="text-danger">{{ errors.first('grupo_id') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="stand_id">Stand</label>
              <div class="col-md-9">
                <select class="form-control" id="stand_id" name="stand_id" v-model="almacen.stand_id" @change="niv" data-vv-as="Stand">
                  <option v-for="item in listaStand" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                </select>
                <span class="text-danger">{{ errors.first('grupo_id') }}</span>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="nivel_id">Nivel</label>
              <div class="col-md-9">
                <select class="form-control" id="nivel_id" name="nivel_id" v-model="almacen.nivel_id" data-vv-as="Nivel">
                  <option v-for="item in listaNivel" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                </select>
                <span class="text-danger">{{ errors.first('grupo_id') }}</span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="guardarDatosAlmacen()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
          </div>

      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal agregar almacen-->

</div>
</main>
</template>


<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Facturas = r => require.ensure([], () => r(require('./Facturas.vue')), 'alm');

export default {
  data() {
    return {
      modal_partidas_oc : 0,
      orden_compra: {},
      PermisosCRUD: {},
      condiciones: 0,
      empleado_responsable: 0,
      url: '/entrada',
      entrada_objeto: null,
      datospartida: null,
      detalle: false,
      factura: false,
      comprobante: '',
      entrada: {
        id: 0,
        fecha: '',
        comentarios: '',
        ord_compra_id: '',
        condicion: 0,
        nombrea: '',
        nombree: '',
        disabled: true,
        orden_compra_id: '',
      },
      almacen: {
        id: 0,
        ida: '',
        nombre: '',
        stand_id: 0,
        stand: '',
        nivel_id: 0,
        nivel: '',
      },
      parentrada: {
        entrada_id: 0,
        req_com_id: 0,
        articulo_id: 0,
        nombrearti: '',
        fechaentrada: '',
        orden_n_id: 0,
        proveedore_id: 0,
        validacion_calidad: 0,
        cantidad: '',
        caducidad: '',
        stocke_id: 0,
        cantidad_opcional: '',
        precio_unitario: '',
        factura_id: '',
        id: 0,
        numero_serie : '',
      },
      lote_nombre: '',
      tituloModalc: '',
      listaOrdenesC: [],
      listaTipoAdquisicion: [],
      listaTipoEntrada: [],
      listaProvedores: [],
      UsoFactura: [],
      dataTableArticulo: [],
      optionsvs: [],
      listaNivel: [],
      listaStand: [],
      lista_facturas: [],
      listaAlmaceness: [],
      modal: 0,
      modala: 0,
      modalb: 0,
      modalc: 0,
      modaldoc: 0,
      tituloModal: '',
      tituloModala: '',
      tituloModalb: '',
      tipoAccion: 0,
      tipoAccionpr: 0,
      disabled: 0,
      isLoading: false,
      isLoadingg: false,
      isLoadingb: false,
      isLoadingDetalle: false,
      columnsa: [ 'descripcion', 'unidad','cantidad','cantidad_entrada','almacen'],
      columns: ['id', 'foliocompra', 'fecha', 'comentarios', 'descarga' ,'condicion', 'estado'],
      tableData: [],
      columnspartidas: ['articulo_id', 'req_com_id', 'foliocompra', 'ad', 'amarca', 'id', 'validacion_calidad', 'cantidad', 'precio_unitario'],
      tableDataPartidas: [],
      optionsa: {
        headings: {
          'ocf': 'Folio orden de compra',
          'descripciona': 'Descripción artículo',
          'ocfo': 'Fecha de la orden',
          'prazonsocial': 'Proveedor',
          'proyectonombre': 'Proyecto',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        //  sortable: ['entrada.folio'],
        //  filterable: ['entrada.folio'],
        filterByColumn: true,
        texts: config.texts
      },
      options: {
        headings: {
          'id': 'Acciones',
          'fecha': 'Fecha',
          'foliocompra': 'Folio',
          'comentarios': 'Comentarios',
          'formato_entrada': 'Formato de entrada',
          'nombrea': 'Tipo de adquisición',
          'nombree': 'Tipo de entrada',
          'condicion': 'Condición',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['foliocompra', 'fecha', 'comentarios', 'condicion', 'estado'],
        filterable: ['foliocompra', 'fecha', 'comentarios', 'condicion', 'estado'],
        filterByColumn: true,
        texts: config.texts
      },

  }
},
components: {
  'factura': Facturas,
},
methods: {

  getListas(){
    let me = this;
    axios.get('/almacen/ver').then(response => {
      me.listaAlmaceness = response.data;
      me.isLoading = false;
    })

    .catch(function (error) {
      console.log(error);
    });

  },

  /**
  * @return {Response} [se obtiene la]
  */
  fecha(fecha){
    const meses = [
      "Enero", "Febrero", "Marzo",
      "Abril", "Mayo", "Junio", "Julio",
      "Agosto", "Septiembre", "Octubre",
      "Noviembre", "Diciembre"
    ];

    const date = new Date(fecha);
    const dia = date.getDate() + 1;
    const mes = date.getMonth();
    const ano = date.getFullYear();
    return `${dia} de ${meses[mes]} del ${ano}`;
  },
  /**
  * [getArticulosEntrada Metodo de consulta a la BD ]
  * @return {Response} [objeto almacenado en la variable dataTableArticulo]
  */
  getArticulosEntrada() {
    let me = this;

    axios.get('/verordenescompras').then(response => {
      me.listaOrdenesC = [];
      response.data.forEach(o => {
        me.listaOrdenesC.push({
          id: o.id,
          folio: `Folio: ${o.folio}`,
        });
      });
    })
    .catch(function (error) {
      console.log(error);
    });
  },

  /**
  * [getData Metodo de consulta a la BD ]
  * @return {Response} [description]
  */
  getData() {
    this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
    this.isLoading = true
    let me = this;
    axios.get('/entrada').then(response => {
      me.tableData = response.data;
      this.isLoading = false;
    })
    .catch(function (error) {
      console.log(error);
    });

    axios.get('/Solicitud').then(response => {
      this.empleado_responsable = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
  },


  /**
  * [actdesact Metodo que actualiza la condicion de la entrada activo o desactivo]
  * @param  {Int} id      [description]
  * @param  {Int} activar [0 = no y 1 = si]
  * @return {Response}         [status = true]
  */
  actdesact(id, activar) {
    if (activar) {
      this.swal_titulo = 'Esta seguro de activar esta entrada?';
      this.swal_tle = 'Activado';
      this.swal_msg = 'El registro ha sido activado con éxito.';
    } else {
      this.swal_titulo = 'Esta seguro de desactivar esta entrada?';
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
        axios.get(me.url + '/' + id + '/edit').then(function (response) {
          if (activar) {
            toastr.success(me.swal_tle, me.swal_msg, 'success');

          } else {
            toastr.error(me.swal_tle, me.swal_msg, 'error');

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

  /**
  * [guardarEntrada Metodo que almacen o actualiza una entrada]
  * @param  {Int} nuevo [1 = si y 0 = no]
  * @return {Response}       [status = true]
  */
  guardarEntrada(nuevo) {
    this.$validator.validate().then(result => {
      if (result) {
        this.isLoading = true;
        this.detalle = false;
        this.isLoadingg = false;
        let me = this;
        axios({
          method: nuevo ? 'POST' : 'PUT',
          url: nuevo ? me.url : me.url + '/' + this.entrada.id,
          data: {
            'id': this.entrada.id,
            'fecha': this.entrada.fecha,
            'comentarios': this.entrada.comentarios,
            'orden_compra_id': me.orden_compra.id
          }
        }).then(function (response) {
          me.isLoading = false;
          if (response.data.status) {
            if (!nuevo) {
              me.cerrarModal();
              me.getData();
              toastr.success('Entrada Actualizada Correctamente');
            } else {
              toastr.success('Entrada Registrada Correctamente');
              me.registrarEntrada(response.data.id_entrada);
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

  registrarEntrada(data){
    this.parentrada.entrada_id = data;
    this.modal_partidas_oc = 1;
  },


  /**
  * [cerrarModal description]
  */
  cerrarModal() {
    this.modal = 0;
    this.modala = 0;
    this.modalb = 0;
    this.modalc = 0;
    this.modaldoc = 0;
    this.tituloModal = '';
    Utilerias.resetObject(this.requisicion);
  },






  /************/
  abrirModal(modelo, accion, data = []) {
    this.getArticulosEntrada();
    switch (modelo) {
      case "entrada": {
        switch (accion) {
          case 'registrar': {
            let me = this;
            Utilerias.resetObject(me.entrada);
            this.modal = 1;
            this.tituloModal = 'Registrar entrada';
            this.tipoAccion = 1;
            this.disabled = 0;
            break;
          }
          case 'actualizar': {
            let me = this;
            Utilerias.resetObject(me.entrada);
            this.modal = 1;
            this.tituloModal = 'Actualizar entrada';
            this.entrada.id = data['id'];
            this.tipoAccion = 2;
            this.disabled = 1;
            this.entrada.fecha = data['fecha'];
            this.entrada.comentarios = data['comentarios'];
            this.entrada.formato_entrada = data['formato_entrada'];
            this.entrada.tipo_adq_id = data['tipo_adq_id'];
            this.entrada.tipo_entrada_id = data['tipo_entrada_id'];
            this.entrada.orden_compra_id = data['orden_compra_id'];
            break;
          }
        }
      }
    }
  },
  /**
  * [cargardetalle Carga las partidas de la entrada seleccionada]
  * @param  {Array}  [data=[]] [description]
  * @return {Response}                   [objeto almacenado en la variable tableDataPartidas]
  */
  cargardetalle(data = []) {
    this.detalle = true;
    this.isLoadingDetalle = true;
    this.isLoading = true;
    this.entrada_objeto = data;
    this.parentrada.entrada_id = data.id;

    let me = this;

    axios.get('/verfinalizacionpartida/' + data.id).then(response => {}).catch(function (error) {
      console.log(error);
    });

    axios.get(me.url + '/' + data.id).then(response => {
      me.tableDataPartidas = response.data;
      me.isLoadingDetalle = false;
    }).catch(function (error) {
      console.log(error);
    });

    axios.get(`facturasentradas/${data.id}&Entrada&${data.orden_compra_id}`).then(response => {
      me.lista_facturas = response.data;
    }).catch(error => {
      console.log(error);
    });

    axios.get('/verordencompra/' + data.orden_compra_id).then(response => {
      me.dataTableArticulo = response.data[0].datos;
    }).catch(function (error) {
      console.log(error);
    });

  },

  terminos() {
    this.condiciones = 1;
  },

  subirDocumento(data, metodo) {
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
      if (file.value) {
        let formData = new FormData();

        formData.append('metodo', metodo);
        formData.append('documento_po', file.value);
        formData.append('id', data.id);

        axios.post(me.url, formData).then(function (response) {
          if (response.data.status) {
            if (metodo == 1) {
              toastr.success('Archivo Subido Correctamente');
            }
            if (metodo == 2) {
              toastr.success('Archivo Actualizado Correctamente');
            }
            me.listar();
          } else {
            swal({
              type: 'error',
              html: response.data.errors.join('<br>'),
            });
          }
        }).catch(function (error) {
          console.log(error);
        });
      } else if (file.dismiss === swal.DismissReason.cancel) {}
    })
  },

  maestro() {
    this.detalle = false;
    this.isLoading = false;
    this.cancelar();
    this.getData();

  },

  cancelar() {
    this.parentrada.articulo_id = 0;
    this.parentrada.req_com_id = 0;
    this.parentrada.nombrearti = "";
    this.parentrada.caducidad = "";
    this.parentrada.cantidad = '';
    this.parentrada.cantidad_opcional = '';
    this.parentrada.precio_unitario = '';
    this.parentrada.factura_id = '';
    this.parentrada.numero_serie = '';
    this.tipoAccionpr = 0;
    this.actualizar_partida = 0;
  },

  abrirBusquedaArticulo() {
    this.tipoAccionpr = 1;
    this.modal = 1;
  },

  seleccionarArticulo2(data) {
    let me = this;
    this.parentrada.req_com_id = data.row.id;
    this.parentrada.articulo_id = data.row.ida;
    this.parentrada.nombrearti = data.row.descripciona;
    this.parentrada.proveedore_id = data.row.idproveedor;
    this.parentrada.cantidad = data.row.cantidad_entrada;
    this.parentrada.cantidad_opcional = data.row.cantidad_entrada;
    this.parentrada.stocke_id = data.row.stokeid;
    me.cerrarModal();
  },

  descargar(data) {
    window.open('descargar-entrada/' + data.id, '_blank');
    let me = this;
    me.getData();
  },

  descargarnuevo(data) {
    window.open('descargar-entrada-nuevo-formato/' + data.id, '_blank');
    let me = this;
    me.getData();
  },

  ver_facturas(data) {
    this.detalle = 2;
    var childfacturas = this.$refs.facturas;
    childfacturas.cargar(data, this.listaProvedores, this.UsoFactura);
  },

  cerrarfacturas() {
    this.detalle = 0;
  },

  enviar_revision(data) {
    Swal.fire({
      title: 'Esta seguro(a) de enviar a revisión?',
      text: "Esta opción no se podrá desahacer!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.value) {
        axios.get(`entradas/revisionfactura/${data.id}`).then(response => {
          this.getData();
        }).catch(error => {
          console.error(error);
        });
      }
    });

  },

  actualizarpartidaentrada(data) {
    this.actualizar_partida = 1;
    this.parentrada.nombrearti = data.ad;
    this.parentrada.cantidad_opcional = data.cantidadcompra;
    this.parentrada.cantidad = data.cantidad;
    this.parentrada.precio_unitario = data.precio_unitario;
    this.parentrada.caducidad = data.caducidad;
    this.parentrada.id = data.id;
    this.parentrada.numero_serie = data.numero_serie;
    this.tipoAccionpr = 2;
    this.parentrada.factura_id = data.factura_id;
  },

  cargarcomprobante(data) {
    // console.log(data);
    var i = data.target.selectedIndex
    this.comprobante = this.lista_facturas[i].comprobante;
  },

  descargarc(archivo) {
    let me = this;
    axios({
      url: '/facturasentradasdescarga/' + archivo,
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
      axios.get('/facturasentradaseditar/' + archivo)
      .then(response => {})
      .catch(function (error) {
        console.log(error);
      });
      //--fin del metodo borrar--//
    });
  },

  descargarCodigoBarras(data){
    window.open('descargar/codigo/barras/' + data.id, '_blank');
  },

  guardarCantidad(data){
    console.log(data);
  },

  abrirModalB(data = []) {
    console.log('modass');
    this.modalb = 1;
    this.modal_partidas_oc = 0;
    this.tituloModalb = 'Agregar a almacen';
    // this.datospartida = data;
    // this.almacen.id = data['almacene_id'];
    // let me = this;
    // var idalmacen = data['almacene_id'];
    // axios.get('/almacen/verstand/' + idalmacen).then(response => {
    //   me.listaStand = response.data;
    //   this.almacen.stand_id = data['stand_id'];
    //   var idstand = data['stand_id'];
    //   axios.get('/almacen/vernivel/' + idstand).then(response => {
    //     me.listaNivel = response.data;
    //     this.almacen.nivel_id = data['nivel_id'];
    //   })
    // })
    // .catch(function (error) {
    //   console.log(error);
    // });
  },

  /**
  * [almac Metodo de consulta a la BD ]
  * @return {Response} [description]
  */
  almac() {
    let me = this;
    this.isLoading = true;
    axios.get('almacen/verstand/' + this.almacen.id).then(response => {
      me.listaStand = response.data;
      me.isLoading = false;
    })
    .catch(function (error) {
      console.log(error);
    });
  },

  /**
  * [niv Metodo de consulta a la BD ]
  * @return {Response} [description]
  */
  niv() {
    let me = this;
    this.isLoading = true;
    axios.get('almacen/vernivel/' + this.almacen.stand_id).then(response => {
      me.listaNivel = response.data;
      me.isLoading = false;
    })
    .catch(function (error) {
      console.log(error);
    });
  },

},

mounted() {
  this.getData();
  this.getListas();
}
}
</script>
