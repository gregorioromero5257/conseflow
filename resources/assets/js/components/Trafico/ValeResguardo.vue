<template>
<div class='main'>
    <div class="lol" v-show="false">
        <button @click="Lol">lol</button>
    </div>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Vales de Resguardo - {{empresa==1?"CONSERFLOW":"CSCT"}}
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle float-sm-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Empresa
                </button>
                <div class="dropdown-menu" v-model="empresa">
                    <button class="dropdown-item" type="button" @click="empresa = 1; ObtenerVales();">Conserflow</button>
                    <button class="dropdown-item" type="button" @click="empresa = 2; ObtenerVales();">CSCT</button>
                </div>
            </div>
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalResguardo(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Resguardo-->
                <div class=''>
                    <v-client-table :columns='columns_resguardo' :data='list_resguardo' :options='options_resguardo' ref='tbl_resguardo'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalResguardo(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template slot='documento' slot-scope='props'>
                            <button class="btn btn-dark btn-sm" @click="Descargar(props.row.id)">
                                <i class="fas fa-file-pdf"></i>
                                <i class="fas fa-download"></i>
                            </button>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Modal Resguardo -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_resguardo}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_resguardo'></h4>
                    <button type='button' class='close' @click='CerrarModalResguardo()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Fecha</label>
                            <div class='col-md-3'>
                                <input type='date' v-validate='"required"' class='form-control' v-model='resguardo_modal.fecha' data-vv-name='Fecha'>
                                <span class="text-danger">{{errors.first('Fecha')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Solicitud</label>
                            <div class='col-md-6'>
                                <v-select @input="ObtenerPolizas" v-validate='"required"' :options="list_solicitudes" label="unidad_solicitud" v-model='resguardo_modal.solicitud' data-vv-name='Solicitud'></v-select>
                                <span class="text-danger">{{errors.first('Solicitud')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Periodo</label>
                            <div class='col-md-4'>
                                <input type='text' disabled v-validate='"required"' class='form-control' v-model='resguardo_modal.solicitud.periodo' data-vv-name='Periodo'>
                                <span class="text-danger">{{errors.first('Periodo')}}</span>
                            </div>
                        </div>

                        <div class='form-row'>
                            <div class="form-group col-md-3">
                                <label class='col-md-3 form-control-label'>Marca</label>
                                <input type='text' disabled v-validate='"required"' class='form-control' v-model='resguardo_modal.solicitud.marca' data-vv-name='Marca'>
                                <span class="text-danger">{{errors.first('Marca')}}</span>
                            </div>

                            <div class="form-group col-md-5">
                                <label class='form-control-label'>Modelo</label>
                                <input type='text' disabled v-validate='"required"' class='form-control' v-model='resguardo_modal.solicitud.modelo' data-vv-name='Modelo'>
                                <span class="text-danger">{{errors.first('Mdelo')}}</span>
                            </div>

                            <div class="form-group col-md-3">
                                <label class='col-md-3 form-control-label'>Color</label>
                                <input type='text' disabled v-validate='"required"' class='form-control' v-model='resguardo_modal.solicitud.color' data-vv-name='Color'>
                                <span class="text-danger">{{errors.first('Color')}}</span>
                            </div>
                        </div>

                        <div class='form-row'>
                            <div class='form-group col-md-4'>
                                <label class='form-control-label'>No. serie</label>
                                <input type='text' disabled v-validate='"required"' class='form-control' v-model='resguardo_modal.solicitud.no_serie' data-vv-name='No_serie'>
                                <span class="text-danger">{{errors.first('No_serie')}}</span>
                            </div>
                            <div class='form-group col-md-4'>
                                <label class='form-control-label'>No. motor</label>
                                <input type='text' disabled v-validate='"required"' class='form-control' v-model='resguardo_modal.solicitud.no_motor' data-vv-name='No_motor'>
                                <span class="text-danger">{{errors.first('No_motor')}}</span>
                            </div>

                            <div class='form-group col-md-4'>
                                <label class='form-control-label'>Capacidad de carga</label>
                                <input type='text' disabled v-validate='"required"' class='form-control' v-model='resguardo_modal.solicitud.capacidad_carga' data-vv-name='Capacidad_carga'>
                                <span class="text-danger">{{errors.first('Capacidad_carga')}}</span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class='form-group col-md-4'>
                                <label class='form-control-label'>Cilindros</label>
                                <input type='text' disabled v-validate='"required"' class='form-control' v-model='resguardo_modal.solicitud.cilindros' data-vv-name='Cilindros'>
                                <span class="text-danger">{{errors.first('Cilindros')}}</span>
                            </div>

                            <div class='form-group col-md-4'>
                                <label class='form-control-label'>Tarjeta</label>
                                <input type='text' disabled v-validate='"required"' class='form-control' v-model='resguardo_modal.solicitud.tarjeta' data-vv-name='Tarjeta'>
                                <span class="text-danger">{{errors.first('Tarjeta')}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class='form-group col-md-8'>
                                <label class='form-control-label'>Poliza seguro</label>
                                <v-select label="nombre" data-vv-name="poliza_seguro" :options="list_polizas" v-model="resguardo_modal.poliza"></v-select>
                                <span class="text-danger">{{errors.first('poliza_seguro')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Responsable</label>
                            <div class='col-md-8'>
                                <input type='text' disabled v-validate='"required"' class='form-control' v-model='resguardo_modal.solicitud.empledo_solicita' data-vv-name='responsable'>
                                <span class="text-danger">{{errors.first('responsable')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-2 form-control-label'>Entrega</label>
                            <div class='col-md-8'>
                                <v-select label="nombre" v-validate='"required"' :options="list_empleados" v-model='resguardo_modal.entrega' data-vv-name='Entrega'></v-select>
                                <span class="text-danger">{{errors.first('Entrega')}}</span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalResguardo()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_resguardo== 1' class='btn btn-secondary' @click='GuardarResguardo(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_resguardo==2' class='btn btn-secondary' @click='GuardarResguardo(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div> <!-- Main -->
</template>

<style>
.lol {
    margin: 1rem;
    padding: 1rem;
    border: black 10px solid;
    background-color: #9fd5d1;
    color: white
}
</style>

<script>
/* CAMBIAR UBICACIÓN  */
var config = require('../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            // Tabla 
            empresa: 1,
            url: "/vehiculos/valeresguardo",
            ver_modal_resguardo: 0,
            columns_resguardo: ['id', 'fecha', 'periodo', "unidad", 'poliza_seguro', "responsable", 'entrega', "documento"],
            list_resguardo: [],
            list_empleados: [],
            list_polizas: [],
            list_empleados: [],
            list_solicitudes: [],
            options_resguardo:
            {
                headings:
                {
                    id: 'Acciones',
                    fecha: 'Fecha',
                    solicitud_id: 'Solicitud',
                    periodo: 'Periodo',
                    marca: 'Marca',
                    modelo: 'Modelo',
                    color: 'Color',
                    placas: 'Placas',
                    no_serie: 'No_serie',
                    no_motor: 'No_motor',
                    capacidad_carga: 'Capacidad_carga',
                    cilindros: 'Cilindros',
                    poliza_seguro: 'Poliza_seguro',
                    tarjeta: 'Tarjeta',
                    responsable_id: 'Responsable',
                    entrega_id: 'Entrega'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal
            titulo_modal_resguardo: '',
            tipoAccion_modal_resguardo: 0,
            resguardo_modal:
            {
                solicitud:
                {},
                poliza:
                {},
            },
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        ObtenerSolicitudes()
        {
            axios.get(this.url + "/obenersolicitudes/" + this.empresa).then(res =>
            {
                if (res.data.status)
                {
                    this.list_solicitudes = res.data.solicitudes;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },

        AbrirModalResguardo(nuevo, model = [])
        {
            this.ObtenerSolicitudes();
            this.ObtenerEmpleados();
            // Cargar solicitudes
            this.ver_modal_resguardo = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_resguardo = 'Registrar Resguardo';
                this.tipoAccion_modal_resguardo = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_resguardo = 'Actualizar Resguardo';
                this.tipoAccion_modal_resguardo = 2;

                this.resguardo_modal.id = model.id;
                this.resguardo_modal.fecha = model.fecha;
                this.resguardo_modal.solicitud = {
                    vs_id: model.vs_id,
                    vs_fecha_solicitud: model.fecha_solicitud,
                    s_folio: model.vs_folio,
                    periodo: model.periodo,
                    vs_solicita_id: "XXXX",
                    unidad_id: model.u_id,
                    unidad: model.u_nombre,
                    placas: model.placas,
                    marca: model.marca,
                    modelo: model.modelo,
                    color: model.color,
                    no_serie: model.numero_serie,
                    no_motor: model.no_motor,
                    capacidad_carga: model.capacidad,
                    cilindros: model.cilindros,
                    tarjeta: model.u_tarjeta,
                    empledo_solicita: model.responsable,
                    unidad_solicitud: model.vs_folio + " - " + model.placas
                };
                this.resguardo_modal.poliza = {
                    id: model.p_id,
                    fecha_inicio: model.fecha_inicio,
                    fecha_termino: model.fecha_termino,
                    proveedor: model.proveedor,
                    numero_poliza: model.poliza_seguro,
                    nombre: model.proveedor + " " + model.poliza_seguro + " "
                };
                this.resguardo_modal.entrega = {
                    id: model.ee_id,
                    nombre: model.entrega
                };
            } // Fin if
        },

        CerrarModalResguardo()
        {
            this.ver_modal_resguardo = false;
            this.resguardo_modal = {
                solicitud:
                {}
            };
        },

        /**
         * Obtener las polizas del vehiculo seleccionado
         */
        ObtenerPolizas()
        {
            axios.get(this.url + "/obenerpolizas/" + this.resguardo_modal.solicitud.unidad_id).then(res =>
            {
                if (res.data.status)
                {
                    this.list_polizas = res.data.polizas;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },

        /**
         * Obtener los empleados
         */
        ObtenerEmpleados()
        {
            axios.get("/vertodosempleados").then(res =>
            {
                let asd = [];
                res.data.forEach(e =>
                {
                    asd.push(
                    {
                        id: e.id,
                        nombre: e.nombre + " " + e.ap_paterno + " " + e.ap_materno,
                    });
                });
                this.list_empleados = asd;
            });
        },

        /**
         * Guarda el reporte
         */
        GuardarResguardo(nuevo)
        {
            this.$validator.validate().then(isValid =>
            {
                console.error(isValid);
                if (!isValid) return;
                let data = new FormData();
                if (!nuevo) data.append("id", this.resguardo_modal.id);
                data.append("solicitud_id", this.resguardo_modal.solicitud.vs_id)
                data.append("fecha", this.resguardo_modal.fecha)
                data.append("entega_id", this.resguardo_modal.entrega.id);
                data.append("poliza_id", this.resguardo_modal.poliza.id);
                data.append("empresa", this.empresa);

                axios.post(this.url + "/guardar", data).then(res =>
                {
                    if (res.data.status)
                    {
                        toastr.success("Guardado correctamente");
                        this.CerrarModalResguardo();
                        this.ObtenerVales();
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                })
            });
        },

        /**
         * Obteiene todos los vales de la empresa actual
         */
        ObtenerVales()
        {
            this.isLoadingVales = true;
            axios.get(this.url + "/obtener/" + this.empresa).then(res =>
            {
                this.isLoadingVales = false;
                if (res.data.status)
                {
                    this.list_resguardo = res.data.vales;
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            })
        },
        Descargar(id)
        {
            window.open(this.url + "/descargar/" + id, "_blank");
        },
        Lol()
        {
            Swal.fire(
            {
                title: 'Custom width, padding, background.',
                width: 600,
                padding: '3em',
                background: '#fff url(/img/trees.png)',
                backdrop: `
    rgba(0,0,123,0.4)
    url("/img/asd.gif")
    top left
    
    no-repeat
  `
            })
        },
    }, // Fin metodos
    mounted()
    {
        this.ObtenerVales();
    }
}
</script>
