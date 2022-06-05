<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Procedimientos
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalProcedimientos(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Procedimientos-->
                <div class=''>
                    <v-client-table :columns='columns_procedimientos' :data='list_procedimientos' :options='options_procedimientos' ref='tbl_procedimientos'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalProcedimientos(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
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

    <!-- Modal Procedimientos -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_procedimientos}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_procedimientos'></h4>
                    <button type='button' class='close' @click='CerrarModalProcedimientos()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>

                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Clave</label>
                            <div class='col-md-3'>
                                <input type='text' data-vv-name="clave" v-validate="'required'" class='form-control' v-model='procedimientos_modal.clave'>
                                <span class="text-danger">{{ errors.first('clave') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Descripción</label>
                            <div class='col-md-9'>
                                <textarea rows="3" data-vv-name="descripcion" v-validate="'required'" class='form-control text-area' v-model='procedimientos_modal.descripcion'>
                                </textarea>
                                <span class="text-danger">{{ errors.first('descripcion') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>WPS</label>
                            <div class='col-md-3'>
                                <input type='text' data-vv-name="WPS" v-validate="'required'" class='form-control' v-model='procedimientos_modal.wps'>
                                <span class="text-danger">{{ errors.first('WPS') }}</span>
                            </div>
                            <label class='col-md-2 form-control-label'>PQR</label>
                            <div class='col-md-3'>
                                <input type='text' data-vv-name="PQR" v-validate="'required'" class='form-control' v-model='procedimientos_modal.pqr'>
                                <span class="text-danger">{{ errors.first('PQR') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Tipo de preparación</label>
                            <div class='col-md-6'>
                                <input type='text' data-vv-name="preparacion" v-validate="'required'" class='form-control' v-model='procedimientos_modal.tipo_preparacion'>
                                <span class="text-danger">{{ errors.first('preparacion') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'> Material de aporte</label>
                            <div class='col-md-6'>
                                <input type='text' data-vv-name="material" v-validate="'required'" class='form-control' v-model='procedimientos_modal.material_aporte'>
                                <span class="text-danger">{{ errors.first('material') }}</span>
                            </div>
                        </div>

                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalProcedimientos()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_procedimientos== 1' class='btn btn-secondary' @click='GuardarProcedimientos(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_procedimientos==2' class='btn btn-secondary' @click='GuardarProcedimientos(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div> <!-- Main -->
</template>

<script>
import
{
    parseZone
}
from 'moment';
/* CAMBIAR UBICACIÓN  */
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            url: "/calidad/procedimientos",
            // Tabla 
            ver_modal_procedimientos: 0,
            columns_procedimientos: ['id', 'clave', 'descripcion', 'wps', 'pqr', 'tipo_preparacion', 'material_aporte'],
            list_procedimientos: [],
            options_procedimientos:
            {
                headings:
                {
                    id: 'Id',
                    clave: 'Clave',
                    descripcion: 'Descripción',
                    wps: 'WPS',
                    pqr: 'PQR',
                    tipo_preparacion: 'Tipo de preparación',
                    material_aporte: ' Mateial de aporte'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['id', 'clave', 'descripcion', 'wps', 'pqr', 'tipo_preparacion', 'material_aporte'],
                filterable: ['id', 'clave', 'descripcion', 'wps', 'pqr', 'tipo_preparacion', 'material_aporte'],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            titulo_modal_procedimientos: '',
            tipoAccion_modal_procedimientos: 1,
            procedimientos_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        /**
         * 
         */
        AbrirModalProcedimientos(nuevo, model = [])
        {
            this.ver_modal_procedimientos = 1;

            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_procedimientos = 'Registrar procedimientos';
                this.tipoAccion_modal_procedimientos = 1;
            }
            else
            {
                this.proceimiento_id = model.id;
                // Actualizar
                this.titulo_modal_procedimientos = 'Actualizar procedimientos';
                this.tipoAccion_modal_procedimientos = 2;
                this.procedimientos_modal = {
                    ...model
                }
            } // Fin if
        },
        CerrarModalProcedimientos()
        {
            this.ver_modal_procedimientos = false;
            Utilerias.resetObject(this.procedimientos_modal);
        },
        GuardarProcedimientos(nuevo)
        {
            this.$validator.validate().then(res =>
            {
                if (!res) return;
                let ms = nuevo ? " registrado " : " actualizado ";
                axios.post(this.url + "/guardar",
                {
                    nuevo,
                    id: this.proceimiento_id,
                    clave: this.procedimientos_modal.clave,
                    descripcion: this.procedimientos_modal.descripcion,
                    wps: this.procedimientos_modal.wps,
                    pqr: this.procedimientos_modal.pqr,
                    tipo_preparacion: this.procedimientos_modal.tipo_preparacion,
                    material_aporte: this.procedimientos_modal.material_aporte,
                }).then(res =>
                {
                    toastr.success("Procedimiento" + ms + "correctamete");
                    this.CerrarModalProcedimientos();
                    this.ObtenerProcedimientos();
                }).catch(r =>
                {
                    toastr.error("Error");
                    consle.console.error(r);
                });
            });

        },
        ObtenerProcedimientos()
        {
            axios.get(this.url + "/cargarprocedimientos").then(res =>
            {
                if (res.data.status)
                {
                    this.list_procedimientos = res.data.procedimietos;
                }
                else
                {
                    toastr.error("Error");
                }
            })
        }
    }, // Fin metodos
    mounted()
    {
        this.ObtenerProcedimientos();
    }
}
</script>
