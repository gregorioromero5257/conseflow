<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Grupos
                        <button type="button" @click="abrirModal('grupo','registrar')" class="btn btn-dark float-sm-right">
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
                                    <button type="button" class="btn btn-outline-danger">Dado de Baja</button>
                                </template>
                            </template>
                            <template slot="id" slot-scope="props">
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group dropup" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
                                <button type="button" @click="abrirModal('grupo','actualizar',props.row)" class="dropdown-item" >
                                    <i class="icon-pencil"></i>&nbsp; Actualizar
                                </button>
                                <template v-if="props.row.condicion">
                                    <button type="button" class="dropdown-item" @click="activarGrupo(props.row.id, 0)" >
                                        <i class="fas fa-ban"></i>&nbsp; Desactivar
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="dropdown-item" @click="activarGrupo(props.row.id, 1)" >
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
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required|max:50'" name="nombre" v-model="grupo.nombre" class="form-control" placeholder="Nombre del grupo" autocomplete="off" id="nombre">
                                        <span class="text-danger">{{ errors.first('nombre') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="categoria_id">Categoria</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="categoria_id"  name="categoria_id" v-model="grupo.categoria_id" v-validate="'excluded:0'" data-vv-as="Categoria">
                                            <option v-for="item in listaCategorias" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('categoria_id') }}</span>
                                    </div>
                                </div>

                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarGrupo(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarGrupo(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                grupo: {
                    id: 0,
                    categoria_id: 0,
                    nombre : ''
                },
                listaCategorias: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'nombre', 'categoria', 'condicion'],
                tableData: [],
                options: {
                    headings: {
                        nombre: 'Nombre',
                        categoria: 'Categoria',
                        condicion: 'Estado',
                        id: 'Acciones',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre', 'categoria'],
                    filterable: ['nombre', 'categoria', 'condicion'],
                    filterByColumn: true,
                    groupBy: ['categoria'],
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
                axios.get('/grupo').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getListaCategorias() {
                let me=this;
                axios.get('/categoria/getlist').then(response => {
                    me.listaCategorias = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarGrupo(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/grupo/registrar' : '/grupo/actualizar',
                            data: {
                                'nombre': this.grupo.nombre,
                                'categoria_id': this.grupo.categoria_id,
                                'id': this.grupo.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                 if(!nuevo){
                                toastr.success('Grupo Actualizada Correctamente');
                                }
                                else
                                {
                                toastr.success('Grupo Registrado Correctamente');

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
            activarGrupo(id, activar){
               swal({
                title: activar ? 'Esta seguro de activar este Grupo?' : 'Esta seguro de desactivar este Grupo?',
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

                    axios.put(activar ? '/grupo/activar' : '/grupo/desactivar',{
                        'id': id
                    }).then(function (response) {
                        
                        me.getData();
                        if(activar){
                        toastr.success('Grupo Activado con Exito');

                        }else{
                        toastr.error('Grupo Desactivado con Exito');

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
                Utilerias.resetObject(this.grupo);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "grupo":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar grupo';
                                Utilerias.resetObject(this.grupo);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar grupo';
                                this.tipoAccion=2;
                                this.grupo.id=data['id'];
                                this.grupo.categoria_id=data['categoria_id'];
                                this.grupo.nombre = data['nombre'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.getData();
            this.getListaCategorias();
        }
    }
</script>