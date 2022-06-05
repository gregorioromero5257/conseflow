<template>
<div class="main">
    <!-- Card Inicio-->
    <div class="card">
        <!-- Inicio card-->
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Nomenclaturas
            <button type="button" class="btn btn-dark float-sm-right" @click="AbrirModalNomenclatura(true)">
                <i class="fas fa-plus">&nbsp;</i>Nuevo
            </button>
        </div>
        <div class="card-body">
            <div class="">
                <!-- Tabla de Nomenclatura-->
                <div class="">
                    <v-client-table :columns="columns_nomenclatura" :data="list_nomenclatura" :options="options_nomenclatura" ref="tbl_nomenclatura">
                        <template slot="id" slot-scope="props">
                            <div class="btn-group" role="group">
                                <button id="btn_id" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                                <div class="dropdown-menu">
                                    <template>
                                        <button type="button" @click="AbrirModalNomenclatura(false, props.row)" class="dropdown-item">
                                            <i class="icon-pencil"></i>&nbsp;Actualizar
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div>
            <!-- card-->
        </div>
    </div>

    <!-- Modal Nomenclatura -->
    <div class="modal fade" tabindex="-1" :class="{ mostrar: ver_modal_nomenclatura }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="titulo_modal_nomenclatura"></h4>
                    <button type="button" class="close" @click="CerrarModalNomenclatura()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Clave</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" v-model="nomenclatura_modal.clave" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="nomenclatura_modal.nombre" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" @click="CerrarModalNomenclatura()">
                        <i class="fas fa-times"></i>&nbsp;Cerrar
                    </button>
                    <button type="button" v-if="tipoAccion_modal_nomenclatura == 1" class="btn btn-secondary" @click="GuardarNomenclatura(1)">
                        <i class="fas fa-save"></i>&nbsp;Guardar
                    </button>
                    <button type="button" v-if="tipoAccion_modal_nomenclatura == 2" class="btn btn-secondary" @click="GuardarNomenclatura(0)">
                        <i class="fas fa-save"></i>&nbsp;Actualizar
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
<!-- Main -->
</template>

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from "../../Herramientas/utilerias.js";
var config = require("../../Herramientas/config-vuetables-client").call(this);
export default
{
    data()
    {
        return {
            url: "/calidad/nomen",
            // Tabla
            ver_modal_nomenclatura: 0,
            columns_nomenclatura: ["id", "clave", "nombre"],
            list_nomenclatura: [],
            options_nomenclatura:
            {
                headings:
                {
                    id: "Acciones",
                    clave: "Clave",
                    nombre: "Nombre",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ["id", "clave", "nombre"],
                filterable: ["id", "clave", "nombre"],
                filterByColumn: true,
                texts: config.texts,
            }, //options
            // Modal
            titulo_modal_nomenclatura: "",
            tipoAccion_modal_nomenclatura: 0,
            nomenclatura_modal:
            {},
        }; // return
    }, //data
    computed:
    {},
    methods:
    {
        CargarNomens()
        {
            axios.get(this.url + "/obtener").then((res) =>
            {
                if (res.data.status)
                {
                    this.list_nomenclatura = res.data.nomenclaturas;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },
        AbrirModalNomenclatura(nuevo, model = [])
        {
            this.ver_modal_nomenclatura = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_nomenclatura = "Registrar Nomenclatura";
                this.tipoAccion_modal_nomenclatura = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_nomenclatura = "Actualizar Nomenclatura";
                this.tipoAccion_modal_nomenclatura = 2;
                this.nomenclatura_modal.clave = model.clave;
                this.nomenclatura_modal.nombre = model.nombre;
                this.nomenclatura_modal.id = model.id;
            } // Fin if
        },
        GuardarNomenclatura(nuevo)
        {
            this.$validator.validate().then((isValid) =>
            {
                if (!isValid) return;
                let data = new FormData();
                if (!nuevo) data.append("id", this.nomenclatura_modal.id);
                data.append("clave", this.nomenclatura_modal.clave);
                data.append("nombre", this.nomenclatura_modal.nombre);
                axios.post(this.url + "/registrar", data).then((res) =>
                {
                    if (res.data.status)
                    {
                        toastr.success("Guardado correctamente");
                        this.CargarNomens();
                        this.CerrarModalNomenclatura();
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                });
            });
        },
        CerrarModalNomenclatura()
        {
            this.ver_modal_nomenclatura = false;
            Utilerias.resetObject(this.nomenclatura_modal);
        },
    }, // Fin metodos
    mounted()
    {
        this.CargarNomens();
    },
};
</script>
