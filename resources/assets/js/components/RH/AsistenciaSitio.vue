<template>

  <main class="main">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Registro asistencia sitio.
          <button type="button" @click="abrirModal('tipo-control','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>

        <div class="card-body">
          <!-- <vue-element-loading :active="isLoading" /> -->

          <!-- <div class="form-row">
            <div class="form-group col-md-2">
              <label>Fecha Inicio:</label>
              <input type="date" name="fechauno" id="fechauno" class="form-control" v-model="controlt.fechauno" data-vv-name="fechauno">
              <span class="text-danger">{{ errors.first('fechauno') }}</span>
            </div>
            <div class="form-group col-md-2">
              <label>Fecha Fin:</label>
              <input type="date" name="fechados" id="fechados" class="form-control" v-model="controlt.fechados" data-vv-name="fechados">
              <span class="text-danger">{{ errors.first('fechados') }}</span>
            </div>
            <div class="form-group col-md-4">
              <label>Proyecto</label>
              <v-select v-model="proyectoId" :options="listaProyectos" label="nombre_corto" data-vv-name="proyecto">
              </v-select>
            </div>
            <div class="form-group col-md-2">
              <label>&nbsp;</label><br>
              <button class="btn btn-primary" @click="intervalosFechas()">
                <i class="fas fa-search-plus"></i>&nbsp;Buscar Registros
              </button>
            </div>
            <div class="form-group col-md-2">
              <label>&nbsp;</label><br>
              <button class="btn btn-success" @click="reporte()">
                <i class="fas fa-file-excel"></i>&nbsp; Reporte
              </button>
            </div>
          </div> -->
          <hr>
          <v-client-table :data="tableData" :columns="columns" :options="options">
            <template slot="id" slot-scope="props">
              <button class="btn btn-outline-dark" @click="eliminar(props.row.id)">
                <i class="fas fa-trash"></i>&nbsp;Eliminar
              </button>
            </template>
          </v-client-table>
        </div>
      </div>


      <!-- <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Detalle Control Tiempo.
        </div>
        <div class="card-body">

        </div>
      </div> -->


      <!-- Fin ejemplo de tabla Listado -->

      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModal"></h4>
              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-row">
                <div class="col-md-6 mb-3">
                  <label>Fecha de actividad:</label>
                  <input type="date" class="form-control" v-validate="'required'" v-model="controlt.fechaInicial" data-vv-name="Fecha actividad">
                  <span class="text-danger">{{errors.first('Fecha actividad')}}</span>
                </div>
                <div class="col-md-6 mb-3">
                  <label>Proyecto:</label>
                  <v-select v-model="controlt.proyecto_id" :options="listaProyectos" label="nombre_corto" v-validate="'required'" data-vv-name="proyecto">
                  </v-select>
                  <span class="text-danger">{{ errors.first('proyecto') }}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-6 mb-3">
                  <label>Supervisor:</label>
                  <v-select :options="vs_options" v-model="controlt.supervisor_id" label="name" name="asigna" data-vv-as="asigna"></v-select>
                  <span class="text-danger">{{ errors.first('asigna') }}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-12 mb-3">
                  <label>Empleado asignado:</label>
                  <v-select multiple v-model="controlt.empleado_asignado_id" :options="vs_options" label="name" data-vv-name="empleado asignado" v-validate="'required'">
                  </v-select>
                  <span class="text-danger">{{ errors.first('empleado asignado') }}</span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <vue-element-loading :active="isLoading"/>
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar
              </button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarTipoControl(1)">
                <i class="fas fa-save"></i>&nbsp;Guardar
              </button>

              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarTipoControl(0)">
                <i class="fas fa-save"></i>&nbsp;Actualizar
              </button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->

      <!--Fin del modal-->
    </div>
  </main>

</template>

<script>

import Utilerias from '../Herramientas/utilerias.js';

var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data() {
    return {
      id_control:0,
      archivo : null,
      columnas : 0,
      tipo : '',
      periodo : '',
      proyectoId : '',
      proyecto:{},
      supervisor_id: '',
      supervisor_busqueda : '',
      supervisor_find : '',
      controlt: {
        id : 0,
        fechaInicial: '',
        proyecto_id: 0,
        empleado_asignado_id: '',
        fechauno: '',
        fechados: '',
        buscar: null,
      },

      vs_options : [],
      deshabilitar: 0,
      id: 0,
      listaProyectos: [],
      listaEmpleados: [],
      modal: 0,
      tituloModal: '',
      tipoAccion: 0,
      isLoading: false,
      columns: ['id','fecha','empleado','nombre_corto'],
      tableData: [],
      options: {
        headings: {
          'nombre_corto': 'Proyecto',
          'id' : 'Acciones',
        },
        perPage: 15,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts,
      },
    }
  },

  methods: {
    getDataGral(){
      let me = this;

      axios.get('/proyecto').then(response => {
        me.listaProyectos = [];
        let aux={id:0,nombre_corto:"Sin proyecto"};
        me.proyecto=aux;
        response.data.forEach(p=>
          {
            me.listaProyectos.push
            ({
              id:p.proyecto.id,
              nombre_corto:p.proyecto.nombre_corto,
            });
          });
          me.listaProyectos.push(aux);
        })
        .catch(function (error) {
          console.log(error);
        });

        axios.get('/vertodosempleados').then(response => {
          this.vs_options = [];
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

      cancelar(){
        let me = this;
        me.controlt.fechauno = null;
        me.controlt.fechados = null;
        me.getData();
      },

      getData() {
        let me = this;
        axios.get('/get/asistencias/sitio').then(response => {
          me.tableData = response.data;
        }).catch(error => {
          console.error(error);
        });


      },


      guardarTipoControl(nuevo) {
        this.$validator.validate().then(result => {
          var hoy = new Date();
          let fecha=new Date(this.controlt.fechaInicial);
          if (result) {
            if(fecha>hoy)
            { // No puden registrar una fecha posterior a hoy
              toastr.warning("Seleccione una fecha válida");
              return;
            }
            let me = this;
            me.isLoading = true;
            axios({
              method: nuevo ? 'POST' : 'PUT',
              url: nuevo ? 'save/asistencias/sitio' : 'update/asistencias/sitio',
              data: {
                'fecha': this.controlt.fechaInicial,
                'proyecto_id': this.controlt.proyecto_id.id,
                'supervisor_id': this.controlt.supervisor_id.id,
                'empleado_asignado_id': this.controlt.empleado_asignado_id,
                'id' : this.controlt.id,
              }
            }).then(function (response) {
              if (response.data.status) {
                me.isLoading = false;
                if (response.data.status === 'error') {
                  swal({
                    type: 'error',
                    html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
                  });
                  me.cerrarModal();
                }else {
                  me.cerrarModal();
                  me.getData();
                  if (!nuevo) {
                    toastr.success(' Actualizada Correctamente');
                  } else {
                    toastr.success('Registrada Correctamente');
                  }
                }
              }
            }).catch(function (error) {
              me.isLoading = false;
              console.log(error);
            });
          }
        });
      },

      cancelar(){
        this.getData();
        this.supervisor_find = '';
      },

      abrirModal(modelo, accion) {
        switch (modelo) {
          case "tipo-control": {
            switch (accion) {
              case 'registrar': {
                // Utilerias.resetObject(this.controlt);
                this.controlt.fechaInicial = '';
                this.modal = 1;
                this.tituloModal = 'Registrar Asistencia';
                this.tipoAccion = 1;
                this.isLoading = false;
                break;
              }
            }
          }
        }
      },

      intervalosFechas(){

        this.isLoading = true;
        let me = this;
        axios({
          method: 'POST',
          url: 'intervalo/',
          data:{
            'fechauno':this.controlt.fechauno,
            'fechados':this.controlt.fechados,
          }
        }).then(function (response){
          me.isLoading = false;
          me.tableData = [];
          me.tableDataIncidencias = [];
          me.tableData = response.data.datos;
          me.tableDataIncidencias = response.data.incidencia
        }).catch(function (error){
          console.log(error);
        });
      },

      cerrarModal() {
        this.modal = 0;
        this.tituloModal = '';
        this.controlt.fechaInicial = '';
        this.isLoading = false;

        // Utilerias.resetObject(this.controlt);
      },

      eliminar(id){
        Swal.fire({
          title: 'Esta seguro de eliminar?',
          text: "No se podra revertir!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si!'
        }).then((result) => {
          if (result.value) {
            axios.get('delete/asistencias/sitio/' + id).then(response => {
              if (response.data.status === 'error') {
                swal({
                  type: 'error',
                  html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
                });
              }else {
                toastr.success('Eliminado correctamentes');
                this.getData();
              }
            }).catch(e => {
              console.error(e);
            });
          }
        });
      },

      reporte(){
        window.open('descargar-reporte-empleados-tiempos/' + this.controlt.fechauno + '&' + this.controlt.fechados + '&' + this.proyectoId.id, '_blank');
      }
    },
    computed: {
      isDisabled() {
        if (this.controlt.fechauno != null && this.controlt.fechados != null) {
          return false;
        } else {
          return true;
        }
      }
    },
    mounted() {
      this.getData();
      this.getDataGral();
    }
  }
  </script>
