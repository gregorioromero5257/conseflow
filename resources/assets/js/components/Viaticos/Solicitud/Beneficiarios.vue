<template>
  <div>
    <template v-if="tipo_viatico != 0">
    <div class="form-row">
      <div class="form-group col-md-6">
        <label>&nbsp;Beneficiario</label>
        <v-select :options="empleados" v-model="solicitud.beneficiario" label="name" name="proyecto"
        data-vv-name="proyecto" @input="buscar_uno" ></v-select>
      </div>
      <div class="form-group col-md-6">
        <label>&nbsp;Banco beneficiario</label>
        <select class="form-control" name="datos bancarios" v-model="solicitud.datos_bancos_beneficiario"  @change="envioDatosBeneficiario($event)" data-vv-name="Proyecto" >
          <option v-for="item in listaDatosBancariosuno" :value="item.id" :key="item.id">{{ item.bnombre }}</option>
          <option value="0">OTRO</option>
        </select>
        <span class="text-danger">{{ errors.first('datos bancarios') }}</span>
      </div>
    </div>
    </template>
    <template v-if="tipo_viatico == 0">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label>&nbsp;Beneficiario</label>
          <input type="text" class="form-control" v-model="solicitud.beneficiario">
        </div>
        <div class="form-group col-md-6">
          <label>&nbsp;Banco</label>
          <input type="text" class="form-control" v-model="solicitud.datos_bancos_beneficiario">
        </div>
      </div>
    </template>
    <!-- {{solicitud}} -->
    <div class="form-row">
      <div class="form-group col-md-6 ">
        <label>CUENTA</label>
        <input type="text" v-model="solicitud.cuentauno" class="form-control" >
      </div>
      <div class="form-group col-md-6 ">
        <label>CLABE</label>
        <input type="text" v-model="solicitud.claveuno" class="form-control" >
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6 ">
        <label>TARJETA</label>
        <input type="text" v-model="solicitud.clavecuentatarjetauno" class="form-control" >
      </div>
      <template v-if="solicitud.datos_bancos_beneficiario === '0' && tipo_viatico > 0">
        <div class="form-group col-md-6 ">
          <label>BANCO</label>
          <input type="text" v-model="solicitud.banco" class="form-control" >
        </div>
      </template>
      <div class="form-group col-md-6">
        <button type="button" class="btn btn-secondary" @click="quitar_uno()"><i class="fas fa-trash"></i>&nbsp;Limpiar</button>
      </div>
    </div>
    <hr>


  </div>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return {
      empresa : '',
      tipo_viatico : '',
      nuevo : null,
      solicitud : {
        beneficiario : '',
        datos_bancos_beneficiario : '',
        clavecuentatarjetauno : '',
        claveuno : '',
        cuentauno : '',
        banco : '',
      },
      empleados : [],
      listaDatosBancariosuno : [],

    }
  },
  watch: {
  'solicitud' : {
      handler : function (after,before){
      var a = [];
      a.push({
       id : after.beneficiario,
       dbemp_id : after.datos_bancos_beneficiario,
       tarjeta : after.clavecuentatarjetauno,
       clave : after.claveuno,
       cuenta : after.cuentauno,
       banco : after.banco,
      });
      this.$emit('envioDatosBeneficiario', a);
      },
      deep :  true
  }
},

  methods : {


    getDatos(empresa, tipo_viatico, empleados){
      this.quitar_uno();
      this.solicitud.beneficiario = '';
      this.empleados = [];
      this.empresa = empresa;
      this.tipo_viatico = tipo_viatico;
      this.empleados = empleados;
      Utilerias.resetObject(this.solicitud);
    },

    buscar_uno(){
      let me = this;
      if (me.solicitud.beneficiario != '') {
        me.listaDatosBancariosuno = [];

      axios.get('/datosbanemp/' + me.solicitud.beneficiario.id + '&' + me.empresa + '/datosbanemp').then(response => {
        me.listaDatosBancariosuno = response.data;
      }).catch(error => {
        console.error(error);
      });
    }
    },

    /**
    ** FUNCION QUE ENVIA LOS DATOS CAPTURADOS EN EL FORMULARIO AL COMPONETE PADRE Solicitud.vue
    ** EVALUA QUE NO SE ENVIEN DATOS VACIOS PARA ELIMINAR EL ERROR DE ENVIO
    **/
    envioDatosBeneficiario(e = null){
      this.limpiaInputs();
      var target = e == null ? 0 : e.target.selectedIndex;
      //EXISTE UN REGISTRO VALIDO EN EL SELECT DE EMPLEADOS
      if (e.target.value != 0) {
          this.solicitud.datos_bancos_beneficiario = this.listaDatosBancariosuno[e.target.selectedIndex]['id'],
          this.solicitud.clavecuentatarjetauno = this.listaDatosBancariosuno[e.target.selectedIndex]['numero_tarjeta'],
          this.solicitud.claveuno = this.listaDatosBancariosuno[e.target.selectedIndex]['clabe'],
          this.solicitud.cuentauno = this.listaDatosBancariosuno[e.target.selectedIndex]['numero_cuenta'],
          this.solicitud.banco = this.listaDatosBancariosuno[e.target.selectedIndex]['bnombre'];
      }
      var a = [];
      a.push({
        id : this.solicitud.beneficiario,
        dbemp_id : this.solicitud.datos_bancos_beneficiario,
        tarjeta : this.solicitud.clavecuentatarjetauno,
        clave : this.solicitud.claveuno,
        cuenta : this.solicitud.cuentauno,
        banco : this.solicitud.banco,
      });
      this.$emit('envioDatosBeneficiario', a);
    },


    setDatos(id, tipo, dato){
      let me = this;
      this.nuevo = id;

      this.tipo_viatico = tipo;
      this.empresa = dato;
      axios.get('get/beneficiario/viatico/' + id).then(response => {

        if (response.data.empleado_beneficiario_id == 0) {
          this.solicitud.beneficiario  = response.data.beneficiario_externo;
          this.solicitud.datos_bancos_beneficiario = response.data.banco_nombre;
        }else {
          this.solicitud.beneficiario  = {id: response.data.empleado_beneficiario_id, name: response.data.nombre_beneficiario};
          this.solicitud.datos_bancos_beneficiario = response.data.datos_bancarios_empleado_id;
        }
        this.solicitud.clavecuentatarjetauno = response.data.tarjeta;
        this.solicitud.claveuno = response.data.clabe;
        this.solicitud.cuentauno = response.data.cuenta;
        this.solicitud.banco = response.data.banco_nombre;
      }).catch(e => {
        console.error(e);
      });

    },

    quitar_uno(){
      this.solicitud.beneficiario = '';
      this.solicitud.datos_bancos_beneficiario = '';
      this.solicitud.clavecuentatarjetauno = '';
      this.solicitud.claveuno = '';
      this.solicitud.cuentauno = '';
      this.solicitud.banco = '';
      this.listaDatosBancariosuno = [];
      this.$emit('limpiarUno');
    },

    limpiaInputs(){
      this.solicitud.clavecuentatarjetauno = '';
      this.solicitud.claveuno = '';
      this.solicitud.cuentauno = '';
      this.solicitud.banco = '';
    },

  },
  mounted (){
  }
}
</script>
