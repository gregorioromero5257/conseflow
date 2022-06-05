<template>

  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Control de tiempo horas extra.
        </div>

        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="inputEmail4">Fecha inicio</label>
              <input type="date" class="form-control" v-model="controlt.fechauno" data-vv-name="fechauno">
              <span class="text-danger">{{ errors.first('fechauno') }}</span>
            </div>
            <div class="form-group col-md-4">
              <label for="inputEmail4">Fecha fin</label>
              <input type="date" class="form-control" v-model="controlt.fechados" data-vv-name="fechados">
              <span class="text-danger">{{ errors.first('fechados') }}</span>
            </div>
            <div class="form-group col-md-4">
              <label for="inputEmail4">&nbsp;</label><br>
              <button class="btn btn-primary"
              v-model="controlt.buscar"
              :disabled="isDisabled"
              @click="getDataIntervalo()"><i class="fas fa-search-plus"></i>&nbsp;Buscar</button>
            </div>
          </div>


        </div>
      </div>


      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Horas Extras.
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <!-- // update text on the fly -->
            <template slot="horas_extras" slot-scope="props">

              <div class="row">
                <div class="form-group col-md-8">
                  <input type="text" :value="props.row.horas_extras" class="form-control" @keyup.enter="updatehr(props,$event)">
                </div>
                <!-- <template v-if="props.row.horas_extras != 0">
                  <div class="form-group col-md-4">
                    <button type="button" name="button" class="from-control btn btn-success"><i class="fas fa-check"></i></button>
                  </div>
                </template> -->

              </div>
            </template>

            <template slot="fecha" slot-scope="props">
              {{fecha(props.row.fecha)}}
            </template>

            <template slot="nuevo_proyectos" slot-scope="props">
              <select class="form-control" @change="updatephr(props,$event)">
                <option :value="props.row.proyecto_id_he">{{props.row.nombre_corto_he}}</option>
                <option v-for="t in listaProyectos"  :key="t.id" :value="t.id">{{t.nombre_corto}}</option>
              </select>
              <!-- <button type="button" name="button" class="from-control btn btn-success" @click="updatephr(props)"><i class="fas fa-check"></i></button> -->

            </template>

          </v-client-table>
        </div>
      </div>

      <!-- Fin ejemplo de tabla Listado -->

    </div>
  </main>

</template>

<script>

import Utilerias from '../../Herramientas/utilerias.js';

var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data() {
    return {
      botones : 1,
      archivo : null,
      tipo : '',
      periodo : '',
      url: '/controltiemporesource',
      proyecto:{},
      supervisor_id: '',
      controlt: {
        fechaInicial: '',
        proyecto_id: 0,
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
      columns: ['fecha','supervisor_nombre','asignado_nombre','nombre_corto','horas_extras','nuevo_proyectos'],
      tableData: [],
      options: {
        // editableColumns : ['horas_extras'],
        headings: {
          'asignado_nombre': 'Empleado Asignado',
          'supervisor_nombre': 'Supervisor',
          'nombre_corto': 'Proyecto Asignado',
          'fecha' : 'Fecha',
          'nuevo_proyectos' : 'Proyecto Hora Extra',

        },
        groupBy: ['nombre_proyecto'],
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
    datas(e){
      console.log(e);

    },

    fecha(fecha){
      const meses = [
        "Enero", "Febrero", "Marzo",
        "Abril", "Mayo", "Junio", "Julio",
        "Agosto", "Septiembre", "Octubre",
        "Noviembre", "Diciembre"
      ];

      const date = new Date(fecha);
      const dia = date.getDate() + 1;
      const mes = date.getMonth();
      const ano = date.getFullYear();
      return `${dia} de ${meses[mes]} del ${ano}`;
    },

    getDataIntervalo(){
      let me = this;
      axios.get('/controltiempo/busqueda/th/' + this.controlt.fechauno + '&' + this.controlt.fechados).then(response => {
        me.tableData = response.data;
      }).catch(error => {
        console.error(error);
      });
    },

    getData() {
      let me = this;
      // axios.get('/controltiempo/busqueda/th').then(response => {
      //   me.tableData = response.data;
      // }).catch(error => {
      //   console.error(error);
      // });

      axios.get('/controltiempo').then(response => {
        me.supervisor_id = {id : response.data.id, name : response.data.nombre +' '+ response.data.ap_paterno + ' ' + response.data.ap_materno};
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
                'supervisor_id': this.supervisor_id,
                'empleado_asignado_id': this.controlt.empleado_asignado_id,
                'horas_extras': this.controlt.horas_extras,
                'empresa': this.controlt.empresa,
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

      updatehr(i,data){
        axios.post('guardar/horas/hr',{
          id : i.row.id,
          horas : data.target.value,
        }).then(response => {
          toastr.success('Guardado Correctamente!!!');
          this.getDataIntervalo();
        }).catch(e => {
          console.error(e);
        });
      },

      updatephr(i,data){
        axios.post('guardar/proyecto/hr',{
          id : i.row.id,
          proyecto_id : data.target.value,
        }).then(response => {
          toastr.success('Guardado Correctamente!!!');
          this.getDataIntervalo();
        }).catch(e => {
          console.error(e);
        });
      },

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
    }
  }
  </script>
