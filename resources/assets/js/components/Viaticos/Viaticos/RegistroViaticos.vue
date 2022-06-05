<template>
<div class="main">
    <!-- Card Inicio-->
    <div class="card">
        <!-- Inicio card-->
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Viatico
            <button type="button" class="btn btn-dark float-sm-right" @click="AbrirModalViatico(true)">
                <i class="fas fa-plus">&nbsp;</i>Nuevo
            </button>
        </div>
        <div class="card-body">
            <div class="">
                <!-- Tabla de Viatico-->
                <div class="">
                    <v-client-table :columns="columns_viatico" :data="list_viatico" :options="options_viatico" ref="tbl_viatico">
                        <template slot="id" slot-scope="props">
                            <div class="btn-group" role="group">
                                <button id="btn_id" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                                <div class="dropdown-menu">
                                    <template>
                                        <button type="button" @click="AbrirModalViatico(false, props.row)" class="dropdown-item">
                                            <i class="icon-pencil"></i>&nbsp;Actualizar
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template slot="empresa" slot-scope="props">
                            <button v-if="props.row.empresa==1" class="btn btn-primary btn-sm">Conserflow</button>
                            <button v-if="props.row.empresa==2" class="btn btn-secondary btn-sm">CSCT</button>
                        </template>

                        <template slot="tipo_contrato" slot-scope="props">
                            <p v-if="props.row.tipo_contrato=='01'">Tiempo indeterminado</p>
                            <p v-if="props.row.tipo_contrato=='02'">Obra determinada</p>
                            <p v-if="props.row.tipo_contrato=='03'">Tiempo determinado</p>
                            <p v-if="props.row.tipo_contrato=='04'">Trabajo por temporada</p>
                            <p v-if="props.row.tipo_contrato=='05'">Sujeto a prueba</p>
                            <p v-if="props.row.tipo_contrato=='06'">Capacitación inicial</p>
                            <p v-if="props.row.tipo_contrato=='07'">Modalidad de contratación por pago de hora laborada</p>
                            <p v-if="props.row.tipo_contrato=='08'">Modalidad de trabajo por comisión laboral</p>
                        </template>

                        <template slot="riesgo_trabajo" slot-scope="props">
                            <p v-if="props.row.riesgo_trabajo==1">Clase I</p>
                            <p v-if="props.row.riesgo_trabajo==2">Clase II</p>
                            <p v-if="props.row.riesgo_trabajo==3">Clase III</p>
                            <p v-if="props.row.riesgo_trabajo==4">Clase IV</p>
                            <p v-if="props.row.riesgo_trabajo==5">Clase V</p>
                            <p v-if="props.row.riesgo_trabajo==99">No aplica</p>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div>
            <!-- card-->
        </div>
    </div>

    <!-- Modal Viatico -->
    <div class="modal fade" tabindex="-1" :class="{ mostrar: ver_modal_viatico }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="titulo_modal_viatico"></h4>
                    <button type="button" class="close" @click="CerrarModalViatico()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-horizontal">

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">No.empleado</label>
                            <div class="col-md-3">
                                <input type="text" data-vv-name="No Empleado" v-validate="'required'" class="form-control" v-model="viatico_modal.num_empleado" />
                                <span class="text-danger">{{errors.first("No Empleado")}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Nombre</label>
                            <div class="col-md-6">
                                <input type="text" data-vv-name="Nombre" v-validate="'required'" class="form-control" v-model="viatico_modal.nombre" />
                                <span class="text-danger">{{errors.first("Nombre")}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Apellido Paterno</label>
                            <div class="col-md-6">
                                <input type="text" data-vv-name="Apellido P" v-validate="'required'" class="form-control" v-model="viatico_modal.ap_paterno" />
                                <span class="text-danger">{{errors.first("Apellido P")}}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Apellido Materno</label>
                            <div class="col-md-6">
                                <input type="text" data-vv-name="Apellido M" v-validate="'required'" class="form-control" v-model="viatico_modal.ap_materno" />
                                <span class="text-danger">{{errors.first("Apellido M")}}</span>
                            </div>
                        </div>

                        <div class="row mb-4">

                            <div class="col-md-2"></div>
                            <div class="col">
                                <label class="col form-control-label">Fecha alta</label>
                                <div class="col">
                                    <input type="date" data-vv-name="Fecha Alta" v-validate="'required'" class="form-control" v-model="viatico_modal.fecha_alta" />
                                    <span class="text-danger">{{errors.first("Fecha Alta")}}</span>
                                </div>
                            </div>

                            <div class="col">
                                <label class="col form-control-label">Fecha Baja</label>
                                <div class="col">
                                    <input type="date" data-vv-name="Fecha Baja" v-validate="'required'" class="form-control" v-model="viatico_modal.fecha_baja" />
                                    <span class="text-danger">{{errors.first("Fecha Baja")}}</span>
                                </div>
                            </div>

                            <div class="col">
                                <label class="col form-control-label">Fecha reingreso</label>
                                <div class="col">
                                    <input type="date" data-vv-name="Fecha Ingreso" v-validate="'required'" class="form-control" v-model="viatico_modal.fecha_reingreso" />
                                    <span class="text-danger">{{errors.first("Fecha Ingreso")}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Tipo contrato</label>
                            <div class="col-md-6">
                                <select class="form-control" data-vv-name="Tipo Contrato" v-validate="'required'" v-model="viatico_modal.tipo_contrato">
                                    <option value="01">01 - Contrato de trabajo por tiempo indeterminado</option>
                                    <option value="02">02 - Contrato de trabajo por obra determinada</option>
                                    <option value="03">03 - Contrato de trabajo por tiempo determinado</option>
                                    <option value="04">04 - Contrato de trabajo por temporada</option>
                                    <option value="05">05 - Contrato de trabajo sujeto a prueba</option>
                                    <option value="06">06 - Contrato de trabajo con capacitación inicial</option>
                                    <option value="07">07 - Modalidad de contratación por pago de hora laborada</option>
                                    <option value="08">08 - Modalidad de trabajo por comisión laboral</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Riesgo de trabajo</label>
                            <div class="col-md-4">
                                <select class="form-control" data-vv-name="Riesgo de trabajo" v-validate="'required'" v-model="viatico_modal.riesgo_trabajo">
                                    <option value="1">1 - Clase I </option>
                                    <option value="2">2 - Clase II </option>
                                    <option value="3">3 - Clase III </option>
                                    <option value="4">4 - Clase IV </option>
                                    <option value="5">5 - Clase V </option>
                                    <option value="99">99 - No aplica </option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">

                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <label class="col form-control-label">SDI</label>
                                <div class="col">
                                    <input type="number" step="0.1" data-vv-name="SDI" v-validate="'required'" class="form-control" v-model="viatico_modal.sdi" />
                                    <span class="text-danger">{{errors.first("SDI")}}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col form-control-label">SBC</label>
                                <div class="col">
                                    <input type="number" step="0.1" data-vv-name="SBC" v-validate="'required'" class="form-control" v-model="viatico_modal.sbc" />
                                    <span class="text-danger">{{errors.first("SBC")}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">

                            <div class="col">
                                <label class="col form-control-label">NSS</label>
                                <div class="col">
                                    <input type="text" data-vv-name="NSS" v-validate="'required'" class="form-control" v-model="viatico_modal.nss" />
                                    <span class="text-danger">{{errors.first("NSS")}}</span>
                                </div>
                            </div>

                            <div class="">
                                <label class="col form-control-label">RFC</label>
                                <div class="col">
                                    <input type="text" data-vv-name="RFC" v-validate="'required'" class="form-control" v-model="viatico_modal.rfc" />
                                    <span class="text-danger">{{errors.first("RFC")}}</span>
                                </div>
                            </div>

                            <div class="col">
                                <label class="col form-control-label">CURP</label>
                                <div class="col">
                                    <input type="text" data-vv-name="CURP" v-validate="'required'" class="form-control" v-model="viatico_modal.curp" />
                                    <span class="text-danger">{{errors.first("CURP")}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Empresa</label>
                            <div class="col-md-4">
                                <select class="form-control" data-vv-name="Empresa" v-validate="'required'" v-model="viatico_modal.empresa">
                                    <option value="1">Conserflow</option>
                                    <option value="2">CSCT</option>
                                </select>
                                <span class="text-danger">{{errors.first("Empresa")}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" @click="CerrarModalViatico()">
                        <i class="fas fa-times"></i>&nbsp;Cerrar
                    </button>
                    <button type="button" v-if="tipoAccion_modal_viatico == 1" class="btn btn-secondary" @click="GuardarViatico(1)">
                        <i class="fas fa-save"></i>&nbsp;Guardar
                    </button>
                    <button type="button" v-if="tipoAccion_modal_viatico == 2" class="btn btn-secondary" @click="GuardarViatico(0)">
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
            // Tabla
            ver_modal_viatico: 0,
            columns_viatico: [
                "id",
                "num_empleado",
                "fecha_alta",
                "riesgo_trabajo",
                "fecha_reingreso",
                "tipo_contrato",
                "ap_paterno",
                "ap_materno",
                "nombre",
                "sdi",
                "sbc",
                "nss",
                "rfc",
                "curp",
                "empresa",
            ],
            list_viatico: [],
            options_viatico:
            {
                headings:
                {
                    id: "Acciones",
                    num_empleado: "No.empleado",
                    fecha_alta: "Fecha alta",
                    fecha_baja: "Fecha baja",
                    fecha_reingreso: "Fecha reingreso",
                    tipo_contrato: "Tipo contrato",
                    ap_paterno: "Apellido p",
                    ap_materno: "Apellido materno",
                    riesgo_trabajo: "Riesgo",
                    nombre: "Nombre",
                    sdi: "SDI",
                    sbc: "SBC",
                    nss: "NSS",
                    rfc: "RFC",
                    curp: "CURP",
                    empresa: "Empresa",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: [
                    "id",
                    "num_empleado",
                    "fecha_alta",
                    "fecha_reingreso",
                    "fecha_baja",
                    "tipo_contrato",
                    "ap_paterno",
                    "ap_materno",
                    "nombre",
                    "sdi",
                    "sbc",
                    "nss",
                    "rfc",
                    "curp",
                    "empresa",
                ],
                filterable: [
                    "id",
                    "num_empleado",
                    "fecha_alta",
                    "fecha_reingreso",
                    "tipo_contrato",
                    "ap_paterno",
                    "ap_materno",
                    "nombre",
                    "sdi",
                    "sbc",
                    "nss",
                    "rfc",
                    "curp",
                    "empresa",
                ],
                filterByColumn: true,
                texts: config.texts,
            }, //options
            // Modal
            titulo_modal_viatico: "",
            tipoAccion_modal_viatico: 0,
            viatico_modal:
            {},
        }; // return
    }, //data
    computed:
    {},
    methods:
    {
        ObtenerViaticos()
        {
            axios.get("/viaticos/registro/obtener").then(res =>
            {
                if (res.data.status)
                {
                    this.list_viatico = res.data.viaticos;
                    console.error(res.data.viaticos);
                }
                else
                {
                    toastr.error("Error al obtener los registros");
                }
            });
        },
        AbrirModalViatico(nuevo, model = [])
        {
            this.ver_modal_viatico = true;
            if (nuevo)
            {
                this.viatico_modal.id = null;
                // Crear nuevo
                this.titulo_modal_viatico = "Registrar Viatico";
                this.tipoAccion_modal_viatico = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_viatico = "Actualizar Viatico";
                this.tipoAccion_modal_viatico = 2;
                this.viatico_modal.id = model.id;
                this.viatico_modal.num_empleado = model.num_empleado;
                this.viatico_modal.fecha_alta = model.fecha_alta;
                this.viatico_modal.fecha_baja = model.fecha_baja;
                this.viatico_modal.fecha_reingreso = model.fecha_reingreso;
                this.viatico_modal.tipo_contrato = model.tipo_contrato;
                this.viatico_modal.ap_paterno = model.ap_paterno;
                this.viatico_modal.ap_materno = model.ap_materno;
                this.viatico_modal.nombre = model.nombre;
                this.viatico_modal.riesgo_trabajo = model.riesgo_trabajo;
                this.viatico_modal.sbc = model.sbc;
                this.viatico_modal.sdi = model.sdi;
                this.viatico_modal.nss = model.nss;
                this.viatico_modal.rfc = model.rfc;
                this.viatico_modal.curp = model.curp;
                this.viatico_modal.empresa = model.empresa;

            } // Fin if
        },

        GuardarViatico(nuevo)
        {
            this.$validator.validate().then(isValid =>
            {
                if (!isValid) return;

                let data = new FormData();
                data.append("id", this.viatico_modal.id);
                data.append("num_empleado", this.viatico_modal.num_empleado);
                data.append("fecha_alta", this.viatico_modal.fecha_alta);
                data.append("riesgo_trabajo", this.viatico_modal.riesgo_trabajo);
                data.append("fecha_baja", this.viatico_modal.fecha_baja);
                data.append("fecha_reingreso", this.viatico_modal.fecha_reingreso);
                data.append("tipo_contrato", this.viatico_modal.tipo_contrato);
                data.append("ap_paterno", this.viatico_modal.ap_paterno);
                data.append("ap_materno", this.viatico_modal.ap_materno);
                data.append("nombre", this.viatico_modal.nombre);
                data.append("sdi", this.viatico_modal.sdi);
                data.append("sbc", this.viatico_modal.sbc);
                data.append("nss", this.viatico_modal.nss);
                data.append("rfc", this.viatico_modal.rfc);
                data.append("curp", this.viatico_modal.curp);
                data.append("empresa", this.viatico_modal.empresa);

                axios.post("/viaticos/registro/registrar", data).then(res =>
                {
                    if (res.data.status)
                    {
                        this.CerrarModalViatico();
                        toastr.success("Registrado correctamente");
                        this.ObtenerViaticos();
                    }
                    else
                    {
                        toastr.error("Error");
                    }
                })
            });
        },

        CerrarModalViatico()
        {
            this.ver_modal_viatico = false;
            Utilerias.resetObject(this.viatico_modal);
        },
    }, // Fin metodos
    mounted()
    {
        this.ObtenerViaticos();
    },
};
</script>
