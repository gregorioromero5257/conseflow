<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card">
      
        <vue-element-loading :active="isLoading"/>
        <div class="card-header">
          <i class="fa fa-align-center"></i> Control de Cotizaciones.
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-4">
              <label class="form-control-label" for="proyecto_id">Seleccionar:</label>
              <!--<select class="form-control" id="proyecto_id" name="proyecto_id" v-model="proyecto_id" v-validate="'required'" data-vv-as="Proyecto">
                <option value="0">- - -Proyectos- - -</option>
                <option v-for="item in listaProyectos" :value="item.id" :key="item.id">{{ item.nombre}}</option>
              </select>
              -->
              <v-select id="proyectos" name="proyectos" label="nombre_corto" v-model="proyecto"
                  :options="listaProyectos">
              </v-select>
              <span class="text-danger">{{ errors.first('proyecto_id') }}</span>&nbsp;
            </div>

            <div class="col-4">
              <label class="form-control-label" for="proveedor_id">Seleccionar:</label>
              <!--<select class="form-control" id="proveedor_id" name="proveedor_id" v-model="cotizacion.proveedor_id" v-validate="'required'" data-vv-as="Proyecto">
                <option value="0">---Proveedores---</option>
                <option v-for="item in listaProveedores" :value="item.id" :key="item.id">{{ item.razon_social}}</option>
              </select>
              -->
              <v-select id="proveedores" name="proveedores" v-model="proveedor" :options="listaProveedores" label="nombre">
              </v-select>
              <span class="text-danger">{{ errors.first('proveedor_id') }}</span>&nbsp;
            </div>

            <div class="col-4">
              <label class="form-control-label">Busqueda:</label>
              <div>
                <button type="button" class="btn btn-primary btn-block" @click="buscarCotizaciones()">Buscar</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Cotizaciones
          <button type="button" @click="abrirModal('cotizacion','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">


            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
               <div class="btn-group dropup" role="group">
                 <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <i class="fas fa-grip-horizontal"></i> Acciones
                 </button>
                 <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
              <button type="button" @click="abrirModal('cotizacion','actualizar',props.row)" class="dropdown-item">
                <i class="icon-pencil"></i>&nbsp;Actualizar cotización.
              </button>
              </div>
              </div>
              </div>

            </template>
          </v-client-table>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
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
              <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
              <input type="hidden" name="id">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="folio">Folio</label>
                <div class="col-md-9">
                  <input type="text" v-validate="'required'" name="folio" v-model="cotizacion.folio" class="form-control" placeholder="Folio" autocomplete="off" id="folio">
                  <span class="text-danger">{{ errors.first('folio') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="adjunto">Adjunto</label>
                <div class="col-md-9">
                  <input type="file" name="adjunto" class="form-control" accept="application/pdf" 
                  placeholder="Adjunto" autocomplete="off" id="adjunto"
                      v-validate="'required'">
                    <span class="text-danger">{{ errors.first('adjunto') }}</span>

                  <!-- v-model="cotizacion.adjunto"e s -->
                </div>
              </div>
              <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="proveedor_id">Proveedor</label>
                  <div class="col-md-9">
                      <v-select id="proveedores" name="proveedores" v-model="proveedor" :options="listaProveedores" label="nombre">
                      </v-select>
                      <span class="text-danger">{{ errors.first('proveedor_id') }}</span>
                  </div>
              </div>

              <!-- </form> -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarCotizacion(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarCotizacion(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      proyecto:{},
      proveedor:{},
      url: '/cotizacion',
      proyecto_id: 0,
      listaProyectos: [],
      cotizacion: {
        folio :'',
        adjunto :'',
        proveedor_id: 0,
      },
      listaProveedores: [],
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      isLoading: false,
      columns: ['id','folio','adjunto', 'proveedor'],
      tableData: [],
      options: {
        headings: {

          id: 'Acciones',
          folio: 'Folio',
          adjunto: 'Adjunto',
          proveedor: 'Proveedor',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['folio','adjunto','proveedor'],
        filterable: ['folio','adjunto','proveedor'],
        filterByColumn: true,
      texts:config.texts
  },
}
},
computed:{
},
methods : {
  getData() {
    let me=this;
    axios.get(me.url).then(response => {
      me.tableData = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
    axios.get('/proveedores').then(response => {
        // me.listaProveedores = response.data;
        let aux={id:0,nombre:"Seleccione proveedor"};
        me.listaProveedores.push(aux);
        me.proveedor=aux;
        response.data.forEach(p=>
        {
          me.listaProveedores.push
          ({
              id:p.id,
              nombre:p.razon_social,
          });
        });
    })
    .catch(function (error) {
        console.log(error);
    });

    axios.get('/proyectos').then(response => {
        let aux={id:0,nombre_corto:"Seleccione proyecto"};
        me.listaProyectos.push(aux);
        me.proyecto=aux;
        response.data.forEach(p=>
        {
          me.listaProyectos.push
          ({
              id:p.id,
              nombre_corto:p.nombre_corto,
          });
        });
      me.isLoading = false;
    })
    .catch(function (error) {
      console.log(error);
    });
  },

  guardarCotizacion(nuevo){
    this.$validator.validate().then(result => {
      if (result) {
        this.isLoading = true;
        let me = this;
        axios({
          method: nuevo ? 'POST' : 'PUT',
          url: nuevo ? me.url : me.url+'/'+this.cotizacion.id,
          data: {
            'id': this.cotizacion.id,
            'folio': this.cotizacion.folio,
            'adjunto': this.cotizacion.adjunto,
            'proveedor_id' : this.proveedor.id,
          }
        }).then(function (response) {
          me.isLoading = false;
          if (response.data.status) {
            me.cerrarModal();
            me.getData();
            if (!nuevo) {
              toastr.success('Cotización Actualizado Correctamente');
            }else {
              toastr.success('Cotización Agregado Correctamente');
            }
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

  buscarCotizaciones() {
    if (this.proyecto.id == 0) {
      toastr.warning('Atención', 'Seleccione un proyecto');
    }
    if (this.proveedor.id == 0) {
      console.error(this.proveedor.id);
      toastr.warning('Atención', 'Seleccione un proveedor');
    }
    if(this.proyecto.id != 0 && this.proveedor.id != 0) {
      console.log("entró");
      this.isLoading = true;
      let me = this;
      axios({
        method: 'GET',
        url: '/documentosEntradas/' + this.proyecto.id,
        data: {
          'proyecto_id': this.proyecto.id,
        }
      }).then(function (response) {
          me.isLoading = false;
          me.tableData = response.data;
          console.log(response.data);
      }).catch(function (error) {
          console.log(error);
          me.isLoading = false;
      });
    }
  },

  cerrarModal(){
    this.modal=0;
    this.tituloModal='';
    Utilerias.resetObject(this.cotizacion);
  },

  abrirModal(modelo, accion, data = []){
    switch(modelo){
      case "cotizacion":
      {
        switch(accion){
          case 'registrar':
          {
            this.modal = 1;
            this.tituloModal = 'Registrar Cotizacion';
            Utilerias.resetObject(this.cotizacion);
            this.tipoAccion = 1;
            break;
          }
          case 'actualizar':
          {
            this.modal=1;
            this.tituloModal='Actualizar Cotizacion';
            this.cotizacion.id=data['id'];
            this.tipoAccion=2;
            this.cotizacion.folio = data['folio'];
            this.cotizacion.adjunto = data['adjunto'];
            this.proveedor.id = data['proveedor_id'];
            break;
          }
        }
      }
    }
  }
},
mounted() {
  this.getData();
  this.$root.limpiarDashboard();


}
}
</script>
