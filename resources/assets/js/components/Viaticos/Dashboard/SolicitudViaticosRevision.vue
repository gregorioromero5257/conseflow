<template>
  <div class="card border-secondary">
    <!-- {{detalles_solicitudes}} -->
    <!-- {{nuevo}} -->
    <div class="card-header bg-dark text-white">
      <i class="fa fa-align-justify"> </i> Solicitudes de viaticos a revisar: {{solicituds == [] ? '' : solicituds.folio}}
      <button v-if="detalle" type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
        <i class="fas fa-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <!-- <div class="card-body"> -->
    <vue-element-loading :active="isLoading"/>
    <table class="VueTables__table table table-hover table-sm table-responsive" v-show="!detalle">
      <thead class="table-light">
        <tr>
          <th scope="col">Acción</th>
          <th scope="col">Detalles</th>
          <th scope="col">Descargar</th>
          <th scope="col">Folio</th>
          <th scope="col">Fecha solicitada</th>
          <th scope="col">Fecha requerida de pago</th>
          <th scope="col">Transferencia</th>
          <th scope="col">Efectivo</th>
          <th scope="col">Total</th>
          <th scope="col">Solicita</th>
        </tr>
      </thead>
      <tbody>

        <tr v-for="item in solicitudes" :value="item.solicitud.id" :key="item.solicitud.id">
          <td>
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-grip-horizontal"></i>&nbsp;
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                <button type="button" @click="enviarautorizar(item.solicitud.id, 3)" class="dropdown-item" >
                  <i class="fas fa-check-circle"></i>&nbsp;Enviar para autorización
                </button>
                <button type="button" @click="rechazar(item.solicitud.id, 0)" class="dropdown-item" >
                  <i class="fas fa-backspace"></i>&nbsp;Rechazar
                </button>
              </div>
            </div>
            </div>
          </td>
          <td>
            <button type="button" @click="detalles(item.solicitud.id)" class="btn btn-outline-dark" >
              <i class="fas fa-eye"></i>&nbsp;
            </button>
          </td>
          <td>
              <template v-if="item.solicitud.tipo ==  0">
                <button type="button" @click="descargarnForFij(item.solicitud.id, empresa)" class="btn btn-outline-dark" >
                  <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>
                </button>
              </template>
              <template v-if="item.solicitud.tipo ==  1">
                <button type="button" @click="decargarForVia(item.solicitud.id, empresa)" class="btn btn-outline-dark" >
                  <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>
                </button>
              </template>
              <template v-if="item.solicitud.tipo ==  2">
                <button type="button" @click="decargarForVia(item.solicitud.id, empresa)" class="btn btn-outline-dark" >
                  <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>
                </button>
              </template>
          </td>
          <td>{{item.solicitud.folio}}</td>
          <td>{{item.solicitud.fecha_solicitud}}</td>
          <td>{{item.solicitud.fecha_pago}}</td>
          <td>{{item.detalles.transferencia}}</td>
          <td>{{item.detalles.efectivo}}</td>
          <td>{{item.detalles.total}}</td>
          <td>{{item.solicitud.nombre_elabora}}</td>
            </tr>
          </tbody>
        </table>

        <!--Inicio tabla detalles -->
        <table class="VueTables__table table table-hover table-sm" v-show="detalle">
          <tbody>
            <tr>
              <th scope="col" align="center">Beneficiarios</th>
            </tr>
            <tr v-for="(item, key) in beneficiarios">
              <th scope="row">{{key + 1 }}</th>
              <td>{{item.nombre_beneficiario}}</td>
              <td>{{item.nombre_banco}}</td>
              <td>{{item.numero_cuenta}}</td>
            </tr>
            <tr>
              <th scope="col" colspan="4" align="center">Detalles</th>
            </tr>
            <tr>
              <th scope="col">Concepto</th>
              <th scope="col">Transferencia</th>
              <th scope="col">Efectivo</th>
              <th scope="col">Total</th>
            </tr>
            <tr v-for="item in detalles_listado">
              <td>{{item.nombre}}</td>
              <td>{{item.transferencia_electronica}}</td>
              <td>{{item.efectivo}}</td>
              <td>{{item.total}}</td>
            </tr>
            <tr >
              <th scope="row">Origen - Destino</th>
              <td>{{solicituds.origen_destino}}</td>
            </tr>
            <tr>
              <th scope="row">Fecha de salida:</th>
              <td>{{solicituds.fecha_salida}}</td>
              <th scope="row">Hora estimada de salida:</th>
              <td>{{solicituds.hora_estimada_salida}}</td>
            </tr>
            <tr>
              <th scope="row">Fecha de operación:</th>
              <td>{{solicituds.fecha_operacion}}</td>
              <th scope="row">Fecha de retorno estimada:</th>
              <td>{{solicituds.fecha_retorno}}</td>
            </tr>
            <tr>
              <th scope="row">Unidad</th>
              <th scope="row">KM Inicial</th>
              <th scope="row">Operador</th>
            </tr>
            <tr v-for="item in vehiculo">
              <td>{{item.unidad}}</td>
              <td>{{item.km_inicial}}</td>
              <td>{{item.nombre_empleado}}</td>
            </tr>
            <tr>
              <th scope="row" colspan="4">Personal destinado al servicio</th>
            </tr>
            <tr>
              <th scope="row" >Total de personas a asistir:</th>
              <td>{{solicituds.total_personas}}</td>
              <th colspan="row">Supervisor</th>
              <td>{{solicituds.nombre_supervisor}}</td>
            </tr>
            <tr v-for="item in empleados">
              <td colspan="4">{{item.nombre_empleado}}</td>
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
        empresa : '1',
        isLoading: false,
        solicitudes: [],
        beneficiarios : [],
        detalles_listado : [],
        empleados : [],
        solicituds : [],
        vehiculo : [],
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
        axios.get('/viaticosenrevision').then(response => {
          me.solicitudes= response.data;
          me.isLoading = false;
        })
        .catch(function (error) {
          console.log(error);
        });
      },
      cargarsolicitudviaticos() {
        this.getData();
      },
      detalles(data){
        this.detalle = true;
        this.isLoading = true;
        axios.get('/solicitudviaticos/'+data).then(response =>{
          this.beneficiarios = response.data[0].beneficiarios;
          this.detalles_listado = response.data[0].detalles_listado;
          this.solicituds = response.data[0].solicitud;
          this.vehiculo = response.data[0].vehiculo;
          this.empleados = response.data[0].empleados;
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
      },
      enviarautorizar(id, edo){
        this.isLoading = true;
        swal({
          title: 'Esta seguro(a) validar?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#4dbd74',
          cancelButtonColor: '#f86c6b',
          confirmButtonText: 'Aceptar!',
          cancelButtonText: 'Cancelar',
          reverseButtons: true
        }).then(result =>{
          if (result.value){
            axios.post('/estadosviaticos',{
              'id' : id,
              'edo' : edo,
            }).then(response => {
              if (edo == 3) {
                    this.isLoading = false;
                    toastr.success('Solicitud de viaticos validado','Correcto');
                    this.getData();
                    this.$emit('autorizar:change');
              }
            }).catch(error => {
              console.error(error);
            });
          }else {
            this.isLoading = false;
          }
          });
      },

      rechazar(id, edo){

        this.isLoading = true;

        swal({
          title: 'Esta seguro(a) rechazar?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#4dbd74',
          cancelButtonColor: '#f86c6b',
          confirmButtonText: 'Aceptar!',
          cancelButtonText: 'Cancelar',
          reverseButtons: true
        }).then(result =>{
          if (result.value) {
            swal({
              title: 'Motivo del rechazo del viatico',
              input: 'textarea',
              inputAttributes: {
                autocapitalize: 'off'
              },
              showCancelButton: true,
              confirmButtonText: 'Continuar',
              showLoaderOnConfirm: true,
            }).then((result) => {
              if (result.value) {
                axios.post('/estadosviaticos',{
                  'id' : id,
                  'edo' : edo,
                  'motivo' : result.value,
                }).then(response => {
                  if (edo == 0) {
                    this.isLoading = false;
                    toastr.warning('Solicitud de viaticos rechazada','Atención');
                    this.getData();
                  }
                }).catch(error => {
                  console.error(error);
                });
              }else {
                this.isLoading = false;
              }
              });
          }else {
            this.isLoading = false;
          }

        });

      },

      decargarForVia(data, empresa){
        window.open('/descargarformatoviatico/' + data + '&' + empresa, '_blank');

      },
      descargarnForFij(data, empresa){
        window.open('/descargarnformatofij/' + data + '&' + empresa, '_blank');

      },
    },
    mounted() {
    }
  }
  </script>
