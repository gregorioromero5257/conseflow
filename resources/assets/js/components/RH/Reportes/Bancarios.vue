<template>
    <main class="main">
        <div class="container-fluid">
            <!-- Formulario de busqueda -->
            <br>
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Búsqueda Datos bancarios de los empleados
                    
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
                            <button class="btn btn-primary btn-block" @click="getDatosBancarios()">Buscar</button>
                        </div>
                    </div>
                    
                </div>
            </div>
            <!-- Fin Formulario de busqueda -->

            <!-- Lista de finiquitos -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Datos bancarios
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
            proyecto:{},
            isLoading: false,
            proyectoId: 0,
            listaProyectos: [],
            columns: ['empleado', 'proyecto', 'numero_tarjeta', 'numero_cuenta', 'clabe', 'banco', 'empresa'],
            tableData: [],
            options: {
                headings: {
                    empresa: 'Empresa',
                    empleado: 'Empleado',
                    numero_tarjeta: 'No. de tarjeta',
                    numero_cuenta: 'No. de cuenta',
                    clabe: 'CLABE',
                    banco: 'Banco',
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
                })
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getDatosBancarios() {
            this.$validator.validate().then(result => {
                if (result) {
                    this.isLoading = true;
                    let me = this;
                    axios({
                        method: 'POST',
                        url: '/datosbanempreporte',
                        data: {
                            'proyecto_id': this.proyecto.id
                        }
                    }).then(function (response) {
                        me.isLoading = false;
                        me.tableData = response.data;
                        
                    }).catch(function (error) {
                        console.log(error);
                        me.isLoading = false;
                    }); 
                }
            });
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