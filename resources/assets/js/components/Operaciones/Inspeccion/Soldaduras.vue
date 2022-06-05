<template>
  <main class="main">
    <div class="container-fluid">
      <br>
      <div class="card" v-show="detalle == 1">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Inspeccion
          <button type="button" @click="abrirModal('soldaduras','registrar')" v-show="proyecto != ''" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
          <div class="dropdown float-sm-right">
            <button type="button" class="btn btn-secondary dropdown-toggle" id="dropmenub" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" name="button">
              Proyecto
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdown" >
              <div >
                <button class="dropdown-item" @click="cargarInspeccion(1)">Valle de México</button>
                <button class="dropdown-item" @click="cargarInspeccion(2)">Puebla</button>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <v-client-table ref="myTable" :columns="columns" :data="data" :options="options">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <template>
                      <button type="button" @click="abrirModal('soldaduras','actualizar', props.row)" class="dropdown-item">
                        <i class="icon-pencil"></i>&nbsp;Actualizar
                      </button>
                      <button type="button" @click="agregar(props.row)" class="dropdown-item">
                        <i class="icon-eye"></i>&nbsp;Agregar conceptos
                      </button>
                      <button type="button" @click="descargar_tra(props.row.id)" class="dropdown-item">
                        <i class="icon-eye"></i>&nbsp;Descargar Trasabilidad
                      </button>
                      <button type="button" @click="descargar_ins(props.row.id)" class="dropdown-item">
                        <i class="icon-eye"></i>&nbsp;Descargar Inspeccion
                      </button>
                    </template>
                  </div>
                </div>
              </div>
            </template>
          </v-client-table>
        </div>
      </div>

      <div v-show="detalle == 2">
        <conceptos ref="conceptos" @maestro:conceptos="maestro()"></conceptos>
      </div>
    </div>

    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">

            <!-- <div class="form-row">
            <div class="form-group col-md-8">
            <label>Proyecto</label>
            <select class="form-control" v-model="proyecto" name="proyecto">
            <option value="TERMINAL DE PETROLÍFEROS VALLE DE MÉXICO">TERMINAL DE PETROLÍFEROS VALLE DE MÉXICO</option>
            <option value="TERMINAL DE PETROLÍFEROS PUEBLA">TERMINAL DE PETROLÍFEROS PUEBLA</option>
          </select>
        </div>

      </div> -->
      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Lugar de fabricación</label>
          <input type="text" class="form-control" name="lugar_fabricacion" v-model="lugar_fabricacion">
        </div>
        <div class="form-group col-md-3">
          <label>Fecha</label>
          <input type="date" v-model="inspeccion.fecha" name="fecha" class="form-control">
        </div>
        <div class="form-group col-md-3">
          <label>N° de reporte</label>
          <input type="text" class="form-control" name="num_reporte" v-model="inspeccion.num_reporte">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Procedimento WPS</label>
          <input type="text" name="procedimento_wps" class="form-control" v-model="inspeccion.procedimento_wps">
        </div>
        <div class="form-group col-md-6">
          <label>Procedimento PQR</label>
          <input type="text" name="procedimento_pqr" class="form-control" v-model="inspeccion.procedimento_pqr">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Número de plano</label>
          <input type="text" name="numero_plano" class="form-control" v-model="inspeccion.numero_plano">
        </div>
        <div class="form-group col-md-6">
          <label>Elemento</label>
          <select class="form-control" name="elemento" v-model="inspeccion.elemento">
            <option v-for="elem in servicios" :key="elem.id" :value="elem.id">{{elem.nombre}}</option>
          </select>
          <!-- <input type="text" name="elemento" class="form-control" v-model="inspeccion.elemento"> -->
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-3">
          <label>Distancia al Objeto</label>
          <input type="text" class="form-control" name="distancia_al_objeto" v-model="inspeccion.distancia_al_objeto">
        </div>
        <div class="form-group col-md-4">
          <label>Angulo aproximado a la superficie</label>
          <input type="text" class="form-control" name="angulo_aprox_superficie" v-model="inspeccion.angulo_aprox_superficie">
        </div>
        <div class="form-group col-md-5">
          <label>Condicción Superficial</label>
          <input type="text" class="form-control" name="condicion_superficial" v-model="inspeccion.condicion_superficial">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Superviso (Conserflow)</label>
          <input type="text" class="form-control" v-model="supervisor" name="supervisor">
        </div>
        <div class="form-group col-md-6">
          <label>Inspeccionó (Conserflow)</label>
          <input type="text" class="form-control" v-model="inspecciono" name="inspeccion">
        </div>
        <div class="form-group col-md-6">
          <label>Supervisión (Emerson)</label>
          <input type="text" class="form-control" v-model="supervision" name="supervision">
        </div>
      </div>





    </div>
    <div class="modal-footer">
      <vue-element-loading :active="isLoading"/>
      <button type="button" class="btn btn-outine-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
      <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarinspeccion(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
      <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarinspeccion(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
    </div>
  </div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<!--Fin del modal-->
</main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Conceptos = r => require.ensure([], () => r(require('./Conceptos.vue')), 'opera');

export default {
  data(){
    return {
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      detalle : 1,
      isLoading : false,
      supervisor : 'ING. EMILIO JIMENEZ TOLEDO',
      inspecciono : 'TEC. DAN LEVI LINO REYES',
      supervision :'',
      inspeccion : {
        id : '',
        condicion_superficial : '',
        angulo_aprox_superficie :'',
        distancia_al_objeto : '',
        elemento : '',
        numero_plano : '',
        procedimento_pqr : '',
        procedimento_wps : '',
        num_reporte : '',
        fecha : '',
      },
      datos_examinacion_id : '',
      lugar_fabricacion : 'TEHUACÁN PUEBLA',
      proyecto : '',
      servicios : [],
      proyec : 0,
      columns : ['id','proyecto','lugar_fabricacion','fecha','num_reporte','nombre_si'],
      data : [],
      options : {
        headings: {
          'id' :'Acciones',
          'proyecto' : 'Proyecto',
          'num_reporte' : '# reporte',
          'nombre_si' : 'Elemento',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['proyecto','lugar_fabricacion','fecha','num_reporte','nombre_si'],
        filterable: ['proyecto','lugar_fabricacion','fecha','num_reporte','nombre_si'],
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  components : {
    'conceptos' : Conceptos,
  },
  methods: {
    getData(){
      axios.get('/servicioselementos').then((response) => {
        this.servicios = response.data;
      }).catch((err) => {console.error(err);})
    },

    cargarInspeccion(estado){
      this.proyec = estado;
      estado == 1 ? this.proyecto = 'TERMINAL DE PETROLÍFEROS VALLE DE MÉXICO' : estado == 2 ? this.proyecto = 'TERMINAL DE PETROLÍFEROS PUEBLA' : '';
      axios.get('/inspeccion/proyecto/' + estado).then((response) => {
        this.data = response.data;
      }).catch((err) => {console.error(err);})
    },

    abrirModal(modelo, accion, data){
      switch (modelo) {
        case 'soldaduras':
        {
          switch (accion) {
            case 'registrar':
            {
              this.modal = 1;
              this.tituloModal = 'Agregar Inspección';
              Utilerias.resetObject(this.inspeccion);
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar' :
            {
              this.modal = 1;
              this.tituloModal = 'Actualizar Inspección';
              Utilerias.resetObject(this.inspeccion);
              this.tipoAccion = 2;
              this.inspeccion.id = data['id'];
              this.proyecto = data['proyecto'];
              this.inspeccion.fecha= data['fecha'];
              this.inspeccion.num_reporte= data['num_reporte'];
              this.inspeccion.procedimento_wps= data['procedimento_wps'];
              this.inspeccion.procedimento_pqr= data['procedimento_pqr'];
              this.inspeccion.numero_plano= data['numero_plano'];
              this.inspeccion.elemento= data['elemento_servicios_id'];
              this.inspeccion.distancia_al_objeto= data['distancia_al_objeto'];
              this.inspeccion.angulo_aprox_superficie= data['angulo_aprox_superficie'];
              this.inspeccion.condicion_superficial= data['condicion_superficial'];
              this.supervisor= data['supervisor'];
              this.inspecciono= data['inspecciono'];
              this.supervision= data['supervision'];
              this.datos_examinacion_id = data['datos_examinacion_id'];
              console.log(data);
              break;
            }
          }
        }
      }
    },

    cerrarModal()
    {
      this.modal = 0;
    },

    guardarinspeccion(nuevo){
      this.$validator.validate().then((result) => {
        if(result){
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? '/inspeccion' : '/inspeccion/update/'+this.inspeccion.id,
            data: {
              // 'nombre': this.clientes.nombre.toUpperCase(),
              'proyecto' : this.proyecto,
              'lugar_fabricacion' : 'TEHUACÁN PUEBLA',
              'fecha' : this.inspeccion.fecha,
              'num_reporte' : this.inspeccion.num_reporte,
              'procedimento_wps' : this.inspeccion.procedimento_wps,
              'procedimento_pqr' : this.inspeccion.procedimento_pqr,
              'numero_plano' : this.inspeccion.numero_plano,
              'elemento_servicios_id' : this.inspeccion.elemento,
              'distancia_al_objeto' : this.inspeccion.distancia_al_objeto,
              'angulo_aprox_superficie' :this.inspeccion.angulo_aprox_superficie,
              'condicion_superficial' : this.inspeccion.condicion_superficial,
              'supervisor' : this.supervisor,
              'inspecciono' : this.inspecciono,
              'supervision' : this.supervision,
              'datos_examinacion_id' : this.datos_examinacion_id,
            }
          }).then(function (response) {
            me.cerrarModal();
            me.isLoading = false;
            me.cargarInspeccion(me.proyec);
          }).catch(function (error) {
            console.log(error);
          });
        }
      });
    },

    agregar(data){
      this.detalle = 2;
      var childConceptos = this.$refs.conceptos;
      childConceptos.getData(data);
    },

    maestro(){
      this.detalle = 1;
    },

    descargar_tra(data){
      window.open('trazabilidad/' + data, '_blank');

    },

    descargar_ins(data){
      window.open('visual/' + data, '_blank');

    },


  },
  mounted() {
    this.getData();
  }
}
</script>
