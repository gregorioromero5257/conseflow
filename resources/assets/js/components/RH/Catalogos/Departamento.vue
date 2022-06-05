<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Departamentos
                        <button type="button" @click="abrirModal('departamento','registrar')" class="btn btn-dark float-sm-right">
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
                                        <i class="fas fa-grip-horizontal"></i> Acciones
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
                                <button type="button" @click="abrirModal('departamento','actualizar',props.row)" class="dropdown-item">
                                    <i class="icon-pencil"></i> &nbsp;Actualizar Departamento
                                </button> 
                                <template v-if="props.row.condicion">
                                    <button type="button" class="dropdown-item" @click="activarDepartamento(props.row.id, 0)">
                                        <i class="fas fa-ban"></i>&nbsp; Desactivar Departamento
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="dropdown-item" @click="activarDepartamento(props.row.id, 1)">
                                        <i class="icon-check"></i>&nbsp; Activar Departamento
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
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required|max:100'" name="nombre" v-model="departamento.nombre" class="form-control" placeholder="Nombre del departamento" autocomplete="off" id="nombre">
                                        <span class="text-danger">{{ errors.first('nombre') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="direccion_administrativa_id">Dirección administrativa</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="direccion_administrativa_id"  name="direccion_administrativa_id" v-model="departamento.direccion_administrativa_id" v-validate="'excluded:0'" data-vv-as="Dirección administrativa">
                                            <option v-for="item in listaDirecciones" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('direccion_administrativa_id') }}</span>
                                    </div>
                                </div>

                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarDepartamento(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarDepartamento(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                departamento: {
                    id: 0,
                    direccion_administrativa_id: 0,
                    nombre : ''
                },
                listaDirecciones: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'nombre', 'direccion', 'condicion'],
                tableData: [],
                options: {
                    headings: {
                        nombre: 'Nombre',
                        direccion: 'Dirección',
                        condicion: 'Estado',
                        id: 'Acciones',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre', 'direccion'],
                    filterable: ['nombre', 'direccion', 'condicion'],
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
                axios.get('/departamento').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getListaDirecciones() {
                let me=this;
                axios.get('/direccionadmin/getlist').then(response => {
                    me.listaDirecciones = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarDepartamento(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/departamento/registrar' : '/departamento/actualizar',
                            data: {
                                'nombre': this.departamento.nombre,
                                'direccion_administrativa_id': this.departamento.direccion_administrativa_id,
                                'id': this.departamento.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                                toastr.success('Departamento Actualizado Correctamente');
                                }
                                else
                                {
                                toastr.success('Departamento Registrado Correctamente');
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
            activarDepartamento(id, activar){
               swal({
                title: activar ? 'Esta seguro de activar este Departamento?' : 'Esta seguro de desactivar este Departamento?',
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

                    axios.put(activar ? '/departamento/activar' : '/departamento/desactivar',{
                        'id': id
                    }).then(function (response) {
                        if(!activar){
                        toastr.error('Departamento Desactivado Correctamente');
                        
                        }
                        else
                        {
                        toastr.success('Departamento Activado Correctamente');
                        }
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
                Utilerias.resetObject(this.departamento);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "departamento":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar departamento';
                                Utilerias.resetObject(this.departamento);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar departamento';
                                this.tipoAccion=2;
                                this.departamento.id=data['id'];
                                this.departamento.direccion_administrativa_id=data['direccion_administrativa_id'];
                                this.departamento.nombre = data['nombre'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.getData();
            this.getListaDirecciones();
        }
    }
</script>