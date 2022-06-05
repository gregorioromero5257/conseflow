<template>
<div>
    <!-- Listado de requisiocnes -->
    <div class="card">
        <vue-element-loading :active="isLoading" />
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Detalles de requisicion pertenecientes al proyecto: {{ requisiicion== null ? '': requisiicion.p_nombre_corto +' con folio '+ requisiicion.folio }}
            <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
                <i class="fas fa-arrow-left"></i>&nbsp;Atras
            </button>
        </div>

        <div class="accordion" id="accordion">
            <div class="card">
                <div class="card-header bg-dark justify-content-center" id="headingTres">
                    <h5 class="mb-0">
                        <button class="btn btn-dark collapsed" type="button" data-toggle="collapse" data-target="#collapseCuatro" aria-expanded="false" aria-controls="collapseCuatro">
                            <b> SERVICIOS </b>
                        </button>
                        <button class="btn btn-dark collapsed" type="button" data-toggle="collapse" data-target="#collapseTres" aria-expanded="false" aria-controls="collapseTres">
                            <b> ARTICULOS</b>
                        </button>
                    </h5>
                </div>
                <div id="collapseTres" class="collapse" aria-labelledby="headingTres" data-parent="#accordion">

                    <v-client-table :columns="columnsdetallerequisicion" :data="tableDatadetallerequisicion" :options="optionsdetallerequisicion" ref="myTabledescuento">
                        <template slot="req.narticulo" slot-scope="props">
                            <template v-if="props.row.correccion != null">
                                <span class="text-danger">{{props.row.req.descripcion}}&nbsp;{{props.row.correccion.comentario}}</span>
                            </template>
                            <template v-else>
                                <span>{{props.row.req.narticulo}}</span>
                            </template>
                        </template>
                        <template slot="req.equivalente" slot-scope="props">
                            <template v-if="props.row.req.equivalente">
                                <button type="button" class="btn btn-success">SI</button>
                            </template>
                            <template v-else>
                                <button type="button" class="btn btn-danger">No</button>
                            </template>
                        </template>
                        <template slot="req.articulo_id" slot-scope="props">

                            <!-- <button type="button" class="btn btn-danger"
                            v-bind:disabled ="requisiicion.estado_id == 1 || requisiicion.estado_id == 2 || requisiicion.estado_id == 3 || requisiicion.estado_id == 5 || requisiicion.estado_id == 6 || requisiicion.estado_id == 7"
                            @click="actdesactreq(props.row.req.pda, preq.requisicione_id,0,null,props)">
                            <i class="icon-trash"></i>&nbsp;
                          </button>
                          <button type="button" class="btn btn-primary"
                            v-bind:disabled ="requisiicion.estado_id == 1 || requisiicion.estado_id == 2 || requisiicion.estado_id == 3 || requisiicion.estado_id == 5 || requisiicion.estado_id == 6 || requisiicion.estado_id == 7"
                            @click="abrirModalA('articulo','actualizar',props.row.req.articulo_id,props.row.req)">
                            <i class="far fa-file"></i>&nbsp;&nbsp;
                          </button>
                          <a type="button" class="btn btn-warning" href="#formulario"
                            v-bind:disabled ="requisiicion.estado_id == 1 || requisiicion.estado_id == 2 || requisiicion.estado_id == 3 || requisiicion.estado_id == 5 || requisiicion.estado_id == 6 || requisiicion.estado_id == 7"
                            @click="abrirModalA('articulo','actualizar_articulo',0,props.row.req)">
                            <i class="far fa-file"></i>&nbsp;&nbsp;
                          </a> -->
                            <div class="dropdown2">
                                <button class="dropbtn2"><i class="fas fa-cogs"></i></i></button>
                                <div class="dropdown-content2">
                                    <a class="button2" v-bind:disabled="requisiicion.estado_id == 1 || requisiicion.estado_id == 2 || requisiicion.estado_id == 3 || requisiicion.estado_id == 5 || requisiicion.estado_id == 6 || requisiicion.estado_id == 7" @click="actdesactreq(props.row.req.pda, preq.requisicione_id,0,null,props)"><i class="far fa-window-close"></i>&nbsp; Remover</a>
                                    <a class="button2" v-bind:disabled="requisiicion.estado_id == 1 || requisiicion.estado_id == 2 || requisiicion.estado_id == 3 || requisiicion.estado_id == 5 || requisiicion.estado_id == 6 || requisiicion.estado_id == 7" @click="abrirModalA('articulo','actualizar',props.row.req.articulo_id,props.row.req)"><i class="far fa-file-alt"></i>&nbsp; Actualizar Documentos</a>
                                    <a class="button2" href="#formulario" v-bind:disabled="requisiicion.estado_id == 1 || requisiicion.estado_id == 2 || requisiicion.estado_id == 3 || requisiicion.estado_id == 5 || requisiicion.estado_id == 6 || requisiicion.estado_id == 7" @click="abrirModalA('articulo','actualizar_articulo',0,props.row.req)"><i class="far fa-edit"></i>&nbsp; Actualizar</a>
                                </div>
                            </div>

                        </template>
                    </v-client-table>
                </div>
                <div id="collapseCuatro" class="collapse" aria-labelledby="headingTres" data-parent="#accordion">
                    <v-client-table :columns="columnsdetallerequisicionServ" :data="tableDatadetallerequisicionServ" :options="optionsdetallerequisicionServ" ref="myTabledescuento">
                        <template slot="req.nservicio" slot-scope="props">
                            <template v-if="props.row.correccion != null">
                                <span class="text-danger">{{props.row.req.descripcion}}&nbsp;{{props.row.correccion.comentario}}</span>
                            </template>
                            <template v-else>
                                <span>{{props.row.req.nservicio}}</span>
                            </template>
                        </template>
                        <template slot="req.equivalente" slot-scope="props">
                            <template v-if="props.row.req.equivalente">
                                <button class="btn btn-success">SI</button>
                            </template>
                            <template v-else>
                                <button class="btn btn-danger">NO</button>
                            </template>
                        </template>

                        <template slot="req.servicio_id" slot-scope="props">

                            <div class="dropdown2">
                                <button class="dropbtn2"><i class="fas fa-cogs"></i></button>
                                <div class="dropdown-content2">
                                    <a class="button2" type="button" v-bind:disabled="requisiicion.estado_id == 1 || requisiicion.estado_id == 2 || requisiicion.estado_id == 3 || requisiicion.estado_id == 5 || requisiicion.estado_id == 6 || requisiicion.estado_id == 7" @click="actdesactreq(null, preq.requisicione_id,0,props.row.req.pda,null)"><i class="far fa-window-close"></i>&nbsp; Remover</a>
                                    <a class="button2" type="button" v-bind:disabled="requisiicion.estado_id == 1 || requisiicion.estado_id == 2 || requisiicion.estado_id == 3 || requisiicion.estado_id == 5 || requisiicion.estado_id == 6 || requisiicion.estado_id == 7" @click="abrirModalA('articulo','actualizar',props.row.req.articulo_id,props.row.req)"><i class="far fa-file-alt"></i>&nbsp; Actualizar Documentos</a>
                                    <a class="button2" type="button" v-bind:disabled="requisiicion.estado_id == 1 || requisiicion.estado_id == 2 || requisiicion.estado_id == 3 || requisiicion.estado_id == 5 || requisiicion.estado_id == 6 || requisiicion.estado_id == 7" href="#formulario" @click="abrirModalA('articulo','actualizar_servicio',0,props.row.req)"><i class="far fa-edit"></i>&nbsp; Actualizar</a>
                                </div>
                            </div>

                            <!-- <button type="button" class="btn btn-danger"
                    v-bind:disabled ="requisiicion.estado_id == 1 || requisiicion.estado_id == 2 || requisiicion.estado_id == 3 || requisiicion.estado_id == 5 || requisiicion.estado_id == 6 || requisiicion.estado_id == 7"
                    @click="actdesactreq(null, preq.requisicione_id,0,props.row.req.pda,null)">
                    <i class="icon-trash"></i>&nbsp;
                  </button>
                  <button type="button" class="btn btn-primary"
                    v-bind:disabled ="requisiicion.estado_id == 1 || requisiicion.estado_id == 2 || requisiicion.estado_id == 3 || requisiicion.estado_id == 5 || requisiicion.estado_id == 6 || requisiicion.estado_id == 7"
                    @click="abrirModalA('articulo','actualizar',props.row.req.articulo_id,props.row.req)">
                    <i class="fas fa-list"></i>&nbsp;
                  </button>
                  <a type="button" class="btn btn-warning" href="#formulario"
                    v-bind:disabled ="requisiicion.estado_id == 1 || requisiicion.estado_id == 2 || requisiicion.estado_id == 3 || requisiicion.estado_id == 5 || requisiicion.estado_id == 6 || requisiicion.estado_id == 7"
                    @click="abrirModalA('articulo','actualizar_servicio',0,props.row.req)">
                    <i class="fas fa-file"></i>&nbsp;
                  </a> -->

                        </template>
                    </v-client-table>
                </div>
            </div>
        </div>
    </div>

    <!-- Nuevo y editar partidas requisiciones -->
    <div class="card" ref="formLote">
        <!-- <vue-element-loading :active="isLoadingg"/> -->
        <div class="card-header">
        </div>
        <div class="card-body" id="formulario">
            <form>
                <input type="text" class="form-control" id="requisicione_id" name="requisicione_id" v-model="preq.requisicione_id" placeholder="Articulo" hidden>
                <input type="text" class="form-control" id="articulo_id" name="articulo_id" v-model="preq.articulo_id" placeholder="Articulo" hidden>
                <input type="text" class="form-control" id="pda" name="pda" v-model="preq.pda" placeholder="Partida" hidden>
                <input type="text" class="form-control" id="documentacionreid" name="documentacionreid" v-model="preq.documentacionreid" placeholder="Articulo" hidden>

                <div class="form-group row">
                    <label for="inputArticulo" class="col-md-3 form-control-label">Articulo/Servicio</label>
                    <div class="col-sm-9">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="preq.nombrearti" placeholder="Articulo/Servicio" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" v-bind:disabled="requisiicion == null ? '' : (requisiicion.estado_id == 1 || requisiicion.estado_id == 2 || requisiicion.estado_id == 3 || requisiicion.estado_id == 5 || requisiicion.estado_id == 7 || tipoAccionpr == 2)" @click="abrirModalA('articulo','registrar',preq.requisicione_id)">Buscar</button>
                                <button class="btn btn-secondary" type="button" @click="agregarArt()">
                                    Agregar</button>
                            </div>
                        </div>
                        <articulo ref="articulo" @cerrarAbrir="abrirBusArticulo()" @cerrar="abrirBusArticulo()"></articulo>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="comentario">Comentario</label>
                    <div class="col-sm-9">
                        <textarea type="text" name="comentario" class="form-control" aria-label="With textarea" v-model="preq.comentario" autocomplete="off" id="comentario"></textarea>
                        <!--
              <input type="text" name="comentario" :maxlength="255" class="form-control" v-model="preq.comentario" autocomplete="off" id="comentario">
-->
                        <span class="text-danger">{{ errors.first('comentario') }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="folio">Peso en kilogramos</label>
                    <div class="col-sm-9">
                        <input type="text" v-validate="'decimal:3'" name="peso" v-model="preq.peso" v-bind:class="'form-control'+clases.peso" autocomplete="off" id="peso" v-bind:disabled="validar==true">
                        <span class="text-danger">{{ errors.first('peso') }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 form-control-label" for="cantidad">Cantidad</label>
                    <div class="col-md-9">
                        <input type="text" v-validate="'decimal:2'" name="cantidad" v-model="preq.cantidad" v-bind:class="'form-control'+clases.cantidades" autocomplete="off" id="cantidad" v-bind:disabled="validar_pendiente==true">
                        <span class="text-danger">{{ errors.first('cantidad') }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="equivalente">Equivalente</label>
                    <div class="col-md-9">
                        <select class="form-control" id="equivalente" v-validate="'required'" name="equivalente" v-model="preq.equivalente" data-vv-name="equivalente" v-bind:disabled="validar_pendiente==true">
                            <option value="1">SI</option>
                            <option value="0">NO</option>
                        </select>
                        <span class="text-danger">{{ errors.first('equivalente') }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="fecha_requerido">Fecha Requerida</label>
                    <div class="col-sm-9">
                        <input type="date" name="fecha_requerido" v-model="preq.fecha_requerido" v-bind:class="'form-control'+clases.fecharequerido" autocomplete="off" id="fecha_requerido">
                        <span class="text-danger">{{ errors.first('fecha_requerido') }}</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputArticulo" class="col-md-3 form-control-label">Documentos Requeridos</label>
                    <div class="col-sm-9">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" v-model="preq.documentacionre" placeholder="Documentos Requeridos" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="button" @click="abrirModalA('articulo','documentos',preq.requisicione_id)" :disabled="tipoAccionpr==2">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <template v-if="preq.articulo_id != 0 && (preq.servicio_id == 0  || preq.servicio_id == null)">

                    <div class="form-group row">
                        <span class="col-md-3">Producción</span>
                        <label class="switch switch-default switch-pill switch-dark">
                            <input type="checkbox" class="switch-input" checked v-model="produccion">
                            <span class="switch-label"></span>
                            <span class="switch-handle"></span>
                        </label>
                        &nbsp;
                    </div>
                </template>

            </form>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-outline-dark" @click="cancelar()"><i class="fas fa-times"></i>&nbsp;Cancelar</button>
            <button type="button" v-if="tipoAccionpr==1" class="btn btn-secondary" @click="validarpartida(); guardarPR(1, preq.requisicione_id);"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" v-if="tipoAccionpr==2" class="btn btn-secondary" @click="guardarPR(0, preq.pda)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
        </div>
    </div>
    <!-- Fin  de Nuevo y editar detalle requisicion correspondiente ala tabla partidas_requisiciones -->

    <!--Inicio del modal agregar/actualizar articulos-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modaluno}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div>

                    <!--<vue-element-loading :active="isLoadingg"/>-->
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModala"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="accordion" id="accordiondos">

                            <div class="card">
                                <div class="card-header bg-dark justify-content-center" id="headingUno">
                                    <h5 class="mb-0">
                                        <button class="btn btn-dark collapsed" type="button" data-toggle="collapse" data-target="#collapsecinco" aria-expanded="false" aria-controls="collapseTres">
                                            <b> Articulos </b>
                                        </button>
                                        <button class="btn btn-dark collapsed" type="button" data-toggle="collapse" data-target="#collapseseis" aria-expanded="false" aria-controls="collapseCuatro">
                                            <b> Servicios </b>
                                        </button>
                                        <button class="btn btn-dark collapsed" type="button" data-toggle="collapse" data-target="#collapsiete" aria-expanded="false" aria-controls="collapseCinco">
                                            <b> Pendientes </b>
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapsecinco" class="collapse" aria-labelledby="headingUno" data-parent="#accordiondos">
                                    <v-server-table ref="myTableArticulo" url="/articulo/busqueda" :columns="columnsa" :options="optionsa" @row-click="seleccionarArticulo2">
                                        <template slot="calidad" slot-scope="props">
                                            <template v-if="props.row.calidad_id != null">
                                                <button type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="top" :title="props.row.descal">
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
                                                        <button type="button" @click="abrirModal(props.row)" class="dropdown-item">
                                                            <i class="icon-pencil"></i>&nbsp;Actualizar Articulo
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </v-server-table>
                                </div>
                                <div id="collapseseis" class="collapse" aria-labelledby="headingDos" data-parent="#accordiondos">
                                    <v-server-table ref="myTable" url="/catservicio/busqueda" :columns="columnss" :options="optionss" @row-click="seleccionarServicio">
                                    </v-server-table>
                                </div>
                                <div id="collapsiete" class="collapse" aria-labelledby="headingTres" data-parent="#accordiondos">
                                    <v-server-table ref="myTablePend" url="/catpendiente/busqueda" :columns="columnsp" :options="optionsp" @row-click="seleccionarPendiente">
                                        <template slot="calidad" slot-scope="props">
                                            <template v-if="props.row.calidad_id != null">
                                                <button type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="top" :title="props.row.descal">
                                                    {{props.row.calidad}}
                                                </button>
                                            </template>
                                        </template>
                                    </v-server-table>
                                </div>
                            </div>

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

    <!--Inicio del modal agregar/actualizar documento-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modaldos}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <!-- <vue-element-loading :active="isLoadinggd"/> -->
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModala"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-4" v-for="doc in documentos">
                                    <div class="form-ch eck checkbox">
                                        <input class="" :value="doc.id" :id="doc.id" type="checkbox" v-model="doc_req">
                                        <label class="form-check-label" for="doc.id">
                                            {{doc.nombre}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" @click="guardardocumentos()">Ok</button> -->
                        <button type="button" v-show="tipoAcciondos==1" class="btn btn-secondary" @click="guardardocumentos()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                        <button type="button" v-show="tipoAcciondos==0" class="btn btn-secondary" @click="guardardocumentosupdate()"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal agregar documentos-->

</div>
<!-- Fin Listado de escolaridades del requisiicion-->

</div>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            requisiicion: null,
            validar_pendiente: false,
            validar: false,
            modaluno: 0,
            modaldos: 0,
            doc_req: [],
            isLoading: false,
            tituloModala: '',
            requisicion:
            {
                id: 0,
                folio: '',
                area_solicita_id: '',
                formato_requisiciones: '',
                fecha_solicitud: '',
                descripcion_uso: '',
                tipo_compra: '',
                proyecto_id: '',
                estado_id: 0,
                solicita_empleado_id: '',
                autoriza_empleado_id: '',
                valida_empleado_id: '',
                recibe_empleado_id: '',
                condicion: 0,
                Area: '',
            },
            produccion: false,
            /*Tabla de Servicios*/
            tableDatadetallerequisicionServ: [],
            columnsdetallerequisicionServ: ['req.servicio_id', 'req.nservicio', 'req.nproveedor','req.comentario_partida', 'req.cantidades', 'req.equivalente', 'req.frequerido'],
            optionsdetallerequisicionServ:
            {
                headings:
                {
                    'req.servicio_id': 'Acciones',
                    'req.nservicio': 'Servicio',
                    'req.nproveedor': 'Proveedor/Marca1',
                    'req.comentario_partida': 'Comentario1',
                    'req.cantidades': 'Cantidad',
                    'req.equivalente': 'Equivalente',
                    'req.frequerido': 'Fecha requerido',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['id', 'folio', 'nservicio', 'nservicio', 'cantidades'],
                filterable: ['folio', 'nservicio', 'nservicio', 'cantidades'],
                filterByColumn: true,
                listColumns:
                {
                    condicion: [
                    {
                        id: 1,
                        text: 'SI'
                    },
                    {
                        id: 0,
                        text: 'NO'
                    }]
                },
                texts: config.texts
            },
            /**/
            columnsa: ['codigo', 'nombre', 'descripcion', 'unidad', 'marca', 'calidad'],
            columnss: ['nombre_servicio', 'proveedor_marca', 'unidad_medida'],
            columnsp: ['codigo', 'nombre', 'descripcion', 'marca', 'calidad'],
            columnsdetallerequisicion: ['req.articulo_id', 'req.narticulo', 'req.comentario_partida','req.carticulo', 'req.cantidades', 'req.equivalente', 'req.frequerido', ],
            tableDatadetallerequisicion: [],
            // Articulos
            optionsdetallerequisicion:
            {
                headings:
                {
                    'req.articulo_id': 'Acciones',
                    'req.narticulo': 'Nombre artículo',
                    'req.carticulo': 'Código',
                    'req.darticulo': 'Descripción',
                    'req.peso': 'Peso',
                    'req.comentario_partida': 'Comentario',
                    'req.cantidades': 'Cantidad',
                    'req.equivalente': 'Equivalente',
                    'req.frequerido': 'Fecha requerido',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['req.narticulo', 'req.carticulo', 'req.darticulo', 'req.peso', 'req.cantidades', 'req.comentario_partida', 'req.frequerido'],
                filterable: ['req.narticulo', 'req.carticulo', 'req.darticulo', 'req.peso', 'req.cantidades', 'req.comentario_partida', 'req.frequerido'],
                filterByColumn: true,
                listColumns:
                {
                    condicion: [
                    {
                        id: 1,
                        text: 'SI'
                    },
                    {
                        id: 0,
                        text: 'NO'
                    }]
                },
                texts: config.texts
            },
            optionsa:
            {
                headings:
                {
                    nombre: 'Nombre',
                    descripcion: 'Descripción',
                    marca: 'Marca',
                    codigo: 'Codigo',
                    id: 'Acciones',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                sortable: ['codigo', 'nombre', 'descripcion', 'marca'],
                filterable: ['codigo', 'nombre', 'descripcion', 'marca'],
                texts: config.texts,
                debounce: 700,
                childRow: true,
            },
            optionss:
            {
                headings:
                {
                    nombre_servicio: 'Nombre del Servicio',
                    proveedor_marca: 'Proveedor/Marca2',
                    unidad_medida: 'Unidad de Medida',
                    id: 'Acciones',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            },
            optionsp:
            {
                headings:
                {
                    nombre: 'Nombre',
                    descripcion: 'Descripción',
                    marca: 'Marca',
                    codigo: 'Codigo',
                    id: 'Acciones',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                sortable: ['codigo', 'nombre', 'descripcion', 'marca'],
                filterable: ['codigo', 'nombre', 'descripcion', 'marca'],
                texts: config.texts,
                debounce: 700,
            },
            preq:
            {
                requisicione_id: 0,
                articulo_id: 0,
                servicio_id: 0,
                peso: 0,
                cantidad: '',
                equivalente: 0,
                fecha_requerido: '',
                comentario: '',
                nombrearti: '',
                validado: 0,
                documentacionre: '',
                documentacionreid: '',
                pendiente: 0,
                req_com_id: 0,
                part_entr: 0,
                req_antigua: 0,
            },
            clases:
            {
                peso: '',
                cantidades: '',
                fecharequerido: '',
            },
            tipoAccionpr: 0,
            documentos: null,
            tipoAcciondos: 1,
        }
    },
    components:
    {
        'articulo': require('./Articulo.vue')
    },
    methods:
    {
        getListas()
        {
            let me = this;
            axios.get('ducumentosrequeridos').then(response =>
                {
                    me.documentos = response.data;
                })
                .catch(function (error)
                {
                    console.error();
                });
        },

        /**
         * [seleccionarArticulo2 description]
         * @param  {[type]} data [description]
         * @return {[type]}      [description]
         */
        seleccionarArticulo2(data)
        {
            let me = this;
            this.validar = false;
            this.validar_pendiente = false;
            this.preq.servicio_id = null;
            this.preq.articulo_id = data.row.id;
            this.preq.nombrearti = data.row.descripcion;
            me.cerrarModal();
        },
        seleccionarServicio(data)
        {
            let me = this;
            this.validar = true;
            this.validar_pendiente = false;
            this.preq.peso = 0;
            this.preq.articulo_id = null;
            this.preq.servicio_id = data.row.id;
            this.preq.nombrearti = data.row.nombre_servicio + '/' + data.row.proveedor_marca;
            me.cerrarModal();
        },
        seleccionarPendiente(data)
        {
            let me = this;
            this.validar = false;
            this.validar_pendiente = true;
            this.preq.servicio_id = null;
            this.preq.articulo_id = data.row.id;
            this.preq.nombrearti = data.row.descripcion;
            this.preq.cantidad = data.row.cantidad;
            this.preq.pendiente = data.row.pendiente;
            this.preq.req_com_id = data.row.req_com_id;
            this.preq.part_entr = data.row.PartEntr;
            this.preq.req_antigua = data.row.ReqAntigua;
            me.cerrarModal();
        },

        emitirEventoHijo()
        {
            this.$emit('detallerequisiciones:change');
        },

        cargardetalle(data)
        {
            this.detalle = true;
            this.isLoading = true;
            let me = this;
            this.requisiicion = data;
            console.log(data);
            this.preq.requisicione_id = data.id;
            axios.get('/requisicion/' + data.id + '/' + 'requisicion').then(response =>
                {
                    me.tableDatadetallerequisicion = response.data;
                    me.isLoading = false;
                })
                .catch(function (error)
                {
                    console.log(error);
                });

            /*Detalle Servicios*/
            axios.get('/catservicios/' + data.id).then(response =>
                {
                    me.tableDatadetallerequisicionServ = response.data;
                    me.isLoading = false;
                    console.error(response.data);

                })
                .catch(function (error)
                {
                    console.log(error);
                });
            /**/
        },

        maestro()
        {
            this.emitirEventoHijo();
            this.cancelar();
        },

        /**
         * [abrirModalA description]
         * @param  {[type]} modelo    [description]
         * @param  {[type]} accion    [description]
         * @param  {[type]} id        [description]
         * @param  {Array}  [data=[]] [description]
         * @return {[type]}           [description]guardarPR
         */
        abrirModalA(modelo, accion, id, data = [])
        {
            switch (modelo)
            {
                case "articulo":
                {
                    switch (accion)
                    {
                        case 'registrar':
                        {
                            this.modaluno = 1;
                            this.tituloModala = 'Registrar partida requisición';
                            Utilerias.resetObject(this.preq);
                            this.tipoAccionpr = 1;
                            this.preq.requisicione_id = id;
                            this.$refs.myTableArticulo.refresh();
                            break;
                        }
                        // case ''
                        case 'documentos':
                        {
                            this.modaldos = 1;
                            this.tipoAcciondos = 1;
                            this.preq.documentacionre = '';
                            this.doc_req = [];
                            this.tituloModala = 'Registrar los documentos requeridos';
                            break;
                        }
                        case 'actualizar':
                        {
                            this.modaldos = 1;
                            this.tipoAcciondos = 0;
                            this.tituloModala = 'Documentos Requeridos';
                            if (data['servicio_id'] == undefined)
                            {
                                var ids = null;
                            }
                            else
                            {
                                var ids = data['servicio_id'];
                            }

                            this.preq.servicio_id = ids;
                            var ida = data['articulo_id'];
                            this.preq.articulo_id = ida;
                            var idr = data['requisicione_id'];
                            this.preq.requisicione_id = idr;
                            var cadenadoc = [];

                            axios.get('/partidadocumentos/' + ida + '&' + idr + '&' + ids).then(function (response)
                            {
                                for (var i = 0; i < response.data.length; i++)
                                {
                                    cadenadoc.push(response.data[i].documento_id);
                                }
                            }).catch(function (error)
                            {
                                console.log(error);
                            });
                            this.doc_req = cadenadoc;

                            break;
                        }
                        case 'actualizar_articulo':
                        {
                            this.preq.articulo_id = data['articulo_id'];
                            this.preq.pda = data['pda'];
                            this.preq.nombrearti = data['darticulo'];
                            this.preq.comentario = data['comentario_partida'];
                            this.preq.peso = data['peso'];
                            this.preq.cantidad = data['cantidades'];
                            this.preq.equivalente = data['equivalente'];
                            this.preq.fecha_requerido = data['frequerido'];
                            this.produccion = data['produccion'] == 1 ? true : false;
                            this.tipoAccionpr = 2;
                            this.preq.validado = 1;
                            break;
                        }
                        case 'actualizar_servicio':
                        {

                            this.preq.servicio_id = data['servicio_id'];
                            this.preq.pda = data['pda'];
                            this.preq.nombrearti = data['nservicio'];
                            this.preq.comentario = data['comentario_partida'];
                            this.preq.peso = data['peso'];
                            this.preq.cantidad = data['cantidades'];
                            this.preq.equivalente = data['equivalente'];
                            this.preq.fecha_requerido = data['frequerido'];
                            this.tipoAccionpr = 2;
                            this.preq.validado = 1;
                            break;
                        }

                    }
                }
            }
        },

        /**
         * [cerrarModal description]
         * @return {[type]} [description]
         */
        cerrarModal()
        {
            this.modaluno = 0;
            this.modaldos = 0;
            this.tituloModal = '';
            Utilerias.resetObject(this.requisicion);
        },

        /**
         * [cancelar description]
         * @return {[type]} [description]
         */
        cancelar()
        {
            this.validar = false;
            this.validar_pendiente = false;
            this.preq.pendiente = 0;
            this.preq.articulo_id = 0;
            this.preq.servicio_id = 0;
            this.preq.peso = '';
            this.preq.cantidad = '';
            this.preq.equivalente = 0;
            this.preq.fecha_requerido = "";
            this.preq.nombrearti = "";
            this.tipoAccionpr = 0;
            this.preq.comentario = "";
            this.preq.documentacionre = "";
            this.doc_req = [];
            this.preq.pendiente = 0;
            this.preq.req_com_id = 0;
            this.preq.part_entr = 0;
            this.preq.req_antigua = 0;
            Utilerias.resetObject(this.clases);

        },

        /**
         * [guardardocumentos description]
         * @return {[type]} [description]
         */
        guardardocumentos()
        {
            var data = this.doc_req;
            var cadena = [];
            var cadenauno = [];

            for (var i = 0; i < data.length; i++)
            {
                axios.get('ducumentosrequeridosb/' + data[i]).then(response =>
                {
                    cadena.push(response.data[0].nombre);
                    cadenauno.push(response.data[0].id);
                }).catch(function (error)
                {
                    console.error(error);
                })
            }
            this.preq.documentacionre = cadena;
            this.preq.documentacionreid = cadenauno;
            this.modaldos = 0;
        },

        /**
         * [validarpartida description]
         * @return {[type]} [description]
         */
        validarpartida()
        {
            if (this.preq.peso === '')
            {
                this.mensaje();
                this.clases.peso = ' is-invalid';
            }
            else if (this.preq.cantidad === '')
            {
                this.mensaje();
                this.clases.peso = '';
                this.clases.cantidades = ' is-invalid';
            }
            else if (this.preq.fecha_requerido === '')
            {
                this.mensaje();
                this.clases.cantidades = '';
                this.clases.fecharequerido = ' is-invalid';
            }
            else
            {
                this.preq.validado = 1;
                this.clases.fecharequerido = '';
            }
        },

        /**
         * [mensaje description]
         * @return {[type]} [description]
         */
        mensaje()
        {
            swal(
            {
                title: 'Error complete todos los campos',
                type: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar!',
                confirmButtonClass: 'btn btn-success',
                buttonsStyling: false,
                reverseButtons: true
            })
        },

        /**
         * [guardarPR description]
         * @param  {[type]} nuevo [description]
         * @param  {[type]} idr   [description]
         * @return {[type]}       [description]
         */
        guardarPR(nuevo, idr)
        {
            if (this.preq.validado == 1)
            {
                this.isLoading = true;
                this.detalle = true;
                let me = this;
                axios(
                {
                    method: nuevo ? 'POST' : 'PUT',
                    url: nuevo ? '/partidare' : '/partidare/' + this.preq.pda,
                    data:
                    {
                        'requisicione_id': this.preq.requisicione_id,
                        'articulo_id': this.preq.articulo_id,
                        'servicio_id': this.preq.servicio_id,
                        'peso': this.preq.peso,
                        'cantidad': this.preq.cantidad,
                        'equivalente': this.preq.equivalente,
                        'fecha_requerido': this.preq.fecha_requerido,
                        'comentario': this.preq.comentario,
                        'documentacionreid': this.preq.documentacionreid,
                        'pda': this.preq.pda,
                        'pendiente': this.preq.pendiente,
                        'req_com_id': this.preq.req_com_id,
                        'part_entr': this.preq.part_entr,
                        'req_antigua': this.preq.req_antigua,
                        'produccion': this.produccion == true ? 1 : 0,
                    }
                }).then(function (response)
                {

                    if (response.data.status)
                    {
                        me.isLoading = false;
                        me.cargardetalle(me.requisiicion);
                        me.cerrarModal();
                        me.cancelar();
                        me.$refs.myTablePend.refresh();
                        me.preq.validado = 0;
                        // me.getData();
                        if (!nuevo)
                        {
                            toastr.success('Partida Actualizada Correctamente');
                        }
                        else
                        {
                            toastr.success('Partida Registrada Correctamente');

                        }
                    }
                    else
                    {
                        /**/
                        swal(
                        {
                            title: 'Error no se puede seleccionar \n 2 articulos iguales \n en la misma requisición',
                            type: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar!',
                            confirmButtonClass: 'btn btn-warning',
                            buttonsStyling: false,
                            reverseButtons: true
                        })
                        me.cancelar();
                        me.isLoading = false;
                        /**/
                    }
                }).catch(function (error)
                {
                    console.log(error);

                    me.cancelar();
                    me.isLoading = false;
                });
            }
        },

        /**
         * [actdesactreq description]
         * @param  {[type]} id      [description]
         * @param  {[type]} prid    [description]
         * @param  {[type]} activar [description]
         * @return {[type]}         [description]
         */
        actdesactreq(id, prid, activar, idserv, row)
        {
            if (!activar)
            {
                this.swal_titulo = 'Esta seguro de remover este articulo ó servicio de la requisición?';
                this.swal_tle = 'Removido!';
                this.swal_msg = 'El registro ha sido removido con éxito.';
            }
            swal(
            {
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
            }).then((result) =>
            {
                if (result.value)
                {
                    let me = this;
                    axios.get('/partidare' + '/' + id + '&' + prid + '&' + idserv + '/edit').then(function (response)
                    {
                        if (!activar)
                        {
                            toastr.success(me.swal_tle, me.swal_msg, 'success');
                            me.$refs.myTablePend.refresh();
                        }
                        me.cargardetalle(me.requisiicion);
                    }).catch(function (error)
                    {
                        console.log(error);
                    });
                }
                else if (
                    result.dismiss === swal.DismissReason.cancel
                )
                {

                }
            })
        },

        /**
         * [guardardocumentosupdate description]
         * @return {[type]} [description]
         */
        guardardocumentosupdate()
        {
            var array = [];
            var id = this.preq.requisicione_id;
            let me = this;
            axios.put('/partidare/' + this.preq.articulo_id + '/updatedoc',
            {
                'documento_id': this.doc_req,
                'partidarequisicione_art': this.preq.articulo_id,
                'partidarequisicione_req': this.preq.requisicione_id,
                'partidarequisicione_serv': this.preq.servicio_id,

            }).then(function (response)
            {
                me.cerrarModal();
                me.cargardetalle(me.requisiicion);
                toastr.success('Correcto', 'Documentos actualizados correctamente');
            }).catch(function (error)
            {
                console.log(error);
            });
        },

        agregarArt()
        {
            let me = this;
            me.$refs.articulo.abrirModal('articulo', 'registrar');
        },
        abrirModal(data)
        {
            //this.$emit('enviar',data);
            //this.mostrar = false;
            let me = this;
            me.$refs.articulo.abrirModal('articulo', 'actualizar', data);
            me.cerrarModal();
        },
    },
    mounted()
    {
        this.getListas();
    },
}
</script>

<style>
.VueTables__child-row-toggler--closed::before {
    content: "+";
}

.VueTables__child-row-toggler--open::before {
    content: "-";
}

.dropbtn2 {
    background-color: #000000;
    color: rgb(255, 255, 255);
    padding: 15px;
    font-size: 15px;
    border: none;
}

.dropdown2 {
    position: relative;
    display: inline-block;
}

.dropdown-content2 {
    display: none;
    position: sticky;
    background-color: #adb1c5;
    min-width: 50px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

.dropdown-content2 a {
    color: black;
    padding: 15px 20px;
    text-decoration: none;
    display: block;
}

.button2 {
    background-color: white;
    /* Green */
    color: white;
    padding: auto;
    border:solid 1px;
    padding: 1px;
    text-decoration: none;
    display: inline-block;
    font-size: 15px;

}

.dropdown-content2 button2:hover {
    background-color: rgb(206, 36, 36);
}

.dropdown2:hover .dropdown-content2 {
    display: block;
}

.dropdown2:hover .dropbtn2 {
    background-color: #000000;
}

th,
td {
    text-align: left;
}

th:nth-child(n+2),
td:nth-child(n+2) {
    text-align: center;
}

thead tr:nth-child(2) th {
    font-weight: normal;
}

.VueTables__sort-icon {
    margin-left: 40px;
}

.VueTables__dropdown-pagination {
    margin-left: 40px;
}

.VueTables__highlight {
    background: yellow;
    font-weight: normal;
}

.VueTables__sortable {
    cursor: pointer;
}

.VueTables__date-filter {
    border: 1px solid #ccc;
    padding: 16px;
    border-radius: 4px;
    cursor: pointer;
}

.VueTables__filter-placeholder {
    color: #aaa;
}

.VueTables__list-filter {
    width: auto;
    height: auto;
}
</style>
