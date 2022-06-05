<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          Generar Codigo de barra
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-3">
              <label>Tipo</label>
              <select class="form-control" v-model="tipo_b">
                <option value="1">Articulo</option>
                <option value="2">Proyecto</option>
              </select>
            </div>
            <template v-if="tipo_b == 1">
              <div class="form-group col-md-6">
                <label>Articulo</label>
                <input type="text" class="form-control" v-model="articulo">
              </div>
            </template>
            <template v-if="tipo_b == 2">
              <div class="form-group col-md-4">
                <label>Proyecto</label>
                <v-select :options="listaProyectos" v-model="proyecto" label="name" ></v-select>
              </div>
            </template>
            <div class="form-group col-md-2">
              <label>&nbsp;</label>
              <br>
              <button class="btn btn-dark" @click="listarComprasText()">Buscar</button>
            </div>
          </div>
          <v-client-table :options="options" :columns="columns" :data="tableData" @row-click="seleccionarArticulo">
            <!-- <template slot="ids" slot-scope="props">
            <button class="btn btn-sm btn-danger" @click="descargar(props.row.ids)"><i class="fas fa-file-pdf"></i> Descargar OC</button>
          </template> -->
        </v-client-table>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <!-- {{array_seleccionado}} -->
        <div class="form-row" >

          <div class="form-group col-md-6">
            <label><b>DESCRIPCIÓN</b></label>
          </div>
          <div class="form-group col-md-1">
            <label><b>ACCIONES</b></label>
          </div>
          <div class="form-group col-md-2">
            <button class="btn btn-dark" @click="generar()"><i class="fas fa-file-pdf"></i>&nbsp;Generar</button>
          </div>
          <div class="form-group col-md-1">
            <button class="btn btn-secondary" @click="limpiar()"><i class="fas fa-trash"></i>&nbsp;Limpar</button>
          </div>
        </div>
        <li v-for="(vi, index) in array_seleccionado" class="list-group-item">
          <div class="form-row">
            <div class="form-group col-md-5">
              <label>{{vi.name}}</label>
            </div>
            <div class="form-group col-md-1">
              <a @click="eliminar_aignacion(vi, index)">
                <span class="fas fa-trash" arial-hidden="true"></span>
              </a>
            </div>
          </div>
        </li>
      </div>
    </div>

  </div>
</main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      array_seleccionado : [],
      articulo : '',
      tipo_b : '',
      proyecto : '',
      listaProyectos : [],
      columns : ['folio','nombre','desc','unidad','comentario','cantidad'],
      tableData : [],
      options : {
        headings: {
          'folio' : 'OC',
          'desc' : 'Descripción',
        },
        perPage: 5,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['folio','nombre','desc','unidad','comentario','cantidad'],
        filterable: ['folio','nombre','desc','unidad','comentario','cantidad'],
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  methods : {
    getData(){
      axios.get('/proyecto-listar-todos').then(response =>{
        this.listaProyectos = [];
        response.data.forEach(data =>{
          this.listaProyectos.push({
            id: data.id,
            name: data.nombre_corto ,
          });
        });
      }).catch(function (error){
        console.log(error);
      });
    },

    limpiar(){
      this.array_seleccionado = [];
    },

    listarComprasText(){
      axios.post('buscar/articulo/lotes',{
        articulo : this.articulo,
        proyecto : this.proyecto.id,
        tipo : this.tipo_b,
      }).then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    seleccionarArticulo(data){
      console.log(data);
      // console.log(this.array_seleccionado.length);
      if (this.array_seleccionado.find(info => info.name == data.row.desc)) {
        toastr.warning('Ya seleccionado');
      }else if (this.array_seleccionado.length > 9) {
        toastr.warning('Solo se pueden seleccionar 10 elementos');
      }
      else {
        this.array_seleccionado.push({
          id : data.row.lote_id,
          name : data.row.desc,
          pos : 0,
        });
      }
    },

    eliminar_aignacion(data, index){
      if (data.pos == 0)
      {
        this.array_seleccionado.splice(index, 1);
      }
      else
      {
        let me = this;
        var id = data['id'];
        axios.get(`relaciones/eliminar/${id}`)
        .then(function (response)
        {
          me.array_seleccionado.splice(index, 1);
        })
        .catch(function (error)
        {
          console.log(error);
        });
      }
    },

    generar(){
      var art = '';
      var c = 0;
      this.array_seleccionado.forEach((item, i) => {
        art += item.id + '&';
        c++;
      });
      // if (c < 10) {
      //   toastr.warning('Necesita 10 elementos');
      // }else {
        window.open('generar/codigos/' + art, '_blank');
      // }
      // axios.post('generar/codigos/',{data : this.array_seleccionado}).then(response => {
      //   console.log(response);
      // }).catch(error => {
      //   console.error(error);
      // });
    }

  },
  mounted() {
    this.getData();
  }
}
</script>
