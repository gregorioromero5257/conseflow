<template>
<div class="main">
    <!-- Card Inicio-->
    <div class="card">
        <!-- Inicio card-->
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Control de solicitudes
        </div>
        <div class="card-body">
            <div class="">
                <!-- Tabla de Solicitud-->
                <div class="">
                    <v-client-table :columns="columns_solicitud" :data="list_solicitud" :options="options_solicitud" ref="tbl_solicitud">
                        <template slot="id" slot-scope="props">
                            <div class="btn-group" role="group">
                                <button id="btn_id" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                                <div class="dropdown-menu">
                                    <template>
                                        <button type="button" @click="AbrirModalSolicitud(props.row)" class="dropdown-item">
                                            <i class="icon-pencil"></i>&nbsp;Detalles
                                        </button>
                                        <button v-if="props.row.estado_id<4" type="button" @click="CambiarEstado(props.row)" class="dropdown-item">
                                            <i class="icon-pencil"></i>&nbsp;Cambiar Estado
                                        </button>
                                        <button v-if="props.row.estado_id<4" type="button" @click="AsignarPrioridad(props.row)" class="dropdown-item">
                                            <i class="fas fa-exclamation-triangle"></i>&nbsp;Asignar prioridad
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template slot="tipo_id" slot-scope="props">
                            <button v-if="props.row.tipo_id==1" class="btn btn-primary">Falla del equipo</button>
                            <button v-else-if="props.row.tipo_id==2" class="btn btn-secondary">Ayuda del ERP</button>
                            <button v-else-if="props.row.tipo_id==3" class="btn btn-warning">Conexión a Internet</button>
                            <button v-else-if="props.row.tipo_id==4" class="btn btn-dark">Otro</button>
                        </template>
                        <template slot="fecha_hora_terminado" slot-scope="props">
                            <p v-if="props.row.fecha_hora_terminado==null">-</p>
                            <p v-else>{{props.row.fecha_hora_terminado}}</p>
                        </template>

                        <template slot="estado_id" slot-scope="props">
                            <button v-if="props.row.estado_id==1" class="btn btn-primary">
                                <i class="fas fa-paper-plane ml-1"></i> Enviado
                            </button>
                            <button v-else-if="props.row.estado_id==2" class="btn btn-secondary">
                                <i class="fas fa-user-check"></i> Recibido
                            </button>
                            <button v-else-if="props.row.estado_id==3" class="btn btn-warning">
                                <i class="fas fa-chalkboard-teacher"></i> En proceso</button>
                            <button v-else-if="props.row.estado_id==4" class="btn btn-success">
                                <i class="fas fa-check-double"></i> Terminado</button>
                            <button v-else-if="props.row.estado_id==5" class="btn btn-danger">
                                <i class="fas fa-times"></i> Cancelado</button>
                        </template>
                        <template slot="prioridad" slot-scope="props">
                            <button v-if="props.row.prioridad==1" class="btn btn-primary">
                                <i class="fas fa-paper-plane ml-1"></i>&nbsp; Baja
                            </button>
                            <button v-else-if="props.row.prioridad==2" class="btn btn-secondary">
                                <i class="fas fa-user-check"></i>&nbsp; Media
                            </button>
                            <button v-else-if="props.row.prioridad==3" class="btn btn-warning">
                                <i class="fas fa-chalkboard-teacher"></i>&nbsp;Alta</button>
                            <button v-else-if="props.row.prioridad==4" class="btn btn-danger">
                                <i class="fas fa-exclamation-triangle"></i>&nbsp;URGENTE</button>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div>
            <!-- card-->
        </div>
    </div>

    <!-- Modal Solicitud -->
    <div class="modal fade" tabindex="-1" :class="{ mostrar: ver_modal_solicitud }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="titulo_modal_solicitud"></h4>
                    <button type="button" class="close" @click="CerrarModalSolicitud()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">Asunto</label>
                        <div class="col-md-9">
                            <input name="asunto" disabled v-validate="'required'" placeholder="Asunto de la solicitud" type="text" class="bg-white form-control" v-model="solicitud_modal.asunto" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">Tipo</label>
                        <div class="col-md-9">
                            <select class="form-control bg-white" disabled name="tipo" v-validate="'required'" v-model="solicitud_modal.tipo_id">
                                <option value="1">Falla de equipo</option>
                                <option value="2">Ayuda del ERP</option>
                                <option value="3">Conexión a Internet</option>
                                <option value="4">Otro</option>
                            </select>
                            <span class="text-danger">{{ errors.first("tipo") }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">Descripción</label>
                        <div class="col-md-9">
                            <textarea disabled placeholder="Ingrese una descripción detallada del problema" rows="5" v-validate="'required'" name="descripcion" class="form-control bg-white" v-model="solicitud_modal.descripcion">
                  </textarea>
                            <span class="text-danger">{{errors.first("descripcion")}}</span>
                        </div>
                    </div>

                    <div class="form-group row mt-4 mx-2">
                        <p>Imágenes</p>
                        <div class="row">
                            <label for="Evidencias"></label>
                            <!-- <button class="btn" @click="CargarImagenes2()">Cargar</button> -->
                            <div class="col col-5  mt-2 card order border-secondary py-1 px-1" v-for="img in listImagenes">
                                <img class="img-fluid img-ver" :src="img" alt="La cabeza y el torso" @click="VerImagen(img)">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" @click="CerrarModalSolicitud()">
                        <i class="fas fa-times"></i>&nbsp;Cerrar
                    </button>
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
.img-ver:hover {
    cursor: pointer;
    margin: 1px;
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
            path: "",
            url: "/ti/solicitudes",
            // Tabla
            ver_modal_solicitud: 0,
            columns_solicitud: [
                "id",
                "nombre_empleado",
                "tipo_id",
                "asunto",
                "prioridad",
                "fecha_hora_recibido",
                "fecha_hora_terminado",
                "estado_id",

            ],
            list_solicitud: [],
            options_solicitud:
            {
                headings:
                {
                    id: "Acciones",
                    asunto: "Asunto",
                    tipo_id: "Tipo",
                    fecha_hora_recibido: "Solicitado",
                    fecha_hora_termiado: "Terminado",
                    estado_id: "Estado",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: [
                    "asunto",
                    "prioridad",
                    "tipo_id",
                    "estado_id",
                ],
                filterable: [
                    "asunto",
                    "prioridad",
                    "tipo_id",
                    "estado_id",
                ],
                filterByColumn: true,
                texts: config.texts,
            }, //options
            // Modal
            titulo_modal_solicitud: "",
            tipoAccion_modal_solicitud: 0,
            solicitud_modal:
            {
                id: 0,
                asunto: "",
                tipo_id: 0,
                descripcion: ""
            },
            listImagenes: [],
        }; // return
    }, //data
    computed:
    {},
    methods:
    {
        ObtenerTodasSolicitudes()
        {
            axios.get(this.url + "/obtenertodas/1").then(res =>
            {
                if (res.data.status)
                {
                    this.list_solicitud = res.data.solicitudes;
                }
                else
                {
                    toastr.error("Intente más tarde", "Error");
                    console.error(res);
                }
            })
        },
        AbrirModalSolicitud(model)
        {
            // return;
            this.ver_modal_solicitud = true;
            this.titulo_modal_solicitud = "Detalle de solicitud";
            this.tipoAccion_modal_solicitud = 1;
            // this.solicitud_modal = model;
            this.solicitud_modal = {
                id: model.id,
                asunto: model.asunto,
                tipo_id: model.tipo_id,
                descripcion: model.descripcion
            };
            //Cargar imagenes
            this.CargarImagenes2(model.id);
        },
        CerrarModalSolicitud()
        {
            this.ver_modal_solicitud = false;
            Utilerias.resetObject(this.solicitud_modal);
        },
        CambiarEstado(row)
        {
            let x = this;
            Swal.fire(
            {
                title: 'Asignar estado',
                input: 'select',
                inputOptions:
                {
                    "2": "Recibido",
                    "3": "En proceso",
                    "4": "Temrminado",
                    "5": "Cancelado"
                },
                showCancelButton: true
            }).then(function (result)
            {
                if (result.value)
                {
                    if (result.value == null) return;
                    if (result.value != "")
                    {
                        if (result.value >= 4) // Terminado o cancelado
                        {
                            let motivo = result.value == 4 ? "Solución del problema" : "Motivo de cancelación";
                            // Motivo de rechazo
                            Swal.fire(
                            {
                                input: "text",
                                icon: "info",
                                title: motivo,
                                showDenyButton: true,
                                text: "",
                                showCancelButton: true,
                                confirmButtonText: `Confirmar`,
                            }).then(ms =>
                            {
                                if (ms == null) return;
                                if (ms.value == null) return;
                                if (ms.value == "") return;
                                axios.put(x.url + "/cambiarestado",
                                {
                                    id: row.id,
                                    estado_id: result.value,
                                    mensaje: ms.value,
                                }).then(res =>
                                {
                                    if (res.data.status)
                                    {
                                        toastr.success("Estado cambiado correctamente");
                                        x.ObtenerTodasSolicitudes();
                                    }
                                    else
                                    {
                                        toastr.error("Contacte al administrador e intente más tarde", "Error")
                                    }
                                });
                            });
                        }
                        else
                        {
                            axios.put(x.url + "/cambiarestado",
                            {
                                id: row.id,
                                estado_id: result.value
                            }).then(res =>
                            {
                                if (res.data.status)
                                {
                                    toastr.success("Estado cambiado correctamente");
                                    x.ObtenerTodasSolicitudes();
                                }
                                else
                                {
                                    toastr.error("Contacte al administrador e intente más tarde", "Error")
                                }
                            })
                        }
                    }
                }
            });
        },
        AsignarPrioridad(row)
        {
            let x = this;
            Swal.fire(
            {
                title: 'Asignar estado',
                input: 'select',
                inputOptions:
                {
                    "1": "Baja",
                    "2": "Media",
                    "3": "Alta",
                    "4": "Urgente"
                },
                showCancelButton: true
            }).then(result =>
            {
                if (result.value == null) return;
                if (result.value != "")
                {
                    axios.put(x.url + "/asignarprioridad",
                    {
                        id: row.id,
                        prioridad: result.value
                    }).then(res =>
                    {
                        if (res.data.status)
                        {
                            toastr.success("Prioridad asignada correctamente");
                            x.ObtenerTodasSolicitudes();
                        }
                        else
                        {
                            toastr.error("Contacte al administrador e intente más tarde", "Error")
                        }
                    });
                }

            });
        },
        CargarImagenes2(soli_id)
        {
            axios.get(this.url + "/cargarimagenes/" + soli_id).then(res =>
            {
                if (res.data.status)
                {
                    this.listImagenes = res.data.imagenes;
                    console.error(res.data.imagenes);
                    console.error(res.data);
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            }).catch(e =>
            {
                console.error(e);
            });
        },

        VerImagen(nombre)
        {
            window.open(nombre, '_blank');
        }
    },
    // Fin metodos
    mounted()
    {
        this.ObtenerTodasSolicitudes();
    },

}
</script>
