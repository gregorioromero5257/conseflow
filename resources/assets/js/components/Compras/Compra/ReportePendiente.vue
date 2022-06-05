<template>
<main class="main">
  <div class="container-fluid">
    <div class="card" v-show="!detalle">
      <div class="card-header">
          <i class="fa fa-align-justify"></i> Requisiciones pendientes
      </div>
      <div class="card-body">
        <v-client-table :data="data" :options="options" :columns="columns">
          <template slot="id" slot-scope="props" >
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button" class="dropdown-item" @click="verpartidas(props.row.id)">
                    <i class="icon-eye"></i>&nbsp;Ver partidas.
                  </button>
                </div>
              </div>
            </div>
          </template>
        </v-client-table>
        <!-- {{data}} -->
      </div>

    </div>

    <div class="card" v-show="detalle">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Detalles de la requisicion pendiente
        <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
          <i class="fas fa-arrow-left"></i>&nbsp;Atras
        </button>
      </div>
      <div class="card-body">
        <v-client-table :data="data_detalle" :columns="columns_detalle" :options="options_detalle">
          <template slot="pda" slot-scope="props" >
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button" class="dropdown-item" @click="cancelar(props.row)">
                    <i class="icon-eye"></i>&nbsp;Cancelar.
                  </button>
                </div>
              </div>
            </div>
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
      detalle: false,
      data : [],
      columns : ['id','folio','fecha_solicitud','descripcion_uso','nombrep','nombre_solicita'],
      options : {
        headings : {
          'id' : 'Acciones',
          'nombrep' : 'Proyecto',
          'nombre_solicita' : 'Solicita',
        },
        perPage : 10,
        perPageValues : [],
        skin : config.skin,
        sortIcon :config.sortIcon,
        sortable : ['folio','fecha_solicitud','descripcion_uso','nombrep','nombre_solicita'],
        filterable : ['folio','fecha_solicitud','descripcion_uso','nombrep','nombre_solicita'],
        filterByColumn : true,
        texts : config.texts,
      },
      data_detalle : [],
      columns_detalle : ['pda','rf','descripciona','marca','rfs','cantidad_compra','cantidad','cantidad_almacen'],
      options_detalle : {
        headings : {
          'pda': 'Acciones',
          'rf': 'Folio',
          'descripciona' : 'Articulo/Servicio',
          'marca' : 'Marca',
          'rfs': 'Fecha solicitud',
          'cantidad_compra' : 'Cantidad',
        },
        perPage : 10,
        perPageValues : [],
        skin :config.skin,
        sortIcon :config.sortIcon,
        sortable : ['rf','descripciona','marca','rfs','cantidad_compra'],
        filterable : ['rf','descripciona','marca','rfs','cantidad_compra'],
        filterByColumn : true,
        texts : config.texts,
      },

    }
  },
  methods : {
    getData(){
      var data_ = [];
    axios.get('/requisicionespendientes').then(response => {
      for (var i = 0; i < response.data.length; i++) {
        data_.push(response.data[i]['arreglo']);
      }
      this.data = data_;
    }).catch(error => {
      console.log(error);
    });
    },

    verpartidas(id){
      this.detalle = true;
      axios.get('/partidasrequisicionespendientes/' +id).then(response => {
        this.data_detalle = response.data;
        // console.log(response);
      }).catch(error => {
        console.log(error);
      });
    },

    cancelar(data){
      // console.log(data);
      axios.post('/cancelarpartidasrequisiciones',{
        'requisicione_id' : data.requisicione_id,
        'pda' : data.pda,
        'servicio_id' : data.servicio_id,
        'articulo_id' : data.articulo_id,
      }).then(response => {
        console.log(response);
        this.verpartidas(data.requisicione_id);
        toastr.info('Partida de requisición cancelada','Atención');
      }).catch(error => {
        console.log(error);
      });
    },

    maestro(){
      this.detalle = false;
      this.getData();
    }

  },
  mounted() {
    this.getData();
  }

}
</script>
