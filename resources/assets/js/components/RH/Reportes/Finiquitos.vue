<template>
    <main class="main">
        <div class="container-fluid">
            <!-- Formulario de busqueda -->
            <br>
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Búsqueda finiquitos

                </div>
                <div class="card-body">
                    <vue-element-loading :active="isLoading"/>
                    <div class="row">
                        <div class="col-1">
                            <label class="form-control-label" for="proyecto_id">Proyecto:</label>
                        </div>
                        <div class="col-3">
                            <v-select id="proyectos" name="proyectos" label="nombre_corto" v-model="proyecto"
                                :options="listaProyectos">
                            </v-select>
                            <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
                        </div>
                        <div class="col-1">Fecha inicial:</div>
                        <div class="col-3">
                            <input type="date" name="fecha_inicial" id="fecha-inicial" class="form-control" v-validate="'required'" v-model="fechaInicial" data-vv-as="Fecha inicial">
                            <span class="text-danger">{{ errors.first('fecha_inicial') }}</span>
                        </div>
                        <div class="col-1">Fecha final:</div>
                        <div class="col-3">
                            <input type="date" name="fecha_final" id="fecha-final" class="form-control" v-validate="'required'" v-model="fechaFinal" data-vv-as="Fecha final">
                            <span class="text-danger">{{ errors.first('fecha_final') }}</span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-2">
                            <button class="btn btn-success btn-block" @click="descargar()">Exportar</button>
                        </div>
                        <div class="col-8"></div>

                        <div class="col-2">
                            <button class="btn btn-primary btn-block" @click="getFiniquitos()">Buscar</button>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Fin Formulario de busqueda -->
            <!-- Lista de finiquitos -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Finiquitos
                </div>
                <div class="card-body">

                    <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                    </v-client-table>

                </div>
            </div>
            <!-- Fin lista de finiquitos -->

        </div>
    </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
    data(){
        return {
            isLoading: false,
            proyectoId: 0,
            fechaInicial: null,
            fechaFinal: null,
            isLoading: false,
            columns: ['empleado', 'fecha_inicial', 'fecha_final', 'empresa', 'proyecto', 'total'],
            tableData: [],
            options: {
                headings: {
                    empresa: 'Empresa',
                    empleado: 'Empleado',
                    fecha_final: 'Fecha final',
                    fecha_inicial: 'Fecha inicial',
                    total: 'Total',
                    proyecto: 'Proyecto'
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                // sortable: [],
                // filterable: [],
                filterByColumn: true,
                texts:config.texts
            },
            listaProyectos: [],
            proyecto:{},
        }
    },
    methods: {
        getData() {
            this.isLoading = true;
            let me = this;
            axios.get('/proyecto').then(response => {
                // me.listaProyectos = response.data;
                response.data.forEach(p=>
                {
                    me.listaProyectos.push
                    ({
                        id:p.proyecto.id,
                        nombre_corto:p.proyecto.nombre_corto,
                    });
                })
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getFiniquitos() {
            this.$validator.validate().then(result => {
                if (result) {
                    this.isLoading = true;
                    let me = this;
                    axios({
                        method: 'POST',
                        url: '/finiquitosproyecto',
                        data: {
                            'fecha_inicial': this.fechaInicial,
                            'fecha_final': this.fechaFinal,
                            'proyecto_id': this.proyecto.id
                        }
                    }).then(function (response) {
                        me.isLoading = false;
                        me.tableData = response.data;

                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            });
        },
        descargar() {
            this.$validator.validate().then(result => {
                if (result) {
                    window.open('finiquitosexcel/' + this.fechaInicial + '/' + this.fechaFinal + '/' + this.proyectoId, '_blank' );
                }
            });
        }
    },
    mounted() {
        this.getData();
    }
}
</script>
