<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->

      <br>
      <div class="card" v-show="!detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Solicitud De Permisos
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
                    <button type="button" @click="cargarsolicitud(props.row.empleado)" class="dropdown-item">
                      <i class="fas fa-eye"></i> &nbsp; Ver permisos
                    </button>
                  </div>
                </div>
              </div>
            </template>
          </v-client-table>

        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
      <!-- Listado de solicituds del empleado -->
      <br>
      <div class="card" v-show="detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Permisos pertenecientes a : {{empleado == null ? '': empleado.nombre + ' '+ empleado.ap_paterno + ' '+ empleado.ap_materno}}
          <button type="button" v-bind:disabled="desabilitado == 1" @click="abrirModal('solicitud','registrar', empleado.id)" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
          <!-- v-bind:disabled="desabilitado == 1" -->
          <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
            <i class="fa fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoadingDetalle"/>
          <v-client-table :columns="columnssolicitud" :data="tableDatasolicitud" :options="optionssolicitud" ref="myTablesolicitud">
            <!-- Como usar un if cuando se tiene tres condiciones-->
            <template slot="condicion" slot-scope="props" >
              <template v-if="props.row.condicion == 1">
                <button type="button" class="btn btn-success">Autorizado</button>
              </template>
              <template v-if="props.row.condicion == 0">
                <button type="button" class="btn btn-info">En espera</button>
              </template>
              <template v-if="props.row.condicion == 2">
                <button type="button" class="btn btn-danger">No Autorizado</button>
              </template>
            </template>

            <template slot="goce" slot-scope="props" >
              <template v-if="props.row.goce == 1">
                <button type="button" class="btn btn-success">Si</button>
              </template>
              <template v-if="props.row.goce == 0">
                <button type="button" class="btn btn-danger">No</button>
              </template>
            </template>

            <template slot="tipo_salida" slot-scope="props" >
              <template v-if="props.row.tipo_salida == 1">
                <button type="button" class="btn btn-outline-secondary">Dia Completo</button>
              </template>
              <template v-if="props.row.tipo_salida == 0">
                <button type="button" class="btn btn-outline-warning">Temporal</button>
              </template>
            </template>

            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <template v-if="props.row.condicion == 0">
                      <button type="button" @click="abrirModal('solicitud','actualizar',empleado.id,props.row)" class="dropdown-item">
                        <i class="icon-pencil"></i>&nbsp; Actualizar Permiso
                      </button>
                    </template>
                    <template v-if="props.row.condicion == 1">
                      <button v-if="props.row.formato_permiso == null" type="button" class="dropdown-item" @click="descargar(props.row)">
                        <i class="icon-cloud-download"></i>Descargar  Formato Autorizado
                      </button>
                      <button v-if="props.row.formato_permiso == null" type="button" @click="abrirModal('solicitud','subir',empleado.id,props.row)" class="dropdown-item">
                        <i class="icon-pencil"></i>&nbsp; Actualizar Formato Firmado
                      </button>
                      <button v-if="props.row.formato_permiso != null" type="button" class="dropdown-item" @click="descargarFormato(props.row.formato_permiso)">
                        <i class="fas fa-file-download"></i>&nbsp; Descargar Formato
                      </button>
                    </template>
                  </div>
                </div>
              </div>
            </template>
          </v-client-table>
        </div>
      </div>
      <!-- Fin Listado de escolaridades del empleado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <vue-element-loading :active="isLoadingDetalle"/>
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
              <input type="hidden" name="id">
              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="fecha_solicitud">Fecha Solicitud</label>
                <div class="col-md-9">
                  <input type="date" v-validate="'required'" name="fecha_solicitud" v-model="solicitud.fecha_solicitud" class="form-control" placeholder="fecha_solicitud"  autocomplete="off" data-vv-as="Fecha Solicitud" >
                  <span class="text-danger">{{ errors.first('fecha_solicitud') }}</span>
                </div>
              </div>

              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="motivo">Motivo</label>
                <div class="col-md-9">
                  <input type="text"  name="motivo" v-model="solicitud.motivo" class="form-control" placeholder="Motivos" autocomplete="off" id="motivo" data-vv-as="Motivo" v-validate="'required'">
                  <span class="text-danger">{{ errors.first('motivo') }}</span>
                </div>
              </div>
              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label " for="tipo_salida" >Tipo de Salida</label>
                <div class="col-md-9">


                  <select name="tipo_salida" v-model="solicitud.tipo_salida" class="form-control" placeholder="off" autocomplete="Seleccionar" data-vv-as="tipo_salida" v-validate="'required'">

                    Temporal

                    <option value="0">Temporal</option>
                    <option value="1">Dia Completo</option>
                  </select>
                  <span class="text-danger">{{ errors.first('tipo_salida') }}</span>
                </div>
              </div>

              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="fech_temp">Fecha de Salida Temporal</label>
                <div class="col-md-9">
                  <input type="date"  v-model="solicitud.fech_temp" class="form-control" placeholder="fech_temp" autocomplete="off" data-vv-as="Fecha Temporal" :disabled ="disabledcompleto()">
                  <span class="text-danger">{{ errors.first('fech_temp') }}</span>
                </div>
              </div>
              <!-- :disabled ="disabledtemporal()" -->
              <div class="form-group row" v-if="tipoAccion == 3">
                <label class="col-md-3 form-control-label" for="formato_permiso">Formato Permiso</label>
                <div class="col-md-6">
                  <label v-if="tipoAccion ==3" :class="ClassL_a">
                    <i class="fas fa-cloud-upload-alt"></i>&nbsp;{{BtnL_a2}}
                    <input type="file" accept="application/pdf" id="file_formato" style="display: none;" class="form-control" v-on:change="onChangeFormato">
                  </label>
                </div>
              </div>

              <!-- Habilitar e Inhabilitar  > -->
              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="fecha_inicio">Fecha Inicio</label>
                <div class="col-md-9">
                  <input type="date"  v-model="solicitud.fecha_inicio" class="form-control" placeholder="fecha_inicio" autocomplete="off" data-vv-as="Fecha Inicio" :disabled ="disabledtemporal()">
                  <span class="text-danger">{{ errors.first('fecha_inicio') }}</span>
                </div>
              </div>
              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="fecha_fin">Fecha Fin</label>
                <div class="col-md-9">
                  <input type="date" name="fecha_fin" v-model="solicitud.fecha_fin" class="form-control" placeholder="fecha_fin" autocomplete="off" data-vv-as="Fecha Fin" :disabled ="disabledtemporal()" @change="validar">
                  <span class="text-danger">{{ errors.first('fecha_fin') }}</span>
                </div>
              </div>
              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="hora_salida">Hora Salida</label>
                <div class="col-md-9">
                  <input type="time" name="hora_salida" v-model="solicitud.hora_salida" class="form-control" placeholder="Hora Salida" autocomplete="off" data-vv-as="Horario Salida" :disabled ="disabledcompleto()">
                  <span class="text-danger">{{ errors.first('hora_salida') }}</span>
                </div>
              </div>
              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="hora_regreso">Hora Regreso</label>
                <div class="col-md-9">
                  <input type="time"  name="hora_regreso" v-model="solicitud.hora_regreso" class="form-control" placeholder="Hora Regreso" autocomplete="off" data-vv-as="Horario Regreso" :disabled ="disabledcompleto()">
                  <span class="text-danger">{{ errors.first('hora_regreso') }}</span>
                </div>
              </div>
              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label " for="condicion" >Estado</label>
                <div class="col-md-9">
                  <select name="estado" v-model="solicitud.condicion" class="form-control" placeholder="Estado" autocomplete="off" data-vv-as="Estado" disabled >

                    <option value="1">Autorizado</option>
                    <option value="0">En Espera</option>
                    <option value="2">No Autorizado</option>
                  </select>
                  <span class="text-danger">{{ errors.first('estado') }}</span>
                </div>
              </div>
              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="solicita_empleado_id">Empleado solicitante</label>
                <div class="col-md-9">
                  <select class="form-control" id="solicita_empleado_id"  name="solicita_empleado_id" v-model="solicitud.solicita_empleado_id" v-validate="'excluded:0'" data-vv-as="Solicita" disabled>
                    <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{ item.empleado.nombre }} {{ item.empleado.ap_paterno }} {{ item.empleado.ap_materno }}</option>
                  </select>
                  <span class="text-danger">{{ errors.first('solicita_empleado_id') }}</span>
                </div>
              </div>
              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="autoriza_empleado_id">Autoriza</label>
                <div class="col-md-9">
                  <select class="form-control" id="autoriza_empleado_id"  name="autoriza_empleado_id" v-model="solicitud.autoriza_empleado_id"  data-vv-as="Autoriza" v-validate="'excluded:0'" disabled>
                    <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{ item.empleado.nombre+' ' + item.empleado.ap_paterno+' ' + item.empleado.ap_materno }}</option>
                  </select>
                  <span class="text-danger">{{ errors.first('autoriza_empleado_id') }}</span>

                </div>
              </div>
            </div>
          </div>
          <!-- </form> -->

          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarsolicitud(1)"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar</button>
            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarsolicitud(0)"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Actualizar</button>
            <button type="button" v-if="tipoAccion==3" class="btn btn-secondary" @click="guardarsolicitud(1)"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Actualizar</button>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->

    <!--Fin del modal-->
  </main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      url: '/permisos',
      empleado: null,
      desabilitado: 0,
      detalle: false,
      solicitud: {
        id: 0,
        fecha_solicitud :'',
        motivo :'',
        formato_permiso :'',
        fecha_inicio :'',
        fecha_fin :'',
        tipo_salida : 0,
        fech_temp : '',
        hora_salida :'',
        hora_regreso:'',
        goce: 0,
        condicion : 0,
        solicita_empleado_id: 0,
        autoriza_empleado_id: 0,
      },
      Metodo: '',
      ClassL_a: 'btn btn-info',
      BtnL_a2: 'Subir Archivo',
      listaEmpleados: [],
      fechavalidar: 0,
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      disabled: 0,
      isLoading: false,
      isLoadingDetalle: false,
      columns: [
        'empleado.id',
        'empleado.nombre',
        'empleado.ap_paterno',
        'empleado.ap_materno',
        'puesto.nombre',
        'departamento.nombre',
      ],
      tableData: [],
      columnssolicitud: ['id','fecha_solicitud','NombreSolicita','motivo','tipo_salida','goce','condicion',,'NombreAutoriza','fecha_inicio','fecha_fin'],
      tableDatasolicitud: [],
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
      optionssolicitud: {
        headings: {
          id: 'Acciones',
          fecha_solicitud: 'Fecha Solicitud',
          motivo: 'Motivo',
          formato_permiso: 'Adjunto',
          fecha_inicio: 'Fecha Inicio',
          fecha_fin: 'Fecha Fin',
          tipo_salida: 'Permiso',
          hora_salida: 'Hr. Salida',
          hora_regreso: 'Hr. Regreso',
          goce: 'Goce De Sueldo',
          total_dias: 'Días Totales',
          condicion: 'Estado',
          NombreAutoriza : 'Autoriza',
          NombreSolicita : 'Solicita',

          autoriza_empleado_id: 'Autoriza',
          solicita_empleado_id: 'Solicitante',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha_solicitud','formato_permiso','fecha_inicio','fecha_fin','tipo_salida','goce','condicion','NombreAutoriza','NombreSolicita'],
        filterable: ['fecha_solicitud','formato_permiso','fecha_inicio','fecha_fin','tipo_salida','goce','condicion','NombreAutoriza','NombreSolicita'],
        filterByColumn: true,
        listColumns: {
          condicion: [{
            id: 1,
            text: 'Autorizado'
          },
          {
            id: 0,
            text: 'En Espera'
          },
          {
            id: 2,
            text: 'No Autorizado'

          }
        ],
        goce: [{
          id:1,
          text: 'Si'
        },
        {
          id: 0,
          text: 'No'
        }
      ],
      tipo_salida: [{

        id: 1,
        text: 'Dia Completo'
      },
      {
        id: 0,
        text: 'Temporal'
      }
    ]
  },
  texts:config.texts
},
}
},
computed:{
},
methods : {
  validar(){
    var a = this.solicitud.fecha_inicio;
    var b = this.solicitud.fecha_fin;
    var fechaIngreso = new Date(a).getTime();
    var fechaFin    = new Date(b).getTime();
    var diff = fechaFin - fechaIngreso;
    var diferencia =diff/(1000*60*60*24);
    if (diferencia < 0) {
      this.fechavalidar = 1;
      toastr.warning('no valido');
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

    if(dd<10) {
      dd='0'+dd
    }

    if(mm<10) {
      mm='0'+mm
    }

    hoy = yyyy+'-'+mm+'-'+dd;
    this.solicitud.fecha_solicitud = hoy;
  },
  getData() {
    let me=this;
    axios.get('/empleado').then(response => {
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
  },
  AutorizaEmpleado (id) {
    let me=this;
    axios.get('/vacaciones/'+id+'/autoriza').then(response => {
      //  me.listaEmpleadosAutoriza = response.data;
      this.solicitud.autoriza_empleado_id = response.data.id;

    })
    .catch(function (error) {
      console.log(error);
    });
  },

  descargar(data) {
    console.log(data);
    window.open('descargar-permisosemp/' + data.id, '_blank');
  },
  descargarFormato(archivo){
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
      axios.delete(me.url + '/' + archivo)
      .then(response => {
      })
      .catch(function (error) {
        console.log(error);
      });
      //--fin del metodo borrar--//
    });
  },
  onChangeFormato(e){
    var nombreF = e.target.files[0].name;
    var extension =  nombreF.split('.').pop();
    if (extension == 'pdf')
    {
      this.ClassL_a = 'btn btn-warning';
      this.solicitud.formato_permiso = e.target.files[0];
      this.BtnL_a2 = 'Archivo Cargado';
    }
    else
    {
      this.ClassL_a = 'btn btn-danger';
      this.BtnL_a2 = 'Archivo Invalido(Omitido)';
    }
  },


  guardarsolicitud(nuevo){
    if (this.fechavalidar == 0) {
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoadingDetalle = true;
          let me = this;
          let formData = new FormData();

          if(this.Metodo == 'Actualizar' || this.Metodo == 'Nuevo') {

            axios({
              method: nuevo ? 'POST' : 'PUT',
              url: nuevo ? me.url : me.url+'/'+this.solicitud.id,

              data: {
                'metodo':this.Metodo,
                'id': this.solicitud.id,

                'fecha_solicitud': this.solicitud.fecha_solicitud,

                'motivo': this.solicitud.motivo,

                'formato_permiso':this.solicitud.formato_permiso,

                'fecha_inicio': this.solicitud.fecha_inicio,

                'fecha_fin': this.solicitud.fecha_fin,

                'tipo_salida': this.solicitud.tipo_salida,

                'fech_temp' : this.solicitud.fech_temp,

                'hora_salida': this.solicitud.hora_salida,

                'hora_regreso': this.solicitud.hora_regreso,

                // 'goce': this.solicitud.goce,

                'condicion': this.solicitud.condicion,

                'solicita_empleado_id': this.solicitud.solicita_empleado_id,

                'autoriza_empleado_id': this.solicitud.autoriza_empleado_id,
              }
            }).then(function (response) {
              if (response.data.status) {
                me.isLoadingDetalle = false;
                me.cerrarModal();
                me.cargarsolicitud(me.empleado);
                if(!nuevo){
                  toastr.success('Permiso Actualizado Correctamente');
                }
                else
                {
                  toastr.success('Permiso Registrado Correctamente');


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
          else{
            formData.append('metodo', this.Metodo);
            formData.append('condicion',this.solicitud.condicion);
            formData.append('fecha_solicitud',this.solicitud.fecha_solicitud);
            formData.append('motivo',this.solicitud.motivo);
            formData.append('formato_permiso',this.solicitud.formato_permiso);
            formData.append('fecha_inicio',this.solicitud.fecha_inicio);
            formData.append('fecha_fin',this.solicitud.fecha_fin);

            formData.append('tipo_salida',this.solicitud.tipo_salida);
            formData.append('fech_temp',this.solicitud.fech_temp);
            formData.append('hora_salida',this.solicitud.hora_salida);
            formData.append('hora_regreso',this.solicitud.hora_regreso);
            // formData.append('goce',this.solicitud.goce);

            formData.append('solicita_empleado_id',this.solicitud.solicita_empleado_id);
            formData.append('autoriza_empleado_id',this.solicitud.autoriza_empleado_id);
            formData.append('id',this.solicitud.id);

            axios.post( me.url,formData).then(function (response) {
              if (response.data.status) {
                me.isLoadingDetalle = false;
                me.cerrarModal();
                me.cargarsolicitud(me.empleado);
                if(!nuevo){
                  toastr.success('Solicitud Permiso Registrada Correctamente');
                }
                else
                {
                  toastr.success('Solicitud Permiso Registrada Correctamente');

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
        }

      });
    }
  },
  disabledtemporal(){
    if(this.solicitud.tipo_salida == 1 ){
      this.disabledt = 1 ;
      this.solicitud.hora_salida ='';
      this.solicitud.hora_regreso ='';
      this.solicitud.fech_temp ='';
    }
    else{
      if(this.solicitud.tipo_salida == 0 ){
        this.disabledt = 0;

        this.solicitud.fecha_inicio ='';
        this.solicitud.fecha_fin ='';

      }
    }

    if (this.disabledt == 0)
    {return true;
    }
    else{
      if(this.gender == 1){
        return false;
      }
    }
  }
  ,
  disabledcompleto(){

    if(this.solicitud.tipo_salida == 1){
      this.disabledc =0 ;
    }
    else{
      if(this.solicitud.tipo_salida == 0){
        this.disabledc = 1;
      }
    }

    if (this.disabledc == 0)
    {return true;
    }
    else{
      if(this.gender == 1){
        return false;
      }
    }
  } ,
  cerrarModal(){
    this.ClassL_a = 'btn btn-info';
    this.BtnL_a2 = 'Subir Archivo';
    if (this.tipoAccion==3){
      document.getElementById('file_formato').value='';
    }
    this.modal=0;
    this.tituloModal='';
    Utilerias.resetObject(this.solicitud);
  },
  abrirModal(modelo, accion,ide,data = []){
    switch(modelo){
      case "solicitud":
      {
        switch(accion){
          case 'registrar':
          {
            this.modal = 1;
            let me = this;
            this.tituloModal = 'Registrar solicitud de permiso';
            this.Metodo ='Nuevo';
            Utilerias.resetObject(this.solicitud);
            this.tipoAccion = 1;
            me.horaActual();
            this.solicitud.solicita_empleado_id = ide;
            me.AutorizaEmpleado(ide);
            this.disabled=0;
            break;
          }
          case 'actualizar':
          {
            Utilerias.resetObject(this.solicitud);
            this.modal=1;
            this.tituloModal='Actualizar Permiso';
            this.tipoAccion=2;
            this.disabled=1;
            this.Metodo = 'Actualizar';
            this.solicitud.id=data['id'];
            this.solicitud.fecha_solicitud = data['fecha_solicitud'];
            this.solicitud.motivo = data['motivo'];
            this.solicitud.formato_permiso = data['formato_permiso'];
            this.solicitud.fecha_inicio = data['fecha_inicio'];
            this.solicitud.fecha_fin = data['fecha_fin'];
            this.solicitud.tipo_salida = data['tipo_salida'];
            this.solicitud.fech_temp = data['fech_temp'];
            this.solicitud.hora_salida = data['hora_salida'];
            this.solicitud.hora_regreso = data['hora_regreso'];
            // this.solicitud.goce = data['goce'];
            this.solicitud.condicion = data['condicion'];
            this.solicitud.solicita_empleado_id = data['solicita_empleado_id'];
            this.solicitud.autoriza_empleado_id = data['autoriza_empleado_id'];
            break;
          }
          case 'subir':
          {
            Utilerias.resetObject(this.solicitud);
            this.modal=1;
            this.tituloModal= 'Subir Formato Firmado';
            this.tipoAccion=3;
            this.disabled=1;
            this.Metodo = 'Subir';

            this.solicitud.id=data['id'];
            this.solicitud.solicita_empleado_id = data['solicita_empleado_id'];
            this.solicitud.autoriza_empleado_id = data['autoriza_empleado_id'];
            this.solicitud.fecha_solicitud = data['fecha_solicitud'];
            this.solicitud.motivo = data['motivo'];
            this.solicitud.formato_permiso = data['formato_permiso'];
            this.solicitud.fecha_inicio=data['fecha_inicio'];
            this.solicitud.fecha_fin=data['fecha_fin'];
            this.solicitud.tipo_salida=data['tipo_salida'];
            this.solicitud.fech_temp=data['fech_temp'];
            this.solicitud.hora_salida=data['hora_salida'];
            this.solicitud.hora_regreso=data['hora_regreso'];
            // this.solicitud.goce=data['goce'];
            this.solicitud.condicion=data['condicion'];
            break;
          }
        }
      }
    }
  },
  permisosmen(){

 //   var contadormensual = response.data.length;

   // if(contadormensual >= 3)
    //{
      //this.desabilitado = 1;
   // }
   // else{
     // this.desabilitado = 0;

    //}



  },
  cargarsolicitud(dataEmpleado = []) {

    this.detalle = true;
    this.isLoadingDetalle = true;
    let me=this;
    this.empleado = dataEmpleado;

    axios.get(me.url + '/' + dataEmpleado.id + '/' +'permisos').then(response => {

      me.tableDatasolicitud = response.data;
      this.solicitud.solicita_empleado_id = dataEmpleado.id;
      me.isLoadingDetalle = false;
    })
    .catch(function (error) {
      console.log(error);
    });

//    axios.get('/permisosver/' + dataEmpleado.id).then(response => {
  //    var contadormensual = response.data.length;

    //  if(contadormensual >= 3)
     // {
      //  this.desabilitado = 1;
       // toastr.info('Este Empleado no Puede Tener Más de 3 Permisos por Mes, Debe Eperar a que Termine el Mes');

      //}
      //else{
       // this.desabilitado = 0;


      //}
    //})
    //.catch(function (error) {
     // console.log(error);
    //});



  },
  maestro(){
    this.$refs.myTablesolicitud.setFilter({
      'fecha_solicitud': '','fecha_inicio': '','fecha_fin': '',
    });
    this.detalle = false;
  }
},
mounted() {
  this.getData();
  this.getLista();


}
}
</script>
