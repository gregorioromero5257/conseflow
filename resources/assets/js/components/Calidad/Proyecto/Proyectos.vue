<template>
<div class='main'>

    <!-- Card Proyectos-->
    <div class='card' v-if="tipoCard==1">
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Proyectos
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalProyectos(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Proyectos-->
                <div class=''>
                    <v-client-table :columns='columns_proyectos' :data='list_proyectos' :options='options_proyectos' ref='tbl_proyectos'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <button type='button' @click='AbrirModalProyectos(false, props.row)' class='dropdown-item'>
                                        <i class='icon-pencil'></i>&nbsp;Actualizar
                                    </button>
                                    <button type='button' @click='AbrirCardSistemas(props.row)' class='dropdown-item'>
                                        <i class='fas fa-eye'></i>&nbsp;Sistemas
                                    </button>
                                    <button type='button' @click='AbrirCardSoldadores(props.row)' class='dropdown-item'>
                                        <i class='fas fa-users'></i>&nbsp;Soldadores
                                    </button>
                                </div>
                            </div>
                        </template>
                        <template slot='logo_cliente' slot-scope='props'>
                            <img height="80" v-if="props.row.logo_cliente!=''" :src="'temp/'+props.row.logo_cliente" alt="">
                            <p v-else>-</p>
                        </template>
                        <template slot='estado' slot-scope='props'>
                            <template v-if="props.row.estado==1">
                                <button class="btn btn-success">Activo</button>
                            </template>
                            <template v-else>
                                <button class="btn btn-warning">Inactivo</button>
                            </template>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Card Sistemas -->
    <div class='card' v-if="tipoCard==2">
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Sistemas de {{aux_proyecto.nombre}}
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalSistemas(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
            <button type='button' class='btn btn-dark float-sm-right' @click='Regresar()'>
                <i class='fas fa-arrow-left'>&nbsp;</i>Atrás
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Sistemas-->
                <div class=''>
                    <v-client-table :columns='columns_sistemas_servicios' :data='list_sistemas_servicios' :options='options_sistemas_servicios'>
                        <template slot='sds_id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <button type='button' @click='AbrirModalSistemas(false, props.row)' class='dropdown-item'>
                                        <i class='icon-pencil'></i>&nbsp;Actualizar
                                    </button>
                                    <button type='button' @click='AbrirCardSpools(props.row)' class='dropdown-item'>
                                        <i class='fas fa-th-list'></i>&nbsp;Spools
                                    </button>
                                </div>
                            </div>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Card Soldadores -->
    <div v-if="tipoCard==3">
        <soldadores-proyecto @cerrarCard="CerrarCardSoldadores" ref="soldadores_proyecto" :servicio_sistema="card_soldadores"></soldadores-proyecto>
    </div>

    <!-- Card Spools -->
    <div v-if="tipoCard==4">
        <spools @cerrarCardSpools="CerrarCardSpools" ref="spools" :servicio_sistema="card_spools"></spools>
    </div>

    <!-- Modal Proyectos -->
    <div class='modal fade' v-if="ver_modal_proyectos" tabindex='-1' :class="{'mostrar' : ver_modal_proyectos}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_proyectos'></h4>
                    <button type='button' class='close' @click='CerrarModalProyectos()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <vue-element-loading :active="isdatosLoading" />
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Proyecto</label>
                            <div class='col-md-9'>
                                <v-select :disabled="!modificar" :options="list_sistema_proyectos" id="proyecto" name="proyecto" v-validate="'required'" v-model="proyectos_modal.proyecto" label="nombre"></v-select>
                                <span class="text-danger">{{ errors.first('proyectos_modal.proyecto') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Nombre</label>
                            <div class='col-md-9'>
                                <input type='text' id="nombre" name="nombre" class='form-control' v-model='proyectos_modal.nombre'>
                                <span class="text-danger">{{ errors.first('nombre') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Logo cliente</label>
                            <div class='col-md-9'>
                                <input type='file' id="logo_cliente" name="logo_cliente" class='form-control'>
                                <span class="text-danger">{{ errors.first('proyectos_modal.logo_cliente') }}</span>
                            </div>
                        </div>

                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalProyectos()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_proyectos== 1' class='btn btn-secondary' @click='GuardarProyectos(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_proyectos==2' class='btn btn-secondary' @click='GuardarProyectos(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Modal Sistemas -->
    <div class='modal fade' v-if="ver_modal_sistemas_servicios" tabindex='-1' :class="{'mostrar' : ver_modal_sistemas_servicios}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_sistemas_servicios'></h4>
                    <button type='button' class='close' @click='CerrarModalSistemas()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Proyecto</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control disabled bg-white text-black font-weight-bold border border-0' v-model='sistemas_modal.proyecto_nombre'>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Nombre</label>
                            <div class='col-md-9'>
                                <v-select @input="RegistrarSistema" :options="list_sistemas" label="nombre" data-vv-name="sistema" v-validate="'required'" class='' v-model='serv_sis_modal.sistema'> </v-select>
                                <span class="text-danger">{{ errors.first('nombre') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Servicio</label>
                            <div class='col-md-9'>
                                <v-select data-vv-name="servicio" :options="list_servicios" label="nombre" v-validate="'required'" v-model='serv_sis_modal.servicio'></v-select>
                                <span class="text-danger">{{ errors.first('servicio') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>No. de plano</label>
                            <div class='col-md-9'>
                                <input type="text" class="form-control" data-vv-name="plano" v-validate="'required'" v-model='serv_sis_modal.plano' />
                                <span class="text-danger">{{ errors.first('plano') }}</span>
                            </div>
                        </div>

                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalSistemas()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_sistemas_servicios== 1' class='btn btn-secondary' @click='GuardarServicioSistema(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_sistemas_servicios==2' class='btn btn-secondary' @click='GuardarServicioSistema(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->

</div> <!-- Main -->
</template>

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const SoldadoresProyecto = r => require.ensure([], () => r(require('./SoldadoresProyecto.vue')), 'calidad');
const Spools = r => require.ensure([], () => r(require('./Spools.vue')), 'calidad');
export default
{
    components:
    {
        "soldadores-proyecto": SoldadoresProyecto,
        "spools": Spools
    },
    data()
    {
        return {
            // Tabla 
            url: '/calidad/proyectos',
            url_sistemas: '/calidad/sistemas',
            // Card 1. Proyectos
            modificar: true,
            ver_modal_proyectos: 0,
            columns_proyectos: ['id', 'nombre', 'nombre_proyecto', 'estado', 'logo_cliente'],
            list_proyectos: [],
            list_sistema_proyectos: [],
            options_proyectos:
            {
                headings:
                {
                    id: 'Acciones',
                    nombre_proyecto: "Proyecto",
                    nombre: 'Nombre',
                    logo_cliente: "Logo cliente",
                    estado: "Estado",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['id', 'nombre'],
                filterable: ['id', 'nombre'],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            isdatosLoading: false,
            titulo_modal_proyectos: '',
            tipoAccion_modal_proyectos: 0,
            proyecto_id: 0,
            proyectos_modal:
            {
                proyecto:
                {},
            },
            // Card 2. Sistemas
            list_sistemas: [],
            list_servicios: [],
            tipoCard: 1,
            sistema_id: 0,
            aux_proyecto:
            {
                id: 0,
                proyecto_id: 0,
                nombre: ""
            },
            ver_modal_sistemas_servicios: 0,
            columns_sistemas_servicios: ['sds_id', 's_nombre', 'serv_nombre', 's_material', 'plano', 'tag'],
            list_sistemas_servicios: [],
            options_sistemas_servicios:
            {
                headings:
                {
                    sds_id: 'Acciones',
                    s_nombre: 'Sistema',
                    serv_nombre: 'Servicio',
                    s_material: 'Espec. de material',
                    plano: 'No. Plano',
                    tag: 'TAG'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['sds_id', 's_nombre', 'serv_nombre', 'no'],
                filterable: ['sds_id', 's_nombre', 'serv_nombre', 'no'],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            titulo_modal_sistemas_servicios: '',
            tipoAccion_modal_sistemas_servicios: 0,
            sistemas_modal:
            {},
            serv_sis_modal_id: 0,
            serv_sis_modal:
            {
                sistema:
                {
                    id: 0,
                    nombre: "",
                },
                servicio:
                {
                    id: 0,
                    nombre: "",
                },
                plano: "",
                s_material: "",
            },
            nombre_proyecto: "",
            card_soldadores:
            {
                proyecto:
                {}
            },
            card_spools:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        // Card Proyectos
        CargarProyectos()
        {
            axios.get(this.url + "/proyectosimg").then(res =>
            {
                if (res.data.status)
                {
                    this.list_proyectos = res.data.proyectos;
                }
                else
                {
                    this.Error(res.data);
                }
            })
        },
        // Abre modal de registro/actualización de proyectos
        AbrirModalProyectos(nuevo, model = [])
        {
            // Carga todos los proyectos existentes
            this.CargarTodosProyectos();
            this.ver_modal_proyectos = 1;
            if (nuevo)
            {
                this.modificar = true;
                // Crear nuevo
                this.titulo_modal_proyectos = 'Registrar Proyectos';
                this.tipoAccion_modal_proyectos = 1;
                this.proyecto_id = null;
            }
            else
            {
                this.modificar = false;
                // Actualizar
                this.proyecto_id = model.id;
                this.titulo_modal_proyectos = 'Actualizar Proyectos';
                this.tipoAccion_modal_proyectos = 2;
                this.proyectos_modal.proyecto = {
                    id: model.id,
                    nombre: model.nombre_proyecto
                };
                this.proyectos_modal.nombre = model.nombre;
                this.proyectos_modal.logo_cliente = model.logo_cliente;
            } // Fin if
        },
        CerrarModalProyectos()
        {
            this.list_sistema_proyectos = [];
            this.ver_modal_proyectos = false;
            Utilerias.resetObject(this.proyectos_modal);
        },
        // Carga todos los proyectos del sistema activos
        CargarTodosProyectos()
        {
            this.isdatosLoading = true;
            axios.get(this.url + "/proyectos_sis").then(r =>
            {
                this.isdatosLoading = false;
                if (r.data.status)
                {
                    this.list_sistema_proyectos = r.data.proyectos;
                }
                else
                {
                    this.Error(res.data);
                }
            }).catch(er =>
            {
                this.isdatosLoading = false;
                this.Error(er);
            });
        },
        GuardarProyectos(nuevo)
        {
            if (this.proyectos_modal.proyecto == null) return;
            if (this.proyectos_modal.proyecto.id == null)
            {
                toastr.warning("Seleccione un proyecto");
                return;
            }
            this.isdatosLoading = true;
            let ms = nuevo ? "registrado" : "actualizado";
            let data = new FormData();
            // Obtenr imagenes
            let img = $("#logo_cliente").prop("files");
            if (img[0] != null) // Si hay imagen se agrega
                data.append("logo_cliente", img[0]);
            data.append("id", this.proyecto_id);
            data.append("nombre", this.proyectos_modal.nombre);
            axios.post(this.url + "/guardarproyecto", data).then(res =>
            {
                this.isdatosLoading = false;
                if (res.data.status)
                {
                    toastr.success("Proyecto " + ms + " correctamente");
                    this.CerrarModalProyectos();
                    this.CargarProyectos();
                }
                else
                {
                    this.Error(res.data);
                }
            })
        },
        /****** Card sistemas ******/
        /**
         * Cambia el card principa a servicios de sistema
         */
        AbrirCardSistemas(data)
        {
            this.tipoCard = 2;
            this.aux_proyecto = {
                id: data.id,
                proyecto_id: data.proyecto_id,
                nombre: data.nombre
            };
            this.CargarServiciosSistemas();
        },
        /**
         * Muestra el modal para el registro/actualización de un servicio de sistema
         */
        AbrirModalSistemas(nuevo, data)
        {
            console.error(this.ver_modal_sistemas_servicios);
            console.error("abierto");
            this.ver_modal_sistemas_servicios = true;
            console.error(this.ver_modal_sistemas_servicios);
            this.sistemas_modal.proyecto_nombre = this.aux_proyecto.nombre;
            this.CargarServicios();
            this.CargarSistemas();

            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_sistemas_servicios = 'Registrar sistemas';
                this.tipoAccion_modal_sistemas_servicios = 1;
            }
            else
            {
                this.serv_sis_modal_id = data.sds_id;
                this.serv_sis_modal = {
                    sistema:
                    {
                        id: data.s_id,
                        nombre: data.s_nombre
                    },
                    servicio:
                    {
                        id: data.serv_id,
                        nombre: data.serv_nombre
                    },
                    plano: data.plano,
                    s_material: data.s_material
                };
                this.titulo_modal_sistemas_servicios = 'Actualizar Sistemas';
                this.tipoAccion_modal_sistemas_servicios = 2;
            }
        },
        /**
         * Carga todos los servicios registrados
         */
        CargarServicios()
        {
            axios.get("/calidad/servicios/cargar").then(res =>
            {
                if (res.data.status)
                {
                    this.list_servicios = res.data.servicios;
                }
                else
                {
                    this.Error("Error");
                }
            }).catch(r =>
            {
                this.Error(res.data);
            });

        },
        /**
         * Registra un nuevo sistema para el proyecto actual
         */
        GuardarSistemas(nuevo)
        {
            this.$validator.validate().then(res =>
            {
                if (!res) return;
                let ms = nuevo ? " guardado " : " actualizado ";
                axios.post(this.url_sistemas + "/guardar",
                    {
                        id: this.serv_sis_modal_id,
                        nuevo,
                        proyecto_id: this.aux_proyecto.id,
                        espeificaciones_material_id: 1,
                        nombre: this.sistemas_modal.nombre
                    })
                    .then(r =>
                    {
                        if (r.data.status)
                        {
                            this.CerrarModalSistemas();
                            this.CargarSistemas();
                            toastr.success("Sistema" + ms + "correctamente");
                        }
                        else
                        {
                            this.Error(r.data);
                        }
                    });
            });
        },
        /**
         * Cierra el modal de registro/actualización de servicios de sistema
         */
        CerrarModalSistemas()
        {
            this.ver_modal_sistemas_servicios = false;
            this.serv_sis_modal = {
                sistema:
                {
                    id: 0,
                    nombre: ""
                },
                servicio:
                {
                    id: 0,
                    nombre: ""
                },
                plano: "",
                s_material: ""
            };
        },
        /**
         * Obtiene los sistemas del proeycto actual
         */
        CargarSistemas()
        {
            axios.get(this.url_sistemas + "/obtener/" + this.aux_proyecto.id).then(res =>
            {
                if (res.data.status)
                {
                    this.list_sistemas = res.data.sistemas;
                    this.list_sistemas.push(
                    {
                        id: -1,
                        nombre: "Registrar nuevo"
                    });
                }
                else
                {
                    this.Error(res.data);
                }
            }).catch(r =>
            {
                this.Error(r);
            });
        },
        RegistrarSistema()
        {
            if (this.serv_sis_modal.sistema == null) return;
            if (this.serv_sis_modal.sistema.id == -1)
            {
                Swal.mixin(
                    {
                        input: "text",
                        confirmButtonText: "Siguiente &rarr;",
                        showCancelButton: true,
                        progressSteps: ["1"],
                    })
                    .queue([
                    {
                        title: "Sistema",
                        text: "Ingrese el nombre del sistema",
                    },
                    {
                        title: "",
                        text: "Ingrese la especificación de material",
                        input: 'text',
                    },
                    {
                        title: "",
                        text: "Ingrese el TAG del sistema",
                        input: 'text',
                    }, ])
                    .then((result) =>
                    {
                        if (result.value)
                        {
                            if (result.value == null) return;
                            if (result.value != "")
                            {
                                axios.post(this.url_sistemas + "/guardar",
                                {
                                    nombre: result.value[0],
                                    proyecto_id: this.aux_proyecto.id,
                                    espeificaciones_material: result.value[1],
                                    tag: result.value[2]
                                }).then(res =>
                                {
                                    if (res.data.status)
                                    {
                                        toastr.success("Sistema registrado");
                                        this.CargarSistemas();
                                    }
                                    else
                                    {
                                        this.Error(res.data);
                                    }
                                })
                            }
                        }
                    });
            }
        },
        /**
         * Asigna el servicio al sistema seleccionado
         */
        GuardarServicioSistema(nuevo)
        {
            this.$validator.validate().then(res =>
            {
                if (!res) return;
                if (this.serv_sis_modal.sistema.id == -1)
                {
                    toastr.warning("Ingresa un sistema");
                    return;
                }
                let ms = nuevo ? " registrado " : " actualizado";
                axios.post("calidad/servsis/asignarservicio",
                {
                    nuevo,
                    id: this.serv_sis_modal_id,
                    sistema_id: this.serv_sis_modal.sistema.id,
                    servicio_id: this.serv_sis_modal.servicio.id,
                    plano: this.serv_sis_modal.plano,
                    tag: this.serv_sis_modal.tag,
                }).then(res =>
                {
                    if (res.data.status)
                    {
                        toastr.success("Sistema" + ms + "correctamente");
                        this.CerrarModalSistemas();
                        this.CargarServiciosSistemas();
                    }
                    else
                    {
                        this.Error(res.data);
                    }
                }).catch(res =>
                {
                    this.Error(res);
                });
            });
        },
        /**
         * Obtiene los sistemas y servicio del proyecto actual
         */
        CargarServiciosSistemas()
        {
            axios.get("/calidad/servsis/cargar/" + this.aux_proyecto.id).then(res =>
            {
                if (res.data.status)
                {
                    this.list_sistemas_servicios = res.data.servicios_sistemas;
                }
                else
                {
                    this.Error(res.data);
                }
            }).catch(res =>
            {
                this.Error(res);
            })
        },
        AbrirCardSoldadores(proyecto)
        {
            this.card_soldadores = {
                proyecto
            };
            this.nombre_proyecto = proyecto.nombre;

            this.tipoCard = 3;
        },
        AbrirCardSpools(servicio_sistema)
        {
            console.error("Arbir spools");
            servicio_sistema.proyecto_id = this.aux_proyecto.proyecto_id;
            servicio_sistema.proyecto_nombre = this.aux_proyecto.nombre;
            this.card_spools = servicio_sistema;
            this.tipoCard = 4;
        },
        CerrarCardSoldadores()
        {
            this.tipoCard = 1;
        },
        CerrarCardSistemas()
        {
            this.tipoCard = 1;
        },
        CerrarCardSpools()
        {
            this.tipoCard = 2;
        },
        Regresar()
        {
            this.tipoCard = 1;
            this.list_sistemas = [];
        },
        Error(data)
        {
            console.error(data);
            toastr.error("Error");
        }
    }, // Fin metodos
    mounted()
    {
        this.CargarProyectos();
    }
}
</script>
