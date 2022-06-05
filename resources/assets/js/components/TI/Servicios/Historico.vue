<template >
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Histórico Servicios

          <button type="button" class="btn btn-dark float-sm-right" @click="abrirModal(1)">
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
                    <button type='button' @click='abrirModal(2, props.row)' class='dropdown-item'>
                      <i class='fas fa-pencil'></i>&nbsp;Actualizar
                    </button>
                    <button type='button' @click='eliminar(props.row.id)' class='dropdown-item'>
                      <i class='fas fa-trash'></i>&nbsp;Eliminar
                    </button>
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
                <div class="col-md-3 mb-3">
                  <label>Tipo</label>
                  <select class="form-control" v-model="tipo" data-vv-name="tipo" v-validate="'required'">
                    <option value="PREVENTIVO">Preventivo</option>
                    <option value="CORRECTIVO">Correctivo</option>
                  </select>
                  <!-- <input type="text" class="form-control" v-model="tipo" data-vv-name="tipo" v-validate="'required'"> -->
                  <span class="text-danger">{{errors.first('tipo')}}</span>
                </div>
                <div class="col-md-5 mb-3">
                  <label>Usuario</label>
                  <v-select :options="listaEmpleados" label="name" v-model="usuario"
                  data-vv-name="usuario" v-validate="'required'" taggable></v-select>
                  <span class="text-danger">{{errors.first('usuario')}}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-9 mb-3">
                  <label>Problema/Servicio</label>
                  <textarea class="form-control" name="name" rows="2" cols="80" data-vv-name="Problema/Servicio" v-validate="'required'"
                  v-model="problema_servicio"></textarea>
                  <span class="text-danger">{{errors.first('Problema/Servicio')}}</span>
                </div>
                <div class="col-md-3 mb-3">
                  <label>Fecha Reporte</label>
                  <input type="date" class="form-control" v-model="fecha_reporte" data-vv-name="fecha reporte" v-validate="'required'">
                  <span class="text-danger">{{errors.first('fecha reporte')}}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-9 mb-3">
                  <label>Solución</label>
                  <textarea class="form-control" name="name" rows="2" cols="80" data-vv-name="solucion" v-validate="'required'"
                  v-model="solucion"></textarea>
                  <span class="text-danger">{{errors.first('solucion')}}</span>
                </div>
                <div class="col-md-3 mb-3">
                  <label>Fecha Solución</label>
                  <input type="date" class="form-control" v-model="fecha_solucion" data-vv-name="fecha solucion" v-validate="'required'">
                  <span class="text-danger">{{errors.first('fecha solucion')}}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-3 mb-3">
                  <label>Reincidencia</label>
                  <input type="text" class="form-control" v-model="reincidencia" data-vv-name="reincidencia" v-validate="'required'">
                  <span class="text-danger">{{errors.first('reincidencia')}}</span>
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

export default{
  data(){
    return {
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,

      listaEmpleados : [],

      id : 0,
      tipo : '',
      usuario : '',
      problema_servicio : '',
      fecha_reporte : '',
      solucion : '',
      fecha_solucion : '',
      reincidencia : '',

      tableData : [],
      columns : ['id','tipo','nombre_usuario','problema_servicio','fecha_reporte','solucion','fecha_solucion','reincidencia'],
      options :
      {
        headings:
        {
          'id': 'Acciones',
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
  methods : {
    getData(){
      axios.get('historico/servicios/ti/get').then(response => {
        this.tableData = response.data;
      }).catch(e =>{
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

    abrirModal(edo, data = []){
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
        this.tipo = data['tipo'];
        if (data['usuario'] == 0) {
          this.usuario = {name : data['nombre_usuario']};
        }else {
          this.usuario = {id : data['usuario'], name : data['nombre_usuario']};
        }
        this.problema_servicio = data['problema_servicio'];
        this.fecha_reporte = data['fecha_reporte'];
        this.solucion = data['solucion'];
        this.fecha_solucion = data['fecha_solucion'];
        this.reincidencia = data['reincidencia'];
      }
    },

    cerrarModal(){
      this.modal = 0;
    },

    vaciar(){
      this.tipo = '';
      this.usuario = '';
      this.problema_servicio = '';
      this.fecha_reporte = '';
      this.solucion = '';
      this.fecha_solucion = '';
      this.reincidencia = '';
    },

    Guardar(nuevo){
      console.log();
      var usuario = 0;
      var nombre_usuario = '';

      if ((this.usuario.id === undefined) == true) {
        usuario = 0;
        nombre_usuario = this.usuario.name;
      }else {
        usuario = this.usuario.id;
        nombre_usuario = this.usuario.name;
      }

      this.$validator.validate().then(result => {
        if (result) {
          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? 'historico/servicios/ti/guardar' : 'historico/servicios/ti/actualizar',
            data : {
              id : this.id,
              tipo : this.tipo,
              usuario : usuario,
              nombre_usuario : nombre_usuario,
              problema_servicio : this.problema_servicio,
              fecha_reporte : this.fecha_reporte,
              solucion : this.solucion,
              fecha_solucion : this.fecha_solucion,
              reincidencia : this.reincidencia,
            }
          }).then(response =>{
            toastr.success(nuevo ? 'Guardado Correctamente' : 'Actualizado Correctamente');
            this.cerrarModal();
            this.getData();
          }).catch(e => {
            console.error(e);
          });
        }
      });
    },

    eliminar(id){
      axios.get('historico/servicios/ti/delete/' + id).then(res => {
        toastr.success('Eliminado Correctamente');
        this.getData();
      }).catch(e => {
        console.error(e);
      });
    },

    descargar(){
      window.open('historico/servicios/ti/descargar' , '_blank');

    },


  },
  mounted(){
    this.getData();
    this.getLista();
  }
}

</script>
