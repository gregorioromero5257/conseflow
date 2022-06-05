<template >

  <div class="card-body">
    <vue-element-loading :active="isLoading"/>

    <i class="fa fa-align-justify"> </i>&nbsp;Tarifas mensuales
    <button v-show="PermisosCrud.Create" type="button" @click="nuevo()" class="btn btn-dark float-sm-right" v-if="!nuevo_registro">
      <i class="fas fa-plus"></i>&nbsp;Nuevo
    </button>
    <hr>
    <table class="VueTables__table table table-hover table-sm" v-show="!nuevo_registro" >
      <thead>
        <tr>
          <th scope="col">&nbsp;Acciones</th>
          <th scope="col">&nbsp;Límite Inferior</th>
          <th scope="col">&nbsp;Límite Superior</th>
          <th scope="col">&nbsp;Couta Fija</th>
          <th scope="col">&nbsp;Porcentaje</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in tarifas" :value="item.id" :key="item.id">
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
          <td>$&nbsp;{{item.limite_inferior}}</td>
          <td>$&nbsp;{{item.limite_superior}}</td>
          <td>$&nbsp;{{item.cuota_fija}}</td>
          <td>%&nbsp;{{item.porcentaje}}</td>
        </tr>
      </tbody>
    </table>

    <div v-show="nuevo_registro">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label> Limite Inferior</label>
           <input type="text" class="form-control" v-validate="'required|decimal:2'" v-model="tarifasM.limite_inferior"
            data-vv-name="límite inferior" placeholder="límite inferior">
           <span class="text-danger">{{ errors.first('límite inferior') }}</span>
        </div>
        <div class="form-group col-md-6">
          <label> Limite Superior</label>
          <input type="text" class="form-control" v-validate="'required|decimal:2'" v-model="tarifasM.limite_superior"
          data-vv-name="límite superior" placeholder="límite superior">
          <span class="text-danger">{{ errors.first('límite superior') }}</span>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label> Couta Fija</label>
           <input type="text" class="form-control" v-validate="'required|decimal:2'" v-model="tarifasM.cuota_fija"
            data-vv-name="cuota fija" placeholder="cuota fija">
           <span class="text-danger">{{ errors.first('cuota fija') }}</span>
        </div>
        <div class="form-group col-md-6">
          <label> Porcentaje</label>
          <input type="text" class="form-control" v-validate="'required|decimal:2'" v-model="tarifasM.porcentaje"
          data-vv-name="porcentaje" placeholder="porcentaje">
          <span class="text-danger">{{ errors.first('porcentaje') }}</span>
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
      PermisosCrud : {},
      nuevo_registro : false,
      isLoading : false,
      tarifas : null,
      tipoAccion : 0,
      tarifasM :{
        id : 0,
        limite_inferior : '',
        limite_superior : '',
        cuota_fija : '',
        porcentaje : '',
      },
    }
  },
  components : {

  },
  methods : {
    getTarifas(){
      let me = this;
      this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
        axios.get('/tarifamensual').then(response => {
          me.tarifas = response.data;
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
      this.tarifasM.id = data.id,
      this.tarifasM.limite_inferior = data.limite_inferior;
      this.tarifasM.limite_superior = data.limite_superior;
      this.tarifasM.cuota_fija = data.cuota_fija;
      this.tarifasM.porcentaje = data.porcentaje;
    },

    cerrar(){
      this.nuevo_registro = false;
      this.tarifasM.limite_inferior = '';
      this.tarifasM.limite_superior = '';
      this.tarifasM.cuota_fija = '';
      this.tarifasM.porcentaje = '';
    },

    guardar(tipo){
      this.$validator.validate().then(result => {
        if(result){
          this.isLoading = true;
          let me = this;
          axios({
            method : tipo ? 'POST' : 'PUT',
            url : tipo ? '/tarifamensual' : '/tarifamensual/' + this.tarifasM.id,
            data : {
              'limite_inferior' : this.tarifasM.limite_inferior,
              'limite_superior' : this.tarifasM.limite_superior,
              'cuota_fija' : this.tarifasM.cuota_fija,
              'porcentaje' : this.tarifasM.porcentaje,
            }
          }).then(function (response) {
              me.getTarifas();
              me.cerrar();
              me.isLoading= false;
              toastr.success('Correcto',tipo == 1 ? 'Tarifa Agregada Correctamente' : 'Tarifa Actualizada Correctamente');
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
