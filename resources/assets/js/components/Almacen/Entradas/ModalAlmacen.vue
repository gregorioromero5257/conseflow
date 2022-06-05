<template>
  <div>
    <div>

    <!--Inicio del modal agregar almacen-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <vue-element-loading :active="isLoading" />
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="almacen_id">Almacen</label>
              <div class="col-md-9">
                <select class="form-control" id="almacen_id" name="almacen_id" v-model="almacen.id" @change="almacenes" data-vv-as="Stand">
                  <option v-for="item in listaAlmacenes" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                </select>
                <span class="text-danger">{{ errors.first('grupo_id') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="stand_id">Stand</label>
              <div class="col-md-9">
                <select class="form-control" id="stand_id" name="stand_id" v-model="almacen.stand_id" @change="nivel" data-vv-as="Stand">
                  <option v-for="item in listaStand" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                </select>
                <span class="text-danger">{{ errors.first('grupo_id') }}</span>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="nivel_id">Nivel</label>
              <div class="col-md-9">
                <select class="form-control" id="nivel_id" name="nivel_id" v-model="almacen.nivel_id" data-vv-as="Nivel">
                  <option v-for="item in listaNivel" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                </select>
                <span class="text-danger">{{ errors.first('grupo_id') }}</span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="guardarDatosAlmacen()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
          </div>

        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal agregar almacen-->
    </div>
  </div>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data() {
    return {
      isLoading : false,
      modales : 0,
      modal : 0,
      tituloModal : '',
      listaAlmacenes : [],
      listaStand : [],
      listaNivel : [],
      almacen :{
        id : '',
        stand_id : '',
        nivel_id : '',
      }
    }
  },
  methods : {
    /**
    *
    */
    cargar(entrada,almacenes,nuevo){
      if (nuevo) {
        this.modal = 1;
        this.tituloModal = 'Registrar Almacen';
        this.entrada_id = entrada;
        this.listaAlmacenes = almacenes;
      }else {
        this.almacen.id = entrada.almacene_id;
        this.almacen.stand_id = entrada.stand_id;
        this.almacen.nivel_id = entrada.nivel_id;
        this.modal = 1;
        this.tituloModal = 'Actualizar Almacen';
        this.entrada_id = entrada;
        this.listaAlmacenes = almacenes;
      }

    },

    cerrarModal(){
      this.modal = 0;
      this.$emit('atras:modalalmacenes');
    },

    /**
    * [almac Metodo de consulta a la BD ]
    * @return {Response} [description]
    */
    almacenes() {
      let me = this;
      this.isLoading = true;
      axios.get('almacen/verstand/' + this.almacen.id).then(response => {
        me.listaStand = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    /**
    * [niv Metodo de consulta a la BD ]
    * @return {Response} [description]
    */
    nivel() {
      let me = this;
      this.isLoading = true;
      axios.get('almacen/vernivel/' + this.almacen.stand_id).then(response => {
        me.listaNivel = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    guardarDatosAlmacen(){
      this.$emit('almacen',this.almacen);
      this.modal = 0;
    }



  }
}
</script>
