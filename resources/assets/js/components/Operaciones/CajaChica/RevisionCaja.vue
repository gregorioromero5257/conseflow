<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Revisión caja chica
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>

          <v-client-table :options="options" :columns="columns" :data="dataTable">
            <template slot="comprobacion.id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>&nbsp;
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                    <button type="button" v-if="props.row.comprobacion.ccc_estado == 0" @click="validar(props.row)" class="dropdown-item" >
                      <i class="fas fa-check"></i>&nbsp;Validar
                    </button>
                    <button type="button" v-if="props.row.comprobacion.ccc_estado == 0" @click="rechazar(props.row)" class="dropdown-item" >
                      <i class="fas fa-times"></i>&nbsp;Rechazar
                    </button>

                  </div>
                </div>
              </div>
            </template>

            <template slot="iva" slot-scope="props">
              <template v-if="props.row.impuestos != null">
                <template v-if="props.row.impuestos.impuesto === '002'">
                  {{props.row.impuestos.importe}}
                </template>
              </template>
            </template>
            <template slot="ieps" slot-scope="props">
              <template v-if="props.row.impuestos != null">
                <template v-if="props.row.impuestos.impuesto == 003">
                  {{props.row.impuestos.importe}}
                </template>
              </template>
            </template>
            <template slot="tasa_0" slot-scope="props">
              <template v-if="props.row.impuestos != null">
                <template v-if="props.row.impuestos == []">
                  -
                </template>
              </template>
            </template>
            <template slot="comprobacion.fecha" slot-scope="props">
              {{(props.row.comprobacion.fecha).substring(0,10)}}
            </template>
            <template slot="comprobacion.ccc_estado" slot-scope="props">
              <template v-if="props.row.comprobacion.ccc_estado === 0">
                <span class="text-warning">Nuevo</span>
              </template>
              <template v-if="props.row.comprobacion.ccc_estado === 2">
                <span class="text-success">Enviado</span>
              </template>
              <template v-if="props.row.comprobacion.ccc_estado === 1">
                <span class="text-primary">Aceptado</span>
              </template>
              <template v-if="props.row.comprobacion.ccc_estado === 3">
                <span class="text-danger">Rechazado</span>
              </template>
            </template>
            <template slot="comprobacion.comprobante" slot-scope="props">
              <button class="btn btn-outline-dark" @click="descargar(props.row.comprobacion.comprobante)"> <i class="fas fa-download"></i> <i class="fas fa-file-pdf"></i> </button>
            </template>
            <template slot="comprobacion.xml" slot-scope="props">
              <button class="btn btn-outline-dark" @click="descargar(props.row.comprobacion.xml)"> <i class="fas fa-download"></i> <i class="fas fa-file-excel"></i> </button>
            </template>
          </v-client-table>
        </div>
      </div>

    </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return {
      isLoading :false,
      dataTable : [],
      columns : [
        'comprobacion.id',
        'comprobacion.fecha',
        'comprobacion.ccc_uuid',
        'comprobacion.nombre_e',
        'comprobacion.rfc_e',
        'concepto.descripcion',
        'comprobacion.nombre_corto',
        'comprobacion.subtotal',
        'tasa_0','ieps','iva','retencion.importe',
        'comprobacion.total',
        'comprobacion.comprobante',
        'comprobacion.xml',
        'comprobacion.ccc_estado'],
        // dataTable : [],
        options : {
          headings: {
            'comprobacion.id' :'Acciones',
            'comprobacion.fecha' : 'Fecha',
            'comprobacion.ccc_uuid' : 'UUID',
            'comprobacion.nombre_e' : 'Acreedor y/o Proveedor',
            'comprobacion.rfc_e' : 'RFC',
            'concepto.descripcion' : 'Concepto',
            'comprobacion.nombre_corto' : 'Centro Costo',
            'comprobacion.subtotal' : 'Importe',
            'retencion.importe' : 'Retencion',
            'comprobacion.total' : 'Total',
            'comprobacion.ccc_estado' : 'Estado',
            'comprobacion.comprobante' : 'Comprobante',
            'comprobacion.xml' : 'XML',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          filterByColumn: true,
          texts:config.texts
        },

      }
    },
    methods : {
      /**
      **
      **/
      getData(){
        axios.get('caja/chica/revision').then(response => {
          this.dataTable = response.data[0].data;
        }).catch(e => {
          console.error(e);
        });
      },

      descargar(archivo){
        let me=this;
        axios({
          url: '/facturasentradasdescarga/' + archivo,
          method: 'GET',
          responseType: 'blob', // importante
        }).then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
          document.body.appendChild(link);
          link.click();
          //--Llama el metodo para borrar el archivo local una ves descargado--//
          axios.get('/facturasentradaseditar/' + archivo)
          .then(response => {
          })
          .catch(function (error) {
            console.log(error);
          });
          //--fin del metodo borrar--//
        });
      },

      validar(data){
        this.isLoading = true;
        swal({
          title: 'Esta seguro de validar el material de caja chica?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#4dbd74',
          cancelButtonColor: '#f86c6b',
          confirmButtonText: 'Aceptar!',
          cancelButtonText: 'Cancelar',
          reverseButtons: true
        }).then(result =>{
          if (result.value) {
            axios.get('validar/material/cajachica/' + data.comprobacion.ccc_id).then(response => {
              this.getData();
              this.isLoading = false;
            }).catch(e => {
              console.error(e);
            });
          }
        });
      },

      rechazar(data){
        this.isLoading = true;
        swal({
          title: 'Esta seguro de rechazar el material de caja chica?',
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
              title: 'Motivo del rechazo de material',
              input: 'textarea',
              inputAttributes: {
                autocapitalize: 'off'
              },
              showCancelButton: true,
              confirmButtonText: 'Continuar',
              showLoaderOnConfirm: true,
            }).then((result_uno) => {
              if (result_uno.value) {

                axios.post('rechazar/material/cajachica/',{
                  id :  data.comprobacion.ccc_id,
                  comentario : result_uno.value
                }).then(response => {
                  this.getData();
                  this.isLoading = false;
                }).catch(e => {
                  console.error(e);
                });

              }
            });
          }
        });
      },

    },
    mounted(){
      this.getData();
    }

  }

  </script>
