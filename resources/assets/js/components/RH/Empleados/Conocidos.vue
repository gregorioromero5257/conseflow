<template>
            <main>
            <button type="button" @click="abrirModal('conocido','registrar')" class="btn btn-dark float-sm-right">
                <i class="fas fa-plus"></i>&nbsp;Nuevo
            </button>
            
            <vue-element-loading :active="isLoadingDetalle"/>
            <v-client-table :columns="columnsConocidos" :data="tableDataConocidos" :options="optionsConocidos" ref="myTableConocidos">
                <template slot="id" slot-scope="props">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group dropup" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
                    <button type="button" @click="abrirModal('conocido','actualizar',props.row)" class="dropdown-item">
                    </button>
                    <button type="button" @click="Eliminar(props.row)" class="dropdown-item">
                        <i class="fas fa-times"></i>&nbsp; Eliminar Conocido
                    </button>
                    </div>
                </div>
                </div>
                </template>
            </v-client-table>
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
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required|max:45'" name="nombre" v-model="conocido.nombre" class="form-control" placeholder="Nombre" autocomplete="off" id="nombre" data-vv-as="Nombre">
                                        <span class="text-danger">{{ errors.first('nombre') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="puesto">Puesto</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required|max:45'" name="puesto" v-model="conocido.puesto" class="form-control" placeholder="Puesto" autocomplete="off" id="puesto" data-vv-as="Puesto">
                                        <span class="text-danger">{{ errors.first('puesto') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="tiempo_conocer">Tiempo de conocer</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required|decimal:1'" name="tiempo_conocer" v-model="conocido.tiempo_conocer" class="form-control" placeholder="Años" autocomplete="off" id="Tiempo_conocer" data-vv-as="Tiempo de conocer">
                                        <span class="text-danger">{{ errors.first('tiempo_conocer') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="motivo">Motivo</label>
                                    <div class="col-md-9">
                                        <select class="custom-select col-md" id="motivo"  name="motivo" v-model="conocido.motivo" v-validate="'excluded:0'" data-vv-as="Motivo">
                                            <option value="Personal">Personal</option>
                                            <option value="Trabajo">Trabajo</option>
                                            <option value="Social">Social</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('motivo') }}</span>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarConocido(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarConocido(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                empleado: null,
                detalle: false,
                conocido: {
                    id: 0,
                    nombre: '',
                    puesto: '',
                    tiempo_conocer: '',
                    motivo: '',
                    empleado_id: 0,
                },
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                isLoadingDetalle: false,
                columnsConocidos: [
                    'id',
                    'nombre',
                    'puesto',
                    'motivo',
                    ],
                tableDataConocidos: [],
                optionsConocidos: {
                    headings: {
                        'id': 'Acciones',
                        'nombre': 'Nombre',
                        'puesto': 'Puesto',
                        'motivo': 'Motivo',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre', 'puesto', 'motivo' ],
                    filterable: ['nombre', 'puesto', 'motivo'],
                    filterByColumn: true,
                    texts:config.texts
                },
            }
        },
        computed:{
        },
        methods : {
            guardarConocido(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/conocido/registrar' : '/conocido/actualizar',
                            data: {
                                'id': this.conocido.id,
                                'empleado_id': this.empleado.id,
                                'nombre': this.conocido.nombre,
                                'puesto': this.conocido.puesto,
                                'tiempo_conocer': this.conocido.tiempo_conocer,
                                'motivo': this.conocido.motivo,
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.cargarConocidos(me.empleado);
                                toastr.success('Conocido ' + ((nuevo) ? 'Registrado' : 'Actualizado') + ' Correctamente.');
                            } else {
                                toastr.error("Intente más tarde","Error");
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
                Utilerias.resetObject(this.conocido);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "conocido":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar conocido de la Empresa';
                                Utilerias.resetObject(this.conocido);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                Utilerias.resetObject(this.conocido);
                                this.modal=1;
                                this.tituloModal= 'Actualizar';
                                this.tipoAccion=2;
                                this.conocido.id=data['id'];
                                this.conocido.empleado_id = data['empleado_id'];
                                this.conocido.nombre = data['nombre'];
                                this.conocido.puesto = data['puesto'];
                                this.conocido.tiempo_conocer = data['tiempo_conocer'];
                                this.conocido.motivo = data['motivo'];
                                break;
                            }
                        }
                    }
                }
            },
            cargarConocidos(dataEmpleado = []) {
                this.detalle = true;
                this.isLoadingDetalle = true;
                let me=this;
                this.empleado = dataEmpleado;
                axios.get('/conocido/' + dataEmpleado.id).then(response => {
                    me.tableDataConocidos = response.data;
                    me.isLoadingDetalle = false;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            maestro(){
                this.$refs.myTableConocidos.setFilter({
                    'nombre': '', 'puesto': '', 'motivo': ''
                });
                this.detalle = false;
            },
            Eliminar(row)
            {
                axios.put("/conocidos/eliminar",{
                    "empleado_id": row.empleado_id,
                    "id":row.id
                }).then(res=>
                {
                    if(res.data.status)
                    {
                        this.cargarConocidos({id:row.empleado_id});
                    }else
                    {
                        toastr.error("Intente más tarde","Error");
                    }
                })
            }
        },
        mounted() {
        }
    }
</script>