<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Soldadores
                        <button  type="button" @click="abrirModal('soldadores','registrar')" class="btn btn-dark float-sm-right">
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
                                <button type="button" @click="abrirModal('soldadores','actualizar',props.row)" class="dropdown-item" >
                                    <i class="icon-pencil"></i>&nbsp; Actualizar
                                </button>

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
                                        <input type="text" v-validate="'required'" name="nombre" v-model="soldadores.nombre"
                                         class="form-control" placeholder="Nombre de categoría" autocomplete="off" id="nombre">
                                        <span class="text-danger">{{ errors.first('nombre') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="numero_serie_maquina">Numero de serie maquina de soldar</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="numero_serie_maquina" v-model="soldadores.numero_serie_maquina"
                                        class="form-control" placeholder="Ingrese descripción" autocomplete="off" id="numero_serie_maquina">
                                        <span class="text-danger">{{ errors.first('numero_serie_maquina') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="num">Id soldador</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required'" name="num" v-model="soldadores.num"
                                        class="form-control" placeholder="Ingrese descripción" autocomplete="off" id="num">
                                        <span class="text-danger">{{ errors.first('num') }}</span>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarsoldadores(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarsoldadores(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                soldadores: {
                    id: 0,
                    nombre : '',
                    numero_serie_maquina : '',
                    num : '',
                },
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'nombre', 'numero_serie_maquina', 'num'],
                tableData: [],
                options: {
                    headings: {
                        nombre: 'Nombre',
                        numero_serie_maquina: 'Numero de serie maquina soldar',
                        num: 'Id Soldador',
                        id: 'Acciones',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre', 'numero_serie_maquina', 'num'],
                    filterable: ['nombre', 'numero_serie_maquina', 'num'],
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
                axios.get('soldadores/obtener').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             guardarsoldadores(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/soldador/registrar' : '/soldador/actualizar/' + this.soldadores.id,
                            data: {
                                'nombre': this.soldadores.nombre,
                                'numero_serie_maquina': this.soldadores.numero_serie_maquina,
                                'num': this.soldadores.num
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                                toastr.success('Soldador Actualizada Correctamente');
                                }
                                else
                                {
                                toastr.success('Soldador Registrada Correctamente');

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
                Utilerias.resetObject(this.soldadores);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "soldadores":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Soldadores';
                                Utilerias.resetObject(this.soldadores);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Soldadores';
                                this.tipoAccion=2;
                                this.soldadores.id=data['id'];
                                this.soldadores.nombre = data['nombre'];
                                this.soldadores.numero_serie_maquina= data['numero_serie_maquina'];
                                this.soldadores.num= data['num'];

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
