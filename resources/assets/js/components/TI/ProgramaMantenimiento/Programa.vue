<template>
  <main class="main">

    <div class="container-fluid">

      <div class="card" v-show="!detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Programa anual de mantenimiento preventivo {{company == 1 ? 'CONSERFLOW' : 'CSCT'}}
          <div class="dropdown">

            <button class="btn btn-secondary dropdown-toggle float-sm-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Empresa
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2" >
              <button class="dropdown-item" type="button" @click="company = 1; buscarCFW();">Conserflow</button>
              <button class="dropdown-item" type="button" @click="company = 2; buscarCSCT();">CSCT</button>
            </div>
          </div>
          <button type="button" class="btn btn-dark float-sm-right" @click="abrirModal(1)">
            <i class="fas fa-plus">&nbsp;</i>Nuevo
          </button>

        </div>
        <div class="card-body">
          <v-client-table :data="tableData" :options="options" :columns="columns">
            <template slot='anio' slot-scope='props'>
              <div class='btn-group' role='group'>
                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  <i class='fas fa-grip-horizontal'></i>&nbsp;
                </button>
                <div class='dropdown-menu'>
                  <template>
                    <button type='button' @click='verdetalle(props.row.anio)' class='dropdown-item'>
                      <i class='fas fa-eye'></i>&nbsp;Ver detalles
                    </button>
                  </template>
                </div>
              </div>
            </template>
            <template slot="descargar" slot-scope="props">
              <button type='button' @click='descargar(props.row.anio)' class='btn btn-outline-dark'>
                <i class='fas fa-download'></i>&nbsp;
              </button>
            </template>
            <template slot="descripcion" slot-scope="props">
              <span>PROGRAMA ANUAL DE MANTENIMIENTO PREVENTIVO DE EQUIPO DE TI {{props.row.anio}}</span>
            </template>
          </v-client-table>

        </div>
      </div>

      <div class="card" v-show="detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Programa anual de mantenimiento preventivo
          <button type="button" class="btn btn-secondary float-sm-right" @click="atras()">
            <i class="fas fa-arrow-left">&nbsp;</i>Atras
          </button>
        </div>
        <div class="card-body">
          <v-client-table :data="tableDataDetalle" :options="optionsDetalle" :columns="columnsDetalle">
            <template slot='data.id' slot-scope='props'>
              <div class='btn-group' role='group'>
                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  <i class='fas fa-grip-horizontal'></i>&nbsp;
                </button>
                <div class='dropdown-menu'>
                  <template>
                    <button type='button' @click='abrirModal(2, props.row)' class='dropdown-item'>
                      <i class='fas fa-eye'></i>&nbsp;Actualizar
                    </button>  <button type='button' @click='eliminar(props.row.data.id)' class='dropdown-item'>
                        <i class='fas fa-trash'></i>&nbsp;Eliminar
                      </button>
                  </template>
                </div>
              </div>
            </template>
            <template slot="data.mes" slot-scope="props">
              <template v-if="props.row.data.mes == 1"><span>Enero</span></template>
              <template v-if="props.row.data.mes == 2"><span>Febrero</span></template>
              <template v-if="props.row.data.mes == 3"><span>Marzo</span></template>
              <template v-if="props.row.data.mes == 4"><span>Abril</span></template>
              <template v-if="props.row.data.mes == 5"><span>Mayo</span></template>
              <template v-if="props.row.data.mes == 6"><span>Junio</span></template>
              <template v-if="props.row.data.mes == 7"><span>Julio</span></template>
              <template v-if="props.row.data.mes == 8"><span>Agosto</span></template>
              <template v-if="props.row.data.mes == 9"><span>Septiembre</span></template>
              <template v-if="props.row.data.mes == 10"><span>Octubre</span></template>
              <template v-if="props.row.data.mes == 11"><span>Noviembre</span></template>
              <template v-if="props.row.data.mes == 12"><span>Diciembre</span></template>
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
              <button type='button' class='close' @click='cerrarModal()' aria-label='Close'>
                <span aria-hidden='true'>×</span>
              </button>
            </div>
            <div class='modal-body'>
              <div class="form-row">
                <div class="col-md-3 mb-3">
                  <label>Tipo</label>
                  <select class="form-control" v-validate="'required'" data-vv-name="tipo" v-model="tipo">
                    <option value="">Seleccione</option>
                    <option value="1">Computo</option>
                    <option value="3">Impresion</option>
                  </select>
                  <span class="text-danger">{{errors.first('tipo')}}</span>
                </div>
                <div class="col-md-9 mb-3">
                  <label>Seleccione</label>
                  <v-select :options="listaCatalogo" v-model="caiv" label="descripcion"
                  data-vv-name="material" @search="buscar" @input="asignar()">
                  </v-select>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-3 mb-3">
                  <label>Marca</label>
                  <input type="text" class="form-control" v-model="marca">
                </div>
                <div class="col-md-3 mb-3">
                  <label>Modelo</label>
                  <input type="text" class="form-control" v-model="modelo">
                </div>
                <div class="col-md-3 mb-3">
                  <label>No. Serie</label>
                  <input type="text" class="form-control" v-model="num_serie">
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-3 mb-3">
                  <label>Mes Mantenimiento</label>
                  <select class="form-control" v-model="mes">
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                  </select>
                </div>
                <div class="col-md-3 mb-3">
                  <label>Año</label>
                  <input type="text" class="form-control" v-model="anio">

                </div>
                <div class="col-md-3 mb-3">
                  <label>Empresa</label>
                  <select class="form-control" v-model="empresa">
                    <option value="1">Conserflow</option>
                    <option value="2">CSCT</option>
                  </select>
                </div>
              </div>


            </div>
            <div class='modal-footer'>
              <vue-element-loading :active="isLoading"/>
              <button type='button' class='btn btn-outline-dark' @click='cerrarModal()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
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
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return{
    modal : 0,
    tituloModal : '',
    tipoAccion : 0,
    isLoading :false,

    company : 1,
    anio_busqueda : '',

    detalle : false,

    caiv : '',
    tipo : '',
    marca : '',
    modelo : '',
    num_serie : '',
    mes : '',
    id : 0,
    anio : '',
    empresa : '',

    listaCatalogo : [],

    tableData : [],
    columns : ['anio','descripcion','descargar'],
    options :
    {
      headings:
      {
        'anio': 'Acciones',
      }, // Headings,
      perPage: 10,
      perPageValues: [],
      skin: config.skin,
      sortIcon: config.sortIcon,
      filterByColumn: true,
      texts: config.texts
    }, //options

    tableDataDetalle : [],
    columnsDetalle : ['data.id','tipo','data.mes','data.anio'],
    optionsDetalle :
    {
      headings:
      {
        'data.id': 'Acciones',
        'data.mes' : 'Mes',
        'data.anio' : 'Año',
      }, // Headings,
      perPage: 10,
      perPageValues: [],
      skin: config.skin,
      sortIcon: config.sortIcon,
      filterByColumn: true,
      texts: config.texts
    }, //options

    }
  },
  methods : {
    getDataI(){
      axios.get('get/inicial/data/programa/ti/' + this.company).then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    buscarCFW(){
      this.getDataI();
    },

    buscarCSCT(){
      this.getDataI();
    },

    buscar(search, loading){
      if (this.tipo === '') {
        toastr.warning('Seleccione un tipo para realizar una busqueda');
      }
      else {

        if (search.length > 2) {

          let me = this;

            setTimeout(function(){
              axios.post('get/material/ti/descripcion/programa',{
                des : search,
                tipo : me.tipo,
              }).then(response => {
                me.listaCatalogo = response.data;
              }).catch(e =>{
                console.error(e);
              });
            }, 1000);

        }

      }
    },

    asignar(){
      this.marca = this.caiv.marca;
      this.modelo = this.caiv.modelo;
      this.num_serie = this.caiv.no_serie;
    },

    vaciar(){
      this.caiv  = "";
      this.tipo  = "";
      this.marca  = "";
      this.modelo  = "";
      this.num_serie  = "";
      this.mes  = "";
      this.anio  = "";
      this.empresa  = "";
    },

    abrirModal(edo, data = []){
      if (edo == 1) {
        this.vaciar();
        this.modal = 1;
        this.tituloModal = 'Guardar';
        this.tipoAccion = 1;
      }else if (edo == 2) {
        this.vaciar();
        this.modal = 1;
        this.tituloModal = 'Actualizar';
        this.tipoAccion = 2;
        this.caiv = {id : data['data']['caiv'], descripcion : data['tipo'], marca : data['data']['marca'],
        modelo :data['data']['modelo'] ,no_serie : data['data']['num_serie'] };
        this.id = data['data']['id'];
        this.tipo = data['data']['tipo'];
        this.marca = data['data']['marca'];
        this.modelo = data['data']['modelo'];
        this.num_serie = data['data']['num_serie'];
        this.mes = data['data']['mes'];
        this.anio = data['data']['anio'];
        this.empresa = data['data']['empresa'];
      }
    },

    cerrarModal(){
      this.modal = 0;
    },

    descargar(anio){
      window.open('descargar/data/programa/ti/'  + anio + '&' + this.company , '_blank');
    },

    Guardar(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? 'guardar/data/programa/ti/' : 'actualizar/data/programa/ti/',
            data : {
              id : this.id,
              caiv : this.caiv.id,
              tipo : this.tipo,
              marca : this.marca,
              modelo : this.modelo,
              num_serie : this.num_serie,
              mes : this.mes,
              anio : this.anio,
              empresa : this.empresa,
            }
          }).then(response => {
            toastr.success(nuevo ? 'Guardado Correctamente' : 'Actualizado Correctamente');
            this.getDataI();
            this.cerrarModal();
            if (!nuevo) {
              this.verdetalle(this.anio_busqueda);
            }
            this.isLoading = false;
          }).catch(e => {
            this.isLoading = false;
            console.error(e);
          });
        }
      });
    },

    verdetalle(anio){
      this.anio_busqueda = anio;
      axios.get('get/data/programa/ti/' + anio + '&' + this.company).then(response =>{
        this.detalle = true;
        this.tableDataDetalle = response.data;
      }).catch(e => {
        console.error();
      });
    },

    atras(){
      this.detalle = false;
    },

    eliminar(id){
      Swal.fire({
        title: 'Esta seguro(a) de eliminar ?',
        text: "Esta opción no se podrá desahacer!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText : 'No',
      }).then(result => {
        if (result.value) {
          axios.get('delete/data/programa/ti/' + id).then(response =>{
            this.verdetalle(this.anio_busqueda);
          }).catch(e => {
            console.error();
          });
        }
      });

    },

  },
  mounted(){
    this.getDataI();
  }
}

</script>
