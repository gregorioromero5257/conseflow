<template>
    <main class="main">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Reporte articulos pendientes.
                </div>


                <div class="card-body">
                    <vue-element-loading :active="isLoading"/>
                    <div class="row">
                        <div class="col-2">
                            <label class="form-control-label" for="proyecto_id">Proyecto:</label>
                        </div>
                        <div class="col-4">
                            <select class="form-control custom-select" id="proyecto_id" name="proyecto_id"
                                    v-model="proyectoId" v-validate="'required'" data-vv-as="Proyecto">
                                <option v-for="item in listaProyectos" :value="item.proyecto.id"
                                        :key="item.proyecto.id">{{ item.proyecto.nombre_corto }}
                                </option>
                            </select>
                            <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
                        </div>

                        <div class="col-4">
                            <button class="btn btn-primary btn-block" @click="busquedaProy()">Buscar</button>
                        </div>
                    </div>
                    <br>

                </div>

                <div class="card-body">

                    <v-client-table :columns="columns" :data="tableData" :options="options"
                                    ref="myTable"></v-client-table>
                    <h6><b>Total del proyecto : {{total}}</b></h6>
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

                proyectoId: 0,
                listaProyectos: [],
                columns: ['articulo', 'cantidad', 'precio'],
                isLoading: false,
                tableData: [],
                options: {
                    headings: {
                        'precio' : 'Precio por articulo'
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['articulo', 'cantidad', 'precio'],
                    filterable: ['articulo', 'cantidad', 'precio'],
                    filterByColumn: true,
                    texts: config.texts
                },

            }
        },

        computed: {
            total() {
                return this.tableData.reduce((sum, item) => {
                    return (sum + (item.cantidad * item.precio))
                }, 0)
            }
        },
        methods: {

            getData() {
                this.isLoading = true;
                let me = this;
                axios.get('/proyecto').then(response => {
                    me.listaProyectos = response.data;
                    me.isLoading = false;
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            busquedaProy() {

                let me = this;
                this.isLoading = true;
                axios.get('informe/' + this.proyectoId).then(response => {
                    me.tableData = response.data;
                    me.isLoading = false;
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

        },

        mounted() {
            this.getData();
        }

    }
</script>
