<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Caracteristicas de Vehículo.
                        <button type="button" @click="abrirModal('vehiculo','registrar')" class="btn btn-dark float-sm-right">
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
                                <button type="button" @click="abrirModal('vehiculo','actualizar',props.row)" class="dropdown-item">
                                    <i class="icon-pencil"></i>&nbsp; Actualizar Datos
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
                                    <label for="inputCity"> N° Puertas</label>
                                    <input type="text" v-model="caracteristicas.puerta" class="form-control" id="inputCity">
                                  </div>
                                  <div class="form-group col-lg-6">
                                    <label for="inputCity">Clave Vehicular</label>
                                    <input type="text" v-model="caracteristicas.clave" class="form-control" id="inputCity">
                                  </div>
                                </div>

                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="inputCity">Capacidad</label>
                                    <input type="text" v-model="caracteristicas.capacidad" class="form-control" id="inputCity">
                                </div>

                              <div class="form-group col-md-6">
                                <label for="inputCity">Numero de Motor</label>
                                <input type="text" class="form-control" v-model="caracteristicas.motor" id="inputCity">
                            </div>
                                </div>

                                <div class="form-row">
                                <div class="form-group col-md-6">
                                  <label for="inputCity">Color</label>
                                  <input type="text" class="form-control" v-model="caracteristicas.colores" id="inputCity">
                                </div>

                                <div class="form-group col-md-6">
                                  <label for="inputCity">Cilindros</label>
                                  <input type="text" class="form-control" v-model="caracteristicas.cilindros" id="inputCity">
                                </div>
                                </div>

                                <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputCity">Transporte</label>
                                    <input type="text" class="form-control" v-model="caracteristicas.transporte" id="inputCity">
                                </div>


                                  <div class="form-group col-md-6">
                                    <label>Unidad:</label>
                                    <select class="form-control custom-select" id="unidad_id" name="unidad_id"
                                            v-model="caracteristicas.unidad" v-validate="'required'"
                                            data-vv-as="Unidad">
                                        <option value="0">---TODOS---</option>
                                        <option v-for="item in listaUnidad" :value="item.id"
                                                :key="item.id">{{ item.unidad }}
                                        </option>
                                    </select>
                                    <span class="text-danger">{{ errors.first('unidad_id') }}</span>
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
                url: '/listarcaracteristicas',
                caracteristicas: {
                    id: 0,
                    puerta : '',
                    clave  : '',
                    capacidad : '',
                    motor : '',
                    fecha_uno : '',
                    colores : '',
                    cilindros : '',
                    transporte : '',
                    unidad : '',
                    },
                modal : 0,
                tituloModal : '',
                listaUnidad : [],
                tipoAccion : 0,
                isLoading: false,
                columns: ['id','numero_puertas','clave_vehicular','capacidad','numero_motor','colores','cilindros','transporte','nombre'],
                tableData: [],
                options: {
                    headings: {
                        id: 'Acciones',
                        puerta: 'N° de puertas',
                        clave: 'Clave',
                        capacidad: 'Capacidad',
                        motor: 'N° de motor',
                        colores: 'Color de Unidad',
                        cilindros: 'N° de cilindros',
                        transporte: 'Tipo de Transporte',
                        nombre: 'Nombre Unidad',

                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['numero_puertas','clave_vehicular','capacidad','numero_motor','colores','cilindros','transporte','nombre'],
                    filterable: ['numero_puertas','clave_vehicular','capacidad','numero_motor','colores','cilindros','transporte','nombre'],
                    filterByColumn: true,
                    texts:config.texts
                },
            }
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

                axios.get('/listadounidades').then(response => {
                    me.listaUnidad = response.data;
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
                            url: nuevo ? /registracaracteristicas/ : /actualizacaracteristicas/ +this.caracteristicas.id,
                            data: {
                                'id': this.caracteristicas.id,
                                'numero_puertas': this.caracteristicas.puerta,
                                'clave_vehicular': this.caracteristicas.clave,
                                'capacidad': this.caracteristicas.capacidad,
                                'numero_motor': this.caracteristicas.motor,
                                'colores': this.caracteristicas.colores,
                                'cilindros': this.caracteristicas.cilindros,
                                'transporte': this.caracteristicas.transporte,
                                'unidad_id': this.caracteristicas.unidad,
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
                                toastr.success('Registro Guardado Correctamente');

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
                    case "vehiculo":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Caracteristicas de Vehículo';
                                Utilerias.resetObject(this.caracteristicas);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Caracteristicas de Vehículo';
                                this.tipoAccion=2;
                                this.caracteristicas.id=data['id'];
                                this.caracteristicas.puerta = data['numero_puertas'];
                                this.caracteristicas.clave = data['clave_vehicular'];
                                this.caracteristicas.capacidad = data['capacidad'];
                                this.caracteristicas.motor = data['numero_motor'];
                                this.caracteristicas.colores = data['colores'];
                                this.caracteristicas.cilindros = data['cilindros'];
                                this.caracteristicas.transporte = data['transporte'];
                                this.caracteristicas.unidad = data['unidad_id'];
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
