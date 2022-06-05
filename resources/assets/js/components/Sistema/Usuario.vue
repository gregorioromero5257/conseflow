<template>
    <main class="main">
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <br>
        <div class="card">
            <boton-nuevo v-bind:arreglo="arreglo" v-on:abrir="abrirModal($event,...args)"></boton-nuevo>
        
            <div class="card-body">
                <!--
                v-client-table es la tabla que se muestra en la vista principal del empleado
                Parametros
                columns las columnas que se van a ver
                data la informacion que se va a mostrar
                options las opciones de la tabla
                -->
                <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">

                    <template slot="sesion" slot-scope="props">
                        <template >
                            <button type="button" class="btn btn-warning"  v-model="session_id" @click="reiniciar(props.row.usuario.id)"  >
                               Reiniciar
                            </button>
                        </template>
                    </template>

                    <template slot="usuario.condicion" slot-scope="props" >
                        <template v-if="props.row.usuario.condicion">
                            <button type="button" class="btn btn-outline-success">Activo</button>
                        </template>
                        <template v-else>
                            <button type="button" class="btn btn-outline-danger">Dado de Baja</button>
                        </template>
                    </template>
                    <template slot="usuario.session_id" slot-scope="props" >
                        <template v-if="props.row.usuario.session_id">
                            <button type="button" class="btn btn-success">Online</button>
                            <button v-if="props.row.miusuario.id == 1" type="button" @click="SignOut(props.row.usuario.id)" class="btn btn-dark btn-sm">
                                <i class="fas fa-sign-out-alt">&nbsp;Sign Out</i>
                            </button>
                        </template>
                        <template v-else>
                            <button type="button" class="btn btn-danger">Offline</button>
                        </template>
                    </template>
                    <template slot="usuario.id" slot-scope="props">  
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                      <div class="btn-group dropup" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-grip-horizontal"></i> Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
                        <template>
                        <button type="button"  @click="abrirModal(['usuario','actualizar',props.row])"
                        class="dropdown-item" >
                            <i class="icon-pencil"></i>&nbsp;Actualizar Usuario
                        </button>
                        </template>

                        <template v-if="props.row.usuario.condicion">
                            <button  class="dropdown-item" @click="actdesact(props.row.usuario.id,0)">
                                <i class="fas fa-ban"></i>&nbsp;Desactivar
                            </button>
                        </template>
                        <template v-else>
                            <button class="dropdown-item" @click="actdesact(props.row.usuario.id,1)">
                                <i class="icon-check"></i>&nbsp;Activar
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
                    <div class="form" v-if="tipoAccion==1">
                        <div class="form-group row">
                             <label class="col-md-2 " for="text-input">Nombre</label>
                            <div class="col-md-4">
                            <input type="text" v-validate="'required'" name="name" v-model="name" class="form-control" placeholder="Nombre">
                            <span class="text-danger">{{ errors.first('name') }}</span>
                            </div>   
                            <label class="col-md-2 " for="text-input">Usuario</label>
                            <div class="col-md-4">
                            <input type="text" v-validate="'required'" name="name_user" v-model="name_user" class="form-control" placeholder="Usuario">
                            <span class="text-danger">{{ errors.first('name_user') }}</span>
                            </div>   
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 " for="text-input">Correo</label>
                                <div class="col-md-4">
                                <input type="text"  name="email" v-model="email" class="form-control" placeholder="Correo">
                                <span class="text-danger">{{ errors.first('email') }}</span>
                                </div>
                            <label class="col-md-2 " for="text-input">Password</label>
                                <div class="col-md-4">
                                <input type="password" v-validate="'required'" name="password" v-model="password" class="form-control" placeholder="Contraseña">
                                <span class="text-danger">{{ errors.first('password') }}</span>
                                </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 " for="text-input">Ubicacion</label>
                            <div class="col-md-4">
                            <select class="form-control" id="tipo_ubicacion_id"  name="tipo_ubicacion_id" v-model="tipo_ubicacion_id" v-validate="'excluded:0'" data-vv-as="Ubicacion">
                                    <option v-for="item in listaUbicaciones" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                </select>
                                <span class="text-danger">{{ errors.first('tipo_ubicacion_id') }}</span>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form" v-if="tipoAccion==2">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input"></label>
                            <div class="col-md-9">
                            <input type="hidden" v-model="id" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                             <label class="col-md-2 " for="text-input">Nombre</label>
                            <div class="col-md-4">
                            <input type="text" v-validate="'required'" name="name" v-model="name" class="form-control" placeholder="Nombre">
                            <span class="text-danger">{{ errors.first('name') }}</span>
                            </div>   
                            <label class="col-md-2 " for="text-input">Usuario</label>
                            <div class="col-md-4">
                            <input type="text" v-validate="'required'" name="name_user" v-model="name_user" class="form-control" placeholder="Nombre de Usuario">
                            <span class="text-danger">{{ errors.first('name_user') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 " for="text-input">Correo</label>
                            <div class="col-md-4">
                            <input type="text" v-model="email" class="form-control" placeholder="Correo">
                            </div> 
                            <label class="col-md-2 " for="text-input">Password</label>
                            <div class="col-md-4">
                            <input type="password" name="password" v-model="password" class="form-control" placeholder="Nueva Contraseña">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 " for="text-input">Ubicacion</label>
                            <div class="col-md-4">
                                <select class="form-control" id="tipo_ubicacion_id"  name="tipo_ubicacion_id" v-model="tipo_ubicacion_id" v-validate="'excluded:0'" data-vv-as="Ubicacion">
                                    <option v-for="item in listaUbicaciones" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                </select>
                                <span class="text-danger">{{ errors.first('tipo_ubicacion_id') }}</span>
                            </div> 
                        </div>                                                                
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" form="text-empleado">Empleado</label>
                            <div class="col-md-6">
                                <select class="form-control" id="empleado_id"  name="empleado_id" v-model="empleado_id" data-vv-as="Empleado">
                                    <option value="0">------</option>
                                    <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{ item.empleado.nombre+' '+item.empleado.ap_paterno+' '+item.empleado.ap_materno }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                        <div v-show="error" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsj" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="registrar()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="actualizar()"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
var config = require('../Herramientas/config-vuetables-client').call(this);

    export default {
        data (){ 
            return {
                url: '/usuario',
                modal : 0,

                swal_titulo:'',
                swal_msg :'',
                swal_tle :'',
                empleado_id: 0,
                tipo_ubicacion_id: '',
                listaUbicaciones: [],
                listaEmpleados: [],
                id:0,
                name : '',
                name_user : '',
                password : '',
                ubicacion: '',
                email : '',
                tituloModal : '',
                tipoAccion : 0,
                error : 0,
                session_id : 0,
                isLoading: false,
                errorMostrarMsj : [],
                arreglo :{
                    titulo: 'Usuarios',
                    emit:   'abrirModal',
                    modulo: 'usuario',
                    accion: 'registrar',
                },
                json_fields : {
                        'name' : 'String',
                        'name_user' : 'String',
                        'email': 'String',
                        'password' : 'String',                        
                        'condicion' : 'String',
                        'session_id' : 'String'
                },
                json_data : [],
                json_meta: [
                    [{
                        "key": "charset",
                        "value": "utf-8"
                    }]
                ],
                columns: [
                    'usuario.id',
                    'usuario.name',
                    'usuario.name_user',
                    'usuario.email',
                    'ubicacion.nombre',
                    'usuario.condicion',
                    'usuario.session_id',
                    'sesion'
                    
                ],
                tableData: [],
                options: {
                    headings: {
                        'usuario.id': 'Acciones',
                        'usuario.name': 'Nombre',
                        'usuario.name_user': 'Usuario',
                        'usuario.email': 'Email',
                        'ubicacion.nombre' :'Ubicacion',
                        'usuario.condicion': 'Estado',
                        'usuario.session_id': 'Conexión',
                        'sesion' : 'Reiniciar Sesión'
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: [
                        'usuario.name',
                        'usuario.name_user',
                        'usuario.email',
                        'usuario.tipo_ubicacion_id',
                        'usuario.condicion',
                        'usuario.session_id'
                        ],
                    filterable: [
                        'usuario.name',
                        'usuario.name_user',
                        'usuario.email',
                        'usuario.tipo_ubicacion_id',
                        'usuario.condicion',
                        'usuario.session_id',
                    ],
                    filterByColumn: true,
                    listColumns: {
                        'usuario.condicion': [{
                            id: 1,
                            text: 'Activo'
                        },
                        {
                            id: 0,
                            text: 'Baja'
                        }
                        ],
                        'usuario.session_id': [{
                            id: 1,
                            text: 'OnLine'
                        },
                        {
                            id: 0,
                            text: 'OffLine'
                        }
                        ]
                    },
                    texts:config.texts
                },
            }
        },
        methods : {
            listarEmpleados(){
                let me=this;
                axios.get('/empleado').then(response => {
                    me.listaEmpleados = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarUbicaciones(){
                let me=this;
                axios.get('/tipoubicacion').then(response => {
                    me.listaUbicaciones = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listar(){
                let me=this;
                axios.get(me.url).then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            SignOut(id){
                this.$validator.validate().then(result => {
                    if (result) {
                        let me = this;
                        axios.get(me.url+'/'+id,{
                            'id' : id,
                        }).then(function (response) {
                            me.cerrarModal();
                            toastr.success('Usuario Desconectado Correctamente')
                            me.listar();
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                });
            },
            registrar(){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios.post(me.url,{
                            'name' : this.name,
                            'name_user' : this.name_user,
                            'password' : this.password,
                            'email' : this.email,
                            'tipo_ubicacion_id' : this.tipo_ubicacion_id,
                        }).then(function (response) {
                            me.isLoading = false;
                            me.cerrarModal();
                            toastr.success('Usuario Registrado Correctamente')
                            me.listar();
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                });
            },
            actualizar(){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios.put(me.url+'/'+this.id,{
                            'name' : this.name,
                            'name_user' : this.name_user,
                            'password' : this.password,
                            'tipo_ubicacion_id' : this.tipo_ubicacion_id,
                            'email' : this.email,
                            'empleado_id': this.empleado_id,
                    }).then(function (response) {
                            me.isLoading = false;
                            me.cerrarModal();
                            toastr.success('Usuario Actualizado Correctamente')
                            me.listar();
                        }).catch(function (error) {
                            console.log(error);
                        }); 
                    }
                });
            },
            actdesact(id,tipo){
                if(tipo){
                    this.swal_titulo = 'Esta seguro de activar este usuario nuevamente?';
                    this.swal_tle = 'Activado';
                    this.swal_msg = 'Usuario activado con éxito.';
                }else{
                    this.swal_titulo = 'Esta seguro de desactivar este usuario?';
                    this.swal_tle = 'Desactivado!';
                    this.swal_msg = 'Usuario desactivado con éxito.';
                }
                swal({
                title: this.swal_titulo,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.get(me.url+'/'+id+'/edit').then(function (response) {
                         if(tipo){
                        toastr.success(me.swal_tle,me.swal_msg,'success');

                        }else{
                        toastr.error(me.swal_tle,me.swal_msg,'error');

                        }
                        me.listar();
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.name = '';
                this.name_user = '';
                this.password = '';
                this.email = '';
                this.tipo_ubicacion_id = ''
            },

            reiniciar(id){
                let me = this;
                axios.get(me.url+'/'+id).then(function (response) {

                }).then(function (response) {
                    me.listar();
                    me.listarEmpleados();
                    toastr.success('Usuario Reiniciado Correctamente')
                }).catch(function (error) {
                    console.log(error);
                });
            },

            abrirModal(modelo, accion=[]){
                var data =  modelo[2];
                switch(modelo[0]){
                    case "usuario":
                    {
                        switch(modelo[1]){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Usuario';
                                this.name = '';
                                this.name_user = '';
                                this.password = '';
                                this.tipo_ubicacion_id = '',
                                this.email = '';
                                this.tipoAccion = 1;
                                this.empleado_id = 0;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Usuario ';
                                this.id = data.usuario.id,
                                this.name = data.usuario.name,
                                this.name_user = data.usuario.name_user,
                                this.password = '',
                                this.email = data.usuario.email,
                                this.tipo_ubicacion_id = data.usuario.tipo_ubicacion_id,
                                this.empleado_id = data.usuario.empleado_id == null ? 0 : data.usuario.empleado_id;
                                this.tipoAccion=2;
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listar();
            this.listarEmpleados();
            this.listarUbicaciones();
        }
    }
</script>