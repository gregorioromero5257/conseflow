<template>
<div class="main">
    <!-- Card Inicio-->
    <div class="card">
        <!-- Inicio card-->
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Solicitud
            <button type="button" class="btn btn-dark float-sm-right" @click="AbrirModalSolicitud()">
                <i class="fas fa-plus">&nbsp;</i>Nuevo
            </button>
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
                                        <button type="button" @click="AbrirModalSolicitud()" class="dropdown-item">
                                            <i class="icon-pencil"></i>&nbsp;Actualizar
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
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label">Solicitante</label>
                            <div class="col-md-9">
                                <v-select :disabled="!PermisosCrud.Create" :options="listaUsuarios" v-model="Usuario" label="nombre" name="solitandte" data-vv-name="proyecto">
                                </v-select>
                                <span class="text-danger">{{ errors.first("asunto") }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label">Asunto</label>
                            <div class="col-md-9">
                                <input name="asunto" v-validate="'required'" placeholder="Asunto de la solicitud" type="text" class="form-control" v-model="solicitud_modal.asunto" />
                                <span class="text-danger">{{ errors.first("asunto") }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label">Tipo</label>
                            <div class="col-md-9">
                                <select class="form-control" name="tipo" v-validate="'required'" v-model="solicitud_modal.tipo_id">
                                    <option value="1">Falla de equipo</option>
                                    <option value="2">Ayuda del Sistema ERP</option>
                                    <option value="3">Conexión a Internet</option>
                                    <option value="4">Otro</option>
                                </select>
                                <span class="text-danger">{{ errors.first("tipo") }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label">Descripción</label>
                            <div class="col-md-9">
                                <textarea placeholder="Ingrese una descripción detallada del problema" rows="5" v-validate="'required'" name="descripcion" class="form-control" v-model="solicitud_modal.descripcion">
                  </textarea>
                                <span class="text-danger">{{errors.first("descripcion")}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <label for="Evidencias"></label>
                                <p>Añadir imagenes</p>
                                <div class="col">
                                    <vue-dropzone class="border border-secondary" ref="dropZone" id="dropZone"
                                    style="margin-left: -1rem; margin-right: -1rem" :options="dropzoneOptions" :useCustomSlot=true>
                                        <div class="dropzone-custom-content">
                                            <h3 class="dropzone-custom-title">Selecciona las imágenes</h3>
                                            <div class="subtitle">para ayudar a identificar el problema</div>
                                        </div>
                                    </vue-dropzone>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" @click="CerrarModalSolicitud()">
                        <i class="fas fa-times"></i>&nbsp;Cerrar
                    </button>
                    <button type="button" v-if="tipoAccion_modal_solicitud == 1" class="btn btn-secondary" @click="GuardarSolicitud(1)">
                        <i class="fas fa-save"></i>&nbsp;Guardar
                    </button>
                    <button type="button" v-if="tipoAccion_modal_solicitud == 2" class="btn btn-secondary" @click="GuardarSolicitud(0)">
                        <i class="fas fa-save"></i>&nbsp;Actualizar
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

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from "../../Herramientas/utilerias.js";
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";

var config = require("../../Herramientas/config-vuetables-client").call(this);
export default
{
    components:
    {
        vueDropzone: vue2Dropzone,
    },
    data()
    {
        return {
            url: "/ti/solicitudes",
            dropzoneOptions:
            {
                url: "https://httpbin.org/post",
                thumbnailWidth: 200,
                addRemoveLinks: true,
                dictCancelUploadConfirmation: true,
                dictCancelUpload: "Cancelar",
                dictUploadCanceled: "cancelar",
                dictRemoveFile: 'Eliminar archivo',
                maxFilesize: 40,
                // acceptedFiles:"image/png,image/jpeg,file/pdf",
                dictFileTooBig:"El archivo debe ser menor a 40 MB",
                dictDefaultMessage: "<i class='fas fa-cloud-upload-alt ml-1'></i>",
            },
            // Tabla
            ver_modal_solicitud: 0,
            columns_solicitud: [
                // "id",
                "tipo_id",
                "asunto",
                "descripcion",
                "fecha_hora_recibido",
                "fecha_hora_terminado",
                "estado_id",
            ],
            list_solicitud: [],
            options_solicitud:
            {
                headings:
                {
                    // id: "Acciones",
                    asunto: "Asunto",
                    tipo_id: "Tipo",
                    fecha_hora_recibido: "Solicitado",
                    fecha_hora_termiado: "Terminado",
                    estado_id: "Estado",
                    descripcion: "Descripción",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: [
                    "asunto",
                    "tipo_id",
                    "descripcion",
                ],
                filterable: [
                    "id",
                    "asunto",
                    "tipo_id",
                    "descripcion",
                ],
                filterByColumn: true,
                texts: config.texts,
            }, //options
            // Modal
            titulo_modal_solicitud: "",
            tipoAccion_modal_solicitud: 0,
            solicitud_modal:
            {},
            // Permisos
            PermisosCrud:
            {},
            listaUsuarios: [],
            Usuario:
            {
                id: 0,
                nombre: "",
            },
        }; // return
    }, //data
    computed:
    {},
    methods:
    {
        ObtenerSolicitudes()
        {
            axios.get(this.url + "/obtener").then(res =>
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
        AbrirModalSolicitud()
        {
            this.ver_modal_solicitud = true;

            // Crear nuevo
            this.titulo_modal_solicitud = "Registrar solicitud";
            this.tipoAccion_modal_solicitud = 1;
            this.CargarUsuarios();
            this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
            console.error(this.PermisosCrud);
        },
        CargarUsuarios()
        {
            axios.get(this.url + "/usuarios").then(res =>
            {
                if (res.data.status)
                {
                    this.listaUsuarios = res.data.listaUsuarios;
                    this.Usuario = res.data.usuario;
                }
                else
                {
                    toastr.error("Error al obtener los usuarios");
                }
            });
        },
        CerrarModalSolicitud()
        {
            this.ver_modal_solicitud = false;
            this.$refs.dropZone.removeAllFiles(); // Limpiar archivos
            Utilerias.resetObject(this.solicitud_modal);
        },
        GuardarSolicitud()
        {
            this.$validator.validate().then((res) =>
            {
                if (!res) return;
                let files = this.$refs.dropZone.getAcceptedFiles();
                axios.post(this.url + "/registrar",
                {
                    asunto: this.solicitud_modal.asunto,
                    descripcion: this.solicitud_modal.descripcion,
                    tipo_id: this.solicitud_modal.tipo_id,
                    archivos: files,
                    usuario_id:this.Usuario.id,
                }).then(res =>
                {
                    if (res.data.status)
                    {
                        toastr.success("Solitud registrada");
                        this.CerrarModalSolicitud();
                        this.ObtenerSolicitudes();
                    }
                    else
                    {
                        toastr.error("Intente más tarde", "Error");
                        console.error(res);
                    }
                }).catch(res =>
                {
                    this.CerrarModalSolicitud();
                    toastr.error("Intente más tarde", "Error");
                    console.error(res);
                })
            });
        },
        ComprobarArchivos()
        {
            let files = this.$refs.dropZone.getAcceptedFiles();
            console.error(files);
            return true;
        },
    },
    // Fin metodos
    mounted()
    {
        this.ObtenerSolicitudes();
    },

}
</script>
