<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Facturas
                        <button type="button" @click="abrirModal('conductor','registrar')" class="btn btn-dark float-sm-right">
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
                                <button type="button" @click="abrirModal('conductor','actualizar',props.row)" class="dropdown-item">
                                    <i class="icon-pencil"></i>&nbsp;Actualizar Factura.
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
                            <div class="form-row">
                            <div class="form-group col-lg-6">
                              <label>Proyecto:</label>
                              <select class="form-control custom-select" id="proyecto_id" name="proyecto_id" @change="ordenProyecto"
                                      v-model="factura.proyecto_id"
                                      data-vv-as="Proyecto">
                                  <option value="0">---TODOS---</option>
                                  <option v-for="item in listaProyectos" :value="item.proyecto.id"
                                          :key="item.proyecto.id">{{ item.proyecto.nombre_corto }}
                                  </option>
                              </select>
                              <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
                          </div>
                          </div>

                            <div class="form-row">
                              <div class="form-group col-lg-6">
                                <label>Orden de compra vinculada:</label>
                                <select class="form-control custom-select" id="oc" name="oc" v-model="factura.oc"
                                        data-vv-as="Orden">
                                    <option value="0">---TODOS---</option>
                                    <option v-for="item in listaOC" :value="item.folio"
                                            :key="item.folio">{{ item.folio }}
                                    </option>
                                </select>
                                <span class="text-danger">{{ errors.first('oc') }}</span>
                            </div>
                            </div>

                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="inputCity">UUID:</label>
                                <input type="text" v-model="factura.uuid" class="form-control" id="Sistema">
                            </div>
                            </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="licencia_a"> Subir PDF:</label>
                                    <div class="col-md-6">
                                        <label v-if="tipoAccion ==1" :class="ClassL_a">
                                            <i class="fas fa-cloud-upload-alt"></i>&nbsp;{{BtnL_a2}}
                                            <input type="file" id="file_licencia_a" style="display: none;" class="form-control" v-on:change="onChangeLicencia_a">
                                        </label>

                                        <label v-for="item in listafactura" v-if="(item.id == factura.id && item.pdf == '')" :class="ClassL_a">
                                            <i class="fas fa-cloud-upload-alt"></i>&nbsp;{{BtnL_a2}}
                                            <input type="file" id="file_licencia_a" style="display: none;" class="form-control" v-on:change="onChangeLicencia_a">
                                        </label>

                                        <label v-for="item in listafactura" v-if="(item.id == factura.id && item.nombre_archivo != '')" class="btn btn-success">
                                            <i class="fas fa-file-download"></i>&nbsp;Descargar
                                            <button type="button" style="display: none;" @click="descargar(item.nombre_archivo)">
                                            </button>
                                        </label>
                                        <label v-for="item in listafactura" v-if="(item.id == factura.id && item.pdf != '')" :class="ClassL_a">
                                            <i class="fas fa-retweet"></i>&nbsp;{{BtnL_a}}
                                            <input type="file" id="file_licencia_a" style="display: none;" class="form-control" v-on:change="onChangeLicencia_a">
                                        </label>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="registrar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="registrar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
                url: '/FacturasStore',
                // PermisosCRUD:{},
                factura: {
                    id: 0,
                    pdf: '',
                    uuid: '',
                    oc: '',
                    proyecto_id: '',
                },
                Metodo: '',
                listaOC: [],
                listaProyectos: [],
                disabled: 0,
                ClassL_a: 'btn btn-info',
                BtnL_a: 'Actualizar',
                BtnL_a2: 'Subir Archivo',
                ClassL_b: 'btn btn-info',
                BtnL_b: 'Actualizar',
                BtnL_b2: 'Subir Archivo',
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                isLoading: false,
                columns: ['id', 'uuid', 'proyecto', 'ordenes_compras_id', 'nombre_archivo'],
                tableData: [],
                listafactura: [],
                listaEmpleados: [],
                options: {
                    headings: {
                        pdf: 'PDF',
                        ordenes_compras_id: 'Orden de compra',
                        uuid: 'UUID',
                        id: 'Opciones',
                        proyecto: 'Proyecto',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['uuid', 'ordenes_compras_id'],
                    filterable: ['uuid', 'ordenes_compras_id'],
                    filterByColumn: true,
                    texts:config.texts
                },
            }
        },
        filters: {
                uppercase: function (str) {
                    return str.toUpperCase() //Retornar el string con el method toUpperCase (mayúscula)
                }
            },
        computed:{
        },
        methods : {
            getData() {
                // this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
                let me=this;
                axios.get(me.url).then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });

                    axios.get('/proyecto').then(response => {
                        me.listaProyectos = response.data;
                    })
                        .catch(function (error) {
                            console.log(error);
                        });
            },

            ordenProyecto(){
                let me=this;
              axios.get('/ordenproyecto/'+this.factura.proyecto_id).then(response=>{
               me.listaOC = response.data;
               console.log(response);
              })
              .catch(function(error){
                console.log(error);
              });
            },

            getFactura() {
                let me=this;
                axios.get(me.url + '/create').then(response => {
                    me.listafactura = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

              onChangeLicencia_a(e){
                var nombreL_a = e.target.files[0].name;
                var extension =  nombreL_a.split('.').pop();
                if (extension == 'pdf' || extension == 'jpg')
                {
                    this.ClassL_a = 'btn btn-warning';
                    this.factura.pdf = e.target.files[0];
                    this.BtnL_a = 'Archivo Cargado';
                    this.BtnL_a2 = 'Archivo Cargado';
                }
                else
                {
                    this.ClassL_a = 'btn btn-danger';
                    this.BtnL_a = 'Archivo Invalido(Omitido)';
                    this.BtnL_a2 = 'Archivo Invalido(Omitido)';
                }
            },


            descargar(archivo){
                let me=this;
                axios({
                    url: me.url+ '/' + archivo,
                    method: 'GET',
                    responseType: 'blob', // importante
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
                    document.body.appendChild(link);
                    link.click();
                    //--Llama el metodo para borrar el archivo local una ves descargado--//
                    axios.get(me.url + '/' + archivo + '/edit')
                    .then(response => {
                    })
                    .catch(function (error) {
                            console.log(error);
                    });
                    //--fin del metodo borrar--//
                });
            },
            registrar(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        let formData = new FormData();

                        formData.append('metodo', this.Metodo);
                        formData.append('oc',this.factura.oc);
                        formData.append('nombre_archivo', this.factura.pdf);
                        formData.append('uuid', this.factura.uuid);
                        formData.append('id', this.factura.id);
                        formData.append('proyecto_id', this.factura.proyecto_id);
                        axios.post(me.url,formData).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {

                            if(!nuevo){
                            toastr.success(' Actualizado Correctamente');
                            me.cerrarModal();
                            me.getData();
                            }
                             else
                            {
                                toastr.success(' Registrado Correctamente');
                                me.cerrarModal();
                                me.getData();
                            }
                            }else {
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
            readFile:function(e){
                var f=e.dataTransfer.files;
            },
            cerrarModal(){
                ///*
                this.getFactura();
                this.ClassL_a = 'btn btn-info';
                this.BtnL_a = 'Actualizar';
                this.BtnL_a2 = 'Subir Archivo';
                this.ClassL_b = 'btn btn-info';
                this.BtnL_b = 'Actualizar';
                this.BtnL_b2 = 'Subir Archivo';
                this.modal=0;
                this.tituloModal='';
                document.getElementById('file_licencia_a').value='';

                Utilerias.resetObject(this.factura);
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "conductor":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.disabled=0;
                                this.listafactura = '';
                                this.Metodo = 'Nuevo';
                                this.tituloModal = 'Registrar Nueva Factura';
                                Utilerias.resetObject(this.factura);
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Datos Factura';
                                this.tipoAccion=2;
                                this.disabled=1;
                                this.factura.id= data['id'];
                                this.factura.uuid = data['uuid'];
                                this.factura.pdf = data['nombre_archivo'];
                                this.factura.oc = data['ordenes_compras_id'];
                                this.factura.proyecto_id = data['proyecto_id'];
                                this.ordenProyecto();
                                this.Metodo = 'Actualizar';
                                break;
                            }
                        }
                    }
                }
            }
      },
        mounted() {
            this.getData();
            this.getFactura();
        }
    }
</script>
