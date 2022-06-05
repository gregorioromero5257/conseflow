<template >
  <div class="card">
     <div class="card-header">
     <i class="fa fa-align-justify"></i> Existencias
       <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
     <i class="fas fa-arrow-left"></i>&nbsp;Atras
     </button>
        <br>
        <br>
        <div class="row">
           <div class="col-2">
          <!--<label class="form-control-label" for="proyecto_id">Proyecto:</label>
            <select class="form-control" id="proyecto_id"  name="proyecto_id"  v-model="proyecto_id" v-validate="'required'" data-vv-as="Proyecto">
              <option value="0">---Proyectos---</option>
              <option v-for="item in listaProyectos" :value="item.proyecto.id" :key="item.proyecto.id">{{ item.proyecto.nombre }}</option>
            </select>
            <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
          </div> -->

          <!-- <div class="col-2">
            <label class="form-control-label" for="ubicacion_id">Ubicacion:</label>
            <select class="form-control" id="ubicacion_id"  name="ubicacion_id"  v-model="almacen_id" v-validate="'required'" data-vv-as="Ubicacion" v-on:change="onChangeAlmacen">
              <option value="0">---Ubicacion---</option>
              <option v-for="item in listaUbicacion" :value="item.id" :key="item.id">{{ item.nombre}} , {{item.ubicacion }}</option>
            </select>
            <span class="text-danger">{{ errors.first('ubicacion_id') }}</span>
          </div> -->

          <!-- <div class="col-2">
            <label class="form-control-label" for="stand_id">Stand:</label>
            <select class="form-control" id="stand_id"  name="stand_id"  v-model="stand_id" v-validate="'required'" data-vv-as="Stand" v-on:change="onChangeStand">
              <option value="0">---Stand---</option>
              <option v-for="item in listaStand" :value="item.id" :key="item.id">{{ item.nombre }}</option>
            </select>
            <span class="text-danger">{{ errors.first('stand_id') }}</span>
          </div> -->

          <!-- <div class="col-2">
            <label class="form-control-label" for="nivel_id">Nivel:</label>
            <select class="form-control" id="nivel_id"  name="nivel_id"  v-model="nivel_id" v-validate="'required'" data-vv-as="Nivel" >
              <option value="0">---Nivel---</option>
              <option v-for="item in listaNivel" :value="item.id" :key="item.id">{{ item.nombre }}</option>
            </select>
            <span class="text-danger">{{ errors.first('nivel_id') }}</span>
          </div> -->
        </div>

        </div>
        <!-- <div class="row">
          <div class="col-2">
            <div>
                <button type="button" class="btn btn-primary btn-block" @click="buscarExistencias()">Buscar</button>
            </div>
          </div>
        </div> -->
      </div>
      <div class="card-body">
        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
        </v-client-table>
      </div>
    </div>

</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {

  data (){
    return {
      data : null,
       art_desc: '',
       niv_nom : '',
       stand_nomb : '',
       cantidad : 0,
       prnom : '',
       tu_n : '',
       almacen_id: 0,
       proyecto_id: 0,
       nombre : '',
       alm_ub: 0,
       stand_id: 0,
       lote: '',
       stand: '',
       nivel_id: 0,
       nivel: '',
       ubicacion_id: 0,
       alm_nom: '',
     listaProyectos: [],
     listaNivel: [],
     listaUbicacion: [],
     listaStand: [],
     listaAlmacenes:[],
     columns: ['lote','alm_nom','tu_n','stand_nomb','niv_nom','prnom'],
     tableData: [],
     options : {
       headings : {
         lote: 'Lote',
         alm_nom: 'Nombre de almacen',
         tu_n: 'Ubicacion',
         niv_nom: 'Nivel',
         stand_nomb: 'Stand',
         prnom: 'Proyecto',
       },
       perPage: 10,
       perPageValues: [],
       groupBy:['art_desc'],
       skin: config.skin,
       sortIcon: config.sortIcon,
      sortable: ['lote','alm_nom','tu_n','stand_nomb','niv_nom','prnom'],
      filterable: ['lote','alm_nom','tu_n','stand_nomb','niv_nom','prnom'],
       filterByColumn: true,
       texts:config.texts
     },

    }
  },
  methods : {
    cargardetalleexistencias(data){
      let me=this;
      me.data=data;
      axios.get('/busquedaE/'+this.proyecto_id+'&'+this.almacen_id+'&'+this.stand_id+'&'+this.nivel_id+'&'+this.data.id).then(response =>{
        if(response.data.length != 0){
          me.tableData = response.data[0].articulos;
          // me.sumas = response.data[0].sumaexistencias;
        }
        else {
          me.tableData = [];
        }
      })

    },
    getData() {
      let me=this;

      axios.get('/proyecto').then(response => {
        me.listaProyectos = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });

      axios.get('existencia/getlist').then(response =>{
        me.listaUbicacion = response.data;
        me.isLoading = false;
      })
      .catch(function (error){
        console.log(error);
      });
    },

    buscarExistencias(){
    let me = this;
    axios.get('/busquedaE/'+this.proyecto_id+'&'+this.almacen_id+'&'+this.stand_id+'&'+this.nivel_id+'&'+this.data.id).then(response =>{
      if(response.data.length != 0){
        me.tableData = response.data[0].articulos;
        // me.sumas = response.data[0].sumaexistencias;
      }
      else {
        me.tableData = [];
      }
    })
  },

  onChangeAlmacen() {
    // console.log(this.almacen_id);
    let me = this;
    axios.get('/existencia/getlist/stand/' + this.almacen_id).then(response => {
      me.listaStand = response.data;
    })
    .catch(function (error) {
      console.log(error);
    })
  },
  onChangeStand(){
    let me = this;
    axios.get('/existencia/getlist/nivele/' + this.stand_id).then(response =>{
      me.listaNivel = response.data;
    })
    .catch(function (error){
      console.log(error);
    })

  },
  descargar() {
      window.open('descargar-existencia/'+this.proyecto_id+'&'+this.almacen_id+'&'+this.stand_id+'&'+this.nivel_id+'&'+this.data.id, '_blank');
    },

  maestro(){
    this.$emit('existenciasdetalle:change');
      }

  },

  mounted() {
    this.getData();
  }

}
</script>
