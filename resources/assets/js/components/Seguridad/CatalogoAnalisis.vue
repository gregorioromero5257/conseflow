<template>
  <div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
      <!-- Inicio card--><div class='card-header'>
      <i class='fa fa-align-justify'></i> {{tipo == 1 ? 'Catálogo Analisis' : tipo == 2 ? 'Catálogo Residuos' : 'Catálogos'}}
      <button type='button' v-show="tipo == 1" class='btn btn-dark float-sm-right' @click='AbrirModalAnalisis(true)'>
        <i class='fas fa-plus'>&nbsp;</i>Nuevo
      </button>
      <button type='button' v-show="tipo == 2" class='btn btn-dark float-sm-right' @click='AbrirModalResiduos(true)'>
        <i class='fas fa-plus'>&nbsp;</i>Nuevo
      </button>
      <div class="dropdown float-sm-right">
        <button type="button" class="btn btn-secondary dropdown-toggle" id="dropmenub" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" name="button">
          Tipo
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdown" >
          <button class="dropdown-item" @click="tipo = 1">Analisis</button>
          <button class="dropdown-item" @click="tipo = 2">Residuos</button>
        </div>
      </div>
    </div>
    <div class='card-body' v-show="tipo == 1">
      <div class=''>
        <!-- Tabla de Analisis-->
        <div class=''>
          <v-client-table :columns='columns_analisis' :data='list_analisis' :options='options_analisis' ref='tbl_analisis'>
            <template slot='id' slot-scope='props'>
              <div class='btn-group' role='group'>
                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                </button>
                <div class='dropdown-menu'>
                  <template>
                    <button type='button' @click='AbrirModalAnalisis(false, props.row)' class='dropdown-item'>
                      <i class='icon-pencil'></i>&nbsp;Actualizar
                    </button>
                  </template>
                </div>
              </div>
            </template>
          </v-client-table>
        </div> <!--Card body -->
      </div> <!-- card-->
    </div>
    <div class="card-body" v-show="tipo == 2">
      <v-client-table :columns='columns_residuos' :data='list_residuos' :options='options_residuos' >
        <template slot='residuo.id' slot-scope='props'>
          <div class='btn-group' role='group'>
            <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
              <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
            </button>
            <div class='dropdown-menu'>
              <template>
                <button type='button' @click='AbrirModalResiduos(false, props.row)' class='dropdown-item'>
                  <i class='icon-pencil'></i>&nbsp;Actualizar
                </button>
              </template>
            </div>
          </div>
        </template>
        <template slot="fuente" slot-scope="props">
          <template v-for="t in props.row.fuente"  >
            {{t.fuente_generacion}},
          </template>
        </template>
      </v-client-table>
    </div>
    <div class="card-body" v-show="tipo == 0">

    </div>
  </div>


  <!-- Modal Analisis -->
  <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_analisis}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
    <div class='modal-dialog modal-dark modal-lg' role='document'>
      <div class='modal-content'>
        <div class='modal-header'>
          <h4 class='modal-title' v-text='titulo_modal_analisis'></h4>
          <button type='button' class='close' @click='CerrarModalAnalisis()' aria-label='Close'>
            <span aria-hidden='true'>×</span>
          </button>
        </div>
        <div class='modal-body'>
            <div class='form-group row'>
              <label class='col-md-3 form-control-label'>Secuencia</label>
              <div class='col-md-9'>
                <input type='text' class='form-control' v-model='analisis_modal.secuencia'>
              </div>
            </div>


            <div class='form-group row'>
              <label class='col-md-3 form-control-label'>Riesgo</label>
              <div class='col-md-9'>
                <textarea name="name" rows="4" cols="80" class='form-control' v-model='analisis_modal.riesgo'></textarea>
              </div>
            </div>


            <div class='form-group row'>
              <label class='col-md-3 form-control-label'>Recomendación</label>
              <div class='col-md-9'>
                <textarea name="name" rows="4" cols="80" class='form-control' v-model='analisis_modal.recomendacion'></textarea>
              </div>
            </div>

        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-outline-dark' @click='CerrarModalAnalisis()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
          <button type='button' v-if='tipoAccion_modal_analisis== 1' class='btn btn-secondary' @click='GuardarAnalisis(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
          <button type='button' v-if='tipoAccion_modal_analisis==2' class='btn btn-secondary' @click='GuardarAnalisis(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <!-- Modal Analisis -->
  <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_residuos}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
    <div class='modal-dialog modal-dark modal-lg' role='document'>
      <div class='modal-content'>
        <div class='modal-header'>
          <h4 class='modal-title' v-text='titulo_modal_residuos'></h4>
          <button type='button' class='close' @click='CerrarModalResiduos()' aria-label='Close'>
            <span aria-hidden='true'>×</span>
          </button>
        </div>
        <div class='modal-body'>
          <div class="form-row">
            <div class="col-md-10 mb-3">
              <label>Residuo</label>
              <input type="text" class="form-control" v-model="residuos_modal.residuo">
            </div>

            <div class="col-md-2 mb-3">
              <label>Tipo</label>
              <select class="form-control" v-validate="'required'" data-vv-name="Tipo" v-model="residuos_modal.tipo">
                <option value=""></option>
                <option value="1">RME</option>
                <option value="2">RP</option>
              </select>
            </div>

          </div>
          <div class="form-row">
            <div class="col-md-12 mb-3">
              <label>Fuente</label>
              <v-select taggable multiple v-model="residuos_modal.fuente"/>
            </div>
          </div>

        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-outline-dark' @click='CerrarModalResiduos()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
          <button type='button' v-if='tipoAccion_modal_residuos == 1' class='btn btn-secondary' @click='GuardarResiduos(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
          <button type='button' v-if='tipoAccion_modal_residuos == 2' class='btn btn-secondary' @click='GuardarResiduos(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

</div> <!-- Main -->
</template>

<script>
/* CAMBIAR UBICACIÓN  */import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
export default
{
  data()
  {
    return {
      // Tabla
      tipo : 0,
      ver_modal_analisis: 0,
      ver_modal_residuos: 0,
      columns_analisis: ['id','secuencia','riesgo','recomendacion'],
      columns_residuos: ['residuo.id','residuo.residuo','fuente'],
      list_analisis : [],
      list_residuos :  [],
      options_analisis:
      {
        headings:
        {
          id:'Acciones',secuencia:'Secuencia',riesgo:'Riesgo',recomendacion:'Recomendación'
        }, // Headings,
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['id','secuencia','riesgo','recomendacion'],
        filterable: ['id','secuencia','riesgo','recomendacion'],
        filterByColumn: true,
        texts: config.texts
      }, //options
      options_residuos:
      {
        headings:
        {
          'residuo.id':'Acciones',
          'residuo.residuo' : 'Residuo',
        }, // Headings,
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        // sortable: ['id','secuencia','riesgo','recomendacion'],
        // filterable: ['id','secuencia','riesgo','recomendacion'],
        filterByColumn: true,
        texts: config.texts
      }, //options
      // Modal
      titulo_modal_analisis: '',
      tipoAccion_modal_analisis:0,
      analisis_modal:{},

      titulo_modal_residuos: '',
      tipoAccion_modal_residuos:0,
      residuos_modal:{
        residuo : '',
        fuente : '',
        tipo : '',
        id : 0,
      },

    } // return
    }, //data
    computed:{},
    methods:{
      getData(){
        axios.get('get/lista/catalogo/analisis').then(response => {
          this.list_analisis = response.data;
        }).catch(e => {
          console.error(e);
        });

        axios.get('get/lista/catalogo/residuos').then(response => {
          this.list_residuos = response.data;
        }).catch(e => {
          console.error(e);
        });
      },

      AbrirModalAnalisis(nuevo, model=[])
      {
        this.ver_modal_analisis=true;
        if(nuevo)
        {
          // Crear nuevo
          this.titulo_modal_analisis='Registrar Analisis';this.tipoAccion_modal_analisis=1;
        } else {
          // Actualizar
          this.titulo_modal_analisis='Actualizar Analisis';this.tipoAccion_modal_analisis=2;
          this.analisis_modal.recomendacion = model.recomendacion;
          this.analisis_modal.riesgo = model.riesgo;
          this.analisis_modal.secuencia = model.secuencia;
          this.analisis_modal.id = model.id;
        } // Fin if
      },

      AbrirModalResiduos(nuevo, model = []){
        this.ver_modal_residuos = true;
        if (nuevo) {
          this.titulo_modal_residuos = 'Registrar';
          this.tipoAccion_modal_residuos = 1;
        }else {
          var array = [];
        model.fuente.forEach((item, i) => {
          array.push(item.fuente_generacion);
        });

          console.log(array);
          this.titulo_modal_residuos='Actualizar';
          this.tipoAccion_modal_residuos=2;
          this.residuos_modal.residuo = model.residuo.residuo;
          this.residuos_modal.id = model.residuo.id;
          this.residuos_modal.tipo = model.residuo.tipo;
          this.residuos_modal.fuente = array;
        }
      },

      CerrarModalAnalisis()
      {
        this.ver_modal_analisis=false;
        Utilerias.resetObject(this.analisis_modal);
      },

      CerrarModalResiduos()
      {
        this.ver_modal_residuos=false;
        Utilerias.resetObject(this.residuos_modal);
      },

      GuardarAnalisis(nuevo){
        axios({
          method : nuevo ? 'POST' : 'PUT',
          url : nuevo ? 'guardar/lista/catalogo/analisis' : 'actualizar/lista/catalogo/analisis',
          data : {
            id : nuevo ? 0 : this.analisis_modal.id,
            secuencia : this.analisis_modal.secuencia,
            riesgo : this.analisis_modal.riesgo,
            recomendacion : this.analisis_modal.recomendacion,
          },
        }).then(response => {
          this.getData();
          toastr.success('Correcto!!!');
          this.CerrarModalAnalisis();
        }).catch(e => {
          console.error(e);
        });
      },

      GuardarResiduos(nuevo){
        axios({
          method : nuevo ? 'POST' : 'PUT',
          url : nuevo ? 'guardar/lista/catalogo/residuos' : 'actualizar/lista/catalogo/residuos',
          data : {
            id : nuevo ? 0 : this.residuos_modal.id,
            residuo : this.residuos_modal.residuo,
            tipo : this.residuos_modal.tipo,
            fuente : this.residuos_modal.fuente,
          },
        }).then(response => {
          this.getData();
          toastr.success('Correcto!!!');
          this.CerrarModalResiduos();
        }).catch(e => {
          console.error(e);
        });
      },

    }, // Fin metodos
      mounted()
      {
        this.getData();
        //this.CargarProyectos();
      }
    }
    </script>
