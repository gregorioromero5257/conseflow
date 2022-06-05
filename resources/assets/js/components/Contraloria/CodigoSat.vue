<template>
<main class="main">

    <!--main>-->
    <div class="container-fluid">
        <br>
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Articulos
            </div>
            <div class="card-body">
                <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">

                    <template slot="codigo_sat" slot-scope="props">
                        <template v-if="props.row.codigo_sat ==null || props.row.codigo_sat==0 ">
                            <p class="text-center">-</p>
                        </template>
                        <template v-else>
                            <p class="text-center">{{props.row.codigo_sat}}</p>
                        </template>
                    </template>

                    <template slot="id" slot-scope="props">
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group dropup" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp;Acciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <button type="button" class="dropdown-item" @click="AbrirModalCostos(props.row.id)">
                                        <i class="icon-pencil"></i>&nbsp;Ingresar centro de costo
                                    </button>

                                    <button type="button" class="dropdown-item" @click="AbrirModalSat(props.row.id)">
                                        <i class="icon-pencil"></i>&nbsp;Ingresar código del SAT
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                </v-client-table>
                <vue-element-loading :active="isLoading" />
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>

    <!-- inicio Modal sat -->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalSat}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <div class="modal-header">
                        <h4 class="modal-title">Código de producto</h4>
                        <button type="button" class="close" @click="cerrarModalSat()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- formulario-->

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="sat_tipo_id">Tipo</label>
                            <div class="col-md-6">
                                <select class="form-control" id="sat_tipo_id" name="sat_tipo_id" v-model="tiposat_id" v-on:change="onChangeTipoSat">
                                    <option v-for="item in listaTiposSsat" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="sat_division_id">División</label>
                            <div class="col-md-6">
                                <select class="form-control" id="sat_division_id" name="sat_division_id" v-model="divisionsat_id" v-on:change="onChangeDivisionSat">
                                    <option v-for="item in listaDivisionSat" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="sat_grupo_id">Grupo</label>
                            <div class="col-md-6">
                                <select class="form-control" id="sat_grupo_id" name="sat_grupo_id" v-model="gruposat_id" v-on:change="onChangeGrupoSat">
                                    <option v-for="item in listaGrupos" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="sat_clase_id">Clase</label>
                            <div class="col-md-6">
                                <select class="form-control" id="sat_clase_id" name="sat_clase_id" v-model="clasesat_id" v-on:change="onChangeClaseSat">
                                    <option v-for="item in listaClaseSat" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="sat_descripcion">Producto</label>
                            <div class="col-md-6">
                                <select class="form-control" id="sat_descripcion" name="sat_descripcion" v-model="codigosSat_id" v-on:change="onChangeCodigoSat">
                                    <option v-for="codigo in listaCodigosSat" :value="codigo.id" :key="codigo.id">{{codigo.descripcion}}</option>
                                </select>
                            </div>
                        </div>

                    </div> <!-- Modal body -->
                    <div class="modal-footer">
                        <button type="button" v-if="productoSeleccionado==1" class="btn btn-secondary" @click="guardarProducto()">
                            <i class="fas fa-save"></i>&nbsp;Guardar
                        </button>
                        <button type="button" class="btn btn-secondary" @click="cerrarModalSat()">
                            <i class="fas fa-window-close"></i>&nbsp;Cerrar
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin Modal sat -->

    <!-- inicio Modal centro costos -->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalCostos}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <div class="modal-header">
                        <h4 class="modal-title">Centro de costos</h4>
                        <button type="button" class="close" @click="cerrarModalCostos()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- formulario-->
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="trid">Centro de costo</label>
                            <div class="col-md-9">
                                <select class="form-control" data-vv-name="Centro de costos" v-model="centro_costo_id">
                                    <option v-for="t in list_centro_costo" :value="t.id">{{t.nombre_sub}}&nbsp;{{t.nombre}}</option>
                                </select>
                            </div>
                            <span class="text-danger">{{ errors.first('Centro de costos') }}</span>
                        </div>

                    </div> <!-- Modal body -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="guardarCentroCosto()">
                            <i class="fas fa-save"></i>&nbsp;Guardar
                        </button>
                        <button type="button" class="btn btn-outline-dark" @click="cerrarModalCostos()">
                            <i class="fas fa-window-close"></i>&nbsp;Cerrar
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin Modal costos -->

</main>
</template>

<script>
var config = require('../Herramientas/config-vuetables-client').call(this);
export default {
    data() {
        return {
            // Tabla
            columns: ['id', 'nombre', 'codigo_sat', 'costo', 'categoria'],
            tableData: [],
            options: {
                headings: {
                    nombre: 'Nombre',
                    codigo_sat: 'Código SAT',
                    costo: 'Centro de Costo',
                    categoria: 'Categoría',
                    id: 'Acciones',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['nombre'],
                filterable: ['nombre'],
                filterByColumn: true,
                listColumns: {

                },
            },
            isLoading: false,

            // Moda sat
            modalSat: 0,
            modalSat: 0,
            listaGrupos: [],
            listaTiposSsat: [],
            listaDivisionSat: [],
            listaClaseSat: [],
            listaCodigosSat: [],
            tiposat_id: 0,
            gruposat_id: 0,
            divisionsat_id: 0,
            clasesat_id: 0,
            codigosSat_id: 0,
            productoSeleccionado: 0,

            // modal costos
            id_articulo: 0,
            modalCostos: 0,
            centro_costo_id: 0,
            list_centro_costo: [],
        }
    },
    components: {},
    methods: {
        getData() {
            let me = this;
            axios.get('/articulos_sat_contraloria').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        AbrirModalSat(id) {
            this.id_articulo = id;
            this.modalSat = 1;
            axios.get('/codigosat/gettipos').then(res => {
                    this.listaTiposSsat = res.data.tipos_sat;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        onChangeTipoSat() {
            let me = this;
            me.listaGrupos = [];
            me.listaClaseSat = [];
            me.listaCodigosSat = [];
            me.productoSeleccionado = 0;
            axios.get('codigosat/getdivisiones/' + me.tiposat_id).then(res => {
                me.listaDivisionSat = res.data.divisiones;
            }).catch(error => {
                console.error(error);
            })
        },
        onChangeDivisionSat() {
            let me = this;
            me.listaGrupos = [];
            me.listaClaseSat = [];
            me.listaCodigosSat = [];
            me.productoSeleccionado = 0;
            axios.get('codigosat/getgrupos/' + me.divisionsat_id).then(res => {
                me.listaGrupos = res.data.grupos;
            }).catch(error => {
                console.error(error);
            })
        },
        onChangeGrupoSat() {
            let me = this;
            me.listaClaseSat = [];
            me.listaCodigosSat = [];
            me.productoSeleccionado = 0;
            axios.get('codigosat/getclases/' + me.gruposat_id).then(res => {
                me.listaClaseSat = res.data.clases;
            }).catch(error => {
                console.error(error);
            })
        },
        onChangeClaseSat() {
            let me = this;
            me.listaCodigosSat = [];
            me.productoSeleccionado = 0;
            axios.get('codigosat/getcodigos/' + me.clasesat_id).then(res => {
                me.listaCodigosSat = res.data.productos;
            }).catch(error => {
                console.error(error);
            })
        },
        onChangeCodigoSat() {
            let me = this;
            me.productoSeleccionado = 1;

        },
        cerrarModalSat() {
            this.modalSat = 0;

            this.divisionsat_id = 0;
            this.listaGrupos = [];
            this.listaClaseSat = [];
            this.listaCodigosSat = [];
            this.productoSeleccionado = 0;
            this.tiposat_id = 0;
            this.id_articulo = 0;
        },
        guardarProducto() {
            // alert("asd");
            // return;
            if (this.productoSeleccionado == 1 && this.id_articulo > 0) {
                axios.put("//codigosat/codigo/" + this.id_articulo + "&" + this.codigosSat_id).then(response => {
                    if (response.data.status) {
                        toastr.success("Código actualizado correctamente");
                        this.cerrarModalSat();
                        this.getData();
                    } else {
                        toastr.error(response.data.mensaje, "Error 500");
                    }
                }).catch(error => {
                    console.log(error)
                });
            }
        },
        AbrirModalCostos(id) {
            this.id_articulo = id;
            this.modalCostos = 1;
            axios.get('/codigosat_centrocostos').then(response => {
                this.list_centro_costo = response.data;
            }).catch(error => {
                console.log(error)
            });
        },
        cerrarModalCostos() {

            this.list_centro_costo = [];
            this.modalCostos = 0;
        },
        guardarCentroCosto() {
            axios.put('/codigosat/centrocostos/' + this.id_articulo + "&" + this.centro_costo_id).then(response => {
                if (response.data.status) {
                    toastr.success("Centro de costos actualizado correctamente");
                    this.cerrarModalCostos();
                    this.getData();
                } else {
                    toastr.error(response.data.mensaje, "Error 500");
                }
            }).catch(error => {
                console.log(error)
            });
        },
    },
    mounted() {
        this.isLoading = true;
        this.getData();
        this.isLoading = false;
    }
}
</script>
