<template>
    <main class="main">
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <br>
            <div class="card" v-show="!capacitacionDetalle">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> PLAN DE CAPACITACIÓN.
                    <button v-show="PermisosCRUD.Create" type="button" @click="abrirModal('cursos','registrar')"
                            class="btn btn-dark float-sm-right">
                        <i class="fas fa-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                        <template slot="id" slot-scope="props">
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group dropup" role="group">
                                    <button id="btnGroupDrop1" type="button"
                                            class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-grip-horizontal"></i> Acciones
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <button v-show="PermisosCRUD.Update" type="button"
                                                @click="abrirModal('cursos','actualizar',props.row)"
                                                class="dropdown-item">
                                            <i class="icon-pencil"></i>&nbsp;Actualizar Capacitación.
                                        </button>

                                        <button v-show="PermisosCRUD.Delete" type="button" class="dropdown-item"
                                                @click="eliminar(props.row.id)">
                                            <i class="fas fa-ban"></i>&nbsp;Borrar Capacitación.
                                        </button>

                                        <button v-show="PermisosCRUD.Upload" type="button" class="dropdown-item"
                                                @click="detalleCapEmp(props.row)">
                                            <i class="fas fa-plus"></i> Empleados Asignados
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </template>
                    </v-client-table>
                </div>
            </div>

            <div v-show="capacitacionDetalle">
                <componentedetalle ref="componentedetalle" @componentedetalle:change="maestro"></componentedetalle>
            </div>

        </div>
        <!-- Fin ejemplo de tabla Listado -->

        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel"
             style="display: none;" aria-hidden="true">
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
                            <form>

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="empresa">EMPRESA</label>
                                        <select class="form-control custom-select" id="empresa" name="empresa"
                                                v-model="curso.empresa_id" v-validate="'required'" @change="infoEmpresa"
                                                data-vv-as="empresa">
                                            <option value="0">--MOSTRAR TODOS--</option>
                                            <option v-for="item in listaEmpresa" :value="item.id" :key="item.id">{{
                                                item.nombre }}
                                            </option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('empresa') }}</span>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="rfc">RFC</label>
                                        <input type="text" class="form-control" v-model="curso.rfc" id="rfc"
                                               placeholder="RFC" name="rfc"  data-vv-as="Empresa"
                                               readonly>
                                        <span class="text-danger">{{ errors.first('rfc') }}</span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="imss">REGISTRO PATRONAL IMSS</label>
                                        <input type="text" class="form-control" id="imss" v-model="curso.imss"
                                               placeholder="IMSS" name="imss"
                                               data-vv-as="Empresa" readonly>
                                        <span class="text-danger">{{ errors.first('imss') }}</span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="actividad">ACTIVIDAD/GIRO (DE LA EMPRESA)</label>
                                        <input type="text" class="form-control" v-model="curso.actividad" id="actividad"
                                               placeholder="ACTIVIDAD" name="actividad"
                                               data-vv-as="Empresa" readonly>
                                        <span class="text-danger">{{ errors.first('imss') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="curso">NOMBRE DE CAPACITACIÓN</label>
                                    <input type="text" class="form-control" v-model="curso.nomCurso" id="curso"
                                           placeholder="Capacitación" name="curso" v-validate="'required'"
                                           data-vv-as="Capacitacion">
                                    <span class="text-danger">{{ errors.first('curso') }}</span>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="duracion">DURACION DE LA CAPACITACIÓN</label>
                                        <input type="text" class="form-control" v-model="curso.duracion" id="duracion"
                                               name="duracion" v-validate="'required'" data-vv-as="Duracion">
                                        <span class="text-danger">{{ errors.first('duracion') }}</span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="fecha-inicial">INICIO DE CAPACITACIÓN</label>
                                        <input type="date" name="fecha_inicial" v-model="curso.fecha_inicio"
                                               id="fecha-inicial" class="form-control" v-validate="'required'"
                                               @change="validacionFecha" data-vv-as="Inicio">
                                        <span class="text-danger">{{ errors.first('fecha_inicial') }}</span>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="fecha-inicial">FIN DE CAPACITACIÓN.</label>
                                        <input type="date" name="fecha_final" v-model="curso.fecha_fin" id="fecha-final"
                                               class="form-control" v-validate="'required'" @change="validacionFecha"
                                               data-vv-as="Fin">
                                        <span class="text-danger">{{ errors.first('fecha_final') }}</span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="capacitador">NOMBRE DE CAPACITADOR</label>
                                        <input type="text" class="form-control" v-model="curso.capacitador"
                                               id="capacitador" placeholder="CAPACITADOR" name="capacitador"
                                               v-validate="'required'" data-vv-as="Capacitador">
                                        <span class="text-danger">{{ errors.first('capacitador') }}</span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="empCap">EMPRESA RESPONSABLE</label>
                                        <input type="text" class="form-control" v-model="curso.empCap" id="empCap"
                                               placeholder="EMPRESA RESPONSABLE" name="empCap" v-validate="'required'"
                                               data-vv-as="Empresa Responsable">
                                        <span class="text-danger">{{ errors.first('empCap') }}</span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <div>COSTO DE LA CAPACITACIÓN:</div>
                                        <div class="input-group ">
                                            <div class="input-group-addon">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" v-validate="'required'" name="costo"
                                                   class="form-control"
                                                   id="costo" v-model="curso.costo"
                                                   aria-label="Amount (to the nearest dollar)" data-vv-as="Costo">
                                        </div>
                                        <span class="text-danger">{{ errors.first('costo') }}</span>
                                    </div>
                                </div>
                            </form>

                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i
                                    class="fas fa-window-close"></i>&nbsp;Cerrar
                            </button>
                            <button type="button" v-if="tipoAccion==1" :disabled="deshabilitarbtn == 1"
                                    class="btn btn-secondary" @click="registrar(1)">
                                <i class="fas fa-save"></i>&nbsp;Guardar
                            </button>
                            <button type="button" v-if="tipoAccion==2"
                                    class="btn btn-secondary" @click="registrar(0)">
                                <i class="fas fa-save"></i>&nbsp;Actualizar
                            </button>
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

    const DetalleCapacitacion = r => require.ensure([], () => r(require('./DetalleCapacitacion')), 'rh');

    import Utilerias from '../Herramientas/utilerias.js';

    var config = require('../Herramientas/config-vuetables-client').call(this);

    export default {
        data() {
            return {
                url: '/',
                capacitacionDetalle: false,
                PermisosCRUD: {},
                curso: {
                    empresa_id: 0,
                    actividad: '',
                    nomCurso: '',
                    duracion: '',
                    fecha_inicio: null,
                    fecha_fin: null,
                    capacitador: '',
                    empCap: '',
                    rfc: '',
                    imss: '',
                    costo: '',
                },
                deshabilitarbtn: 1,
                listaEmpresa: [],
                Metodo: '',
                disabled: 0,
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                isLoading: false,
                columns: ['id', 'nombre_curso', 'empresa_capacitadora', 'fecha_inicio', 'fecha_fin', 'nombre_capacitador','costo', 'duracion',],
                tableData: [],
                options: {
                    headings: {
                        id: 'Acciones'
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre_curso', 'empresa', 'empresa_capacitadora', 'fecha_inicio', 'fecha_fin', 'nombre_capacitador'],
                    filterable: ['nombre_curso', 'empresa', 'empresa_capacitadora', 'fecha_inicio', 'fecha_fin', 'nombre_capacitador'],
                    filterByColumn: true,
                    texts: config.texts
                },
            }
        },

        components: {
            'componentedetalle': DetalleCapacitacion,
        },

        methods: {
            getData() {
                this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
                let me = this;
                axios.get('/capacitacion').then(response => {
                    me.tableData = response.data;
                })
                    .catch(function (error) {
                        console.log(error);
                    });

                axios.get('/infEmpresa').then(response => {
                    me.listaEmpresa = response.data;
                })
                    .catch(function (error) {
                        console.log(error);
                    });

            },

            infoEmpresa() {
                axios.get('/infEmpresa/' + this.curso.empresa_id).then(response => {
                    this.curso.rfc = response.data.rfc;
                    this.curso.imss = response.data.registro_imss;
                    this.curso.actividad = response.data.giro;
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            detalleCapEmp(dataDetalle) {
                this.capacitacionDetalle = true;
                var ChildDetalleCapacitacion = this.$refs.componentedetalle;
                ChildDetalleCapacitacion.detalleCapEmp(dataDetalle);
            },

            registrar(nuevo) {
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;

                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/capacitacion/registrar/' : '/capacitacionUpd/actualizar/' + this.id,
                            data: {
                                'empresa_id': this.curso.empresa_id,
                                'nombre_curso': this.curso.nomCurso,
                                'fecha_inicio': this.curso.fecha_inicio,
                                'fecha_fin': this.curso.fecha_fin,
                                'duracion': this.curso.duracion,
                                'nombre_capacitador': this.curso.capacitador,
                                'empresa_capacitadora': this.curso.empCap,
                                'costo': this.curso.costo,

                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.getData();
                                me.cerrarModal();
                                if (!nuevo) {
                                    toastr.success(' Actualizada Correctamente');
                                } else {
                                    toastr.success('Registrada Correctamente');
                                }
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

            eliminar(id) {
                swal({
                    title: 'Esta seguro de eliminar el registro?',
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

                        axios.put('/capacitacionEl/eliminar/', {
                            'id': id
                        }).then(function (response) {
                            toastr.info('Registro Eliminado Correctamente');
                            me.getData();
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                })
            },
            validacionFecha() {

                var fecha_inicio = this.curso.fecha_inicio;
                var fecha_fin = this.curso.fecha_fin;

                if (fecha_fin < fecha_inicio) {
                    toastr.warning('El Fin de Curso debe ser mayor al Inicio de Curso', 'ERROR!!!');
                    this.deshabilitarbtn = 1;
                } else {
                    this.deshabilitarbtn = 0;
                }


            },

            cerrarModal() {
                ///*
                this.modal = 0;
            },

            abrirModal(modelo, accion, data = []) {
                let me = this;

                switch (modelo) {
                    case "cursos": {
                        switch (accion) {
                            case 'registrar': {
                                this.modal = 1;
                                this.disabled = 0;
                                this.Metodo = 'Nuevo';
                                this.tituloModal = 'Registrar Nueva Capacitación';
                                Utilerias.resetObject(this.curso);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar': {
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Datos de  Capacitación';
                                this.tipoAccion = 2;
                                this.disabled = 1;
                                this.id = data['id'];
                                this.curso.empresa_id = data['empresa_id'];
                                this.curso.nomCurso = data['nombre_curso'];
                                this.curso.fecha_inicio = data['fecha_inicio'];
                                this.curso.fecha_fin = data['fecha_fin'];
                                this.curso.duracion = data['duracion'];
                                this.curso.capacitador = data['nombre_capacitador'];
                                this.curso.empCap = data['empresa_capacitadora'];
                                this.curso.costo = data['costo'];
                                this.infoEmpresa();
                                this.Metodo = 'Actualizar';
                                break;
                            }
                        }
                    }
                }
            },

            maestro() {
                this.capacitacionDetalle = false;
            }

        },
        mounted() {
            this.getData();
        }
    }
</script>