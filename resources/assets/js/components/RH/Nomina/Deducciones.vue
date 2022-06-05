<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Deducción
                        <button v-show="PermisosCRUD.Create" type="button" @click="abrirModal('deduccion','registrar')" class="btn btn-dark float-sm-right">
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
                                <button v-show="PermisosCRUD.Update" type="button" @click="abrirModal('deduccion','actualizar',props.row)" class="dropdown-item">
                                    <i class="icon-pencil"></i>&nbsp; Actualizar Deduccion
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
                            <input type="hidden" name="id">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="unidad">Unidad</label>
                                    <div class="col-md-6">
                                        <input type="text" v-validate="'required'" name="unidad" v-model="tipoDeduccion.unidad" class="form-control" placeholder="Unidad" autocomplete="off" id="unidad">
                                        <span class="text-danger">{{ errors.first('unidad') }}</span>
                                    </div>
                                </div>
                                    <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="costo">Costo</label>
                                    <div class="col-md-6">
                                        <input type="number" v-validate="'required'" name="costo" v-model="tipoDeduccion.costo" class="form-control" placeholder="0.00" autocomplete="off" id="costo" step="0.01">
                                        <span class="text-danger">{{ errors.first('costo') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="importe">Importe</label>
                                    <div class="col-md-6">
                                        <input type="number" v-validate="'required'" name="importe" v-model="tipoDeduccion.importe" class="form-control" placeholder="0.00" autocomplete="off" id="importe" step="0.01">
                                        <span class="text-danger">{{ errors.first('importe') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="tipo_deduccione_id">Tipo de Deducción</label>
                                    <div class="col-md-6">
                                        <select class="form-control" id="   tipo_deduccione_id"  name="tipo_deduccione_id" v-model="tipoDeduccion.tipo_deduccione_id" v-validate="'excluded:0'" data-vv-as="Tipo de Deducción">
                                            <option v-for="item in listaTipoDeduccion" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('tipo_deduccione_id') }}</span>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarDeduccion(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarDeduccion(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                url: '/deducciones',
                PermisosCRUD :{},
                tipoDeduccion: {
                    id: 0,
                    unidad : '',
                    costo: 0,
                    importe: 0,
                    tipo_deduccione_id: 0,
                },
                listaTipoDeduccion: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'unidad', 'costo', 'importe', 'td_nombre'],
                tableData: [],
                options: {
                    headings: {
                        unidad: 'Unidad',
                        costo: 'Costo',
                        importe: 'Importe',
                        td_nombre: 'Tipo de Deducción',
                        id: 'Acciones',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['unidad', 'td_nombre'],
                    filterable: ['unidad', 'td_nombre'],
                    filterByColumn: true,
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
                axios.get(me.url).then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getLista() {
                let me=this;
                axios.get('/tipodeducciones').then(response => {
                    me.listaTipoDeduccion = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarDeduccion(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? me.url : me.url+'/'+this.id,
                            data: {
                                'costo' : this.tipoDeduccion.costo,
                                'importe' : this.tipoDeduccion.importe,
                                'unidad': this.tipoDeduccion.unidad,
                                'tipo_deduccione_id': this.tipoDeduccion.tipo_deduccione_id,
                                'id': this.tipoDeduccion.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                  if(!nuevo){
                                toastr.success('Deduccion Actualizada Correctamente');
                                }
                                else
                                {
                                toastr.success('Deduccion Registrada Correctamente');

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
                Utilerias.resetObject(this.tipoDeduccion);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "deduccion":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Deducción';
                                Utilerias.resetObject(this.tipoDeduccion);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Deducción';
                                this.tipoAccion=2;
                                this.tipoDeduccion.id=data['id'];
                                this.tipoDeduccion.costo = data['costo'];
                                this.tipoDeduccion.importe = data['importe'];
                                this.tipoDeduccion.unidad = data['unidad'];
                                this.tipoDeduccion.tipo_deduccione_id = data['tipo_deduccione_id'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.getData();
            this.getLista();
            this.$root.limpiarDashboard();
        }
    }
</script>