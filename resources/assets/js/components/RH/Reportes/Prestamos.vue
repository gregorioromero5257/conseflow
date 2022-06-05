<template>
    <main class="main">
        <div class="container-fluid">

            <!-- Formulario de busqueda -->
            <br>
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Búsqueda prestamos
                    
                </div>
                <div class="card-body">
                    <vue-element-loading :active="isLoading"/>
                    <div class="row">
                        <div class="col-2">
                            <label class="form-control-label" for="pagado">Prestamos:</label>
                        </div>
                        <div class="col-4">
                            <select class="form-control custom-select" id="pagado"  name="pagado" v-model="pagado" v-validate="'required'" data-vv-as="Prestamo">
                                <option value="2">---TODOS---</option>
                                <option value="1">Pagados</option>
                                <option value="0">Por pagar</option>
                            </select>
                            <span class="text-danger">{{ errors.first('pagado') }}</span>
                        </div>
                        <div class="col-2">
                            <label class="form-control-label" for="departamento_id">Departamento:</label>
                        </div>
                        <div class="col-4">
                            <select class="form-control custom-select" id="departamento_id"  name="departamento_id" v-model="departamentoId" v-validate="'required'" data-vv-as="Departamento">
                                <option value="0">---TODOS---</option>
                                <option v-for="item in listaDepartamentos" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                            </select>
                            <span class="text-danger">{{ errors.first('departamento_id') }}</span>
                        </div>
                        
                    </div>
                    <br>
                    <p><b>Periodo de los prestamos</b></p>
                    <br>
                    <div class="row">
                        <div class="col-2">Fecha inicial:</div>
                        <div class="col-4">
                            <input type="date" name="fecha_inicial" id="fecha-inicial" class="form-control" v-validate="'required'" v-model="fechaInicial" data-vv-as="Fecha inicial">
                            <span class="text-danger">{{ errors.first('fecha_inicial') }}</span>
                        </div>
                        <div class="col-2">Fecha final:</div>
                        <div class="col-4">
                            <input type="date" name="fecha_final" id="fecha-final" class="form-control" v-validate="'required'" v-model="fechaFinal" data-vv-as="Fecha final">
                            <span class="text-danger">{{ errors.first('fecha_final') }}</span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-2">Fecha de pago:</div>
                        <div class="col-4">
                            <input type="date" name="fecha_pago" id="fecha-pago" class="form-control" v-validate="'required'" v-model="fechaPago" data-vv-as="Fecha pago">
                            <span class="text-danger">{{ errors.first('fecha_pago') }}</span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-2">
                            <button class="btn btn-success btn-block" @click="descargar()">Exportar</button>
                        </div>
                        <div class="col-8"></div>
                        
                        <div class="col-2">
                            <button class="btn btn-primary btn-block" @click="getPrestamos()">Buscar</button>
                        </div>
                    </div>
                    
                </div>
            </div>
            <!-- Fin Formulario de busqueda -->

            <!-- Lista de finiquitos -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Prestamos
                </div>
                <div class="card-body">

                    <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                        <template slot="id" slot-scope="props" >
                            <template v-if="props.row.cantidad_pagada == props.row.cantidad">
                                <button type="button" class="btn btn-outline-success">PAGADO</button>
                            </template>
                            <template v-else>
                                <button type="button" class="btn btn-outline-warning">POR PAGAR</button>
                            </template>
                        </template>
                    </v-client-table>

                    <div class="row">
                        <div class="col-8"></div>
                        <div class="col-4">
                            <table class="table table-sm">
                                <tr>
                                    <td><b>Cantidad pagada</b></td>
                                    <td class="text-right">{{ cantidadPagada }}</td>
                                </tr>
                                <tr>
                                    <td><b>Por pagar</b></td>
                                    <td class="text-right">{{ cantidadPorPagar }}</td>
                                </tr>
                                <tr>
                                    <td><b>Total prestamos</b></td>
                                    <td class="text-right">{{ totalPrestamos }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

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
            pagado: 2,
            departamentoId: 0,
            fechaInicial: null,
            fechaFinal: null,
            fechaPago: null,
            isLoading: false,
            totalPrestamos: 0,
            cantidadPorPagar: 0,
            cantidadPagada: 0,
            columns: [
                'nombre', 'ap_paterno', 'ap_materno', 'departamento', 'fecha', 'cantidad', 'cantidad_pagada', 'id'
                ],
            tableData: [],
            options: {
                headings: {
                    cantidad: 'Cantidad',
                    fecha: 'Fecha',
                    cantidad_pagada: 'Cantidad pagada',
                    nombre: 'Nombre',
                    ap_paterno: 'Paterno',
                    ap_materno: 'Materno',
                    departamento: 'Departamento',
                    id: 'Prestamo'
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['cantidad', 'fecha', 'cantidad_pagada', 'nombre', 'ap_paterno', 'ap_materno', 'departamento'],
                filterable: ['cantidad', 'fecha', 'cantidad_pagada', 'nombre', 'ap_paterno', 'ap_materno', 'departamento'],
                filterByColumn: true,
                texts:config.texts
            },
            listaDepartamentos: []
        }
    },
    methods: {
        getData() {
            this.isLoading = true;
            let me = this;
            axios.get('/departamento').then(response => {
                me.listaDepartamentos = response.data;
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getPrestamos() {
            this.$validator.validate().then(result => {
                if (result) {
                    this.isLoading = true;
                    let me = this;
                    axios({
                        method: 'POST',
                        url: '/prestamoreportedepto',
                        data: {
                            'fecha_inicial': this.fechaInicial,
                            'fecha_final': this.fechaFinal,
                            'departamento_id': this.departamentoId,
                            'pagado': this.pagado,
                            'fecha_pago': this.fechaPago,
                        }
                    }).then(function (response) {
                        me.isLoading = false;
                        me.tableData = response.data.prestamos;
                        me.totalPrestamos =  response.data.total_prestamos;
                        me.cantidadPagada = response.data.total_pagado;
                        me.cantidadPorPagar = me.totalPrestamos - me.cantidadPagada;
                    }).catch(function (error) {
                        console.log(error);
                    }); 
                }
            });
        },
        descargar() {
            this.$validator.validate().then(result => {
                if (result) {
                    window.open('pdfprestamosdepto/' + this.pagado + '/' + this.fechaInicial + '/' + this.fechaFinal + '/' + this.fechaPago + '/' + this.departamentoId, '_blank');
                }
            });            
        }
    },
    mounted() {
        this.getData();
    }
}
</script>

