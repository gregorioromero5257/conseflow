<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Listado de requisiocnes -->
      <br>

      <div class="card" v-show="detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Entradas sin Orden de Compra
          <button type="button" @click="abrirModal('entrada','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoadingDetalle"/>
          <v-server-table :columns="columnsdescuento" url="busqueda/entradainterna" :options="optionsentinterna" ref="myTabledescuento">
            <template slot="articulo_id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button  v-show="PermisosCRUD.Delete" type="button" class="dropdown-item" @click="eliminarpartidaentrada(props.row, 0)" >
                      <i class="icon-trash"></i>&nbsp; Eliminar partida
                    </button>
                    <button type="button" class="dropdown-item" @click="actualizarPrecio(props.row)">
                      <i class="icon-pencil"></i>&nbsp; Actualizar Precio Unitario
                    </button>
                  </div>
                </div>
              </div>
            </template>
            <template slot="status" slot-scope="props">
              <template v-if="props.row.almacene_id == null" >
                <button type="button" class="btn btn-success btn-sm" @click="abrirModalB(props.row)" >
                  <i class="fas fa-boxes"></i>&nbsp;Almacenar en ...&nbsp;&nbsp;</button>
                </template>
                <template v-if="props.row.almacene_id != null" >
                  <button type="button" class="btn btn-greg btn-sm" @click="abrirModalB(props.row)" >
                    <i class="fas fa-boxes"></i>&nbsp;Almacenado en ...</button>
                  </template>
                </template>
                <template slot="validacion_calidad" slot-scope="props">
                  <template v-if="props.row.validacion_calidad == 1">
                    <button type="button" class="btn btn-success btn-sm" @click="vcalidad(props.row,0)">
                      <i class="far fa-square"></i></button>
                      <button type="button" class="btn btn-danger btn-sm" @click="vcalidadsalida(props.row,0)">
                        <i class="far fa-square"></i></button>
                      </template>
                      <template v-if="props.row.validacion_calidad == 0">
                        <button type="button" class="btn btn-success btn-sm" @click="vcalidad(props.row,1)" disabled>
                          <i class="far fa-check-square"></i></button>

                        </template>
                        <template v-if="props.row.validacion_calidad == 2">
                          <button type="button" class="btn btn-danger btn-sm" @click="vcalidadsalida(props.row,1)" disabled>
                            <i class="far fa-check-square"></i></button>
                          </template>
                        </template>
                        <template slot="pendiente" slot-scope="props">
                          <template v-if="props.row.pendiente == 1">
                            <label>Pendiente</label>
                          </template>
                          <template v-if="props.row.pendiente == 0">
                            <label>Inventario</label>
                          </template>
                        </template>
                      </v-server-table>
                    </div>
                    <!-- Nuevo y editar partidas requisiciones -->
                    <div class="card" ref="formLote">
                      <vue-element-loading :active="isLoadingg"/>
                      <div class="card-header">
                      </div>
                      <div class="card-body">
                        <form>
                          <input type="text" class="form-control" id="req_com_id" name="req_com_id" v-model="parentrada.req_com_id" placeholder="Compra" hidden >
                          <input type="text" class="form-control" id="articulo_id" name="articulo_id" v-model="parentrada.articulo_id" placeholder="Articulo" hidden>
                          <input type="text" class="form-control" id="entrada_id" name="entrada_id" v-model="parentrada.entrada_id" placeholder="Entrada" hidden >
                          <input type="text" class="form-control" id="proveedore_id" name="proveedore_id" v-model="parentrada.proveedore_id" hidden>
                          <input type="text" class="form-control" id="cantidad" name="cantidad" v-model="parentrada.cantidad" hidden >
                          <input type="text" class="form-control" id="stocke_id" name="stocke_id" v-model="parentrada.stocke_id" hidden >

                          <div class="form-group row">
                            <label v-show="PermisosCRUD.Create" for="inputArticulo" class="col-md-2 form-control-label">Artículo</label>
                            <div class="col-md-8">
                              <div class="input-group mb-3">
                                <input v-show="PermisosCRUD.Create" type="text" class="form-control"  v-model="parentrada.nombrearti" placeholder="Articulo" readonly>
                                <div class="input-group-append">
                                  <button v-show="PermisosCRUD.Read" class="btn btn-secondary" type="button" @click.prevent="abrirBusArticulo()">Buscar</button>
                                  <button class="btn btn-secondary" type="button" @click="agregarArt()">
                                    Agregar</button>

                                  </div>
                                </div>
                                <bus-articulo ref="busqueda" @enviar="editarArt($event)" @clicked="onClickArticuloSeleccionado"></bus-articulo>
                                <articulo ref="articulo" @cerrarAbrir="abrirBusArticulo()" @cerrar="abrirBusArticulo()"></articulo>

                              </div>
                            </div>
                            <div class="form-group row">
                              <label v-show="PermisosCRUD.Create" for="inputArticulo" class="col-md-2 form-control-label">Caducidad</label>
                              <div class="col-md-8">
                                <div class="input-group mb-3">
                                  <input v-show="PermisosCRUD.Create" type="date" class="form-control"  v-model="parentrada.caducidad" placeholder="Caducidad" >
                                </div>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label v-show="PermisosCRUD.Create" for="inputCantidad" class="col-md-2 form-control-label">Cantidad</label>
                              <div class="col-md-8">
                                <div class="input-group mb-3">
                                  <input v-show="PermisosCRUD.Create" type="number" class="form-control"  v-model="parentrada.cantidad" placeholder="Cantidad" min="0" step="1" >
                                </div>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label v-show="PermisosCRUD.Create" for="inputPrecio" class="col-md-2 form-control-label">Precio</label>
                              <div class="col-md-8">
                                <div class="input-group mb-3">
                                  <input v-show="PermisosCRUD.Create" type="text" v-validate="'decimal:2'" name="precio" class="form-control"  v-model="parentrada.precio" placeholder="Precio" min="0" step="0.01">
                                </div>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label v-show="PermisosCRUD.Create" for="inputPrecio" class="col-md-2 form-control-label">Adicionales</label>
                              <div class="col-md-8">
                                <div class="input-group mb-3">
                                  <input v-show="PermisosCRUD.Create" type="text" v-validate="'required|decimal:2'" name="adicionales" class="form-control"  v-model="parentrada.adicionales" placeholder="Adicionales" min="0" step="0.01">
                                </div>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="col-md-2 form-control-label" for="tipo_entrada">Tipo</label>
                              <div class="col-md-8">
                                <select class="form-control" id="tipo_entrada"  name="tipo_entrada"  v-model="pendiente" v-validate="'required'" data-vv-as="Pendiente" @change="cambioPendiente($event)">
                                  <option selected value="0">Inventario</option>
                                  <option value="1">Pendiente</option>
                                </select>
                                <span class="text-danger">{{ errors.first('pendiente') }}</span>
                              </div>
                            </div>
                            <div class="form-group row" v-show="habilitarStocks == 1">
                              <label v-show="PermisosCRUD.Create" class="col-md-2 form-control-label" for="nstock">Proyecto</label>
                              <div class="col-md-8">
                                <v-select :options="listaStocks"  v-validate="'required'" v-model="stocke_id" label="name" name="proyecto" data-vv-name="proyecto" ></v-select> <!---->
                                <!-- <select v-show="PermisosCRUD.Create" class="form-control" id="nstock"  name="nstock"  v-model="stocke_id" v-validate="'excluded:0'" data-vv-as="Stock">
                                <option  v-for="item in listaStocks" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                              </select> -->
                              <span class="text-danger">{{ errors.first('nstock') }}</span>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="card-footer">
                        <button v-show="PermisosCRUD.Delete" type="button" class="btn btn-secondary" @click="cancelar()"><i class="fas fa-window-close"></i>&nbsp;Cancelar</button>
                        <button type="button" v-if="tipoAccionpr==1" class="btn btn-dark" @click="validarGuardado(); guardarPR(1, parentrada.entrada_id);"><i class="fas fa-plus"></i>&nbsp;Agregar</button>
                        <!-- <button type="button" v-if="tipoAccionpr==2" class="btn btn-dark" @click="guardarPR(0, preq.requisicione_id)">Actualizar</button> -->
                      </div>
                    </div>
                    <!-- Fin  de Nuevo y editar detalle requisicion correspondiente ala tabla partidas_requisiciones -->

                    <!--Inicio del modal agregar/actualizar articulos-->
                    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modala}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                      <div class="modal-dialog modal-dark modal-lg" role="document">
                        <div class="modal-content">
                          <div>
                            <vue-element-loading :active="isLoadingg"/>
                            <div class="modal-header">
                              <h4 class="modal-title" v-text="tituloModala"></h4>
                              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <input type="hidden" name="id">
                              <div class="modal-body">
                                <v-client-table ref="myTable" :data="dataTableArticulo" :columns="columnsa" :options="optionsa" @row-click="seleccionarArticulo2">
                                </v-client-table>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                            </div>
                          </div>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                      <!-- /.modal-dialog -->
                    </div>
                    <!--Fin del modal agregar articulo-->

                    <!--Inicio del modal agregar/actualizar articulos-->
                    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalb}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                      <div class="modal-dialog modal-dark modal-lg" role="document">
                        <div class="modal-content">

                          <div>
                            <vue-element-loading :active="isLoadingb"/>
                            <div class="modal-header">
                              <h4 class="modal-title" v-text="tituloModalb"></h4>
                              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="almacen_id">Almacen</label>
                                <div class="col-md-9">
                                  <select class="form-control" id="almacen_id"  name="almacen_id"  v-model="almacen.id" @change="almac"  data-vv-as="Stand">
                                    <option v-for="item in listaAlmaceness" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                  </select>
                                  <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="stand_id">Stand</label>
                                <div class="col-md-9">
                                  <select class="form-control" id="stand_id"  name="stand_id"  v-model="almacen.stand_id" @change="niv" data-vv-as="Stand">
                                    <option v-for="item in listaStand" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                  </select>
                                  <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="nivel_id">Nivel</label>
                                <div class="col-md-9">
                                  <select class="form-control" id="nivel_id"  name="nivel_id"  v-model="almacen.nivel_id"  data-vv-as="Nivel">
                                    <option v-for="item in listaNivel" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                  </select>
                                  <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" @click="guardarDatosAlmacen()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
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
                </div>
                <!--Inicio del modal agregar/actualizar-->
                <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                  <vue-element-loading :active="isLoading"/>
                  <div class="modal-dialog modal-dark modal-lg" role="document">
                    <div class="modal-content">
                      <div>

                        <div class="modal-header">
                          <h4 class="modal-title" v-text="tituloModal"></h4>
                          <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
                          <input type="hidden" name="id">

                          <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="fecha">Fecha</label>
                            <div class="col-md-9">
                              <input type="date" v-validate="'required'" name="fecha" v-model="entrada.fecha" class="form-control" placeholder="Fecha de Entrada" autocomplete="off" id="fecha">
                              <span class="text-danger">{{ errors.first('fecha') }}</span>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="comentarios">Cometarios</label>
                            <div class="col-md-9">
                              <input type="text" name="comentarios" v-validate="'required'" v-model="entrada.comentarios" class="form-control" placeholder="Comentarios" autocomplete="off" id="comentarios">
                              <span class="text-danger">{{ errors.first('comentarios') }}</span>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="formato_entrada">Formato Entrada</label>
                            <div class="col-md-9">
                              <input type="text" name="formato_entrada" v-validate="'required'" v-model="entrada.formato_entrada" class="form-control" placeholder="Formato Entrada" autocomplete="off" id="formato_entrada">
                              <span class="text-danger">{{ errors.first('formato_entrada') }}</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="tipo_entrada_id">Tipo Entrada</label>
                            <div class="col-md-9">
                              <select class="form-control" id="tipo_entrada_id" v-validate="'required'" name="tipo_entrada_id" v-model="entrada.tipo_entrada_id" data-vv-as="Tipo de adquisicion">
                                <option v-for="item in listaTipoEntrada" :value="item.id" :key="item.id">{{ item.nombre}}</option>
                              </select>
                              <span class="text-danger">{{ errors.first('tipo_entrada_id') }}</span>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="tipo_adq_id">Tipo de Adquisicion</label>
                            <div class="col-md-9">
                              <select class="form-control" id="tipo_adq_id"  name="tipo_adq_id" v-model="entrada.tipo_adq_id"  >
                                <option v-for="item in listaTipoAdquisicion" :value="item.id" :key="item.id">{{ item.nombre}} </option>
                              </select>
                              <span class="text-danger">{{ errors.first('tipo_adq_id') }}</span>
                            </div>
                          </div>

                          <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                          <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarEntrada(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                          <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarEntrada(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                        </div>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!--Fin del modal-->

                <!--Inicio del modal editar precio-->
                <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalEditar}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                  <div class="modal-dialog modal-dark modal-lg" role="document">
                    <div class="modal-content">
                      <div>
                        <vue-element-loading :active="isLoadingb"/>
                        <div class="modal-header">
                          <h4 class="modal-title">Editar Precio Unitario</h4>
                          <button type="button" class="close" @click="cerrarActualizarPrecioUnitario()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->

                          <div class="form-group row">
                            <label for="inputPrecio" class="col-md-2 form-control-label">Articulo</label>
                            <div class="col-md-8">
                              <div class="input-group mb-3">
                                <input type="text" class="form-control"  v-model="partidaEntrada.descripcion" placeholder="Articulo" readonly>
                              </div>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="inputPrecio" class="col-md-2 form-control-label">Precio</label>
                            <div class="col-md-8">
                              <div class="input-group mb-3">
                                <input type="text" v-validate="'decimal:2'" name="precio" class="form-control"  v-model="partidaEntrada.precio_unitario" placeholder="Precio" min="0" step="0.01">
                              </div>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="inputPrecio" class="col-md-2 form-control-label">Adicionales</label>
                            <div class="col-md-8">
                              <div class="input-group mb-3">
                                <input type="text" v-validate="'required|decimal:2'" name="adicional" class="form-control"  v-model="partidaEntrada.cantidad_adicional" placeholder="Precio" min="0" step="0.01">
                              </div>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="inputComentario" class="col-md-2 form-control-label">Comentario</label>
                            <div class="col-md-8">
                              <div class="input-group mb-3">
                                <input type="text" class="form-control"  v-model="comentarioArticulo" placeholder="Comentario" maxlength="100">
                              </div>
                            </div>
                          </div>

                          <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-outline-dark" @click="cerrarActualizarPrecioUnitario()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                          <button type="button" class="btn btn-secondary" @click="actulizarPrecioUnitario()"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                        </div>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!--Fin del modal-->

              </main>
            </template>

            <script>

            import Utilerias from '../Herramientas/utilerias.js';
            var config = require('../Herramientas/config-vuetables-client').call(this);

            export default {
              components: {
                'bus-articulo': require('./BusArticulo.vue'),
                'articulo' : require('./Articulo.vue')
              },
              data (){
                return {
                  url: '/entrada',
                  PermisosCRUD:{},
                  empleado: null,
                  validaGuardado : false,
                  habilitarStocks : '',
                  datospartida : null,
                  detalle: false,
                  req_com_id: 1,
                  stocke_id: '',
                  listaStocks: [],
                  proveedor_id: 0,
                  entrada_id: 1,
                  tipo_entrada_id: 'Interna',
                  pendiente : '',
                  comentarioArticulo: '',
                  entrada: {
                    id : 0,
                    fecha :'',
                    comentarios	 :'',
                    formato_entrada : '',
                    ord_compra_id : '',
                    tipo_adq_id: 0,
                    tipo_entrada_id: 'Interna',
                    condicion: 0,
                    nombrea :'',
                    nombree :'',
                    disabled : true,
                  },
                  almacen : {
                    id : 0,
                    ida: '',
                    nombre : '',
                    stand_id : 0,
                    stand : '',
                    nivel_id : 0,
                    nivel : '',
                  },
                  parentrada : {
                    entrada_id :0,
                    req_com_id : 0,
                    articulo_id :0,
                    nombrearti :'',
                    fechaentrada : '',
                    orden_n_id :0,
                    proveedore_id : 0,
                    validacion_calidad : 0,
                    cantidad :'',
                    precio_unitario : 0,
                    stockenombre: 0,
                    caducidad : '',
                    stocke_id : 0,
                    precio: 0,
                    adicionales: 0,
                  },
                  partidaEntrada: {},
                  articulo_dato : [],
                  listaTipoAdquisicion :[],
                  listaTipoEntrada : [],
                  dataTableArticulo : [],
                  optionsvs :[],
                  listaNivel : [],
                  listaStand : [],
                  listaAlmaceness : [],
                  modal : 0,
                  modala : 0,
                  modalb : 0,
                  modaldoc : 0,
                  modalEditar: 0,
                  tituloModal : '',
                  tituloModala : '',
                  tituloModalb : '',
                  tipoAccion : 0,
                  tipoAccionpr : 0,
                  disabled: 0,
                  isLoading: false,
                  isLoadingg: false,
                  isLoadingb: false,
                  isLoadingDetalle: false,
                  columnsa: ['ocf','descripciona','ocfo','prazonsocial','proyectonombre'],
                  columns: ['id','fecha','comentarios','formato_entrada','nombrea','nombree','condicion' ],
                  tableData: [],
                  columnsdescuento: ['articulo_id','pendiente','codigo','descripcion','marca','status','validacion_calidad','cantidad','fechaentrada','precio_unitario','stokenombre'],
                  tableDatadescuento: [],
                  optionsa: {
                    headings: {
                      'ocf': 'Folio orden de compra',
                      'descripciona': 'Descripción artículo',
                      'ocfo' : 'Fecha de la orden',
                      'prazonsocial' : 'Proveedor',
                      'proyectonombre' : 'Proyecto',
                      'pendiente' : 'Tipo',
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
                  options: {
                    headings: {
                      'id': 'Acciones',
                      'fecha': 'Fecha',
                      'comentarios': 'Comentarios',
                      'formato_entrada': 'Formato de entrada',
                      'nombrea' : 'Tipo de adquisición',
                      'nombree' : 'Tipo de entrada',
                      'condicion' : 'Condición',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['fecha','comentarios','formato_entrada','nombrea','nombree','condicion'],
                    filterable: ['id','fecha','comentarios','formato_entrada','nombrea','nombree'],
                    filterByColumn: true,
                    texts:config.texts
                  },
                  optionsentinterna: {
                    headings: {
                      'articulo_id' : 'Acciones',
                      'pendiente': 'Tipo entrada',
                      'amarca': 'Marca',
                      'descripcion': 'Artículo',
                      'validacion_calidad' : 'Validación calidad',
                      'cantidadcompra' : 'Cantidad',
                      'status' : 'Almacenar',
                      'precio_unitario' : 'Precio Unitario',
                      'stokenombre' : 'Proyecto',

                    },
                    perPage: 5,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    orderBy : 'DESC',
                    sortable: ['status','marca','descripcion', 'codigo','tipo_entrada', 'cantidadcompra','stokenombre'],
                    filterable: ['status','pendiente','marca','codigo','descripcion','stokenombre'],
                    filterByColumn: true,
                    listColumns: {
                      pendiente: [{
                        id: 0,
                        text: 'Inventario'
                      },
                      {
                        id: 1,
                        text: 'Pendiente'
                      }
                    ],
                    status:[
                      {
                        id: 0,
                        text : 'Pendiente',
                      },
                      {
                        id: 1,
                        text : 'Almacenado'
                      }
                    ]
                  },
                  texts:config.texts,
                  requestAdapter : data => {
                      console.log(data);
                      var arr = [];
                      arr.push({
                        'S.nombre' : data.query.stokenombre,
                        'status' : data.query.status,
                        'pendiente' :data.query.pendiente,
                        'codigo' : data.query.codigo,
                        'descripcion' : data.query.descripcion,
                        'marca' : data.query.marca,
                      });
                      data.query = arr[0];
                      return data;
                  },
                },
              }
            },
            computed:{
            },
            methods : {

              /**
              * [vcalidad Metodo el cual hace la validacion de calidad donde se pase de tener la partida en un lote temporal a un lote ya definido]
              * @param  {Array}  [data=[]] [description]
              * @param  {Int} c         [description]
              * @return {Response}           [status => true]
              */
              vcalidad(data = [],c){
                var cadena = [];
                var cadenaid = [];
                var cadenanum = [];
                var steps = [];
                let me =  this;
                me.isLoadingDetalle = true;
                let formData = new FormData();
                axios.get('/partidaentrada'+'/'+data.id).then(function (response) {
                  if (response.data[0].almacene_id !=  null) {
                    axios.post('/partidaentrada/calidad', {
                      id : data.id,
                      entrada_id: data.entrada_id,
                      orden_compra_id: data.req_com_id,
                      articulo_id : data.articulo_id,
                      cantidad : data.cantidad,
                      caducidad : data.caducidad,
                      almacene_id : data.almacene_id,
                      nivel_id : data.nivel_id,
                      stand_id : data.stand_id,
                      proyecto_id : data.proyecto_id,
                      stokeid : data.stokeid,
                      precio_unitario : data.precio_unitario,
                    })
                    .then(function (response) {
                      me.cargardetalle(me.empleado);
                      me.isLoadingDetalle = false;
                      toastr.success('Validado Correctamente')

                    })
                    .catch(function (error) {
                      console.log(error);
                    });

                  }
                  else{

                    toastr.error('No se puede validar hasta definir un almacen')
                    me.isLoadingDetalle = false;
                  }
                }).catch(function (error) {
                  console.log(error);
                });
              },

              /**
              * [vcalidadsalida Metodo que se activa al no contar con la validacion de calidad]
              * @param  {Array}  [data=[]] [description]
              * @return {Response}           [description]
              */
              vcalidadsalida (data = []){
                swal({
                  title: 'Motivo',
                  text: "Motivo por el articulo no es validado",
                  showCancelButton: true,
                  input: 'textarea',
                  confirmButtonColor: '#4aa0f1',
                  cancelButtonColor: '#898b8e',
                  confirmButtonText: 'Guardar',
                  cancelButtonText: 'Cerrar',
                  reverseButtons: true
                }).then((result) => {
                  let me= this;
                  if (result.value) {
                    var val= result.value;
                    axios.get('/partidaentrada'+'/'+data.entrada_id+'&'+data.req_com_id+'&'+data.articulo_id+'&'+data.id+'/calidadsalida').then(function (response) {
                      me.cargardetalle(me.empleado);
                      me.isLoadingDetalle = false;

                    }).catch(function (error) {
                      console.log(error);
                    });
                  }
                  else {
                    console.log('false');
                  }
                });
              },

              /**
              * [almac Metodo de consulta a la BD ]
              * @return {Response} [description]
              */
              almac(){
                let me = this;
                this.isLoading = true;
                axios.get('almacen/verstand/' + this.almacen.id).then(response => {
                  me.listaStand = response.data;
                  me.isLoading = false;
                })
                .catch(function (error) {
                  console.log(error);
                });
              },

              /**
              * [niv Metodo de consulta a la BD ]
              * @return {Response} [description]
              */
              niv(){
                let me = this;
                this.isLoading = true;
                axios.get('almacen/vernivel/' + this.almacen.stand_id).then(response => {
                  me.listaNivel = response.data;
                  me.isLoading = false;
                })
                .catch(function (error) {
                  console.log(error);
                });
              },

              /**
              * [getListas Metodos de consulta a la BD ]
              * @return {Response} [Objetos almacenados en diferentes variables]
              */
              getListas() {
                this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
                let me=this;
                axios.get('/almacen/ver').then(response => {
                  me.listaAlmaceness = response.data;
                  me.isLoading = false;
                })
                .catch(function (error) {
                  console.log(error);
                });

                axios.get('/tipoadquisicion').then(response => {
                  me.listaTipoAdquisicion = response.data;
                })
                .catch(function (error) {
                  console.log(error);
                });

                axios.get('/tipoentrada').then(response => {
                  me.listaTipoEntrada = response.data;
                })
                .catch(function (error) {
                  console.log(error);
                });

                axios.get('/stock').then(response => {
                  //me.listaStocks = response.data;
                  for (var i = 0; i < response.data.length; i++) {
                    this.listaStocks.push({
                      id: response.data[i].id,
                      name : response.data[i].nombre,
                    });
                  }
                })
                .catch(function (error) {
                  console.log(error);
                });


              },

              /**
              * [getArticulosEntrada Metodo de consulta a la BD ]
              * @return {Response} [objeto almacenado en la variable dataTableArticulo]
              */
              getArticulosEntrada(){
                let me = this;
                axios.get('/partidaentrada').then(response => {
                  me.dataTableArticulo= response.data;

                })
                .catch(function (error) {
                  console.log(error);
                });
              },

              /**
              * [eliminarpartidaentrada Metodo que elimina una partida de entrada en la BD]
              * @param  {Array}  [data=[]] [description]
              * @param  {int} activar   [0 = no y 1 = si]
              * @return {Response}           [status = true]
              */
              eliminarpartidaentrada(data = [] ,activar){
                if(!activar){
                  this.swal_titulo = 'Esta seguro de remover este artículo de la entrada?';
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
                    // console.log(data);
                    // console.log(activar);
                    axios.post('/partidaentrada/eliminarpartidaentrada', {
                      articulo_id: data.articulo_id,
                      orden_compra_id: data.req_com_id,
                      partida_entrada_id : data.id,
                      cantidad : data.cantidad,
                    })
                    .then(function (response) {
                      // console.log(response);
                      me.cargardetalle(me.empleado);
                      me.getArticulosEntrada();
                      toastr.success('Eliminado Correctamente')
                    })
                    .catch(function (error) {
                      console.log(error);
                    });
                  } else if (
                    result.dismiss === swal.DismissReason.cancel
                  ) {

                  }
                })
              },

              /**
              * [actdesact Metodo que actualiza la condicion de la entrada activo o desactivo]
              * @param  {Int} id      [description]
              * @param  {Int} activar [0 = no y 1 = si]
              * @return {Response}         [status = true]
              */
              actdesact(id,activar){
                if(activar){
                  this.swal_titulo = 'Esta seguro de activar esta entrada?';
                  this.swal_tle = 'Activado';
                  this.swal_msg = 'El registro ha sido activado con éxito.';
                }else{
                  this.swal_titulo = 'Esta seguro de desactivar esta entrada?';
                  this.swal_tle = 'Desactivado!';
                  this.swal_msg = 'El registro ha sido desactivado con éxito.';
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
                    axios.get(me.url+'/'+id+'/edit').then(function (response) {
                      if(activar){
                        toastr.success(me.swal_tle,me.swal_msg,'success');

                      }else{
                        toastr.error(me.swal_tle,me.swal_msg,'error');

                      }
                      toastr.options = {
                        "closeButton": false,
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": true,
                        "positionClass": "toast-top-center",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                      }
                    }).catch(function (error) {
                      console.log(error);
                    });
                  } else if (
                    result.dismiss === swal.DismissReason.cancel
                  ) {

                  }
                })
              },

              /**
              * [guardarPR Metodo que almacena o actualiza una partida en la BD]
              * @param  {Int} nuevo [0 = no y 1 = si]
              * @param  {Int} idr   [Id]
              * @return {Response}       [status = true]
              */
              guardarPR(nuevo, idr){
                if (this.validaGuardado) {

                  this.isLoading = false;
                  this.detalle = true;
                  this.isLoadingg = true;
                  let me = this;
                  // axios.get('/partidaentrada'+'/'+this.parentrada.articulo_id+'&'+this.parentrada.req_com_id+'/edit').then(function (response) {
                  // }).catch(function (error) {
                  //   console.log(error);
                  // });
                  axios({
                    method: nuevo ? 'POST' : 'PUT',
                    url: nuevo ? '/guardarpartidainterna' : '/partidaentrada'+'/'+this.parentrada.entrada_id,
                    data: {
                      'entrada_id': this.parentrada.entrada_id,
                      'orden_compra_id': this.parentrada.req_com_id,
                      'articulo_id': this.parentrada.articulo_id,
                      'proveedore_id': this.parentrada.proveedore_id,
                      'caducidad' : this.parentrada.caducidad,
                      'cantidad' : this.parentrada.cantidad,
                      'stocke_id' : this.stocke_id.id,
                      'precio' : this.parentrada.precio,
                      'tipo_entrada': this.tipo_entrada_id,
                      'adicionales' : this.parentrada.adicionales,
                      'pendiente' : this.pendiente,
                    }
                  }).then(function (response) {
                    me.isLoading = false;
                    me.isLoadingg = false;
                    if (response.data.status) {
                      me.cargardetalle();
                      // me.cargardetalle(me.empleado);
                      me.vaciarguardado();
                      // me.cancelar();
                      // me.getArticulosEntrada();
                    } else {
                      me.cerrarModal();
                      me.cancelar();
                      swal(
                        'Error!',
                        'No se pueden agregar articulos de diferente orden de compra o proveedor!',
                        'warning'
                      )
                    }
                  }).catch(function (error) {
                    console.log(error);

                    me.cancelar();
                    me.isLoading = false;
                    me.isLoadingg = false;
                  });
                }
              },

              /**
              * [guardarEntrada Metodo que almacen o actualiza una entrada]
              * @param  {Int} nuevo [1 = si y 0 = no]
              * @return {Response}       [status = true]
              */
              guardarEntrada(nuevo){

                this.$validator.validate().then(result => {
                  if (result) {
                    this.isLoading = true;
                    this.detalle = false;
                    this.isLoadingg = false;
                    let me = this;
                    axios({
                      method: nuevo ? 'POST' : 'PUT',
                      url: nuevo ? me.url : me.url+'/'+this.entrada.id,
                      data: {
                        'id': this.entrada.id,
                        'fecha': this.entrada.fecha,
                        'comentarios': this.entrada.comentarios,
                        'formato_entrada': this.entrada.formato_entrada,
                        'tipo_adq_id': this.entrada.tipo_adq_id,
                        'tipo_entrada_id': this.entrada.tipo_entrada_id,

                      }
                    }).then(function (response) {
                      me.isLoading = false;
                      if (response.data.status) {
                        me.cerrarModal();
                      } else {
                        swal({
                          type: 'error',
                          html: response.data.errors.join('<br>'),
                        });
                      }
                    }).catch(function (error) {
                      console.log(error);
                    });
                  }
                });
              },

              /**
              * [guardarDatosAlmacen Metodo que actualiza la partida de la entrada agregando los datos de almacen]
              * @return {Response} [status = true]
              */
              guardarDatosAlmacen(){

                let me = this;
                axios.put( 'partidaentrada/update/almacen/'+me.datospartida.id,{
                  'id':me.datospartida.id,
                  'entrada_id': me.datospartida.entrada_id,
                  'orden_compra_id': me.datospartida.req_com_id,
                  'articulo_id': me.datospartida.articulo_id,
                  'almacene_id': this.almacen.id,
                  'nivel_id': this.almacen.nivel_id,
                  'stand_id': this.almacen.stand_id,
                  'status' : 1,
                }).then(function (response) {
                  if (response.data.status) {
                    toastr.success('Guardado Correctamente')
                    me.cargardetalle(me.empleado);
                    me.cerrarModal();
                    //  Utilerias.resetObject(this.almacen);
                  } else {
                    swal({
                      type: 'error',
                      html: response.data.errors.join('<br>'),
                    });
                  }
                }).catch(function (error) {
                  console.log(error);
                });


              },
              cerrarModal(){
                this.modal=0;
                this.modala=0;
                this.modalb = 0;
                this.modaldoc =0;
                this.tituloModal='';
                Utilerias.resetObject(this.requisicion);
              },
              abrirModalA(modelo, accion, id ,data = []){
                switch(modelo){
                  case "articulo":
                  {
                    switch(accion){
                      case 'registrar':
                      {
                        this.modala = 1;
                        this.tituloModala = 'Registrar artículo a la entrada ';
                        Utilerias.resetObject(this.preq);
                        this.tipoAccionpr = 1;
                        this.parentrada.entrada_id = id;
                        break;
                      }
                    }
                  }
                }
              },
              abrirModalB(data = []){

                this.modalb = 1;
                this.tituloModalb = 'Agregar a almacen';
                this.datospartida = data;
                this.almacen.id = data['almacene_id'];

                let me=this;
                var idalmacen =data['almacene_id'];

                axios.get('/almacen/verstand/'+idalmacen).then(response => {
                  me.listaStand = response.data;
                  this.almacen.stand_id = data['stand_id'];
                  var idstand = data['stand_id'];
                  axios.get('/almacen/vernivel/'+idstand).then(response => {
                    me.listaNivel = response.data;
                    this.almacen.nivel_id = data['nivel_id'];
                  })
                })
                .catch(function (error) {
                  console.log(error);
                });

              },

              abrirModal(modelo, accion, data = []){
                switch(modelo){
                  case "entrada":
                  {
                    switch(accion){
                      case 'registrar':
                      {
                        this.modal= 1;
                        this.tituloModal = 'Registrar entrada';
                        Utilerias.resetObject(this.entrada);
                        this.tipoAccion = 1;
                        this.disabled=0;
                        break;
                      }
                      case 'actualizar':
                      {
                        Utilerias.resetObject(this.entrada);
                        this.modal=1;
                        this.tituloModal='Actualizar entrada';
                        this.entrada.id=data['id'];
                        this.tipoAccion=2;
                        this.disabled=1;
                        this.entrada.fecha = data['fecha'];
                        this.entrada.comentarios = data['comentarios'];
                        this.entrada.formato_entrada = data['formato_entrada'];
                        this.entrada.tipo_adq_id = data['tipo_adq_id'];
                        this.entrada.tipo_entrada_id = data['tipo_entrada_id'];


                        break;
                      }
                    }
                  }
                }
              },
              cargardetalle(dataEmpleado = []) {

                this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
                this.detalle = true;
                // this.isLoadingDetalle = true;
                // this.isLoading = true;
                let me=this;
                //   this.empleado = dataEmpleado;
                //   this.parentrada.entrada_id = dataEmpleado.id;
                this.parentrada.entrada_id = this.entrada_id;

                // axios.get(me.url + '/' + this.parentrada.entrada_id ).then(response => {
                //   me.tableDatadescuento = response.data;
                me.isLoadingDetalle = false;
                me.$refs.myTabledescuento.refresh();
                // console.log(me.$refs.myTabledescuento);
                //
                //
                // })
                // .catch(function (error) {
                //   console.log(error);
                // });
              },

              maestro(){
                this.$refs.myTabledescuento.setFilter({

                });
                this.detalle = false;
                this.isLoading = false;
                this.cancelar();
              },
              cancelar(){

                this.parentrada.articulo_id = 0;
                this.parentrada.req_com_id = 0;
                this.parentrada.nombrearti = "";
                this.parentrada.caducidad = "";
                this.tipoAccionpr =0;


              },
              abrirBusquedaArticulo() {
                this.tipoAccionpr = 1;
                this.modal = 1;
                //  this.$refs.busqueda.mostrarBusArticulo();
              },

              seleccionarArticulo2(data)
              {

                let me = this;
                this.parentrada.req_com_id = data.row.id;
                this.parentrada.articulo_id = data.row.ida;
                this.parentrada.nombrearti = data.row.descripciona;
                this.parentrada.proveedore_id = data.row.idproveedor;
                this.parentrada.cantidad = data.row.cantidad;
                this.parentrada.stocke_id = data.row.stokeid;
                me.cerrarModal();
              },
              descargar(data) {
                //  console.log(data);
                window.open('descargar-entrada/' + data.id, '_blank');
                let me = this;
              },
              abrirBusArticulo() {
                let me = this;
                me.$refs.busqueda.mostrarBusArticulo();
                me.$refs.myTabledescuento.refresh();
              },
              agregarArt() {

                let me = this;
                me.$refs.articulo.abrirModal('articulo','registrar');
              },
              editarArt(data){
                let me = this;
                me.$refs.articulo.abrirModal('articulo','actualizar',data);
              },
              actualizaArt(){
                let me = this;
                me.$refs.articulo.abrirModal('articulo','actualizar',me.articulo_dato)
              },
              onClickArticuloSeleccionado(value) {
                console.log(value,'value');
                this.articulo_dato = value;
                this.tipoAccionpr = 1;
                this.parentrada.req_com_id = this.req_com_id; //data.row.id;
                this.parentrada.articulo_id = value.id;
                this.parentrada.nombrearti = value.descripcion;
                // this.parentrada.proveedore_id = data.row.idproveedor;
                this.parentrada.cantidad = 1;
                // this.parentrada.stocke_id = this.stocke_id; //data.row.stokeid;
                this.parentrada.precio = 1;
              },
              actualizarPrecio(data) {
                console.log(data);
                this.modalEditar = 1;
                this.partidaEntrada = data;
                // console.log(data);
                this.isLoadingb = true;
                let me = this;
                axios.post('/partidaentradainterna/otenercomentario', {
                  partida_entrada_id: this.partidaEntrada.id,
                })
                .then(function (response) {
                  me.comentarioArticulo = response.data.comentario;
                  me.isLoadingb = false;
                })
                .catch(function (error) {
                  console.log(error);
                });
              },
              cerrarActualizarPrecioUnitario() {
                this.modalEditar = 0;
              },
              vaciarguardado(){
                this.parentrada.nombrearti = "";
                this.parentrada.articulo_id = "";
                this.parentrada.proveedore_id = "";
                this.parentrada.caducidad = "";
                this.parentrada.cantidad = "";
                this.stocke_id = "";
                this.parentrada.precio = 0;
                this.tipo_entrada_id = "";
                this.parentrada.adicionales = 0;
                this.pendiente = "";
                this.tipoAccionpr = 0;
                this.habilitarStocks = false;
              },
              actulizarPrecioUnitario() {
                this.modalEditar = 0;
                let me = this;
                axios.post('/partidaentradainterna/actualizar', {
                  partida_entrada_id: this.partidaEntrada.id,
                  req_com_id: this.partidaEntrada.req_com_id,
                  precio_unitario: this.partidaEntrada.precio_unitario,
                  adicionales : this.parentrada.adicionales,
                  comentario: this.comentarioArticulo
                })
                .then(function (response) {
                  me.cargardetalle();
                  me.comentarioArticulo = '';
                  toastr.success('Precio actualizado correctamente.')
                })
                .catch(function (error) {
                  console.log(error);
                });
              },

              cambioPendiente(data){

                if(data.target.options.selectedIndex === 1){
                  this.habilitarStocks = 1;
                }else{
                  this.habilitarStocks = 0;
                  this.stocke_id = '';
                }
              },

              validarGuardado(){

                if (this.parentrada.cantidad === '') {
                  this.validaGuardado = false;
                  toastr.warning('Atención','Escriba la cantidad!!!');
                }else {
                  if (this.parentrada.precio === '') {
                    this.validaGuardado = false;
                    toastr.warning('Atención','Escriba el precio!!!');
                  }else {
                    if (this.parentrada.adicionales === '') {
                      this.validaGuardado = false;
                      toastr.warning('Atención','Escriba el precio adicional!!!');
                    }else {
                      if (this.pendiente === '') {
                        this.validaGuardado = false;
                        toastr.warning('Atención','Seleccione el tipo de entrada!!!');
                      }else {
                        if(this.pendiente === '1' && this.stocke_id === ''){
                          this.validaGuardado = false;
                          toastr.warning('Atención','Seleccione el proyecto!!!');
                        }else {
                          this.validaGuardado = true;
                        }
                      }
                    }
                  }
                }




              }

            },

            mounted() {
              this.getListas();
              this.cargardetalle();
            }
          }
          </script>
          <style>

          .btn-greg {
            color: #fff;
            background-color: #e9b443;
            border-color: #e9b443; }

            </style>
