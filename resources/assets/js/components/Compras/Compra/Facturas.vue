<template>
  <div>
    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Facturas de la {{data_compra == null ? '' : data_compra.folio}}
        <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
          <i class="fas fa-arrow-left"></i>&nbsp;Atras
        </button>
        <button type="button" :disabled="estado == 3" @click="abrirModal('factura','registrar')" class="btn btn-dark float-sm-right">
          <i class="fas fa-plus"></i>&nbsp;Nuevo
        </button>
      </div>
      <div class="card-body">
        <v-client-table :data="tableData" :columns="columns" :options="options">
          <template slot="proveedor_name" slot-scope="props">
            <template v-if="props.row.proveedor_name != null">
            <span>{{props.row.proveedor_name}}</span>
            </template>
            <template v-if="props.row.proveedor_name == null">
              <span>{{props.row.emisor}}</span>
            </template>
          </template>

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

          <template slot="xml" slot-scope="props">
            <template v-if="props.row.xml != ''">
              <button type="button" class="form-control" @click="descargar(props.row.xml)">
                <i class="fas fa-file-download"></i>&nbsp;Descargar
              </button>
            </template>
            <template v-if="props.row.xml == ''">
              <span>Vacio</span>
            </template>
          </template>

          <template slot="id" slot-scope="props" v-if="estado != 3">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i>&nbsp;
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button"  @click="abrirModal('factura','actualizar',props.row)" class="dropdown-item" >
                    <i class="icon-pencil"></i>&nbsp;Actualizar
                  </button>
                  <button type="button"  @click="eliminar(props.row.id)" class="dropdown-item" >
                    <i class="icon-trash"></i>&nbsp;Eliminar
                  </button>
                </div>
              </div>
            </div>
          </template>
        </v-client-table>
      </div>
    </div>
    <!-- {{factura}} -->

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
                <div class="form-group col-md-8">
                  <template v-if="tipoAccion == 1">
                    <label >Comprobante</label>
                    <input type="file" class="form-control" name="comprobante" id="c_nuevo" @change="onChangeComprobante($event)">
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
                      <input type="file" class="form-control" id="c_act" name="comprobante" @change="onChangeComprobante($event)">
                    </template>
                  </template>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-8">
                  <template v-if="tipoAccion == 1">
                    <label >XML</label>
                    <input type="file" accept="text/xml" id="x_nuevo" class="form-control" name="comprobante"  @change="onChangeXML($event)">
                  </template>
                  <template v-if="tipoAccion == 2">
                    <template v-if="xml != ''">
                      <label>&nbsp;</label>
                      <button type="button" class="form-control" @click="xml = ''">
                        <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
                      </button>
                    </template>
                    <template v-if="xml === ''">
                      <label>XML</label>
                      <input type="file" accept="text/xml" id="x_act" class="form-control" name="comprobante" @change="onChangeXML($event)">
                    </template>
                  </template>
                </div>
              </div>

              <template v-if="xml === ''">
                <div class="form-row">
                  <div class="form-group col-md-10">
                    <label>Proveedor</label>
                    <v-select :options="listaProvedores"  id="proveedore_id" name="proveedore_id"
                    v-model="factura.proveedore_id" label="name" data-vv-name="Proveedor" ></v-select>
                    <span class="text-danger">{{ errors.first('Proveedor') }}</span>
                  </div>
                </div>
              </template>

              <div class="form-row">
                <div class="form-group col-md-5">
                  <label>Numero factura /UUID</label>
                  <input type="text" class="form-control" v-model="factura.uuid" v-validate="'required'" data-vv-name="UUID"></input>
                  <span class="text-danger">{{ errors.first('UUID') }}</span>
                </div>
              </div>

              <template v-if="xml === ''">
                <div class="form-row">
                  <div class="form-group col-md-4" >
                    <label>Uso CFDI </label>
                    <select class="form-control" v-model="factura.uso_factura" data-vv-name="Uso CFDI" v-validate="'required'">
                      <option v-for="item in UsoFactura" :value="item.id">{{item.c_uso}}  {{item.descripcion}}</option>
                    </select>
                    <span class="text-danger">{{ errors.first('Uso CFDI') }}</span>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Total Factura</label>
                    <input type="text" class="form-control" v-model="factura.total_factura" >
                  </div>
                </div>
              </template>



              <div class="form-row">
                <div class="form-group col-md-8">
                  <label>Centro de costo</label>
                  <select class="form-control" v-model="factura.centro_costo" data-vv-name="Centro de costos" v-validate="'required'">
                    <option v-for="t in centro_costo"  :key="t.id" :value="t.id">{{t.nombre}}-{{t.nombre_sub}}</option>
                  </select>
                  <span class="text-danger">{{ errors.first('Centro de costos') }}</span>
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
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data() {
    return {
      xml : '',
      modal : 0,
      titulo_modal : '',
      data_compra : null,
      tableData : [],
      listaProvedores : [],
      UsoFactura : [],
      tipoAccion : 0,
      columns : ['id','proveedor_name','uuid','total_factura','descripcion','comprobante','xml'],
      options: {
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
      },
      centro_costo : [],
      factura : {
        id : '',
        proveedore_id : '',
        total : '',
        impuestos : '',
        uso_factura: '',
        comprobante : '',
        uuid : '',
        uuid_relacionado : '',
        descuento : 0,
        centro_costo: '',
        total_factura : '',
      },
      orden_compra_id : '',
      entrada_id : '',
      estado : 0,
    }
  },
  methods : {
    /**
    **
    **
    **/
    cargar(data){
      let me = this;
      me.getData(data.id);
      me.orden_compra_id = data.id;
      me.estado = data.estado;
      me.data_compra = data;
      axios.get('/proveedores/activos/1').then(response => {
        me.listaProvedores = [];
        response.data.forEach(data =>{
          me.listaProvedores.push({
            id : data.id,
            name : data.nombre + ' ' + data.razon_social,
          });
        });
      })
      .catch(function (error) {
        console.log(error);
      });

      axios.get('/satcatusocfdi?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {
        me.UsoFactura = response.data.data;
      }).catch(error => {console.error(error);});

      axios.get(`/catalogos/centrocostos/`).then(response => {
        me.centro_costo = response.data;
      }).catch(error => {console.log(error)});
    },

    getData(id){
      var valor = 'Compra';
      axios.get(`facturasentradas/${id}&${valor}`).then(response => {
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
            this.xml = '';
            $('#c_nuevo').val('');
            $('#x_nuevo').val('');
            this.titulo_modal = 'Agregar Factura';
            break;
          }
          case 'actualizar' :
          {
            this.titulo_modal = 'Actualizar Factura';
            this.modal = 1;
            this.tipoAccion = 2;
            Utilerias.resetObject(this.factura);
            this.xml = '';
            $('#c_act').val('');
            $('#x_act').val('');
            this.factura.id = data['id'];
            this.entrada_id = data['entrada_id'];
            this.factura.proveedore_id = {id : data['proveedore_id'],name : data['proveedor_name']};
            this.factura.uso_factura = data['uso_factura_id'];
            this.factura.comprobante = data['comprobante'];
            this.xml = data['xml'];
            this.factura.uuid = data['uuid'];
            this.factura.uuid_relacionado = data['uuid_relacionado'];
            this.factura.centro_costo = data['catalogo_centro_costos_id'];
            this.orden_compra_id = data['orden_compra_id'];
            this.factura.total_factura = data['total_factura'];
          }
        }
      }
    },

    onChangeComprobante(e){
      this.factura.comprobante = e.target.files[0];
    },

    onChangeXML(e){
      this.xml = e.target.files[0];
    },

    guardarFactura(nuevo){
      this.$validator.validate().then(result => {
        if (result) {

          this.isLoading = true;
          let me = this;
          var valor = [];

          let formData = new FormData();
          formData.append('id', this.factura.id);
          formData.append('entrada', this.entrada_id == 'null' ? 0 : 0 );
          formData.append('proveedore_id', this.factura.proveedore_id != 'null' ? this.factura.proveedore_id.id : 0);
          formData.append('uso_factura' , this.factura.uso_factura);
          formData.append('comprobante' , this.factura.comprobante);
          formData.append('xml' , this.xml);
          formData.append('valor',valor);
          formData.append('uuid' , this.factura.uuid);
          formData.append('uuid_relacionado' , this.factura.uuid_relacionado);
          formData.append('descuento' , this.factura.descuento);
          formData.append('centro_costo' , this.factura.centro_costo);
          formData.append('orden_compra_id',this.orden_compra_id);
          formData.append('total_factura',this.factura.total_factura);

          axios({
            method: nuevo ? 'POST' : 'POST',
            url: nuevo ? 'facturasentradas' : 'facturasentradas/update',
            data: formData
          }).then(function (response) {
            me.isLoading = false;
            me.modal = 0;
            me.getData(response.data.orden_compra_id);
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
        this.getData(this.orden_compra_id);
      }).catch(error => {
        console.error(error);
      })
    },

  }
}
</script>
