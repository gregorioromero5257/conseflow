<template>
<div class="main">

    <!-- Card Inicio-->
    <div class="card" v-if="tipoAccion_modal_reporte == 1">
        <!-- Inicio card-->
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Inspecciones de calidad - Cliente
            <button type="button" class="btn btn-dark float-sm-right" @click="AbrirModalRime()">
                <i class="fas fa-plus">&nbsp;</i>Nuevo
            </button>
        </div>
        <div class="card-body">
            <div class="">
                <!-- Tabla de Rimes-->
                <div class="">
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Proyecto</label>
                        <vue-element-loading :active="is_proyectos_loading" />
                        <div class="col-md-6">
                            <v-select v-model="rime.proyecto" :options="list_proyectos" label="nombre"></v-select>
                        </div>
                        <button class="btn btn-sm btn-dark" @click="ObtenerRime()">
                            <i class="fas fa-search"></i> &nbsp; Buscar
                        </button>
                    </div>
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
                                        <button type="button" @click="Detalles(props.row)" class="dropdown-item">
                                            <i class="fas fa-eye"></i>&nbsp;Detalles
                                        </button>
                                        <button type="button" @click="VerPartidas(props.row.id,props.row.folio)" class="dropdown-item">
                                            <i class="fas fa-eye"></i>&nbsp;Ver partidas
                                        </button>
                                        <button type="button" @click="DescargarRime(props.row)" class="dropdown-item">
                                            <i class="fas fa-file-pdf"></i>&nbsp;Descargar Rime
                                        </button>
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

    <!-- Card Rime (Encabezado) -->
    <div v-if="ver_modal_rime" class="modal fade" tabindex="-1" :class="{ mostrar: ver_modal_rime}" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Inspección</h4>
                    <button type="button" class="close" @click="CerrarModalRime()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <vue-element-loading :active="is_crear_rime_loading" />
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">Fecha</label>
                        <div class="col-md-3">
                            <input type="date" v-validate="'required'" name="fecha" v-model="rime.fecha" class="form-control" />
                            <span class="text-danger">{{ errors.first('fecha') }}</span>
                        </div>
                    </div>
                    <!-- Proyectos -->
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">Proyecto</label>
                        <vue-element-loading :active="is_proyectos_loading" />
                        <div class="col-md-6">
                            <v-select v-model="rime.proyecto" v-validate="'required'" name="proyecto" :options="list_proyectos" label="nombre" @input="VerCliente"></v-select>
                            <span class="text-danger">{{ errors.first('proyecto') }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">Cliente</label>
                        <div class="col-md-6">
                            <input type="text" v-model="rime.proyecto.cliente" class="form-control" v-validate="'required'" name="cliente" />
                            <span class="text-danger">{{ errors.first('cliente') }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">No. Proyecto</label>
                        <div class="col-md-6">
                            <input type="text" v-validate="'required'" name="no_proyecto" v-model="rime.no_proyecto" class="form-control" />
                            <span class="text-danger">{{ errors.first('no_proyecto') }}</span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <label class="col-md-3 form-control-label">Revisó</label>
                        <div class="col-md-6">
                            <v-select :options="listEmpleados" v-validate="'required'" v-model="rime.reviso" label="nombre" data-vv-name="empleado que reviso"></v-select>
                            <span class="text-danger">{{ errors.first('empleado que reviso') }}</span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <label class="col-md-3 form-control-label">Enterado</label>
                        <div class="col-md-6">
                            <v-select :options="listEmpleados" v-validate="'required'" v-model="rime.enterado" label="nombre" data-vv-name="empleado enterado"></v-select>
                            <span class="text-danger">{{ errors.first('empleado enterado') }}</span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <label class="col-md-3 form-control-label">Aprobó</label>
                        <div class="col-md-6">
                            <v-select :options="listEmpleados" v-validate="'required'" v-model="rime.aprobo" label="nombre" data-vv-name="empleado aprueba"></v-select>
                            <span class="text-danger">{{ errors.first('empleado aprueba') }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" @click="CerrarModalRime()">
                        <i class="fas fa-window-close"></i>&nbsp;Cerrar
                    </button>
                    <button v-if="modificar_rime" type="button" class="btn btn-outline-dark" @click="GuardarRime()">
                        <i class="fas fa-save"></i>&nbsp;Guardar
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Card partidas-->
    <div class="card" v-if="tipoAccion_modal_reporte == 3">
        <!-- Inicio card-->
        <div class="card-header">
            <i class="fa fa-align-justify"></i> {{folio}}
            <button type="button" class="btn btn-dark float-sm-right" @click="AbrirModalReporte(true)">
                <i class="fas fa-plus">&nbsp;</i>Nuevo
            </button>
            <button type="button" class="ml-2 btn btn-dark float-sm-right" @click="CerrarModalReporte(1)">
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
                        <template slot="certificado" slot-scope="props">
                            <button v-if="props.row.certificado!=null" class="btn btn-outline-dark" @click="DescargarCertificado(props.row.id)">
                                <i class="fas fa-file-pdf"></i>
                                <i class="fas fa-download ml-1"></i>
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
                    <br>
                    <h4>Datos de equipos e instrumentos</h4>
                    <br>
                    <br>
                    <!-- PArtida / Cantidad -->
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Partida</label>
                        <div class="col-md-3">
                            <input type="number" v-validate="'required'" name="partida" step="1" v-model="inspecciones.partida" class="form-control" />
                            <span class="text-danger">{{ errors.first('partida') }}</span>
                        </div>

                        <label class="col-md-1 form-control-label">Cantidad</label>
                        <div class="col-md-3">
                            <input type="number" step="1" v-validate="'required'" name="cantidad" v-model="inspecciones.cantidad" class="form-control" />
                            <span class="text-danger">{{ errors.first('cantidad') }}</span>
                        </div>
                    </div>

                    <!-- Articulos -->
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Artículo</label>
                        <div class="col-md-8">
                            <textarea v-model="inspecciones.articulo" v-validate="'required'" name="articulo" class="form-control bg-white" rows="2"></textarea>
                            <span class="text-danger">{{ errors.first('articulo') }}</span>
                        </div>
                    </div>

                    <!-- Modelo -->
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Marca</label>
                        <div class="col-md-3">
                            <input type="text" v-validate="'required'" name="marca" class="form-control" v-model="inspecciones.marca" />
                            <span class="text-danger">{{ errors.first('marca') }}</span>
                        </div>
                        <label class="col-md-2 form-control-label">Modelo</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" v-validate="'required'" name="modelo" v-model="inspecciones.modelo" />
                            <span class="text-danger">{{ errors.first('modelo') }}</span>
                        </div>
                    </div>

                    <!-- Certificado -->
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Tag</label>
                        <div class="col-md-3">
                            <input type="text" v-validate="'required'" name="tag" class="form-control" v-model="inspecciones.tag" />
                            <span class="text-danger">{{ errors.first('tag') }}</span>
                        </div>

                        <label class="col-md-2 form-control-label">No. Serie</label>
                        <div class="col-md-3">
                            <input type="text" name="serie" v-validate="'required'" class="form-control" v-model="inspecciones.no_serie" />
                            <span class="text-danger">{{ errors.first('serie') }}</span>
                        </div>
                    </div>

                    <!-- Colado -->
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Colada</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="colada" v-validate="'required'" v-model="inspecciones.colada" />
                            <span class="text-danger">{{ errors.first('colada') }}</span>
                        </div>
                        <label class="col-md-2 form-control-label">No. Certificado</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" v-validate="'required'" name="certificado" v-model="inspecciones.no_certificado" />
                            <span class="text-danger">{{ errors.first('certificado') }}</span>
                        </div>
                    </div>

                    <!-- Tipo -->
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Tipo</label>
                        <div class="col-md-3">
                            <select class="form-control" v-validate="'required'" name="tipo" v-model="inspecciones.tipo_equipo_id">
                                <option value="1" selected>Equipo</option>
                                <option value="2">Instrumento</option>
                            </select>
                            <span class="text-danger">{{ errors.first('tipo') }}</span>
                        </div>

                    </div>

                    <br>
                    <h4>Inspección de equipos e instrumentos</h4>
                    <br>
                    <br>
                    <div class="form-group row">
                        <label class="form-control-label col-md-3">Certificados de calibración</label>

                        <div class="form-group col-2">
                            <input class="" type="radio" value="A" v-model="inspecciones.inspeccion_certificado">
                            <label class="form-control-label">Aceptado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="R" v-model="inspecciones.inspeccion_certificado">
                            <label class="radio-inspeccion">Rechazado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="N/A" v-model="inspecciones.inspeccion_certificado">
                            <label class="form-control-label">N/A</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="form-control-label col-md-3">Manuales de instalación y/o mantenimiento</label>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="A" v-model="inspecciones.inspeccion_manuales">
                            <label class="form-control-label">Aceptado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="R" v-model="inspecciones.inspeccion_manuales">
                            <label class="radio-inspeccion">Rechazado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="N/A" v-model="inspecciones.inspeccion_manuales">
                            <label class="form-control-label">N/A</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="form-control-label col-md-3">Partes de repuesto</label>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="A" v-model="inspecciones.inspeccion_respuesto">
                            <label class="form-control-label">Aceptado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="R" v-model="inspecciones.inspeccion_respuesto">
                            <label class="radio-inspeccion">Rechazado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="N/A" v-model="inspecciones.inspeccion_respuesto">
                            <label class="form-control-label">N/A</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="form-control-label col-md-3">Kits de instalación</label>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="A" v-model="inspecciones.inspeccion_kids">
                            <label class="form-control-label">Aceptado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="R" v-model="inspecciones.inspeccion_kids">
                            <label class="radio-inspeccion">Rechazado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="N/A" v-model="inspecciones.inspeccion_kids">
                            <label class="form-control-label">N/A</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="form-control-label col-md-3">Presenta daños fisicos</label>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="A" v-model="inspecciones.inspeccion_dagnos">
                            <label class="form-control-label">Aceptado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="R" v-model="inspecciones.inspeccion_dagnos">
                            <label class="radio-inspeccion">Rechazado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="N/A" v-model="inspecciones.inspeccion_dagnos">
                            <label class="form-control-label">N/A</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="form-control-label col-md-3">Embalaje</label>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="A" v-model="inspecciones.inspeccion_embalaje">
                            <label class="form-control-label">Aceptado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="R" v-model="inspecciones.inspeccion_embalaje">
                            <label class="radio-inspeccion">Rechazado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="N/A" v-model="inspecciones.inspeccion_embalaje">
                            <label class="form-control-label">N/A</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="form-control-label col-md-3">Almacenamiento</label>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="A" v-model="inspecciones.inspeccion_almacenamiento">
                            <label class="form-control-label">Aceptado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="R" v-model="inspecciones.inspeccion_almacenamiento">
                            <label class="radio-inspeccion">Rechazado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="N/A" v-model="inspecciones.inspeccion_almacenamiento">
                            <label class="form-control-label">N/A</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="form-control-label col-md-3">Conservacion</label>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="A" v-model="inspecciones.inspeccion_conservacion">
                            <label class="form-control-label">Aceptado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="R" v-model="inspecciones.inspeccion_conservacion">
                            <label class="radio-inspeccion">Rechazado</label>
                        </div>
                        <div class="form-group col-2">
                            <input class="" type="radio" value="N/A" v-model="inspecciones.inspeccion_conservacion">
                            <label class="form-control-label">N/A</label>
                        </div>
                    </div>
                    <br>

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Documentos</label>
                        <div class="col-md-8">
                            <input class="form-control col-10" accept="application/pdf" type="file" required id="fileCertificado">
                        </div>
                    </div>
                    <br>
                    <br>

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Observaciones</label>
                        <div class="col-md-8">
                            <textarea v-model="inspecciones.observaciones" class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                    <br>
                    <!--Card body -->
                    <div class='modal-footer'>
                        <button type='button' v-if='tipo_registro== 1' class='btn btn-secondary' @click='GuardarReporte(true)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                        <button type='button' v-if='tipo_registro==2' class='btn btn-secondary' @click='GuardarReporte(false)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                    </div> <!-- Card footer -->
                </div>
            </div>
            <!-- card-->
        </div>
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
            url: "/calidad/inspeccionescliente",
            // Tabla
            ver_modal_reporte: 0,
            rime:
            {
                id: 0,
                proyecto:
                {
                    id: 0,
                    nombre: "",
                    cliente: "",
                },
            },
            modificar_rime:true,
            columns_reporte: [
                "id",
                "articulo",
                "cantidad",
                "marca",
                "modelo",
                "certificado"
            ],
            list_reporte: [],
            options_reporte:
            {
                headings:
                {
                    id: "Acciones",
                    material: "Descripción",
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
            nombre_card: "Registrar inspección",
            titulo_modal_reporte: "",
            tipoAccion_modal_reporte: 1,
            list_proyectos: [],
            list_articulos: [],
            list_oc: [],
            proyecto:
            {},
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
                cliente: "",
                no_proyecto: '',
                factura: '',
                fecha: '',
                reviso:
                {

                },
                enterado:
                {

                },
                aprobo:
                {

                },
            },

            // Radios
            inspecciones:
            {},
            listEmpleados: [],
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
                sortable: [
                    "descripcion"
                ],
                filterable: [
                    "descripcion"
                ],
                filterByColumn: true,
                texts: config.texts,
            }, //options
            // Rimes
            list_rimes: [],
            columns_rimes: ["id", "folio", "fecha"],
            options_rimes:
            {
                headings:
                {
                    id: "Acciones",
                    folio: "Folio",
                    fecha: "Fecha de recepción",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ["folio", "fecha"],
                filterable: ["folio", "fecha"],
                filterByColumn: true,
                texts: config.texts,
            }, //options
            is_rimes_loading: false,
            is_inspecciones_loading: false,
            folio: "",
            oc_id: 0,
            oc_folio: "",
            // Encabezado
            ver_modal_rime: false,
            is_crear_rime_loading: false,
        }; // return
    }, //data
    computed:
    {},
    methods:
    {
        // * Obtiene todos los proyectos de la DB
        CargarProyectos()
        {
            this.is_proyectos_loading = true;
            axios.get(this.url + "/proyectos").then(res =>
                {
                    if (res.data.status)
                    {
                        this.list_proyectos = res.data.proyectos;
                    }
                    else
                    {
                        toastr.show(res.data.mensaje);
                    }
                    this.is_proyectos_loading = false;
                })
                .catch((x) =>
                {
                    console.error(x);
                });
        },

        // Crea una nueva rime (encabezado)
        AbrirModalRime()
        {
            // Obtener empleados
            axios.get(this.url + "/empledos").then(res =>
            {
                this.listEmpleados = res.data.empleados;
            });
            this.ver_modal_rime = true;
            this.modificar_rime = true;
        },
        Detalles(model)
        {
            console.error(model);
            // Obtener empleados
            axios.get(this.url + "/empledos").then(res =>
            {
                this.listEmpleados = res.data.empleados;
            });
            this.rime.fecha = model.fecha;
            this.rime.proyecto = {
                id: model.proyecto_id,
                nombre: model.proyecto,
                cliente: model.cliente,
            };
            this.rime.no_proyecto = model.no_proyecto;
            this.rime.reviso = {
                id: model.reviso_id,
                nombre: model.reviso,
            };
            this.rime.aprobo = {
                id: model.aprobo_id,
                nombre: model.aprobo,
            };

            this.rime.enterado = {
                id: model.enterado_id,
                nombre: model.enterado,
            };
            this.ver_modal_rime = true;
            this.modificar_rime = false;
        },
        CerrarModalRime()
        {
            this.ver_modal_rime = false;
        },

        // Crea una nueva rime (encabezado)
        GuardarRime()
        {

console.error(this.rime);
            this.$validator.validate().then(result =>
            {
                if (!result) return;
                this.is_crear_rime_loading = true;
                axios.post(this.url + "/crearrime",
                {
                    "proyecto_id": this.rime.proyecto.id,
                    "proyecto_no": this.rime.proyecto_no,
                    "fecha": this.rime.fecha,
                    "reviso_id": this.rime.reviso.id,
                    "enterado_id": this.rime.enterado.id,
                    "aprobo_id": this.rime.aprobo.id,
                    "no_proyecto": this.rime.no_proyecto,
                }).then(res =>
                {
                    if (res.data.status)
                    {
                        let aux = this.rime.proyecto;
                        toastr.success("Inspección creada correctamente");
                        this.ObtenerRime();
                        this.Limpiar(this.rime);
                        this.CerrarModalRime();
                        this.rime.proyecto = aux;
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                }).catch(r =>
                {
                    console.error(r);
                });
                this.is_crear_rime_loading = false;
            });
        },

        // Obtiene las partidas de la rime seleccionada ?
        ObtenerRime()
        {
            if (this.rime.proyecto == null) return;
            if (this.rime.proyecto.id == null) return;
            this.is_rimes_loading = true;

            axios.get(this.url + "/rime/" + this.rime.proyecto.id).then(res =>
                {
                    this.list_rimes = [];
                    if (res.data.status)
                    {
                        this.list_rimes = res.data.inspecciones;
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

        // * Muestra el cliente del proyecto seleccioado
        VerCliente()
        {

        },

        // XX Muestra las partidas del rime seleccionado
        VerPartidas(rime_id, folio)
        {
            this.folio = folio;
            this.rime.id = rime_id;
            this.tipoAccion_modal_reporte = 3;
            this.ObtenerPartidas();
        },
        // Obtiene las inpsecciones de la rime seleccionada
        ObtenerPartidas()
        {
            this.is_inspecciones_loading = true;
            axios.get(this.url + "/partidas/" + this.rime.id).then(res =>
            {
                if (res.data.status)
                {
                    this.list_reporte = res.data.inspecciones;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            }).catch(r =>
            {
                toastr.error("ERROR");
            });
            this.is_inspecciones_loading = false;
        },
        VerInspeccion(row)
        {
            this.AbrirModalReporte(false, row);
        },
        // XX Cambia la tarjeta a registro/detalle
        AbrirModalReporte(nuevo, model = [])
        {
            let t = this;
            t.tipoAccion_modal_reporte = 2;
            if (nuevo)
            {
                this.tipo_registro = 1;
                this.LimpiarRadios();
            }
            else
            {
                // actualizar
                this.inspecciones.id = model.id;
                this.inspecciones = model;
                this.inspecciones.tipo_equipo_id = model.tipo_equipo == "X" ? 1 : 2;
            }
        },

        // * Cerrar card de registro
        CerrarModalReporte(tipo)
        {
            // //Limpiar
            this.tipoAccion_modal_reporte = tipo;
            this.ver_modal_reporte = 1;
            this.LimpiarRadios();
        },

        // * Guardar registro
        GuardarReporte(nuevo)
        {
            this.$validator.validate().then(result =>
            {
                if (!result) return;

                let mensaje = nuevo ? "registrada" : "actualizada";
                this.is_guardando_loading = true;
                // Documento
                let certificado = $('#fileCertificado').prop('files')[0];
                // Validaciones

                // Form data
                let datos_inspec = this.inspecciones; // Radios

                let data = new FormData();
                data.append("rime_id", this.rime.id);
                data.append("certificado_pdf", certificado);
                // Datos de inspección
                Object.keys(datos_inspec).forEach(x =>
                {
                    data.append(x, datos_inspec[x]);
                });
                axios(
                {
                    url: this.url + "/guardar",
                    method: 'POST',
                    data,
                }).then(res =>
                {
                    this.is_guardando_loading = false;
                    if (res.data.status)
                    {
                        toastr.success("Inspección " + mensaje + " correctamente");
                        this.CerrarModalReporte(3);
                        // Obtener inspecciones
                        let x = this;
                        x.VerPartidas(res.data.rime, this.folio);

                        this.tipoAccion_modal_reporte = 3; //Lista de inspecciones
                    }
                    else
                    {
                        toastr.error(res.data.mensaje, "Error");
                    }
                }).catch(x =>
                {
                    this.is_guardando_loading = false;
                    // toastr.error("Error al guardar inspección");
                    console.error(x);
                });
            });
        }, // Fin metodos
        LimpiarRadios()
        {
            this.inspecciones = {
                marca: "",
                articulo: "",
                modelo: "",
                tag: "",
                no_serie: "",
                colada: "",
                no_certificado: "",
                tipo_equipo: "",
                tipo_inssto: "",
                inspeccion_certificado: "N/A",
                inspeccion_manuales: "N/A",
                inspeccion_respuesto: "N/A",
                inspeccion_kids: "N/A",
                inspeccion_dagnos: "N/A",
                inspeccion_embalaje: "N/A",
                inspeccion_almacenamiento: "N/A",
                inspeccion_conservacion: "N/A",
                observaciones: "",
                reviso:
                {},
                aprobo:
                {},
                enterado:
                {},
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
            window.open(this.url + '/certificado/' + id, '_blank');
        },
        DescargarRime(model)
        {
            window.open(this.url + '/descargar/' + model.id, '_blank');
        },
        Limpiar(obj)
        {

            for (let key in obj)
            {
                if (typeof obj[key] === 'string')
                    obj[key] = '';
                else if (typeof obj[key] === 'number')
                    obj[key] = 0;
                else if (typeof obj[key] === 'object')
                    obj[key] = {};
            }

        },

    },
    mounted()
    {
        this.CargarProyectos();
    }

}
</script>
