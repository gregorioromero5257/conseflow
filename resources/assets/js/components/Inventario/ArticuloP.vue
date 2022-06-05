<template>
  <main class="main">
    <div class="container-fluid">
      <div class="card">
        <vue-element-loading :active="isLoading"/>
        <div class="card-header">
          <i class="fa fa-align-justify"></i> INVENTARIOS.
        </div>
        <div class="card-body">
          <form>
            <div class="form-row">
              <div class="col-md-6">
                <label>Articulo</label>
                <v-select id="articulo" label="cod"
                :options="listaArt" v-model="articulo_">
              </v-select>
            </div>

          </div>
          <br>
          <div class="form-row">
            <div class="col-md-6">
              <!-- {{listaArt}} -->
              <label><h5>{{articulo_ == '[]' ? '' : articulo_.name}}</h5></label>
              <label><h5>{{articulo_ == '[]' ? '' : articulo_.cat}}</h5></label>
              <label><h5>{{articulo_ == '[]' ? '' : articulo_.ubi}}</h5></label>
            </div>
          </div>
          <div class="form-row" v-show="articulo_ != ''">
            <div class="col-md-6">
              <label>Foto codigo</label>
              <img id="photo_uno" class="photo">
              <input type="file" accept="image/*" capture="camera" id="camera_uno" @change="onChangeUno($event)" />
            </div>
            <div class="col-md-6">
              <label>Foto articulo</label>
              <img id="photo_dos" class="photo">
              <input type="file" accept="image/*" capture="camera" id="camera_uno" @change="onChangeDos($event)" />
            </div>
          </div>
          <br>

      </form>
      <hr>

      <div class="form-row">
        <div class="col-md-2">
          <button class="btn btn-secondary" @click="Cancelar()">Cancelar</button>
          <button class="btn btn-dark" @click="Guardar()">Guardar</button>
        </div>
      </div>
      <hr>
      <!-- <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable"> -->
<!--

        <template slot="id" slot-scope="props">
          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
           <div class="btn-group dropup" role="group">
             <button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                 <i class="fas fa-grip-horizontal"></i> Acciones
             </button>
             <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
          <button type="button" @click="abrirModal('servicio','actualizar',props.row)" class="dropdown-item">
            <i class="icon-pencil"></i>&nbsp;Actualizar servicio.
          </button>
          </div>
          </div>
          </div>

        </template> -->
      <!-- </v-client-table> -->
      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_uno}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <vue-element-loading :active="isLoading"/>
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div>

              <div class="modal-header">
                <h4 class="modal-title" >Agregar Articulo</h4>
                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
                <input type="hidden" name="id">
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" >Nombre </label>
                  <div class="col-md-9">
                    <input type="text" v-validate="'required'"
                    name="nombre" v-model="articulo.nombre"
                    class="form-control" placeholder="Nombre"
                    autocomplete="off" data-vv-name="Nombre">
                    <span class="text-danger">{{ errors.first('Nombre') }}</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" >Categoria</label>
                  <div class="col-md-9">
                    <select class="form-control" data-vv-name="Categoria"
                    v-model="articulo.categoria" v-validate="'required'">
                    <option v-for="t in categoria" :value="t.id">
                      {{t.nombre}}</option>
                    </select>
                  </div>
                  <span class="text-danger">{{ errors.first('Categoria') }}</span>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" >Marca</label>
                  <div class="col-md-9">
                    <input type="text" v-validate="'required'" name="marca"
                    v-model="articulo.marca" class="form-control"
                    placeholder="Marca" autocomplete="off" id="marca"
                    data-vv-name="Marca">
                    <span class="text-danger">{{ errors.first('Marca') }}</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" >
                    # Serie</label>
                    <div class="col-md-9">
                      <input type="text" name="serie"
                      v-model="articulo.serie" class="form-control"
                      placeholder="# Serie" autocomplete="off" id="serie">
                      <span class="text-danger">{{ errors.first('Serie') }}</span>
                    </div>
                  </div>

                  <!-- </form> -->
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                  <button type="button" class="btn btn-secondary" @click="guardarArt(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                  <!-- v-if="tipoAccion==1" -->
                  <!-- <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarServicio(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button> -->
                </div>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
      </div>
    </div>
  </div>
</main>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
export default{
  data () {
    return {
      isLoading : false,
      listaArt : [],
      listaUbi : [],
      articulo_ : '',
      nave : 0,
      ubicacion : '',
      cantidad : '',
      modal_uno : 0,
      categoria : [],
      comentario : '',
      imagen_uno : '',
      imagen_dos : '',
      articulo : {
        nombre : '',
        marca : '',
        categoria : '',
        serie : '',
        id : 0,
      },
      columns: ['nombre_a','marca_a', 'nombre_c','cod','nombre_u'],
      tableData: [],
      options: {
        headings: {
         'id': 'Acción',
        'nombre_a' : 'Nombre',
        'marca_a' : 'Marca',
        'nombre_c' : 'Categoria',
        'cod' : 'Codigo',
        'nombre_u': 'Ubicación',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['nombre_a','marca_a', 'nombre_c','cod','nombre_u'],
        filterable: ['nombre_a','marca_a', 'nombre_c','cod','nombre_u'],
        filterByColumn: true,
      texts:config.texts
  },
    }
  },
  methods : {

    getData(){


        axios.get('inv/get/datainitial').then(response => {
          this.listaArt = [];
          response.data.forEach((item, i) => {
            this.listaArt.push({
              id: item.id,
              name : item.nombre_a + ' ' + item.marca_a ,
              cat : item.nombre_c,
              ubi : item.nombre_u,
              cod : item.codigo,
            });
          });

        }).catch(e => {
          console.error(e);
        });

        axios.get('/inv/art/categoria').then(response => {
          this.categoria = response.data;
        }).catch(e => {
          console.error(e);
        });
      },

      getList(){
        axios.get('inv/get/datainitial').then(response => {
          // console.log(response.data);
          this.tableData = response.data;
        }).catch(error => {
          console.error(error);
        });
      },

      verubicacion(){
        this.ubicacion ='';
        axios.get('naves/'+this.nave).then(response => {
          this.listaUbi = [];
          response.data.forEach((item, i) => {
            this.listaUbi.push({
              id : item.id,
              name : item.cod + ' '+item.nombre,
              cod : item.cod,
            });
          });
        }).catch(e => {
          console.error(e);
        });
      },

      abrirModal(){
        this.modal_uno = 1;
      },

      cerrarModal(){
        this.modal_uno = 0;
      },

      onChangeUno(e){
        alert(e.target.files[0]);
        this.imagen_uno = e.target.files[0];
      },
      onChangeDos(e){
        alert(e.target.files[0]);
        this.imagen_dos = e.target.files[0];
      },

      guardarArt(nuevo){
        this.$validator.validate().then(result => {
          if (result) {
            this.isLoading = true;
            let me = this;
            axios({
              method: nuevo ? 'POST' : 'PUT',
              url: nuevo ? '/inv/art/store' : '/inv/art/update'+this.articulo.id,
              data: {
                'id': this.articulo.id,
                'nombre': this.articulo.nombre,
                'categoria': this.articulo.categoria,
                'marca': this.articulo.marca,
                'serie' : this.articulo.serie,
              }
            }).then(function (response) {
              me.isLoading = false;
              if (response.data.status) {
                if (response.data.status === 'error') {
                  swal({
                    type: 'error',
                    html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
                  });
                }else {
                  me.cerrarModal();
                  me.getData();
                  if (!nuevo) {
                    toastr.success('Actualizado Correctamente');
                  }else {
                    toastr.success('Agregado Correctamente');
                  }
                }
              } else {
                swal({
                  type: 'error',
                  html: response.data.errors.join('<br>'),
                });
              }
            }).catch(function (error) {
              console.log(error);
            });
          }
        });
      },

      Cancelar(){
        this.articulo_ = '';
        this.nave = 0;
        this.ubicacion = '';
        this.cantidad = '';
        this.comentario = '';

      },

      Guardar(){
        if (this.articulo_ === '') {
          toastr.warning('Agregar articulo');
        }else if (this.imagen_uno == '') {
          toastr.warning('Agregar foto codigo');
        }else if (this.imagen_dos == '') {
          toastr.warning('Agregar foto articulo');
        }else {

          this.isLoading = true;

          let formData = new FormData();
          formData.append('id',this.articulo_.id);
          formData.append('imagen_uno',this.imagen_uno);
          formData.append('imagen_dos',this.imagen_dos);
          axios.post('guardarphotos',formData).then(response => {
            this.isLoading = false;
            if (response.data.status) {
                toastr.success('Registrado Correctamente');
                this.getData();
                this.articulo_='';
                this.imagen_uno = '';
                this.imagen_dos = '';
            }
            else {
              swal({
                type: 'error',
                html: response.data.errors.join('<br>'),
              });
            }
          }).catch(err => {
            console.error(err);
          });


        }


      }



    },
    mounted() {
      this.getData();
      this.getList();
    }
  }

  </script>
