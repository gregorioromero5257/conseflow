<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card" v-show="!detalleu">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Unidades {{empresa == 1  ? 'CONSERFLOW' : 'CSCT'}}
          <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle float-sm-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Empresa
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2" v-model="empresa">
              <button class="dropdown-item" type="button" @click="empresa = 1;getData();">Conserflow</button>
              <button class="dropdown-item" type="button" @click="empresa = 2;getData();">Constructora</button>
            </div>
          </div>
          <button type="button" @click="abrirModal('unidad','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
          <button type="button" @click="descargarInv()" class="btn btn-dark float-sm-right">
            <i class="fas fa-download"></i>&nbsp;Descargar
          </button>

        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <!-- <button v-show="PermisosCRUD.Update" type="button" @click="abrirModal('unidad','actualizar',props.row)" class="dropdown-item">
                    <i class="icon-pencil"></i>&nbsp;Actualizar unidades.
                  </button> -->
                  <button type="button" @click="detalles(props.row)" class="dropdown-item">
                    <i class="icon-eye"></i>&nbsp;Detalles.
                  </button>
                </div>
              </div>
            </div>
          </template>
        </v-client-table>

      </div>
    </div>
    <!-- Fin ejemplo de tabla Listado -->
    <div v-show="detalleu">
      <vue-element-loading :active="isLoading"/>
      <detalleunidades ref="detalleunidades" @maestro:atras="maestro()" @detalle:actualizar="actualiza($event)"></detalleunidades>
    </div>
  </div>
  <!--Inicio del modal agregar/actualizar-->
  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dark modal-lg" role="document">
      <div class="modal-content">
        <div>
          <vue-element-loading :active="isLoading"/>
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
            <input type="hidden" name="id">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Unidad</label>
                <input type="text" v-validate="'required'" name="unidad" v-model="Unidades.unidad"  data-vv-name="Unidad" class="form-control" placeholder="Unidad" autocomplete="off" id="unidad">
                <span class="text-danger">{{ errors.first('Unidad') }}</span>
              </div>
              <div class="form-group col-md-4">
                <label>Marca:</label>
                <input type="text" v-validate="'required'" name="marca" v-model="Unidades.marca" class="form-control" placeholder="Marca" data-vv-name="Marca" id="marca">
                <span class="text-danger">{{ errors.first('Marca') }}</span>
              </div>
              <div class="form-group col-md-4">
                <label>Modelo:</label>
                <input type="text" v-validate="'required'" name="modelo" v-model="Unidades.modelo" class="form-control" data-vv-name="Modelo" placeholder="Modelo" id="modelo">
                <span class="text-danger">{{ errors.first('Modelo') }}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label>Color</label>
                <input type="text" v-validate="'required'" name="color" v-model="Unidades.color"  data-vv-name="Color" class="form-control" placeholder="Color" autocomplete="off" id="color">
                <span class="text-danger">{{ errors.first('Color') }}</span>
              </div>
              <div class="form-group col-md-3">
                <label>No.Motor</label>
                <input type="text" v-validate="'required'" name="unidad" v-model="Unidades.no_motor"  data-vv-name="No Motor" class="form-control" placeholder="No Motor" autocomplete="off" id="no_motor">
                <span class="text-danger">{{ errors.first('No Motor') }}</span>
              </div>
              <div class="form-group col-md-3">
                <label>Capacidad carga</label>
                <input type="text" v-validate="'required'" name="capacidad" v-model="Unidades.capacidad"  data-vv-name="Capacidad" class="form-control" placeholder="Capacidad" autocomplete="off" id="capacidad">
                <span class="text-danger">{{ errors.first('Capacidad') }}</span>
              </div>
              <div class="form-group col-md-3">
                <label>Cilindros</label>
                <input type="text" v-validate="'required'" name="cilindros" v-model="Unidades.cilindros"  data-vv-name="Cilindros" class="form-control" placeholder="Cilindros" autocomplete="off" id="cilindros">
                <span class="text-danger">{{ errors.first('Cilindros') }}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Año</label>
                <input type="number" v-validate="'required'" name="anio" v-model="Unidades.anio" class="form-control" data-vv-name="Año" placeholder="Año" id="anio">
                <span class="text-danger">{{ errors.first('Año') }}</span>
              </div>
              <div class="form-group col-md-4">
                <label>Placas de circulación</label>
                <input type="text" v-validate="'required'" name="placas" v-model="Unidades.placas" class="form-control" data-vv-name="Placas" placeholder="Placas" id="placas">
                <span class="text-danger">{{errors.first('Placas')}}</span>
              </div>
              <div class="form-group col-md-4">
                <label>Estado</label>
                <input type="text" v-validate="'required'" name="estado" v-model="Unidades.estado" class="form-control" data-vv-name="Estado" placeholder="Estado" id="estado">
                <span class="text-danger">{{errors.first('Estado')}}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Número de serie</label>
                <input type="text" v-model="Unidades.numero_serie" v-validate="'required'" data-vv-name="Número de serie" class="form-control">
                <span class="text-danger">{{ errors.first('Número de serie')}}</span>
              </div>
              <div class="form-group col-md-4">
                <label>Clase y tipo</label>
                <select class="form-control" v-validate="'required'" data-vv-name="Clase-Tipo" v-model="Unidades.clase_tipo">
                  <option v-for="item in clase_tipo" :value="item.id" :key="item.id">{{item.clase_tipo}}</option>
                </select>
                <span class="text-danger">{{errors.first('Clase-Tipo')}}</span>
              </div>
              <div class="form-group col-md-4">
                <label>Combustible</label>
                <select class="form-control" v-validate="'required'" data-vv-name="Combustible" v-model="Unidades.combustible">
                  <option v-for="item in combustible" :value="item.id" :key="item.id">{{item.nombre}}</option>
                </select>
                <span class="text-danger">{{errors.first('Combustible')}}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Verificación 1er. semestre</label>
                <select class="form-control" name="primer_semestre" id="primer_semestre" v-model="Unidades.primer_semestre" :disabled="Unidades.excento">
                  <option value="" selected>--Seleccione--</option>
                  <option value="Enero-Febrero">Enero-Febrero</option>
                  <option value="Febrero-Marzo">Febrero-Marzo</option>
                  <option value="Marzo-Abril">Marzo-Abril</option>
                  <option value="Abril-Mayo">Abril-Mayo</option>
                  <option value="Mayo-Junio">Mayo-Junio</option>
                </select>
              </div>
              <div class="form-group col-md-4">
                <label>Verificación 2do. semestre</label>
                <select class="form-control" name="segundo_semestre" id="segundo_semestre" v-model="Unidades.segundo_semestre" :disabled="Unidades.excento">
                  <option value="" selected>--Seleccione--</option>
                  <option value="Julio-Agosto">Julio-Agosto</option>
                  <option value="Agosto-Septiembre">Agosto-Septiembre</option>
                  <option value="Septiembre-Octubre">Septiembre-Octubre</option>
                  <option value="Octubre-Noviembre">Octubre-Noviembre</option>
                  <option value="Noviembre-Diciembre">Noviembre-Diciembre</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                <label>Excento</label>
                <input type="checkbox" class="form-control" value="excento" v-model="Unidades.excento" @change="excento()">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Número tarjeta circulación</label>
                <input type="text" v-validate="'required'" name="numero_tarjeta_circulacion" v-model="Unidades.numero_tarjeta_circulacion" class="form-control" data-vv-name="Número tarjeta circulación" placeholder="Número tarjeta circulación" id="numero_tarjeta_circulacion">
                <span class="text-danger">{{errors.first('Número tarjeta circulación')}}</span>
              </div>
              <div class="form-group col-md-4">
                <label>Tarjeta</label>
                <input type="file" class="form-control" data-vv-name="tarjeta" name="tarjeta" id="tarjeta" @change="onChangeT($event)">
                <span class="text-danger">{{errors.first('tarjeta')}}</span>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-8">
                <label>Comentarios</label>
                <input type="text" name="comentarios" v-model="Unidades.comentarios" class="form-control" data-vv-name="Comentarios" placeholder="Comentarios" id="comentarios">
                <span class="text-danger">{{errors.first('Comentarios')}}</span>
              </div>
              <div class="form-group col-md-4">
                <label>Factura</label>
                <input type="file" class="form-control" data-vv-name="Factura" name="factura" id="factura" @change="onChange($event)">
                <span class="text-danger">{{errors.first('Factura')}}</span>
              </div>
            </div>
            <div class="form-group" >
              Propio
              <label class="switch switch-default switch-pill switch-dark">
                <input type="radio" class="switch-input" data-vv-name="Tipo" name="customRadioInline1" v-model="Unidades.tipo" @change="formpropio($event)" v-validate="'required'">
                <span class="switch-label"></span>
                <span class="switch-handle"></span>
              </label>
              &nbsp;
              Rentado
              <label class="switch switch-default switch-pill switch-dark">
                <input type="radio" class="switch-input" data-vv-name="Tipo" name="customRadioInline1" v-model="Unidades.tipo" @change="formrentado($event)" v-validate="'required'">
                <span class="switch-label"></span>
                <span class="switch-handle"></span>
              </label>
              <span class="text-danger">{{errors.first('Tipo')}}</span>
            </div>
            <template v-if="tipo == 1">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>Propietario</label>
                  <input type="text" name="propietario" v-model="Unidades.propietario" class="form-control" data-vv-name="Propietario" placeholder="Propietario" id="propietario">
                  <span class="text-danger">{{errors.first('Propietario')}}</span>
                </div>
              </div>
            </template>
            <template v-if="tipo == 2">
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>Proveedor</label>
                  <input type="text" name="proveedor" v-model="Unidades.proveedor" class="form-control" data-vv-name="Proveedor" placeholder="Proveedor" id="proveedor">
                  <span class="text-danger">{{errors.first('Proveedor')}}</span>
                </div>
                <div class="form-group col-md-4">
                  <label>Tiempo</label>
                  <input type="text" name="tiempo" v-model="Unidades.tiempo" class="form-control" data-vv-name="Tiempo de renta" placeholder="Tiempo de renta" id="tiempo">
                  <span class="text-danger">{{errors.first('Tiempo de renta')}}</span>
                </div>
                <div class="form-group col-md-4">
                  <label>Costo Renta</label>
                  <input type="text" v-validate="'decimal:2'" name="costo_renta" v-model="Unidades.costo_renta" class="form-control" data-vv-name="Costo Renta" placeholder="Costo Renta" id="costo_renta">
                  <span class="text-danger">{{errors.first('Costo Renta')}}</span>
                </div>
              </div>
            </template>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="registrar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="registrar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal-->


</main>
</template>

<script>
const DetalleUnidades = r => require.ensure([], () => r(require('./DetalleUnidades.vue')), 'trafico');
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      empresa : 1,
      url: '/UnidadesStore',
      PermisosCRUD:{},
      tipo : 0,
      Unidades: {
        id: 0,
        unidad: '',
        marca : '',
        modelo : '',
        anio : '',
        placas: '',
        estado : '',
        comentarios : '',
        tipo : '',
        propietario : '',
        proveedor : '',
        tiempo : '',
        costo_renta : '',
        factura : '',
        clase_tipo: '',
        combustible : '',
        numero_tarjeta_circulacion : '',
        tarjeta : '',
        excento : false,
        segundo_semestre : '',
        primer_semestre  : '',
        numero_serie : '',
        color : '',
        no_motor : '',
        capacidad : '',
        cilindros : '',
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
  filters: {
    uppercase: function (str) {
      return str.toUpperCase() //Retornar el string con el method toUpperCase (mayúscula)
    }
  },
  computed:{
  },
  components :{
    'detalleunidades' : DetalleUnidades,
  },
  methods : {
    getData() {
      this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
      let me=this;
      me.tableData = [];
      axios.get(me.url).then(response => {
        response.data.forEach((item, i) => {
          if (item.empresa === me.empresa) {
            me.tableData.push(item);
          }
        });
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getUnidades() {
      let me=this;
      axios.get(me.url + '/create').then(response => {
        me.tableUnidades = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getCatalogos(){
      let me = this;
      var array = [];
      axios.get('catalogostrafico').then(response => {
        me.catalogotrafico = response.data;
      }).catch(err => {
        console.log(err);
      });

      axios.get('catalogoclasetipo').then(response => {
        me.clase_tipo = response.data;
      }).catch(err => {
        console.error(err);
      });

      axios.get('catalogocombustible').then(response => {
        me.combustible = response.data;
      }).catch(err => {
        console.error(err);
      });
    },



    onChange(e){
        this.Unidades.factura = e.target.files[0];
    },

    onChangeT(e){
        this.Unidades.tarjeta = e.target.files[0];
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
        })
        .catch(function (error) {
          console.log(error);
        });
        //--fin del metodo borrar--//
      });
    },

    registrar(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          let formData = new FormData();
          formData.append('metodo', this.Metodo);
          formData.append('unidad', this.Unidades.unidad);
          formData.append('marca', this.Unidades.marca);
          formData.append('modelo', this.Unidades.modelo);
          formData.append('anio', this.Unidades.anio);
          formData.append('placas', this.Unidades.placas);
          formData.append('estado', this.Unidades.estado);
          formData.append('comentarios', this.Unidades.comentarios);
          formData.append('tipo', this.Unidades.tipo);
          formData.append('factura', this.Unidades.factura);
          formData.append('propietario', this.Unidades.propietario);
          formData.append('proveedor', this.Unidades.proveedor);
          formData.append('tiempo', this.Unidades.tiempo);
          formData.append('costo_renta', this.Unidades.costo_renta);
          formData.append('clase_tipo', this.Unidades.clase_tipo);
          formData.append('combustible', this.Unidades.combustible);
          formData.append('numero_tarjeta_circulacion',this.Unidades.numero_tarjeta_circulacion);
          formData.append('tarjeta',this.Unidades.tarjeta);
          formData.append('id', this.Unidades.id);
          formData.append('primer_semestre',this.Unidades.primer_semestre);
          formData.append('segundo_semestre',this.Unidades.segundo_semestre);
          formData.append('excento',this.Unidades.excento);
          formData.append('empresa',this.empresa);
          formData.append('numero_serie',this.Unidades.numero_serie);
          formData.append('color',this.Unidades.color);
          formData.append('no_motor',this.Unidades.no_motor);
          formData.append('capacidad',this.Unidades.capacidad);
          formData.append('cilindros',this.Unidades.cilindros);
          axios.post(me.url,formData).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              if(!nuevo){
                toastr.success('Unidad Actualizada Correctamente');
                me.cerrarModal();
                me.getData();
              }
              else
              {
                toastr.success('Unidad Registrado Correctamente');
                me.cerrarModal();
                me.getData();
              }
            }
            else {
              swal({
                type: 'error',
                html: response.data.errors.join('<br>'),
              });
            }
          }).catch(function (error) {
            console.log(error);
          });
        }else {
          toastr.warning('Complete todos los campos');
        }
      });
    },

    readFile:function(e){
      var f=e.dataTransfer.files;
    },

    cerrarModal(){
      this.getUnidades();
      this.modal=0;
      this.tituloModal='';
      Utilerias.resetObject(this.Unidades);
    },

    abrirModal(modelo, accion, data = []){
      switch(modelo){
        case "unidad":
        {
          switch(accion){
            case 'registrar':
            {
              this.modal = 1;
              this.tableUnidades = '';
              this.Metodo = 'Nuevo';
              this.tituloModal = 'Registrar Nueva Unidad';
              Utilerias.resetObject(this.Unidades);
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar':
            {
              // console.log(data);
              this.tipoAccion=2;
              this.Unidades.id=data['id'];
              this.Unidades.unidad = data['unidad'];
              this.Unidades.marca = data['marca'];
              this.Unidades.modelo = data['modelo'];
              this.Unidades.anio = data['anio'];
              this.Unidades.placas = data['placas'];
              this.Unidades.estado = data['estado'];
              this.Unidades.comentarios = data['comentarios'];
              this.Unidades.tipo = data['tipo'];
              this.Unidades.factura = data['factura'];
              this.Unidades.propietario = data['propietario'];
              this.Unidades.proveedor = data['proveedor'];
              this.Unidades.tiempo = data['tiempo'];
              this.Unidades.costo_renta = data['costo_renta'];
              this.Unidades.clase_tipo = data['clase_tipo'];
              this.Unidades.combustible = data['combustible'];
              this.Unidades.numero_tarjeta_circulacion = data['numero_tarjeta_circulacion'];
              this.Unidades.tarjeta = data['tarjeta'];
              this.Unidades.excento = data['excento'];
              this.Unidades.primer_semestre = data['primer_semestre'];
              this.Unidades.segundo_semestre = data['segundo_semestre'];
              this.Unidades.numero_serie = data['numero_serie'];
              this.Unidades.color = data['color'];
              this.Unidades.no_motor = data['no_motor'];
              this.Unidades.capacidad = data['capacidad'];
              this.Unidades.cilindros = data['cilindros'];
              // this.Unidades.empresa =
              this.Metodo = 'Actualizar';
              break;
            }
          }
        }
      }
    },

    detalles(data){
      this.detalleu = true;
      var ChildDetalle = this.$refs.detalleunidades;
      ChildDetalle.cargarDatos(data,this.catalogotrafico,this.clase_tipo,this.combustible);
    },

    maestro(){
      this.detalleu = false;
    },

    actualiza(data){
      this.abrirModal('unidad','actualizar',data);
      this.registrar(0);
    },


    formpropio(){
      this.tipo = 1;
      this.Unidades.tipo = 1;
    },

    formrentado(){
      this.tipo = 2;
      this.Unidades.tipo = 2;
    },

    excento(){
      this.Unidades.primer_semestre = '';
      this.Unidades.segundo_semestre = '';
    },

    descargarInv(){
      window.open('trafico/descargar/inventario/', '_blank');
    },

  },
  mounted() {
    this.getData();
    this.getUnidades();
    this.getCatalogos();
  }
}
</script>
