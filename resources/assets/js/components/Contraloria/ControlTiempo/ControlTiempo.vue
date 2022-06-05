<template>

  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Actualizacion gastos.
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading" :is-full-screen="true"/>
          <div class="form-row">
            <div class="form-group col-md-1">
              <label for="inputEmail4">Semana</label>
                <input type="text" class="form-control" v-model="num_semana_dos" >
            </div>

            <div class="form-group col-md-2">
              <label for="inputEmail4">Año</label>
              <input type="text" class="form-control" v-model="anio_n_dos">
            </div>
            <div class="form-group col-md-4">
              <label>&nbsp;</label><br>
              <button class="btn btn-outline-success" type="button" @click="Actualizar()">
                <i class="fas fa-retweet"></i>&nbsp;Actualizar</button>

            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Control de tiempo reportes.
        </div>

        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="inputEmail4">Fecha Inicio:</label>
              <input type="date" name="fechauno" id="fechauno" class="form-control" v-model="controlt.fechauno" data-vv-name="fechauno">
              <span class="text-danger">{{ errors.first('fechauno') }}</span>
            </div>

            <div class="form-group col-md-4">
              <label for="inputEmail4">Fecha Fin:</label>
              <input type="date" name="fechados" id="fechados" class="form-control" v-model="controlt.fechados" data-vv-name="fechados">
              <span class="text-danger">{{ errors.first('fechados') }}</span>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <!-- <button type="button" :disabled="isDisabled" @click="descargar()" class="btn btn-outline-success">
                <i class="far fa-file-excel"></i>&nbsp;Descargar Reporte
              </button> -->
              <div class="dropdown" >
                <button class="btn btn-outline-success dropdown-toggle" :disabled="isDisabled"  type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Opciones
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <!-- <button class="dropdown-item btn-outline-success" type="button" @click="descargar()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte</button> -->
                  <!-- <button class="dropdown-item btn-outline-success" type="button" @click="descargarGeneral()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte General Trabajador</button> -->
                  <button class="dropdown-item btn-outline-success" type="button" @click="descargarGeneralProyecto()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte General Proyecto</button>
                  <!-- <button class="dropdown-item btn-outline-success" type="button" @click="descargarSemanal()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte Semanal</button> -->
                  <button class="dropdown-item btn-outline-success" type="button" @click="descargarGeneralProyectAdmin()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte General Administrativos</button>
                  <button class="dropdown-item btn-outline-success" type="button" @click="descargarGeneralSinProyecto()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte General Sin Proyecto</button>

                </div>
              </div>

            </div>




          </div>

        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Reportes nomina.
        </div>

        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="inputEmail4">Semana</label>
                <input type="text" class="form-control" v-model="num_semana" >
            </div>

            <div class="form-group col-md-4">
              <label for="inputEmail4">Año</label>
              <input type="text" class="form-control" v-model="anio_n">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="inputEmail4">Fecha Inicio:</label>
              <input type="date" class="form-control" v-model="fecha_uno_semanal">
            </div>

            <div class="form-group col-md-4">
              <label for="inputEmail4">Fecha Fin:</label>
              <input type="date" class="form-control" v-model="fecha_dos_semanal">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <!-- <button type="button" :disabled="isDisabled" @click="descargar()" class="btn btn-outline-success">
                <i class="far fa-file-excel"></i>&nbsp;Descargar Reporte
              </button> -->
              <div class="dropdown" >
                <button class="btn btn-outline-success dropdown-toggle" :disabled="isDisabled_uno"  type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Opciones
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <button class="dropdown-item btn-outline-success" type="button" @click="descargar()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte Semanal</button>
                  <button class="dropdown-item btn-outline-success" type="button" @click="descargarCompleto()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte Completo</button>

                </div>
              </div>

            </div>




          </div>

        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Subir reportes IMSS
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>&nbsp;Subir --</label>
              <input class="form-control" type="file" v-validate="'ext:xls,xlsx'" data-vv-as="Archivo"
              name="import_file_emp" @change="getArchivo" accept="*/*" id="archivo" >
              <span class="text-danger">{{ errors.first('import_file_emp') }}</span>
            </div>
            <div class="form-group col-md-4">
              <label>Tipo</label>
              <select class="form-control" v-model="tipo" @change="cambiarPeriodo()">
                <option value="EMA">EMA</option>
                <option value="EBA">EBA</option>
              </select>
            </div>
            <div class="form-group col-md-4" v-if="tipo === 'EMA'">
              <label>Tipo</label>
              <select class="form-control" v-model="periodo" @change="cambiarPeriodo()">
                <option value="01">ENERO</option>
                <option value="02">FEBRERO</option>
                <option value="03">MARZO</option>
                <option value="04">ABRIL</option>
                <option value="05">MAYO</option>
                <option value="06">JUNIO</option>
                <option value="07">JULIO</option>
                <option value="08">AGOSTO</option>
                <option value="09">SEPTIEMBRE</option>
                <option value="10">OCTUBRE</option>
                <option value="11">NOVIEMBRE</option>
                <option value="12">DICIEMBRE</option>
              </select>
            </div>
            <div class="form-group col-md-4" v-if="tipo === 'EBA'">
              <label>Tipo</label>
              <select class="form-control" v-model="periodo" @change="cambiarPeriodo()">
                <option value="01-02">ENERO-FEBRERO</option>
                <option value="03-04">MARZO-ABRIL</option>
                <option value="05-06">MAYO-JUNIO</option>
                <option value="07-08">JULIO-AGOSTO</option>
                <option value="09-10">SEPTIEMBRE-OCTUBRE</option>
                <option value="11-12">NOVIEMBRE-DICIEMBRE</option>
              </select>
            </div>


          </div>
        </div>
        <div class="card-footer">
          <div class="form-row">
            <div class="form-group col-md-4">
              <button @click="update" class="btn btn-primary" >Subir Archivo</button>
            </div>
          </div>

        </div>
      </div>

      <!-- <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Detalle Control Tiempo.
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable"> -->
            <!-- // update text on the fly
            <input type="text" slot="control.horas_extras" slot-scope="{row, update}" v-model="row.control.horas_extras" @input="update">
            // update a checkbox
            <input type="checkbox" slot="checkbox" slot-scope="{row, update}" v-model="row.checkbox" @input="update">

            // update text on submit + toggle editable state + revert to original value on cancel
            <div slot="text2" slot-scope="{row, update, setEditing, isEditing, revertValue}">
              <span @click="setEditing(true)" v-if="!isEditing()">
                {{row.control.horas_extras}}
              </span>
              <span v-else>
                <input type="text" v-model="row.control.horas_extras">
                <button type="button" @click="update(row.control.horas_extras); setEditing(false)">Submit</button>
                <button type="button" @click="revertValue(); setEditing(false)">Cancel</button>
              </span>
            </div> -->
          <!-- </v-client-table>
        </div>
      </div> -->

      <!-- Fin ejemplo de tabla Listado -->

      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade"
           tabindex="-1"
           :class="{'mostrar' : modal}"
           role="dialog"
           aria-labelledby="myModalLabel"
           style="display: none;"
           aria-hidden="true">
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
                <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->

                <div class="row">
                  <div class="form-group col-lg-6">
                    <label>Fecha de actividad:</label>
                    <input type="date"
                           name="fecha_inicial"
                           id="fecha-inicial"
                           class="form-control"
                           v-validate="'required'"
                           v-model="controlt.fechaInicial"
                           data-vv-as="Fecha inicial">
                    <span class="text-danger"></span>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-6">
                    <label>Empresa:</label>
                    <p>
                      <select name="text-input"
                              id="text-input"
                              v-model="controlt.empresa"
                              data-toggle="tooltip"
                              title="Selecciona empresa"
                              class="form-control"
                              v-validate="'excluded:'"
                              data-vv-as="empresa">
                      <option value="CONSERFLOW">CONSERFLOW</option>
                      <option value="CSCT">CSCT</option>
                    </select>
                      <span class="text-danger">{{ errors.first('text-input') }}</span>
                    </p>
                  </div>

                  <div class="form-group col-lg-6">
                    <label>Proyecto:</label>
                    <v-select v-model="controlt.proyecto_id" :options="listaProyectos" label="nombre_corto">
                    </v-select>
                    <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
                  </div>
                </div>

                <div class="row">

                  <div class="form-group col-lg-6">
                    <label>Supervisor:</label>

                    <v-select v-model="controlt.supervisor_id" :options="vs_options" label="name" name="asigna" data-vv-as="asigna"></v-select>
                    <!---->
                    <span class="text-danger">{{ errors.first('asigna') }}</span>
                  </div>

                  <div class="form-group col-lg-6">
                    <label>Empleado asignado:</label>
                    <v-select multiple
                              v-model="controlt.empleado_asignado_id"
                              :options="vs_options"
                              label="name"
                              name="empleado_asignado"
                              data-vv-as="empleado_asignado"
                              ></v-select>
                    <span class="text-danger">{{ errors.first('recibe') }}</span>
                  </div>

                </div>

                <div class="row">
                  <div class="form-group col-lg-6">
                    <label>Horas extra de empleado:</label>
                    <input type="number"
                           maxlength="18"
                           name="horas"
                           v-model="controlt.horas_extras"
                           class="form-control"
                           placeholder="N° Horas"
                           autocomplete="off">
                  </div>
                </div>

              </div>

              <!-- </form> -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar
            </button>
              <button type="button"
                      v-if="tipoAccion==1"
                      class="btn btn-secondary"
                      @click="guardarTipoControl(1)"><i class="fas fa-save"></i>&nbsp;Guardar
          </button>
              <!--
          //  <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarBanco(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
        -->
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

import Utilerias from '../../Herramientas/utilerias.js';

var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data() {
    return {
      archivo : null,
      tipo : '',
      periodo : '',
      num_semana : '',
      anio_n : '',
      num_semana_dos : '',
      anio_n_dos : '',
      fecha_uno_semanal : '',
      fecha_dos_semanal : '',
      url: '/controltiemporesource',
      proyecto:{},
      controlt: {
        fechaInicial: '',
        proyecto_id: 0,
        supervisor_id: '',
        empleado_asignado_id: '',
        horas_extras: 0,
        fechauno: null,
        fechados: null,
        buscar: null,
        empresa: '',
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
      columns: ['control.fecha','control.supervisor_nombre', 'control.asignado_nombre','control.horas_extras','control.empresa','proyecto_inicial','control.nombre_corto'],
      tableData: [],
      options: {
        // editableColumns : ['control.horas_extras'],
        headings: {
          'control.asignado_nombre': 'Empleado Asignado',
          'control.supervisor_nombre': 'Supervisor',
          'control.horas_extras': 'Horas Extras',
          'control.empresa': 'Empresa',
          proyecto_inicial: 'Proyecto Inicial',
          'control.nombre_corto': 'Proyecto Asignado',
          'control.fecha' : 'Fecha'

        },
        groupBy: ['nombre_proyecto'],
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['control.fecha','control.supervisor_nombre', 'control.asignado_nombre','control.horas_extras','control.empresa','proyecto_inicial','control.nombre_corto'],
        filterable: ['control.fecha','control.supervisor_nombre', 'control.asignado_nombre','control.horas_extras','control.empresa','proyecto_inicial','control.nombre_corto'],
        filterByColumn: true,
        texts: config.texts
      },
    }
  },

  methods: {

    // getData() {
    //   let me = this;
    //   axios.get('/controltiempo').then(response => {
    //     me.tableData = response.data;
    //   }).catch(function (error) {
    //     console.log(error);
    //   });
    //
    //   axios.get('/proyecto').then(response => {
    //     // me.listaProyectos = response.data;
    //     let aux={id:0,nombre_corto:"Seleccione proyecto"};
    //     me.listaProyectos.push(aux);
    //     me.proyecto=aux;
    //     response.data.forEach(p=>
    //       {
    //         me.listaProyectos.push
    //         ({
    //           id:p.proyecto.id,
    //           nombre_corto:p.proyecto.nombre_corto,
    //         });
    //       });
    //
    //     })
    //     .catch(function (error) {
    //       console.log(error);
    //     });
    //
    //     axios.get('/vertodosempleados').then(response => {
    //       this.vs_options = [];
    //       for (var i = 0; i < response.data.length; i++) {
    //         this.vs_options.push({
    //           id : response.data[i].id,
    //           name : response.data[i].nombre + ' ' + response.data[i].ap_paterno + ' ' + response.data[i].ap_materno,
    //         });
    //       }
    //     }).catch(error => {
    //       console.error(error);
    //     });
    //   },

      descargar() {
        if (this.num_semana === '' || this.anio_n === '') {
          toastr.warning('Escriba el el año y numero de semana');
        } else if (this.num_semana > 52) {
          toastr.warning('Un año no puede tener mas de 52 semanas');
        }else {
          window.open('descargar-control/' + this.num_semana + '&' + this.anio_n, '_blank');
        }
      },

      descargarCompleto(){
        var lunes = this.fecha_uno_semanal;
        let datel = new Date(lunes.replace(/-+/g, '/'));
        var dialunes = datel.getDay();

        var domingo = this.fecha_dos_semanal;
        let dated = new Date(domingo.replace(/-+/g, '/'));
        var diadomingo = dated.getDay();

        if (dialunes != 1) {
          swal('Error!','Debe comenzar en Lunes !','warning');
          this.isDisabled = true;
        }
        else if (diadomingo != 0) {
          swal('Error!','Debe terminar en Domingo !','warning');
          this.isDisabled = true;
        }else {
          window.open('descargar-control-completo/' + this.fecha_uno_semanal + '&' + this.fecha_dos_semanal , '_blank');
        }

      },

      descargarGeneral(){
        var lunes = this.controlt.fechauno;
        let datel = new Date(lunes.replace(/-+/g, '/'));
        var dialunes = datel.getDay();

        var domingo = this.controlt.fechados;
        let dated = new Date(domingo.replace(/-+/g, '/'));
        var diadomingo = dated.getDay();

        if (dialunes != 1) {
          swal('Error!','Debe comenzar en Lunes !','warning');
          this.isDisabled = true;
        }
        else if (diadomingo != 0) {
          swal('Error!','Debe terminar en Domingo !','warning');
          this.isDisabled = true;
        }else {
          window.open('descargar-control-general/' + this.controlt.fechauno + '&' + this.controlt.fechados , '_blank');
        }

      },

      descargarGeneralProyecto(){
        var lunes = this.controlt.fechauno;
        let datel = new Date(lunes.replace(/-+/g, '/'));
        var dialunes = datel.getDay();

        var domingo = this.controlt.fechados;
        let dated = new Date(domingo.replace(/-+/g, '/'));
        var diadomingo = dated.getDay();

        if (dialunes != 1) {
          swal('Error!','Debe comenzar en Lunes !','warning');
          this.isDisabled = true;
        }
        else if (diadomingo != 0) {
          swal('Error!','Debe terminar en Domingo !','warning');
          this.isDisabled = true;
        }else {
          window.open('descargar-control-general-proyecto/' + this.controlt.fechauno + '&' + this.controlt.fechados , '_blank');
        }

      },

      descargarSemanal(){
        var lunes = this.controlt.fechauno;
        let datel = new Date(lunes.replace(/-+/g, '/'));
        var dialunes = datel.getDay();

        var domingo = this.controlt.fechados;
        let dated = new Date(domingo.replace(/-+/g, '/'));
        var diadomingo = dated.getDay();

        var fechaIngreso = new Date(this.controlt.fechauno).getTime();//obtener el numero de dias de una fecha inicial a una final
        var fechaFin    = new Date(this.controlt.fechados).getTime();//obtener el numero de dias de una fecha inicial a una final
        var diff = fechaFin - fechaIngreso;//obtener el numero de dias de una fecha inicial a una final
        var diferencia =diff/(1000*60*60*24);//obtener el numero de dias de una fecha inicial a una final


        if (dialunes != 1) {
          swal('Error!','La semana debe comenzar en Lunes !','warning');
          this.isDisabled = true;
        }
        else if (diadomingo != 0) {
          swal('Error!','La semana debe terminar en Domingo !','warning');
          this.isDisabled = true;
        }else if ((diferencia+1) > 7) {
          swal('Error!','No se pueden crear reporte por contener mas de 7 dias','warning');
          this.isDisabled = true;
        }
        else {


          var mesi = this.controlt.fechauno.substr(5,2);
          var diai = this.controlt.fechauno.substr(8,2);
          var anio = this.controlt.fechauno.substr(0,4);
          Date.prototype.getWeekNumber = function () {
            var d = new Date(+this);  //Creamos un nuevo Date con la fecha de "this".
            d.setHours(0, 0, 0, 0);   //Nos aseguramos de limpiar la hora.
            d.setDate(d.getDate() + 4 - (d.getDay() || 7)); // Recorremos los días para asegurarnos de estar "dentro de la semana"
            //Finalmente, calculamos redondeando y ajustando por la naturaleza de los números en JS:
            return Math.ceil((((d - new Date(d.getFullYear(), 0, 1)) / 8.64e7) + 1) / 7);
          };
          var numerodesemana = new Date(anio, (mesi-1), diai).getWeekNumber();
          window.open('descargar-control-semanal/' + this.controlt.fechauno + '&' + this.controlt.fechados + '&' +numerodesemana, '_blank');
        }

      },

      descargarGeneralProyectAdmin(){
        var lunes = this.controlt.fechauno;
        let datel = new Date(lunes.replace(/-+/g, '/'));
        var dialunes = datel.getDay();

        var domingo = this.controlt.fechados;
        let dated = new Date(domingo.replace(/-+/g, '/'));
        var diadomingo = dated.getDay();

        if (dialunes != 1) {
          swal('Error!','Debe comenzar en Lunes !','warning');
          this.isDisabled = true;
        }
        else if (diadomingo != 0) {
          swal('Error!','Debe terminar en Domingo !','warning');
          this.isDisabled = true;
        }else {
          window.open('descargar-control-general-proyecto-admin/' + this.controlt.fechauno + '&' + this.controlt.fechados , '_blank');
        }

      },

      descargarGeneralSinProyecto(){
        var lunes = this.controlt.fechauno;
        let datel = new Date(lunes.replace(/-+/g, '/'));
        var dialunes = datel.getDay();

        var domingo = this.controlt.fechados;
        let dated = new Date(domingo.replace(/-+/g, '/'));
        var diadomingo = dated.getDay();

        if (dialunes != 1) {
          swal('Error!','Debe comenzar en Lunes !','warning');
          this.isDisabled = true;
        }
        else if (diadomingo != 0) {
          swal('Error!','Debe terminar en Domingo !','warning');
          this.isDisabled = true;
        }else {
          window.open('descargar-control-general-proyecto-sin/' + this.controlt.fechauno + '&' + this.controlt.fechados , '_blank');
        }

      },

      guardarTipoControl(nuevo) {
        this.$validator.validate().then(result => {
          if (result) {

            let me = this;
            axios({
              method: nuevo ? 'POST' : 'PUT',
              url: nuevo ? me.url : me.url + '/' + this.id,
              data: {
                'fecha': this.controlt.fechaInicial,
                'proyecto_id': this.controlt.proyecto_id.id,
                'supervisor_id': this.controlt.supervisor_id,
                'empleado_asignado_id': this.controlt.empleado_asignado_id,
                'horas_extras': this.controlt.horas_extras,
                'empresa': this.controlt.empresa,
              }
            }).then(function (response) {

              if (response.data.status === true) {
                me.cerrarModal();
                // me.getData();
                if (!nuevo) {
                  toastr.success(' Actualizada Correctamente');
                } else {
                  toastr.success('Registrada Correctamente');
                }
              }
              else
              {
                if (response.data.error === true && response.data.status === false) {
                  toastr.error(' Verifica datos registrados \n empleado o fecha duplicada' + response.data.empleado);
                } else {
                  swal({
                    type: 'error',
                    html: response.data.errors.join('<br>'),
                  });
                }
              }


            }).catch(function (error) {
              console.log(error);
            });
          }
        });
      },

      abrirModal(modelo, accion) {
        switch (modelo) {
          case "tipo-control": {
            switch (accion) {
              case 'registrar': {
                Utilerias.resetObject(this.controlt);
                this.modal = 1;
                this.tituloModal = 'Registrar Actividad';
                this.tipoAccion = 1;
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
          me.tableData = response.data;
        }).catch(function (error){
          console.log(error);
        });
      },

      cerrarModal() {
        this.modal = 0;
        this.tituloModal = '';
        Utilerias.resetObject(this.controlt);
      },

      getArchivo(event){
        //Asignamos el archivo a  nuestra data
        this.archivo = event.target.files[0];
        // console.log(event);
      },

      update(){


            // this.isLoading = true;
            //Creamos el formData
            var data = new  FormData();
            //Añadimos la imagen seleccionada
            data.append('import_file', this.archivo);
            data.append('tipo', this.tipo);
            data.append('periodo', this.periodo);
            //Añadimos el método PUT dentro del formData
            // Como lo hacíamos desde un formulario simple _(no ajax)_
            data.append('_method', 'PUT');
            //Enviamos la petición
            axios.post('/registrosueldosimss/upload',data)
            .then(response => {
              // console.log(response);
              this.archivo = null;
              // var field = this.$validator.fields.find({ name: 'email' });
              // field.reset();
              if (response.status == 200){
                swal(
                  'agregadas',
                  //  'Registros agregados: ' + response.data.nuevos + '<br>Registros repetidos: ' + response.data.repetidos,
                  'correcto!!!'
                );
                $('#archivo').val('');
                // this.$refs.table.refresh();
              }
              else
              swal(
                'Articulos',
                'Ocurrio un error.',
                'error'
              );
            })
            .catch(function (error) {
              this.archivo = null;
              // this.isLoading = false;
              console.log(error);
              swal(
                'Articulos',
                'Ocurrio un error al leer el archivo.',
                'error'
              );
            });

      },

      Actualizar(){
        this.isLoading = true;
        axios.post('/registros/sueldos/tiempos/',{
          semana : this.num_semana_dos,
          anio : this.anio_n_dos,
        }).then(response => {
          this.isLoading = false;
          swal(
            'Actualizado',
            'correcto!!!'
          );
        }).catch(error => {
          console.error(error);
        })
      },


    },
    computed: {
      isDisabled() {
        if (this.controlt.fechauno != null && this.controlt.fechados != null) {
          return false;
        } else {
          return true;
        }
      },
      isDisabled_uno() {
        if ((this.num_semana != null && this.anio_n != null) || (this.fecha_uno_semanal != null && this.fecha_dos_semanal != null)) {
          return false;
        } else {
          return true;
        }
      },
    },
    mounted() {
      // this.getData();
    }
  }
  </script>
