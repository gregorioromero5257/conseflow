<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Bridas
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalBrida(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <vue-element-loading :active="isBridasLoading" />
                <!-- Tabla de Brida-->
                <div class=''>
                    <v-client-table :columns='columns_brida' :data='list_brida' :options='options_brida' ref='tbl_brida'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalBrida(false, props.row)' class='dropdown-item'>
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

    <!-- Modal Brida -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_brida}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_brida'></h4>
                    <button type='button' class='close' @click='CerrarModalBrida()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Clase</label>
                            <div class='col-md-3'>
                                <input type='text' name="clase" v-validate="'required'" class='form-control' v-model='brida_modal.clase'>
                                <span class="text-danger">{{errors.first("clase")}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Diametro de la brida</label>
                            <div class='col-md-3'>
                                <input type='text' data-vv-name="diametro brida" v-validate="'required'" class='form-control' v-model='brida_modal.diametro_brida'>
                                <span class="text-danger">{{errors.first("diametro brida")}}</span>
                            </div>

                            <label class='col-md-2 form-control-label'>Longitud del esparrago</label>
                            <div class='col-md-3'>
                                <input type='text' data-vv-name="longitud esparrago" v-validate="'required'" class='form-control' v-model='brida_modal.longitud_esparrago'>
                                <span class="text-danger">{{errors.first("longitud esparrago")}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Diametro del tornillo</label>
                            <div class='col-md-3'>
                                <input type='text' data-vv-name="diametro tornillo" v-validate="'required'" class='form-control' v-model='brida_modal.diametro_tornillo'>
                                <span class="text-danger">{{errors.first("diametro tornillo")}}</span>
                            </div>

                            <label class='col-md-2 form-control-label'>Medida de la tuerca</label>
                            <div class='col-md-3'>
                                <input type='text' v-validate="'required'" name="tuerca" class='form-control' v-model='brida_modal.medida_tuerca'>
                                <span class="text-danger">{{errors.first("tuerca")}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Imagen</label>
                            <div class='col-md-8'>
                                <input id="imgBrida" type='file' class='form-control'>
                            </div>
                        </div>

                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalBrida()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_brida== 1' class='btn btn-secondary' @click='GuardarBrida(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_brida==2' class='btn btn-secondary' @click='GuardarBrida(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            url: "calidad/bridas",
            // Tabla 
            ver_modal_brida: 0,
            columns_brida: ['id', 'diametro_brida', 'longitud_esparrago', 'diametro_tornillo', 'medida_tuerca', 'imagen'],
            list_brida: [],
            isBridasLoading: false,
            options_brida:
            {
                headings:
                {
                    id: 'Acciones',
                    diametro_brida: 'Diametro de la brida',
                    longitud_esparrago: 'Longitud del esparrago',
                    diametro_tornillo: 'Diametro del tornillo',
                    medida_tuerca: 'Medida de la tuerca',
                    imagen: 'Imagen'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['id', 'diametro_brida', 'longitud_esparrago', 'diametro_tornillo', 'medida_tuerca', 'imagen'],
                filterable: ['id', 'diametro_brida', 'longitud_esparrago', 'diametro_tornillo', 'medida_tuerca', 'imagen'],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            titulo_modal_brida: '',
            tipoAccion_modal_brida: 0,
            brida_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        ObtenerBridas()
        {
            this.isBridasLoading = true;
            axios.get(this.url + "/obtener").then(res =>
            {
                this.isBridasLoading = false;
                if (res.data.status)
                {
                    this.list_brida = res.data.bridas;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }

            });
        },
        AbrirModalBrida(nuevo, model = [])
        {
            this.ver_modal_brida = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_brida = 'Registrar Brida';
                this.tipoAccion_modal_brida = 1;
            }
            else
            {
                // Actualizar
                this.brida_modal = {
                    ...model
                };
                this.titulo_modal_brida = 'Actualizar Brida';
                this.tipoAccion_modal_brida = 2;
            } // Fin if
        },

        CerrarModalBrida()
        {
            this.ver_modal_brida = false;
            $("#imgBrida").val("");

            this.brida_modal = {};
        },

        /**
         * Guarda la brida
         */
        GuardarBrida(nuevo)
        {
            this.$validator.validate().then(isValid =>
            {
                if (!isValid) return;
                let inputimg = $("#imgBrida").prop("files");
                let img = inputimg[0];
                if (img == null)
                {
                    toastr.warning("Seleccione la imágen");
                    return;
                }
                // Completo
                let data = new FormData();
                if (!nuevo)
                    data.append("id", this.brida_modal.id);
                data.append("clase", this.brida_modal.clase);
                data.append("diametro_brida", this.brida_modal.diametro_brida);
                data.append("diametro_tornillo", this.brida_modal.diametro_tornillo);
                data.append("longitud_esparrago", this.brida_modal.longitud_esparrago);
                data.append("medida_tuerca", this.brida_modal.medida_tuerca);
                data.append("img_brida", img);
                axios.post(this.url + "/guardar", data).then(res =>
                {
                    if (res.data.status)
                    {
                        toastr.success("Guardado correctamente");
                        this.ObtenerBridas();
                        this.CerrarModalBrida();
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                });
            });
        }
    }, // Fin metodos
    mounted()
    {
        this.ObtenerBridas();
    }
}
</script>
