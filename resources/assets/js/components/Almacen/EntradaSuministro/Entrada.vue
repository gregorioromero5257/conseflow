<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Entradas Suministros
          <button type="button" @click="abrirModal('entrada','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <v-client-table :options="options" :data="tableData" :columns="columns">
          </v-client-table>
        </div>
      </div>

      <!--Inicio del modal Mostrar Lote-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div>
              <!-- <vue-element-loading :active="isLoadingc"/> -->
              <div class="modal-header">
                <h4 class="modal-title">{{titulo_modal}}</h4>
                <button type="button" class="close" @click="modal = 0" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>Folio</label>
                    <input type="text" class="form-control" v-model="entrada.folio" >
                  </div>
                  <div class="form-group col-md-4">
                    <label>Fecha recepción</label>
                    <input type="date" class="form-control" v-model="entrada.fecha" >
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>Cliente</label>
                    <v-select :options="listaClientes" v-model="entrada.cliente" label="name"></v-select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label>Proyecto</label>
                    <v-select :options="listaProyectos" v-model="entrada.proyecto" label="name"></v-select>
                  </div>
                </div>

                <!-- folio-fecha recepción - cliente - proyecto  -->

              </div>
              <div class="modal-footer">
                <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                <button type="button" class="btn btn-outline-dark" @click="modal = 0"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal agregar almacen-->



    </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data() {
    return {
      modal : 0,
      tipoAccion : 0,
      titulo_modal : '',
      entrada : {
        id : '',
        folio : '',
        fecha : '',
        cliente : '',
        proyecto : '',
      },
      listaClientes : [],
      listaProyectos : [],
      tableData : [],
      columns : [],
      options : {
        headings: {
          'id': 'Acciones',
          'proveedor_name': 'Proveedor',
          'total' : 'Total',
          'descripcion' : 'Uso de CFDI',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['proveedor_name','uuid','total','descripcion','comprobante'],
        filterable: ['proveedor_name','uuid','total','descripcion','comprobante'],
        filterByColumn: true,
        texts:config.texts
      }
    }
  },
  methods : {
    getData(){
      axios.get('clientes').then(response =>{
        response.data.forEach((item, i) => {
            this.listaClientes.push({
              id : item.id,
              name : item.nombre,
            });
        });
      }).catch(error => {
        console.error(error);
      });

      axios.get('/proyecto-listar-todos').then(response => {
        this.listaProyectos = [];
        response.data.forEach(data => {
          this.listaProyectos.push({id: data.id,
             name: data.nombre_corto,
             });
        });
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    getEntradas(){
      axios.get('entradas/suministros').then(response => {
        console.log(response);
      }).catch(e => {
        console.error(e);
      });
    },

    abrirModal(modelo, accion, data =[]){
      switch(modelo) {
        case 'entrada' :
          switch(accion) {
            case 'registrar' :
            {
              Utilerias.resetObject(this.entrada);
              this.modal = 1;
              this.tipoAccion = 1;
              this.titulo_modal = 'Registrar Entrada Suministros';
            }
            case 'actualizar' :
            {
              Utilerias.resetObject(this.entrada);
              this.modal = 1;
              this.tipoAccion = 2;
              this.titulo_modal = 'Actualizar Entrada Suministros';
              this.entrada.id = data['id'];
              this.entrada.folio = data['folio'];
              this.entrada.fecha = data['fecha'];
              this.entrada.cliente = { id : data['cliente_id'], name : ['nombre_cliente']};
              this.entrada.proyecto = {id : data['proyecto_id'], name : ['nombre_corto']};
            }
          }
      }
    },

    guardar(nuevo){
      axios({
        method : nuevo ? 'POST' : 'POST',
        url : nuevo ? 'entradas/suministros/guardar' : 'entradas/suministros/actualizar',
        data : {
          id : this.entrada.id,
          folio : this.entrada.folio,
          fecha : this.entrada.fecha,
          cliente : this.entrada.cliente.id,
          proyecto : this.entrada.proyecto.id,
        }
      }).then(response => {
        toastr.success(nuevo ? 'Entrada Guardada Correctamente' : 'Entrada Actualizada Correctamente');
      }).catch(e => {
        console.error(e);
      })
    }

  },
  mounted () {
    this.getData();
  },
}

</script>
