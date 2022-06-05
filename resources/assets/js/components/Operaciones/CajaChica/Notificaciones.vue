<template>
  <main class="main">
    <div class="container-fluid">
      <vue-element-loading :active="isLoading"/>
      <br>
      <div class="row">
        <div class="col-md-10" v-show="widgets.validarequisicion">
          <validarequisicion ref="validarequisicion" @validarequisicion:change="escucharHijo"></validarequisicion>
        </div>
      </div>


      <div class="row">
        <div class="col-md-10" v-show="widgets.autorizarequisicion">
          <autorizarequisicion ref="autorizarequisicion"></autorizarequisicion>
        </div>
      </div>




    </div>
  </main>
</template>
<script>
const RequisicionesAutorizar = r => require.ensure([], () => r(require('./RequisicionesAutorizar.vue')), 'opera');
const RequisicionesValidar = r => require.ensure([], () => r(require('./RequisicionesValidar.vue')), 'opera');
const modulo_id = 13;

export default {
  data (){
    return {
      isLoading: false,
      listaPermisos: [],
      widgets: {
        autorizarequisicion: false,
        validarequisicion : false,
      }
    }
  },
  components: {
    'autorizarequisicion': RequisicionesAutorizar,
    'validarequisicion' : RequisicionesValidar,
  },
  methods: {
    escucharHijo () {
      this.getListaPermisos();
    },
    getListaPermisos() {
      this.isLoading = true;
      let me=this;
      axios.post('/permisosdashboard/porusuariomodulo', {
        modulo_id: modulo_id
      }).then(response => {
        me.listaPermisos = response.data;
        me.isLoading = false;

        if (me.listaPermisos.indexOf('autorizarcaja') >= 0) {
          me.widgets.autorizarequisicion = true;
          var childautorizarequisicion = this.$refs.autorizarequisicion;
          childautorizarequisicion.cargarequisicion();
        }
        if (me.listaPermisos.indexOf('validarcaja') >= 0) {
          me.widgets.validarequisicion = true;
          var childvalidarequisicion = this.$refs.validarequisicion;
          childvalidarequisicion.cargarequisicion();
        }


      })
      .catch(function (error) {
        console.log(error);
      });
    }
  },
  mounted() {
    this.getListaPermisos();
  }
}
</script>
