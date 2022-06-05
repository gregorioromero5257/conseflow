<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Módulos del sistema
                        <button type="button" @click="abrirModal('modulo','registrar')" class="btn btn-secondary float-sm-right">
                            <i class="icon-plus"></i>&nbsp;Nuevo
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
                                <button type="button" @click="abrirModal('modulo','actualizar',props.row)" class="dropdown-item" href="#">
                                    <i class="icon-pencil"></i>&nbsp;Actualizar Modulo
                                </button>
                                </template>
                                </div>
                                </div>
                                </div>
                            </template>
                            <template slot="nombre" slot-scope="props" >

                                <div style="text-align: center;">
                                    <div id="icon-grid">
                                        <div class="case-wrapper" :title="props.row.nombre">
                                            <a> 
                                                <div class="app-icon" v-bind:style="'background-color:' + props.row.color" 
                                                :title="props.row.nombre">
                                                    <i v-bind:class="props.row.icono" :title="props.row.nombre"></i>
                                                </div>
                                            </a> 
                                                <div class="case-label ellipsis">
                                                    <span class="case-label-text" v-text="props.row.nombre"></span> 
                                                </div> 
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
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
                            <input type="hidden" name="id">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nombre">Nombre:</label>
                                    <div class="col-md-3">
                                        <input type="text" v-validate="'required'" name="nombre" v-model="Modulo.nombre" class="form-control" placeholder="Nombre del tipo de Horario" autocomplete="off" id="nombre">
                                        <span class="text-danger">{{ errors.first('nombre') }}</span>
                                    </div>

                                    <label class="col-md-3 form-control-label" for="color">Color:</label>
                                    <div class="col-md-3">
                                        <i class="fas fa-eye-dropper"></i>
                                        <input type="color" name="color" v-model="Modulo.color" value="#B7B6B6">

                                        <input type="text" v-validate="'required'" name="color" v-model="Modulo.color" class="form-control" placeholder="Color del tipo de Horario" autocomplete="off" id="color">
                                        <span class="text-danger">{{ errors.first('color') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="icono">Ícono:</label>
                                    <div class="col-md-3">
                                        <input type="text" v-validate="'required'" name="icono" v-model="Modulo.icono" class="form-control" placeholder="Ícono" autocomplete="off" id="icono">
                                        <span class="text-danger">{{ errors.first('icono') }}</span>
                                    </div>
                                    <label class="col-md-3 form-control-label" for="page">Page:</label>
                                    <div class="col-md-3">
                                        <input type="text" v-validate="'required'" name="page" v-model="Modulo.page" class="form-control" placeholder="Page" autocomplete="off" id="page">
                                        <span class="text-danger">{{ errors.first('page') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="submenu">Submenú:</label>
                                    <div class="col-md-3">
                                        <select class="form-control" id="submenu"  name="submenu" v-model="Modulo.submenu" data-vv-as="Submenú">
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('submenu') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-10 col-sm-offset-1 hid">
                                        <div class="row">
                                            <div class="col-sm-6 col-sm-offset-4 text-center"></div>

                                            <div class="col-sm-4 col-sm-offset-4 text-center">
                                                <div style="text-align: center;">
                                                    <div id="icon-grid">
                                                        <div class="case-wrapper" :title="Modulo.nombre">
                                                            <a> 
                                                            <div class="app-icon" v-bind:style="'background-color:' + Modulo.color" 
                                                            :title="Modulo.nombre">
                                                                <i v-bind:class="Modulo.icono" :title="Modulo.nombre"></i>
                                                            </div>
                                                            </a> 
                                                            <div class="case-label ellipsis">
                                                                <span class="case-label-text" v-text="Modulo.nombre"></span> 
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarTipoNomina(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarTipoNomina(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                url: '/ModulosSistema',
                Modulo: {
                    id: 0,
                    nombre : '',
                    color: '',
                    icono: '',
                    page: '/',
                    submenu:0,
                },
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'nombre'],
                tableData: [],
                options: {
                    headings: {
                        nombre: 'Módulo',
                        id: 'Acciones',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre'],
                    filterable: ['nombre'],
                    filterByColumn: true,
                    texts:config.texts
                },
            }
        },
        filters: {
                uppercase: function (str) {
                    return str.toUpperCase() //Retornar el string con el method toUpperCase (mayúscula)
                }
            },
        computed:{
        },
        methods : {
            getData() {
                let me=this;
                axios.get(me.url).then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            readFile:function(e){
                var f=e.dataTransfer.files;
            },
            guardarTipoNomina(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? me.url : me.url+'/'+this.id,
                            data: {
                                'icono': this.Modulo.icono,
                                'color': this.Modulo.color,
                                'nombre': this.Modulo.nombre,
                                'id': this.Modulo.id,
                                'page': this.Modulo.page,
                                'submenu': this.Modulo.submenu,
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(nuevo){
                        toastr.success('Modulo Registrado Correctamente');
                        }else{
                        toastr.success('Modulo Actualizado Correctamente');
                        }
                        toastr.options = {
                        "closeButton": false,
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": true,
                        "positionClass": "toast-top-center",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
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
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                Utilerias.resetObject(this.Modulo);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "modulo":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Módulo';
                                Utilerias.resetObject(this.Modulo);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Módulo';
                                this.tipoAccion=2;
                                this.Modulo.id=data['id'];
                                this.Modulo.nombre = data['nombre'];
                                this.Modulo.color = data['color'];
                                this.Modulo.icono = data['icono'];
                                this.Modulo.page = data['page'];
                                this.Modulo.submenu = data['submenu'];
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