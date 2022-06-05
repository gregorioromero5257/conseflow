<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card' v-if="!ver_modal_trazabilidad">
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Trazabilidad
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalTrazabilidad(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class="row">
                <label class="col-md-1" for="">Proyecto</label>
                <v-select class="col-md-6" :options="listProyectos" label="nombre" v-model="trazabilidad_modal.proyecto"></v-select>
                <button class="btn btn-dark btn-sm" @click="Buscar">
                    <i class="fas fa-search mr-1"></i>Buscar
                </button>
            </div>
            <br>
            <div class=''>
                <!-- Tabla de Trazabilidad-->
                <div class=''>
                    <v-client-table :columns='columns_trazabilidad' :data='list_trazabilidad' :options='options_trazabilidad' ref='tbl_trazabilidad'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button if='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalTrazabilidad(false, props.row)' class='dropdown-item'>
                                            <i class='fas fa-edit'></i>&nbsp;Actualizar
                                        </button>
                                        <button type='button' @click='Descargar(props.row)' class='dropdown-item'>
                                            <i class='fas fa-file-pdf'></i>&nbsp;Descargar reporte
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

    <!-- Modal Trazabilidad -->
    <div class='card' v-if="ver_modal_trazabilidad">
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Trazabilidad
            <button type='button' class='btn btn-dark float-sm-right' @click='CerrarModalTrazabilidad()'>
                <i class='fas fa-arrow-left'>&nbsp;</i> Regresar
            </button>
        </div>
        <div class='card-body'>
            <div class='form-horizontal'>

                <div class='form-group row'>
                    <label class='col-md-2 form-control-label'>Folio</label>
                    <div class='col-md-3'>
                        <input type='text' data-vv-name="folio" v-validate="'required'" class='form-control' v-model='trazabilidad_modal.folio'>
                        <span class="text-danger">{{errors.first("folio")}}</span>
                    </div>
                </div>

                <div class='form-group row'>
                    <label class='col-md-2 form-control-label'>Proyecto</label>
                    <div class='col-md-6'>
                        <v-select :disabled="!(inspeccion_id==null)" :options="listProyectos" label="nombre" data-vv-name="Proyecto" v-model="trazabilidad_modal.proyecto" v-validate="'required'" @input="ObtenerSistemas"></v-select>
                        <span class="text-danger">{{errors.first("Proyecto")}}</span>
                    </div>
                </div>

                <div class='form-group row'>
                    <label class='col-md-2 form-control-label'>Elemento</label>
                    <div class='col-md-6'>
                        <vue-element-loading :active="isSistemasLoading" />
                        <v-select :disabled="!(inspeccion_id==null)" :options="listSistemas" label="nombre" v-validate="'required'" data-vv-name="Elemento" v-model="trazabilidad_modal.sistema"></v-select>
                        <span class="text-danger">{{errors.first("Elemento")}}</span>
                    </div>
                </div>

                <div class='form-group row'>
                    <label class='col-md-2 form-control-label'>Identificación</label>
                    <div class='col-md-6'>
                        <input type='text' data-vv-name="Identificacion" v-validate="'required'" class='form-control' v-model='trazabilidad_modal.identificacion'>
                        <span class="text-danger">{{errors.first("Identificacion")}}</span>
                    </div>
                </div>

                <div v-if="inspeccion_id!=null">
                    <br>
                    <hr class="mx-5 mt-2">
                    <div class="form-inline">
                        <p class="font-weight-bold h5">Nomenclatura</p>
                        <button class="btn btn-sm btn-dark ml-2" @click="AbrirModalNomeclatura">
                            <i class="fas fa-plus"></i> Añadir
                        </button>
                    </div>
                    <br>
                    <div class="container col-sm-6 ml-0">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Clave</th>
                                    <th scope="col">Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(nom,i) in list_nomenclaturas">
                                    <th scope="row">{{i+1}}</th>
                                    <td>{{nom.clave}}</td>
                                    <td>{{nom.nombre}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="font-weight-bold h5"></p>
                    <br>
                </div>
            </div>
            <div class=''>
                <button type='button' v-if='tipoAccion_modal_trazabilidad== 1' class='btn btn-secondary' @click='GuardarTrazabilidad(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                <button type='button' v-if='tipoAccion_modal_trazabilidad==2' class='btn btn-secondary' @click='GuardarTrazabilidad(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
            </div>
        </div>
    </div>

    <!-- Modal nomemclaturas -->
    <div v-if="ver_modal_nomenclatura" class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_nomenclatura}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Registrar nomenclatura</h4>
                    <button type='button' class='close' @click='CerrarModalNomeclatura' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Nomenclatura</label>
                            <div class='col'>
                                <v-select v-validate="'required'" data-vv-name="nomenclatura" v-model="nomenclatura" :options="list_nomens" label="aux_nombre"></v-select>
                                <span class="text-danger">{{errors.first("nomenclatura")}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalNomeclatura()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' class='btn btn-secondary' @click='GuardarNomenclatura(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
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
            url: "/calidad/trazab",
            // Tabla 
            ver_modal_trazabilidad: 0,
            columns_trazabilidad: ['id', 'nombre', 'identificacion', 'folio'],
            list_trazabilidad: [],
            options_trazabilidad:
            {
                headings:
                {
                    id: 'Acciones',
                    identificacion: 'Identificacion',
                    folio: 'Folio',
                    nombre: "Elemento"
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['id', 'nombre', 'identificacion', 'folio'],
                filterable: ['id', 'nombre', 'identificacion', 'folio'],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            titulo_modal_trazabilidad: '',
            tipoAccion_modal_trazabilidad: 0,
            trazabilidad_modal:
            {
                proyecto:
                {},
                sistema:
                {},
            },
            isSistemasLoading: false,
            id: 0,
            listProyectos: [],
            listSistemas: [],
            list_nomens: [],

            // modal nommenclatura
            inspeccion_id: null,
            list_nomenclaturas: [],
            ver_modal_nomenclatura: false,
            isGuardarNomenclaturaLoading: false,
            modal_nomencaltura:
            {},
            nomenclatura:
            {},
            isObtenerNomensLoading: false,
        } // return
    }, //data
    computed:
    {},
    methods:
    {

        /**Obtiene los proyectos de calididad */
        CargarProyectos()
        {
            axios.get("/calidad/proyectos/proyectos").then(res =>
            {
                this.listProyectos = res.data.proyectos;
            });
        },

        /**Abre el modal para registro de trazabilidad */
        AbrirModalTrazabilidad(nuevo, model = [])
        {
            this.ver_modal_trazabilidad = true;
            if (nuevo)
            {
                // Crear nuevo
                this.inspeccion_id = null;
                this.titulo_modal_trazabilidad = 'Registrar Reporte de Trazabilidad';
                this.tipoAccion_modal_trazabilidad = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_trazabilidad = 'Actualizar Reporte de Trazabilidad';
                this.tipoAccion_modal_trazabilidad = 2;
                this.inspeccion_id = model.id;
                this.trazabilidad_modal = {
                    proyecto:
                    {
                        id: model.cp_id,
                        nombre: model.cp_nombre
                    },
                    sistema:
                    {
                        sds_id: model.servicios_sistema_id,
                        nombre: model.nombre
                    },
                    identificacion: model.identificacion,
                    folio: model.folio,
                };
                this.ObtenerNomens(model.id);

            } // Fin if
        },
        /**
         * Obtiene las nomenclaturas de la inspección actual
         */
        ObtenerNomens(inspec_id)
        {
            this.isObtenerNomensLoading = true;
            axios.get(this.url + "/obtenernomens/" + inspec_id).then(res =>
            {
                this.isObtenerNomensLoading = false;
                if (res.data.status)
                {
                    this.list_nomenclaturas = res.data.nomenclaturas;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        CerrarModalTrazabilidad()
        {
            this.ver_modal_trazabilidad = false;
            this.trazabilidad_modal = {
                sistema:
                {},
                identificacion: "",
                folio: "",
            };
        },
        CerrarModalNomeclatura()
        {
            this.ver_modal_nomenclatura = false;
        },
        AbrirModalNomeclatura()
        {
            this.ver_modal_nomenclatura = true;
            // Obtener las nomenclaturas registradas
            axios.get("/calidad/nomen/obtener").then(res =>
            {
                if (res.data.status)
                {
                    this.list_nomens = res.data.nomenclaturas;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        /**Registra una nueva nomenclatura */
        GuardarNomenclatura()
        {
            this.$validator.validate().then(isValid =>
            {
                if (!isValid) return;
                this.isGuardarNomenclaturaLoading = true;
                axios.post(this.url + "/nomens/registrar",
                {
                    "inpec_trazab_id": this.inspeccion_id,
                    "nomen_id": this.nomenclatura.id,
                }).then(res =>
                {
                    this.isGuardarNomenclaturaLoading = false;
                    if (res.data.status)
                    {
                        toastr.success("Registrado correctamente");
                        this.CerrarModalNomeclatura();
                        this.ObtenerNomens(this.inspeccion_id);
                        this.modal_nomencaltura = {};
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                });
            });
        },

        /**
         * Obtiene los servicios-sistema del proyecto seleccionado
         */
        ObtenerSistemas()
        {
            if (this.trazabilidad_modal.proyecto == null) return;
            if (this.trazabilidad_modal.proyecto.id == null) return;
            this.isSistemasLoading = true;
            axios.get("/calidad/servsis/cargar/" + this.trazabilidad_modal.proyecto.id).then(res =>
            {
                this.isSistemasLoading = false;
                if (res.data.status)
                {
                    this.listSistemas = [];
                    res.data.servicios_sistemas.forEach(s =>
                    {
                        this.listSistemas.push(
                        {
                            sds_id: s.sds_id,
                            nombre: s.s_nombre + " - " + s.serv_nombre
                        });
                    });
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },

        /**
         * Guarda el reporte de trazabilidad
         */
        GuardarTrazabilidad()
        {
            this.$validator.validate().then(isValid =>
            {
                if (!isValid) return;
                let data = new FormData();
                if (this.inspeccion_id != null)
                    data.append("id", this.inspeccion_id);
                data.append("folio", this.trazabilidad_modal.folio);
                data.append("servicios_sistema_id", this.trazabilidad_modal.sistema.sds_id);
                data.append("identificacion", this.trazabilidad_modal.identificacion);

                axios.post(this.url + "/registrar", data).then(res =>
                {
                    if (res.data.status)
                    {
                        toastr.success("Registrado correctamente");
                        this.Buscar();
                        this.CerrarModalTrazabilidad();
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                });
            });
        },

        /**
         * Busca los reportes de trazabilidad del proyecto seleccionado
         */
        Buscar()
        {
            if (this.trazabilidad_modal.proyecto == null) return;
            if (this.trazabilidad_modal.proyecto.id == null) return;
            axios.get(this.url + "/obtener/" + this.trazabilidad_modal.proyecto.id).then(res =>
            {
                if (res.data.status)
                {
                    this.list_trazabilidad = res.data.inspecciones;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        /**
         * Descarga el reporte de la inspección ingresada
         */
        Descargar(inspec)
        {
            // De servicio_sistema
            window.open(this.url + "/reporte/" + inspec.id, '_blank');
        }
    }, // Fin metodos
    mounted()
    {
        this.CargarProyectos();
    }
}
</script>
