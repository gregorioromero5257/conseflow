<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
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

          <button type="button" class="btn btn-success float-sm-right" data-toggle="tooltip" data-placement="top" title="Reporte Excel" @click="ReporteGral()">
            <i class="fas fa-file-excel">&nbsp;</i>
          </button>

          <button type="button" class="btn btn-danger float-sm-right" data-toggle="tooltip" data-placement="top" title="Reporte PDF" @click="ReporteGralPdf()">
            <i class="fas fa-file-pdf">&nbsp;</i>
          </button>

        </div>
        <div class="card-body" id="take">
          <v-server-table :url="'/get/solicitudes/viaticos/'+ empresa" :columns="columns_server" :options="options_server" ref="myTable">

            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a type="button" v-show="props.row.eliminado == 0" @click.prevent="abrirModal('solicitud','actualizar',props.row)" class="dropdown-item" href="#">
                      <i class="icon-pencil"></i>&nbsp;Actualizar Solicitud
                    </a>
                    <!-- v-show="props.row.eliminado == 0" -->
                    <a type="button"  @click.prevent="verdetalle(props.row, 1)" class="dropdown-item" href="#">
                      <i class="fas fa-eye"></i>&nbsp;Ver detalles
                    </a>
                    <!-- v-show="PermisosCrud.Read && props.row.eliminado == 0" -->
                    <a type="button" @click.prevent="eliminar(props.row.id, empresa)" class="dropdown-item" href="#">
                      <i class="fas fa-trash"></i>&nbsp;Eliminar
                    </a>
                    <!-- v-show="props.row.estado < 2 && props.row.eliminado == 0" -->
                    <a type="button"  @click.prevent="enviaRevision(props.row.id, 2)" class="dropdown-item" href="#">
                      <i class="far fa-paper-plane"></i>&nbsp;Cerrar
                    </a>

                  </div>
                </div>
              </div>
            </template>

          </v-server-table>
          <hr>
        </div>
      </div>

      <!-- Inicio del modal agregar/actualizar -->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" arial-hidden="true">
        <div class="modal-dialog modal-dark modal-lg modal-lg-new" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModal"></h4>
              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                <span arial-hidden="true">X</span>
              </button>
            </div>
            <div class="modal-body">
              <vue-element-loading :active="isLoading"/>
              {{solicitud.conceptos}}
              <div class="form-row">
                <div class="form-group col-md-2">
                  <label>&nbsp;Tipo</label>
                  <select class="form-control" v-model="tipo_viatico" v-validate="'required'" data-vv-name="tipo" @change="cambiarTipoViatico()">
                    <option value="0">SINDICATO</option>
                    <option value="1">REEMBOLSO</option>
                    <option value="2">VIATICOS</option>
                  </select>
                  <span class="text-danger">{{errors.first('tipo')}}</span>
                </div>
                <div class="form-group col-md-2">
                  <label>&nbsp;Fecha solicitud</label>
                  <input type="date" name="fecha solicitud" v-model="solicitud.fecha" class="form-control"  data-vv-name="fecha solicitud" v-validate="'required'">
                  <span class="text-danger">{{errors.first('fecha solicitud')}}</span>
                </div>
                <div class="form-group col-md-3">
                  <label>&nbsp;Fecha requerida de pago</label>
                  <input type="date" name="fecha de pago" v-model="solicitud.fecha_pago" class="form-control"  data-vv-name="fecha de pago" v-validate="'required'" >
                  <span class="text-danger">{{errors.first('fecha de pago')}}</span>
                </div>
                <div class="form-group col-md-5">
                  <label>&nbsp;Proyecto</label>
                  <v-select :options="proyectos_empresa"  v-validate="'required'" v-model="solicitud.proyecto_id" label="name" name="proyecto" data-vv-name="proyecto" ></v-select> <!---->
                  <span class="text-danger">{{ errors.first('proyecto') }}</span>
                </div>
              </div>
              <hr>

              <div class="accordion" id="accordion" v-show="tipo_viatico != ''">

                <div class="card">
                  <div class="card-header" id="headingOne">
                    <h5 class="mb-0" >
                      <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        BENEFICIARIOS
                      </button>
                    </h5>
                  </div>
                  <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                    <beneficiario ref="beneficiario" @envioDatosBeneficiario="envioDatosBeneficiario($event)" @limpiarUno="limpiarBeneficiario()"></beneficiario>
                  </div>
                </div>

                <div class="card">
                  <div class="card-header" id="headingTwo">
                    <h5 class="mb-0" >
                      <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                        DETALLE DE VIATICOS
                      </button>
                    </h5>
                  </div>
                  <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body" >
                      <detallesv ref="detallesv" @listado="envioListado($event)"></detallesv>
                    </div>
                  </div>
                </div>

                <div class="card" v-show="tipo_viatico > 0">
                  <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                      <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                        NOTAS DE ITINERIARIO Y LOGISTICA
                      </button>
                    </h5>
                  </div>
                  <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                    <div class="card-body">
                      <div class="form-row">
                        <div class="form-group col-md-2">
                          <label>Origen</label>
                        </div>
                        <div class="form-group col-md-4">
                          <input type="text"  v-model="solicitud.origen" class="form-control" name="origen_destino" data-vv-name="origen_destino"><!--v-validate="'required'"-->
                          <span class="text-danger">{{ errors.first('origen_destino') }}</span>
                        </div>
                        <div class="form-group col-md-2">
                          <label>Destino</label>
                        </div>
                        <div class="form-group col-md-4">
                          <input type="text"  v-model="solicitud.destino" class="form-control" name="destino" data-vv-name="destino"><!--v-validate="'required'"-->
                          <span class="text-danger">{{ errors.first('destino') }}</span>
                        </div>
                      </div><hr>
                      <div class="form-row">
                        <div class="col-md-3 mb-3">
                          <label>Fecha Salida</label>
                          <input type="date" class="form-control"  v-model="solicitud.fecha_salida" name="fecha_salida" data-vv-name="fecha_salida">
                          <span class="text-danger">{{ errors.first('fecha_salida') }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                          <label>Fecha operación</label>
                          <input type="date" class="form-control" v-model="solicitud.fecha_operacion" name="fecha_operacion" data-vv-name="fecha_operacion" >
                          <span class="text-danger"> {{ errors.first('fecha_operacion') }} </span>
                        </div>
                        <div class="col-md-3 mb-3">
                          <label>Fecha retorno</label>
                          <input type="date" class="form-control" v-model="solicitud.fecha_retorno" name="fecha_retorno" data-vv-name="fecha_retorno">
                          <span class="text-danger"> {{ errors.first('fecha_retorno')}} </span>
                        </div>
                      </div>
                      <hr>
                      <div class="form-row">

                        <div class="form-group col-md-4">
                          <label><b>UNIDAD</b></label>
                        </div>
                        <div class="form-group col-md-2">
                          <label><b>PLACAS</b></label>
                        </div>

                        <div class="form-group col-md-4">
                          <label><b>OPERADOR</b></label>
                        </div>
                        <div class="form-group col-md-2">
                          <label><b>ACCIONES</b></label>
                        </div>
                      </div>
                      <li v-for="(vi, index) in vehiculos_itinerario_viaticos" class="list-group-item">
                        <div class="form-row">

                          <div class="form-group col-md-4">
                            <label>{{vi.unidad}}</label>
                          </div>
                          <div class="form-group col-md-2">
                            <label>{{vi.km_inicial}}</label>
                          </div>
                          <div class="form-group col-md-4">
                            <label>{{vi.empleado_operador_name}}</label>
                          </div>
                          <div class="form-group col-md-2">
                            <a @click="deleteu(index)">
                              <span class="fas fa-trash" arial-hidden="true"></span>
                            </a>
                          </div>
                        </div>
                      </li>

                      <div class='form-row'>
                        <div class='form-group col-md-4'>
                          <input type='text'  v-model="vehiculos_temporal.unidad" class='form-control' name="unidad  " data-vv-name="unidad" placeholder='Unidad'>
                        </div>
                        <div class='form-group col-md-2'>
                          <input type='text' v-model="vehiculos_temporal.km_inicial" class='form-control' placeholder='Placas'>
                        </div>
                        <div class='form-group col-md-4'>
                          <v-select v-model='vehiculos_temporal.empleado_operador_id' :options='empleados_empresa' label='name' name='personal_servicio_viaticos_id' data-vv-as='personal_servicio_viaticos_id'></v-select>
                        </div>
                        <div class="form-group col-md-2">
                          <button type="button" class="btn btn-secondary" @click="crear()"><i class="fas fa-plus"></i>&nbsp;Crear</button>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>

                <div class="card" v-show="tipo_viatico > 0">
                  <div class="card-header" id="headingFour">
                    <h5 class="mb-0" >
                      <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                        PERSONAL  DESTINADO AL SERVICIO
                      </button>
                    </h5>
                  </div>
                  <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                    <div class="card-body">
                      <div class="form-row">
                        <div class="form-group col-md-4">
                          <label>Supervisor</label>
                        </div>
                        <div class="form-group col-md-6">
                          <v-select v-model="solicitud.empleado_supervisor_id" :options="empleados_empresa" label="name" name="personal_servicio_viaticos_id"
                          data-vv-as="personal_servicio_viaticos_id" ></v-select>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-4">
                          <label>Nombre del personal que asiste al servicio</label>
                        </div>
                        <div class="form-group col-md-6">
                          <v-select multiple v-model="solicitud.personal_servicio_viaticos_id"
                          :options="empleados_empresa" label="name" name="personal_servicio_viaticos_id" data-vv-as="personal_servicio_viaticos_id"
                          ></v-select>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>

              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Elaboró</label>
                  <v-select v-validate="'required'" v-model="solicitud.empleado_elabora_id"  :options="empleados_empresa"
                  label="name" name="elabora" data-vv-as="elabora"></v-select>
                  <span class="text-danger">{{ errors.first('elabora') }}</span>
                </div>
                <div class="form-group col-md-6">
                  <label>Revisó</label>
                  <v-select v-model="solicitud.empleado_revisa_id"  :options="empleados_empresa" label="name"
                  name="revisa" data-vv-as="revisa" v-validate="'required'"></v-select>
                  <span class="text-danger">{{ errors.first('revisa') }}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Autorizó</label>
                  <v-select v-model="solicitud.empleado_autoriza_id"   :options="empleados_empresa"
                  label="name" name="autoriza" data-vv-as="autoriza" v-validate="'required'"></v-select>
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

    </div>
  </main>
</template>
<script>
const Beneficiarios = r => require.ensure([], () => r(require('./Beneficiarios.vue')), 'via');
const DetallesV = r => require.ensure([], () => r(require('./DetallesV.vue')), 'via');

import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default{
  data(){
    return{
      empresa : 1,

      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      isLoading : false,

      solicitud : {
        id : 0,
        fecha : '',
        fecha_pago : '',
        proyecto_id : '',
        beneficiario : '',
        conceptos : '',

        origen : '',
        destino : '',
        fecha_salida : '',
        fecha_operacion : '',
        fecha_retorno : '',

        empleado_supervisor_id : '',
        personal_servicio_viaticos_id : '',

        empleado_elabora_id : '',
        empleado_revisa_id : '',
        empleado_autoriza_id : '',
      },
      tipo_viatico : '',

      proyectos_empresa : [],
      empleados_empresa : [],
      vehiculos_itinerario_viaticos : [],

      vehiculos_temporal : {
        unidad : '',
        km_inicial : '',
        empleado_operador_id : '',
        empleado_operador_name : '',
      },


      columns_server : ['id','folio','tipo','fecha_solicitud','fecha_pago','transferencia','efectivo','total',
      'beneficiario_nombre','nombre_elabora','estado'],
      tableData : [],
      options_server : {
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        requestAdapter :function (data) {
          var arr = [];
          arr.push({
            'EE.nombre' : data.query.nombre_elabora,
            'EE.ap_materno' : data.query.nombre_elabora,
            'EE.ap_paterno' : data.query.nombre_elabora,
            'EB.nombre' : data.query.beneficiario_nombre,
            'EB.ap_materno' : data.query.beneficiario_nombre,
            'EB.ap_paterno' : data.query.beneficiario_nombre,
            'folio' :data.query.folio,
            'fecha_pago' :data.query.fecha_pago,
            'fecha_solicitud' :data.query.fecha_solicitud,
            'tipo' :data.query.tipo,
            'estado' :data.query.estado,
            'tsv.total' :data.query.total,
            'tsv.efectivo' :data.query.efectivo,
            'tsv.transferencia' :data.query.transferencia,
          });
          data.query = arr[0];
          return data;
        },
        texts:config.texts,
        listColumns: {
          'tipo': [{
            id: 1,
            text: 'REEMBOLSO'
          },
          {
            id: 2,
            text: 'VIATICOS'
          },
          {
            id: 0,
            text: 'SINDICATO'
          },
        ],
        'estado': [{
          id: 0,
          text: 'No Autorizado'
        },
        {
          id: 1,
          text: 'Nuevo'
        },
        {
          id: 2,
          text: 'En Revisión'
        },
        {
          id: 3,
          text: 'En Autorización'
        },
        {
          id: 4,
          text: 'En espera de pagos'
        },
        {
          id: 5,
          text: 'Pagos agendados'
        },
        {
          id: 6,
          text: 'Finalizado'
        },
      ],
    },

  },
}
},
components : {
  'beneficiario' : Beneficiarios,
  'detallesv' : DetallesV,
},
methods : {

  //PETICIONES DE AXIO DE PROYECTOS Y EMPLEADOS DE CONSERFLOW
  getDataCFW(){
    console.log('conserflow');
    this.empleados_empresa = [];
    this.proyectos_empresa = [];

    axios.get('proyectos/viaticos/' + this.empresa).then(response => {
      response.data.forEach((item, i) => {
        this.proyectos_empresa.push({
          id: item.id,
          name : item.nombre_corto,
        });
      });
    }).catch(error => {
      console.error(error);
    });

    axios.get('/empleados/viaticos/' + this.empresa).then(response => {
      response.data.forEach((item, i) => {
        this.empleados_empresa.push({
          id : item.id,
          name : item.nombre + ' ' + item.ap_paterno + ' ' + item.ap_materno,
        });
      });
    }).catch(error => {
      console.error(error);
    });
  },

  //PETICIONES DE AXIO DE PROYECTOS Y EMPLEADOS DE CSCT
  getDataCSCT(){
    console.log('Constructora');

    this.empleados_empresa = [];
    this.proyectos_empresa = [];

    axios.get('proyectos/viaticos/' + this.empresa).then(response => {
      response.data.forEach((item, i) => {
        this.proyectos_empresa.push({
          id: item.id,
          name : item.nombre_corto,
        });
      });
    }).catch(error => {
      console.error(error);
    });

    axios.get('/empleados/viaticos/' + this.empresa).then(response => {
      response.data.forEach((item, i) => {
        this.empleados_empresa.push({
          id : item.id,
          name : item.nombre + ' ' + item.ap_paterno + ' ' + item.ap_materno,
        });
      });
    }).catch(error => {
      console.error(error);
    });
  },

  /**
  ** SE OBTIENE LAS SOLICITUDES DE VIATICOS DE CONSERFLOW
  **/
  buscarCFW(){
    axios.get('solicitud/viaticos/conserflow').then(response => {
      this.tableData = response.data;
    }).catch(error => {
      console.error(error);
    });
    this.getDataCFW();
  },

  /**
  ** SE OBTIENE LAS SOLICITUDES DE VIATICOS DE CSCT
  **/
  buscarCSCT(){
    axios.get('/solicitud/viaticos/csct').then(response => {
      this.tableData = response.data;
    }).catch(error => {
      console.error(error);
    });
    this.getDataCSCT();
  },


  abrirModal(modelo, accion, data = []){
    switch(modelo){
      case "solicitud":
      {
        switch(accion){
          case 'registrar':
          {
            Utilerias.resetObject(this.solicitud);
            this.modal= 1;
            this.diaActual();
            this.tituloModal = this.empresa == 1 ? 'Registrar solicitud de viaticos CONSERFLOW' : this.empresa == 2 ?  'Registrar solicitud de viaticos CONSERFLOW CSCT' : '';
            this.tipoAccion=1;
            break;
          }
          case 'actualizar':
          {
            console.log(data);
            Utilerias.resetObject(this.solicitud);
            this.modal= 1;
            this.tipoAccion = 2;
            var tipo =  data['tipo'] === 'VIATICOS' ? 2 : data['tipo'] === 'REEMBOLSO' ? 1 : data['tipo'] === "SINDICATO" ? '0' : '0';
            this.tipo_viatico = tipo;
            this.solicitud.id = data['id'];
            this.solicitud.fecha = data['fecha_solicitud'];
            this.solicitud.fecha_pago = data['fecha_pago'];
            this.solicitud.proyecto_id = {id : data['proyecto_id'], name :  data['nombre_proyecto']};
            var childBeneficiarioSend = this.$refs.beneficiario;
            childBeneficiarioSend.setDatos(data['id'], tipo, this.empresa);

            var ChilDetallesvSend = this.$refs.detallesv;
            ChilDetallesvSend.setDatos(data['id'], tipo, this.empresa);

            this.solicitud.origen = data['origen'];
            this.solicitud.destino = data['origen'];
            this.solicitud.fecha_salida = data['fecha_salida'];
            this.solicitud.fecha_operacion = data['fecha_operacion'];
            this.solicitud.fecha_retorno = data['fecha_retorno'];

            this.solicitud.empleado_supervisor_id = {id : data['empleado_supervisor_id'], name : data['nombre_supervisor']};
            this.solicitud.personal_servicio_viaticos_id = JSON.parse(data['personal_servicio']);

            this.solicitud.empleado_elabora_id =  {id : data['empleado_elabora_id'], name : data['nombre_elabora']};
            this.solicitud.empleado_revisa_id = {id : data['empleado_revisa_id'], name : data['nombre_revisa']};
            this.solicitud.empleado_autoriza_id = {id : data['empleado_autoriza_id'], name : data['nombre_autoriza']};

            this.vehiculos_itinerario_viaticos = JSON.parse(data['vehiculos']);
            break;
          }
        }
      }
    }
  },

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

  cerrarModal(){
    this.modal = 0;
  },

  cambiarTipoViatico(){
    var childBeneficiario = this.$refs.beneficiario;
    childBeneficiario.getDatos(this.empresa, this.tipo_viatico, this.empleados_empresa);

    var ChilDetallesv = this.$refs.detallesv;
    ChilDetallesv.getDatos(this.tipo_viatico);

    this.solicitud.beneficiario = '';
    this.solicitud.conceptos = '';
  },

  /**
  ** CAPTURA EL ENVIO DE DATOS DEL COMPONENTE HIJO Beneficiarios.vue
  **/
  envioDatosBeneficiario(data){
    this.solicitud.beneficiario = data;
  },

  envioListado(data){
    this.solicitud.conceptos = data;
  },

  limpiarBeneficiario(){
    this.solicitud.beneficiario = '';
  },

  crear(){
    if (this.vehiculos_temporal.unidad == '' ||
    this.vehiculos_temporal.km_inicial == '' ||
    (this.vehiculos_temporal.empleado_operador_id  == '' || this.vehiculos_temporal.empleado_operador_id  == null)) {
      toastr.warning('Atención','Ingrese todos los campos de los vehiculos a utilizar');
    }else {

      let me = this;
      me.vehiculos_itinerario_viaticos.push({
        unidad : me.vehiculos_temporal.unidad,
        km_inicial : this.vehiculos_temporal.km_inicial,
        empleado_operador_id : this.vehiculos_temporal.empleado_operador_id.id,
        empleado_operador_name : this.vehiculos_temporal.empleado_operador_id.name,
      });

      me.vehiculos_temporal.unidad = '';
      me.vehiculos_temporal.km_inicial = '';
      me.vehiculos_temporal.empleado_operador_id = '';
      me.vehiculos_temporal.empleado_operador_name = '';
    }

  },

  /**
  ** Registro y actuaizacion de datos mediante una peticion axios
  **/
  guardar(nuevo){


    this.$validator.validate().then(result => {
      if (result) {
        //EVALUAR QUE SE REGISTRE UN BENEFICIARIO
        if (this.solicitud.beneficiario === '' || this.solicitud.beneficiario[0].id === '') {
          toastr.warning('Debe registrar un beneficiario !!!');
          return;
        }
        else if (this.solicitud.beneficiario[0].dbemp_id == '') {
          toastr.warning('Debe registrar un banco !!!');
          return;
        }
        //EVALUAR QUE EXISTA CONCEPTOS
        else if (this.solicitud.conceptos === '') {
          toastr.warning('Debe registrar al menos un concepto !!!');
          return;
        }
        else {
          this.isLoading = true;
          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? 'create/solicitudes/viaticos' : 'update/solicitudes/viaticos',
            data : {
              solicitud : this.solicitud,
              empresa : this.empresa,
              tipo :this.tipo_viatico,
              vehiculos : this.vehiculos_itinerario_viaticos,
            }
          }).then(response => {
            if (response.data.status == true) {
              if (this.empresa == 1) {
                this.buscarCFW();
              }else if (this.empresa == 2) {
                this.buscarCSCT();
              }
              this.modal = 0;
              this.$refs.myTable.refresh();
            }else {
              toastr.error('Ha ocurrido un error contacte al administrador !!!');
            }
            this.isLoading = false;
          }).catch(e => {
            this.isLoading = false;
            toastr.error('Ha ocurrido un error contacte al administrador !!!');
          });
        }
      }else {
        toastr.warning('Complete los campos requeridos !!!');
      }
    });

  }


},
mounted(){
  //MOTANDO DATA SEGUN EMPRESA
  if (this.empresa == 1) {
    this.buscarCFW();
    // this.getDataCFW();
  }else if (this.empresa == 2) {
    this.buscarCSCT();
    // this.getDataCSCT();
  }

}
}

</script>
<style >
.modal-lg-new {
  max-width: 1000px;
}
. table
{
  Overflow :scroll! important ;
}
</style>
