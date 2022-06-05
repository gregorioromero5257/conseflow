<template>
  <div class="card border-secondary">
    {{detalles_solicitudes}}
    <!-- {{nuevo}} -->
    <div class="card-header">
      <i class="fa fa-align-justify"> </i> Requisiciones por completar:
      <button v-if="detalles_ver" type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
        <i class="icon-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <!-- <div class="card-body"> -->
    <vue-element-loading :active="isLoading"/>
    <table class="VueTables__table table table-hover table-sm" v-show="!detalle">
      <thead class="table-light">
        <tr>
          <th scope="col">Detalles</th>
          <th scope="col">Folio</th>
          <th scope="col">Proyecto</th>
          <th scope="col">Fecha de solicitud</th>
          <th scope="col">Empleado que solicita</th>
          <th scope="col">Empleado que autoriza</th>
          <th scope="col">Recibir</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in solicitudes" :value="item.id" :key="item.id">
          <td><button type="button" @click="cargardetalle(item.id)" class="btn btn-info btn-sm">
            <i class="fas fa-eye"></i>
          </button>
        </td>
        <td>{{item.folio}}</td>
        <td>{{item.nombrep}}</td>
        <td>{{item.fecha_solicitud}}</td>
        <td>{{item.nombre_solicita}}</td>
        <td>{{item.nombre_autoriza}}</td>
        <td>
            <button class="btn btn-sm btn-success" @click="autorizar(2, item.id)"><i class="fas fa-check"></i> Si</button>
            <button class="btn btn-sm btn-danger" @click="autorizar(3, item.id)"><i class="fas fa-close"></i> No</button>
        </td>

      </tr>
    </tbody>
  </table>

  <div v-show="detalles_ver">
<table class="VueTables__table table table-hover table-sm">
  <thead class="table-light">
    <tr>
      <th scope="col">Artículo</th>
      <th scope="col">Fecha requerida</th>
      <th scope="col">Unidad</th>
      <th scope="col">Cantidad</th>
      <th scope="col">Cantidad a comprar</th>
      <th scope="col">Cantidad en almacen</th>
      <th scope="col">Asignar</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="item in detalles_solicitudes" :value="item.req.id" :key="item.req.id">
    <td>{{item.req.descripcion}}</td>
    <td>{{item.req.frequerido}}</td>
    <td>{{item.req.unidad_articulo}}</td>
    <td>{{item.req.equivale}}</td>
    <td>{{item.req.cantidad_compra}}</td>
    <td>{{item.req.cantidad_almacen}}</td>
    <td><button type="button" @click="asignar(item)" class="btn btn-info btn-sm">
      <i class="fas fa-eye"></i>
    </button></td>
    </tr>
</tbody>
</table>
  </div>


  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_almacen}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Modal body text goes here.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary">Save changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
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
      modal_almacen : 0,
    }
  },
  methods: {
    emitirEventoHijo()
    {
      this.$emit('requisicionesalmacen:change');
    },
    getData() {
      this.isLoading = true;
      let me=this;
      axios.get('/requisicionesalmacen').then(response => {
        me.solicitudes= response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    cargarcompras() {
      this.getData();
    },
    cargardetalle(id)
    {
      this.isLoading = true;
      this.detalles_ver = true;
      this.detalle = true;
      let me = this;
      me.emitirEventoHijo();
      axios.get('/requisicion/' + id + '/requisicion').then(response => {
        me.detalles_solicitudes = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });

    },
    autorizar(estado , id)
    {
      let me = this;
      axios.post('/autorizarequisicionproyectos',{
        id : id,
        estado : estado,
      }).then(function (response){
        if (response == 2) {
          toastr.success('Correcto','Requisicion recibida correctamente');
        }
        else if (response == 3) {
          toastr.danger('Atención','Requisicion no recibida');
        }
        me.getData();
      }).catch(function (error){
        console.error(error);
      });
    },
    asignar(data = [])
    {
      console.log(data['req']);
      this.modal_almacen = 1;
      // console.log(data['req'].descripcion);
    },
    maestro(){
      this.detalles_ver = false;
      this.detalle = false;
      this.detalles_solicitudes = null;
      this.emitirEventoHijo();


    }
  },
  mounted() {
  }
}
</script>
