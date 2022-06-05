<template>
    <main>
            
        <button type="button" @click="abrirModal('escolaridad','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
        </button>

        <vue-element-loading :active="isLoadingDetalle"/>
        <v-client-table :columns="columnsEscolaridades" :data="tableDataEscolaridades" :options="optionsEscolaridades" ref="myTableEscolaridades">
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
                <button type="button" @click="abrirModal('escolaridad','actualizar',props.row)" class="dropdown-item">
                    <i class="icon-pencil"></i>&nbsp; Actualizar Escolaridad
                </button>
                <template v-if="props.row.condicion">
                    <button type="button" class="dropdown-item" @click="activarEscolaridad(props.row.id, 0,props.row.empleado_id)">
                        <i class="fas fa-ban"></i>&nbsp; Desactivar
                    </button>
                </template>
                <template v-else>
                    <button type="button" class="dropdown-item" @click="activarEscolaridad(props.row.id, 1,props.row.empleado_id)">
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
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="titulo">Titulo</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="titulo" v-model="escolaridad.titulo" class="form-control" placeholder="Titulo" autocomplete="off" id="titulo">
                                        <span class="text-danger">{{ errors.first('titulo') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="cedula_prof">Cedula profesional</label>
                                    <div class="col-md-9">
                                        <input type="text" name="cedula_prof" v-model="escolaridad.cedula_prof" class="form-control" placeholder="Cedula profesional" autocomplete="off" id="cedula_prof">
                                        <span class="text-danger">{{ errors.first('cedula_prof') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="fecha_inicio">Fecha inicio</label>
                                    <div class="col-md-9">
                                        <input type="date" v-validate="'required'" name="fecha_inicio" v-model="escolaridad.fecha_inicio" class="form-control" placeholder="Fecha inicio" autocomplete="off" id="fecha_inicio">
                                        <span class="text-danger">{{ errors.first('fecha_inicio') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="fecha_termino">Fecha termino</label>
                                    <div class="col-md-9">
                                        <input type="date" v-validate="'required'" name="fecha_termino" v-model="escolaridad.fecha_termino" class="form-control" placeholder="Fecha termino" autocomplete="off" id="fecha_termino">
                                        <span class="text-danger">{{ errors.first('fecha_termino') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="grado_id">Grado</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="grado_id"  name="grado_id" v-model="escolaridad.grado_id" v-validate="'excluded:0'" data-vv-as="Grado">
                                            <option v-for="item in listaGrados" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('grado_id') }}</span>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarEscolaridad(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarEscolaridad(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                url: '/escolaridad',
                empleado: null,
                detalle: false,
                escolaridad: {
                    id: 0,
                    grupo_id: 0,
                    fecha_inicio: '',
                    fecha_termino: '',
                    titulo: '',
                    cedula_prof: '',
                    empleado_id: 0,
                },
                listaGrados: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                isLoadingDetalle: false,
                columnsEscolaridades: [
                    'id',
                    'titulo',
                    'cedula_prof',
                    'grado',
                    'fecha_inicio',
                    'fecha_termino',
                    'condicion',
                    ],
                tableDataEscolaridades: [],
                optionsEscolaridades: {
                    headings: {
                        'id': 'Acciones',
                        'titulo': 'Titulo',
                        'cedula_prof': 'Cedula prof.',
                        'fecha_inicio': 'Inicio',
                        'fecha_termino': 'Termino',
                        'grado': 'Grado',
                        'condicion':'Estado',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['titulo', 'cedula_prof', 'fecha_inicio', 'fecha_termino', 'grado'],
                    filterable: ['titulo', 'cedula_prof', 'fecha_inicio', 'fecha_termino', 'grado', 'condicion'],
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
            getListaGrados() {
                let me=this;
                axios.get('/grados').then(response => {
                    me.listaGrados = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarEscolaridad(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? me.url+'/'+this.escolaridad.empleado_id+'/escolaridades' : me.url+'/'+this.escolaridad.id+'/'+'escolaridades/'+this.escolaridad.empleado_id,
                            data: {
                                'id': this.escolaridad.id,
                                'empleado_id': this.empleado.id,
                                'grado_id': this.escolaridad.grado_id,
                                'titulo': this.escolaridad.titulo,
                                'cedula_prof': this.escolaridad.cedula_prof,
                                'fecha_inicio': this.escolaridad.fecha_inicio,
                                'fecha_termino': this.escolaridad.fecha_termino
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.cargarEscolaridades(me.empleado);
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
            activarEscolaridad(id, activar,empleado){
               swal({
                title: activar ? 'Esta seguro de activar esta Escolaridad?' : 'Esta seguro de desactivar esta Escolaridad?',
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

                    axios.get(me.url+'/'+id+'/'+'escolaridades/'+empleado+'/edit').then(function (response) {
                        swal(
                        activar ? 'Activado!' : 'Desactivado!',
                        activar ? 'El registro ha sido activado con éxito.' : 'El registro ha sido desactivado con éxito.',
                        'success'
                        );
                        me.cargarEscolaridades(me.empleado);
                    }).catch(function (error) {
                        console.log(error);
                    });                       
                }
                }) 
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                Utilerias.resetObject(this.escolaridad);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "escolaridad":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar escolaridad';
                                Utilerias.resetObject(this.escolaridad);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                Utilerias.resetObject(this.escolaridad);
                                this.modal=1;
                                this.tituloModal= 'Actualizar escolaridad';
                                this.tipoAccion=2;
                                this.escolaridad.id=data['id'];
                                this.escolaridad.grado_id = data['grado_id'];
                                this.escolaridad.empleado_id = data['empleado_id'];
                                this.escolaridad.titulo = data['titulo'];
                                this.escolaridad.cedula_prof = data['cedula_prof'];
                                this.escolaridad.fecha_inicio = data['fecha_inicio'];
                                this.escolaridad.fecha_termino = data['fecha_termino'];
                                break;
                            }
                        }
                    }
                }
            },
            cargarEscolaridades(dataEmpleado = []) {
                this.detalle = true;
                this.isLoadingDetalle = true;
                let me=this;
                this.empleado = dataEmpleado;
                axios.get(me.url + '/' + dataEmpleado.id+ '/' +'escolaridades').then(response => {
                    me.tableDataEscolaridades = response.data;
                    me.isLoadingDetalle = false;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        },
        mounted() {
            this.getListaGrados();
        }
    }
</script>