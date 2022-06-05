<template>
    <main class="main">
        <div class="container-fluid">
            <!-- Formulario de busqueda -->
            <br>
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Resguardos de Empleados

                </div>
                <div class="card-body">
                    <vue-element-loading :active="isLoading"/>
                    <div class="row">
                        <div class="col-2">
                            <label class="form-control-label" for="proyecto_id">Proyecto:</label>
                        </div>
                        <div class="col-4">
                            <v-select id="proyectos" name="proyectos" label="nombre_corto" v-model="proyecto"
                                :options="listaProyectos">
                            </v-select>
                            <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-2">
                            <button class="btn btn-success btn-block" @click="descargar()"><i class="fa fa-download"></i> Descargar</button>
                        </div>
                        <div class="col-8"></div>

                        <div class="col-2">
                            <button class="btn btn-primary btn-block" @click="getResguardosEmpleados()">Buscar</button>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Fin Formulario de busqueda -->

            <!-- Lista -->
            <div class="card" v-show="detalle">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Empleados con Resguardos Activos
                </div>
                <div class="card-body">
                    <vue-element-loading :active="isLoading"/>
                    <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                        <template slot="id" slot-scope="props">
                          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group dropup" role="group">
                              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-grip-horizontal"></i> Acciones
                              </button>
                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <button type="button" @click="detalleresguardo(props.row)" class="dropdown-item">
                                  <i class="fas fa-eye"></i>&nbsp; Ver detalle.
                            </button>
                        </div>
                      </div>
                      </div>
                        </template>
                    </v-client-table>

                </div>
            </div>
            <!-- Fin lista -->

            <!-- Lista -->
            <div class="card" v-show="!detalle">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Resguardos Pertenecientes a: {{ empleado == null ? '': empleado.empleado + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }}
                    <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
                        <i class="fas fa-backward"></i>&nbsp;Atras
                    </button>
                </div>
                <div class="card-body">
                    <vue-element-loading :active="isLoading2"/>
                    <!--<v-client-table :columns="columnsDetalle" :data="tableDataDetalle" :options="optionsDetalle" ref="myTable">
                        <template slot="id" slot-scope="props">
                            <button type="button" @click="detalleresguardo(props.row)" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </button>
                        </template>
                    </v-client-table>-->
                    <div v-for="resguardo, key in tableDataDetalle">
                        <h4>Resguardo #{{++key}}</h4>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr class="table-active">
                                    <th style="width:30%">Articulo:</th>
                                    <th style="width:30%">Código:</th>
                                </tr>
                            </thead>
                            <tr>
                                <td>
                                    * {{ resguardo.ar_nombre }} <br>
                                    <br>
                                    <strong>Descripción:</strong><br>
                                    * {{resguardo.ar_descripcion}}<br><br>

                                    <strong>Tipo de Salida:</strong><br>
                                    * {{resguardo.ts_nombre}}<br><br>

                                    <strong>Fecha de Entrega:</strong><br>
                                    * {{resguardo.fecha_entrega}}<br><br>
                                </td>
                                <td>
                                    * {{resguardo.ar_codigo}} <br>
                                    <br>
                                    <strong>Tipo de Resguardo:</strong><br>
                                    * {{resguardo.tr_nombre}}<br><br>

                                    <strong>Cantidad:</strong><br>
                                    * {{resguardo.cantidad}}<br><br>

                                    <strong>Fecha de Devolución:</strong><br>
                                    * {{resguardo.fecha_devolucion}}<br><br>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
            <!-- Fin lista -->

        </div>
    </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
    data(){
        return {
            proyecto:{},
            empleado:null,
            detalle:true,
            isLoading: false,
            isLoading2:false,
            proyectoId: 0,
            listaProyectos: [],
            columns: ['id','empleado', 'ap_paterno', 'ap_materno', 'puesto'],
            columnsDetalle: ['id','tipo_resguardo_id', 'salida_id', 'tipo_salida_id', 'responsable_id'],
            tableData: [],
            tableDataDetalle:[],
            options: {
                headings: {
                    'id':'Acción',
                    'empleado':'Nombre',
                    'ap_paterno':'Apellido Paterno',
                    'ap_materno':'Apellido Materno',
                    'puesto':'Puesto',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['empleado', 'ap_paterno', 'ap_materno', 'puesto'],
                filterable: ['empleado', 'ap_paterno', 'ap_materno', 'puesto'],
                filterByColumn: true,
                texts:config.texts
            },
            optionsDetalle: {
                headings: {
                    'tipo_resguardo_id':'Tipo de Resguardo',
                    'salida_id':'Salida',
                    'tipo_salida_id':'Tipo de Salida',
                    'responsable_id':'Responsable',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                //sortable: ['empleado', 'ap_paterno', 'ap_materno', 'puesto'],
                //filterable: ['empleado', 'ap_paterno', 'ap_materno', 'puesto'],
                filterByColumn: true,
                texts:config.texts
            },
        }
    },
    methods: {
        getData() {
            this.isLoading = true;
            let me = this;
            axios.get('/proyecto').then(response => {
                let aux={id:0,nombre_corto:"Seleccione proyecto"};
                me.listaProyectos.push(aux);
                me.proyecto=aux;
                response.data.forEach(p=>
                {
                    me.listaProyectos.push
                    ({
                        id:p.proyecto.id,
                        nombre_corto:p.proyecto.nombre_corto,
                    });
                });
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getResguardosEmpleados() {
            this.$validator.validate().then(result => {
                if (result) {
                    this.isLoading = true;
                    let me = this;
                    axios.get('/registroresguardo/'+this.proyecto.id).then(function (response) {
                        me.isLoading = false;
                        me.tableData = response.data;

                    }).catch(function (error) {
                        console.log(error);
                        me.isLoading = false;
                    });
                }
            });
        },
        detalleresguardo(dataEmpleado = []){
            this.empleado = dataEmpleado;
            this.detalle = false;
            this.isLoading2 = true;
            console.log(dataEmpleado.idempleado);
            let me = this;
            axios.get('/registroresguardo/'+dataEmpleado.idempleado+'/edit').then(function (response) {
                me.isLoading2 = false;
                me.tableDataDetalle = response.data;
            }).catch(function (error) {
                console.log(error);
                me.isLoading2 = false;
            });
        },
        maestro(){
            this.detalle = true;
        },
        descargar() {
            window.open('datosbanempreportepdf/' + this.proyecto.id, '_blank' );
        }
    },
    mounted() {
        this.getData();
    }
}
</script>
