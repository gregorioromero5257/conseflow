<template>
  <div class="card border-secondary">
    <!-- {{detalles_solicitudes}} -->
    <!-- {{nuevo}} -->
    <div class="card-header bg-dark text-white">
      <i class="fa fa-align-justify"> </i> Fecha probable de pagos:
      <button v-if="detalle" type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
        <i class="fas fa-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <!-- <div class="card-body"> -->
    <vue-element-loading :active="isLoading"/>
    <table class="VueTables__table table table-hover table-sm" v-show="!detalle">
      <thead class="table-light">
        <tr>
          <th scope="col">Acción</th>
          <th scope="col">Cliente</th>
          <th scope="col">Fecha problable de pago</th>
          <th scope="col">Total</th>
          <th scope="col">Pagado</th>
        </tr>
      </thead>
      <tbody>

        <tr v-for="item in solicitudes" :value="item.id" :key="item.id">
          <td>
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-grip-horizontal"></i>&nbsp;Acciones
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <button type="button" @click="detalles(item.id)" class="dropdown-item" >
                  <i class="fas fa-eye"></i>&nbsp;Detalles
                </button>
              </div>
            </div>
            </div>
          </td>
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

        <!--Inicio tabla detalles -->
        <table class="VueTables__table table table-hover table-sm" v-show="detalle">
          <tbody>
            <tr>
              <th scope="col">Cliente</th>
              <td>{{datos == null ? '' : datos.nombre_cliente}}</td>
            </tr>
            <tr>
              <th scope="col">Banco</th>
              <td>{{datos == null ? '' : datos.nombre_banco}}</td>
            </tr>
            <tr>
              <th scope="col">Fecha entrega</th>
              <td>{{datos == null ? '' : datos.fecha_entrega}}</td>
            </tr>
            <tr>
              <th scope="col">Fecha corte</th>
              <td>{{datos == null ? '' : datos.fecha_corte}}</td>
            </tr>
            <tr>
              <th scope="col">Fecha emisión</th>
              <td>{{datos == null ? '' : datos.fecha_emision}}</td>
            </tr>
            <tr>
              <th scope="col">Días de crédito</th>
              <td>{{datos == null ? '' : datos.dias_credito}}</td>
            </tr>
            <tr>
              <th scope="col">Tipo de dias</th>
              <td>{{datos == null ? '' : datos.tipo_dias == 1 ? 'Naturales' : 'Hábiles'}}</td>
            </tr>
            <tr>
              <th scope="col">Total</th>
              <td>{{datos == null ? '' : datos.total}}</td>
            </tr>
          </tbody>
        </table>
        <!--Fin tabla detalles -->

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
        datos : null,
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
      detalles(data){
        console.log(data);
        this.detalle = true;
        this.isLoading = true;
        axios.get('/registropagos/'+data).then(response =>{
          this.datos = response.data;
          this.isLoading = false;
        }).catch(function (error){
          console.error(error);
        });
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
