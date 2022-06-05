<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Formulario de busqueda -->
      <br>
      <div class="card" v-show="!detalle">
        <div class="card-header">

          <i class="fa fa-align-justify"></i> Buscar salidas por proyecto.
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-2">
              <label class="form-control-label" >Proyecto:</label>
            </div>
            <div class="col-4">
              <!-- {{proyectoId}} -->
              <v-select :options="listaProyectos" label="name" v-model="proyectoId"></v-select>

            </div>
            <div class="col-2">
              <button class="btn btn-success btn-block" @click="getData()">Buscar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Fin Formulario de busqueda -->
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card" v-show="!detalle" >
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Salidas Taller
          <button type="button" @click="abrirModal('salida','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>
          <div v-show="tablas.taller">
            <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
              <template slot="descargar" slot-scope="props">
                <button v-show="PermisosCRUD.Download" type="button" class="btn btn-outline-dark" @click="descargarnew(props.row)" >
                  <i class="fas fa-file-pdf"></i>  <i class="fas fa-download"></i>&nbsp;
                </button>
              </template>
              <template slot="fecha" slot-scope="props">
                {{fecha_enletra(props.row.fecha)}}
              </template>
              <!-- Como usar un if cuando se tiene 2 condiciones-->
              <template slot="condicion" slot-scope="props" >
                <template v-if="props.row.condicion == 1">
                  <button type="button" class="btn btn-outline-success">Activo</button>
                </template>
                <template v-if="props.row.condicion == 0">
                 <button type="button" class="btn btn-outline-danger">Dado de Baja</button>
                </template>
              </template>
              <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group dropup" role="group">
                      <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-grip-horizontal"></i>&nbsp;
                      </button>
                      <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <button v-show="PermisosCRUD.Read" type="button" @click="cargardetallesalida(props.row)" class="dropdown-item" >
                  <i class="fas fa-eye"></i>&nbsp; Ver detalle
                </button>

                <button v-show="PermisosCRUD.Update" type="button" @click="abrirModal('salida','actualizar',props.row)" class="dropdown-item" >
                  <i class="icon-pencil"></i>&nbsp; Actualizar Salida
                </button>

                <!-- <template v-if="props.row.condicion">
                  <button v-show="PermisosCRUD.Delete" type="button" class="dropdown-item" @click="actdesact(props.row.id, 0)" >
                    <i class="icon-trash"></i>&nbsp;Desactivar
                  </button>
                </template>
                <template v-else>
                  <button type="button" class="dropdown-item" @click="actdesact(props.row.id, 1)" >
                    <i class="icon-check"></i>&nbsp;Activar
                  </button>
                </template> -->

                <template >
                  <!-- <button v-show="PermisosCRUD.Download" type="button" class="dropdown-item" @click="descargar(props.row)" >
                    <i class="fas fa-file-pdf"></i>&nbsp; Descargar PDF Salida
                  </button> -->

                </template>
                      </div>
                    </div>
              </div>

              </template>
            </v-client-table>
          </div>
        </div>

      </div>
      <!-- Fin ejemplo de tabla Listado -->
      <div v-show="detalle">
        <salidastallerdetalle ref="salidastallerdetalle" @salidastallerdetalle:change="maestro"></salidastallerdetalle>
      </div>

      </div>
      <!--Inicio del modal agregar/actualizar-->
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
                  <label class="col-md-3 form-control-label" for="proyecto_id">Proyecto</label>
                  <div class="col-md-9">
                    <!-- <select class="form-control" id="proyecto_id"  name="proyecto_id" v-model="salida.proyecto_id"  v-validate="'excluded:0'" data-vv-as="Proyecto">
                      <option v-for="item in listaProyectos" :value="item.proyecto.id" :key="item.id">{{item.proyecto.nombre_corto}}</option>
                    </select> -->
                    <v-select :options="listaProyectos" label="name" v-model="salida.proyecto_id" v-validate="'required'" data-vv-name="Proyecto"></v-select>

                    <span class="text-danger">{{ errors.first('Proyecto') }}</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="fecha">Fecha</label>
                  <div class="col-md-9">
                    <input type="date" name="fecha" v-model="salida.fecha" class="form-control" placeholder="Fecha de Entrada" autocomplete="off" id="fecha">
                    <span class="text-danger">{{ errors.first('fecha') }}</span>
                  </div>
                </div>

                <!-- <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="folio">Folio</label>
                  <div class="col-md-9">
                    <input type="text" name="folio" v-validate="'required'" v-model="salida.folio" class="form-control" placeholder="Folio" autocomplete="off" id="folio">
                    <span class="text-danger">{{ errors.first('folio') }}</span>
                  </div>
                </div> -->

                <div class="form-group row">
                  <label class="col-md-3 form-control-label"  for="ubicacion">Ubicacion</label>
                  <div class="col-md-9">
                    <input type="text" name="ubicacion" v-validate="'required'" v-model="salida.ubicacion" class="form-control" placeholder="Ubicacion" autocomplete="off" id="ubicacion">
                    <span class="text-danger">{{ errors.first('ubicacion') }}</span>
                  </div>
                </div>



                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="tiposalida_id">Tipo Salida</label>
                  <div class="col-md-9">
                    <select class="form-control" id="tiposalida_id"  name="tiposalida_id" v-model="salida.tiposalida_id" v-bind:disabled="desabilitado == 1" v-validate="'excluded:0'" data-vv-as="Salida">
                      <option v-for="item in listaTipoSalida" :value="item.id" :key="item.id">{{item.nombre}}</option>
                    </select>
                    <span class="text-danger">{{ errors.first('tiposalida_id') }}</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="empleado_id">Nombre solicitante:</label>
                  <div class="col-md-9">
                    <v-select :options="optionsvs" v-model="salida.empleado_id" name="empleado_id" label="name" v-validate="'excluded:0'" data-vv-as="Empleado" ></v-select>
                    <span class="text-danger">{{ errors.first('empleado_id') }}</span>
                  </div>
                </div>
                <!-- </form> -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-ouline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarSalida(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarSalida(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
  const SalidasTallerDetalle = r => require.ensure([], () => r(require('./SalidasTallerDetalle.vue')), 'alm');

  import Utilerias from '../../Herramientas/utilerias.js';
  var config = require('../../Herramientas/config-vuetables-client').call(this);

  export default {
    data (){
      return {
        PermisosCRUD : {},
        url: '/salida',
        proyectoId: 0,
        salidav : null,
        detalle : false,
        tituloprincipal : '',
        optionsvs: [],
        desabilitado : 0,
        tituloempleado : '',
        valorfecha:'',
        tablas : {
          taller : true,
          sitio : false,
          resguardo : false,
        },
        salida: {
          fecha :'',
          folio  :'',
          format_salida : '',
          ubicacion: '',
          proyecto_id: 0,
          tiposalida_id: 0,
          empleado_id : '',
          condicion :0,
        },
        listaProyectos: [],
        listaTipoSalida: [],
        modal : 0,
        tituloModal : '',
        tipoAccion : 0,
        isLoading: false,
        isLoadingg: false,
        columns: ['id','fecha','folio','nombrec','salidan','empleado','descargar','condicion'],
        tableData: [],

        options: {
          headings: {
            id: 'Acciones',
            fecha: 'Fecha',
            folio: 'Folio',
            nombrec: ' Proyecto',
            salidan: 'Tipo de Salida',
            empleado: 'Solicita',
            condicion: 'Estado',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['fecha','folio','condicion'],
          filterable: ['fecha','folio','empleado'],
          filterByColumn: true,
          listColumns: {
            condicion: [{
              id: 1,
              text: 'Activo'
            },
            {
              id: 0,
              text: 'Dado de Baja'
            }
          ]
        },
        texts:config.texts
      },

}
},
computed:{
},
components : {
  'salidastallerdetalle' : SalidasTallerDetalle,
},
methods : {

  fecha_enletra(fecha){
    const meses = [
      "Enero", "Febrero", "Marzo",
      "Abril", "Mayo", "Junio", "Julio",
      "Agosto", "Septiembre", "Octubre",
      "Noviembre", "Diciembre"
    ];

    const date = new Date(fecha);
    const dia = date.getDate() + 1;
    const mes = date.getMonth();
    const ano = date.getFullYear();
    return `${dia} de ${meses[mes]} del ${ano}`;
  },

  /**
   * [getData Obtiene todas la salidas a taller del un proyecto en especifico o de todos los exixtentes]
   * @return {[type]} [description]
   */
  getData() {
    if (this.proyectoId == 0 || this.proyectoId == null) {
      toastr.warning('Seleccione proyecto...!!!');
    }else {
    this.isLoading = true;
    let me = this;
    axios.get(me.url+'/'+this.proyectoId.id).then(function (response) {
      me.isLoading = false;
      me.tableData = response.data;

    }).catch(function (error) {
      console.log(error);
      me.isLoading = false;
    });
  }
  },

  /**
   * [getListaEmpleados Obtiene todos los empleado existentes y se almacenan en un objeto para se ocupados en el v-select]
   * @return {[type]} [description]
   */
  getListaEmpleados() {
    let me= this;
    axios.get('/empleado').then(response => {
      for (var i = 0; i < response.data.length; i++) {
        this.optionsvs.push(
          {
            id:response.data[i].empleado.id,
            name:response.data[i].empleado.nombre+' '+response.data[i].empleado.ap_paterno+' '+response.data[i].empleado.ap_materno,
            puesto:response.data[i].puesto.nombre
          });
        }
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    /**
     * [getProyecto Obtiene todos los proyectos existentes]
     * @return {[type]} [description]
     */
    getProyecto() {
      this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
      let me=this;
      axios.get('/proyecto').then(response => {
        response.data.forEach(value =>{
          me.listaProyectos.push({
            id : value.proyecto.id,
            name : value.proyecto.nombre_corto
          });
        });
        // me.listaProyectos = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    /**
     * [getTipoSalida Obtiene los tipos de salidas exixtentes]
     * @return {[type]} [description]
     */
    getTipoSalida() {
      this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
      let me=this;
      axios.get('/tiposalida').then(response => {
        me.listaTipoSalida = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    fechaActual(){
      let me=this;
      axios.get('/FechaActual').then(response => {
        this.valorfecha = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    /**
     * [validarFecha Valida que la fecha seleccionada no se menor a la actual]
     * @param  {Date} dato [description]
     * @return {[type]}      [description]
     */
    validarFecha(dato){
      if (dato < this.valorfecha) {
        toastr.error('La Fecha no Puede ser Anterior a la Actual');
        this.salida.fecha = this.valorfecha;
      }
    },

    /**
     * [guardarSalida Guardado y actualizacion de los encabezados de las salidas a taller]
     * @param  {String} nuevo [description]
     * @return {Response}       [description]
     */
    guardarSalida(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? me.url : me.url+'/'+this.id,
            data: {
              'id': this.salida.id,
              'fecha': this.salida.fecha,
              'folio': this.salida.folio,
              'ubicacion': this.salida.ubicacion,
              'proyecto_id': this.salida.proyecto_id.id,
              'tiposalida_id': this.salida.tiposalida_id,
              'empleado_id': this.salida.empleado_id.id,
            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              me.proyectoId = me.salida.proyecto_id,
              me.cerrarModal();
              me.getData();
              toastr.success('Correcto',nuevo ? 'Salida registrada correctamente' : 'Salida actualizada correctamente');
            } else {
              swal({
                type: 'error',
                html: response.data.errors.join('<br>'),
              });
            }
          }).catch(function (error) {
            console.log(error);
          });
        }
      });
    },

    /**
     * [actdesact Activa y desactiva una salida cambiando el campo condicion del mismo]
     * @param  {Int} id      [description]
     * @param  {String} activar [description]
     * @return {[type]}         [description]
     */
    actdesact(id,activar){
      if(activar){
        this.swal_titulo = 'Esta seguro de activar esta Salida?';
        this.swal_tle = 'Activado';
        this.swal_msg = 'El registro ha sido activado con éxito.';
      }else{
        this.swal_titulo = 'Esta seguro de desactivar esta Salida?';
        this.swal_tle = 'Desactivado!';
        this.swal_msg = 'El registro ha sido desactivado con éxito.';
      }
      swal({
        title: this.swal_titulo,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar!',
        cancelButtonText: 'Cancelar',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          let me = this;
          axios.get(me.url+'/'+id+'/edit').then(function (response) {
            swal(me.swal_tle,me.swal_msg,'success')
            me.getData();
          }).catch(function (error) {
            console.log(error);
          });
        } else if (
          result.dismiss === swal.DismissReason.cancel
        ) {

        }
      })
    },

    cerrarModal(){
      this.modal=0;
      this.modala = 0;
      this.tituloModal='';
      Utilerias.resetObject(this.salida);
    },

    /**
     * [cargardetallesalida Se carga el componente salidastallerdetalle]
     * @param  {Array}  [dataSalida=[]] [description]
     * @return {[type]}                 [description]
     */
    cargardetallesalida (dataSalida = []) {
      this.detalle = true;
      var ChildDetalleSalidaTaller = this.$refs.salidastallerdetalle;
      ChildDetalleSalidaTaller.cargardetallesalida(dataSalida);

    },

    maestro(){
      this.detalle = false;
      // this.cancelar();
    },

    /**
     * [abrirModal Abre los modales de actualizacion y llenado dependiendo de la acción especificada]
     * @param  {String} modelo    [description]
     * @param  {String} accion    [description]
     * @param  {Array}  [data=[]] [description]
     * @return {[type]}           [description]
    */
    abrirModal(modelo, accion, data = []){
      var ts = data['tiposalida_id'];
      switch(modelo){
        case "salida":
        {
          switch(accion){
            case 'registrar':
            {
              let me = this;
              this.modal = 1;
              this.tituloModal = 'Registrar Salida';
              Utilerias.resetObject(this.salida);
              this.tipoAccion = 1;
              this.desabilitado = 1;
              this.salida.tiposalida_id = 1;
              ///me.fechaActual();
              this.salida.fecha = this.valorfecha;
              break;
            }
            case 'actualizar':
            {
              Utilerias.resetObject(this.salida);
              this.modal=1;
              this.desabilitado = 1;
              this.tituloModal='Actualizar Salida';
              this.salida.id=data['id'];
              this.tipoAccion=2;
              this.salida.fecha = data['fecha'];
              this.salida.folio = data['folio'];
              this.salida.ubicacion = data['ubicacion'];
              this.salida.proyecto_id = {id : data['proyecto_id'], name : data['nombrec']};
              this.salida.tiposalida_id = data['tiposalida_id'];

              this.salida.empleado_id = {name:data.empleado,id:data.empleado_id};

              break;
            }
          }
        }
      }
    },

    /**
     * [descargar Descarga el respectivo pdf de la salida]
     * @param  {Array} data [description]
     * @return {[type]}      [description]
     */
    descargar(data) {
      window.open('descargar-salida/' + data.id, '_blank');
      let me = this;
      me.getData();
    },

    /**
     * [descargar Descarga el respectivo pdf de la salida]
     * @param  {Array} data [description]
     * @return {[type]}      [description]
     */
    descargarnew(data) {
      window.open('descargar-salida-new/' + data.id, '_blank');
      let me = this;
      me.getData();
    }

  },
  mounted() {
    this.getProyecto();
    this.getTipoSalida();
    this.fechaActual();
    this.getListaEmpleados();
  }
}
</script>
