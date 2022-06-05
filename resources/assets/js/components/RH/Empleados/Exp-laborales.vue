<template>
    <main>

        <button type="button" @click="abrirModal('explaboral','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
        </button>
        <vue-element-loading :active="isLoadingDetalle"/>
        <v-client-table :columns="columnsExperiencias" :data="tableDataExperiencias" :options="optionsExperiencias" ref="myTableExperiencias">
            <template slot="condicion" slot-scope="props" >
                <template v-if="props.row.condicion">
                   <button type="button" class="btn btn-outline-success">Activo</button>
                </template>
                <template v-else>
                   <button type="button" class="btn btn-outline-danger">Desactivado</button>
                </template>
            </template>
            <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group dropup" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <button type="button" @click="abrirModal('explaboral','actualizar',props.row)" class="dropdown-item">
                    <i class="icon-pencil"></i>&nbsp; Actualizar Experiencia Laboral
                </button>
                <template v-if="props.row.condicion">
                    <button type="button" class="dropdown-item" @click="activarExperiencia(props.row.id, 0)">
                        <i class="fas fa-ban"></i>&nbsp; Desactivar
                    </button>
                </template>
                <template v-else>
                    <button type="button" class="dropdown-item" @click="activarExperiencia(props.row.id, 1)">
                        <i class="icon-check"></i>&nbsp; Activar
                    </button>
                </template>
                    </div>
                    </div>
            </div>
            </template>
        </v-client-table>

            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dark modal-lg" role="document">
                    <div class="modal-content">
                        <div>
                        <vue-element-loading :active="isLoading"/>
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
                            <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="empresa">Empresa</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="empresa" v-model="experiencia.empresa" class="form-control" placeholder="Empresa" autocomplete="off" id="empresa">
                                        <span class="text-danger">{{ errors.first('empresa') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="jefe_inmediato">Jefe inmediato</label>
                                    <div class="col-md-9">
                                        <input type="text" name="jefe_inmediato"  v-validate="'required'" v-model="experiencia.jefe_inmediato" class="form-control" placeholder="Jefe inmediato" autocomplete="off" id="jefe_inmediato" data-vv-as="Jefe inmediato">
                                        <span class="text-danger">{{ errors.first('jefe_inmediato') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="fecha_inicio">Fecha inicio</label>
                                    <div class="col-md-9">
                                        <input type="date" v-validate="'required'" name="fecha_inicio" v-model="experiencia.fecha_inicio" class="form-control" placeholder="Fecha inicio" autocomplete="off" id="fecha_inicio" data-vv-as="Fecha inicio">
                                        <span class="text-danger">{{ errors.first('fecha_inicio') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="fecha_termino">Fecha termino</label>
                                    <div class="col-md-9">
                                        <input type="date" v-validate="'required'" name="fecha_termino" v-model="experiencia.fecha_termino" class="form-control" placeholder="Fecha termino" autocomplete="off" id="fecha_termino" data-vv-as="Fecha termino">
                                        <span class="text-danger">{{ errors.first('fecha_termino') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="referencia">Referencia</label>
                                    <div class="col-md-9">
                                        <input type="text" name="referencia"  v-validate="'required'" v-model="experiencia.referencia" class="form-control" placeholder="Referencia" autocomplete="off" id="referencia">
                                        <span class="text-danger">{{ errors.first('referencia') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="tel_referencia">Tel. referencia</label>
                                    <div class="col-md-9">
                                        <input type="text" name="tel_referencia" v-validate="'required'" v-model="experiencia.tel_referencia" class="form-control" placeholder="Tel. referencia" autocomplete="off" id="tel_referencia" data-vv-as="Tel. referencia">
                                        <span class="text-danger">{{ errors.first('tel_referencia') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="puesto">Puesto</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="puesto" v-model="experiencia.puesto" class="form-control" placeholder="Puesto" autocomplete="off" id="puesto">
                                        <span class="text-danger">{{ errors.first('puesto') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="actividades">Actividades</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" v-validate="'required'" name="actividades" v-model="experiencia.actividades" rows="3" placeholder="Actividades" id="actividades"></textarea>

                                        <span class="text-danger">{{ errors.first('actividades') }}</span>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarExperiencia(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarExperiencia(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                        </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

    export default {
        data (){
            return {
                empleado: null,
                detalle: false,
                experiencia: {
                    id: 0,
                    empresa: '',
                    fecha_inicio: '',
                    fecha_termino: '',
                    jefe_inmediato: '',
                    referencia: '',
                    tel_referencia: '',
                    puesto: '',
                    actividades: '',
                    empleado_id: 0,
                },
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                isLoadingDetalle: false,
                columnsExperiencias: [
                    'id',
                    'empresa',
                    'puesto',
                    'actividades',
                    'fecha_inicio',
                    'fecha_termino',
                    'condicion',
                    ],
                tableDataExperiencias: [],
                optionsExperiencias: {
                    headings: {
                        'id': 'Acciones',
                        'empresa': 'Empresa',
                        'puesto': 'Puesto',
                        'actividades': 'Actividades',
                        'fecha_inicio': 'Inicio',
                        'fecha_termino': 'Termino',
                        'condicion':'Estado',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['empresa', 'fecha_inicio', 'fecha_termino' ],
                    filterable: ['empresa', 'fecha_inicio', 'fecha_termino', 'condicion'],
                    filterByColumn: true,
                    listColumns: {
                        condicion: config.columnCondicion
                    },
                    texts:config.texts
                },
            }
        },
        computed:{
        },
        methods : {
            guardarExperiencia(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/explab/registrar' : '/explab/actualizar',
                            data: {
                                'id': this.experiencia.id,
                                'empleado_id': this.empleado.id,
                                'empresa': this.experiencia.empresa,
                                'jefe_inmediato': this.experiencia.jefe_inmediato,
                                'fecha_inicio': this.experiencia.fecha_inicio,
                                'fecha_termino': this.experiencia.fecha_termino,
                                'referencia': this.experiencia.referencia,
                                'tel_referencia': this.experiencia.tel_referencia,
                                'puesto': this.experiencia.puesto,
                                'actividades': this.experiencia.actividades,
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.cargarExperiencias(me.empleado);
                            } else {
                                swal({
                                    type: 'error',
                                    html: response.data.errors.join('<br>'),
                                });
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                });
            },
            activarExperiencia(id, activar){
               swal({
                title: activar ? 'Esta seguro de activar esta Experiencia?' : 'Esta seguro de desactivar esta Experiencia?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put(activar ? '/explab/activar' : '/explab/desactivar',{
                        'id': id
                    }).then(function (response) {
                        swal(
                        activar ? 'Activado!' : 'Desactivado!',
                        activar ? 'El registro ha sido activado con éxito.' : 'El registro ha sido desactivado con éxito.',
                        'success'
                        );
                        me.cargarExperiencias(me.empleado);
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
                })
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                Utilerias.resetObject(this.experiencia);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "explaboral":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar experiencia laboral';
                                Utilerias.resetObject(this.experiencia);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                Utilerias.resetObject(this.experiencia);
                                this.modal=1;
                                this.tituloModal= 'Actualizar experiencia laboral';
                                this.tipoAccion=2;
                                this.experiencia.id=data['id'];
                                this.experiencia.empleado_id = data['empleado_id'];
                                this.experiencia.empresa = data['empresa'];
                                this.experiencia.jefe_inmediato = data['jefe_inmediato'];
                                this.experiencia.fecha_inicio = data['fecha_inicio'];
                                this.experiencia.fecha_termino = data['fecha_termino'];
                                this.experiencia.referencia = data['referencia'];
                                this.experiencia.tel_referencia = data['tel_referencia'];
                                this.experiencia.puesto = data['puesto'];
                                this.experiencia.actividades = data['actividades'];
                                break;
                            }
                        }
                    }
                }
            },
            cargarExperiencias(dataEmpleado = []) {
                this.detalle = true;
                this.isLoadingDetalle = true;
                let me=this;
                this.empleado = dataEmpleado;
                axios.get('/explab/' + dataEmpleado.id).then(response => {
                    me.tableDataExperiencias = response.data;
                    me.isLoadingDetalle = false;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        },
        mounted() {
        }
    }
</script>
