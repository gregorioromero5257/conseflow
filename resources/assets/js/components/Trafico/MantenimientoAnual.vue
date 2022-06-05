<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card' v-show="tipoCard==2">
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i>
            {{folio}}
            <button type='button' class='btn btn-dark float-sm-right mr-1' @click='tipoCard=1;'>
                <i class='fas fa-arrow-left'>&nbsp;</i>Regresar
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Anual-->
                <div class=''>
                    <v-client-table :columns='columns_anual' :data='list_anual' :options='options_anual' ref='tbl_anual'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalAnual(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                        <button type='button' @click='Descargar(props.row.id)' class='dropdown-item'>
                                            <i class='fas fa-file-pdf'></i>&nbsp;Descargar
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template slot='mes' slot-scope='props'>
                            <p>{{ObtenerMes(props.row.mes)}}</p>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <div class='card' v-show="tipoCard==1">
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i>
            Programación de Servicios Vehículares - {{empresa==1?"CONSERFLOW":"CSCT"}}
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle float-sm-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Empresa
                </button>
                <div class="dropdown-menu" v-model="empresa">
                    <button class="dropdown-item" type="button" @click="empresa = 1; ObtenerAnios();">Conserflow</button>
                    <button class="dropdown-item" type="button" @click="empresa = 2; ObtenerAnios();">CSCT</button>
                </div>
            </div>
            <button type='button' class='btn btn-dark float-sm-right mr-1' @click='AbrirModalAnual(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Anual-->
                <div class=''>
                    <v-client-table :columns='columns_anios' :data='list_anios' :options='options_anios'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='ObtenerMantenimientos(props.row.anio)' class='dropdown-item'>
                                            <i class='fas fa-eye'></i>&nbsp;Ver Detalles
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template slot='descripcion' slot-scope='props'>
                            <p>Programa de servicios vehiculares {{props.row.anio}}</p>
                        </template>
                        <template slot='descargar' slot-scope='props'>
                            <button class="btn btn-sm btn-dark" @click="Descargar(props.row.anio)">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Modal Anual -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_anual}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_anual'></h4>
                    <button type='button' class='close' @click='CerrarModalAnual()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Vehículo</label>
                            <div class='col-md-9'>
                                <v-select data-vv-name='vehiculo' v-model="anual_modal.vehiculo" :options="list_vehiculos" label="nombre" v-validate='"required"'></v-select>
                                <span class="text-danger">{{errors.first('vehiculo')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Placas</label>
                            <div class='col-md-9'>
                                <input disabled type='text' v-validate='"required"' class='form-control' v-model='anual_modal.vehiculo.placas' data-vv-name='placas'>
                                <span class="text-danger">{{errors.first('placas')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Servicio</label>
                            <div class='col-md-9'>
                                <input type='text' v-validate='"required"' class='form-control' v-model='anual_modal.servicio' data-vv-name='servicio'>
                                <span class="text-danger">{{errors.first('servicio')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Mes</label>
                            <div class='col-md-3'>
                                <select class="form-control col" v-model="anual_modal.mes">
                                    <option v-for="mes in list_meses" :value="mes">{{ObtenerMes(mes)}}</option>
                                </select>
                                <span class="text-danger">{{errors.first('mes')}}</span>
                            </div>
                            <label class='col-md-1 form-control-label'>Año</label>
                            <div class="col-md-3">

                                <select class="form-control col" v-model="anual_modal.anio">
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                            </div>
                            <span class="text-danger">{{errors.first('anio')}}</span>
                        </div>
                    </div>

                    <div class='form-group row'>
                        <label class='col-md-2 form-control-label'>Observaciones</label>
                        <div class='col-md-10'>
                            <textarea type='text' class='form-control' v-model='anual_modal.observaciones' data-vv-name='observaciones'> </textarea>
                        </div>
                    </div>
                </div>

                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalAnual()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_anual== 1' class='btn btn-secondary' @click='GuardarAnual(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_anual==2' class='btn btn-secondary' @click='GuardarAnual(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                </div>
            </div>

            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div> <!-- Main -->
</template>

<script>
import TraspasosVue from '../Almacen/Transferencias/Traspasos.vue';
/* CAMBIAR UBICACIÓN  */
var config = require('../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            folio: "",
            tipoCard: 1,
            url: "/vehiculos/mttoanual",
            // Tabla 
            ver_modal_anual: 0,
            empresa: 1,
            columns_anual: ['id', 'vehiculo', 'placas', 'servicio', 'mes', 'anio', 'observaciones'],
            columns_anios: ['id', "descripcion", "descargar"],
            list_anual: [],
            list_anios: [],
            options_anual:
            {
                headings:
                {
                    id: 'Acciones',
                    descripcion: "Descripción",
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            }, //options 
            options_anios:
            {
                headings:
                {
                    id: 'Acciones',
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                sortable: ['id', "descripcion", "descargar"],
                filterable: ['id', "descripcion", "descargar"],
                texts: config.texts
            }, //options 
            // Modal
            list_vehiculos: [],
            list_placas: [],
            titulo_modal_anual: '',
            tipoAccion_modal_anual: 0,
            anual_modal:
            {
                vehiculo:
                {
                    placas: ""
                }
            },
            list_meses: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] // return
        }
    }, //data
    computed:
    {},
    methods:
    {
        /**
         * Obtiene los reportes por año
         */
        ObtenerAnios()
        {
            axios.get(this.url + "/obteneranios/" + this.empresa).then(res =>
            {
                if (res.data.status)
                {
                    this.list_anios = res.data.anios
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        /**
         * Obtener los mantenimientos de la empresa actual
         */
        ObtenerMantenimientos(anio)
        {
            this.tipoCard = 2;
            this.isMantenimientosLoading = true;
            axios.get(this.url + "/obtener/" + this.empresa + "&" + anio).then(res =>
            {
                this.isMantenimientosLoading = false;
                if (res.data.status)
                {
                    this.folio = "Programa de servicios vehiculares " + anio;
                    this.list_anual = res.data.mantenimientos;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },
        AbrirModalAnual(nuevo, model = [])
        {
            this.ObtenerVehiculos();
            this.ver_modal_anual = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_anual = 'Registrar';
                this.tipoAccion_modal_anual = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_anual = 'Actualizar';
                this.tipoAccion_modal_anual = 2;
                this.anual_modal = {
                    id: model.id,
                    vehiculo:
                    {
                        id: model.unidad_id,
                        nombre: model.nombre,
                        placas: model.placas
                    },
                    mes: model.mes,
                    anio: model.anio,
                    observaciones: model.observaciones,
                    servicio: model.servicio,
                }
            } // Fin if
        },

        CerrarModalAnual()
        {
            this.ver_modal_anual = false;
            this.anual_modal = {
                vehiculo:
                {
                    placas: ""
                },
                mes: this.list_meses[0],
                anio: "2018",
            }
        },

        /**
         * Obtiene todos los vehiculos
         */
        ObtenerVehiculos()
        {
            axios.get(this.url + "/obtenerunidades/" + this.empresa).then(res =>
            {
                this.list_vehiculos = res.data.unidades;
            });
        },

        /**
         * Registra o actualiza
         */
        GuardarAnual(nuevo)
        {
            this.$validator.validate().then(isValid =>
            {
                if (!isValid) return;
                let data = new FormData();
                if (!nuevo) data.append("id", this.anual_modal.id);
                data.append("unidad_id", this.anual_modal.vehiculo.id)
                data.append("servicio", this.anual_modal.servicio)
                data.append("mes", this.anual_modal.mes)
                data.append("anio", this.anual_modal.anio)
                data.append("empresa", this.empresa)
                data.append("observaciones", this.anual_modal.observaciones)

                this.isGuardarLoading = true;
                axios.post(this.url + "/guardar", data).then(res =>
                {
                    if (res.data.status)
                    {
                        this.isGuardarLoading = false;
                        toastr.success("Guardado correctamente");
                        this.ObtenerMantenimientos(this.anual_modal.anio);
                        this.CerrarModalAnual();
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                });

            })
        },

        /**
         * Obtiene el nombre del mes ingresado
         */
        ObtenerMes(n)
        {
            let meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            ];
            return meses[n - 1];
        },

        /**
         * Descargar formato
         */
        Descargar(anio)
        {
            window.open(this.url + "/descargar/" + this.empresa + "&" + anio, '_blank');
        }
    }, // Fin metodos
    mounted()
    {
        this.ObtenerAnios();
    }
}
</script>
