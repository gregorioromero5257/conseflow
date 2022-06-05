<template>
<main class="main">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> ANÁLISIS DE SEGURIDAD EN EL TRABAJO (AST)

                <button type="button" @click="abrirModal(true)" class="btn btn-dark float-sm-right">
                    <i class="fas fa-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">

                <v-client-table :data="tableData" :columns="columns" :options="options">

                    <template slot="id" slot-scope="props">
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group dropup" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <a type="button" @click.prevent="abrirModal(false,props.row)" class="dropdown-item" href="#">
                                        <i class="fas fa-pencil-alt"></i>&nbsp;Actualizar analisis
                                    </a>
                                    <a type="button" @click="participantes(props.row)" class="dropdown-item" href="#">
                                        <i class="fas fa-user-friends"></i>&nbsp;Agregar Participantes
                                    </a>
                                    <a type="button" @click="eliminar(props.row.id)" class="dropdown-item" href="#">
                                        <i class="fas fa-trash"></i>&nbsp;Eliminar
                                    </a>

                                </div>
                            </div>
                        </div>

                    </template>

                    <template slot="descargar" slot-scope="props">
                        <button type="button" @click="descargar(props.row.id)" class="btn btn-outline-dark">
                            <i class="fas fa-download"></i>&nbsp;<i class="fas fa-file-pdf"></i>
                        </button>
                    </template>

                </v-client-table>

            </div>
        </div>

        <div class="modal fade" tabindex="-1" :class="{ mostrar: modal }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-lg" role="document">
                <div class="modal-content">
                    <div>
                        <div class="modal-header">
                            <h4 class="modal-title">{{tituloModal}}</h4>
                            <button type="button" class="close" @click="Cerrar()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <vue-element-loading :active="isLoading" />

                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label>Ubicación de trabajo</label>
                                    <input type="text" class="form-control" v-model="ubicacion" data-vv-name="ubicacion" v-validate="'required'">
                                    <span class="text-danger">{{errors.first('ubicacion')}}</span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Número de permiso</label>
                                    <input type="text" class="form-control" v-model="permiso" data-vv-name="permiso" v-validate="'required'">
                                    <span class="text-danger">{{errors.first('permiso')}}</span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Fecha</label>
                                    <input type="date" class="form-control" v-model="fecha" data-vv-name="fecha" v-validate="'required'">
                                    <span class="text-danger">{{errors.first('fecha')}}</span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12 mb-3">
                                    <label>Descripción del trabajo</label>
                                    <textarea class="form-control" v-model="descripcion" data-vv-name="descripcion" rows="3" cols="40" v-validate="'required'"></textarea>
                                    <span class="text-danger">{{errors.first('descripcion')}}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-10 mb-3">
                                    <label>Análisis</label>
                                    <v-select @input="ObtenerRiesgos" :options="listaCatalogo" v-model="analisis" label="nombre" data-vv-name="Análisis">
                                    </v-select>
                                    <!-- v-validate="'required'" -->
                                    <span class="text-danger">{{errors.first('Análisis')}}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-10 mb-3">
                                    <vue-element-loading :active="isLoadingRiesgos" />
                                    <label>Riesgo - Recomendación</label>
                                    <v-select :options="listRecomendaciones" v-model="recomendacion" label="riesgo_recomen" data-vv-name="Recomendación">
                                    </v-select>
                                    <!-- v-validate="'required'" -->
                                    <span class="text-danger">{{errors.first('Recomendación')}}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-7 mb-3">
                                    <label>Supervisor</label>
                                    <v-select v-model="supervisor" :options="vs_options" label="name" data-vv-as="operaciones"></v-select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label>&nbsp;</label><br>
                                    <button @click="guardarasignacion()" class="btn btn-outline-dark" name="button">Crear</button>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row">

                                <div class="form-group col-md-3">
                                    <label><b>Actividad</b></label>
                                </div>
                                <div class="form-group col-md-2">
                                    <label><b>Riesgos</b></label>
                                </div>

                                <div class="form-group col-md-3">
                                    <label><b>Medidas</b></label>
                                </div>
                                <div class="form-group col-md-2">
                                    <label><b>Supervisor</b></label>
                                </div>
                                <div class="form-group col-md-2">
                                    <label><b>ACCIONES</b></label>
                                </div>
                            </div>

                            <vue-element-loading :active="isLoadingPartidas" />
                            <div>

                                <li v-for="(analisis,index) in listado_data" class="list-group-item">
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label>{{analisis.analisis.nombre}}</label>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>{{analisis.riesgo_recomen.riesgo}}</label>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>{{analisis.riesgo_recomen.recomendacion}}</label>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>{{analisis.supervisor.name}}</label>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <a v-if="analisis.temp" @click="BorrarTemp(index)">
                                                <span class="fas fa-trash" arial-hidden="true"></span>
                                            </a>
                                            <a v-else @click="Borrar(analisis.id)">
                                                <span class="fas fa-trash" arial-hidden="true"></span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </div>
                            <hr>
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Responsable del Área (Operaciones)</label>
                                    <v-select v-validate="'required'" v-model="operaciones" :options="vs_options" label="name" data-vv-name="operaciones"></v-select>
                                    <!---->
                                    <span class="text-danger">{{errors.first('operaciones')}}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Responsable del Área (SHSO)</label>
                                    <v-select v-validate="'required'" v-model="shso" :options="vs_options" label="name" data-vv-name="shso"></v-select>
                                    <!---->
                                    <span class="text-danger">{{errors.first('shso')}}</span>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="Cerrar()">
                                <i class="fas fa-window-close"></i>&nbsp;Cerrar
                            </button>
                            <button v-show="tipoAccion == 1" type="button" class="btn btn-secondary" @click="Guardar(1)">
                                Guardar
                            </button>
                            <button v-show="tipoAccion == 2" type="button" class="btn btn-secondary" @click="Guardar(0)">
                                Actualizar
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal agregar documentos-->

        <div class="modal fade" tabindex="-1" :class="{ mostrar: modal_participantes }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-lg" role="document">
                <div class="modal-content">
                    <div>
                        <div class="modal-header">
                            <h4 class="modal-title">Participantes</h4>
                            <button type="button" class="close" @click="cerrarModalP()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <vue-element-loading :active="isLoading" />

                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Empleado</label>
                                    <v-select v-model="empleado" :options="list_empleados" label="name" data-vv-as="shso"></v-select>
                                    <!---->
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>&nbsp;</label><br>
                                    <button type="button" class="btn btn-outline-dark" @click="agregarEmpleado()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <vue-element-loading :active="isPartcipantesLoading" />
                            <div>
                                <v-client-table :data="tableDataParticipantes" :options="optionsParticipantes" :columns="columnsParticipantes">
                                    <template slot="empleado_id" slot-scope="props">
                                        <a @click="eliminarParticipante(props.row.empleado_id)">
                                            <span class="fas fa-trash" arial-hidden="true"></span>
                                        </a>
                                    </template>
                                </v-client-table>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModalP()">
                                <i class="fas fa-window-close"></i>&nbsp;Cerrar
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal agregar documentos-->

    </div>
</main>
</template>

<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default
{
    data()
    {
        return {
            tituloModal: '',
            modal: 0,
            modal_participantes: 0,
            tipoAccion: 0,
            isLoading: false,
            ///
            id: 0,
            ubicacion: '',
            permiso: '',
            descripcion: '',
            operaciones: [],
            shso: '',
            empleado: '',
            fecha: '',
            //
            listaCatalogo: [],
            catalogo: '',
            isPartcipantesLoading: false,
            analisis:
            {},
            list_empleados: [],
            listado_data: [],
            listado_id: [],
            listado_supervisor: [],
            vs_options: [],
            supervisor: '',
            ///
            isLoadingPartidas: false,
            tableData: [],
            columns: ['id', 'fecha', 'no_permiso', 'ubicacion', 'descripcion', 'descargar'],
            options:
            {
                headings:
                {
                    id: 'Acciones',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            },

            recomendacion:
            {},
            isLoadingRiesgos: false,
            listRecomendaciones: [],
            tableDataParticipantes: [],
            columnsParticipantes: ['empleado_id', 'empleado'],
            optionsParticipantes:
            {
                headings:
                {
                    'empleado_id': 'ACCIONES'
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            },
        }
    },
    methods:
    {
        /** 
         * Obtiene los ananlisis de seguridad registrados
         */
        getDataInicial()
        {
            axios.get('/seguridad/nuevoanalisis/obtener').then(res =>
            {
                if (res.data.status)
                {

                    this.tableData = res.data.analisis;
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

        /**
         * Obtiene todos las secuencias de seguridad registrados 
         */
        ObtenerSecuencias()
        {
            this.listaCatalogo = [];
            axios.get('/seguridad/nuevoanalisis/obtenersecuencias').then(res =>
            {
                if (res.data.status)
                {
                    this.listaCatalogo = res.data.secuencias;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            }).catch(e =>
            {
                console.error(e);
            });

            axios.get('vertodosempleados').then(response =>
            {
                response.data.forEach((item, i) =>
                {
                    this.vs_options.push(
                    {
                        id: item.id,
                        name: item.nombre + ' ' + item.ap_paterno + ' ' + item.ap_materno,
                    });
                });

            }).catch(e =>
            {
                console.error(e);
            });
        },

        /**
         * Obtiene los reisgos y recomendaciones del anlisis ingresado
         */
        ObtenerRiesgos()
        {
            if (this.analisis == null) return;
            if (this.analisis.id == null) return;
            this.isLoadingRiesgos = true;
            axios.get("/seguridad/nuevoanalisis/obtenerrecomedaciones/" + this.analisis.id).then(res =>
            {
                8
                if (res.data.status)
                {
                    this.listRecomendaciones = res.data.recomendaciones;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
                this.isLoadingRiesgos = false;
            });
        },

        abrirModal(nuevo, data = [])
        {
            this.ObtenerSecuencias();

            if (nuevo)
            {
                this.modal = 1;
                this.tituloModal = 'Registrar análisis';
                this.tipoAccion = 1;
                this.id = null;
                this.listado_data = [];
                this.listado_id = [];
                this.listado_supervisor = [];
            }
            else
            {

                this.id = data['id'];
                this.ObtenerPartidas(); // Carga las partidas del analisis
                // Mostrar datos del model
                this.operaciones = {
                    id: data['id_empleado_operaciones'],
                    name: data['operaciones_nombre']
                };
                this.shso = {
                    id: data['id_empleado_sho'],
                    name: data['seguridad_nombre']
                };
                this.ubicacion = data['ubicacion'];
                this.permiso = data['no_permiso'];
                this.descripcion = data['descripcion'];
                this.fecha = data['fecha'];
                this.modal = 1;
                this.tituloModal = 'Actualizar análisis';
                this.tipoAccion = 2;

            }
        },

        /**
         * Obtener partidas del analisis actual
         */
        ObtenerPartidas()
        {
            this.isLoadingPartidas = true;
            axios.get("/seguridad/nuevoanalisis/obtenerpartidas/" + this.id).then(res =>
            {
                if (res.data.status)
                {
                    let aux_listado_data = [];
                    res.data.partidas.forEach(p =>
                    {
                        aux_listado_data.push(
                        {
                            id: p.id,
                            analisis:
                            {
                                id: p.secuencia_id,
                                nombre: p.secuencia
                            },
                            riesgo_recomen:
                            {
                                riesgo_id: p.riesgo_id,
                                riesgo: p.riesgo,
                                recomendacion: p.recomendacion,
                                recomendacion_id: p.recomendacion_id
                            },
                            supervisor:
                            {
                                id: p.supervisor_id,
                                name: p.supervisor_nombre
                            }
                        });
                    });

                    this.listado_data = aux_listado_data;
                    // Cargar partidas actuales
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
                this.isLoadingPartidas = false;
            });
        },

        Cerrar()
        {
            this.modal = false; // Cerrar modal de registo de analisis
            this.listado_data = [];
            this.listado_id = [];
            this.listado_supervisor = [];
        },

        /**
         * Guarda de manera temporal el analisis seleccionado
         */
        guardarasignacion()
        {
            if (this.analisis == null) return;
            if (this.recomendacion == null) return;
            if (this.supervisor == null) return;

            // Comprobar registro duplicado
            let analisis_aux = this.recomendacion;
            // if (this.listado_data.find(a => a.actividad == this.analisis.secuencia))
            if (false)
            {
                toastr.warning('Ya seleccionado');
            }
            else
            {
                this.listado_data.push(
                {
                    analisis: this.analisis,
                    riesgo_recomen: this.recomendacion,
                    supervisor: this.supervisor,
                    temp: true,
                });

            }
            this.analisis = {};
            this.recomendacion = {};
            this.supervisor = {};
        },

        /**
         * Elimina el riesgo actual de la lista de temporales
         */
        BorrarTemp(index)
        {
            this.listado_data.splice(index, 1);
            this.listado_id.splice(index, 1);
            this.listado_supervisor.splice(index, 1);
        },

        /**
         * Elimina el registro actual de la db
         */
        Borrar(id)
        {
            axios.post("seguridad/nuevoanalisis/eliminarpartida/",
            {
                id
            }).then(res =>
            {
                if (res.data.status)
                {
                    toastr.success("Eliminado correctamente");
                    this.ObtenerPartidas();
                }
            });
        },

        /**
         * Registra los analisis-riesgo-recomendacion seleccionados
         */
        Guardar(nuevo)
        {
            this.$validator.validate().then(result =>
            {

                // if (!result) return;
                let data = new FormData();
                if (!nuevo)
                    data.append("id", this.id);
                // Obter riesgos temporales
                data.append("ubicacion", this.ubicacion);
                data.append("no_permiso", this.permiso);
                data.append("descripcion", this.descripcion);
                data.append("id_empleado_operaciones", this.operaciones.id);
                data.append("id_empleado_sho", this.shso.id);
                data.append("fecha", this.fecha);

                let temporales = this.listado_data.filter(a => a.temp);
                let list_temporales = "";
                temporales.forEach(t =>
                {
                    list_temporales +=
                        t.analisis.id + "|" +
                        t.riesgo_recomen.riesgo_id + "|" +
                        t.riesgo_recomen.recomendacion_id + "|" +
                        t.supervisor.id + "&"
                });
                data.append("ids", list_temporales);

                axios.post('seguridad/nuevoanalisis/guardar', data).then(res =>
                {
                    if (res.data.status)
                    {
                        this.getDataInicial();
                        this.Vaciar();
                        toastr.success("Registrado correctamente");
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                }).catch(e =>
                {
                    console.error(e);
                });
            });
        },

        /**
         * Cerrar modal  y limpiar datos del analisis
         */
        Vaciar()
        {
            this.id = null;
            this.ubicacion = '';
            this.permiso = '';
            this.descripcion = '';
            this.operaciones = '';
            this.shso = '';
            this.listado_data = [];
            this.listado_id = [];
            this.modal = 0;
            this.fecha = '';
        },

        VaciarDos()
        {
            this.id = 0;
            this.ubicacion = '';
            this.permiso = '';
            this.descripcion = '';
            this.operaciones = '';
            this.shso = '';
            this.fecha = '';
            this.listado_data = [];
            this.listado_id = [];
            this.listaCatalogo = [];
        },

        participantes(analisis)
        {
            this.ObtenerEmpleados();
            this.analisis_id = analisis.id;
            this.modal_participantes = true;
            this.getParticipantes(analisis.id);
        },

        /**
         * Carga todos los empleados para asignar participantes
         */
        ObtenerEmpleados()
        {
            axios.get("vertodosempleados").then(res =>
            {
                this.list_empleados = [];
                if (res.status)
                {
                    res.data.forEach(item =>
                    {
                        this.list_empleados.push(
                        {
                            id: item.id,
                            name: item.nombre + ' ' + item.ap_paterno + ' ' + item.ap_materno,
                        });
                    });
                }
                else
                {
                    toastr.error("Error al obtener los empleados");
                }
            })
        },
        /**
         * Obtiene los participantes del analisis actual
         */
        getParticipantes(id)
        {
            axios.get("seguridad/nuevoanalisis/participantes/obtener/" + id).then(res =>
            {
                if (res.data.status)
                {
                    this.tableDataParticipantes = res.data.participantes;
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

        cerrarModalP()
        {
            this.tableDataParticipantes = [];
            this.modal_participantes = false;
        },

        agregarEmpleado()
        {
            if (this.empleado == null) return;
            if (this.empleado.id == null) return;
            this.isPartcipantesLoading = true;
            axios.post('seguridad/nuevoanalisis/participantes/guardar',
            {
                id: this.analisis_id,
                empleado_id: this.empleado.id,
                analisis_id: this.analisis_id
            }).then(res =>
            {
                this.isPartcipantesLoading = false;
                if (res.data.status)
                {
                    toastr.success("Registrado correctamente");
                    this.getParticipantes(this.analisis_id);
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
                this.getParticipantes(this.analisis_id);
            }).catch(error =>
            {
                console.error(error);
            })
        },

        eliminarParticipante(empleado_id)
        {
            this.isPartcipantesLoading = true;
            let data = new FormData();
            data.append("empleado_id", empleado_id);
            data.append("analisis_id", this.analisis_id);
            axios.post("seguridad/nuevoanalisis/participantes/eliminar", data).then(res =>
            {
                this.isPartcipantesLoading = false;
                if (res.data.status)
                {
                    this.getParticipantes(this.analisis_id);
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

        descargar(data)
        {
            window.open('seguridad/nuevoanalisis/descargar/' + data, '_blank');
        },

        /**
         * Elimina un analisis de seguirdad
         */
        eliminar(id)
        {
            Swal.fire(
            {
                title: '¿Desea eliminar el análisis?',
                text: "Esta opción no se podrá desahacer",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'No',
            }).then(result =>
            {
                if (result.value)
                {
                    axios.post("seguridad/nuevoanalisis/eliminar",
                    {
                        id
                    }).then(res =>
                    {
                        if (res.data.status)
                        {
                            toastr.success('Eliminado correctamente');
                            this.getDataInicial();
                        }
                        else
                        {
                            toastr.error(res.data.mensaje);
                        }
                    }).catch(e =>
                    {
                        console.error(e);
                    });
                }
            })
        },

    },
    mounted()
    {
        this.getDataInicial();
    }
}
</script>
