<template>
  <main class="main">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Registro sabana tiempo por Horas
          <button @click="abrirModal(1)" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <v-client-table :data="tableData" :columns="columns" :options="options">
          </v-client-table>
        </div>
      </div>
      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">

            <div class="modal-header">
              <h4 class="modal-title" >Guardar</h4>
              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-row">
                <div class="col-md-4 mb-3">
                  <label>Empleado asignado:</label>
                  <v-select v-model="controlt.empleado_asignado_id" :options="listaEmpleados" label="name" name="empleado_asignado" data-vv-name="empleado asignado" v-validate="'required'">
                  </v-select>
                  <span class="text-danger">{{errors.first('empleado asignado')}}</span>
                </div>
                <div class="col-md-3 mb-3">
                  <label>Fecha</label>
                  <input type="date" name="fecha_inicial" id="fecha-inicial" class="form-control" v-validate="'required'" v-model="controlt.fecha" data-vv-name="Fecha actividad">
                  <span class="text-danger">{{errors.first('Fecha actividad')}}</span>
                </div>
                <div class="col-md-1 mb-3">
                  <label>Horas</label>
                  <input type="text" class="form-control" v-validate="'required|decimal:2'" v-model="controlt.horas" data-vv-name="Horas">
                  <span class="text-danger">{{errors.first('Fecha actividad')}}</span>
                </div>
                <div class="col-md-4 mb-3">
                  <label>Proyecto:</label>
                  <v-select v-model="controlt.proyecto_id" :options="listaProyectos" label="nombre_corto" v-validate="'required'" data-vv-name="proyecto">
                  </v-select>
                  <span class="text-danger">{{ errors.first('proyecto') }}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-12">
                  <button class="btn btn-outline-dark float-right" @click="crear()"> <i class="fas fa-plus"></i> Crear</button>
                </div>
              </div>

              <hr>
              <div class="form-row">

                <div class="form-group col-md-3">
                  <label><b>EMPLEADO</b></label>
                </div>
                <div class="form-group col-md-3">
                  <label><b>PROYECTO</b></label>
                </div>
                <div class="form-group col-md-2">
                  <label><b>FECHA</b></label>
                </div>
                <div class="form-group col-md-2">
                  <label><b>HORAS</b></label>
                </div>
                <div class="form-group col-md-2">
                  <label><b>ACCIONES</b></label>
                </div>
              </div>
              <li v-for="(vi, index) in listaRegistros" class="list-group-item">
                <div class="form-row">

                  <div class="form-group col-md-3">
                    <label>{{vi.empleado}}</label>
                  </div>
                  <div class="form-group col-md-3">
                    <label>{{vi.proyecto}}</label>
                  </div>
                  <div class="form-group col-md-2">
                    <label>{{vi.fecha}}</label>
                  </div>
                  <div class="form-group col-md-2">
                    <label>{{vi.horas}}</label>
                  </div>
                  <div class="form-group col-md-2">
                    <a @click="deleteu(index)">
                      <span class="fas fa-trash" arial-hidden="true"></span>
                    </a>
                  </div>
                </div>
              </li>

            </div>
            <div class="modal-footer">
              <vue-element-loading :active="isLoading" />
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar
              </button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarTipoControl(1)"><i class="fas fa-save"></i>&nbsp;Guardar
              </button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarTipoControl(0)"><i class="fas fa-save"></i>&nbsp;Actualizar
              </button>
            </div>

          </div>
        </div>
      </div>

    </div>
  </main>
</template>
<script type="text/javascript">
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      isLoading : false,
      modal : 0,
      tipoAccion : 0,
      controlt : {
        empleado_asignado_id : '',
        proyecto_id : '',
        fecha : '',
        horas : '',
      },
      tableData : [],
      listaProyectos : [],
      listaEmpleados : [],
      listaRegistros : [],
      columns : ['id','empleado','fecha','hora','proyecto'],
      options : {
        headings: {
          id : 'Acciones'
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
  methods : {
    getData(){
      let me = this;
      axios.get('/proyecto').then(response => {

        me.listaProyectos = [];
        let aux={id:0,nombre_corto:"Sin proyecto"};
        me.proyecto=aux;
        response.data.forEach(p => {
            me.listaProyectos.push({
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
          me.listaEmpleados = [];
          response.data.forEach((item, i) => {
            me.listaEmpleados.push({
              id : item.id,
              name : item.nombre + ' ' + item.ap_paterno + ' ' + item.ap_materno,
            });
          });
        }).catch(error => {
          console.error(error);
        });
    },

    abrirModal(estado , data = []){
      this.modal = 1;
      if (estado == 1) {

      }else if (estado == 2) {

      }
    },

    cerrarModal(){
      this.modal = 0;
    },

    crear(){
      if (this.controlt.horas === '' || this.controlt.proyecto_id === '') {
        toastr.warning('Seleccione un proyecto/hora');
      }else {
        this.listaRegistros.push({
          empleado : this.controlt.empleado_asignado_id.name,
          empleado_id : this.controlt.empleado_asignado_id.id,
          proyecto_id : this.controlt.proyecto_id.id,
          proyecto : this.controlt.proyecto_id.nombre_corto,
          fecha : this.controlt.fecha,
          horas : this.controlt.horas,
        });
        this.controlt.horas = '';
        this.controlt.proyecto_id = '';
      }
    },

    deleteu(index , vi = []){
      if (this.tipoAccion == 1) {
        this.listaRegistros.splice(index, 1);
      }else if (this.tipoAccion == 2) {
        // axios.get('eliminar/accesorio/resguardo/ti/' + vi.id + '&' + this.resguardo.id).then(response => {
        //   toastr.success('Correcto !!!');
        //   this.listado_data_accesorios.splice(index, 1);
        //   this.getAccesorios();
        //   this.getInicial(this.empresa);
        // }).catch(e => {
        //   console.error(e);
        // });
      }
    },
  },
  mounted() {
    this.getData();
  }
}
</script>
