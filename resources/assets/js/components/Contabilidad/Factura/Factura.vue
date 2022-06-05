<template>
  <main class="main">
    <div class="container-fluid">
      <br>
      <div class="card" v-show="detalle == 1">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Facturas {{usuario_texto}}
          <div class="dropdown float-sm-right">
            <button type="button" class="btn btn-secondary dropdown-toggle" id="dropmenub" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" name="button">
              Emisor
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdown" >
              <div v-for="elem in DatosGral" :key="elem.key">
                <button class="dropdown-item" @click="verfacturas(elem.id)">{{elem.nombre}}</button>
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-light float-sm-right" @click="timbresRestantes()">
            <i ></i>Timbres Restantes
          </button>
          <button type="button" class="btn btn-dark float-sm-right" @click="abrirModal('factura','registrar')">
            <i class="fas fa-plus">&nbsp;</i>Nuevo
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>
          <v-server-table :url="'verfacturauno/'+ id_buscar_emisor" :columns="columns" :options="options" ref="tabla">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <!-- v-show="PermisosCrud.Update" -->
                    <button  type="button" v-show="props.row.timbrado == 0" @click="abrirModal('factura','actualizar',props.row)" class="dropdown-item">
                      <i class="icon-pencil"></i>&nbsp; Actualizar Factura
                    </button>
                    <button  type="button" v-show="props.row.timbrado == 0 && props.row.descripcion_tipofactura === 'Pago'" @click="agregarP(props.row)" class="dropdown-item">
                      <i class="fas fa-list-ol"></i>&nbsp; Agregar Conceptos
                    </button>
                    <button  type="button" v-show="props.row.timbrado == 0 && (props.row.descripcion_tipofactura === 'Ingreso' || props.row.descripcion_tipofactura === 'Egreso')" @click="agregarI(props.row)" class="dropdown-item">
                      <i class="fas fa-list-ol"></i>&nbsp; Agregar Conceptos
                    </button>
                    <button  type="button" v-show="props.row.timbrado == 1 && (props.row.descripcion_tipofactura === 'Ingreso' || props.row.descripcion_tipofactura === 'Egreso')" @click="agregarI(props.row)" class="dropdown-item">
                      <i class="fas fa-list-ol"></i>&nbsp; Ver detalles
                    </button>
                    <button  type="button" v-show="props.row.timbrado == 1 && props.row.descripcion_tipofactura === 'Pago'" @click="agregarP(props.row)" class="dropdown-item">
                      <i class="fas fa-list-ol"></i>&nbsp; Ver detalles
                    </button>
                    <button  type="button" v-show="props.row.timbrado == 0 " @click="descargarprefactura(props.row)" class="dropdown-item">
                      <i class="fas fa-download"></i>&nbsp; Descargar prefactura
                    </button>
                    <button  type="button" v-show="props.row.timbrado == 0 || props.row.timbrado == 9" @click="timbrar(props.row)" class="dropdown-item">
                      <i class="fas fa-bell"></i>&nbsp; Timbrar
                    </button>

                    <button  type="button" v-show="props.row.timbrado == 0" @click="timbrarprueba(props.row)" class="dropdown-item">
                      <i class="fas fa-bell"></i>&nbsp; Timbrar Prueba
                    </button>
                    <button  type="button" v-show="props.row.timbrado == 9" @click="descargarprueba(props.row)" class="dropdown-item">
                      <i class="fas fa-download"></i>&nbsp; Descargar Factura Prueba
                    </button>
                    <button  type="button" v-show="props.row.timbrado == 9" @click="descargarxmlprueba(props.row)" class="dropdown-item">
                      <i class="fas fa-download"></i>&nbsp; Descargar XML Prueba
                    </button>

                    <button  type="button" v-show="props.row.timbrado == 1" @click="descargar(props.row)" class="dropdown-item">
                      <i class="fas fa-download"></i>&nbsp; Descargar
                    </button>
                    <button  type="button" v-show="props.row.timbrado == 1" @click="descargarxml(props.row)" class="dropdown-item">
                      <i class="fas fa-download"></i>&nbsp; Descargar XML
                    </button>
                    <button  type="button" v-show="props.row.timbrado == 1" @click="cancelarfactura(props.row)" class="dropdown-item">
                      <i class="fas fa-close"></i>&nbsp; Cancelar factura
                    </button>
                    <button  type="button" v-show="props.row.timbrado == 9" @click="cancelarprueba(props.row)" class="dropdown-item">
                      <i class="fas fa-close"></i>&nbsp; Cancelar Prueba
                    </button>
                  </div>
                </div>
              </div>
            </template>
            <template slot="timbrado" slot-scope="props">
              <template v-if="props.row.timbrado == 0">
                <button type="button" class="btn btn-outline-info"><i class="fa fa-files-o"></i>&nbsp;Nuevo</button>
              </template>
              <template v-if="props.row.timbrado == 1">
                <button type="button" class="btn btn-outline-success"><i class="fa fa-exclamation-circle"></i>&nbsp;Timbrado</button>
              </template>
              <template v-if="props.row.timbrado == 2">
                <button type="button" class="btn btn-outline-danger"><i class="fa fa-window-close"></i>&nbsp;Cancelado</button>
              </template>
            </template>
          </v-server-table>
        </div>
      </div>
      <div v-show="detalle == 2">
        <conceptos ref="conceptos" @maestro:conceptos="maestro()"></conceptos>
      </div>
      <div v-show="detalle == 3">
        <conceptospagos ref="conceptospagos" @maestro:conceptospagos="maestrop()"></conceptospagos>
      </div>
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
              <form>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label>RFC Emisor </label>
                    <select class="form-control" v-model="factura.emisor_id" @click="guardarDG()">
                      <option v-for="elem in DatosGral" :key="elem.id" :value="elem.id">{{elem.rfc}}</option>
                    </select>
                  </div>
                  <div class="form-group col-md-6">
                    <label>Nombre comercial </label>
                    <input type="text" class="form-control" v-model="DatosGenerales.nombre"
                    placeholder="Nombre comercial" readonly>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-8">
                    <label>Nombre o razon social </label>
                    <input type="text" class="form-control" v-model="DatosGenerales.razon_social"
                    placeholder="Nombre o razon social" readonly>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-8">
                    <label>Regimen fiscal</label>
                    <input type="text" class="form-control" v-model="DatosGenerales.regimenfiscal"
                    placeholder="Regimen fiscal" readonly>
                  </div>
                  <div class="form-group col-md-4">
                    <label>Tipo de factura</label>
                    <select class="form-control" v-model="factura.tipo_factura_id">
                      <option v-for="item in TipoFactura" :value="item.id">{{item.c_tipofactura}} {{item.descripcion}}</option>
                    </select>
                  </div>
                </div>
                <hr >
                <div class="form-row" v-show="factura.tipo_factura_id == 1 || factura.tipo_factura_id == 2">
                  <div class="form-group col-md-7">
                    <label>Tipo de relacción</label>
                    <select class="form-control" v-model="factura.tipo_relacion" >
                      <option v-for="item in TipoRelacion" :value="item.id">{{item.c_tiporelacion}} {{item.descripcion}}</option>
                    </select>
                  </div>
                </div>
                <div class="form-row" v-show="factura.tipo_factura_id == 1 || factura.tipo_factura_id == 2">
                  <div class="form-group col-md-2">
                    <label>Tipo:</label>
                  </div>
                  <div class="form-group col-md-2">
                    <div class="form-check">
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input class="form-check-input" type="radio" name="gridRadiosdos" id="gridRadios3" @change="edo" >
                      <label class="form-check-label" for="gridRadios3">
                        Anterior
                      </label>
                    </div>
                  </div>
                  <div class="form-group col-md-2">
                    <div class="form-check">
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input class="form-check-input" type="radio" name="gridRadiosdos" id="gridRadios4" @change="edo">
                      <label class="form-check-label" for="gridRadios4">
                        Existente
                      </label>
                    </div>
                  </div>
                </div>
                <!-- <div class="form-row" v-show="factura.tipo_factura_id == 1 || factura.tipo_factura_id == 2">
                <div class="form-group col-md-7" >
                <label>UUID</label>
                <input type="text" v-model="factura.uuid_relacionado" class="form-control" v-show="ante">
                <v-select :options="optionsvsex" id="relaccionados" name="relaccionados"
                v-model="factura.factura_id" label="name" data-vv-name="relacionados"  v-show="exis"></v-select>
              </div>
            </div> -->

            <div class="form-row" v-show="factura.tipo_factura_id == 1 || factura.tipo_factura_id == 2">
              <div class="form-group col-md-7" >
                <label>UUID</label>
                <input type="text" v-model="factura.uuid_relacionado" class="form-control" v-show="ante">
                <v-select :options="optionsvsex" id="relaccionados" name="relaccionados"
                v-model="factura.factura_id" label="name" data-vv-name="relacionados"  v-show="exis"></v-select>
              </div>
              <div class="form-group col-md-2">
                <label>&nbsp;</label>
                <br>
                <button type="button" class="btn btn-secondary" @click="guardaRelaccion()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              </div>
            </div>
            <div class="form-row" v-show="factura.tipo_factura_id == 1 || factura.tipo_factura_id == 2">

              <div class="form-group col-md-5">
                <label><b>UUID</b></label>
              </div>
              <div class="form-group col-md-1">
                <label><b>ACCIONES</b></label>
              </div>
            </div>
            <li v-for="(vi, index) in array_relacionados" class="list-group-item">
              <div class="form-row">
                <div class="form-group col-md-5">
                  <label>{{vi.name}}</label>
                </div>
                <div class="form-group col-md-1">
                  <a @click="eliminar_aignacion(vi, index)">
                    <span class="fas fa-trash" arial-hidden="true"></span>
                  </a>
                </div>
              </div>
            </li>


            <hr v-show="factura.tipo_factura_id == 1 || factura.tipo_factura_id == 2">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Cliente </label>
                <!--<select class="form-control" v-model="factura.cliente_id" @change="llenarDatosCliente()">
                <option v-for="item in Clientes" :value="item.id">{{item.nombre}}</option>
              </select>
            -->
            <v-select id="cliente" name="cliente" v-model="cliente" :options="Clientes"
            @input="llenarDatosCliente" v-validate="'required'" label="nombre">
          </v-select>
        </div>
        <div class="form-group col-md-4">
          <label>RFC receptor </label>
          <input type="text" class="form-control" v-model="Receptor.rfc"
          placeholder="RFC receptor" readonly>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-10">
          <label>Nombre o razon social</label>
          <input type="text" class="form-control" v-model="Receptor.nombre"
          placeholder="Nombre o razon social" readonly>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Uso Factura </label>
          <select class="form-control" v-model="factura.uso_factura" >
            <option v-for="item in UsoFactura" :value="item.id">{{item.c_uso}}  {{item.descripcion}}</option>
          </select>
        </div>
        <div class="form-group">
          <label>Correo </label>
          <input type="text" class="form-control" v-model="Receptor.contacto"
          placeholder="Correo" readonly>
        </div>
      </div>
      <hr>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label>Fecha y hora de expedición</label>
          <input type="datetime-local" class="form-control" v-model="factura.fecha_hora" placeholder="Fecha y hora de expedicion">
        </div>
        <div class="form-group col-md-4">
          <label>Código postal </label>
          <input type="text" maxlength="5" class="form-control" v-model="factura.codigo_postal" placeholder="Codigo postal">
        </div>
        <div class="form-group col-md-4">
          <label>Moneda</label>
          <select class="form-control" v-model="factura.moneda_id" @change="tipomoneda">
            <option v-for="item in Monedas" :value="item.id">{{item.c_moneda}}</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label>Forma de pago </label>
          <select class="form-control" v-model="factura.formapago">
            <option v-for="item in FormaPago" :value="item.id">{{item.clave}}  {{item.descripcion}}</option>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label>Metodo de pago</label>
          <select class="form-control" v-model="factura.metodopago">
            <option v-for="item in MetodoPago" :value="item.id">{{item.c_metodopago}} {{item.descripcion}}</option>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label>Tipo de cambio</label>
          <div class="input-group mb-2">
            <div class="input-group-addon">
              <div class="input-group-text">$</div>
            </div>
            <input :disabled="dasabiltarMonedas" type="text" class="form-control" v-model="factura.tipo_cambio" placeholder="Tipo de cambio">
          </div>
        </div>
      </div>
      <div class="form-row" v-show="factura.tipo_factura_id == 4">
        <div class="form-group col-md-4">
          <label>Número de operación</label>
          <input type="text" v-validate="'integer'" class="form-control" name="numero de operacion"
          data-vv-name="numero de operacion" v-model="factura.num_operacion">
          <span class="text-danger">{{errors.first('numero de operacion')}}</span>
        </div>
        <div class="form-group col-md-4">
          <label>Banco ordenante</label>
          <input type="text" class="form-control" name="banco ordenante" data-vv-name="banco ordenante"
          v-model="factura.ban_ordenante">
        </div>
        <div class="form-group col-md-4">
          <label>Cuenta ordenante</label>
          <input type="text" class="form-control" name="cuenta ordenante" data-vv-name="cuenta ordenante"
          v-model="factura.cuenta_ordenante">
        </div>
      </div>
      <div class="form-row" v-show="factura.tipo_factura_id == 4">
        <div class="form-group col-md-4">
          <label>RFC banco ordenante</label>
          <input type="text" class="form-control" name="RFC banco ordenante" data-vv-name="RFC banco ordenante"
          v-model="factura.rfc_cuenta_ordenante">
        </div>
        <div class="form-group col-md-4">
          <label>RFC banco beneficiario</label>
          <input type="text" class="form-control" name="RFC banco beneficiario" data-vv-name="RFC banco beneficiario"
          v-model="factura.rfc_cuenta_beneficiario">
        </div>
      </div>
      <div class="form-row" v-show="factura.tipo_factura_id == 4">
        <div class="form-group col-md-4">
          <label>Fecha de pago</label>
          <input type="datetime-local" name="fecha de pago" v-model="factura.fecha_pago" data-vv-name="fecha_pago" class="form-control">
        </div>
      </div>
      <div class="form-row" v-show="factura.tipo_factura_id == 2 || factura.tipo_factura_id == 1">
        <div class="form-group col-md-8">
          <label>Condiciones de pago </label>
          <input type="text" class="form-control" v-model="factura.condicion_pago" placeholder="Condiciones de pago">
        </div>
      </div>
      <hr>
      <div class="form-row">
        <div class="form-group col-md-2">
          <label>Observaciones</label>
        </div>
        <div class="form-group col-md-1">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="checkbox" class="form-check-input" id="observa" @change="observaciones($event)">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-3">
          <label>Adenda</label>
          <input type="text" id="adenda" class="form-control" v-model="factura.adenda" disabled>
        </div>
        <div class="form-group col-md-3">
          <label>Proveedor</label>
          <input type="text" id="proveeade" class="form-control" v-model="factura.proveeade" disabled>
        </div>
        <div class="form-group col-md-3">
          <label>No. Recepción</label>
          <input type="text" id="clave_ob" class="form-control" v-model="factura.clave_ob" disabled>
        </div>
        <div class="form-group col-md-3">
          <label>Orden de compra</label>
          <input type="text" id="orden_ob" class="form-control" v-model="factura.orden_ob" disabled>
        </div>
      </div>
      <hr>
      <div class="form-row" >
        <div class="form-group col-md-2">
          <label>Tipo:</label>
        </div>
        <div class="form-group col-md-2">
          <div class="form-check">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" @click="nacional" >
            <label class="form-check-label" for="gridRadios1">
              Nacional
            </label>
          </div>
        </div>
        <div class="form-group col-md-2">
          <div class="form-check">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" @click="extranjero">
            <label class="form-check-label" for="gridRadios2">
              Extranjero
            </label>
          </div>
        </div>
        <div class="form-group col-md-1">
          <label>Tax Id: </label>
        </div>
        <div class="form-group col-md-4">
          <input type="text" class="form-control" v-model="factura.taxid" placeholder="Tax Id" :disabled="desabilitartax == true">
        </div>
      </div>
      <hr>
    </form>
  </div>
  <div class="modal-footer">
    <vue-element-loading :active="isLoading"/>
    <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
    <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
    <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
  </div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
</div>
</main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Conceptos = r => require.ensure([], () => r(require('./Conceptos.vue')), 'conta');
const ConceptosPagos = r => require.ensure([], () => r(require('./ConceptosPagos.vue')), 'conta');

export default {
  data(){
    return{
      usuario_texto : '',
      isLoading : false,
      dasabiltarMonedas : false,
      desabilitartax : true,
      datos_relaccionados : null,
      array_relacionados : [],
      modal : 0,
      contador : 0,
      tituloModal : '',
      tipoAccion : 0,
      detalle : 1,
      DatosGral : '',
      optionsvsu : [],
      optionsvsex : [],
      ante : false,
      exis : false,
      id_buscar_emisor : 0,
      factura : {
        id : '',
        emisor_id : '',
        tipo_factura_id : '',
        cliente_id : '',
        uso_factura : '',
        fecha_hora : '',
        codigo_postal : '',
        moneda_id : '',
        formapago : '',
        metodopago : '',
        tipo_cambio : '',
        condicion_pago : '',
        tipo_relacion : '',
        factura_id : '',
        uuid : '',
        taxid : '',
        clave_ob : '',
        orden_ob : '',
        num_operacion : '',
        ban_ordenante : '',
        cuenta_ordenante : '',
        adenda : '',
        proveeade : '',
        ante : '',
        exis : '',
        uuid_relacionado : '',
        fecha_pago : '',
        rfc_cuenta_ordenante : '',
        rfc_cuenta_beneficiario : '',
        //  rfc_receptor : '',
        //  nombre_razons_receptor : '',
        //  correo_receptor : '',
        //rfc_emisor : '',
        //razons_emisor : '',
        //nombrec : '',
        //regimenfiscal_emisor : '',
      },
      Clientes : [],
      cliente:{},
      UsoFactura : [],
      Monedas : [],
      FormaPago : [],
      MetodoPago : [],
      TipoFactura : [],
      TipoRelacion :[],
      optionsvsps : [],
      DatosGenerales : {
        rfc : '',
        razon_social : '',
        nombre : '',
        regimenfiscal : '',
      },
      Receptor : {
        rfc : '',
        nombre : '',
        contacto : '',
      },
      tableData : [],
      columns : ['id','serie','folio','rfc_receptor','nombre_razons_receptor','fecha_hora','c_tipofactura','uuid','clave_ob','orden_ob','total','condicion_pago','timbrado'],
      options: {
        headings: {
          'id' : 'Acciones',
          'c_tipofactura' : 'Efecto',
          'rfc_receptor' : 'RFC Receptor',
          'nombre_razons_receptor' : 'Razón social',
          'fecha_hora' : 'Fecha de emisión',
          'timbrado' : 'Estado',
          'uuid' : 'UUID',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts,
        listColumns: {
          timbrado: [{
            id: 0,
            text: 'Nuevo'
          },
          {
            id: 1,
            text: 'Timbrado'
          },
          {
            id: 2,
            text: 'Cancelado'
          }
        ]
      },
      requestAdapter : function (data) {
        var arr = [];
        arr.push({'c.nombre' : data.query.nombre_razons_receptor,'c.rfc' : data.query.rfc_receptor,
        'factura.fecha_hora' : data.query.fecha_hora,
        'c_tipofactura' : data.query.c_tipofactura,
        'factura.uuid' : data.query.uuid,
        'factura.total' : data.query.total,
        'factura.timbrado' : data.query.timbrado,
        'factura.clave_ob' : data.query.clave_ob,
        'factura.orden_ob' : data.query.orden_ob,
        'factura.serie' : data.query.serie,'factura.folio' : data.query.folio});
        data.query = arr[0];
        return data;
      },
    },
  }
},
components : {
  'conceptos' : Conceptos,
  'conceptospagos' : ConceptosPagos,
},
methods : {
  /**
  * [getData se obtiene los datos nesesarios para utilizar en el formulario]
  * @return
  */
  getData(){
    axios.get('/datosgenerales?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {
      this.DatosGral = response.data.data;
    }).catch(error => {console.error(error);});

    axios.get('/clientes').then(response => {
      this.Clientes = [];;
      response.data.forEach(c=>
        {
          this.Clientes.push({id:c.id,nombre:c.nombre});
        });
      }).catch(error => {console.error(error);});

      axios.get('/satcatusocfdi?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {
        this.UsoFactura = response.data.data;
      }).catch(error => {console.error(error);});

      axios.get('/satcatmonedas?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {
        this.Monedas = response.data.data;
      }).catch(error => {console.error(error);});

      axios.get('/satcatformpago?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {
        this.FormaPago = response.data;
      }).catch(error => {console.error(error);});

      axios.get('/satcatmetodopago?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {
        this.MetodoPago = response.data.data;
      }).catch(error => {console.error(error);});

      axios.get('/satcattiporelacion?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {
        this.TipoRelacion = response.data.data;
      }).catch(error => {console.error(error);});

      axios.get('/satcattipofactura?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {
        this.TipoFactura = response.data.data;
      }).catch(error => {console.error(error);});

      axios.get('/satcatprodser?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {
        for (var i = 0; i < response.data.data.length; i++) {
          this.optionsvsps.push(
            {
              id:response.data.data[i].id,
              name:response.data.data[i].clave+' '+response.data.data[i].descripcion,
            });
          }
        }).catch(error => {console.error(error);});

        axios.get('/catfactura?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {
          // this.tableData = response.data;

          if (response.data.data.length != 0) {
            for (var i = 0; i < response.data.data.length; i++) {

              if (response.data.data[i].timbrado == '1') {
                this.optionsvsu.push({
                  id : response.data.data[i].id,
                  name : response.data.data[i].serie + ' ' + response.data.data[i].folio + ' ' + response.data.data[i].uuid,
                });
                this.optionsvsex.push({
                  id : response.data.data[i].id,
                  name : response.data.data[i].serie + ' ' + response.data.data[i].folio + ' ' + response.data.data[i].uuid,
                })
                this.contador += 1;
              }
            }
          }
        }).catch(error => {console.error(error);});

      },

      /**
      * [abrirModal description]
      * @param  {String} modelo    [description]
      * @param  {String} accion    [description]
      * @param  {Array}  [data=[]] [description]
      * @return {Response}           [description]
      */
      abrirModal(modelo, accion, data = [])
      {
        switch(modelo){
          case "factura":
          {
            switch(accion){
              case 'registrar':
              {
                this.modal= 1;
                this.tituloModal = 'Registrar factura';
                this.tipoAccion=1;
                this.vaciar();
                this.verfechahora();
                $("#gridRadios1").prop("checked", true) ;

                break;
              }
              case 'actualizar':
              {
                // console.log(data);
                this.vaciar();
                var texto = data['fecha_hora'];
                var separadores = ['-',':',' '];
                var textoseparado = texto.split (new RegExp (separadores.join('|'),'g'));
                if (data['fecha_pago'] != null) {
                  var texto_p = data['fecha_pago'];
                  var separadores_p = ['-',':',' '];
                  var textoseparado_p = texto_p.split (new RegExp (separadores_p.join('|'),'g'));
                }
                this.modal= 1;
                this.tituloModal = 'Actualizar factura';
                this.tipoAccion=2;
                this.factura.id = data['id'];
                this.factura.emisor_id = data['emisor_id'];
                this.factura.tipo_factura_id = data['tipo_factura_id'];
                this.cliente.id = data['cliente_id'];
                this.factura.uso_factura = data['uso_factura'];
                this.factura.fecha_hora = textoseparado[0]+'-'+textoseparado[1]+'-'+textoseparado[2]+'T'+textoseparado[3]+':'+textoseparado[4];
                this.factura.codigo_postal = data['codigo_postal'];
                this.factura.moneda_id = data['moneda_id'];
                this.factura.formapago = data['formapago'];
                this.factura.metodopago = data['metodopago'];
                this.factura.tipo_cambio = data['tipo_cambio'];
                this.factura.condicion_pago = data['condicion_pago'];
                this.factura.taxid = data['taxid'];
                this.factura.clave_ob = data['clave_ob'];
                this.factura.orden_ob = data['orden_ob'];
                this.factura.adenda = data['adenda'];
                this.factura.proveeade = data['proveeade'];
                this.factura.num_operacion = data['num_operacion'];
                this.factura.cuenta_ordenante = data['cuenta_ordenante'];
                this.factura.rfc_cuenta_ordenante = data['rfc_cuenta_ordenante'];
                this.factura.rfc_cuenta_beneficiario = data['rfc_cuenta_beneficiario'];
                this.factura.ban_ordenante = data['ban_ordenante'];
                this.factura.tipo_relacion = data['tipo_relacion'];
                this.factura.fecha_pago = data['fecha_pago'] == null ? '' : textoseparado_p[0]+'-'+textoseparado_p[1]+'-'+textoseparado_p[2]+'T'+textoseparado_p[3]+':'+textoseparado_p[4];;
                this.factura.factura_id = data['factura_id'] == null ? '' : {id : data['factura_id'], name : data['serie_relacionado'] + ' ' + data['folio_relacionado'] + ' ' + data['uuid_relacionado_f'] };
                // this.factura.factura_id = data['factura_id'] == null ? '' : {id : data['factura_id'], name : data['serie_relacionado'] + ' ' + data['folio_relacionado'] + ' ' + data['uuid_relacionado'] };
                // this.factura.uuid_relacionado = data['uuid_relacionado'];

                axios.get('get/relacionados/'+ data['id']).then(response => {
                  response.data.forEach((item, i) => {
                    this.array_relacionados.push({
                      id : item.id,
                      name : item.uuid,
                    });
                  });

                }).catch(error =>{
                  console.error(error);
                });


                this.vertipo();
                this.vertipouno();
                // this.verDatosEmisor(data['emisor_id']);

                // this.verfactura();
                this.llenarDatosCliente();
                break;
              }
            }
          }
        }
      },

      /**
      * [verfechahora Funcion que obtiene la hora y fecha atual del sistema]
      * @return
      */
      verfechahora(){
        var d = new Date();
        this.factura.fecha_hora = d.getFullYear() + '-' +((d.getMonth()+1) < 10 ? '0'+ (d.getMonth()+1) : (d.getMonth() + 1))
        + '-' + (d.getDate() < 10 ? ('0' + d.getDate()): d.getDate())  + 'T'
        + (d.getHours() < 10 ? ('0'+ d.getHours()) : d.getHours()) + ':'
        + (d.getMinutes() < 10 ? '0' + (d.getMinutes()) : d.getMinutes()) ;
      },

      cerrarModal(){
        this.modal = 0;
      },

      vaciar(){
        this.factura.tipo_factura_id = '';
        this.cliente.id = '';
        this.factura.uso_factura = '';
        this.factura.fecha_hora = '';
        // this.factura.codigo_postal = '';
        this.factura.moneda_id = '';
        this.factura.formapago = '';
        this.factura.metodopago = '';
        this.factura.tipo_cambio = '';
        this.factura.condicion_pago = '';
        this.factura.taxid = '';
        this.factura.factura_id = '';
        this.factura.tipo_relacion = '';
        this.factura.uuid_relacionado = '';
        this.factura.num_operacion = '';
        this.factura.ban_ordenante = '';
        this.factura.cuenta_ordenante = '';
        this.factura.rfc_cuenta_ordenante = '';
        this.factura.rfc_cuenta_beneficiario = '';
        this.factura.fecha_pago = '';
        this.array_relacionados = [];
        Utilerias.resetObject(this.Receptor);

      },

      tipomoneda(){
        if(this.factura.moneda_id == 1) {this.dasabiltarMonedas = true; this.factura.tipo_cambio = '1';}
        else {this.dasabiltarMonedas = false; this.factura.tipo_cambio = ''; }
      },

      /**
      * [llenarDatosCliente Funcion que completa los campos del formulario relacionados con el cliente en este caso el receptor haciendo UNA
      *  busqueda de un cliente en especifico]
      * @return
      */
      llenarDatosCliente(){

        if(this.cliente!=null)
        {
          axios.get('/clientes/' + this.cliente.id).then(response => {
            this.Receptor.rfc = response.data.rfc;
            this.Receptor.nombre = response.data.nombre;
            this.Receptor.contacto = response.data.contacto;
          }).catch(error => {
            console.error(error);
          });
        }
      },

      /**
      * [guardar Funcion que guarda o actualiza los encabezados de las facturas]
      * @param nuevo
      * @returnfecha_pago
      */
      guardar(nuevo){
        this.$validator.validate().then(result => {
          if (result) {
            this.isLoading = true;
            let me = this;
            axios({
              method: nuevo ? 'POST' : 'PUT',
              url: nuevo ? '/factura' : '/factura/'+ this.factura.id,
              data: {
                // id : this.factura.id,
                emisor_id : this.factura.emisor_id,
                tipo_factura_id : this.factura.tipo_factura_id,
                cliente_id : this.cliente.id,
                uso_factura : this.factura.uso_factura,
                fecha_hora : this.factura.fecha_hora,
                codigo_postal : this.factura.codigo_postal,
                moneda_id : this.factura.moneda_id,
                formapago : this.factura.formapago,
                metodopago : this.factura.metodopago,
                tipo_cambio : this.factura.tipo_cambio,
                condicion_pago : this.factura.condicion_pago,
                taxid : this.factura.taxid,
                tipo_relacion : this.factura.tipo_relacion,
                factura_id : this.factura.factura_id == '' ? '' : this.factura.factura_id.id,
                uuid_relacionado : this.factura.uuid_relacionado,
                clave_ob : this.factura.clave_ob,
                orden_ob : this.factura.orden_ob,
                adenda : this.factura.adenda,
                proveeade : this.factura.proveeade,
                num_operacion : this.factura.num_operacion,
                ban_ordenante : this.factura.ban_ordenante,
                cuenta_ordenante : this.factura.cuenta_ordenante,
                rfc_cuenta_ordenante : this.factura.rfc_cuenta_ordenante,
                rfc_cuenta_beneficiario : this.factura.rfc_cuenta_beneficiario,
                fecha_pago : this.factura.fecha_pago,
                relacionados : this.array_relacionados,
              }
            }).then(function (response) {
              me.isLoading = false;
              if (response.data.status) {
                if (response.data.status === 'error') {
                  swal({
                    type: 'error',
                    html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
                  });
                  me.cerrarModal();
                }else {
                  me.contador = 0;
                  me.cerrarModal();
                  toastr.success(nuevo ? 'Factura agregada correctamente' : 'Factura actualizada correctamente','Correcto');
                  me.verfacturas(response.data.emisor_id);
                }
              }
              else {
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
      * [agregar description]
      * @return {[type]} [description]
      */
      agregarI(data){
        this.detalle = 2;
        var childConceptos = this.$refs.conceptos;
        childConceptos.getData(data, this.optionsvsps);
      },

      agregarP(data){
        this.detalle = 3;
        var childConceptosPagos = this.$refs.conceptospagos;
        childConceptosPagos.getData(data);
      },

      maestro(){
        this.detalle = 1;
      },

      maestrop(){
        this.detalle = 1;
      },

      timbrar(data){
        Swal.fire({
          title: 'Esta seguro(a) de timbrar?',
          text: "Esta opción no se podrá desahacer!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si',
          cancelButtonText : 'No',
        }).then((result) => {
          if (result.value) {
            this.isLoading = true;
            axios.get('/partidafactura/'+ data.id + '?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {

              if (response.data.count == 0) {
                toastr.warning('No se puede timbrar hasta tener conceptos registrados','Atención');
                this.isLoading = false;
              }else {
                axios.get('/sellartimbrarfactura/' + data.id).then(response => {
                  if (response.data.status) {
                    this.isLoading = false;
                    this.contador = 0;
                    toastr.success('Factura timbrada correctamente !!!','Correcto');
                    this.verfacturas(response.data.emisor_id);
                  }
                  else {
                    this.isLoading = false;
                    toastr.warning('Ha occurrido un error !!!','Atención');
                    swal({
                      type: 'error',
                      html: response.data.respuesta,
                    });
                  }
                }).catch(error => {
                  console.error(error);
                });
              }
            }).catch(error => {
              console.error(error);
            });
          }
        })

      },

      timbrarprueba(data){
        Swal.fire({
          title: 'Esta seguro(a) de timbrar a prueba?',
          text: "Esta opción no se podrá desahacer!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si',
          cancelButtonText : 'No',
        }).then((result) => {
          if (result.value) {
            this.isLoading = true;
            axios.get('/partidafactura/'+ data.id + '?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {

              if (response.data.count == 0) {
                toastr.warning('No se puede timbrar hasta tener conceptos registrados','Atención');
                this.isLoading = false;
              }else {
                axios.get('/sellartimbrarfactura/prueba/' + data.id).then(response => {
                  if (response.data.status) {
                    this.isLoading = false;
                    this.contador = 0;
                    toastr.success('Factura timbrada correctamente !!!','Correcto');
                    this.verfacturas(response.data.emisor_id);
                  }
                  else {
                    this.isLoading = false;
                    toastr.warning('Ha occurrido un error !!!','Atención');
                    swal({
                      type: 'error',
                      html: response.data.respuesta,
                    });
                  }
                }).catch(error => {
                  console.error(error);
                });
              }
            }).catch(error => {
              console.error(error);
            });
          }
        })

      },

      descargar(data){
        window.open('/descargarfactura/' + data.archivo, '_blank');
      },

      descargarprueba(data){
        window.open('/descargarfactura/prueba/' + data.archivo, '_blank');
      },

      descargarprefactura(data){
        window.open('/descargarprefactura/' + data.id, '_blank');
      },

      extranjero(){
        this.desabilitartax = false;
        axios.get('/clientextranjero').then(response => {
          this.cliente.id = response.data.id;
          this.Receptor.rfc = response.data.rfc;
          this.Receptor.nombre = response.data.nombre;
          this.Receptor.contacto = response.data.contacto;
          this.factura.uso_factura = 22;
        }).catch(error => {
          console.error(error);
        });
      },

      nacional(){
        this.desabilitartax = true;
        this.cliente.id = '';
        this.Receptor.rfc = '';
        this.Receptor.nombre = '';
        this.Receptor.contacto = '';
        this.factura.uso_factura = '';
        this.factura.taxid = '';
      },

      vertipo(){
        if (this.factura.taxid == null) {
          $("#gridRadios1").prop("checked", true);
          $("#gridRadios2").prop("checked", false);
          this.desabilitartax = true;
        }
        else if (this.factura.taxid != null) {
          $("#gridRadios1").prop("checked", false);
          $("#gridRadios2").prop("checked", true);
          this.desabilitartax = false;
        }
      },

      vertipouno(){
        if(this.factura.factura_id == ''){
          $("#gridRadios3").prop("checked", true);
          $("#gridRadios4").prop("checked", false);
          this.ante = true;
          this.exis = false;
          this.factura.factura_id = '';
        }else if (this.factura.factura_id != '') {
          $("#gridRadios4").prop("checked", true);
          $("#gridRadios3").prop("checked", false);
          this.ante = false;
          this.exis = true;
          this.factura.uuid_relacionado = '';
        }
      },

      observaciones(data){
        if (data.target.checked == true) {
          $("#clave_ob").prop("disabled", false);
          $("#orden_ob").prop("disabled", false);
          $("#adenda").prop("disabled", false);
          $("#proveeade").prop("disabled", false);
        }
        else {
          $("#clave_ob").prop("disabled",true);
          $("#orden_ob").prop("disabled", true);
          $("#adenda").prop("disabled", true);
          $("#proveeade").prop("disabled", true);
          this.factura.orden_ob = '';
          this.factura.clave_ob = '';
          this.factura.proveeade = '';
          this.factura.adenda = '';
        }

      },

      // verfactura(){
      //   console.log(this.factura.factura_id);
      //   axios.get('/factura/' + (this.factura.factura_id == '' ? 0 : this.factura.factura_id.id)).then(response => {
      //     this.datos_relaccionados = response.data;
      //   }).catch((err) => {console.log(err);})
      // },

      cancelarfactura(data){
        Swal.fire({
          title: 'Esta seguro(a) de cancelar?',
          text: "Esta opción no se podrá desahacer!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si',
          cancelButtonText : 'No',
        }).then((result) => {
          if (result.value) {

            axios.get('/partidafactura/cancelarfactura/' + data.id).then((response) => {
              /***********************************************/
              if (response.data.status) {
                this.isLoading = false;
                // this.contador = 0;
                toastr.success('Factura cancelada correctamente !!!','Correcto');
                this.verfacturas(response.data.emisor_id);
              }
              else {
                this.isLoading = false;
                toastr.warning('Ha occurrido un error !!!','Atención');
                swal({
                  type: 'error',
                  html: response.data.respuesta,
                });
              }
              // ***********************
            }).catch((err) => {
              console.error(err);
            })
          }
        })
      },

      guardarDG(){
        axios.get('/datosgeneral/' + this.factura.emisor_id).then((response) => {
          this.DatosGenerales.razon_social = response.data.razon_social;
          this.DatosGenerales.nombre = response.data.nombre;
          this.DatosGenerales.regimenfiscal = response.data.regimenfiscal;
          this.factura.codigo_postal = response.data.codigo_postal;
        }).catch((err) => {console.error(error);})
      },
      //
      // verDatosEmisor(id){
      //   axios.get('/datosgeneral/' + id).then((response) => {
      //     this.DatosGenerales.razon_social = response.data.razon_social;
      //     this.DatosGenerales.nombre = response.data.nombre;
      //     this.DatosGenerales.regimenfiscal = response.data.regimenfiscal;
      //     this.factura.codigo_postal = response.data.codigo_postal;
      //   }).catch((err) => {console.error(error);})
      // },

      timbresRestantes(){
        axios.get('/timbresrestantes').then((response) => {
          Swal.fire('Timbres Restantes',
          'Conserflow :'+response.data.c)
        }).catch((err) => {console.error(error);})
      },

      verfacturas(id){
        this.usuario_texto = (id == 1 ? 'CONSERFLOW SA DE CV' : '-');
        this.id_buscar_emisor = id;
        this.$refs.tabla.refresh();
      },

      descargarxml(data){
        let me=this;
        axios({
          url: '/descargarfacturareportexml/' + data.archivo+'tim.xml' ,
          method: 'GET',
          responseType: 'blob', // importante
        }).then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', data.archivo+'tim.xml'); //archivo = nombre del archivo alojado en el ftp
          document.body.appendChild(link);
          link.click();
          //--Llama el metodo para borrar el archivo local una ves descargado--//
          axios.delete( '/eliminarfacturareportexml/' + data.archivo+'tim.xml')
          .then(response => {
          })
          .catch(function (error) {
            console.log(error);
          });
          //--fin del metodo borrar--//
        });
      },

      descargarxmlprueba(data){
        let me=this;
        axios({
          url: '/descargarfacturareportexml/' + data.archivo+'timp.xml' ,
          method: 'GET',
          responseType: 'blob', // importante
        }).then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', data.archivo+'timp.xml'); //archivo = nombre del archivo alojado en el ftp
          document.body.appendChild(link);
          link.click();
          //--Llama el metodo para borrar el archivo local una ves descargado--//
          axios.delete( '/eliminarfacturareportexml/' + data.archivo+'timp.xml')
          .then(response => {
          })
          .catch(function (error) {
            console.log(error);
          });
          //--fin del metodo borrar--//
        });
      },

      edo(){
        if ($("#gridRadios3").prop("checked")) {
          this.ante = true;
          this.exis = false;
          this.factura.factura_id = '';
        }else {
          this.ante = false;
          this.exis = true;
          this.factura.uuid_relacionado = '';
        }
      },

      guardaRelaccion(){

        if (this.ante == true) {
          this.array_relacionados.push({
            id : 0,
            name : this.factura.uuid_relacionado
          });
        }
        if (this.exis == true) {
          this.array_relacionados.push({
            id : 0,
            name : this.factura.factura_id.name
          });
        }
        this.factura.uuid_relacionado = '';
        this.factura.factura_id = '';
      },

      eliminar_aignacion(data, index){
        if (data.id == 0)
        {
          this.array_relacionados.splice(index, 1);
        }
        else
        {
          let me = this;
          var id = data['id'];
          axios.get(`relaciones/eliminar/${id}`)
          .then(function (response)
          {
            me.array_relacionados.splice(index, 1);
          })
          .catch(function (error)
          {
            console.log(error);
          });
        }
      },

      cancelarprueba(data){
        axios.get('cancelar/prueba/' + data.id).then(response => {
          toastr.success('Cancelado Correctamente!!!');
          this.verfacturas(response.data.emisor_id);
          // console.log(response);
        }).catch(e => {
          console.error(e);
        });
      },
    },
    mounted(){
      this.getData();
    }
  }
  </script>
  <style media="screen">
  hr {
    display: block;
    margin-top: 2em;
    margin-bottom: 2em;
    margin-left: auto;
    margin-right: auto;
    border-style: inset;
    border-width: 2px;
  }
  </style>
