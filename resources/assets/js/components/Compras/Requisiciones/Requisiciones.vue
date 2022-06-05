<template>
  <div>

        <!-- Ejemplo de tabla Listado -->
      <div class="card" v-show="!detallerequisiciones">
        <vue-element-loading :active="isLoading"/>
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Requisiciones
          <button type="button" @click="maestroInicial()" class="btn btn-secondary float-sm-right" >
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="id" slot-scope="props">
              <div class="btn-group " role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <i class="fas fa-grip-horizontal"></i>&nbsp;Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <template v-if="props.row.formato_requisiciones == null || props.row.formato_requisiciones == '' ">
                      <a v-show="PermisosCrud.Download" class="dropdown-item" @click="descargar(props.row)" href="#">
                        <i class="fas fa-file-pdf"></i>&nbsp;Descargar Formato de Requisición
                      </a>
                    </template>
                    
                  </div>
                </div>
              </div>
            </template>

            <template slot="estado_id" slot-scope="props">
              <template v-if="props.row.estado_id == 5">
                <button type="button" class="btn btn-siete"><i class="fa fa-caret-square-o-left"></i>&nbsp;Recibido</button>
              </template>
            </template>
          </v-client-table>
        </div>
      <!-- </div> -->
      <!-- Fin ejemplo de tabla Listado -->

</div>

</div>
</template>
<script>

import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data (){
    return {
      PermisosCrud : {},
      url: '/requisicioncom',
      detallerequisiciones: false,
      principal : null,
      modal : false,
      doc_req : [],
      optionsvs : [],//
      widgets : {
        detallerequisiciones : false,
      },
      modal : 0,//
      tituloModal : '',//
      tipoAccion : 0,//
      tipoAcciondos : 1,
      disabled: 0,
      isLoading: false,
      columns: [ 'id','folio','nombrep','fecha_solicitud','descripcion_uso','estado_id'],
      tableData: [],
      options: {
        headings: {
          'id': 'Acción',
          'folio': 'Folio',
          'nombrep': 'Nombre proyecto',
          'fecha_solicitud': 'Fecha solicitud',
          'estado_id' :'Estado',
          'descripcion_uso' : 'Descripción',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['folio','nombrep','fecha_solicitud'],
        filterable: ['folio','nombrep','fecha_solicitud'],
        filterByColumn: true,
        
        texts:config.texts
      },

    }
  },
  methods : {


      getData(data) {
        let me=this;
        this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
        me.isLoading = true;
        me.principal = data;
        axios.get('/requisicioncom/' + data).then(response => {
          me.tableData = response.data;
          me.isLoading = false;
        })
        .catch(function (error) {
          console.log(error);
        });
      },
      maestro(){
        this.widgets.detallerequisiciones = false;
        this.detallerequisiciones = false;
        this.tipoAccionpr =0;
        this.isLoading = false;
      },
      descargar(data) {
        window.open('descargar-requisicion/' + data.id, '_blank');
      },

      descargarFormato(archivo,id){
        let me=this;
        axios({
          url: '/descargaRequi',
          method: 'POST',
          data: {'metodo': id, 'archivo':archivo},
          responseType: 'blob', // importante
        }).then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
          document.body.appendChild(link);
          link.click();
          //--Llama el metodo para borrar el archivo local una ves descargado--//
          axios({
            url: '/descargaRequi',
            method: 'POST',
            data: {'metodo': 0, 'archivo':archivo},
          })
          .then(response => {
          })
          .catch(function (error) {
            console.log(error);
          });
          //--fin del metodo borrar--//
        });
      },

     

      /**
      * [cerrarRequisicion description]
      * @param  {Array}  [data=[]] [description]
      * @return {[type]}           [description]
      */
  

      maestroInicial(){
        this.$emit('requisiciones:click');
      }
    },
    mounted() {
    }
  }
  </script>
  <style>
  .btn-siete {
  color: rgb(255, 255, 255);
  background-color: #25a30d;
  border-color: #25a30d;
  border-radius: 2px; 
  }      
  </style>
