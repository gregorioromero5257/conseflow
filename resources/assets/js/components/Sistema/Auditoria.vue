<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <!-- <br> -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Auditoria
                    </div>
                    <div class="card-body">
                        <vue-element-loading :active="isLoading"/>
                        <div class="form-group row">
                            <div class="col-md-6 col-sm-12">
                                <div class="input-group">
                                    <input class="form-control" v-model="fecha" id="fecha" v-validate="'required'" name="fecha" placeholder="Fecha" type="date">
                                    <span class="input-group-append">
                                    <button class="btn btn-primary" type="button" @click="buscar()">Buscar</button>
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
                columns: ['f', 'o.u', 'o.m', 'o.id', 'o.c', 'o.o', 'o.n'],
                tableData: [],
                options: {
                    headings: {
                        'f': 'Fecha',
                        'o.u': 'Usuario',
                        'o.m': 'Modelo',
                        'o.id': 'PK',
                        'o.c': 'Campo',
                        'o.o': 'Original',
                        'o.n': 'Nuevo'
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['o.u', 'o.u', 'o.c', 'o.m'],
                    filterable: ['o.u', 'o.u', 'o.c', 'o.m', 'o.id'],
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
                axios.get('/auditoria').then(response => {
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
                        axios.post('/auditoria', {
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