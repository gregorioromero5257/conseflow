<template >

  <div class="card-body">
    <vue-element-loading :active="isLoading"/>

    <i class="fa fa-align-justify"> </i>&nbsp;Subsidio al empleo
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
        </tr>
      </tbody>
    </table>

    <div v-show="nuevo_registro">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label> Limite Inferior</label>
           <input type="text" class="form-control" v-validate="'required|decimal:2'" v-model="subsidioE.limite_inferior"
            data-vv-name="límite inferior" placeholder="límite inferior">
           <span class="text-danger">{{ errors.first('límite inferior') }}</span>
        </div>
        <div class="form-group col-md-6">
          <label> Limite Superior</label>
          <input type="text" class="form-control" v-validate="'required|decimal:2'" v-model="subsidioE.limite_superior"
          data-vv-name="límite superior" placeholder="límite superior">
          <span class="text-danger">{{ errors.first('límite superior') }}</span>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label> Couta Fija</label>
           <input type="text" class="form-control" v-validate="'required|decimal:2'" v-model="subsidioE.cuota_fija"
            data-vv-name="cuota fija" placeholder="cuota fija">
           <span class="text-danger">{{ errors.first('cuota fija') }}</span>
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
      subsidioE :{
        id : 0,
        limite_inferior : '',
        limite_superior : '',
        cuota_fija : '',
      },
    }
  },
  components : {

  },
  methods : {
    getTarifas(){
      let me = this;
      this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
        axios.get('/subsidioempleo').then(response => {
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
      this.subsidioE.id = data.id,
      this.subsidioE.limite_inferior = data.limite_inferior;
      this.subsidioE.limite_superior = data.limite_superior;
      this.subsidioE.cuota_fija = data.cuota_fija;
    },

    cerrar(){
      this.nuevo_registro = false;
      this.subsidioE.limite_inferior = '';
      this.subsidioE.limite_superior = '';
      this.subsidioE.cuota_fija = '';
    },

    guardar(tipo){
      this.$validator.validate().then(result => {
        if(result){
          this.isLoading = true;
          let me = this;
          axios({
            method : tipo ? 'POST' : 'PUT',
            url : tipo ? '/subsidioempleo' : '/subsidioempleo/' + this.subsidioE.id,
            data : {
              'limite_inferior' : this.subsidioE.limite_inferior,
              'limite_superior' : this.subsidioE.limite_superior,
              'cuota_fija' : this.subsidioE.cuota_fija,
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
