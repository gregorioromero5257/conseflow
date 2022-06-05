<template>
<div class=''>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Juntas - {{spool.no}}
            <button type='button' class='btn btn-dark float-sm-right' @click='CerrarCardJuntas'>
                <i class='fas fa-arrow-left'>&nbsp;</i>Regresar
            </button>
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalJuntas(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Juntas-->
                <div class=''>
                    <v-client-table :columns='columns_juntas' :data='list_juntas' :options='options_juntas' ref='tbl_juntas'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <!-- Actualizar -->
                                        <button type='button' @click='AbrirModalJuntas(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                        <!-- Habilitar -->
                                        <button v-if="props.row.estado==0" type='button' @click='AsignarFecha("Habilitar",props.row,"/habilitar")' class='dropdown-item'>
                                            <i class='fas fa-check'></i>&nbsp;Habilitada
                                        </button>
                                        <!-- Soldador -->
                                        <button v-if="props.row.estado==1" type='button' @click='FechaSoldada(props.row)' class='dropdown-item'>
                                            <i class='fas fa-user-check'></i>&nbsp;Soldada
                                        </button>
                                        <button v-if="props.row.estado==2" type='button' @click='AsignarFecha("Inspeccionar",props.row,"/registarinspeccion")' class='dropdown-item'>
                                            <i class='fas fa-check-double'></i>&nbsp;Registrar inspección
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template slot='fecha_habilitado' slot-scope='props'>
                            <p>{{props.row.fecha_habilitado==null?'-':props.row.fecha_habilitado}}</p>
                        </template>
                        <template slot='fecha_soldado' slot-scope='props'>
                            <p>{{props.row.fecha_soldado==null?'-':props.row.fecha_soldado}}</p>
                        </template>
                        <template slot='fecha_inspeccion' slot-scope='props'>
                            <p>{{props.row.fecha_inspeccion==null?'-':props.row.fecha_inspeccion}}</p>
                        </template>
                        <template slot='estado' slot-scope='props'>
                            <button v-if="props.row.estado==0" class="btn btn-secondary">Nuevo</button>
                            <button v-if="props.row.estado==1" class="btn btn-primary">Habilitada</button>
                            <button v-if="props.row.estado==2" class="btn btn-warning">Soldada</button>
                            <button v-if="props.row.estado==3" class="btn btn-success">Inspeccionada</button>
                        </template>

                        <!-- "fecha_habilitado",
                    "fecha_soldado", -->
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Modal Juntas -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_juntas}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_juntas'></h4>
                    <button type='button' class='close' @click='CerrarModalJuntas()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Spool</label>
                            <div class='col-md-8'>
                                <input :disabled="true" type='text' class='form-control' v-model='spool.no'>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Nombre</label>
                            <div class='col-md-3'>
                                <input type='text' data-vv-name="No. Junta" class='form-control' v-validate="'required'" v-model='juntas_modal.no'>
                                <span class="text-danger">{{errors.first("No. Junta")}}</span>
                            </div>
                            <label class='col-md-1 form-control-label'>Hoja</label>
                            <div class='col-md-3'>
                                <input type='text' name="hoja" class='form-control' v-validate="'required'" v-model='juntas_modal.hoja'>
                                <span class="text-danger">{{errors.first("hoja")}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Diametro</label>
                            <div class='col-md-3'>
                                <input type='text' name="diametro" class='form-control' v-validate="'required'" v-model='juntas_modal.diametro'>
                                <span class="text-danger">{{errors.first("diametro")}}</span>
                            </div>
                            <label class='col-md-1 form-control-label'>Espesor</label>
                            <div class='col-md-3'>
                                <input type='text' name="espesor" class='form-control' v-validate="'required'" v-model='juntas_modal.espesor'>
                                <span class="text-danger">{{errors.first("espesor")}}</span>
                            </div>
                        </div>

                        <hr class="mx-1">
                        <h4 class="mb-3">Material 1</h4>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Artículo</label>
                            <div class="col-md-8">
                                <v-select label="nombre" data-vv-name="Material 1" :options="list_materiales" v-model="juntas_modal.material_uno" v-validate="'required'" @input="ObtenerColada1"></v-select>
                                <span class="text-danger">{{errors.first("Material 1")}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Nombre</label>
                            <div class='col-md-3'>
                                <input type="text" class="form-control" data-vv-name="Nombre 1" v-model="juntas_modal.material_uno_nombre" v-validate="'required'">
                                <span class="text-danger">{{errors.first("Nombre 1")}}</span>
                            </div>

                            <label class='col-md-1 form-control-label'>Colada</label>
                            <div class='col-md-3'>
                                <vue-element-loading :active="isColadas_uno_loading" />
                                <v-select label="colada" data-vv-name="Colada 1" :options="list_coladas_uno" v-model="juntas_modal.colada_uno" v-validate="'required'"></v-select>
                                <span class="text-danger">{{errors.first("Colada 1")}}</span>
                            </div>
                        </div>

                        <hr class="mx-1">
                        <h4 class="mb-3">Material 2</h4>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Artículo</label>
                            <div class='col-md-8'>
                                <v-select label="nombre" data-vv-name="Material 2" :options="list_materiales" v-model="juntas_modal.material_dos" v-validate="'required'" @input="ObtenerColada2"></v-select>
                                <span class="text-danger">{{errors.first("Material 2")}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Nombre</label>
                            <div class='col-md-3'>
                                <input type="text" class="form-control" data-vv-name="Nombre 2" v-model="juntas_modal.material_dos_nombre" v-validate="'required'">
                                <span class="text-danger">{{errors.first("Nombre 2")}}</span>
                            </div>

                            <label class='col-md-1 form-control-label'>Colada</label>
                            <div class='col-md-3'>
                                <vue-element-loading :active="isColadas_dos_loading" />
                                <v-select :options="list_coladas_dos" data-vv-name="Colada 2" v-model="juntas_modal.colada_dos" label="colada" v-validate="'required'"></v-select>
                                <span class="text-danger">{{errors.first("Colada 2")}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Procedimiento</label>
                            <div class='col-md-9'>
                                <vue-element-loading :active="isProcedimientosLoading" />
                                <v-select data-vv-name="Procedimiento" :options="list_procedimientos" label="nombre_aux" v-model="juntas_modal.procedimiento" v-validate="'required'" @input="VerSoldadores"></v-select>
                                <span class="text-danger">{{errors.first("Procedimiento")}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Soldador</label>
                            <div class='col-md-9'>
                                <vue-element-loading :active="isSoldadoresLoading" />
                                <v-select :options="listSoldadores" name="Soldador" label="clave_nombre" v-model="juntas_modal.soldador" v-validate="'required'"></v-select>
                                <span class="text-danger">{{errors.first("Soldador")}}</span>
                            </div>
                        </div>

                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalJuntas()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_juntas== 1' class='btn btn-secondary' @click='GuardarJuntas(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_juntas==2' class='btn btn-secondary' @click='GuardarJuntas(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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
    props: ["spool"],
    data()
    {
        return {
            url: "/calidad/juntas",
            // Tabla 
            ver_modal_juntas: 0,
            columns_juntas: [
                "id",
                "nombre",
                "diametro",
                "espesor",
                "nombre_material1",
                "colada_uno",
                "nombre_material2",
                "colada_dos",
                "clave_soldador",
                "procedimiento",
                "fecha_habilitado",
                "fecha_soldado",
                "fecha_inspeccion",
                "estado",
            ],
            list_juntas: [],
            options_juntas:
            {
                headings:
                {
                    id: "Acciones",
                    nombre: "Nombre",
                    cci1_id: "Colada 1",
                    nombre_material1: "Material 1",
                    cci2_id: "Coalda 2",
                    nombre_material2: "Material 2",
                    clave_soldador: "Soldador",
                    fecha_habilitado: "Habilidata",
                    fecha_soldado: "Soldada",
                    fecha_inspeccion: "Inspeccionada"
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: [
                    "nombre",
                    "diametro",
                    "espesor",
                    "nombre_material1",
                    "colada_uno",
                    "nombre_material2",
                    "colada_dos",
                    "clave_soldador",
                    "procedimiento",
                    // "estado",
                    "fecha_habilitado",
                    "fecha_soldado",
                    "fecha_inspeccion",
                ],
                filterable: [
                    "nombre",
                    "diametro",
                    "espesor",
                    "nombre_material1",
                    "colada_uno",
                    "nombre_material2",
                    "colada_dos",
                    "clave_soldador",
                    "procedimiento",
                    // "estado",
                    "fecha_habilitado",
                    "fecha_soldado",
                    "fecha_inspeccion",
                ],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            titulo_modal_juntas: '',
            tipoAccion_modal_juntas: 0,
            juntas_modal:
            {
                material_uno:
                {},
                material_dos:
                {}
            },
            list_materiales: [],
            isObtenerJuntasLoading: false,
            iSGuardarJuntasLoading: false,
            list_coladas_uno: [],
            list_coladas_dos: [],
            list_procedimientos: [],
            listSoldadores: [],
            isColadas_dos_loading: false,
            isColadas_uno_loading: false,
            isProcedimientosLoading: false,
            isSoldadoresLoading: false,
            material1_id: 0,
            material2_id: 0,
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        ObtenerJuntas()
        {
            this.isObtenerJuntasLoading = true;
            axios.get(this.url + "/obtener/" + this.spool.id).then(res =>
            {
                this.isObtenerJuntasLoading = false;
                if (res.data.status)
                {
                    this.list_juntas = res.data.juntas;
                }
                else
                {
                    this.Error(res.data);
                }
            });
        },
        ObtenerMateriales()
        {
            this.isMaterialesLoading = true;
            axios.get(this.url + "/materiales/" + this.spool.servicio_sistema_id).then(res =>
            {
                this.isMaterialesLoading = false;
                if (res.data.status)
                {
                    this.list_materiales = res.data.materiales;
                }
                else
                {
                    this.Error(res.data);
                }
            })
        },
        ObtenerProcedimientos()
        {
            this.isProcedimientosLoading = true;
            axios.get("/calidad/procedimientos/cargarprocedimientos").then(res =>
            {
                this.isProcedimientosLoading = false;
                if (res.data.status)
                {
                    this.list_procedimientos = res.data.procedimietos;
                }
                else
                {
                    this.Error(res.data);
                }
            });
        },
        AbrirModalJuntas(nuevo, junta = {})
        {
            this.ObtenerMateriales();
            this.ObtenerProcedimientos();
            this.ver_modal_juntas = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_juntas = 'Registrar Juntas';
                this.tipoAccion_modal_juntas = 1;
            }
            else
            {
                // Actualizar
                this.juntas_modal.id = junta.id;
                // Datos de junta
                this.titulo_modal_juntas = 'Actualizar junta';
                this.tipoAccion_modal_juntas = 2;
                this.juntas_modal.no = junta.nombre;
                this.juntas_modal.diametro = junta.diametro;
                this.juntas_modal.espesor = junta.espesor;
                this.juntas_modal.hoja = junta.hoja;
                // Procedimeinto
                this.juntas_modal.procedimiento = {
                    id: junta.procedimiento_id,
                    nombre_aux: junta.nombre_aux
                };
                // Soldador
                this.juntas_modal.soldador = {
                    csp_id: junta.csp_id,
                    clave_nombre: junta.clave_nombre
                };
                // Obtener materiales
                axios.get("/calidad/juntas/materialesjunta/" + junta.id).then(res =>
                {
                    if (res.data.status)
                    {
                        // Material 1
                        this.juntas_modal.material_uno = {
                            nombre: res.data.materiales.articulo1_nombre,
                            ic_id: res.data.materiales.ic1_id
                        };
                        this.juntas_modal.material_uno_nombre = res.data.materiales.nombre_corto1; //nombre corto
                        this.material1_id = res.data.materiales.yolo1;
                        this.juntas_modal.colada_uno = {
                            c_id: res.data.materiales.colada_uno_id,
                            colada: res.data.materiales.colada1
                        };
                        // Material 2
                        this.juntas_modal.material_dos = {
                            nombre: res.data.materiales.articulo2_nombre,
                            ic_id: res.data.materiales.ic2_id
                        };
                        this.material2_id = res.data.materiales.yolo2;

                        this.juntas_modal.material_dos_nombre = res.data.materiales.nombre_corto2; //nombre corto
                        this.juntas_modal.colada_dos = {
                            c_id: res.data.materiales.colada_dos_id,
                            colada: res.data.materiales.colada2
                        };

                    }
                    else
                    {
                        this.Error(res.data);
                    }
                }).catch(r =>
                {
                    r.mensaje = "Error";
                    this.Error(r);
                })

            } // Fin if
        },

        CerrarModalJuntas()
        {
            this.ver_modal_juntas = false;
            this.juntas_modal = {
                material_uno:
                {},
                material_dos:
                {}
            };
        },
        CerrarCardJuntas()
        {
            this.$emit("CerrarCardJuntas");
        },
        GuardarJuntas()
        {
            this.$validator.validate().then(result =>
            {
                if (!result) return;
                this.iSGuardarJuntasLoading = true;
                // if (this.juntas_modal.diametro.includes('"'))
                // {
                //     toastr.error("Caracter en no permitido: \"");
                //     return;
                // }
                // if (this.juntas_modal.espesor.includes('"'))
                // {
                //     toastr.error("Caracter no permitido: \"");
                //     return;
                // }
                // Comprobar que se seleccione las coladas
                if (this.juntas_modal.colada_uno.ci_id == null)
                {
                    toastr.error("Seleccione la colada 1");
                    return;
                }
                if (this.juntas_modal.colada_dos.ci_id == null)
                {
                    toastr.error("Seleccione la colada 2");
                    return;
                }
                axios.post(this.url + "/guardar",
                {
                    id: this.juntas_modal.id,
                    nombre: this.juntas_modal.no,
                    diametro: this.juntas_modal.diametro,
                    espesor: this.juntas_modal.espesor,
                    spool_id: this.spool.id,
                    hoja: this.juntas_modal.hoja,
                    soldador_proyecto_id: this.juntas_modal.soldador.csp_id,
                    // Colada 1
                    material1_id: this.material1_id,
                    material2_id: this.material2_id,
                    nombre_material_1: this.juntas_modal.material_uno_nombre,
                    colada_1: this.juntas_modal.colada_uno.ci_id, // Id calidad colada inspeccion
                    // Colada 2
                    colada_2_id: this.juntas_modal.colada_dos.id, // Id calidad colada inspeccion
                    nombre_material_2: this.juntas_modal.material_dos_nombre,
                    colada_2: this.juntas_modal.colada_dos.ci_id
                }).then(res =>
                {
                    this.iSGuardarJuntasLoading = false;
                    if (res.data.status)
                    {
                        toastr.success("Junta guardada correctamente");
                        this.CerrarModalJuntas();
                        this.ObtenerJuntas();
                    }
                    else
                    {
                        this.Error(res.data);
                    }
                }).catch(r =>
                {
                    r.mensaje = "Error";
                    this.Error(r);
                })
            });
        },
        ObtenerColada1()
        {
            if (this.juntas_modal.material_uno == null) return;
            if (this.juntas_modal.material_uno.ic_id == null) return;
            this.isColadas_uno_loading = true;
            axios.get(this.url + "/coladas/" + this.juntas_modal.material_uno.ic_id).then(res =>
            {
                this.isColadas_uno_loading = false;
                if (res.data.status)
                {
                    this.list_coladas_uno = res.data.coladas;
                }
                else
                {
                    this.Error(res.dat);
                }
            })
        },
        ObtenerColada2()
        {
            if (this.juntas_modal.material_dos == null) return;
            if (this.juntas_modal.material_dos.ic_id == null) return;
            this.isColadas_dos_loading = true;
            axios.get(this.url + "/coladas/" + this.juntas_modal.material_dos.ic_id).then(res =>
            {
                this.list_coladas_dos = [];
                this.isColadas_dos_loading = false;
                if (res.data.status)
                {
                    this.list_coladas_dos = res.data.coladas;
                }
                else
                {
                    this.Error(res.dat);
                }
            })
        },
        VerSoldadores()
        {
            if (this.juntas_modal.procedimiento == null) return;
            if (this.juntas_modal.procedimiento.id == null) return;
            this.isSoldadoresLoading = true;
            axios.get(this.url + "/obtenersoldadores/" +
                this.spool.servicio_sistema_id + "&" +
                this.juntas_modal.procedimiento.id).then(res =>
            {
                if (res.data.status)
                {
                    this.isSoldadoresLoading = false;
                    this.listSoldadores = res.data.soldadores;
                }
                else
                {
                    this.Error(res.data);
                }
            })
        },
        AsignarFecha(tipo, junta, ruta, )
        {
            // Tipo: Habilitar, Inspeccionar, Soldar(?)
            Swal.fire(
            {
                title: tipo + ' junta' + junta.nombre,
                icon: 'info',
                html: '<input class="form-control" required type="date" id="dtpFecha">',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
            }).then(result =>
            {
                let fecha = $("#dtpFecha")[0]; // obtener input
                if (fecha.value == "") return; // fecha no seleccionada
                if (result == null) return;
                if (result.value == null) return;
                if (result.value) // Guardar fecha
                {
                    axios.post(this.url + ruta,
                    {
                        "id": junta.id,
                        "fecha": fecha.value
                    }).then(res =>
                    {
                        if (res.data.status)
                        {
                            toastr.success("Junta habilidata correctamente");
                            this.ObtenerJuntas();
                        }
                        else
                        {
                            toastr.error(res.data.mensaje);
                        }
                    }).catch(r =>
                    {
                        toastr.error("Error al guardar la fecha");
                        console.error(r);
                    })
                }
            });
        },
        FechaSoldada(junta)
        {
            Swal.mixin(
                {
                    input: "text",
                    confirmButtonText: "Siguiente &rarr;",
                    showCancelButton: true,
                    progressSteps: ["1", "2", "3"],
                })
                .queue([
                {
                    title: "Criterio de aceptación",
                    input: 'select',
                    inputOptions:
                    {
                        1: 'Aceptada',
                        0: 'Rechazada'
                    },
                },
                {
                    title: "Comentarios",
                },
                {
                    title: "Seleccione la fecha",
                    input: "",
                    html: '<input class="form-control" type="date" id="dtpFecha">',
                }]).then(result =>
                {
                    if (result.value == null) return; // Cerrado
                    let fecha = $("#dtpFecha")[0] // obtener date
                    if (fecha == null) return; // fecha no seleccionada
                    if (fecha.value == "") return; // fecha no seleccionada
                    axios.post(this.url + "/soldar",
                    {
                        "junta_id": junta.id,
                        "aceptado": result.value[0],
                        "comentarios": result.value[1],
                        "fecha": fecha.value,
                    }).then(res =>
                    {
                        if (res.data.status)
                        {
                            toastr.success("Inspección de soldadura registrada correctamente");
                            this.ObtenerJuntas();
                        }
                        else
                        {
                            toastr.error(res.data.mensaje);
                        }
                    }).catch(r =>
                    {
                        toastr.error("Error al guardar la fecha");
                        console.error(r);
                    });
                });
            return;
            // Tipo: Habilitar, Inspeccionar, Soldar(?)
            Swal.fire(
            {
                title: tipo + ' junta' + junta.nombre,
                icon: 'info',
                html: '<input class="form-control" required type="date" id="dtpFecha">',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
            }).then(result =>
            {
                let fecha = $("#dtpFecha")[0]; // obtener input
                if (fecha.value == "") return; // fecha no seleccionada
                if (result == null) return;
                if (result.value == null) return;
                if (result.value) // Guardar fecha
                {
                    axios.post(this.url + ruta,
                    {
                        "id": junta.id,
                        "fecha": fecha.value
                    }).then(res =>
                    {
                        if (res.data.status)
                        {
                            toastr.success("Junta habilidata correctamente");
                            this.ObtenerJuntas();
                        }
                        else
                        {
                            toastr.error(res.data.mensaje);
                        }
                    }).catch(r =>
                    {
                        toastr.error("Error al guardar la fecha");
                        console.error(r);
                    })
                }
            });
        },
        Error(r)
        {
            console.error(r);
            toastr.error(r.mensaje);
        },
    }, // Fin metodos
    mounted()
    {
        this.ObtenerJuntas();
    }
}
</script>
