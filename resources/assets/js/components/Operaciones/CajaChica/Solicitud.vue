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
          <button type="button" class="btn btn-dark float-sm-right" @click="abrirModal('solicitud','registrar')">
            <i class="fas fa-plus">&nbsp;</i>Nuevo
          </button>

        </div>
        <div class="card-body">



          <v-client-table :columns="columns" :options="options" :data="tableData" ref="myTable">

            <template slot="id" slot-scope="props" >

              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>&nbsp;
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button"  v-if="props.row.estado == 0" @click="abrirModal('solicitud','actualizar',props.row)" class="dropdown-item" >
                      <i class="fas fa-pencil-ruler"></i>&nbsp;Actualizar
                    </button>
                    <button type="button" v-if="props.row.estado == 0"  @click="enviaRevision(props.row)" class="dropdown-item" >
                      <i class="fas fa-mail-bulk"></i>&nbsp;Enviar Revision
                    </button>
                    <button type="button" v-if="props.row.estado > 0"  @click="comprobaciones(props.row)" class="dropdown-item" >
                      <i class="fas fa-mail-bulk"></i>&nbsp;Comprobaciones
                    </button>

                  </div>
                </div>
              </div>
            </template>
            <!-- <template slot="folio" slot-scope="props">
              {{props.row.id}}
            </template> -->

            <template slot="descarga" slot-scope="props">
              <button type="button" @click="descargar(props.row.id)" class="btn btn-outline-dark" >
                <i class="fas fa-download"></i>&nbsp;<i class="fas fa-file-pdf"></i>
              </button>
            </template>

          </v-client-table>
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
                  <label>&nbsp;Fecha solicitud</label>
                  <input type="date" name="fecha solicitud" v-model="solicitud.fecha" class="form-control"  data-vv-name="fecha solicitud" ><!---->
                  <span class="text-danger">{{errors.first('fecha solicitud')}}</span>
                </div>
              </div>
              <hr>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>&nbsp;Beneficiario uno</label>
                  <v-select :options="vs_options" v-model="solicitud.beneficiario_id" label="name" v-validate="'required'" data-vv-name="Beneficiario" @input="buscar_uno()" ></v-select>
                  <span class="text-danger">{{errors.first('Beneficiario')}}</span>
                </div>
                <div class="form-group col-md-6">
                  <!-- {{solicitud}} -->
                  <label>&nbsp;Banco beneficiario</label>
                  <select class="form-control" name="datos bancarios" v-model="solicitud.datos_bancos_beneficiario"  @change="enviar_uno($event)" data-vv-name="Proyecto" >
                    <option v-for="item in listaDatosBancariosuno" :value="item.id" :key="item.id">{{ item.bnombre }}</option>
                    <option value="0">OTRO</option>
                  </select>
                  <span class="text-danger">{{ errors.first('datos bancarios') }}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6 ">
                  <label>CUENTA</label>
                  <input type="text" v-model="solicitud.cuentauno" class="form-control" >
                </div>
                <div class="form-group col-md-6 ">
                  <label>CLABE</label>
                  <input type="text" v-model="solicitud.claveuno" class="form-control" >
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6 ">
                  <label>TARJETA</label>
                  <input type="text" v-model="solicitud.clavecuentatarjetauno" class="form-control" >
                </div>
                <template v-if="solicitud.datos_bancos_beneficiario === '0'">
                  <div class="form-group col-md-6 ">
                    <label>BANCO</label>
                    <input type="text" v-model="solicitud.banco" class="form-control" >
                  </div>
                </template>
                <div class="form-group col-md-6">
                  <button type="button" class="btn btn-secondary" @click="quitar_uno()"><i class="fas fa-trash"></i>&nbsp;Limpiar</button>
                </div>
              </div>
              <hr>
              <!-- detalles de solicitud -->
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label><b>Concepto</b></label>
                </div>
                <div class="form-group col-md-2">
                  <label><b>T.E (Transferencia Electronica)</b></label>
                </div>

                <div class="form-group col-md-2">
                  <label><b>Efectivo</b></label>
                </div>

                <div class="form-group col-md-2">
                  <label><b>Total</b></label>
                </div>
                <div class="form-group col-md-2">
                  <label><b>ACCIONES</b></label>
                </div>
              </div>
              <li v-for="(vi, index) in listado.conceptos" class="list-group-item">
                <div class="form-row">

                  <div class="form-group col-md-4">
                    <label>{{listado.conceptos[index]}}</label>
                  </div>
                  <div class="form-group col-md-2">
                    <label>{{listado.tranferencia[index]}}</label>
                  </div>
                  <div class="form-group col-md-2">
                    <label>{{listado.efectivo[index]}}</label>
                  </div>
                  <div class="form-group col-md-2">
                    <label>{{parseFloat(listado.tranferencia[index]) + parseFloat(listado.efectivo[index])}}</label>
                  </div>
                  <div class="form-group col-md-2">
                    <a @click="deleteu(index)">
                      <span class="fas fa-trash" arial-hidden="true"></span>
                    </a>
                  </div>
                </div>
              </li>

              <div class='form-row'>
                <div class='form-group col-md-4' v-if="Object.keys(listado.conceptos).length == 0">
                  <input type='text'  v-model="listado_temporal.conceptos" class='form-control' placeholder='Concepto'>
                </div>
                <div class='form-group col-md-2' v-if="Object.keys(listado.conceptos).length == 0">
                  <input type='number'  v-model="listado_temporal.tranferencia" class='form-control' placeholder='Tranferencia'>
                </div>
                <div class='form-group col-md-2' v-if="Object.keys(listado.conceptos).length == 0">
                  <input type='number' v-model="listado_temporal.efectivo" class='form-control' placeholder='Efectivo'>
                </div>
                <div class='form-group col-md-2' v-if="Object.keys(listado.conceptos).length == 0">
                  &nbsp;
                  <!-- <input type='number' v-model="listado_temporal.efectivo" class='form-control' placeholder='KM inicial'> -->
                </div>
                <div class="form-group col-md-2">
                  <!-- {{Object.keys(listado.conceptos).length}} -->
                  <button type="button" class="btn btn-secondary" v-if="Object.keys(listado.conceptos).length == 0" @click="crear()"><i class="fas fa-plus"></i>&nbsp;Crear</button>
                </div>
              </div>

              <hr>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Revisó</label>
                  <v-select v-model="solicitud.empleado_revisa_id"  :options="vs_options_uno" v-validate="'required'" label="name" name="revisa" data-vv-as="revisa"></v-select><!--v-validate="'required'"-->
                  <span class="text-danger">{{ errors.first('revisa') }}</span>
                </div>
                <div class="form-group col-md-6">
                  <label>Autorizó</label>
                  <v-select v-model="solicitud.empleado_autoriza_id"  v-validate="'required'" :options="vs_options_uno" label="name" name="autoriza" data-vv-as="autoriza"></v-select>
                  <span class="text-danger">{{ errors.first('autoriza') }}</span>
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

      <div v-show="estado == 1">
        <comprobaciones ref="comprobaciones" @comprobaciones:change="maestro"></comprobaciones>
      </div>



    </div>
  </main>
</template>
<script>

const Comprobaciones = r => require.ensure([], () => r(require('./Comprobaciones.vue')), 'opera');
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default{
  data () {
    return {
      estado : 0,
      isLoading : false,
      empresa : 0,
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
      vs_options_uno : [],
      tipoAccion : 0,
      modal : 0,
      tituloModal : '',
      listaDatosBancariosuno : [],
      columns: ['id','folio','fecha','conceptos','efectivo','tranferencia','estado','descarga'],
      tableData: [],
      options: {
          headings: {
              'id': 'Acciones',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['fecha','folio','conceptos','efectivo','tranferencia'],
          filterable: ['fecha','folio','conceptos','efectivo','tranferencia'],
          filterByColumn: true,
          texts:config.texts
      },
    }
  },
  components : {
    'comprobaciones' : Comprobaciones,
  },
  methods : {
    getEmpleado(){
      this.vs_options = [];
      axios.get('caja/chica/get/empleado').then(response => {

        this.vs_options.push({
          id : response.data.usuario.id,
          name : response.data.usuario.nombre + ' ' + response.data.usuario.ap_paterno + ' ' + response.data.usuario.ap_materno,
        });

        response.data.empleados.forEach((item, i) => {
          this.vs_options_uno.push({
            id : item.id,
            name : item.nombre + ' ' + item.ap_paterno + ' ' + item.ap_materno,
          });
        });

      }).catch(e => {
        console.error(e);
      })
    },

    maestro(){
      this.estado = 0;
    },

    comprobaciones(data){
      this.estado = 1;
      var childComprobacion = this.$refs.comprobaciones;
      childComprobacion.cargar(data,'solicitud');
    },

    /*//
    *Cargando datos principales para el formulario
    **/
    getData(){

    },
    /******/
    buscarCFW(){
      this.tableData = [];
      // this.vs_options = [];
      this.optionsvs = [];

      axios.get('get/listado/caja/chica/cfw').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });

      axios.get('/empleados/viaticos/' + this.empresa).then(response => {
        for (var i = 0; i < response.data.length; i++) {
          // this.vs_options.push({
          //   id : response.data[i].id,
          //   name : response.data[i].nombre + ' ' + response.data[i].ap_paterno + ' ' + response.data[i].ap_materno,
          // });
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

      axios.get('get/listado/caja/chica/csct').then(response => {
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
    abrirModal(modelo, accion, data = []){
      if (this.empresa == 0) {
        toastr.warning('Seleccione una empresa');
      }else {
        switch(modelo){
          case "solicitud":
          {
            switch(accion){
              case 'registrar':
              {
                Utilerias.resetObject(this.solicitud);
                this.modal= 1;
                this.diaActual();
                this.tituloModal = 'Registrar solicitud de caja chica';
                this.tipoAccion=1;
                this.listado.conceptos =  [];
                break;
              }
              case 'actualizar' :
              {
                // console.log(data);
                Utilerias.resetObject(this.solicitud);
                this.tituloModal = 'Actualizar solicitud de caja chica';
                this.modal = 1;
                this.tipoAccion = 2;
                  this.solicitud.id = data['id'];
                  this.solicitud.fecha = data['fecha'];
                  this.solicitud.empleado_revisa_id = {id : data['empleado_revisa_id'] , name : data['empleado_revisa']} ;
                  this.solicitud.empleado_autoriza_id = {id : data['empleado_autoriza_id'], name : data['empleado_autoriza']};
                  this.solicitud.beneficiario_id = {id : data['beneficiario_id'] , name : data['beneficiario']};
                  this.solicitud.datos_bancos_beneficiario = data['datos_bancos_beneficiario'];
                  this.solicitud.cuentauno = data['cuentauno'];
                  this.solicitud.claveuno = data['claveuno'];
                  this.solicitud.banco = data['banco'];
                  this.solicitud.clavecuentatarjetauno = data['clavecuentatarjetauno'];

                    this.listado.tranferencia = [data['tranferencia']];
                    this.listado.efectivo = [data['efectivo']];
                    this.listado.conceptos = [data['conceptos']];

                break;
              }
            }
          }
        }
      }
    },

    /****/
    cerrarModal(){
      this.modal = 0
    },

    /****/
    guardar(nuevo){
      if (Object.keys(this.listado.conceptos).length == 0) {
        toastr.warning('Escriba la cantidad de caja chica!!!');
      }else if (this.solicitud.beneficiario_id === '') {
        toastr.warning('Seleccione el beneficiario !!!');
      }else if (this.solicitud.datos_bancos_beneficiario === '') {
        toastr.warning('Seleccione el banco a depositar !!!');
      }else if (this.solicitud.cuentauno === '') {
        toastr.warning('Escriba la cuenta o digite 0 !!!');
      }else if (this.solicitud.claveuno === '') {
        toastr.warning('Escriba la clabe o digite 0 !!!');
      }else if (this.solicitud.banco === '') {
        toastr.warning('Escriba el banco o digite 0 !!!');
      }else if (this.solicitud.clavecuentatarjetauno === '') {
        toastr.warning('Escriba el numero de tarjeta o digite 0 !!!');
      }else {
        this.$validator.validate().then(result =>{
          if (result) {
            let me = this;

            axios({
              method : nuevo ? 'POST' : 'PUT',
              url : nuevo ? 'caja/chica/guardar/solicitud/' : 'caja/chica/actualizar/solicitud/',
              data : {
                id : this.solicitud.id,
                fecha : this.solicitud.fecha,
                empleado_revisa_id :this.solicitud.empleado_revisa_id.id,
                empleado_autoriza_id :this.solicitud.empleado_autoriza_id.id,
                beneficiario_id :this.solicitud.beneficiario_id.id,
                datos_bancos_beneficiario :this.solicitud.datos_bancos_beneficiario,
                cuentauno :this.solicitud.cuentauno,
                claveuno :this.solicitud.claveuno,
                banco :this.solicitud.banco,
                clavecuentatarjetauno :this.solicitud.clavecuentatarjetauno,
                listado :this.listado,
                empresa : this.empresa,
              }
            }).then(response => {
              this.modal = 0;
              this.limpiaInputs();
              toastr.success(nuevo ? 'Creado Correctamente!!' : 'Actualizado Correctamente!!!')
              if(this.empresa == 1){
                this.buscarCFW();
              }else if (this.empresa == 2) {
                this.buscarCSCT();
              }
              this.deleteu(0);

            }).catch(e => {
              console.error(e);
            });
          }
        });
      }
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

      if (me.solicitud.beneficiario_id != null) {
      axios.get('/datosbanemp/' + me.solicitud.beneficiario_id.id + '&' + me.empresa + '/datosbanemp').then(response => {
        me.listaDatosBancariosuno = response.data;
      });
    }
    },

    /******/
    enviar_uno(e = null){
      // console.log(e,'ss');
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
      // this.solicitud.datos_bancos_beneficiario = '';
      this.solicitud.clavecuentatarjetauno = '';
      this.solicitud.claveuno = '';
      this.solicitud.cuentauno = '';
      this.solicitud.banco = '';
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

  },
  mounted(){
    this.getData();
    this.getEmpleado();
  }
}
</script>
