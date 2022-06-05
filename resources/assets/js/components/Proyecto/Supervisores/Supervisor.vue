<template>
<main class="main">
    <div class="card border-dark">
        <div class="card-header bg-dark text-white">
            <i class="fa fa-align-justify"> </i> Asignar supervisor
        </div>
        <div class="card-body">
            <v-client-table :columns="columnsProyectos" :data="lstProyectos" :options="optionsProyectos">
                <template slot="sp_id" slot-scope="props">
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group dropup" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-grip-horizontal"></i> Acciones
                            </button>
                            <div class="dropdown-menu">
                                <button type="button" @click="AbrirModalAsignacion(props.row)" class="dropdown-item">
                                    <i class="fas fa-eye"></i>&nbsp; Asignar Supervisor
                                </button>
                                <button v-if="props.row.sp_activo==1" type="button" @click="CambiarEstadoSuperv(0,props.row)" class="dropdown-item">
                                    <i class="fas fa-ban"></i>&nbsp; Desactivar supervisor
                                </button>
                                <button v-if="props.row.sp_activo==0" type="button" @click="CambiarEstadoSuperv(1,props.row)" class="dropdown-item">
                                    <i class="fas fa-check"></i>&nbsp; Activar supervisor
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <template slot="sp_activo" slot-scope="props">
                    <template>
                        <div class="container text-center">
                            <button v-if="props.row.sp_activo==0" class="btn btn-danger btn-block">Inactivo</button>
                            <button v-else-if="props.row.sp_activo==1" class="btn btn-success btn-block">Activo</button>
                            <p v-else> — </p>
                        </div>
                    </template>
                </template>

                <template slot="sup_nombre" slot-scope="props">
                    <p v-if="props.row.sup_nombre==''">SIN ASIGNAR</p>
                    <p v-else>{{props.row.sup_nombre}}</p>
                </template>
            </v-client-table>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" :class="{ mostrar: ver_modal_asignar }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <div class="modal-header">
                        <h4 class="modal-title">Asignar supervisor</h4>
                        <button type="button" class="close" @click="CerrarModalAsignar()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <vue-element-loading :active="isLoading" />
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="nombre">Proyecto</label>
                            <div class="col-md-9">
                                <input type="text" v-model="asginar_proyecto.proyecto_nombre" class="form-control disabled bg-white border-0 font-weight-bold tex-black" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="nombre">Supervisor</label>
                            <div class="col-md-9">
                                <v-select label="nombre" v-model="supervisor" :options="lstEmeplados">
                                </v-select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="CerrarModalAsignar()">
                            <i class="fas fa-window-close"></i>&nbsp;Cerrar
                        </button>
                        <button type="button" class="btn btn-secondary" @click="GuardarSupervisor()">
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal agregar documentos-->
</main>
</template>

<script>
import Utilerias from "../../Herramientas/utilerias.js";
var config = require("../../Herramientas/config-vuetables-client").call(this);

export default
{
    data()
    {
        return {
            // Tabla de proyectos
            lstProyectos: [],
            columnsProyectos: [
                "sp_id",
                "p_nombre",
                "sup_nombre",
                "sp_activo",
            ],
            optionsProyectos:
            {
                headings:
                {
                    sp_id: "Acciones",
                    sp_activo: "Estado",
                    p_nombre: "Proyecto",
                    sup_nombre: "Supervisor",
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts,
            },
            // modal
            isLoading: false,
            ver_modal_asignar: false,
            supervisor:
            {},
            lstEmeplados: [],
            // model de modal dx
            proeycto_id:0,
            id_super_proyecto:0,
            asginar_proyecto:
            {
                proyecto_nombre: "",
                proeycto_id: 0,
            },
        };
    },
    computed:
    {},
    methods:
    {
        // Obtiene todos los proyctos activos
        CargarProyectos()
        {
            axios.get("proyectos/supervisor/obtenerproyectos").then((r) =>
            {
                if (r.data.status)
                {
                    this.lstProyectos = r.data.proyectos;
                }
                else
                {
                    toastr.error("", "Error");
                    console.error(r.data);
                }
            });
        },
        AbrirModalAsignacion(row)
        {
            this.id_super_proyecto = row.sp_id;
            this.asginar_proyecto.proyecto_nombre = row.p_nombre;
            this.supervisor = {
                id: row.sup_id,
                nombre: row.sup_nombre
            };
            this.proyecto_id=row.p_id;
            axios.get("/proyectos/supervisor/obtenersupervisores").then((res) =>
            {
                if (res.data.status)
                {
                    this.lstEmeplados = res.data.empleados;
                }
                else
                {
                    toastr.error("", "Error");
                }
            });
            this.ver_modal_asignar = true;
        },
        CerrarModalAsignar()
        {
            this.ver_modal_asignar = false;
        },
        CambiarEstadoSuperv(estado, row)
        {
            axios.put("/proyectos/supervisor/cambiarestado",
                {
                    id: row.sp_id,
                    estado
                })
                .then(res =>
                {
                    if (res.data.status)
                    {
                        let ms = estado ? "activado" : "desactivado";
                        toastr.success("Supervisor " + ms + " correctamente");
                        this.CargarProyectos();
                    }
                    else
                    {
                        toastr.error("", "Error");
                    }
                })
        },
        GuardarSupervisor()
        {
            if (this.supervisor == null) return;
            if (this.supervisor.id == null)
            {
                toastr.warning("Seleccione un supervisor");
                return;
            }
            let id_super_proyecto = this.id_super_proyecto;
            axios.post("/proyectos/supervisor/guardarsuper",
            {
                id_super_proyecto,
                sp_id: this.supervisor.id,
                proy_id:this.proyecto_id,
            }).then(res =>
            {
                if (res.data.status)
                {
                    toastr.success("", "Supervisor asignado correctamente");
                    this.CerrarModalAsignar();
                    this.CargarProyectos();
                }
                else
                {
                    toastr.error("Error");
                }
            }).catch(er =>
            {
                console.error(er);
            })

        }
    },
    mounted()
    {
        this.CargarProyectos();
    },
};
</script>
