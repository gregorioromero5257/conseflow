<template >
  <div >

  <!--Inicio del modal agregar/actualizar requisicion-->
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
            <input type="hidden" name="id">
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="folio">Folio</label>
              <div class="col-md-9">
                <input type="text" name="folio" v-model="requisicion.folio" data-vv-name="folio" class="form-control" placeholder="Folio" autocomplete="off" id="folio" readonly>
                <span class="text-danger">{{ errors.first('folio') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="area_solicita_id">Área Solicitante</label>
              <div class="col-md-9">
                <select class="form-control" v-validate="'required'" id="area_solicita_id" name="area_solicita_id" v-model="requisicion.area_solicita_id" data-vv-name="área solicitante">
                  <option v-for="item in listaAreasSol" :value="item.id" :key="item.id">{{item.nombre}} </option>
                </select>
                <span class="text-danger">{{ errors.first('área solicitante') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="fecha_solicitud">Fecha Solicitud</label>
              <div class="col-md-9">
                <input type="date" name="fecha_solicitud" v-validate="'required'" v-model="requisicion.fecha_solicitud" class="form-control" placeholder="Fecha" autocomplete="off" data-vv-name="fecha solicitud" id="fecha_solicitud">
                <span class="text-danger">{{ errors.first('fecha solicitud') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="descripcion_uso">Descripcion de uso</label>
              <div class="col-md-9">
                <input type="text" name="descripcion_uso" v-validate="'required'" v-model="requisicion.descripcion_uso" class="form-control" placeholder="Uso" autocomplete="off" data-vv-name="descripcion uso" id="descripción uso">
                <span class="text-danger">{{ errors.first('descripcion uso') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="tipo_compra">Tipo de Compra</label>
              <div class="col-md-9">
                <select class="form-control" id="tipo_compra" v-validate="'required'" name="tipo_compra" v-model="requisicion.tipo_compra" data-vv-name="tipo de compra">
                  <option value="1">Programada</option>
                  <option value="2">Inmediata</option>
                </select>
                <span class="text-danger">{{ errors.first('tipo de compra') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="proyecto_id">Proyecto</label>
              <div class="col-md-9">
                <!--<select class="form-control" id="proyecto_id" v-validate="'required'" name="proyecto_id" v-model="requisicion.proyecto_id" data-vv-name="proyecto">
                  <option v-for="item in listaProyecto" :value="item.proyecto.id" :key="item.proyecto.id">{{item.proyecto.nombre_corto}} </option>
                </select>
                -->
                <v-select  id="proyecto" v-validate="'required'"  label="nombre_corto"   v-model="proyecto"
                           name="proyecto" :options="listaProyecto">
                </v-select>
                <span class="text-danger">{{ errors.first('proyecto') }}</span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 form-control-label" for="solicita_empleado_id">Solicitado por</label>
              <div class="col-md-9">
              <v-select :options="optionsvs" id="solicita_empleado_id" v-validate="'required'" name="solicita_empleado_id" v-model="requisicion.solicita_empleado_id" label="name" data-vv-name="empleado que solicita" ></v-select>
              <span class="text-danger">{{ errors.first('empleado que solicita') }}</span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3 form-control-label" for="valida_empleado_id">Revisado por</label>
            <div class="col-md-9">
            <v-select :options="optionsvs" id="valida_empleado_id" v-validate="'required'" name="valida_empleado_id" v-model="requisicion.valida_empleado_id" label="name" data-vv-name="empleado que valida" ></v-select>
            <span class="text-danger">{{ errors.first('validado por') }}</span>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="autoriza_empleado_id">Autorizado por </label>
          <div class="col-md-9">
            <v-select :options="optionsvs" id="autoriza_empleado_id" v-validate="'required'" name="autoriza_empleado_id" v-model="requisicion.autoriza_empleado_id" label="name" data-vv-name="empleado que autoriza" ></v-select>
            <span class="text-danger">{{ errors.first('empleado que autoriza') }}</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
        <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarRequisicion(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
        <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarRequisicion(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
      </div>
    </div>
  </div>
  <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal-->

</div>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      url: '/requisicion',
      proyecto:{},
      modal : 0,
      isLoading : false,
      tituloModal : '',
      tipoAccion : 0,
      principal : null,
      optionsvs : [],//
      requisicion: {
        id :0,
        folio	 :'',
        area_solicita_id : '',
        formato_requisiciones : '',
        fecha_solicitud: '',
        descripcion_uso	 : '',
        tipo_compra: '',
        proyecto_id: 0,
        estado_id : 0,
        solicita_empleado_id : '',
        autoriza_empleado_id : '',
        valida_empleado_id : '',
        recibe_empleado_id : '',
        condicion :0,
        Area : '',
      },
      listaAreasSol : [],//
      listaProyecto : [],//
    }
  },
  methods : {
    /**
    * [getListas description]
    * @return {[type]} [description]
    */
    getListas() {
      let me=this;
      axios.get('/departamento').then(response => {
        me.listaAreasSol = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
      axios.get('/proyecto').then(response => {
        me.listaProyecto = [];//response.data;
        console.clear();

        response.data.forEach(p=>
        {
          let proyecto={id: p.proyecto.id, nombre_corto: p.proyecto.nombre_corto};
          me.listaProyecto.push(proyecto);
        });
      })
      .catch(function (error) {
        console.log(error);
      });

      axios.get('/vertodosempleados').then(response => {
        for (var i = 0; i < response.data.length; i++) {
          this.optionsvs.push(
            {
              id:response.data[i].id,
              name:response.data[i].nombre+' '+response.data[i].ap_paterno+' '+response.data[i].ap_materno,
            });
          }
        })
        .catch(function (error) {
          console.log(error);
        });
      },

      /**
      * [guardarRequisicion description]
      * @param  {Int} nuevo [description]
      * @return {Response}       [description]
      */
      guardarRequisicion(nuevo){
        this.$validator.validate().then(result => {
          if (result) {
            this.isLoading = true;
            this.detalle = false;
            let me = this;
            axios({
              method: nuevo ? 'POST' : 'PUT',
              url: nuevo ? me.url : me.url+'/'+this.requisicion.id,
              data: {
                'id': this.requisicion.id,
                'folio': this.requisicion.folio,
                'area_solicita_id': this.requisicion.area_solicita_id,
                'fecha_solicitud': this.requisicion.fecha_solicitud,
                'descripcion_uso': this.requisicion.descripcion_uso,
                'tipo_compra': this.requisicion.tipo_compra,
                'proyecto_id': me.proyecto.id,
                'estado_id': this.requisicion.estado_id,
                'solicita_empleado_id': this.requisicion.solicita_empleado_id.id,
                'autoriza_empleado_id': this.requisicion.autoriza_empleado_id.id,
                'valida_empleado_id': this.requisicion.valida_empleado_id.id,
                'recibe_empleado_id': this.requisicion.recibe_empleado_id,
                'identificador': 0,
              }
            }).then(function (response) {
              me.isLoading = false;
              console.log(response.status);
              if (response.data.status) {
                me.cerrarModal();
                if(!nuevo){
                  toastr.success('Requisicion Actualizada Correctamente');
                  me.getData(me.principal);
                }
                else
                {
                  me.getPrincipal(response.data.data.proyecto_id);
                  toastr.success('Requisicion Registrada Correctamente');
                }
              } else {
                swal({
                  type: 'error',
                  html: response.data.errors.join('<br>'),
                });
                me.cerrarModal();
              }
            }).catch(function (error) {
              console.log(error);
            });
          }
          else {
            swal({
              title: 'Complete todos los campos',
              type: 'warning',
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Aceptar!',
              confirmButtonClass: 'btn btn-success',
              buttonsStyling: false,
              reverseButtons: true
            })
          }
        });
      },

      /**
      * [abrirModal description]
      * @param  {String} modelo    [description]
      * @param  {String} accion    [description]
      * @param  {Array}  [data=[]] [description]
      * @return {[type]}           [description]
      */
      abrirModal(modelo, accion, data = []){
        switch(modelo){
          case "requisicion":
          {
            switch(accion){
              case 'registrar':
              {
                this.modal= 1;
                this.tituloModal = 'Registrar requisición';
                Utilerias.resetObject(this.requisicion);
                this.tipoAccion = 1;
                this.disabled=0;
                break;
              }
              case 'actualizar':
              {
                let me=this;
                console.error(data);
                this.principal = data['proyecto_id'];
                Utilerias.resetObject(this.requisicion);
                this.modal=1;
                this.tituloModal='Actualizar requisición';
                this.requisicion.id=data['id'];
                this.tipoAccion=2;
                this.disabled=1;
                this.requisicion.folio = data['folio'];
                this.requisicion.area_solicita_id = data['area_solicita_id'];
                this.requisicion.fecha_solicitud = data['fecha_solicitud'];
                this.requisicion.descripcion_uso = data['descripcion_uso'];
                this.requisicion.tipo_compra = data['tipo_compra'];
                this.proyecto={ id:data['proyecto_id'],nombre_corto: data['p_nombre_corto']}  ;
                this.requisicion.estado_id = data['estado_id'];
                this.requisicion.solicita_empleado_id = {id:data['solicita_empleado_id'], name: data['nombre_empleado_solicita']};
                this.requisicion.autoriza_empleado_id = {id:data['autoriza_empleado_id'], name:data['nombre_empleado_autoriza']};
                this.requisicion.valida_empleado_id = {id:data['valida_empleado_id'],name:data['nombre_empleado_valida']};
                // this.requisicion.recibe_empleado_id	 = data['recibe_empleado_id'];
                break;
              }
            }
          }
        }
      },

      /**
      * [cerrarModal description]
      * @return {} [description]
      */
      cerrarModal(){
        this.modal=0;
        this.tituloModal='';
        Utilerias.resetObject(this.requisicion);
      },

      getData(data)
      {
        this.$emit('modal:click',data);

      },

      getPrincipal(data){
        this.modal = 0;
        this.$emit('modal:nuevo',data);
      },
  },
  mounted () {
    this.getListas();
  }
}
</script>
<style >
.style-chooser .vs__search::placeholder,
  .style-chooser .vs__dropdown-toggle,
  .style-chooser .vs__dropdown-menu {
    background: #dfe5fb;
    border: none;
    color: #394066;
    text-transform: lowercase;
    font-variant: small-caps;
  }

  .style-chooser .vs__clear,
  .style-chooser .vs__open-indicator {
    fill: #394066;
  }
</style>
