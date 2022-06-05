<template>

  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Control de tiempo.
          <button type="button"
                  @click="abrirModal('tipo-control','registrar')"
                  class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>

        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="inputEmail4">Fecha Inicio:</label>
              <input type="date" name="fechauno" id="fechauno" class="form-control" v-model="controlt.fechauno" data-vv-name="fechauno">
              <span class="text-danger">{{ errors.first('fechauno') }}</span>
            </div>

            <div class="form-group col-md-3">
              <label for="inputEmail4">Fecha Fin:</label>
              <input type="date" name="fechados" id="fechados" class="form-control" v-model="controlt.fechados" data-vv-name="fechados">
              <span class="text-danger">{{ errors.first('fechados') }}</span>
            </div>
            <div class="form-group col-md-3">
              <label for="inputEmail4">Proyecto</label>
              <v-select v-model="proyectoId" :options="listaProyectos" label="nombre_corto" data-vv-name="proyecto">
              </v-select>
            </div>

            <div class="form-group col-md-2">
              <label>&nbsp;</label><br>
              <button class="btn btn-primary"
                      v-model="controlt.buscar"
                      :disabled="isDisabled"
                      @click="intervalosFechas()"><i class="fas fa-search-plus"></i>&nbsp;Buscar Registros</button>
            </div>
            <div class="form-group col-md-2">
              <label>&nbsp;</label><br>
              <button class="btn btn-success"
                      v-model="controlt.buscar"
                      :disabled="isDisabled"
                      @click="reporte()"><i class="fas fa-file-excel"></i>&nbsp; Reporte</button>
            </div>
            <!-- <div class="form-group col-md-2">
              <label>&nbsp;</label><br>
              <button class="btn btn-outline-dark" @click="cancelar()"
                      ><i class="fas fa-window-close"></i>&nbsp;Cancelar</button>
            </div> -->
          </div>

          <div class="form-row">
            <template v-if="supervisor_id.name === 'FELIPE REYES ASCENCIO' || supervisor_id.name === 'ANGEL MARTINEZ HERNANDEZ'
            || supervisor_id.name === 'GREGORIO ROMERO VELASCO' || supervisor_id.name === 'GLORIA TRINIDAD VELASCO' || supervisor_id.name === 'YESENIA YASBETH CRUZ SALOMON'">

            <div class="form-group col-md-6">
              <label>Nombre Supervisor</label>
              <input type="text" class="form-control" v-model="supervisor_find">
            </div>
            <div class="form-group col-md-2">
              <label>&nbsp;</label><br>
              <button class="btn btn-primary"
                      @click="buscarSuper()"><i class="fas fa-search"></i>&nbsp;Buscar Supervisor</button>
            </div>
            <div class="form-group col-md-2">
              <label>&nbsp;</label><br>
              <button class="btn btn-black" @click="cancelar()"
                      ><i class="fas fa-window-close"></i>&nbsp;Cancelar</button>
            </div>

          </template>

            <div class="form-group col-md-4">
              <!-- <button type="button" :disabled="isDisabled" @click="descargar()" class="btn btn-outline-success">
                <i class="far fa-file-excel"></i>&nbsp;Descargar Reporte
              </button> -->
              <div class="dropdown" hidden >
                <button class="btn btn-outline-success dropdown-toggle" :disabled="isDisabled"  type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Opciones
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <button class="dropdown-item btn-outline-success" type="button" @click="descargar()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte</button>
                  <button class="dropdown-item btn-outline-success" type="button" @click="descargarGeneral()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte General Trabajador</button>
                  <button class="dropdown-item btn-outline-success" type="button" @click="descargarGeneralProyecto()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte General Proyecto</button>
                  <button class="dropdown-item btn-outline-success" type="button" @click="descargarSemanal()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte Semanal</button>
                  <button class="dropdown-item btn-outline-success" type="button" @click="descargarGeneralProyectAdmin()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte General Administrativos</button>

                </div>
              </div>

            </div>




          </div>

        </div>
      </div>
      <!--<div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Subir reportes IMSS
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>&nbsp;Subir </label>
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
      </div>-->

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Detalle Control Tiempo.
        </div>
        <div class="card-body">
          <div v-if="columnas == 2">
            <v-client-table :columns="columns_normal" :data="tableData" :options="options" ref="myTable">
              <template slot="horas_extras" slot-scope="props">
                <div class="row">
                  <div class="form-group col-md-8">
                    <input type="text" :value="props.row.horas_extras" class="form-control" @input="updatehr(props,$event)">
                  </div>
                  <template v-if="props.row.horas_extras != 0 && props.row.contador < 3 && props.row.activo == 1">
                    <div class="form-group col-md-4">
                      <button type="button" name="button" class="from-control btn btn-success" @click="guardarTE(props.row)"><i class="fas fa-check"></i></button>
                    </div>
                  </template>

                </div>
              </template>
            </v-client-table>
          </div>
          <div v-if="columnas == 1">
            <v-client-table :columns="columns_supervisor" :data="tableData" :options="options" ref="myTable" >
              <template slot="id" slot-scope="props">
                <button class="btn btn-sm btn-dark" v-show="PermisosCrud.Update" @click="CambiarFecha(props.row)" >Actualizar</button>
              </template>
            </v-client-table>
          </div>

        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Empleados agregados por otro supervisor
        </div>
        <div class="card-body">
          <v-client-table :columns="columns_incidencias" :data="tableDataIncidencias" :options="options_incidencias" ref="Incidencias">
          </v-client-table>
        </div>
      </div>

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
                           data-vv-name="Fecha actividad">
                    <span class="text-danger">{{errors.first('Fecha actividad')}}</span>
                  </div>
                </div>

                <div class="row">
                  <!-- <div class="form-group col-lg-6">
                    <label>Empresa:</label>
                    <p>
                      <select name="text-input"
                              id="text-input"
                              v-model="controlt.empresa"
                              data-toggle="tooltip"
                              title="Selecciona empresa"
                              class="form-control"
                              data-vv-as="empresa">
                      <option value="CONSERFLOW">CONSERFLOW</option>
                      <option value="CSCT">CSCT</option>
                    </select>
                      <span class="text-danger">{{ errors.first('text-input') }}</span>
                    </p>
                  </div> -->

                  <div class="form-group col-lg-6">
                    <label>Proyecto:</label>
                    <v-select v-model="controlt.proyecto_id" :options="listaProyectos" label="nombre_corto" v-validate="'required'" data-vv-name="proyecto">
                    </v-select>
                    <span class="text-danger">{{ errors.first('proyecto') }}</span>
                  </div>
                </div>

                <div class="row">

                  <div class="form-group col-lg-6">
                    <label>Supervisor:</label>

                    <v-select v-model="supervisor_id" disabled label="name" name="asigna" data-vv-as="asigna"></v-select>
                    <!---->
                    <span class="text-danger">{{ errors.first('asigna') }}</span>
                  </div>



                </div>
                <div class="row">
                  <div class="form-group col-lg-12">
                    <label>Empleado asignado:</label>
                    <v-select multiple
                              v-model="controlt.empleado_asignado_id"
                              :options="vs_options"
                              label="name"
                              name="empleado_asignado"
                              data-vv-name="empleado asignado"
                              v-validate="'required'"
                              ></v-select>
                    <span class="text-danger">{{ errors.first('empleado asignado') }}</span>
                  </div>
                </div>

                <!-- <div class="row">
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
                </div> -->

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
                      
              <button type="button"
              v-if="tipoAccion==2"
              class="btn btn-secondary"
              @click="guardarTipoControl(0)"><i class="fas fa-save"></i>&nbsp;Actualizar
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
      PermisosCrud : {},
      id_control:0,
      archivo : null,
      columnas : 0,
      tipo : '',
      periodo : '',
      proyectoId : '',
      url: '/controltiemporesource',
      proyecto:{},
      supervisor_id: '',
      supervisor_busqueda : '',
      supervisor_find : '',
      controlt: {
        fechaInicial: '',
        proyecto_id: 0,
        empleado_asignado_id: '',
        horas_extras: 0,
        fechauno: '',
        fechados: '',
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
      columns_supervisor: ['id','fecha','supervisor_nombre', 'horas_extras','asignado_nombre','nombre_corto'],
      columns_normal: ['fecha', 'horas_extras','asignado_nombre','nombre_corto'],
      columns_incidencias: ['fecha','supervisor_nombre','asignado_nombre','nombre_corto'],
      tableData: [],
      tableDataIncidencias : [],
      options_incidencias : {
        headings: {
          'asignado_nombre': 'Empleado Asignado',
          'supervisor_nombre': 'Supervisor',
          'nombre_corto': 'Proyecto Asignado',
          'fecha' : 'Fecha',
          'id': "Acciones"

        },
        perPage: 15,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha','supervisor_nombre','asignado_nombre','nombre_corto'],
        filterable: ['fecha','supervisor_nombre', 'asignado_nombre','nombre_corto'],
        filterByColumn: true,
        texts: config.texts,
      },
      options: {
        // editableColumns : ['horas_extras'],
        headings: {
          'asignado_nombre': 'Empleado Asignado',
          'supervisor_nombre': 'Supervisor',
          'nombre_corto': 'Proyecto Asignado',
          'fecha' : 'Fecha'

        },
        perPage: 15,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha','supervisor_nombre', 'asignado_nombre','nombre_corto'],
        filterable: ['fecha','supervisor_nombre', 'asignado_nombre','nombre_corto'],
        filterByColumn: true,
        texts: config.texts,
        // requestAdapter : function (data) {
        //   var arr = [];
        //    arr.push({
        //     'fecha' : data.query.fecha,
        //     '' : data.query.rfc_receptor,
        //    'factura.fecha_hora' : data.query.fecha_hora,
        //    'c_tipofactura' : data.query.c_tipofactura,
        //    'factura.uuid' : data.query.uuid,
        //    'factura.total' : data.query.total,
        //    'factura.timbrado' : data.query.timbrado
        //    });
        //   data.query = arr[0];
        //   return data;
        // },
      },
    }
  },

  methods: {
    getDataGral(){
      let me = this;

      axios.get('/controltiempo').then(response => {
        me.supervisor_id = {id : response.data.id, name : response.data.nombre +' '+ response.data.ap_paterno + ' ' + response.data.ap_materno};
        if (response.data.id == 11 || response.data.id == 85 || response.data.id == 119 || response.data.id == 10 || response.data.id == 226) {
          me.columnas = 1;
        }else {
          me.columnas = 2;
        }
      }).catch(function (error) {
        console.log(error);
      });

      axios.get('/proyecto').then(response => {
        // me.listaProyectos = response.data;
        // let aux={id:0,nombre_corto:"Seleccione proyecto"};
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
      this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
      axios.get('/controltiempo/busqueda/getEmp').then(response => {
        me.tableData = response.data;
      }).catch(error => {
        console.error(error);
      });

      axios.get('/get/incidencia/tiempos/').then(response => {
        me.tableDataIncidencias = response.data;
        if (response.data.length > 0) {
          toastr.warning('Empleados dados de alta por otro supervisor!!!');
        }
      }).catch(e => {
        console.error(e);
      });

      },

      descargar() {
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
        window.open('descargar-control/' + this.controlt.fechauno + '&' + this.controlt.fechados + '&' +numerodesemana, '_blank');
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
            axios({
              method: nuevo ? 'POST' : 'PUT',
              url: nuevo ? me.url : me.url + '/' + this.id,
              data: {
                'fecha': this.controlt.fechaInicial,
                'proyecto_id': this.controlt.proyecto_id.id,
                'supervisor_id': this.supervisor_id,
                'empleado_asignado_id': this.controlt.empleado_asignado_id,
                'horas_extras': this.controlt.horas_extras,
                'empresa': this.controlt.empresa,
                "id_control":this.id_control,
              }
            }).then(function (response) {

              if (response.data.status) {
                if (response.data.status === 'error') {
                  swal({
                    type: 'error',
                    html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
                  });
                  me.cerrarModal();
                }else {
                  console.log(response.data);
                  me.cerrarModal();
                  me.getData();
                  if (!nuevo) {
                    toastr.success(' Actualizada Correctamente');
                  } else {
                    toastr.success('Registrada Correctamente');
                  }
                }
              }

              // {
              //   if (response.data.error === true && response.data.status === false) {
              //     toastr.error(' Verifica datos registrados \n empleado o fecha duplicada' + response.data.empleado);
              //   } else {
              //     swal({
              //       type: 'error',
              //       html: response.data.errors.join('<br>'),
              //     });
              //   }
              // }


            }).catch(function (error) {
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

      updatehr(i,data){
        const result = this.tableData.findIndex(id => id.id == i.row.id);
        this.tableData[result].horas_extras = data.target.value;
        this.tableData[result].activo = 1;
      },

      guardarTE(data){
        axios.post('guardar/tiempo/extra',{
          id : data.id,
          horas_extras : data.horas_extras
        }).then(response => {
          toastr.success('Hora Extra guardada correctamente ');
          this.tableData = [];

          axios.get('/controltiempo/busqueda/getEmp').then(response => {
            this.tableData = response.data;
          }).catch(error => {
            console.error(error);
          });

        }).catch(e => {console.error(e);})
      },

      buscarSuper(){
        let me = this;
        axios.post('buscar/registros/supervisor/',{name : me.supervisor_find}).then(response => {
          me.tableData = response.data;
        }).catch(e => {
          console.error(e);
        });
      },
      CambiarFecha(model)
      {
        this.modal = 1;
        this.tituloModal = 'Actualizar Actividad';
        this.tipoAccion = 2;
        this.controlt.fechaInicial=model.fecha;
        this.controlt.proyecto_id={"proyecto_id":model.proyecto_id, "nombre_corto":model.nombre_corto};
        this.controlt.empleado_asignado_id=[{"name":model.asignado_nombre, "id":model.empleado_asignado_id}];
        this.supervisor_id={"name":model.supervisor_nombre,"id":model.supervisor_id};
        this.id_control=model.id;
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
