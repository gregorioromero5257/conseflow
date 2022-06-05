<template >
    <main class="main">
        <div class="container-fluid">

            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist" ref="tabs">
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu1" role="tab" @click="setId(1)"><i class="fas fa-plus"></i>&nbsp;Vales EPP</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#menu2" role="tab" @click="setId(2)"><i class="fas fa-book"></i>&nbsp;Consulta</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="tab-content">
                <!-- AGREGAR MATERIAL DE EPP -->
                <div id="menu1" class="tab-pane fade" v-show="tab == 1">

                    <div class="card" v-show="detalle == false">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Vale entrega EPP
                            <button type="button" @click="abrirModal('vale','registrar')" class="btn btn-dark float-sm-right">
                                <i class="fas fa-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                        <div class="card-body">

                            <!-- INICIO LISTADO DE EMPLEADOS CON VALES -->
                            <v-client-table :data="tableData" :options="options" :columns="columns">


                                <template slot="empleado_id" slot-scope="props">
                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                        <div class="btn-group dropup" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-grip-horizontal"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                                <button type="button" @click="detalles(props.row)" class="dropdown-item" href="#">
                                                    <i class="fas fa-eye"></i>&nbsp;Detalles
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <template slot="descargar" slot-scope="props">
                                    <button type="button" @click="descargar(props.row)" class="btn btn-dark">
                                        <i class="fas fa-file-excel"></i>&nbsp;Descargar
                                    </button>
                                </template>

                            </v-client-table>
                            <!-- FIN LISTADO DE EMPLEADOS CON VALES -->

                        </div>
                    </div>

                    <div class="card" v-show="detalle == true">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Detalle de {{data_detalle == '' ? '' :data_detalle.empleado}}
                            <button type="button" @click="atras()" class="btn btn-secondary float-sm-right">
                                <i class="fa fa-arrow-left"></i>&nbsp;Atras
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- {{data_detalle}} -->
                            <!-- INICIO LISTADO DE EMPLEADOS CON VALES -->
                            <v-client-table :data="tableDataDetalle" :options="optionsDetalle" :columns="columnsDetalle">


                                <template slot="id" slot-scope="props">
                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                        <div class="btn-group dropup" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-grip-horizontal"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                                                <button type="button" @click="eliminar(props.row.id)" class="dropdown-item" href="#">
                                                    <i class="fas fa-trash"></i>&nbsp;Eliminar
                                                </button>
                                                <button type="button" @click="Actualizar(props.row)" class="dropdown-item" href="#">
                                                    <i class="fas fa-save"></i>&nbsp;Actualizar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>


                            </v-client-table>
                            <!-- FIN LISTADO DE EMPLEADOS CON VALES -->

                        </div>
                    </div>

                </div>
                <!-- CONSULTAR MATERIAL ENTREGADO -->
                <div id="menu2" class="tab-pane fade" v-show="tab == 2">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-10 mb-3">
                                    <v-select :options="listadoArticulos" label="descripcion" v-model="articulo_epp"></v-select>
                                </div>
                                <div class="col-md-1 mb-3">
                                    <button class="btn btn-outline-dark" @click="BuscarArtEpp()">Buscar</button>
                                </div>
                                <div class="col-md-1 mb-3">
                                    <button class="btn btn-outline-success" @click="ExportarArtEpp()">Exportar</button>
                                </div>
                            </div>
                            <v-client-table :data="tableDataArticulos" :options="optionsarticulos" :columns="columnsarticulos">
                            </v-client-table>
                        </div>
                    </div>

                </div>
            </div>



            <div class="modal fade" tabindex="-1" :class="{ mostrar: modal }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
                <div class="modal-dialog modal-dark modal-lg" role="document">
                    <div class="modal-content">
                        <div>
                            <div class="modal-header">
                                <h4 class="modal-title">{{tituloModal}}</h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <vue-element-loading :active="isLoading"/>

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Nombre empleado</label>
                                        <v-select :options="listaEmpleados" label="name" v-model="empleado_entrega"></v-select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Proyecto</label>
                                        <v-select :options="listaProyectos" label="name" v-model="proyectoId"></v-select>
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label>Seleccione</label>
                                        <v-select :options="listaCatalogo" v-model="catalogo" label="descripcion" data-vv-name="catalogo" @search="buscar">
                                        </v-select>
                                        <span class="text-danger">{{errors.first('catalogo')}}</span>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-2 mb-3">
                                        <label >Cantidad</label>
                                        <input type="text" class="form-control" v-model="cantidad">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Fecha</label>
                                        <input type="date" class="form-control" v-model="fecha">
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label>Autorizó</label>
                                        <v-select :options="listaEmpleados" label="name" v-model="empleado_autoriza"></v-select>
                                    </div>
                                    <div class="col-md-1 mb-3">
                                        <label>&nbsp;</label><br>
                                        <button @click="guardarasignacion()" class="btn btn-outline-dark" name="button">Crear</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-row">

                                    <div class="form-group col-md-4">
                                        <label><b>Articulo</b></label>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label><b>Empleado</b></label>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label><b>Proyecto</b></label>
                                    </div>
                                    <div class="form-group col-md-1">
                                        <label><b>Cantidad</b></label>
                                    </div>
                                    <div class="form-group col-md-1">
                                        <label><b>Fecha</b></label>
                                    </div>
                                    <div class="form-group col-md-1">
                                        <label><b>Aut</b></label>
                                    </div>
                                    <div class="form-group col-md-1">
                                        <label><b>.</b></label>
                                    </div>
                                </div>
                                <li v-for="(vi, index) in listado_data" class="list-group-item">
                                    <div class="form-row">

                                        <div class="form-group col-md-4">
                                            <label>{{vi.descripcion}}</label>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>{{vi.empleado}}</label>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>{{vi.proyecto}}</label>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label>{{vi.cantidad}}</label>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label>{{vi.fecha}}</label>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <label>{{vi.iniciales}}</label>
                                        </div>
                                        <div class="form-group col-md-1">
                                            <a @click="deleteu(index)">
                                                <span class="fas fa-trash" arial-hidden="true"></span>
                                            </a>
                                        </div>
                                    </div>
                                </li>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-dark" @click="cerrarModal()">
                                    <i class="fas fa-window-close"></i>&nbsp;Cerrar
                                </button>
                                <button v-show="tipoAccion == 1" type="button" class="btn btn-secondary" @click="Guardar(1)">
                                    Guardar
                                </button>
                                <button v-show="tipoAccion == 2" type="button" class="btn btn-secondary" @click="Guardar(0)">
                                    Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal agregar documentos-->


            <div class="modal fade" tabindex="-1" :class="{ mostrar: modal_actualizar }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
                <div class="modal-dialog modal-dark modal-lg" role="document">
                    <div class="modal-content">
                        <div>
                            <div class="modal-header">
                                <h4 class="modal-title">Actualizar</h4>
                                <button type="button" class="close" @click="cerrarModalAct()" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label>Seleccione</label>
                                        <v-select :options="listaCatalogo" v-model="partida.articulo" label="descripcion" data-vv-name="catalogo" @search="buscar">
                                        </v-select>
                                        <span class="text-danger">{{errors.first('catalogo')}}</span>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-3 mb-3">
                                        <label>Cantidad</label>
                                        <input type="text" class="form-control" v-model="partida.cantidad">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Fecha</label>
                                        <input type="date" class="form-control" v-model="partida.fecha">
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label>Autorizó</label>
                                        <v-select :options="listaEmpleados" label="name" v-model="partida.autoriza"></v-select>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-dark" @click="cerrarModalAct()">
                                    <i class="fas fa-window-close"></i>&nbsp;Cerrar
                                </button>
                                <button type="button" class="btn btn-secondary" @click="GuardarAct()">
                                    Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal agregar documentos-->

        </div>
    </main>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
    data (){
        return {
            tab :1,
            isLoading : false,
            modal_actualizar : 0,
            data_detalle : '',
            listaEmpleados : [],
            listadoArticulos : [],
            empleado_entrega : '',
            empleado_autoriza : '',
            empleado_supervisor : '',
            articulo_epp : '',
            proyectoId : '',
            fecha : '',
            tableData : [],
            listaProyectos : [],
            columns : ['empleado_id','empleado','descargar'],
            options:
            {
                headings:
                {
                    empleado_id : 'Acciones',
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            }, //options

            tableDataDetalle : [],
            columnsDetalle : ['id','descripcion','cantidad','fecha','autoriza'],
            optionsDetalle:
            {
                headings:
                {
                    id : 'Acciones',
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            }, //options

            tableDataArticulos : [],
            columnsarticulos : ['descripcion','cantidad','fecha','empleador','empleadoa'],
            optionsarticulos:
            {
                headings:
                {
                    id : 'Acciones',
                    empleador : 'Recibe',
                    empleadoa : 'Autoriza',
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                filterByColumn: true,
                texts: config.texts
            }, //options

            modal : 0,
            tituloModal : '',
            tipoAccion : 0,
            listaCatalogo : [],
            catalogo : '',
            cantidad : '',
            listado_data : [],
            id :0 ,
            detalle : false,
            partida : {
                id : 0,
                articulo : '',
                cantidad : '',
                fecha : '',
                autoriza : '',
            },
        }
    },
    methods : {

        getInicial(){
            axios.get('/vale/epp/seguridad/emp').then(response => {
                this.tableData = response.data;
            }).catch(e => {
                console.error(e);
            });

            axios.get('/vale/epp/seguridad/ver/articulos').then(response => {
                this.listadoArticulos = response.data;
                this.listadoArticulos.push({id : 0, descripcion : 'TODOS'});
            }).catch(e => {
                console.error(e);
            });
        },

        getData(){
            this.listaEmpleados = [];
            axios.get('/vertodosempleados').then(response =>{
                response.data.forEach(data =>{
                    this.listaEmpleados.push({
                        id: data.id,
                        name: data.nombre + ' ' + data.ap_paterno + ' ' + data.ap_materno
                    });
                });
            })
            .catch(function (error)
            {
                console.log(error);
            });
        },

        /**
        * [getProyecto Obtiene todos los proyectos existentes]
        * @return {[type]} [description]
        */
        getProyecto() {
            this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
            let me=this;
            axios.get('/proyecto').then(response => {
                response.data.forEach(value =>{
                    me.listaProyectos.push({
                        id : value.proyecto.id,
                        name : value.proyecto.nombre_corto
                    });
                });
                // me.listaProyectos = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        abrirModal(modelo, accion, data = []){
            switch(modelo){
                case "vale":
                {
                    switch(accion){
                        case 'registrar':
                        {
                            this.modal = 1;
                            this.tituloModal = 'Registrar';
                            this.tipoAccion = 1;
                            break;
                        }
                        case 'actualizar':
                        {
                            this.modal=1;
                            this.tituloModal='Actualizar';
                            this.tipoAccion=2;
                            break;
                        }
                    }
                }
            }
        },

        cerrarModal(){
            this.modal = 0;
        },

        Guardar(nuevo){
            this.isLoading = true;
            axios({
                method : nuevo ? 'POST' : 'PUT',
                url : nuevo ? '/vale/epp/seguridad/guardar' : '/vale/epp/seguridad/actualizar',
                data : {
                    id : this.id,
                    datos : this.listado_data,
                }
            }).then(response => {
                this.getInicial();
                this.listado_data = [];
                this.empleado_entrega = '';
                this.empleado_supervisor = '';
                this.empleado_autoriza = '';
                this.fecha = '';
                this.modal = 0;

                toastr.success(nuevo ? 'Guardado Correctamente' : 'Actualizado Correctamente');
                // console.log(response);
                this.isLoading = false;

            }).catch(e => {
                this.isLoading = false;
                console.error(e);
            });
        },

        buscar (search, loading) {
            //  ... do some asynchronous stuff!
            // console.log(search,'doe');
            if (search.length > 2) {

                let me = this;

                setTimeout(function(){
                    axios.post('get/articulos/descripcion/',{
                        des : search,
                    }).then(response => {
                        me.listaCatalogo = response.data;
                    }).catch(e =>{
                        console.error(e);
                    });
                }, 1000);


            }
            // console.log(loading,'see');
        },

        guardarasignacion(){
            if (this.fecha === '') {
                toastr.warning('Seleccione una fecha !!!');
            }else if (this.cantidad === '') {
                toastr.warning('Escriba una cantidad !!!');
            }else if (this.catalogo === '') {
                toastr.warning('Seleccione un articulo !!!');
            }else if (this.empleado_entrega === '') {
                toastr.warning('Seleccione un empleado !!!');
            }else {
                this.listado_data.push(
                    {
                        id : this.catalogo.id,
                        descripcion : this.catalogo.descripcion,
                        cantidad : this.cantidad,
                        fecha : this.fecha,
                        autoriza : this.empleado_autoriza.id,
                        iniciales : this.GetIniciales(this.empleado_autoriza.name),
                        empleado : this.empleado_entrega.name,
                        empleado_id : this.empleado_entrega.id,
                        proyecto : this.proyectoId.name,
                        proyecto_id :this.proyectoId.id,
                    },
                );

                this.catalogo = '';
                this.cantidad = '';
                this.fecha = '';
                this.empleado_entrega = '';
            }





        },

        deleteu(index){
            this.listado_data.splice(index, 1);
        },

        atras(){
            this.detalle = false;
        },

        detalles(data){
            // console.log(data);
            this.detalle = true;
            this.data_detalle = data;
            axios.get('/vale/epp/seguridad/emp/detalle/' + data.empleado_id).then(response => {
                this.tableDataDetalle = response.data;
            }).catch(e => {
                console.error(e);
            });
        },

        eliminar(id){
            let me = this;
            axios.get('/vale/epp/seguridad/emp/eliminar/' + id).then(response => {
                me.detalles(me.data_detalle);
                toastr.success('Correcto');
            }).catch(e => {
                console.error(e);
            });
        },

        descargar(data){
            window.open('/vale/epp/seguridad/emp/descargar/' + data.empleado_id , '_blank');
        },

        GetIniciales(data){
            var porciones = data.split(' ');
            var iniciales = '';

            porciones.forEach((item, i) => {
                iniciales+= item.substring(0,1);
            });

            return iniciales;
        },

        Actualizar(data){
            this.modal_actualizar = 1;
            this.partida.id = data['id'];
            this.partida.articulo = {id: data['articulo_id'], descripcion : data['descripcion']};
            this.partida.cantidad = data['cantidad'];
            this.partida.fecha = data['fecha'];
            this.partida.autoriza = {id : data['autoriza_id'], name : data['autoriza']};
        },

        cerrarModalAct(){
            this.modal_actualizar = 0;
        },

        GuardarAct(){
            axios.post('guardar/act/partida/vale/epp',{
                id : this.partida.id,
                articulo_id : this.partida.articulo.id,
                cantidad : this.partida.cantidad,
                fecha : this.partida.fecha,
                autoriza_id : this.partida.autoriza.id,
            }).then(response => {
                this.detalles(this.data_detalle);
                this.cerrarModalAct();
            }).catch(e => {
                console.error(e);
            });
        },

        setId(id){
            this.tab = id;
        },

        BuscarArtEpp(){
            axios.get('buscar/historico/art/vale/epp/' + this.articulo_epp.id).then(response => {
                this.tableDataArticulos = response.data;
            }).catch(e => {
                console.error(e);
            });
        },

        ExportarArtEpp(){
            window.open('exportar/historico/art/vale/epp/' + this.articulo_epp.id, '_blank');

        }

    },
    mounted(){
        this.getData();
        this.getInicial();
        this.getProyecto();
    }
}
</script>
