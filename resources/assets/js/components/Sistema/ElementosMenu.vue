<template>
    <main class="main">
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <!-- <br> -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Elementos Menu y Submenu
                    <button type="button" @click="abrirModal('submenu','registrar')" class="btn btn-dark float-sm-right" :disabled="(modulo==0)">
                        <i class="fas fa-plus"></i>&nbsp;Submenu
                    </button>
                    <button type="button" @click="abrirModal('menu','registrar')" class="btn btn-dark float-sm-right" :disabled="(modulo==0)">
                        <i class="fas fa-plus"></i>&nbsp;Menu
                    </button>
                </div>
                <div class="card-body">
                    <vue-element-loading :active="isLoading"/>
                    <div class="form-group row">
                        <div class="col-md-2 col-sm-2">
                            <label for="modulo">Modulo</label>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <div class="input-group">
                                <select class="form-control" v-model="modulo" id="modulo" v-validate="'required'" name="modulo" placeholder="Modulo">
                                    <option v-for="item in listaModulos" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                </select>
                            </div>
                            <span class="text-danger">{{ errors.first('modulo') }}</span>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <button class="btn btn-dark" type="button" @click="buscar()"><i class="fas fa-search"></i>&nbsp; Buscar</button>
                        </div>
                    </div>

                    <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                        <template slot="id" slot-scope="props">
                         <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group dropup" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">   
                            <template>
                                <button type="button" @click="abrirModal( (props.row.es_menu) ? 'menu' : 'submenu','actualizar',props.row)" 
                                class="dropdown-item" >
                                <i class="icon-pencil"></i>&nbsp;Actualizar
                                </button>
                            </template>
                            
                            <template>
                            <button type="button" class="dropdown-item" @click="eliminar(props.row)" >
                              <i class="icon-trash"></i>&nbsp;Eliminar
                            </button>
                            </template>
                                </div>
                            </div>
                         </div>

                        </template>
                        <template slot="clase" slot-scope="props">
                            <i :class="props.row.clase"></i>
                        </template>
                    </v-client-table>
                    
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal Menu agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalMenu}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <vue-element-loading :active="isLoadingMenu"/>
            <div class="modal-dialog modal-dark modal-lg" role="document">
                <div class="modal-content">
                    <div>
                    
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModalMenu"></h4>
                        <button type="button" class="close" @click="cerrarModalMenu()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" data-vv-scope="form-menu">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="menu_name">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" v-validate="'required'" name="menu_name" v-model="menu.name" class="form-control" placeholder="Nombre" autocomplete="off" id="menu_name" data-vv-as="Nombre" maxlength="50">
                                <span class="text-danger">{{ errors.first('form-menu.menu_name') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="menu_page">Página</label>
                            <div class="col-md-9">
                                <input type="text" name="menu_page" v-model="menu.page" class="form-control" placeholder="Página" autocomplete="off" id="menu_page" data-vv-as="Página" maxlength="50">
                                <span class="text-danger">{{ errors.first('form-menu.menu_page') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="menu_clase">Clase</label>
                            <div class="col-md-9">
                                <input type="text" v-validate="'required'" name="menu_clase" v-model="menu.clase" class="form-control" placeholder="Clase" autocomplete="off" id="menu_clase" data-vv-as="Clase" maxlength="50">
                                <span class="text-danger">{{ errors.first('form-menu.menu_clase') }}</span>
                            </div>
                        </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="cerrarModalMenu()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                        <button type="button" v-if="tipoAccionMenu==1" class="btn btn-secondary" @click="guardarMenu(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                        <button type="button" v-if="tipoAccionMenu==2" class="btn btn-secondary" @click="guardarMenu(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                    </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

        <!--Inicio del modal Menu agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalSubmenu}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-lg" role="document">
                <div class="modal-content">
                    <div>
                    <vue-element-loading :active="isLoadingSubmenu"/>
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModalSubmenu"></h4>
                        <button type="button" class="close" @click="cerrarModalSubmenu()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" data-vv-scope="form-submenu">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="submenu_submenu">Submenu</label>
                            <div class="col-md-9">
                                <input type="text" v-validate="'required'" name="submenu_submenu" v-model="submenu.submenu" class="form-control" placeholder="Nombre" autocomplete="off" id="submenu_submenu" data-vv-as="Submenu" maxlength="100">
                                <span class="text-danger">{{ errors.first('form-submenu.submenu_submenu') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="submenu_page">Página</label>
                            <div class="col-md-9">
                                <input type="text" name="submenu_page" v-validate="'required'" v-model="submenu.page" class="form-control" placeholder="Página" autocomplete="off" id="submenu_page" data-vv-as="Página" maxlength="100">
                                <span class="text-danger">{{ errors.first('form-submenu.submenu_page') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="submenu_clase">Clase</label>
                            <div class="col-md-9">
                                <input type="text" v-validate="'required'" name="submenu_clase" v-model="submenu.clase" class="form-control" placeholder="Clase" autocomplete="off" id="submenu_clase" data-vv-as="Clase" maxlength="100">
                                <span class="text-danger">{{ errors.first('form-submenu.submenu_clase') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="submenu_menu">Menu</label>
                            <div clasS="col-md-9">
                                <select class="form-control" v-model="submenu.elementos_menu_id" id="submenu_menu" name="submenu_modulo_id" v-validate="'excluded:0'" data-vv-as="Menu">
                                    <option v-for="item in listaMenus" :value="item.id" :key="item.id">{{ item.name }}</option>
                                </select>
                                <span class="text-danger">{{ errors.first('form-submenu.submenu_modulo_id') }}</span>
                            </div>
                        </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="cerrarModalSubmenu()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                        <button type="button" v-if="tipoAccionSubmenu==1" class="btn btn-secondary" @click="guardarSubmenu(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                        <button type="button" v-if="tipoAccionSubmenu==2" class="btn btn-secondary" @click="guardarSubmenu(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
    data() {
        return {
            modalMenu: 0,
            tituloModalMenu: '',
            tipoAccionMenu: 0,
            modalSubmenu: 0,
            tituloModalSubmenu: '',
            tipoAccionSubmenu: 0,
            listaMenus: [],
            menu: {
                id: 0,
                clase: '',
                name: '',
                page: '',
                modulo_id: 0
            },
            submenu: {
                id: 0,
                clase: '',
                name: '',
                submenu: '',
                page: '',
                elementos_menu_id: 0
            },
            modulo: 0,
            listaModulos: [],
            isLoading: false,
            isLoadingMenu: false,
            isLoadingSubmenu: false,
            columns: ['id', 'clase', 'name', 'submenu', 'page'],
            tableData: [],
            options: {
                headings: {
                    id : 'Acciones',
                    clase: 'Clase',
                    name: 'Menu',
                    submenu: 'Submenu',
                    page: 'Página',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['name', 'submenu', 'page'],
                filterable: ['name', 'submenu', 'page'],
                filterByColumn: true,
                texts:config.texts
            },
        }
    },
    methods: {
        getData() {
            let me=this;
            this.isLoading =  true;
            axios.get('/ModulosSistema').then(response => {
                me.listaModulos = response.data;
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        buscar() {
            let me=this;
            this.isLoading =  true;
            axios.get('/elementospormodulo/' + this.modulo).then(response => {
                me.tableData = response.data;
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        eliminar(data) {
            swal({
                title: data.es_menu ? '¿Esta seguro de eliminar el Menu ?' : '¿Esta seguro de eliminar el Submenu?',
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
                    me.isLoading = true;
                    axios.put(data.es_menu ? '/eliminarmenu' : '/eliminarsubmenu',{
                        'id': data.id
                    }).then(function (response) {
                        me.isLoading = false;
                        me.buscar();
                        if(data.es_menu){
                        toastr.success('Menu eliminado correctamente');

                        }else{
                        toastr.success('SubMenu eliminado correctamente');

                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            })
        },
        cerrarModalMenu(){
            this.modalMenu=0;
            this.tituloModalMenu='';
            Utilerias.resetObject(this.menu);
        },
        cerrarModalSubmenu(){
            this.modalSubmenu=0;
            this.tituloModalSubmenu='';
            Utilerias.resetObject(this.submenu);
        },
        abrirModal(modelo, accion, data = []){
            switch(modelo){
                case "menu":
                {
                    switch(accion){
                        case 'registrar':
                        {
                            this.modalMenu = 1;
                            this.tituloModalMenu = 'Registrar menu';
                            Utilerias.resetObject(this.menu);
                            this.tipoAccionMenu = 1;
                            break;
                        }
                        case 'actualizar':
                        {
                            this.modalMenu=1;
                            this.tituloModalMenu='Actualizar menu';
                            this.tipoAccionMenu=2;
                            this.menu.id = data['id'];
                            this.menu.clase = data['clase'];
                            this.menu.name = data['name'];
                            this.menu.page = data['page'];
                            this.menu.modulo_id = data['modulo_id'];
                            break;
                        }
                    }
                }
                break;
                case "submenu":
                {
                    switch(accion){
                        case 'registrar':
                        {
                            let me=this;
                            this.modalSubmenu = 1;
                            this.isLoadingSubmenu =  true;
                            axios.get('/elementosmenupormodulo/' + this.modulo).then(response => {
                                me.listaMenus = response.data;
                                me.isLoadingSubmenu = false;
                                me.tituloModalSubmenu = 'Registrar submenu';
                                Utilerias.resetObject(me.submenu);
                                me.tipoAccionSubmenu = 1;
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                            break;
                        }
                        case 'actualizar':
                        {
                            let me=this;
                            this.modalSubmenu=1;
                            this.isLoadingSubmenu =  true;
                            axios.get('/elementosmenupormodulo/' + this.modulo).then(response => {
                                me.listaMenus = response.data;
                                me.isLoadingSubmenu = false;
                                me.tituloModalSubmenu='Actualizar submenu';
                                me.tipoAccionSubmenu=2;
                                me.submenu.id = data['id'];
                                me.submenu.clase = data['clase'];
                                me.submenu.name = data['name'];
                                me.submenu.page = data['page'];
                                me.submenu.submenu = data['submenu'];
                                me.submenu.elementos_menu_id = data['elementos_menu_id'];
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                            break;
                        }
                    }
                }
                break;
            }
        },
        guardarMenu(nuevo){
            this.$validator.validateAll('form-menu').then(result => {
                if (result) {
                    this.isLoadingMenu = true;
                    let me = this;
                    axios({
                        method: nuevo ? 'POST' : 'PUT',
                        url: nuevo ? '/agregarmenu' : '/actualizarmenu' ,
                        data: {
                            'clase': this.menu.clase,
                            'page': this.menu.page,
                            'name': this.menu.name,
                            'id': this.menu.id,
                            'modulo_id': this.modulo
                        }
                    }).then(function (response) {
                        me.isLoadingMenu = false;
                        if (response.data.status) {
                            me.cerrarModalMenu();
                            me.buscar();
                            if(!nuevo){
                                toastr.success('Menu Actualizado Correctamente');
                            } else {
                                toastr.success('Menu Registrado Correctamente');
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
        guardarSubmenu(nuevo){
            this.$validator.validateAll('form-submenu').then(result => {
                if (result) {
                    this.isLoadingSubmenu = true;
                    let me = this;
                    axios({
                        method: nuevo ? 'POST' : 'PUT',
                        url: nuevo ? '/agregarsubmenu' : '/actualizarsubmenu' ,
                        data: {
                            'clase': this.submenu.clase,
                            'page': this.submenu.page,
                            'name': $("#submenu_menu option:selected").text(),
                            'submenu': this.submenu.submenu,
                            'id': this.submenu.id,
                            'elementos_menu_id': this.submenu.elementos_menu_id
                        }
                    }).then(function (response) {
                        me.isLoadingSubmenu = false;
                        if (response.data.status) {
                            me.cerrarModalSubmenu();
                            me.buscar();
                            if(!nuevo){
                                toastr.success('Submenu Actualizado Correctamente');
                            } else {
                                toastr.success('Submenu Registrado Correctamente');
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

<style>

</style>
