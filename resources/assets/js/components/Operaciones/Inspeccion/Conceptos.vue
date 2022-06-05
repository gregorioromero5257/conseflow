<template>
  <div >
    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i>  {{ partida == null ? '' : partida.num_reporte}}
        <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
          <i class="fas fa-arrow-left"></i>&nbsp;Atras
        </button>
        <button type="button" class="btn btn-dark float-sm-right" @click="abrirModal('partidas','registrar')">
          <i class="fas fa-plus">&nbsp;</i>Nuevo
        </button>
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
                    <button type="button" @click="abrirModal('partidas','actualizar', props.row)" class="dropdown-item">
                      <i class="icon-pencil"></i>&nbsp;Actualizar
                    </button>

                  </template>
                </div>
              </div>
            </div>
          </template>
        </v-client-table>
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
                  <div class="form-group col-md-2">
                    <label>Soldadura</label>
                    <v-select :options="optionsvs" id="soldadura" name="soldadura"
                    v-model="partidas.materiales_servicios_sol_id" label="name" data-vv-name="soldadura" @input="cargar()"></v-select>
                  </div>

                  <div class="form-group col-md-2">
                    <label>Díametro</label>
                    <input type="text" class="form-control" v-model="partidas.diametro" name="diametro">
                  </div>

                  <div class="form-group col-md-2">
                    <label>Espesor</label>
                    <input type="text" class="form-control" v-model="partidas.espesor" name="espesor">
                  </div>

                  <div class="form-group col-md-2">
                    <label>Indicación</label>
                    <input type="text" class="form-control" v-model="partidas.indicacion" name="espesor">
                  </div>


                  <div class="form-group col-md-3">
                    <label>Evaluación</label>
                    <input type="text" class="form-control" v-model="evaluacion" name="evaluacion">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label>{{material_uno}}</label>
                    <input type="text" class="form-control" v-model="partidas.material_uno" name="material uno">
                  </div>
                  <div class="form-group col-md-3">
                    <label>{{material_dos}}</label>
                    <input type="text" class="form-control" v-model="partidas.material_dos" name="material dos">
                  </div>
                  <div class="form-group col-md-3">
                    <label>Id Soldador</label>
                    <select class="form-control" v-model="partidas.id_soldador">
                      <option v-for="elem in soldadores" :key="elem.key" :value="elem.id">{{elem.num}}</option>
                    </select>
                  </div>
                  <div class="form-group col-md-3">
                    <label>Observaciones</label>
                    <input type="text" class="form-control" v-model="partidas.observaciones" name="observaciones">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <vue-element-loading :active="isLoading"/>
                <button type="button" class="btn btn-outine-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarpartida(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarpartida(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        <!--Fin del modal-->
      </div>
    </div>
  </div>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return {
      isLoading : false,
      columns : ['id','soldadura','diametro','espesor','indicacion','evaluacion','id_soldador','observaciones'],
      soldadores : [],
      data : [],
      optionsvs : [],
      material_uno : '',
      material_dos : '',
      modal : 0,
      tipoAccion : 0,
      tituloModal : '',
      evaluacion : 'ACEPTADO',
      partidas : {
        id : '',
        materiales_servicios_sol_id : '',
        diametro : '',
        espesor : '',
        material_uno : '',
        material_dos : '',
        observaciones : '',
        id_soldador : '',
        inspeccion_id : '',
        indicacion : '',

      },
      partida : null,
      options : {
        headings: {
        'id' : 'Acciones',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['soldadura','diametro','espesor','indicacion','evaluacion','id_soldador','observaciones'],
        filterable: ['soldadura','diametro','espesor','indicacion','evaluacion','id_soldador','observaciones'],
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  methods: {
    maestro(){
      this.$emit('maestro:conceptos');
    },

    getData(data){
      this.optionsvs = [];
      this.partida = data;
      this.partidas.inspeccion_id = data.id;
      // console.log(data);
      axios.get('partidasinpeccion/get/'+data.id).then((response) => {
        this.data = response.data;
      }).catch((err) => {console.error(err);})
      axios.get('/soldadores/obtener').then((response) => {
        this.soldadores = response.data;
      }).catch((err) => {console.error(err);})
      axios.get('/partidas/juntas/' + data.elemento_servicios_id + '&' + data.proyecto).then((response) => {
        for (var i = 0; i < response.data.length; i++) {
          this.optionsvs.push({
            id: response.data[i].id,
            name : response.data[i].soldadura,
            material_uno : response.data[i].material_uno,
            material_dos : response.data[i].material_dos,
            diametro : response.data[i].diametro,
            espesor : response.data[i].espesor,
            observaciones : response.data[i].observaciones,
          });
        }
      }).catch((err) => {console.error(err);});
    },

    cargar(){
      this.material_uno = this.partidas.materiales_servicios_sol_id.material_uno;
      this.material_dos = this.partidas.materiales_servicios_sol_id.material_dos;
      this.partidas.diametro = this.partidas.materiales_servicios_sol_id.diametro;
      this.partidas.espesor = this.partidas.materiales_servicios_sol_id.espesor;
      this.partidas.observaciones = this.partidas.materiales_servicios_sol_id.observaciones;
    },

    abrirModal(modelo, accion, data = []){
      switch (modelo) {
        case 'partidas':
        {
          switch (accion) {
            case 'registrar':
            {
              this.modal = 1;
              this.tituloModal = 'Agregar Inspección';
              Utilerias.resetObject(this.partidas);
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar' :
            {
              this.modal = 1;
              this.tituloModal = 'Actualizar Inspección';
              Utilerias.resetObject(this.partidas);

              this.tipoAccion = 2;
              this.partidas.diametro = data['diametro'];
              this.partidas.espesor = data['espesor'];
              this.partidas.indicacion = data['indicacion'];
              this.evaluacion = data['evaluacion'];
              this.partidas.id_soldador = data['id_soldador'];
              this.partidas.observaciones = data['observaciones'];
              this.partidas.materiales_servicios_sol_id = {id: data['materiales_servicios_sol_id'],
              name : data['soldadura'], material_uno : data['material_uno_n'], material_dos : data['material_dos_n'],};
              this.partida.indicacion = data['indicacion'];
              this.partidas.material_uno = data['material_uno'];
              this.partidas.material_dos = data['material_dos'];
              this.partidas.id = data['id'];
              console.log(data);
              break;
            }partida
          }
        }
      }
    },

    guardarpartida(nuevo){
      this.$validator.validate().then((result) => {
        if(result){
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? '/partidasinpeccion' : '/partidasinpeccion/update/'+this.partidas.id,
            data: {
              // 'nombre': this.clientes.nombre.toUpperCase(),
              'diametro' : this.partidas.diametro,
              'espesor' : this.partidas.espesor,
              'indicacion' : this.partidas.indicacion,
              'evaluacion' : this.evaluacion,
              'id_soldador' : this.partidas.id_soldador,
              'observaciones' : this.partidas.observaciones,
              'materiales_servicios_sol_id' : this.partidas.materiales_servicios_sol_id.id,
              'material_uno' : this.partidas.material_uno,
              'material_dos' : this.partidas.material_dos,
              'inspeccion_id' : me.partida.id,
              'indicacion' : this.partidas.indicacion,

            }
          }).then(function (response) {
            me.cerrarModal();
            me.isLoading = false;
            me.getData(me.partida);
          }).catch(function (error) {
            console.log(error);
          });
        }
      });
    },


    cerrarModal(){
      this.modal = 0;
    }
  },
}
</script>
