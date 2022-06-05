<template>
    <div class="card-body">
        <vue-element-loading :active="isLoading"/>
        <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
            <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group dropup" role="group">
            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <button type="button" v-show="PermisosCrud.Update" @click.prevent="abrirModal('actualizar',props.row)" class="dropdown-item">
                    <i class="icon-pencil"></i>&nbsp; Editar
                </button>
            </div>
            </div>
            </div>
            </template>
        </v-client-table>

        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <vue-element-loading :active="isLoadingModal"/>
            <div class="modal-dialog modal-dark modal-lg" role="document">
                <div class="modal-content">
                    <div>

                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button"  class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
                        <input type="hidden" name="id">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="tipo_partida">Tipo de partida</label>
                            <div class="col-md-9">
                                <select class="form-control" id="tipo_partida"  name="tipo_partida" v-model="partida.tipo_partida_id"  data-vv-as="Tipo de partida" v-validate="'excluded:0'" >
                                    <option value="0" selected>---</option>
                                    <option v-for="item in listaTipoPartidas" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                </select>
                            <span class="text-danger">{{ errors.first('tipo_partida') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" v-validate="'required'" name="nombre" v-model="partida.nombre" class="form-control" placeholder="Nombre del tipo de partida" autocomplete="off" id="nombre" data-vv-as="Nombre">
                                <span class="text-danger">{{ errors.first('nombre') }}</span>
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="monto_cotizado">Monto cotizado</label>
                            <div class="col-md-9">
                                <input type="text" v-validate="'required|decimal:2'" name="monto_cotizado" v-model="partida.monto_cotizado" class="form-control" placeholder="Monto cotizado" autocomplete="off" id="monto_cotizado" data-vv-as="Monto cotizado">
                                <span class="text-danger">{{ errors.first('monto_cotizado') }}</span>
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="monto_ejecutado">Monto ejecutado</label>
                            <div class="col-md-9">
                                <input type="text" v-validate="'required|decimal:2'" name="monto_ejecutado" v-model="partida.monto_ejecutado" class="form-control" placeholder="Monto ejecutado" autocomplete="off" id="monto_ejecutado" data-vv-as="Monto ejecutado">
                                <span class="text-danger">{{ errors.first('monto_ejecutado') }}</span>
                            </div>
                        </div>
                        <!-- </form> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarPartida(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarPartida(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                    </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->


    </div>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
    data (){
        return {
            PermisosCrud : {},
            isLoadingModal: false,
            modal: false,
            tipoAccion: 0,
            tituloModal: '',
            proyecto_id: 0,
            partida: {
                id: 0,
                nombre: '',
                monto_ejecutado: 0,
                monto_cotizado: 0,
                tipo_partida_id: 0,
                proyecto_id: 0
            },
            isLoading: false,
            listaTipoPartidas: [],
            tableData: [],
            columns: [
                'id','nombre', 'tipo_partida', 'monto_cotizado','monto_ejecutado','diferencia'
            ],
            tableData: [],
            options: {
                headings: {
                    'id': 'Acciones',
                    'nombre': 'Nombre',
                    'tipo_partida': 'Tipo partida',
                    'monto_cotizado': 'Monto cotizado',
                    'monto_ejecutado': 'Monto ejecutado',
                },
                perPage: 5,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: [
                    'nombre',
                    'tipo_partida',
                    'monto_cotizado',
                    'monto_ejecutado',
                    ],
                filterable: [
                    'nombre',
                    'tipo_partida',
                    'monto_cotizado',
                    'monto_ejecutado',
                ],
                filterByColumn: true,
                listColumns: { },
                texts:config.texts
            },
        }
    },
    methods: {
        cargarPartidas(data) {
            this.proyecto_id = data;
            this.isLoading = true;
            let me=this;
            axios.get('/partidascostos/' + this.proyecto_id).then(response => {
                me.tableData = response.data;
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        agregar() {
            this.abrirModal('registrar');
        },
        getData() {
            let me=this;
            this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
            axios.get('/tipopartidacostos/').then(response => {
                me.listaTipoPartidas = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        cerrarModal(){
            this.modal=0;
            this.tituloModal='';
            Utilerias.resetObject(this.partida);
        },
        abrirModal(accion, data = []){
            switch(accion){
                case 'registrar':
                {
                    this.modal = 1;
                    this.tituloModal = 'Registrar partida';
                    Utilerias.resetObject(this.partida);
                    this.tipoAccion = 1;
                    break;
                }
                case 'actualizar':
                {
                    this.modal=1;
                    this.tituloModal='Actualizar partida';
                    this.tipoAccion=2;
                    this.partida.id=data['id'];
                    this.partida.nombre = data['nombre'];
                    this.partida.monto_ejecutado = data['monto_ejecutado'];
                    this.partida.monto_cotizado = data['monto_cotizado'];
                    this.partida.tipo_partida_id = data['tipo_partida_id'];
                    break;
                }
            }
        },
        guardarPartida(nuevo){
            this.$validator.validate().then(result => {
                if (result) {
                    this.isLoadingModal = true;
                    let me = this;
                    axios({
                        method: nuevo ? 'POST' : 'PUT',
                        url: nuevo ? '/partidascostos/registrar' : '/partidascostos/actualizar',
                        data: {
                            'nombre': this.partida.nombre,
                            'id': this.partida.id,
                            'monto_cotizado': this.partida.monto_cotizado,
                            'monto_ejecutado': this.partida.monto_ejecutado,
                            'tipo_partida_id': this.partida.tipo_partida_id,
                            'proyecto_id': this.proyecto_id,
                        }
                    }).then(function (response) {
                        me.isLoadingModal = false;
                        if (response.data.status) {
                            me.cerrarModal();
                            me.cargarPartidas(me.proyecto_id);
                            if(!nuevo){
                            toastr.success('Partida Actualizada Correctamente');
                            }
                            else
                            {
                            toastr.success('Partida Registrada Correctamente');
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
    },
    mounted() {
        this.getData();
    }
}
</script>
