<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-body'>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">
                        Secuencia <i class="fas fa-walking"></i>
                    </a>
                    <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">
                        Riesgo <i class="fas fa-exclamation-triangle"></i>
                    </a>
                    <a class="nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">
                        Recomendación <i class="fas fa-exclamation"></i>
                    </a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <!-- Secuencia -->
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModal(true,1)'>
                        <i class='fas fa-plus'>&nbsp;</i>Nuevo
                    </button>
                    <v-client-table :columns='columns_secuencia' :data='list_secuencia' :options='options_secuencia' ref='tbl_secuencia'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModal(false, 1,props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </v-client-table>
                </div>

                <!-- Riesgo -->
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModal(true,2)'>
                        <i class='fas fa-plus'>&nbsp;</i>Nuevo
                    </button>
                    <v-client-table :columns='columns_secuencia' :data='list_riesgo' :options='options_secuencia' ref='tbl_secuencia'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModal(false,2, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </v-client-table>
                </div>

                <!-- Recomendacion -->
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModal(true,3)'>
                        <i class='fas fa-plus'>&nbsp;</i>Nuevo
                    </button>
                    <v-client-table :columns='columns_secuencia' :data='list_recomendacion' :options='options_secuencia' ref='tbl_secuencia'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModal(false,3, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </v-client-table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Secuencia -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_secuencia'></h4>
                    <button type='button' class='close' @click='CerrarModal()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Acciones</label>
                            <div class='col-md-9'>
                                <input type='text' v-validate="'required'" name="nombre" class='form-control' v-model='registro_modal.nombre'>
                                <span class="text-danger">{{errors.first("nombre")}}</span>
                            </div>
                        </div>

                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModal()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_secuencia== 1' class='btn btn-secondary' @click='Guardar(true)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_secuencia==2' class='btn btn-secondary' @click='Guardar(false)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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

var config = require('../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            url: "/seguridad/catalogos",
            // Tabla 
            ver_modal: 0,
            columns_secuencia: ["id", "nombre"],
            list_secuencia: [],
            list_riesgo: [],
            list_recomendacion: [],
            registro_modal:
            {},
            options_secuencia:
            {
                headings:
                {
                    id: 'Acciones',
                    nombre: "Nombre"
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            titulo_modal_secuencia: '',
            tipoAccion_modal_secuencia: 0,
            secuencia_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        /**
         * Abre el modal para el registro
         */
        AbrirModal(nuevo, tipo, model = [])
        {
            this.ver_modal = true;
            let nombre = tipo == 1 ?
                "Secuencia" : tipo == 2 ?
                "Riesgo" : "Recomendación";
            this.tipo_registro = tipo;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_secuencia = "Registrar " + nombre
                this.tipoAccion_modal_secuencia = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_secuencia = "Actualizar " + nombre
                this.tipoAccion_modal_secuencia = 2;
                this.registro_modal.id = model.id;
                this.registro_modal.nombre = model.nombre;
            } // Fin if
        },

        /**
         * Guarda un registro del tipo seleccionado
         */
        Guardar(nuevo)
        {
            this.$validator.validate().then(isValid =>
            {
                if (!isValid) return;
                // Url del catalogo
                let path = this.tipo_registro == 1 ?
                    "/secuencia" : this.tipo_registro == 2 ?
                    "/riesgo" : "/recomendacion";

                let data = new FormData();
                if (!nuevo)
                    data.append("id", this.registro_modal.id);

                data.append("nombre", this.registro_modal.nombre);
                axios.post(this.url + path + "/registrar", data).then(res =>
                {
                    if (res.data.status)
                    {
                        console.error(this.tipo_registro)
                        toastr.success("Registrado correctamente");
                        this.CerrarModal();
                        if (this.tipo_registro == 1)
                            this.ObtenerSecuencias();
                        else if (this.tipo_registro == 2)
                            this.ObtenerRiesgos();
                        else
                            this.ObtenerRecomendaciones();
                    }
                    else
                    {

                        toastr.danger(res.data.mensaje);
                    }
                })
            })
        },

        /**
         * Obtener las secuencias registradas
         */
        ObtenerSecuencias()
        {
            axios.get(this.url + "/secuencia/cargar").then(res =>
            {
                if (res.data.status)
                {
                    this.list_secuencia = res.data.secuencias;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        /**
         * Obtener los riesgos registradas
         */
        ObtenerRiesgos()
        {
            axios.get(this.url + "/riesgo/cargar").then(res =>
            {
                if (res.data.status)
                {
                    this.list_riesgo = res.data.riesgos;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        /**
         * Obtener las recomenciones registradas
         */
        ObtenerRecomendaciones()
        {
            axios.get(this.url + "/recomendacion/cargar").then(res =>
            {
                if (res.data.status)
                {
                    this.list_recomendacion = res.data.recomendaciones;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        CerrarModal()
        {
            this.ver_modal = false;
            this.registro_modal = {};
        },
    }, // Fin metodos
    mounted()
    {
        this.ObtenerSecuencias();
        this.ObtenerRiesgos();
        this.ObtenerRecomendaciones();
    }
}
</script>
