<template>
  <div>
  <div class="card border-dark">

    <div class="card-header bg-dark text-white">
      <i class="fa fa-align-justify"> </i> Compras por agendar :
      <button v-if="detalles_ver" type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
        <i class="icon-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <vue-element-loading :active="isLoading"/>
    <v-client-table :data="solicitudes" :options="options" :columns="columns" v-show="!detalle">
      <template slot="id" slot-scope="props">
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
          <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-grip-horizontal"></i> Acciones
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
              <template >
                <a class="dropdown-item" @click="cargardetalle(props.row.id)" href="#">
                  <i class="fas fa-eye"></i>&nbsp;Detalles
                </a>
              </template>
            </div>
          </div>
        </div>
      </template>
      <template slot="moneda" slot-scope="props">
        <template v-if="props.row.moneda == 1">
          <span class="btn btn-outline-success">USD</span>
        </template>
        <template v-if="props.row.moneda == 2">
          <span class="btn btn-outline-success">MXN</span>
        </template>
        <template v-if="props.row.moneda == 3">
          <span class="btn btn-outline-success">EUR</span>
        </template>
      </template>
    </v-client-table>

    <div v-show="detalles_ver">
      <table class="VueTables__table table table-hover table-sm" >
        <!-- <vue-element-loading :active="isLoading"/> -->
        <thead class="table-light">
          <tr>
            <th scope="col">Autorizar</th>
            <th scope="col">Proveedor</th>
            <th scope="col">Folio Compra</th>
            <th scope="col">Proyecto</th>

          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1"  type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button" class="dropdown-item"  @click="autorizar(1, detalles_solicitudes.PagosNoRecurrentes)">
                      <i class="fas fa-check"></i>&nbsp;Autorizar.
                    </button>
                    <button type="button" class="dropdown-item" @click="autorizar(0, detalles_solicitudes.PagosNoRecurrentes)">
                      <i class="fas fa-close"></i>&nbsp;No Autorizar.
                    </button>
                    <button type="button" class="dropdown-item" @click="guardarFechaPrioridad(detalles_solicitudes.PagosNoRecurrentes)">
                      <i class="icon-pencil"></i>&nbsp;Actualizar
                    </button>
                  </div>
                </div>
              </div>
            </td>
            <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.PagosNoRecurrentes.razon_social}}</td>
            <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.PagosNoRecurrentes.folio}}</td>
            <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.PagosNoRecurrentes.nombre_corto}}</td>
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
            <th scope="col">Moneda</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.PagosNoRecurrentes.nombre_condicion_pago}}</td>
            <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.PagosNoRecurrentes.rango_dias}}</td>
            <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.PagosNoRecurrentes.eventos}}</td>
            <td>{{detalles_solicitudes == null ? '' : detalles_solicitudes.PagosNoRecurrentes.monto}}</td>
            <td>{{detalles_solicitudes == null ? '' : (detalles_solicitudes.PagosNoRecurrentes.moneda == 1 ? 'USD' : detalles_solicitudes.PagosNoRecurrentes.moneda == 2 ? 'MNX' : detalles_solicitudes.PagosNoRecurrentes.moneda == 3 ? 'EUR' : '')}}</td>
          </tr>
        </tbody>
      </table>
      <hr>
      <v-client-table :columns="columnsdescuento" :data="tableDataPartidasCompras" :options="optionsdescuento" ref="myTabledescuento">

      </v-client-table>
    </div>
  </div>

  <!--Inicio del modal agregar/actualizar-->
  <div class="modal" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dark" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Actualizar Datos</h4>
          <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <div class="col-md-6">
              <label>Fecha probable de pago</label>
              <input type="date" class="form-control" v-validate="'required'" v-model="compras.fecha_probable_pago" data-vv-name="fecha_problable_pago">
              <span class="text-danger">{{errors.first('fecha_problable_pago')}}</span>
            </div>
          </div>
          <div class="form-row">
            <div class="col-md-6">
              <label>Urgente</label>
              <select class="form-control" v-validate="'required'" v-model="compras.prioridad" data-vv-name="prioridad">
                <option value="0">NO</option>
                <option value="1">SI</option>
              </select>
              <span class="text-danger">{{errors.first('prioridad')}}</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
          <button type="button" class="btn btn-primary" @click="guardar()">Actualizar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal-->
</div>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data() {
    return {
      modal : 0,
      isLoading: false,
      solicitudes: [],
      detalle: false,
      columnsdescuento: ['articulo_descripcion','nombre_corto','cantidad','precio_unitario'],
      columns : ['id','razon_social','folio','nombre_corto','total','moneda'],
      tableDataPartidasCompras: [],
      solicitud: [],
      detalles_ver : false,
      detalles_solicitudes : null,
      compras : {
        id : '',
        fecha_probable_pago : '',
        prioridad : '',
        compra_id : '',
      },
      optionsdescuento: {
        headings: {
          'articulo_descripcion' : 'Artículo',
          'proyecto_nombre' : 'Proyecto',
          'cantidad': 'Cantidad',
          'precio_unitario': 'Precio Unitario',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts,
      },
      options:{
        headings: {
          'id' : 'Acciones',
          'razon_social' : 'Razón social',
          'folio': 'Folio',
          'nombre_corto': 'Nombre Proyecto',
          'total': 'Total',
          'moneda': 'Moneda'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts,
        listColumns: {
          moneda: [{
            id: 1,
            text: 'Dolares'
          },
          {
            id: 2,
            text: 'Moneda Nacional'
          },
          {
            id: 3,
            text: 'Euros'
          }
        ]
      }
    }
  }
},
methods: {
  getData() {
    this.isLoading = true;
    let me=this;
    axios.get('/compraporautorizar').then(response => {
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
  cargardetalle(id)
  {
    this.compras.id = id;
    this.detalles_ver = true;
    this.detalle = true;
    let me = this;
    axios.get('/compraporautorizarid/'+id).then(response => {
      me.detalles_solicitudes = response.data[0];
      me.tableDataPartidasCompras = response.data[0].partidacompra;

    }).catch(function (error){
      console.error(error);
    });
  },
  autorizar(edo ,data)
  {
    if ((data.fecha_probable_pago == null || data.fecha_probable_pago == '') && edo == 1) {
      toastr.warning('No se puede autorizar, defina una fecha problable de pago');
    }else {
      this.isLoading = true;
      let me = this;
      axios.post('/autorizarcompra', {
        id: data.id,
        edo : edo,
      })
      .then(function (response) {
        me.isLoading = false;
        toastr.success('Autorizado correctamente');
        me.maestro();
        me.getData();
      })
      .catch(function (error) {
        console.log(error);
      });
    }


  },
  maestro(){
    this.detalles_ver = false;
    this.detalle = false;
    this.detalles_solicitudes = null;
  },

  guardarFechaPrioridad(data){
    this.modal = 1;
    this.compras.fecha_probable_pago = data.fecha_probable_pago;
    this.compras.prioridad = data.prioridad;
    this.compras.compra_id = data.ordenes_comp_id;
  },

  cerrarModal(){
    this.modal = 0;
  },

  guardar(){
    this.$validator.validate().then(result => {
      if (result) {
        axios.put('actualizarcompra/fp',{
          'compra_id' : this.compras.compra_id,
          'fecha_probable_pago' : this.compras.fecha_probable_pago,
          'prioridad' : this.compras.prioridad,
        }).then(response => {
          if (response.data.status === 'error') {
            swal({
              type : 'error',
              html : 'Ha ocurrido un error notifiqué al administrador y recargue la página'
            })
          }else {
            toastr.success('Compra actualizada correctamente');
            this.cargardetalle(this.compras.id);
            this.compras.fecha_probable_pago = "";
            this.compras.prioridad = "";
            this.compras.compra_id = "";
            this.cerrarModal();
          }
        }).catch(error => {
          console.error(error);
        });
      }
    })
  }
},
mounted() {
}
}
</script>
