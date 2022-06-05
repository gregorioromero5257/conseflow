<template>
<div >
  <!-- <template v-if="tipo == 1"> -->

    <div class="form-row">
      <div class="form-group col-md-4">
        <label>Operacion</label>
        <select class="form-control" name="operacion" data-vv-name="Operacion" v-model="mantenimiento.operacion" @change="cambiar()">
          <option :value="0" selected>--Seleccione--</option>
          <option :value="1">Verificación de niveles</option>
          <option :value="2">Sustituciones</option>
          <option :value="3">Comprobación y ajuste de componentes</option>
          <option :value="4">Refacciones</option>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label>Tipo</label>
        <v-select :options="operacion_array" v-model="mantenimiento.tipo" label="name"></v-select>


      </div>
      <div class="form-group col-md-2">
        <label>&nbsp;</label>
        <button type="button" class="form-control" name="button" @click="confirmar()">Confirmar</button>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-12">
        <label>Servicios</label>
        <v-select multiple :options="servicios_totales"  v-validate="'required'" data-vv-name="Servicios" label="name" v-model="mantenimiento.tipo_servicios"></v-select>
        <span class="text-danger">{{ errors.first('Servicios') }}</span>
      </div>
    </div>
    <hr>
    <div class="form-row">
      <div class="form-group col-md-5">
        <label>Proveedor</label>
        <input type="text" class="form-control"  v-validate="'required'" data-vv-name="Proveedor" name="proveedor" v-model="mantenimiento.proveedor">
        <span class="text-danger">{{ errors.first('Proveedor') }}</span>
      </div>
      <div class="form-group col-md-4">
        <label>Responsable</label>
        <input type="text" class="form-control"  v-validate="'required'" data-vv-name="Responsable" name="responsable" v-model="mantenimiento.responsable">
        <span class="text-danger">{{ errors.first('Responsable') }}</span>
      </div>
      <div class="form-group col-md-3">
        <label>Costo total</label>
        <input type="text" class="form-control"  v-validate="'required|decimal:2'" data-vv-name="total" name="total" v-model="mantenimiento.total">
        <span class="text-danger">{{ errors.first('total') }}</span>
      </div>
    </div>
    <div class="form-group col-md-4">
      <label>Fecha</label>
      <input type="date" class="form-control"  v-validate="'required'" data-vv-name="Fecha" name="fecha_servicio" v-model="mantenimiento.fecha_servicio">
      <span class="text-danger">{{ errors.first('Fecha') }}</span>
    </div>
    <hr>
    <div class="form-row">
      <div class="form-group col-md-2">
        <label>Kilometraje</label>
        <input type="number" class="form-control"  v-validate="'required'" data-vv-name="Kilometraje" name="kilometraje" v-model="mantenimiento.kilometraje">
        <span class="text-danger">{{ errors.first('Kilometraje') }}</span>
      </div>
      <div class="form-group col-md-3">
        <label>Fecha entrega</label>
        <input type="date" class="form-control"  v-validate="'required'" data-vv-name="Fecha entrega" name="fecha_entrega" v-model="mantenimiento.fecha_entrega">
        <span class="text-danger">{{ errors.first('Fecha entrega') }}</span>
      </div>
      <div class="form-group col-md-3">
        <label>Fecha proxíma revisión</label>
        <input type="date" class="form-control"  v-validate="'required'" data-vv-name="Fecha proxíma revisión" name="fecha_prox_rev" v-model="mantenimiento.fecha_prox_rev">
        <span class="text-danger">{{ errors.first('Fecha proxíma revisión') }}</span>
      </div>
      <div class="form-group col-md-2">
        <label>Kilometraje revisión</label>
        <input type="number" class="form-control" v-validate="'required'" data-vv-name="Kilometraje revisión"  name="kilometraje_revision" v-model="mantenimiento.kilometraje_revision">
        <span class="text-danger">{{ errors.first('Kilometraje revisión') }}</span>
      </div>
    </div>
    <div class="form-row">

      <template v-if="mantenimiento.factura != ''">
        <div class="form-group col-md-2">
          <label>&nbsp;</label>
          <button type="button" class="form-control" @click="descargar(mantenimiento.factura)">
            <i class="fas fa-file-download"></i>&nbsp;Descargar
          </button>
        </div>
        <div class="form-group col-md-2">
          <label>&nbsp;</label>
          <button type="button" class="form-control" @click="actualizaMto()">
            <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
          </button>
        </div>
      </template>
      <template v-if="mantenimiento.factura == ''">
        <div class="form-group col-md-5">
          <label>Comprobante</label>
          <input type="file" class="form-control" name="comprobante"  @change="onChangeMto($event)">
        </div>
      </template>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-outline-dark" @click="cerrarMto()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
      <button type="button" v-if="Tipo_Manto_Edo == 1" class="btn btn-secondary" @click="guardarMto(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
      <button type="button" v-if="Tipo_Manto_Edo == 2" class="btn btn-secondary" @click="guardarMto(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
    </div>
  <!-- </template> -->
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
    }
  },
  methods :{
    cambiar(){
      var array = [];
      if (this.mantenimiento.operacion == 0) {
      array = [];
      }

      if (this.mantenimiento.operacion == 1) {
        this.catalogos.forEach((item, i) => {
          if(item.operacion_id == 1){
            array.push(
              {id : item.id, name : item.nombre}
            );
          }
        });
      }

      if (this.mantenimiento.operacion == 2) {
        this.catalogos.forEach((item, i) => {
          if(item.operacion_id == 2){
            array.push(
              {id : item.id, name : item.nombre}
            );
          }
        });
      }
      if (this.mantenimiento.operacion == 3) {
        this.catalogos.forEach((item, i) => {
          if(item.operacion_id == 3){
            array.push(
              {id : item.id, name : item.nombre}
            );
          }
        });
      }
      if (this.mantenimiento.operacion == 4) {
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

    getCatalogo(){
      alert('hete');
      // console.log(catalogos);
      // this.catalogos = catalogos;
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
          this.mantenimiento.tipo_servicios.push({
              id : this.mantenimiento.tipo.id, name :  this.mantenimiento.tipo.name
          });
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
