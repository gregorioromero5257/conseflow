<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Juntas
                        <button  type="button" @click="abrirModal('juntas','registrar')" class="btn btn-dark float-sm-right">
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
                                <button type="button" @click="abrirModal('juntas','actualizar',props.row)" class="dropdown-item" >
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
                                  <label class="col-md-3 form-control-label" for="nombre">Servicio</label>
                                  <div class="col-md-9">
                                      <select class="form-control" v-model="juntas.servicio_id" name="">
                                        <option v-for="elem in servicios" :key="elem.key" :value="elem.id">{{elem.nombre}}</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('nombre') }}</span>
                                  </div>
                              </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="Soldadura">Soldadura</label>
                                    <div class="col-md-9">
                                        <input type="text"  name="Soldadura" v-model="juntas.soldadura"
                                         class="form-control" placeholder="Nombre de categoría" autocomplete="off" id="Soldadura">
                                        <span class="text-danger">{{ errors.first('Soldadura') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="Diametro">Diametro</label>
                                    <div class="col-md-9">
                                        <input type="text"  name="Diametro" v-model="juntas.diametro"
                                         class="form-control" placeholder="Nombre de categoría" autocomplete="off" id="Diametro">
                                        <span class="text-danger">{{ errors.first('Diametro') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="Espesor">Espesor</label>
                                    <div class="col-md-9">
                                        <input type="text"  name="Espesor" v-model="juntas.espesor"
                                         class="form-control" placeholder="Nombre de categoría" autocomplete="off" id="Espesor">
                                        <span class="text-danger">{{ errors.first('Espesor') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="material_uno">Material Uno</label>
                                    <div class="col-md-9">
                                        <input type="text"  name="material_uno" v-model="juntas.material_uno"
                                        class="form-control" placeholder="Ingrese descripción" autocomplete="off" id="material_uno">
                                        <span class="text-danger">{{ errors.first('material_uno') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="material_dos">Material Dos</label>
                                    <div class="col-md-9">
                                        <input type="text"  name="material_dos" v-model="juntas.material_dos"
                                        class="form-control" placeholder="Ingrese descripción" autocomplete="off" id="material_dos">
                                        <span class="text-danger">{{ errors.first('material_dos') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="proyecto">Proyecto</label>
                                    <div class="col-md-9">
                                        <input type="text"  name="proyecto" v-model="juntas.proyecto"
                                        class="form-control" placeholder="Ingrese descripción" autocomplete="off" id="proyecto">
                                        <span class="text-danger">{{ errors.first('proyecto') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="Observaciones">Observaciones</label>
                                    <div class="col-md-9">
                                        <input type="text"  name="Observaciones" v-model="juntas.observaciones"
                                         class="form-control" placeholder="Nombre de categoría" autocomplete="off" id="Observaciones">
                                        <span class="text-danger">{{ errors.first('Observaciones') }}</span>
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
                juntas: {
                    id: 0,
                    servicio_id : '',
                    soldadura : '',
                    material_uno : '',
                    material_dos : '',
                    proyecto : 'TERMINAL DE PETROLÍFEROS VALLE DE MÉXICO',
                    diametro : '',
                    espesor : '',
                    observaciones : '',
                },
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                servicios : [],
                columns: ['id', 'soldadura','diametro' ,'espesor','material_uno', 'material_dos','servicio','proyecto','observaciones'],
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
                    sortable: ['soldadura','diametro' ,'espesor','material_uno', 'material_dos','servicio','proyecto','observaciones'],
                    filterable: ['soldadura','diametro' ,'espesor','material_uno', 'material_dos','servicio','proyecto','observaciones'],
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
                axios.get('/servicioselementos').then(response => {
                    me.servicios = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });

                axios.get('/materialservicios').then(response => {
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
                            url: nuevo ? '/juntas/registrar' : '/juntas/actualizar/' + this.juntas.id,
                            data: {
                                'soldadura': this.juntas.soldadura,
                                'servicio_id': this.juntas.servicio_id,
                                'material_uno': this.juntas.material_uno,
                                'material_dos': this.juntas.material_dos,
                                'proyecto': this.juntas.proyecto,
                                'diametro' : this.juntas.diametro,
                                'espesor' : this.juntas.espesor,
                                'observaciones' : this.juntas.observaciones,
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                                toastr.success('Junta Actualizada Correctamente');
                                }
                                else
                                {
                                toastr.success('Junta Registrada Correctamente');

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
                    case "juntas":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Soldadores';
                                Utilerias.resetObject(this.juntas);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Soldadores';
                                this.tipoAccion=2;
                                this.juntas.id=data['id'];
                                this.juntas.soldadura = data['soldadura'];
                                this.juntas.servicio_id= data['servicio_id'];
                                this.juntas.material_uno= data['material_uno'];
                                this.juntas.material_dos= data['material_dos'];
                                this.juntas.proyecto= data['proyecto'];

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
