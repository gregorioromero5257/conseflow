<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Máquinas de soldar
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalMaquinas(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Maquinas-->
                <div class=''>
                    <vue-element-loading :active="isLoadingMaquinas" />
                    <v-client-table :columns='columns_maquinas' :data='list_maquinas' :options='options_maquinas' ref='tbl_maquinas'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalMaquinas(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                        <button type='button' @click='AbrirModalCalibracion(true,props.row)' class='dropdown-item'>
                                            <i class='fas fa-plus'></i>&nbsp;Registrar calibración
                                        </button>
                                        <button type='button' @click='AbrirModalHistorial(props.row)' class='dropdown-item'>
                                            <i class='fas fa-list'></i>&nbsp;Ver calibraciones
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template slot='estado' slot-scope='props'>
                            <button class="btn btn-success">Activo</button>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Modal Maquinas -->
    <div class='modal fade' v-if="ver_modal_maquinas" tabindex='-1' :class="{'mostrar' : ver_modal_maquinas}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_maquinas'></h4>
                    <button type='button' class='close' @click='CerrarModalMaquinas()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <vue-element-loading :active="isLoadingModalMaquinas" />
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>No. control</label>
                            <div class='col-md-5'>
                                <input type='text' class='form-control' v-validate="'required'" data-vv-name="no_control" v-model='maquinas_modal.no_control'>
                                <span class="text-danger">{{ errors.first('no_control') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>No. serie</label>
                            <div class='col-md-5'>
                                <input type='text' data-vv-name="no_serie" v-validate="'required'" class='form-control' v-model='maquinas_modal.no_serie'>
                                <span class="text-danger">{{ errors.first('no_serie') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Marca</label>
                            <div class='col-md-5'>
                                <input type='text' data-vv-name="marca" v-validate="'required'" class='form-control' v-model='maquinas_modal.marca'>
                                <span class="text-danger">{{ errors.first('marca') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class='col-md-3 form-control-label'>Modelo</label>
                            <div class='col-md-5'>
                                <input type='text' data-vv-name="modelo" v-validate="'required'" class='form-control' v-model='maquinas_modal.modelo'>
                                <span class="text-danger">{{ errors.first('modelo') }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalMaquinas()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_maquinas== 1' class='btn btn-secondary' @click='GuardarMaquinas()'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_maquinas==2' class='btn btn-secondary' @click='GuardarMaquinas()'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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
                                            <th scope="col">Fecha</th>
                                            <th scope="col">No. Certificado</th>
                                            <th scope="col">Certificado</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(h,i) in listHistorial">
                                            <th scope="row">{{i+1}}</th>
                                            <td>{{h.fecha}}</td>
                                            <td>{{h.no_certificado}}</td>
                                            <td>
                                                <a class="link" style="cursor:pointer" @click="DescargarCertificado(h.id)">
                                                    {{h.documento}}
                                                </a>
                                            </td>
                                            <td>
                                                <span style="cursor:pointer" class="btn btn-sm bg-dark" @click="AbrirModalCalibracion(false,h)">
                                                    <i class="fas fa-edit text-white"></i>
                                                </span>
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

    <!-- Modal Calibración -->
    <div class='modal fade' v-if="ver_modal_calibracion_maquinas" tabindex='-1' :class="{'mostrar' : ver_modal_calibracion_maquinas}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>{{titulo_calibracion}}</h4>
                    <button type='button' class='close' @click='CerrarModalCalibracion()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <vue-element-loading :active="isLoadingCalibracionMaquinas" />
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Fecha</label>
                            <div class='col-md-3'>
                                <input type='date' class='form-control' v-validate="'required'" data-vv-name="fecha" v-model='calibracion_modal.fecha'>
                                <span class="text-danger">{{ errors.first('fecha') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>No. Certificado</label>
                            <div class='col-md-5'>
                                <input type='text' data-vv-name="no_certificado" v-validate="'required'" class='form-control' v-model='calibracion_modal.no_certificado'>
                                <span class="text-danger">{{ errors.first('no_certificado') }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Certificado</label>
                            <div class='col-md-5'>
                                <input type='file' accept="application/pdf" id="documento" data-vv-name="documento" class='form-control'>
                                <span class="text-danger">{{ errors.first('documento') }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalCalibracion()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' class='btn btn-secondary' @click='GuardarCalibracion()'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
            url: "/calidad/maquinas",
            // Tabla 
            isLoadingMaquinas: false,
            ver_modal_maquinas: 0,
            columns_maquinas: ['id', 'no_control', 'no_serie', 'marca', 'modelo', 'fecha_calibracion', 'no_certificado', 'estado'],
            list_maquinas: [],
            options_maquinas:
            {
                headings:
                {
                    id: 'Acciones',
                    no_control: 'No. control',
                    no_serie: 'No. serie',
                    marca: 'Marca',
                    modelo: 'Modelo',
                    fecha_calibracion: 'Fecha calibración',
                    no_certificado: 'No. certificado'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['no_serie', 'marca', 'modelo', 'fecha_calibracion', 'no_certificado'],
                filterable: ['no_serie', 'marca', 'modelo', 'fecha_calibracion', 'no_certificado'],
                filterByColumn: true,
                texts: config.texts
            }, //options 
            // Modal registro
            titulo_modal_maquinas: '',
            tipoAccion_modal_maquinas: 0,
            maquinas_modal:
            {},
            isLoadingModalMaquinas: false,
            //Modal calibracion
            calibracion_modal:
            {},
            ver_modal_calibracion_maquinas: false,
            isLoadingCalibracionMaquinas: false,
            // Historial
            ver_modal_historial: false,
            listHistorial: [],
            isLoadingHistorial: false,
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        // Obtiene las maquinas de soldar
        ObtenerMaquias()
        {
            this.isLoadingMaquinas = true;
            axios.get(this.url + "/obtener").then(res =>
            {
                this.isLoadingMaquinas = false;
                if (res.data.status)
                {
                    this.list_maquinas = res.data.maquinas;
                }
                else
                {
                    this.Error(res.data.mensaje);
                }
            })
        },
        AbrirModalMaquinas(nuevo, model = [])
        {
            this.ver_modal_maquinas = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_maquinas = 'Registrar máquina';
                this.tipoAccion_modal_maquinas = 1;
            }
            else
            {
                // Actualizar
                // this.maquinas_modal = model;
                this.titulo_modal_maquinas = 'Actualizar máquina';
                this.maquinas_modal.id = model.id;
                this.maquinas_modal.no_control = model.no_control;
                this.maquinas_modal.no_serie = model.no_serie;
                this.maquinas_modal.marca = model.marca;
                this.maquinas_modal.modelo = model.modelo;
                this.tipoAccion_modal_maquinas = 2;
            } // Fin if
        },

        CerrarModalMaquinas()
        {
            this.ver_modal_maquinas = false;
            Utilerias.resetObject(this.maquinas_modal);
        },
        GuardarMaquinas()
        {
            this.$validator.validate().then(result =>
            {
                if (!result) return;
                this.isLoadingModalMaquinas = true;
                axios.post(this.url + "/guardar", this.maquinas_modal).then(res =>
                {
                    this.isLoadingModalMaquinas = false;
                    if (res.data.status)
                    {
                        this.Success("Máquina guardada");
                        this.CerrarModalMaquinas();
                        this.ObtenerMaquias();
                    }
                    else
                    {
                        this.Error(res.data.mensaje, res.data);
                    }
                }).catch(r =>
                {
                    this.Error("ERROR", r);
                });
            });
        },
        AbrirModalCalibracion(nuevo, model)
        {
            console.error(nuevo);
            this.titulo_calibracion = nuevo ? "Registrar calibración" : "Actualizar calibración";
            this.ver_modal_calibracion_maquinas = true;
            this.calibracion_modal.maquina_id = model.maquina_id;
            if (!nuevo)
            {

                this.calibracion_modal.id = model.id;
                this.calibracion_modal.fecha = model.fecha;
                this.calibracion_modal.no_certificado = model.no_certificado;
                this.calibracion_modal.historial = !nuevo;
            }else
            {
                this.calibracion_modal.id = null;
            }
        },
        CerrarModalCalibracion(cerrar_historial = false)
        {
            console.error(cerrar_historial);
            this.ver_modal_calibracion_maquinas = false;
            if (cerrar_historial)
            {
                this.ver_modal_historial = false;
            }
            if (!cerrar_historial)
            {
                this.AbrirModalHistorial(
                {
                    id: this.calibracion_modal.maquina_id
                });
            }
            Utilerias.resetObject(this.calibracion_modal);
        },
        GuardarCalibracion()
        {
            this.$validator.validate().then(result =>
            {
                if (!result) return;
                let data = new FormData();

                data.append("id", this.calibracion_modal.id);
                data.append("fecha", this.calibracion_modal.fecha);
                data.append("no_certificado", this.calibracion_modal.no_certificado);
                let doc = $("#documento")[0].files[0];
                data.append("certificado", doc);
                data.append("maquina_id", this.calibracion_modal.maquina_id);
                if(doc==null) 
                {
                    toastr.warning("Seleccione el documento de calibración");
                    return;
                }

                axios.post(this.url + "/guardarcalibracion", data).then(res =>
                {
                    if (res.data.status)
                    {
                        this.Success("Calibración registrada");
                        this.CerrarModalCalibracion(this.calibracion_modal.historial);
                        this.ObtenerMaquias();
                    }
                    else
                    {
                        this.Error(res.data.mensaje);
                    }
                }).catch(r => this.Error("ERROR", r))
            });
        },
        AbrirModalHistorial(maquina)
        {
            this.ver_modal_historial = true;
            this.isLoadingHistorial = true;
            axios.get(this.url + "/historialcalib/" + maquina.id).then(res =>
            {
                this.isLoadingHistorial = false;
                if (res.data.status)
                {
                    this.listHistorial = res.data.historial;
                }
                else
                {
                    this.Error(res.data.mensaje, res.data);
                }
            })
        },
        CerrarModalHistorial()
        {
            this.ver_modal_historial = false;
        },
        DescargarCertificado(id)
        {
            window.open(this.url + "/descargarcert/" + id, '_blank');
        },
        Success(ms)
        {
            toastr.success(ms + " correctamente");
        },
        Error(ms, data = {})
        {
            toastr.error(ms);
            console.error(data);
        }
    }, // Fin metodos
    mounted()
    {
        this.ObtenerMaquias();
    }
}
</script>
