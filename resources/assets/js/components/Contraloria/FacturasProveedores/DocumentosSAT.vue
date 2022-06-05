<template>
<div class=''>
    <!-- Card Inicio-->
    <div class='card border-dark'>
        <!-- Inicio card-->
        <div class='card-header bg-dark text-white'>
            <i class='fa fa-align-justify'></i> Validación de Documentos
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Documentos-->
                <div class=''>
                    <v-client-table :columns='columns_documentos' :data='list_documentos' :options='options_documentos' ref='tbl_documentos'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='Aceptar(props.row)' class='dropdown-item'>
                                            <i class='fas fa-check'></i>&nbsp;Aceptar Documento
                                        </button>
                                        <button type='button' @click='Rechazar(props.row)' class='dropdown-item'>
                                            <i class='fas fa-times'></i>&nbsp;Rechazar Documento
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template slot='documento' slot-scope='props'>
                            <button class="btn btn-dark" @click="VerDocumento(props.row)">
                                Documento1
                                <i class="fas fa-file-pdf ml-1"></i>
                            </button>
                        </template>
                        <template slot='tipo' slot-scope='props'>
                            <button v-if="props.row.tipo==1" class="btn btn-primary">
                                Opinión SAT
                            </button>
                            <button v-if="props.row.tipo==2" class="btn btn-warning">
                                RFC
                            </button>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>
</div> <!-- Main -->
</template>

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            url: "/proveedores/validaciondatos",
            // Tabla 
            ver_modal_documentos: 0,
            columns_documentos: ["id", "nombre", "rfc", "fecha_carga", "documento", "tipo"],
            list_documentos: [],
            options_documentos:
            {
                headings:
                {

                    id: 'Acciones',
                    nombre: "Proveedor",
                    rfc: "RFC",
                    fecha_carga: "Fecha",
                    documento: "Documento",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: [
                    "documento",
                    "fecha_carga",
                    "estado",
                    "nombre",
                    "rfc",
                    "tipo"
                ],
                filterable: [
                    "documento",
                    "fecha_carga",
                    "estado",
                    "nombre",
                    "rfc",
                    "tipo"
                ],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            titulo_modal_documentos: '',
            tipoAccion_modal_documentos: 0,
            documentos_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        /**
         * Carga todos los documentos pendientes por revisión
         */
        CargarDocumentos()
        {
            axios.get(this.url + "/obtener").then(res =>
            {
                if (res.data.status)
                {
                    this.list_documentos = res.data.documentos;
                }
                else
                {
                    this.Error(res.data);
                }
            });
        },
        // DEscarga el pdf
        VerDocumento(documento) 
        {
            console.error(documento);
            if(documento.tipo==1) //opinión
            {
                window.open(this.url + "/descargaropinion/" + documento.id, '_blank');
            }
            else
            {
                window.open(this.url + "/descargarrfc/" + documento.id, '_blank');
            }
        },

        /**
         * Acepta el documento
         */
        Aceptar(documento)
        {

            Swal.fire(
            {
                title: 'Aceptar documento',
                text: "¿El documento es válido?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: "Cancelar",
            }).then((result) =>
            {
                if (result.value == null) return;
                if (result.value)
                {
                    axios.post(this.url + "/aceptar/",
                    {
                        "id_documento": documento.id,
                        "tipo": documento.tipo,
                        "estado": 1, // rechazado
                    }).then(res =>
                    {
                        if (res.data.status)
                        {
                            toastr.success("Documentos validado correctamente");
                            this.CargarDocumentos();
                        }
                        else
                        {
                            this.Error(res.data);
                        }
                    })
                }
            })
        },
        /**
         * Rechaza el documento
         */
        Rechazar(documento)
        {
            Swal.fire(
            {
                title: 'Motivo de rechazo (Minimo 5 caracteres)',
                input: 'text',
                inputAttributes:
                {
                    autocapitalize: 'on'
                },
                showCancelButton: true,
                confirmButtonText: 'Look up',
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
            }).then((result) =>
            {
                if (result.value == null) return;
                if (result.value.length < 5) return;
                axios.post(this.url + "/rechazar/",
                {
                    "id_documento": documento.id,
                    "tipo": documento.tipo,
                    "estado": -1, // rechazado
                    "mensaje": result.value,
                }).then(res =>
                {
                    if (res.data.status)
                    {
                        toastr.success("Documentos validado correctamente");
                        this.CargarDocumentos();
                    }
                    else
                    {
                        this.Error(res.data);
                    }
                });
            })
        },

        Error(res)
        {
            toastr.error(res.data.mensaje);
            console.error(res.data);
        }
    }, // Fin metodos
    mounted()
    {
        this.CargarDocumentos();
    }
}
</script>
