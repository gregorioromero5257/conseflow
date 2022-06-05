<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Estatus de material

        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label class="form-control-label" for="proyecto_id">Seleccionar:</label>
              <v-select :options="listaProyectos"  v-validate="'required'" v-model="proyecto_id" label="name" name="proyecto" data-vv-name="Proyecto" ></v-select> <!---->
              <span class="text-danger">{{ errors.first('Proyecto') }}</span>
            </div>
            <div class="form-group col-md-2">
              <label>&nbsp;</label>
              <button type="button" class="btn btn-primary btn-block" @click="buscarExistencias()">
                Buscar
              </button>
            </div>
            <div class="form-group col-md-2">
              <label>&nbsp;</label>
              <button type="button" class="btn btn-success btn-block" @click="descargar()">
                Descargar
              </button>
            </div>
          </div>
          <v-client-table :data="tableData" :options="options" :columns="columns">
            <vue-element-loading :active="isLoading"/>

            <template slot="requisicion.folio_requisicion" slot-scope="props">
              {{String(props.row.requisicion.folio_requisicion).replace('REQ-','')}}<br>
              <button class="btn btn-outline-dark" @click="descargareq(props.row.requisicion)">
                <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>
              </button>
              <!-- <button v-if="props.row.folio_requisicion != null" class="btn btn-outline-dark" @click="descargareq(props.row)" >
                <i class="fas fa-file-pdf"></i>&nbsp;&nbsp;<i class="fas fa-download"></i>
              </button> -->
              <button v-if="props.row.requisicion.folio_requisicion != null " class="btn btn-outline-dark" @click="subirfotoreq(props.row.requisicion)"><i class="fas fa-camera-retro"></i></button>
              <button v-if="props.row.requisicion.imagen != null" class="btn btn-outline-dark" @click="descargarfotoreq(props.row.requisicion)"><i class="fas fa-eye"></i></button>
            </template>

            <template slot="oc.poc_comentario" slot-scope="props">
              <template v-if="props.row.oc != null">
                <template v-if="props.row.oc.folio_orden_compra != null">
                  {{props.row.requisicion.descripcion}}<br>{{props.row.oc.poc_comentario}}
                </template>
              </template>

            </template>

            <template slot="oc.folio_orden_compra" slot-scope="props" >
              <template v-if="props.row.oc != null">
              <template v-if="props.row.oc.folio_orden_compra != null">
                {{String(props.row.oc.folio_orden_compra).replace('OC-CONSERFLOW-020-','')}}
              </template><br>
              <button v-if="props.row.oc.folio_orden_compra != null" class="btn btn-outline-dark" @click="descargaoc(props.row.oc)" >
                <i class="fas fa-file-pdf"></i>&nbsp;&nbsp;<i class="fas fa-download"></i>
              </button>
              <button v-if="props.row.oc.folio_orden_compra != null " class="btn btn-outline-dark" @click="subirfoto(props.row.oc)"><i class="fas fa-camera-retro"></i></button>
              <button v-if="props.row.oc.image != null" class="btn btn-outline-dark" @click="descargarfoto(props.row.oc)"><i class="fas fa-eye"></i></button>
            </template>

            </template>
          </v-client-table>
        </div>
      </div>

      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalver}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Detalle</h4>
              <button type="button" class="close" @click="cerrarModalVer()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- <div style="text-align: center;">
              <iframe :src="this.nombre_archivo" width="500" height="500" frameborder="0"></iframe>
            </div> -->
            <ul class="list-group">
              <li v-for="l in listadoImagenes" class="list-group-item d-flex justify-content-between align-items-right">

                {{l.imagen}}
                <div class="col-md-3">
                  <span class="btn btn-primary btn-sm" @click="verPreview(l)"> <i class="fas fa-eye"></i> </span>
                  <span class="btn btn-success btn-sm" @click="descargarFile(l.imagen)"> <i class="fas fa-download"></i> </span>
                  <span class="btn btn-danger btn-sm" @click="eliminarImg(l)"> <i class="fas fa-trash"></i> </span>
                </div>
              </li>

            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" @click="cerrarModalVer()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->

    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : verModalImg}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Preview</h4>
            <button type="button" class="close" @click="cerrarModalImg()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="card-body">
                <img :src="this.path"
                alt="La cabeza y el torso de un esqueleto de dinosaurio;
                tiene una cabeza grande con dientes largos y afilados"
                width="400"
                height="400">
              </div>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" @click="cerrarModalImg()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal-->

  </div>
</main>
</template>
<script>

import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      data : '',
      nombre_img_temp : '',
      path : '',
      verModalImg : 0,
      isLoading : false,
      nombre_archivo : '',
      listaProyectos : [],
      listadoImagenes : [],
      proyecto_id : '',
      modalver : 0,
      columns: [
        'requisicion.nombre',
        'requisicion.folio_requisicion',
        'requisicion.descripcion',
        'requisicion.cantidad_compra',
        'oc.partida_orden_compra_cantidad',
        'oc.folio_orden_compra',
        'oc.poc_comentario',
        'oc.fecha_orden',
        'oc.periodo_entrega'
      ],
      tableData: [],
      options: {
        headings: {
          'requisicion.nombre' : 'Tipo de material',
          'requisicion.folio_requisicion' : 'Requisición',
          'requisicion.descripcion' : 'Descripción',
          'requisicion.cantidad_compra' : 'Cant. Requisitada',
          'oc.partida_orden_compra_cantidad' :  'Cant. Procurada',
          'oc.folio_orden_compra' : 'OC Folio',
          'oc.poc_comentario' : 'Descripción',
          'oc.fecha_orden' : 'Fecha Orden',
          'oc.periodo_entrega' : 'Periodo Entrega',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        listColumns: { },
        texts:config.texts
      },
    }
  },

  methods :{
    getData() {
      let me=this;
      axios.get('/proyecto-todos').then(response => {
        response.data.forEach((item, i) => {
          this.listaProyectos.push({
            id: item.proyecto.id,
            name : item.proyecto.nombre_corto
          });
        });
      })
      .catch(function (error) {
        console.log(error);
      });

    },

    buscarExistencias(){
      this.$validator.validate().then(result => {
        if (result) {
          axios.get('estatusmaterial/' + this.proyecto_id.id).then(response => {
            this.tableData = response.data;
          }).catch(err => {
            console.error(err);
          })
        }
      });
    },

    descargar(){
      window.open(`descargar-excel-estatus-de-material/${this.proyecto_id.id}` , '_blank');
    },

    /**
    * [descargar description]
    * @param  {[type]} data [description]
    * @return {[type]}      [description]
    */
    descargareq(data) {
      console.log(data);
      window.open('descargar-requisicionew/' + data.requisicione_id, '_blank');
    },

    /**
    * [descargar description]
    * @param  {[type]} data [description]
    * @return {[type]}      [description]
    */
    descargaoc(data) {
      window.open('descargar-compran/' + data.oc_id, '_blank');
      // let me = this;
      // me.getData(data.proyecto_id);
    },

    subirfoto(data){
      console.log(
        data,'asdfghjk'
      );
      this.isLoading = true;
      Swal.fire({
        title: 'Selecionar Imagen',
        input: 'file',
        inputAttributes: {
          'accept': 'image/*',
          'aria-label': 'Upload your profile picture'
        }
      }).then(result => {
        if (result.value) {
          console.log(result);

          ///////////////


          let formData = new FormData();
          formData.append('req_com_id',data.rhoc_id);
          formData.append('imagen',result.value);

          axios({
            method : 'POST',
            url : 'estatusmaterial/subir/foto/',
            data :formData,
          }).then(response => {
            this.buscarExistencias();
            toastr.success('Correcto');
            this.isLoading = false;
          }).catch(e => {
            this.isLoading = true;
            toastr.success('Error');
            console.error(e);
          });

          //////////////////////

        }
      });

    },

    subirfotoreq(data){
      console.log(
        data,'asdfghjk'
      );
      this.isLoading = true;
      Swal.fire({
        title: 'Selecionar Imagen',
        input: 'file',
        inputAttributes: {
          'accept': 'image/*',
          'aria-label': 'Upload your profile picture'
        }
      }).then(result => {
        if (result.value) {
          console.log(result);

          ///////////////


          let formData = new FormData();
          formData.append('requisicione_id',data.requisicione_id);
          formData.append('articulo_id',data.articulo_id);
          formData.append('imagen',result.value);

          axios({
            method : 'POST',
            url : 'estatusmaterial/subir/foto/req',
            data :formData,
          }).then(response => {
            this.buscarExistencias();
            toastr.success('Correcto');
            this.isLoading = false;
          }).catch(e => {
            this.isLoading = true;
            toastr.success('Error');
            console.error(e);
          });

          //////////////////////

        }
      });

    },

    descargarfoto(data){
      console.log(data);
      this.data = data;
      axios.get('get/imagenes/partidas/oc/' +  data.rhoc_id).then(response => {
        this.listadoImagenes = response.data;
      }).catch(e => {
        console.error(e);
      });

      this.modalver = 1;
      // var method = 1;
      // axios.get('/estatusmaterial/descargar/foto/'+ data.image).then(response => {
      // }).catch(error => {
      //   console.error(error);
      // });
      // this.nombre_archivo = 'FilesFTP/'+data.image;
    },

    descargarfotoreq(data){
      console.log(data);
      this.data = data;
      axios.get('get/imagenes/partidas/oc/req/' +  data.requisicione_id + '&' + data.articulo_id).then(response => {
        this.listadoImagenes = response.data;
      }).catch(e => {
        console.error(e);
      });

      this.modalver = 1;
      // var method = 1;
      // axios.get('/estatusmaterial/descargar/foto/'+ data.image).then(response => {
      // }).catch(error => {
      //   console.error(error);
      // });
      // this.nombre_archivo = 'FilesFTP/'+data.image;
    },

    verimg(){
      this.modalver = 1;
    },

    cerrarModalVer(){
      this.modalver = 0;
    },

    verPreview(data){
      console.log(data);
      this.nombre_img_temp = data.imagen;
      axios.get('get/imagenes/save/temp/' + data.imagen).then(response => {
        this.path = 'temp/' + data.imagen;
      }).catch(e => {
        console.error(e);
      });

      this.verModalImg = 1;


    },

    // descargarFile(data){
    //   console.log(data);
    // },

    cerrarModalImg(){
      axios.get('get/imagenes/delete/temp/' + this.nombre_img_temp).then(response => {
      }).catch(e => {
        console.error(e);
      });
      this.verModalImg = 0;
    },

    eliminarImg(data){
      if (data.hasOwnProperty('requisicione_id')) {
        axios.get('delete/imagenes/partidas/req/' + data.id).then(response => {
          toastr.success('Correcto !!!');
          this.descargarfoto(this.data);
        }).catch(e => {
          console.error(e);
        });
      }else {
        axios.get('delete/imagenes/partidas/' + data.id).then(response => {
          toastr.success('Correcto !!!');
          this.descargarfoto(this.data);
        }).catch(e => {
          console.error(e);
        });
      }

    },

    descargarFile(archivo){
      let me=this;
      axios({
        url: '/descargar/fotos/calidad/' + archivo,
        method: 'GET',
        responseType: 'blob', // importante
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
        document.body.appendChild(link);
        link.click();
        //--Llama el metodo para borrar el archivo local una ves descargado--//
        axios.get('/descargar/fotos/editar/' + archivo)
        .then(response => {
        })
        .catch(function (error) {
          console.log(error);
        });
        //--fin del metodo borrar--//
      });
    },

  },
  mounted(){
    this.getData();
  }
}

</script>
