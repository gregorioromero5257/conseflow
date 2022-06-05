<template>
<div class="">
    <!-- Card Inicio-->
    <div class="card">
        <!-- Inicio card-->
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Equipos por calibrar
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
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template slot="estado" slot-scope="props">
                            <div class="btn-group" role="group">
                                <button class="btn btn-danger" v-if="props.row.estado==1">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Sin Servicio
                                </button>
                                <button class="btn btn-danger" v-if="props.row.estado==2">
                                    <i class="fas fa-info-circle mr-1"></i>Vencido
                                </button>
                                <button class="btn btn-warning" v-if="props.row.estado==3">
                                    <i class="fas fa-info-circle mr-1"></i>Próximo
                                </button>
                            </div>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div>
            <!-- card-->
        </div>
    </div>

    <!-- Modal Calibracion -->
    <div class="modal fade" tabindex="-1" :class="{ mostrar: ver_modal_calibracion }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">

                <div>

                    <div class="modal-header">
                        <h4 class="modal-title">Detalles de equipo</h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-row">
                            <div class="col-md-10 mb-3">
                                <label>Descripción</label>
                                <input type='text' class='form-control' v-model='calibracion_modal.descripcion'>
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label>Marca</label>
                                <input type='text' class='form-control' v-model='calibracion_modal.marca'>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Modelo</label>
                                <input type="text" class="form-control" v-validate="'required'" data-vv-name="Modelo" v-model="calibracion_modal.modelo">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>No. Serie</label>
                                <input type='text' class='form-control' v-model='calibracion_modal.numero_serie'>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-10 mb-3">
                                <label>Rango Medición</label>
                                <input type="text" class="form-control" v-validate="'required'" data-vv-name="Rango Medición" v-model="calibracion_modal.rango_medicion">
                                <span class="text-danger">{{errors.first('Rango Medición')}}</span>
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
                                <span class="text-danger">{{errors.first('Frecuencia')}}</span>
                            </div>
                            <template v-if="calibracion_modal.frecuencia != '' && calibracion_modal.frecuencia != '2'">
                                <div class="col-md-4 mb-3">
                                    <label>Fecha Servicio</label>
                                    <input type="date" class="form-control" v-validate="'required'" data-vv-name="Fecha Servicio" v-model="calibracion_modal.fecha_servicio" @change="cambiarFecha()">
                                    <span class="text-danger">{{errors.first('Fecha Servicio')}}</span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Proxima fecha</label>
                                    <input type="date" class="form-control" v-validate="'required'" data-vv-name="Proxima Fecha" v-model="calibracion_modal.proxima_fecha">
                                    <span class="text-danger">{{errors.first('Proxima Fecha')}}</span>
                                </div>
                            </template>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label>Resguardo</label>
                                <input type="text" class="form-control" v-model="calibracion_modal.resguardo">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Observaciones</label>
                                <input type="text" class="form-control" v-model="calibracion_modal.observaciones">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="CerrarModalCalibracion()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                    </div>
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
            url: "/calidad/calib/",
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
                "estado",
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
                    "estado"
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
                    "estado"
                ],
                filterByColumn: true,
                texts: config.texts,
            }, //options
            // Modal
            titulo_modal_calibracion: "",
            tipoAccion_modal_calibracion: 0,
            calibracion_modal:
            {},
        }; // return
    }, //data
    computed:
    {},
    methods:
    {
        AbrirModalCalibracion(model = [])
        {
            this.ver_modal_calibracion = true;
            this.calibracion_modal = {
                id: model.id,
                rango_medicion: model.rango_medicion,
                descripcion:model.descripcion,
                marca: model.marca,
                modelo: model.modelo,
                numero_serie: model.numero_serie,
                fecha_servicio: model.fecha_servicio,
                proxima_fecha:model.proxima_fecha,
                frecuencia: model.frecuencia,
                resguardo: model.resguardo,
                observaciones: model.observaciones,
                articulo_id: model.articulo_id,
            };
        },

        CerrarModalCalibracion()
        {
            this.ver_modal_calibracion = false;
            Utilerias.resetObject(this.calibracion_modal);
        },
        Cargarcalibracion_modal()
        {
            axios.get(this.url + "pendientes").then(res =>
            {
                if (res.data.status)
                {
                    this.list_calibracion = res.data.equipos;
                }
                else
                {
                    toastr.error("Error al cargar los calibracion_modal")
                }
            })
        },
    }, // Fin metodos
    mounted()
    {
        this.Cargarcalibracion_modal();
    },
};
</script>
