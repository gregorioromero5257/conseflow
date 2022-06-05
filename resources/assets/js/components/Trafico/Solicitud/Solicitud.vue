<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Solicitud de vehiculos - {{empresa==1?"CONSERFLOW":"CONSTRUCTORA"}}

            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle float-sm-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Empresa
                </button>
                <div class="dropdown-menu" v-model="empresa">
                    <button class="dropdown-item" type="button" @click="empresa = 1; ObtenerSolicitudes();">Conserflow</button>
                    <button class="dropdown-item" type="button" @click="empresa = 2; ObtenerSolicitudes();">CSCT</button>
                </div>
            </div>

            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalSolicitud(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Solicitud-->
                <div class=''>
                    <v-client-table :columns='columns_solicitud' :data='list_solicitud' :options='options_solicitud' ref='tbl_solicitud'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalSolicitud(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                        <button type='button' @click='Descargar(props.row.id)' class='dropdown-item'>
                                            <i class='fas fa-file-pdf'></i>&nbsp;Descargar
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Modal Solicitud -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_solicitud}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_solicitud'></h4>
                    <button type='button' class='close' @click='CerrarModalSolicitud()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <vue-element-loading :active="isGuardarLoading" />
                    <div class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Fecha</label>
                            <div class='col-md-3'>
                                <input type='date' data-vv-name="Fecha Solicitud" v-validate="'required'" class='form-control' v-model='solicitud_modal.fecha_solicitud'>
                                <span class="text-danger">{{errors.first("Fecha Solicitud")}}</span>
                            </div>
                            <label class='col-md-1 form-control-label'>Folio</label>
                            <div class='col-md-3'>
                                <input type='text' data-vv-name="Folio" v-validate="'required'" class='form-control' v-model='solicitud_modal.folio'>
                                <span class="text-danger">{{errors.first("Folio")}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Tipo Unidad</label>
                            <div class='col-md-4'>
                                <v-select @input="ObtenerUnidades" label="clase_tipo" v-validate="'required'" data-vv-name="Tipo unidad" v-model="solicitud_modal.tipo_unidad" :options="list_tipos"></v-select>
                                <span class="text-danger">{{errors.first("Tipo unidad")}}</span>
                            </div>
                            <label class='col-md-1 form-control-label'>Unidad</label>
                            <div class='col-md-5'>
                                <v-select label="nombre" v-validate="'required'" data-vv-name="Unidad" v-model="solicitud_modal.unidad" :options="list_unidades"></v-select>
                                <span class="text-danger">{{errors.first("Tipo unidad")}}</span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Lapso de tiempo</label>
                                <input type="text" v-validate="'required'" v-model="solicitud_modal.tiempo" data-vv-name="Lapso" class="form-control">
                                <span class="text-danger">{{ errors.first('Lapso') }}</span>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Fecha de inicio</label>
                                <input v-validate="'required'" data-vv-name="Fecha inicio" type='date' class='form-control' v-model='solicitud_modal.fecha_inicio'>
                                <span class="text-danger">{{errors.first("Fecha inicio")}}</span>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Fecha de fin</label>
                                <input v-validate="'required'" data-vv-name="Fecha fin" type='date' class='form-control' v-model='solicitud_modal.fecha_fin'>
                                <span class="text-danger">{{errors.first("Fecha fin")}}</span>
                            </div>
                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label class=''>Ciudad</label>
                                <input v-validate="'required'" data-vv-name="Ciudad" type='text' class='form-control' v-model='solicitud_modal.ciudad'>
                                <span class="text-danger">{{errors.first("Ciudad")}}</span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Estado</label>
                                <input v-validate="'required'" data-vv-name="Estado" type='text' class='form-control' v-model='solicitud_modal.estado'>
                                <span class="text-danger">{{errors.first("Estado")}}</span>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Población</label>
                                <input v-validate="'required'" data-vv-name="Población" type='text' class='form-control' v-model='solicitud_modal.poblacion'>
                                <span class="text-danger">{{errors.first("Población")}}</span>
                            </div>
                        </div>

                        <div class='form-row'>
                            <div class='col'>
                                <label class='col-md-3 form-control-label'>Proyecto</label>
                                <v-select label="nombre_corto" v-validate="'required'" data-vv-name="Proyecto" v-model="solicitud_modal.proyecto" :options="list_proyectos"></v-select>
                                <span class="text-danger">{{errors.first("Proyecto")}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label class=''>Necesidad de la unidad</label>
                                <textarea v-validate="'required'" data-vv-name="Necesidad" class="form-control" cols="10" rows="6" v-model='solicitud_modal.necesidad'></textarea>
                                <span class="text-danger">{{errors.first("Necesidad")}}</span>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <p class="h5">Responsables</p>
                            <div class="form-row">
                                <label class="col-md-2">Empleado</label>
                                <v-select class="col-md-6" label="nombre" v-model="responsable" :options="list_empleados"></v-select>
                                <button class="ml-2 btn btn-sm btn-dark mr-1" @click="AgregarResponsable">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <br>
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label><b>#</b></label>
                                </div>

                                <div class="form-group col-md-3">
                                    <label><b>Responsable</b></label>
                                </div>
                            </div>
                            <li v-for="(responsable, index) in lista_responsables" class="list-group-item">
                                <div class="form-row">

                                    <div class="form-group col-md-1">
                                        <label>{{index+1}}</label>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label>{{responsable.nombre}}</label>
                                    </div>
                                    <div class="form-group col-md-2" v-if="responsable.temp">
                                        <i class="fas fa-trash" @click="BorrarTemp(index)"></i>
                                    </div>
                                </div>
                            </li>
                        </div>

                        <div class='form-group row'>
                            <div class="col">
                                <label class='col form-control-label'>Empleado Solicita</label>
                                <div class='col'>
                                    <v-select label="nombre" v-validate="'required'" data-vv-name="Solicita" v-model="solicitud_modal.solicita" :options="list_empleados"></v-select>
                                    <span class="text-danger">{{errors.first("Solicita")}}</span>
                                </div>
                            </div>
                            <div class="col">
                                <label class='col form-control-label'>Empleado Autoriza</label>
                                <div class='col'>
                                    <v-select label="nombre" v-validate="'required'" data-vv-name="Autoriza" v-model="solicitud_modal.autoriza" :options="list_empleados"></v-select>
                                    <span class="text-danger">{{errors.first("Autoriza")}}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalSolicitud()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_solicitud== 1' class='btn btn-secondary' @click='GuardarSolicitud(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_solicitud==2' class='btn btn-secondary' @click='GuardarSolicitud(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div> <!-- Main -->
</template>

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            url: "/vehiculos/solicitud",
            empresa: 1,
            // Tabla 
            ver_modal_solicitud: 0,
            columns_solicitud: ['id', "proyecto", 'fecha_solicitud', 'folio', 'clase_tipo', 'tiempo'],
            list_solicitud: [],
            isGuardarLoading: false,
            options_solicitud:
            {
                headings:
                {
                    id: 'Acciones',
                    fecha_solicitud: 'Fecha',
                    folio: 'Folio',
                    clase_tipo: 'Tipo Unidad',
                    tiempo: 'Lapso de tiempo',
                    fecha_inicio: 'Fecha de inicio',
                    fecha_fin: 'Fecha de fin',
                    ciudad: 'Ciudad',
                    estado: 'Estado',
                    poblacion: 'Población',
                    necesidad: 'Necesidad',
                    solicita_id: 'Empleado Solicita',
                    autoriza_id: 'Empleado Autoriza'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            }, //options 
            list_unidades: [],
            lista_responsables: [],
            responsable:
            {},
            list_empleados: [],
            list_tipos: [],
            list_proyectos: [],
            // Modal
            titulo_modal_solicitud: '',
            tipoAccion_modal_solicitud: 0,
            solicitud_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        /**
         * Obtiene las solicitudes de vehiculos
         */
        ObtenerSolicitudes()
        {
            this.isSolicitudesLading = true;
            axios.get(this.url + "/obtener/" + this.empresa).then(res =>
            {
                this.isSolicitudesLading = false;
                if (res.data.status)
                {
                    this.list_solicitud = res.data.solicitudes;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },
        AbrirModalSolicitud(nuevo, model = [])
        {
            this.ObtenerEmpleados();
            this.ObtenerProyectos();
            this.ObtenerTiposUnidad();
            this.ver_modal_solicitud = true;
            if (nuevo)
            {
                this.solicitud_modal.proyecto = {
                    id: -1,
                    nombre_corto: "Sin proyecto"
                };
                // Crear nuevo
                this.titulo_modal_solicitud = 'Registrar Solicitud';
                this.tipoAccion_modal_solicitud = 1;
            }
            else
            {
                console.error(model);
                // Obtener Participantes
                this.ObtenerResponsables(model.id);
                this.solicitud_modal.unidad = {
                    id: model.unidad_id,
                    nombre: model.nombre_unidad,
                    placas: model.placas,
                    modelo: model.modelo
                };
                this.solicitud_modal.id = model.id;
                // Actualizar
                this.titulo_modal_solicitud = 'Actualizar Solicitud';
                this.tipoAccion_modal_solicitud = 2;

                this.solicitud_modal.fecha_solicitud = model.fecha_solicitud;
                this.solicitud_modal.folio = model.folio;
                this.solicitud_modal.tipo_unidad = {
                    id: model.tipo_unidad_id,
                    clase_tipo: model.clase_tipo
                };
                this.solicitud_modal.tiempo = model.tiempo;
                this.solicitud_modal.fecha_inicio = model.fecha_inicio;
                this.solicitud_modal.fecha_fin = model.fecha_fin;
                this.solicitud_modal.ciudad = model.ciudad;
                this.solicitud_modal.estado = model.estado;
                this.solicitud_modal.poblacion = model.poblacion;
                this.solicitud_modal.necesidad = model.necesidad;
                this.solicitud_modal.solicita = {
                    id: model.solicita_id,
                    nombre: model.solicita,
                };
                this.solicitud_modal.autoriza = {
                    id: model.autoriza_id,
                    nombre: model.autoriza
                };
                this.solicitud_modal.proyecto = {
                    id: model.proyecto_id,
                    nombre_corto: model.proyecto
                };
            } // Fin if
        },

        /**
         * Obtiene los responsables de la solicitud
         */
        ObtenerResponsables(id)
        {
            axios.get(this.url + "/obtenerresponsables/" + id).then(res =>
            {
                if (res.data.status)
                {
                    this.lista_responsables = res.data.responsables;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },
        /**
         * Obtiene todos los empleados
         */
        ObtenerEmpleados()
        {
            axios.get("/calidad/inspeccionescliente/empledos").then(res =>
            {
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
         * Obtiene todos los proyectos
         */
        ObtenerProyectos()
        {
            axios.get("/calidad/inspecciones/obtenerproyectos").then(res =>
            {
                if (res.data.status)
                {
                    this.list_proyectos = res.data.proyectos;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        /**
         * Obtiene los tipos de unidad registrados
         */
        ObtenerTiposUnidad()
        {
            axios.get("/catalogoclasetipo").then(res =>
            {
                this.list_tipos = res.data;
            });
        },

        GuardarSolicitud(nuevo)
        {
            // Validar personas responsables
            if (this.lista_responsables.length == 0)
            {
                toastr.warning("Seleccione al menos un responsable");
                return;
            }
            let temporales = this.lista_responsables.filter(a => a.temp);
            let ids = [];
            temporales.forEach(r =>
            {
                ids.push(r.id);
            });
            // Validar datos
            this.$validator.validate().then(isValid =>
            {
                if (!isValid) return;
                let data = new FormData();
                if (!nuevo)
                    data.append("id", this.solicitud_modal.id);
                data.append("fecha_solicitud", this.solicitud_modal.fecha_solicitud);
                data.append("folio", this.solicitud_modal.folio);
                data.append("tipo_unidad_id", this.solicitud_modal.tipo_unidad.id);
                data.append("unidad_id", this.solicitud_modal.unidad.id);
                data.append("tiempo", this.solicitud_modal.tiempo);
                data.append("fecha_inicio", this.solicitud_modal.fecha_inicio);
                data.append("fecha_fin", this.solicitud_modal.fecha_fin);
                data.append("ciudad", this.solicitud_modal.ciudad);
                data.append("estado", this.solicitud_modal.estado);
                data.append("poblacion", this.solicitud_modal.poblacion);
                data.append("necesidad", this.solicitud_modal.necesidad);
                data.append("solicita_id", this.solicitud_modal.solicita.id);
                data.append("autoriza_id", this.solicitud_modal.autoriza.id);
                data.append("responsables", ids);
                data.append("empresa", this.empresa);
                data.append("proyecto_id", this.solicitud_modal.proyecto.id);

                this.isGuardarLoading = true;
                axios.post(this.url + "/guardar", data).then(res =>
                {
                    if (res.data.status)
                    {
                        this.isGuardarLoading = false;
                        toastr.success("Guardado correctamente");
                        this.CerrarModalSolicitud();
                        this.ObtenerSolicitudes();
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                })
            })
        },

        /**
         * Anade un participante a la lista
         */
        AgregarResponsable()
        {
            if (this.responsable == null) return;
            if (this.responsable.id == null) return;
            this.lista_responsables.push(
            {
                id: this.responsable.id,
                nombre: this.responsable.nombre,
                temp: true
            });
            this.responsable = {};
        },

        /**
         * Elimina el riesgo actual de la lista de temporales
         */
        BorrarTemp(index)
        {
            this.lista_responsables.splice(index, 1);
        },

        CerrarModalSolicitud()
        {
            this.ver_modal_solicitud = false;
            this.solicitud_modal = {
                solicita:
                {},
                autoriza:
                {}
            };
            this.lista_responsables = [];
        },

        /**
         * Obtiene las unidades del tipo seleccionado
         */
        ObtenerUnidades()
        {
            axios.get(this.url + "/portipo/" + this.empresa + "&" + this.solicitud_modal.tipo_unidad.id).then(res =>
            {
                if (res.data.status)
                {
                    this.list_unidades = res.data.unidades;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        /**
         * Descarga el formato de la solicitud
         */
        Descargar(id)
        {
            window.open(this.url + "/descargar/" + id, '_blank');
        }
    }, // Fin metodos
    mounted()
    {
        this.ObtenerSolicitudes()
    }
}
</script>
