<template>

  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Subir
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>&nbsp;Subir --</label>
              <input class="form-control" type="file" v-validate="'ext:xls,xlsx,xml'" data-vv-as="Archivo"
              name="import_file_emp" @change="getArchivo" accept="*/*" id="archivo" ref="file" multiple>
              <span class="text-danger">{{ errors.first('import_file_emp') }}</span>
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


      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Subir
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>&nbsp;Semana</label>
              <input class="form-control" v-model="semana_nm">
            </div>
            <div class="form-group col-md-4">
              <button @click="generar" class="btn btn-primary" >Generar</button>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>&nbsp;Numero</label>
              <select class="form-control" v-model="numero_q">
                <option value="1 Q ">Primera quincena de</option>
                <option value="2 Q ">Segunda quincena de</option>
              </select>
            </div>
            <div class="form-group col-md-4">
              <label>&nbsp;Mes</label>
              <select class="form-control" v-model="mes_q">
                <option value="E">Enero</option>
                <option value="F">Febrero</option>
                <option value="MZ">Marzo</option>
                <option value="AB">Abril</option>
                <option value="MY">Mayo</option>
                <option value="JN">Junio</option>
                <option value="JL">Julio</option>
                <option value="AG">Agosto</option>
                <option value="SP">Septiembre</option>
                <option value="OC">Octubre</option>
                <option value="NV">Noviembre</option>
                <option value="DC">Diciembre</option>
              </select>
            </div>
            <div class="form-group col-md-4">
              <button @click="generar_quincena" class="btn btn-primary" >Generar</button>
            </div>
          </div>
        </div>

      </div>
      <!-- <div class="card">
        <div class="card-header">
          <button type="button" v-if="estado == 2" @click="maestro()" class="btn btn-secondary float-sm-right">
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">

          <div v-if="estado == 1">
          <v-client-table :data="sueldosDatos" :options="options" :columns="columns" ref="uno">
            <template slot="id" slot-scope="props">
              <button class="btn btn-outline-dark" @click="cargardetalle(props.row.semana)" >
                 <i class="fas fa-list"></i>&nbsp;Detalles
               </button>
            </template>
            <template slot="semana" slot-scope="props">
              Semana {{props.row.semana}}
            </template>
          </v-client-table>
          </div>

          <div v-show="estado == 2">
          <v-client-table :data="sueldosDatosDos" :options="optionsdos" :columns="columnsdos" ref="dos">
          </v-client-table>
          </div>

        </div>
      </div> -->
      <!-- <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Subir gastos comida semanales
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>&nbsp;Subir </label>
              <input class="form-control" type="file" v-validate="'ext:xls,xlsx'" data-vv-as="Archivo"
              name="import_file_emp" @change="getArchivo_uno" accept="*/*" id="archivo" >
              <span class="text-danger">{{ errors.first('import_file_emp') }}</span>
            </div>
            <div class="form-group col-md-4">
              <label>Rango de semanas a guardar</label>
              <input type="text" class="form-control" v-model="semanas_uno">
            </div>
            <div class="form-group col-md-4">
              <label>Empresa</label>
              <select class="form-control" v-model="empresa">
                <option value="CONSERFLOW">Conserflow</option>
                <option value="CSCT">CSCT</option>
              </select>
            </div>


          </div>
        </div>
        <div class="card-footer">
          <div class="form-row">
            <div class="form-group col-md-4">
              <button @click="update_uno" class="btn btn-primary" >Subir Archivo</button>
            </div>
          </div>

        </div>
      </div> -->

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

    </div>
  </main>

</template>

<script>

import Utilerias from '../Herramientas/utilerias.js';

var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data() {
    return {
      numero_q : '',
      mes_q : '',
      semana_nm : '',
      archivo : null,
      archivo_uno : null,
      tipo : '',
      periodo : '',
      semanas : '',
      semanas_uno : '',
      empresa : '',
      estado : 1,
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
      columns: ['id','semana'],
      columnsdos: ['val.nombre','t.total'],
      tableData: [],
      sueldosDatos : [],
      sueldosDatosDos : [],
      options: {
        headings: {
          'id' : 'Acciones',
        },
        perPage: 20,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['semana'],
        filterable: ['semana'],
        filterByColumn: true,
        texts: config.texts
      },
      optionsdos: {
        headings: {
          'val.nombre' : 'Empleado',
          't.total' : 'Total',
        },
        perPage: 20,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['val.nombre','t.total'],
        filterable: ['val.nombre','t.total'],
        filterByColumn: true,
        texts: config.texts
      },
    }
  },

  methods: {

    getData() {
      // let me = this;
      // axios.get('/controltiempo').then(response => {
      //   me.tableData = response.data;
      // }).catch(function (error) {
      //   console.log(error);
      // });
      //
      // axios.get('/proyecto').then(response => {
      //   // me.listaProyectos = response.data;
      //   let aux={id:0,nombre_corto:"Seleccione proyecto"};
      //   me.listaProyectos.push(aux);
      //   me.proyecto=aux;
      //   response.data.forEach(p=>
      //     {
      //       me.listaProyectos.push
      //       ({
      //         id:p.proyecto.id,
      //         nombre_corto:p.proyecto.nombre_corto,
      //       });
      //     });
      //
      //   })
      //   .catch(function (error) {
      //     console.log(error);
      //   });
      //
      //   axios.get('/vertodosempleados').then(response => {
      //     this.vs_options = [];
      //     for (var i = 0; i < response.data.length; i++) {
      //       this.vs_options.push({
      //         id : response.data[i].id,
      //         name : response.data[i].nombre + ' ' + response.data[i].ap_paterno + ' ' + response.data[i].ap_materno,
      //       });
      //     }
      //   }).catch(error => {
      //     console.error(error);
      //   });
      axios.get('sueldos/semanales').then(response => {
        this.sueldosDatos = response.data;
      }).catch(e => {
        console.error();
      });
      },

      cargardetalle(data){
        this.estado = 2;
        axios.get('sueldos/semanales/empleados/'+data).then(response => {
          this.sueldosDatosDos = response.data;
        }).catch(e => {
          console.error();
        });
      },

      maestro(){
        this.estado = 1;
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
                me.getData();
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
        console.log(event);

        //Asignamos el archivo a  nuestra data
        this.archivo = event.target.files;
        // console.log(event);
      },

      getArchivo_uno(event){
        //Asignamos el archivo a  nuestra data
        this.archivo_uno = event.target.files[0];
        // console.log(event);
      },

      update(){


            // this.isLoading = true;
            //Creamos el formData
            var data = new  FormData();
            //Añadimos la imagen seleccionada
            // data.append('import_file', this.archivo);
            for( var i = 0; i < this.$refs.file.files.length; i++ ){
              let file = this.$refs.file.files[i];
              data.append('files'+i, file);
            }

            data.append('tamanio',this.$refs.file.files.length);
            //Añadimos el método PUT dentro del formData
            // Como lo hacíamos desde un formulario simple _(no ajax)_
            data.append('_method', 'PUT');
            //Enviamos la petición
            axios.post('/xml/upload',data)
            .then(response => {
              // console.log(response);
              this.archivo = null;
              // var field = this.$validator.fields.find({ name: 'email' });
              // field.reset();
              if (response.status == 200){
                this.getData();
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

      update_uno(){


            // this.isLoading = true;
            //Creamos el formData
            var data = new  FormData();
            //Añadimos la imagen seleccionada
            data.append('import_file', this.archivo_uno);
            data.append('semanas', this.semanas_uno);
            data.append('empresa', this.empresa);
            //Añadimos el método PUT dentro del formData
            // Como lo hacíamos desde un formulario simple _(no ajax)_
            data.append('_method', 'PUT');
            //Enviamos la petición
            axios.post('/registrogastosesemanales/upload',data)
            .then(response => {
              // console.log(response);
              this.archivo_uno = null;
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
              this.archivo_uno = null;
              // this.isLoading = false;
              console.log(error);
              swal(
                'Articulos',
                'Ocurrio un error al leer el archivo.',
                'error'
              );
            });

      },

      generar(){
        window.open('descargar-reporte-semana/' + this.semana_nm, '_blank');
      },

      generar_quincena(){
        window.open('descargar-reporte-quincena/' + this.numero_q + this.mes_q, '_blank');
      }


    },
    computed: {

    },
    mounted() {
      // this.getData();
    }
  }
  </script>
