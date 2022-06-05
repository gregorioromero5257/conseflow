<template>
  <main class="main">
    <div class="container-fluid">
      <div class="card" v-show="estado == 0">
        <div class="card-header">

          <i class="fa fa-align-justify"></i> Solicitudes {{empresa == 1 ? 'CONSERFLOW' : empresa == 2 ? 'CSCT' : ''}}
          <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle float-sm-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Empresa
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2" v-model="empresa">
              <button class="dropdown-item" type="button" @click="empresa = 1;buscarCFW();">Conserflow</button>
              <button class="dropdown-item" type="button" @click="empresa = 2;buscarCSCT();">Constructora</button>
            </div>
          </div>
          <!-- <button type="button" class="btn btn-dark float-sm-right" @click="abrirModal('solicitud','registrar')">
          <i class="fas fa-plus">&nbsp;</i>Nuevo
        </button> -->

      </div>
      <div class="card-body">



        <v-client-table :columns="columns" :options="options" :data="tableData" ref="myTable">

          <template slot="id" slot-scope="props" >
            <!-- {{props.row}} -->
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i>&nbsp;
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                  <button type="button" @click="detalles(props.row)" class="dropdown-item" >
                    <i class="fas fa-mail-bulk"></i>&nbsp;Detalles
                  </button>

                </div>
              </div>
            </div>
          </template>

          <template slot="descarga" slot-scope="props">
            <button type="button" @click="descargar(props.row.id)" class="btn btn-outline-dark" >
              <i class="fas fa-download"></i>&nbsp;<i class="fas fa-file-pdf"></i>
            </button>
          </template>

        </v-client-table>
      </div>
    </div>

    <div v-show="estado == 1">
      <ul class="nav nav-tabs" role="tablist" ref="tabs">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#menu1" role="tab" @click="revisareporte()"><i class="fas fa-book"></i>&nbsp;Comprobaciones</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#menu2" role="tab" @click="pagos()" ><i class="fas fa-tools"></i>&nbsp;Pagos</a>
        </li>

        <li class="nav-item">
          <a class="btn btn-secondary float-sm-right" @click="maestro()"><i class="fa fa-arrow-left"></i>&nbsp;Atras</a>
        </li>
      </ul>
      <div class="tab-content">
        <div id="menu1" class="tab-pane fade in active show" >
          <div v-show="tabs == 1">
            <comprobacion ref="comprobacion" @comprobacion:maestro="maestro"></comprobacion>
          </div>

        </div>
        <div id="menu2" class="tab-pane fade">
          <div v-show="tabs == 2">
            <div class="card">
              <!-- {{data_detalle}} -->
              <div class="card-header">
                <i class="fa fa-align-justify"></i> Pagos
                <button type="button" class="btn btn-dark float-sm-right" @click="abrirModal('pagos','registrar')">
                  <i class="fas fa-plus">&nbsp;</i>Nuevo
                </button>
              </div>
              <div class="card-body">
                <v-client-table :data="dataTablePago" :columns="columnsPago" :options="optionsPago">
                  <template slot="id" slot-scope="props">
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                      <div class="btn-group dropup" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-grip-horizontal"></i>&nbsp;
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                          <button type="button" @click="eliminar(props.row.id)" class="dropdown-item" >
                            <i class="fas fa-trash"></i>&nbsp;Eliminar
                          </button>

                        </div>
                      </div>
                    </div>
                  </template>
                </v-client-table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Inicio del modal agregar/actualizar -->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" arial-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span arial-hidden="true">X</span>
            </button>
          </div>
          <div class="modal-body">
            <vue-element-loading :active="isLoading"/>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Folio</label>
                <input type="text" class="form-control" v-validate="'required'" data-vv-name="folio" v-model="pago.folio">
                <span class="text-danger">{{errors.first('folio')}}</span>
              </div>
              <div class="form-group col-md-6">
                <label>Fecha</label>
                <input type="date" class="form-control" v-validate="'required'" data-vv-name="fecha" v-model="pago.fecha">
                <span class="text-danger">{{errors.first('fecha')}}</span>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Cantidad</label>
                <input type="text" v-validate="'required|decimal:2'" class="form-control" data-vv-name="cantidad" v-model="pago.cantidad">
                <span class="text-danger">{{errors.first('cantidad')}}</span>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-8">

                <template v-if="tipoAccion == 1">
                  <label >Comprobante</label>
                  <vue-editor id="editor"
                  @text-change="agregandoImg" v-model="editorContent"
                  :editorToolbar="customToolbar"
                  >
                </vue-editor>

              </template>
              <template v-if="tipoAccion == 2">
                <template v-if="pago.comprobante != ''">
                  <label>&nbsp;Comprobante</label>
                  <button type="button" class="form-control" @click="pago.comprobante = ''">
                    <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                  </button>
                </template>
                <template v-if="pago.comprobante == ''">
                  <label >Comprobante</label>
                  <vue-editor id="editor"
                  @text-change="agregandoImg" v-model="editorContent"
                  :editorToolbar="customToolbar"
                  >
                </vue-editor>
              </template>
            </template>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <vue-element-loading :active="isLoading"/>
        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
        <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
        <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
      </div>
    </div>
  </div>
</div>
<!-- Fin de modal  -->

</div>
</main>
</template>
<script>
import { VueEditor } from "vue2-editor";
const Comprobaciones = r => require.ensure([], () => r(require('./Comprobaciones.vue')), 'opera');
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default{
  data () {
    return {
      editorContent: "",
      customToolbar: [["bold", "italic", "underline"], [{ list: "ordered" }, { list: "bullet" }], ["image", "code-block"]],
      data_detalle : '',
      tabs : 0,
      estado : 0,
      isLoading : false,
      empresa : 0,
      image_temporal : '',
      solicitud : {
        id : 0,
        fecha : '',
        empleado_revisa_id : '',
        empleado_autoriza_id : '',
        beneficiario_id : '',
        datos_bancos_beneficiario : '',
        cuentauno : '',
        claveuno : '',
        banco : '',
        clavecuentatarjetauno : '',
      },
      pago : {
        id : 0,
        fecha : '',
        folio : '',
        comprobante : '',
        cantidad : '',
      },
      listado_temporal : {
        tranferencia : '',
        efectivo : '0',
        conceptos : 'CAJA CHICA',
      },
      listado : {
        tranferencia : [],
        efectivo : [],
        conceptos : [],
      },
      vs_options : [],
      tipoAccion : 0,
      modal : 0,
      tituloModal : '',
      listaDatosBancariosuno : [],
      columns: ['id','folio','beneficiario','fecha','conceptos','efectivo','tranferencia','descarga'],
      tableData: [],
      options: {
        headings: {
          'id': 'Acciones',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha','conceptos','efectivo','tranferencia'],
        filterable: ['fecha','conceptos','efectivo','tranferencia'],
        filterByColumn: true,
        texts:config.texts
      },
      dataTablePago : [],
      columnsPago : ['id','folio','fecha','cantidad'],
      optionsPago: {
        headings: {
          'id': 'Acciones',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  components : {
    'comprobacion' : Comprobaciones,
    VueEditor,
  },
  methods : {

    agregandoImg(file) {
      this.image_temporal = '';
      this.image_temporal =file.ops[0].insert.image;
    },


    onChangeComprobante(e){
      this.pago.comprobante = e.target.files[0];
    },
    /**
    **
    **/
    maestro(){
      this.estado = 0;
    },

    /**
    **
    **/
    detalles(data){
      this.data_detalle = data;
      this.estado = 1;
      this.revisareporte();
    },

    /**
    **
    **/
    revisareporte(){
      this.tabs =1;
      var childComprobacion = this.$refs.comprobacion;
      childComprobacion.cargar(this.data_detalle,'control');
    },

    /**
    **
    **/
    pagos(){
      this.tabs = 2;
      axios.get('/caja/chica/pagos/' + this.data_detalle.id + '&' + this.data_detalle.empresa).then(response => {
        this.dataTablePago = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    /*//
    *Cargando datos principales para el formulario
    **/
    getData(){

    },
    /******/
    buscarCFW(){
      this.tableData = [];
      this.vs_options = [];
      this.optionsvs = [];

      axios.get('get/listado/caja/chica/cfw/control').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });

      axios.get('/empleados/viaticos/' + this.empresa).then(response => {
        for (var i = 0; i < response.data.length; i++) {
          this.vs_options.push({
            id : response.data[i].id,
            name : response.data[i].nombre + ' ' + response.data[i].ap_paterno + ' ' + response.data[i].ap_materno,
          });
        }
      }).catch(error => {
        console.error(error);
      });
    },

    /**+*/
    buscarCSCT(){
      this.tableData = [];
      this.vs_options = [];
      this.optionsvs = [];

      axios.get('get/listado/caja/chica/csct/control').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });

      axios.get('/empleados/viaticos/' + this.empresa).then(response => {
        for (var i = 0; i < response.data.length; i++) {
          this.vs_options.push({
            id : response.data[i].id,
            name : response.data[i].nombre + ' ' + response.data[i].ap_paterno + ' ' + response.data[i].ap_materno,
          });
        }
      }).catch(error => {
        console.error(error);
      });

    },

    /***/

    /****/
    cerrarModal(){
      this.modal = 0
    },

    /****/
    guardar(nuevo){
      this.$validator.validate().then(result =>{
        if (result) {
          this.isLoading = true;
          let me = this;

          let formData = new FormData();

          formData.append('id',this.pago.id);
          formData.append('folio',this.pago.folio);
          formData.append('fecha',this.pago.fecha);
          formData.append('cantidad',this.pago.cantidad);
          formData.append('comprobante',this.image_temporal);
          formData.append('solicitud_caja_chica_id',this.data_detalle.id);
          formData.append('empresa',this.data_detalle.empresa);
          axios({

            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? 'caja/chica/guardar/pago/' : 'caja/chica/actualizar/pago/',
            data : formData
          }).then(response => {
            this.modal = 0;
            this.limpiaInputs();
            toastr.success(nuevo ? 'Creado Correctamente!!' : 'Actualizado Correctamente!!!')
            this.pagos();
            this.isLoading = false;

          }).catch(e => {
            console.error(e);
          });
        }
      });
    },

    /*****/
    diaActual(){
      var hoy = new Date ();
      var dd = hoy.getDate();
      var mm = hoy.getMonth()+1; //hoy es 0!
      var yyyy = hoy.getFullYear();

      if(dd<10) { dd='0'+dd }
      if(mm<10) { mm='0'+mm }

      hoy = yyyy+'-'+mm+'-'+dd;
      this.solicitud.fecha = hoy;
    },

    /**resetar datos bancarios **/
    quitar_uno(){
      this.listaDatosBancariosuno = [];
      this.solicitud.beneficiario_id = '';
      this.limpiaInputs();
    },

    /****/
    buscar_uno(){
      this.listaDatosBancariosuno = [];
      let me = this;
      axios.get('/datosbanemp/' + me.solicitud.beneficiario_id.id + '&' + me.empresa + '/datosbanemp').then(response => {
        me.listaDatosBancariosuno = response.data;
      });
    },

    /******/
    enviar_uno(e = null){
      this.limpiaInputs();
      var target = e == null ? 0 : e.target.selectedIndex;
      if (e.target.value != 0) {
        this.solicitud.datos_bancos_beneficiario = this.listaDatosBancariosuno[e.target.selectedIndex]['id'],
        this.solicitud.clavecuentatarjetauno = this.listaDatosBancariosuno[e.target.selectedIndex]['numero_tarjeta'],
        this.solicitud.claveuno = this.listaDatosBancariosuno[e.target.selectedIndex]['clabe'],
        this.solicitud.cuentauno = this.listaDatosBancariosuno[e.target.selectedIndex]['numero_cuenta'],
        this.solicitud.banco = this.listaDatosBancariosuno[e.target.selectedIndex]['bnombre'];
      }
    },

    /*****/
    limpiaInputs(){
      this.pago.folio = '';
      this.pago.fecha = '';
      this.pago.cantidad = '';
      this.pago.comprobante = '';
      $('#c_nuevo').val('');
    },

    //****//
    crear(){
      if (this.listado_temporal.tranferencia === '' ) {
        toastr.warning('Registre la cantidad de transferencia!!!');
      }else {
        this.listado.tranferencia.push(this.listado_temporal.tranferencia);
        this.listado.efectivo.push(this.listado_temporal.efectivo);
        this.listado.conceptos.push(this.listado_temporal.conceptos);

        this.listado_temporal.tranferencia = '';
        this.listado_temporal.efectivo = '0';
        this.listado_temporal.conceptos = 'CAJA CHICA';
      }
    },

    deleteu(index){
      this.listado.tranferencia.splice(index, 1);
      this.listado.efectivo.splice(index, 1);
      this.listado.conceptos.splice(index, 1);
    },

    enviaRevision(data){
      axios.get('enviar/revision/caja/chica/' + data.id + '&' + this.empresa).then(response => {
        toastr.success('Enviado a revisión !!!');
        if (this.empresa == 1) {
          this.buscarCFW();
        }else if (this.empresa == 2) {
          this.buscarCSCT();
        }
      }).catch(e => {
        console.error(e);
      })
    },

    descargar(data){
      window.open('descargar/solicitud/caja/chica/' + data + '&' + this.empresa, '_blank');
    },


    /***/
    abrirModal(modelo, accion, data = []){
      switch(modelo){
        case "pagos":
        {
          switch(accion){
            case 'registrar':
            {
              Utilerias.resetObject(this.pagos);
              this.modal= 1;
              this.tituloModal = 'Registrar pagos de caja chica';
              this.tipoAccion=1;
              break;
            }
            case 'actualizar' :
            {
              Utilerias.resetObject(this.pagos);
              this.tituloModal = 'Actualizar pagos de caja chica';
              this.modal = 1;
              this.tipoAccion = 2;
              break;
            }
          }
        }
      }
    },

    eliminar(data){
      axios.get('caja/chica/eliminar/pago/' + data).then(response => {
        toastr.success('Eliminado Correctamente!!!')
        this.pagos();
      }).catch(e => {
        console.error(e);
      });
    },

  },
  mounted(){
    this.getData();
  }
}
</script>
