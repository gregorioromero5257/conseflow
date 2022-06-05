<template >
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> &nbsp;Detalles del traspaso
        <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
          <i class="fa fa-arrow-left"></i>&nbsp;Atras
        </button>
      </div>
    </div>

    <div class="card" v-show="detalles == null ? '': detalles.estado == 1">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> &nbsp;Origen : {{ detalles == null ? '' : detalles.origen}}
      </div>
      <div class="card-body">

        <vue-element-loading :active="isLoading"/>
        <h5>Seleccione la fila del artículo a traspasar:</h5>
        <v-client-table ref="myTable" :data="dataTableArticulo" :columns="columnsarticulos" :options="optionsaarticulos" @row-click="seleccionarArticulo">
        </v-client-table>

      </div>
    </div>



    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> &nbsp;Destino : {{detalles == null ? '' : detalles.destino}}
      </div>
      <div class="card-body">
        <vue-element-loading :active="isLoading"/>
        <v-client-table :columns="columns" :data="data" :options="options" ref="myTable">
          <template slot="id" slot-scope="props" >
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" v-bind:disabled="detalles == null ? '' : detalles.estado > 1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button  type="button" @click="abrirModalA('articulo','corregir',props.row)" class="dropdown-item">
                    <i class="icon-pencil"></i>&nbsp;Corregir cantidad
                  </button>
                  <button  type="button" @click="eliminar(props.row)" class="dropdown-item">
                    <i class="fas fa-trash"></i>&nbsp;Eliminar
                  </button>
                </div>
              </div>
            </div>
          </template>
        </v-client-table>

      </div>

    </div>

    <!--Inicio del modal agregar/actualizar articulos-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modala}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <vue-element-loading :active="isLoading"/>
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div>
            <div class="modal-header">
              <h4 class="modal-title">&nbsp;Agregar la cantidad a trapasar</h4>
              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- <v-client-table ref="myTable" :data="dataTableArticulo" :columns="columnsarticulos" :options="optionsaarticulos" @row-click="seleccionarArticulo">
            </v-client-table> -->

            <div class="form-group">
              <label for="inputArticulo" >Artículo</label>
              <input type="text" class="form-control col-md-8"  v-model="traspasosdetalle.articulo" placeholder="Articulo" readonly>
            </div>

            <div class="form-group">
              <label for="inputCantidadExistente">Cantidad existente</label>
              <input type="text" name="cantidad_existente"  v-model="traspasosdetalle.cantidad_existente" class="form-control col-md-8"
              readonly>
            </div>

            <div class="form-group">
              <label for="inputCantidad">Cantidad nueva</label>
              <input type="text" data-vv-name="cantidad" v-validate="'required|decimal:2'" v-model="traspasosdetalle.cantidad"
              v-bind:class="'form-control '+clasescantidad+ ' col-md-8'" @blur="validacioncantidad" placeholder="Cantidad" autocomplete="off">
              <span class="text-danger">{{ errors.first('cantidad') }}</span>
            </div>
            <div v-show="!corregir">
              <div class="form-group">
                <label for="Calidad">Calidad</label>
                <select class="form-control col-md-8" name="tipo_calidad_id" data-vv-name="tipo_calidad_id" v-model="traspasosdetalle.tipo_calidad_id" >
                  <option v-for="item in listatipoCalidad" :value="item.id" >{{ item.nombre }}&nbsp; {{item.descripcion}}</option>
                </select>
                <span class="text-danger">{{ errors.first('tipo_calidad_id') }}</span>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="validacioncantidaduno(); guardarPT();"><i class="fas fa-save"></i>&nbsp;Agregar</button>
            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="validacioncantidaduno(); guardarActualizacion();"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal agregar articulo-->

</div>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      detalles: null,
      validado : 0,
      corregir :  false,
      listatipoCalidad : [],
      isLoading : false,
      tipoAccion : 0,
      columns : ['id','cantidad','nombre_stock','anombre','acodigo','adescripcion','amarca','aunidad'],
      data : [],
      dataTableArticulo : [],
      columnsarticulos : ['cantidad','lote_nombre','nombre_stock','anombre','acodigo','adescripcion','amarca','aunidad','nombre_ubicacion'],
      modala : 0,
      clasescantidad : '',
      traspasosdetalle : {
        traspaso_id : 0,
        lote_almacen_id : 0,
        articulo : '',
        cantidad_existente : '',
        cantidad : '',
        tipo_calidad_id : 1,
        id: 0,
      },
      optionsaarticulos: {
        headings: {
          'anombre': 'Nombre',
          'acodigo': 'Codigo',
          'adescripcion' : 'Descripción',
          'amarca' : 'Marca',
          'aunidad' : 'Unidad',
          'lote_nombre' : 'Lote',
          'nombre_stock' : 'Stock',
          'nombre_ubicacion' : 'Ubicación'
        },
        perPage: 6,
        groupBy:['nombre_almacen'],
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
      options: {
        headings: {
          'id' : 'Acciones',
          'anombre': 'Nombre',
          'acodigo': 'Codigo',
          'adescripcion' : 'Descripción',
          'amarca' : 'Marca',
          'aunidad' : 'Unidad',
          'nombre_stock' : 'Stock',
        },
        perPage: 6,
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
      let me = this;
      axios.get('/tipocalidad').then(response => {
        me.listatipoCalidad = response.data;
      }).catch(function (error){
        console.error(error);
      })
      axios.get('/ubicacion').then(response => {
        me.ubicacion = response.data;
      }).catch(function (error) {
        console.error(error);
      });
    },

    cargardetalle(data){
      this.detalles = data;
      this.isLoading = true;
      let me= this;
      axios.get('/stockubicacion/' + this.detalles.stock_id).then(response => {
        me.dataTableArticulo =  response.data;
        me.isLoading = false;
      }).catch(function (error){
        console.error(error);
      });
      axios.get('/partidatraspasos/'+me.detalles.id).then(response => {
        me.data = response.data;
        me.isLoading = false;
      }).catch(function (error){
        console.error(error);
      });
    },

    maestro(){
      this.$emit('detalle:click');
    },

    abrirModalA(modelo , accion, data = []){
      switch(modelo){
        case "articulo":
        {
          switch(accion){
            case 'registrar':
            {
              this.isLoading = true;
              let me= this;
              this.modala = 1;
              axios.get('/stockubicacion/' + this.detalles.stock_id).then(response => {
                me.dataTableArticulo =  response.data;
                me.isLoading = false;
              }).catch(function (error){
                console.error(error);
              });
              break;
            }
            case 'corregir':
            {
              this.modala = 1;
              this.corregir = true;
              this.tipoAccion = 2;
              this.traspasosdetalle.id = data['id'];
              this.traspasosdetalle.cantidad_existente = data['cantidad'];
              this.traspasosdetalle.articulo = data['adescripcion'];
              break;
            }
          }
        }
      }
    },

    seleccionarArticulo(data){
      this.modala = 1;
      this.traspasosdetalle.traspaso_id = this.detalles.id;
      this.traspasosdetalle.lote_almacen_id = data.row.id;
      this.traspasosdetalle.articulo = data.row.adescripcion;
      this.traspasosdetalle.cantidad_existente = data.row.cantidad;
      this.corregir = false;
      this.tipoAccion = 1;
    },

    validacioncantidaduno(){
      if (this.traspasosdetalle.cantidad == '') {
        swal('Error!','Complete los campos','warning' )
        this.validado = 0;
        this.clasescantidad = ' is-invalid';
      }
      else {
        this.validado = 1;
      }
    },

    validacioncantidad(){
      if (parseFloat(this.traspasosdetalle.cantidad) > parseFloat(this.traspasosdetalle.cantidad_existente)) {
        swal('Error!','No se puede pedir una cantidad mayor a la existente!','warning' )
        this.traspasosdetalle.cantidad = '';
        this.validado = 0;
      }
      else {
        this.validado = 1;
      }
    },

    guardarPT(){
      if (this.validado == 1) {
        let me = this;
        me.isLoading = true;
        axios.post('/partidatraspasos', {
          lote_almacen_id : this.traspasosdetalle.lote_almacen_id,
          cantidad : this.traspasosdetalle.cantidad,
          traspaso_id: this.traspasosdetalle.traspaso_id,
          tipo_calidad_id : this.traspasosdetalle.tipo_calidad_id,
        }).then(response => {
          toastr.success('Correcto', 'Partida agregada existosamente!!!');
          me.cargardetalle(me.detalles);
          me.cerrarModal();
          me.isLoading = false;
        }).catch(function (error) {
          console.error(error);
        });
      }
    },

    guardarActualizacion(){
      if (this.validado == 1) {
        let me = this;
        me.isLoading = true;
        axios.post('/corregirpartidatraspaso',{
          id : this.traspasosdetalle.id,
          cantidad: this.traspasosdetalle.cantidad,
          cantidad_existente : this.traspasosdetalle.cantidad_existente,
        }).then(response => {
          toastr.success('Correcto', 'Partida corregida existosamente!!!');
          me.cargardetalle(me.detalles);
          me.cerrarModal();
          me.isLoading = false;
        }).catch(function (error){
          console.error(error);
        });
      }
    },

    eliminar(data){
      let me = this;
      me.isLoading = true;
      axios.get('/eliminarpartidatraspaso/' + data.id).then(response => {
        toastr.warning('Correcto', 'Partida eliminada correctamente');
        me.cargardetalle(me.detalles);
        me.isLoading = false;
      }).catch(error => {
        console.error(error);
      });
    },

    cerrarModal(){
      this.modala = 0;
      this.traspasosdetalle.cantidad = '';
    },

    cancelar(){
      this.tipoAccion = 0;
    }

  },
  mounted(){
    this.getData();
  }
}
</script>
