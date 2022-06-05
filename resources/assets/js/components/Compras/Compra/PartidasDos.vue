<template>

  <!-- Listado de partidas compras -->
  <div class="card" v-if="mostrar == 1">
    <vue-element-loading :active="isLoadingDetalle"/>
    <div class="card-header">
      <i class="fa fa-align-justify"></i> Detalles de la compra con folio:  {{ empleado == null ? '':  empleado.folio }}
      <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
        <i class="fas fa-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <div class="card-body">
      <v-client-table :columns="columnscompras" :data="tableDataPartidasCompras" :options="optionscompras" ref="myTabledescuento">
        <template slot="requisicione_id" slot-scope="props">
          <button hidden type="button" class="btn btn-danger btn-sm" >
            <i class="icon-trash"></i>
          </button>
        </template>

        <template slot="id" slot-scope="props" >
          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1"  type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-grip-horizontal"></i>
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <button type="button" class="dropdown-item" @click="eliminarpartidacompra(props.row, 0)">
                  <i class="icon-trash"></i>&nbsp;Eliminar.
                </button>
                <button type="button"  class="dropdown-item" @click="actualizarpartidacomprauno(props.row, 0)">
                  <i class="icon-trash"></i>&nbsp;Actualizar Partida.
                </button>
                <!-- <template v-if="empleado.nombre_categoria === 'Proyectos' || empleado.nombre_categoria === 'Servicios'">
                <button type="button" class="dropdown-item" @click="actualizarpartidacompra(props.row, 0)">
                <i class="icon-pencil"></i>&nbsp;Actualizar Centro Costo.
              </button>
            </template> -->
          </div>
        </div>
      </div>
    </template>

    <template slot="ad" slot-scope="props">
      {{props.row.ad}} {{props.row.comentario == null ? '' : props.row.comentario}}
    </template>


  </v-client-table>
</div>
<!-- Nuevo partidas compras -->
<div class="card" ref="formLote">
  <vue-element-loading :active="isLoadingDetalle"/>
  <div class="card-header">
  </div>
  <div class="card-body">

    <form>

      <div class="form-row">
        <div class="form-group col-md-8">
          <label>Artículo</label>
          <div class="input-group">
            <textarea name="name" v-model="partidascompras.nombrearti" v-bind:class="'form-control'+clases.nombrearti" rows="2" cols="80" readonly></textarea>
            <div class="input-group-append">
              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Opciones
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <button class="dropdown-item btn btn-secondary" type="button" @click="abrirModalA('articulo','registrar',partidascompras.orden_compra_id)">Buscar Requisición</button>
                  <button class="dropdown-item btn btn-secondary" type="button" @click="abrirModalA('articulo','agregar',partidascompras.orden_compra_id)">Agregar Articulo/Servicio</button>
                  <button class="dropdown-item btn btn-success" v-show="verequivalente" type="button" @click="comprarEquivalente()" ><i class="fas fa-exchange-alt"></i>&nbsp;Cambiar</button>
                  <button class="dropdown-item btn btn-secondary" type="button" @click="agregarArt()">Agregar Concepto</button>
                  <button class="dropdown-item btn btn-secondary" type="button" @click="abrirModalA('articulo','apartados',partidascompras.orden_compra_id)">Apartados</button>
                </div>
              </div>
            </div>
          </div>
          <articulo ref="articulo"></articulo>
        </div>
      </div>


      <div class="form-row">
        <div class="form-group col-md-8">
          <label for="comentario">Comentario de Proveedor:</label>
          <textarea class="form-control"
           title="Texto que aparecera en el formato!"
           v-model="partidascompras.comentario" autocomplete="off" id="comentario" rows="3" @blur="ActivarTool"></textarea>
        </div>
      </div>



      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="validationDefaultUsername">Precio Unitario</label>
          <div class="input-group">
            <div class="input-group-addon">
              <span class="input-group-text">$</span>
            </div>
            <input type="text" v-bind:disabled="desabilitar_precios" v-validate="'decimal:2'"  min="0" pattern="^[0-9]+" v-model="partidascompras.precio_unitario"
            v-bind:class="'form-control'+clases.precio_unitario" placeholder="Precio unitario" autocomplete="off" id="precio_unitario" data-vv-name="precio_unitario">
          </div>
          <span class="text-danger">{{ errors.first('precio_unitario') }}</span>
        </div>
        <div class="form-group col-md-4">
          <label for="validationDefaultUsername">Adicionales</label>
          <div class="input-group">
            <div class="input-group-addon">
              <span class="input-group-text">$</span>
            </div>
            <input type="text" v-bind:disabled="desabilitar_precios" v-validate="'decimal:2'" min="0" pattern="^[0-9]+" v-model="partidascompras.adicionales"
            v-bind:class="'form-control'+clases.precio_unitario" placeholder="Adicionales" autocomplete="off" id="adicionales" data-vv-name="adicionales">
          </div>
          <span class="text-danger">{{ errors.first('adicionales') }}</span>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label>Cantidad requerida</label>
          <input type="text" class="form-control" v-model="partidascompras.cantidad_real"  disabled>
        </div>
        <div class="form-group col-md-4">
          <label for="validationDefaultUsername">Cantidad real comprada</label>
          <input type="text" v-bind:disabled="desabilitar_precios" v-validate="'decimal:2'" min="0" pattern="^[0-9]+" v-model="partidascompras.cantidad"
          v-bind:class="'form-control'+clases.cantidad" placeholder="Cantidad real a comprar" autocomplete="off" id="cantidad" data-vv-name="cantidad">
          <span class="text-danger">{{ errors.first('cantidad') }}</span>
        </div>
      </div>

    </form>
  </div>
  <div class="card-footer">
    <button type="button" class="btn btn-secondary" @click="cancelar()">Cancelar</button>
    <button type="button" v-if="tipoAccionpr==1" class="btn btn-secondary" @click="validardetalle(); guardarPR(1, partidascompras.orden_compra_id);"><i class="fas fa-save"></i>&nbsp;Agregar</button>
    <!-- <button type="button" v-if="boton_centro_costo == 1" class="btn btn-secondary" @click="guardarCC();"><i class="fas fa-save"></i>&nbsp;Actualizar Centro Costos</button> -->
    <button type="button" v-if="boton_actualizar_partida == 1" class="btn btn-secondary" @click="guardarAP();">
      <i class="fas fa-save"></i>&nbsp;Actualizar Partida</button>
      <button type="button" v-if="boton_apartados == 1" class="btn btn-secondary" @click="validardetalle(); guardarPA(1, partidascompras.orden_compra_id);">
        <i class="fas fa-save"></i>&nbsp;Agregar</button>
      </div>
    </div>
    <!-- Fin  de Nuevo y editar detalle requisicion correspondiente ala tabla partidas_requisiciones -->
    <!--Inicio del modal agregar articulos-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modala}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div>
            <vue-element-loading :active="isLoadingDetalle"/>
            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModala"></h4>
              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id">
              <div class="modal-body">
                <v-client-table ref="myTable" :data="dataTableArticulo"  :columns="columnsa" :options="optionsa" @row-click="seleccionarArticulo2">
                </v-client-table>
              </div>
            </div>
            <!-- url="/partidare" -->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal agregar articulo-->


    <!--*************************************************************-->

    <!--Inicio del modal agregar/actualizar articulos-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_agregar}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div>
            <div class="modal-header">
              <h4 class="modal-title">Seleccionar artículo/servicio</h4>
              <button type="button" class="close" @click="cerrarModalAgregar()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id">
              <div class="modal-body">
                <!-- ********************************************************************* -->
                <div class="accordion" id="accordiondos">

                  <div class="card">
                    <div class="card-header bg-dark justify-content-center" id="headingUno">
                      <h5 class="mb-0">
                        <button class="btn btn-dark collapsed" @click="cambiarEstado(1)">
                          <b> Articulos </b>
                        </button>
                        <button class="btn btn-dark collapsed" @click="cambiarEstado(2)">
                          <b> Servicios </b>
                        </button>
                      </h5>
                    </div>
                    <div v-show="estadomodal == 1" >
                      <v-server-table ref="myTableArticulo" url="/articulo/busqueda" :columns="columnsart" :options="optionsart" @row-click="seleccionarArticuloAgregar">
                        <template slot="calidad" slot-scope="props">
                          <template v-if="props.row.calidad_id != null">
                            <button type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="top" :title="props.row.descal" >
                              {{props.row.calidad}}
                            </button>
                          </template>
                        </template>
                        <template slot="child_row" slot-scope="props">
                          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-grip-horizontal"></i>&nbsp;Acciones
                              </button>
                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <button type="button" @click="abrirModal(props.row)" class="dropdown-item" >
                                  <i class="icon-pencil"></i>&nbsp;Actualizar Articulo
                                </button>
                              </div>
                            </div>
                          </div>
                        </template>
                      </v-server-table>
                    </div>
                    <div v-show="estadomodal == 2">
                      <v-server-table ref="myTable" url="/catservicio/busqueda" :columns="columnsserv" :options="optionsserv" @row-click="seleccionarServicioAgregar">
                      </v-server-table>
                    </div>
                  </div>

                </div>
                <!--*****************************************************************************++-->

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cerrarModalAgregar()">Cerrar</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal agregar articulo-->

    <!--**********************************************************************-->
    <!--Inicio del modal agregar/actualizar articulos-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalarticulos}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div>
            <!-- <vue-element-loading :active="isLoadingg"/> -->
            <div class="modal-header">
              <h4 class="modal-title">Seleccionar artículo equivalente</h4>
              <button type="button" class="close" @click="cerrarModalArticulos()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id">
              <div class="modal-body">

        <!-- ********************************************************************* -->
        <div class="accordion" id="accordiondos">

          <div class="card">
            <div class="card-header bg-dark justify-content-center" id="headingUno">
              <h5 class="mb-0">
                <button class="btn btn-dark collapsed" v-bind:disabled="partidascompras.articulo_id == null" type="button" data-toggle="collapse" data-target="#collapsecinco" aria-expanded="false" aria-controls="collapseTres">
                  <b> Articulos </b>
                </button>
                <button class="btn btn-dark collapsed" v-bind:disabled="partidascompras.servicio_id == null" type="button" data-toggle="collapse" data-target="#collapseseis" aria-expanded="false" aria-controls="collapseCuatro">
                  <b> Servicios </b>
                </button>
              </h5>
            </div>
            <div id="collapsecinco" class="collapse" aria-labelledby="headingUno" data-parent="#accordiondos">
              <v-server-table ref="myTable" url="/articulo/busqueda" :columns="columnsart" :options="optionsart" @row-click="seleccionarArticulosDos">
                <template slot="calidad" slot-scope="props">
                  <template v-if="props.row.calidad_id != null">
                    <button type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="top" :title="props.row.descal" >
                      {{props.row.calidad}}
                    </button>
                  </template>
                </template>
              </v-server-table>
            </div>
            <div id="collapseseis" class="collapse" aria-labelledby="headingDos" data-parent="#accordiondos">
              <v-server-table ref="myTable" url="/catservicio/busqueda" :columns="columnsserv" :options="optionsserv" @row-click="seleccionarServicio">
              </v-server-table>
            </div>
          </div>

        </div>
        <!--*****************************************************************************++-->

      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" @click="cerrarModalArticulos()">Cerrar</button>
    </div>
  </div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!--Fin del modal agregar articulo-->

</div>
<!-- Fin Listado de escolaridades del empleado -->

</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data() {
    return {
      tipo : 0,
      estadomodal : 0,
      boton_centro_costo : 0,
      boton_actualizar_partida : 0,
      boton_apartados : 0,
      desabilitar : false,
      verequivalente : false,
      guardarequivalente : false,
      desabilitar_precios : false,
      compra : {
        condicion : '',
      },
      partidascompras : {
        requisicione_id :0,
        orden_compra_id : 0,
        articulo_id :0,
        servicio_id : 0,
        articulo_uno_id : 0,
        servicio_uno_id : 0,
        nombrearti :'',
        nombrearti_uno : '',
        nombreserv_uno : '',
        cantidad : 0,
        precio_unitario : '',
        proyecto_id : 0,
        adicionales : 0,
        pda : '',
        cambio_equivalente : 0,
        comentario: '',
        cantidad_real : 0,
        centro_costo_id : '',
        id : '',


      },
      clases : {
        nombrearti : '',
        precio_unitario : '',
        cantidad : '',
      },
      tituloModala : '',
      empleado : null,
      tipoAccionpr : 0,
      detalle : 0,
      modala : 0,
      modal_agregar : 0,
      mostrar : 0,
      modalarticulos : 0,
      isLoadingDetalle : false,
      tableDataPartidasCompras: [],
      dataTableArticulo : [],//
      columnsart: ['codigo', 'nombre', 'descripcion', 'marca','calidad'],//
      columnsa: ['rf','descripciona','proyecton','rfs','cantidad_compra','unidad'],
      columnsserv: ['nombre_servicio', 'proveedor_marca', 'unidad_medida'],
      columnscompras: ['id','rf','rfs','ad','cantidad','precio_unitario'],//
      optionsart: {
        headings: {
          nombre: 'Nombre',
          descripcion: 'Descripción',
          marca: 'Marca',
          codigo: 'Codigo',
          id: 'Acción',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable : ['codigo', 'nombre', 'descripcion', 'marca'],
        filterable : ['codigo', 'nombre', 'descripcion', 'marca'],
        filterByColumn: true,
        texts:config.texts
      },
      optionsserv: {
        headings: {
          nombre_servicio: 'Nombre del Servicio',
          proveedor_marca: 'Proveedor/Marca',
          unidad_medida: 'Unidad de Medida',
          id: 'Acciones',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
      optionsa: {
        headings: {
          rf: 'Folio de requisición',
          descripciona : 'Descripcion Artículo',
          proyecton :'Proyecto',
          rfs : 'Fecha solicitud de la requisición',
          cantidad_compra : 'Cantidad'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        //  sortable: ['empleado.folio'],
        //  filterable: ['empleado.folio'],
        filterByColumn: true,
        texts:config.texts
      },
      optionscompras: {
        headings: {
          'id' : 'Acción',
          // 'requisicione_id' : '',
          'rf': 'Folio Requisición',
          'rfs': 'Fecha Solicitud Requisición',
          'proyecton': 'Proyecto',
          'ad': 'Artículo',
          'cantidad' : 'Cantidad'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['rf','rfs','proyecton','ad','cantidad','precio_unitario'],
        filterable: ['rf','rfs','proyecton','ad','cantidad','precio_unitario'],
        filterByColumn: true,
        listColumns: {
          condicion: [{
            id: 1,
            text: 'Activo'
          },
          {
            id: 0,
            text: 'Dado de Baja'
          }
        ]
      },
      texts:config.texts
    },
    centro_costo : [],

  }
},
components: {
  'articulo' : require('./Articulo.vue')
},
methods: {

  cargarpartidas(data){
    this.mostrar = 1;
    this.boton_centro_costo = 0;
    this.isLoadingDetalle = true;
    this.empleado = data;
    (data.condicion == 2) ? this.desabilitar = true : this.desabilitar = false;
    this.compra.condicion = data['condicion'];
    this.partidascompras.orden_compra_id = data.id;
    let me= this;
    axios.get( '/compras/' + data.id + '/' +'compras').then(response => {
      me.tableDataPartidasCompras = response.data;
      me.isLoadingDetalle = false;
    })
    .catch(function (error) {
      console.log(error);
    });
    axios.get(`/catalogos/centrocostos/`).then(response => {
      me.centro_costo = response.data;
    }).catch(error => {console.log(error)});
  },

  /**
  * [abrirModalA description]
  * @param  {String} modelo    [description]
  * @param  {String} accion    [description]
  * @param  {[Int]} id        [description]
  * @param  {Array}  [data=[]] [description]
  * @return {}           [description]
  */
  abrirModalA(modelo, accion, id ,data = []){
    // me.widgets.autorizarequisicion = true;

    switch(modelo){
      case "articulo":
      {
        switch(accion){
          case 'registrar':
          {
            this.modala = 1;
            this.tituloModala = 'Registrar artículo a la compra ';
            Utilerias.resetObject(this.partidascompras);
            Utilerias.resetObject(this.modala);
            this.tipoAccionpr = 1;
            this.partidascompras.orden_compra_id = id;
            this.getArticulosRequisicion();
            this.verequivalente = false;
            this.guardarequivalente = false;
            this.tipo = 0;
            break;
          }
          case 'agregar':
          {
            this.tipo = 1;
            this.modal_agregar = 1;
            Utilerias.resetObject(this.partidascompras);
            Utilerias.resetObject(this.modala);
            this.tipoAccionpr = 1;
            this.partidascompras.orden_compra_id = id;
            this.getArticulosRequisicion();
            this.verequivalente = false;
            this.guardarequivalente = false;
            this.$refs.myTableArticulo.refresh();
            break;
          }
          case 'apartados':
          {
            this.modala = 1;
            this.tituloModala = 'Registrar articulos apartados';
            Utilerias.resetObject(this.partidascompras);
            Utilerias.resetObject(this.modala);
            this.getArticulosRequisicionApartados();
            this.boton_apartados = 1;
            this.verequivalente = false;
            this.guardarequivalente = false;
            this.partidascompras.orden_compra_id = id;
            break;
          }
        }
      }
    }
  },

  /**
  * [getArticulosRequisicion Metodo de consultas a la BD]
  * @return {Response} [Objeto almacenado en dataTableArticulo]
  *
  */
  getArticulosRequisicion(){
    let me = this;
    // var url = '/partidare';
    // url = url + me.empleado.proyecto_id == null ? '' : '/'me.empleado.proyecto_id
    axios.get('/partidare/' + (me.empleado.proyecto_id == null ? '' : me.empleado.proyecto_id)).then(response => {
      me.dataTableArticulo= response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
  },

  getArticulosRequisicionApartados(){
    let me = this;
    // var url = '/partidare';
    // url = url + me.empleado.proyecto_id == null ? '' : '/'me.empleado.proyecto_id
    axios.get('/partidare/apartados/' + (me.empleado.proyecto_id == null ? '' : me.empleado.proyecto_id)).then(response => {
      me.dataTableArticulo= response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
  },

  /**
  * [seleccionarArticulo2 description]
  * @param  {Object} data [description]
  * @return {}      [description]
  */
  seleccionarArticulo2(data)
  {
    // console.log(data);
    let me = this;
    this.partidascompras.pda = data.row.pda;
    this.partidascompras.requisicione_id = data.row.rid;
    this.partidascompras.articulo_id = data.row.articulo_id;
    this.partidascompras.servicio_id = data.row.servicio_id;
    this.partidascompras.nombrearti = data.row.descripciona;
    this.partidascompras.comentario = data.row.descripciona;
    this.partidascompras.cantidad = data.row.cantidad_compra;
    this.partidascompras.cantidad_real = data.row.cantidad_compra;
    this.partidascompras.proyecto_id = data.row.proyectoi;
    if (data.row.equivalente == 1) {
      this.verequivalente = true;
    }
    else {
      this.verequivalente = true;
    }
    this.modala =0;
    this.isLoadingg = false;
    me.cerrarModal();
  },

  seleccionarArticulosDos(data){
    this.partidascompras.articulo_id = data.row.id;
    this.partidascompras.nombrearti = data.row.descripcion;
    if (this.partidascompras.articulo_id == this.partidascompras.articulo_uno_id) {
      toastr.warning('Atención','Seleccione un artículo equivalente diferente al original');
    }
    else {
      this.modalarticulos = 0;
      this.verequivalente = false;
      this.guardarequivalente = true;
      this.partidascompras.cambio_equivalente = 1;
    }
  },

  seleccionarServicio(data){
    this.partidascompras.servicio_id = data.row.id;
    this.partidascompras.nombrearti = data.row.nombre_servicio +' '+data.row.proveedor_marca;
    if (this.partidascompras.servicio_id == this.partidascompras.servicio_uno_id) {
      toastr.warning('Atención','Seleccione un servicio equivalente diferente al original');
    }
    else {
      this.modalarticulos = 0;
      this.verequivalente = false;
      this.guardarequivalente = true;
      this.partidascompras.cambio_equivalente = 1;

    }
  },

  cerrarModal(){
    this.modala = 0;
  },

  cerrarModalAgregar(){
    this.modal_agregar = 0;
  },

  maestro(){
    let me = this;
    me.mostrar = 0;
    this.$emit('partidas:click',me.empleado.proyecto_id);
    this.cancelar();
  },

  validardetalle(){
    if (this.partidascompras.nombrearti === '') {
      this.mensaje();
      this.validado = 0;
      this.clases.nombrearti = ' is-invalid';
    }
    else if (this.partidascompras.precio_unitario === '') {
      this.mensaje();
      this.validado = 0;
      this.clases.nombrearti = '';
      this.clases.precio_unitario = ' is-invalid';
    }
    else if (this.partidascompras.cantidad === '') {
      this.mensaje();
      this.validado = 0;
      this.clases.precio_unitario = '';
      this.clases.cantidad = ' is-invalid';
    }
    else {
      this.validado = 1;
      this.clases.cantidad = '';
    }
  },

  mensaje(){
    swal({
      title: 'Error complete todos los campos',
      type: 'warning',
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Aceptar!',
      confirmButtonClass: 'btn btn-succeguardarEquivalentess',
      buttonsStyling: false,
      reverseButtons: true
    })
  },

  /**
  * [guardarPR guarda una partida a la compra]
  * @param  {Int} nuevo [description]
  * @param  {Int} idr   [description]
  * @return {Response}       [status = true]
  */
  guardarPR(nuevo, idr){
    //  console.log(this.partidascompras);
    if (this.validado == 1) {
      this.isLoadingDetalle = true;
      this.detalle = 1;
      let me = this;
      axios({
        method: nuevo ? 'POST' : 'PUT',
        url: nuevo ? '/requisicioncompra' : '/requisicioncompra'+'/'+this.partidascompras.requisicione_id,
        data: {
          'requisicione_id': this.partidascompras.requisicione_id,
          'orden_compra_id': this.partidascompras.orden_compra_id,
          'articulo_id': this.partidascompras.articulo_id,
          'servicio_id' : this.partidascompras.servicio_id,
          'cantidad' : this.partidascompras.cantidad,
          'precio_unitario': this.partidascompras.precio_unitario,
          'proyecto_id' : this.partidascompras.proyecto_id,
          'adicionales' : this.partidascompras.adicionales,
          'articulo_uno_id' : this.partidascompras.articulo_uno_id,
          'servicio_uno_id': this.partidascompras.servicio_uno_id,
          'nombrearti_uno' : this.partidascompras.nombrearti_uno,
          'folio' : this.empleado.folio,
          'pda' : this.partidascompras.pda,
          'cambio_equivalente' : this.partidascompras.cambio_equivalente,
          'comentario' : this.partidascompras.comentario,
          'centro_costo_id' : this.partidascompras.centro_costo_id,
          'tipo' : this.tipo,
        }
      }).then(function (response) {
        me.isLoadingDetalle = false;
        if (response.data.status) {
          if (response.data.status === 'error') {
            swal({
              type: 'error',
              html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
            });
          }else {
            me.cargarpartidas(me.empleado);
            me.cerrarModal();
            me.cancelar();
            me.getArticulosRequisicion();
            me.tipo = 0;
            toastr.success('Correcto', 'Partida agregada a la compra correctamente');
          }
        }
        else {
          me.isLoadingDetalle = false;
          me.cerrarModal();
          me.cancelar();
          swal(
            'Error!',
            'No se pueden agregar articulos de diferentes proyectos!',
            'warning'
          )
        }
      }).catch(function (error) {
        me.cancelar();
        me.isLoadingDetalle = false;
      });
    }
  },


  /**
  * [guardarPR guarda una partida a la compra]
  * @param  {Int} nuevo [description]
  * @param  {Int} idr   [description]
  * @return {Response}       [status = true]
  */
  guardarPA(nuevo, idr){
    //  console.log(this.partidascompras);
    if (this.validado == 1) {
      this.isLoadingDetalle = true;
      this.detalle = 1;
      let me = this;
      axios({
        method: nuevo ? 'POST' : 'PUT',
        url: nuevo ? '/requisicioncompra/apartados' : '/requisicioncompra/apartados/'+this.partidascompras.requisicione_id,
        data: {
          'requisicione_id': this.partidascompras.requisicione_id,
          'orden_compra_id': this.partidascompras.orden_compra_id,
          'articulo_id': this.partidascompras.articulo_id,
          'servicio_id' : this.partidascompras.servicio_id,
          'cantidad' : this.partidascompras.cantidad,
          'precio_unitario': this.partidascompras.precio_unitario,
          'proyecto_id' : this.partidascompras.proyecto_id,
          'adicionales' : this.partidascompras.adicionales,
          'articulo_uno_id' : this.partidascompras.articulo_uno_id,
          'servicio_uno_id': this.partidascompras.servicio_uno_id,
          'nombrearti_uno' : this.partidascompras.nombrearti_uno,
          'folio' : this.empleado.folio,
          'pda' : this.partidascompras.pda,
          'cambio_equivalente' : this.partidascompras.cambio_equivalente,
          'comentario' : this.partidascompras.comentario,
          'centro_costo_id' : this.partidascompras.centro_costo_id,
          // 'tipo' : this.tipo,
        }
      }).then(function (response) {
        me.isLoadingDetalle = false;
        if (response.data.status) {
          if (response.data.status === 'error') {
            swal({
              type: 'error',
              html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
            });
          }else {
            me.cargarpartidas(me.empleado);
            me.cerrarModal();
            me.cancelar();
            me.getArticulosRequisicion();
            me.tipo = 0;
            toastr.success('Correcto', 'Partida agregada a la compra correctamente');
          }
        }
        else {
          me.isLoadingDetalle = false;
          me.cerrarModal();
          me.cancelar();
          swal(
            'Error!',
            'No se pueden agregar articulos de diferentes proyectos!',
            'warning'
          )
        }
      }).catch(function (error) {
        me.cancelar();
        me.isLoadingDetalle = false;
      });
    }
  },

  cancelar(){
    this.partidascompras.articulo_id = 0;
    this.partidascompras.servicio_id = 0;
    this.partidascompras.peso = 0;
    this.partidascompras.precio_unitario ="";
    this.partidascompras.cantidad = 0;
    this.partidascompras.fecha_requerido = "";
    this.partidascompras.nombrearti = "";
    this.tipoAccionpr =0;
    this.partidascompras.requisicione_id = 0;
    this.partidascompras.adicionales = 0;
    this.verequivalente = false;
    this.guardarequivalente = false;
    this.desabilitar_precios = false;
    this.partidascompras.cambio_equivalente = 0;
    this.partidascompras.comentario = "";
    this.partidascompras.centro_costo_id = "";
    this.boton_centro_costo = 0;
    this.boton_apartados = 0;
    this.boton_actualizar_partida = 0;
  },

  /**
  * [eliminarpartidacompra description]
  * @param  {[type]} id      [description]
  * @param  {[type]} idr     [description]
  * @param  {[type]} activar [description]
  * @return {[type]}         [description]
  */
  eliminarpartidacompra(data,activar){
    // console.log(data);
    if(!activar){
      this.swal_titulo = 'Esta seguro de remover este artículo de la compra?';
      this.swal_tle = 'Removido!';
      this.swal_msg = 'El registro ha sido removido con éxito.';
    }
    swal({
      title: this.swal_titulo,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Aceptar!',
      cancelButtonText: 'Cancelar',
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-danger',
      buttonsStyling: false,
      reverseButtons: true
    }).then((result) => {
      if (result.value) {
        let me = this;
        axios.get('/requisicioncompra/' + data['id'] +'/edit').then(function (response) {
        }).catch(function (error) {
          console.log(error);
        });
        //
        axios.get('/partidare/'+ data['articulo_id'] + '&' + data['requisicione_id'] + '&' + data['servicio_id'] +'/activ').then(function (response) {
          if(!activar){
            toastr.success('Eliminado Correctamente')
          }
          me.cargarpartidas(me.empleado);
          me.getArticulosRequisicion();
        }).catch(function (error) {
          console.log(error);
        });

      } else if (
        result.dismiss === swal.DismissReason.cancel
      ) {

      }
    })
  },

  comprarEquivalente() {
    let me = this;
    // me.isLoadingDetalle = true;
    toastr.warning('Atención','Seleccione el articulo/servicio equivalente');
    this.modalarticulos = 1;
    this.partidascompras.articulo_uno_id = this.partidascompras.articulo_id;
    this.partidascompras.nombrearti_uno = this.partidascompras.nombrearti;
    this.partidascompras.servicio_uno_id = this.partidascompras.servicio_id;
    this.desabilitar_precios = false;
    this.tipoAccionpr = 1;
  },

  //agragarsser vicio_id y servicio_id_uno y su description y ver como se guarda
  guardarEquivalente(){
    let me = this;
    me.isLoadingDetalle = true;
    // console.log(this.partidascompras);
    axios.post('/comprarequivalente', {
      requisicione_id : this.partidascompras.requisicione_id,
      articulo_id : this.partidascompras.articulo_id,
      servicio_id : this.partidascompras.servicio_id,
      articulo_uno_id : this.partidascompras.articulo_uno_id,
      servicio_uno_id: this.partidascompras.servicio_uno_id,
      nombrearti_uno : this.partidascompras.nombrearti_uno,
      folio : this.empleado.folio,
      pda : this.partidascompras.pda,
    }).then(response => {
      me.cancelar();
      me.isLoadingDetalle = false;
      me.desabilitar_precios = false;
    }).catch(error => {
      console.error(error);
    });
  },

  cerrarModalArticulos(){
    this.modalarticulos = 0;
  },

  actualizarpartidacompra(data, n){
    this.partidascompras.nombrearti = data.ad;
    this.partidascompras.id = data.id;
    this.partidascompras.comentario = data.comentario;
    this.partidascompras.cantidad = data.cantidad;
    this.partidascompras.precio_unitario = data.precio_unitario;
    this.partidascompras.centro_costo_id = data.catalogo_centro_costos_id;
    this.boton_centro_costo = 1;
  },

  actualizarpartidacomprauno(data, n){
    this.partidascompras.nombrearti = data.ad;
    this.partidascompras.id = data.id;
    this.partidascompras.comentario = data.comentario;
    this.partidascompras.cantidad = data.cantidad;
    this.partidascompras.precio_unitario = data.precio_unitario;
    this.partidascompras.centro_costo_id = data.catalogo_centro_costos_id;
    this.boton_actualizar_partida = 1;
  },

  guardarCC(){
    if (  this.partidascompras.centro_costo_id === '') {
      toastr.warning('Seleccione un centro de costos para guardar');
    }else {
      axios.post('guardarpartidacompracosto',{
        'id_partida' : this.partidascompras.id,
        'centro_costo_id' :   this.partidascompras.centro_costo_id,
      }).then(response => {
        if (response.data.status === 'error') {
          swal({
            type: 'error',
            html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
          });
        }else {
          this.boton_centro_costo = 0;
          this.cargarpartidas(this.empleado);
          this.cerrarModal();
          this.cancelar();
          this.getArticulosRequisicion();
          toastr.success('Correcto', 'Partida actualizada correctamente');
        }
      }).catch(error => {
        console.error(error);
      });
    }
  },

  guardarAP(){
    if (this.partidascompras.precio_unitario === '') {
      toastr.warning('Escriba un precio unitario');
    }else if (this.partidascompras.cantidad === '') {
      toastr.warning('Escriba la cantidad real comprada');
    }
    else {
      axios.put('guardaractualizapartida',{
        'id_partida' : this.partidascompras.id,
        'precio_unitario' : this.partidascompras.precio_unitario,
        'cantidad' : this.partidascompras.cantidad,
        'comentario' : this.partidascompras.comentario,
      }).then(response => {
        if (response.data.status === 'error') {
          swal({
            type: 'error',
            html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
          });
        }else {
          this.boton_actualizar_partida = 0;
          this.cargarpartidas(this.empleado);
          this.cerrarModal();
          this.cancelar();
          this.getArticulosRequisicion();
          toastr.success('Correcto', 'Partida actualizada correctamente');
        }
      }).catch(error => {
        console.error(error);
      });
    }
  },

  /**
  * [seleccionarArticuloAgregar description]
  * @param  {Object} data [description]
  * @return {}      [description]
  */
  seleccionarArticuloAgregar(data)
  {


    let me = this;
    //this.partidascompras.pda = data.row.pda; //se crea en el controlador
    //this.partidascompras.requisicione_id = data.row.rid;//se crea en el controlador
    this.partidascompras.articulo_id = data.row.id;
    this.partidascompras.servicio_id = data.row.servicio_id;
    this.partidascompras.nombrearti = data.row.descripcion;
    //this.partidascompras.cantidad = data.row.cantidad_compra;
    //this.partidascompras.cantidad_real = data.row.cantidad_compra;
    this.partidascompras.proyecto_id = this.empleado.proyecto_id;
    if (data.row.equivalente == 1) {
      this.verequivalente = false;//ambos en true
    }
    else {
      this.verequivalente = false;//ambos en true
    }
    this.modal_agregar =0;
    this.isLoadingg = false;
    this.tipo = 1;
    me.cerrarModalAgregar();

  },

  seleccionarServicioAgregar(data){
    this.tipo = 1;
    this.partidascompras.servicio_id = data.row.id;
    this.partidascompras.nombrearti = data.row.nombre_servicio +' '+data.row.proveedor_marca;
    if (this.partidascompras.servicio_id == this.partidascompras.servicio_uno_id) {
      toastr.warning('Atención','Seleccione un servicio equivalente diferente al original');
    }
    else {
      this.modal_agregar = 0;
      this.verequivalente = false;
      this.guardarequivalente = true;
      this.partidascompras.cambio_equivalente = 1;

    }
  },

  cambiarEstado(edo){
    edo == 1 ? this.estadomodal = 1 : this.estadomodal = 2;
  },

  agregarArt() {
    let me = this;
    me.$refs.articulo.abrirModal('articulo','registrar');
  },

  abrirModal(data){
    //this.$emit('enviar',data);
    //this.mostrar = false;
    let me = this;
    me.$refs.articulo.abrirModal('articulo','actualizar',data);
    me.cerrarModalAgregar();
  },

  ActivarTool(){
    $('#comentario').tooltip();
  },

},
mounted() {

}
}
</script>
<style>
.tooltip-inner {
      background-color: #fff;
      color: #000;
      border: 1px solid #000;
  }
</style>
