<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Herramientas
                        <button type="button" @click="abrirModal('herramienta','registrar')" class="btn btn-secondary float-sm-right">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">

                        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                            <template slot="id" slot-scope="props">
                                <button type="button" @click="abrirModal('herramienta','actualizar',props.row)" class="btn btn-warning btn-sm">
                                    <i class="icon-pencil"></i>
                                </button>
                            </template>
                        </v-client-table>
                        
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
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
                            <input type="hidden" name="id">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="descripcion">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="descripcion" v-model="herramienta.descripcion" class="form-control" placeholder="Descripción de la herramienta" autocomplete="off" id="descripcion">
                                        <span class="text-danger">{{ errors.first('descripcion') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="cantidad">Cantidad</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="cantidad" v-model="herramienta.cantidad" class="form-control" placeholder="Cantidad" autocomplete="off" id="cantidad">
                                        <span class="text-danger">{{ errors.first('cantidad') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="marca">Marca</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="marca" v-model="herramienta.marca" class="form-control" placeholder="Marca" autocomplete="off" id="marca">
                                        <span class="text-danger">{{ errors.first('marca') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="unidad">Unidad</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="unidad" v-model="herramienta.unidad" class="form-control" placeholder="Unidad" autocomplete="off" id="unidad">
                                        <span class="text-danger">{{ errors.first('unidad') }}</span>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="guardarHerramienta(1)">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="guardarHerramienta(0)">Actualizar</button>
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
                url: '/herramienta',
                herramienta: {
                    id: 0,
                    descripcion : '',
                    cantidad: 0,
                    marca: '',
                    unidad: '',
                },
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'descripcion','cantidad','marca','unidad'],
                tableData: [],
                options: {
                    headings: {
                        descripcion: 'descripcion',
                        cantidad: 'cantidad',
                        marca: 'marca',
                        unidad: 'unidad',                        
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['descripcion'],
                    sortable: ['marca'],
                    filterable: ['descripcion'],
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
                axios.get(me.url).then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarHerramienta(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? me.url : me.url+'/'+this.herramienta.id,
                            data: {
                                'unidad': this.herramienta.unidad,
                                'marca': this.herramienta.marca,
                                'cantidad': this.herramienta.cantidad,
                                'descripcion': this.herramienta.descripcion,
                                'id': this.herramienta.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                                toastr.success('Herramienta Actualizada Correctamente');
                                }
                                else
                                {
                                toastr.success('Herramienta Registrada Correctamente');

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
                Utilerias.resetObject(this.herramienta);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "herramienta":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar herramienta';
                                Utilerias.resetObject(this.herramienta);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar herramienta';
                                this.tipoAccion=2;
                                this.herramienta.id=data['id'];
                                this.herramienta.descripcion = data['descripcion'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.getData();
            this.$root.limpiarDashboard();
        }
    }
</script>