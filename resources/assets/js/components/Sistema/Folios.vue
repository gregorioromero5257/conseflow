<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Folios
                        <button type="button" @click="abrirModal('folio','registrar')" class="btn btn-dark float-sm-right">
                            <i class="fas fa-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">

                        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                            <template slot="id" slot-scope="props">
                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group dropup" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <template>
                                <button type="button" @click="abrirModal('folio','actualizar',props.row)" class="dropdown-item">
                                    <i class="icon-pencil"></i>&nbsp; Actualizar Folio
                                </button>
                                </template>
                                <template>
                                <button type="button" class="dropdown-item" @click="eliminar(props.row.id)">
                                    <i class="icon-trash"></i>&nbsp; Eliminar Folio
                                </button>
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
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="tipo_doc">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="tipo_doc" v-model="folio.tipo_doc" class="form-control" placeholder="Tipo de doc." autocomplete="off" id="tipo_doc">
                                        <span class="text-danger">{{ errors.first('tipo_doc') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="consecutivo">Consecutivo</label>
                                    <div class="col-md-9">
                                        <input type="number" v-validate="'required'" name="consecutivo" v-model="folio.consecutivo" class="form-control" placeholder="Consecutivo" autocomplete="off" id="consecutivo">
                                        <span class="text-danger">{{ errors.first('consecutivo') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="proyecto_id">Proyecto</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="proyecto_id"  name="proyecto_id" v-model="folio.proyecto_id" v-validate="'excluded:0'" data-vv-as="Modulo">
                                            <option v-for="item in listaProyectos" :value="item.proyecto.id" :key="item.proyecto.id">{{ item.proyecto.nombre_corto }}</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
                                    </div>
                                </div>

                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarFolio(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarFolio(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

    export default {
        data (){
            return {
                folio: {
                    id: 0,
                    proyecto_id: 0,
                    tipo_doc : '',
                    consecutivo: 1
                },
                listaProyectos: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'nombre_corto', 'tipo_doc', 'consecutivo'],
                tableData: [],
                options: {
                    headings: {
                        nombre_corto: 'Proyecto',
                        tipo_doc: 'Tipo Doc.',
                        consecutivo: 'Consecutivo',
                        id: 'Acciones',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre_corto', 'tipo_doc','consecutivo'],
                    filterable: ['nombre_corto', 'tipo_doc','consecutivo'],
                    filterByColumn: true,
                    texts:config.texts
                },
            }
        },
        computed:{
        },
        methods : {
            getData() {
                let me=this;
                axios.get('/folio').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getListaProyectos() {
                let me=this;
                axios.get('/proyecto').then(response => {
                    me.listaProyectos = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarFolio(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/folio/registrar' : '/folio/actualizar',
                            data: {
                                'proyecto_id': this.folio.proyecto_id,
                                'consecutivo': this.folio.consecutivo,
                                'tipo_doc': this.folio.tipo_doc,
                                'id': this.folio.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                        toastr.success('Folio Actualizado Correctamente');

                        }
                        else
                        {
                        toastr.success('Folio Registrado Correctamente');
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
            eliminar(id){
               swal({
                title: 'Esta seguro de eliminar este Folio?',
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

                    axios.put('/folio/eliminar',{
                        'id': id
                    }).then(function (response) {
                        toastr.error('Folio Desactivado Correctamente');
                        me.getData();
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
                })
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                Utilerias.resetObject(this.folio);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "folio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar folio';
                                Utilerias.resetObject(this.folio);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar folio';
                                this.tipoAccion=2;
                                this.folio.id=data['id'];
                                this.folio.proyecto_id=data['proyecto_id'];
                                this.folio.tipo_doc = data['tipo_doc'];
                                this.folio.consecutivo = data['consecutivo'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.getData();
            this.getListaProyectos();
        }
    }
</script>
