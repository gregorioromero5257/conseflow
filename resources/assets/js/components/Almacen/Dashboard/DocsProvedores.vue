<template>
<div class="card border-dark">
    <div class="card-header bg-dark text-white">
        <i class="fa fa-align-justify"> </i> Aprobación de entrega de materiales
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
                                <button type="button" @click="VerOC(props.row.oc_id)" class="dropdown-item">
                                    <i class="fas fa-eye"></i>&nbsp; Ver Orden de Compra
                                </button>
                                <button type="button" @click="Aprobar(props.row.fp_id, 1)" class="dropdown-item">
                                    <i class="fas fa-check"></i>&nbsp; Aprobar
                                </button>
                                <button type="button" @click="Aprobar(props.row.fp_id, 0)" class="dropdown-item">
                                    <i class="fas fa-times"></i>&nbsp; Rechazar
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

<!-- </div> -->
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
                "fp_id",
                "proveedor",
                "folio",
                "nombre_corto",
                "fecha_carga"
            ],
            list_facturasproveedores: [],
            options_facturasproveedores:
            {
                headings:
                {
                    fp_id: "Acciones",
                    proveedor: "Proveedor",
                    nombre_corto: "Proyecto",
                    fecha_carga: "Fecha de carga"
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ["proveedor", "folio", "nombre_corto"],
                filterable: ["proveedor", "folio", "nombre_corto"],
                filterByColumn: true,
                texts: config.texts,
            }, //options
            // Modal
            titulo_modal_facturasproveedores: "",
            tipoAccion_modal_facturasproveedores: 0,
            facturasproveedores_modal:
            {},
        };
    },
    methods:
    {
        ObtenerPagos()
        {
            axios
                .get("/conta/facturasproveedores/obtenerprogramados/=&1")
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
        // DEscargar OC
        VerOC(id)
        {
            window.open("descargar-compran/" + id, "_blank");
        },
        Aprobar(id, valido)
        {
            let titulo = valido ? "Autorizar" : "Rechazar";
            let texto = valido ? "¿Se recibieron todos los materiales y documentación correspondiente?" :
                "Se rechazará la recepción de los materiales";
            let icono = valido ? "info" : "warning";
            Swal.fire(
            {
                icon: "warning",
                title: `${titulo} la recepción de materiales`,
                showDenyButton: true,
                text: texto,
                showCancelButton: true,
                confirmButtonText: `Confirmar`,
            }).then((result) =>
            {
                let mensaje = "";
                if (!valido) // Rechazado
                {
                    // Motivo de rechazo
                    Swal.fire(
                    {
                        input: "text",
                        icon: "info",
                        title: "Motivo del rechazo",
                        showDenyButton: true,
                        text: "Ingrese el motivo del rechazo (Minimo 15 caracteres).",
                        showCancelButton: true,
                        confirmButtonText: `Confirmar`,
                    }).then(ms =>
                    {
                        if (ms == null) return;
                        if (ms.value == null) return;
                        if (ms.value == "") return;
                        if (ms.value.length <= 15)
                        {
                            toastr.warning("Indique el motivo del rechazo.");
                            return;
                        }
                        this.GuardarEstado(id, 0, "ALMACÉN: "+ms.value);
                    });
                }
                else
                {
                    // Aprobado
                    if (result == null) return;
                    if (result.value == null) return;
                    console.error(result);
                    if (result.value)
                    {
                        this.GuardarEstado(id, 2, null);
                    }
                    else
                    {
                        alert("Error");
                    }
                }
            });
        },
        GuardarEstado(id, estado, mensaje)
        {
            let aux_mensaje = estado ==2? "autorizada" : "rechazada";
            axios
                .put("/conta/facturasproveedores/cambiarestado",
                {
                    id,
                    estado,
                    mensaje
                })
                .then((res) =>
                {
                    if (res.status)
                    {
                        toastr.success("Recepción " + aux_mensaje + " correctamente");
                        this.ObtenerPagos();
                    }
                    else
                    {
                        toastr.error("Error. Intente más tarde");
                    }
                });
        }
    },
    mounted()
    {
        this.ObtenerPagos();
    },
};
</script>
