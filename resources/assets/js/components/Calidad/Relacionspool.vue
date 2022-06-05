<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Control de juntas
                        <button type="button" @click="abrirModal('banco','registrar')" class="btn btn-dark float-sm-right">
                            <i class="fas fa-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">

                        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                            <template slot="id" slot-scope="props">
                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group dropup" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i> Acciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <button type="button" @click="abrirModal('banco','actualizar',props.row)" class="dropdown-item">
                                    <i class="icon-pencil"></i>&nbsp; Actualizar Junta
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
                <div class="modal-dialog modal-dark modal-md" role="document">
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
                              <form>


                                <div class="form-row">
                                  <div class="form-group col-lg-6">
                                    <label>Proyecto:</label>
                                    <select class="form-control custom-select" id="proyecto_id" name="proyecto_id"
                                            v-model="spool.proyecto" v-validate="'required'"
                                            data-vv-as="Proyecto">
                                        <option value="0">---TODOS---</option>
                                        <option v-for="item in listaProyectos" :value="item.proyecto.id"
                                                :key="item.proyecto.id">{{ item.proyecto.nombre_corto }}
                                        </option>
                                    </select>
                                    <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
                                </div>
                                </div>

                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="inputCity">Sistema</label>
                                    <input type="text" v-model="spool.sistema" class="form-control" id="Sistema">
                                </div>
                                </div>

                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="inputCity">Servicio</label>
                                    <input type="text" class="form-control" v-model="spool.servicio" id="Servicio">
                                </div>
                                </div>

                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="inputCity">Spool</label>
                                    <input type="text" class="form-control" v-model="spool.spool" id="Spool">
                                </div>
                                </div>
                              </form>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary"  @click="guardarJunta(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarJunta(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                url: '/relacionspool',
                spool: {
                    id: 0,
                    proyecto : '',
                    sistema : '',
                    servicio : '',
                    spool : '',
                  },

                modal : 0,
                listaProyectos: [],
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'corto','sistema','servicio','spool'],
                tableData: [],
                options: {
                    headings: {
                        id: 'Acciones',
                        proyecto: 'Proyecto',
                        sistema: 'Sistema',
                        servicio: 'Servicio',
                        spool:'Spool',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['corto','sistema','servicio'],
                    filterable: ['corto','sistema','servicio'],
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

                axios.get('/proyecto').then(response => {
                    me.listaProyectos = response.data;
                })
                    .catch(function (error) {
                        console.log(error);
                    });

            },
            guardarJunta(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? /registraspool/ : /actualizaspool/ +this.spool.id,
                            data: {

                                'id': this.spool.id,
                                'proyecto' : this.spool.proyecto,
                                'sistema' : this.spool.sistema,
                                'servicio' : this.spool.servicio,
                                'spool' : this.spool.spool,

                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                                toastr.success('Registro Actualizado Correctamente');
                                }
                                else
                                {
                                toastr.success('Registro guardado Correctamente');

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
                Utilerias.resetObject(this.juntas);
            },

            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "banco":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Junta';
                                Utilerias.resetObject(this.juntas);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Junta';
                                this.tipoAccion=2;
                                this.spool.id=data['id'];
                                this.spool.proyecto=data['proyecto'];
                                this.spool.sistema=data['sistema'];
                                this.spool.servicio=data['servicio'];
                                this.spool.spool=data['spool'];
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
