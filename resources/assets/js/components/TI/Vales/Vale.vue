<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Vales

        </div>
        <div class="card-body">

          <ul class="nav nav-tabs" role="tablist" ref="tabs">
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#menu1" role="tab" @click="setId(1)"><i class="fas fa-user-shield"></i>&nbsp;Resguardo</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#menu2" role="tab" @click="setId(2)"><i class="fas fa-map-marker-alt"></i>&nbsp;Salida</a>
            </li>
          </ul>

          <div class="tab-content">
            <div id="menu1" class="tab-pane fade" v-show="tab == 1">
              <div class="form-row">
                <div class="col-md-8">
                  <h4>Resguardo</h4>
                </div>
                <div class="col-md-4">
                  <button type="button" @click="guardarR()" class="btn btn-dark float-sm-right">
                    <i class="fas fa-plus"></i>&nbsp;Nuevo
                  </button>
                  <div class="dropdown">

                    <button class="btn btn-secondary dropdown-toggle float-sm-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Empresa
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2" v-model="empresa">
                      <button class="dropdown-item" type="button" @click="empresa = 1;setDataTabResguardo()">Conserflow</button>
                      <button class="dropdown-item" type="button" @click="empresa = 2;setDataTabResguardo()">Constructora</button>
                    </div>
                  </div>
                </div>
              </div>
              <resguardo ref="resguardo"></resguardo>
            </div>
            <div id="menu2" class="tab-pane fade" v-show="tab == 2">
              <div class="form-row">
                <div class="col-md-8">
                  <h4>Sitio</h4>
                </div>
                <div class="col-md-4">
                  <button type="button" @click="guardarS()" class="btn btn-dark float-sm-right">
                    <i class="fas fa-plus"></i>&nbsp;Nuevo
                  </button>
                  <div class="dropdown">

                    <button class="btn btn-secondary dropdown-toggle float-sm-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Empresa
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2" v-model="empresa">
                      <button class="dropdown-item" type="button" @click="empresa = 1;setDataTabSitio()">Conserflow</button>
                      <button class="dropdown-item" type="button" @click="empresa = 2;setDataTabSitio()">Constructora</button>
                    </div>
                  </div>
                </div>
              </div>
              <sitio ref="sitio"></sitio>
            </div>
          </div>

        </div>
      </div>

    </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Resguardo = r => require.ensure([], () => r(require('./Resguardo.vue')), 'ti');
const Sitio = r => require.ensure([], () => r(require('./Sitio.vue')), 'ti');

export default {
  data(){
    return {
      tab : 1,
      empresa : '1',
    }
  },
  components : {
    'resguardo' : Resguardo,
    'sitio' : Sitio,
  },
  methods : {
    setId(id){
      this.tab = id;
    },

    guardarR(){
      var ChildResguardo = this.$refs.resguardo;
      ChildResguardo.abrirModal(1);
    },

    guardarS(){
      var ChildSitio = this.$refs.sitio;
      ChildSitio.abrirModal(1);
    },

    setData(){
      // console.log('montando');
      // var ChildResguardoI = this.$refs.resguardo;
      // ChildResguardoI.setInicial(1);

      // var ChildSitio = this.$refs.sitio;
      // ChildSitio.setDataEmpresa(1);
    },

    setDataTabResguardo(){
      var ChildResguardo = this.$refs.resguardo;
      ChildResguardo.getInicial(this.empresa);
    },

    setDataTabSitio(){
      var ChildSitio = this.$refs.sitio;
      ChildSitio.getLista(this.empresa);
    },
  },
  mounted(){
    // this.setData();
  }
}
</script>
