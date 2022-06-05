<template>
    <div>
        <div>
            <!-- Ejemplo de tabla Listado -->
            <br>
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Capacitación de Empleado del Curso de: {{ data == null ? '' : data.nombre_curso}}
                    <button type="button" @click="abrirModal('cursos','registrar')"
                            class="btn btn-dark float-sm-right">
                        <i class="fas fa-plus"></i>&nbsp;Nuevo
                    </button>
                    <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
                        <i class="fas fa-arrow-left"></i>&nbsp;Atras
                    </button>

                </div>
                <div class="card-body">
                    <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                        <!--<template slot="id" slot-scope="props">
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button"
                                            class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-grip-horizontal"></i> Acciones
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <button type="button" @click="abrirModal('cursos','actualizar',props.row)"
                                                class="dropdown-item">
                                            <i class="icon-pencil"></i>&nbsp;Actualizar Capacitación.
                                        </button>

                                        <button type="button" class="dropdown-item" @click="eliminar(props.row.id)">
                                            <i class="fas fa-ban"></i>&nbsp;Borrar Capacitación.
                                        </button>

                                        <button v-if="props.row.adjunto_pdf == null" type="button" class="dropdown-item" @click="subirDocumento(props.row,1)">
                                            <i class="fas fa-file-upload"></i> Subir Documento.
                                        </button>
                                        <button v-if="props.row.adjunto_pdf != null" type="button" class="dropdown-item" @click="subirDocumento(props.row,2)">
                                            <i class="fas fa-file-upload"></i> Actualizar Documento.
                                        </button>
                                         <button v-if="props.row.adjunto_pdf != null" type="button" class="dropdown-item" @click="descargarDocumento(props.row)">
                                            <i class="fas fa-file-download"></i> Descargar Documento.
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </template>-->
                        <template slot="id" slot-scope="props">
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group dropup" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <button type="button" @click="abrirModal('cursos','actualizar',props.row)" class="dropdown-item">
                                <i class="icon-pencil"></i>&nbsp;Actualizar Capacitación.
                            </button>

                            <button type="button" class="dropdown-item" @click="eliminar(props.row.id)">
                                <i class="fas fa-ban"></i>&nbsp;Borrar Capacitación.
                            </button>
                            <template v-if="props.row.adjunto_pdf == null">
                              <a class="dropdown-item" @click="subirDocumento(props.row,1)" href="#">
                                <i class="fas fa-file-upload"></i>&nbsp;Subir Documento
                              </a>
                            </template>
                            <template v-if="props.row.adjunto_pdf != null">
                              <a class="dropdown-item" @click="subirDocumento(props.row,2)" href="#">
                                <i class="fas fa-file-upload"></i>&nbsp;Actualizar Documento
                              </a>
                            </template>
                            <template v-if="props.row.adjunto_pdf != null">
                              <a class="dropdown-item" @click="descargarDocumento(props.row.adjunto_pdf)" href="#">
                                <i class="fas fa-file-upload"></i>&nbsp;Descargar Documento
                              </a>
                            </template>
                            
                        </div>
                        </div>
                        </div>

                        </template>
                    </v-client-table>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
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
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">-->
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="formato">DOCUMENTO OBTENIDO</label>
                                        <input type="text" class="form-control" v-model="curso.formato" id="formato"
                                               placeholder="DOCUMENTO OBTENIDO" name="formato" v-validate="'required'"
                                               data-vv-as="Documento obtenido">
                                        <span class="text-danger">{{ errors.first('formato') }}</span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="empleado_id">EMPLEADO</label>
                                        <select class="form-control custom-select" @change="infoEmpleado"
                                                id="empleado_id" name="empleado_id" v-model="curso.empleado_id"
                                                v-validate="'required'" data-vv-as="empleado_id">
                                            <option value="0">--MOSTRAR TODOS--</option>
                                            <option v-for="item in listaEmpleado" :value="item.id" :key="item.id">
                                                {{item.nombre }} {{item.ap_paterno}} {{item.ap_materno}}
                                            </option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('empleado_id') }}</span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="curp">CURP</label>
                                        <input type="text" class="form-control" v-model="curso.curp" id="curp"
                                               placeholder="CURP" name="curp" v-validate="'required'"
                                               data-vv-as="Empleado" readonly>
                                        <span class="text-danger">{{ errors.first('curp') }}</span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="puesto">PUESTO</label>
                                        <input type="text" class="form-control" v-model="curso.puesto" id="puesto"
                                               placeholder="PUESTO" name="puesto" v-validate="'required'"
                                               data-vv-as="Empleado" readonly>
                                        <span class="text-danger">{{ errors.first('puesto') }}</span>
                                    </div>
                                </div>
                            </form>

                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i
                                    class="fas fa-window-close"></i>&nbsp;Cerrar
                            </button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="registrar(1)">
                                <i class="fas fa-save"></i>&nbsp;Guardar
                            </button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="registrar(0)">
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
    </div>
</template>
<script>
    import Utilerias from '../Herramientas/utilerias.js';

    var config = require('../Herramientas/config-vuetables-client').call(this);

    export default {
        data() {
            return {
                url: '/',
                PermisosCRUD: {},
                curso: {
                    empleado_id: 0,
                    formato: '',
                    curp: '',
                    puesto: '',
                },
                data: null,
                listaEmpleado: [],
                Metodo: '',
                disabled: 0,
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                isLoading: false,
                columns: ['id', 'nombre', 'documento'],
                tableData: [],
                listaEmpleados: [],
                options: {
                    headings: {
                        id: 'Acciones',
                        documento: 'Documento Obtenido',
                        nombre: 'Empleado',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre'],
                    filterable: ['nombre'],
                    filterByColumn: true,
                    texts: config.texts
                },
            }
        },

        methods: {
            detalleCapEmp(data) {

                this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
                let me = this;
                me.data = data;
                axios.get('/detalles/' + data.id).then(response => {
                    me.tableData = response.data;
                })
                    .catch(function (error) {
                        console.log(error);
                    });

                axios.get('/infEmp/').then(response => {
                    me.listaEmpleado = response.data;
                })
                    .catch(function (error) {
                        console.log(error);
                    });

            },

            infoEmpleado() {
                axios.get('/infEmp/' + this.curso.empleado_id).then(response => {
                    this.curso.curp = response.data.curp;
                    this.curso.puesto = response.data.puesto;
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            subirDocumento(data,metodo){
                console.log(data);
                swal({
                  title: data.documento,
                  input: 'file',
                  inputAttributes: {
                    'accept': 'application/pdf',
                    'aria-label': 'Upload your profile picture'
                  },
                  confirmButtonText: 'Subir Archivo',
                  showCancelButton: true,
                  inputValidator: (file) => {
                    return !file && 'Este Campo no puede estar Vacío'
                  }
                }).then((file) => {
                    let me = this;
                    if(file.value) {
                        let formData = new FormData();

                        formData.append('metodo',metodo);
                        formData.append('documento_po', file.value);
                        formData.append('id',data.id);

                        axios.post('/detallecap/documento',formData
                        ).then(function (response) {
                          if (response.data.status) {
                            if (metodo == 1) {
                                toastr.success('Documento Subido Correctamente');
                            }
                            if (metodo == 2) {
                                toastr.success('Documento Actualizado Correctamente');
                            }
                            me.detalleCapEmp(me.data);
                          }
                          else {
                            swal({
                              type: 'error',
                              html: response.data.errors.join('<br>'),
                            });
                          }
                        }).catch(function (error) {
                          console.log(error);
                        });
                    }
                    else if (file.dismiss === swal.DismissReason.cancel) {
                    }
                })
            },
            descargarDocumento(archivo){
                let me=this;
                axios({
                    url: '/detallesc'+ '/' + archivo,
                    method: 'GET',
                    responseType: 'blob', // importante
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
                    document.body.appendChild(link);
                    link.click();
                    //--Llama el metodo para borrar el archivo local una ves descargado--//
                    axios.get('/detallesc' + '/' + archivo + '/edit')
                    .then(response => {
                    })
                    .catch(function (error) {
                            console.log(error);
                    });
                    //--fin del metodo borrar--//
                });
            },

            registrar(nuevo) {
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;

                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/detallec/registrar' : '/detalleactualizacion/actualizar/' + this.id,
                            data: {
                                'empleado_id': this.curso.empleado_id,
                                'formato': this.curso.formato,
                                'id_capacitacion': this.data.id,
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.detalleCapEmp(me.data);
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

                        axios.put('/eliminardetalle/eliminar/', {
                            'id': id
                        }).then(function (response) {
                            toastr.info('Registro Eliminado Correctamente');
                            me.detalleCapEmp(me.data);
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                })
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
                                this.curso.empleado_id = data['empleado_id'];
                                this.curso.formato = data['documento'];
                                this.infoEmpleado();
                                this.Metodo = 'Actualizar';
                                break;
                            }
                        }
                    }
                }
            },

            maestro() {

                this.$emit('componentedetalle:change');
            },
        },
        mounted() {

        }
    }
</script>