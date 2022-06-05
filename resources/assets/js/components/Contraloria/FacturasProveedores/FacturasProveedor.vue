<template>
<div class="card border-dark">
    <div class="card-header bg-dark text-white">
        <i class="fa fa-align-justify"> </i> Pago de facturas de proveedor
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
                                    <button type="button" @click="VerOC(props.row.oc_id)" class="dropdown-item">
                                        <i class="fas fa-eye"></i>&nbsp; Ver Orden de Compra
                                    </button>
                                    <button v-if="props.row.estado==0" type="button" @click="VerMensaje(props.row)" class="dropdown-item">
                                        <i class="fab fa-readme"></i>&nbsp; Ver motivo de rechazo
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                    <template slot="fecha_programada" slot-scope="props">
                        <p v-if="props.row.fecha_programada==null">-</p>
                        <p v-else>{{props.row.fecha_programada}}</p>
                    </template>
                    <template slot="estado" slot-scope="props">
                        <button v-if="props.row.estado==0" class="btn btn btn-danger">
                            Rechazado
                            &nbsp;<i class="fas fa-times"></i>
                        </button>
                        <button v-else-if="props.row.estado==1" class="btn btn-primary">
                            Aprobación de Almacén &nbsp;
                            <i class="fas fa-boxes"></i>
                        </button>
                        <button v-else-if="props.row.estado==2" class="btn btn-secondary">
                            Aprobación de Calidad &nbsp;
                            <i class="fas fa-check"></i>
                        </button>
                        <button v-else-if="props.row.estado==3" class="btn btn-warning">
                            Asignación de fecha de pago &nbsp;
                            <i class="fas fa-money-check"></i>
                        </button>
                        <button v-else-if="props.row.estado==4" class="btn btn-dark">
                            Autorización de pago &nbsp;
                            <i class="fas fa-check"></i>
                        </button>
                        <button v-else-if="props.row.estado==5" class="btn btn-success">
                            Pendiente de pago &nbsp;
                            <i class="fas fa-clock"></i>
                        </button>
                        <button v-else-if="props.row.estado==6" class="btn btn-success">
                            Pagado &nbsp;
                            <i class="fas fa-check"></i>
                        </button>

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
                "fecha_carga",
                "fecha_programada",
                "estado",
                "total"
            ],
            list_facturasproveedores: [],
            options_facturasproveedores:
            {
                headings:
                {
                    fp_id: "Acciones",
                    proveedor: "Proveedor",
                    nombre_corto: "Proyecto",
                    fecha_carga: "Fecha de carga",
                    fecha_programada: "Fecha de pago",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: [
                    "proveedor",
                    "folio",
                    "fecha_carga",
                    "nombre_corto",
                    "fecha_programada",
                    "estado"
                ],
                filterable: [
                    "proveedor",
                    "fecha_carga",
                    "folio",
                    "nombre_corto",
                    "fecha_programada",
                    "estado"
                ],
                filterByColumn: true,
                texts: config.texts,
            }, //options
            // Modal
            titulo_modal_facturasproveedores: "",
            tipoAccion_modal_facturasproveedores: 0,
            facturasproveedores_modal:
            {},
        }
    },
    methods:
    {
        ObtenerPagos()
        {
            axios
                .get("/conta/facturasproveedores/obtenerprogramados/<&6")
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
        VerOC(id)
        {
            window.open("descargar-compran/" + id, "_blank");
        },
        Aprobar(id, valido)
        {
            let titulo = valido ? "Autorizar" : "Rechazar";
            let mensaje = valido ? "autorizada" : "rechazada";
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
                if (result == null) return;
                if (result.value == null) return;
                console.error(result);
                if (result.value)
                {
                    let estado = valido ? "3" : "0";
                    axios
                        .put("/conta/facturasproveedores/cambiarestado",
                        {
                            id,
                            estado
                        })
                        .then((res) =>
                        {
                            if (res.status)
                            {
                                toastr.success("Recepción " + mensaje + " correctamente");
                                this.ObtenerPagos();
                            }
                            else
                            {
                                toastr.error("Error. Intente más tarde");
                            }
                        });
                }
                else
                {
                    alert("Errpr");
                }
            });
        },
        VerMensaje(row)
        {
            Swal.fire(
                'Motivo de rechazo',
                row.mensaje,
                'warning'
            )
        }
    },
    mounted()
    {
        this.ObtenerPagos();
    },
};
</script>
