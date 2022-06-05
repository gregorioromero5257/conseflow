<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <!--Inicio card principal  -->
      <div class="card" v-show="detalle == 0">
        <vue-element-loading :active="isLoading" />
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Entradas
          <button v-show="PermisosCRUD.Create" type="button" @click="abrirModal('entrada','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <!-- Listad0 de entradas -->
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                    <template>
                      <button type="button" class="dropdown-item" @click="descargar(props.row)">
                        <i class="fas fa-file-pdf"></i>&nbsp;Descargar Entrada
                      </button>
                      <button type="button" class="dropdown-item" @click="descargarnuevo(props.row)">
                        <i class="fas fa-file-pdf"></i>&nbsp;Descargar Entrada Nuevo
                      </button>
                    </template>

                    <template>
                      <button type="button" @click="cargardetalle(props.row)" class="dropdown-item">
                        <i class="fas fa-eye"></i>&nbsp; Ver Detalles
                      </button>
                      <!-- <button type="button" @click="ver_facturas(props.row)" class="dropdown-item">
                        <i class="fas fa-file-pdf"></i>&nbsp; Ver Facturas
                      </button> -->
                    </template>

                    <button type="button" @click="abrirModal('entrada','actualizar',props.row)" class="dropdown-item">
                      <i class="icon-pencil"></i>&nbsp;Actualizar
                    </button>

                    <!-- <button type="button"  @click="enviar_revision(props.row)" class="dropdown-item" >
                    <i class="icon-pencil"></i>&nbsp;Enviar a revisión
                  </button> -->

                  <template v-if="props.row.condicion">
                    <button type="button" class="dropdown-item" @click="actdesact(props.row.id, 0)">
                      <i class="icon-trash"></i>&nbsp;Desactivar
                    </button>
                  </template>
                  <template v-else>
                    <button type="button" class="dropdown-item" @click="actdesact(props.row.id, 1)">
                      <i class="icon-check"></i>&nbsp; Activar
                    </button>
                  </template>
                </div>
              </div>
            </div>
          </template>
          <template slot="condicion" slot-scope="props">
            <template v-if="props.row.condicion == 1">
              <button type="button" class="btn btn-outline-success">Activo</button>
              <!-- <span class="badge badge-success">Activo</span> -->
            </template>
            <template v-if="props.row.condicion == 0">
              <button type="button" class="btn btn-outline-danger">Dado de Baja</button>
              <!-- <span class="badge badge-danger">Dado de Baja</span> -->
            </template>

          </template>
          <template slot="estado" slot-scope="props">
            <template v-if="props.row.estado == 1">
              <button type="button" class="btn btn-outline-info">Nuevo</button>
              <!-- <span class="badge badge-success">Activo</span> -->
            </template>
            <template v-if="props.row.estado == 2">
              <button type="button" class="btn btn-outline-success">Finalizada</button>
              <!-- <span class="badge badge-danger">Dado de Baja</span> -->
            </template>
            <template v-if="props.row.estado == 3">
              <button type="button" class="btn btn-outline-warning">En revisión</button>
              <!-- <span class="badge badge-danger">Dado de Baja</span> -->
            </template>

          </template>
        </v-client-table>
        <!-- Fin de listado de entradas -->
      </div>
    </div>
    <!-- Fin del card principal -->
    <br>

    <!-- Inicio del card detalle.vue -->
    <div class="card" v-show="detalle == 1">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Detalles de la entrada con fecha : {{ entrada_objeto == null ? '':  entrada_objeto.fecha }}
        <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
          <i class="fas fa-arrow-left"></i>&nbsp;Atras
        </button>

      </div>
      <div class="card-body">

        <vue-element-loading :active="isLoadingDetalle" />
        <!-- Listado de partidas de la entradas -->
        <v-client-table :columns="columnspartidas" :data="tableDataPartidas" :options="optionspartidas" ref="myTabledescuento">
          <template slot="req_com_id" slot-scope="props">
            <button hidden type="button" class="btn btn-danger btn-sm">
            </button>
          </template>

          <template slot="articulo_id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <!-- v-bind:disabled="props.row.almacene_id != null" -->
                  <template v-if="entrada_objeto.estado != 3">
                    <button type="button" class="dropdown-item" @click="eliminarpartidaentrada(props.row, 0)">
                      <i class="icon-trash"></i>&nbsp;Eliminar Partida
                    </button>
                  </template>
                  <button type="button" class="dropdown-item" @click="descargarCodigoBarras(props.row)">
                    <i class="icon-trash"></i>&nbsp;Descargar Código barras
                  </button>
                  <!-- <template v-if="entrada_objeto.estado != 3">
                    <button type="button" class="dropdown-item" @click="CrearCodigo(props.row)">
                      <i class="fas fa-barcode"></i>&nbsp;Generar código de barras
                    </button>
                  </template> -->
                  <button type="button" class="dropdown-item" @click="actualizarpartidaentrada(props.row, 0)">
                    <i class="icon-trash"></i>&nbsp;Actualizar Partida
                  </button>
                </div>
              </div>
            </div>

          </template>
          <template slot="id" slot-scope="props" v-if="entrada_objeto.estado != 3">
            <!-- <button type="button" class="btn btn-success btn-sm" @click="abrirModalB(props.row)" >
            Almacenar en...</button> -->

            <template v-if="props.row.almacene_id == null">
              <button type="button" class="btn btn-primary btn-sm" @click="abrirModalB(props.row)">
                <i class="fas fa-boxes"></i>&nbsp;Almacenar En ...&nbsp;&nbsp;</button>
              </template>
              <template v-if="props.row.almacene_id != null">
                <button type="button" class="btn btn-warning btn-sm" @click="abrirModalB(props.row)">
                  <i class="fas fa-boxes"></i>&nbsp;Almacenado en ...</button>
                </template>

              </template>
              <template slot="validacion_calidad" slot-scope="props" v-if="entrada_objeto.estado != 3">
                <template v-if="props.row.validacion_calidad == 1 && PermisosCRUD.Upload">
                  <button type="button" class="btn btn-outline-success btn-sm" @click="vcalidad(props.row,0)">
                    <i class="far fa-square"></i>&nbsp;Sí</button>
                    <button type="button" class="btn btn-outline-danger btn-sm" @click="vcalidadsalida(props.row,0)">
                      <i class="far fa-square"></i>&nbsp;No</button>
                    </template>
                    <template v-if="props.row.validacion_calidad == 0">
                      <button type="button" class="btn btn-outline-success btn-sm" @click="vcalidad(props.row,1)" disabled>
                        <i class="far fa-check-square"></i>&nbsp;Si</button>
                      </template>
                      <template v-if="props.row.validacion_calidad == 2">
                        <button type="button" class="btn btn-outline-danger btn-sm" @click="vcalidadsalida(props.row,1)" disabled>
                          <i class="far fa-check-square"></i>&nbsp;No</button>
                        </template>
                      </template>
                    </v-client-table>
                    <!-- Fin de listado de las partidas de las entradas -->

                  </div>

                  <!-- Nuevo partidas entradas -->
                  <div class="card" ref="formLote">
                    <vue-element-loading :active="isLoadingg" />
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                      <form>
                        <input type="text" class="form-control" id="req_com_id" name="req_com_id" v-model="parentrada.req_com_id" placeholder="Compra" hidden>
                        <input type="text" class="form-control" id="articulo_id" name="articulo_id" v-model="parentrada.articulo_id" placeholder="Articulo" hidden>
                        <input type="text" class="form-control" id="entrada_id" name="entrada_id" v-model="parentrada.entrada_id" placeholder="Entrada" hidden>
                        <input type="text" class="form-control" id="proveedore_id" name="proveedore_id" v-model="parentrada.proveedore_id" hidden>
                        <input type="text" class="form-control" id="cantidad" name="cantidad" v-model="parentrada.cantidad" hidden>
                        <input type="text" class="form-control" id="stocke_id" name="stocke_id" v-model="parentrada.stocke_id" hidden>

                        <div class="form-group row">
                          <label for="inputArticulo" class="col-md-2 form-control-label">Artículo</label>
                          <div class="col-md-6">
                            <div class="input-group mb-3">
                              <input type="text" class="form-control" v-model="parentrada.nombrearti" placeholder="Articulo" readonly>
                              <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" @click="abrirModalA('articulo','registrar',parentrada.entrada_id)" :disabled="entrada_objeto == null ? '' : entrada_objeto.estado == 3">Buscar</button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="inputArticulo" class="col-md-2 form-control-label">Cantidad solicitada</label>
                          <div class="col-md-3">
                            <div class="input-group mb-2">
                              <input type="text" class="form-control" v-model="parentrada.cantidad_opcional" placeholder="Cantidad" readonly>
                            </div>
                          </div>
                          <label for="inputArticulo" class="col-md-2 form-control-label">Cantidad recibida</label>
                          <div class="col-md-3">
                            <div class="input-group mb-2">
                              <input type="text" class="form-control" :disabled="actualizar_partida == 1" v-model="parentrada.cantidad" placeholder="Cantidad">
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputArticulo" class="col-md-2 form-control-label">Precio Unitario</label>
                          <div class="col-md-3">
                            <div class="input-group mb-3">
                              <input type="text" v-validate="'decimal:2'" data-vv-name="Precio" class="form-control" v-model="parentrada.precio_unitario" placeholder="Precio">
                              <span class="text-danger">{{errors.first('Precio')}}</span>
                            </div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputArticulo" class="col-md-2 form-control-label">Número de Serie</label>
                          <div class="col-md-3">
                            <div class="input-group mb-3">
                              <input type="text"  data-vv-name="Numero" class="form-control"
                              v-model="parentrada.numero_serie" placeholder="# de Serie" >

                          </div>
                        </div>
                      </div>

                      <!-- <div class="form-group row">
                        <label for="inputArticulo" class="col-md-2 form-control-label">Factura</label>
                        <div class="col-md-3">
                          <div class="input-group mb-3">
                            <select class="form-control" v-model="parentrada.factura_id" data-vv-name="Factura" @change="cargarcomprobante($event)">
                              <option v-for="t of lista_facturas" :value="t.id" :key="t.id">{{t.uuid}}</option>
                            </select>
                            <span class="text-danger">{{errors.first('Factura')}}</span>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <template v-if="comprobante != ''">
                            <button type="button" class="form-control" @click="descargarc(comprobante)">
                              <i class="fas fa-file-download"></i>&nbsp;Descargar
                            </button>
                          </template>
                        </div>
                      </div> -->

                      <div class="form-group row">
                        <label for="inputArticulo" class="col-md-2 form-control-label">Caducidad</label>
                        <div class="col-md-6">
                          <div class="input-group mb-3">
                            <input type="date" class="form-control" v-model="parentrada.caducidad" placeholder="Caducidad">
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="card-footer">
                    <button type="button" class="btn btn-outline-dark" @click="cancelar()"><i class="fas fa-window-close"></i>&nbsp;Cancelar</button>
                    <button type="button" v-if="tipoAccionpr==1" class="btn btn-dark" @click="guardarPR(1, parentrada.entrada_id)"><i class="fas fa-plus"></i>&nbsp;Agregar</button>
                    <button type="button" v-if="tipoAccionpr==2" class="btn btn-dark" @click="guardarPR(0, parentrada.entrada_id)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                  </div>
                </div>
                <!-- Fin Nuevo partidas entradas -->

                <!--Inicio del modal agregar articulos-->
                <div class="modal fade" tabindex="-1" :class="{'mostrar' : modala}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                  <vue-element-loading :active="isLoadingg" />
                  <div class="modal-dialog modal-dark modal-lg" role="document">
                    <div class="modal-content">
                      <div>

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
                          <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                        </div>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!--Fin del modal agregar articulo-->

                <!--Inicio del modal agregar almacen-->
                <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalb}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                  <vue-element-loading :active="isLoadingb" />
                  <div class="modal-dialog modal-dark modal-lg" role="document">
                    <div class="modal-content">

                      <div>

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
                              <select class="form-control" id="almacen_id" name="almacen_id" v-model="almacen.id" @change="almac" data-vv-as="Stand">
                                <option v-for="item in listaAlmaceness" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                              </select>
                              <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="stand_id">Stand</label>
                            <div class="col-md-9">
                              <select class="form-control" id="stand_id" name="stand_id" v-model="almacen.stand_id" @change="niv" data-vv-as="Stand">
                                <option v-for="item in listaStand" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                              </select>
                              <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="nivel_id">Nivel</label>
                            <div class="col-md-9">
                              <select class="form-control" id="nivel_id" name="nivel_id" v-model="almacen.nivel_id" data-vv-as="Nivel">
                                <option v-for="item in listaNivel" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                              </select>
                              <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" @click="guardarDatosAlmacen()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                          <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                        </div>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
                <!--Fin del modal agregar almacen-->
              </div>
              <!-- Fin del card detalle -->
              <div v-show="detalle == 2">
                <factura ref="facturas" @atras:facturas="cerrarfacturas()"></factura>
              </div>

            </div>

            <!--Inicio del modal agregar/actualizar Entradas-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
              <vue-element-loading :active="isLoading" />
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
                        <label class="col-md-3 form-control-label" for="comentarios">Comentarios</label>
                        <div class="col-md-9">
                          <input type="text" name="comentarios" v-validate="'required'" v-model="entrada.comentarios" class="form-control" placeholder="Comentarios" autocomplete="off" id="comentarios">
                          <span class="text-danger">{{ errors.first('comentarios') }}</span>
                        </div>
                      </div>
                      <!--
                      <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="formato_entrada">Formato Entrada</label>
                      <div class="col-md-9">
                      <input type="text" name="formato_entrada" v-validate="'required'" v-model="entrada.formato_entrada" class="form-control" placeholder="Formato Entrada" autocomplete="off" id="formato_entrada">
                      <span class="text-danger">{{ errors.first('formato_entrada') }}</span>
                    </div>
                  </div> -->
                  <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="tipo_entrada_id">Tipo Entrada</label>
                    <div class="col-md-9">
                      <select class="form-control" id="tipo_entrada_id" v-validate="'required'" name="tipo_entrada_id" v-model="entrada.tipo_entrada_id" data-vv-as="tipo de entrada">
                        <option v-for="item in listaTipoEntrada" :value="item.id" :key="item.id">{{ item.nombre}}</option>
                      </select>
                      <span class="text-danger">{{ errors.first('tipo_entrada_id') }}</span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="tipo_adq_id">Tipo de Adquisicion</label>
                    <div class="col-md-9">
                      <select class="form-control" id="tipo_adq_id" v-validate="'required'" name="tipo_adq_id" v-model="entrada.tipo_adq_id" data-vv-as="tipo de adquisicion">
                        <option v-for="item_dos in listaTipoAdquisicion" :value="item_dos.id" :key="item_dos.id">{{ item_dos.nombre}} </option>
                      </select>
                      <span class="text-danger">{{ errors.first('tipo_adq_id') }}</span>
                    </div>
                  </div>

                  <div class="form-group row" v-show="tipoAccion != 2">
                    <label class="col-md-3 form-control-label" for="orden_compra_id">Orden de compra</label>
                    <div class="col-md-9">
                      <v-select id="orden_compra" :options="listaOrdenesC"  v-validate="'required'"
                      v-model="orden_compra" label="folio" name="folio"
                      data-vv-name="folio" >
                    </v-select>
                    <span class="text-danger">{{ errors.first('orden_compra_id') }}</span>

                  </div>
                </div>

                <!-- </form> -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarEntrada(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarEntrada(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal Entradas-->

      <!--Inicio del modal Mostrar Lote-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalc}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">

            <div>
              <!-- <vue-element-loading :active="isLoadingc"/> -->
              <div class="modal-header">
                <h4 class="modal-title" v-text="tituloModalc"></h4>
                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="almacen_id">Nombre</label>
                  <div class="col-md-9">
                    <input class="form-control" id="almacen_id" name="almacen_id" v-model="lote_nombre" @change="almac" data-vv-as="Stand">
                    <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary"><i class="fas fa-print"></i>&nbsp;Imprimir</button>
                <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal agregar almacen-->

      <!--Inicio del modal código de barras-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalCodigo}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div>
              <div class="modal-header">
                <h4 class="modal-title">Código generado</h4>
                <button type="button" class="close" @click="cerrarModalCodigo()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group row">
                  <img class="mx-auto" :src="codigo_barras" alt="codigo" />
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" @click="cerrarModalCodigo()">
                  <i class="fas fa-window-close"></i>&nbsp;Cerrar
                </button>
                <button type="button" class="btn btn-secondary" @click="imprimirCodigo()">
                  <i class="fas fa-print"></i>&nbsp;Imprimir
                </button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal código de barras-->
    </div>
  </div>
  <!--Fin  del modal Mostrar Lote-->
</main>
</template>


<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Facturas = r => require.ensure([], () => r(require('./Facturas.vue')), 'alm');

export default {
  data() {
    return {
      codigo_barras: '',
      modalCodigo: 0,
      codigo_texto: '',
      actualizar_partida: 0,
      orden_compra: {},
      PermisosCRUD: {},
      condiciones: 0,
      empleado_responsable: 0,
      url: '/entrada',
      entrada_objeto: null,
      datospartida: null,
      detalle: false,
      factura: false,
      comprobante: '',
      entrada: {
        id: 0,
        fecha: '',
        comentarios: '',
        formato_entrada: '',
        ord_compra_id: '',
        tipo_adq_id: '',
        tipo_entrada_id: '',
        condicion: 0,
        nombrea: '',
        nombree: '',
        disabled: true,
        orden_compra_id: '',
      },
      almacen: {
        id: 0,
        ida: '',
        nombre: '',
        stand_id: 0,
        stand: '',
        nivel_id: 0,
        nivel: '',
      },
      parentrada: {
        entrada_id: 0,
        req_com_id: 0,
        articulo_id: 0,
        nombrearti: '',
        fechaentrada: '',
        orden_n_id: 0,
        proveedore_id: 0,
        validacion_calidad: 0,
        cantidad: '',
        caducidad: '',
        stocke_id: 0,
        cantidad_opcional: '',
        precio_unitario: '',
        factura_id: '',
        id: 0,
        numero_serie : '',
      },
      lote_nombre: '',
      tituloModalc: '',
      listaOrdenesC: [],
      listaTipoAdquisicion: [],
      listaTipoEntrada: [],
      listaProvedores: [],
      UsoFactura: [],
      dataTableArticulo: [],
      optionsvs: [],
      listaNivel: [],
      listaStand: [],
      lista_facturas: [],
      listaAlmaceness: [],
      modal: 0,
      modala: 0,
      modalb: 0,
      modalc: 0,
      modaldoc: 0,
      tituloModal: '',
      tituloModala: '',
      tituloModalb: '',
      tipoAccion: 0,
      tipoAccionpr: 0,
      disabled: 0,
      isLoading: false,
      isLoadingg: false,
      isLoadingb: false,
      isLoadingDetalle: false,
      columnsa: ['ocf', 'descripciona', 'ocfo', 'prazonsocial', 'proyectonombre', 'cantidad_entrada'],
      columns: ['id', 'foliocompra', 'fecha', 'comentarios', 'nombrea', 'nombree', 'condicion', 'estado'],
      tableData: [],
      columnspartidas: ['articulo_id', 'req_com_id', 'foliocompra', 'ad', 'amarca', 'id', 'validacion_calidad', 'cantidad', 'precio_unitario'],
      tableDataPartidas: [],
      optionsa: {
        headings: {
          'ocf': 'Folio orden de compra',
          'descripciona': 'Descripción artículo',
          'ocfo': 'Fecha de la orden',
          'prazonsocial': 'Proveedor',
          'proyectonombre': 'Proyecto',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        //  sortable: ['entrada.folio'],
        //  filterable: ['entrada.folio'],
        filterByColumn: true,
        texts: config.texts
      },
      options: {
        headings: {
          'id': 'Acciones',
          'fecha': 'Fecha',
          'foliocompra': 'Folio',
          'comentarios': 'Comentarios',
          'formato_entrada': 'Formato de entrada',
          'nombrea': 'Tipo de adquisición',
          'nombree': 'Tipo de entrada',
          'condicion': 'Condición',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha', 'foliocompra', 'comentarios', 'nombrea', 'nombree', 'condicion'],
        filterable: ['id', 'fecha', 'foliocompra', 'comentarios', 'nombrea', 'nombree'],
        filterByColumn: true,
        texts: config.texts
      },
      optionspartidas: {
        headings: {
          'articulo_id': 'Acciones',
          'req_com_id': '',
          'foliocompra': 'Folio compra',
          'amarca': 'Marca',
          'ad': 'Artículo',
          'validacion_calidad': 'Validación calidad',
          'cantidadcompra': 'Cantidad',
          'id': 'Almacenar',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['id', 'foliocompra', 'amarca', 'ad'],
        filterable: ['foliocompra', 'amarca', 'ad'],
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
      texts: config.texts
    },
  }
},
components: {
  'factura': Facturas,
},
methods: {
  /**
  * [vcalidad Metodo el cual hace la validacion de calidad donde se pase de tener la partida en un lote temporal a un lote ya definido]
  * @param  {Array}  [data=[]] [description]
  * @param  {Int} c         [description]
  * @return {Response}           [status => true]
  */
  vcalidad(data = [], ce) {
    var reinicio = 0;
    var estado_doc = [];
    var cadena = [];
    var cadenaid = [];
    var cadenanum = [];
    var steps = [];
    let me = this;
    if (ce == 3) {
      reinicio = 1;
    }
    // me.isLoadingDetalle = true;
    let formData = new FormData();
    axios.get('/partidaentrada'+'/'+data.id).then(function (response) {
      if (response.data[0].almacene_id !=  null) {
        // descometar Esta linea
        // axios.get('/documentosrequeridosarticulos'+'/'+data.req_com_id+'&'+data.articulo_id).then(function (response) {//busqueda de los documentos por articulos
        //inicio compruba que no existan documentos pendientes

        // if (response.data.length == 0 ) {//decomentar esta linea subida de archivos
        me.isLoadingDetalle = true;
        axios.post('/partidaentrada/calidad', {
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
          me.cargardetalle(me.entrada_objeto);
          me.isLoadingDetalle = false;
          toastr.success('Validado Correctamente')
          me.abrirModalC(response.data)
          reinicio = 1;
        })
        .catch(function (error) {
          console.log(error);
        });
        // }//descomentar Esta linea subida de archivos
        //fin docuemntos pendientes

        // inicio comentatios
        //  script nesearios para la subida de archivos
        //  descomentar

        //inicio llenado de datos del swal
        // for (var j = 0; j < response.data.length; j++) {
        //   cadena.push(response.data[j].nombre);
        //   cadenaid.push(response.data[j].documento_id);
        // }
        // for (var i = 0; i < cadena.length; i++) {
        //   steps.push ( 'Documento '+cadena[i] );
        //   cadenanum.push(i+1);
        // }
        //fin llenado de datos del swal
        // swal.setDefaults({
        //   html: '<br><div class="form-group"> <label for="estado-documento"><h5>Condición del Documento:</h5></label> <select class="form-control" id="estado-documento"> <option value="1">CONFIRMADO</option> <option value="0">PENDIENTE</option> </select> </div><br>',
        //   input: 'file',
        //   inputAttributes: {
        //     'accept': 'image/jpeg,application/pdf',
        //     'aria-label': 'Upload your profile picture'
        //   },
        //   confirmButtonText: 'Siguiente &rarr;',
        //   showCancelButton: true,
        //   preConfirm: () => {
        //     //console.log(document.getElementById('estado-documento').value)
        //     estado_doc.push(document.getElementById('estado-documento').value);
        //   },
        //   inputValidator: (file) => {
        //     return !file && " Por Favor Adjunte El Archivo Solicitado e Indique La Condición En Que Se Encuentra. "
        //   },
        //   cancelButtonText : 'Cerrar',
        //   progressSteps: cadenanum
        // })
        //carga del resultado
        //si no hay archivo se manda null
        //   swal.queue(steps).then(function (result) {
        //
        //     if (result.value == '') {
        //       swal.resetDefaults()
        //       swal({
        //         title: 'Hecho!',
        //         html:
        //         'Documentos Requeridos:  ' +
        //         '¡No Se Solicitaron Documentos! ' +'<pre>'+
        //         '</pre>',
        //         confirmButtonText: 'OK!'
        //       })
        //     }
        //     else{
        //       swal.resetDefaults()
        //       swal({
        //         title: 'Hecho!',
        //         html:
        //         +'<pre>'+'<pre>'+'<pre>'+
        //         'Documentos Requeridos: <pre>' +
        //         JSON.stringify(result) +'<pre>'+
        //         '</pre>',
        //         confirmButtonText: 'OK!'
        //       })
        //     }
        //     swal.resetDefaults()
        //     if (reinicio == 1) {
        //       Swal.fire({
        //         title: 'VALIDADO',
        //         text: "¡VALIDACIÓN EXITOSA!",
        //         type: 'success',
        //         confirmButtonColor: '#68E777',
        //         confirmButtonText: 'OK!'
        //       }).then((result) => {
        //         if (result.value) {
        //         }
        //       })
        //       //console.log('ya se apreto OK1');
        //     }
        //     if (reinicio == 0) {
        //       Swal.fire({
        //         title: 'FINALIZADO',
        //         text: "¡CARGA DE ARCHIVOS EXITOSA!",
        //         type: 'success',
        //         confirmButtonColor: '#68E777',
        //         confirmButtonText: 'OK!'
        //       }).then((result) => {
        //         if (result.value) {
        //           //console.log('ya se apreto OK2');
        //           me.vcalidad(data,3);
        //           reinicio = 0;
        //         }
        //       })
        //       //console.log('ya se apreto OK1');
        //     }
        //
        //     //fin de carga
        //     //si no clic en el boton de cancelar valida calidad
        //     if (result.dismiss) {
        //       swal.resetDefaults()
        //       swal({
        //         title: '¡CANCELADO!',
        //         html:
        //         'Se Ha Cancelado La Carga De ¡Documentos Requeridos!' +
        //         '</pre>',
        //         confirmButtonText: 'OK!'
        //       })
        //     }
        //     if (!result.dismiss) {
        //
        //       for (var k = 0; k < result.value.length; k++) {
        //         var adjuntos = result.value[k];
        //
        //
        //         if (adjuntos != null) {
        //           let formData = new FormData();
        //           formData.append('adjunto', result.value[k]);
        //           //console.log('Archivo',result.value[k].name);
        //           formData.append('partidaentrada_id', data.id);
        //           //console.log('Partida ID',data.id);
        //           formData.append('documento_id', cadenaid[k]);
        //           //console.log('Documento ID',cadenaid[k]);
        //           formData.append('orden_compra_id', data.req_com_id);
        //           //console.log('Orden Compra ID',data.req_com_id);
        //           formData.append('articulo_id', data.articulo_id);
        //           //console.log('Articulo ID',data.articulo_id);
        //           //console.log('***********************************');
        //           formData.append('empleado_responsable_id', me.empleado_responsable.empleado_id)
        //           //console.log(me.empleado_responsable.empleado_id);
        //           formData.append('estado_documento', estado_doc[k]);
        //           //console.log(estado_doc[k]);
        //
        //
        //           axios.post('/certificadosdocumentos/cargar',formData)
        //           .then(function (response) {
        //             console.log(response.data);
        //             reinicio = 0;
        //           })
        //           .catch(function (error) {
        //             console.log(error);
        //           });
        //         }
        //         else {
        //           swal.resetDefaults()
        //           swal({
        //             title: '¡SIN DOCUMENTOS!',
        //             html:
        //             'No Se Seleccionaron Documentos Para Subir Al Sistema' +
        //             '</pre>',
        //             confirmButtonText: 'OK!'
        //           })
        //         }
        //       }
        //
        //   }
        //
        //   // fin if
        // }, function () {
        //   swal.resetDefaults()
        // })
        // }).catch(function (error) {
        //   console.log(error);
        // });

        // fin Comentarios
        // script nesearios para la subida de archivos de cada articulos
        // documentos requeridos por articulos

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
  vcalidadsalida(data = []) {
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
      let me = this;
      if (result.value) {
        var val = result.value;
        axios.get('/partidaentrada' + '/' + data.entrada_id + '&' + data.req_com_id + '&' + data.articulo_id + '/calidadsalida').then(function (response) {
          me.cargardetalle(me.entrada_objeto);
          me.isLoadingDetalle = false;

        }).catch(function (error) {
          console.log(error);
        });
      } else {
        console.log('false');
      }
    });
  },

  /**
  * [almac Metodo de consulta a la BD ]
  * @return {Response} [description]
  */
  almac() {
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
  niv() {
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
    let me = this;
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

    axios.get('/proveedores/activos/1').then(response => {
      response.data.forEach(data => {
        me.listaProvedores.push({
          id: data.id,
          name: data.nombre + ' ' + data.razon_social,
        });
      });
    })
    .catch(function (error) {
      console.log(error);
    });

    axios.get('/satcatusocfdi?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {
      this.UsoFactura = response.data.data;
    }).catch(error => {
      console.error(error);
    });

  },

  /**
  * [getArticulosEntrada Metodo de consulta a la BD ]
  * @return {Response} [objeto almacenado en la variable dataTableArticulo]
  */
  getArticulosEntrada() {
    let me = this;

    axios.get('/verordenescompras').then(response => {
      me.listaOrdenesC = [];
      response.data.forEach(o => {
        me.listaOrdenesC.push({
          id: o.id,
          folio: `Folio: ${o.folio}`,
        });
      });
    })
    .catch(function (error) {
      console.log(error);
    });
  },

  /**
  * [getData Metodo de consulta a la BD ]
  * @return {Response} [description]
  */
  getData() {
    this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
    this.isLoading = true
    let me = this;
    axios.get('/entrada').then(response => {
      me.tableData = response.data;
      this.isLoading = false;
    })
    .catch(function (error) {
      console.log(error);
    });

    axios.get('/Solicitud').then(response => {
      this.empleado_responsable = response.data;
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
  eliminarpartidaentrada(data = [], activar) {
    if (!activar) {
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
        axios.post('/partidaentrada/eliminarpartidaentrada', {
          articulo_id: data.articulo_id,
          orden_compra_id: data.req_com_id,
          partida_entrada_id: data.id,
          cantidad: data.cantidad,
        })
        .then(function (response) {
          me.cargardetalle(me.entrada_objeto);
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
  actdesact(id, activar) {
    if (activar) {
      this.swal_titulo = 'Esta seguro de activar esta entrada?';
      this.swal_tle = 'Activado';
      this.swal_msg = 'El registro ha sido activado con éxito.';
    } else {
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
        axios.get(me.url + '/' + id + '/edit').then(function (response) {
          if (activar) {
            toastr.success(me.swal_tle, me.swal_msg, 'success');

          } else {
            toastr.error(me.swal_tle, me.swal_msg, 'error');

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
          me.getData();
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
  guardarPR(nuevo, idr) {
    if (this.parentrada.cantidad === '' || this.parentrada.precio_unitario === '') {
      toastr.warning('Atencion complete los campos');
    } else {
      this.isLoading = false;
      this.detalle = true;
      this.isLoadingg = true;
      let me = this;

      axios({
        method: nuevo ? 'POST' : 'PUT',
        url: nuevo ? '/partidaentrada' : `partidaentrada/update/factura/${this.parentrada.id}`,
        data: {
          'entrada_id': this.parentrada.entrada_id,
          'req_com_id': this.parentrada.req_com_id,
          'articulo_id': this.parentrada.articulo_id,
          'proveedore_id': this.parentrada.proveedore_id,
          'caducidad': this.parentrada.caducidad,
          'cantidad': this.parentrada.cantidad,
          'cantidad_opcional': this.parentrada.cantidad_opcional,
          'stocke_id': this.parentrada.stocke_id,
          'precio_unitario': this.parentrada.precio_unitario,
          'factura_id': this.parentrada.factura_id,
          'numero_serie' : this.parentrada.numero_serie,
          'id': this.parentrada.id,
        }
      }).then(function (response) {
        me.isLoading = false;
        me.isLoadingg = false;
        if (response.data.status) {
          me.cargardetalle(me.entrada_objeto);
          me.cerrarModal();
          me.cancelar();
          me.getData();
          me.getArticulosEntrada();
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
  guardarEntrada(nuevo) {
    this.$validator.validate().then(result => {
      if (result) {
        this.isLoading = true;
        this.detalle = false;
        this.isLoadingg = false;
        let me = this;
        axios({
          method: nuevo ? 'POST' : 'PUT',
          url: nuevo ? me.url : me.url + '/' + this.entrada.id,
          data: {
            'id': this.entrada.id,
            'fecha': this.entrada.fecha,
            'comentarios': this.entrada.comentarios,
            'formato_entrada': this.entrada.formato_entrada,
            'tipo_adq_id': this.entrada.tipo_adq_id,
            'tipo_entrada_id': this.entrada.tipo_entrada_id,
            'orden_compra_id': me.orden_compra.id
          }
        }).then(function (response) {
          me.isLoading = false;
          if (response.data.status) {
            me.cerrarModal();
            me.getData();
            if (!nuevo) {
              toastr.success('Entrada Actualizada Correctamente');
            } else {
              toastr.success('Entrada Registrada Correctamente');
            }
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
  guardarDatosAlmacen() {

    let me = this;
    axios.put('/partidaentrada/update/almacen/' + me.datospartida.id, {
      'id': me.datospartida.id,
      'entrada_id': me.datospartida.entrada_id,
      'orden_compra_id': me.datospartida.req_com_id,
      'articulo_id': me.datospartida.articulo_id,
      'almacene_id': me.almacen.id,
      'nivel_id': me.almacen.nivel_id,
      'stand_id': me.almacen.stand_id,
    }).then(function (response) {
      if (response.data.status) {
        toastr.success('Guardado Correctamente')
        me.cargardetalle(me.entrada_objeto);
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
  /**
  * [cerrarModal description]
  */
  cerrarModal() {
    this.modal = 0;
    this.modala = 0;
    this.modalb = 0;
    this.modalc = 0;
    this.modaldoc = 0;
    this.tituloModal = '';
    Utilerias.resetObject(this.requisicion);
  },
  abrirModalA(modelo, accion, id, data = []) {
    switch (modelo) {
      case "articulo": {
        switch (accion) {
          case 'registrar': {
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

  abrirModalB(data = []) {
    this.modalb = 1;
    this.tituloModalb = data['almacene_id'] != null ? 'Artículo almacenado en ...' : 'Agregar a almacen';
    this.datospartida = data;
    this.almacen.id = data['almacene_id'];
    let me = this;
    var idalmacen = data['almacene_id'];
    axios.get('/almacen/verstand/' + idalmacen).then(response => {
      me.listaStand = response.data;
      this.almacen.stand_id = data['stand_id'];
      var idstand = data['stand_id'];
      axios.get('/almacen/vernivel/' + idstand).then(response => {
        me.listaNivel = response.data;
        this.almacen.nivel_id = data['nivel_id'];
      })
    })
    .catch(function (error) {
      console.log(error);
    });
  },

  abrirModalC(data = []) {
    this.modalc = 1;
    this.tituloModalc = 'Numero de lote:';
    this.datospartida = data;
    this.lote_nombre = data['lote_nombre'];
  },
  /************/
  abrirModal(modelo, accion, data = []) {
    this.getArticulosEntrada();
    switch (modelo) {
      case "entrada": {
        switch (accion) {
          case 'registrar': {
            let me = this;
            Utilerias.resetObject(me.entrada);
            this.modal = 1;
            this.tituloModal = 'Registrar entrada';
            this.tipoAccion = 1;
            this.disabled = 0;
            break;
          }
          case 'actualizar': {
            let me = this;
            Utilerias.resetObject(me.entrada);
            this.modal = 1;
            this.tituloModal = 'Actualizar entrada';
            this.entrada.id = data['id'];
            this.tipoAccion = 2;
            this.disabled = 1;
            this.entrada.fecha = data['fecha'];
            this.entrada.comentarios = data['comentarios'];
            this.entrada.formato_entrada = data['formato_entrada'];
            this.entrada.tipo_adq_id = data['tipo_adq_id'];
            this.entrada.tipo_entrada_id = data['tipo_entrada_id'];
            this.entrada.orden_compra_id = data['orden_compra_id'];
            break;
          }
        }
      }
    }
  },
  /**
  * [cargardetalle Carga las partidas de la entrada seleccionada]
  * @param  {Array}  [data=[]] [description]
  * @return {Response}                   [objeto almacenado en la variable tableDataPartidas]
  */
  cargardetalle(data = []) {
    this.detalle = true;
    this.isLoadingDetalle = true;
    this.isLoading = true;
    this.entrada_objeto = data;
    this.parentrada.entrada_id = data.id;

    let me = this;

    axios.get('/verfinalizacionpartida/' + data.id).then(response => {}).catch(function (error) {
      console.log(error);
    });

    axios.get(me.url + '/' + data.id).then(response => {
      me.tableDataPartidas = response.data;
      me.isLoadingDetalle = false;
    }).catch(function (error) {
      console.log(error);
    });

    axios.get(`facturasentradas/${data.id}&Entrada&${data.orden_compra_id}`).then(response => {
      me.lista_facturas = response.data;
    }).catch(error => {
      console.log(error);
    });

    axios.get('/verordencompra/' + data.orden_compra_id).then(response => {
      me.dataTableArticulo = response.data[0].datos;
    }).catch(function (error) {
      console.log(error);
    });

  },

  terminos() {
    this.condiciones = 1;
  },

  subirDocumento(data, metodo) {
    swal({
      title: 'Documento P.O.',
      input: 'file',
      inputAttributes: {
        'accept': 'application/pdf',
        'aria-label': 'Upload your profile picture'
      },
      confirmButtonText: 'Subir Archivo',
      showCancelButton: true,
      inputValidator: (file) => {
        return !file && 'Este Campo no puede estar Vacío'
      }
    }).then((file) => {
      let me = this;
      if (file.value) {
        let formData = new FormData();

        formData.append('metodo', metodo);
        formData.append('documento_po', file.value);
        formData.append('id', data.id);

        axios.post(me.url, formData).then(function (response) {
          if (response.data.status) {
            if (metodo == 1) {
              toastr.success('Archivo Subido Correctamente');
            }
            if (metodo == 2) {
              toastr.success('Archivo Actualizado Correctamente');
            }
            me.listar();
          } else {
            swal({
              type: 'error',
              html: response.data.errors.join('<br>'),
            });
          }
        }).catch(function (error) {
          console.log(error);
        });
      } else if (file.dismiss === swal.DismissReason.cancel) {}
    })
  },

  maestro() {
    this.detalle = false;
    this.isLoading = false;
    this.cancelar();
    this.getData();

  },

  cancelar() {
    this.parentrada.articulo_id = 0;
    this.parentrada.req_com_id = 0;
    this.parentrada.nombrearti = "";
    this.parentrada.caducidad = "";
    this.parentrada.cantidad = '';
    this.parentrada.cantidad_opcional = '';
    this.parentrada.precio_unitario = '';
    this.parentrada.factura_id = '';
    this.parentrada.numero_serie = '';
    this.tipoAccionpr = 0;
    this.actualizar_partida = 0;
  },

  abrirBusquedaArticulo() {
    this.tipoAccionpr = 1;
    this.modal = 1;
  },

  seleccionarArticulo2(data) {
    let me = this;
    this.parentrada.req_com_id = data.row.id;
    this.parentrada.articulo_id = data.row.ida;
    this.parentrada.nombrearti = data.row.descripciona;
    this.parentrada.proveedore_id = data.row.idproveedor;
    this.parentrada.cantidad = data.row.cantidad_entrada;
    this.parentrada.cantidad_opcional = data.row.cantidad_entrada;
    this.parentrada.stocke_id = data.row.stokeid;
    me.cerrarModal();
  },

  descargar(data) {
    window.open('descargar-entrada/' + data.id, '_blank');
    let me = this;
    me.getData();
  },

  descargarnuevo(data) {
    window.open('descargar-entrada-nuevo-formato/' + data.id, '_blank');
    let me = this;
    me.getData();
  },

  ver_facturas(data) {
    this.detalle = 2;
    var childfacturas = this.$refs.facturas;
    childfacturas.cargar(data, this.listaProvedores, this.UsoFactura);
  },

  cerrarfacturas() {
    this.detalle = 0;
  },

  enviar_revision(data) {
    Swal.fire({
      title: 'Esta seguro(a) de enviar a revisión?',
      text: "Esta opción no se podrá desahacer!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si',
      cancelButtonText: 'No',
    }).then((result) => {
      if (result.value) {
        axios.get(`entradas/revisionfactura/${data.id}`).then(response => {
          this.getData();
        }).catch(error => {
          console.error(error);
        });
      }
    });

  },

  actualizarpartidaentrada(data) {
    this.actualizar_partida = 1;
    this.parentrada.nombrearti = data.ad;
    this.parentrada.cantidad_opcional = data.cantidadcompra;
    this.parentrada.cantidad = data.cantidad;
    this.parentrada.precio_unitario = data.precio_unitario;
    this.parentrada.caducidad = data.caducidad;
    this.parentrada.id = data.id;
    this.parentrada.numero_serie = data.numero_serie;
    this.tipoAccionpr = 2;
    this.parentrada.factura_id = data.factura_id;
  },

  cargarcomprobante(data) {
    // console.log(data);
    var i = data.target.selectedIndex
    this.comprobante = this.lista_facturas[i].comprobante;
  },

  descargarc(archivo) {
    let me = this;
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
      .then(response => {})
      .catch(function (error) {
        console.log(error);
      });
      //--fin del metodo borrar--//
    });
  },

  descargarCodigoBarras(data){
    window.open('descargar/codigo/barras/' + data.id, '_blank');
  }

},

mounted() {
  this.getData();
  this.getListas();
  // this.getArticulosEntrada();
}
}
</script>
