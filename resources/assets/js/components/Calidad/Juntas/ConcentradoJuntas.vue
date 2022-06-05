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
                                    <label for="inputCity"> N° Junta</label>
                                    <input type="text" v-model="juntas.juntas" class="form-control" id="inputCity">
                                  </div>
                                  <div class="form-group col-lg-6">
                                    <label for="inputCity">Diámetro</label>
                                    <input type="text" v-model="juntas.diametro" class="form-control" id="inputCity">
                                  </div>
                                </div>

                                <div class="form-row">
                                  <div class="form-group col-md-12">
                                    <label for="inputCity">Sistema</label>
                                    <input type="text" v-model="juntas.sistema" class="form-control" id="inputCity">
                                </div>
                                </div>

                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="inputCity">Habilitado</label>
                                    <input type="text" class="form-control" v-model="juntas.habilitadas" id="inputCity">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="fecha-inicial">Fecha Habilitado</label>
                                    <input type="date" name="fecha_inicial" v-model="juntas.fecha_uno"    @change="validacionFecha"
                                           id="fecha-inicial" class="form-control" v-validate="'required'"
                                           data-vv-as="Inicio">
                                    <span class="text-danger">{{ errors.first('fecha_inicial') }}</span>
                                </div>
                                </div>

                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="inputCity">Soldado</label>
                                    <input type="text" class="form-control" v-model="juntas.soldadas" id="inputCity">
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="fecha-inicial">Fecha Soldado</label>
                                  <input type="date" name="fecha_inicial" id="fecha-inicial" v-model="juntas.fecha_dos"    @change="validacionFecha"
                                  class="form-control" v-validate="'required'" data-vv-as="Inicio">
                                  <span class="text-danger">{{ errors.first('fecha_inicial') }}</span>
                                </div>
                                </div>

                                <div class="form-row">
                                  <div class="form-group col-md-3">
                                    <label for="inputCity">Clave Soldador</label>
                                    <input type="text" class="form-control" v-model="juntas.w" id="inputCity">
                                  </div>
                                </div>
                              </form>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" :disabled="deshabilitarbtn == 1" class="btn btn-secondary"  @click="guardarJunta(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
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
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

    export default {
        data (){
            return {
                url: '/listarjuntas',
                juntas: {
                    id: 0,
                    juntas : '',
                    diametro : '',
                    sistema : '',
                    habilitadas : '',
                    fecha_uno : '',
                    soldadas : '',
                    fecha_dos : '',
                    w : '',
                  },
                deshabilitarbtn: 1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'juntas','diametro','sistema','habilitadas','fecha_uno','soldadas','fecha_dos','w'],
                tableData: [],
                options: {
                    headings: {
                        juntas: 'No Junta',
                        id: 'Acciones',
                        diametro: 'Diametro',
                        sistema: 'Sistema',
                        habilitadas: 'Habilitadas',
                        fecha_uno:'Fecha de habilitacion',
                        soldadas:'Junta Soldada',
                        fecha_dos:'Fecha de soldadura',
                        w:'Soldador',
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
            guardarJunta(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? /registrajunta/ : /actualizajunta/ +this.juntas.id,
                            data: {
                                'juntas': this.juntas.juntas,
                                'id': this.juntas.id,
                                'diametro' : this.juntas.diametro,
                                'sistema' : this.juntas.sistema,
                                'habilitadas' : this.juntas.habilitadas,
                                'fecha_uno' : this.juntas.fecha_uno,
                                'soldadas' : this.juntas.soldadas,
                                'fecha_dos' : this.juntas.fecha_dos,
                                'w' : this.juntas.w,
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

            validacionFecha() {

                var fecha_inicio = this.juntas.fecha_uno;
                var fecha_fin = this.juntas.fecha_dos;

                if (fecha_fin < fecha_inicio) {
                    toastr.warning('La fecha de habilitado no puede ser mayor a la fecha de soldado', 'ERROR!!!');
                    this.deshabilitarbtn = 1;
                } else {
                    this.deshabilitarbtn = 0;
                }


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
                                this.juntas.id=data['id'];
                                this.juntas.juntas = data['juntas'];
                                this.juntas.diametro = data['diametro'];
                                this.juntas.sistema = data['sistema'];
                                this.juntas.habilitadas = data['habilitadas'];
                                this.juntas.fecha_uno = data['fecha_uno'];
                                this.juntas.soldadas = data['soldadas'];
                                this.juntas.fecha_dos = data['fecha_dos'];
                                this.juntas.w = data['w'];
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
