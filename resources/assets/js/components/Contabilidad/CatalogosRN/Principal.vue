<template>
<main class="main">
  <div class="container-fluid">
    <vue-element-loading :active="isLoading"/>

    <div class="accordion" id="accordion">

  <div class="card">
    <div class="card-header bg-dark" id="headingUno">
      <h5 class="mb-0">
        <button class="text-white btn btn-dark collapsed" @click="reset()" type="button" data-toggle="collapse" data-target="#collapseUno" aria-expanded="false" aria-controls="collapseUno">
          Factor de integración
        </button>
        <button class="text-white btn btn-dark collapsed" @click="reset()" type="button" data-toggle="collapse" data-target="#collapseDos" aria-expanded="false" aria-controls="collapseDos">
          Imss
        </button>
        <button class="text-white btn btn-dark collapsed" @click="reset()" type="button" data-toggle="collapse" data-target="#collapseTres" aria-expanded="false" aria-controls="collapseTres">
          Subsidio al empleo
        </button>
        <button class="text-white btn btn-dark collapsed" @click="reset()" type="button" data-toggle="collapse" data-target="#collapseCuatro" aria-expanded="false" aria-controls="collapseCuatro">
          Tarifas mensuales
        </button>
      </h5>
    </div>
    <div id="collapseUno" class="collapse" aria-labelledby="headingUno" data-parent="#accordion">
        <factorintegracion ref="factorintegracion"></factorintegracion>
    </div>
    <div id="collapseDos" class="collapse" aria-labelledby="headingDos" data-parent="#accordion">
        <imss ref="imss"></imss>
    </div>
    <div id="collapseTres" class="collapse" aria-labelledby="headingTres" data-parent="#accordion">
        <subsidioempleo ref="subsidioempleo"></subsidioempleo>
    </div>
    <div id="collapseCuatro" class="collapse" aria-labelledby="headingCuatro" data-parent="#accordion">
      <tarifamensual ref="tarifamensual"></tarifamensual>
    </div>
  </div>

</div>


  </div>
</main>

</template>

<script>
 const FactorIntegracion = r => require.ensure([], () => r(require('./FactorIntegracion.vue')), 'conta');
 const Imss = r => require.ensure([], () => r(require('./Imss.vue')), 'conta');
 const SubsidioEmpleo = r => require.ensure([], () => r(require('./SubsidioEmpleo.vue')), 'conta');
const TarifaMensual = r => require.ensure([], () => r(require('./TarifaMensual.vue')), 'conta');
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      isLoading :  false,
    }
  },
  components : {
    'tarifamensual' : TarifaMensual,
    'subsidioempleo' : SubsidioEmpleo,
    'imss' :  Imss,
    'factorintegracion' :FactorIntegracion,

  },
  methods : {
    reset(){
      var childTarifamensual = this.$refs.tarifamensual;
      childTarifamensual.cerrar();

      var childSubsidioEmpleo = this.$refs.subsidioempleo;
      childSubsidioEmpleo.cerrar();

      var childImss = this.$refs.imss;
      childImss.cerrar();

      var childFactorIntegracion = this.$refs.factorintegracion;
      childFactorIntegracion.cerrar();

    },
  },
  mounted () {

  },
}
</script>
