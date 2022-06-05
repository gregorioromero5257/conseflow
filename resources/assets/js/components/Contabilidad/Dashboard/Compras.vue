<template>
  <div class="card border-dark">
    <!-- {{detalles_solicitudes}} -->
    <!-- {{nuevo}} -->
    <div class="card-header bg-dark text-white">
      <i class="fa fa-align-justify"> </i> Compras por agendar :
      <button v-if="detalles_ver" type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
        <i class="fas fa-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <!-- <div class="card-body"> -->
    <vue-element-loading :active="isLoading"/>
    <table class="VueTables__table table table-hover table-sm" v-show="!detalle">
      <thead class="table-light">
        <tr>
          <th scope="col">Acciones</th>
          <th scope="col">Proveedor</th>
          <th scope="col">Folio Compra</th>
          <th scope="col">Proyecto</th>
          <th scope="col">Total</th>

        </tr>
      </thead>
      <tbody>
        <tr v-for="item in solicitudes" :value="item.id" :key="item.id">
           <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                      <div class="btn-group dropup" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-grip-horizontal"></i> Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
          <td><button type="button" @click="cargardetalle(item.id)" class="dropdown-item">
            <i class="fas fa-eye"></i>&nbsp; Ver detalles
          </button>

        </td></div> </div> </div>
        <td>{{item.razon_social}}</td>
        <td>{{item.folio}}</td>
        <td>{{item.nombre_corto}}</td>
        <td>{{item.total}}</td>

      </tr>

    </tbody>
  </table>

  <div v-show="detalles_ver">
    <table class="VueTables__table table table-hover table-sm" >
      <thead class="table-light">
        <tr>
          <!-- <th scope="col">Acción</th> -->
          <th scope="col">Proveedor</th>
          <th scope="col">Banco</th>
          <th scope="col">CLABE</th>
          <th scope="col">N° Cuenta</th>
          <th scope="col">Convenio CIE</th>
          <th scope="col">Folio Compra</th>
          <th scope="col">Proyecto</th>


        </tr>
      </thead>
      <tbody>
        <tr>
          <!-- <td>
          <button class="btn btn-sm btn-success" @click="autoriznar(detalles_solicitudes.id)"><i class="fas fa-calendar"></i> Agendar</button>
        </td> -->
        <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.razon_social}}</td>
        <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.banco_transferencia}}</td>
        <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.clabe}}</td>
        <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.numero_cuenta}}</td>
        <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.cie}}</td>
        <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.folio}}</td>
        <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.nombre_corto}}</td>

      </tr>
    </tbody>
  </table>
  <table class="VueTables__table table table-hover table-sm" >
    <thead class="table-light">
      <tr>
        <th scope="col">Condición de pago</th>
        <th scope="col">Rango de dias</th>
        <th scope="col">Pagos</th>
        <th scope="col">Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.nombre_condicion_pago}}</td>
        <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.rango_dias}}</td>
        <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.eventos}}</td>
        <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.monto}}</td>
      </tr>
    </tbody>
  </table>


  <!-- <div v-show="detalles_solicitudes == null ? '' : detalles_solicitudes.tipo_cambio != 1">
    <div class='form-row align-items-center'>
      <div class='form-group col-md-6'>
    <label for="moneda">Moneda de pago</label>
    <select class="form-control" disabled name="moneda" id="moneda" v-model="detalles_solicitudes == null ? 0 :detalles_solicitudes.moneda" >
      <option value="1">Dolares (USD)</option>
      <option value="2">Moneda Nacional (MXN)</option>
      <option value="3">Euros (EUR)</option>
    </select>
  </div>
</div>

    <div class='form-row align-items-center'>
      <div class='form-group col-md-6'>
        <label for='tipo_cambio'>Tipo de cambio</label>
        <input type='number' class='form-control' v-validate="'required'" placeholder='Tipo de cambio' v-model="tipo_cambio">
      </div>
    </div>
  </div> -->

  <!-- &nbsp;&nbsp;&nbsp;<div class="input_fields" id="input_impuesto"></div> -->
  <div class="form-row">

<div class="col-md-2">
  &nbsp;&nbsp;&nbsp;
  <button class="btn btn-sm btn-success" @click="autorizar(detalles_solicitudes.id, detalles_solicitudes.orden_compra_id)"><i class="fas fa-check"></i> Aceptado</button>
</div>
<div class="col-md-2">
  &nbsp;&nbsp;&nbsp;
  <button class="btn btn-sm btn-danger" @click="descargar(detalles_solicitudes.orden_compra_id)"><i class="fas fa-file-pdf"></i> Descargar</button>
</div>

  </div>
  <br>
</div>
</div>


<!-- </div> -->

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
      axios.get('/comprasautorizadas').then(response => {

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
    sumarDias(fecha, dias){
      fecha.setDate(fecha.getDate() + dias);
      return fecha;
    },
    cargardetalle(id)
    {
      this.detalles_ver = true;
      this.detalle = true;
      let me = this;
      axios.get('/comprasautorizadasid/'+id).then(response => {
        me.detalles_solicitudes = response.data;
      // console.log(response.data);

      }).catch(function (error){
        console.error(error);
      });
    },
    autorizar(id,ord_compra_id)
    {
    location.href='dashboard#/pagos/norecurrentes/?id=' + ord_compra_id;

      //
      // this.nuevo = result;
      // let me = this;
      //
      //     axios.post('/agendapagosnr', {
      //       id: id,
      //       fecha: tipos,
      //       orden_compra_id : ord_compra_id,
      //       tipo_cambio : this.tipo_cambio,
      //     })
      //     .then(function (response) {
      //       toastr.success('Compra agendada correctamente');
      //       me.maestro();
      //       me.getData();
      //     })
      //     .catch(function (error) {
      //       console.log(error);
      //     });



    },
    maestro(){
      this.detalles_ver = false;
      this.detalle = false;
      this.detalles_solicitudes = null;
      $("#input_impuesto").html('');

    },

    descargar(id) {
      window.open('descargar-compran/' + id, '_blank');
      let me = this;
    },
  },
  mounted() {
  }
}
</script>
