<template>
  <main class="main">
    <div class="container-fluid">
      <vue-element-loading :active="isLoading"/>
      <br>
      <div class="row">
        <div class="col-md-8" v-show="widgets.validarequisicion">
          <validarequisicion ref="validarequisicion" @validarequisicion:change="escucharHijo"></validarequisicion>
        </div>
      </div>

      <div class="row">
        <div class="col-md-8" v-show="widgets.calidadrequisicion">
          <calidadrequisicion ref="calidadrequisicion" @calidadrequisicion:change="escucharHijo"></calidadrequisicion>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12" v-show="widgets.autorizarequisicion">
          <autorizarequisicion ref="autorizarequisicion"></autorizarequisicion>
        </div>
      </div>


      <div class="row">
        <div class="col-md-8" v-show="widgets.documentospendientes">
          <documentospendientes ref="documentospendientes"></documentospendientes>
        </div>
      </div>


    </div>
  </main>
</template>
<script>
const RequisicionesAutorizar = r => require.ensure([], () => r(require('./RequisicionesAutorizar.vue')), 'proyecto');
const RequisicionesValidar = r => require.ensure([], () => r(require('./RequisicionesValidar.vue')), 'proyecto');
const RequisicionesCalidad = r => require.ensure([], () => r(require('./RequisicionesCalidad.vue')), 'proyecto');
const DocumentosPendientes = r => require.ensure([], () => r(require('./DocumentosPendientes.vue')), 'proyecto');
const modulo_id = 3;

export default {
  data (){
    return {
      isLoading: false,
      listaPermisos: [],
      widgets: {
        autorizarequisicion: false,
        validarequisicion : false,
        calidadrequisicion : false,
        documentospendientes : false,
      }
    }
  },
  components: {
    'autorizarequisicion': RequisicionesAutorizar,
    'validarequisicion' : RequisicionesValidar,
    'calidadrequisicion' : RequisicionesCalidad,
    'documentospendientes' : DocumentosPendientes,
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

        if (me.listaPermisos.indexOf('autorizarequisicion') >= 0) {
          me.widgets.autorizarequisicion = true;
          var childautorizarequisicion = this.$refs.autorizarequisicion;
          childautorizarequisicion.cargarequisicion();
        }
        if (me.listaPermisos.indexOf('validarequisicion') >= 0) {
          me.widgets.validarequisicion = true;
          var childvalidarequisicion = this.$refs.validarequisicion;
          childvalidarequisicion.cargarequisicion();
        }

        if (me.listaPermisos.indexOf('calidadrequisicion') >= 0) {
          me.widgets.calidadrequisicion = true;
          var childcalidadrequisicion = this.$refs.calidadrequisicion;
          childcalidadrequisicion.cargarequisicion();
        }

        if (me.listaPermisos.indexOf('documentospendientes') >= 0) {
          me.widgets.documentospendientes = true;
          var childDocumentospendientes = this.$refs.documentospendientes;
          childDocumentospendientes.cargardocumentos();
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
