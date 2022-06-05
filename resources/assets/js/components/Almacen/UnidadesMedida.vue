<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Unidades de Medida
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalUnidades(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Unidades-->
                <div class=''>
                    <v-client-table :columns='columns_unidades' :data='list_unidades' :options='options_unidades' ref='tbl_unidades'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalUnidades(false, props.row)' class='dropdown-item'>
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

    <!-- Modal Unidades -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_unidades}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_unidades'></h4>
                    <button type='button' class='close' @click='CerrarModalUnidades()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Clave</label>
                            <div class='col-md-4'>
                                <input type='text' name="clave" v-validate="'required|max:10'" class='form-control' data-vv-name="clave" v-model='unidades_modal.clave'>
                                <span class="text-danger">{{ errors.first('clave') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Nombre</label>
                            <div class='col-md-9'>
                                <input type='text' v-validate="'required|max:20'" data-vv-name="nombre" class='form-control' v-model='unidades_modal.nombre'>
                                <span class="text-danger">{{ errors.first('nombre') }}</span>
                            </div>
                        </div>

                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalUnidades()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_unidades== 1' class='btn btn-secondary' @click='GuardarUnidades(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_unidades==2' class='btn btn-secondary' @click='GuardarUnidades(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            id: 0,
            url: "/almacen/unidadesm",
            // Tabla 
            ver_modal_unidades: 0,
            columns_unidades: ['id', 'clave', 'nombre'],
            list_unidades: [],
            options_unidades:
            {
                headings:
                {
                    id: 'Acciones',
                    clave: 'Clave',
                    nombre: 'Nombre'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['id', 'clave', 'nombre'],
                filterable: ['id', 'clave', 'nombre'],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            titulo_modal_unidades: '',
            tipoAccion_modal_unidades: 0,
            unidades_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        CargarUnidades()
        {
            this.isCargandoloading = true;
            axios.get(this.url + "/obtener").then(res =>
            {
                this.isCargandoloading = false;
                if (res.data.status)
                {
                    this.list_unidades = res.data.unidades;
                }
                else
                {
                    this.Error();
                }
            })
        },
        AbrirModalUnidades(nuevo, model = [])
        {
            this.ver_modal_unidades = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_unidades = 'Registrar Unidades';
                this.tipoAccion_modal_unidades = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_unidades = 'Actualizar Unidades';
                this.tipoAccion_modal_unidades = 2;
                this.id = model.id;
                this.unidades_modal.clave = model.clave;
                this.unidades_modal.nombre = model.nombre;
            } // Fin if
        },

        CerrarModalUnidades()
        {
            this.ver_modal_unidades = false;
            Utilerias.resetObject(this.unidades_modal);
        },
        GuardarUnidades(nuevo)
        {

            this.isloading = true;
            let url, mensaje, method = "";
            this.$validator.validate().then(result =>
            {
                if(!result) return;
                if (nuevo)
                {
                    url = this.url + "/registrar";
                    mensaje = "Unidad de mediada registrada correctamente";
                    method = "POST";
                }
                else
                {
                    method = "PUT";
                    url = this.url + "/actualizar";
                    mensaje = "Unidad de mediada actualizada correctamente";
                }
                axios(
                {
                    method,
                    url,
                    data:
                    {
                        id: this.id,
                        clave: this.unidades_modal.clave,
                        nombre: this.unidades_modal.nombre
                    }
                }).then(res =>
                {
                    this.CerrarModalUnidades();
                    this.isloading = false;
                    if (res.data.status)
                    {
                        toastr.success(mensaje);
                        this.CargarUnidades();
                    }
                    else
                    {
                        this.Error();
                        console.error(res);
                    }

                });
            });
        },
        Error()
        {
            toastr.error("Contacte al administrador e intente más tarde", "Error");
        }
    }, // Fin metodos
    mounted()
    {
        this.CargarUnidades();
    }
}
</script>
