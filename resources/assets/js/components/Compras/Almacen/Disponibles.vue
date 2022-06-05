<template>
<main class="main">
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <br>
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Precio de artículos registrados
            </div>
            <div class="card-body">
                <v-client-table :columns="columns_articulos" :data="list_articulos" :options="options_articulos" ref="myTable">
                    <template slot="precio" slot-scope="props">
                        <label>$ {{new Intl.NumberFormat("en-US").format(props.row.precio)}}</label>
                    </template>
                </v-client-table>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>

</main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default
{
    data()
    {
        return {
            url: "compras/precioalmacen",
            isTablaLoading: false,
            list_articulos: [],
            columns_articulos: ["folio","descripcion", "precio"],
            options_articulos:
            {
                headings:
                {
                    folio: "Folio O.C.",
                    descripcion: "Artículo",
                    precio: "Precio",
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts,
            },

        }
    },
    computed:
    {},
    methods:
    {

        ObtenerArticulos()
        {
            this.isTablaLoading = true;
            this.list_articulos = [];
            axios
                .get(this.url + "/obtenerprecios")
                .then((r) =>
                {
                    if (r.data.status)
                    {
                        this.list_articulos = r.data.articulos;
                    }
                    else
                    {
                        toastr.error(r.data.mensaje, "Error");
                    }
                    this.isTablaLoading = false;
                })
                .catch((x) =>
                {
                    console.error(x);
                });
        },
    },

    mounted()
    {
        this.ObtenerArticulos();
    }
}
</script>
