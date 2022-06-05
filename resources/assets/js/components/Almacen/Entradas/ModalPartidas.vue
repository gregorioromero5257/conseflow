<template>
  <div>
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Agregar partidas de OC {{orden_compra.folio}}</h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id">
            <div class="modal-body">
              <v-server-table ref="myTable" :url="'entradas/partidas/oc/'+orden_compra.id" :columns="columns" :options="options">
                <template slot="cantidad_entrada" slot-scope="props">
                  <input type="text" v-validate="'decimal:2'" width="140px" class="form-control" @keyup.enter="guardarCantidad(props, $event)" data-vv-name="cantidad">
                  <span class="text-danger">{{errors.first('cantidad')}}</span>
                </template>
                <template slot="almacen" slot-scope="props">
                  <button type="button" class="btn btn-primary btn-sm" @click="registrarAlmacen(props)">
                    <i class="fas fa-boxes"></i>&nbsp;Almacenar En ...</button>
                  </template>
                </v-server-table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            </div>
          </div>
        </div>
      </div>
      <div v-show="modales == 1">
        <modalalmacenes ref="modalalmacenes" @almacen="getDatosAlmacen($event)" @atras:modalalmacenes="cerrarmodalalmacenes()"></modalalmacenes>
      </div>
    </div>

  </template>
  <script>
  import Utilerias from '../../Herramientas/utilerias.js';
  var config = require('../../Herramientas/config-vuetables-client').call(this);
  const ModalAlmacen = r => require.ensure([], () => r(require('./ModalAlmacen.vue')), 'alm');

  export default {
    data() {
      return {
        nuevo : 1,
        modales : 0,
        modal : 0,
        orden_compra : '',
        columns: [ 'descripcion', 'unidad','cantidad','cantidad_entrada','almacen'],
        options: {
          headings: {
            'descripcion': 'Descripción artículo',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
           sortable: ['descripcion', 'unidad','cantidad'],
           filterable: ['descripcion', 'unidad','cantidad'],
          filterByColumn: true,
          texts: config.texts
        },
        almacenes : [],
        entrada_id : '',
        almacen :{
          id : '',
          stand_id : '',
          nivel_id : '',
        },
        parentrada : {
          id : '',
        }
      }
    },
    components: {
      'modalalmacenes': ModalAlmacen,
    },
    methods : {
      /**
      *
      */
      cargar(data,oc,almacenes){
        this.modal = 1;
        this.entrada_id = data;
        this.orden_compra = oc;
        this.almacenes = almacenes;
      },

      guardarCantidad(row, data){
        let id = row.row.id;
        let cantidad =  data.target.value;
        let articulo_id = row.row.articulo_id;
        // if (parseFloat(data.target.value) > parseFloat(row.row.cantidad) ) {
        //   toastr.warning('Se esta registrando una cantidad mayor a la compra');
        // }
        // else
         if (this.almacen.id === '') {
          toastr.warning('Defina un almacen para continuar');
        }
        else {

        let me = this;

        axios({
          method: this.nuevo ? 'POST' : 'PUT',
          url: this.nuevo ? '/partidaentrada' : `partidaentrada/update/factura/${this.parentrada.id}`,
          data: {
            'entrada_id': this.entrada_id,
            'req_com_id': id,
            'cantidad': cantidad,
            'id': this.parentrada.id,
            'articulo_id' : articulo_id,
            'validacion_calidad' : 0,
            'almacene_id' : this.almacen.id,
            'nivel_id' : this.almacen.stand_id,
            'stand_id' : this.almacen.nivel_id,
            // 'row' : row,
          }
        }).then(function (response) {
          if (response.data.status) {
            me.finalizaPartidaEntrada();
            toastr.success("Guardado Correctamente");
            me.$refs.myTable.refresh();
            // $('#input_number').html('');
            // me.getArticulosEntrada();
          } else {
            swal(
              'Error!',
              '!',
              'warning'
            )
          }
        }).catch(function (error) {
          console.log(error);
        });
      }
      },

      registrarAlmacen(data){
        this.modales = 1;
        var childalmacenes = this.$refs.modalalmacenes;
        childalmacenes.cargar(this.entrada_id, this.almacenes, 1);
      },

      cerrarmodalalmacenes(){
        this.modales = 0;
      },

      cerrarModal(){
        this.modales = 1;
        this.$emit('atras:modalpartidas');
      },

      getDatosAlmacen(data){
        this.modales = 1;
        this.almacen.id = data.id;
        this.almacen.stand_id = data.stand_id;
        this.almacen.nivel_id = data.nivel_id;
      },

      finalizaPartidaEntrada(){
        this.almacen.id = '';
        this.almacen.nivel_id = '';
        this.almacen.stand_id = '';
        // Utilerias.resetObject(this.almacen);
      }


    }
  }
  </script>
