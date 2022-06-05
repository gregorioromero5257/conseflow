<template>
  <div class="card border-secondary">
    <!-- {{detalles_solicitudes}} -->
    <!-- {{nuevo}} -->
    <div class="card-header">
      <i class="fa fa-align-justify"> </i> Fecha probable de pagos:
      <button v-if="detalles_ver" type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
        <i class="icon-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <!-- <div class="card-body"> -->
    <vue-element-loading :active="isLoading"/>
    <table class="VueTables__table table table-hover table-sm" v-show="!detalle">
      <thead class="table-light">
        <tr>
          <th scope="col">Cliente</th>
          <th scope="col">Fecha problable de pago</th>
          <th scope="col">Total</th>
          <th scope="col">Pagado</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in solicitudes" :value="item.id" :key="item.id">
        <td>{{item.nombre_cliente}}</td>
        <td>{{item.fecha_pago}}</td>
        <td>{{item.total}}</td>
        <td>
          <div v-if="item.condicion == 1">
            <button type="button" class="btn btn-success btn-sm" @click="pagado(item.id)">
              <i class="far fa-square"></i></button>
          </div>
          <div v-if="item.condicion == 0">
            <button type="button" class="btn btn-success btn-sm" disabled>
              <i class="far fa-check-square"></i></button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>


</div>


<!-- </div> -->
</div>
</template>
<script>
export default {
  data() {
    return {
      isLoading: false,
      solicitudes: [],
      detalle: false,
      nuevo : null,
      solicitud: [],
      detalles_ver : false,
      detalles_solicitudes : null,
      tipo_cambio : 0,
      moneda : 0,
    }
  },
  methods: {
    getData() {
      this.isLoading = true;
      let me=this;
      axios.get('/registropagosdasboard').then(response => {
        me.solicitudes= response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    cargarpagosclientes() {
      this.getData();
    },
    pagado(id)
    {
      let me= this;
      axios.get('/clientepago/'+id).then(response => {
        me.getData();
        toastr.success('Pago Realizado Correctamente');

      }).catch(function (error) {
          console.log(error);
        });
    },

    maestro(){
      this.detalles_ver = false;
      this.detalle = false;
    }
  },
  mounted() {
  }
}
</script>
