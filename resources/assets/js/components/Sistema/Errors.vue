<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <!-- <br> -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Errores
                    </div>
                    <div class="card-body">
                        <vue-element-loading :active="isLoading"/>
                        <div class="form-group row">
                            <div class="col-md-3 col-sm-3">
                                <div class="input-group">
                                    <input class="form-control" v-model="fecha" id="fecha" v-validate="'required'" name="fecha" placeholder="Fecha" type="date">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span class="input-group-append">
                                    <button class="btn btn-primary" type="button" @click="buscar()"> Buscar</button>
                                    </span>
                                </div>
                                <span class="text-danger">{{ errors.first('fecha') }}</span>
                            </div>
                        </div>

                        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                        </v-client-table>

                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>

        </main>
</template>

<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

    export default {
        data (){
            return {
                fecha: '',
                isLoading: false,
                columns: ['f', 'o.user', 'o.mensaje', 'o.file', 'o.line'],
                tableData: [],
                options: {
                    headings: {
                        'f': 'Fecha',
                        'o.user': 'Usuario',
                        'o.mensaje': 'Mensaje',
                        'o.file': 'Archivo',
                        'o.line': 'Linea',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['f', 'o.user', 'o.mensaje', 'o.file', 'o.line'],
                    filterable: ['f', 'o.user', 'o.mensaje', 'o.file', 'o.line'],
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
                axios.get('/errors').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            buscar() {
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios.post('/errors', {
                                'fecha': me.fecha
                            }).then(response => {
                            me.tableData = response.data;
                            me.isLoading = false;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                    }
                })
            }
        },
        mounted() {
            this.$root.limpiarDashboard();
        }
    }
</script>
