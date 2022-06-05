<template>
    <main class="main">
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <br>
            <div class="card">
                <vue-element-loading :active="isLoading"/>
                <div class="card-header">
                    <i class="fa fa-align-center"></i> Control de documentos por salida.
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-control-label" for="proyecto_id">Seleccionar:</label>
                            <!--<select class="form-control" id="proyecto_id" name="proyecto_id" v-model="proyecto_id"
                                    v-validate="'required'" data-vv-as="Proyecto">
                                <option value="0">---Proyectos---</option>
                                <option v-for="item in listaProyectos" :value="item.id" :key="item.id">{{ item.nombre }}
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
                    <i class="fa fa-align-center"></i> Artículos con salidas:
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
                                        <button type="button" @click="descargar(props.row)" class="dropdown-item">
                                            <i class="fas fa-arrow-down"></i>&nbsp;Descargar formato
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template slot="condicion" slot-scope="props">
                            <template v-if="props.row.condicion">
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
                listaProyectos: [],
                proyecto_id: 0,
                isLoading: false,
                detalle: false,
                nombredoc: '',
                condicion: 0,
                columns: ['id','descripcion','codigo','marca','unidad','lote','adjunto','condicion','nombreEmp'],
                tableData: [],
                options: {
                    headings: {
                        descripcion : 'Artículo',
                        codigo : 'Código',
                        nombreEmp : 'Responsable'
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['codigo', 'descripcion', 'nombre', 'marca', 'unidad','nombreEmp'],
                    filterable: ['codigo', 'descripcion', 'nombre', 'marca', 'unidad','nombreEmp'],
                    filterByColumn: true,
                    texts: config.texts
                },

            }
        },
        computed: {},
        methods: {

            getData() {
                let me = this;
                axios.get('/proyectos').then(response => {
                    me.listaProyectos=[];
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
                        url: 'busquedaarticulocontrolB/' + this.proyecto.id,
                        data: {
                            'proyecto_id': this.proyecto.id,
                        }
                    }).then(function (response) {
                        me.isLoading = false;
                        me.tableData = response.data;

                    }).catch(function (error) {
                        console.log(error);
                        me.isLoading = false;
                    });
                }
            },


            maestro() {
                this.detalle = false;
            },

            descargar() {
                alert(this.nombredoc);
                // axios.post('/descargardocumentos',{
                //   'documento' : this.nombredoc,
                // }).then(response => {
                //   console.log(response);
                // }).catch(error => {
                //   console.error(error);
                // });

                let me = this;
                axios({
                    url: '/descargardocumentos/' + this.nombredoc,
                    method: 'GET',
                    responseType: 'blob', // importante
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', this.nombredoc); //archivo = nombre del archivo alojado en el ftp
                    document.body.appendChild(link);
                    link.click();
                    //--Llama el metodo para borrar el archivo local una ves descargado--//
                    // axios.get('/ConductoresStore/' + this.nombredoc + '/edit')
                    //     .then(response => {
                    //     })
                    //     .catch(function (error) {
                    //         console.log(error);
                    //     });
                    //--fin del metodo borrar--//
                }).catch(error => {
                    console.error(error);
                });
            }

        },
        mounted() {
            this.getData();
        }
    }
</script>
