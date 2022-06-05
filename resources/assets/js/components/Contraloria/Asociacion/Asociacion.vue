<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card" v-show="!detalle">
        <div class="card-header">

        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="col-md-5 mb-3">
              <label>Proyectos</label>
              <v-select :options="listaProyectos" v-validate="'required'" v-model="proyecto_id"
              label="nombre_corto" name="nombre_corto" data-vv-name="proyecto"
              @input="getProyecto()" ></v-select> <!---->
            </div>
          </div>
          <hr><br><br><br>
          <v-client-table :data="tableData" :options="options" :columns="columns">
            <template slot="data.id" slot-scope="props">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group dropup" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-grip-horizontal"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button type="button" @click="partidas(props.row.data)" class="dropdown-item">
                                <i class="fas fa-eye"></i>&nbsp; Detalle
                            </button>
                        </div>
                    </div>
                </div>
            </template>
            <template slot="data.moneda" slot-scope="props">
              <template v-if="props.row.data.moneda == 1">
                <span class="btn btn-outline-success">USD</span>
              </template>
              <template v-if="props.row.data.moneda == 2">
                <span class="btn btn-outline-success">MXN</span>
              </template>
              <template v-if="props.row.data.moneda == 3">
                <span class="btn btn-outline-success">EUR</span>
              </template>
            </template>
            <template slot="porcentaje" slot-scope="props">
                <span class="btn btn-primary">{{props.row.porcentaje}}</span>
            </template>

          </v-client-table>
        </div>
      </div>

      <div class="card" v-if="detalle">
        <div class="card-header">
          {{data_detalle.folio}}
          <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">

          <v-client-table :data="tableDataDetalle" :columns="columnsDetalle" :options="optionsdetalle">
            <template slot="id" slot-scope="props">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group dropup" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-grip-horizontal"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button v-show="props.row.asociada == 0"  type="button" @click="asociar(props.row)" class="dropdown-item">
                                <i class="fas fa-eye"></i>&nbsp; Asociar
                            </button>
                        </div>
                    </div>
                </div>
            </template>
            <template slot="asociada" slot-scope="props">
              <template v-if="props.row.asociada == 0">
                <span class="btn btn-outline-danger">NO</span>
              </template>
              <template v-if="props.row.asociada == 1">
                <span class="btn btn-outline-success">SI</span>
              </template>
            </template>
          </v-client-table>
        </div>
      </div>

      <div class="modal fade" tabindex="-1" :class="{ mostrar: modal }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div>
              <div class="modal-header">
                <h4 class="modal-title">Asociar</h4>
                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-row">
                  <div class="col-md-12 mb-3">
                    <b>Articulo :</b> {{data_detalle_partida === '' ? '' : data_detalle_partida.ad}}
                  </div>
                </div>
                <v-client-table :data="tableDataPartidas" :columns="columnsPartidas" :options="optionsPartidas">
                  <template slot="id" slot-scope="props">
                    <button type="button" @click="asociarPartida(props.row, data_detalle_partida)" class="btn btn-success">
                        <i class="fas fa-check"></i>&nbsp; Asociar
                    </button>
                  </template>
                </v-client-table>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" @click="cerrarModal()">
                  <i class="fas fa-window-close"></i>&nbsp;Cerrar
                </button>
                <button type="button" class="btn btn-secondary" @click="GuardarAct()">
                  Actualizar
                </button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal agregar documentos-->

    </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default{
  data(){
    return {
      detalle : false,
      modal : 0,

      listaProyectos : [],

      proyecto_id : '',
      data_detalle : '',
      data_detalle_partida : '',

      tableData : [],
      columns : ['data.id','data.folio','data.total','data.moneda','data.fecha_orden','data.razon_social','porcentaje'],
      options: {
        headings: {
            'data.id' :'Acciones',
            'data.folio' :'Folio',
            'data.total' :'Total',
            'data.moneda' :'Moneda',
            'data.fecha_orden' :'Fecha',
          'data.razon_social' :'Razón social',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },

      tableDataDetalle : [],
      columnsDetalle : ['id','ad','comentario','cantidad','asociada'],
      optionsdetalle: {
        headings: {
          'id' : 'Acciones',
          'ad' : 'Descripción',
          'asociada' : 'Asociado',
        },
        perPage: 10,
        perPageValues: [],
        // sortable: ['id','ad','comentario','cantidad'],
        // filterable: ['ad','comentario','cantidad'],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },

      tableDataPartidas : [],
      columnsPartidas : ['id','descripcion','cantidad','claveprodserv','claveunidad'],
      optionsPartidas: {
        headings: {
          'id' : 'Acciones',
          'claveprodserv' : 'Clave',
          'claveunidad' : 'unidad',
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
    getData(){
      axios.get('get/proyectos/xml').then(response => {
        this.listaProyectos = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    getProyecto(){
      axios.get('get/ocs/xml/' + this.proyecto_id.id).then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    maestro(){
      this.detalle = false;
    },

    partidas(data){
      this.detalle = true;
      this.data_detalle = data;

      axios.get('get/partidas/oc/xml/' + data.id).then(response =>{
        this.tableDataDetalle = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    asociar(data){
      this.data_detalle_partida = data;
      axios.get('get/conceptos/facturas/xml/' + this.data_detalle.id).then(response => {
        this.tableDataPartidas = response.data;
      }).catch(e => {
        console.error(e);
      });
      this.modal = 1;
    },

    cerrarModal(){
      this.modal = 0;
    },

    asociarPartida(data, partida){
      Swal.fire({
        title: 'Esta seguro(a) de Asociar ?',
        text: "Esta opción no se podrá desahacer!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText : 'No',
      }).then(result => {
        if (result.value) {
          axios.post('asiociar/partida/oc/xml',{
            partida : partida.id,
            concepto_xml : data.id,
          }).then(response => {
            toastr.success('Correcto !!!');
            this.partidas(this.data_detalle);
            this.asociar(this.data_detalle_partida);
            this.cerrarModal();
          }).catch(e => {
            console.error(e);
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
