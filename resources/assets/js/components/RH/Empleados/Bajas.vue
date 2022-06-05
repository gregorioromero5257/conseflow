<template>
            <main class="main">
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <br>
                <div class="card" v-show="!detalle">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Contratos Finalizados
                    </div>
                    <div class="card-body">
                        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                        <template slot="empleado.id" slot-scope="props">
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group dropup" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <button type="button" @click="cargarTipoContrato(props.row.empleado, props.row.puesto)" class="dropdown-item">
                                    <i class="fas fa-eye"></i>&nbsp; Ver Contratos Finalizados del Empleado
                                </button>
                            </div>
                            </div>
                            </div>
                            </template>
                        </v-client-table>
                  </div>
                </div>
        </div>
                <!-- Fin ejemplo de tabla Listado -->
                <!-- Listado -->
                <br>
                <div class="card" v-show="detalle">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Contratos Finalizados de: {{ empleado == null ? '': empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }}
                      
                        <button type="button" @click="maestro()" class="btn btn-dark float-sm-right">
                            <i class="fas fa-backward"></i>&nbsp;Atras
                        </button>
                    </div>
                    <div class="card-body">
                      <vue-element-loading :active="isLoadingDetalle"/>
                      
                        <div v-for="contrato, key in tableDataTipoContrato">
                            <h4>Contrato #{{++key}}</h4>
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="table-active">
                                        <th style="width:30%">Puesto:</th>
                                        <th style="width:30%">Proyecto:</th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td>
                                        * {{ puestos != null ? puestos.nombre : ''}} <br>
                                        <br>
                                        <strong>Inicio de Contrato:</strong><br>
                                        * {{contrato.fecha_ingreso}}<br><br>

                                        <strong>Fecha de Baja:</strong><br>
                                        * {{contrato.fecha_baja}}<br><br>

                                        <strong>Motivo de Baja:</strong><br>
                                        * {{contrato.motivo}}<br><br>

                                        <strong>Observaciones:</strong><br>
                                        * {{contrato.observaciones}}<br><br>
                                    </td>
                                    <td>
                                        * {{contrato.p_nombre}} <br>
                                        <br>
                                        <strong>Nómina:</strong><br>
                                        * {{contrato.tn_nombre}}<br><br>

                                        <strong>Horario:</strong><br>
                                        * De: {{contrato.h_he}} a: {{contrato.h_hs}}<br><br>

                                        <strong>Sueldo Diario Neto:</strong><br>
                                        * {{contrato.SDN}}<br><br>

                                        <strong v-if="contrato.formato_renuncia != null">Formato de Renuncia:</strong><br><br>
                                        <label :class="ClassFR" v-if="contrato.formato_renuncia != null">
                                            <i class="fas fa-file-download"></i>&nbsp;{{BtnFR}} Renuncia.
                                            <button type="button" style="display: none;" @click="descargarFormato(contrato.formato_renuncia)">
                                            </button>
                                        </label><br><br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Fin Listado -->
            </div>
        </main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

    export default {
        data (){
            return {
                url: '/bajas',
                empleado: null,
                puestos: null,
                detalle: false,
                BtnFR: 'Descargar Formato de ',
                ClassFR: 'btn btn-success btn-sm',
                isLoadingDetalle: false,
                columns: [
                    'empleado.id',
                    'empleado.nombre',
                    'empleado.ap_paterno',
                    'empleado.ap_materno',
                    'puesto.nombre',
                    'departamento.nombre',
                    ],
                tableData: [],
                tableDataTipoContrato: [],
                options: {
                    headings: {
                        'empleado.id': 'Acción',
                        'empleado.nombre': 'Nombre',
                        'empleado.ap_paterno': 'Apellido Paterno',
                        'empleado.ap_materno': 'Apellido Materno',
                        'puesto.nombre': 'Puesto',
                        'departamento.nombre': 'Departamento',
                    },
                    perPage: 15,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
                    filterable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
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
                axios.get(me.url+'/create').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
           descargarFormato(archivo){
                let me=this;
                this.BtnFR = 'Descargando Archivo de ';
                this.ClassFR = 'btn btn-warning btn-sm'
                axios({
                    url: me.url+ '/' + archivo + '/edit',
                    method: 'GET',
                    responseType: 'blob', // importante
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
                    document.body.appendChild(link);
                    link.click();
                    this.BtnFR = 'Descargar Formato de ';
                    this.ClassFR = 'btn btn-success btn-sm';
                    //--Llama el metodo para borrar el archivo local una ves descargado--//
                    axios.delete(me.url + '/' + archivo)
                    .then(response => {
                    })
                    .catch(function (error) {
                            console.log(error);
                    });
                    //--fin del metodo borrar--//
                });
            },
            
            cargarTipoContrato(dataEmpleado = [], dataPuesto = []) {
                this.detalle = true;
                this.isLoadingDetalle = true;
                let me=this;
                this.empleado = dataEmpleado;
                this.puestos = dataPuesto;
                axios.get(me.url + '/' + dataEmpleado.id + '/' +'baja').then(response => {
                    me.tableDataTipoContrato = response.data;
                    me.isLoadingDetalle = false;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            maestro(){
                this.detalle = false;
            },
        },
        mounted() {
            this.getData();
            this.$root.limpiarDashboard();
        }
    }
</script>