<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Categorías
                        <button v-show="PermisosCRUD.Create" type="button" @click="abrirModal('categoria','registrar')" class="btn btn-dark float-sm-right">
                            <i class="fas fa-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">

                        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
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
                                <button v-show="PermisosCRUD.Update" type="button" @click="abrirModal('categoria','actualizar',props.row)" class="dropdown-item" >
                                    <i class="icon-pencil"></i>&nbsp; Actualizar
                                </button>
                                <template v-if="props.row.condicion">
                                    <button v-show="PermisosCRUD.Delete" type="button" class="dropdown-item" @click="desactivarCategoria(props.row.id, 0)" >
                                        <i class="fas fa-ban"></i>&nbsp; Desactivar
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="dropdown-item" @click="activarCategoria(props.row.id, 1)" >
                                        <i class="icon-check"></i>&nbsp; Activar
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
                <vue-element-loading :active="isLoading"/>
                <div class="modal-dialog modal-dark modal-lg" role="document">
                    <div class="modal-content">
                        <div>
                        
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required|max:50'" name="nombre" v-model="categoria.nombre" class="form-control" placeholder="Nombre de categoría" autocomplete="off" id="nombre">
                                        <span class="text-danger">{{ errors.first('nombre') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="descripcion">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="descripcion" v-model="categoria.descripcion" class="form-control" placeholder="Ingrese descripción" autocomplete="off" id="descrpcion">
                                        <span class="text-danger">{{ errors.first('descripcion') }}</span>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarcategoria(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarcategoria(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
import utilerias from "../../Herramientas/utilerias";
var config = require('../../Herramientas/config-vuetables-client').call(this);

    export default {
        data (){
            return {
                PermisosCRUD : {},
                categoria: {
                    id: 0,
                    nombre : '',
                    descripcion : '',
                },
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'nombre', 'descripcion', 'condicion'],
                tableData: [],
                options: {
                    headings: {
                        nombre: 'Nombre',
                        descripcion: 'Descripción',
                        condicion: 'Estado',
                        id: 'Acciones',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre', 'descripcion'],
                    filterable: ['nombre', 'descripcion', 'condicion'],
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
            getData() {
                this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
                let me=this;
                axios.get('/categoria').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             guardarcategoria(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/categoria/registrar' : '/categoria/actualizar',
                            data: {
                                'nombre': this.categoria.nombre,
                                'descripcion': this.categoria.descripcion,
                                'id': this.categoria.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                                toastr.success('Categoria Actualizada Correctamente');
                                }
                                else
                                {
                                toastr.success('Categoria Registrada Correctamente');

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
            desactivarCategoria(id){
               swal({
                title: 'Esta seguro de desactivar esta Categoría?',
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

                    axios.put('/categoria/desactivar',{
                        'id': id
                    }).then(function (response) {
                        toastr.error(
                            'El registro ha sido desactivado con éxito.'
                        );
                        me.getData();
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                }
                }) 
            },
            activarCategoria(id){
               swal({
                title: 'Esta seguro de activar esta Categoría?',
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

                    axios.put('/categoria/activar',{
                        'id': id
                    }).then(function (response) {
                        toastr.success(
                        'El registro ha sido activado con éxito.'
                      );
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
                Utilerias.resetObject(this.categoria);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "categoria":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Categoría';
                                Utilerias.resetObject(this.categoria);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar categoría';
                                this.tipoAccion=2;
                                this.categoria.id=data['id'];
                                this.categoria.nombre = data['nombre'];
                                this.categoria.descripcion= data['descripcion'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.getData();
        }
    }
</script>