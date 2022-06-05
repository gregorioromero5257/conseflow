<template>
<div class="main">
    <!-- Card Inicio-->
    <div class="card">
        <!-- Inicio card-->
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Equipos de calibración
            <button class="btn btn-success float-sm-right" @click="Reporte()">
                <i class="fas fa-file-excel"></i> Reporte
            </button>
        </div>
        <div class="card-body">
            <div class="">
                <!-- Tabla de Calibracion-->
                <div class="">
                    <v-client-table :columns="columns_calibracion" :data="list_calibracion" :options="options_calibracion" ref="tbl_calibracion">
                        <template slot="id" slot-scope="props">
                            <div class="btn-group" role="group">
                                <button id="btn_id" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                                <div class="dropdown-menu">
                                    <template>
                                        <button type="button" @click="AbrirModalCalibracion(props.row)" class="dropdown-item">
                                            <i class="icon-pencil"></i>&nbsp;Detalles
                                        </button>
                                        <button type="button" @click="AbrirRegistrarCalib(props.row)" class="dropdown-item">
                                            <i class="fas fa-plus"></i>&nbsp;Registrar Calibración
                                        </button>
                                        <button type="button" @click="AbrirModalHistorial(props.row)" class="dropdown-item">
                                            <i class="fas fa-history"></i>&nbsp;Historial de
                                            calibraciones
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template slot="estado_equipo" slot-scope="props">
                            <button v-if="props.row.estado_equipo == 0" class="btn btn-secondary">
                                Sin servicio
                            </button>
                            <button v-else-if="props.row.estado_equipo == 1" class="btn btn-secondary">
                                Fecha Servicio
                            </button>
                            <button v-else-if="props.row.estado_equipo == 2" class="btn btn-secondary">
                                Proxima Fecha
                            </button>
                            <button v-else-if="props.row.estado_equipo == 3" class="btn btn-secondary">
                                Renovar
                            </button>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div>
            <!-- card-->
        </div>
    </div>

    <!-- Modal Calibracion -->
    <div class="modal fade" tabindex="-1" :class="{ mostrar: ver_modal_calibracion}" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <div class="modal-header">
                        <h4 class="modal-title">Detalles del equipo</h4>
                        <button type="button" class="close" @click="CerrarModalRegisCalib()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-md-10 mb-3">
                                <label>Descripción</label>
                                <input type="text" class="form-control" v-model="calibracion_modal.descripcion" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label>Marca</label>
                                <input type="text" class="form-control" v-model="calibracion_modal.marca" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Modelo</label>
                                <input type="text" class="form-control" v-validate="'required'" data-vv-name="Modelo" v-model="calibracion_modal.modelo" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>No. Serie</label>
                                <input type="text" class="form-control" v-model="calibracion_modal.numero_serie" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-10 mb-3">
                                <label>Rango Medición</label>
                                <input type="text" class="form-control" v-validate="'required'" data-vv-name="Rango Medición" v-model="calibracion_modal.rango_medicion" />
                                <span class="text-danger">{{
                    errors.first("Rango Medición")
                  }}</span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label>Frecuencia</label>
                                <select class="form-control" v-model="calibracion_modal.frecuencia" v-validate="'required'" data-vv-name="Frecuencia">
                                    <option value="1">Anual</option>
                                    <option value="2">Sin Info.</option>
                                    <option value="3">Semenstral</option>
                                    <option value="4">Bimestral</option>
                                    <option value="5">Mensual</option>
                                </select>
                                <span class="text-danger">{{
                    errors.first("Frecuencia")
                  }}</span>
                            </div>
                            <template v-if="
                    calibracion_modal.frecuencia != '' &&
                    calibracion_modal.frecuencia != '2'
                  ">
                                <div class="col-md-4 mb-3">
                                    <label>Fecha Servicio</label>
                                    <input type="date" class="form-control" v-validate="'required'" data-vv-name="Fecha Servicio" v-model="calibracion_modal.fecha_servicio" />
                                    <span class="text-danger">{{
                      errors.first("Fecha Servicio")
                    }}</span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Proxima fecha</label>
                                    <input type="date" class="form-control" v-validate="'required'" data-vv-name="Proxima Fecha" v-model="calibracion_modal.proxima_fecha" />
                                    <span class="text-danger">{{
                      errors.first("Proxima Fecha")
                    }}</span>
                                </div>
                            </template>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label>Resguardo</label>
                                <input type="text" class="form-control" v-model="calibracion_modal.resguardo" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Observaciones</label>
                                <input type="text" class="form-control" v-model="calibracion_modal.observaciones" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="CerrarModalCalibracion()">
                            <i class="fas fa-window-close"></i>&nbsp;Cerrar
                        </button>
                        <button type="button" class="btn btn-outline-dark" @click="Actualizar()">
                            <i class="fas fa-save"></i>&nbsp;Guardar
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Modal Historial -->
    <div class="modal fade" tabindex="-1" :class="{ mostrar: ver_modal_historial }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <div class="modal-header">
                        <h4 class="modal-title">Historial de calibraciones</h4>
                        <button type="button" class="close" @click="CerrarModalHistorial()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <vue-element-loading :active="isLoadingHistorial" />
                        <div class="container">
                            <div v-if="listHistorial.length>0">
                                <table class="table table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Fecha de servicio</th>
                                            <th scope="col">Próxima servicio</th>
                                            <th scope="col">Certificado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(h,i) in listHistorial">
                                            <th scope="row">{{i+1}}</th>
                                            <td>{{h.fecha_servicio}}</td>
                                            <td>{{h.proxima_fecha}}</td>
                                            <td>
                                                <a class="link" style="cursor:pointer" @click="DescargarCertificado(h.id)">
                                                    {{h.certificado}}
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else>
                                <h5 class="text-center">Sin calibraciones</h5>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="CerrarModalHistorial()">
                            <i class="fas fa-window-close"></i>
                            &nbsp;Cerrar
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Modal REgistrar calib -->
    <div class="modal fade" tabindex="-1" :class="{ mostrar: ver_modal_regis_calib }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Registrar calibración </h4>
                    <button type="button" class="close" @click="CerrarModalRegisCalib()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="" method="post" enctype="multipart/form-data" class="modal-body">

                    <div class="col mb-3 mt-3">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label>Fecha Servicio</label>
                                <input type="date" class="form-control" name="reg_fecha_servicio" v-model="reg_calibracion_modal.reg_fecha_servicio" />
                                <span class="text-danger">{{errors.first("reg_fecha_servicio")}}</span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Próximo servicio</label>
                                <input type="date" name="reg_fecha_proxima" class="form-control" v-model="reg_calibracion_modal.reg_fecha_proxima" />
                                <span class="text-danger">{{errors.first("reg_fecha_proxima")}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Certificado de calibración</span>
                            </div>
                            <div class="ml-3">
                                <input class="" accept="application/pdf" type="file">
                                <!-- <input v-on:change="Comrprobar" type="file" accept="application/pdf" id="reg_calibracion_modal_certificado" class="custom-file-input" style="opacity:1"> -->
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="CerrarModalRegisCalib()">
                            <i class="fas fa-window-close"></i>
                            &nbsp;Cerrar
                        </button>

                        <button type="button" class="btn btn-outline-dark" @click="GuardarCalib()">
                            <i class="fas fa-save"></i>&nbsp;Guardar
                        </button>
                    </div>
                </form>

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
            url: "/calidad/calib",
            // Tabla
            ver_modal_calibracion: 0,
            columns_calibracion: [
                "id",
                "descripcion",
                "marca",
                "modelo",
                "numero_serie",
                "fecha_servicio",
                "proxima_fecha",
                //"estado_equipo",
            ],
            list_calibracion: [],
            options_calibracion:
            {
                headings:
                {
                    id: "Acciones",
                    descripcion: "Descripción",
                    marca: "Marca",
                    modelo: "Modelo",
                    numero_serie: "No. Serie",
                    fecha_servicio: "Último ser.",
                    proxima_fecha: "Prox. servicio",
                    frecuencia: "Frecuencia",
                    resguardo: "Resguardo",
                    observaciones: "Observaciones",
                    articulo_id: "Articulo_id",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: [
                    "id",
                    "descripcion",
                    "marca",
                    "modelo",
                    "numero_serie",
                    "fecha_servicio",
                    "proxima_fecha",
                    "frecuencia",
                    "resguardo",
                    "observaciones",
                    "articulo_id",
                ],
                filterable: [
                    "id",
                    "descripcion",
                    "marca",
                    "modelo",
                    "numero_serie",
                    "fecha_servicio",
                    "proxima_fecha",
                    "frecuencia",
                    "resguardo",
                    "observaciones",
                    "articulo_id",
                ],
                filterByColumn: true,
                texts: config.texts,
            }, //options
            // Modal
            titulo_modal_calibracion: "",
            tipoAccion_modal_calibracion: 0,
            calibracion_modal:
            {},
            // Historial
            ver_modal_historial: false,
            listHistorial: [],
            isLoadingHistorial: false,
            // Registrar calib
            ver_modal_regis_calib: false,
            reg_calibracion_modal:
            {
                id: 0,
                reg_frecuencia: "",
                reg_fecha_servicio: "",
                reg_fecha_proxima: ""
            },
            reg_calibracion_modal_certificado:
            {},
        }; // return
    }, //data
    computed:
    {},
    methods:
    {
        AbrirModalCalibracion(model = [])
        {
            console.error(model.servicio_id);
            this.ver_modal_calibracion = true;
            this.calibracion_modal = {
                id: model.id,
                rango_medicion: model.rango_medicion,
                descripcion: model.descripcion,
                marca: model.marca,
                modelo: model.modelo,
                numero_serie: model.numero_serie,
                fecha_servicio: model.fecha_servicio,
                proxima_fecha: model.proxima_fecha,
                frecuencia: model.frecuencia,
                resguardo: model.resguardo,
                observaciones: model.observaciones,
                articulo_id: model.articulo_id,
                servicio_id: model.servicio_id,
            };
        },

        CerrarModalCalibracion()
        {
            this.ver_modal_calibracion = false;
            Utilerias.resetObject(this.calibracion_modal);
        },
        Cargarcalibracion_modal()
        {
            axios.get(this.url + "/equipos").then((res) =>
            {
                if (res.data.status)
                {
                    this.list_calibracion = res.data.equipos;
                }
                else
                {
                    this.Error(res.data.mensaje);
                }
            });
        },
        Actualizar()
        {
            this.$validator.validate().then((res) =>
            {
                if (!res) return;
                axios.put("actualizar/calibracion/equipos/", this.calibracion_modal).then((res) =>
                {
                    if (res.data.status)
                    {
                        toastr.success("Equipo actualizado correctamente");
                        this.Cargarcalibracion_modal();
                        this.CerrarModalCalibracion();
                    }
                    else
                    {
                        this.Error("Error al actualizar el equipo");
                    }
                });
            });
        },
        AbrirModalHistorial(model)
        {
            this.isLoadingHistorial = true;
            this.ver_modal_historial = true;
            axios.get(this.url + "/historial/" + model.id).then((res) =>
            {
                this.isLoadingHistorial = false;
                if (res.data.status)
                {
                    this.listHistorial = res.data.historial;
                }
                else
                {
                    this.Error(res.data.mensaje);
                }
            });
        },
        CerrarModalHistorial()
        {
            this.ver_modal_historial = false;
        },
        AbrirRegistrarCalib(model)
        {
            this.ver_modal_regis_calib = true;
            this.reg_calibracion_modal.id = model.id;
        },
        CerrarModalRegisCalib()
        {
            this.ver_modal_regis_calib = false;
            Utilerias.resetObject(this.reg_calibracion_modal);
        },
        GuardarCalib()
        {
            if (this.RegistrarValido())
            {
                let certificado1 = $('#reg_calibracion_modal_certificado').prop('files')[0];
                this.isLoadingRegCalib = true;
                console.error(certificado1);
                let data = new FormData();
                data.append("equipos_catalogo_id", this.reg_calibracion_modal.id);
                data.append("fecha_servicio", this.reg_calibracion_modal.reg_fecha_servicio);
                data.append("proxima_fecha", this.reg_calibracion_modal.reg_fecha_proxima);
                data.append("certificado", certificado1);
                axios(
                {
                    url: this.url + "/regcalib",
                    method: "POST",
                    data,
                }).then(res =>
                {
                    this.isLoadingRegCalib = false;
                    if (res.data.status)
                    {
                        toastr.success("Calibración registrada correctamente");
                        this.Cargarcalibracion_modal();
                        this.CerrarModalRegisCalib();
                    }
                    else
                    {
                        this.Error(res.data.mensaje);
                    }
                });
            }
            else
                toastr.warning("Ingrese todos los campos");

        },
        Comrprobar()
        {
            let f = document.getElementById('reg_calibracion_modal_certificado');
            if (f == null) return;
            if (f.files[0] == null) return;

            var nombreL_a = f.files[0].name;
            var extension = nombreL_a.split('.').pop();
            if (extension == 'pdf')
            {
                this.reg_calibracion_modal_certificado = f.files[0];
            }
            console.error("ok");
        },
        RegistrarValido()
        {
            if (this.reg_calibracion_modal.id == 0) return false;
            if (this.reg_calibracion_modal.reg_fecha_servicio == null) return false;
            if (this.reg_calibracion_modal.reg_fecha_proxima == null) return false;
            let f = document.getElementById('reg_calibracion_modal_certificado');
            if (f == null) return false;
            if (f.files[0] == null) return false;
            return true;
        },
        DescargarCertificado(id)
        {
            window.open(this.url + "/descargarcert/" + id, '_blank');
        },
        Error(ms)
        {
            toastr.error(ms);
        },
        Reporte()
        {
            // window.open('descargar-excel-proveedores/','_blank');
            console.error(21234);
            window.open(this.url + "/descreporte", "_blank");
        }
    }, // Fin metodos
    mounted()
    {
        this.Cargarcalibracion_modal();
    },
};
</script>
