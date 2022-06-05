<template>
  <!-- Inicio card principal -->
  <div class="card" v-if="mostrar == 1">
    <vue-element-loading :active="isLoading" />
    <div class="card-header">
      <h4 class=" title" v-text="tituloModal"></h4>
    </div>
    <div class="card-body">
      <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
      <input type="hidden" name="id">
      <div class="form-row">

        <div class="col-md-4 mb-3">
          <label for="proyecto_id">Proyecto</label>
          <v-select :options="listaProyectos" v-bind:disabled="tipoAccion == 2 && compra.condicion == 2" id="proyecto_id" v-validate="'required'" name="proyecto_id" v-model="compra.proyecto_id" label="name" data-vv-name="Proyecto"></v-select>
          <span class="text-danger">{{ errors.first('Proyecto') }}</span>
        </div>
        <div class="col-md-4 mb-3">
          <label for="proveedore_id">Proveedor</label>
          <v-select :options="listaProvedores" id="proveedore_id" v-validate="'required'" @input="buscarDatosBancarios($event)" name="proveedore_id" v-model="compra.proveedore_id" label="name" data-vv-name="Proveedor"></v-select>
          <span class="text-danger">{{ errors.first('Proveedor') }}</span>
        </div>
        <div class="col-md-4 mb-3" v-if="compra.proveedore_id.name === 'CSCT Constructora y Servicios Calderon Torres S.A. de C.V.' || compra.proveedore_id.name === 'Conserflow Constructora y Servicios Calderon Torres S.A. de C.V.'
        || compra.proveedore_id.name === 'Constructora y Servicios Calderon Torres S.A. de C.V.'">
        <label for="proveedore_id">Proveedor CSCT</label>
        <v-select :options="listaProvedores_CSCT" v-validate="'required'" v-model="compra.proveedore_csct_id" label="name" data-vv-name="Proveedor CSCT"></v-select>
        <span class="text-danger">{{ errors.first('Proveedor CSCT') }}</span>
      </div>
    </div>
    <hr>
    <div class="form-row">
      <div class="col-md-4 mb-3">
        <!-- <label for="folio">Folio</label> -->
        <!-- <input type="text" name="folio" v-model="compra.folio" class="form-control" placeholder="Folio" autocomplete="off" id="folio" readonly> -->
        <!-- <span class="text-danger">{{ errors.first('folio') }}</span> -->
        <label for="folio">Comentario de condición de pago</label>
        <input type="text" name="comentario_condicion_pago" v-model="compra.comentario_condicion_pago" class="form-control" placeholder="Comentario" autocomplete="off" id="comentario_condicion_pago" >
        <span class="text-danger">{{ errors.first('comentario_condicion_pago') }}</span>
      </div>
      <div class="col-md-4 mb-3">
        <label for="condicion_pago">Condición Pago</label>
        <select class="form-control" id="condicion_pago" @change="cambiarcondicion()" v-validate="'required'" name="condicion_pago" v-model="compra.condicion_pago" data-vv-as="condicion_pago" >
          <option v-for="item in listaCondicionPago" :value="item.id" :key="item.id">{{item.nombre}} </option>
        </select>
        <span class="text-danger">{{ errors.first('condicion_pago') }}</span>
      </div>
      <div class="col-md-4 mb-3" v-show="compra.condicion_pago == 1">
        <label for="sueldo_mensual">Rango de dias a pagar</label>
        <div class="input-group">
          <input type="number" min="0" pattern="^[0-9]" name="rango_dias" v-model="compra.rango_dias" class="form-control" placeholder="Rango Dias" data-vv-as="Rango dias" autocomplete="off" id="rango_dias" >
          <div class="input-group-addon">
            <span class="input-group-text">días a:</span>
          </div>
          <input type="number" min="0" pattern="^[0-9]" name="pagos" @blur="creardates()" v-model="compra.pagos" class="form-control" placeholder="Pagos" data-vv-as="Pagos" autocomplete="off" id="pagos" >
          <div class="input-group-addon">
            <span class="input-group-text">pagos.</span>
          </div>
        </div>
      </div>
    </div>
    <div id="fechas" class="form-row"> </div>

    <hr>
    <div class="form-row">


      <div class="col-md-4 mb-3">
        <label for="periodo_entrega">Período Entrega</label>

        <v-select :options="listaPeriodoEntrega"
        id="periodo_entrega"
        v-validate="'required'"
        name="periodo_entrega"
        v-model="compra.periodo_entrega"
        label="name"
        data-vv-name="periodo_entrega"></v-select>

        <span class="text-danger">{{ errors.first('periodo_entrega') }}</span>
      </div>

    <div class="col-md-4 mb-3">
      <label for="fecha_orden">Fecha Orden</label>
      <input type="date" name="fecha_orden" v-model="compra.fecha_orden" class="form-control" v-validate="'required'" data-vv-name="Fecha orden" placeholder="Fecha orden" autocomplete="off" id="fecha_orden" >
      <span class="text-danger">{{ errors.first('Fecha orden') }}</span>
    </div>
    <div class="col-md-4 mb-3">
      <label for="lugar_entrega">Lugar Entrega</label>
      <input type="text" name="lugar_entrega" v-model="compra.lugar_entrega" class="form-control" v-validate="'required'" data-vv-name="Lugar entrega" placeholder="Lugar Entrega" autocomplete="off" id="lugar_entrega" >
      <span class="text-danger">{{ errors.first('Lugar entrega') }}</span>
    </div>
  </div>
  <div class="form-row">

  </div>
  <hr>
  <div class="form-row">
    <div class="col-md-3 mb-3">
      <label>Fecha requerida de pago</label>
      <input type="date" v-model="compra.fecha_probable_pago" class="form-control">
    </div>
    <div class="col-md-1 mb-3">
      <label>Urgente :</label>
      <select class="form-control" v-model="compra.prioridad">
        <option value="0">No</option>
        <option value="1">Si</option>
      </select>
    </div>
    <div class="col-md-5 mb-3">
      <label for="observaciones">Observaciones</label>
      <textarea name="observaciones" v-model="compra.observaciones" class="form-control" rows="2" cols="80" id="observaciones" ></textarea>
      <span class="text-danger">{{ errors.first('observaciones') }}</span>
    </div>
  </div>
  <hr>

  <div class="form-group row container">
    <h5>Impuestos</h5>
    <div class="col-md-6">
      <button type="button" class="btn btn-secondary btn-sm" @click="agregarinpuestos(1,compra.id)" >
        <i class="fas fa-plus"></i>&nbsp;Agregar
      </button>
    </div>
  </div>

  <div class="container mt-2">
    <div class="form-row mt-2 ml-3">
      <div class="form-group col-md-3">
        <p class="font-weight-bold">Tipo</p>
      </div>
      <div class="form-group col-md-2">
        <p class="font-weight-bold">Porcentaje</p>
      </div>
      <div class="form-group col-md-2">
        <p class="font-weight-bold">Retenido</p>
      </div>

    </div>

    <li v-for="(vi, index) in listImpuestos" class="list-group-item">
      <div class="form-row my-0">

        <div class="form-group form-sm col-md-4 my-0">
          <label>{{vi.tipo}}</label>
        </div>
        <div class="form-group col-md-2 my-0">
          <label>{{vi.porcentaje}}</label>
        </div>
        <div class="form-group col-md-2 my-0 ">
          <template v-if="vi.retenido">
            <label>Sí</label>
          </template>
          <template v-else>
            <label>No</label>
          </template>
        </div>
      </div>
    </li>
    <br>
    <br>
  </div>

  <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="descuento">Descuento</label>
      <div class="input-group">
        <div class="input-group-addon">
          <span class="input-group-text">%</span>
        </div>
        <input type="text" name="descuento" v-model="compra.descuento" class="form-control" placeholder="Referencia" autocomplete="off" id="descuento" >
      </div>
      <span class="text-danger">{{ errors.first('descuento') }}</span>
    </div>
  </div>
  <hr>
  <div class="form-row">
    <div class="col-md-2 mb-3">
      <label for="moneda">Moneda de pago</label>
      <select class="form-control" v-validate="'required'" name="moneda" id="moneda" v-model="compra.moneda" >
        <option value="1">Dolares (USD)</option>
        <option value="2">Moneda Nacional (MXN)</option>
        <option value="3">Euros (EUR)</option>
      </select>
      <span class="text-danger">{{ errors.first('moneda') }}</span>
    </div>
    <div class="col-md-3 mb-3">
      <label for="sucursal">Sucursal</label>
      <input type="text" name="sucursal" v-model="compra.sucursal" class="form-control" placeholder="Sucursal" autocomplete="off" id="sucursal" >
      <span class="text-danger">{{ errors.first('sucursal') }}</span>
    </div>
    <div class="col-md-4 mb-3">
      <label for="referencia">Referencia</label>
      <input type="text" name="referencia" v-model="compra.referencia" class="form-control" placeholder="Referencia" autocomplete="off" id="referencia" >
      <span class="text-danger">{{ errors.first('referencia') }}</span>
    </div>
    <div class="col-md-3 mb-3">
      <label for="cie">Convenio CIE</label>
      <input type="text" name="cie" v-model="compra.cie" class="form-control" placeholder="CIE" autocomplete="off" id="cie" >
      <span class="text-danger">{{ errors.first('cie') }}</span>
    </div>
  </div>

  <div class="form-row">

    <div class="col-md-3 mb-3">
      <label for="referencia">Banco de Transferencia</label>
      <template v-if="enabledb == true">
        <select class="form-control" v-model="compra.transferencia" @change="llenarCamposBanco($event)" v-validate="'required'" data-vv-name="Banco">
          <option v-for="t in datosBancarios"  :key="t.id" :value="t.banco" >{{t.banco}}</option>
          <option value="0">Otro</option>
        </select>
        <span class="text-danger">{{ errors.first('Banco') }}</span><br>
      </template>
      <template v-if="enabledb == false">
        <input type="text" name="transferencia" v-model="compra.transferencia" class="form-control" placeholder="Escriba el nombre del banco" autocomplete="off" id="bancoTrans" @keyup.esc="regresardb">
        <span class="text-danger">{{ errors.first('transferencia') }}</span><br>
      </template>
    </div>

    <template v-if="enabledb == false">
      <div class="col-md-1 mb-3">
        <label>&nbsp;</label><br>
        <button class="btn btn-outline-dark" name="button" @click="regresardb">Cancelar</button>
      </div>
    </template>

    <div class="col-md-3 mb-3">
      <label for="sucursal">Número de Cuenta</label>
      <input type="text" name="cuenta" v-model="compra.cuenta" class="form-control" placeholder="N° Cuenta" autocomplete="off" id="numCuenta" >
      <span class="text-danger">{{ errors.first('sucursal') }}</span>
    </div>

    <div class="col-md-3 mb-3">
      <label for="referencia">Cuenta CLABE</label>
      <input type="text" name="referencia" v-model="compra.clabe" class="form-control" placeholder="CLABE" autocomplete="off" id="ctaClabe" >
      <span class="text-danger">{{ errors.first('referencia') }}</span>
    </div>

    <div class="col-md-3 mb-3">
      <label for="cie">Titular de la Cuenta</label>
      <input type="text" name="cie" v-model="compra.titular" class="form-control" placeholder="Titular" autocomplete="off" id="titular" >
      <span class="text-danger">{{ errors.first('cie') }}</span>
    </div>

  </div>
  <hr>
  <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="elabora_empleado_id">Empleado que elabora</label>
      <v-select :options="listaEmpleados" id="elabora_empleado_id" v-validate="'required'" name="elabora_empleado_id" v-model="compra.elabora_empleado_id" label="name" data-vv-name="Elabora empleado" :disabled="tipoAccion == 2 && compra.condicion == 2"></v-select>
      <span class="text-danger">{{ errors.first('Elabora empleado') }}</span>
    </div>
    <div class="col-md-4 mb-3">
      <label for="autoriza_empleado_id">Autorizado por</label>
      <v-select :options="listaEmpleados" id="autoriza_empleado_id" v-validate="'required'" name="autoriza_empleado_id" v-model="compra.autoriza_empleado_id" label="name" data-vv-name="Autoriza empleado" :disabled="tipoAccion == 2 && compra.condicion == 2"></v-select>
      <span class="text-danger">{{ errors.first('Autoriza empleado') }}</span>
    </div>

    <div class="col-md-2 mb-3" v-show="tipoAccion != 2">
      <label>Compra con requisición ?</label>
      <div class="form-check">
        <label class="switch switch-default switch-pill switch-dark">
          <input type="checkbox" id="autoSizingCheck2" class="switch-input" @change="cambiar($event)" v-model="conrequisicion">
          <span class="switch-label"></span>
          <span class="switch-handle"></span>
        </label>
      </div>
    </div>
    <div class="col-md-1 mb-3">
      <label>ASME</label>
      <div class="form-check">
        <label class="switch switch-default switch-pill switch-dark">
          <input type="checkbox" id="autoSizingCheck2" class="switch-input" v-model="asme">
          <span class="switch-label"></span>
          <span class="switch-handle"></span>
        </label>
      </div>
    </div>
  </div>
  <hr>
  <!-- datos ASME -->
  <div class="form-row" v-if="conrequisicion == 0 && asme == 1">
    <div class="col-md-4 mb-3">
      <label>Requisición elaborada por:</label>
      <v-select :options="listaEmpleadosCompleta" v-model="datos_asme_cop.elabora_empleado_requisicion_id" label="name"
      data-vv-name="Elabora requisicion empleado" ></v-select>
    </div>
  </div>
  <template v-if="asme == 1">


  <div class="form-row">
      <div class="col-md-2 mb-3">
          <label >Fecha</label>
          <input type="date" class="form-control" v-model="datos_asme_cop.fecha">
      </div>
      <div class="col-md-2 mb-3">
          <label >Con. Pago (%)</label>
          <input type="text" class="form-control" v-model="datos_asme_cop.cond_pago" data-vv-name="Con. Pago %" v-validate="'decimal:2'">
          <span class="text-danger">{{errors.first('Con. Pago %')}}</span>
      </div>
      <div class="col-md-2 mb-3">
          <label >Importe</label>
          <input type="text" class="form-control" v-model="datos_asme_cop.importe" data-vv-name="Importe" v-validate="'decimal:2'">
          <span class="text-danger">{{errors.first('Importe')}}</span>
      </div>
      <div class="col-md-2 mb-3">
          <label >Moneda</label>
          <select class="form-control" v-model="datos_asme_cop.moneda" >
            <option value="MNX">MNX</option>
            <option value="USD">USD</option>
            <option value="EUR">EUR</option>
          </select>
      </div>
      <div class="col-md-1 mb-3">
          <label>&nbsp;</label><br>
          <button @click="guardarasignacion()" class="btn btn-outline-dark" name="button">Crear</button>
      </div>
  </div>
  <hr>
  <div class="form-row">
      <div class="form-group col-md-2">
          <label><b>Fecha</b></label>
      </div>
      <div class="form-group col-md-2">
          <label><b>Con. Pago (%)</b></label>
      </div>
      <div class="form-group col-md-2">
          <label><b>Importe</b></label>
      </div>
      <div class="form-group col-md-2">
          <label><b>Moneda</b></label>
      </div>
      <div class="form-group col-md-1">
          <label><b>.</b></label>
      </div>
  </div>
  <li v-for="(vi, index) in listado_data" class="list-group-item">
      <div class="form-row">
          <div class="form-group col-md-2">
              <label>{{vi.fecha}}</label>
          </div>
          <div class="form-group col-md-2">
              <label>{{vi.cond_pago}}</label>
          </div>
          <div class="form-group col-md-2">
              <label>{{vi.importe}}</label>
          </div>
          <div class="form-group col-md-1">
              <label>{{vi.moneda}}</label>
          </div>
          <div class="form-group col-md-1">
              <a @click="deleteu(index, vi)">
                  <span class="fas fa-trash" arial-hidden="true"></span>
              </a>
          </div>
      </div>
  </li>
  <hr>
  <div class="form-row">
    <div class="col-md-3 mb-3">
      <label>Atención a</label>
      <input type="text" class="form-control" v-model="datos_asme_cop.atencion">
    </div>
    <div class="col-md-3 mb-3">
      <label>Info. Factura</label>
      <select class="form-control" v-model="datos_asme_cop.info_factura">
        <option value="1">Transferencia</option>
        <option value="2">Efectivo</option>
        <option value="3">Deposito con cheque</option>
      </select>
    </div>
    <div class="col-md-3 mb-3">
      <label>Correo envio facturas</label>
      <input type="text" class="form-control" v-model="datos_asme_cop.correo">
    </div>
    <div class="col-mb-3 mb-3">
      <label>Documentos</label>
      <v-select multiple v-model="datos_asme_cop.documentos" :options="['Cotización','Factura','Pago','Remisión','Pedimento']" />
    </div>
  </div>

  </template>
  <!-- </form> scastaños 012-->
</div>
<div class="card-footer">

  <button type="button" v-if="tipoAccion==1" class="btn btn-dark float-sm-right" @click="cerrarModal()"><i class="fas fa-close"></i>&nbsp;Cerrar</button>&nbsp;
  <button type="button" v-if="tipoAccion==2" class="btn btn-dark float-sm-right" @click="cerrarModalActualizar()"><i class="fas fa-close"></i>&nbsp;Cerrar</button>&nbsp;
  <button type="button" v-if="tipoAccion==1" class="btn btn-secondary float-sm-right" @click="GuardarCompra(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
  <button type="button" v-if="tipoAccion==2" class="btn btn-secondary float-sm-right" @click="GuardarCompra(0)"><i class="fas fa-edit"></i>&nbsp;Actualizar</button>
</div>
<div v-show="widgets.modalimpuestos">
  <modalimpuestos ref="modalimpuestos" @modalimpuestos:click="grabar($event)" @update="actualizarImpuestos"></modalimpuestos>
</div>

<div v-show="widgets.modalcotizaciones">
  <modalcotizaciones ref="modalcotizaciones" @modalcotizaciones:click="asignacion($event)"></modalcotizaciones>
</div>

<div v-show="widgets.partidas">
  <partidas ref="partidas" @partidas:click="maestro($event)"></partidas>
</div>

</div>
<!-- fin card principal -->
</template>

<script>
const ModalImpuestos = r => require.ensure([], () => r(require('./ModalImpuestos.vue')), 'compras');
const ModalCotizaciones = r => require.ensure([], () => r(require('./ModalCotizaciones.vue')), 'compras');
const Partidas = r => require.ensure([], () => r(require('./Partidas.vue')), 'compras');

import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default
{
  data()
  {
    return {
      listado_data : [],
      datos_asme_cop : {
        id : 0,
        fecha : '',
        cond_pago : '',
        importe : '',
        moneda : '',
        elabora_empleado_requisicion_id : '',
        atencion : '',
        info_factura : '',
        correo : '',
        documentos : '',
      },
      asme : 0,
      listaPeriodoEntrega : [
        {id : 1, name : '1 día'},
        {id : 2, name : '2 días'},
        {id : 3, name : '3 días'},
        {id : 4, name : '4 días'},
        {id : 5, name : '5 días'},
        {id : 6, name : '6 días'},
        {id : 7, name : '1 semana'},
        {id : 8, name : '2 semanas'},
        {id : 9, name : '3 semanas'},
        {id : 10, name : '4 semanas'},
        {id : 11, name : '5 semanas'},
        {id : 12, name : '6 semanas'},
        {id : 13, name : '7 semanas'},
        {id : 14, name : '8 semanas'},
        {id : 15, name : '9 semanas'},
        {id : 16, name : '10 semanas'},
        {id : 17, name : '11 semanas'},
        {id : 18, name : '12 semanas'},
        {id : 19, name : '13 semanas'},
        {id : 20, name : '14 semanas'},
        {id : 21, name : '15 semanas'},
        {id : 22, name : '16 semanas'},
        {id : 23, name : '17 semanas'},
        {id : 24, name : '18 semanas'},
        {id : 25, name : '19 semanas'},
        {id : 26, name : '20 semanas'},
        {id : 27, name : '21 semanas'},
        {id : 28, name : '22 semanas'},
        {id : 29, name : '23 semanas'},
        {id : 30, name : '24 semanas'},
        {id : 31, name : '25 semanas'},
        {id : 32, name : '26 semanas'},
        {id : 33, name : '27 semanas'},
        {id : 34, name : '28 semanas'},
        {id : 35, name : '29 semanas'},
        {id : 36, name : '30 semanas'},
        {id : 37, name : '31 semanas'},
        {id : 38, name : '32 semanas'},
        {id : 39, name : '33 semanas'},
        {id : 40, name : '34 semanas'},
        {id : 41, name : '35 semanas'},
        {id : 42, name : '36 semanas'},
        {id : 43, name : '37 semanas'},
        {id : 44, name : '38 semanas'},
        {id : 45, name : '39 semanas'},
        {id : 46, name : '40 semanas'},
      ],
      preq:
      {
        cotizacionre: '',
        cotizacionreid: '',
      },
      mostrar: 0,
      getDoc: false,
      upload: false,
      isLoading: false,
      widgets:
      {
        modalimpuestos: false,
        modalcotizaciones: false,
      },
      datosBancarios : [],
      selected: [],
      listImpuestos: [],
      conrequisicion: 0,
      asignacion_costos: 0,
      enabledb : true,
      compra:
      {
        id: 0,
        transferencia: '',
        cuenta: '',
        folio: '',
        condicion: 0,
        ordenes_formato: '',
        condicion_pago: '',
        periodo_entrega: '',
        fecha_orden: '',
        lugar_entrega: '',
        observaciones: '',
        total: '',
        tipo_cambio: 0,
        moneda: '',
        referencia: '',
        cie: '',
        sucursal: '',
        titular: '',
        proveedore_id: '',
        cotizacione_id: '',
        estado_id: '',
        elabora_empleado_id: '',
        autoriza_empleado_id: '',
        rango_dias: 0,
        pagos: 0,
        descuento: 0,
        proyecto_id: '',
        comentario_condicion_pago: '',
        fecha_probable_pago: '',
        prioridad: '',
        nombre_categoria: '',
        proveedore_csct_id: '',
      },
      nombre_categoria: '',
      tituloModal: '',
      Metodo: '',
      tipoAccion: 0,
      listaProvedores: [],
      listaProvedores_CSCT: [],
      listaCotizaciones: [],
      listaEmpleados: [],
      listaProyectos: [],
      listaCondicionPago: [],
      tableData: [],
      mostrareinpuesto: 0,
      nuevo: null,
      ClassL_a: 'btn btn-info',
      BtnL_a: 'Actualizar',
      BtnL_a2: 'Subir Archivo',
      Metodo: '',
      array_asignaciones: [],
      centro_costo: [],
      tabla_asignacion:
      {
        proyecto_id: '',
        porcentaje: '',
        centro_costo_id: '',
      },
    }
  },
  components:
  {
    'modalimpuestos': ModalImpuestos,
    'modalcotizaciones': ModalCotizaciones,
    'partidas': Partidas,
  },
  watch:
  {
    compra:
    {
      deep: true,
      handler: (nuevoValor, valorAnterior) =>
      {
        // console.log(nuevoValor);
        let me = this;
        if(nuevoValor.proyecto_id!=null)
        {

          me.nombre_categoria = nuevoValor.proyecto_id.categoria;
        }
        // console.log(nuevoValor.proyecto_id.categoria);
        // me.asignarV(nuevoValor.proyecto_id.categoria);
      }
    }
  },
  methods:
  {

    regresardb(){
      this.enabledb = true;
    },

    llenarCamposBanco(data){
      // console.log('enytando');

      this.compra.cuenta = '';
      this.compra.clabe = '';
      this.compra.titular = '';
      if (data.target.value === '0') {
        this.enabledb = false;
        this.compra.transferencia = '';
      }else {
        this.enabledb = true;
        this.compra.cuenta = this.datosBancarios[data.target.selectedIndex]['cuenta'];
        this.compra.clabe = this.datosBancarios[data.target.selectedIndex]['clabe'];
        this.compra.titular = this.compra.proveedore_id.nombre;
      }
    },

    buscarDatosBancarios(data){
      this.enabledb = true;

      if ( data != '') {
        // console.log(data,'s');
        axios.get('get/datos/bancarios/proveedor/' + data.id).then(response => {
          this.datosBancarios = response.data;
        }).catch(error => {
          console.error(error);
        });
      }
    },


    creardates(){
      $('#fechas').html('');
      // alert(parseInt(this.compra.pagos));
      // var new_div = '';
      // for (var i = 1; i < (parseInt(this.compra.pagos) +  1); i++) {
      //   new_div += "<div class='col-md-3 mb-1'>";
      //   new_div +='<label> Fecha '+ i + '</label>';
      //   new_div +='<input class="form-control" type="date" id="'+ i +'">';
      //   new_div +="</div>";
      // }
      // $('#fechas').append(new_div);

    },

    cambiarcondicion(){
      $('#fechas').html('');
    },

    asignarV(d)
    {
      // console.log(d);
    },
    /**
    * [getListas Metodos de consulta a la BD ]
    * @return {Response} [Objetos almacenados en diferentes variables]
    */
    getListas()
    {
      // alert('here');
      let me = this;
      me.mostrar = 1;
      axios.get('/proveedores/activos/1').then(response =>{

        me.listaProvedores = [];
        response.data.forEach(data =>{
          me.listaProvedores.push({
            id: data.id,
            name: data.razon_social,
            nombre : data.nombre,
          });
        });

        me.listaProvedores_CSCT = [];
        response.data.forEach(data =>{
          if (data.nombre != 'CSCT') {
            me.listaProvedores_CSCT.push({
              id: data.id,
              name: data.razon_social,
              nombre : data.nombre,
            });
          }
        });
        // me.listaProvedores_CSCT = (me.listaProvedores).pop();
      }).catch(function (error){
        console.log(error);
      });


      axios.get('/cotizacion').then(response =>{
        me.listaCotizaciones = response.data;
      }).catch(function (error){
        console.log(error);
      });

      axios.get('/proyecto-listar-todos').then(response =>{
        me.listaProyectos = [];
        response.data.forEach(data =>{
          me.listaProyectos.push({
            id: data.id,
            name: data.nombre_corto,
            categoria: data.nombre_categoria,
            nombre_corto: data.nombre_corto
          });
        });
      }).catch(function (error){
        console.log(error);
      });

      // this.listaEmpleadosCompleta = [];
      axios.get('/vertodosempleados').then(response =>{
          response.data.forEach(data =>{
              this.listaEmpleadosCompleta.push({
                  id: data.id,
                  name: data.nombre + ' ' + data.ap_paterno + ' ' + data.ap_materno
              });
          });
      })
      .catch(function (error)
      {
          console.log(error);
      });

      // axios.get('/vertodosempleados').then(response =>
      //   {
      me.listaEmpleados = [];
      //     response.data.forEach(data =>
      //       {
      //         me.listaEmpleados.push(
      //           {
      //             id: data.id,
      //             name: data.nombre + ' ' + data.ap_paterno + ' ' + data.ap_materno
      //           });
      //         });
      //       })
      //       .catch(function (error)
      //       {
      //         console.log(error);
      //       });

      me.listaEmpleados.push(
        {
          id: 6,
          name: 'JOEL MACHORRO MARTINEZ'
        },
        {
          id: 241,
          name: 'RODOLFO RODRIGUEZ ALVARADO'
        },
        {
          id: 205,
          name: 'RAMON CRUZ MARTINEZ'
        },
      );

      axios.get('/condicion_pago/ver').then(response =>{
        me.listaCondicionPago = response.data;
        me.isLoading = false;
      }).catch(function (error){
        console.log(error);
      });

      axios.get(`/catalogos/centrocostos/`).then(response =>{
        me.centro_costo = response.data;
      }).catch(error =>{
        console.log(error)
      });
    },

    changeproyecto()
    {
      this.nombre_categoria = this.compra.proyecto_id.categoria;
      if (this.getDoc && this.tipoAccion == 1)
      {
        this.preq.cotizacionre = "";
        var childmodalcotizaciones = this.$refs.modalcotizaciones;
        childmodalcotizaciones.limpiarDatos();
        this.getDoc = false;
      }
    },
    //
    // changeActualizarProyecto(data){
    //   data.nombre_categoria === 'Administrativo' ? this.asignacion_costos = 1 : 0;
    //   console.log(data.nombre_categoria,'changeActualizarProyecto');
    // },

    changeproveedor()
    {
      if (this.getDoc && this.tipoAccion == 1)
      {
        this.preq.cotizacionre = "";
        var childmodalcotizaciones = this.$refs.modalcotizaciones;
        childmodalcotizaciones.limpiarDatos();
        this.getDoc = false;
        // console.log('cambió proveedor');
      }
    },

    cotizacionprovedor()
    {
      this.changeproveedor();
      this.selected = [];
      axios.get('/cotizacion/' + this.compra.proveedore_id.id).then(response =>{
        for (var i = 0; i < response.data.length; i++)
        {
          this.selected.push({
            id: response.data[i].id,
            folio: response.data[i].folio
          });
        }
      }).catch(error =>{
        console.error(error);
      });

      axios.get('/proveedores/' + this.compra.proveedore_id.id).then(response =>{
        this.compra.transferencia = response.data.banco_transferencia;
        this.compra.clabe = response.data.clabe;
        this.compra.cuenta = response.data.numero_cuenta;
      });
    },

    cargarcardprincipal(modelo, accion, data = [])
    {
      this.p(data);

      this.isLoading = true;
      let me = this;
      me.getListas();
      me.tableData = data;
      switch (modelo){
        case "compra":
        {
          switch (accion){
            case 'registrar':
            {
              // this.modal= 1;
              this.tituloModal = 'Registrar compra';
              this.Metodo = 'Nuevo';
              Utilerias.resetObject(this.compra);
              this.tipoAccion = 1;
              this.disabled = 0;
              this.principal = true;
              this.mostrareinpuesto = 0;

              break;
            }
            case 'actualizar':
            {
              this.selected = [];
              this.upload = true;
              Utilerias.resetObject(this.compra);
              this.array_asignaciones = [];
              this.tituloModal = 'Actualizar compra';
              this.compra.id = data['id'];
              this.tipoAccion = 2;
              this.disabled = 1;
              this.mostrareinpuesto = 1;
              this.compra.folio = data['folio'];
              this.compra.ordenes_formato = data['ordenes_formato'];
              this.compra.condicion_pago = data['condicion_pago_id'];
              this.compra.periodo_entrega = {id:0, name : data['periodo_entrega']};
              this.compra.fecha_orden = data['fecha_orden'];
              this.compra.lugar_entrega = data['lugar_entrega'];
              this.compra.observaciones = data['observaciones'];
              this.compra.descuento = data['descuento'];
              this.compra.total = data['total'];
              this.compra.tipo_cambio = data['tipo_cambio'];
              this.compra.moneda = data['moneda'];
              this.compra.referencia = data['referencia'];
              this.compra.cie = data['cie'];
              this.compra.sucursal = data['sucursal'];
              this.compra.transferencia = data['datos_bancarios'] == null ? '' : data['datos_bancarios']['banco'];
              this.compra.clabe = data['datos_bancarios'] == null ? '' : data['datos_bancarios']['clabe'];
              this.compra.cuenta = data['datos_bancarios'] == null ? '' : data['datos_bancarios']['cuenta'];
              this.compra.titular = data['datos_bancarios'] == null ? '' :data['datos_bancarios']['titular'];
              this.compra.fecha_probable_pago = data['fecha_probable_pago'] == null ? '' : data['fecha_probable_pago'];
              this.compra.prioridad = data['prioridad'] == null ? '' : data['prioridad'];
              this.compra.proveedore_id = {
                id: data['proveedore_id'],
                name: data['proveedor_razon_social']
              };
              this.compra.proveedore_csct_id = {
                id: data['proveedore_csct_id'],
                name: data['proveedor_razon_social_csct']
              };
              // this.cotizacionprovedor();

              // Obtener impuestos
              axios.get("/comprabusquedaimpuesto/" + data.id).then(res =>{
                this.listImpuestos = res.data;
              }).catch(r =>{
                console.error("ERROR");
                console.error(r);
              });

              this.compra.estado_id = data['estado_id'];
              this.compra.elabora_empleado_id = {
                id: data['elabora_empleado_id'],
                name: data['nombre_empleado_elabora']
              };
              this.compra.autoriza_empleado_id = {
                id: data['autoriza_empleado_id'],
                name: data['nombre_empleado_autoriza']
              };
              this.compra.rango_dias = data['rango_dias'];
              this.compra.pagos = data['pagos'];
              this.compra.condicion = data['condicion'];
              this.compra.proyecto_id = {
                id: data['proyecto_id'],
                name: data['nombre_corto_proyecto'],
                nombre_categoria: data['nombre_categoria']
              };
              this.compra.comentario_condicion_pago = data['comentario_condicion_pago'];
              this.Metodo = 'Actualizar';
              this.principal = true;
              this.nombre_categoria = data['nombre_categoria'];

              this.asme = data['asme'];

              // this.datos_asme_cop.id : 0,
              // this.datos_asme_cop.elabora_empleado_requisicion_id : '',
              this.datos_asme_cop.atencion = data['atencion'];
              this.datos_asme_cop.info_factura  = data['info_factura'];
              this.datos_asme_cop.correo = data['correo'];
              this.datos_asme_cop.documentos = data['documentos'].split(',');
              this.datos_asme_cop.elabora_empleado_requisicion_id = JSON.parse(data['empleado_elabora_requisicion']);

              this.listado_data = [];
              axios.get('condiciones/pago/asme/' + data['asme_id']).then(response => {
                this.listado_data = response.data;
              }).catch(e => {
                console.error(e);
              });

              break;
            }
          }
        }
      }
    },

    cerrar()
    {
      if (this.getDoc)
      {
        var method = 2;
        axios.get('/obtenerarchivos/' + method + '&' + this.compra.proyecto_id.id + '&' + this.compra.proveedore_id.id).then(response =>{
          //console.log(response.data);
        }).catch(error =>{
          console.error(error);
        });
      }
      var d = 3;
      this.upload = false;
      this.$emit('cardprincipal:click');
      this.ClassL_a = 'btn btn-info';
      this.BtnL_a = 'Actualizar';
      this.BtnL_a2 = 'Subir Archivo';
      if (this.tipoAccion == 2)
      {
        // document.getElementById('file_factura').value='';
        $('#file_factura').html('');
      }
      Utilerias.resetObject(this.compra);
      this.preq.cotizacionre = "";
      var childmodalcotizaciones = this.$refs.modalcotizaciones;
      childmodalcotizaciones.limpiarDatos();
      this.getDoc = false;

    },

    cerrarModal()
    {
      this.mostrar = 0;
      this.$emit('cardprincipalcerrarmodal:click');
    },

    cerrarModalActualizar()
    {
      this.mostrar = 0;
      this.$emit('comprascerrarmodal:click');
    },

    cerraruno(data)
    {
      this.$emit('cardprincipaluno:click', data);
    },

    agregarinpuestos(num, id)
    {

      let me = this;
      me.widgets.modalimpuestos = true;
      var childmodalimpuestos = this.$refs.modalimpuestos;
      childmodalimpuestos.cargarinpuesto(num, id, this.listImpuestos);

    },

    agregarCotizacion(proyecto, proveedor)
    {
      if (this.compra.proveedore_id != '' && this.compra.proyecto_id != '')
      {
        let me = this;
        this.getDoc = true;
        me.widgets.modalcotizaciones = true;
        var childmodalcotizaciones = this.$refs.modalcotizaciones;
        childmodalcotizaciones.cargarcotizacion(proyecto.id, proveedor.id);
        if (this.upload)
        {
          var childmodalcotizaciones = this.$refs.modalcotizaciones;
          childmodalcotizaciones.cambio();;
        }
      }
      else
      {
        toastr.warning('Atención', 'Debe seleccionar un Proyecto y un Proveedor');
      }
    },

    grabar(nuevo)
    {
      this.nuevo = nuevo;
    },

    asignacion(datos)
    {
      this.preq.cotizacionre = datos[0];
      this.preq.cotizacionreid = datos[1];
    },

    obtenerCot(orden_id)
    {
      var cadenadoc = [];
      var cadenadocnomb = [];
      var Metodo = 2;
      var childmodalcotizaciones = this.$refs.modalcotizaciones;

      axios.get('/cotizacionesrequeridas/' + orden_id + '&' + Metodo).then(function (response){
        for (var i = 0; i < response.data.length; i++)
        {
          cadenadoc.push(response.data[i].folio);
          cadenadocnomb.push(response.data[i].id);
        }
      }).catch(function (error){
        console.log(error);
      });

      this.preq.cotizacionre = cadenadoc;
      childmodalcotizaciones.getCotizaciones(cadenadocnomb);
    },

    /**
    * [GuardarCompra description]
    * @param {Int} nuevo [description]
    */
    GuardarCompra(nuevo)
    {
      this.$validator.validate().then(result =>{

        if (result)
        {
          this.isLoading = true;
          let me = this;

          var conrequisicion = me.conrequisicion;
          var asme = me.asme;
          var tipos = [];
          var porcentajes = [];
          var ret = [];

          this.nuevo = this.listImpuestos;
          let n = 0;
          if (me.nuevo != null)
          {
            // if (me.nuevo.length != 0) {
            me.nuevo.forEach((item, i) =>{
              if (item.id === "0")
              {
                this.p(item);
                tipos.push(item.tipo);
                // if (item.porcentaje != '') {
                porcentajes.push(item.porcentaje);
                // }
                ret.push(item.retenido);
                n += 1;
              }

            });
            var tam = n;
            var impuestovar = me.nuevo;
            // }
          }

          var proyectos = [];
          var porcentaje = [];
          var catalogo_costos = [];
          var tam_asignacion = 0;
          var id_asignacion = [];

          if (me.array_asignaciones != null)
          {
            me.array_asignaciones.forEach((item, i) =>{
              proyectos.push(item.proyecto_id);
              porcentaje.push(item.porcentaje);
              catalogo_costos.push(item.centro_costo_id);
              id_asignacion.push(item.id);
            });
            tam_asignacion = me.array_asignaciones.length;

          }
          var cotizaciones = [];
          if (this.compra.cotizacione_id != null)
          {

            for (var i = 0; i < Object.keys(this.compra.cotizacione_id).length; i++)
            {
              cotizaciones.push(this.compra.cotizacione_id[i].id);
            }
          }

          var dias = parseInt(this.compra.pagos);
          var arreglo_fechas = [];
          // for (var i = 1; i <= dias; i++) {
          //   arreglo.push($('#'+i).val());
          // }
          // console.log(Object.getOwnPropertyDescriptor(this.compra.cotizacione_id,0));

          // console.log(cotizaciones);
          // let formData = new FormData();
          //
          // formData.append('metodo', this.Metodo);
          // formData.append('id', this.compra.id);
          // formData.append('folio', this.compra.folio);
          // formData.append('ordenes_formato', this.compra.ordenes_formato);
          // formData.append('condicion_pago_id', this.compra.condicion_pago);
          // formData.append('periodo_entrega', this.compra.periodo_entrega.name);
          // formData.append('fecha_orden', this.compra.fecha_orden);
          // formData.append('lugar_entrega', this.compra.lugar_entrega);
          // formData.append('observaciones', this.compra.observaciones);
          // formData.append('total', this.compra.total);
          // formData.append('descuento', this.compra.descuento);
          // formData.append('tipo_cambio', this.compra.tipo_cambio);
          // formData.append('moneda', this.compra.moneda);
          // formData.append('referencia', this.compra.referencia);
          // formData.append('cie', this.compra.cie);
          // formData.append('sucursal', this.compra.sucursal);
          // formData.append('proveedore_id', this.compra.proveedore_id.id);
          // formData.append('cotizacione_id', cotizaciones);
          // formData.append('proyecto_id', this.compra.proyecto_id.id);
          // formData.append('proveedore_csct_id', (this.compra.proveedore_csct_id == '' ? this.compra.proveedore_csct_id : this.compra.proveedore_csct_id.id ));
          // formData.append('estado_id', this.compra.estado_id);
          // formData.append('elabora_empleado_id', this.compra.elabora_empleado_id.id);
          // formData.append('autoriza_empleado_id', this.compra.autoriza_empleado_id.id);
          // formData.append('rango_dias', this.compra.rango_dias);
          // formData.append('pagos', this.compra.pagos);
          // formData.append('tipos', tipos);
          // formData.append('porcentaje', porcentajes);
          // formData.append('retenido', ret);
          // formData.append('tamanio', tam);
          // formData.append('comentario_condicion_pago', this.compra.comentario_condicion_pago);
          // formData.append('conrequisicion', conrequisicion);
          // formData.append('fecha_probable_pago', this.compra.fecha_probable_pago);
          // formData.append('prioridad', this.compra.prioridad);
          // formData.append('banco',this.compra.transferencia);
          // formData.append('clabe',this.compra.clabe);
          // formData.append('cuenta',this.compra.cuenta);
          // formData.append('titular',this.compra.titular);
          // formData.append('fechas',arreglo_fechas);
          // formData.append('asme',this.asme);
          // formData.append('datos_asme_cop',this.datos_asme_cop);

          axios.post('/compras', {
            'metodo':  this.Metodo,
            'id':  this.compra.id,
            'folio':  this.compra.folio,
            'ordenes_formato':  this.compra.ordenes_formato,
            'condicion_pago_id':  this.compra.condicion_pago,
            'periodo_entrega':  this.compra.periodo_entrega.name,
            'fecha_orden':  this.compra.fecha_orden,
            'lugar_entrega':  this.compra.lugar_entrega,
            'observaciones':  this.compra.observaciones,
            'total':  this.compra.total,
            'descuento':  this.compra.descuento,
            'tipo_cambio':  this.compra.tipo_cambio,
            'moneda':  this.compra.moneda,
            'referencia':  this.compra.referencia,
            'cie':  this.compra.cie,
            'sucursal':  this.compra.sucursal,
            'proveedore_id':  this.compra.proveedore_id.id,
            'cotizacione_id':  cotizaciones,
            'proyecto_id':  this.compra.proyecto_id.id,
            'proveedore_csct_id':  (this.compra.proveedore_csct_id == '' ? this.compra.proveedore_csct_id : this.compra.proveedore_csct_id.id ),
            'estado_id':  this.compra.estado_id,
            'elabora_empleado_id':  this.compra.elabora_empleado_id.id,
            'autoriza_empleado_id':  this.compra.autoriza_empleado_id.id,
            'rango_dias':  this.compra.rango_dias,
            'pagos':  this.compra.pagos,
            'tipos':  tipos,
            'porcentaje':  porcentajes,
            'retenido':  ret,
            'tamanio':  tam,
            'comentario_condicion_pago':  this.compra.comentario_condicion_pago,
            'conrequisicion':  conrequisicion,
            'fecha_probable_pago':  this.compra.fecha_probable_pago,
            'prioridad':  this.compra.prioridad,
            'banco': this.compra.transferencia,
            'clabe': this.compra.clabe,
            'cuenta': this.compra.cuenta,
            'titular': this.compra.titular,
            'fechas': arreglo_fechas,
            'asme': asme,
            'datos_asme_cop': this.datos_asme_cop,
            'datos_asme_cond' : this.listado_data,

          }).then(function (response){
            me.isLoading = false;
            if (response.data.status){
              if (response.data.status === 'error'){
                swal({
                  type: 'error',
                  html: 'Ha ocurrido un error notifiqué al administrador y recargue la página',
                });
              }
              else{
                me.enabledb = true;
                $("#input_impuesto").html('');

                me.listImpuestos = [];

                if (!nuevo){

                  toastr.success('Compra Actualizada Correctamente');
                  me.nuevo = null;
                  me.cerrar();

                  // this.cerrarModalActualizar();
                  me.cerraruno(me.tableData.proyecto_id);
                }
                else{
                  if (me.conrequisicion == 0){
                    me.proceso(response.data.compra);
                  }
                  else{
                    me.procesodos(response.data.compra);
                  }
                  toastr.success('Compra Agregada Correctamente');
                  me.nuevo = null;
                  this.cerrarModal();

                }
              }
            }
            else
            {
              swal({
                type: 'error',
                html: response.data.errors.join('<br>'),
              });
            }
          }).catch(function (error){
            console.log(error);
          });
        }
        else
        {
          toastr.warning("Complete todos los campos obligatorios", "Atención");

        }
      });
    },

    /**
    * [cargardetalle description]
    * @param  {Array}  [dataEmpleado=[]] [description]
    * @return {[Response]}                   [description]
    */
    proceso(data)
    {
      this.$emit('cardprincipalproceso:click', data);
    },

    procesodos(data)
    {
      this.$emit('cardprincipalprocesodos:click', data);
    },

    /**
    * [onChangeFactura description]
    * @param  {[type]} e [description]
    * @return {[type]}   [description]
    */
    onChangeFactura(e)
    {
      var nombreL_a = e.target.files[0].name;
      var extension = nombreL_a.split('.').pop();
      if (extension == 'pdf')
      {
        this.ClassL_a = 'btn btn-warning';
        this.compra.ordenes_formato = e.target.files[0];
        this.BtnL_a = 'Archivo Cargado';
        this.BtnL_a2 = 'Archivo Cargado';
      }
      else
      {
        this.ClassL_a = 'btn btn-danger';
        this.BtnL_a = 'Archivo Invalido(Omitido)';
        this.BtnL_a2 = 'Archivo Invalido(Omitido)';
      }
    },

    /**
    * [descargarFactura description]
    * @param  {[type]} archivo [description]
    * @param  {[type]} id      [description]
    * @return {[type]}         [description]
    */
    descargarFactura(archivo, id)
    {
      let me = this;
      axios(
        {
          url: me.url + '/' + archivo,
          method: 'PUT',
          data:
          {
            'metodo': id,
            'archivo': archivo
          },
          responseType: 'blob', // importante
        }).then((response) =>
        {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
          document.body.appendChild(link);
          link.click();
          //--Llama el metodo para borrar el archivo local una ves descargado--//
          axios(
            {
              url: me.url + '/' + archivo,
              method: 'PUT',
              data:
              {
                'metodo': 0,
                'archivo': archivo
              },
            })
            .then(response =>
              {})
              .catch(function (error)
              {
                console.log(error);
              });
              //--fin del metodo borrar--//
            });
          },

          cambiar(data)
          {

            if (data.target.checked == true)
            {
              this.conrequisicion = 1;
            }
            else
            {
              this.conrequisicion = 0;
            }
          },

          eliminar_aignacion(data, index)
          {
            if (data.id == 0)
            {
              this.array_asignaciones.splice(index, 1);
            }
            else
            {
              let me = this;
              var id = data['id'];
              axios.get(`asignacion_costos_admin/eliminar/${id}`)
              .then(function (response)
              {
                me.array_asignaciones.splice(index, 1);
              })
              .catch(function (error)
              {
                console.log(error);
              });
            }
          },

          crearAsignacion()
          {
            var porcentaje = 0;
            this.array_asignaciones.forEach((item, i) =>
            {
              porcentaje += parseFloat(item.porcentaje);
            });
            porcentaje += parseFloat(this.tabla_asignacion.porcentaje);
            if (porcentaje <= 100)
            {
              this.array_asignaciones.push(
                {
                  id: 0,
                  proyecto_id: this.tabla_asignacion.proyecto_id.id,
                  nombre_corto: this.tabla_asignacion.proyecto_id.nombre_corto,
                  porcentaje: this.tabla_asignacion.porcentaje,
                  centro_costo_id: this.tabla_asignacion.centro_costo_id.id,
                  centro_costo: this.tabla_asignacion.centro_costo_id.nombre + ' ' + (this.tabla_asignacion.centro_costo_id.nombre_sub == null ? '' : this.tabla_asignacion.centro_costo_id.nombre_sub)
                });
                Utilerias.resetObject(this.tabla_asignacion);
              }
              else
              {
                toastr.warning('No se puede asignar un porcentaje mayor al 100%');
                this.tabla_asignacion.porcentaje = '';
              }
            },

            vaciarAsignacion()
            {
              Utilerias.resetObject(this.tabla_asignacion);
            },
            actualizarImpuestos(imps)
            {
              this.p(imps);
              this.listImpuestos = imps;
            },

            guardarasignacion(){

              if (this.datos_asme_cop.fecha == '') {
                toastr.warning('La fecha es requerida');
              }else if (this.datos_asme_cop.cond_pago == '') {
                toastr.warning('La condicion es requerida');
              }else if (this.datos_asme_cop.importe == '') {
                toastr.warning('El importe es requerido');
              }else if (this.datos_asme_cop.moneda == '') {
                toastr.warning('La moneda es requerida');
              }else {
                this.listado_data.push({
                  id : 0,
                  fecha : this.datos_asme_cop.fecha,
                  cond_pago : this.datos_asme_cop.cond_pago,
                  importe : this.datos_asme_cop.importe,
                  moneda : this.datos_asme_cop.moneda
                });

                this.datos_asme_cop.fecha = '';
                this.datos_asme_cop.cond_pago = '';
                this.datos_asme_cop.importe = '';
                this.datos_asme_cop.moneda = '';
              }
            },

           deleteu(index){
              this.listado_data.splice(index, 1);
            },

            p(asd)
            {
              console.error(asd);
            }

          },
          mounted()
          {

          }
        }
        </script>
