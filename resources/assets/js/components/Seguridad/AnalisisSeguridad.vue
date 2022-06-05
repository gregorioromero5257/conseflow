<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> ANÁLISIS DE SEGURIDAD EN EL TRABAJO (AST)

          <button type="button" @click="abrirModal('analisis','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">

          <v-client-table :data="tableData" :columns="columns" :options="options">

            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a type="button" @click.prevent="abrirModal('analisis','actualizar',props.row)" class="dropdown-item" href="#">
                      <i class="fas fa-pencil-alt"></i>&nbsp;Actualizar analisis
                    </a>
                    <a type="button" @click="participantes(props.row)" class="dropdown-item" href="#">
                      <i class="fas fa-user-friends"></i>&nbsp;Agregar Participantes
                    </a>
                    <a type="button" @click="eliminar(props.row.id)" class="dropdown-item" href="#">
                      <i class="fas fa-trash"></i>&nbsp;Eliminar
                    </a>





                  </div>
                </div>
              </div>


            </template>

            <template slot="descargar" slot-scope="props">
              <button type="button" @click="descargar(props.row.id)" class="btn btn-outline-dark" >
                <i class="fas fa-download"></i>&nbsp;<i class="fas fa-file-pdf"></i>
              </button>
            </template>

          </v-client-table>

        </div>
      </div>

      <div class="modal fade" tabindex="-1" :class="{ mostrar: modal }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div>
              <div class="modal-header">
                <h4 class="modal-title">{{tituloModal}}</h4>
                <button type="button" class="close" @click="Cerrar()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <vue-element-loading :active="isLoading" />

                <div class="form-row">
                  <div class="col-md-4 mb-3">
                    <label>Ubicación de trabajo</label>
                    <input type="text" class="form-control" v-model="ubicacion" data-vv-name="ubicacion" v-validate="'required'">
                    <span class="text-danger">{{errors.first('ubicacion')}}</span>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label>Número de permiso</label>
                    <input type="text" class="form-control" v-model="permiso" data-vv-name="permiso" v-validate="'required'">
                    <span class="text-danger">{{errors.first('permiso')}}</span>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label>Fecha</label>
                    <input type="date" class="form-control" v-model="fecha" data-vv-name="fecha" v-validate="'required'">
                    <span class="text-danger">{{errors.first('fecha')}}</span>
                  </div>
                </div>

                <div class="form-row">
                  <div class="col-md-8 mb-3">
                    <label>Descripción del trabajo</label>
                    <textarea class="form-control" v-model="descripcion" data-vv-name="descripcion" rows="2" cols="40" v-validate="'required'"></textarea>
                    <span class="text-danger">{{errors.first('descripcion')}}</span>
                  </div>
                </div>

                <div class="form-row">
                  <div class="col-md-5 mb-3">
                    <label>Seleccione</label>
                    <v-select :options="listaCatalogo" v-model="catalogo" label="secuencia" data-vv-name="catalogo" >
                    </v-select>
                    <!-- v-validate="'required'" -->
                    <span class="text-danger">{{errors.first('catalogo')}}</span>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label>Supervisor</label>
                    <v-select v-model="supervisor"
                    :options="vs_options" label="name" data-vv-as="operaciones"></v-select><!---->
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>&nbsp;</label><br>
                    <button @click="guardarasignacion()" class="btn btn-outline-dark" name="button">Crear</button>
                  </div>
                </div>
                <hr>
                <div class="form-row">

                  <div class="form-group col-md-3">
                    <label><b>Actividad</b></label>
                  </div>
                  <div class="form-group col-md-2">
                    <label><b>Riesgos</b></label>
                  </div>

                  <div class="form-group col-md-3">
                    <label><b>Medidas</b></label>
                  </div>
                  <div class="form-group col-md-2">
                    <label><b>Supervisor</b></label>
                  </div>
                  <div class="form-group col-md-2">
                    <label><b>ACCIONES</b></label>
                  </div>
                </div>
                <li v-for="(vi, index) in listado_data" class="list-group-item">
                  <div class="form-row">

                    <div class="form-group col-md-3">
                      <label>{{vi.actividad}}</label>
                    </div>
                    <div class="form-group col-md-2">
                      <label>{{vi.riesgo}}</label>
                    </div>
                    <div class="form-group col-md-3">
                      <label>{{vi.medidas}}</label>
                    </div>
                    <div class="form-group col-md-2">
                      <label>{{vi.supervisor}}</label>
                    </div>
                    <div class="form-group col-md-2">
                      <a @click="deleteu(index)">
                        <span class="fas fa-trash" arial-hidden="true"></span>
                      </a>
                    </div>
                  </div>
                </li>
                <hr>
                <div class="form-row">
                  <div class="col-md-6 mb-3">
                    <label>Responsable del Área (Operaciones)</label>
                    <v-select v-validate="'required'" v-model="operaciones"
                    :options="vs_options" label="name" data-vv-name="operaciones"></v-select><!---->
                    <span class="text-danger">{{errors.first('operaciones')}}</span>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Responsable del Área (SHSO)</label>
                    <v-select v-validate="'required'" v-model="shso"
                    :options="vs_options" label="name" data-vv-name="shso"></v-select><!---->
                    <span class="text-danger">{{errors.first('shso')}}</span>
                  </div>
                </div>


              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" @click="Cerrar()">
                  <i class="fas fa-window-close"></i>&nbsp;Cerrar
                </button>
                <button v-show="tipoAccion == 1" type="button" class="btn btn-secondary" @click="Guardar(1)">
                  Guardar
                </button>
                <button v-show="tipoAccion == 2" type="button" class="btn btn-secondary" @click="Guardar(0)">
                  Actualizar
                </button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal agregar documentos-->


      <div class="modal fade" tabindex="-1" :class="{ mostrar: modal_participantes }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div>
              <div class="modal-header">
                <h4 class="modal-title">Participantes</h4>
                <button type="button" class="close" @click="cerrarModalP()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <vue-element-loading :active="isLoading" />


                <div class="form-row">
                  <div class="col-md-6 mb-3">
                    <label>Empleado</label>
                    <v-select v-model="empleado"
                    :options="vs_options" label="name" data-vv-as="shso"></v-select><!---->
                  </div>
                  <div class="col-md-4 mb-3">
                    <label>&nbsp;</label><br>
                    <button type="button" class="btn btn-outline-dark" @click="agregarEmpleado()">Agregar</button>
                  </div>
                </div>
                <v-client-table :data="tableDataParticipantes" :options="optionsParticipantes" :columns="columnsParticipantes">
                  <template slot="empleado_id" slot-scope="props">
                    <a @click="eliminarParticipante(props.row.empleado_id)">
                      <span class="fas fa-trash" arial-hidden="true"></span>
                    </a>
                  </template>
                </v-client-table>


              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" @click="cerrarModalP()">
                  <i class="fas fa-window-close"></i>&nbsp;Cerrar
                </button>
                <!-- <button type="button" class="btn btn-secondary" @click="GuardarParticipantes()">
                  Guardar
                </button> -->
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal agregar documentos-->

    </div>
  </main>
</template>

<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return{
      tituloModal : '',
      modal : 0,
      modal_participantes : 0,
      tipoAccion : 0,
      isLoading : false,
      ///
      id: 0,
      ubicacion : '',
      permiso : '',
      descripcion : '',
      operaciones : [],
      shso  : '',
      empleado : '',
      fecha : '',
      //
      listaCatalogo : [],
      catalogo : '',
      listado_data : [],
      listado_id : [],
      listado_supervisor : [],
      vs_options : [],
      supervisor : '',
      ///
      tableData : [],
      columns : ['id','fecha','numero_permiso','ubicacion','descripcion','descargar'],
      options: {
        headings: {
          'id' :'ACCIONES'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
      ////
      tableDataParticipantes : [],
      columnsParticipantes : ['empleado_id','empleado'],
      optionsParticipantes: {
        headings: {
          'empleado_id' :'ACCIONES'
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
  methods : {
    getDataInicial(){
      axios.get('get/analisis/seguridad').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    getData(){
      this.listaCatalogo = [];
      axios.get('get/lista/catalogo/analisis').then(response => {
        this.listaCatalogo = response.data;
      }).catch(e => {
        console.error(e);
      });

      axios.get('vertodosempleados').then(response => {
        // console.log(response);
        response.data.forEach((item, i) => {
          this.vs_options.push({
            id : item.id,
            name : item.nombre + ' ' + item.ap_paterno + ' ' + item.ap_materno,
          });
        });

      }).catch(e => {
        console.error(e);
      });
    },

    abrirModal(modelo, accion, data = []){
      switch(modelo){
        case "analisis":
        {
          switch(accion){
            case 'registrar':
            {
              this.modal = 1;
              this.tituloModal = 'Registrar análisis';
              this.tipoAccion = 1;
              this.VaciarDos();
              break;
            }
            case 'actualizar':
            {
              this.listado_data = [];
              this.listado_id = [];
              this.listado_supervisor = [];
              // console.log(data);
              axios.get('get/lista/evaluacion/riesgo/' + data.id).then(response => {
                response.data.forEach((item, i) => {
                  this.listado_data.push(
                    {
                      actividad : item.secuencia ,
                      riesgo : item.riesgo,
                      medidas : item.recomendacion,
                      supervisor : item.supervisor,
                    }
                  );

                  this.listado_supervisor.push(item.supervisor_id);

                  this.listado_id.push(item.catalogo_analisis_seguridad_id);
                });

              }).catch(e => {
                console.error(e);
              });
              this.operaciones = {id : data['empleado_operaciones'], name: data['operaciones_empleado']};
              this.shso = {id : data['empleado_shso'], name: data['shso_empleado']};
              this.id = data['id'];
              this.ubicacion = data['ubicacion'];
              this.permiso = data['numero_permiso'];
              this.descripcion = data['descripcion'];
              this.fecha = data['fecha'];
              this.modal=1;
              this.tituloModal='Actualizar análisis';
              this.tipoAccion=2;
              break;
            }
          }
        }
      }
    },

    Cerrar(){
      this.modal = 0;
    },

    guardarasignacion(){
      if (this.listado_data.find(info => info.actividad == this.catalogo.secuencia)) {
        toastr.warning('Ya seleccionado');
      }else {
        if (this.catalogo != '') {
          this.listado_data.push(
            {
              actividad : this.catalogo.secuencia,
              riesgo : this.catalogo.riesgo,
              medidas : this.catalogo.recomendacion,
              supervisor : this.supervisor.name,
            },
          );

          this.listado_id.push(this.catalogo.id);
          this.listado_supervisor.push(this.supervisor.id);
          this.catalogo = '';
          this.supervisor = '';
        }
      }
    },

    deleteu(index){
      this.listado_data.splice(index, 1);
      this.listado_id.splice(index, 1);
      this.listado_supervisor.splice(index, 1);
    },

    Guardar(nuevo){
      // console.log('her');

      this.$validator.validate().then(result => {
        if (result) {
          let me = this;

          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? '/guardar/analisis/seguridad' : '/actualizar/analisis/seguridad',
            data : {
              id : this.id,
              ubicacion : this.ubicacion,
              permiso : this.permiso,
              descripcion : this.descripcion,
              operaciones : this.operaciones.id,
              shso  : this.shso.id,
              listado_id : this.listado_id,
              listado_supervisor : this.listado_supervisor,
              fecha : this.fecha,
            }
          }).then(response => {
            this.getDataInicial();
            this.Vaciar();
          }).catch(e => {
            console.error(e);
          });
        }
      });
    },

    Vaciar(){
      this.id = 0;
      this.ubicacion = '';
      this.permiso = '';
      this.descripcion = '';
      this.operaciones = '';
      this.shso  = '';
      this.listado_data = [];
      this.listado_id = [];
      this.modal = 0;
      this.fecha = '';
    },

    VaciarDos(){
      this.id = 0;
      this.ubicacion = '';
      this.permiso = '';
      this.descripcion = '';
      this.operaciones = '';
      this.shso  = '';
      this.fecha = '';
      this.listado_data = [];
      this.listado_id = [];
      this.listado_supervisor = [];
    },

    participantes(data){
      this.analisis_id = data.id;
      this.modal_participantes = 1;
      this.getParticipantes(data.id);
    },

    getParticipantes(id){
      this.tableDataParticipantes = [];
      axios.get('get/participantes/analisis/seguridad/' + id).then(response => {
        this.tableDataParticipantes = response.data;
      }).catch(e =>{
        console.error(e);
      });
    },

    cerrarModalP(){
      this.modal_participantes = 0;
    },

    agregarEmpleado(){
      axios.post('guardar/participantes/analisis/seguridad',{
        id : this.analisis_id,
        empleado  : this.empleado.id,
      }).then(response => {
        // console.log(response);
        this.getParticipantes(this.analisis_id);
      }).catch(error => {
        console.error(error);
      })
    },

    eliminarParticipante(empleado_id){
      axios.get('eliminar/participantes/analisis/seguridad/' + empleado_id + '&' + this.analisis_id).then(response =>{
        this.getParticipantes(this.analisis_id);
      }).catch(e => {
        console.error(e);
      });
    },

    descargar(data){
      window.open('descargar/analisis/seguridad/' + data, '_blank');
    },

    eliminar(id){
      Swal.fire({
        title: 'Esta seguro(a) de eliminar ?',
        text: "Esta opción no se podrá desahacer!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText : 'No',
      }).then(result => {
        if (result.value) {
          axios.get('eliminar/analisis/seguridad/' + id).then(response =>{
            toastr.success('Eliminado Correctamente !!!');
            this.getDataInicial();
          }).catch(e => {
            console.error(e);
          });
        }
      })
    },

  },
  mounted(){
    this.getData();
    this.getDataInicial();
  }
}
</script>
