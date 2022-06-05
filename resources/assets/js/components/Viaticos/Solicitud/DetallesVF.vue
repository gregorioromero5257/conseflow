<template >
  <div>
    <!-- {{tipo}}sss -->
    <template v-if="tipo > 0">

    <div class="table-responsive">
      <table class="table">
        <tr>
          <th scope="col">Concepto</th>
          <th scope="col">T.E (Transferencia Electronica)</th>
          <th scope="col">Efectivo</th>
          <th scope="col">Total</th>
        </tr>

        <tr v-for="item in listaConceptos">
          <td scope="row"><label>{{item.nombre}}</label></td>
          <td>&nbsp;<input class="form-control col-md-6" type="number" step=".01" v-model.number="listado.tranferencia[item.id - 1]" @input="enviar" /></td>
          <td>&nbsp;<input class="form-control col-md-6" type="number" step=".01" v-model.number="listado.efectivo[item.id - 1]" @input="enviar"/></td>
          <td>&nbsp;{{ (parseFloat(listado.tranferencia[item.id - 1]) + parseFloat(listado.efectivo[item.id -1 ])).toFixed(2) }}</td>
        </tr>
        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
          <td><b>Total</b></td>
          <td><b>{{totalTransferencia.toFixed(2)}}</b></td>
          <td><b>{{totalEfectivo.toFixed(2)}}</b></td>
          <td><b>{{totalGeneral.toFixed(2)}}</b></td>
        </tr>
      </table>

    </div>
  </template>
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
    <li v-for="(vi, index) in listado.conceptos" class="list-group-item">
      <div class="form-row">

        <div class="form-group col-md-4">
          <label>{{listado.conceptos[index]}}</label>
        </div>
        <div class="form-group col-md-2">
          <label>{{listado.tranferencia[index]}}</label>
        </div>
        <div class="form-group col-md-2">
          <label>{{listado.efectivo[index]}}</label>
        </div>
        <div class="form-group col-md-2">
          <label>{{parseFloat(listado.tranferencia[index]) + parseFloat(listado.efectivo[index])}}</label>
        </div>
        <div class="form-group col-md-2">
          <a @click="deleteu(index)">
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
        <input type='number'  v-model="listado_temporal.tranferencia" class='form-control' placeholder='Tranferencia'>
      </div>
      <div class='form-group col-md-2'>
        <input type='number' v-model="listado_temporal.efectivo" class='form-control' placeholder='Efectivo'>
      </div>
      <div class='form-group col-md-2'>
        &nbsp;
        <!-- <input type='number' v-model="listado_temporal.efectivo" class='form-control' placeholder='KM inicial'> -->
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
      listaConceptos : [],
      listado : {
        tranferencia : [],
        efectivo : [],
        conceptos : [],
      },
      listado_temporal : {
        tranferencia : '',
        efectivo : '',
        conceptos : '',
      },
      totales : {
        totaltranferencia : '',
        totalefectivo : '',
        totalgeneral : '',

      }
    }
  },
  computed : {
    totalTransferencia() {
      return this.listado.tranferencia.reduce((sum, item) => {
        return this.totales.totaltranferencia =  (sum + parseFloat(item))
      }, 0)
    },
    totalEfectivo(){
      return this.listado.efectivo.reduce((sum, item) => {
        return this.totales.totalefectivo = (sum + parseFloat(item))
      }, 0)
    },
    totalGeneral() {
      return this.totales.totalgeneral = parseFloat(this.totales.totaltranferencia) + parseFloat(this.totales.totalefectivo)
    }
  },
  methods :{
    getDatos(data){
      this.tipo = data;
      this.listado.tranferencia = [];
      this.listado.efectivo = [];
      this.listado.conceptos = [];
      this.listaConceptos = [];
      if (data > 0) {
        this.getData();
      }
    },

    getData(){
      axios.get('conceptosviaticos').then(response => {
        for (var i = 0; i < response.data.length; i++) {
          this.listado.tranferencia.push(0);
          this.listado.efectivo.push(0);
          this.listado.conceptos.push(response.data[i].id);
        }
        this.listaConceptos = response.data;
      }).catch(error => {
        console.log(error);
      });
    },

    enviar(){
      this.$emit('listado', this.listado);
      this.$emit('totales', this.totales);
      this.$emit('conceptos', this.conceptos);
    },

    setDatos(data,tipo){
      var transferencia = [];
      var efectivo = [];
      var conceptos = [];
      this.tipo = tipo;
      for (var i = 0; i < data.length; i++) {
        transferencia.push(data[i]['transferencia_electronica']);
        efectivo.push(data[i]['efectivo']);
        if (tipo == 0) {
          conceptos.push(data[i]['catalogo_concepto_viaticos']);
        }else {
          conceptos.push(i + 1);
        }
      }

      this.listado.tranferencia = transferencia;
      this.listado.efectivo = efectivo;
      this.listado.conceptos = conceptos;

      let me = this;
      me.enviar();
    },

    crear(){
      this.listado.tranferencia.push(this.listado_temporal.tranferencia);
      this.listado.efectivo.push(this.listado_temporal.efectivo);
      this.listado.conceptos.push(this.listado_temporal.conceptos);

        this.listado_temporal.tranferencia = '';
        this.listado_temporal.efectivo = '';
        this.listado_temporal.conceptos = '';

        let me = this;
        me.enviar();

    },

    deleteu(index){
      this.listado.tranferencia.splice(index, 1);
      this.listado.efectivo.splice(index, 1);
      this.listado.conceptos.splice(index, 1);

    }

  },
  mounted(){
    this.getData();
  }
}
</script>
