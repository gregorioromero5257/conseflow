<template>
  <div class="card-header">
      <i class="fa fa-align-justify"></i> {{ arreglo.titulo }}
      <button type="button" 
      v-on:click="abrir(arreglo.modulo,arreglo.accion)"
       class="btn btn-dark">
          <i class="fas fa-plus"></i>&nbsp;Nuevo
      </button>
  </div>
</template>
<script>
  export default {
    props:['arreglo'],
    data() {
      return {
      }
    },
    methods: {
      abrir(modulo,accion) {
        this.$emit('abrir',[modulo,accion]);
      }
    }
  }
</script>

