<template>
  <div>
    <!-- inicio del lsitado principal -->
    <div class="card">
      <div class="card-header">
        <!-- <button type="button" @click="abrirModal('viaticos','registrar')" class="btn btn-dark float-sm-right">
          <i class="fas fa-plus"></i>&nbsp;Registrar Pago
        </button> -->
        <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right" >
          <i class="fas fa-arrow-left"></i>&nbsp;Atras
        </button>
      </div>
      <div class="card-body">

      </div>

    </div>
    <!--fin del listado principal  -->

  </div>
</template>

<script>
export default {
  data(){
    return {

    }
  },
  methods : {
    maestro(){
      this.$emit('facturas:maestro');
    },

    getData(data){
      console.log(data);
    }

  },
}
</script>
