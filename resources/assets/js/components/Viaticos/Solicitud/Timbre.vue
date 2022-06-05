<template>
  <div>

    <!-- Modal -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : modal}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
      <div class='modal-dialog modal-dark modal-lg' role='document'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'> {{estado_vista == 1 ? 'Anticipo' : estado_vista == 2 ? 'Anticipo-Comprobación' : estado_vista == 3 ? 'Comprobación' : ''}}</h4>
            <button type='button' class='close' @click='CerrarModal()' aria-label='Close'>
              <span aria-hidden='true'>×</span>
            </button>
          </div>
          <div class='modal-body'>
            <!-- {{data_viaticos['solicitud']['estado_timbre'] }} -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-book"></i>&nbsp;Comprobante</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-tools"></i>&nbsp;Concepto</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"><i class="fas fa-tools"></i>&nbsp;Emisor</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="recept-tab" data-toggle="tab" href="#recept" role="tab" aria-controls="recept" aria-selected="false"><i class="fas fa-tools"></i>&nbsp;Receptor</a>
              </li>

              <template v-if="estado_vista == 2 || estado_vista == 3">
                <li class="nav-item">
                  <a class="nav-link" id="percepcion-tab" data-toggle="tab" href="#percepcion" role="tab" aria-controls="percepcion" aria-selected="false"><i class="fas fa-book"></i>&nbsp;Percepciones</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="deduccion-tab" data-toggle="tab" href="#deduccion" role="tab" aria-controls="deduccion" aria-selected="false"><i class="fas fa-book"></i>&nbsp;Deducciones</a>
                </li>
              </template>
              <li class="nav-item">
                <a class="nav-link" id="other-tab" data-toggle="tab" href="#other" role="tab" aria-controls="other" aria-selected="false"><i class="fas fa-book"></i>&nbsp;Otros Pagos</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label>Lugar Expedicion</label>
                    <input type="text" class="form-control" v-model="comprobante.lugar_expedicion">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Metodo Pagos</label>
                    <select class="form-control" v-model="comprobante.metodopago">
                      <option v-for="item in MetodoPago" :value="item.id">{{item.c_metodopago}} {{item.descripcion}}</option>
                    </select>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Tipo Comprobante</label>
                    <input type="text" class="form-control" v-model="comprobante.tipo_comprobante">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Forma de pago</label>
                    <select class="form-control" v-model="comprobante.formapago">
                      <option v-for="item in FormaPago" :value="item.id">{{item.clave}}  {{item.descripcion}}</option>
                    </select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-2 mb-3">
                    <label>Total</label>
                    <input type="text" class="form-control" v-validate="'decimal:2'" data-vv-name="Total" v-model="comprobante.total">
                      <span class="text-danger">{{errors.first('Total')}}</span>
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>SubTotal</label>
                    <input type="text" class="form-control" v-validate="'decimal:2'" data-vv-name="SubTotal" v-model="comprobante.subtotal">
                      <span class="text-danger">{{errors.first('SubTotal')}}</span>
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Descuento</label>
                    <input type="text" class="form-control" v-validate="'decimal:2'" data-vv-name="Descuento" v-model="comprobante.descuento">
                      <span class="text-danger">{{errors.first('Descuento')}}</span>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Moneda</label>
                    <select class="form-control" v-model="comprobante.moneda">
                      <option value="MXN">MXM</option>
                      <option value="USD">USD</option>
                      <option value="EUR">EUR</option>
                    </select>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Tipo Cambio</label>
                    <input type="text" class="form-control" v-model="comprobante.tipo_cambio">
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-4 mb-3">
                    <label>Fecha</label>
                    <input type="datetime-local" class="form-control" v-model="comprobante.fecha">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Folio</label>
                    <input type="text" class="form-control" v-model="comprobante.folio">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Serie</label>
                    <input type="text" class="form-control" v-model="comprobante.serie">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label>Fecha Pago</label>
                    <input type="date" class="form-control" v-model="nomina.fecha_pago" @input="calculoAntiguedad()">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Fecha Inicial Pago</label>
                    <input type="date" class="form-control" v-model="nomina.fecha_pago_i" @input="calculoDias()">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Fecha Final Pago</label>
                    <input type="date" class="form-control" v-model="nomina.fecha_pago_f" @input="calculoDias()">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Dias</label>
                    <input type="text" class="form-control" v-model="nomina.dias">
                  </div>
                </div>

              </div>

              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                <div class="form-row">
                  <div class="col-md-6 mb-3">
                    <label>Clave SAT</label>
                    <!-- <input type="text" class="form-control"> -->
                    <v-select :options="listaCatalogo" v-model="concepto.clave_sat" label="descripcion" @search="buscar">
                    </v-select>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Cantidad</label>
                    <input type="text" class="form-control" v-model="concepto.cantidad">
                  </div>
                  <div class="col-md-3">
                    <label>Clave Unidad</label>
                    <input type="text" class="form-control" v-model="concepto.clave_unidad">
                  </div>
                </div>

                <div class="form-row">
                  <div class="col-md-12 mb-3">
                    <label>Descripción</label>
                    <input type="text" class="form-control" v-model="concepto.descripcion">
                  </div>
                </div>

                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label>Valor Unitario</label>
                    <input type="text" class="form-control" v-model="concepto.valor_unitario">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Importe</label>
                    <input type="text" class="form-control" v-model="concepto.importe">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Descuento</label>
                    <input type="text" class="form-control" v-model="concepto.descuento">
                  </div>
                </div>

              </div>

              <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label>RFC</label>
                    <input type="text" class="form-control" v-model="emisor.rfc">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label>Nombre</label>
                    <input type="text" class="form-control" v-model="emisor.nombre">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Regimen Fiscal</label>
                    <input type="text" class="form-control" v-model="emisor.regimenfiscal">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Registro Patronal</label>
                    <input type="text" class="form-control" v-model="emisor.registro_patronal">
                  </div>
                </div>

              </div>
              <div class="tab-pane fade" id="recept" role="tabpanel" aria-labelledby="recept-tab">

                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label>RFC</label>
                    <input type="text" class="form-control" v-model="receptor.rfc">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Nombre</label>
                    <input type="text" class="form-control" v-model="receptor.nombre">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Uso CFDI</label>
                    <input type="text" class="form-control" v-model="receptor.usocfdi">
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label>SDI</label>
                    <input type="text" class="form-control" v-model="receptor.sdi">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>SBC</label>
                    <input type="text" class="form-control" v-model="receptor.sbc">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Periodicidad Pago</label>
                    <select class="form-control" v-model="receptor.periodicidadpago">
                      <option value="01">Diario</option>
                      <option value="02">Semanal</option>
                      <option value="03">Catorcenal</option>
                      <option value="04">Quincenal</option>
                      <option value="05">Mensual</option>
                      <option value="06">Bimestral</option>
                      <option value="07">Unidad obra</option>
                      <option value="08">Comisión</option>
                      <option value="09">Precio alzado</option>
                      <option value="10">Decenal</option>
                      <option value="99">Otra Periodicidad</option>
                    </select>
                    <!-- <input type="text" class="form-control" > -->
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Riesgo Puesto</label>
                    <select class="form-control" v-model="receptor.riesgopuesto">
                      <option value="1">1 Clase I</option>
                      <option value="2">2 Clase II</option>
                      <option value="3">3 Clase III</option>
                      <option value="4">4 Clase IV</option>
                      <option value="5">5 Clase V</option>
                      <option value="99">99 No Aplica</option>
                    </select>
                    <!-- <input type="text" class="form-control" > -->
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label>Puesto</label>
                    <input type="text" class="form-control" v-model="receptor.puesto">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Clave Ent. Fed.</label>
                    <input type="text" class="form-control" v-model="receptor.cef">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Departamento</label>
                    <input type="text" class="form-control" v-model="receptor.departamento">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label>Tipo Regimen</label>
                    <select class="form-control" v-model="receptor.tiporegimen">
                      <option value="02">Sueldos (Incluye ingresos señalados en la fracción I del artículo 94 de LISR)</option>
                      <option value="03">Jubilados</option>
                      <option value="04">Pensionados</option>
                      <option value="05">Asimilados Miembros Sociedades Cooperativas Produccion</option>
                      <option value="06">Asimilados Integrantes Sociedades Asociaciones Civiles</option>
                      <option value="07">Asimilados Miembros consejos</option>
                      <option value="08">Asimilados comisionistas</option>
                      <option value="09">Asimilados Honorarios</option>
                      <option value="10">Asimilados acciones</option>
                      <option value="11">Asimilados otros</option>
                      <option value="12">Jubilados o Pensionados</option>
                      <option value="13">Indemnización o Separación</option>
                      <option value="99">Otro Regimen</option>
                    </select>
                    <!-- <input type="text" > -->
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label>Tipo Jornada</label>
                    <!-- <input type="text" > -->
                    <select class="form-control" v-model="receptor.tipojornada">
                      <option value="01">Diurna</option>
                      <option value="02">Nocturna</option>
                      <option value="03">Mixta</option>
                      <option value="04">Por hora</option>
                      <option value="05">Reducida</option>
                      <option value="06">Continuada</option>
                      <option value="07">Partida</option>
                      <option value="08">Por turnos</option>
                      <option value="99">Otra Jornada</option>
                    </select>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Sindicalizado</label>
                    <input type="text" class="form-control" v-model="receptor.sindicalizado">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Tipo Contrato</label>
                    <input type="text" class="form-control" v-model="receptor.tipocontrato">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Antigüedad</label>
                    <input type="text" class="form-control" v-model="receptor.antiguedad">
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label>Fecha Inicio Rel. Laboral</label>
                    <input type="date" class="form-control" v-model="receptor.fechainiciorellaboral">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Num. Seguridad Social</label>
                    <input type="text" class="form-control" v-model="receptor.nss">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Num. Empleado</label>
                    <input type="text" class="form-control" v-model="receptor.numempleado">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Curp</label>
                    <input type="text" class="form-control" v-model="receptor.curp">
                  </div>
                </div>


              </div>

              <div class="tab-pane fade" id="percepcion" role="tabpanel" aria-labelledby="percepcion-tab">

                <div class="form-row">
                  <div class="col-md-4 mb-3">
                    <label>Clave</label>
                    <v-select :options="DPercepcion" v-model="percepcion.tipo" label="descripcion" @input="completarCamposP()">
                    </v-select>
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Clave</label>
                    <input type="text" class="form-control" v-model="percepcion.clave">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Concepto</label>
                    <input type="text" class="form-control" v-model="percepcion.concepto">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Importe Excento</label>
                    <input type="text" class="form-control" v-model="percepcion.importeexento">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Importe Gravado</label>
                    <input type="text" class="form-control" v-model="percepcion.importegravado">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>&nbsp;</label><br>
                    <button @click="guardarasignacionpercepcion()" class="btn btn-outline-dark" name="button">Crear</button>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label><b>Catalogo</b></label>
                  </div>
                  <div class="form-group col-md-2">
                    <label><b>Clave</b></label>
                  </div>
                  <div class="form-group col-md-2">
                    <label><b>Importe Excento</b></label>
                  </div>
                  <div class="form-group col-md-2">
                    <label><b>Importe Gravado</b></label>
                  </div>
                  <div class="form-group col-md-2">
                    <label><b>ACCIONES</b></label>
                  </div>
                </div>
                <li v-for="(vi, index) in listado_data_percepcion" class="list-group-item">
                  <div class="form-row">

                    <div class="form-group col-md-4">
                      <label>{{vi.concepto}}</label>
                    </div>
                    <div class="form-group col-md-2">
                      <label>{{vi.clave}}</label>
                    </div>
                    <div class="form-group col-md-2">
                      <label>{{vi.importeexento}}</label>
                    </div>
                    <div class="form-group col-md-2">
                      <label>{{vi.importegravado}}</label>
                    </div>
                    <div class="form-group col-md-2">
                      <a @click="deleteup(index)">
                        <span class="fas fa-trash" arial-hidden="true"></span>
                      </a>
                    </div>
                  </div>
                </li>

              </div>

              <div class="tab-pane fade" id="deduccion" role="tabpanel" aria-labelledby="deduccion-tab">

                <div class="form-row">
                  <div class="col-md-6 mb-3">
                    <label>Tipo</label>
                    <v-select :options="DDeduccion" v-model="deduccion.tipo" label="descripcion" @input="completarCamposD()">
                    </v-select>
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Clave</label>
                    <input type="text" class="form-control" v-model="deduccion.clave">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Concepto</label>
                    <input type="text" class="form-control" v-model="deduccion.concepto">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Importe</label>
                    <input type="text" class="form-control" v-model="deduccion.importe">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>&nbsp;</label><br>
                    <button @click="guardarasignaciondeduccion()" class="btn btn-outline-dark" name="button">Crear</button>
                  </div>

                </div>
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label><b>Catalogo</b></label>
                  </div>
                  <div class="form-group col-md-2">
                    <label><b>Clave</b></label>
                  </div>
                  <div class="form-group col-md-2">
                    <label><b>Importe</b></label>
                  </div>

                  <div class="form-group col-md-2">
                    <label><b>ACCIONES</b></label>
                  </div>
                </div>
                <!-- {{listado_data_deducciones}} -->
                <li v-for="(vi, index) in listado_data_deducciones" class="list-group-item">
                  <div class="form-row">

                    <div class="form-group col-md-4">
                      <label>{{vi.concepto}}</label>
                    </div>
                    <div class="form-group col-md-2">
                      <label>{{vi.clave}}</label>
                    </div>
                    <div class="form-group col-md-2">
                      <label>{{vi.importe}}</label>
                    </div>

                    <div class="form-group col-md-2">
                      <a @click="deleteud(index)">
                        <span class="fas fa-trash" arial-hidden="true"></span>
                      </a>
                    </div>
                  </div>
                </li>

              </div>

              <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="other-tab">
                <div class="form-row">
                  <div class="col-md-8 mb-3">
                    <label>Catalogo</label>
                    <v-select v-model="otros.catalogo" :options="OtroPagos" label="name"></v-select>
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Importe</label>
                    <input type="text" class="form-control" v-model="otros.importe" v-validate="'decimal:2'" data-vv-name="Importe">
                    <span class="text-danger">{{errors.first('Importe')}}</span>
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>&nbsp;</label><br>
                    <button @click="guardarasignacion()" class="btn btn-outline-dark" name="button">Crear</button>
                  </div>

                </div>
                <div class="form-row">
                  <div class="form-group col-md-8">
                    <label><b>Catalogo</b></label>
                  </div>
                  <div class="form-group col-md-2">
                    <label><b>Importe</b></label>
                  </div>
                  <div class="form-group col-md-2">
                    <label><b>ACCIONES</b></label>
                  </div>
                </div>
                <li v-for="(vi, index) in listado_data" class="list-group-item">
                  <div class="form-row">

                    <div class="form-group col-md-8">
                      <label>{{vi.name}}</label>
                    </div>
                    <div class="form-group col-md-2">
                      <label>{{vi.importe}}</label>
                    </div>
                    <div class="form-group col-md-1">
                      <a @click="deleteu(index)">
                        <span class="fas fa-trash" arial-hidden="true"></span>
                      </a>
                    </div>
                  </div>
                </li>
              </div>
            </div>


          </div>
          <div class='modal-footer'>

            <!-- {{data_viaticos}} -->
            <!-- {{partidas_timbres}} -->
            <template v-for="pt in  partidas_timbres" >
              <div class="dropdown">
                <button class="btn btn-outline-dark dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  {{pt.estado_vista == 1 ? 'Anticipo' : pt.estado_vista == 2 ? 'Anticipo/Comprobación' : pt.estado_vista == 3 ? 'Comprobación' : ''}}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <template v-if="pt.timbrado_pd == 0">
                  <button class="dropdown-item" type="button" v-show="comprobante.total != ''" @click="Guardar()">Guardar</button>
                  <button class="dropdown-item" type="button" v-show="comprobante.total == ''" @click="Actualizar(pt,response.data[0]['beneficiario'][0])">Actualizar</button>
                  <button class="dropdown-item" type="button" @click="TPrueba(pt['id'])">Timbrar</button>
                  <button type='button' class='dropdown-item' @click="DescargarPre(pt['id'])">&nbsp;Pre-Comprobante</button>
                </template>
                  <template v-if="pt.timbrado_pd == 1">
                    <button type='button' class='dropdown-item'
                    @click="Descargar(pt['id'])"><i class='fas fa-file-pdf'></i>&nbsp;Comprobante</button>
                    <button type='button' class='dropdown-item'
                    @click="descargarxml(pt['nombre_archivo'])"><i class='fas fa-file-code'></i>&nbsp;XML</button>
                  </template>
                </div>
              </div>
            </template>

            <div class="dropdown">
              <button class="btn btn-outline-dark dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Tipo
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <button class="dropdown-item" type="button" @click="estado(1)">Anticipo</button>
                <button class="dropdown-item" type="button" @click="estado(2)">Anticipo/Comprobacion</button>
                <button class="dropdown-item" type="button" @click="estado(3)">Comprobacion</button>
              </div>
            </div>
            <button type='button' class='btn btn-outline-dark' @click='CerrarModal()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>

            <button type='button' class='btn btn-outline-dark'
            v-show="estado_vista != 0"
            @click='Guardar()'><i class='fas fa-save'></i>&nbsp;Guardar</button>

            <!-- <button type='button' class='btn btn-outline-dark'
            v-show="data_viaticos != '' && data_viaticos['solicitud']['timbrado'] == 0"
            @click='DescargarPre()'><i class='fas fa-download'></i>&nbsp;Pre-Comprobante</button> -->

            <!-- <button type='button' class='btn btn-outline-dark' @click='TPrueba()'><i class='fas fa-bell'></i>&nbsp;Timbrar Prueba</button> -->

            <!-- <button type='button' class='btn btn-outline-dark'
            v-show="data_viaticos != '' && data_viaticos['solicitud']['timbrado'] == 0 && estado_vista != 0"
            @click='TPrueba()'><i class='fas fa-bell'></i>&nbsp;Timbrar</button> -->


            <!-- <button type='button' class='btn btn-outline-dark'
            v-show="data_viaticos != '' && data_viaticos['solicitud']['timbrado'] == 1"
            @click="descargarxml(data_viaticos['timbre']['nombre_archivo'])"><i class='fas fa-file-code'></i>&nbsp;XML</button>

            <button type='button' class='btn btn-outline-dark'
            v-show="data_viaticos != '' && data_viaticos['solicitud']['timbrado'] == 1"
            @click='Descargar()'><i class='fas fa-file-pdf'></i>&nbsp;Comprobante</button> -->



          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- Modal -->



  </div>
</template>
<script>

export default{
  data(){
    return {
      modal : 0,
      tituloModal : 'Solicitud',
      estado_vista : 0,

      MetodoPago: '',
      FormaPago : '',
      OtroPagos : [],
      listaCatalogo : [],
      listado_otros_pagos : [],
      listado_data : [],
      listado_data_percepcion : [],
      listado_data_deducciones : [],
      DDeduccion : [],
      DPercepcion : [],

      data_viaticos : '',
      partidas_timbres :  '',
      response : '',

      comprobante : {
        lugar_expedicion : '',
        metodopago : '1',
        tipo_comprobante : 'N',
        total : '',
        descuento : '',
        moneda : 'MXN',
        tipo_cambio : '1',
        subtotal : '',
        formapago : '23',
        fecha : '',
        folio : '',
        serie : 'VIATICOS'
      },
      concepto :{
        clave_sat : '',
        cantidad : '1',
        clave_unidad : '',
        descripcion : '',
        valor_unitario : '',
        importe : '',
        descuento : '',
      },
      emisor : {
        rfc : '',
        nombre : '',
        regimenfiscal : '',
        registro_patronal : '',
      },
      receptor : {
        antiguedad : '',
        fechainiciorellaboral : '',
        departamento : '',
        puesto : '',
        curp : '',
        nss : '',
        sindicalizado : '',
        cef : '',
        usocfdi : '',
        nombre : '',
        rfc : '',
        riesgopuesto : '',
        periodicidadpago : '99',
        tiporegimen : '02',
        tipojornada : '01',
        sdi : '',
        sbc : '',
        tipocontrato : '',
        numempleado : '',
      },
      nomina : {
        dias : '',
        fecha_pago : '',
        fecha_pago_i : '',
        fecha_pago_f : '',
      },
      otros : {
        catalogo : '',
        importe : '',
      },

      percepcion :{
        tipo : '',
        clave : '',
        concepto : '',
        importegravado : '',
        importeexento : '',
      },

      deduccion : {
        tipo : '',
        concepto : '',
        clave : '',
        importe : '',
      },

      solicitud_id : '',
      tipo : '',
      empresa : '',
    }
  },
  methods:{
    completarCamposP(){
      this.percepcion.clave = this.percepcion.tipo.c_tipopercepcion;
      this.percepcion.concepto = this.percepcion.tipo.desc;
    },

    completarCamposD(){
      this.deduccion.clave = this.deduccion.tipo.c_tipodeduccion;
      this.deduccion.concepto = this.deduccion.tipo.desc;
    },

    estado(edo){
      if (this.partidas_timbres == '') {
        if (this.response.data[0]['comprobacion']['total'] == null && edo == 2) {
          toastr.warning('No cuenta con comprobaciones!!!');
        }else if (this.response.data[0]['comprobacion']['total'] == null && edo == 3) {
          toastr.warning('No cuenta con comprobaciones!!!');
        }else {
          if (edo == 1) {
            if (this.partidas_timbres.find(info => info.estado_vista == edo)) {
              toastr.warning('Ya cuenta con Anticipo');
            }else {
              this.llenadoInicial(this.response, this.empresa);
              this.estado_vista = edo;
            }
          }else if (edo == 2) {
            if (this.partidas_timbres.find(info => info.estado_vista == edo)) {
              toastr.warning('Ya cuenta con Anticipo/Comprobación');
            }else {
              if (this.response.data[0]['comprobacion']['total'] == '') {
                toastr.warning('No cuenta con comprobaciones!!!');
              }else {
                this.llenadoAntCom(this.response, this.empresa);
                this.estado_vista = edo;
              }
            }
          }else if (edo == 3) {
            if (this.partidas_timbres.find(info => info.estado_vista == edo)) {
              toastr.warning('Ya cuenta con Comprobación');
            }else {
              if (this.response.data[0]['comprobacion']['total'] == '') {
                toastr.warning('No cuenta con comprobaciones!!!');
              }else {
                this.llenadoCom(this.response, this.empresa);
                this.estado_vista = edo;
              }
            }
          }
        }
        // console.log(this.response.data[0]['comprobacion']['total']);
      }else {
        if (edo == 1) {
          if (this.partidas_timbres.find(info => info.estado_vista == edo)) {
            toastr.warning('Ya cuenta con Anticipo');
          }else {
            this.llenadoInicial(this.response, this.empresa);
            this.estado_vista = edo;
          }
        }else if (edo == 2) {
          if (this.partidas_timbres.find(info => info.estado_vista == edo)) {
            toastr.warning('Ya cuenta con Anticipo/Comprobación');
          }else {
            if (this.response.data[0]['comprobacion']['total'] == '') {
              toastr.warning('No cuenta con comprobaciones!!!');
            }else {
              this.llenadoAntCom(this.response, this.empresa);
              this.estado_vista = edo;
            }
          }
        }else if (edo == 3) {
          if (this.partidas_timbres.find(info => info.estado_vista == edo)) {
            toastr.warning('Ya cuenta con Comprobación');
          }else {
            if (this.response.data[0]['comprobacion']['total'] == '') {
              toastr.warning('No cuenta con comprobaciones!!!');
            }else {
              this.llenadoCom(this.response, this.empresa);
              this.estado_vista = edo;
            }
          }
        }
      }
    },

    llenadoAntCom(response, empresa){

      this.vaciar();
      console.log(response,'sdfg');
      console.log(response.data[0]['comprobacion']['total']);
      let date = new Date();
      let day = ("0" + date.getDate()).slice(-2);
      let month = ("0" + (date.getMonth() + 1)).slice(-2);
      let year = date.getFullYear();

      if (empresa == 'CON1912026U2') {
        this.emisor.rfc = 'CON1912026U2';
        this.emisor.nombre = 'CONSERFLOW SA DE CV';
        this.emisor.regimenfiscal = '601';
        this.emisor.registro_patronal = 'W8910499107';
      }else if (empresa == 'CSC050609LF7') {
        this.emisor.rfc = 'CSC050609LF7';
        this.emisor.nombre = 'CONSTRUCTORA Y SERVICIOS CALDERON-TORRES SA DE CV';
        this.emisor.regimenfiscal = '601';
        this.emisor.registro_patronal = 'PENDIENTE';
      }

      this.comprobante.subtotal = response.data[0]['detalle']['transferencia'];
      this.comprobante.descuento = response.data[0]['comprobacion']['total'];
      this.comprobante.total = parseFloat(this.comprobante.subtotal) - parseFloat(this.comprobante.descuento);
      this.comprobante.folio = date.getFullYear();
      this.comprobante.fecha = this.Getdate();

      this.concepto.clave_sat = {id : '2742',descripcion : '84111505 Servicios de contabilidad de sueldos y salarios'};
      this.concepto.clave_unidad = 'ACT';
      this.concepto.descripcion = 'Pago de nómina';
      this.concepto.valor_unitario = parseFloat(response.data[0]['detalle']['transferencia']) + parseFloat(response.data[0]['detalle']['transferencia']);
      this.concepto.importe  = parseFloat(response.data[0]['detalle']['transferencia']) + parseFloat(response.data[0]['detalle']['transferencia']);
      this.concepto.descuento  = response.data[0]['comprobacion']['total'];

      this.receptor.rfc = response.data[0]['empleado']['rfc'];
      this.receptor.nombre = response.data[0]['beneficiario'][0]['nombre_beneficiario'];
      this.receptor.usocfdi = 'P01';
      this.receptor.cef = '';
      this.receptor.sindicalizado = 'No';
      this.receptor.nss = response.data[0]['empleado']['nss_imss'];
      this.receptor.curp = response.data[0]['empleado']['curp'];
      console.log(response.data[0]['datos_empleado']['fecha_reingreso']);
      this.receptor.fechainiciorellaboral = response.data[0]['datos_empleado']['fecha_reingreso'];
      this.receptor.departamento = 'NINGUNO';
      this.receptor.puesto = 'NINGUNO';
      this.receptor.sdi = response.data[0]['datos_empleado']['sdi'];
      this.receptor.sbc= response.data[0]['datos_empleado']['sbc'];
      this.receptor.tipocontrato = response.data[0]['datos_empleado']['tipo_contrato'];
      this.receptor.numempleado = response.data[0]['datos_empleado']['num_empleado'];

      this.nomina.fecha_pago = year + '-' + month + '-' + day;
      this.nomina.fecha_pago_i = year + '-' + month + '-' + day;
      this.nomina.fecha_pago_f = year + '-' + month + '-' + day;

      this.calculoDias();
      this.calculoAntiguedad();
      //
      this.listado_data = [{ "id": 13, "name": "003 Viáticos (entregados al trabajador).", "importe": response.data[0]['detalle']['transferencia']},
      { "id": 13, "name": "002 Subsidio para el empleo (efectivamente entregado al trabajador).", "importe": "0" }];

      this.listado_data_deducciones = [  {
          id : 81,
          clave : '081',
          concepto : 'Ajuste en Viáticos (entregados al trabajador)',
          importe : response.data[0]['comprobacion']['total'],
        }];

      this.listado_data_percepcion = [
        {
          id : 41,
          concepto : 'Viáticos',
          clave : '050',
          importeexento :  response.data[0]['detalle']['transferencia'],
          importegravado : '0.00',
        }
      ];

    },

    llenadoCom(response , empresa){

      this.vaciar();
      // console.log(response,'sdfg');
      let date = new Date();
      let day = ("0" + date.getDate()).slice(-2);
      let month = ("0" + (date.getMonth() + 1)).slice(-2);
      let year = date.getFullYear();

      if (empresa == 1) {
        this.emisor.rfc = 'CON1912026U2';
        this.emisor.nombre = 'CONSERFLOW SA DE CV';
        this.emisor.regimenfiscal = '601';
        this.emisor.registro_patronal = 'W8910499107';
      }else if (empresa == 2) {
        this.emisor.rfc = 'CSC050609LF7';
        this.emisor.nombre = 'CONSTRUCTORA Y SERVICIOS CALDERON-TORRES SA DE CV';
        this.emisor.regimenfiscal = '601';
        this.emisor.registro_patronal = 'PENDIENTE';
      }

      this.comprobante.subtotal = parseFloat(response.data[0]['detalle']['transferencia']) + 0.01;
      this.comprobante.descuento = parseFloat(response.data[0]['detalle']['transferencia']);
      this.comprobante.total = '0.01';
      this.comprobante.folio = date.getFullYear();
      this.comprobante.fecha = this.Getdate();

      this.concepto.clave_sat = {id : '2742',descripcion : '84111505 Servicios de contabilidad de sueldos y salarios'};
      this.concepto.clave_unidad = 'ACT';
      this.concepto.descripcion = 'Pago de nómina';
      this.concepto.valor_unitario = parseFloat(response.data[0]['detalle']['transferencia']) + 0.01;
      this.concepto.importe  = parseFloat(response.data[0]['detalle']['transferencia']) + 0.01;

      this.receptor.rfc = response.data[0]['empleado']['rfc'];
      this.receptor.nombre = response.data[0]['beneficiario'][0]['nombre_beneficiario'];
      this.receptor.usocfdi = 'P01';
      this.receptor.cef = '';
      this.receptor.sindicalizado = 'No';
      this.receptor.nss = response.data[0]['empleado']['nss_imss'];
      this.receptor.curp = response.data[0]['empleado']['curp'];
      this.receptor.fechainiciorellaboral = response.data[0]['empleado']['fech_alta_imss'];
      this.receptor.departamento = 'NINGUNO';
      this.receptor.puesto = 'NINGUNO';

      this.nomina.fecha_pago = year + '-' + month + '-' + day;
      this.nomina.fecha_pago_i = year + '-' + month + '-' + day;
      this.nomina.fecha_pago_f = year + '-' + month + '-' + day;

      this.calculoDias();
      this.calculoAntiguedad();
      //
      this.listado_data = [
       { "id": 12, "name": "002 Subsidio para el empleo (efectivamente entregado al trabajador).", "importe": "0" }
     ];

      this.listado_data_deducciones = [
        {
          id : 81,
          clave : '081',
          concepto : 'Ajuste de viáticos entregados al trabajador',
          importe : response.data[0]['comprobacion']['total'],
        },
        {
          id : 81,
          clave : '082',
          concepto : 'Ajuste de viáticos devueltos por el  trabajador',
          importe : (response.data[0]['detalle']['transferencia'] - response.data[0]['comprobacion']['total']),
        }
      ];

      this.listado_data_percepcion = [
        {
          id : 41,
          concepto : 'Viáticos comprobados',
          clave : '050',
          importeexento : response.data[0]['comprobacion']['total'],
          importegravado : '0.00',
        },
        {
          id : 41,
          concepto : 'Viáticos devolucione',
          clave : '051',
          importeexento : parseFloat(response.data[0]['detalle']['transferencia'] - response.data[0]['comprobacion']['total']) + 0.01,
          importegravado : '0.00',
        }
      ];

    },

    setDatos(id, tipo, empresa){
      this.estado_vista = 0;
      this.vaciar();
      this.solicitud_id = id;
      this.tipo = tipo;
      this.empresa = empresa;
      axios.get('get/data/timbre/viaticos/' + id + '&' + empresa).then(response =>{

        // console.log(response.data,'response');
        this.data_viaticos = response.data[0];
        this.response = response;
        // if (response.data[0]['timbre'] == null) {
        //   this.llenadoInicial(response, empresa);
        // }
        // else {
        //   this.llenado(response.data[0]['timbre'], response.data[0]['beneficiario'][0], response.data[0]['partidas']);
        // }
      }).catch(e => {
        console.error(e);
      });
      this.partidas_timbres = '';
      axios.get('get/timbres/viaticos/save/'+ id + '&' + empresa).then(response => {
        this.partidas_timbres = response.data;
      }).catch(e =>{
        console.error(e);
      });

      this.modal = 1;
      this.tabs = 1;
    },

    CerrarModal(){
      // this.estado_vista = 0;
      this.modal = 0;
      this.$emit('atras');
    },

    getData(){
      axios.get('/satcatmetodopago?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {
        this.MetodoPago = response.data.data;
      }).catch(error => {console.error(error);});

      axios.get('/satcatformpago?query=%7B%7D&limit=10&ascending=1&page=1&byColumn=1').then(response => {
        this.FormaPago = response.data;
      }).catch(error => {console.error(error);});
      axios.get('/satcatdeduccion').then(response => {
        this.DDeduccion = response.data;
      }).catch(error => {console.error(error);});
      axios.get('/satcatpercepcion').then(response => {
        this.DPercepcion = response.data;
      }).catch(error => {console.error(error);});
      this.OtroPagos = [];
      axios.get('get/sat/cat/otropago').then(response => {
        response.data.forEach((item, i) => {
          this.OtroPagos.push({id : item.id, name : item.c_tipootropago + ' ' + item.descripcion});
        });

      }).catch(error => {console.error(error);});
    },

    Getdate(){
      var d = new Date();

      return d.getFullYear() + '-' +((d.getMonth()+1) < 10 ? '0'+ (d.getMonth()+1) : (d.getMonth() + 1))
      + '-' + (d.getDate() < 10 ? ('0' + d.getDate()): d.getDate())  + 'T'
      + (d.getHours() < 10 ? ('0'+ d.getHours()) : d.getHours()) + ':'
      + (d.getMinutes() < 10 ? '0' + (d.getMinutes()) : d.getMinutes());

      // return year + "-" + month + "-" + date + "T" + hours + ":" + minutes + ":" + seconds;
    },

    buscar (search, loading) {
      if (search.length > 3) {

        let me = this;

        setTimeout(function(){
          axios.post('get/sat/clave/',{
            des : search,
          }).then(response => {
            me.listaCatalogo = response.data;
          }).catch(e =>{
            console.error(e);
          });
        }, 1000);


      }
    },

    calculoDias(){
      var fecha1 = moment(this.nomina.fecha_pago_i);
      var fecha2 = moment(this.nomina.fecha_pago_f);
      let me  = this;
      var c = (fecha2.diff(fecha1, 'days') + 1);
      me.nomina.dias = c;

    },

    calculoAntiguedad(){
      var fecha1 = moment(this.receptor.fechainiciorellaboral);
      var fecha2 = moment(this.nomina.fecha_pago);
      var c = (fecha2.diff(fecha1, 'days') + 1);
      var a = Math.trunc(c / 7);

      this.receptor.antiguedad = 'P' + a + 'W';
    },

    guardarasignacion(){
      if (this.otros.catalogo != '') {
        this.listado_data.push(
          {
            id : this.otros.catalogo.id,
            name : this.otros.catalogo.name,
            importe : this.otros.importe,
          },
        );
        this.otros.catalogo = '';
        this.otros.importe = '';
      }
    },

    guardarasignacionpercepcion(){
      if (this.percepcion.tipo != '') {
        this.listado_data_percepcion.push(
          {
            id : this.percepcion.tipo.id,
            concepto : this.percepcion.tipo.desc,
            clave : this.percepcion.clave,
            importeexento : this.percepcion.importeexento,
            importegravado : this.percepcion.importegravado,
          },
        );
        this.percepcion = '';
      }
    },

    guardarasignaciondeduccion(){
      if (this.deduccion.tipo != '') {
        this.listado_data_deducciones.push(
          {
            id : this.deduccion.tipo.id,
            clave : this.deduccion.clave,
            concepto : this.deduccion.tipo.desc,
            importe : this.deduccion.importe,
          },
        );
        this.deduccion = '';
      }
    },

    deleteu(index){
      this.listado_data.splice(index, 1);
    },

    deleteup(index){
      this.listado_data_percepcion.splice(index, 1);
    },

    deleteud(index){
      this.listado_data_deducciones.splice(index, 1);
    },

    Guardar(){
      console.log(this.listado_data_percepcion.length);
      if (this.estado_vista != 1 && this.listado_data_percepcion.length  == 0 ) {
        toastr.warning('Revise el registro de percepciones');
      }
      else if (this.estado_vista != 1 && this.listado_data_deducciones.length == 0 ) {
        toastr.warning('Revise el registro de deducciones');
      }else {

        axios.post('guardar/datos/timbre/',{
          solicitud_id : this.solicitud_id,
          tipo : this.tipo,
          empresa : this.empresa,
          comprobante : this.comprobante,
          concepto : this.concepto,
          emisor : this.emisor,
          receptor : this.receptor,
          otros : this.listado_data,
          nomina : this.nomina,
          percepcion : this.listado_data_percepcion,
          deduccion : this.listado_data_deducciones,
          estado_vista : this.estado_vista,
        }).then(response => {
          toastr.success('Correctamente!!');
          this.setDatos(this.solicitud_id, this.tipo, this.empresa);
          this.estado_vista = 0;
        }).catch(e => {
          console.error(e);
        });
      }
    },

    llenadoInicial(response, empresa){
      this.vaciar();
      console.log(response,'inicial');
      let date = new Date();
      let day = ("0" + date.getDate()).slice(-2);
      let month = ("0" + (date.getMonth() + 1)).slice(-2);
      let year = date.getFullYear();

      if (response.data[0]['datos_empleado']['empresa'] === 'conserflow') {
        this.emisor.rfc = 'CON1912026U2';
        this.emisor.nombre = 'CONSERFLOW SA DE CV';
        this.emisor.regimenfiscal = '601';
        this.emisor.registro_patronal = 'W8910499107';
        this.comprobante.lugar_expedicion = '75820';
        this.receptor.cef = 'PUE';

      }else if (response.data[0]['datos_empleado']['empresa'] === 'csct') {
        this.emisor.rfc = 'CSC050609LF7';
        this.emisor.nombre = 'CONSTRUCTORA Y SERVICIOS CALDERON-TORRES SA DE CV';
        this.emisor.regimenfiscal = '601';
        this.emisor.registro_patronal = 'PENDIENTE';
        this.comprobante.lugar_expedicion = '96410';
        this.receptor.cef = 'VER';

      }

      this.comprobante.total = response.data[0]['detalle']['transferencia'];
      this.comprobante.subtotal = response.data[0]['detalle']['transferencia'];
      this.comprobante.folio = date.getFullYear();
      this.comprobante.fecha = this.Getdate();

      this.concepto.clave_sat = {id : '2742',descripcion : '84111505 Servicios de contabilidad de sueldos y salarios'};
      this.concepto.clave_unidad = 'ACT';
      this.concepto.descripcion = 'Pago de nómina';
      this.concepto.valor_unitario = response.data[0]['detalle']['transferencia'];
      this.concepto.importe  = response.data[0]['detalle']['transferencia'];

      this.receptor.rfc = response.data[0]['empleado']['rfc'];
      this.receptor.nombre = response.data[0]['beneficiario'][0]['nombre_beneficiario'];
      this.receptor.usocfdi = 'P01';
      this.receptor.sdi = response.data[0]['datos_empleado']['sdi'];
      this.receptor.sbc= response.data[0]['datos_empleado']['sbc'];
      this.receptor.tipocontrato = response.data[0]['datos_empleado']['tipo_contrato'];
      this.receptor.numempleado = response.data[0]['datos_empleado']['num_empleado'];
      this.receptor.sindicalizado = 'No';
      this.receptor.nss = response.data[0]['empleado']['nss_imss'];
      this.receptor.curp = response.data[0]['empleado']['curp'];
      this.receptor.fechainiciorellaboral =response.data[0]['datos_empleado']['fecha_reingreso'];
      this.receptor.departamento = 'NINGUNO';
      this.receptor.puesto = 'NINGUNO';
      // this.receptor.riesgopuesto = '';
      this.nomina.fecha_pago = year + '-' + month + '-' + day;
      this.nomina.fecha_pago_i = year + '-' + month + '-' + day;
      this.nomina.fecha_pago_f = year + '-' + month + '-' + day;

      this.calculoDias();
      this.calculoAntiguedad();

      this.listado_data = [{ "id": 13, "name": "003 Viáticos (entregados al trabajador).", "importe": response.data[0]['detalle']['transferencia']},
       { "id": 12, "name": "002 Subsidio para el empleo (efectivamente entregado al trabajador).", "importe": "0" }];
    },

    Actualizar(general, beneficiario){
      this.vaciar();
      // console.log(general);
      this.estado_vista = general['estado_vista'];
      this.comprobante.lugar_expedicion = general['lugar_expedicion'];
      this.comprobante.metodopago = general['metodopago'];
      this.comprobante.total = general['total'];
      this.comprobante.descuento = general['descuento'];
      this.comprobante.moneda = general['moneda'];
      this.comprobante.tipo_cambio = general['tipo_cambio'];
      this.comprobante.subtotal = general['subtotal'];
      this.comprobante.formapago = general['formapago'];
      this.comprobante.fecha = general['fecha'];
      this.comprobante.folio = general['folio'];
      this.comprobante.serie = general['serie'];

      this.nomina.dias = general['dias'];
      this.nomina.fecha_pago = general['fecha_pago'];
      this.nomina.fecha_pago_i = general['fecha_pago_i'];
      this.nomina.fecha_pago_f = general['fecha_pago_f'];

      this.concepto.clave_sat = {id : general['clave_sat'],descripcion : general['descripcion_sat']};
      this.concepto.cantidad = general['cantidad'];
      this.concepto.clave_unidad = general['clave_unidad'];
      this.concepto.descripcion = general['descripcion'];
      this.concepto.valor_unitario = general['valor_unitario'];
      this.concepto.importe = general['importe'];
      this.concepto.descuento = general['descuento'];

      this.comprobante.descuento = general['descuento'];


      this.receptor.antiguedad = general['antiguedad'];
      this.receptor.fechainiciorellaboral = general['fechainiciorellaboral'];
      this.receptor.departamento = general['departamento'];
      this.receptor.puesto = general['puesto'];
      this.receptor.curp = general['curp'];
      this.receptor.nss = general['nss'];
      this.receptor.sindicalizado = general['sindicalizado'];
      this.receptor.cef = general['cef'];
      this.receptor.usocfdi = general['usocfdi'];
      this.receptor.nombre = beneficiario['nombre_beneficiario'];
      this.receptor.rfc = general['rfc_r'];
      this.receptor.riesgopuesto = general['riesgopuesto'];
      this.receptor.periodicidadpago = general['periodicidadpago'];
      this.receptor.tiporegimen = general['tiporegimen'];
      this.receptor.tipojornada = general['tipojornada'];
      this.receptor.sdi = general['sdi'];
      this.receptor.sbc = general['sbc'];
      this.receptor.tipocontrato = general['tipocontrato'];
      this.receptor.numempleado = general['numempleado'];

      // this.percepcion.clave = {id : 41 , descripcion : '050 Viáticos'};
      // this.percepcion.importegravado = '0.00';
      // this.percepcion.importeexento = response.data[0]['comprobacion']['total'];

      // this.deduccion.clave = {id : 81, descripcion : 'Ajuste de viáticos entregados al trabajador'};
      // this.deduccion.importe = response.data[0]['comprobacion']['total'];

      if (general['emisor_rfc'] == 'CON1912026U2') {
        this.emisor.rfc = 'CON1912026U2';
        this.emisor.nombre = 'CONSERFLOW SA DE CV';
        this.emisor.regimenfiscal = '601';
        this.emisor.registro_patronal = 'W8910499107';
      }else if (general['emisor_rfc'] == 'CSC050609LF7') {
        this.emisor.rfc = 'CSC050609LF7';
        this.emisor.nombre = 'CONSTRUCTORA Y SERVICIOS CALDERON-TORRES SA DE CV';
        this.emisor.regimenfiscal = '601';
        this.emisor.registro_patronal = 'P3437438101';
      }

      this.listado_data = [ ];
      axios.get('get/otros/pagos/update/' + general['id']).then(response => {
        response.data.forEach((item, i) => {
          this.listado_data.push({id : item.sat_cat_tipo_otro_pago_id, name : item.desct, importe : item.importe});
        });
      }).catch(e => {
        console.error(e);
      });

      this.listado_data_deducciones = [];
      axios.get('get/deducciones/update/' + general['id']).then(res => {
        res.data.forEach((item, i) => {
          this.listado_data_deducciones.push(
            {
              id : item.sat_cat_tipodeduccion_id,
              clave : item.clave,
              concepto : item.concepto,
              importe : item.importe,
            }
          );
        });
      }).catch(e => {
        console.error(e);
      });

      this.listado_data_percepcion = [];
      axios.get('get/percepciones/update/' + general['id']).then(response => {
        response.data.forEach((item, i) => {
          this.listado_data_percepcion.push({
            id : item.sat_cat_tipopercepcion_id,
            concepto : item.concepto,
            clave : item.clave,
            importeexento : item.importeexento,
            importegravado : item.importegravado,
          });
        });
      }).catch(e => {
        console.error(e);
      });

      // this.listado_data = [{ "id": 3, "name": "003 Viáticos (entregados al trabajador).", "importe": response.data[0]['detalle']['transferencia']}, { "id": 2, "name": "002 Subsidio para el empleo (efectivamente entregado al trabajador).", "importe": "0" }];


    },

    vaciar(){

      this.comprobante.lugar_expedicion = '';
      this.comprobante.metodopago = '1';
      this.comprobante.tipo_comprobante = 'N';
      this.comprobante.total = '';
      this.comprobante.descuento = '';
      this.comprobante.moneda = 'MXN';
      this.comprobante.tipo_cambio = '1';
      this.comprobante.subtotal = '';
      this.comprobante.formapago = '23';
      this.comprobante.fecha = '';
      this.comprobante.folio = '';
      this.comprobante.serie = 'VIATICOS';

      this.concepto.clave_sat = '';
      this.concepto.cantidad = '1';
      this.concepto.clave_unidad = '';
      this.concepto.descripcion = '';
      this.concepto.valor_unitario = '';
      this.concepto.importe = '';
      this.concepto.descuento = '';

      this.receptor.antiguedad = '';
      this.receptor.fechainiciorellaboral = '';
      this.receptor.departamento = '';
      this.receptor.puesto = '';
      this.receptor.curp = '';
      this.receptor.nss = '';
      this.receptor.sindicalizado = '';
      this.receptor.cef = '';
      this.receptor.usocfdi = '';
      this.receptor.nombre = '';
      this.receptor.rfc = '';
      this.receptor.riesgopuesto = '';
      this.receptor.periodicidadpago = '99';
      this.receptor.tiporegimen = '02';
      this.receptor.tipojornada = '01';
      this.receptor.sdi = '';
      this.receptor.sbc = '';
      this.receptor.tipocontrato = '';
      this.receptor.numempleado = '';

      this.nomina.dias = '';
      this.nomina.fecha_pago = '';
      this.nomina.fecha_pago_i = '';
      this.nomina.fecha_pago_f = '';

      this.percepcion.clave = '';
      this.percepcion.importegravado = '';
      this.percepcion.importeexento = '';

      this.deduccion.clave = '';
      this.deduccion.importe = '';

      this.listado_data = [];
      this.listado_data_percepcion = [];
      this.listado_data_deducciones = [];

    },

    TPrueba(pt){

      Swal.fire({
        title: 'Esta seguro(a) de timbrar a prueba?',
        text: "Esta opción no se podrá desahacer!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText : 'No',
      }).then(result => {
        if (result.value) {
          axios.get('viaticos/timbrado/prueba/' + this.solicitud_id + '&' + this.empresa + '&' + pt).then(response => {
            if (response.data.estado == 1) {
              toastr.warning(response.data.respuesta);
            }else if (response.data.estado == 2) {
              toastr.success(response.data.respuesta);
              this.setDatos(this.solicitud_id, this.tipo, this.empresa)
            }
          }).catch(e => {
            console.error(e);
          });
        }
      })

    },

    DescargarPre(pt){
      window.open('/viaticos/timbrado/descargar/pre/' + this.solicitud_id + '&' + this.empresa + '&' + pt, '_blank');
    },

    Descargar(pt){
      window.open('/viaticos/timbrado/descargar/tim/' + this.solicitud_id + '&' + this.empresa + '&' + pt, '_blank');
    },

    descargarxml(data){
      let me=this;
      axios({
        url: '/descargar/factura/xml/' + data+'tim.xml&' + this.empresa ,
        method: 'GET',
        responseType: 'blob', // importante
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', data+'tim.xml'); //archivo = nombre del archivo alojado en el ftp
        document.body.appendChild(link);
        link.click();
        //--Llama el metodo para borrar el archivo local una ves descargado--//
        axios.delete( '/eliminar/factura/reporte/xml/' + data+'tim.xml')
        .then(response => {
        })
        .catch(function (error) {
          console.log(error);
        });
        //--fin del metodo borrar--//
      });
    },

  },
  mounted(){
    this.getData();
  },
}
</script>
