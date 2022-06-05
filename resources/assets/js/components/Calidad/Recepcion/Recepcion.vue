<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card' v-show="tipoCard==1">
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Recepcion
            <button v-show="false" type='button' class='btn btn-dark float-sm-right' @click='AbrirModalRecepcion(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <div class='form-group row'>
                    <label class='col-md-1 form-control-label'>Proyecto</label>
                    <div class='col-md-6'>
                        <v-select label="nombre_corto" :options="list_proyectos" v-model="proyecto"></v-select>
                    </div>
                    <button class="btn btn-dark btn-sm" @click="ObtenerRecepcions">
                        <i class="ml-1 fas fa-search"></i>
                    </button>
                    <button v-show="false" class="btn btn-dark btn-sm" @click="Generar">
                        <i class="fas fa-meh-rolling-eyes"></i>
                    </button>
                </div>
                <!-- Tabla de Recepcion-->
                <div class=''>

                    <v-client-table :columns='columns_recepcion' :data='list_recepcion' :options='options_recepcion' ref='tbl_recepcion'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button v-show="false" type='button' @click='AbrirModalRecepcion(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                        <button type='button' @click='VerPartidas(props.row)' class='dropdown-item'>
                                            <i class='fas fa-eye'></i>&nbsp;Ver partidas
                                        </button>
                                        <button type='button' @click='Descargar(props.row.id)' class='dropdown-item'>
                                            <i class='fas fa-file-pdf'></i>&nbsp;Descargar
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template slot='metarial_aceptado'>
                            <button class="btn btn-success"> Aprobado </button>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <div class='card' v-show="tipoCard==2">
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Recepción {{folio}}
            <button type='button' class='btn btn-dark float-sm-right' @click='Regresar()'>
                <i class='fas fa-arrow-left'>&nbsp;</i>Regresar
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <v-client-table :columns='columns_partidas' :data='list_partidas' :options='options_partidas'>
                    <template slot='id' slot-scope='props'>
                        <div class='btn-group' role='group'>
                            <button type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                            </button>
                            <div class='dropdown-menu'>
                            </div>
                        </div>
                    </template>
                </v-client-table>
            </div> <!-- card-->
        </div>
    </div>

    <!-- Modal Recepcion -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_recepcion}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_recepcion'></h4>
                    <button type='button' class='close' @click='CerrarModalRecepcion()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class='form-horizontal'>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Fecha</label>
                            <div class='col-md-9'>
                                <input type='date' v-validate='"required"' class='form-control' v-model='recepcion_modal.fecha' data-vv-name='Fecha'>
                                <span class="text-danger">{{errors.first('Fecha')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Orden de compra</label>
                            <div class='col-md-9'>
                                <v-select :options="list_ocs" v-model="recepcion_modal.oc" label="folio"></v-select>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Folio</label>
                            <div class='col-md-9'>
                                <input type='text' v-validate='"required"' class='form-control' v-model='recepcion_modal.folio' data-vv-name='Folio'>
                                <span class="text-danger">{{errors.first('Folio')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Proveedor</label>
                            <div class='col-md-9'>
                                <input type='text' disabled v-validate='"required"' class='form-control' v-model='recepcion_modal.oc.proveedor' data-vv-name='Proveedor'>
                                <span class="text-danger">{{errors.first('Proveedor')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Hora de entrega</label>
                            <div class='col-md-9'>
                                <input type='time' v-validate='"required"' class='form-control' v-model='recepcion_modal.hora_entrega' data-vv-name='Hora de entrega'>
                                <span class="text-danger">{{errors.first('Hora de entrega')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Unidad</label>
                            <div class='col-md-9'>
                                <input type='text' v-validate='"required"' class='form-control' v-model='recepcion_modal.unidad' data-vv-name='Unidad'>
                                <span class="text-danger">{{errors.first('Unidad')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Placas</label>
                            <div class='col-md-9'>
                                <input type='text' v-validate='"required"' class='form-control' v-model='recepcion_modal.placas' data-vv-name='Placas'>
                                <span class="text-danger">{{errors.first('Placas')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Almacen</label>
                            <div class='col-md-9'>
                                <input type='text' v-validate='"required"' class='form-control' v-model='recepcion_modal.no_almacen' data-vv-name='Almacen'>
                                <span class="text-danger">{{errors.first('Almacen')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Observaciones</label>
                            <div class='col-md-9'>
                                <input type='text' v-validate='"required"' class='form-control' v-model='recepcion_modal.observaciones' data-vv-name='Observaciones'>
                                <span class="text-danger">{{errors.first('Observaciones')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Persona que entrega</label>
                            <div class='col-md-9'>
                                <input type='text' v-validate='"required"' class='form-control' v-model='recepcion_modal.persona_entrega' data-vv-name='Persona que entrega'>
                                <span class="text-danger">{{errors.first('Persona que entrega')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Empleado recibe</label>
                            <div class='col-md-9'>
                                <v-select data-vv-name='Empleado recibe' v-validate='"required"' :options="list_empleados" v-model="recepcion_modal.empleado_recibe"></v-select>
                                <span class="text-danger">{{errors.first('Empleado recibe')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Cumple con lo solicitado solicitado</label>
                            <div class='col-md-9'>
                                <input type='checkbox' v-validate='"required"' class='form-control' v-model='recepcion_modal.cumple_solicitado' data-vv-name='Cumple solicitado'>
                                <span class="text-danger">{{errors.first('Cumple solicitado')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Daños en el material</label>
                            <div class='col-md-9'>
                                <input type='checkbox' v-validate='"required"' class='form-control' v-model='recepcion_modal.danios_material' data-vv-name='Daños en el material'>
                                <span class="text-danger">{{errors.first('Daños en el material')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Condiciones aceptables</label>
                            <div class='col-md-9'>
                                <input type='checkbox' v-validate='"required"' class='form-control' v-model='recepcion_modal.condiciones_aceptables' data-vv-name='Condiciones aceptables'>
                                <span class="text-danger">{{errors.first('Condiciones aceptables')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Material aceptado</label>
                            <div class='col-md-9'>
                                <input type='checkbox' v-validate='"required"' class='form-control' v-model='recepcion_modal.meterial_aceptado' data-vv-name='Material aceptado'>
                                <span class="text-danger">{{errors.first('Material aceptado')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Motivo</label>
                            <div class='col-md-9'>
                                <input type='text' v-validate='"required"' class='form-control' v-model='recepcion_modal.motivo' data-vv-name='Motivo'>
                                <span class="text-danger">{{errors.first('Motivo')}}</span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalRecepcion()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_recepcion== 1' class='btn btn-secondary' @click='GuardarRecepcion(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_recepcion==2' class='btn btn-secondary' @click='GuardarRecepcion(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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
            url: "calidad/recepciones",
            tipoCard: 1,
            folio: "",
            // Tabla 
            ver_modal_recepcion: 0,
            columns_recepcion: ['id', 'folio', "fecha", 'empleado_recibe', "oc", "metarial_aceptado"],
            columns_partidas: ["id", "descripcion", "marca"],
            list_recepcion: [],
            list_partidas: [],
            list_proyectos: [],
            proyecto:
            {},
            options_recepcion:
            {
                headings:
                {
                    id: "Acciones",
                    fecha: 'Fecha',
                    folio: 'Folio',
                    proveedor_id: 'Proveedor',
                    hora_entrega: 'Hora de entrega',
                    unidad: 'Unidad',
                    placas: 'Placas',
                    no_almacen: 'No_almacen',
                    observaciones: 'Observaciones',
                    persona_entrega: 'Persona que entrega',
                    empleado_recibe: 'Empleado recibe',
                    cumple_solicitado: 'Cumple solicitado',
                    danios_material: 'Daños en el material',
                    condiciones_aceptables: 'Condiciones aceptables',
                    meterial_aceptado: 'Estado recepción',
                    motivo: 'Motivo'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['fecha', 'folio', 'proveedor_id', 'hora_entrega', 'unidad', 'placas', 'no_almacen', 'observaciones', 'persona_entrega', 'empleado_recibe', 'cumple_solicitado', 'danios_material', 'condiciones_aceptables', 'meterial_aceptado', 'motivo'],
                filterable: ['fecha', 'folio', 'proveedor_id', 'hora_entrega', 'unidad', 'placas', 'no_almacen', 'observaciones', 'persona_entrega', 'empleado_recibe', 'cumple_solicitado', 'danios_material', 'condiciones_aceptables', 'meterial_aceptado', 'motivo'],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            options_partidas:
            {
                headings:
                {}, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            list_ocs: [],
            list_empleados: [],
            titulo_modal_recepcion: '',
            tipoAccion_modal_recepcion: 0,
            recepcion_modal:
            {
                proveedor:
                {},
                oc:
                {},
            },
        } // return
    }, //data
    computed:
    {},
    methods:
    {

        /**
         * Obtiene las recepcions del proyecto seleccionado
         */
        ObtenerRecepcions()
        {
            if (this.proyecto == null) return;
            if (this.proyecto.id == null) return;
            axios.get(this.url + "/obtener/" + this.proyecto.id).then(res =>
            {
                if (res.data.status)
                {
                    this.list_recepcion = res.data.recepciones;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }

            })
        },
        /**
         * Obtener proyectos
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
         * Obtenre las ocs del proyecto actual
         */
        ObtenerOcs()
        {
            axios.get(this.url + "/obtenerocs/" + this.proyecto.id).then(res =>
            {
                if (res.data.status)
                {
                    this.list_ocs = res.data.ocs;
                }
                else
                    toastr.error(res.data.mensaje);
            })
        },
        AbrirModalRecepcion(nuevo, model = [])
        {
            if (this.proyecto == null) return;
            if (this.proyecto.id == null) return;
            this.ObtenerOcs();
            this.ver_modal_recepcion = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_recepcion = 'Registrar Recepcion';
                this.tipoAccion_modal_recepcion = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_recepcion = 'Actualizar Recepcion';
                this.tipoAccion_modal_recepcion = 2;
            } // Fin if
        },

        /**
         * Mostrar las partida de la recepción ingresada
         */
        VerPartidas(row)
        {
            this.folio = row.folio;
            axios.get(this.url + "/partidas/" + row.id).then(res =>
            {
                if (res.data.status)
                {
                    this.tipoCard = 2;
                    this.list_partidas = res.data.partidas;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },
        Regresar()
        {
            this.tipoCard = 1;
        },

        /**
         * Guardar
         */
        GuardarRecepcion(nuevo)
        {
            this.$validator.validate().then(isValid =>
            {
                if (!isValid) return;
                let data = new FormData();
                if (!nuevo) data.append("id", this.recepcion_modal.id);
                data.append("folio", this.recepcion_modal.folio);
                data.append("fecha", this.recepcion_modal.fecha);
                data.append("hora", this.recepcion_modal.hora_entrega);
                dat.append("oc_id", this.recepcion_modal.oc.id);
                data.append("coincide_solicitado", this.recepcion_modal.coincide_solicitado);
                data.append("danios_visivles", this.recepcion_modal.danios_visivles);
                data.append("condiciones_aceptables", this.recepcion_modal.condiciones_aceptables);
                data.append("metarial_aceptado", this.recepcion_modal.metarial_aceptado);
                data.append("motivo", this.recepcion_modal.motivo);
                data.append("unidad", this.recepcion_modal.unidad);
                data.append("placas", this.recepcion_modal.placas);
                data.append("no_almacen", this.recepcion_modal.no_almacen);
                data.append("observaciones", this.recepcion_modal.observaciones);
                data.append("persona_entrega", this.recepcion_modal.persona_entrega);
                data.append("empleado_recibe", this.recepcion_modal.empleado_recibe);
                data.append("placas", this.recepcion_modal.placas);
                axios.post(this.url + "/guardar", data).then(res =>
                {
                    if (res.data.status)
                    {
                        toastr.success("Registrado correctamente");
                        this.CerrarModalRecepcion();
                        this.ObtenerRecepcions();
                    }
                    else
                    {
                        toastr.error(res.dat.mensaje);
                    }
                })
            })
        },
        CerrarModalRecepcion()
        {
            this.ver_modal_recepcion = false;
            this.recepcion_modal = {
                oc:
                {}
            };
        },
        Generar()
        {
            if (this.proyecto == null) return;
            if (this.proyecto.id == null)
            {
                toastr.warning("Selecciona el pto proyecto -.-");
                return;
            }

            Swal.fire(
            {
                title: 'Generar recepciones',
                text: "Vamos a hacer tu trabajo, papu/mamu",
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'Ayúdame,Plox :(',
                cancellButtonText: 'Nel,prro'
            }).then((result) =>
            {
                console.error(result);
                if (result.value == null) return;
                if (result.value == "") return;
                if (!(result.value === "#Yolo123")) return; // Contra

                axios.post(this.url + "/generar",
                {
                    proyecto_id: this.proyecto.id
                }).then(res =>
                {
                    if (res.data.status)
                    {
                        toastr.success("Ya terminé tu trabajo ;)");
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }

                });
            });
        },
        Descargar(id)
        {
            window.open(this.url + "/descargar/" + id, "_blank");
        }

    }, // Fin metodos
    mounted()
    {
        this.ObtenerProyectos();
        this.ObtenerRecepcions();
    }
}
</script>
