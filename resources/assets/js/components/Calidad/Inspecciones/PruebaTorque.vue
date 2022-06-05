<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card' v-if="tipoCard==1">
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Reporte de Torque
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalTorque(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <div class="row">
                    <label class="col-md-1" for="">Proyecto</label>
                    <div class="col-md-6">
                        <v-select :options="listProyectos" label="nombre" v-model="proyecto"></v-select>
                    </div>
                    <button class="btn btn-dark btn-sm" @click="ObtenerReportes">
                        <i class="fas fa-search mr-1"></i>Buscar
                    </button>
                </div>
                <br>
                <!-- Tabla de Torque-->
                <vue-element-loading :active="isTorqueLoading" />
                <div>
                    <v-client-table :columns='columns_torque' :data='list_torque' :options='options_torque' ref='tbl_torque'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalTorque(false, props.row)' class='dropdown-item'>
                                            <i class='fas fa-edit'></i>&nbsp;Actualizar
                                        </button>
                                        <button type='button' @click='VerPartidas(props.row)' class='dropdown-item'>
                                            <i class='fas fa-eye'></i>&nbsp;Ver Partidas
                                        </button>
                                        <button type='button' @click='Reporte(props.row.id)' class='dropdown-item'>
                                            <i class='fas fa-file-pdf'></i>&nbsp;Descargar Reporte
                                        </button>

                                        <button type='button' @click='AbrirCardImagenes(props.row)' class='dropdown-item'>
                                            <i class='fas fa-images'></i>&nbsp;Ver Imágenes
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template slot='aceptado' slot-scope='props'>
                            <button v-if="props.row.aceptado" class="btn btn-success">Aceptado</button>
                            <button v-else class="btn btn-warning">Rechazado</button>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Torque -->
    <div class="card" v-if="tipoCard==2">
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Reporte de Torque
            <button type='button' class='btn btn-dark float-sm-right' @click='Regresar()'>
                <i class='fas fa-arrow-left'>&nbsp;</i> Regresar
            </button>
        </div>
        <div class="container mt-2 card-body">
            <vue-element-loading :active="isGuadarLoading" />

            <div class='form-group row'>
                <label class='col-md-2 form-control-label'>Proyecto</label>
                <div class='col-md-6'>
                    <input :disabled="true" type='text' class='form-control' v-model='torque_modal.nombre_proyecto'>
                </div>
            </div>

            <div class='form-group row'>
                <label class='col-md-2 form-control-label'>Ubicación</label>
                <div class='col-md-5'>
                    <input type='text' name="ubicacion" class='form-control' v-model='torque_modal.ubicacion'>
                    <!-- <span class="text-danger">{{errors.first("ubicacion")}}</span> -->
                </div>

                <label class='col-md-2 form-control-label'>Fecha</label>
                <div class='col-md-2'>
                    <input type='date' name="fecha" v-validate="'required'" class='form-control' v-model='torque_modal.fecha'>
                    <span class="text-danger">{{errors.first("fecha")}}</span>
                </div>
            </div>

            <div class='form-group row'>
                <label class='col-md-2 form-control-label'>Cliente</label>
                <div class='col-md-5'>
                    <input type='text' :disabled="true" v-validate="'required'" name="cliente" class='form-control' v-model='torque_modal.cliente'>
                    <span class="text-danger">{{errors.first("cliente")}}</span>
                </div>

                <label class='col-md-2 form-control-label'>No. reporte</label>
                <div class='col-md-3'>
                    <input type='text' name="folio" v-validate="'required'" class='form-control' v-model='torque_modal.folio'>
                    <span class="text-danger">{{errors.first("folio")}}</span>
                </div>
            </div>
            <hr class="ml-0 mt-1 w-75">
            <div class='form-group row'>
                <label class='col-md-2 form-control-label'>Elemento</label>
                <div class='col-md-5'>
                    <v-select v-show="tipoAccion_modal_torque==1" name="elemento" :options="list_sistemas" label="nombre" v-model="sistema"></v-select>
                    <input v-show="tipoAccion_modal_torque==2" :disabled="true" type="text" class="form-control" v-model="torque_modal.aux_sistema">
                </div>

                <label class='col-md-2 form-control-label'>Diámtetro</label>
                <div class='col-md-3'>
                    <input v-validate="'required'" name="diametro" type='text' class='form-control' v-model='torque_modal.diametro'>
                    <span class="text-danger">{{errors.first("diametro")}}</span>
                </div>
            </div>

            <div class='form-group row'>
                <label class='col-md-2 form-control-label'>TAG</label>
                <div class='col-md-3'>
                    <input type='text' disabled="true" v-validate="'required'" name="tag" class='form-control' v-model='sistema.tag'>
                    <span class="text-danger">{{errors.first("tag")}}</span>
                </div>

                <label class='col-md-2 form-control-label'>Material</label>
                <div class='col-md-5'>
                    <input type='text' name="material" v-validate="'required'" class='form-control' v-model='torque_modal.material'>
                    <span class="text-danger">{{errors.first("material")}}</span>
                </div>
            </div>

            <div class='form-group row'>
                <label class='col-md-2 form-control-label'>Referencia</label>
                <div class='col-md-4'>
                    <input type='text' name="referencia" v-validate="'required'" class='form-control' v-model='torque_modal.referencia'>
                    <span class="text-danger">{{errors.first("referencia")}}</span>
                </div>

                <label class='col-md-2 form-control-label'>Procedimiento</label>
                <div class='col-md-4'>
                    <input type='text' v-validate="'required'" name="procedimiento" class='form-control' v-model='torque_modal.procedimiento'>
                    <span class="text-danger">{{errors.first("procedimiento")}}</span>
                </div>
            </div>

            <hr class="ml-0 mt-1 w-75">

            <p class="h4 my-2">Equipo para Torque</p>

            <div class='form-group row'>
                <label class='col-md-2 form-control-label'>Equipo 1</label>
                <div class='col-md-5'>
                    <v-select :options="listTorquimetros" name="equipo1" v-validate="'required'" label="descripcion" v-model="equipo1"></v-select>
                    <span class="text-danger">{{errors.first("equipo1")}}</span>
                </div>

                <label class='col-md-1 form-control-label'>NS</label>
                <div class='col-md-4'>
                    <input disabled class="form-control" type="text" v-model="equipo1.numero_serie ">
                </div>
            </div>

            <div class='form-group row'>
                <label class='col-md-2 form-control-label'>Modelo</label>
                <div class='col-md-3'>
                    <input disabled type='text' class='form-control' v-model='equipo1.modelo'>
                </div>

                <label class='col-md-3 form-control-label'>Secuencia de torque</label>
                <div class='col-md-4'>
                    <input type='text' v-validate="'required'" name="secuencia1" class='form-control' v-model='torque_modal.secuencia1'>
                    <span class="text-danger">{{errors.first("secuencia1")}}</span>
                </div>
            </div>

            <hr class="ml-0 mt-1 w-75">
            <div class='form-group row'>
                <label class='col-md-2 form-control-label'>Equipo 2</label>
                <div class='col-md-5'>
                    <v-select :options="listHerramientras" name="equipo2" v-validate="'required'" label="descripcion" v-model="equipo2"></v-select>
                    <span class="text-danger">{{errors.first("equipo2")}}</span>
                </div>

                <label class='col-md-1 form-control-label'>NS</label>
                <div class='col-md-4'>
                    <input class="form-control" disabled type="text" v-model="equipo2.numero_serie">
                </div>
            </div>

            <div class='form-group row'>
                <label class='col-md-2 form-control-label'>Modelo</label>
                <div class='col-md-3'>
                    <input disabled type='text' class='form-control' v-model='equipo2.modelo'>
                </div>

                <label class='col-md-3 form-control-label'>Secuencia de torque</label>
                <div class='col-md-4'>
                    <input type='text' v-validate="'required'" name="secuencia2" class='form-control' v-model='torque_modal.secuencia2'>
                    <span class="text-danger">{{errors.first("secuencia2")}}</span>
                </div>
            </div>

            <hr class="ml-0 mt-1 w-75">

            <div class="row">
                <div class="col-md-4">
                    <div class='form-group row'>
                        <label class='col-md-12 form-control-label'>Inspecciona</label>
                        <div class='col'>
                            <v-select v-validate="'required'" name="inspecciona1" :options="list_empleados" label="nombre" v-model="torque_modal.inspecciona1"></v-select>
                            <span class="text-danger">{{errors.first("inspecciona1")}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class='form-group row'>
                        <label class='col-md-12 form-control-label'>Inspecciona</label>
                        <div class='col'>
                            <input type="text" v-validate="'required'" name="inspecciona2" class="form-control" v-model="torque_modal.inspecciona2">
                            <span class="text-danger">{{errors.first("inspecciona2")}}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class='form-group row'>
                        <label class='col-md-12 form-control-label'>Aprueba</label>
                        <div class='col'>
                            <input class="form-control" type="text" name="aprueba" v-validate="'required'" v-model="torque_modal.aprueba">
                            <span class="text-danger">{{errors.first("aprueba")}}</span>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <button type='button' v-if='tipoAccion_modal_torque== 1' class='btn btn-secondary float-sm-right' @click='GuardarTorque(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_torque==2' class='btn btn-secondary float-sm-right' @click='GuardarTorque(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                </div>
            </div>

        </div>

        <!-- /.modal-dialog -->
    </div>

    <!-- Partidas de toque -->
    <div class="card" v-if="tipoCard==3">
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> {{folio}}
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalPartidas(true)'>
                <i class='fas fa-plus'>&nbsp;</i> Nuevo
            </button>
            <button type='button' class='btn btn-dark float-sm-right' @click='Regresar()'>
                <i class='fas fa-arrow-left'>&nbsp;</i> Regresar
            </button>
        </div>
        <br>
        <div class="container car-body">
            <!-- Tabla de Partidas-->
            <v-client-table :columns='columns_partidas' :data='list_partidas' :options='options_partidas' ref='tbl_partidas'>
                <template slot='id' slot-scope='props'>
                    <div class='btn-group' role='group'>
                        <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                            <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                        </button>
                        <div class='dropdown-menu'>
                            <template>
                                <button type='button' @click='AbrirModalPartidas(false, props.row)' class='dropdown-item'>
                                    <i class='icon-pencil'></i>&nbsp;Actualizar
                                </button>
                            </template>
                        </div>
                    </div>
                </template>
            </v-client-table>
            <!--Card body -->
        </div>
    </div>

    <div v-if="tipoCard==4">
        <card-imagenes @regresar="Regresar" :folio="folio" :reporte_id="reporte_id"></card-imagenes>
    </div>

    <!-- Modal Partidas -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_partidas}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Partidas del reporte</h4>
                    <button type='button' class='close' @click='CerrarModalPartidas()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class='form-group row'>
                        <label class='col-md-4 form-control-label'>Item</label>
                        <div class='col-md-4'>
                            <input type='text' name="item" v-validate="'required'" class='form-control' v-model='partidas_modal.item'>
                            <span class="text-danger">{{errors.first("item")}}</span>
                        </div>
                    </div>

                    <div class='form-group row'>
                        <label class='col-md-4 form-control-label'>Ubicacion Unión Brida</label>
                        <div class='col-md-6'>
                            <input type='text' v-validate="'required'" name="ubicacion" class='form-control' v-model='partidas_modal.ubicacion'>
                            <span class="text-danger">{{errors.first("ubicacion")}}</span>
                        </div>
                    </div>

                    <div class='form-group row'>
                        <label class='col-md-4 form-control-label'>Clase de brida</label>
                        <div class='col-md-8'>
                            <v-select label="nombre" :options="listBridas" v-model="brida"></v-select>
                        </div>
                    </div>

                    <div class='form-group row'>
                        <label class='col-md-4 form-control-label'>Diametro de la brida</label>
                        <div class='col-md-8'>
                            <input type='text' :disabled="true" data-vv-name="diametro brida" v-validate="'required'" class='form-control' v-model='brida.diametro_brida'>
                            <span class="text-danger">{{errors.first("diametro brida")}}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class='col-md-4 form-control-label'>Longitud del esparrago</label>
                        <div class='col-md-8'>
                            <input type='text' :disabled="true" name="longitud" v-validate="'required'" class='form-control' v-model='brida.longitud_esparrago'>
                            <span class="text-danger">{{errors.first("longitud")}}</span>
                        </div>
                    </div>

                    <div class='form-group row'>
                        <label class='col-md-4 form-control-label'>Diametro del tornillo</label>
                        <div class='col-md-8'>
                            <input type='text' :disabled="true" v-validate="'required'" data-vv-name="diametro tornillo" class='form-control' v-model='brida.diametro_tornillo' />
                            <span class="text-danger">{{errors.first("diametro tornillo")}}</span>
                        </div>
                    </div>

                    <div class=' form-group row'>
                        <label class='col-md-4 form-control-label'>Medida de la tuerca</label>
                        <div class='col-md-8'>
                            <input type='text' :disabled="true" v-validate="'required'" name="tuerca" class='form-control' v-model='brida.medida_tuerca'>
                            <span class="text-danger">{{errors.first("tuerca")}}</span>
                        </div>
                    </div>

                    <div class='form-group row'>
                        <label class='col-md-4 form-control-label'>Torque Lb/Pie al 100%</label>
                        <div class='col-md-8'>
                            <input type='text' name="torque" v-validate="'required'" class='form-control' v-model='partidas_modal.torque'>
                            <span class="text-danger">{{errors.first("torque")}}</span>
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalPartidas()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_partidas== 1' class='btn btn-secondary' @click='GuardarPartidaTorque(true)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_partidas==2' class='btn btn-secondary' @click='GuardarPartidaTorque(false)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</div> <!-- Main -->
</template>

<style>
.check_ver {
    visibility: visible;
    margin-right: 0.5rem;
}

.check_ocultar {
    visibility: hidden;
    margin-left: 0.5rem;
}
</style>

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Imagenes = r => require.ensure([], () => r(require('./ImagenesTorque.vue')), 'calidad');
export default
{
    components:
    {
        "card-imagenes": Imagenes,
    },
    data()
    {
        return {
            url: "calidad/torque",
            tipoCard: 1,
            isTorqueLoading: false,
            folio: "",
            // Tabla 
            ver_modal_partidas: false,
            columns_torque: ['id', 'sistema_nombre', 'fecha', 'folio'],
            list_torque: [],
            listProyectos: [],
            list_sistemas: [],
            list_empleados: [],
            isEmpleadosLoading: false,
            isSistemasLoading: [],
            proyecto:
            {},
            sistema:
            {},
            equipo1:
            {},
            equipo2:
            {},
            reporte_id: 0,
            folio: "",
            listTorquimetros: [],
            listHerramientras: [],
            isGuadarLoading: false,
            options_torque:
            {
                headings:
                {
                    id: 'Acciones',
                    folio: 'Folio',
                    fecha: 'Fecha',
                    servicio_sistema: 'Elemento',
                    plano: 'Plano',
                    doc_ref: 'Documento de referencia',
                    aceptado: 'Estado'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            titulo_modal_torque: '',
            tipoAccion_modal_torque: 0,
            torque_modal:
            {
                aceptado: false,
            },
            brida:
            {},
            listBridas: [],
            isEmpleadosLoading: false,
            /** PARTIDAS **/
            ver_modal_partidas: false,
            columns_partidas: ['id', 'item', 'ubicacion', 'diametro_brida', 'longitud_esparrago', 'diametro_tornillo', 'medida_tuerca', 'torque'],
            list_partidas: [],
            options_partidas:
            {
                headings:
                {
                    id: 'Acciones',
                    item: 'Item',
                    ubicacion: 'Ubicación',
                    diametro: 'Diametro',
                    longitud: 'Longitud',
                    diametro: 'Diametro',
                    medida_tuerca: 'Tuerca',
                    torque: 'Torque'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            titulo_modal_partidas: '',
            tipoAccion_modal_partidas: 0,
            partidas_modal:
            {},

        } // return
    }, //data
    computed:
    {},
    methods:
    {

        /**Obtiene los proyectos de calididad */
        CargarProyectos()
        {
            axios.get("/calidad/proyectos/proyectos").then(res =>
            {
                this.listProyectos = res.data.proyectos;
            });
        },

        /**
         * Obtiene todos los reportes de torque del proyecto seleccionado
         */
        ObtenerReportes()
        {
            this.isTorqueLoading = true;
            axios.get(this.url + "/obtener/" + this.proyecto.id).then(res =>
            {
                this.isTorqueLoading = false;
                if (res.data.status)
                {
                    this.list_torque = res.data.torque;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },

        /** 
         * Obtiene los sistemas-servicios del proyecto seleccionado
         */
        Obtenersistemas()
        {
            this.isSistemasLoading = true;
            axios.get("calidad/torque/obtenersistemas/" + this.proyecto.id).then(res =>
            {
                this.isSistemasLoading = false;
                if (res.data.status)
                {
                    this.list_sistemas = res.data.sistemas;
                }
            });
        },

        AbrirModalTorque(nuevo, model = [])
        {
            this.ObtenerEmpleados();
            if (this.proyecto == null) return;
            if (this.proyecto.id == null) return;
            // Mostrar proyecto
            this.torque_modal.nombre_proyecto = this.proyecto.nombre_proyecto;
            // Cargar sistemas
            this.Obtenersistemas();
            // Obtener los equipos de calibración
            this.ObtenerEquipos();
            this.tipoCard = 2;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_torque = 'Registrar reporte de torque';
                this.tipoAccion_modal_torque = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_torque = 'Actualizar reporte de torque';
                this.tipoAccion_modal_torque = 2;
                this.torque_modal.id = model.id;
                this.torque_modal.ubicacion = model.ubicacion;
                this.torque_modal.folio = model.folio;
                this.torque_modal.fecha = model.fecha;
                this.torque_modal.cliente = model.cliente;
                this.torque_modal.aux_sistema = model.sistema_nombre;
                this.sistema = {
                    id: model.sistema_id,
                    tag: model.tag,
                    nombre: model.sistema_nombre
                };
                this.torque_modal.diametro = model.diametro;
                this.torque_modal.material = model.material;
                this.torque_modal.referencia = model.referencia;
                this.torque_modal.secuencia1 = model.secuencia_1;
                this.torque_modal.secuencia2 = model.secuencia_2;
                this.torque_modal.procedimiento = model.procedimiento;
                this.equipo1 = {
                    id: model.ec1_id,
                    descripcion: model.ec1_desc,
                    numero_serie: model.ec1_numero_serie,
                    modelo: model.ec1_modelo,
                };
                this.equipo2 = {
                    id: model.ec2_id,
                    descripcion: model.ec2_desc,
                    numero_serie: model.ec2_numero_serie,
                    modelo: model.ec2_modelo,
                };
                this.torque_modal.inspecciona1 = {
                    id: model.emp_inspecciona_id,
                    nombre: model.emp_inspecciona_nom
                };
                this.torque_modal.inspecciona2 = model.inspecciona2;
                this.torque_modal.aprueba = model.aprueba;
                this.aprueba = model.aprueba;

            } // Fin if
        },

        /**
         * Guardar el reporte de torque
         * @param bool nuevo true: Nuevo registro. false: Actualización
         */
        GuardarTorque(nuevo)
        {
            this.$validator.validate().then(isValid =>
            {
                if (!isValid) return;
                this.isGuadarLoading = true;
                let data = new FormData();
                if (!nuevo)
                    data.append("id", this.torque_modal.id);
                data.append("proyecto_id", this.proyecto.id);
                data.append("ubicacion", this.torque_modal.ubicacion);
                data.append("fecha", this.torque_modal.fecha);
                data.append("folio", this.torque_modal.folio);
                data.append("sistema_id", this.sistema.id);
                data.append("referencia", this.torque_modal.referencia);
                data.append("diametro", this.torque_modal.diametro);
                data.append("material", this.torque_modal.material);
                data.append("procedimiento", this.torque_modal.procedimiento);
                data.append("equipo1_id", this.equipo1.id);
                data.append("secuencia_1", this.torque_modal.secuencia1);
                data.append("equipo2_id", this.equipo2.id);
                data.append("secuencia_2", this.torque_modal.secuencia2);
                data.append("inspecciona1_id", this.torque_modal.inspecciona1.id);
                data.append("inspecciona2", this.torque_modal.inspecciona2);
                data.append("aprueba", this.torque_modal.aprueba);

                axios.post(this.url + "/guardar", data).then(res =>
                {
                    this.isGuadarLoading = false;
                    if (res.data.status)
                    {
                        toastr.success("Guardado correctamente");
                        this.CerrarModalTorque();
                        this.ObtenerReportes();
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                })
            });
        },

        CerrarModalTorque()
        {
            this.tipoCard = 1;
            Utilerias.resetObject(this.torque_modal);
        },

        // Obtiene todos los empleados
        ObtenerEmpleados()
        {
            this.isEmpleadosLoading = true;
            axios.get("calidad/inspeccionescliente/empledos").then(res =>
            {
                this.isEmpleadosLoading = false;
                if (res.data.status)
                {
                    this.list_empleados = res.data.empleados;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },

        /**
         * Obtiene todos los equipos de calibración
         */
        ObtenerEquipos()
        {
            this.isEquiposLoading = true;
            axios.get(this.url + "/obtenerequiposcalib").then(res =>
            {
                if (res.data.status)
                {
                    this.listTorquimetros = res.data.equipos.torquimetros;
                    this.listHerramientras = res.data.equipos.herramientas;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
            this.isEquiposLoading = false;
        },

        /**
         * Abre el card de las partidas del reporte seleccionado
         */
        VerPartidas(reporte)
        {
            this.reporte_id = reporte.id;
            this.folio = reporte.folio;
            this.tipoCard = 3;
            axios.get(this.url + "/partidas/obtener/" + reporte.id).then(res =>
            {
                if (res.data.status)
                {
                    // Ver partidas
                    this.list_partidas = res.data.partidas;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },

        /**
         * Regresa al card principal
         */
        Regresar()
        {
            this.tipoCard = 1;
        },

        /**
         * Descarga el reporte de torque
         * */
        Reporte(reporte_id)
        {
            // De servicio_sistema
            window.open(this.url + "/reporte/" + reporte_id, '_blank');
        },

        /**Partidas **/
        AbrirModalPartidas(nuevo, model = [])
        {
            this.ObtenerBridas();
            this.ver_modal_partidas = true;
            if (nuevo)
            {
                this.tipoAccion_modal_partidas = 1;
            }
            else
            {
                this.tipoAccion_modal_partidas = 2;
                this.partidas_modal.id = model.id;
                this.partidas_modal.item = model.item;
                this.partidas_modal.ubicacion = model.ubicacion;
                this.partidas_modal.diametro_brida = model.diametro_brida;
                this.partidas_modal.diametro_tornillo = model.diametro_tornillo;
                this.partidas_modal.longitud = model.longitud_esparrago;
                this.partidas_modal.tuerca = model.medida_tuerca;
                this.partidas_modal.torque = model.torque;
                this.brida = {
                    id: model.cb_id,
                    diametro_brida: model.diametro_brida,
                    longitud_esparrago: model.longitud_esparrago,
                    diametro_tornillo: model.diametro_tornillo,
                    medida_tuerca: model.medida_tuerca,
                    nombre: model.nombre_brida
                };
            }
        },

        /**
         * Obiene todas las bridas registradas
         */
        ObtenerBridas()
        {
            axios.get(this.url + "/obtenerbridas").then(res =>
            {
                if (res.data.status)
                {
                    this.listBridas = res.data.bridas;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },
        /**
         * Guarda la partida de torque 
         */
        GuardarPartidaTorque(nuevo)
        {
            this.$validator.validate().then(isValid =>
            {
                if (!isValid) return;
                let data = new FormData();
                if (!nuevo)
                    data.append("id", this.partidas_modal.id);
                data.append("item", this.partidas_modal.item);
                data.append("ubicacion", this.partidas_modal.ubicacion);
                data.append("brida_id", this.brida.id);
                data.append("torque", this.partidas_modal.torque);
                data.append("torque_id", this.reporte_id);

                this.isPartidasLoading = true;
                axios.post(this.url + "/partidas/guardar", data).then(res =>
                {
                    this.isPartidasLoading = false;
                    if (res.data.status)
                    {
                        toastr.success("Guardado correctamente");
                        this.CerrarModalPartidas();
                        this.VerPartidas(
                        {
                            id: this.reporte_id,
                            folio: this.folio
                        });
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                })
            });
        },
        CerrarModalPartidas()
        {
            this.ver_modal_partidas = false;
            this.partidas_modal = {};
        },
        AbrirCardImagenes(reporte)
        {
            this.folio = reporte.folio;
            this.reporte_id = reporte.id;
            this.tipoCard = 4;
        },

    }, // Fin metodos
    mounted()
    {
        this.CargarProyectos();
    }
}
</script>
