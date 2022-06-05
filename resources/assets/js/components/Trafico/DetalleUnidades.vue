<template>
  <div >
    <!-- <h2>Dynamic Tabs</h2> -->

    <ul class="nav nav-tabs" role="tablist" ref="tabs">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#home" role="tab" @click="setId(0)"><i class="fas fa-book"></i>&nbsp;Generales</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#menu1" role="tab" @click="setId(1)"><i class="fas fa-tools"></i>&nbsp;Servicios</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#menu2" role="tab" @click="setId(2)"><i class="fas fa-certificate"></i>&nbsp;Polizas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#menu3" role="tab" @click="setId(3)"><i class="fas fa-tasks"></i>&nbsp;Verificación</a>
      </li>
    <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu4" role="tab" @click="setId(4)"><i class="fas fa-sticky-note"></i>&nbsp;Tenencia</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" data-toggle="tab" href="#menu5" role="tab" @click="setId(5)"><i class="fas fa-sticky-note"></i>&nbsp;Fotos</a>
</li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab"  role="tab"  @click="maestro()"><i class="fa fa-arrow-left"></i>&nbsp;Atras</a>
  </li>
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active show" v-show="tab == 0">
    <div class="card" style="border : 0">
      <div class="form-row">
        <div class="form-group col-md-4">
          <label>Unidad</label>
          <input type="text" v-validate="'required'" name="unidad" v-model="Unidades.unidad" class="form-control" placeholder="Unidad" data-vv-name="Unidad" autocomplete="off" id="unidad_actualizar">
          <span class="text-danger">{{ errors.first('Unidad') }}</span>
        </div>
        <div class="form-group col-md-4">
          <label>Marca:</label>
          <input type="text" v-validate="'required'" name="marca" v-model="Unidades.marca" class="form-control" placeholder="Marca" data-vv-name="Marca" id="marca_actualizar">
          <span class="text-danger">{{ errors.first('Marca') }}</span>
        </div>
        <div class="form-group col-md-4">
          <label>Modelo:</label>
          <input type="text" v-validate="'required'" name="modelo" v-model="Unidades.modelo" class="form-control" data-vv-name="Modelo" placeholder="Modelo" id="modelo_actualizar">
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
          <input type="number" v-validate="'required'" name="anio" v-model="Unidades.anio" class="form-control" data-vv-name="Año" placeholder="Año" id="anio_actualizar">
          <span class="text-danger">{{ errors.first('Año') }}</span>
        </div>
        <div class="form-group col-md-4">
          <label>Placas de criculación</label>
          <input type="text" v-validate="'required'" name="placas" v-model="Unidades.placas" class="form-control" data-vv-name="Placas" placeholder="Placas" id="placas_actualizar">
          <span class="text-danger">{{errors.first('Placas')}}</span>
        </div>
        <div class="form-group col-md-4">
          <label>Estado</label>
          <input type="text" v-validate="'required'" name="estado" v-model="Unidades.estado" class="form-control" data-vv-name="Estado" placeholder="Estado" id="estado_actualizar">
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
          <select class="form-control" name="primer_semestre_actualizar" id="primer_semestre" v-model="Unidades.primer_semestre" :disabled="Unidades.excento">
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
          <select class="form-control" name="segundo_semestre_actualizar" id="segundo_semestre" v-model="Unidades.segundo_semestre" :disabled="Unidades.excento">
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
          <input type="text" v-validate="'required'" name="numero_tarjeta_circulacion_actualizar" v-model="Unidades.numero_tarjeta_circulacion" class="form-control" data-vv-name="Número tarjeta circulación" placeholder="Número tarjeta circulación" id="numero_tarjeta_circulacion_actualizar">
          <span class="text-danger">{{errors.first('Número tarjeta circulación')}}</span>
        </div>

        <template v-if="Unidades.tarjeta != ''">
          <div class="form-group col-md-2">
            <label>&nbsp;</label>
            <button type="button" class="form-control" @click="descargar(Unidades.tarjeta)">
              <i class="fas fa-file-download"></i>&nbsp;Descargar
            </button>
          </div>
          <div class="form-group col-md-2">
            <label>&nbsp;</label>
            <button type="button" class="form-control" @click="actualizaT()">
              <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
            </button>
          </div>
        </template>
        <template v-if="Unidades.tarjeta == ''">
          <div class="form-group col-md-4">
            <label>Tarjeta</label>
            <input type="file" class="form-control" data-vv-name="tarjeta" name="tarjeta_actualizar" id="tarjeta_actualizar" @change="onChangeT($event)">
            <span class="text-danger">{{errors.first('tarjeta')}}</span>
          </div>
        </template>
      </div>

      <div class="form-row">
        <div class="form-group col-md-8">
          <label>Comentarios</label>
          <input type="text" name="comentarios_actualizar" v-model="Unidades.comentarios" class="form-control" data-vv-name="Comentarios" placeholder="Comentarios" id="comentarios_actualizar">
          <span class="text-danger">{{errors.first('Comentarios')}}</span>
        </div>
        <template v-if="Unidades.factura != ''">
          <div class="form-group col-md-2">
            <label>&nbsp;</label>
            <button type="button" class="form-control" @click="descargar(Unidades.factura)">
              <i class="fas fa-file-download"></i>&nbsp;Descargar
            </button>
          </div>
          <div class="form-group col-md-2">
            <label>&nbsp;</label>
            <button type="button" class="form-control" @click="actualiza()">
              <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
            </button>
          </div>
        </template>
        <template v-if="Unidades.factura == ''">
          <div class="form-group col-md-4">
            <label>Factura</label>
            <input type="file" class="form-control" name="factura_actualizar" id="factura_actualizar" data-vv-name="Factura" @change="onChange($event)">
            <span class="text-danger">{{errors.first('Factura')}}</span>
          </div>
        </template>

      </div>
      <div class="form-group" >
        Propio
        <label class="switch switch-default switch-pill switch-dark">
          <input type="radio" class="switch-input" id="propio" name="customRadioInline1" @change="formpropio($event)">
          <span class="switch-label"></span>
          <span class="switch-handle"></span>
        </label>
        &nbsp;
        Rentado
        <label class="switch switch-default switch-pill switch-dark">
          <input type="radio" class="switch-input" id="rentado" name="customRadioInline1" @change="formrentado($event)">
          <span class="switch-label"></span>
          <span class="switch-handle"></span>
        </label>
      </div>
      <template v-if="tipo == 1">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label>Propietario</label>
            <input type="text" name="propietario" v-model="Unidades.propietario" class="form-control" data-vv-name="Propietario" placeholder="Propietario" id="propietario_actualizar">
            <span class="text-danger">{{errors.first('Propietario')}}</span>
          </div>
        </div>
      </template>
      <template v-if="tipo == 2">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label>Proveedor</label>
            <input type="text" name="proveedor" v-model="Unidades.proveedor" class="form-control" data-vv-name="Proveedor" placeholder="Proveedor" id="proveedor_actualizar">
            <span class="text-danger">{{errors.first('Proveedor')}}</span>
          </div>
          <div class="form-group col-md-4">
            <label>Tiempo</label>
            <input type="text" name="tiempo" v-model="Unidades.tiempo" class="form-control" data-vv-name="Tiempo de renta" placeholder="Tiempo de renta" id="tiempo_actualizar">
            <span class="text-danger">{{errors.first('Tiempo de renta')}}</span>
          </div>
          <div class="form-group col-md-4">
            <label>Costo Renta</label>
            <input type="text" v-validate="'decimal:2'" name="costo_renta" v-model="Unidades.costo_renta" class="form-control" data-vv-name="Costo Renta" placeholder="Costo Renta" id="costo_renta_actualizar">
            <span class="text-danger">{{errors.first('Costo Renta')}}</span>
          </div>
        </div>
      </template>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="registrar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
      </div>
    </div>


  </div>
  <div id="menu1" class="tab-pane fade" v-show="tab == 1">
    <servicios ref="servicios"></servicios>
  </div>
  <div id="menu2" class="tab-pane fade" v-show="tab == 2">
    <h4>Registro de Polizas</h4>
    <poliza ref="poliza"></poliza>
  </div>
  <div id="menu3" class="tab-pane fade" v-show="tab == 3">
    <h4>Registro de verificacion</h4>
    <verificacion ref="verificacion"></verificacion>
  </div>
  <div id="menu4" class="tab-pane fade" v-show="tab == 4">
    <h4>Tenencia</h4>
    <tenencia ref="tenencia"></tenencia>
  </div>
  <div id="menu5" class="tab-pane fade" v-show="tab == 5">
    <div class="form-row">
      <div class="col-md-10">
        <h4>Fotos</h4>
      </div>
      <div class="col-md-2">
        <button type="button" @click="guardarFoto()" class="btn btn-dark float-sm-right">
          <i class="fas fa-plus"></i>&nbsp;Nuevo
        </button>
      </div>
    </div>
    <fotos ref="fotos"></fotos>
  </div>
</div>
</div>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
const Servicios = r => require.ensure([], () => r(require('./Servicio.vue')), 'trafico');
const Poliza = r => require.ensure([], () => r(require('./Poliza.vue')), 'trafico');
const Verificacion = r => require.ensure([], () => r(require('./Verificacion.vue')), 'trafico');
// const Control = r => require.ensure([], () => r(require('./Control.vue')), 'trafico');
const Tenencia = r => require.ensure([], () => r(require('./Tenencia.vue')), 'trafico');
const Fotos = r => require.ensure([], () => r(require('./Fotos.vue')), 'trafico');


export default{

  data(){
    return {
      Unidades : {
        id : '',
        unidad : '',
        marca : '',
        modelo : '',
        anio : '',
        placas : '',
        estado : '',
        comentarios : '',
        tipo : '',
        propietario : '',
        proveedor : '',
        tiempo: '',
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
      tipo : 0,
      catalogotrafico : [],
      clase_tipo : [],
      combustible : [],
      tab :1,
    }
  },
  components : {
    'servicios' : Servicios,
    'verificacion' : Verificacion,
    'poliza' : Poliza,
    'tenencia' : Tenencia,
    'fotos' : Fotos,
  },
  methods : {
    cargarDatos(data, catalogotrafico, catalogoclasetipo, catalogocombustible){
      console.log(data);
      this.tab = 0;
      this.Unidades.id = data.id;
      this.Unidades.unidad = data.unidad;
      this.Unidades.marca = data.marca;
      this.Unidades.modelo = data.modelo;
      this.Unidades.anio = data.anio;
      this.Unidades.placas = data.placas;
      this.Unidades.estado = data.estado;
      this.Unidades.comentarios = data.comentarios;
      this.Unidades.tipo = data.tipo;
      this.Unidades.propietario = data.propietario;
      this.Unidades.proveedor = data.proveedor;
      this.Unidades.tiempo = data.tiempo;
      this.Unidades.costo_renta = data.costo_renta;
      this.Unidades.factura = data.factura;
      this.Unidades.clase_tipo = data.clase_tipo;
      this.Unidades.combustible = data.combustible;
      this.Unidades.primer_semestre = data.primer_semestre;
      this.Unidades.segundo_semestre = data.segundo_semestre;
      this.Unidades.excento = data.excento == 0 ? false :true;
      this.Unidades.numero_tarjeta_circulacion = data.numero_tarjeta_circulacion;
      this.Unidades.tarjeta = data.tarjeta;
      this.Unidades.numero_serie = data.numero_serie;
      this.catalogotrafico = catalogotrafico;
      this.clase_tipo = catalogoclasetipo;
      this.combustible = catalogocombustible;
      this.Unidades.color = data.color;
      this.Unidades.no_motor = data.no_motor;
      this.Unidades.capacidad = data.capacidad;
      this.Unidades.cilindros = data.cilindros;
      if (data.tipo == 1) {
        $("#propio").prop("checked", true);
        this.formpropio();
      }else if (data.tipo == 2) {
        this.formrentado();
        $("#rentado").prop("checked", true);
      }

    },

    maestro(){
      this.tab = 0;
      this.$emit('maestro:atras');
      // axios.get('eliminar/unidades/foto/' + this.Unidades.id).then(response => {
      //   console.log('OK');
      // }).catch(e => {
      //   console.error(e);
      // });
    },

    registrar(){

      this.$emit('detalle:actualizar',this.Unidades);

    },

    setId(id){
      this.tab = id;
      var ChildServicio = this.$refs.servicios;
      var ChildPoliza = this.$refs.poliza;
      var ChildVerficacion = this.$refs.verificacion;
      var ChildTenencia = this.$refs.tenencia;
      var ChildFotos = this.$refs.fotos;

      if(id == 1){
        ChildServicio.getId(this.Unidades.id);
        ChildServicio.setCatalogo(this.catalogotrafico);
      }
      if (id == 2) {
        ChildPoliza.getId(this.Unidades.id);
      }
      if (id == 3) {
        ChildVerficacion.getId(this.Unidades.id);
      }
      if (id == 4) {
        ChildTenencia.getId(this.Unidades.id);
      }
      if (id == 5) {
        ChildFotos.getId(this.Unidades.id);
      }
    },

    formpropio(){
      this.tipo = 1;
      this.Unidades.tipo = 1;
    },

    formrentado(){
      this.tipo = 2;
      this.Unidades.tipo = 2;
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
        url: '/UnidadesStore/' + archivo,
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
        axios.get('/UnidadesStore/' + archivo + '/edit')
        .then(response => {
        })
        .catch(function (error) {
          console.log(error);
        });
        //--fin del metodo borrar--//
      });
    },

    actualiza(){
      this.Unidades.factura = '';
    },

    actualizaT(){
      this.Unidades.tarjeta = '';
    },

    excento(){
      this.Unidades.primer_semestre = '';
      this.Unidades.segundo_semestre = '';
    },

    guardarFoto(){

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

          let formData = new FormData();
          formData.append('unidad_id',this.Unidades.id);
          formData.append('imagen',result.value);

          axios({
            method : 'POST',
            url : 'vehiculos/subir/foto/unidad',
            data :formData,
          }).then(response => {
            toastr.success('Correcto');
            this.isLoading = false;
          }).catch(e => {
            this.isLoading = true;
            toastr.success('Error');
            console.error(e);
          });
        }
      });

    },

  },
  mounted(){

  }
}

</script>
