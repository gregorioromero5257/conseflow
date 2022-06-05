<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Puestos
                        <button type="button" @click="abrirModal('puesto','registrar')" class="btn btn-dark float-sm-right">
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
                                <button type="button" @click="abrirModal('puesto','actualizar',props.row)" class="dropdown-item">
                                    <i class="icon-pencil"></i>&nbsp; Actualizar
                                </button>
                                <template v-if="props.row.condicion">
                                    <button type="button" class="dropdown-item" @click="activarPuesto(props.row.id, 0)">
                                        <i class="fas fa-ban"></i>&nbsp; Desactivar
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="dropdown-item" @click="activarPuesto(props.row.id, 1)">
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
                                        <input type="text" v-validate="'required|max:50'" name="nombre" v-model="puesto.nombre" class="form-control" placeholder="Nombre del puesto" autocomplete="off" id="nombre">
                                        <span class="text-danger">{{ errors.first('nombre') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="area">Area</label>
                                    <div class="col-md-9">
                                        <input type="text"  :maxlength="45" name="area" v-model="puesto.area" class="form-control" placeholder="Area" autocomplete="off" id="area">     
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="departamento_id">Departamento</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="departamento_id"  name="departamento_id" v-model="puesto.departamento_id" v-validate="'excluded:0'" data-vv-as="Departamento">
                                            <option v-for="item in listaDepartamentos" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('departamento_id') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nivel_o">Jefe Directo</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="nivel_o"  name="nivel_o" v-model="puesto.nivel_o" v-validate="'excluded:0'" data-vv-as="Nivel">
                                            <option v-for="item in listaP" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('departamento_id') }}</span>
                                    </div>
                                </div>


                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarPuesto(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarPuesto(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                puesto: {
                    id: 0,
                    departamento_id: 0,
                    nivel_o : 0,
                    nombre : '',
                    area: ''
                },
                listaDepartamentos: [],
                listaP :[],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'nombre', 'departamento','area', 'direccion', 'condicion'],
                tableData: [],
                options: {
                    headings: {
                        nombre: 'Nombre',
                        departamento: 'Departamento',
                        area: 'Area',
                        direccion: 'Dirección',
                        condicion: 'Estado',
                        id: 'Acciones',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre', 'direccion', 'departamento', 'area'],
                    filterable: ['nombre', 'direccion', 'condicion', 'departamento', 'area'],
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
                axios.get('/puesto').then(response => {
                    me.tableData = response.data;
                    me.listaP = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getListaDepartamentos() {
                let me=this;
                axios.get('/departamento/getlist').then(response => {
                    me.listaDepartamentos = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarPuesto(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/puesto/registrar' : '/puesto/actualizar',
                            data: {
                                'nombre': this.puesto.nombre,
                                'area': this.puesto.area,
                                'departamento_id': this.puesto.departamento_id,
                                'nivel_o' : this.puesto.nivel_o,
                                'id': this.puesto.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                        toastr.success('Puesto Actualizado Correctamente');

                        }
                        else
                        {
                        toastr.success('Puesto Registrado Correctamente');
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
            activarPuesto(id, activar){
               swal({
                title: activar ? 'Esta seguro de activar este Puesto?' : 'Esta seguro de desactivar este Puesto?',
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

                    axios.put(activar ? '/puesto/activar' : '/puesto/desactivar',{
                        'id': id
                    }).then(function (response) {
                        if(!activar){
                        toastr.error('Puesto Desactivado Correctamente');

                        }
                        else
                        {
                        toastr.success('Puesto Activado Correctamente');
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
                Utilerias.resetObject(this.puesto);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "puesto":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar puesto';
                                Utilerias.resetObject(this.puesto);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar puesto';
                                this.tipoAccion=2;
                                this.puesto.id=data['id'];
                                this.puesto.departamento_id=data['departamento_id'];
                                this.puesto.nombre = data['nombre'];
                                this.puesto.area = data['area'];
                                this.puesto.nivel_o = data['nivel_o'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.getData();
            this.getListaDepartamentos();
        }
    }
</script>
