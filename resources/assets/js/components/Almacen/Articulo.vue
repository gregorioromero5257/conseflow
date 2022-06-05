<template>
  <main class="main">


    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <vue-element-loading :active="isLoading"/>
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div>
            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModal"></h4>
              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">

              <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                <div class="row">
                  <div class="col">
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                      <div class="col-md-9">
                        <input type="text"  name="nombre" v-model="nombre" class="form-control" placeholder="Nombre del articulo" autocomplete="off" id="nombre">
                        <span class="text-danger">{{ errors.first('nombre') }}</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="codigo">Código</label>
                      <div class="col-md-9">
                        <input type="text"  name="codigo" v-validate="'max:50'" v-model="codigo" class="form-control" placeholder="Codigo" autocomplete="off" id="codigo">
                        <span class="text-danger">{{ errors.first('codigo') }}</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="descripcion">Descripción</label>
                      <div class="col-md-9">
                        <textarea v-validate="'required'" name="descripcion" v-model="descripcion" class="form-control" placeholder="Descripcion"></textarea>
                        <span class="text-danger">{{ errors.first('descripcion') }}</span>
                      </div>
                    </div>
                    <!-- <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="nombreproveedor">Descripcion para proveedores</label>
                      <div class="col-md-9">
                        <textarea v-validate="'required'" name="nombreproveedor" v-model="nombreproveedor" class="form-control" placeholder="Nombre Proveedor"></textarea>
                        <span class="text-danger">{{ errors.first('nombreproveedor') }}</span>
                      </div>
                    </div> -->
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="marca">Marca</label>
                      <div class="col-md-9">
                        <input type="text"  name="marca" v-validate="'max:100'" v-model="marca" class="form-control" placeholder="Marca" autocomplete="off" id="marca">
                        <span class="text-danger">{{ errors.first('marca') }}</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="unidad">U.M.</label>
                      <div class="col-md-9">
                         <v-select name="proyectos" label="nombre" v-model="unidad" :options="listUnidadesM"></v-select>
                        <!-- <input type="text" v-validate="'required|max:30'" name="unidad" v-model="unidad" class="form-control" placeholder="Unidad de medida" autocomplete="off" id="unidad"> -->
                        <span class="text-danger">{{ errors.first('unidad') }}</span>
                      </div>
                    </div>

                </div>
                  <div class="col">
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="trid">Tipo de Resguardo</label>
                      <div class="col-md-9">
                        <select class="form-control" id="trid"  name="trid"  v-model="trid" v-validate="'required|excluded:0'" data-vv-as="Resguardo">
                          <option v-for="item in listaTipoResguardo" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                        </select>
                          <span class="text-danger">{{ errors.first('trid') }}</span>
                      </div>
                    </div>
                    <!-- <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="comentarios">Comentarios</label>
                      <div class="col-md-9">
                        <textarea name="comentarios" v-model="comentarios" class="form-control" placeholder="Comentarios"></textarea>
                        <span class="text-danger">{{ errors.first('comentarios') }}</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="minimo">Minimo</label>
                      <div class="col-md-9">
                        <input type="number"  name="minimo" v-model="minimo" class="form-control" placeholder="0" autocomplete="off" id="minimo" min="0">
                        <span class="text-danger">{{ errors.first('minimo') }}</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="maximo">Maximo</label>
                      <div class="col-md-9">
                        <input type="number"  name="maximo" v-model="maximo" class="form-control" placeholder="0" autocomplete="off" id="maximo" min="0">
                        <span class="text-danger">{{ errors.first('maximo') }}</span>
                      </div>
                    </div> -->
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="categoria_id">Categoria</label>
                      <div class="col-md-9">
                        <select class="form-control" id="categoria_id"  name="categoria_id" v-model="categoria_id" v-validate="'required|excluded:0'" data-vv-as="Categoria" v-on:change="onChangeCategoria">
                          <option v-for="item in listaCategorias" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                        </select>
                        <span class="text-danger">{{ errors.first('categoria_id') }}</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="grupo_id">Grupo</label>
                      <div class="col-md-9">
                        <vue-element-loading :active="isLoadingSelect"/>

                        <select class="form-control" id="grupo_id"  name="grupo_id"  v-model="grupo_id" v-validate="'required|excluded:0'" data-vv-as="Grupo">
                          <option v-for="item in listaGrupos" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                        </select>
                        <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="calidad_id">Calidad</label>
                      <div class="col-md-9">
                        <select class="form-control" id="calidad_id"  name="calidad_id"  v-model="calidad_id" v-validate="'required|excluded:0'" data-vv-as="Calidad">
                          <option v-for="item in listaTipoCalidad" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                        </select>
                        <span class="text-danger">{{ errors.first('calidad_id') }}</span>
                      </div>
                    </div>
                    <!--Se eliminara la condicion v-if cuando se tenga el servidor FTP de producción-->
                    <div class="form-group row" >
                      <label class="col-md-3 form-control-label" for="ficha_tecnica">Ficha Técnica:</label>
                      <div class="col-md-6">
                        <label v-if="tipoAccion ==1" :class="ClassL_a">
                          <i class="fas fa-cloud-upload-alt"></i>&nbsp;{{BtnL_a2}}
                          <input type="file" id="file_ficha_tecnica" style="display: none;" class="form-control" accept="application/pdf" v-on:change="onChangeFichaTecnica">
                        </label>

                        <label v-for="item in tableData" v-if="(item.id == articulo_id && item.ficha_tecnica == null)" :class="ClassL_a">
                          <i class="fas fa-cloud-upload-alt"></i>&nbsp;{{BtnL_a2}}
                          <input type="file" id="file_ficha_tecnica" style="display: none;" class="form-control" accept="application/pdf" v-on:change="onChangeFichaTecnica">
                        </label>

                        <label v-for="item in tableData" v-if="(item.id == articulo_id && item.ficha_tecnica != null)" class="btn btn-success">
                          <i class="fas fa-file-download"></i>&nbsp;Descargar
                          <button type="button" style="display: none;" @click="descargar(item.ficha_tecnica)">
                          </button>
                        </label>
                        <label v-for="item in tableData" v-if="(item.id == articulo_id && item.ficha_tecnica != null)" :class="ClassL_a">
                          <i class="fas fa-retweet"></i>&nbsp;{{BtnL_a}}
                          <input type="file" id="file_ficha_tecnica" style="display: none;" class="form-control" accept="application/pdf" v-on:change="onChangeFichaTecnica">
                        </label>
                      </div>
                    </div>
                    <!--Se eliminara la condicion v-if cuando se tenga el servidor FTP de producción-->
                    <div class="form-group row" >
                      <label class="col-md-3 form-control-label" for="fotografia">Fotografía:</label>
                      <div class="col-md-6">
                        <label v-if="tipoAccion ==1" :class="ClassL_b">
                          <i class="fas fa-cloud-upload-alt"></i>&nbsp;{{BtnL_b2}}
                          <input type="file" id="file_fotografia" style="display: none;" class="form-control" accept="image/*" v-on:change="onChangeFotografia">
                        </label>

                        <label v-for="item in tableData" v-if="(item.id == articulo_id && item.fotografia == null)" :class="ClassL_b">
                          <i class="fas fa-cloud-upload-alt"></i>&nbsp;{{BtnL_b2}}
                          <input type="file" id="file_fotografia" style="display: none;" class="form-control" accept="image/*" v-on:change="onChangeFotografia">
                        </label>

                        <label v-for="item in tableData" v-if="(item.id == articulo_id && item.fotografia != null)" class="btn btn-success">
                          <i class="fas fa-file-download"></i>&nbsp;Descargar
                          <button type="button" style="display: none;" @click="descargar(item.fotografia)">
                          </button>
                        </label>
                        <label v-for="item in tableData" v-if="(item.id == articulo_id && item.fotografia != null)" :class="ClassL_b">
                          <i class="fas fa-retweet"></i>&nbsp;{{BtnL_b}}
                          <input type="file" id="file_fotografia" style="display: none;" class="form-control" accept="image/*" v-on:change="onChangeFotografia">
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>

              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="RegistrarActualizar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="RegistrarActualizar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->

    <!--Inicio del modal existencias-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalExistencias}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div>
            <div class="modal-header">
              <h4 class="modal-title">Existencias</h4>
              <button type="button" class="close" @click="cerrarModalExistencias()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <existencias ref="existencias"></existencias>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" @click="cerrarModalExistencias()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->

    <!--Inicio del modal existencias-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalKardex}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div>
            <div class="modal-header">
              <h4 class="modal-title">Movimientos</h4>
              <button type="button" class="close" @click="cerrarModalKardex()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <kardex ref="kardex"></kardex>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" @click="cerrarModalKardex()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->

    <!-- <precio ref="precio"></precio> -->



  </main>
</template>

<script>
  import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
const Existencias = r => require.ensure([], () => r(require('./Existencias.vue')), 'rh');
const Kardex = r => require.ensure([], () => r(require('./Kardex.vue')), 'rh');
// const Precio = r => require.ensure([], () => r(require('../Precio.vue')), 'alm');

export default {
  components: {
    'existencias': Existencias,
    'kardex': Kardex,
    // 'precio' : Precio,
   },
  data (){
    return {
      listUnidadesM:[], 
      PermisosCRUD : {},
      url: '/articulos',
      articulo_id: 0,
      nombre : '',
      codigo: '',
      nombreproveedor: '',
      descripcion : '',
      marca: '',
      unidad: {
        id:0,
        clave:""
      },
      comentarios: '',
      minimo: 0,
      maximo: 0,
      listaGrupos: [],
      listaCategorias: [],
      listaTipoCalidad: [],
      listaTipoResguardo:[],
      categoria_id: 0,
      grupo_id: 0,
      ficha_tecnica:'',
      fotografia:'',
      calidad_id: 0,
      descal:'',
      trid: 0,
      trnom:'',
      ClassL_a: 'btn btn-info',
      BtnL_a: 'Actualizar',
      BtnL_a2: 'Subir Archivo',
      ClassL_b: 'btn btn-info',
      BtnL_b: 'Actualizar',
      BtnL_b2: 'Subir Archivo',
      Metodo: '',
      modal : 0,
      tituloModal : '',
      eje : [],
      tipoAccion : 0,
      isLoading: false,
      isLoadingSelect : false,
      modalExistencias: 0,
      modalKardex: 0,
      modalPrecio : 0,
      columns: ['item','id', 'nombre', 'codigo','trnom','descripcion','marca', 'unidad', 'comentarios', 'minimo', 'maximo', 'grupo', 'categoria','calidad', 'condicion'],
      tableData: [],
      options: {
        headings: {
          nombre: 'Nombre',
          codigo: 'Codigo',
          trnom:'Resguardo',
          // nombreproveedor:'Descripción para proveedor',
          descripcion: 'Descripción',
          marca: 'Marca',
          unidad: 'Unidad',
          comentarios: 'Comentarios',
          minimo: 'Min',
          maximo: 'Max',
          grupo: 'Grupo',
          categoria: 'Categoria',
          calidad: 'Calidad',
          condicion: 'Estado',
          fotografia: 'Fotografia',
          id: 'Acciones',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['nombre', 'descripcion', 'codigo', 'minimo', 'maximo'],
        filterable: ['nombre', 'codigo', 'descripcion','marca', 'unidad', 'condicion', 'grupo', 'categoria','calidad'],
        filterByColumn: true,
        listColumns: {
          condicion: [{
            id: 1,
            text: 'Activo'
          },
          {
            id: 0,
            text: 'Desactivado'
          }
        ]
      },
      texts:config.texts
    },
  }
},
computed:{
},
methods : {
  getData() {
    this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
    let me=this;
    axios.get('/articulo').then(response => {
      me.tableData = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
  },
  getLista(){
    let me=this;
    axios.get('/tipocalidad').then(response =>{
      me.listaTipoCalidad = response.data;
    });
    axios.get('/tipoResguardo').then(response =>{
      me.listaTipoResguardo = response.data;
    });
    axios.get('/grupo').then(response =>{
      me.listaGrupos = response.data;
    });
  },


  onChangeFichaTecnica(e){
    var nombreL_a = e.target.files[0].name;
    var extension =  nombreL_a.split('.').pop();
    if (extension == 'pdf')
    {
      this.ClassL_a = 'btn btn-warning';
      this.ficha_tecnica = e.target.files[0];
      this.BtnL_a = 'Archivo Cargado';
      this.BtnL_a2 = 'Archivo Cargado';
    }
    else
    {
      this.ClassL_a = 'btn btn-danger';
      this.BtnL_a = 'Archivo Invalido(Omitido)';
      this.BtnL_a2 = 'Archivo Invalido(Omitido)';
    }
  },
  onChangeFotografia(e){
    var nombreL_b = e.target.files[0].name;
    var extension =  nombreL_b.split('.').pop();
    if (extension == 'png' || extension == 'jpg')
    {
      this.ClassL_b = 'btn btn-warning';
      this.fotografia = e.target.files[0];
      this.BtnL_b = 'Archivo Cargado';
      this.BtnL_b2 = 'Archivo Cargado';
    }
    else
    {
      this.ClassL_b = 'btn btn-danger';
      this.BtnL_b = 'Archivo Invalido(Omitido)';
      this.BtnL_b2 = 'Archivo Invalido(Omitido)';
    }
  },
  descargar(archivo){
    let me=this;
    axios({
      url: me.url+ '/' + archivo,
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
      axios.get(me.url + '/' + archivo + '/edit')
      .then(response => {
      //  console.log(response);
              toastr.success('Archivo Encontrado');

      })
      .catch(function (error) {
        console.log(error);
      });
      //--fin del metodo borrar--//
    });
  },
  RegistrarActualizar(nuevo){
    this.$validator.validate().then(result => {
      if (result) {
        this.isLoading = true;
        let me = this;
        let formData = new FormData();

        formData.append('metodo', this.Metodo);
        formData.append('nombre', this.nombre == null ? '' : this.nombre);
        formData.append('codigo', this.codigo == null ? '' : this.codigo);
        formData.append('marca', this.marca == null ? '' : this.marca);
        formData.append('unidad', this.unidad.nombre);
        formData.append('um_id', this.unidad.id);
        //formData.append('nombreproveedor', this.nombreproveedor);
        formData.append('descripcion', this.descripcion);
        formData.append('comentarios', this.comentarios == null ? '' : this.comentarios);
        formData.append('minimo', this.minimo == null ? '0' : this.minimo);
        formData.append('maximo', this.maximo == null ? '0' : this.maximo);
        formData.append('grupo_id', this.grupo_id == null ? '' : this.grupo_id);
        formData.append('categoria_id', this.categoria_id);
        formData.append('calidad_id', this.calidad_id) == null ? '' : this.calidad_id;
        formData.append('ficha_tecnica', this.ficha_tecnica);
        formData.append('fotografia', this.fotografia);
        formData.append('id', this.articulo_id);
        formData.append('trid', this.trid);

        axios.post(me.url,formData).then(function (response) {
          me.isLoading = false;
          if (response.data.status) {
            me.cerrarModal();
            me.getData();
            if(!nuevo){
              toastr.success('Articulo Actualizado Correctamente');
              me.cerrarModalAbrir();
            }
            else
            {
              toastr.success('Articulo Registrado Correctamente');
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

  desactivarArticulo(id){
    swal({
      title: 'Esta seguro de desactivar este Articulo?',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#4dbd74',
      cancelButtonColor: '#f86c6b',
      confirmButtonText: 'Aceptar!',
      cancelButtonText: 'Cancelar',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {
        let me = this;

        axios.put('/articulo/desactivar',{
          'id': id
        }).then(function (response) {
          toastr.error(
            'El registro ha sido desactivado con éxito.'
          );
          me.getData();
        }).catch(function (error) {
          console.log(error);
        });

      }
    })
  },
  activarArticulo(id){
    swal({
      title: 'Esta seguro de activar este Articulo?',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#4dbd74',
      cancelButtonColor: '#f86c6b',
      confirmButtonText: 'Aceptar!',
      cancelButtonText: 'Cancelar',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {
        let me = this;

        axios.put('/articulo/activar',{
          'id': id
        }).then(function (response) {
          toastr.success(
            'El registro ha sido activado con éxito.'
          );
          me.getData();
        }).catch(function (error) {
          console.log(error);
        });
      }
    })
  },
  cerrarModal(){
    this.ClassL_a = 'btn btn-info';
    this.BtnL_a = 'Actualizar';
    this.BtnL_a2 = 'Subir Archivo';
    this.ClassL_b = 'btn btn-info';
    this.BtnL_b = 'Actualizar';
    this.BtnL_b2 = 'Subir Archivo';
    //Se habilitaran en cuanto se tenga el servodor FTP para Producción//
    /*document.getElementById('file_ficha_tecnica').value='';
    document.getElementById('file_fotografia').value='';*/
    this.modal=0;
    this.tituloModal='';
    this.nombre='';
    this.descripcion='';
  },
  abrirModal(modelo, accion, data = []){
    switch(modelo){
      case "articulo":
      {
        switch(accion){
          case 'registrar':
          {
            let me=this;
            axios.get('/categoria/getlist').then(response => {
              me.listaCategorias = response.data;
            })
            .catch(function (error) {
              console.log(error);
            });
            this.modal = 1;
            this.tituloModal = 'Registrar articulo';
            this.tipoAccion = 1;
            this.nombre= '';
            this.codigo = '';
            this.descripcion = '';
            this.marca = '';
            this.unidad = '';
            this.comentarios = '';
            this.minimo = 0;
            this.maximo = 0;
            this.categoria_id = 0;
            this.grupo_id = 0;
            this.calidad_id = 0;
            this.trid = 0;
            this.Metodo = 'Nuevo';
            break;
          }
          case 'actualizar':
          {

            this.modal=1;
            this.tituloModal='Actualizar articulo';
            this.tipoAccion=2;
            this.articulo_id=data['id'];
            this.nombre = data['nombre'];
            this.codigo = data['codigo'];
            this.descripcion = data['descripcion'];
            this.marca = data['marca'];
            this.unidad=
            {
              id:data['um_id'],
              nombre:data['unidad']
            };
            this.comentarios = data['comentarios'];
            this.minimo = data['minimo'];
            this.maximo = data['maximo'];
          //  this.categoria_id = data['categoria_id'];
            this.calidad_id = data['calidad_id'];
            this.trid = data['tipo_resguardo_id'];
            this.Metodo = 'Actualizar';
            var gpo = data['grupo_id'];
            let me=this;

            axios.get('/grupo/find/' + gpo).then(response => {
              me.grupo_id = response.data.id ;
              me.categoria_id = response.data.categoria_id;
            }).catch(error => {
              console.log(error);
            })

            axios.get('/categoria/getlist').then(response => {
              me.listaCategorias = response.data;
            })
            .catch(function (error) {
              console.log(error);
            });


            break;
          }
        }
      }
    }
  },
  mostrarExistencias(data) {
    this.modalExistencias = 1;
    var childExistencias = this.$refs.existencias;
    childExistencias.cargarExistencias(data);
  },
  cerrarModalExistencias(){
    this.modalExistencias = 0;
  },
  mostrarKardex(data) {
    this.modalKardex = 1;
    var childKardex = this.$refs.kardex;
    childKardex.cargarMovimientos(data);
  },
  cerrarModalKardex(){
    this.modalKardex = 0;
  },
  onChangeCategoria() {
    let me = this;
    this.isLoadingSelect = true;
    axios.get('/grupo/getlist/' + me.categoria_id).then(response => {
      me.listaGrupos = response.data;
      me.isLoadingSelect = false;
    })
    .catch(function (error) {
      console.log(error);
    });
  },

  verprecioventa(data){
    let me = this;
    me.isLoading = true;
    me.datos = data;
    me.isLoading = false;
    axios.get('/precioventa/' + data.id).then(response => {
      swal(
        'Precio de ventas!',
        'El precio de ventas por unidad es: $'+response.data
        // 'success'
      )
    }).catch(error => {
      console.error(error);
    });
  },

  cerrarModalAbrir(){
    this.$emit('cerrarAbrir');
  },
  ObtenerUnidadesM()
  {
    axios.get("/almacen/unidadesm/obtener").then(res=>
    {
      if(res.status)
      {
        this.listUnidadesM=res.data.unidades;
        console.error(this.listUnidadesM[0]);
      }else{
        toastr.error("Error al obtener las unidades","Error");
      }
    });
  },



},
mounted() {
  this.getData();
  this.getLista();
  this.ObtenerUnidadesM();
},
}
</script>
