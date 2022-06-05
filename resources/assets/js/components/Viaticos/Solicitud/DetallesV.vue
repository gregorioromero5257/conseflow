<template >
  <div>
  <!-- SOLICITUD DE VIATICOS NORMAL O REEMBOLSO -->
  <template v-if="tipo > 0">
    <div class="form-row">

      <div class="form-group col-md-4">
        <label><b>Concepto</b></label>
      </div>
      <div class="form-group col-md-2">
        <label><b>T.E (Transferencia Electronica)</b></label>
      </div>

      <div class="form-group col-md-2">
        <label><b>Efectivo</b></label>
      </div>
      <div class="form-group col-md-2">
        <label><b>Total</b></label>
      </div>
      <div class="form-group col-md-2">
        <label><b>ACCIONES</b></label>
      </div>
    </div>
    <li v-for="(vi, index) in listado_conceptos" class="list-group-item">
      <div class="form-row">

        <div class="form-group col-md-4">
          <label>{{vi.concepto}}</label>
        </div>
        <div class="form-group col-md-2">
          <label>{{vi.transferencia}}</label>
        </div>
        <div class="form-group col-md-2">
          <label>{{vi.efectivo}}</label>
        </div>
        <div class="form-group col-md-2">
          <label>{{parseFloat(vi.transferencia) + parseFloat(vi.efectivo)}}</label>
        </div>
        <div class="form-group col-md-2">
          <a @click="deleteu(index, vi)">
            <span class="fas fa-trash" arial-hidden="true"></span>
          </a>
        </div>
      </div>
    </li>

    <div class='form-row'>
      <div class='form-group col-md-4'>
        <v-select :options="catalogo_conceptos"  v-model="listado_temporal.conceptos" label="nombre" ></v-select>
      </div>
      <div class='form-group col-md-2'>
        <input type='number'  v-model="listado_temporal.transferencia" class='form-control' placeholder='Tranferencia'>
      </div>
      <div class='form-group col-md-2'>
        <input type='number' v-model="listado_temporal.efectivo" class='form-control' placeholder='Efectivo'>
      </div>
      <div class='form-group col-md-2'>
        &nbsp;
      </div>
      <div class="form-group col-md-2">
        <button type="button" class="btn btn-secondary" @click="crearCatalogo()"><i class="fas fa-plus"></i>&nbsp;Crear</button>
      </div>
    </div>


  </template>
  <!-- SOLICITUD DE VISATIVOS TIPO SINDICATO -->
  <template v-if="tipo == 0">
    <div class="form-row">

      <div class="form-group col-md-4">
        <label><b>Concepto</b></label>
      </div>
      <div class="form-group col-md-2">
        <label><b>T.E (Transferencia Electronica)</b></label>
      </div>

      <div class="form-group col-md-2">
        <label><b>Efectivo</b></label>
      </div>
      <div class="form-group col-md-2">
        <label><b>Total</b></label>
      </div>
      <div class="form-group col-md-2">
        <label><b>ACCIONES</b></label>
      </div>
    </div>
    <li v-for="(vi, index) in listado_conceptos" class="list-group-item">
      <div class="form-row">

        <div class="form-group col-md-4">
          <label>{{vi.concepto}}</label>
        </div>
        <div class="form-group col-md-2">
          <label>{{vi.transferencia}}</label>
        </div>
        <div class="form-group col-md-2">
          <label>{{vi.efectivo}}</label>
        </div>
        <div class="form-group col-md-2">
          <label>{{parseFloat(vi.transferencia) + parseFloat(vi.efectivo)}}</label>
        </div>
        <div class="form-group col-md-2">
          <a @click="deleteu(index, vi)">
            <span class="fas fa-trash" arial-hidden="true"></span>
          </a>
        </div>
      </div>
    </li>

    <div class='form-row'>
      <div class='form-group col-md-4'>
        <input type='text'  v-model="listado_temporal.conceptos" class='form-control' placeholder='Concepto'>
      </div>
      <div class='form-group col-md-2'>
        <input type='number'  v-model="listado_temporal.transferencia" class='form-control' placeholder='Tranferencia'>
      </div>
      <div class='form-group col-md-2'>
        <input type='number' v-model="listado_temporal.efectivo" class='form-control' placeholder='Efectivo'>
      </div>
      <div class='form-group col-md-2'>
        &nbsp;
      </div>
      <div class="form-group col-md-2">
        <button type="button" class="btn btn-secondary" @click="crear()"><i class="fas fa-plus"></i>&nbsp;Crear</button>
      </div>
    </div>

  </template>

  </div>

</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return {
      tipo : '',

      listado_conceptos :  [],
      catalogo_conceptos : [],

      listado_temporal : {
        transferencia : 0,
        efectivo : 0,
        conceptos : '',
      },

    }
  },
  methods :{
    getDatos(tipo){
      this.tipo = tipo;
      this.listado_conceptos = [];
    },

    getData(){
      axios.get('conceptosviaticos').then(response => {
      this.catalogo_conceptos = response.data;
      }).catch(error => {
        console.log(error);
      });
    },

    crear(){
      if (this.listado_temporal.conceptos === '' ) {
        toastr.warning('Agregue un concepto !!!');
      }else if (this.listado_temporal.transferencia == 0 && this.listado_temporal.efectivo == 0) {
        toastr.warning('No se pueden registrar 2 cantidades en 0 !!!');
      }else {
        this.listado_conceptos.push({
          id : 0,
          concepto : this.listado_temporal.conceptos,
          transferencia : this.listado_temporal.transferencia,
          efectivo : this.listado_temporal.efectivo,
          id_registro : 0,
        });

        this.listado_temporal.transferencia = 0;
        this.listado_temporal.efectivo = 0;
        this.listado_temporal.conceptos = '';
      let me = this;
      me.enviar();
      }
    },

    crearCatalogo(){
      if (this.listado_temporal.conceptos === '' ) {
        toastr.warning('Agregue un concepto !!!');
      }else if (this.listado_temporal.transferencia == 0 && this.listado_temporal.efectivo == 0) {
        toastr.warning('No se pueden registrar 2 cantidades en 0 !!!');
      }else {
        this.listado_conceptos.push({
          id : this.listado_temporal.conceptos.id,
          concepto : this.listado_temporal.conceptos.nombre,
          transferencia : this.listado_temporal.transferencia,
          efectivo : this.listado_temporal.efectivo,
          id_registro : 0,
        });

        this.listado_temporal.transferencia = 0;
        this.listado_temporal.efectivo = 0;
        this.listado_temporal.conceptos = '';
      let me = this;
      me.enviar();
      }
    },

    enviar(){
      this.$emit('listado', this.listado_conceptos);
    },

    deleteu(index, data){
      console.log(data);
      // this.listado_conceptos.splice(index, 1);
    },

    setDatos(id, tipo, empresa){
      this.listado_conceptos = [];
      this.tipo = tipo;

      axios.get('get/detalles/viaticos/' + id + '&' + empresa).then(response => {
        response.data.forEach((item, i) => {
          this.listado_conceptos.push({
            id : item.catalogo_conceptos_id,
            concepto : item.catalogo_conceptos_viaticos,
            transferencia : item.transferencia_electronica,
            efectivo : item.efectivo,
            id_registro : item.id,
          });
        });
      }).catch(e => {
        console.error(e);
      });
      let me = this;
      me.enviar();
    },


  },
  mounted(){
    this.getData();
  }
}
</script>
