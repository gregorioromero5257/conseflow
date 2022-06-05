<template>
  <div>

    <div class="card">

      <div class="card-body">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            <template v-if="dataD.length != 0">
              <div class="carousel-item active">
                <img class="d-block w-100" :src="'temp/' + dataD[0].image" alt="First slide" @click.left="opciones()">
              </div>
            </template>
            <template v-if="dataF.length != 0">
              <div class="carousel-item" v-for="t in dataF"  >
                <img class="d-block w-100" :src="'temp/' + t.image" alt="Second slide" @click.left="opciones()" >
              </div>
            </template>




          </div>
          <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>

  </div>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return {
      dataF : [],
      id : 0,
      isLoading : false,
      datatenencia : [],
      ts : [0,1,2,3,4,5,6],
      contador : 0,
      dataD : [],
    }
  },
  methods : {
    getId(id){
      this.dataF = [];
      this.dataD = [];
      this.id = id;
      this.getData();
      this.getFotos();
    },

    getData(){
      this.isLoading = true;
      axios.get('tenenciaunidades/'+this.id).then(response => {
        this.datatenencia = response.data;
        this.isLoading = false;
      }).catch(err => {
        console.error(err);
      });
    },

    opciones(){
      if (this.contador < 1) {
        this.contador = this.contador + 1;
      }else if(this.contador == 1){
        alert('Evento');
        this.contador = 0;
      }

    },

    getFotos(){
      axios.get('vehiculos/get/foto/unidad/' + this.id).then(response => {
        console.log(response);
        this.dataF = response.data;
        this.dataD = this.dataF.splice(0 , 1);
      }).catch(e => {
        console.error(e);
      });
    },

  },
  mounted() {

  },
}

</script>
