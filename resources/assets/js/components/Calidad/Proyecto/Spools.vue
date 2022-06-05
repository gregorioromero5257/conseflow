<template>
<div class=''>
    <!-- Card Inicio-->
    <div v-if="tipoAccionCard==1" class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i>
            Spools de {{servicio_sistema.proyecto_nombre}}: {{servicio_sistema.s_nombre.toUpperCase()}}
            - {{servicio_sistema.serv_nombre.toUpperCase()}}
            <button type='button' class='btn btn-dark float-sm-right ml-1' @click='CerrarCardSpools()'>
                <i class='fas fa-arrow-left'>&nbsp;</i>Regresar
            </button>

            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalSpools(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>

        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Spools-->
                <div class=''>
                    <vue-element-loading :active="isVerSpoolsLoading" />
                    <v-client-table :columns='columns_spools' :data='list_spools' :options='options_spools' ref='tbl_spools'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalSpools(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                        <button type='button' @click='AbrirCardJuntas(props.row)' class='dropdown-item'>
                                            <i class='fas fa-list-alt'></i>&nbsp;Juntas
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

    <!-- Modal Spools -->
    <div v-if="ver_modal_spools" class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_spools}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_spools'></h4>
                    <button type='button' class='close' @click='CerrarModalSpools()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <vue-element-loading :active="isGuardarSpoolsLoading" />
                    <div class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Sistema</label>
                            <div class='col-md-7'>
                                <label :disabled="true" class='form-control'>{{servicio_sistema.s_nombre.toUpperCase()}} </label>
                            </div>
                        </div>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Servicio</label>
                            <div class='col-md-7'>
                                <label :disabled="true" class='form-control'>{{servicio_sistema.serv_nombre.toUpperCase()}} </label>
                            </div>
                        </div>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>No</label>
                            <div class='col-md-4'>
                                <input type='text' name="Nombre" class='form-control' v-model='spools_modal.no' v-validate="'required'" v-on:keyup.enter="GuardarSpools(1)">
                                <span class="text-danger">{{errors.first("Nombre")}}</span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalSpools()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_spools== 1' class='btn btn-secondary' @click='GuardarSpools(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_spools==2' class='btn btn-secondary' @click='GuardarSpools(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div v-if="tipoAccionCard==2">
        <!-- <label for="">Juntas</label> -->
        <juntas @CerrarCardJuntas="CerrarCardJuntas" ref="juntas" :spool="spool_aux"></juntas>
    </div>

</div> <!-- Main -->
</template>

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Juntas = r => require.ensure([], () => r(require('./Juntas.vue')), 'calidad');

export default
{
    components:
    {
        "juntas": Juntas,
    },
    props: ["servicio_sistema"],
    data()
    {
        return {
            url: "calidad/spools",
            // Tabla 
            ver_modal_spools: 0,
            columns_spools: ['id', 'no', ],
            list_spools: [],
            options_spools:
            {
                headings:
                {
                    id: 'Acciones',
                    no: 'Nombre',
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['no'],
                filterable: ['no'],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            titulo_modal_spools: '',
            tipoAccion_modal_spools: 0,
            spools_modal:
            {},
            isVerSpoolsLoading: false,
            isGuardarSpoolsLoading: false,
            tipoAccionCard: 1,
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        ObtenerSpools()
        {
            this.isVerSpoolsLoading = true;
            axios.get(this.url + "/obtener/" + this.servicio_sistema.sds_id).then(res =>
            {
                this.isVerSpoolsLoading = false;
                if (res.data.status)
                {
                    this.list_spools = res.data.spools;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },
        AbrirModalSpools(nuevo, spool_model = [])
        {
            this.ver_modal_spools = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_spools = 'Registrar Spools';
                this.tipoAccion_modal_spools = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_spools = 'Actualizar Spools';
                this.tipoAccion_modal_spools = 2;
                this.spools_modal.id = spool_model.id;
                this.spools_modal.no = spool_model.no;
            } // Fin if
        },
        GuardarSpools(nuevo)
        {
            this.isGuardarSpoolsLoading = true;
            this.$validator.validate().then(valid =>
            {
                if (!valid) return;
                axios.post(this.url + "/guardar",
                {
                    id: this.spools_modal.id,
                    no: this.spools_modal.no,
                    servicio_sistema_id: this.servicio_sistema.sds_id
                }).then(res =>
                {
                    this.isGuardarSpoolsLoading = false;
                    if (res.data.status)
                    {
                        toastr.success("Spool guardado correctamente");
                        this.CerrarModalSpools();
                        this.ObtenerSpools();
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                })
            });
        },
        CerrarCardSpools()
        {
            this.$emit("cerrarCardSpools");
        },
        CerrarCardJuntas()
        {
            this.tipoAccionCard=1;
        },
        CerrarModalSpools()
        {
            this.ver_modal_spools = false;
            Utilerias.resetObject(this.spools_modal);
        },

        // Juntas
        AbrirCardJuntas(spool_model)
        {
            console.error("juntas");
            this.tipoAccionCard = 2;
            this.spool_aux = spool_model;
        },
    }, // Fin metodos
    mounted()
    {
        console.error("cargado spool");
        this.ObtenerSpools();
    }
}
</script>
