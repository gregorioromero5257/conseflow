<template>
    <main class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" >
                        <vue-element-loading :active="isLoading"/>
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i>Descargar Archivos en PDF
                        </div>
                        <div class="card-body">
                            <a href="#" class="dropdown-item" 
                            v-for="list in listaArchivos.pdf" 
                            :value="list.id" :key="list.id"
                            @click="descargarArchivo(list.filename, list.extension)">
                                <i :class="list.clase"></i>{{ list.filename }}
                            </a>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" >
                        <vue-element-loading :active="isLoading"/>
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i>Descargar Archivos de EXCEL
                        </div>
                        <div class="card-body">
                            <a href="#" class="dropdown-item" 
                            v-for="list in listaArchivos.excel" 
                            :value="list.id" :key="list.id"
                            @click="descargarArchivo(list.filename, list.extension)">
                                <i :class="list.clase"></i>{{ list.filename }}
                            </a>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" >
                        <vue-element-loading :active="isLoading"/>
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i>Descargar Archivos de Word
                        </div>
                        <div class="card-body">
                            <a href="#" class="dropdown-item" 
                            v-for="list in listaArchivos.word" 
                            :value="list.id" :key="list.id"
                            @click="descargarArchivo(list.filename, list.extension)">
                                <i :class="list.clase"></i>{{ list.filename }}
                            </a>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
    data (){
        return {
            listaArchivos:[],
            isLoading: false,
        }
    },
    methods : {
        traerArchivos(){
            axios.get('traerarchivos/sgi/psc-01/')
            .then(response => { 
                this.listaArchivos = response.data;

             })
            .catch(function (error) {console.log(error);});
        },
        descargarArchivo(nombre, tipo){
            window.open('descargararchivos/sgi/psc-01/'+nombre+'/'+tipo, '_blank');
        }
    },
    mounted() {
        this.traerArchivos();
    }
}
</script>