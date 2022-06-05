<template>
<div class="main">
    <!-- Card Inicio-->
    <div class="card" v-if="tipoAccion_modal_reporte == 1">
        <!-- Inicio card-->
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Inspecciones de calidad
            <button :disabled="proyecto.id==null" type="button" class="btn btn-dark float-sm-right" @click="AbrirModalReporte(true)">
                <i class="fas fa-plus">&nbsp;</i>Nuevo
            </button>
        </div>
        <div class="card-body">
            <div class="">
                <!-- Tabla de Rimes-->
                <div class="">
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Proyecto</label>
                        <div class="col-md-6">
                            <vue-element-loading :active="is_proyectos_loading" />
                            <v-select v-model="proyecto" :options="list_proyectos" label="nombre"></v-select>
                        </div>
                        <button class="btn btn-sm btn-dark" @click="ObtenerRime()">
                            <i class="fas fa-search"></i> &nbsp; Buscar
                        </button>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Buscar</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Artículo a buscar" v-model="busqueda" v-on:keyup.enter="Buscar" />
                        </div>
                        <button class="btn btn-sm btn-dark" @click="Buscar()">
                            <i class="fas fa-search"></i> &nbsp; Buscar
                        </button>
                    </div>

                    <!-- <div class="form-group row">
            <label class="col-md-2 form-control-label">Orden de compra</label>
            <div class="col-md-5">
            <vue-element-loading :active="is_oc_loading" />
            <v-select v-model="orden_compra" :options="list_oc" label="folio"></v-select>
          </div>

        </div> -->
                    <br>
                    <br>

                    <!-- Tabla rimes -->
                    <vue-element-loading :active="is_rimes_loading" />
                    <v-client-table :columns="columns_rimes" :data="list_rimes" :options="options_rimes">
                        <template slot="id" slot-scope="props">
                            <div class="btn-group" role="group">
                                <button id="btn_id" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                                <div class="dropdown-menu">
                                    <template>
                                        <button type="button" @click="VerPartidas(props.row)" class="dropdown-item">
                                            <i class="fas fa-eye"></i>&nbsp;Ver partidas
                                        </button>
                                        <button type="button" @click="Descargar(props.row)" class="dropdown-item">
                                            <i class="fas fa-file-pdf"></i>&nbsp;Descargar RIME
                                        </button>
                                        <button type="button" @click="DescargarOC(props.row.oc_id)" class="dropdown-item">
                                            <i class="fas fa-file-pdf"></i>&nbsp;Descargar OC
                                        </button>
                                        <!-- <button type="button" @click="SubirAdjunto(props.row)" class="dropdown-item">
                                            <i class="fas fa-file-pdf"></i>&nbsp;Subir
                                        </button> -->
                                    </template>
                                </div>
                            </div>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div>
            <!-- card-->
        </div>
    </div>

    <!-- Card partidas-->
    <div class="card" v-if="tipoAccion_modal_reporte == 3">
        <!-- Inicio card-->
        <div class="card-header">
            <i class="fa fa-align-justify"></i> {{folio}}
            <button type="button" class="btn btn-dark float-sm-right" @click="AbrirModalReporte(false)">
                <i class="fas fa-plus">&nbsp;</i> Añadir
            </button>
            <button type="button" class="ml-2 btn btn-dark float-sm-right" @click="CerrarModalReporte(3)">
                <i class="fas fa-arrow-left">&nbsp;</i>Regresar
            </button>
        </div>
        <div class="card-body">
            <div class="">
                <!-- Tabla de partidas-->
                <div class="">

                    <!-- Tabla partidas -->
                    <vue-element-loading :active="is_inspecciones_loading" />

                    <v-client-table :columns="columns_reporte" :data="list_reporte" :options="options_reporte">
                        <template slot="id" slot-scope="props">
                            <div class="btn-group" role="group">
                                <button id="btn_id" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                                <div class="dropdown-menu">
                                    <template>
                                        <button type="button" @click="AbrirModalReporte(false,props.row)" class="dropdown-item">
                                            <i class="fas fa-eye"></i>&nbsp;Ver inspección
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template slot="certificado_pdf" slot-scope="props">
                            <button v-if="props.row.certificado_pdf!=null" class="btn btn-outline-dark" @click="DescargarCertificado(props.row.id)">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                            <button v-else class="btn btn-outline-dark">
                                Pendiente
                            </button>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div>
            <!-- card-->
        </div>
    </div>

    <!-- Card reporte -->
    <div class="card" v-if="tipoAccion_modal_reporte == 2">
        <!-- Inicio card-->
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Registrar inspección
            <button type="button" class="btn btn-dark float-sm-right" @click="CerrarModalReporte(3)">
                <i class="fas fa-arrow-left">&nbsp;</i>Regresar
            </button>
        </div>
        <div class="card-body">
            <div class="">
                <vue-element-loading :active="is_guardando_loading" />
                <!-- Formulario de Reporte-->
                <div class="container card-body border">
                    <h3 v-if="tipo_registro==2">Actualizar inspección </h3>
                    <h3 v-if="tipo_registro==1">Registrar inspección </h3>
                    <br>
                    <br>
                    <!-- Proyectos -->
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Folio</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" v-model="reporte_modal.folio" name="folio" v-validate="'required'">
                            <span class="text-danger">{{errors.first("folio")}}</span>
                        </div>
                        <div class="col">
                            <div class="form-check">
                                <label class="form-check-label col-md-6" for="autoSizingCheck2">
                                    Existencia en almacén
                                </label>
                                <label class="switch switch-default switch-pill switch-dark">
                                    <input type="checkbox" class="switch-input" @change="CambiarTipoOC($event)">
                                    <span class="switch-label"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Proyecto</label>
                        <div class="col-md-4">
                            <vue-element-loading :active="is_proyectos_loading" />
                            <v-select v-model="proyecto" name="proyecto" v-validate="'required'" :options="list_proyectos" label="nombre" @input="ObtenerOC()"></v-select>
                            <span class="text-danger">{{errors.first("proyecto")}}</span>
                        </div>
                        <label class="col-md-1 form-control-label">Fecha</label>
                        <div class="col-md-4">
                            <input type="date" v-validate="'required'" name="fecha" v-model="reporte_modal.fecha" class="form-control" />
                            <span class="text-danger">{{errors.first("fecha")}}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">No. Proyecto</label>
                        <div class="col-md-4">
                            <input type="text" data-vv-name="no proyecto" v-validate="'required'" v-model="reporte_modal.no_proyecto" class="form-control" />
                            <span class="text-danger">{{errors.first("no proyecto")}}</span>
                        </div>
                        <label class="col-md-1 form-control-label">Factura</label>
                        <div class="col-md-4">
                            <input type="text" name="factura" v-validate="'required'" v-model="reporte_modal.factura" class="form-control" />
                            <span class="text-danger">{{errors.first("factura")}}</span>
                        </div>
                    </div>

                    <!-- OCs -->
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Orden de compra</label>
                        <div class="col-md-5">
                            <vue-element-loading :active="is_oc_loading" />
                            <v-select v-model="orden_compra" :options="list_oc" label="folio"></v-select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-dark" @click="MostrarArticulos()">Buscar</button>
                        </div>
                    </div>

                    <!-- Articulos -->
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Artículo</label>
                        <div class="col-md-8">
                            <textarea readonly v-model="reporte_modal.descripcion" class="form-control bg-white" rows="2"></textarea>
                        </div>
                    </div>

                    <!-- Cantidad -->
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Cantidad</label>
                        <div class="col-md-2">
                            <input type="number" v-validate="'required'" name="cantidad" step="0.1" v-model="reporte_modal.cantidad" class="form-control" />
                            <span class="text-danger">{{errors.first("cantidad")}}</span>
                        </div>

                        <label class="col-md-2 form-control-label">Modelo</label>
                        <div class="col-md-3">
                            <input type="text" name="modelo" v-validate="'required'" class="form-control" v-model="reporte_modal.modelo" />
                            <span class="text-danger">{{errors.first("modelo")}}</span>
                        </div>
                    </div>

                    <!-- Modelo -->
                    <div class="form-group row">

                        <label class="col-md-2 form-control-label">No. Serie</label>
                        <div class="col-md-3">
                            <input type="text" data-vv-name="No serie" v-validate="'required'" class="form-control" v-model="reporte_modal.no_serie" />
                            <span class="text-danger">{{errors.first("No serie")}}</span>
                        </div>
                        <label class="col-md-1 form-control-label">Marca</label>
                        <div class="col-md-3">
                            <input type="text" v-validate="'required'" name="marca" class="form-control" v-model="reporte_modal.marca" />
                            <span class="text-danger">{{errors.first("marca")}}</span>
                        </div>
                    </div>

                    <!-- Certificado -->
                    <div class="container">
                        <input type="checkbox" v-model="tiene_certificado">
                        <label>Contiene certificado</label>
                    </div>

                    <div class="a" v-show="tiene_certificado">
                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">No. Certificado</label>
                            <div class="col-md-3">
                                <input type="text" name="certificado" class="form-control" v-model="reporte_modal.no_certificado" />
                                <span class="text-danger">{{errors.first("certificado")}}</span>
                            </div>

                            <!-- Cantidad de equipos que avala el certificado -->
                            <label class="col-md-1 form-control-label">Cantidad</label>
                            <div class="col-md-2">
                                <input type="text" v-validate="'required'" data-vv-name="cantidad certificados" class="form-control" v-model="reporte_modal.cantidad_certificados" />
                                <span class="text-danger">{{errors.first("cantidad certificados")}}</span>
                            </div>

                        </div>
                        <!-- Documento -->
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label">Documentos</label>
                            <div class="col-md-12">
                                <input class="form-control col-4" accept="application/pdf" type="file" required id="fileCertificado">
                            </div>
                        </div>
                    </div>

                    <br>
                    <!-- Menu -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="list" href="#materiales" role="tab">
                                <p class="font-weight-bold">Tipo de material</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="list" href="#inspeccion" role="tab">
                                <p class="font-weight-bold">Inspección</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="list" href="#messages" role="tab">
                                <p class="font-weight-bold">Equipos e instrumentos</p>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Material -->
                        <div class="tab-pane active" id="materiales" role="tabpanel">
                            <br>
                            <h4>Tipo de material</h4>
                            <br>
                            <br>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Mecánico</label>

                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.tipo_mecanico">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.tipo_mecanico">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.tipo_mecanico">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Estructural</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.tipo_estructura">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.tipo_estructura">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.tipo_estructura">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Rec. Anticorrosivo</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.tipo_anticorrosivo">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.tipo_anticorrosivo">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.tipo_anticorrosivo">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Eléctrico</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.tipo_electrico">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.tipo_electrico">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.tipo_electrico">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Instrumentación</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.tipo_instrumentacion">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.tipo_instrumentacion">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.tipo_instrumentacion">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                        </div>

                        <!-- Inspeccion materiales -->
                        <div class="tab-pane" id="inspeccion" role="tabpanel">
                            <br>
                            <h4>Inspección de materiales</h4>
                            <br>
                            <br>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Coincide OC con Factura</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_mat_factura">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_mat_factura">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_mat_factura">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Estado Físico</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_mat_edo_fisico">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_mat_edo_fisico">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_mat_edo_fisico">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Dimensiones de acuerdo a especificación</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_mat_dimesiones">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_mat_dimesiones">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_mat_dimesiones">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Cumple especificaciones y/o normativa</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_mat_normativa">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_mat_normativa">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_mat_normativa">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Nacionalidad cumple con lo solicitado</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_mat_nacionalidad">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_mat_nacionalidad">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_mat_nacionalidad">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Certificado de Calidad</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_mat_cetificado">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_mat_cetificado">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_mat_cetificado">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Certficado cumple con especificaciones</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_mat_cet_cumpl">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_mat_cet_cumpl">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_mat_cet_cumpl">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Coinciden coladas del material con el certificado</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_mat_coladas">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_mat_coladas">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_mat_coladas">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Verificación de Documentos y características Técnicas</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_mat_documentos">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_mat_documentos">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_mat_documentos">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                        </div>

                        <!-- Equipos e instrumentos -->
                        <div class="tab-pane" id="messages" role="tabpanel">
                            <br>
                            <h4>Inspección de equipos y/o instrumentos</h4>
                            <br>
                            <br>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Se reciben certificados de calibración</label>

                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_equi_recibe_cert">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_equi_recibe_cert">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_equi_recibe_cert">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Se recibieron manuales de instalación y/o mantenimiento</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_equi_recibe_manual">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_equi_recibe_manual">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_equi_recibe_manual">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Se recibieron partes de repuesto</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_equi_recibe_repuesto">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_equi_recibe_repuesto">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_equi_recibe_repuesto">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">Se recibieron kits de instalación</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_equi_recibe_kit">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_equi_recibe_kit">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_equi_recibe_kit">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-3">El rango de los instrumentos coincide con lo solicitado</label>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="A" v-model="inspecciones.ins_equi_rango">
                                    <label class="form-control-label">Aceptado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="R" v-model="inspecciones.ins_equi_rango">
                                    <label class="radio-inspeccion">Rechazado</label>
                                </div>
                                <div class="form-group col-2">
                                    <input class="" type="radio" value="N/A" v-model="inspecciones.ins_equi_rango">
                                    <label class="form-control-label">N/A</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <br>
                    <br />
                    <br />
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Observaciones</label>
                        <div class="col-md-8">
                            <textarea v-model="reporte_modal.observaciones" class="form-control" rows="4"></textarea>
                        </div>
                    </div>

                    <div class="col-auto">
                        <div class=" mb-2">
                            <div class="mt-2 ml-2">
                                <button type="button" class="btn btn-sm btn-secondary" @click="AbrirModalColadas()">
                                    <i class="fas fa-plus"></i>&nbsp;Coladas
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <table class="table table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Colada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(colada,i) in list_coladas">
                                    <th scope="row">{{i+1}}</th>
                                    <td>{{colada.cantidad}}</td>
                                    <td>{{colada.colada}}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Total:</td>
                                    <td>{{total}}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <br>
                    <br>
                    <div class="form-group row">
                        <label class="col-md-1 form-control-label">REVISÓ</label>
                        <div class="col-md-3">
                            <v-select :options="optionsvs" v-validate="'required'" v-model="reviso" label="name" data-vv-name="empleado que reviso"></v-select>
                            <span class="text-danger">{{ errors.first('empleado que reviso') }}</span>
                        </div>

                        <label class="col-md-1 form-control-label">ENTERADO</label>
                        <div class="col-md-3">
                            <v-select :options="optionsvs" v-validate="'required'" v-model="enterado" label="name" data-vv-name="empleado enterado"></v-select>
                            <span class="text-danger">{{ errors.first('empleado enterado') }}</span>
                        </div>

                        <label class="col-md-1 form-control-label">APROBÓ</label>
                        <div class="col-md-3">
                            <v-select :options="optionsvs" v-validate="'required'" v-model="aprobo" label="name" data-vv-name="empleado aprueba"></v-select>
                            <span class="text-danger">{{ errors.first('empleado aprueba') }}</span>
                        </div>
                    </div>

                </div>

                <!--Card body -->
                <div class='modal-footer'>
                    <button type='button' v-if='tipo_registro== 1' class='btn btn-secondary' @click='GuardarReporte(true)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipo_registro==2' class='btn btn-secondary' @click='GuardarReporte(false)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                </div> <!-- Card footer -->
            </div>
            <!-- card-->
        </div>
    </div>

    <!-- Coladas -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_coladas}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Coladas</h4>
                    <button type='button' class='close' @click='CerrarModalColadas()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Cantidad</label>
                        <div class="col-md-3">
                            <input type="number" step="1" v-model="colada_temp.cantidad" class="form-control" />
                        </div>
                        <label class="col-md-1 form-control-label">Colada</label>
                        <div class="col-md-3">
                            <input type="text" v-model="colada_temp.colada" class="form-control" />
                        </div>
                        <button class="btn btn-dark btn-sm" @click="GuardarColadaTemp">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <br>
                    <table class="table table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Colada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(colada,i) in coladas_temp">
                                <th scope="row">{{i+1}}</th>
                                <td>{{colada.cantidad}}</td>
                                <td>{{colada.colada}}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total:</td>
                                <td>{{total}}</td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="GuardarColadas()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card" v-if="tipoAccion_modal_reporte == 4">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Registrar inspección
            <button type="button" class="btn btn-dark float-sm-right" @click="CerrarModalReporte(0)">
                <i class="fas fa-arrow-left">&nbsp;</i>Regresar
            </button>
        </div>
        <div class="card-body">

        </div>
    </div>
    <!-- Modal Articulos -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_articulos}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Seleccionar Artículo</h4>
                    <button type='button' class='close' @click='CerrarModalArticulos()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <vue-element-loading :active="is_articulos_loading" />
                    <!-- Tabla artículos -->
                    <v-client-table :columns='columns_articulos' :data='list_articulos' :options='options_articulos' ref='tbl_reporte' class="container mx-3" @row-click="SeleccionarArticulo">
                    </v-client-table>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</div>
<!-- Main -->
</template>

<style>
.nav-tabs .nav-link.active {
    color: black;

}
</style>

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from "../../Herramientas/utilerias.js";
var config = require("../../Herramientas/config-vuetables-client").call(this);
export default
{
    data()
    {
        return {
            url: "/calidad/inspecciones",
            // Tabla
            ver_modal_reporte: 0,
            rime: '',
            columns_reporte: [
                "id",
                "material",
                "cantidad",
                "marca",
                "modelo",
                "certificado_pdf"
            ],
            list_reporte: [],
            options_reporte:
            {
                headings:
                {
                    id: "Acciones",

                    cantidad: " Cantidad",
                    marca: "Marca",
                    modelo: "Modelo",
                    certificado_pdf: "Certificado"
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: [
                    "id",
                    "cantidad",
                    "material",
                    "marca",
                    "modelo",
                ],
                filterable: [
                    "id",
                    "cantidad",
                    "material",
                    "marca",
                    "modelo",
                ],
                filterByColumn: true,
                texts: config.texts,
            }, //options
            // CArd2
            titulo_modal_reporte: "",
            tipoAccion_modal_reporte: 1,
            list_proyectos: [],
            list_articulos: [],
            list_oc: [],
            proyecto:
            {},
            rime_id:null,
            reviso: '',
            enterado: '',
            aprobo: '',
            orden_compra:
            {},
            articulo:
            {},
            reporte_modal:
            {
                articulo_id: 0,
                descripcion: "",
                marca: "N/A",
                modelo: "N/A",
                no_serie: "N/A",
                no_certificado: "N/A",
                cantidad_certificados: 0,
                certificado: "N/D",
                no_proyecto: '',
                factura: '',
                fecha: '',
            },

            // Radios
            inspecciones:
            {},
            optionsvs: [], //
            nueva_rime:false,

            is_proyectos_loading: false,
            is_oc_loading: false,
            is_articulos_loading: false,
            is_reportes_loading: false,
            is_guardando_loading: false,
            registro_id: 0,
            // Moda articulos
            columns_articulos: ["descripcion"],
            list_articulos: [],
            tipo_registro: 1,
            ver_modal_articulos: 0,
            options_articulos:
            {
                headings:
                {
                    descripcion: "Descripción",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts,
            }, //options
            // Rimes
            list_rimes: [],
            columns_rimes: ["id", "folio", "articulo", "oc"], // oc
            options_rimes:
            {
                headings:
                {
                    id: "Acciones",
                    folio: "Folio",
                    // oc: "Orden de compra",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts,
            }, //options
            is_rimes_loading: false,
            is_inspecciones_loading: false,
            folio: "",
            oc_id: 0,
            oc_folio: "",
            list_coladas: [],
            ver_modal_coladas: false,
            coladas_temp: [],
            colada_temp:
            {},
            total: 0,
            busqueda: "",
            oc_temp: false, // OC temporal (Búsqueda en almacén) 
            tiene_certificado: false,

        }; // return
    }, //data
    computed:
    {},
    methods:
    {
        // Obtiene todos los proyectos de la DB
        Cargar()
        {
            this.is_proyectos_loading = true;
            axios
                .get(this.url + "/obtenerproyectos")
                .then((res) =>
                {
                    if (res.data.status)
                    {
                        this.list_proyectos = [];
                        res.data.proyectos.forEach((p) =>
                        {
                            this.list_proyectos.push(
                            {
                                id: p.id,
                                nombre: p.nombre_corto,
                            });
                        });
                        this.is_proyectos_loading = false;
                    }
                    else
                    {
                        toastr.show(res.data.mensaje, "Error");
                    }
                    this.is_proyectos_loading = false;
                })
                .catch((x) =>
                {
                    console.error(x);
                });

            axios.get('/vertodosempleados').then(response =>
                {
                    for (var i = 0; i < response.data.length; i++)
                    {
                        this.optionsvs.push(
                        {
                            id: response.data[i].id,
                            name: response.data[i].nombre + ' ' + response.data[i].ap_paterno + ' ' + response.data[i].ap_materno,
                        });
                    }
                })
                .catch(function (error)
                {
                    console.log(error);
                });
        },

        // Obtiene los repotes de calidad del proyecto seleccionado
        BuscarReportes()
        {
            if (this.proyecto == null) return;
            if (this.proyecto.id == null) return;

            this.is_inspecciones_loading = true;
            this.list_reporte = []
            axios.get(this.url + "/reportes/" + this.rime_id).then(res =>
            {
                if (res.data.status)
                {
                    this.list_reporte = res.data.reportes;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
                this.is_inspecciones_loading = false;
            });
        },
        // Obtiene las ordenes de compra del proyecto seleccionado
        ObtenerOC()
        {
            if (this.proyecto == null) return;
            if (this.proyecto.id == null) return;
            this.is_oc_loading = true;
            this.orden_compra = {};
            axios.get(this.url + "/obteneroc/" + this.proyecto.id)
                .then((res) =>
                {
                    if (res.data.status)
                    {
                        this.list_oc = [];
                        res.data.oc.forEach((o) =>
                        {
                            this.list_oc.push(
                            {
                                id: o.id,
                                folio: o.folio,
                            });
                        });
                        this.is_oc_loading = false;
                        this.orden_compra = {
                            id: this.oc_id,
                            folio: this.oc_folio,
                        };
                    }
                    else
                    {
                        toastr.show(res.data.mensaje, "Error");
                    }
                })
                .catch((x) =>
                {
                    console.error(x);
                });
        },
        ObtenerRime()
        {
            if (this.proyecto == null) return;
            if (this.proyecto.id == null) return;
            this.is_rimes_loading = true;
            // Cambiar las comlumnas
            this.columns_rimes = ["id", "folio"];
            axios.get(this.url + "/obtenerrime/" + this.proyecto.id)
                .then((res) =>
                {
                    if (res.data.status)
                    {
                        this.list_rimes = [];
                        res.data.rimes.forEach((o) =>
                        {
                            this.list_rimes.push(
                            {
                                id: o.id,
                                folio: o.folio_rime,
                                oc: o.oc_folio,
                                oc_id: o.oc_id,
                                fecha: o.fecha,
                                no_proyecto: o.no_proyecto,
                                factura: o.factura,
                                empleado_reviso_n: o.empleado_reviso_n,
                                empleado_aprobo_n: o.empleado_aprobo_n,
                                empleado_enterado_n: o.empleado_enterado_n,
                                empleado_reviso: o.empleado_reviso,
                                empleado_enterado: o.empleado_enterado,
                                empleado_aprobo: o.empleado_enterado,

                            });
                        });
                        this.is_rimes_loading = false;
                    }
                    else
                    {
                        toastr.show(res.data.mensaje, "Error");
                    }
                })
                .catch((x) =>
                {
                    console.error(x);
                });
        },

        // Obtiene los artículos de la OC seleccionada
        MostrarArticulos()
        {
            if (this.orden_compra == null) return;
            if (this.orden_compra.id == null)
            {
                toastr.warning("Seleccione una orden de compra");
                return;
            }
            // Obtener
            let oc = "";
            if (this.oc_temp)
            {
                // OC temporal y proyecto
                oc = "temp&" + this.proyecto.id;
            }
            else
            {
                // OC
                oc = this.orden_compra.id;
            }
            this.is_articulos_loading = true;
            this.ver_modal_articulos = 1;
            axios.get(this.url + "/articulos/" + oc).then(res =>
            {
                if (res.data.status)
                {
                    // Vacío
                    if (res.data.articulos.length == 0)
                        toastr.warning("Sin artículos");

                    this.list_articulos = res.data.articulos;
                    this.is_articulos_loading = false;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            }).catch(x =>
            {
                console.error(x);
            });

        },

        // Selecciona el articulo
        SeleccionarArticulo(data)
        {
            this.articulo = data.row;
            this.reporte_modal.articulo_id = data.row.articulo_id;
            this.reporte_modal.descripcion = data.row.descripcion;
            this.reporte_modal.marca = data.row.marca;
            this.reporte_modal.cantidad = data.row.cantidad;
            this.oc_id_registro = data.row.oc_id;
            this.CerrarModalArticulos();
        },

        // Cierra el modal de artículos
        CerrarModalArticulos()
        {
            this.ver_modal_articulos = 0;
            this.list_articulos = [];
        },

        // Muestra las partidas del rime seleccionado
        VerPartidas(row)
        {
            this.rime_abierto=true;
            this.rime = row;
            this.rime_id = row.id;
            this.nueva_rime=false;
            this.folio = row.folio;
            this.oc_folio = row.oc; // Folio
            this.oc_id = row.oc_id;
            this.orden_compra = {
                id: row.oc_id,
                folio: row.oc
            };
            this.reporte_modal.folio = row.folio;
            // this.reviso = '';
            // this.enterado = '';
            // this.aprobo = '':
            this.aprobo = {
                id: row.empleado_aprobo,
                name: row.empleado_aprobo_n
            };
            this.reviso = {
                id: row.empleado_reviso,
                name: row.empleado_reviso_n
            };
            this.enterado = {
                id: row.empleado_enterado,
                name: row.empleado_enterado_n
            };
            this.reporte_modal.fecha = row.fecha;
            this.reporte_modal.no_proyecto = row.no_proyecto;
            this.reporte_modal.factura = row.factura;
            this.tipoAccion_modal_reporte = 3;

            this.BuscarReportes();
        },
        // Cambia la tarjeta a registro/detalle
        AbrirModalReporte(nuevo, model = [])
        {
            let t = this;
            t.tipoAccion_modal_reporte = 2;
            if (nuevo)
            {
                this.nueva_rime=true;
                axios.get(this.url + "/folio/" + this.proyecto.id).then(res =>
                {
                    if (res.data.status)
                    {
                        this.reporte_modal.folio = res.data.folio;
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                })
                this.tipo_registro = 1;
                this.LimpiarRadios();
                this.orden_compra = null;
                this.rime=null;
                this.rime_id=null;
            }
            else
            {
                if (model.length == 0) // Nueva inspección del mismo folio
                {
                    // registrar sobre folio
                    console.error("registrar sobre folio");
                    this.reporte_modal.folio = this.folio;
                    this.tipo_registro = 1;
                    this.LimpiarRadios();
                    this.orden_compra = null;
                    
                }
                else
                {
                    console.error("actu");
                    this.tiene_certificado = model.tiene_certificado == 1 ? true : false;
                    // alert('sd');
                    this.reporte_modal.cantidad_certificados = model.cantidad_equipos_cert;
                    this.reporte_modal.tiene_certificado = model.tiene_certificado;
                    this.reporte_modal.folio = this.folio;
                    this.tipo_registro = 2;
                    this.registro_id = model.id;
                    // Descripcion
                    this.reporte_modal.articulo_id = model.articulo_id;
                    this.articulo.articulo_id = model.articulo_id;
                    this.reporte_modal.descripcion = model.material;
                    this.reporte_modal.marca = model.marca;
                    this.reporte_modal.modelo = model.modelo;
                    this.reporte_modal.no_serie = model.no_serie;
                    this.reporte_modal.no_certificado = model.no_certificado;
                    this.reporte_modal.fecha = model.fecha;
                    this.reporte_modal.no_proyecto = model.no_proyecto;
                    this.reporte_modal.factura = model.factura;
                    // Inspecciones
                    this.reporte_modal.cantidad = model.cantidad;
                    this.inspecciones.marca = model.marca;
                    this.inspecciones.modelo = model.modelo;
                    this.inspecciones.no_serie = model.no_serie;
                    this.inspecciones.no_certificado = model.no_certificado;
                    this.inspecciones.tipo_mecanico = model.tipo_mecanico;
                    this.inspecciones.tipo_estructura = model.tipo_estructura;
                    this.inspecciones.tipo_anticorrosivo = model.tipo_anticorrosivo;
                    this.inspecciones.tipo_electrico = model.tipo_electrico;
                    this.inspecciones.tipo_instrumentacion = model.tipo_instrumentacion;
                    this.inspecciones.ins_mat_factura = model.ins_mat_factura;
                    this.inspecciones.ins_mat_edo_fisico = model.ins_mat_edo_fisico;
                    this.inspecciones.ins_mat_dimesiones = model.ins_mat_dimesiones;
                    this.inspecciones.ins_mat_normativa = model.ins_mat_normativa;
                    this.inspecciones.ins_mat_nacionalidad = model.ins_mat_nacionalidad;
                    this.inspecciones.ins_mat_cetificado = model.ins_mat_cetificado;
                    this.inspecciones.ins_mat_cet_cumpl = model.ins_mat_cet_cumpl;
                    this.inspecciones.ins_mat_coladas = model.ins_mat_coladas;
                    this.inspecciones.ins_mat_documentos = model.ins_mat_documentos;
                    this.inspecciones.ins_equi_recibe_cert = model.ins_equi_recibe_cert;
                    this.inspecciones.ins_equi_recibe_manual = model.ins_equi_recibe_manual;
                    this.inspecciones.ins_equi_recibe_repuesto = model.ins_equi_recibe_repuesto;
                    this.inspecciones.ins_equi_recibe_kit = model.ins_equi_recibe_kit;
                    this.inspecciones.ins_equi_rango = model.ins_equi_rango;
                    this.reporte_modal.observaciones = model.observaciones;
                    this.orden_compra = {
                        id: this.oc_id,
                        folio: this.oc_folio
                    };
                    // this.reviso = '';
                    // this.enterado = '';
                    // this.aprobo = '':
                    this.reviso = {
                        id: model.emp_rev_id,
                        name: model.emp_rev_name,
                    };
                    this.enterado = {
                        id: model.emp_ent_id,
                        name: model.emp_ent_name,
                    };
                    this.aprobo = {
                        id: model.emp_apr_id,
                        name: model.emp_apr_name,
                    };
                    // Coladas
                    if (model.coladas.length == 0)
                        this.list_coladas = [];
                    else
                        this.list_coladas = model.coladas;
                    let n = 0;
                    this.list_coladas.forEach(c =>
                    {
                        n += parseInt(c.cantidad);
                    });
                    this.total = n;
                }
            }
        },

        // Cerrar card de registro
        CerrarModalReporte(tipo)
        {
            //Limpiar
            if (!this.folio || tipo == 3)
                tipo = 1;
            this.tipoAccion_modal_reporte = tipo;
            this.ver_modal_reporte = 1;
            this.reporte_modal = {};
            this.LimpiarRadios();
        },

        // Guardar registro
        GuardarReporte(nuevo)
        {
            console.error(this.rime);
            if (!this.tiene_certificado) // Seleccionado que NO cuenta concertificado
            {
                this.reporte_modal.no_certificado = "N/D";
                this.reporte_modal.cantidad_certificados = 0;
            }
            this.$validator.validate().then(isValid =>
            {
                if (!isValid) return;

                // Documento");
                let certificado = $('#fileCertificado').prop('files')[0];
                // Validaciones
                if (this.articulo.articulo_id == null)
                {
                    toastr.warning("Seleccione un artículo");
                    return;
                }
                if (this.orden_compra == null) return;
                if (this.orden_compra.id == null)
                {
                    toastr.warning("Seleccione una orden de compra");
                    return;
                }
                let marca = this.reporte_modal.marca.length;
                let modelo = this.reporte_modal.modelo.length;
                let cert = this.reporte_modal.no_certificado.length;
                let ns = this.reporte_modal.no_serie.length;
                let n = this.reporte_modal.cantidad == null ? 0 : this.reporte_modal.cantidad;

                if (marca == 0 || modelo == 0 || cert == 0 || ns == 0 || n == 0)
                {
                    toastr.warning("Ingrese todos los campos del artículo");
                    return;
                }
                let mensaje = nuevo ? "registrada" : "actualizada";

                this.is_guardando_loading = true;
                // Form data
                let datos_desc = this.reporte_modal;
                let datos_inspec = this.inspecciones;

                let data = new FormData();
                // Datos de inspección
                Object.keys(datos_inspec).forEach(x =>
                {
                    data.append(x, datos_inspec[x]);
                });

                // Datos del artículo
                Object.keys(datos_desc).forEach(x =>
                {
                    data.append(x, datos_desc[x]);
                });
                if (!nuevo)
                    data.append("id", this.registro_id); // id inspeccion
                data.append("rime_id", this.rime_id);
                data.append("folio", this.reporte_modal.folio);
                data.append("cantidad_equipos_cert", this.reporte_modal.cantidad_certificados);
                data.append("tiene_certificado", this.tiene_certificado ? 1 : 0);
                data.append("nuevo", nuevo ? 1 : 2);
                data.append("proyecto_id", this.proyecto.id);
                data.append("oc_id", this.orden_compra.id);
                data.append("aprobo", this.aprobo.id);
                data.append("enterado", this.enterado.id);
                data.append("reviso", this.reviso.id);
                data.append("certificado", certificado);
                let cantidades = "";
                let ids = "";
                let coladas = "";
                this.list_coladas.forEach(c =>
                {
                    ids += c.id == null ? null : c.id;
                    ids += "|";
                    coladas += c.colada + "|";
                    cantidades += c.cantidad + "|";
                });
                data.append("ids", ids);
                data.append("cantidades", cantidades);
                data.append("coladas", coladas);

                axios(
                {
                    url: this.url + "/guardar",
                    method: 'POST',
                    data,
                }).then((res) =>
                {
                    this.is_guardando_loading = false;
                    if (res.data.status)
                    {
                        toastr.success("Inspección " + mensaje + " correctamente");
                        this.CerrarModalReporte(3);
                        // Obtener inspecciones
                        let me = this;
                        if (nuevo == 1)
                        {
                            me.VerPartidas(res.data.rime);
                        }
                        else
                        {
                            me.VerPartidas(me.rime);
                        }
                        this.list_coladas = [];
                        this.coladas_temp = [];
                        this.total = 0;
                        // this.BuscarReportes();
                        this.ObtenerRime();
                        this.tipoAccion_modal_reporte = 3; //Lista de inspecciones
                    }
                    else
                    {
                        toastr.error(res.data.mensaje, "Error");
                    }
                }).catch(x =>
                {
                    console.error(x);
                });
            });
        }, // Fin metodos
        LimpiarRadios()
        {
            this.list_coladas = [];
            this.total = 0;
            this.inspecciones = {
                tipo_mecanico: "N/A",
                tipo_estructura: "N/A",
                tipo_anticorrosivo: "N/A",
                tipo_electrico: "N/A",
                tipo_instrumentacion: "N/A",
                ins_mat_factura: "A",
                ins_mat_edo_fisico: "A",
                ins_mat_dimesiones: "A",
                ins_mat_normativa: "A",
                ins_mat_nacionalidad: "A",
                ins_mat_cetificado: "A",
                ins_mat_cet_cumpl: "A",
                ins_mat_coladas: "A",
                ins_mat_documentos: "A",
                ins_equi_recibe_cert: "A",
                ins_equi_recibe_manual: "A",
                ins_equi_recibe_repuesto: "A",
                ins_equi_recibe_kit: "A",
                ins_equi_rango: "A",
            };
        },

        Descargar(data)
        {
            window.open('/calidad/inspecciones/descargar/' + data.id, '_blank');
        },

        SubirAdjunto(data)
        {
            this.tipoAccion_modal_reporte = 4;
            this.data = data;

        },
        DescargarOC(oc_id)
        {
            window.open('descargar-compran/' + oc_id, '_blank');
        },
        DescargarCertificado(id)
        {
            window.open(this.url + '/descargar-certificado/' + id, '_blank');
        },
        AbrirModalColadas()
        {
            this.coladas_temp = [...this.list_coladas];
            this.ver_modal_coladas = true;
            this.ver_modal_coladas = true;
        },
        CerrarModalColadas()
        {
            this.ver_modal_coladas = false;
            this.coladas_temp = [];
        },
        // Guadar una colada en temporal
        GuardarColadaTemp()
        {
            if (this.colada_temp.cantidad == null) return;
            if (this.colada_temp.cantidad <= 0)
            {
                toastr.warning("Ingrese una cantidad");
                return;
            }
            if (this.colada_temp.colada == null) return;
            if (this.colada_temp.colada == "")
            {
                toastr.warning("Ingrese una colada");
                return;
            }
            this.total += parseInt(this.colada_temp.cantidad);
            this.coladas_temp.push(
            {
                ...this.colada_temp
            });
            this.colada_temp = {};
        },
        // Guarda las coladas del temp
        GuardarColadas()
        {
            this.list_coladas = [...this.coladas_temp];
            this.coladas_temp = [];
            this.ver_modal_coladas = false;
        },

        /**
         * Muestra las RIMES que coincidan con el artículo ingresado del proyecto seleccionado
         */
        Buscar()
        {
            if (this.proyecto == null)
            {
                toastr.warning("Seelccione un proyecto");
                return;
            }
            if (this.proyecto.id == null)
            {
                toastr.warning("Seelccione un proyecto");
                return;
            }
            let data = this.proyecto.id + "&" + this.busqueda;
            this.is_rimes_loading = true;
            // mostrar campo de articulo en tabla
            this.columns_rimes = ["id", "folio", "oc", "articulo"];
            axios.get(this.url + "/buscar/" + data).then(res =>
            {
                if (res.data.status)
                {
                    this.list_rimes = [];
                    res.data.inspecciones.forEach((o) =>
                    {
                        this.list_rimes.push(
                        {
                            id: o.id,
                            folio: o.folio_rime,
                            oc: o.oc_folio,
                            oc_id: o.oc_id,
                            fecha: o.fecha,
                            no_proyecto: o.no_proyecto,
                            factura: o.factura,
                            empleado_reviso_n: o.empleado_reviso_n,
                            empleado_aprobo_n: o.empleado_aprobo_n,
                            empleado_enterado_n: o.empleado_enterado_n,
                            empleado_reviso: o.empleado_reviso,
                            empleado_enterado: o.empleado_enterado,
                            empleado_aprobo: o.empleado_enterado,
                            articulo: o.descripcion,
                        });
                    });
                    this.is_rimes_loading = false;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        /*
         * Cambiar el tipo de OC al existencia en almacén
         */
        CambiarTipoOC(e)
        {
            this.oc_temp = !this.oc_temp;
            if (this.oc_temp)
            {
                // Mostrar la OC temporal
                this.list_oc = [];
                let folio = `OC-${this.proyecto.nombre}-TEMP-001`;
                this.orden_compra = {
                    id: -99,
                    folio
                };
            }
        }
    },
    mounted()
    {
        this.Cargar();
    }

}
</script>
