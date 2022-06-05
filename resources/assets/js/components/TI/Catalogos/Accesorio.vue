<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Accesorios
                        <button type="button" @click="abrirModal('accesorio','registrar')" class="btn btn-dark float-sm-right">
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
                                <button type="button" @click="abrirModal('accesorio','actualizar',props.row)" class="dropdown-item">
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
                <div class="modal-dialog modal-dark modal-lg" role="document">
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
                                    <label class="col-md-3 form-control-label" for="tipo">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'required|max:255'" name="tipo" v-model="accesorio.tipo" class="form-control" placeholder="Tipo de accesorio" autocomplete="off" id="nombre">
                                        <span class="text-danger">{{ errors.first('tipo') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="etiqueta">Etiqueta</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'max:10'" name="etiqueta" v-model="accesorio.etiqueta" class="form-control" placeholder="Etiqueta" autocomplete="off" id="razon">
                                        <span class="text-danger">{{ errors.first('etiqueta') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="ns">Número de Serie</label>
                                    <div class="col-md-9">
                                        <input type="text" v-validate="'max:30'" name="ns" v-model="accesorio.ns" class="form-control" placeholder="Número de serie" autocomplete="off" id="razon">
                                        <span class="text-danger">{{ errors.first('ns') }}</span>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarAccesorio(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarAccesorio(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                url: '/accesorio',
                accesorio: {
                    id: 0,
                    tipo : '',
                    etiqueta: '',
                    ns: ''
                },
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'tipo', 'etiqueta', 'ns'],
                tableData: [],
                options: {
                    headings: {
                        ns: 'Número de serie',
                        etiqueta: 'Etiqueta',
                        tipo: 'Tipo',
                        id: 'Acción',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['tipo', 'etiqueta', 'ns'],
                    filterable: ['tipo', 'etiqueta', 'ns'],
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
            guardarAccesorio(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? me.url : me.url+'/'+this.id,
                            data: {
                                'ns': this.accesorio.ns,
                                'etiqueta': this.accesorio.etiqueta,
                                'tipo': this.accesorio.tipo,
                                'id': this.accesorio.id
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                if(!nuevo){
                                toastr.success('Accesorio actualizado correctamente');
                                }
                                else
                                {
                                toastr.success('Accesorio registrado correctamente');

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
                Utilerias.resetObject(this.accesorio);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "accesorio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar accesorio';
                                Utilerias.resetObject(this.accesorio);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar accesorio';
                                this.tipoAccion=2;
                                this.accesorio.id=data['id'];
                                this.accesorio.tipo = data['tipo'];
                                this.accesorio.etiqueta = data['etiqueta'];
                                this.accesorio.ns = data['ns'];
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