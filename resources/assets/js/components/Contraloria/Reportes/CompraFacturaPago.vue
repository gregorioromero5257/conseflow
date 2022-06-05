<template>
  <main class="main">
    <div class="container-fluid">
      <br>
      <div class="row">
        <div class="col-6">
          <div class="card">
            <div class="card-header">Proyectos</div>
            <div class="card-body">

              <div class="form-group row">
                <label class="col-md-2 form-control-label" for="tipo_partida">Proyecto</label>
                <div class="col-md-8">
                  <v-select :options="listaProyectos"  v-validate="'required'" v-model="proyecto_id" label="name" name="proyecto" data-vv-name="proyecto" ></v-select> <!---->
                </div>
                <div class="col-md-2">&nbsp;</div>
                <br>
                <label class="col-md-2 form-control-label" for="tipo_partida">Tipo de cambio</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" v-model="tipo_cambio_promedio" >
                </div>
                <div class="col-md-2"><button class="btn btn-primary" @click="aumentar()">Buscar</button></div>

              </div>

              <table class="table table-bordered table-sm">

                <tr>
                  <th width="20%" class="table-active">Total USD</th>
                  <td style="text-align:right">  &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(parseFloat(tableData['usd']).toFixed(2))}}</td>
                  <td></td>
                </tr>
                <tr>
                  <th width="20%" class="table-active">Tipo USD/MNX</th>
                  <td style="text-align:right">  &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_usd_mnx).toFixed(2))}}</td>
                  <td></td>
                </tr>
                <tr>
                  <th width="20%" class="table-active">Total MNX</th>
                  <td style="text-align:right">  &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(parseFloat(tableData['mnx']).toFixed(2))}}</td>
                  <td></td>
                </tr>
                <tr>
                  <th width="20%" class="table-active">Total OC's</th>
                  <td style="text-align:right">
                    <b>
                      &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((totales).toFixed(2))}}
                    </b>
                  <td></td>
                  </td>
                </tr>
                <tr>
                  <th width="20%" class="table-active">Total OC's sin IVA</th>
                  <td></td>
                  <td style="text-align:right">
                    <b>
                      &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((totales/1.16).toFixed(2))}}
                    </b>
                  </td>
                </tr>
                <tr>
                  <th width="20%" class="table-active">Total Material Almacen</th>
                  <td></td>
                  <td style="text-align:right">
                    <b>
                      &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_material_almacen).toFixed(2))}}
                    </b>
                  </td>
                </tr>
                <tr>
                  <th width="20%" class="table-active">Mano de obra operativos</th>
                  <td></td>
                  <td style="text-align:right">
                    <b>
                      &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_mano_obra * 1.66).toFixed(2)) }}
                    </b></td>
                  </tr>
                  <tr>
                    <th width="20%" class="table-active">Mano de obra administrativos</th>
                    <td></td>
                    <td style="text-align:right">
                      <b>
                        &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_admin * 1.66).toFixed(2))}}
                      </b></td>
                    </tr>
                    <tr>
                      <th width="20%" class="table-active">Mano de obra sin proyecto</th>
                      <td></td>
                      <td style="text-align:right">
                        <b>
                          &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_sin_pro * 1.66).toFixed(2))}}
                        </b></td>
                      </tr>
                      <tr>
                        <th width="20%" class="table-active">VIATICOS</th>
                        <td></td>
                        <td style="text-align:right">
                          <b>
                            &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(total_viaticos)}}
                          </b></td>
                        </tr>
                        <tr>
                          <th width="20%" class="table-active">CAJA CHICA</th>
                          <td></td>
                          <td style="text-align:right">
                            <b>
                              &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(total_caja_chica)}}
                            </b></td>
                          </tr>
                          <tr>
                            <th width="20%" class="table-active">COMBUSTIBLE</th>
                            <td></td>
                            <td style="text-align:right">
                              <b>
                                &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(total_combustible)}}
                              </b></td>
                            </tr>
                        <tr>
                          <th width="20%" class="table-active">Total </th>
                          <td></td>

                          <td style="text-align:right; background:#1B2D92;color:#FFFFFF;">
                            <b>
                              &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_general_parcial).toFixed(2))}}
                            </b>
                          </td>
                        </tr>
                        <tr>
                          <th width="20%" class="table-active">ADMINISTRACIÓN</th>
                          <td style="text-align:right">
                            &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(total_gastos_admin)}}
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <th width="20%" class="table-active">FINANCIAMIENTO</th>
                          <td style="text-align:right">
                            &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(total_gastos_finanzas)}}
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <th width="20%" class="table-active">SEGURIDAD</th>
                          <td style="text-align:right">
                            &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(total_gastos_seguridad)}}
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <th width="20%" class="table-active">VEHICULOS</th>
                          <td style="text-align:right">
                            &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(total_gastos_vehiculos)}}
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <th width="20%" class="table-active">VENTAS</th>
                          <td style="text-align:right">
                            $&nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(total_gastos_ventas)}}
                          </td>
                          <td></td>
                        </tr>
                        <tr>
                          <th width="20%" class="table-active">TOTAL GASTOS</th>
                          <td></td>
                          <td style="text-align:right">
                            <b>
                              &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(total_gastos)}}
                            </b></td>
                          </tr>
                          <tr>
                            <th width="20%" class="table-active">Total General</th>
                            <td></td>

                            <td style="text-align:right; background:#1B2D92;color:#FFFFFF;">
                              <b>
                                &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_general).toFixed(2))}}
                              </b>
                            </td>
                          </tr>
                        </table>
                        <div v-show="ver == 1">
                          {{inputd}}
                          <br>
                          {{totolss}}
                          <br>
                          {{resultado_final}}
                          <br>
                          {{resultado_final_parcial}}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="card">
                      <table class="table table-hover table-sm table-bordered table-responsive">
                        <thead class="table-light">
                          <tr>
                            <th scope="col" rowspan="3" style="text-align:center" width="100%">PROVEEDORES 10</th>
                          </tr>
                        </thead>
                        <tbody>
                          <!-- <div v-if="resultado_proveedores != ''">
                            <tr><td scope="col">1</td><td>{{resultado_proveedores == '' ? '' : resultado_proveedores.length < 1 ? '' : resultado_proveedores[0]['proveedor']}}</td><td>{{(resultado_proveedores == '' ? '' : resultado_proveedores.length < 1 ? '' : resultado_proveedores[0]['total_mnx'])}}</td></tr>
                            <tr><td scope="col">2</td><td>{{resultado_proveedores == '' ? '' : resultado_proveedores.length < 2 ? '' : resultado_proveedores[1]['proveedor']}}</td><td>{{(resultado_proveedores == '' ? '' : resultado_proveedores.length < 2 ? '' : resultado_proveedores[1]['total_mnx'])}}</td></tr>
                            <tr><td scope="col">3</td><td>{{resultado_proveedores == '' ? '' : resultado_proveedores.length < 3 ? '' : resultado_proveedores[2]['proveedor']}}</td><td>{{(resultado_proveedores == '' ? '' : resultado_proveedores.length < 3 ? '' : resultado_proveedores[2]['total_mnx'])}}</td></tr>
                            <tr><td scope="col">4</td><td>{{resultado_proveedores == '' ? '' : resultado_proveedores.length < 4 ? '' : resultado_proveedores[3]['proveedor']}}</td><td>{{(resultado_proveedores == '' ? '' : resultado_proveedores.length < 4 ? '' : resultado_proveedores[3]['total_mnx'])}}</td></tr>
                            <tr><td scope="col">5</td><td>{{resultado_proveedores == '' ? '' : resultado_proveedores.length < 5 ? '' : resultado_proveedores[4]['proveedor']}}</td><td>{{(resultado_proveedores == '' ? '' : resultado_proveedores.length < 5 ? '' : resultado_proveedores[4]['total_mnx'])}}</td></tr>
                            <tr><td scope="col">6</td><td>{{resultado_proveedores == '' ? '' : resultado_proveedores.length < 6 ? '' : resultado_proveedores[5]['proveedor']}}</td><td>{{(resultado_proveedores == '' ? '' : resultado_proveedores.length < 6 ? '' : resultado_proveedores[5]['total_mnx'])}}</td></tr>
                            <tr><td scope="col">7</td><td>{{resultado_proveedores == '' ? '' : resultado_proveedores.length < 7 ? '' : resultado_proveedores[6]['proveedor']}}</td><td>{{(resultado_proveedores == '' ? '' : resultado_proveedores.length < 7 ? '' : resultado_proveedores[6]['total_mnx'])}}</td></tr>
                            <tr><td scope="col">8</td><td>{{resultado_proveedores == '' ? '' : resultado_proveedores.length < 8 ? '' : resultado_proveedores[7]['proveedor']}}</td><td>{{(resultado_proveedores == '' ? '' : resultado_proveedores.length < 8 ? '' : resultado_proveedores[7]['total_mnx'])}}</td></tr>
                            <tr><td scope="col">9</td><td>{{resultado_proveedores == '' ? '' : resultado_proveedores.length < 9 ? '' : resultado_proveedores[8]['proveedor']}}</td><td>{{(resultado_proveedores == '' ? '' : resultado_proveedores.length < 9 ? '' : resultado_proveedores[8]['total_mnx'])}}</td></tr>
                            <tr><td scope="col">10</td><td>{{resultado_proveedores == '' ? '' : resultado_proveedores.length < 10 ? '' : resultado_proveedores[9]['proveedor']}}</td><td>{{(resultado_proveedores == '' ? '' : resultado_proveedores.length < 10 ? '' : resultado_proveedores[9]['total_mnx'])}}</td></tr>
                          </div> -->
                        </tbody>
                      </table>
                    </div>
                  </div>


                </div>
                <div class="row">
                  <div class="col-8">
                    <div class="card" >
                      <chart-total :chart-data="chartData6" :options="options6"></chart-total>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="card">
                      <table class="table table-hover table-sm table-bordered table-responsive">
                        <thead class="table-light">
                          <tr>
                            <th scope="col" colspan="3" style="text-align:center">Totales con IVA</th>

                          </tr>
                          <tr>
                            <th scope="col" style="text-align:center" width="20%">&nbsp;</th>
                            <th scope="col" style="text-align:center" width="40%">&nbsp;</th>
                            <th scope="col" style="text-align:center" width="40%">&nbsp;</th>
                          </tr>
                        </thead>
                        <tbody>

                          <tr v-for="(c, key) in listCI" >
                            <td> <button type="button" :style="{backgroundColor:backgroundColorCI[key]}" class="form-control">&nbsp;&nbsp;</button> </td>
                            <td> {{tutulos[key]}}</td>
                            <td> $ {{(parseFloat(c)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-8">
                    <div class="card" >
                      <chart-total :chart-data="chartData5" :options="options5"></chart-total>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="card">
                      <table class="table table-hover table-sm table-bordered table-responsive">
                        <thead class="table-light">
                          <tr>
                            <th scope="col" colspan="3" style="text-align:center">Totales sin IVA</th>

                          </tr>
                          <tr>
                            <th scope="col" style="text-align:center" width="20%">&nbsp;</th>
                            <th scope="col" style="text-align:center" width="40%">&nbsp;</th>
                            <th scope="col" style="text-align:center" width="40%">&nbsp;</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(s, ke) in listSI" >
                            <td> <button type="button" :style="{backgroundColor:backgroundColorCI[ke]}" class="form-control">&nbsp;&nbsp;</button> </td>
                            <td> {{tutulos[ke]}}</td>
                            <td> $ {{(parseFloat(s)).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="row">


                  <div class="col-12">


                    <nav>
                      <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button :class="classnavpro"  @click="navPro()" data-bs-toggle="tab"  role="tab" aria-selected="true">Proveedores</button>
                        <button :class="classnavpagos" @click="navPagos()" data-bs-toggle="tab"  role="tab" aria-selected="true">Material Almacen</button>
                        <button :class="classnavoc" @click="navOc()" data-bs-toggle="tab"  role="tab" aria-selected="true">OC</button>
                      </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                      <!-- Inicio primer TAB -->
                      <div  v-show="tipoNav == 1" :class="tabconpro"  role="tabpanel" >

                        <div class="card">
                          <vue-element-loading :active="isLoading"/>


                          <!-- Contiene acordio con el listado de proveedores -->
                          <div id="accordion" >
                            <div class="card mb-0" v-for="(t, key) in resultado_proveedores">
                              <div class="card-header" id="headingOne">
                                <table>
                                  <tr>
                                    <th style="text-align:center" width="2%">&nbsp;</th>
                                    <th scope="col" style="text-align:left" width="46%">&nbsp;</th>
                                    <th scope="col" style="text-align:right" width="18%">&nbsp;</th>
                                    <th scope="col" style="text-align:right" width="18%">&nbsp;</th>
                                    <!-- <th scope="col" style="text-align:right" width="15%">&nbsp;</th> -->
                                    <th scope="col" style="text-align:right" width="18%">&nbsp;</th>
                                  </tr>
                                  <tr>
                                    <td >
                                      <button class="btn btn-link" data-toggle="collapse" :data-target="'#' + t['proveedor_id']" aria-expanded="true" aria-controls="collapseOne">
                                        &nbsp;{{key + 1}}&nbsp;&nbsp;
                                      </button>
                                    </td>
                                    <!-- style="word-wrap: break-word" -->
                                    <td style="word-wrap: break-word">
                                      {{t['proveedor']}}
                                    </td>
                                    <td >{{(t['total_usd_parcial'] == '' ? '' : t['total_usd_parcial'] + ' USD')}} </td>
                                    <td >{{(t['total_mnx_parcial'] == '' ? '' : t['total_mnx_parcial'] + ' MNX')}} </td>
                                    <!-- <td >{{(t['total_mnx'])}} MNX C/IVA</td> -->
                                    <td >{{(t['total_mnx_s'])}} MNX </td>
                                  </tr>
                                </table>
                              </div>
                              <div :id="t['proveedor_id']" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                  <!-- Inicio de listado de ordenes de compras por proveedor -->

                                  <div id="accordion_uno" >
                                    <div class="card mb-0" v-for="(p, key_p) in t['paridas']">
                                      <div class="card-header" id="headingTwo">
                                        <table>
                                          <tr>
                                            <td style="text-align:center" width="3%">&nbsp;{{key + 1}}.{{key_p + 1}}&nbsp;&nbsp;</td>
                                            <td scope="col" style="text-align:left" width="52%">
                                              <button class="btn btn-link" data-toggle="collapse" :data-target="'#' + p['oc']['id']+'Uno'" aria-expanded="true" aria-controls="collapseTwo">
                                                {{p['oc']['folio']}}
                                              </button>
                                            </td>

                                            <td scope="col" style="text-align:right" width="15%">
                                              <template v-if="p['porcentaje'] != 0">
                                                <b>  PORCENTAJE DE ENTRADA:</b> <br>{{(p['porcentaje']).toFixed(2)}}%
                                              </template>
                                              <template v-if="p['porcentaje'] == 0 && p['calculo'] == null">
                                              <b> PERIODO ENTREGA:</b> <br> {{p['oc']['periodo_entrega']}}
                                              </template>
                                              <template v-if="p['porcentaje'] == 0 && p['calculo'] != null">
                                              <b> FECHA ENTREGA:</b> <br> {{p['calculo']}}
                                              </template>
                                            </td>
                                            <!-- <td scope="col" style="text-align:center" width="15%">${{(p['oc']['folio'])}}</td> -->
                                            <!-- <td scope="col" style="text-align:center" width="15%">${{(p['oc']['comentario_condicion_pago'])}}</td> -->
                                            <td scope="col" style="text-align:right" width="15%">
                                              {{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(((p['oc']['total_aux']/1.16)).toFixed(2)) }}
                                              <template v-if="p['oc']['moneda'] == 1">
                                                USD
                                              </template>
                                              <template v-if="p['oc']['moneda'] == 2">
                                                MNX
                                              </template>
                                              <template v-if="p['oc']['moneda'] == 3">
                                                EUR
                                              </template>
                                            </td>
                                          </tr>
                                        </table>
                                      </div>
                                      <div :id="p['oc']['id']+'Uno'" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion_uno">
                                        <div class="card-body">
                                          <!-- Inicio de listado de partidas de ordenes de compras pro proveedor -->

                                          <div id="accordion_dos" >
                                            <div class="card mb-0" v-for="(o, key_o) in p['p']">
                                              <div class="card-body" id="headingThree">
                                                <table>
                                                  <tr>
                                                    <td style="text-align:center" width="3%">&nbsp;{{key + 1}}.{{key_p + 1}}.{{key_o + 1}}&nbsp;&nbsp;</td>
                                                    <td scope="col" style="text-align:left" width="52%">
                                                      <!-- <button class="btn btn-link" data-toggle="collapse" :data-target="'#' + o['id']+'Tres'" aria-expanded="true" aria-controls="collapseThree"> -->
                                                      <template v-if="o['descripcion'] === o['comentario']">
                                                        {{o['descripcion']}}
                                                      </template>
                                                      <template v-if="o['descripcion'] != o['comentario']">
                                                        {{o['comentario']}} {{o['descripcion']}}
                                                      </template>

                                                      <!-- </button> -->
                                                    </td>
                                                    <td>
                                                      <template v-if="p['porcentaje'] > 0 && p['porcentaje'] < 100">
                                                        <b>  CANTIDAD ENTRADA:</b> <br>{{(o['cantidad'] - o['cantidad_entrada']).toFixed(2)}}
                                                      </template>
                                                    </td>
                                                    <td scope="col" style="text-align:right" width="15%">{{(o['cantidad'])}}</td>
                                                    <td scope="col" style="text-align:right" width="15%">{{     new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format( (o['precio_unitario']) )}}</td>
                                                    <td scope="col" style="text-align:right" width="15%"> {{ new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(((o['cantidad'] * o['precio_unitario'])).toFixed(2))  }}</td>
                                                  </tr>
                                                </table>
                                              </div>
                                            </div>
                                          </div>

                                          <!-- Fin de listado de partidas de ordenes de compras por proveedor -->
                                        </div>
                                      </div>
                                    </div>
                                  </div>

                                  <!-- Fin de listado de ordenes de compras por proveedor -->
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- Fin de listado de proveedores -->

                        </div>

                      </div>
                      <!-- Fin de segundo TAB -->

                      <!-- Segundo TAB -->
                      <div  v-show="tipoNav == 2" :class="tabconpago " role="tabpanel" aria-labelledby="nav-home-tab">

                        <table class="table table-bordered table-sm">
                          <tr>
                            <th width="60%">Descripción</th>
                            <th with="10%">Cantidad</th>
                            <th with="10%">Fecha</th>
                            <th with="10%">Precio</th>
                            <th with="10%">Moneda</th>
                          </tr>
                          <template v-for="t in listado_material"  >
                            <tr>
                              <td>{{t['a']['descripcion']}}</td>
                              <td>{{t['a']['cantidad_almacen']}}</td>
                              <td>{{t['a']['fecha_requerido']}}</td>
                              <td>{{t['b']['precio']}}</td>
                              <td>{{t['b']['moneda']}}</td>
                            </tr>
                          </template>
                        </table>
                      </div>
                      <!-- Fin de segundo TAB -->

                      <!-- Inicio Tercer Tab -->
                      <div  v-show="tipoNav == 3" :class="tabconoc" role="tabpanel" aria-labelledby="nav-home-tab">PENDIENTE...</div>
                      <!--  Fin Tercer Tab-->
                    </div>

                    <div class="card">
                      <!-- <table class="table table-hover table-sm table-bordered table-responsive">
                        <thead class="table-light">
                          <tr>
                            <th style="text-align:center" width="3%"></th>
                            <th scope="col" style="text-align:center" width="52%">PROVEEDORES</th>
                            <th scope="col" style="text-align:center" width="15%">USD</th>
                            <th scope="col" style="text-align:center" width="15%">MNX</th>
                            <th scope="col" style="text-align:center" width="15%">Total MNX</th>
                          </tr>
                        </thead> -->
                        <!-- <tbody> -->
                        <!-- <div v-if="resultado_proveedores != ''"> -->
                        <!--
                        <tr v-for="(t, key) in resultado_proveedores">
                        <td scope="col"> <a @click="verOrdenes(t['proveedor_id'])">{{key +1}}</a> </td>
                        <td>{{t['proveedor']}}</td>
                        <td>${{(t['total_usd_parcial'])}}</td>
                        <td>${{(t['total_mnx_parcial'])}}</td>
                        <td>${{(t['total_mnx'])}}</td>

                      </tr> -->
                      <!-- <tr><td scope="col">2</td><td>{{resultado_proveedores == '' ? '' :resultado_proveedores[1]['proveedor']}}</td><td>${{(resultado_proveedores == '' ? '' :resultado_proveedores[1]['total_mnx'])}}</td></tr>
                      <tr><td scope="col">3</td><td>{{resultado_proveedores == '' ? '' :resultado_proveedores[2]['proveedor']}}</td><td>${{(resultado_proveedores == '' ? '' :resultado_proveedores[2]['total_mnx'])}}</td></tr>
                      <tr><td scope="col">4</td><td>{{resultado_proveedores == '' ? '' :resultado_proveedores[3]['proveedor']}}</td><td>${{(resultado_proveedores == '' ? '' :resultado_proveedores[3]['total_mnx'])}}</td></tr>
                      <tr><td scope="col">5</td><td>{{resultado_proveedores == '' ? '' :resultado_proveedores[4]['proveedor']}}</td><td>${{(resultado_proveedores == '' ? '' :resultado_proveedores[4]['total_mnx'])}}</td></tr>
                      <tr><td scope="col">6</td><td>{{resultado_proveedores == '' ? '' :resultado_proveedores[5]['proveedor']}}</td><td>${{(resultado_proveedores == '' ? '' :resultado_proveedores[5]['total_mnx'])}}</td></tr>
                      <tr><td scope="col">7</td><td>{{resultado_proveedores == '' ? '' :resultado_proveedores[6]['proveedor']}}</td><td>${{(resultado_proveedores == '' ? '' :resultado_proveedores[6]['total_mnx'])}}</td></tr>
                      <tr><td scope="col">8</td><td>{{resultado_proveedores == '' ? '' :resultado_proveedores[7]['proveedor']}}</td><td>${{(resultado_proveedores == '' ? '' :resultado_proveedores[7]['total_mnx'])}}</td></tr>
                      <tr><td scope="col">9</td><td>{{resultado_proveedores == '' ? '' :resultado_proveedores[8]['proveedor']}}</td><td>${{(resultado_proveedores == '' ? '' :resultado_proveedores[8]['total_mnx'])}}</td></tr>
                      <tr><td scope="col">10</td><td>{{resultado_proveedores == '' ? '' :resultado_proveedores[9]['proveedor']}}</td><td>${{(resultado_proveedores == '' ? '' :resultado_proveedores[9]['total_mnx'])}}</td></tr> -->
                      <!-- </div> -->
                      <!-- </tbody> -->
                    <!-- </table> -->
                  </div>

                </div>
              </div>



            </div>
          </main>
        </template>

        <script>
        import chartTotal from './chartBar.vue';
        import chartUno from './chartBar.vue';
        import chartDos from './chartBar.vue';
        import chartTres from './chartBar.vue';
        import Utilerias from '../../Herramientas/utilerias.js';

        export default {
          name: 'app',
          components: {
            chartTotal,
            chartUno,
            chartDos,
            chartTres,
          },
          data() {
            return {
              classnavpro : 'nav-link active',
              classnavpagos : 'nav-link',
              classnavoc : 'nav-link',
              tabconpro : 'tab-pane fade show active',
              tabconpago : 'tab-pane fade',
              tabconpago : 'tab-pane fade',
              tipoNav : 1,

              ///
              isLoading :false,
              ver : 0,
              tableData : [],
              backgroundColorCI: ['#C28535', '#8AAE56', '#B66C46','#1A9EB6','#1AB65A'],
              tutulos: ['Cotización','Pago Cliente','OCs', 'Facturas','Proveedores'],
              listCI : null,
              listSI : null,
              resultado_proveedores : '',
              usd_compras_mxn : '',
              usd_factura_mxn : '',
              usd_pago_mxn : '',
              tipo_cambio_promedio : '',
              totalEjecutado: [],
              listaProyectos: [],
              listado_material : [],
              input : 0,
              totales : 0,
              total_usd_mnx : 0,
              listSuma: 0,
              listejec: 0,
              t_cotizado: 0,
              total_ejecutado: 0,
              utilidad: 0,
              proyecto_id: 0,
              registros: [],
              total_mano_obra : 0,
              total_admin : 0,
              total_sin_pro : 0,
              total_gastos_admin : 0,
              total_gastos_finanzas : 0,
              total_gastos_seguridad : 0,
              total_gastos_vehiculos : 0,
              total_gastos_ventas : 0,
              total_gastos : 0,
              total_viaticos : 0,
              total_general : 0,
              total_general_parcial : 0,
              total_caja_chica : 0,
              total_combustible : 0,
              total_material_almacen : 0,
              options1: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                  display: true,
                  position: 'bottom',
                },
                title: {
                  display: true,
                  text: 'Compras-Facturas'
                },
                scales: {
                  yAxes: [{
                    ticks: {
                      beginAtZero:true
                    }
                  }]
                }
              },
              options2: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                  display: true,
                  position: 'bottom',
                },
                title: {
                  display: true,
                  text: 'Compras-Pagos'
                },
                scales: {
                  yAxes: [{
                    ticks: {
                      beginAtZero:true
                    }
                  }]
                }
              },
              options3: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                  display: true,
                  position: 'bottom',
                },
                title: {
                  display: true,
                  text: 'Facturas-Pagos'
                },
                scales: {
                  yAxes: [{
                    ticks: {
                      beginAtZero:true
                    }
                  }]
                }
              },

              options6: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                  display: true,
                  position: 'bottom',
                },
                title: {
                  display: true,
                  text: 'Montos totales con IVA'
                },
                scales: {
                  yAxes: [{
                    ticks: {
                      beginAtZero:true
                    }
                  }]
                }
              },
              options5: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                  display: true,
                  position: 'bottom',
                },
                title: {
                  display: true,
                  text: 'Montos totales sin IVA'
                },
                scales: {
                  yAxes: [{
                    ticks: {
                      beginAtZero:true
                    }
                  }]
                }
              },
              chartData1: null,
              chartData2: null,
              chartData5: null,
              chartData6: null,

            }
          },
          computed : {
            inputd(){
              var resul = 0;
              resul = parseFloat(this.tableData['usd']) * this.tipo_cambio_promedio;
              return this.total_usd_mnx = resul;
            },
            totolss(){
              var resu=0;
              resu = parseFloat(this.total_usd_mnx) + parseFloat(this.tableData['mnx']);
              return this.totales = resu;
            },

            resultado_final(){
              var resultado = 0;
              resultado = parseFloat(this.totales/1.16) + parseFloat(this.total_mano_obra * 1.66) + parseFloat(this.total_admin * 1.66) + parseFloat(this.total_sin_pro * 1.66) + parseFloat(this.total_gastos_admin) + parseFloat(this.total_gastos_finanzas) + parseFloat(this.total_gastos_seguridad) + parseFloat(this.total_gastos_vehiculos)
              + parseFloat(this.total_gastos_ventas) + parseFloat(this.total_viaticos) +  parseFloat(this.total_caja_chica) +  parseFloat(this.total_combustible) + parseFloat(this.total_material_almacen);
              return this.total_general = resultado;
            },

            resultado_final_parcial(){
              var resultado = 0;
              resultado = parseFloat(this.totales/1.16) + parseFloat(this.total_mano_obra * 1.66) + parseFloat(this.total_admin * 1.66) + parseFloat(this.total_sin_pro * 1.66)
               + parseFloat(this.total_viaticos) +  parseFloat(this.total_caja_chica) +  parseFloat(this.total_combustible) + parseFloat(this.total_material_almacen);
              return this.total_general_parcial = resultado;
            }

          },
          methods: {
            navPro(){
              this.classnavpro = 'nav-link active';
              this.classnavpagos = 'nav-link';
              this.classnavoc = 'nav-link';
              this.tabconpro = 'tab-pane fade show active';
              this.tabconpago = 'tab-pane fade';
              this.tabconoc = 'tab-pane fade';
              this.tipoNav = 1;
            },

            navPagos(){
              this.classnavpro = 'nav-link';
              this.classnavpagos = 'nav-link active';
              this.classnavoc = 'nav-link';
              this.tabconpro = 'tab-pane fade';
              this.tabconpago = 'tab-pane fade show active';
              this.tabconoc = 'tab-pane fade';
              this.tipoNav = 2;
            },

            navOc(){
              this.classnavpro = 'nav-link';
              this.classnavpagos = 'nav-link';
              this.classnavoc = 'nav-link active';
              this.tabconpro = 'tab-pane fade';
              this.tabconpago = 'tab-pane fade';
              this.tabconoc = 'tab-pane fade show active';
              this.tipoNav = 3;
            },

            listarCompras(){
              if (this.proyecto_id == '') {
                toastr.warning('Seleccione un proyecto');
              }else {
                axios.get(`trazabilidadcompras/costo/${this.proyecto_id.id}`).then(response => {
                  this.tableData = response.data;
                  // console.log(response);
                }).catch(error => {
                  console.error(error);
                });
                this.objeto = this.proyecto_id.id;
                this.encabezado = 1;


                axios.get('obtener/mano/obra/operativo/'+ this.proyecto_id.id).then(response => {
                  this.total_mano_obra = response.data;
                }).catch(e => {
                  console.error();
                });

                axios.get('obtener/mano/obra/administrativos/'+ this.proyecto_id.id).then(response => {
                  this.total_admin = response.data;
                }).catch(e => {
                  console.error();
                });

                axios.get('obtener/mano/obra/sinproyecto/'+ this.proyecto_id.id).then(response => {
                  this.total_sin_pro = response.data;
                }).catch(e => {
                  console.error();
                });

                axios.get('obtener/total/gastos/admin/'+ this.proyecto_id.id).then(response => {
                  this.total_gastos_admin = response.data;
                }).catch(e => {
                  console.error();
                });

                axios.get('obtener/total/gastos/finanzas/'+ this.proyecto_id.id).then(response => {
                  this.total_gastos_finanzas = response.data;
                }).catch(e => {
                  console.error();
                });

                axios.get('obtener/total/gastos/seguridad/'+ this.proyecto_id.id).then(response => {
                  this.total_gastos_seguridad = response.data;
                }).catch(e => {
                  console.error();
                });

                axios.get('obtener/total/gastos/vehiculos/'+ this.proyecto_id.id).then(response => {
                  this.total_gastos_vehiculos = response.data;
                }).catch(e => {
                  console.error();
                });

                axios.get('obtener/total/gastos/ventas/'+ this.proyecto_id.id).then(response => {
                  this.total_gastos_ventas = response.data;
                }).catch(e => {
                  console.error();
                });

                axios.get('obtener/total/gastos/'+ this.proyecto_id.id).then(response => {
                  this.total_gastos = response.data;
                }).catch(e => {
                  console.error();
                });

                axios.get('reportegastosviaticos/porproyecto/'+ this.proyecto_id.id).then(response => {
                  this.total_viaticos = response.data;
                }).catch(e => {
                  console.error();
                });

                axios.get('obtener/total/gastos/caja/chica/'+ this.proyecto_id.id).then(response => {
                  console.log(response.data);
                  this.total_caja_chica = response.data;
                }).catch(e => {
                  console.error();
                });

                axios.get('obtener/total/gastos/combustible/'+ this.proyecto_id.id).then(response => {
                  this.total_combustible = response.data;
                }).catch(e => {
                  console.error();
                });

                axios.get('obtener/material/almacen/suma/'+ this.proyecto_id.id + '&' + this.tipo_cambio_promedio).then(response => {
                  this.total_material_almacen = response.data;
                }).catch(e => {
                  console.error();
                });

                axios.get('obtener/material/almacen/'+ this.proyecto_id.id).then(response => {
                  this.listado_material_cenagas = response.data;
                }).catch(e => {
                  console.error();
                });
              }
            },


            aumentar() {
              this.isLoading = true;
              if (this.proyecto_id.id == 0)
              {
                toastr.error('Seleccione un proyecto');
              }
              else if (this.tipo_cambio_promedio == '') {
                toastr.error('Defina el tipo de cambio');
              }else {
                this.listarCompras();

                let me=this;
                this.bgcoloruno =  Utilerias.setRandomColor();
                this.bgcolordos =  Utilerias.setRandomColor();
                me.listCI = null;
                me.listSI = null;
                axios.get('/compras/trazabilidad/' + this.proyecto_id.id  + '&' +this.tipo_cambio_promedio).then(response => {
                  var listBguno = [];
                  var listBgdos = [];
                  var listUsd = [];
                  var listUsd_iva = [];

                  var total_cotizado = [];
                  var total_pago_cliente = [];
                  var to_oc =0;
                  var to_f =0;
                  var to_p =0;
                  var to_cot = 0;
                  var to_pa = 0;
                  var to_co = 0;
                  var to_pc = 0;

                  var s_iva_ct = 0;
                  var s_iva_pc = 0;
                  var s_iva_oc = 0;
                  var s_iva_f = 0;
                  var s_iva_p = 0;
                  response.data.map(function(value, key) {

                    to_oc =  value.total_mnx_oc + (parseFloat(me.tipo_cambio_promedio) * value.total_usd_oc);
                    to_f =  value.total_mnx_f + (parseFloat(me.tipo_cambio_promedio) * value.total_usd_f);
                    to_p =  value.total_usd_p;
                    to_co = (value.cotizacion + ((value.cotizacion /100)*16));
                    to_pc = value.pagos_cliente;
                    listUsd.push(
                      (to_co.toFixed(2)),
                      to_pc.toFixed(2),
                      to_oc.toFixed(2),
                      to_f.toFixed(2),
                      to_p.toFixed(2)
                    );


                    s_iva_ct =  (to_co) - ((value.cotizacion / 100)*16);
                    s_iva_pc =  (value.pagos_cliente) - ((value.pagos_cliente / 100)*16);
                    s_iva_oc = to_oc - ((to_oc/100) * 16);
                    s_iva_f = to_f - ((to_f / 100) * 16);
                    s_iva_p = (value.total_usd_p) - ((value.total_usd_p /100)*16);

                    listUsd_iva.push(
                      s_iva_ct.toFixed(2),
                      s_iva_pc.toFixed(2),
                      s_iva_oc.toFixed(2),
                      s_iva_f.toFixed(2),
                      s_iva_p.toFixed(2)
                    );

                  });

                  me.listCI = listUsd;
                  me.listSI = listUsd_iva;
                  me.datacollection6 = {
                    labels: ['Cotización','Pago Cliente','OCs', 'Facturas','Pagos Proveedores'],
                    datasets: [
                      // {
                      //   label : 'Cotización',
                      //   backgroundColor : '#C28535',
                      //   data : [(to_co.toFixed(2))],
                      // },
                      // {
                      //   label : 'Pago Cliente',
                      //   backgroundColor : '#8AAE56',
                      //   data : ['',(to_pc.toFixed(2))],
                      //
                      // },
                      // {
                      //   label : 'OCs',
                      //   backgroundColor : '#B66C46',
                      //   data : ['','',(to_oc.toFixed(2))],
                      // },
                      {
                        label: '',
                        backgroundColor: ['#C28535', '#8AAE56', '#B66C46','#1A9EB6','#1AB65A'],
                        data: listUsd
                      },
                    ]
                  };
                  me.chartData6 = me.datacollection6;

                  me.datacollection5 = {
                    labels: ['Cotización','Pago Cliente','OCs', 'Facturas','Proveedores'],
                    datasets: [
                      // {
                      //   label : 'Cotizado',
                      //   backgroundColor : '#C28535',
                      //   data : total_cotizado,
                      // },
                      // {
                      //   label : 'Pagp Clientes',
                      //   backgroundColor : '#8AAE56',
                      //   data : total_cotizado,
                      // },
                      {
                        label: '',
                        backgroundColor: ['#C28535', '#8AAE56', '#B66C46','#1A9EB6','#1AB65A'],
                        data: listUsd_iva
                      },
                    ]
                  };
                  me.chartData5 = me.datacollection5;








                })
                .catch(function (error) {
                  console.log(error);
                });
                this.resultado_proveedores = [];
                axios.get('compras/trazabilidad/pro/'+this.proyecto_id.id + '&' +this.tipo_cambio_promedio).then(response => {
                  this.resultado_proveedores = response.data;
                  this.isLoading = false;
                }).catch(e => {
                  console.error(e);
                });
              }



            },

            // var colores=[];
            //
            // function getRandomColor() {
            //   var num=(Math.floor(Math.random()*4)*4).toString(16);
            //   var letters = ['0','F',num];
            //   var color = '#';
            //
            //   for (var i = 0; i < 3; i++ ) {
            //     let pos=Math.floor(Math.random() * letters.length);
            //     color += letters[pos];
            //     letters.splice(pos,1);
            //   }
            //
            //   //para evitar que se repitan colores
            //   if(colores.includes(color))
            //   return getRandomColor();
            //   else
            //   colores.push(color)
            //
            //   var str = "<div style='background-color:"+color+"'><button id='b1'>hola</button></div>"
            //   document.getElementById('colores').innerHTML+=str;
            //   return color;
            // }
            //
            getData() {
              let me=this;
              axios.get('/principales').then(response => {
                //  me.listaProyectos = response.data;
                for (var i = 0; i < response.data.length; i++) {
                  this.listaProyectos.push({
                    id: response.data[i].id,
                    name : response.data[i].nombre_corto,
                  });
                }
              })
              .catch(function (error) {
                console.log(error);
              });

            },

            verOrdenes(id){


            }

          },
          mounted() {
            this.getData();
          }
        }
        </script>
