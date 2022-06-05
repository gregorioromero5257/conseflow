<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card" v-show="!detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Vacaciones
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
                    <button type="button" @click="desabilitarboton(props.row.diasrestantes); cargarsolicitudvacacional(props.row.empleado);" class="dropdown-item">
                      <i class="fas fa-eye"></i>&nbsp; Ver Solicitudes de Vacaciones
                    </button>
                  </div>
                </div>
              </div>
            </template>
          </v-client-table>

        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
      <!-- Listado de solicitudvacacional del empleado -->
      <br>
      <div class="card" v-show="detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Solicitudes Vacacionales pertenecientes a:  {{ empleado == null ? '': empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }}
          <button type="button" @click="abrirModal('solicitudvacacional','registrar', empleado.id)" class="btn btn-dark float-sm-right" v-bind:disabled= "desabilitar == 1" >
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
          <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
            <i class="fa fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoadingDetalle"/>
          <v-client-table :columns="columnssolicitudvacacional" :data="tableDatasolicitudvacacional" :options="optionssolicitudvacacional" ref="myTablesolicitudvacacional">

            <template slot="estado" slot-scope="props" >
              <template v-if="props.row.estado == 1">
                <button  type="button" class="btn btn-success">Autorizado</button>
              </template>
              <template v-if="props.row.estado == 0">
                <button  type="button" class="btn btn-light">En Espera</button>
              </template>
              <template v-if="props.row.estado == 2">
                <button  type="button" class="btn btn-danger"> No Autorizado</button>
              </template>
            </template>


            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <template v-if="props.row.estado == 0">

                      <button type="button" @click="abrirModal('solicitudvacacional','actualizar',empleado.id,props.row)" class="dropdown-item">
                        <i class="icon-pencil"></i>&nbsp; Actualizar
                      </button>
                    </template>


                    <template v-if="props.row.estado == 1">
                      <button v-if="props.row.formato_vacacion == null" type="button" class="dropdown-item" @click="descargar(props.row)">
                        <i class="icon-cloud-download"></i>&nbsp; Descargar Formato Firmado
                      </button>
                      <button v-if="props.row.formato_vacacion == null" type="button" @click="abrirModal('solicitudvacacional','subir',empleado.id,props.row)" class="dropdown-item">
                        <i class="fas fa-upload"></i>&nbsp; Subir Formato Firmado
                      </button>
                      <button v-if="props.row.formato_vacacion != null" type="button" class="dropdown-item" @click="descargarFormato(props.row.formato_vacacion)">
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
              <input type="hidden" name="id">
              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="fecha_solicitud">Fecha de solicitud</label>
                <div class="col-md-9">
                  <input type="date" v-validate="'required'" name="fecha_solicitud" v-model="solicitudvacacional.fecha_solicitud" class="form-control" placeholder="Fecha de Solicitud"  autocomplete="off" data-vv-as="Fecha Solicitud">
                  <span class="text-danger">{{ errors.first('fecha_solicitud') }}</span>
                </div>
              </div>

              <div class="form-group row" v-if="tipoAccion == 3">
                <label class="col-md-3 form-control-label" for="formato_vacacion">Formato Vacacional:</label>
                <div class="col-md-6">
                  <label v-if="tipoAccion ==3" :class="ClassL_a">
                    <i class="fas fa-cloud-upload-alt"></i>&nbsp;{{BtnL_a2}}
                    <input type="file" accept="application/pdf" id="file_formato" style="display: none;" class="form-control" v-on:change="onChangeFormato">
                  </label>
                </div>
              </div>

              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="solicita_empleado_id">Empleado solicitante</label>
                <div class="col-md-9">
                  <select class="form-control" id="solicita_empleado_id"  name="solicita_empleado_id" v-model="solicitudvacacional.solicita_empleado_id" v-validate="'excluded:0'" data-vv-as="Solicita" disabled>
                    <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{ item.empleado.nombre }} {{ item.empleado.ap_paterno }} {{ item.empleado.ap_materno }}</option>
                  </select>
                  <span class="text-danger">{{ errors.first('solicita_empleado_id') }}</span>
                </div>
              </div>

              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="autoriza_empleado_id">Autoriza</label>
                <div class="col-md-9">
                  <select class="form-control" id="autoriza_empleado_id"  name="autoriza_empleado_id" v-model="solicitudvacacional.autoriza_empleado_id" v-validate="'excluded:0'" data-vv-as="Autoriza" >
                    <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{ item.empleado.nombre+' ' + item.empleado.ap_paterno+' ' + item.empleado.ap_materno }}</option>
                  </select>
                  <span class="text-danger">{{ errors.first('autoriza_empleado_id') }}</span>

                </div>
              </div>

              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="fecha_inicio">Fecha inicio</label>
                <div class="col-md-9">
                  <input type="date" v-validate="'required'" name="fecha_inicio" v-model="solicitudvacacional.fecha_inicio" class="form-control" placeholder="fecha_inicio" autocomplete="off" data-vv-as="Fecha Inicio" v-on:change="dia" >
                  <span class="text-danger">{{ errors.first('fecha_inicio') }}</span>
                </div>
              </div>

              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="fecha_fin">Fecha fin</label>
                <div class="col-md-9">
                  <input type="date" v-validate="'required'" name="fecha_fin" v-model="solicitudvacacional.fecha_fin" class="form-control" placeholder="fecha_fin" autocomplete="off" data-vv-as="Fecha Fin" v-on:change="dia" @change="validar">
                  <span class="text-danger">{{ errors.first('fecha_fin') }}</span>
                </div>
              </div>

              {{solicitudvacacional.fecha_fin.hidden}}

              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label" for="total_dias">Días totales</label>
                <div class="col-md-9">
                  <input type="text" v-validate="'required'" vname="total_dias" v-model="solicitudvacacional.total_dias" class="form-control" placeholder="Días" autocomplete="off" data-vv-as="Días" disabled>
                  <span class="text-danger">{{ errors.first('total_dias') }}</span>
                </div>
              </div>
              <div class="form-group row" v-if="(tipoAccion == 1 || tipoAccion == 2)">
                <label class="col-md-3 form-control-label " for="estado" >Estado</label>
                <div class="col-md-9">
                  <select v-validate="'required'" name="estado" v-model="solicitudvacacional.estado" class="form-control" placeholder="estado" autocomplete="off" data-vv-as="Estado" disabled >


                    <option value="2">No Autorizado</option>
                    <option value="1">Autorizado</option>
                    <option value="0">En Espera</option>

                  </select>
                  <span class="text-danger">{{ errors.first('estado') }}</span>
                </div>
              </div>
            </div>

          </div>
          <!-- </form> -->

          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarsolicitudvacacional(1)">Guardar</button>
            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarsolicitudvacacional(0)">Actualizar</button>
            <button type="button" v-if="tipoAccion==3" class="btn btn-secondary" @click="guardarsolicitudvacacional(1)">Actualizar</button>
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
      url: '/vacaciones',
      empleado: null,
      detalle: false,
      desabilitar : 0,
      solicitudvacacional: {
        id: 0,
        e_autoriza: '',
        e_solicita: '',
        fecha_solicitud: '',
        formato_vacacion : '',
        fecha_inicio : '',
        fecha_fin :'',
        total_dias : 0,
        estado: 0,
        solicita_empleado_id: 0,
        autoriza_empleado_id: 0,
      },
      Metodo: '',
      ClassL_a: 'btn btn-info',
      BtnL_a2: 'Subir Archivo',
      listaEmpleados: [],
      listasolicitudvacacional: [],
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
        'diasganados',
        'diastomados',
        'diasrestantes'
      ],
      tableData: [],
      columnssolicitudvacacional: [
        'id',
        'e_autoriza',
        'fecha_solicitud',

        'fecha_inicio',
        'fecha_fin',
        'total_dias',
        'estado',
      ],
      tableDatasolicitudvacacional: [],
      options: {
        headings: {
          'empleado.id': 'Acciones',
          'empleado.nombre': 'Nombre',
          'empleado.ap_paterno': 'Apellido paterno',
          'empleado.ap_materno': 'Apellido materno',
          'puesto.nombre': 'Puesto',
          'departamento.nombre': 'Departamento',
          'diasganados' : 'Días ganados',
          'diastomados': 'Días tomados',
          'diasrestantes': 'Días restantes',
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
      optionssolicitudvacacional: {
        headings: {
          'id': 'Acciones',
          'e_autoriza': 'Autoriza',
          'fecha_solicitud': 'Fecha solicitud',
          'formato_vacacion': 'Formato',
          'fecha_inicio': 'Fecha inicio',
          'fecha_fin': 'Fecha fin',
          'total_dias': 'Días totales',
          'estado': 'Estado',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha_solicitud','fecha_inicio','fecha_fin', 'total_dias'],
        filterable: ['fecha_solicitud'],
        filterByColumn: true,
        listColumns: {
          estado: [{
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
        ]
      },
      texts:config.texts
    }, }  },
    computed:{
    },
    methods : {
      validar(){
        var a = this.solicitudvacacional.fecha_inicio;
        var b = this.solicitudvacacional.fecha_fin;
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
        this.solicitudvacacional.fecha_solicitud = hoy;
        this.solicitudvacacional.fecha_inicio = hoy;
      },
      dia(){
        var a = this.solicitudvacacional.fecha_inicio;
        var b = this.solicitudvacacional.fecha_fin;
        var fechaInicio = new Date(a).getTime();
        var fechaFin    = new Date(b).getTime();
        var diff = fechaFin - fechaInicio;
        var diferencia =diff/(1000*60*60*24);
        this.solicitudvacacional.total_dias = diferencia +  1;
      },
      getData() {
        let me=this;
        axios.get('/vacacionesemp').then(response => {
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
      desabilitarboton(id){
        var x = id;
        if (x <= 0) {
          this.desabilitar = 1;
        } else {
          this.desabilitar = 0;
        }
      },
      AutorizaEmpleado (id) {
        let me=this;
        axios.get('/vacaciones/'+id+'/autoriza').then(response => {
          //  me.listaEmpleadosAutoriza = response.data;
          this.solicitudvacacional.autoriza_empleado_id = response.data.id;

        })
        .catch(function (error) {
          console.log(error);
        });
      },
      descargar(data) {
        console.log(data);
        window.open('descargar-vacaciones/' + data.id, '_blank');
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
          this.solicitudvacacional.formato_vacacion = e.target.files[0];
          this.BtnL_a2 = 'Archivo Cargado';
        }
        else
        {
          this.ClassL_a = 'btn btn-danger';
          this.BtnL_a2 = 'Archivo Invalido(Omitido)';
        }
      },

      guardarsolicitudvacacional(nuevo){
        if (this.fechavalidar == 0) {
          this.$validator.validate().then(result => {
            if (result) {
              this.isLoading = true;
              let me = this;
              let formData = new FormData();

              if (this.Metodo == 'Actualizar' || this.Metodo == 'Nuevo') {
                axios({
                  method: nuevo ? 'POST' : 'PUT',
                  url: nuevo ? me.url : me.url+'/'+this.solicitudvacacional.id,
                  data: {
                    'metodo':this.Metodo,
                    'estado':this.solicitudvacacional.estado,
                    'fecha_solicitud':this.solicitudvacacional.fecha_solicitud,
                    'formato_vacacion':this.solicitudvacacional.formato_vacacion,
                    'fecha_inicio':this.solicitudvacacional.fecha_inicio,
                    'fecha_fin':this.solicitudvacacional.fecha_fin,
                    'total_dias':this.solicitudvacacional.total_dias,
                    'solicita_empleado_id':this.solicitudvacacional.solicita_empleado_id,
                    'autoriza_empleado_id':this.solicitudvacacional.autoriza_empleado_id,
                    'id':this.solicitudvacacional.id
                  }
                }).then(function (response) {
                  me.isLoading = false;
                  if (response.data.status) {
                    me.cerrarModal();
                    me.cargarsolicitudvacacional(me.empleado);
                    if(!nuevo){
                      toastr.success('Solicitud Vacacional Actualizada Correctamente');
                    }
                    else
                    {
                      toastr.success('Solicitud Vacacional Registrada Correctamente');

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
                formData.append('estado',this.solicitudvacacional.estado);
                formData.append('fecha_solicitud',this.solicitudvacacional.fecha_solicitud);
                formData.append('formato_vacacion',this.solicitudvacacional.formato_vacacion);
                formData.append('fecha_inicio',this.solicitudvacacional.fecha_inicio);
                formData.append('fecha_fin',this.solicitudvacacional.fecha_fin);
                formData.append('total_dias',this.solicitudvacacional.total_dias);
                formData.append('solicita_empleado_id',this.solicitudvacacional.solicita_empleado_id);
                formData.append('autoriza_empleado_id',this.solicitudvacacional.autoriza_empleado_id);
                formData.append('id',this.solicitudvacacional.id);

                axios.post( me.url,formData).then(function (response) {
                  me.isLoading = false;
                  if (response.data.status) {
                    me.cerrarModal();
                    me.cargarsolicitudvacacional(me.empleado);
                    if(!nuevo){
                      toastr.success('Solicitud Vacacional Registrada Correctamente');
                    }
                    else
                    {
                      toastr.success('Solicitud Vacacional Registrada Correctamente');

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
      cerrarModal(){
        this.ClassL_a = 'btn btn-info';
        this.BtnL_a2 = 'Subir Archivo';
        if (this.tipoAccion==3) {
          document.getElementById('file_formato').value='';
        }
        this.modal=0;
        this.tituloModal='';
        Utilerias.resetObject(this.solicitudvacacional);
      },
      abrirModal(modelo, accion,ide, data = []){
        switch(modelo){
          case "solicitudvacacional":
          {
            switch(accion){
              case 'registrar':
              {
                this.modal = 1;
                let me =this;
                this.tituloModal = 'Registrar solicitud vacacional';
                this.Metodo = 'Nuevo';
                Utilerias.resetObject(this.solicitudvacacional);
                this.tipoAccion = 1;
                me.horaActual();
                this.solicitudvacacional.solicita_empleado_id = ide;
                me.AutorizaEmpleado(ide);
                this.disabled=0;
                break;
              }
              case 'actualizar':
              {
                Utilerias.resetObject(this.solicitudvacacional);
                this.modal=1;
                this.tituloModal= 'Actualizar solicitud vacacional';
                this.tipoAccion=2;
                this.disabled=1;
                this.Metodo = 'Actualizar';
                this.solicitudvacacional.id=data['id'];
                this.solicitudvacacional.solicita_empleado_id = data['solicita_empleado_id'];
                this.solicitudvacacional.autoriza_empleado_id = data['autoriza_empleado_id'];
                this.solicitudvacacional.fecha_solicitud = data['fecha_solicitud'];
                this.solicitudvacacional.formato_vacacion = data['formato_vacacion'];
                this.solicitudvacacional.fecha_inicio=data['fecha_inicio'];
                this.solicitudvacacional.fecha_fin=data['fecha_fin'];
                this.solicitudvacacional.total_dias=data['total_dias']
                this.solicitudvacacional.estado=data['estado'];
                break;
              }
              case 'subir':
              {
                Utilerias.resetObject(this.solicitudvacacional);
                this.modal=1;
                this.tituloModal= 'Subir Formato Firmado';
                this.tipoAccion=3;
                this.disabled=1;
                this.Metodo = 'Subir';
                this.solicitudvacacional.id=data['id'];
                this.solicitudvacacional.solicita_empleado_id = data['solicita_empleado_id'];
                this.solicitudvacacional.autoriza_empleado_id = data['autoriza_empleado_id'];
                this.solicitudvacacional.fecha_solicitud = data['fecha_solicitud'];
                this.solicitudvacacional.formato_vacacion = data['formato_vacacion'];
                this.solicitudvacacional.fecha_inicio=data['fecha_inicio'];
                this.solicitudvacacional.fecha_fin=data['fecha_fin'];
                this.solicitudvacacional.total_dias=data['total_dias']
                this.solicitudvacacional.estado=data['estado'];
                break;
              }
            }
          }
        }
      },
      cargarsolicitudvacacional(dataEmpleado = []) {
        this.detalle = true;
        this.isLoadingDetalle = true;
        let me=this;
        this.empleado = dataEmpleado;
        axios.get(me.url + '/' + dataEmpleado.id + '/' +'vacaciones').then(response => {
          me.tableDatasolicitudvacacional = response.data;
          me.isLoadingDetalle = false;
        })
        .catch(function (error) {
          console.log(error);
        });
      },
      maestro(){
        this.$refs.myTablesolicitudvacacional.setFilter({
          'fecha_solicitud': '','fecha_inicio': '','fecha_fin': '',
        });
        this.detalle = false;
      }
    },
    mounted() {
      this.getData();
      this.getLista();
    }   }
    </script>
