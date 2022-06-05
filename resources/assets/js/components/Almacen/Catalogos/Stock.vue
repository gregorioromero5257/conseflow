<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <!-- <div class="card-header">
                        <i class="fa fa-align-justify"></i> Stocks
                        <button type="button" @click="abrirModal('stock','registrar')" class="btn btn-dark float-sm-right">
                            <i class="fas fa-plus"></i>&nbsp;Nuevo
                        </button>
                    </div> -->
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
                                <!-- <button type="button" @click="abrirModal('stock','actualizar',props.row)" class="dropdown-item" >
                                    <i class="icon-pencil"></i>&nbsp; Actualizar
                                </button> -->
                                <template v-if="props.row.condicion">
                                    <button type="button" class="dropdown-item" @click="activarStock(props.row.id, 0)" >
                                        <i class="fas fa-ban"></i>&nbsp;Desactivar
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="dropdown-item" @click="activarStock(props.row.id, 1)" >
                                        <i class="icon-check"></i>&nbsp;Activar
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
                <div class="modal-dialog modal-darksecondary modal-lg" role="document">
                    <div class="modal-content">
                        <div>
                        
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
                            <input type="hidden" name="id">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="nombre" v-model="stock.nombre" class="form-control" placeholder="Nombre del Stock" autocomplete="off" id="nombre">
                                        <span class="text-danger">{{ errors.first('nombre') }}</span>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarStock(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarStock(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                stock: {
                    id: 0,
                    nombre : ''
                },
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'nombre', 'proyecto', 'condicion'],
                tableData: [],
                options: {
                    headings: {
                        nombre: 'Nombre',
                        condicion: 'Estado',
                        id: 'Acciones',
                        proyecto: 'Proyecto'
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre'],
                    filterable: ['nombre', 'condicion', 'proyecto'],
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
                let me=this;
                axios.get('/stock').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarStock(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/stock/registrar' : '/stock/actualizar',
                            data: {
                                'nombre': this.stock.nombre,
                                'id': this.stock.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                 if(!nuevo){
                                toastr.success('Stock Actualizado Correctamente');
                                }
                                else
                                {
                                toastr.success('Stock Registrado Correctamente');

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
            activarStock(id, activar){
               swal({
                title: activar ? 'Esta seguro de activar este Stock?' : 'Esta seguro de desactivar este Stock?',
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

                    axios.put(activar ? '/stock/activar' : '/stock/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.getData();
                        if(activar){
                        toastr.success('Stock Activado con Exito');

                        }else{
                        toastr.error('Stock Desactivado con Exito');

                        }
                    }).catch(function (error) {
                        console.log(error);
                    });                       
                }
                }) 
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                Utilerias.resetObject(this.stock);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "stock":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar stock';
                                Utilerias.resetObject(this.stock);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar stock';
                                this.tipoAccion=2;
                                this.stock.id=data['id'];
                                this.stock.nombre = data['nombre'];
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