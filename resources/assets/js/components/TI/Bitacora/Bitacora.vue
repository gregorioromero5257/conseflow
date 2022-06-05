<template >
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <button type="button" class="btn btn-dark float-sm-right" @click="AbrirModal(1)">
              <i class="fas fa-plus">&nbsp;</i>Nuevo
          </button>
          <button type="button" @click="descargar()" class="btn btn-dark float-sm-right">
            <i class="fas fa-file-excel"></i>&nbsp;Descargar
          </button>
        </div>
        <div class="card-body">
          <v-client-table :data="tableData" :options="options" :columns="columns">
            <template slot='id' slot-scope='props'>
              <div class='btn-group' role='group'>
                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  <i class='fas fa-grip-horizontal'></i>&nbsp;
                </button>
                <div class='dropdown-menu'>
                  <template>
                    <button type='button' @click='AbrirModal(2, props.row)' class='dropdown-item'>
                      <i class='fas fa-pencil'></i>&nbsp;Actualizar
                    </button>
                    <!-- <button type='button' @click='eliminar(props.row)' class='dropdown-item'>
                      <i class='fas fa-trash'></i>&nbsp;Eliminar
                    </button> -->
                  </template>
                </div>
              </div>
            </template>
          </v-client-table>
        </div>
      </div>

      <!-- Modal -->
      <div class='modal fade' tabindex='-1' :class="{'mostrar' : modal}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h4 class='modal-title' v-text='tituloModal'></h4>
              <button type='button' class='close' @click='cerrarModal()' aria-label='Close'>
                <span aria-hidden='true'>×</span>
              </button>
            </div>
            <div class='modal-body'>

              <div class="form-row">
                <div class="col-md-4 mb-3">
                  <label>Responsable Información</label>
                  <v-select :options="listaEmpleados" label="name" v-model="responsable_i" data-vv-name="responsable" v-validate="'required'"></v-select>
                  <span class="text-danger">{{errors.first('responsable')}}</span>
                </div>
                <div class="col-md-5 mb-3">
                  <label>Ruta de respaldo</label>
                  <input type="text" class="form-control" v-validate="'required'" data-vv-name="ruta" v-model="ruta">
                  <span class="text-danger">{{errors.first('ruta')}}</span>
                </div>
                <div class="col-md-3 mb-3">
                  <label>Tamaño</label>
                  <input type="text" class="form-control" v-model="tamanio" data-vv-name="tamaño" v-validate="'required'">
                  <span class="text-danger">{{errors.first('tamaño')}}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-3 mb-3">
                  <label>Fecha Resguardo</label>
                  <input type="date" class="form-control" v-model="fecha" data-vv-name="fecha" v-validate="'required'">
                  <span class="text-danger">{{errors.first('fecha')}}</span>
                </div>
                <div class="col-md-5 mb-3">
                  <label>Ubicación Resguardo</label>
                  <input type="text" class="form-control" v-model="ubicacion" data-vv-name="ubicacion" v-validate="'required'">
                  <span class="text-danger">{{errors.first('ubicacion')}}</span>
                </div>
                <div class="col-md-4 mb-3">
                  <label>Responsable Respaldo</label>
                  <v-select :options="listaEmpleados" label="name" v-model="responsable_r" data-vv-name="responsable" v-validate="'required'"></v-select>
                  <span class="text-danger">{{errors.first('responsable')}}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-6 mb-3">
                  <label>Observaciones</label>
                  <textarea name="name" rows="2" cols="80" class="form-control" v-model="observaciones"></textarea>
                </div>
              </div>

            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-outline-dark' @click='cerrarModal()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
              <button type='button' v-if='tipoAccion == 1' class='btn btn-secondary' @click='Guardar(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
              <button type='button' v-if='tipoAccion == 2' class='btn btn-secondary' @click='Guardar(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- Modal -->


    </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return {
      responsable_i: '',
      ruta :'',
      tamanio : '',
      fecha : '',
      ubicacion: '',
      responsable_r : '',
      observaciones : '',

      listaEmpleados : [],

      modal : 0,
      tituloModal : '',
      tipoAccion : '',

      tableData : [],
      columns : ['id','fecha','ruta','tamanio','responsable_i','responsable_r'],
      options :
      {
        headings:
        {
          'id': 'Acciones',
          'responsable_i' : 'Responsable',
          'responsable_r' : 'Responsable',
        }, // Headings,
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
      }, //options
    }
  },
  methods :{
    getData(){
      axios.get('get/data/bitacora/resguardo/info').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    getLista(){
      this.listaEmpleados = [];
      axios.get('/vertodosempleados').then(response =>{
        response.data.forEach(data =>{
          this.listaEmpleados.push({
            id: data.id,
            name: data.nombre + ' ' + data.ap_paterno + ' ' + data.ap_materno
          });
        });
      })
      .catch(function (error)
      {
        console.log(error);
      });
    },


    AbrirModal(edo, data = []){
      if (edo == 1) {
        this.vaciar();
        this.modal = 1;
        this.tituloModal = 'Guardar';
        this.tipoAccion = 1;
      }else if (edo == 2) {
        this.vaciar();
        this.modal = 1;
        this.tituloModal = 'Actualizar';
        this.tipoAccion = 2;
        this.id = data['id'];
        this.responsable_i= {id : data['responsable_i'], name : data['empleado_ii']};
        this.ruta= data['ruta'];
        this.tamanio = data['tamanio'];
        this.fecha = data['fecha'];
        this.ubicacion= data['ubicacion'];
        this.responsable_r = {id : data['responsable_r'], name : data['empleado_ri']};
        this.observaciones = data['observaciones'];
      }
    },

    vaciar(){
      this.responsable_i= "";
      this.ruta= "";
      this.tamanio = "";
      this.fecha = "";
      this.ubicacion= "";
      this.responsable_r = "";
      this.observaciones = "";
    },

    Guardar(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? 'guardar/data/bitacora/resguardo/info' : 'actualizar/data/bitacora/resguardo/info',
            data : {
              id : this.id,
              responsable_i : this.responsable_i.id,
              ruta : this.ruta,
              tamanio : this.tamanio,
              fecha : this.fecha,
              ubicacion : this.ubicacion,
              responsable_r : this.responsable_r.id,
              observaciones : this.observaciones,
            }
          }).then(response => {
            toastr.success(nuevo ? 'Guardado Correctamente !!!' : 'Actualizado Correctamente !!!');
            this.getData();
            this.cerrarModal();
          }).catch(e => {
            console.error(e);
          });
        }
      });
    },

    cerrarModal(){
      this.modal = 0;
    },

    descargar(){
      window.open('descargar/data/bitacora/resguardo/info' , '_blank');
    },

  },
  mounted(){
    this.getData();
    this.getLista();
  },
}

</script>
