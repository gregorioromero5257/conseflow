<template>
<div class=''>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Soldadores de {{servicio_sistema.proyecto.nombre}}
            <button type='button' class='btn btn-dark float-sm-right ml-1' @click='CerrarCardSoldadores()'>
                <i class='fas fa-arrow-left'>&nbsp;</i>Regresar
            </button>

            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalSoldadores(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Soldadores-->
                <div class=''>
                    <vue-element-loading :active="isSoldadoresLoading" />
                    <v-client-table :columns='columns_list_soldadores_proyecto' :data='list_soldadores_proyecto' :options='options_soldadores_proyecto' ref='soldadores_proyecto'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalSoldadores(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template slot='estado' slot-scope='props'>
                            <button class="btn btn-success" type="button" v-if="props.row.estado==1">Activo</button>
                            <button class="btn btn-warning" type="button" v-else>Inactivo</button>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Modal Soldadores -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_soldadores}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_soldadores'></h4>
                    <button type='button' class='close' @click='CerrarModalSoldadores()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>

                        <!-- Proyecto -->
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Proyecto</label>
                            <div class='col-md-7'>
                                <input class="border-0 font-weight-bold form-control text-black bg-white" disabled v-model="servicio_sistema.proyecto.nombre" />
                            </div>
                        </div>
                        <hr class="mx-1">
                        <h5 class="mb-4">Soldador</h5>

                        <!-- Soldador -->
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Nombre</label>
                            <div class='col-md-7'>
                                <vue-element-loading :active="isSoldadoresLoading" />
                                <v-select :options="list_soldadores" label="nombre_clave" v-validate="'required'" name="soldador" @input="VerProcedimientosSoldador" v-model="modal_asignacion.soldador"></v-select>
                                <span class="text-danger">{{ errors.first('soldador') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Certificación</label>
                            <div class='col-md-7'>
                                <vue-element-loading :active="isProcedimientosLoading" />
                                <v-select :options="list_procedimientos" label="clave_folio" v-validate="'required'" name="procedimiento" v-model="modal_asignacion.procedimiento"></v-select>
                                <span class="text-danger">{{ errors.first('procedimiento') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Procedimiento</label>
                            <div class='col-md-7'>
                                <input :disabled="true" type="text" v-validate="'required'" data-vv-name="Nombre procedimiento" class="form-control text-dark bg-white" disabled v-model="modal_asignacion.procedimiento.nombre_procedimiento">
                                <span class="text-danger">{{ errors.first('Nombre procedimiento') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Fecha</label>
                            <div class='col-md-3'>
                                <input :disabled="true" type="date" name="fecha" v-validate="'required'" class="form-control" v-model="modal_asignacion.procedimiento.fecha_certificacion">
                            </div>
                            <label class='col-md-1 form-control-label'>WPS</label>
                            <div class='col-md-3'>
                                <input :disabled="true" type="text" name="folio" v-validate="'required'" class="form-control" v-model="modal_asignacion.procedimiento.folio">
                            </div>
                        </div>

                        <!-- Máquina -->
                        <hr class="mx-1">
                        <h5 class="mb-4">Máquina de soldar</h5>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>No. control</label>
                            <div class='col-md-7'>
                                <vue-element-loading :active="isMaquinasLoading" />
                                <v-select label="no_control" v-validate="'required'" :options="list_maquinas_asginacion" @input="CargarCertificados" name="maquina" v-model="modal_asignacion.maquina"></v-select>
                                <span class="text-danger">{{ errors.first('maquina') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>No. serie</label>
                            <div class='col-md-5'>
                                <input :disabled="true" type='text' class='text-black  bg-white form-control' v-model='modal_asignacion.maquina.no_serie'>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Marca</label>
                            <div class='col-md-3'>
                                <input :disabled="true" type='text' class='text-black bg-white form-control' v-model='modal_asignacion.maquina.marca'>
                            </div>

                            <label class='col-md-1 form-control-label'>Modelo</label>
                            <div class='col-md-3'>
                                <input :disabled="true" type='text' class='text-black bg-white form-control' v-model='modal_asignacion.maquina.modelo'>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Certificado</label>
                            <div class='col-md-7'>
                                <vue-element-loading :active="isCertificadosLoading" />
                                <v-select label="no_certificado" v-validate="'required'" :options="list_certificados" name="certificado" v-model="modal_asignacion.certificado_maquina"></v-select>
                                <span class="text-danger">{{ errors.first('certificado') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class='col-md-3 form-control-label'>Fecha</label>
                            <div class='col-md-3'>
                                <input :disabled="true" type='text' class='text-black bg-white form-control' v-model='modal_asignacion.certificado_maquina.fecha'>
                            </div>
                        </div>

                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalSoldadores()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_soldadores== 1' class='btn btn-secondary' @click='GuardarSoldadores(true)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_soldadores== 2' class='btn btn-secondary' @click='GuardarSoldadores(false)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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
    props: ["servicio_sistema"],
    data()
    {
        return {
            url: "/calidad/sold_proy",
            url_maquinas: "/calidad/maquinas",
            // Tabla 
            ver_modal_soldadores: 0,
            columns_list_soldadores_proyecto: ['id', 'nombre_clave', 'clave', 'wpq', 'no_control'],
            list_soldadores_proyecto: [],
            options_soldadores_proyecto:
            {
                headings:
                {
                    id: 'Acciones',
                    wpq: "WPQ",
                    no_control: "Máquina"
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['soldador', 'clave', 'wpq', 'no_control'],
                filterable: ['soldador', 'clave', 'wpq', 'no_control'],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            modificar: true,
            listEmpleados: [],
            titulo_modal_soldadores: '',
            tipoAccion_modal_soldadores: 0,
            soldadores_modal:
            {},
            isEmpleadosLoading: false,
            isSoldadoresLoading: false,
            isMaquinasLoading: false,
            // Asignar maquina
            ver_modal_asignar_maquina: false,
            list_maquinas_asginacion: [],
            modal_asignacion:
            {
                maquina:
                {
                    id: null
                },
                certificado_maquina:
                {
                    id: null
                },
                procedimiento:
                {
                    id: null
                },
                soldador:
                {
                    id: null
                },
            },
            isProcedimientosLoading: false,
            list_certificados: [],
            isCertificadosLoading: false,
            list_procedimientos: [],
            list_soldadores: [],
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        // Cierra el card de soldadores (esta)
        CerrarCardSoldadores()
        {
            this.$emit("cerrarCard");
        },
        // Obtiene los soldadores del proyecto actual
        ObtenerSoldadoresProyecto()
        {
            this.isSoldadoresProyectoLoading = true;
            axios.get(this.url + "/obtener/" + this.servicio_sistema.proyecto.id).then(res =>
            {
                this.isSoldadoresProyectoLoading = false;
                if (res.data.status)
                {
                    this.list_soldadores_proyecto = res.data.soldadores;
                }
                else
                {
                    this.Error(res.data);
                }
            })
        },
        // Abrir modal para agregar/modificar soldador
        AbrirModalSoldadores(nuevo, soldador = {})
        {
            this.modal_asignacion.proyecto_id = soldador.id;
            // Cargar soldadores registrados
            this.isSoldadoresLoading = true;
            axios.get("/calidad/sold_proy/obtenersoldadores").then(res =>
            {
                this.isSoldadoresLoading = false;
                if (res.data.status)
                {
                    this.list_soldadores = res.data.soldadores;
                }
                else
                {
                    this.Error(res.data);
                }
            });
            // Cargar maquinas de soldar
            this.isMaquinasLoading = true;
            axios.get("/calidad/sold_proy/maquinas").then(res =>
            {
                if (res.data.status)
                {
                    this.list_maquinas_asginacion = res.data.maquinas;
                    this.isMaquinasLoading = false;
                }
                else
                {
                    this.Error(res.data);
                }
            });
            this.ver_modal_soldadores = true;

            if (nuevo)
            {
                this.modificar = true;
                // Crear nuevo
                this.titulo_modal_soldadores = 'Asignar soldador';
                this.tipoAccion_modal_soldadores = 1;
            }
            else
            {
                // Actualizar
                // Datos del soldador
                axios.get(this.url + "/detalles/" + soldador.id).then(res =>
                {
                    if (res.data.status)
                    {
                        let data = res.data.soldador;
                        this.modal_asignacion = {
                            id: soldador.id,
                            maquina:
                            {
                                id: data.maquina_id,
                                no_control: data.no_control,
                                no_serie: data.no_serie,
                                marca: data.marca,
                                modelo: data.modelo,
                            },
                            certificado_maquina:
                            {
                                id: data.certificado_id,
                                no_certificado: data.no_certificado,
                                fecha: data.fecha_calibracion
                            },
                            procedimiento:
                            {
                                id: data.procedimiento_id,
                                clave_folio: data.clave_folio,
                                nombre_procedimiento: data.nombre_procedimiento,
                                fecha_certificacion: data.fecha_certificacion,
                                folio: data.folio_procedimiento
                            },
                            soldador:
                            {
                                id: data.soldador_id,
                                nombre_clave: data.nombre_clave
                            }
                        };
                        console.error(this.modal_asignacion);
                    }
                    else
                    {
                        this.Error(res.data);
                    }
                }).catch(r =>
                {
                    r.mensaje = "ERROR";
                    this.Error(r);
                });
                this.modificar = false;
                this.titulo_modal_soldadores = 'Modificar soldador';
                this.tipoAccion_modal_soldadores = 2;
            } // Fin if
        },
        // Guardar los el soldador en el proyecto actual
        GuardarSoldadores(nuevo)
        {
            this.$validator.validate().then(result =>
            {
                if (!result)
                {
                    toastr.warning("Ingrese todos los campos");
                    return;
                }
                console.error(this.modal_asignacion);
                axios.post(this.url + "/guardar",
                {
                    "id": this.modal_asignacion.id,
                    "certificacion_procedimiento_id": this.modal_asignacion.procedimiento.id,
                    "maquina_id": this.modal_asignacion.certificado_maquina.id,
                    "proyecto_id": this.servicio_sistema.proyecto.id
                }).then(res =>
                {
                    if (res.data.status)
                    {
                        this.Success("soldador guardado");
                        this.CerrarModalSoldadores();
                        this.ObtenerSoldadoresProyecto();
                    }
                    else
                    {
                        this.Error(res.data);
                    }
                })
            });
        },
        // Cierra modal de selección de soldadores
        CerrarModalSoldadores()
        {
            this.ver_modal_soldadores = false;
            this.LimpiarModal();
        },

        Success(ms)
        {
            toastr.success(ms + " correctamente");
        },
        AbrirModalAsignarMaquina(soldador)
        {
            // console.error(soldador);
            this.ver_modal_asignar_maquina = true;
            this.modal_asignacion.clave = soldador.clave;
            this.modal_asignacion.nombre_soldador = soldador.nombre;
            // console.error(this.modal_asignacion);
            axios.get(this.url_maquinas).then(res =>
            {
                if (res.data.status)
                {
                    this.list_maquinas_asginacion = res.data.maquinas;
                }
                else
                {
                    this.Error(res.data);
                }
            });
        },
        CargarCertificados()
        {
            this.isCertificadosLoading = true;
            axios.get(this.url + "/certificados/" + this.modal_asignacion.maquina.id).then(res =>
            {
                this.isCertificadosLoading = false;
                if (res.data.status)
                {
                    this.list_certificados = res.data.certificados;
                }
                else
                {
                    this.Error(res.data);
                }
            });
        },
        CerrarModalAsignarMaquina()
        {
            this.ver_modal_asignar_maquina = false;
            LimpiarModal();
        },
        AbrirModalAsignarProcedimiento(model)
        {
            this.ver_modal_asignar_procedimiento = true;
        },
        CerrarModalAsignarProcedimiento()
        {
            this.ver_modal_asignar_procedimiento = false;
        },
        LimpiarModal()
        {
            this.modal_asignacion = {
                maquina:
                {
                    id: null
                },
                certificado_maquina:
                {
                    id: null
                },
                procedimiento:
                {
                    id: null
                },
                soldador:
                {
                    id: null
                },
            };
        },
        VerProcedimientosSoldador()
        {
            if (this.modal_asignacion.soldador == null) return;
            if (this.modal_asignacion.soldador.id == null) return;
            this.isProcedimientosLoading = true;
            axios.get("/calidad/sold_proy/obtenercertificaciones/" + this.modal_asignacion.soldador.id).then(res =>
            {
                if (res.data.status)
                {
                    this.list_procedimientos = res.data.procedimientos;
                }
                else
                {
                    this.Error(res.data);
                }
            }).catch(r =>
            {
                this.Error(
                {
                    mensaje: "ERROR",
                    data: r
                });
            })
            this.isProcedimientosLoading = false;
        },
        Error(data = {})
        {
            console.error(data);
            toastr.error(data.mensaje);
        }
    }, // Fin metodos
    mounted()
    {
        this.ObtenerSoldadoresProyecto();
    }
}
</script>
