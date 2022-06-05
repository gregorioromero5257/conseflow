<template>
<div class="card border-dark">
    <!-- <vue-element-loading :active="true" /> -->
    <div class="card-header bg-dark text-white">
        <i class="fa fa-align-justify"> </i> Precios de artículos - Salidas
    </div>
    <div class="container">
        <br>
        <div class="form-row">
            <label class="col-md-1">Proyecto</label>
            <v-select class="col-md-6" :options="listProyectos" label="nombre_corto" v-model="proyecto"></v-select>
            <button class="btn btn-dark" @click="BuscarSalidas">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <vue-element-loading :active="isTablaLoading" />
        <v-client-table :columns="columns_articulos" :data="list_articulos" :options="options_articulos" ref="myTable" @row-click="PedirPrecio">
            <template slot="id" slot-scope="props">
                <button type="button" class="btn btn-outline-success" @click="terminado(1, props.row.id)">
                    <i class="fas fa-check-square"></i> &nbsp;&nbsp;
                </button>
            </template>
        </v-client-table>
    </div>
</div>
</template>

<style>
.VueTables__row {
    cursor: pointer;
}
</style>

<script>
import Utilerias from "../../Herramientas/utilerias.js";
var config = require("../../Herramientas/config-vuetables-client").call(this);
export default
{
    data()
    {
        return {
            // Tabla
            proyecto:
            {},
            listProyectos: [],
            url: "compras/precioalmacensalidas",
            isTablaLoading: false,
            list_articulos: [],
            columns_articulos: ["nombre", "desc", "cantidad_salida", "oc_folio"],
            options_articulos:
            {
                headings:
                {
                    proyecto: "Proyecto",
                    descripcion: "Artículo",
                    marca: "Marca",
                    fecha: "Fecha solicitud",
                    grupo: "Gupo",
                    cantidad: "Cantidad"
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts,
            },
        };
    },
    methods:
    {
        /**
         * Obtiene los proyectos
         */
        ObtenerProyectos()
        {
            axios.get("/calidad/inspecciones/obtenerproyectos").then(res =>
            {
                if (res.data.status)
                {
                    this.listProyectos = res.data.proyectos;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        /**
         * Carga los articulos del proyecto seleccionado
         */
        BuscarSalidas()
        {
            if (this.proyecto == null) return;
            if (this.proyecto.id == null) return;
            this.isTablaLoading=true;

            axios.get("/compras/precioalmacensalidas/obtenerarticulos/" + this.proyecto.id).then(res =>
            {
                this.isTablaLoading=false;
                if (res.data.status)
                {
                    if (res.data.articulos.length == 0)
                    {
                        toastr.warning("Sin artículos");
                        this.list_articulos = [];
                    }
                    else
                        this.list_articulos = res.data.articulos;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },
        // Obtiene los articulos de compras-almacen

        PedirPrecio(data)
        {
            let row=data.row;
            Swal.mixin(
                {
                    input: "text",
                    confirmButtonText: "Siguiente &rarr;",
                    showCancelButton: true,
                    progressSteps: ["1", "2"],
                })
                .queue([
                {
                    title: "Precio",
                    text: "Ingrese el precio del artículo",
                },
                {
                    title: "Moneda",
                    text: "Ingrese la moneda",
                    input: 'select',
                    inputOptions:
                    {
                        'MX': 'MX',
                        'USD': 'USD',
                        'EUR': 'EUR'
                    },
                }, ])
                .then((result) =>
                {
                    if (result.value)
                    {
                        if (result.value == null) return;
                        if (result.value != "")
                        {
                            this.isTablaLoading = true;
                            axios(
                            {
                                method: "POST",
                                url: this.url + "/registrar",
                                data:
                                {
                                    articulo_id: row.a_id,
                                    req_id: 0,// Requi id
                                    partida_id:row.partida_id,
                                    precio: result.value[0], // Precio
                                    moneda: result.value[1] // Moneda
                                },
                            }).then((res) =>
                            {
                                this.isTablaLoading = false;
                                if (res.data.status)
                                {
                                    toastr.success("", "Guardado correctamente");
                                    this.BuscarSalidas();
                                }
                                else
                                {
                                    toastr.error(res.data.mensaje);
                                }
                            });
                        }
                    }
                });
        },

// ?
        ObtenerArticulos()
        {return;
            this.isTablaLoading = true;
            this.list_articulos = [];
            axios
                .get(this.url + "/obtener")
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
            this.isTablaLoading = false;
        },
    },
    mounted()
    {
        this.ObtenerProyectos();
    },
};
</script>
