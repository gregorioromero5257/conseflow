<template >
  <div >

  <!--Inicio del modal agregar/actualizar documento-->
  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dark modal-lg" role="document">
      <div class="modal-content">
        <div>
          <div class="modal-header">
            <h4 class="modal-title"> Revisar documentación requerida</h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <vue-element-loading :active="isLoading"/>
            <!-- {{doc_req}} -->
            <input type="hidden" name="id">
            <div class="modal-body">
              <div class="form-group row">
                <div class="col-md-4" v-for="doc in documentos">
                  <div class="form-check checkbox" >
                    <input class="" :value="doc.id" :id="doc.id" type="checkbox" v-model="doc_req" >
                    <label class="form-check-label" for="doc.id">
                      {{doc.nombre}}
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <!-- <button type="button" class="btn btn-primary" @click="guardardocumentos()">Ok</button> -->
            <!-- <button type="button" v-show="tipoAcciondos==1" class="btn btn-primary" @click="guardardocumentos()">Guardar</button> -->
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            <button type="button" v-show="tipoAcciondos==0" class="btn btn-secondary" @click="guardardocumentosupdate()">Actualizar</button>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal agregar documentos-->

</div>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return {
      modal : 0,
      isLoading : false,
      preq : {
        articulo_id : 0,
        requisicione_id : 0,
        servicio_id : 0,
      },
      doc_req : [],
      tipoAcciondos : 1,
      documentos : null,
    }
  },
  methods : {
    getListas(){
      let me = this;
      axios.get('ducumentosrequeridos').then(response =>{
        me.documentos = response.data;
      })
      .catch(function (error){
        console.error();
      });

    },
    abrirModal(req, art, serv){
      this.modal = 1;
      this.isLoading = true;
      this.tipoAcciondos =0;
      var ida = art;
      this.preq.articulo_id = ida;
      var serv = serv;
      this.preq.servicio_id = serv;
      var idr = req;
      this.preq.requisicione_id = idr;
      var cadenadoc = [];
      let me = this;
      axios.get('/partidadocumentos/' + ida + '&' + idr + '&' + serv).then(function (response) {
        for (var i = 0; i < response.data.length; i++) {
          cadenadoc.push(response.data[i].documento_id);
        }
        me.isLoading = false;
      }).catch(function (error) {
        console.log(error);
      });
      this.doc_req = cadenadoc;

    },

    /**
    * [cerrarModal description]
    * @return {[type]} [description]
    */
    cerrarModal(){
      this.modal = 0;

    },

    /**
    * [guardardocumentosupdate description]
    * @return {[type]} [description]
    */
    guardardocumentosupdate(){
      var array = [] ;
      var id =this.preq.requisicione_id;
      let me = this;
      axios.put('/partidare/'+this.preq.articulo_id+'/updatedoc',{
        'documento_id': this.doc_req,
        'partidarequisicione_art': this.preq.articulo_id,
        'partidarequisicione_req': this.preq.requisicione_id,
        'partidarequisicione_serv' : this.preq.servicio_id,

      }).then(function (response) {
        console.log(response);
        me.cerrarModal();
        // me.cargardetalle(me.requisiicion);
        toastr.success('Correcto','Documentos actualizados correctamente');
      }).catch(function (error) {
        console.log(error);
      });
    },
  },
  mounted(){
    this.getListas();
  }
}
</script>
