<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"> </i> Generar Reportes

        </div>
        <div class="card-body">
          <div class="card-header">

            <div class="form-row">
              <div class="form-group col-md-4">
                <select class="form-control" @change="unidades()" v-model="Unidades.usuario" v-validate="'required'" data-vv-name="Propietario">
                  <option value="0">Todos</option>
                  <option v-for="item in Unidades.propietario"  :value="item.nombre">{{item.nombre}}</option>
                </select>
                <span class="text-danger">{{errors.first('Propietario')}}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-8">
                <label>Unidades</label>
                <select class="form-control" v-model="Unidades.vehiculo" v-validate="'required'" data-vv-name="Unidades">
                  <option value="0" selected>Todos</option>
                  <option v-for="item in Unidades.unidades" :value="item.id">{{item.unidad}}/{{item.modelo}}/{{item.placas}}</option>
                </select>
                <span class="text-danger">{{errors.first('Unidades')}}</span>
              </div>
            </div>

            <div class="form-group" >

              Servicios
              <label class="switch switch-default switch-pill switch-dark">
                <input type="checkbox" class="switch-input" name="servicios" v-model="Unidades.servicio">
                <span class="switch-label"></span>
                <span class="switch-handle"></span>
              </label>
              &nbsp;
              Polizas
              <label class="switch switch-default switch-pill switch-dark">
                <input type="checkbox" class="switch-input" name="poliza" v-model="Unidades.poliza">
                <span class="switch-label"></span>
                <span class="switch-handle"></span>
              </label>
              &nbsp;
              Verificacion
              <label class="switch switch-default switch-pill switch-dark">
                <input type="checkbox" class="switch-input" name="verificacion" v-model="Unidades.verificacion">
                <span class="switch-label"></span>
                <span class="switch-handle"></span>
              </label>
              &nbsp;
              Tenencia
              <label class="switch switch-default switch-pill switch-dark">
                <input type="checkbox" class="switch-input" name="tenencia" v-model="Unidades.tenencia" >
                <span class="switch-label"></span>
                <span class="switch-handle"></span>
              </label>

            </div>
            <div class="form-row">
              <!-- <div class="form-group col-md-2">
                <button type="button" @click="buscar()" class="btn btn-secondary">
                  <i class="fas fa-search"></i>&nbsp;Buscar
                </button>
              </div> -->
              <div class="form-group col-md-2">
                <button type="button" @click="buscar()" class="btn btn-primary">
                  <i class="fas fa-file-pdf"></i>&nbsp;Descargar
                </button>
              </div>
            </div>



          </div>


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
      url: '/UnidadesStore',
      tipo : 0,
      Unidades: {
        id: 0,
        unidades: [],
        propietario : [],
        usuario : '',
        vehiculo : '',
        servicio : '',
        poliza : '',
        verificacion : '',
        tenencia : '',
      },
      detalleu : false,
      Metodo: '',
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      isLoading: false,
      columns: ['id', 'unidad', 'modelo', 'placas'],
      tableData: [],
      tableUnidades: [],
      catalogotrafico : [],
      clase_tipo : [],
      combustible : [],
      options: {
        headings: {
          placas: 'Placas',
          modelo: 'Modelo',
          unidad: 'Unidad',
          id: 'Opciones',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['modelo', 'placas', 'unidad'],
        filterable: ['modelo', 'placas', 'unidad'],
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  methods : {
    eliminarObjetosDuplicados(arr, prop) {
      var nuevoArray = [];
      var lookup  = {};

      for (var i in arr) {
        lookup[arr[i][prop]] = arr[i];
      }

      for (i in lookup) {
        nuevoArray.push(lookup[i]);
      }

      return nuevoArray;
    },

    getData() {
      let me=this;
      axios.get('propietariosUnidades').then(response => {
        var array = [];
        response.data.forEach((item, i) => {
          array.push({'nombre' : item.nombre});
        });
        var duplicadosEliminados = this.eliminarObjetosDuplicados(array, 'nombre');
        me.Unidades.propietario = duplicadosEliminados;
      }).catch(err => {
        console.error(err);
      });
    },

    unidades(){
      let me=this;
      axios.get('/unidadesbuscarusr/'+this.Unidades.usuario).then(response => {
        me.Unidades.unidades = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    buscar(){
      this.$validator.validate().then(result => {
        if(result){
          // axios.post('unidadreportebuscar',{
          //   usuario : this.Unidades.usuario,
          //   vehiculo : this.Unidades.vehiculo,
          //   servicio : this.Unidades.servicio,
          //   poliza : this.Unidades.poliza,
          //   verificacion : this.Unidades.verificacion,
          //   tenencia : this.Unidades.tenencia,
          // }).then(response => {
          //   console.log(response);
          // }).catch(err => {
          //   console.error(err);
          // });

          window.open('unidadreportebuscar/' + this.Unidades.usuario + '&' +this.Unidades.vehiculo + '&' +this.Unidades.servicio
              + '&' + this.Unidades.poliza + '&' + this.Unidades.verificacion + '&' + this.Unidades.tenencia, '_blank');
          let me = this;


        }
      });
    }
  },
  mounted() {
    this.getData();
  }
}
</script>
