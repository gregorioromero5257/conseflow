<template>
    <main class="main">
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <br>
            <div class="card">
                <vue-element-loading :active="isLoading"/>
                <div class="card-header">
                    <i class="fa fa-align-center"></i> Control de documentos por Entrada de Artículos.
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label" for="proyecto_id">Seleccionar:</label>
                            <!--<select class="form-control" id="proyecto_id" name="proyecto_id" v-model="proyecto_id"
                                    v-validate="'required'" data-vv-as="Proyecto">
                                <option value="0">---Proyectos---</option>
                                <option v-for="item in listaProyectos" :value="item.id" :key="item.id">{{ item.nombre_corto
                                    }}
                                </option>
                            </select>-->
                            <v-select id="proyectos" name="proyectos" label="nombre_corto" v-model="proyecto"
                                      :options="listaProyectos">
                            </v-select>
                            <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
                            &nbsp;
                            <div>
                                <button type="button" class="btn btn-primary btn-block" @click="buscarExistencias()">
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" v-show="!detalle">
                <div class="card-header">
                    <i class="fa fa-align-center"></i> Articulos con entradas:
                </div>

                <div class="card-body">
                    <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                        <template slot="id" slot-scope="props">

                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group dropup" role="group">
                                    <button id="btnGroupDrop1" type="button"
                                            class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-grip-horizontal"></i> Acciones
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <template>
                                            <a v-show="PermisosCrud.Upload" class="dropdown-item" @click="subirDocumento(props.row,2)" href="#">
                                            <i class="fas fa-file-upload"></i>&nbsp;Actulizar Documento
                                            </a>
                                        </template>
                                        <button v-show="PermisosCrud.Download" type="button" @click="descargar(props.row.adjunto)" class="dropdown-item">
                                            <i class="fas fa-download"></i>&nbsp;Descargar Documento
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template slot="estado_documento" slot-scope="props">
                            <template v-if="props.row.estado_documento">
                                <button type="button" class="btn btn-success">Confirmado</button>
                            </template>
                            <template v-else>
                                <button type="button" class="btn btn-danger">Pendiente</button>
                            </template>
                        </template>

                    </v-client-table>


                </div>
            </div>
       </div>
    </main>
</template>

<script>
    import Utilerias from '../../Herramientas/utilerias.js';

    var config = require('../../Herramientas/config-vuetables-client').call(this);

    export default {
        data() {
            return {
                proyecto:{},
                PermisosCrud : {},
                url: '/proyectos',
                listaProyectos: [],
                proyecto_id: 0,
                isLoading: false,
                detalle: false,
                nombredoc: '',
                estado_documento: 0,
                columns: ['id', 'codigo', 'descripcion', 'nombre', 'marca', 'unidad', 'estado_documento','adjunto','nombreEmp'],
                tableData: [],
                tableDatauno: [],
                options: {
                    headings: {
                        id: 'Acción',
                        tsn: 'Tipo de salida',
                        estado_documento: 'Estado del documento',
                        nombre: 'Lote',
                        adjunto: 'Documento',
                        nombreEmp: 'Subido por'
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['codigo', 'descripcion', 'nombre', 'marca', 'unidad'],
                    filterable: ['codigo', 'descripcion', 'nombre', 'marca', 'unidad'],
                    filterByColumn: true,
                    texts: config.texts
                },

            }
        },
        computed: {},
        methods: {

            getData() {
                let me = this;
                this.PermisosCrud = Utilerias.getCRUD(this.$route.path)
                axios.get('/proyectos').then(response => {
                    me.listaProyectos=[];
                    // me.listaProyectos = response.data;
                    response.data.forEach(p=>
                    {
                        me.listaProyectos.push
                        ({
                            id: p.id,
                            nombre_corto: p.nombre_corto,
                        });
                    });
                    me.isLoading = false;
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            buscarExistencias() {

                if (this.proyecto.id == 0) {
                    toastr.warning('Atención', 'Seleccione un proyecto');
                } else {
                    this.isLoading = true;
                    let me = this;
                    axios({
                        method: 'GET',
                        url: '/documentosEntradas/' + this.proyecto.id,
                        data: {
                            'proyecto_id': this.proyecto.id,
                        }
                    }).then(function (response) {
                        me.isLoading = false;
                        me.tableData = response.data;
                        console.log(response.data);
                    }).catch(function (error) {
                        console.log(error);
                        me.isLoading = false;
                    });
                }
            },


            maestro() {
                this.detalle = false;
            },

            subirDocumento(data,metodo){
                swal({
                    title: 'Documento',
                    input: 'file',
                    inputAttributes: {
                        'accept': 'application/pdf',
                        'aria-label': 'Upload your profile picture'
                    },
                    confirmButtonText: 'Subir Archivo',
                    showCancelButton: true,
                    inputValidator: (file) => {
                        return !file && 'Este Campo no puede estar Vacío'
                    }
                }).then((file) => {
                    let me = this;
                    if(file.value) {
                        let formData = new FormData();

                        formData.append('metodo',metodo);
                        formData.append('adjunto', file.value);
                        formData.append('id',data.id);

                        axios.post(me.url,formData
                        ).then(function (response) {
                        if (response.data.status) {
                            if (metodo == 1) {
                                toastr.success('Archivo Subido Correctamente');
                            }
                            if (metodo == 2) {
                                toastr.success('Archivo Actualizado Correctamente');
                            }
                            me.buscarExistencias();
                        }
                        else {
                            swal({
                                type: 'error',
                                html: response.data.errors.join('<br>'),
                            });
                        }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                    else if (file.dismiss === swal.DismissReason.cancel) {
                    }
                })
            },

            descargar(archivo) {
                console.log(archivo);
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
            }

        },
        mounted() {
            this.getData();
        }
    }
</script>
