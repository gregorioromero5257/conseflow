<template>
  <div class="card border-dark">
    <!-- {{detalles_solicitudes}} -->
    <!-- {{nuevo}} -->
    <vue-element-loading :active="isLoading"/>
    <div class="card-header bg-dark text-white">
      <i class="fa fa-align-justify"> </i> Caja chica por validar:
    </div>
    <div class="card-body">
      <v-client-table :columns="columns" :data="dataTable" :options="options" ref="myTable" >

        <template slot="id" slot-scope="props">
          <button type="button" @click="descargar(props.row)" class="btn btn-outline-dark" >
            <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>
          </button>

        </template>

        <template slot="empresa" slot-scope="props">
          <template v-if="props.row.empresa == 1">
            <span>CONSERFLOW</span>
          </template>
          <template v-if="props.row.empresa == 2">
            <span> CONSTRUCTORA</span>
          </template>
        </template>

        <template slot="total" slot-scope="props">
          <span style="text-align: right;">$ &nbsp;{{new Intl.NumberFormat().format((parseFloat(props.row.efectivo) + parseFloat(props.row.tranferencia)).toFixed(2)) }}</span>
        </template>

        <template slot="estado" slot-scope="props" >
          <button class="btn btn-sm btn-success" @click="autorizar(2, props.row)">
            <i class="far fa-check-square"></i>&nbsp; Si&nbsp;&nbsp;</button>
            <button class="btn btn-sm btn-danger" @click="autorizar(3, props.row)">
              <i class="fas fa-window-close"></i>&nbsp; No</button>
            </template>
          </v-client-table>

        </div>

        <!-- </div> -->
      </div>
    </template>
    <script>
    import Utilerias from '../../Herramientas/utilerias.js';
    var config = require('../../Herramientas/config-vuetables-client').call(this);

    export default {
      data() {
        return {
          columns : ['id','empresa','fecha','total','beneficiario','estado'],
          dataTable : [],
          options: {
            headings: {
              'id' : 'Acciones',
            },
            perPage: 10,
            perPageValues: [],
            skin: config.skin,
            sortIcon: config.sortIcon,
            filterByColumn: true,
            texts:config.texts,
          },
          modaldocumentos : false,
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
        emitirEventoHijo () {
          this.$emit('validarequisicion:change');
        },
        getData() {
          this.isLoading = true;
          let me=this;
          axios.get('/caja/chica/validar').then(response => {
            me.solicitudes= response.data;
            me.dataTable = response.data;
            me.isLoading = false;
          })
          .catch(function (error) {
            console.log(error);
          });
        },
        cargarequisicion() {
          this.getData();
        },
        autorizar(estado , data)
        {

          swal({
            title: estado != 3  ? 'Esta seguro(a) validar la caja chica?' : 'Esta seguro(a) de rechazar la caja chica?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4dbd74',
            cancelButtonColor: '#f86c6b',
            confirmButtonText: 'Aceptar!',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
          }).then(result =>{
            if (result.value) {
              console.log(data,'si');
              this.isLoading = true;
              let me = this;
              if (estado == 2) {

                axios.post('/caja/chica/solicitud/validar',{
                  id : data.id,
                  empresa : data.empresa,
                  estado : estado,
                  mensaje_adicional : '',
                }).then(function (response){
                  me.emitirEventoHijo();
                  if (response.data == 2) {
                    toastr.success('Correcto','Validado !!!');
                  }
                  else if (response.data == 3) {
                    toastr.warning('Atención','No validado !!!');
                  }
                  me.getData();
                  me.isLoading = false;
                }).catch(function (error){
                  console.error(error);
                });

              }else if (estado == 3) {
                //
                swal({
                  title: 'Motivo del rechazo de la caja chica',
                  input: 'textarea',
                  inputAttributes: {
                    autocapitalize: 'off'
                  },
                  showCancelButton: true,
                  confirmButtonText: 'Continuar',
                  showLoaderOnConfirm: true,
                }).then((result) => {
                  if (result.value) {
                    axios.post('/caja/chica/solicitud/validar',{
                      id : data.id,
                      empresa : data.empresa,
                      estado : estado,
                      mensaje_adicional : result.value,
                    }).then(function (response){
                      me.emitirEventoHijo();
                      if (response.data == 2) {
                        toastr.success('Correcto','Validado !!!');
                      }
                      else if (response.data == 3) {
                        toastr.warning('Atención','No validado !!!');
                      }
                      me.getData();
                      me.isLoading = false;
                    }).catch(function (error){
                      console.error(error);
                    });
                  }
                });
                //
              }

            }
          });

        },

        maestro(){
          this.detalles_ver = false;
          this.detalle = false;
          this.detalles_solicitudes = null;
        },

        descargar(data){
          window.open('descargar/solicitud/caja/chica/' + data.id + '&' + data.empresa, '_blank');
        },

      },
      mounted() {
      }
    }
    </script>
