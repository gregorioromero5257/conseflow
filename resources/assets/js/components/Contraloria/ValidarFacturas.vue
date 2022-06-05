<template>
  <main class="main">

  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Validar facturas O.C.
      
      </div>
      <div class="card-body">
        <v-client-table :data="tableData" :columns="columns" :options="options">
          <template slot="comprobante" slot-scope="props">
            <template v-if="props.row.comprobante != ''">
              <button type="button" class="form-control" @click="descargar(props.row.comprobante)">
                <i class="fas fa-file-download"></i>&nbsp;Descargar
              </button>
            </template>
            <template v-if="props.row.comprobante == ''">
              <span>Vacio</span>
            </template>
          </template>
          <template slot="tipo_factura" slot-scope="props">
            <template v-if="props.row.tipo_factura == 0">
              <span class="text-success">Ingreso</span>
            </template>
            <template v-if="props.row.tipo_factura == 1">
              <span class="text-success">Pago</span>
            </template>
          </template>
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button" class="dropdown-item" >
                    <i class="icon-check"></i>&nbsp;Autorizar
                  </button>
                  <button type="button"  class="dropdown-item" >
                    <i class="icon-trash"></i>&nbsp;Rechazar
                  </button>
                </div>
              </div>
            </div>
          </template>
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
              <h4 class="modal-title">Agregar factura</h4>
              <button type="button" class="close" @click="modal = 0" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-row">
                <div class="form-group col-md-10">
                  <label>Proveedor</label>
                    <v-select :options="listaProvedores"  id="proveedore_id" v-validate="'required'" name="proveedore_id"
                     v-model="factura.proveedore_id" label="name" data-vv-name="Proveedor" ></v-select>
                    <span class="text-danger">{{ errors.first('Proveedor') }}</span>
              </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>Total</label>
                    <input type="text" class="form-control" v-model="factura.total" >
                </div>
                <div class="form-group col-md-4">
                  <label>Impuesto</label>
                  <input type="text" class="form-control" v-model="factura.impuestos" >
                </div>
                <div class="form-group col-md-4">
                  <label>Tipo Factura</label>
                    <select class="form-control" v-model="factura.tipo_factura">
                      <option value="0">Ingreso</option>
                      <option value="1">Pago</option>
                    </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Uso Factura </label>
                  <select class="form-control" v-model="factura.uso_factura" >
                    <option v-for="item in UsoFactura" :value="item.id">{{item.c_uso}}  {{item.descripcion}}</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <template v-if="tipoAccion == 1">
                    <label >Comprobante</label>
                    <input type="file" class="form-control" name="comprobante"  @change="onChangeComprobante($event)">
                  </template>
                  <template v-if="tipoAccion == 2">
                  <template v-if="factura.comprobante != ''">
                      <label>&nbsp;</label>
                    <button type="button" class="form-control" @click="factura.comprobante = ''">
                      <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                    </button>
                  </template>
                  <template v-if="factura.comprobante == ''">
                      <label>Comprobante</label>
                      <input type="file" class="form-control" name="comprobante" @change="onChangeComprobante($event)">
                  </template>
                </template>
                </div>
              </div>



            </div>
            <div class="modal-footer">
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarFactura(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarFactura(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data() {
    return {
      modal : 0,
      tableData : [],
      listaProvedores : [],
      UsoFactura : [],
      tipoAccion : 0,
      columns : ['id','foliocompra','proveedor_name','total','impuesto','tipo_factura','descripcion','comprobante'],
      options: {
        headings: {
          'id': 'Acciones',
          'proveedor_name': 'Proveedor',
          'foliocompra' : 'O.C.'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['foliocompra','proveedor_name','total','impuestos','tipo_factura','descripcion','comprobante'],
        filterable: ['foliocompra','proveedor_name','total','impuestos','tipo_factura','descripcion','comprobante'],
        filterByColumn: true,
        texts:config.texts
      },
      factura : {
        id : '',
        proveedore_id : '',
        total : '',
        impuestos : '',
        tipo_factura : '',
        uso_factura: '',
        comprobante : '',
      },
      entrada_id : '',
    }
  },
  methods : {
    /**
    **
    **
    **/
    cargar(data,listaProvedores, UsoFactura){
      console.log(data);
      this.getData(data.id);
      this.listaProvedores = listaProvedores;
      this.UsoFactura = UsoFactura;
      this.entrada_id = data.id;

    },

    getData(id){
      axios.get(`facturasentradas`).then(response => {
        this.tableData = response.data;
      }).catch(error => {
        console.error(error);
      });
    },

    /**
    **
    **/
    maestro()
    {
      this.$emit('atras:facturas');
    },

    descargar(archivo){
      let me=this;
      axios({
        url: '/facturasentradasdescarga/' + archivo,
        method: 'GET',
        responseType: 'blob', // importante
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
        document.body.appendChild(link);
        link.click();
        //--Llama el metodo para borrar el archivo local una ves descargado--//
        axios.get('/facturasentradaseditar/' + archivo)
        .then(response => {
        })
        .catch(function (error) {
          console.log(error);
        });
        //--fin del metodo borrar--//
      });
    },

    abrirModal(modelo, accion, data = []){
      switch (modelo) {
        case 'factura':
        switch (accion) {
          case 'registrar':
          {
            this.modal = 1;
            this.tipoAccion = 1;
            Utilerias.resetObject(this.factura);
            break;
          }
          case 'actualizar' :
          {
            this.modal = 1;
            this.tipoAccion = 2;
            Utilerias.resetObject(this.factura);
            this.factura.id = data['id'];
            this.entrada_id = data['entrada_id'];
            this.factura.proveedore_id = {id : data['proveedore_id'],name : data['proveedor_name']};
            this.factura.total = data['total'];
            this.factura.impuestos = data['impuesto'];
            this.factura.tipo_factura = data['tipo_factura'];
            this.factura.uso_factura = data['uso_factura_id'];
            this.factura.comprobante = data['comprobante'];
          }
        }
      }
    },

    onChangeComprobante(e){
      this.factura.comprobante = e.target.files[0];
    },

    guardarFactura(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          let formData = new FormData();
          formData.append('id', this.factura.id);
          formData.append('entrada', this.entrada_id);
          formData.append('proveedore_id', this.factura.proveedore_id.id);
          formData.append('total', this.factura.total);
          formData.append('impuestos', this.factura.impuestos);
          formData.append('tipo_factura', this.factura.tipo_factura);
          formData.append('uso_factura' , this.factura.uso_factura);
          formData.append('comprobante' , this.factura.comprobante);

          axios({
            method: nuevo ? 'POST' : 'POST',
            url: nuevo ? 'facturasentradas' : 'facturasentradas/update',
            data: formData
          }).then(function (response) {
              me.isLoading = false;
              me.modal = 0;
              me.getData(response.data.entrada_id);
              if(!nuevo){
                toastr.success('Factura Actualizada Correctamente');
              }
              else
              {
                toastr.success('Factura Registrada Correctamente');
              }
          }).catch(function (error) {
            console.log(error);
          });
        }
      });
    },

    eliminar(data){
      axios.get('facturasentradas/delete/'+data).then(response => {
        this.getData(this.entrada_id);
      }).catch(error => {
        console.error(error);
      })
    }

  },
  mounted () {
    this.getData();
  }
}
</script>
