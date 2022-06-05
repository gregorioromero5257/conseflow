<template>
  <main class="main">
    <div class="container-fluid">
      <br>
      <!-- Inicio del contenido principal -->
      <div class="card" v-show="ver == 1">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Solicitudes {{empresa == 1 ? 'CONSERFLOW' : empresa == 2 ? 'CSCT' : ''}}
          <div class="dropdown">

            <button class="btn btn-secondary dropdown-toggle float-sm-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Empresa
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2" v-model="empresa">
              <button class="dropdown-item" type="button" @click="empresa = 1;buscarCFW();">Conserflow</button>
              <button class="dropdown-item" type="button" @click="empresa = 2;buscarCSCT();">Constructora</button>
            </div>
          </div>

          <button type="button" class="btn btn-dark float-sm-right" @click="abrirModal('solicitud','registrar')">
            <i class="fas fa-plus">&nbsp;</i>Nuevo
          </button>

          <button type="button" class="btn btn-success float-sm-right" data-toggle="tooltip" data-placement="top" title="Reporte Excel" @click="ReporteGral()">
            <i class="fas fa-file-excel">&nbsp;</i>
          </button>

          <button type="button" class="btn btn-danger float-sm-right" data-toggle="tooltip" data-placement="top" title="Reporte PDF" @click="ReporteGralPdf()">
            <i class="fas fa-file-pdf">&nbsp;</i>
          </button>

        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="solicitud.id" slot-scope="props">
              <!-- {{props}} -->
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a type="button" v-show="props.row.solicitud.eliminado == 0" @click.prevent="abrirModal('solicitud','actualizar',props.row)" class="dropdown-item" href="#">
                      <i class="icon-pencil"></i>&nbsp;Actualizar Solicitud
                    </a>
                    <!-- v-show="props.row.solicitud.estado > 1 "  -->
                    <a type="button"  v-show="props.row.solicitud.eliminado == 0" @click.prevent="verdetalle(props.row, 1)" class="dropdown-item" href="#">
                      <i class="fas fa-eye"></i>&nbsp;Ver detalles
                    </a>
                    <a type="button" v-show="PermisosCrud.Read && props.row.solicitud.eliminado == 0" @click.prevent="eliminar(props.row.solicitud.id, empresa)" class="dropdown-item" href="#">
                      <i class="fas fa-trash"></i>&nbsp;Eliminar
                    </a>
                    <a type="button" v-show="props.row.solicitud.estado < 2 && props.row.solicitud.eliminado == 0" @click.prevent="enviaRevision(props.row.solicitud.id, 2)" class="dropdown-item" href="#">
                      <i class="far fa-paper-plane"></i>&nbsp;Cerrar
                    </a>

                  </div>
                </div>
              </div>
              <!-- <div class="dropdown2">
              <button class="dropbtn2"><i class="fas fa-cogs"></i></button>
              <div class="dropdown-content2">
              <button class="button2" type="button" v-show="props.row.solicitud.estado < 2" @click.prevent="abrirModal('solicitud','actualizar',props.row)">
              <i class="far fa-window-close"></i>&nbsp; Actualizar Solicitud</button>
              <button class="button2" type="button" v-show="props.row.solicitud.estado > 1" @click.prevent="verdetalle(props.row, 1)">
              <i class="far fa-file-alt"></i>&nbsp; Ver detalles</button>
              <button class="button2" type="button" v-show="props.row.solicitud.estado < 2" @click.prevent="enviaRevision(props.row.solicitud.id, 2)">
              <i class="far fa-edit"></i>&nbsp; Enviar para revision</button>
              <button class="button2" type="button" @click="decargarForVia(props.row.solicitud.id)">
              <i class="far fa-edit"></i>&nbsp; Descargar Formato</button>
              <button class="button2" type="button" @click="descargarnForFij(props.row.solicitud.id)">
              <i class="far fa-edit"></i>&nbsp; Descargar Nuevo Formato</button>
            </div>
          </div> -->


        </template>
        <template slot="solicitud.estado" slot-scope="props" >
          <template v-if="props.row.solicitud.eliminado == 1">
            <span class="btn btn-outline-danger">Eliminado</span>
          </template>
          <template v-if="props.row.solicitud.eliminado == 0">

          <template v-if="props.row.solicitud.estado == 6">
            <span class="btn btn-outline-info">Finalizado</span>
          </template>
          <template v-if="props.row.solicitud.estado == 5">
            <span class="btn btn-outline-info">Pagos <br> agendados</span>
          </template>
          <template v-if="props.row.solicitud.estado == 4">
            <span class="btn btn-outline-info">En espera <br> de pagos</span>
          </template>
          <template v-if="props.row.solicitud.estado == 3">
            <span class="btn btn-outline-warning">En Autorizacion</span>
          </template>
          <template v-if="props.row.solicitud.estado == 2">
            <span class="btn btn-outline-warning">En revisión</span>
          </template>
          <template v-if="props.row.solicitud.estado == 1">
            <span class="btn btn-outline-success">Nuevo</span>
          </template>
          <template v-if="props.row.solicitud.estado == 0">
            <span class="btn btn-outline-danger">No autorizado</span>
          </template>
        </template>
      </template>

        <template slot="solicitud.tipo" slot-scope="props">
          <template v-if="props.row.solicitud.tipo ==  0">
            SINDICATO
          </template>
          <template v-if="props.row.solicitud.tipo ==  1">
            REEMBOLSO
          </template>
          <template v-if="props.row.solicitud.tipo ==  2">
            VIATICOS
          </template>
        </template>

        <template slot="descarga" slot-scope="props">
          <template v-if="props.row.solicitud.tipo ==  0">
            <button type="button" @click="descargarnForFij(props.row.solicitud.id, empresa)" class="btn btn-outline-dark" >
              <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>
            </button>
          </template>
          <template v-if="props.row.solicitud.tipo ==  1">
            <button type="button" @click="decargarForVia(props.row.solicitud.id, empresa)" class="btn btn-outline-dark" >
              <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>
            </button>
          </template>
          <template v-if="props.row.solicitud.tipo ==  2">
            <button type="button" @click="decargarForVia(props.row.solicitud.id, empresa)" class="btn btn-outline-dark" >
              <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>
            </button>
          </template>
        </template>

        <template slot="timbrado" slot-scope="props">
          <button type="button" v-show="PermisosCrud.Read && props.row.solicitud.eliminado == 0" @click="timbrar(props.row.solicitud.id,1)" class="btn btn-outline-dark" >
            <i class="fas fa-bell"></i>
          </button>
        </template>

        <template slot="beneficiario" slot-scope="props">
          {{props.row.beneficiarios[0].nombre_beneficiario}}
        </template>


      </v-client-table>
    </div>

  </div>
  <div v-show="ver == 4">
    <ul class="nav nav-tabs" role="tablist" ref="tabs">
      <li class="nav-item" v-show="PermisosCrud.Create">
        <a class="nav-link active" data-toggle="tab" href="#menu1" role="tab" @click="reporte()"><i class="fas fa-book"></i>&nbsp;Reporte Gastos</a>
      </li>
      <!-- <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#menu2" role="tab" @click="fechaspagos()"><i class="fas fa-tools"></i>&nbsp;Pagos</a>
    </li> -->
    <li class="nav-item"  v-show="PermisosCrud.Read">
      <a class="nav-link" data-toggle="tab" href="#menu2" role="tab" @click="verfechas()"><i class="fas fa-tools"></i>&nbsp;Pagos</a>
    </li>
    <li class="nav-item"  v-show="PermisosCrud.Update">
      <a class="nav-link" data-toggle="tab" href="#menu4" role="tab" @click="verpagos()"><i class="fas fa-tools"></i>&nbsp;Pagos Recibidos</a>
    </li>
    <li class="nav-item" v-show="PermisosCrud.Delete">
      <a class="nav-link" data-toggle="tab" href="#menu3" role="tab" @click="revisareporte()"><i class="fas fa-book"></i>&nbsp;Revisión Gastos</a>
    </li>
    <li class="nav-item">
      <a class="btn btn-secondary float-sm-right" @click="maestro()"><i class="fa fa-arrow-left"></i>&nbsp;Atras</a>
    </li>
  </ul>
  <div class="tab-content">
    <div id="menu1" class="tab-pane fade in active show" >
      <div v-show="tabs == 1">
        <reporte ref="reporte" @maestro="maestro()" @recargar="verdetalle($event)"></reporte>
      </div>
    </div>
    <div id="menu2" class="tab-pane fade">
      <div v-show="tabs == 2">
        <detalle ref="detalle"></detalle>
      </div>
    </div>
    <div id="menu3" class="tab-pane fade" >
      <div v-show="tabs == 3">
        <reporterevision ref="reporterevision"  @recargar="verdetalle($event)" ></reporterevision>
      </div>

    </div>
    <div id="menu4" class="tab-pane fade" >
      <div v-show="tabs == 4">
        <detalledos ref="detalledos" ></detalledos>
      </div>

    </div>
    <!-- <div id="menu2" class="tab-pane fade">
    <div v-show="tabs == 2">
    <detalle ref="detalle" @detalle:maestro="maestro()"></detalle>
  </div>
</div> -->
</div>
</div>

<!-- Inicio del modal agregar/actualizar -->
<div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" arial-hidden="true">
  <div class="modal-dialog modal-dark modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" v-text="tituloModal"></h4>
        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
          <span arial-hidden="true">X</span>
        </button>
      </div>
      <div class="modal-body">
        <vue-element-loading :active="isLoading"/>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>&nbsp;Tipo</label>
            <select class="form-control" v-model="tipo" v-validate="'required'" data-vv-name="tipo" @change="componenteBeneficiario()">
              <option value="0">SINDICATO</option>
              <option value="1">REEMBOLSO</option>
              <option value="2">VIATICOS</option>
            </select>
            <span class="text-danger">{{errors.first('tipo')}}</span>
          </div>
          <div class="form-group col-md-6">
            <label>&nbsp;Fecha solicitud</label>
            <input type="date" name="fecha solicitud" v-model="solicitud.fecha" class="form-control"  data-vv-name="fecha solicitud" ><!---->
            <span class="text-danger">{{errors.first('fecha solicitud')}}</span>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>&nbsp;Fecha requerida de pago</label>
            <input type="date" name="fecha de pago" v-model="solicitud.fecha_pago" class="form-control"  data-vv-name="fecha de pago" ><!--v-validate="'required'"-->
            <span class="text-danger">{{errors.first('fecha de pago')}}</span>
          </div>
          <div class="form-group col-md-6">
            <label>&nbsp;Proyecto</label>
            <v-select :options="optionsvs_proyecto"  v-validate="'required'" v-model="solicitud.proyecto_id" label="name" name="proyecto" data-vv-name="proyecto" ></v-select> <!---->
            <span class="text-danger">{{ errors.first('proyecto') }}</span>
          </div>
        </div>
        <hr>
        <div class="accordion" id="accordion">

          <div class="card">
            <div class="card-header" id="headingOne">
              <h5 class="mb-0" >
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  BENEFICIARIOS
                </button>
              </h5>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="card-body">
                <beneficiario ref="beneficiario"
                @enviarUno="enviouno($event)" @enviarDos="enviodos($event)"
                @limpiarUno="limpiarBeneficiarioUno()" @limpiarDos="limpiarBeneficiarioDos()"></beneficiario>
              </div>
            </div>
          </div>
        <!-- </div> -->
        <!-- <hr> -->
        <!-- <div class="accordion" id="accordion_uno"> -->
          <div class="card">
            <div class="card-header" id="headingTwo">
              <h5 class="mb-0" >
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                  DETALLE DE VIATICOS
                </button>
              </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body" >
                <detallesv ref="detallesv" @listado="envio_u($event)" @totales="envio_d($event)" @conceptos="envio_c($event)"></detallesv>
              </div>
            </div>
          </div>
        <!-- </div> -->
        <!-- <hr> -->
        <!-- <template v-if="tipo > 0"> -->

          <!-- <div class="accordion" id="accordion_dos"> -->
            <div class="card" v-show="tipo > 0">
              <div class="card-header" id="headingThree">
                <h5 class="mb-0">
                  <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                    NOTAS DE ITINERIARIO Y LOGISTICA
                  </button>
                </h5>
              </div>
              <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                  <div class="form-row">
                    <div class="form-group col-md-2">
                      <label>Origen</label>
                    </div>
                    <div class="form-group col-md-4">
                      <input type="text"  v-model="solicitud.origen_destino" class="form-control" name="origen_destino" data-vv-name="origen_destino"><!--v-validate="'required'"-->
                      <span class="text-danger">{{ errors.first('origen_destino') }}</span>
                    </div>
                    <div class="form-group col-md-2">
                      <label>Destino</label>
                    </div>
                    <div class="form-group col-md-4">
                      <input type="text"  v-model="solicitud.origen_destino_destino" class="form-control" name="destino" data-vv-name="destino"><!--v-validate="'required'"-->
                      <span class="text-danger">{{ errors.first('destino') }}</span>
                    </div>
                  </div><hr>
                  <div class="form-row">
                    <div class="form-group col-md-5">
                      <label>1.- Fecha salida:</label>
                      <label>2.- Hora de estimada de salida:</label>
                    </div>
                    <div class="form-group col-md-4">

                      <div class="input-group">
                        <div class="input-group-addon">
                          <span class="input-group-text">1</span>
                        </div>
                        <!-- v-validate="'required'" -->
                        <input type="date" class="form-control"  v-model="solicitud.fecha_salida" name="fecha_salida" data-vv-name="fecha_salida">
                        <span class="text-danger">{{ errors.first('fecha_salida') }}</span>
                      </div>
                      <div class="input-group">
                        <div class="input-group-addon">
                          <span class="input-group-text">2</span>
                        </div>
                        <input type="time" class="form-control" v-model="solicitud.hora_estimada_salida" name="hora_estimada_salida" data-vv-name="hora_estimada_salida" >
                        <span class="text-danger">{{ errors.first('hora_estimada_salida')}}</span>
                      </div>
                    </div>
                    <div class="form-group col-md-5">
                      <label>3.- Fecha de operación:</label>
                      <label>4.- Fecha de retorno estimada:</label>
                    </div>
                    <div class="form-group col-md-4">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <span class="input-group-text">3</span>
                        </div>
                        <input type="date" class="form-control" v-model="solicitud.fecha_operacion" name="fecha_operacion" data-vv-name="fecha_operacion" >
                        <span class="text-danger"> {{ errors.first('fecha_operacion') }} </span>
                      </div>
                      <div class="input-group">
                        <div class="input-group-addon">
                          <span class="input-group-text">4</span>
                        </div>
                        <input type="date" class="form-control" v-model="solicitud.fecha_retorno" name="fecha_retorno" @change="verdiferencia()" data-vv-name="fecha_retorno">
                        <span class="text-danger"> {{ errors.first('fecha_retorno')}} </span>
                      </div>
                    </div>
                  </div><hr>
                  <div class="form-row">

                    <div class="form-group col-md-4">
                      <label><b>UNIDAD</b></label>
                    </div>
                    <div class="form-group col-md-2">
                      <label><b>PLACAS</b></label>
                    </div>

                    <div class="form-group col-md-4">
                      <label><b>OPERADOR</b></label>
                    </div>
                    <div class="form-group col-md-2">
                      <label><b>ACCIONES</b></label>
                    </div>
                  </div>
                  <li v-for="(vi, index) in vehiculos_itinerario_viaticos" class="list-group-item">
                    <div class="form-row">

                      <div class="form-group col-md-4">
                        <label>{{vi.unidad}}</label>
                      </div>
                      <div class="form-group col-md-2">
                        <label>{{vi.km_inicial}}</label>
                      </div>
                      <div class="form-group col-md-4">
                        <label>{{vi.empleado_operador_name}}</label>
                      </div>
                      <div class="form-group col-md-2">
                        <a @click="deleteu(index)">
                          <span class="fas fa-trash" arial-hidden="true"></span>
                        </a>
                      </div>
                    </div>
                  </li>

                  <div class='form-row'>
                    <div class='form-group col-md-4'>
                      <input type='text'  v-model="vehiculos_temporal.unidad" class='form-control' name="unidad  " data-vv-name="unidad" placeholder='Unidad'>
                    </div>
                    <div class='form-group col-md-2'>
                      <input type='text' v-model="vehiculos_temporal.km_inicial" class='form-control' placeholder='Placas'>
                    </div>
                    <div class='form-group col-md-4'>
                      <v-select v-model='vehiculos_temporal.empleado_operador_id' :options='vs_options' label='name' name='personal_servicio_viaticos_id' data-vv-as='personal_servicio_viaticos_id'></v-select>
                    </div>
                    <div class="form-group col-md-2">
                      <button type="button" class="btn btn-secondary" @click="crear()"><i class="fas fa-plus"></i>&nbsp;Crear</button>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          <!-- </div> -->
          <!-- <hr> -->
          <!-- <div class="accordion" id="accordion_tres"> -->
            <div class="card" v-show="tipo > 0">
              <div class="card-header" id="headingFour">
                <h5 class="mb-0" >
                  <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                    PERSONAL  DESTINADO AL SERVICIO
                  </button>
                </h5>
              </div>
              <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                <div class="card-body">
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label>Total de personas a asistir</label>
                    </div>
                    <div class="form-group col-md-6">
                      <input class="form-control" type="number" v-model="solicitud.total_personas" name="total_personas" data-vv-name="total_personas" readonly>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label>Supervisor</label>
                    </div>
                    <div class="form-group col-md-6">
                      <v-select v-model="solicitud.empleado_supervisor_id" :options="vs_options" label="name" name="personal_servicio_viaticos_id" data-vv-as="personal_servicio_viaticos_id" @input="sumarTotalPersona"></v-select>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label>Nombre del personal que asiste al servicio</label>
                    </div>
                    <div class="form-group col-md-6">
                      <v-select multiple v-model="solicitud.personal_servicio_viaticos_id" :options="vs_options" label="name" name="personal_servicio_viaticos_id" data-vv-as="personal_servicio_viaticos_id" @input="sumarTotalPersonas"></v-select>
                    </div>
                  </div>

                </div>
              </div>
            </div>

          </div>
        <!-- </template> -->
        <!-- <hr> -->
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Elaboró</label>
            <v-select v-validate="'required'" v-model="solicitud.empleado_elabora_id"  :options="vs_options" label="name" name="elabora" data-vv-as="elabora"></v-select><!---->
            <span class="text-danger">{{ errors.first('elabora') }}</span>
          </div>
          <div class="form-group col-md-6">
            <label>Revisó</label>
            <v-select v-model="solicitud.empleado_revisa_id"  :options="vs_options" label="name" name="revisa" data-vv-as="revisa"></v-select><!--v-validate="'required'"-->
            <span class="text-danger">{{ errors.first('revisa') }}</span>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Autorizó</label>
            <v-select v-model="solicitud.empleado_autoriza_id"   :options="vs_options" label="name" name="autoriza" data-vv-as="autoriza"></v-select>
            <span class="text-danger">{{ errors.first('autoriza') }}</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <vue-element-loading :active="isLoading"/>
        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
        <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
        <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
      </div>
    </div>
  </div>
</div>
<!-- Fin de modal  -->
  <div v-show="timbre_modal == 1">
    <timbre ref="timbre" @atras="cerrarTimbre()"></timbre>
  </div>
</div>
</main>

</template>
<script>
const Beneficiarios = r => require.ensure([], () => r(require('./Beneficiarios.vue')), 'via');
const DetallesV = r => require.ensure([], () => r(require('./DetallesV.vue')), 'via');
const Reporte = r => require.ensure([], () => r(require('../Reporte/Reporte.vue')), 'via');
const Detalle = r => require.ensure([], () => r(require('../Viaticos/Detalle.vue')), 'via');
const DetalleDos = r => require.ensure([], () => r(require('../Viaticos/DetalleDos.vue')), 'via');
const ReporteRevision = r => require.ensure([], () => r(require('../Viaticos/ReporteRevision.vue')), 'via');
const Timbre = r => require.ensure([], () => r(require('./Timbre.vue')), 'via');


import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return {
      PermisosCrud : {},
      timbre_modal : 0,
      tipo : '',
      empresa : '1',
      ver : 0,
      tabs : 1,
      isLoading : false,
      tituloModal : '',
      modal : 0,
      tipoAccion : 0,
      data_detalle : '',
      solicitud : {
        id : '',
        fecha : '',
        fecha_pago : '',
        proyecto_id : '',
        personal_servicio_viaticos_id : '',
        empleado_elabora_id : '',
        empleado_revisa_id : '',
        empleado_autoriza_id : '',
        empleado_supervisor_id : '',
        beneficiario_uno : [],
        detalles_listado : [],
        detalles_totales : [],
        detalles_conceptos : [],
        origen_destino : '',
        origen_destino_destino : '',
        fecha_salida : '',
        hora_estimada_salida : '',
        fecha_operacion : '',
        fecha_retorno : '',
        total_personas : '',
      },
      vehiculos_itinerario_viaticos : [],
      vehiculos_temporal : {
        unidad : '',
        km_inicial : '',
        empleado_operador_id : '',
        empleado_operador_name : '',
      },
      optionsvs_proyecto : [],
      vs_options : [],
      columns : [
        'solicitud.id',
        'solicitud.folio',
        'solicitud.tipo',
        'solicitud.fecha_solicitud',
        'solicitud.fecha_pago',
        'detalle.transferencia',
        'detalle.efectivo',
        'detalle.total',
        'descarga',
        'timbrado','beneficiario',
        'solicitud.nombre_elabora',
        'solicitud.estado',

      ],
        tableData : [],
        options: {
          headings: {
            'solicitud.id' : 'Acciones',
            'solicitud.folio' : 'Folio',
            'solicitud.fecha_solicitud' : 'Fecha solicitada',
            'solicitud.fecha_pago' : 'Fecha requerida de pago',
            'detalle.transferencia' : 'Transferencia',
            'detalle.efectivo' : 'Efectivo',
            'detalle.total' : 'Total',
            'solicitud.nombre_revisa' : 'Revisa',
            'solicitud.nombre_autoriza' : 'Autoriza',
            'solicitud.estado' : 'Estado',
            'solicitud.tipo' : 'Tipo',
            'solicitud.nombre_elabora':'Elabora'
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['solicitud.folio','solicitud.tipo','solicitud.fecha_solicitud','solicitud.fecha_pago','detalle.transferencia','detalle.efectivo','detalle.total','solicitud.nombre_revisa','solicitud.nombre_autoriza','solicitud.nombre_elabora'],
          filterable: ['solicitud.folio','solicitud.tipo','solicitud.fecha_solicitud','solicitud.fecha_pago','detalle.transferencia','detalle.efectivo','detalle.total','solicitud.nombre_revisa','solicitud.nombre_autoriza','solicitud.nombre_elabora'],
          filterByColumn: true,
          listColumns: {
            'solicitud.tipo': [{
              id: 1,
              text: 'REEMBOLSO'
            },
            {
              id: 2,
              text: 'VIATICOS'
            },
            {
              id: 0,
              text: 'SINDICATO'
            },
          ],

        },
          texts:config.texts
        },
      }
    },
    components : {
      'beneficiario' : Beneficiarios,
      'detallesv' : DetallesV,
      'reporte' : Reporte,
      'detalle' : Detalle,
      'detalledos' : DetalleDos,
      'reporterevision' : ReporteRevision,
      'timbre' : Timbre,
      // 'detalle' : Detalle,
    },
    methods : {
      getData() {
        this.ver = 1;
        this.PermisosCrud = Utilerias.getCRUD(this.$route.path);

      },

      sumarTotalPersona(){
        this.solicitud.total_personas = 1;
      },

      sumarTotalPersonas(){
        if(this.solicitud.personal_servicio_viaticos_id.length == 1 ){
          this.solicitud.total_personas = '';
          this.solicitud.total_personas = 1 + this.solicitud.personal_servicio_viaticos_id.length;
        }
        else {
          this.solicitud.total_personas = '';
          this.solicitud.total_personas = 1 + this.solicitud.personal_servicio_viaticos_id.length;
        }
      },

      diaActual(){
        var hoy = new Date ();
        var dd = hoy.getDate();
        var mm = hoy.getMonth()+1; //hoy es 0!
        var yyyy = hoy.getFullYear();

        if(dd<10) { dd='0'+dd }
        if(mm<10) { mm='0'+mm }

        hoy = yyyy+'-'+mm+'-'+dd;
        this.solicitud.fecha = hoy;
        this.solicitud.fecha_salida = hoy;
      },

      verdiferencia()
      {
        if (new Date(this.solicitud.fecha).getTime() > new Date(this.solicitud.fecha_retorno)) {
          toastr.warning('La fecha de salida no puede ser menor a la fecha salida');
        }
      },

      componenteBeneficiario(){
        var childBeneficiario = this.$refs.beneficiario;
        childBeneficiario.getDatos(this.empresa,this.tipo,this.vs_options);

        var ChilDetallesv = this.$refs.detallesv;
        ChilDetallesv.getDatos(this.tipo);
      },

      cargarComponentes(){
        // console.log(this.tipo);

      },

      abrirModal(modelo, accion, data = []){
        if (this.empresa === '') {
          toastr.warning('Seleccione una empresa');
        }else {
          switch(modelo){
            case "solicitud":
            {
              switch(accion){
                case 'registrar':
                {
                  // this.cargarComponentes();
                  Utilerias.resetObject(this.solicitud);
                  this.modal= 1;
                  this.diaActual();
                  this.tituloModal = 'Registrar solicitud de viaticos';
                  this.tipoAccion=1;
                  break;
                }
                case 'actualizar' :
                {
                  console.log(data,'data');
                  Utilerias.resetObject(this.solicitud);
                  // this.cargarComponentes();
                  this.tituloModal = 'Actualizar solicitud de viaticos';
                  this.modal = 1;
                  this.tipoAccion = 2;
                  this.tipo = data['solicitud']['tipo'];
                  this.solicitud.fecha = data['solicitud']['fecha_solicitud'];
                  this.solicitud.fecha_pago = data['solicitud']['fecha_pago'];
                  this.solicitud.proyecto_id = {id: data['solicitud']['proyecto_id'], name : data['solicitud']['nombre_proyecto']};

                  var childBeneficiarioSend = this.$refs.beneficiario;
                  childBeneficiarioSend.setDatos(data['beneficiarios'],data['solicitud']['tipo'],this.empresa);

                  var ChilDetallesvSend = this.$refs.detallesv;
                  ChilDetallesvSend.setDatos(data['detalles_listado'],data['solicitud']['tipo']);

                  this.solicitud.origen_destino = data['solicitud']['origen_destino'];
                  this.solicitud.origen_destino_destino = data['solicitud']['origen_destino_destino'];
                  this.solicitud.fecha_salida = data['solicitud']['fecha_salida'];
                  this.solicitud.hora_estimada_salida = data['solicitud']['hora_estimada_salida'];
                  this.solicitud.fecha_operacion = data['solicitud']['fecha_operacion'];
                  this.solicitud.fecha_retorno = data['solicitud']['fecha_retorno'];
                  this.vehiculos_itinerario_viaticos= [];

                  for (var i = 0; i < data['vehiculo'].length; i++) {
                    let me = this;
                    me.vehiculos_itinerario_viaticos.push({
                      unidad : data['vehiculo'][i]['unidad'],
                      km_inicial : data['vehiculo'][i]['km_inicial'],
                      empleado_operador_id : data['vehiculo'][i]['empleado_operador_id'],
                      empleado_operador_name : data['vehiculo'][i]['nombre_operador'],
                    });
                  }

                  this.solicitud.total_personas = data['solicitud']['total_personas'];
                  this.solicitud.empleado_supervisor_id = {id: data['solicitud']['empleado_supervisor_id'],name : data['solicitud']['nombre_supervisor']};
                  this.solicitud.empleado_elabora_id = {id: data['solicitud']['empleado_elabora_id'],name : data['solicitud']['nombre_elabora']};
                  this.solicitud.empleado_revisa_id = {id: data['solicitud']['empleado_revisa_id'],name : data['solicitud']['nombre_revisa']};
                  this.solicitud.empleado_autoriza_id = {id: data['solicitud']['empleado_autoriza_id'],name : data['solicitud']['nombre_autoriza']};
                  this.solicitud.id = data['solicitud']['id'];
                  var datos = [];
                  for (var i = 0; i < data['empleados'].length; i++) {
                    datos.push({
                      id : data['empleados'][i]['empleado_id'],
                      name : data['empleados'][i]['nombre_empleado']
                    });
                  }
                  this.solicitud.personal_servicio_viaticos_id = datos;
                  break;
                }
              }
            }
          }
        }
      },

      cerrarModal(){
        this.modal = 0;
        // this.tipo = '';
        var childBeneficiario = this.$refs.beneficiario;
        childBeneficiario.quitar_uno();
        Utilerias.resetObject(this.solicitud);
      },

      enviouno(data){
        // console.log(data,'datos enviasdos desde el cpm');
        this.solicitud.beneficiario_uno = data;
      },


      envio_u(data){
        this.solicitud.detalles_listado = data;
      },

      envio_d(data){
        this.solicitud.detalles_totales = data;
      },

      envio_c(data){
        this.solicitud.detalles_conceptos = data;
      },

      crear(){
        if (this.vehiculos_temporal.unidad == '' ||
        this.vehiculos_temporal.km_inicial == '' ||
        (this.vehiculos_temporal.empleado_operador_id  == '' || this.vehiculos_temporal.empleado_operador_id  == null)) {
          toastr.warning('Atención','Ingrese todos los campos de los vehiculos a utilizar');
        }else {

          let me = this;
          me.vehiculos_itinerario_viaticos.push({
            unidad : me.vehiculos_temporal.unidad,
            km_inicial : this.vehiculos_temporal.km_inicial,
            empleado_operador_id : this.vehiculos_temporal.empleado_operador_id.id,
            empleado_operador_name : this.vehiculos_temporal.empleado_operador_id.name,
          });

          me.vehiculos_temporal.unidad = '';
          me.vehiculos_temporal.km_inicial = '';
          me.vehiculos_temporal.empleado_operador_id = '';
          me.vehiculos_temporal.empleado_operador_name = '';
        }

      },

      deleteu(index){
        this.vehiculos_itinerario_viaticos.splice(index, 1);
      },

      guardar(nuevo)
      {
        this.$validator.validate().then(result => {
          if (result) {
            let me = this;
            // this.isLoading = true;
            axios({
              method : nuevo ? 'POST' : 'PUT',
              url : nuevo ? '/solicitudviaticos' : '/solicitudviaticos/' + this.solicitud.id,
              data : {
                'id' : this.solicitud.id,
                'fecha_solicitud' : this.solicitud.fecha,
                'fecha_pago' : this.solicitud.fecha_pago,
                'proyecto_id' : this.solicitud.proyecto_id.id,
                'personal_servicio_viaticos_id' : this.solicitud.personal_servicio_viaticos_id == '' ? [] : this.solicitud.personal_servicio_viaticos_id,
                'empleado_elabora_id' : this.solicitud.empleado_elabora_id.id,
                'empleado_revisa_id' : this.solicitud.empleado_revisa_id.id,
                'empleado_autoriza_id' : this.solicitud.empleado_autoriza_id.id,
                'empleado_supervisor_id' : this.solicitud.empleado_supervisor_id == '' ? ''  : this.solicitud.empleado_supervisor_id.id,
                'beneficiario_uno' : this.solicitud.beneficiario_uno,
                'detalles_listado' : this.solicitud.detalles_listado,
                'detalles_totales' : this.solicitud.detalles_totales,
                'detalles_conceptos' : this.solicitud.detalles_conceptos,
                'origen_destino' : this.solicitud.origen_destino,
                'origen_destino_destino' : this.solicitud.origen_destino_destino,
                'fecha_salida' : this.solicitud.fecha_salida,
                'hora_estimada_salida' : this.solicitud.hora_estimada_salida,
                'fecha_operacion' : this.solicitud.fecha_operacion,
                'fecha_retorno' : this.solicitud.fecha_retorno,
                'total_personas' : this.solicitud.total_personas,
                'vehiculos_itinerario_viaticos' : this.vehiculos_itinerario_viaticos,
                'empresa' : this.empresa,
                'tipo' : this.tipo,

              }
            }).then(response => {
              // console.log(response);
              // this.isLoading = false;
              let me = this;
              // console.log(me.empresa,'tpoo');
              if (me.empresa === '1' || me.empresa == 1) {
                me.buscarCFW();
              }else if (me.empresa === '2' || me.empresa == 2) {
                me.buscarCSCT();
              }
              me.cerrarModal();
              me.getData();
              toastr.success(nuevo ? 'Solicitud creada exitosamente' : 'Solicitud actualizada exitosamente','Correcto');

            }).catch(error => {
              console.error(error);
            });

          }
          else {
            toastr.warning('Complete los campos requeridos')
          }
        });
      },

      limpiarBeneficiarioUno(){
        this.solicitud.beneficiario_uno = null;
      },

      limpiarBeneficiarioDos(){
        this.solicitud.beneficiario_dos= null;
      },



      maestro(){
        this.ver = 1;
      },

      enviaRevision(id, edo){
        this.isLoading = true;
        swal({
          title: 'Esta seguro(a) de enviar?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#4dbd74',
          cancelButtonColor: '#f86c6b',
          confirmButtonText: 'Aceptar!',
          cancelButtonText: 'Cancelar',
          reverseButtons: true
        }).then(result =>{
          if (result.value) {
            axios.post('/estadosviaticos',{
              'id' : id,
              'edo' : edo,
              'empresa' : this.empresa,
            }).then(response => {
              this.isLoading = false;
              if (response.data.status) {
                if (edo == 2) {
                  toastr.success('Solicitud de viaticos enviada a revisión','Correcto');
                }
                this.getData();
                if (this.empresa == 1) {
                  this.buscarCFW();
                }else if (this.empresa == 2) {
                  this.buscarCSCT();
                }
              }else {
                toastr.warning(response.data.respuesta,'Atención');
              }

            }).catch(error => {
              console.log(error);
            });
          }else {
            this.isLoading = false;
          }
        });
      },


      verfechas(){
        this.tabs = 2;
      },

      revisareporte(){
        this.tabs = 3;
      },

      verpagos(){
        this.tabs = 4;
      },

      enviareporte(id, edo)
      {
        axios.get('/reportegastosviaticos/'+ id).then(response => {
          if (respoFdata.beneficiario.forEachnse.data.length == 0) {
            toastr.warning('Atención','No se puede enviar reporte de sin registros');
          }
          else {
            axios.post('/estadosviaticos',{
              'id' : id,
              'edo' : edo,
            }).then(response => {
              if (edo == 6) {
                toastr.success('Solicitud de viaticos enviada a revisión','Correcto');
              }
              this.getData();
            }).catch(error => {
              console.log(error);
            });
          }
        }).catch(error => {
          console.log(error);
        });

      },


      decargarForVia(data, empresa){
        window.open('/descargarformatoviatico/' + data + '&' + empresa, '_blank');

      },
      descargarnForFij(data, empresa){
        window.open('/descargarnformatofij/' + data + '&' + empresa, '_blank');

      },

      fechaspagos(){
        this.tabs = 2;
      },

      reporte(){
        this.tabs = 1;
      },

      verdetalle(data){
        this.data_detalle = data;
        this.ver = 4;
        var childReporte = this.$refs.reporte;
        childReporte.setDatos(data,this.empresa);

        var childDetalle = this.$refs.detalle;
        childDetalle.getData(data,0,this.empresa);

        var childDetalleDos = this.$refs.detalledos;
        childDetalleDos.getData(data,0,this.empresa);

        var chilReporteRevision = this.$refs.reporterevision;
        chilReporteRevision.getData(data,this.empresa);
        // var childDetalle = this.$refs.detalle;
        // childDetalle.getData(data, 1);
      },

      buscarCFW(){
        this.tableData = [];
        this.vs_options = [];
        this.optionsvs = [];
        this.optionsvs_proyecto = [];
        axios.get('solicitud/viaticos/conserflow').then(response => {
          this.tableData = response.data;
        }).catch(error => {
          console.error(error);
        });

        axios.get('proyectos/viaticos/' + this.empresa).then(response => {
          for (var i = 0; i < response.data.length; i++) {
            this.optionsvs_proyecto.push({
              id: response.data[i].id,
              name : response.data[i].nombre_corto,
            });
          }
        }).catch(error => {
          console.error(error);
        });

        axios.get('/empleados/viaticos/' + this.empresa).then(response => {
          for (var i = 0; i < response.data.length; i++) {
            this.vs_options.push({
              id : response.data[i].id,
              name : response.data[i].nombre + ' ' + response.data[i].ap_paterno + ' ' + response.data[i].ap_materno,
            });
          }
        }).catch(error => {
          console.error(error);
        });
      },

      buscarCSCT(){
        this.vs_options = [];
        this.tableData = [];
        this.optionsvs = [];
        this.optionsvs_proyecto = [];
        axios.get('/solicitud/viaticos/csct').then(response => {
          this.tableData = response.data;
        }).catch(error => {
          console.error(error);
        });

        axios.get('proyectos/viaticos/' + this.empresa).then(response => {
          for (var i = 0; i < response.data.length; i++) {
            this.optionsvs_proyecto.push({
              id: response.data[i].id,
              name : response.data[i].nombre_corto,
            });
          }
        }).catch(error => {
          console.error(error);
        });

        axios.get('/empleados/viaticos/' + this.empresa).then(response => {
          for (var i = 0; i < response.data.length; i++) {
            this.vs_options.push({
              id : response.data[i].id,
              name : response.data[i].nombre + ' ' + response.data[i].ap_paterno + ' ' + response.data[i].ap_materno,
            });
          }
        }).catch(error => {
          console.error(error);
        });
      },

      ReporteGral(){
        window.open('descargar/reporte/general/viaticos/' + this.empresa, '_blank' );
      },

      ReporteGralPdf(){
        window.open('descargar/reporte/general/viaticos/pdf/' + this.empresa, '_blank' );
      },

      timbrar(id, tipo){
        this.timbre_modal = 1;
        if (this.timbre_modal == 1) {
        var childTimbre = this.$refs.timbre;
        childTimbre.setDatos(id, tipo ,this.empresa);
        }
      },

      cerrarTimbre(){
        this.timbre_modal = 0;
      },

      eliminar(id, empresa){
        axios.get('eliminar/solicitud/viaticos/' + id + '&' + empresa).then(response => {
          toastr.success('Eliminado Correctamente');
          if (this.empresa == 1) {
            this.buscarCFW();
          }else if (this.empresa == 2) {
            this.buscarCSCT();
          }
        }).catch(e => {
          console.error(e);
        });
      },

    },

    mounted(){
      this.getData();
      this.buscarCFW();
    }
  }
  </script>
  <style >

  .dropbtn2 {
    background-color: #000000;
    color: rgb(255, 255, 255);
    padding: 15px;
    font-size: 15px;
    border: none;
  }

  .dropdown2 {
    position: relative;
    display: inline-block;
  }

  .dropdown-content2 {
    display: none;
    position: sticky;
    background-color: #4361f3;
    min-width: 50px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
  }

  .dropdown-content2 a {
    color: black;
    padding: 15px 20px;
    text-decoration: none;
    display: block;
  }

  .button2{
    background-color: #4361f3;
    border: none;
    color: white;
    padding: auto;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 15px;


  }
  /* .dropdown-content2 button2:hover {background-color: rgb(206, 36, 36);} */

  .dropdown2:hover .dropdown-content2 {display: block;}

  .dropdown2:hover .dropbtn2 {background-color: #000000;}
  </style>
