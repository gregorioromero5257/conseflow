<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Servicios
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalServicios(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Servicios-->
                <div class=''>
                    <vue-element-loading :active="isdatosLoading" />
                    <v-client-table :columns='columns_servicios' :data='list_servicios' :options='options_servicios' ref='tbl_servicios'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_nombre' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalServicios(false, props.row)' class='dropdown-item'>
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

    <!-- Modal Servicios -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_servicios}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_servicios'></h4>
                    <button type='button' class='close' @click='CerrarModalServicios()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <vue-element-loading :active="isGuardarLoading" />
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Nombre</label>
                            <div class='col-md-9'>
                                <input type='text' data-vv-name="nombre" class='form-control' v-validate="'required'" v-model='servicios_modal.nombre'>
                                <span class="text-danger">{{ errors.first('nombre') }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalServicios()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_servicios== 1' class='btn btn-secondary' @click='GuardarServicios(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_servicios==2' class='btn btn-secondary' @click='GuardarServicios(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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
            // Tabla 
            url: 'calidad/servicios',
            ver_modal_servicios: 0,
            isdatosLoading: false,
            isGuardarLoading: false,
            columns_servicios: ['id', 'nombre'],
            list_servicios: [],
            options_servicios:
            {
                headings:
                {
                    id: "Acciones",
                    nombre: 'Nombre'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['nombre'],
                filterable: ['nombre'],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            servicio_id: 0,
            titulo_modal_servicios: '',
            tipoAccion_modal_servicios: 0,
            servicios_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        AbrirModalServicios(nuevo, model = [])
        {
            this.ver_modal_servicios = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_servicios = 'Registrar Servicios';
                this.tipoAccion_modal_servicios = 1;
            }
            else
            {
                // Actualizar
                this.servicios_modal.nombre=model.nombre;
                this.servicios_modal.id=model.id;
                this.servicios_modal=model;
                this.servicio_id = model.id;
                this.titulo_modal_servicios = 'Actualizar Servicios';
                this.tipoAccion_modal_servicios = 2;
            } // Fin if
        },
        CerrarModalServicios()
        {
            this.ver_modal_servicios = false;
            Utilerias.resetObject(this.servicios_modal);
        },
        GuardarServicios(nuevo)
        {
            this.$validator.validate().then(res =>
            {
                if (!res) return;
                let ms = nuevo ? " guardado " : " actualizado ";
                axios.post(this.url + "/guardar",
                    {
                        id: this.servicio_id,
                        nuevo,
                        nombre: this.servicios_modal.nombre
                    })
                    .then(r =>
                    {
                        if (r.data.status)
                        {
                            this.CerrarModalServicios();
                            this.CargarServicios();
                            toastr.success("Servicio" + ms + "correctamente");
                        }
                        else
                        {
                            toastr.error("Error");
                            console.error(red.data);
                        }
                    });
            });

        },
        CargarServicios()
        {
            this.isdatosLoading = true;
            axios.get(this.url + "/cargar").then(res =>
            {
                this.isdatosLoading = false;
                if (res.data.status)
                {
                    this.list_servicios = res.data.servicios;
                    this.CerrarModalServicios();
                }
                else
                {
                    toastr.error("Error");
                    console.error(res);
                }
            }).catch(r =>
            {
                toastr.error("Error");
                console.error(r);
            })
        },
    }, // Fin metodos
    mounted()
    {
        this.CargarServicios();
    }
}
</script>
