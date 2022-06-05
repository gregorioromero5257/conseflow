<template>
        <main class="main">
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <br>
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Almacenes
                    <button v-show="PermisosCRUD.Create" type="button" @click="abrirModal('nivel','registrar')" class="btn btn-dark float-sm-right">
                        <i class="fas fa-plus"></i>&nbsp;Nuevo Nivel
                    </button>
                    <button v-show="PermisosCRUD.Create" type="button" @click="abrirModal('stand','registrar')" class="btn btn-dark float-sm-right">
                        <i class="fas fa-plus"></i>&nbsp;Nuevo Stand
                    </button>
                    <button v-show="PermisosCRUD.Create" type="button" @click="abrirModal('almacen','registrar')" class="btn btn-dark float-sm-right">
                        <i class="fas fa-plus"></i>&nbsp;Nuevo Almacen
                    </button>
                </div>
                <div class="card-body">

                    <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                        <template slot="condicion" slot-scope="props" >
                             
                            <template v-if="props.row.condicion">
                                <button type="button" class="btn btn-outline-success">Activo</button>
                            </template>
                            <template v-else>
                               <button type="button" class="btn btn-outline-danger">Desactivado</button>
                            </template>
                        </template>
                        <template slot="stand" slot-scope="props">
                            <template v-if="props.row.stand != null">
                            <button type="button"  class="btn btn-link" @click="abrirModal('stand','actualizar',props.row)">{{props.row.stand}}</button>
                            </template>
                        </template>
                        <template slot="nivel" slot-scope="props">
                            <template v-if="props.row.nivel != null">
                            <button type="button"  class="btn btn-link" @click="abrirModal('nivel','actualizar',props.row)">{{props.row.nivel}}</button>
                            </template>
                        </template>
                        <template slot="id" slot-scope="props">
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group dropup" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
                            <button type="button" @click="abrirModal('almacen','actualizar',props.row)" class="dropdown-item" >
                                <i class="icon-pencil"></i>&nbsp; Actualizar Almacen
                            </button>
                            <template v-if="props.row.condicion">
                                <button type="button" class="dropdown-item" @click="desactivarAlmacen(props.row.id)" >
                                    <i class="fas fa-ban"></i>&nbsp; Desactivar
                                </button>
                            </template>
                            <template v-else>
                                <button type="button" class="dropdown-item" @click="activarAlmacen(props.row.id)" >
                                    <i class="icon-check"></i>&nbsp; Activar
                                </button>
                            </template>
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
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" data-vv-scope="form-almacen">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                                <div class="col-md-9">
                                    <input type="text" v-validate="'required'" name="nombre" v-model="nombre" class="form-control" placeholder="Nombre del almacen" autocomplete="off" id="nombre">
                                    <span class="text-danger">{{ errors.first('form-almacen.nombre') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="ubicacion_id">Ubicación</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="ubicacion_id"  name="ubicacion_id" v-model="ubicacion_id" v-validate="'excluded:0'" data-vv-as="Ubicación">
                                        <option v-for="item in listaUbicaciones" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                    </select>
                                    <span class="text-danger">{{ errors.first('form-almacen.ubicacion_id') }}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="registrarAlmacen()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="actualizarAlmacen()"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

        <!--Inicio del modal agregar/actualizar stand-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalStand}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" data-vv-scope="form-stand">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="almacen_id">Almacen</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="almacen_id"  name="almacen_id" v-model="almacen_id" v-validate="'excluded:0'" data-vv-as="Almacen">
                                        <option v-for="item in listaAlmacenes" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                    </select>
                                    <span class="text-danger">{{ errors.first('form-stand.almacen_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="stand">Stand</label>
                                <div class="col-md-9">
                                    <input type="text" v-validate="'required'" name="stand" v-model="stand" class="form-control" placeholder="Nombre del stand" autocomplete="off" id="stand">
                                    <span class="text-danger">{{ errors.first('form-stand.stand') }}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="registrarStand()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="actualizarStand()"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

        <!--Inicio del modal agregar/actualizar nivel-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalNivel}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" data-vv-scope="form-nivel">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="almacen_id">Almacen</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="almacen_id"  name="almacen_id" v-model="almacen_id" v-validate="'excluded:0'" data-vv-as="Almacen" v-on:change="onChangeAlmacen">
                                        <option v-for="item in listaAlmacenes" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                    </select>
                                    <span class="text-danger">{{ errors.first('form-nivel.almacen_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="stand_id">Stand</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="stand_id"  name="stand_id" v-model="stand_id" v-validate="'excluded:0'" data-vv-as="Stand">
                                        <option v-for="item in listaStands" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                    </select>
                                    <span class="text-danger">{{ errors.first('form-nivel.stand_id') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="nivel">Nivel</label>
                                <div class="col-md-9">
                                    <input type="text" v-validate="'required'" name="nivel" v-model="nivel" class="form-control" placeholder="Nombre del nivel" autocomplete="off" id="nivel">
                                    <span class="text-danger">{{ errors.first('form-nivel.nivel') }}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="registrarNivel()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @keypress.enter="actualizarNivel()"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                PermisosCRUD : {},
                almacen_id: 0,
                nombre : '',
                ubicacion_id: 0,
                stand_id: 0,
                stand: '',
                nivel_id: 0,
                nivel: '',
                listaAlmacenes: [],
                listaStands: [],
                listaUbicaciones: [],
                modal : 0,
                modalStand: 0,
                modalNivel: 0,
                tituloModal : '',
                tipoAccion : 0,
                columns: ['id', 'nombre', 'ubicacion', 'condicion', 'stand', 'nivel'],
                tableData: [],
                options: {
                    headings: {
                        nombre: 'Almacen',
                        condicion: 'Estado',
                        id: 'Acciones',
                        stand: 'Stand',
                        nivel: 'Nivel',
                        ubicacion: 'Ubicación'
                    },
                    perPage: 10,
                    perPageValues: [],
                    groupBy: ['nombre'],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['nombre'],
                    filterable: ['nombre', 'condicion', 'stand', 'nivel', 'ubicacion'],
                    filterByColumn: true,
                    listColumns: {
                        condicion: [{
                            id: 1,
                            text: 'Activo'
                        },
                        {
                            id: 0,
                            text: 'Desactivado'
                        }
                        ]
                    },
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
                axios.get('/almacen').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getUbicaciones() {
                let me=this;
                axios.get('/tipoubicacion').then(response => {
                    me.listaUbicaciones = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarAlmacen(){
                this.$validator.validateAll('form-almacen').then(result => {
                    if (result) {
                        let me = this;
                        axios.post('/almacen/registrar',{
                            'nombre': this.nombre,
                            'ubicacion_id': this.ubicacion_id,
                        }).then(function (response) {
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                toastr.success('Almacen Registrado Correctamente')
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
            actualizarAlmacen(){
                this.$validator.validateAll('form-almacen').then(result => {
                    if (result) {
                        let me = this;

                        axios.put('/almacen/actualizar',{
                            'nombre': this.nombre,
                            'id': this.almacen_id,
                            'ubicacion_id': this.ubicacion_id,
                        }).then(function (response) {
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                toastr.success('Almacen Actualizado Correctamente')
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
            desactivarAlmacen(id){
               swal({
                title: 'Esta seguro de desactivar esta almacen?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/almacen/desactivar',{
                        'id': id
                    }).then(function (response) {
                        toastr.error(
                        'El registro ha sido desactivado con éxito.'
                        );
                        me.getData();
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                }
                }) 
            },
            activarAlmacen(id){
               swal({
                title: 'Esta seguro de activar esta almacen?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/almacen/activar',{
                        'id': id
                    }).then(function (response) {
                        toastr.success(
                        'El registro ha sido activado con éxito.'
                        );
                        me.getData();
                    }).catch(function (error) {
                        console.log(error);
                    });                       
                }
                }) 
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.modalStand = 0;
                this.modalNivel = 0;
                this.ubicacion_id = 0;
            },
            abrirModal(modelo, accion, data = []){

                switch(modelo){
                    case "almacen":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar almacen';
                                this.nombre= '';
                                this.tipoAccion = 1;
                                this.ubicacion_id = 0;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar almacen';
                                this.tipoAccion=2;
                                this.almacen_id=data['id'];
                                this.nombre = data['nombre'];
                                this.ubicacion_id =  data['ubicacion_id'];
                                break;
                            }
                        }
                        break;
                    }
                    case "stand":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modalStand = 1;
                                this.tituloModal = 'Registrar stand';
                                this.nombre= '';
                                this.almacen_id = 0;
                                this.stand_id = 0;
                                this.stand = '';
                                this.nivel_id = 0;
                                this.nivel = '';
                                this.tipoAccion = 1;
                                let me=this;
                                axios.get('/almacen/getlist').then(response => {
                                    me.listaAlmacenes = response.data;
                                })
                                .catch(function (error) {
                                    console.log(error);
                                });
                                break;
                            }
                            case 'actualizar':
                            {
                                let me = this;
                                axios.get('/almacen/getlist').then(response => {
                                    me.listaAlmacenes = response.data;
                                    this.modalStand=1;
                                    this.tituloModal='Actualizar stand';
                                    this.tipoAccion=2;
                                    this.nombre= '';
                                    this.almacen_id = data['id'];
                                    this.stand_id = data['stand_id'];
                                    this.stand = data['stand'];
                                    this.nivel_id = 0;
                                    this.nivel = '';
                                })
                                .catch(function (error) {
                                    console.log(error);
                                });
                                break;
                            }
                        }
                        break;
                    }
                    case "nivel":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modalNivel = 1;
                                this.tituloModal = 'Registrar nivel';
                                this.nombre= '';
                                this.almacen_id = 0;
                                this.stand_id = 0;
                                this.stand = '';
                                this.nivel_id = 0;
                                this.nivel = '';
                                this.tipoAccion = 1;
                                let me=this;
                                axios.get('/almacen/getlist').then(response => {
                                    me.listaAlmacenes = response.data;
                                })
                                .catch(function (error) {
                                    console.log(error);
                                });
                                break;
                            }
                            case 'actualizar':
                            {
                                let me = this;
                                axios.get('/almacen/getlist').then(response => {
                                    me.listaAlmacenes = response.data;
                                    axios.get('/almacen/getlist/stand/' + data['id']).then(responsestand => {
                                        me.listaStands = responsestand.data;
                                        this.modalNivel=1;
                                        this.tituloModal='Actualizar Nivel';
                                        this.tipoAccion=2;
                                        this.nombre= '';
                                        this.almacen_id = data['id'];
                                        this.stand_id = data['stand_id'];
                                        this.stand = data['stand'];
                                        this.nivel_id = data['nivel_id'];
                                        this.nivel = data['nivel'];
                                    })
                                })
                                .catch(function (error) {
                                    console.log(error);
                                });
                                break;
                            }
                        }
                        break;
                    }
                }
            },
            actualizarStand() {
                this.$validator.validateAll('form-stand').then(result => {
                    if (result) {
                        let me = this;

                        axios.put('/almacen/actualizar/stand',{
                            'id': this.stand_id,
                            'stand': this.stand,
                            'almacen_id': this.almacen_id
                        }).then(function (response) {
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                toastr.success('Stand Actualizado Correctamente')
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
            registrarStand() {
                this.$validator.validateAll('form-stand').then(result => {
                    if (result) {
                        let me = this;

                        axios.post('/almacen/registrar/stand',{
                            'stand': this.stand,
                            'almacen_id': this.almacen_id
                        }).then(function (response) {
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                toastr.success('Stand Registrado Correctamente')
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
            registrarNivel() {
                this.$validator.validateAll('form-nivel').then(result => {
                    if (result) {
                        let me = this;

                        axios.post('/almacen/registrar/nivel',{
                            'nivel': this.nivel,
                            'stand_id': this.stand_id,
                            'almacen_id': this.almacen_id
                        }).then(function (response) {
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                toastr.success('Nivel Registrado Correctamente')
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
            actualizarNivel() {
                this.$validator.validateAll('form-nivel').then(result => {
                    if (result) {
                        let me = this;

                        axios.put('/almacen/actualizar/nivel',{
                            'id': this.nivel_id,
                            'nivel': this.nivel,
                            'stand_id': this.stand_id,
                            'almacen_id': this.almacen_id
                        }).then(function (response) {
                            if (response.data.status) {
                                me.cerrarModal();
                                me.getData();
                                toastr.success('Nivel Actualizado Correctamente')
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
            onChangeAlmacen() {
                let me = this;
                axios.get('/almacen/getlist/stand/' + this.almacen_id).then(response => {
                    me.listaStands = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted() {
            this.getData();
            this.getUbicaciones();
        }
    }
</script>

