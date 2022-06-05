<template>
<div class='main'>

    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Soldadores
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalSoldadores(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Soldadores-->
                <div class=''>
                    <vue-element-loading :active="isSoldadoresLoading" />
                    <v-client-table :columns='columns_soldadores' :data='list_soldadores' :options='options_soldadores' ref='tbl_soldadores'>
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
                                        <button type='button' @click='AbrirModalAsignarProcedimiento(true,props.row)' class='dropdown-item'>
                                            <i class='fas fa-plus'></i>&nbsp;Añadir procedimiento
                                        </button>
                                        <button type='button' @click='AbrirModalHistorialProcedimientos(props.row)' class='dropdown-item'>
                                            <i class='fas fa-list'></i>&nbsp;Ver procedimientos
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
    <div v-if="ver_modal_soldadores" class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_soldadores}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
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
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Clave</label>
                            <div class='col-md-3'>
                                <input type='text' class='form-control' v-validate="'required'" v-model='soldadores_modal.clave' name="clave">
                                <span class="text-danger">{{ errors.first('clave') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Empleado</label>
                            <div class='col-md-7'>
                                <vue-element-loading :active="isEmpleadosLoading" />
                                <v-select :disabled="!modificar" :options="listEmpleados" label="nombre" v-validate="'required'" name="empleado" v-model="soldadores_modal.empleado"></v-select>
                                <span class="text-danger">{{ errors.first('empleado') }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalSoldadores()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_soldadores== 1' class='btn btn-secondary' @click='GuardarSoldadores(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_soldadores==2' class='btn btn-secondary' @click='GuardarSoldadores(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Modal Historial -->
    <div class="modal fade" tabindex="-1" :class="{ mostrar: ver_modal_historial_procedimientos}" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <div class="modal-header">
                        <h4 class="modal-title">Certificaciones</h4>
                        <button type="button" class="close" @click="CerrarModalHistorialProcedimientos()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <vue-element-loading :active="isLoadingHistorial" />
                        <div class="container">
                            <div v-if="listHistorial.length>0">
                                <table class="table table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Procedimiento</th>
                                            <th scope="col">WPQ</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">No. Certificado</th>
                                            <th scope="col">Certificado</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(h,i) in listHistorial">
                                            <th scope="row">{{i+1}}</th>
                                            <td>{{h.nombre_aux}}</td>
                                            <td>{{h.wpq}}</td>
                                            <td>{{h.fecha_certificacion}}</td>
                                            <td>{{h.folio}}</td>
                                            <td>
                                                <a v-if="h.certificado!=''" class="link" style="cursor:pointer" @click="DescargarCertificado(h.id)">
                                                    {{h.certificado}}
                                                </a>
                                                <a v-else-if="h.certificado==null" class="link" style="cursor:pointer" @click="DescargarCertificado(h.id)">
                                                    N/D
                                                </a>
                                                <a v-else>N/D</a>
                                            </td>
                                            <td>
                                                <span style="cursor:pointer" class="btn btn-sm bg-dark" @click="AbrirModalAsignarProcedimiento(false,h)">
                                                    <i class="fas fa-edit text-white"></i>
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else>
                                <h5 class="text-center">Sin certificaciones</h5>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="CerrarModalHistorialProcedimientos()">
                            <i class="fas fa-window-close"></i>
                            &nbsp;Cerrar
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Modal Procedimieto -->
    <div v-if="ver_modal_asignar_procedimiento" class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_asignar_procedimiento}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Certificación de procedimiento de soldadura</h4>
                    <button type='button' class='close' @click='CerrarModalAsignarProcedimiento()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <vue-element-loading :active="isEmpleadosLoading" />
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Clave</label>
                            <div class='col-md-3'>
                                <input type='text' disabled class='form-control text-black bg-white' v-model='modal_asignacion.clave' name="clave">
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Soldador</label>
                            <div class='col-md-7'>
                                <input type="text" disabled class="form-control text-black bg-white" v-model="modal_asignacion.nombre_soldador">
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Procedimieto</label>
                            <div class='col-md-7'>
                                <v-select :options="listProcedimientos" label="nombre_aux" name="procedimiento" v-model="modal_asignacion.procedimiento" v-validate="'required'"></v-select>
                                <span class="text-danger">{{errors.first("procedimiento")}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Fecha</label>
                            <div class='col-md-3'>
                                <input type="date" name="fecha" v-validate="'required'" class="form-control" v-model="modal_asignacion.fecha">
                                <span class="text-danger">{{errors.first("fecha")}}</span>
                            </div>
                            <label class='col-md-1 form-control-label'>Folio</label>
                            <div class='col-md-3'>
                                <input type="text" name="folio" v-validate="'required'" class="form-control" v-model="modal_asignacion.folio">
                                <span class="text-danger">{{errors.first("folio")}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>WPQ</label>
                            <div class='col-md-7'>
                                <input type="text" name="wpq" class="form-control" v-validate="'required'" v-model="modal_asignacion.wpq">
                                <span class="text-danger">{{errors.first("wpq")}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Certificado</label>
                            <div class='col-md-7'>
                                <input type="file" ref="fileCertificado" id="fileCertificado" accept="application/pdf" name="certificado" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalAsignarProcedimiento()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_asignacion== 1' class='btn btn-secondary' @click='GuardarAsignacion(true)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_asignacion==2' class='btn btn-secondary' @click='GuardarAsignacion(false)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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
            url: "/calidad/soldadores",
            url_maquinas: "/calidad/maquinas/obtenerasginacion",
            // Tabla 
            ver_modal_soldadores: 0,
            columns_soldadores: ['id', 'clave', 'ap_paterno', 'ap_materno', 'nombre_aux', 'estado'],
            list_soldadores: [],
            options_soldadores:
            {
                headings:
                {
                    id: 'Acciones',
                    nombre_aux: 'Nombre(s)',
                    ap_paterno: 'Apellido P.',
                    ap_materno: 'Apellido M.',
                    clave: 'Clave'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['id', 'clave', 'ap_paterno', 'ap_materno', 'nombre_aux', 'estado'],
                filterable: ['id', 'clave', 'ap_paterno', 'ap_materno', 'nombre_aux', 'estado'],
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
            // Asignar maquina
            ver_modal_asignar_maquina: false,
            list_maquinas_asginacion: [],
            modal_asignacion:
            {
                procedimiento:
                {},
                soldador:
                {},
            },
            listProcedimientos: [],
            ver_modal_asignar_procedimiento: false,
            tipoAccion_modal_asignacion: 0,
            isGuardarProcedimietosLoading: false,
            ver_modal_historial_procedimientos: false,
            isLoadingHistorial: false,
            listHistorial: [],
            aux_historial:
            {},
            aux_soldador_id: 0,
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        ObtenerSoldadores()
        {
            this.isSoldadoresLoading = true;
            axios.get(this.url + "/obtener").then(res =>
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
            })
        },
        AbrirModalSoldadores(nuevo, model = [])
        {
            // console.error(model);
            this.isEmpleadosLoading = true;
            axios.get(this.url + "/empleados").then(res =>
            {
                this.isEmpleadosLoading = false;
                if (res.data.status)
                {
                    this.listEmpleados = res.data.empleados;
                }
                else
                {
                    this.Error(res.data);
                }
            })
            this.ver_modal_soldadores = true;
            if (nuevo)
            {
                this.modificar = true;
                // Crear nuevo
                this.titulo_modal_soldadores = 'Registrar soldador';
                this.tipoAccion_modal_soldadores = 1;
            }
            else
            {
                // Actualizar
                this.modificar = false;
                this.titulo_modal_soldadores = 'Actualizar soldador';
                this.tipoAccion_modal_soldadores = 2;
                this.soldadores_modal = model;
                this.soldadores_modal.empleado = {
                    "id": model.empleado_id,
                    "nombre": model.nombre
                };
            } // Fin if
        },
        GuardarSoldadores()
        {
            this.$validator.validate().then(result =>
            {
                if (!result) return;
                axios.post(this.url + "/guardar",
                {
                    "id": this.soldadores_modal.id,
                    "clave": this.soldadores_modal.clave,
                    "empleado_id": this.soldadores_modal.empleado.id
                }).then(res =>
                {
                    if (res.data.status)
                    {
                        this.Success("soldador guardado");
                        this.CerrarModalSoldadores();
                        this.ObtenerSoldadores();
                    }
                    else
                    {
                        this.Error(res.data);
                    }
                })
            });
        },
        CerrarModalSoldadores()
        {
            this.ver_modal_soldadores = false;
            Utilerias.resetObject(this.soldadores_modal);
        },
        Success(ms)
        {
            toastr.success(ms + " correctamente");
        },
        AbrirModalAsignarProcedimiento(nuevo, model)
        {
            // console.error("Soldador id:" + this.aux_soldador_id);
            this.aux_soldador = model.id;
            this.isProcedimietosLoading = true;
            axios.get("/calidad/procedimientos/cargarprocedimientos").then(res =>
            {
                this.isProcedimietosLoading = false;
                if (res.data.status)
                {
                    this.listProcedimientos = res.data.procedimietos;
                }
                else
                {
                    this.Error(res.data);
                }
            });
            // console.error(model);
            if (nuevo)
            {
                // console.error(model);
                this.tipoAccion_modal_asignacion = 1;
                this.modal_asignacion.soldador.id = model.id;
                // Obtener procedimientos de sodladura
                this.modal_asignacion.clave = model.clave;
                this.modal_asignacion.nombre_soldador = model.nombre;
            }
            else
            {
                // console.error("Soldador id:" + this.aux_soldador_id);
                this.isEmpleadosLoading = true;
                // console.error("model");
                // console.error(model);
                this.modal_asignacion.id = model.id;
                this.tipoAccion_modal_asignacion = 2;
                // obtener procedimiento
                axios.get(this.url + "/verproced/" + model.id).then(res =>
                {
                    this.isEmpleadosLoading = false;
                    if (res.data.status)
                    {
                        this.modal_asignacion = {
                            id: model.id,
                            soldador:
                            {
                                id: this.aux_soldador_id,
                            },
                            fecha: res.data.certficicacion.fecha_certificacion,
                            folio: res.data.certficicacion.folio,
                            wpq: res.data.certficicacion.wpq,
                            procedimiento:
                            {
                                id: model.procedimiento_id,
                                nombre_aux: model.nombre_aux,
                            },
                            clave: this.aux_historial.clave,
                            nombre_soldador: this.aux_historial.nombre
                        };
                        // console.error(this.modal_asignacion);
                    }
                    else
                    {
                        this.Error(res.data);
                    }
                });

            }

            this.ver_modal_asignar_procedimiento = true;
        },
        GuardarAsignacion(nuevo)
        {
            // console.error(nuevo);
            // console.error(this.modal_asignacion);
            this.$validator.validate().then(valid =>
            {
                if (!valid) return;
                this.isGuardarProcedimietosLoading = true;
                let files = $("#fileCertificado")[0].files;
                let data = new FormData();
                if (files.length >= 1)
                    data.append("certificado", files[0]);
                if (!nuevo)
                    data.append("id", this.modal_asignacion.id);
                data.append("fecha_certificacion", this.modal_asignacion.fecha);
                data.append("folio", this.modal_asignacion.folio);
                data.append("wpq", this.modal_asignacion.wpq);
                data.append("procedimiento_id", this.modal_asignacion.procedimiento.id);
                if (nuevo)
                    data.append("soldador_id", this.modal_asignacion.soldador.id);
                else
                    data.append("soldador_id", this.aux_soldador_id);

                axios.post("/calidad/soldadores/guardarproced", data).then(res =>
                {
                    this.isGuardarProcedimietosLoading = false;
                    if (res.data.status)
                    {
                        toastr.success("Procedimiento guardado");
                        this.CerrarModalAsignarProcedimiento();
                    }
                    else
                    {
                        this.Error(res.data);
                    }
                }).catch(r =>
                {
                    this.Error(
                    {
                        mensaje: "Error"
                    });
                })
            });
        },
        CerrarModalAsignarProcedimiento()
        {
            this.modal_asignacion = {soldador:{},procedimiento:{}};
            this.listProcedimientos = [];
            this.$refs.fileCertificado.value=null;
            this.ver_modal_asignar_procedimiento = false;
        },

        //* HISTORIAL PROCEDIMIENTOS *//
        AbrirModalHistorialProcedimientos(model) // soldador
        {
            // console.error("model historial");
            // console.error(model);
            this.aux_soldador_id = model.id;
            this.aux_proced_id =
                this.modal_asignacion
            this.aux_historial = model;
            this.isLoadingHistorial = true;
            axios.get(this.url + "/verproceds/" + model.id).then(res =>
            {
                this.isLoadingHistorial = false;
                if (res.data.status)
                {
                    this.listHistorial = res.data.procedimientos;
                }
                else
                {
                    this.Error(res.data);
                }
            });
            this.ver_modal_historial_procedimientos = true;
        },
        CerrarModalHistorialProcedimientos()
        {
            this.ver_modal_historial_procedimientos = false;
            this.listHistorial = [];
            this.modal_asignacion = {
                soldador:
                {},
                procedimiento:
                {}
            };
        },
        Error(data = {})
        {
            toastr.error(data.mensaje);
            // console.error(data);
        }
    }, // Fin metodos
    mounted()
    {
        this.ObtenerSoldadores();
        // console.error("sdf");
    }
}
</script>
