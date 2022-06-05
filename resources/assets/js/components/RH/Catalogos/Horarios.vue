<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Horarios
                        <button type="button" @click="abrirModal('horario','registrar')" class="btn btn-dark float-sm-right">
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
                                <button type="button" @click="abrirModal('horario','actualizar',props.row)" class="dropdown-item">
                                    <i class="icon-pencil"></i>&nbsp; Actualizar horario
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
                            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="hora_entrada">Hora Entrada</label>
                                    <div class="col-md-9">
                                        <input type="time"  name="hora_entrada" v-model="horario.hora_entrada" class="form-control" placeholder="Hora entrada" autocomplete="off" id="hora_entrada" data-vv-as="Hora salida">
                                        <span class="text-danger">{{ errors.first('hora_entrada') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="hora_salida_comida">Salida a Comida</label>
                                    <div class="col-md-9">
                                        <input type="time"  name="hora_salida_comida" v-model="horario.hora_salida_comida" class="form-control" placeholder="Salida a Comer" autocomplete="off" id="hora_salida_comida" data-vv-as="Hora salida">
                                        <span class="text-danger">{{ errors.first('hora_salida_comida') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="hora_entrada_comida">Entrada de Comida</label>
                                    <div class="col-md-9">
                                        <input type="time"  name="hora_entrada_comida" v-model="horario.hora_entrada_comida" class="form-control" placeholder="Entrada de Comida" autocomplete="off" id="hora_entrada_comida" data-vv-as="Entrada de Comida">
                                        <span class="text-danger">{{ errors.first('hora_entrada_comida') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="hora_salida">Hora Salida</label>
                                    <div class="col-md-9">
                                        <input type="time" name="hora_salida" v-model="horario.hora_salida" class="form-control" placeholder="Hora salida" autocomplete="off" id="hora_salida" data-vv-as="Hora salida">
                                        <span class="text-danger">{{ errors.first('hora_salida') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="tipo_horario_id">Día Laboral</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="dia_id"  name="dia_id" v-model="horario.dia_id" v-validate="'excluded:0'" data-vv-as="Día">
                                            <option v-for="item in listaDias" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('dia_id') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="tipo_horario_id">Tipo de horario</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="tipo_horario_id"  name="tipo_horario_id" v-model="horario.tipo_horario_id" v-validate="'excluded:0'" data-vv-as="Tipo horario">
                                            <option v-for="item in listaTipos" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                        </select>
                                        <span class="text-danger">{{ errors.first('tipo_horario_id') }}</span>
                                    </div>
                                </div>

                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarHorario(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarHorario(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                horario: {
                    id: 0,
                    tipo_horario_id: 0,
                    dia_id: 0,
                    hora_entrada : '',
                    hora_salida_comida: '',
                    hora_entrada_comida: '',
                    hora_salida: ''
                },
                listaTipos: [],
                listaDias: [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'hora_entrada','hora_salida_comida', 'hora_entrada_comida', 'hora_salida','tipo', 'dia'],
                tableData: [],
                options: {
                    headings: {
                        hora_entrada: 'Entrada',
                        hora_salida_comida: 'Salida a Comer',
                        hora_entrada_comida: 'Entrada de Comida',
                        hora_salida: 'Salida',
                        tipo: 'Tipo de horario',
                        dia: 'Dia',
                        id: 'Acciones',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['hora_entrada', 'hora_salida', 'tipo'],
                    filterable: ['hora_entrada', 'hora_salida', 'tipo'],
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
                axios.get('/horario').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getListaTipos() {
                let me=this;
                axios.get('/tipohorario').then(response => {
                    me.listaTipos = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });

                axios.get('/horario/create').then(response => {
                    me.listaDias = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            guardarHorario(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/horario' : '/horario/' + this.horario.id,
                            data: {
                                'hora_entrada': this.horario.hora_entrada,
                                'hora_salida_comida': this.horario.hora_salida_comida,
                                'hora_entrada_comida': this.horario.hora_entrada_comida,
                                'hora_salida': this.horario.hora_salida,
                                'tipo_horario_id': this.horario.tipo_horario_id,
                                'dia_id': this.horario.dia_id,
                                'id': this.horario.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                                toastr.success('Horario Actualizado Correctamente');
                                
                                }
                                else
                                {
                                toastr.success('Horario Registrado Correctamente');
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
                Utilerias.resetObject(this.horario);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "horario":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar horario';
                                Utilerias.resetObject(this.horario);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar horario';
                                this.tipoAccion=2;
                                this.horario.id=data['id'];
                                this.horario.dia_id=data['dia_id'];
                                this.horario.tipo_horario_id=data['tipo_horario_id'];
                                this.horario.hora_entrada = data['hora_entrada'];
                                this.horario.hora_salida_comida=data['hora_salida_comida'];
                                this.horario.hora_entrada_comida=data['hora_entrada_comida'];
                                this.horario.hora_salida = data['hora_salida'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.getData();
            this.getListaTipos();
        }
    }
</script>