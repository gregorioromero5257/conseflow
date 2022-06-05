<template>
<main class="main">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Estado de proyecto
                <br />
                <br />
            </div>
            <div class="card-body">
                <vue-element-loading :active="isLoading" />

                <v-client-table :columns="columns_cateegoria" :data="list_cateegoria" :options="options_cateegoria" ref="tbl_cateegoria">
                    <template slot="id" slot-scope="props">
                        <div class="btn-group" role="group">
                            <button id="btn_proyecto" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                            </button>
                            <div class="dropdown-menu">
                                <template>
                                    <button type="button" class="dropdown-item">
                                        <i class="icon-pencil"></i>&nbsp;Detalles
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                    <template slot="condicion" slot-scope="props">
                        <button v-if="props.row.condicion==1" class="btn btn-sm btn-success">Activo</button>
                        <button v-else class="btn btn-sm btn-warning">Inactivo</button>
                    </template>
                </v-client-table>
            </div>
        </div>
    </div>
</main>
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
            // Tabla
            isLoading: false,
            ver_modal_cateegoria: 0,
            columns_cateegoria: [
                "proyecto",
                "categoria",
                "sub",
                "condicion",
                "fecha_inicio",
            ],
            list_cateegoria: [],
            options_cateegoria:
            {
                headings:
                {
                    id: "Acciones",
                    proyecto: " Proyecto",
                    categoria: "Categoría",
                    sub: "Subcategoría",
                    condicion: "Estado",
                    fecha_inicio: "Fecha inicio",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ["id", "proyecto", "categoria", "sub", "condicion", "fecha_inicio"],
                filterable: [
                    "proyecto",
                    "categoria",
                    "sub",
                    "condicion",
                    "fecha_inicio",
                ],
                filterByColumn: true,
                texts: config.texts,
            }, //options
            // Modal
            titulo_modal_cateegoria: "",
            tipoAccion_modal_cateegoria: 0,
            cateegoria_modal:
            {},
        }; // return
    }, //data
    computed:
    {},
    methods:
    {
        // Obtiene todos los proyectos con su categoría
        CargarProyectos()
        {
            axios
                .get("proyectos1/estado")
                .then((res) =>
                {
                    if (res.data.status)
                    {
                        this.list_cateegoria = res.data.proyectos;
                    }
                    else
                    {
                        console.error(r);
                        this.toastr.error("Error");
                    }
                })
                .catch((r) =>
                {
                    this.toastr.error("Error");
                    console.error(r);
                });
        },
    }, // Fin metodos
    mounted()
    {
        this.CargarProyectos();
    },
};
</script>
