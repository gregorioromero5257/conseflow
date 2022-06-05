<template >

  <div class="card-body">
    <vue-element-loading :active="isLoading"/>

    <i class="fa fa-align-justify"> </i>&nbsp;Factor de Integración
    <button v-show="PermisosCrud.Create" type="button" @click="nuevo()" class="btn btn-dark float-sm-right" v-if="!nuevo_registro">
      <i class="fas fa-plus"></i>&nbsp;Nuevo
    </button>
    <hr>
    <table class="VueTables__table table table-hover table-sm" v-show="!nuevo_registro" >
      <thead>
        <tr>
          <th scope="col">&nbsp;Acciones</th>
          <th scope="col">&nbsp;Años de servicio</th>
          <th scope="col">&nbsp;Días de aguinaldo</th>
          <th scope="col">&nbsp;Dias de vacaciones</th>
          <th scope="col">&nbsp;Prima vacacional</th>
          <th scope="col">&nbsp;Factor de integración</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in factorintegracion" :value="item.id" :key="item.id">
          <td>&nbsp;
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
             <div class="btn-group dropup" role="group">
               <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <i class="fas fa-grip-horizontal"></i> Acciones
               </button>
               <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
               <button v-show="PermisosCrud.Update" type="button"  @click="actualizar(item)"
               class="dropdown-item" >
                 <i class="icon-pencil"></i>&nbsp;Actualizar
               </button>
               </div>
             </div>
            </div>
          </td>
          <td>&nbsp;{{item.anio_servicio}}</td>
          <td>&nbsp;{{item.dias_aguinaldo}}</td>
          <td>&nbsp;{{item.dias_vacaciones}}</td>
          <td>&nbsp;{{item.prima_vacacional}}</td>
          <td>&nbsp;{{item.factor_integracion}}</td>
        </tr>
      </tbody>
    </table>

    <div v-show="nuevo_registro">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label> Años de servicio</label>
           <input type="number" :maxlength="2" class="form-control" v-validate="'required'" v-model="FactorIntegracion.anio_servicio"
            data-vv-name="años de servicio" placeholder="años de servicio">
           <span class="text-danger">{{ errors.first('años de servicio') }}</span>
        </div>
        <div class="form-group col-md-6">
          <label> Días de aguinaldo</label>
          <input type="number" class="form-control" disabled :maxlength="2" v-validate="'required'" v-model="FactorIntegracion.dias_aguinaldo"
          data-vv-name="días de aguinaldo" placeholder="días de aguinaldo">
          <span class="text-danger">{{ errors.first('días de aguinaldo') }}</span>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label> Dias de vacaciones</label>
           <input type="number" class="form-control" maxlength="2" v-validate="'required'" v-model="FactorIntegracion.dias_vacaciones"
            data-vv-name="días de vacaciones" placeholder="días de vacaciones">
           <span class="text-danger">{{ errors.first('días de vacaciones') }}</span>
        </div>
        <div class="form-group col-md-6">
          <label> Prima vacacional</label>
          <input type="text" class="form-control" disabled v-validate="'required|decimal:2'" v-model="FactorIntegracion.prima_vacacional"
          data-vv-name="prima vacacional" placeholder="prima vacacional">
          <span class="text-danger">{{ errors.first('prima vacacional') }}</span>
        </div>
      </div>
      <div v-show="ver">
        {{factor_integra}}
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label> Factor de integración</label>
           <input type="number" class="form-control" v-model="FactorIntegracion.factor_integracion"
            data-vv-name="factor integración" placeholder="factor integración" >
           <span class="text-danger">{{ errors.first('factor integración') }}</span>
        </div>
      </div>
      <div class="form-row">
        <button type="button" class="btn btn-outline-dark" @click="cerrar()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>&nbsp;
        <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
        <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
      </div>
    </div>
  </div>

</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return {
      PermisosCrud :{},
      nuevo_registro : false,
      isLoading : false,
      ver : false,
      factorintegracion : null,
      tipoAccion : 0,
      FactorIntegracion :{
        id : 0,
        anio_servicio : '',
        dias_aguinaldo : 15,
        dias_vacaciones : '',
        prima_vacacional : 25,
        factor_integracion : null,
      },
    }
  },
  components : {

  },
  computed : {
    factor_integra(){
      var prima_vacacional_porcentaje = (this.FactorIntegracion.prima_vacacional / 100);
      var prima_vacacional = (this.FactorIntegracion.dias_vacaciones * prima_vacacional_porcentaje) / 365;
      var dias_aguinaldo = (this.FactorIntegracion.dias_aguinaldo / 365);
      return this.FactorIntegracion.factor_integracion =(1 + prima_vacacional + dias_aguinaldo).toFixed(4);
    }
  },
  methods : {
    getTarifas(){
      let me = this;
      this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
        axios.get('/factorintegracion').then(response => {
          me.factorintegracion = response.data;
        }).catch(function (error){
          console.error(error);
        });
    },

    nuevo(){
      this.nuevo_registro = true;
      this.tipoAccion = 1;
    },

    actualizar(data = []){
      this.nuevo_registro = true;
      this.tipoAccion = 2;
      this.FactorIntegracion.id = data.id,
      this.FactorIntegracion.anio_servicio = data.anio_servicio;
      this.FactorIntegracion.dias_aguinaldo = data.dias_aguinaldo;
      this.FactorIntegracion.dias_vacaciones = data.dias_vacaciones;
      this.FactorIntegracion.prima_vacacional = data.prima_vacacional;
      this.FactorIntegracion.factor_integracion = data.factor_integracion;
    },

    cerrar(){
      this.nuevo_registro = false;
      this.FactorIntegracion.anio_servicio = '';
      this.FactorIntegracion.dias_vacaciones = '';
      this.FactorIntegracion.factor_integracion = '';

    },

    guardar(tipo){
      this.$validator.validate().then(result => {
        if(result){
          this.isLoading = true;
          let me = this;
          axios({
            method : tipo ? 'POST' : 'PUT',
            url : tipo ? '/factorintegracion' : '/factorintegracion/' + this.FactorIntegracion.id,
            data : {
              'anio_servicio' : this.FactorIntegracion.anio_servicio,
              'dias_aguinaldo' : this.FactorIntegracion.dias_aguinaldo,
              'dias_vacaciones' : this.FactorIntegracion.dias_vacaciones,
              'prima_vacacional' : this.FactorIntegracion.prima_vacacional,
              'factor_integracion' : this.FactorIntegracion.factor_integracion,

            }
          }).then(function (response) {
              me.getTarifas();
              me.cerrar();
              me.isLoading= false;
              toastr.success('Correcto',tipo == 1 ? 'Agregado Correctamente' : 'Actualizado Correctamente');
          }).catch(function (error){
            console.error(error);
          });
        }
      });
    },
  },
  mounted(){
    this.getTarifas();
  }
}
</script>
