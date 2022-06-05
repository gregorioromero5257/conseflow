<template>
  <div>

    <div class="form-row">
      <div class="form-group col-md-4">
        <label>Fecha</label>
        <input type="date" class="form-control" v-model="servicio.fecha_servicio" data-vv-name="Fecha servicio_" v-validate="'required'">
        <span class="text-danger">{{errors.first('Fecha servicio_')}}</span>
      </div>
      <div class="form-group col-md-4">
        <label>Fecha entrega</label>
        <input type="date" class="form-control"  v-model="servicio.fecha_entrega" data-vv-name="Fecha entrega_" v-validate="'required'">
        <span class="text-danger">{{errors.first('Fecha entrega_')}}</span>
      </div>
      <div class="form-group col-md-3">
        <label>Kilometraje</label>
        <input type="number" class="form-control"  v-model="servicio.kilometraje" data-vv-name="Kilometraje_" v-validate="'required'">
        <span class="text-danger">{{errors.first('Kilometraje_')}}</span>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-5">
        <label>Proveedor</label>
        <input type="text" class="form-control"  v-model="servicio.proveedor" data-vv-name="Proveedor_" v-validate="'required'">
        <span class="text-danger">{{errors.first('Proveedor_')}}</span>
      </div>
      <div class="form-group col-md-4">
        <label>Responsable</label>
        <input type="text" class="form-control" v-model="servicio.responsable" data-vv-name="responsable_" v-validate="'required'">
        <span class="text-danger">{{errors.first('responsable_')}}</span>
      </div>
      <div class="form-group col-md-3">
        <label>Total</label>
        <input type="text" class="form-control" v-model="servicio.total" data-vv-name="Total_" v-validate="'required'">
        <span class="text-danger">{{errors.first('Total_')}}</span>
      </div>
    </div>
    <hr>
    <div class="form-row">
      <div class="form-group col-md-4">
        <label>Operacion</label>
        <select class="form-control"  v-model="servicio.operacion" @change="cambiar()">
          <option :value="0" selected>--Seleccione--</option>
          <option :value="1">Verificación de niveles</option>
          <option :value="2">Sustituciones</option>
          <option :value="3">Comprobación y ajuste de componentes</option>
          <option :value="4">Refacciones</option>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label>Tipo</label>
        <v-select :options="operacion_array" v-model="servicio.tipo" label="name"></v-select>
      </div>
      <div class="form-group col-md-2">
        <label>&nbsp;</label>
        <button type="button" class="form-control" name="button" @click="confirmar()">Confirmar</button>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-12">
        <label>Servicios</label>
        <v-select multiple :options="servicios_totales" label="name" v-model="servicio.tipo_servicios" data-vv-name="Servicio" v-validate="'required'"></v-select>
        <span class="text-danger">{{errors.first('Servicio')}}</span>
      </div>
    </div>
    <hr>
    <div class="form-row">
      <div class="form-group col-md-5">
        <label>Comprobante</label>
        <input type="file" class="form-control" name="comprobante" @change="onChangeServ()" >
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline-dark" @click="cerrarSrv()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
      <button type="button" v-if="Tipo_Serv_Edo == 1" class="btn btn-secondary" @click="guardarServ(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
      <button type="button" v-if="Tipo_Serv_Edo == 2" class="btn btn-secondary" @click="guardarServ(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
    </div>

  </div>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
export default {
  data (){
    return{
      tipo : 0,
      Tipo_Manto_Edo : 1,
      Tipo_Serv_Edo : 1,
      operacion_array : [],
      text_manto : 'Nuevo registro de mantenimiento',
      text_serv : 'Nuevo registro de servicio',
      id : '',
      catalogos : [],
      servicios_totales : [],
      mantenimiento : {
        operacion : '0',
        tipo_operacion : '',
        fecha_servicio : '',
        kilometraje : '',
        proveedor : '',
        responsable : '',
        total : '',
        descripcion : '',
        comprobante : '',
        fecha_entrega : '',
        fecha_prox_rev : '',
        kilometraje_revision : '',
        tipo : '',
        tipo_servicios : [],
      },
      servicio : {
        fecha_servicio : '',
        fecha_entrega : '',
        kilometraje : '',
        proveedor : '',
        responsable : '',
        total :'',
        descripcion : '',
        comprobante : '',
        operacion : '0',
        tipo: '',
        tipo_servicios : [],
      },
      datamto: [],
      columnsmto: [],
      optionsmto: {
          headings: {

          },
          perPage: 5,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: [ ],
          filterable: [ ],
          filterByColumn: true,
          listColumns: { },
          texts:config.texts
      },
      dataserv: [],
      columnsserv: [],
      optionsserv: {
          headings: {

          },
          perPage: 5,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: [ ],
          filterable: [ ],
          filterByColumn: true,
          listColumns: { },
          texts:config.texts
      },
    }
  },
  components : {
    'mantenimiento' : Mantenimiento,
  },
  methods :{
    formMantenimiento(e){
      this.tipo = 1;
      Utilerias.resetObject(this.servicio);
    },
    formEventual(e){
      this.tipo = 2;
      Utilerias.resetObject(this.mantenimiento);
    },

    cambiar(){
      var array = [];
      if (this.mantenimiento.operacion == 0 || this.servicio.operacion == 0) {
      array = [];
      }

      if (this.mantenimiento.operacion == 1 || this.servicio.operacion == 1) {
        this.catalogos.forEach((item, i) => {
          if(item.operacion_id == 1){
            array.push(
              {id : item.id, name : item.nombre}
            );
          }
        });
      }

      if (this.mantenimiento.operacion == 2 || this.servicio.operacion == 2) {
        this.catalogos.forEach((item, i) => {
          if(item.operacion_id == 2){
            array.push(
              {id : item.id, name : item.nombre}
            );
          }
        });
      }
      if (this.mantenimiento.operacion == 3 || this.servicio.operacion == 3) {
        this.catalogos.forEach((item, i) => {
          if(item.operacion_id == 3){
            array.push(
              {id : item.id, name : item.nombre}
            );
          }
        });
      }
      if (this.mantenimiento.operacion == 4 || this.servicio.operacion == 4) {
        this.catalogos.forEach((item, i) => {
          if(item.operacion_id == 4){
            array.push(
              {id : item.id, name : item.nombre}
            );
          }
        });
      }
      this.operacion_array = [];
      this.operacion_array = array;
    },

    getId(id){
      this.id = id;
    },

    setCatalogo(catalogos){
      this.catalogos = catalogos;
    },

    confirmar(){
      // if(this.mantenimiento.tipo_servicios.length == 0 || this.mantenimiento.tipo_servicios == null){
      //   this.mantenimiento.tipo_servicios.push({
      //       id : this.mantenimiento.tipo.id, name :  this.mantenimiento.tipo.name
      //   });
      // }else {
      // this.mantenimiento.tipo_servicios.forEach((item, i) => {
      //     if(item.id != this.mantenimiento.tipo.id){
      //       this.mantenimiento.tipo_servicios.push({
      //         id : this.mantenimiento.tipo.id, name :  this.mantenimiento.tipo.name
      //       });
      //     }else {
      //       toastr.warning('ATENCIÓN','Registro ya agregado');
      //     }
      //   });
      // }

        if (this.tipo == 1) {
          this.mantenimiento.tipo_servicios.push({
              id : this.mantenimiento.tipo.id, name :  this.mantenimiento.tipo.name
          });
        }
        if (this.tipo == 2) {
          this.servicio.tipo_servicios.push({
            id : this.servicio.tipo.id, name :  this.servicio.tipo.name
          });
        }
    },

    guardarMto(nuevo){
      this.$validator.validate().then(result => {
        if(result){
          // this.isLoading = true;
          // let me = this;

        }
      });
    }
  },

  mounted () {

  }
}
</script>
