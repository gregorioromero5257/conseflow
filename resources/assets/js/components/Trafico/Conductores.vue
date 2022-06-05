<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Conductores
          <button type="button" @click="abrirModal(1)" class="btn btn-dark float-sm-right">
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">

          <v-client-table :data="tableData" :columns="columns" :options="options">
            <template slot='conductores.id' slot-scope='props'>
              <div class='btn-group' role='group'>
                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  <i class='fas fa-grip-horizontal'></i>&nbsp;
                </button>
                <div class='dropdown-menu'>
                  <template>
                    <button type='button' @click='abrirModal(2, props.row)' class='dropdown-item'>
                      <i class='fas fa-pencil'></i>&nbsp;Actualizar
                    </button>
                    <button type='button' @click='eliminar(props.row.conductores)' class='dropdown-item'>
                      <i class='fas fa-trash'></i>&nbsp;Eliminar
                    </button>
                  </template>
                </div>
              </div>
            </template>
            <template slot="adjunto" slot-scope="props">
              <button class="btn btn-outline-dark" @click="descargarf(props.row.imagen.nombre)">Descargar</button>
            </template>
          </v-client-table>

        </div>
      </div>

      <!-- Modal -->
      <div class='modal fade' tabindex='-1' :class="{'mostrar' : modal}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h4 class='modal-title' v-text='tituloModal'></h4>
              <button type='button' class='close' @click='CerrarModal()' aria-label='Close'>
                <span aria-hidden='true'>×</span>
              </button>
            </div>
            <div class='modal-body'>
              <div class="form-row">
                <div class="col-md-5 mb-3">
                  <label>Empleado</label>
                  <v-select :options="listaEmpleados" label="name" v-model="empleado" data-vv-name="empleado" v-validate="'required'"></v-select>
                  <span class="text-danger">{{errors.first('empleado')}}</span>
                </div>
                <div class="col-md-4 mb-3">
                  <label># Licencia</label>
                  <input type="text" class="form-control" v-model="licencia" data-vv-name="Licencia" v-validate="'required'">
                  <span class="text-danger">{{errors.first('Licencia')}}</span>
                </div>
                <div class="col-md-3 mb-3">
                  <label>Tipo</label>
                  <input type="text" class="form-control" v-model="tipo" data-vv-name="Tipo" v-validate="'required'">
                  <span class="text-danger">{{errors.first('Tipo')}}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-3 mb-3">
                  <label>Vigencia</label>
                  <input type="date" class="form-control" v-model="vigencia" data-vv-name="Vigencia" >
                  <span class="text-danger">{{errors.first('Vigencia')}}</span>
                </div>
                <div class="col-md-4 mb-3">
                  <label>&nbsp;</label>
                  <!-- <input type="file"  ref="adjunto" @change="cargarImg()"/> -->
                  <template v-if="poliza != ''">
                    <label>Adjunto</label>
                    <button type="button" class="form-control" @click="poliza = ''">
                      <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                    </button>
                  </template>
                  <template v-if="poliza === ''">
                    <label>Adjunto</label>
                    <input type="file" class="form-control" name="pdf"  @change="onChangeComprobantePoliza($event)">
                  </template>


                </div>
              </div>
              <!-- <div class="form-row">

                <div class="form-group col-md-2">
                  <label><b>#</b></label>
                </div>
                <div class="form-group col-md-6">
                  <label><b>Preview</b></label>
                </div>
                <div class="form-group col-md-1">
                  <label><b>.</b></label>
                </div>
              </div> -->
              <!-- <li v-for="(vi, index) in img" class="list-group-item">
                <div class="form-row">

                  <div class="form-group col-md-2">
                    <label>{{index + 1}}</label>
                  </div>
                  <div class="form-group col-md-6">
                    <label></label>
                    <img :src="vi.name" width="200px" height="150px">
                  </div>
                  <div class="form-group col-md-1">
                    <a @click="deleteu(vi, index)">
                      <span class="fas fa-trash" arial-hidden="true"></span>
                    </a>
                  </div>
                </div>
              </li> -->



            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-outline-dark' @click='CerrarModal()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
              <button type='button' v-if='tipoAccion == 1' class='btn btn-secondary' @click='Guardar(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
              <button type='button' v-if='tipoAccion == 2' class='btn btn-secondary' @click='Guardar(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- Modal -->

    </div>
  </main>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return{
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,

      listaEmpleados : [],

      img : [],
      empleado : '',
      licencia : '',
      tipo : '',
      vigencia : '',
      id : 0,
      poliza : '',

      tableData : [],
      columns : ['conductores.id','conductores.nombre_empleado','conductores.licencia','conductores.tipo','conductores.vigencia','adjunto'],
      options: {
        headings: {
          'conductores.id' : 'Acciones',
          'conductores.nombre_empleado' : 'Empleado',
          'conductores.licencia' : 'Licencia',
          'conductores.tipo' : 'Tipo',
          'conductores.vigencia' : 'Vigencia',

        },
        perPage: 5,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },

    }
  },
  methods : {
    getLista(){
      axios.get('get/conductores').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    getData(){
      this.listaEmpleados = [];
      axios.get('/vertodosempleados').then(response =>{
        response.data.forEach(data =>{
          this.listaEmpleados.push({
            id: data.id,
            name: data.nombre + ' ' + data.ap_paterno + ' ' + data.ap_materno
          });
        });
      })
      .catch(function (error)
      {
        console.log(error);
      });
    },

    onChangeComprobantePoliza(e){
      this.poliza = e.target.files[0];
    },

    vaciar(){
      this.empleado = '';
      this.licencia = '';
      this.tipo = '';
      this.vigencia = '';
      this.img = [];
    },

    abrirModal(edo, data = []){
      if (edo == 1) {
        this.vaciar();
        this.modal = 1;
        this.tituloModal = 'Agregar';
        this.tipoAccion = 1;
      }else if (edo == 2) {
        console.log(data);
        this.vaciar();
        this.id = data['conductores']['id'];
        this.empleado = {id : data['conductores']['nombre'], name : data['conductores']['nombre_empleado']};
        this.licencia = data['conductores']['licencia'];
        this.tipo = data['conductores']['tipo'];
        this.vigencia = data['conductores']['vigencia'];
        this.poliza = data['imagen'] == null ? '' :data['imagen']['nombre'];
        this.modal = 1;
        this.tituloModal = 'Actualizar';
        this.tipoAccion = 2;
      }
    },

    getImages(id){
      this.img = [];
      axios.get('get/imagenes/conductores/' + id).then(response => {
        response.data.forEach((item, i) => {
          this.img.push({id : item.id, name : item.img});
        });
      }).catch(e => {
        console.error(e);
      });
    },

    CerrarModal(){
      this.modal = 0;
    },

    Guardar(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
            let me = this;
            let formData = new FormData();
            formData.append('id', me.id);
            formData.append('poliza',me.poliza);
            formData.append('nombre', me.empleado.id);
            formData.append('licencia', me.licencia);
            formData.append('tipo', me.tipo);
            formData.append('vigencia', me.vigencia);
            formData.append('metodo',me.tipoAccion);
          axios({
            method :'POST',
            url : 'guardar/conductor',
            data : formData
          }).then(response => {
            toastr.success('Correcto !!!');
            this.CerrarModal();
            this.getLista();
          }).catch(e => {
            console.error(e);
          });
        }
      });
    },

    cargarImg(){
      const selectedImage = this.$refs.adjunto.files[0];
      this.imageToBase64(selectedImage);
    },

    imageToBase64 (file) {
      var reader = new FileReader()
      reader.readAsDataURL(file)
      reader.onload = () => {
        this.img.push({id : 0,name : reader.result});
      }
      reader.onerror = function (error) {
        console.log('Error: ', error)
      }
    },

    deleteu(data, index){
      if (data.id == 0) {
        this.img.splice(index, 1);
      }else if (data.id != 0) {
        axios.get('delete/imagenes/conductores/' + data.id).then(response => {
          this.getImages(this.id);
        }).catch(e => {
          console.error(e);
        });
      }
    },

    descargarf(archivo){
      let me=this;
      axios({
        url: '/licenciadescarga/' + archivo,
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
        axios.get('/licenciaeditar/' + archivo)
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
    this.getLista();
  }
}
</script>
