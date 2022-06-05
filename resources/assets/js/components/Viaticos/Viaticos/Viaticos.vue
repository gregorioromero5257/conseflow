<template>
  <main class="main">
    <div class="container-fluid">
      <br>
      <div class="card" v-show="detalle == 0">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Proyectos {{empresa == 1 ? 'CONSERFLOW' : empresa == 2 ? 'CSCT' : ''}}
          <!-- <button type="button" @click="abrirModal('viaticos','registrar')" class="btn btn-dark float-sm-right">
          <i class="fas fa-plus"></i>&nbsp;Nuevo
        </button> -->
        <div class="dropdown">
          <button class="btn btn-secondary dropdown-toggle float-sm-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Empresa
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenu2" v-model="empresa">
            <button class="dropdown-item" type="button" @click="empresa = 1;">Conserflow</button>
            <button class="dropdown-item" type="button" @click="empresa = 2;">Constructora</button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <v-server-table ref="myTablePrincipal" :columns="columns" :url="'proyectos/buscarviaticos/'+empresa" :options="options">
          <template slot="condicion" slot-scope="props" >
            <template v-if="props.row.condicion == 1">
              <button type="button" class="btn btn-outline-success">Activo</button>
            </template>
            <template v-if="props.row.condicion == 0">
              <button type="button" class="btn btn-outline-danger">Terminado</button>
            </template>
            <template v-if="props.row.condicion == 2">
              <button type="button" class="btn btn-outline-warning">Pausa</button>
            </template>
            <template v-if="props.row.condicion == 3">
              <button type="button" class="btn btn-outline-dark">Rechazado</button>
            </template>
          </template>
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                  <button type="button" @click="verviaticos(props.row)"
                  class="dropdown-item" >
                  <i class="fas fa-eye"></i>&nbsp; Ver viaticos
                </button>


              </div>
            </div>
          </div>

        </template>
      </v-server-table>
    </div>
  </div>
  <!-- Fin ejemplo de tabla Listado -->

  <!-- inicio del listado de viaticos -->
  <div class="card" v-show="detalle == 1">
    <div class="card-header">
      <i class="fa fa-align-justify"></i>Viaticos del proyecto: {{proyectos == null ? '' : proyectos.nombre_corto}}

      <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right" >
        <i class="fas fa-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <div class="card-body">
      <v-client-table :columns="columnsviatico" :data="dataviatico" :options="optionsviatico" ref="myTable" >
        <template slot="solicitud.estado" slot-scope="props" >
          <template v-if="props.row.pagos.total == null && props.row.solicitud.estado < 6">
            <span class="btn btn-uno">Por agendar pagos</span>
          </template>
          <template v-if="props.row.pagos.total != null && props.row.solicitud.estado < 6">
            <span class="btn btn-dos">Con pagos agendados</span>
          </template>
          <template v-if="props.row.solicitud.estado == 6">
            <span class="btn btn-tres">Finalizado</span>
          </template>
        </template>
        <template slot="solicitud.id" slot-scope="props" >
          <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-grip-horizontal"></i>&nbsp;
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">


            <button type="button"  @click="verdetalle(props.row)"
            class="dropdown-item" >
            <i class="fas fa-eye"></i>&nbsp; Detalles
          </button>
            <button type="button"  @click="finalizar(props.row)"
            class="dropdown-item" >
            <i class="fas fa-eye"></i>&nbsp; Finalizar
          </button>
          </div>
        </div>
      </div>
    </template>
    <template slot="descarga" slot-scope="props">
      <template v-if="props.row.solicitud.tipo ==  0">
        <button type="button" @click="descargarnForFij(props.row.solicitud.id, empresa)" class="btn btn-outline-dark" >
          <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>
        </button>
      </template>
      <template v-if="props.row.solicitud.tipo ==  1">
        <button type="button" @click="decargarForVia(props.row.solicitud.id, empresa)" class="btn btn-outline-dark" >
          <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>
        </button>
      </template>
      <template v-if="props.row.solicitud.tipo ==  2">
        <button type="button" @click="decargarForVia(props.row.solicitud.id, empresa)" class="btn btn-outline-dark" >
          <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>
        </button>
      </template>
    </template>
  </v-client-table>
</div>
</div>
<!-- Fin de listado de viaticos -->

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

        <div class="form-row">
          <div class="form-group col-md-6">
            <label> Tipo</label>
            <select class="form-control" v-validate="'required'" data-vv-name="tipo" name="tipo" v-model="viaticos.tipo"  >
              <option value="VIA">VIA</option>
              <option value="GASTOS">GASTOS</option>
              <option value="COMBUSTIBLE">COMBUSTIBLE</option>
              <option value="OTRO">OTRO</option>
            </select>
            <!-- <input type="text" class="form-control" v-validate="'required'" v-model="viaticos.nombre" data-vv-name="nombre" placeholder="Nombre"> -->
            <span class="text-danger">{{ errors.first('tipo') }}</span>
          </div>
          <div class="form-group col-md-6">
            <label> Centro de costo</label>
            <select class="form-control" v-validate="'required'" name="centro" v-model="viaticos.proyecto" data-vv-name="centro" v-bind:disabled="tipoAccion == 2">
              <option v-for="item in listaProyectos" :value="item.id"> {{item.nombre}}</option>
            </select>
            <!-- <input type="text" class="form-control" v-validate="'required'" v-model="viaticos.rfc" data-vv-name="rfc" placeholder="RFC"> -->
            <span class="text-danger">{{ errors.first('centro') }}</span>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-8">
            <label> Beneficiario</label>
            <v-select :options="optionsvs" v-model="viaticos.beneficiario" label="name" name="beneficiario" data-vv-name="beneficiario  " ></v-select>
            <span class="text-danger">{{ errors.first('beneficiario') }}</span>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Importe TE</label>
            <input type="text" name="importe TE" v-model="viaticos.importeTE" class="form-control" v-validate="'required'" data-vv-name="importe TE" placeholder="Importe TE">
            <span class="text-danger">{{errors.first('importe TE')}}</span>
          </div>
          <div class="form-group col-md-6">
            <label>Importe efectivo</label>
            <input type="text" name="importe efectivo" v-model="viaticos.importeE" class="form-control" v-validate="'required'" data-vv-name="importe efectivo" placeholder="Importe efectivo">
            <span class="text-danger">{{errors.first('importe efectivo')}}</span>
          </div>
        </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
        <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
        <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!--Fin del modal-->

<div v-show="detalle == 3">
  <facturas ref="facturas" @facturas:maestro="maestrouno"></facturas>
</div>
<div v-show="detalle == 5">
  <ul class="nav nav-tabs" role="tablist" ref="tabs">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#menu1" role="tab" @click="revisareporte()"><i class="fas fa-book"></i>&nbsp;Reporte Gastos</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#menu2" role="tab" @click="verfechas()"><i class="fas fa-tools"></i>&nbsp;Pagos</a>
    </li>
    <li class="nav-item">
      <a class="btn btn-secondary float-sm-right" @click="maestrouno()"><i class="fa fa-arrow-left"></i>&nbsp;Atras</a>
    </li>
  </ul>
  <div class="tab-content">
    <div id="menu1" class="tab-pane fade in active show" >
      <div v-show="tabs == 1">
        <reporterevision ref="reporterevision" @reporterevision:maestro="maestrouno"></reporterevision>
      </div>

    </div>
    <div id="menu2" class="tab-pane fade">
      <div v-show="tabs == 2">
        <detalle ref="detalle" @detalle:maestro="maestrouno"></detalle>
      </div>
    </div>
  </div>
</div>


</div>
</main>

</template>

<script>
const Detalle = r => require.ensure([], () => r(require('./Detalle.vue')), 'via');
const Facturas = r => require.ensure([], () => r(require('./Facturas.vue')), 'via');
const ReporteRevision = r => require.ensure([], () => r(require('./ReporteRevision.vue')), 'via');

import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      empresa : '1',
      proyectos : null,
      proyecto_id : 0,
      tabs : 1,
      columnsviatico: ['solicitud.id','descarga','solicitud.folio','solicitud.fecha_solicitud','detalle.total','pagos.total_i_te','pagos.total_i_efectivo','pagos.total','solicitud.estado'],
      dataviatico : [],
      optionsviatico : {
        headings: {
          'solicitud.id' : 'Acciones',
          'solicitud.folio' : 'Folio',
          'detalle.total' : 'Total solicitado',
          'solicitud.fecha_solicitud' : 'Fecha solicitud',
          'pagos.total_i_te' : 'Importe TE pagado',
          'pagos.total_i_efectivo' : 'Importe efectivo pagado',
          'pagos.total' : 'Total',
          'solicitud.estado' : 'Estado',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: [,'solicitud.folio','solicitud.fecha_solicitud','pagos.total_i_te','pagos.total_i_efectivo','pagos.total'],
        filterable: ['solicitud.folio','solicitud.fecha_solicitud','pagos.total_i_te','pagos.total_i_efectivo','pagos.total'],
        filterByColumn: true,
        texts:config.texts
      },
      columns: ['id','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin','condicion' ],
      tableData: [],
      options: {
        headings: {
          'id': 'Acciones',
          'nombre': 'Nombre',
          'nombre_corto': 'Nombre corto',
          'clave': 'Ord Comp',
          'ciudad': 'Ciudad',
          'fecha_inicio': 'F. Inicio',
          'fecha_fin': 'F. Fin',
          'condicion':'Condición',
          'updated_at' : 'Ultima Actualización',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['nombre','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin','condicion'],
        filterable: ['nombre','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin','condicion'],
        filterByColumn: true,
        texts:config.texts
      },
      modal : 0,
      tituloModal : '',
      viaticos : {
        id: '',
        tipo : '',
        proyecto : '',
        beneficiario : '',
        importeTE : '',
        importeE : '',

      },
      tipoAccion : 0,
      listaProyectos : [],
      listaEmpleados : [],
      optionsvs : [],
      detalle : 0,

    }
  },
  components : {
    'detalle' : Detalle,
    'facturas' : Facturas,
    'reporterevision' : ReporteRevision,
  },
  methods :  {


        decargarForVia(data, empresa){
          window.open('/descargarformatoviatico/' + data + '&' + empresa, '_blank');

        },
        descargarnForFij(data, empresa){
          window.open('/descargarnformatofij/' + data + '&' + empresa, '_blank');

        },

    getData(){
      axios.get('/proyectos').then(response => {
        this.listaProyectos = response.data;
      }).catch(error => {
        console.error(error);
      });

      axios.get('/vertodosempleados').then(response => {
        for (var i = 0; i < response.data.length; i++) {
          this.optionsvs.push({
            id : response.data[i].id ,
            name : response.data[i].nombre+' '+response.data[i].ap_paterno+' '+response.data[i].ap_materno,
          });
        }
        // this.listaEmpleados = response.data;
      }).catch(error => {
        console.error(error);
      });

      let me = this;
      axios.get('/proyecto-listar').then(response => {
        me.tableData = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    abrirModal(modelo,accion,data = []){
      switch(modelo){
        case "viaticos":
        {
          switch(accion){
            case 'registrar':
            {
              Utilerias.resetObject(this.viaticos);
              this.modal= 1;
              this.tituloModal = 'Registrar Viaticos';
              this.tipoAccion=1;
              break;
            }
            case 'actualizar' :
            {
              // console.log(data);
              this.modal = 1;
              this.tipoAccion = 2;
              this.tituloModal = 'Actializar Viaticos';
              this.viaticos.id = data['id'];
              this.viaticos.tipo = data['tipo'];
              this.viaticos.proyecto = data['proyecto'];
              this.viaticos.beneficiario = {id:data['beneficiario'], name : data['beneficiario_nombre']};
              this.viaticos.importeTE = data['importe_TE'];
              this.viaticos.importeE = data['importe_efectivo'];
              break;
            }


          }
        }
      }
    },

    cerrarModal(){
      this.modal = 0;
    },

    guardar(nuevo){
      this.$validator.validate().then(result => {
        if (result) {

          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? '/viaticos' : '/viaticos/'+this.viaticos.id,
            data: {
              'tipo': this.viaticos.tipo,
              'proyecto': this.viaticos.proyecto,
              'beneficiario': this.viaticos.beneficiario.id,
              'importe_TE': this.viaticos.importeTE,
              'importe_efectivo': this.viaticos.importeE,

            }
          }).then(function (response) {
            me.isLoading = false;
            me.cerrarModal();
            // console.log(response);
            if (response.status) {
              // me.getData();
              toastr.success('Correcto',nuevo ? 'Viatico registrado correctamente' : 'Viatico actualizado correctamente');
              me.verviaticos(response.data.proyecto);
            } else {
              swal({
                type: 'error',
                html: response.errors.join('<br>'),
              });
            }
          }).catch(function (error) {
            console.log(error);
          });

        }
      });
    },

    verviaticos(data){
      let me = this;
      me.proyecto_id = data.id;
      me.proyectos  = data;
      this.detalle = 1;
      axios.get('/viaticos/ver/solicitudes/'+ data.id + '&' + this.empresa).then(response => {
        me.dataviatico = response.data;
      }).catch(error => {
        console.error(error);
      });
    },

    maestro() {
      this.detalle = 0;
    },

    maestrouno() {
      this.detalle = 1;
    },


    subirfacturas(data){
      this.detalle = 3;
      var chilFactura = this.$refs.facturas;
      chilFactura.getData(data);
    },

    finalizar(data){
      var edo = 6;
      axios.get('/partidasviaticospagos/revisiones/'+data.solicitud.id).then(response => {
        var pendientes = 0;
        for (var i = 0; i < response.data.length; i++) {
          if (response.data[i].condicion === '1') {
            pendientes ++;
          }
        }
        if (pendientes != 0 || response.data.length === 0) {
          toastr.warning('Atención','No se puede finalizar proceso de pago hasta revisar o tener reporte de gastos');
        }
        else {
          axios.post('/estadosviaticos',{
            'id' : data.solicitud.id,
            'edo' : 6,
          }).then(response => {
            if (edo == 6) {
              toastr.success('Solicitud de viaticos con pagos registrados','Correcto');
            }
            let me = this;
            me.verviaticos(me.proyectos);
          }).catch(error => {
            console.log(error);
          });
        }
      }).catch(error => {
        console.log(error);
      });
    },

    revisareporte(){
      this.tabs = 1;
    },

    verfechas(){
      this.tabs = 2;
    },

    verdetalle(data){
      this.detalle = 5;
      var chilReporteRevision = this.$refs.reporterevision;
      chilReporteRevision.getData(data,this.empresa);
      var childDetalle = this.$refs.detalle;
      childDetalle.getData(data,0,this.empresa);
    }



  },
  mounted (){
    this.getData();
  }

}
</script>
<style>

.btn-uno {
  color: rgb(255, 255, 255);
  background-color: #f6a152;
  border-color: #f6a152;
  border-radius: 2px;
}
.btn-dos {
  color: rgb(0, 0, 0);
  background-color: #e9b443;
  border-color: #e9b443;
  border-radius: 2px;
}
/* no */
.btn-tres {
  color: rgb(0, 0, 0);
  background-color: #8bbbef;
  border-color: #8bbbef;
  border-radius: 2px;
}

.btn-cuatro {
  color: rgb(255, 255, 255);
  background-color: #40d0d0;
  border-color: #40d0d0;
  border-radius: 2px;
}
.btn-cinco {
  color: rgb(255, 255, 255);
  background-color: #0d4da3;
  border-color: #0d4da3;
  border-radius: 2px;
}
/* no */
.btn-seis {
  color: rgb(255, 255, 255);
  background-color: #0d98a3;
  border-color: #0d98a3;
  border-radius: 2px;
}
.btn-siete {
  color: rgb(255, 255, 255);
  background-color: #25a30d;
  border-color: #25a30d;
  border-radius: 2px;
}
.btn-ocho {
  color: rgb(255, 255, 255);
  background-color: #d04040;
  border-color: #d04040;
  border-radius: 2px;
}
.btn-nueve {
  color: rgb(0, 0, 0);
  background-color: #40d088;
  border-color: #40d088;
  border-radius: 2px;
}

</style>
