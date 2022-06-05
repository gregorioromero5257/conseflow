<template>
<div class="main">
    <!-- Card Inicio-->
    <div class="card">
        <!-- Inicio card-->
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Pagos pendientes a proveedores
        </div>
        <div class="card-body">
            <div class="">
                <!-- Tabla de Facturasproveedores-->
                <div class="">
                    <v-client-table :columns="columns_facturasproveedores" :data="list_facturasproveedores" :options="options_facturasproveedores" ref="tbl_facturasproveedores">
                        <template slot="fp_id" slot-scope="props">
                            <div class="btn-group" role="group">
                                <button id="btn_id" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                                <div class="dropdown-menu">
                                    <template>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template slot="fecha_programada" slot-scope="props">
                            <div :id="`div-btn-${props.row.fp_id}`" class="container text-center">
                                <button :id="`btn-${props.row.fp_id}`" @click="AsignarFecha($event, props.row)" class="btn btn-outline-primary">
                                    Asignar fecha &nbsp;
                                    <i class="fas fa-table"></i>
                                </button>
                                <div :id="`div-date-${props.row.fp_id}`" class="form-inline" style="display: none">
                                    <input @keyup.esc="Cancelar(props.row)" :id="`dte-${props.row.fp_id}`" class="form-control mr-1" type="date" style="width: 70%" />
                                    <button class="btn btn-sm btn-success" @click="GuardarFecha(props.row)">
                                        Guardar
                                    </button>
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
</div>

<!-- Main -->
</template>

<script>
import Utilerias from "../../Herramientas/utilerias.js";
var config = require("../../Herramientas/config-vuetables-client").call(this);
export default
{
    data()
    {
        return {
            // Tabla
            ver_modal_facturasproveedores: 0,
            columns_facturasproveedores: [

                "proveedor",
                "folio",
                "fecha_carga",
                "fecha_programada",
            ],
            list_facturasproveedores: [],
            options_facturasproveedores:
            {
                headings:
                {
                    fp_id: "Acciones",
                    proveedor: "Proveedor",
                    fecha_carga: "Fecha de carga",
                    fecha_programada: "Fecha de pago",
                    folio: "Folio O.C"
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ["proveedor", "fecha_carga"],
                filterable: ["proveedor", "fecha_carga"],
                filterByColumn: true,
                texts: config.texts,
            }, //options
            // Modal
            titulo_modal_facturasproveedores: "",
            tipoAccion_modal_facturasproveedores: 0,
            facturasproveedores_modal:
            {},
        }; // return
    }, //data
    computed:
    {},
    methods:
    {
        ObtenerPagos()
        {
            axios
                .get("/conta/facturasproveedores/obtenerprogramados/=&3")
                .then((res) =>
                {
                    if (res.data.status)
                    {
                        this.list_facturasproveedores = res.data.pagos;
                    }
                    else
                    {
                        toastr.error("Errror. Intente más tarde");
                    }
                });
        },
        AsignarFecha(e, row)
        {
            let btn_id = "#btn-" + row.fp_id;
            let div_date = "#div-date-" + row.fp_id;
            $(btn_id).hide();
            $(div_date).show();
        },
        Cancelar(row)
        {
            let btn_id = "#btn-" + row.fp_id;
            let div_date = "#div-date-" + row.fp_id;
            $(btn_id).show();
            $(div_date).hide();
        },
        GuardarFecha(row)
        {
            let dte_id = "#dte-" + row.fp_id;
            // Comprobar que el input tenga una fecha
            let fecha = $(dte_id).val();
            if (fecha == "")
            {
                toastr.warning("Seleccione una fecha");
                return;
            }
            var hoy = new Date();
            let fecha1 = new Date(fecha);
            if (fecha1 < hoy)
            {
                // No puden registrar una fecha anterior a hoy
                toastr.warning("Seleccione una fecha válida");
                return;
            }
            axios
                .put("/conta/facturasproveedores/asignarfecha",
                {
                    fp_id: row.fp_id,
                    fecha,
                })
                .then((res) =>
                {
                    if (res.data.status)
                    {
                        let btn_1 = "#btn-" + row.fp_id;
                        let date_1 = "#div-date-" + row.fp_id;
                        $(btn_1).show();
                        $(date_1).hide();
                        toastr.success("Fecha asignada correctamente");
                        this.ObtenerPagos();
                    }
                    else
                    {
                        toastr.error("Notifique al administrados", "Error");
                    }
                })
                .catch((r) =>
                {
                    console.error(r);
                });
        },
    }, // Fin metodos
    mounted()
    {
        this.ObtenerPagos();
    },
};
</script>
