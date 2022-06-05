<template >

  <div class="card-body">
    <vue-element-loading :active="isLoading"/>

    <i class="fa fa-align-justify"> </i>&nbsp;Imss
    <button v-show="PermisosCrud.Create" type="button" @click="nuevo()" class="btn btn-dark float-sm-right" v-if="!nuevo_registro">
      <i class="fas fa-plus"></i>&nbsp;Nuevo
    </button>
    <hr>
    <table class="VueTables__table table table-hover table-sm" v-show="!nuevo_registro" >
      <thead>
        <tr>
          <th scope="col">&nbsp;Acciones</th>
          <th scope="col">&nbsp;Ramo</th>
          <th scope="col">&nbsp;Prestación</th>
          <th scope="col">&nbsp;Base cálculo</th>
          <th scope="col">&nbsp;Porcentaje paga el trabajador</th>
          <th scope="col">&nbsp;Porcentaje pago el patrón</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in imss" :value="item.id" :key="item.id">
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
          <td>&nbsp;{{item.ramo}}</td>
          <td>&nbsp;{{item.prestacion}}</td>
          <td>&nbsp;{{item.base_calculo}}</td>
          <td>&nbsp;{{item.porcentaje_trabajador}}</td>
          <td>&nbsp;{{item.porcentaje_patron}}</td>
        </tr>
      </tbody>
    </table>

    <div v-show="nuevo_registro">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label> Ramo</label>
           <input type="text" class="form-control" v-validate="'required'" v-model="imss.ramo"
            data-vv-name="ramo" placeholder="ramo">
           <span class="text-danger">{{ errors.first('ramo') }}</span>
        </div>
        <div class="form-group col-md-6">
          <label> Prestación</label>
          <input type="text" class="form-control" v-validate="'required'" v-model="imss.prestacion"
          data-vv-name="prestacion" placeholder="prestacion">
          <span class="text-danger">{{ errors.first('prestacion') }}</span>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-8">
          <label> Base cálculo</label>
           <input type="text" class="form-control" v-validate="'required'" v-model="imss.base_calculo"
            data-vv-name="base calculo" placeholder="base calculo">
           <span class="text-danger">{{ errors.first('base calculo') }}</span>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label> Porcentaje paga trabajador</label>
          <input type="text" class="form-control" v-validate="'required|decimal:10'" v-model="imss.porcentaje_trabajador"
          data-vv-name="porcentaje trabajador" placeholder="porcentaje trabajador">
          <span class="text-danger">{{ errors.first('porcentaje trabajador') }}</span>
        </div>
        <div class="form-group col-md-6">
          <label> Porcentaje paga patrón</label>
           <input type="text" class="form-control" v-validate="'required|decimal:10'" v-model="imss.porcentaje_patron"
            data-vv-name="porcentaje patron" placeholder="porcentaje patron">
           <span class="text-danger">{{ errors.first('porcentaje patron') }}</span>
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
      imss : null,
      tipoAccion : 0,
      imss :{
        id : 0,
        ramo : '',
        prestacion : '',
        base_calculo : '',
        porcentaje_patron : '',
        porcentaje_trabajador : '',
      },
    }
  },
  components : {

  },
  methods : {
    getData(){
      let me = this;
      this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
        axios.get('/imss').then(response => {
          me.imss = response.data;
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
      this.imss.id = data.id,
      this.imss.ramo = data.ramo;
      this.imss.prestacion = data.prestacion;
      this.imss.base_calculo = data.base_calculo;
      this.imss.porcentaje_patron = data.porcentaje_patron;
      this.imss.porcentaje_trabajador = data.porcentaje_trabajador;
    },

    cerrar(){
      this.nuevo_registro = false;
      this.imss.ramo = '';
      this.imss.prestacion = '';
      this.imss.base_calculo = '';
      this.imss.porcentaje_trabajador = '';
      this.imss.porcentaje_patron = '';
    },

    guardar(tipo){
      this.$validator.validate().then(result => {
        if(result){
          this.isLoading = true;
          let me = this;
          axios({
            method : tipo ? 'POST' : 'PUT',
            url : tipo ? '/imss' : '/imss/' + this.imss.id,
            data : {
              'ramo' : this.imss.ramo,
              'prestacion' : this.imss.prestacion,
              'base_calculo' : this.imss.base_calculo,
              'porcentaje_trabajador' : this.imss.porcentaje_trabajador,
              'porcentaje_patron' : this.imss.porcentaje_patron,
            }
          }).then(function (response) {
              me.getData();
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
    this.getData();
  }
}
</script>
