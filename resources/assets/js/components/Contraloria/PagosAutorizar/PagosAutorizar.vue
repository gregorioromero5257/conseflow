<template>
  <main class="main">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Compras por autorizar pagos

        </div>
        <div class="card-body">
          <!-- <button type="button"  @click="nextMes()" class="btn btn-secondary float-sm-right">
            <i class="fas fa-arrow-right"></i>&nbsp;Siguiente
          </button>
          <button type="button" class="btn btn-secondary float-sm-left">
            <i class="fas fa-arrow-left"></i>&nbsp;Anterior
          </button>
          <br><br> -->
          <template v-if="registros != null ">
            <div class="form-row">


              <div class="col-md-4">
                <div class="card" v-show="!detalle_uno">
                  <div class="card-header">

                    <h5>{{meses[0]}}</h5>
                  </div>
                  <div class="card-body" >
                    <paginate name="uno" :list="registros[0]" :per="3">
                      <div v-for="lang in paginated('uno')">

                        <h6 class="card-title">&nbsp;<span class="badge badge-pill badge-info">{{(lang.fecha_probable_pago)}}</span>&nbsp;{{lang.folio}}</h6>
                        <template v-if='fecha_dos(lang.fecha_probable_pago) <= 0'>
                          <span class="badge badge-pill badge-danger">Vencido</span>
                        </template>
                        <template v-if="fecha_dos(lang.fecha_probable_pago) > 0 && fecha_dos(lang.fecha_probable_pago) < 3">
                          <span class="badge badge-pill badge-warning">Proxima a vencer</span>
                        </template>
                        <template v-if="fecha_dos(lang.fecha_probable_pago) >= 3">
                          <span class="badge badge-pill badge-success">Normal</span>
                        </template><br>
                        <button type="button" class="btn btn-secondary" @click="detalle(lang)"><i class="fas fa-eye"></i>Detalles</button>
                        <template v-if="lang.condicion_pnr == 6">
                          <button type="button" class="btn btn-primary" @click="autorizar(lang.pnr_id)">Autorizar</button>
                        </template>
                        <template v-if="lang.condicion_pnr == 4">
                          <button type="button" class="btn btn-success">Autorizado</button>
                        </template>

                        <hr>
                      </div>
                    </paginate>
                    <paginate-links for="uno"
                    :simple="{
                      prev: 'Anterior',
                      next: 'Siguiente'
                      }"
                      :classes="{
                        'ul': 'pagination',
                        '.next > a': 'next-link',
                        '.prev > a': ['prev-link', 'another-class'] // multiple classes
                        }"
                        ></paginate-links>
                      </div>
                      <div class="card-footer">
                        <small class="text-muted">Monto debe $&nbsp; {{monto_debe_uno}}</small><br>
                        <small class="text-muted">Monto autorizado $&nbsp; {{monto_autorizado_uno}}</small>
                      </div>
                    </div>
                    <div class="card" v-show="detalle_uno">
                      <div class="card-header">
                        {{data_uno == null ? '' :data_uno.folio}}
                        <h5>{{meses[0]}}</h5>
                        <button type="button" v-show="detalle_uno" @click="maestroUno()" class="btn btn-secondary float-sm-right">
                          <i class="fas fa-arrow-left"></i>&nbsp;Atras
                        </button>
                      </div>
                      <div class="card-body">
                        <table>
                          <tr>
                            <th scope="col">Proveedor</th>
                          </tr>
                          <tr>
                            <td>{{data_uno == null ? '' :data_uno.razon_social}}</td>
                          </tr>
                          <tr>
                            <th scope="col">Urgente</th>
                          </tr>
                          <tr>
                            <td>{{data_uno == null ? '' : data_uno.prioridad == '1' ? 'SI' : 'NO'}}</td>
                          </tr>
                          <hr>
                          <tr>
                            <th scope="col">Condición Pago</th>
                            <th scope="col">Periodo Entrega</th>
                          </tr>
                          <tr>
                            <td>{{data_uno == null ? '' :data_uno.condicion_pago_id == 1 ? 'Crédito' : 'Contado'}}</td>
                            <td>{{data_uno == null ? '' :data_uno.periodo_entrega}}</td>
                          </tr>
                          <hr>
                          <tr>
                            <th scope="col">Moneda</th>
                            <th scope="col">Monto</th>
                          </tr>
                          <tr>
                            <td>{{data_uno == null ? '' :data_uno.moneda == 2 ? 'MXN' : 'USD'}}</td>
                            <td>{{data_uno == null ? '' :data_uno.total}}</td>
                          </tr>
                          <hr>
                          <tr>
                            <th scope="col">Fecha Orden</th>
                            <th scope="col">Luegar Entrega</th>
                          </tr>
                          <tr>
                            <td>{{data_uno == null ? '' :data_uno.fecha_orden == 2 ? 'MXN' : 'USD'}}</td>
                            <td>{{data_uno == null ? '' :data_uno.lugar_entrega}}</td>
                          </tr>
                          <hr>
                          <tr>
                            <th scope="col">Empleado Autoriza</th>
                          </tr>
                          <tr>
                            <td>{{data_uno == null ? '' :data_uno.nombre_empleado_autoriza}}</td>
                          </tr>
                        </table>
                      </div>
                      <div class="card-footer">
                        <small class="text-muted">Monto debe $&nbsp; {{monto_debe_uno}}</small><br>
                        <small class="text-muted">Monto autorizado $&nbsp; {{monto_autorizado_uno}}</small>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="card" v-show="!detalle_dos">
                      <div class="card-header">

                        <h5>{{meses[1]}}</h5>

                      </div>
                      <div class="card-body" >
                        <paginate name="dos" :list="registros[1]" :per="3">
                          <div v-for="lang in paginated('dos')">

                            <h6 class="card-title">&nbsp;<span class="badge badge-pill badge-info">{{getDayF(lang.fecha_probable_pago)}}</span>&nbsp;{{lang.folio}}</h6>
                            <template v-if='fecha_dos(lang.fecha_probable_pago) <= 0'>
                              <span class="badge badge-pill badge-danger">Vencido</span>
                            </template>
                            <template v-if="fecha_dos(lang.fecha_probable_pago) > 0 && fecha_dos(lang.fecha_probable_pago) < 3">
                              <span class="badge badge-pill badge-warning">Proxima a vencer</span>
                            </template>
                            <template v-if="fecha_dos(lang.fecha_probable_pago) >= 3">
                              <span class="badge badge-pill badge-success">Normal</span>
                            </template><br>
                            <button type="button" class="btn btn-secondary" @click="detalleDos(lang)"><i class="fas fa-eye"></i>Detalles</button>
                            <template v-if="lang.condicion_pnr == 6">
                              <button type="button" class="btn btn-primary" @click="autorizar(lang.pnr_id)">Autorizar</button>
                            </template>
                            <template v-if="lang.condicion_pnr == 4">
                              <button type="button" class="btn btn-primary">Autorizado</button>
                            </template>

                            <hr>
                          </div>
                        </paginate>
                        <paginate-links for="dos"
                        :simple="{
                          prev: 'Anterior',
                          next: 'Siguiente'
                          }"
                          :classes="{
                            'ul': 'pagination',
                            '.next > a': 'next-link',
                            '.prev > a': ['prev-link', 'another-class'] // multiple classes
                            }"
                            ></paginate-links>
                          </div>
                          <div class="card-footer">
                            <small class="text-muted">Monto debe $&nbsp; {{monto_debe_dos}}</small><br>
                            <small class="text-muted">Monto autorizado $&nbsp; {{monto_autorizado_dos}}</small>
                          </div>
                        </div>
                        <div class="card" v-show="detalle_dos">
                          <div class="card-header">
                            {{data_dos == null ? '' :data_dos.folio}}
                            <h5>{{meses[1]}}</h5>
                            <button type="button" v-show="detalle_dos" @click="maestroDos()" class="btn btn-secondary float-sm-right">
                              <i class="fas fa-arrow-left"></i>&nbsp;Atras
                            </button>
                          </div>
                          <div class="card-body">
                            <table>
                              <tr>
                                <th scope="col">Proveedor</th>
                              </tr>
                              <tr>
                                <td>{{data_dos == null ? '' :data_dos.razon_social}}</td>
                              </tr>
                              <tr>
                                <th scope="col">Urgente</th>
                              </tr>
                              <tr>
                                <td>{{data_dos == null ? '' : data_dos.prioridad == '1' ? 'SI' : 'NO'}}</td>
                              </tr>
                              <hr>
                              <tr>
                                <th scope="col">Condición Pago</th>
                                <th scope="col">Periodo Entrega</th>
                              </tr>
                              <tr>
                                <td>{{data_dos == null ? '' :data_dos.condicion_pago_id == 1 ? 'Crédito' : 'Contado'}}</td>
                                <td>{{data_dos == null ? '' :data_dos.periodo_entrega}}</td>
                              </tr>
                              <hr>
                              <tr>
                                <th scope="col">Moneda</th>
                                <th scope="col">Monto</th>
                              </tr>
                              <tr>
                                <td>{{data_dos == null ? '' :data_dos.moneda == 2 ? 'MXN' : 'USD'}}</td>
                                <td>{{data_dos == null ? '' :data_dos.total}}</td>
                              </tr>
                              <hr>
                              <tr>
                                <th scope="col">Fecha Orden</th>
                                <th scope="col">Luegar Entrega</th>
                              </tr>
                              <tr>
                                <td>{{data_dos == null ? '' :data_dos.fecha_orden == 2 ? 'MXN' : 'USD'}}</td>
                                <td>{{data_dos == null ? '' :data_dos.lugar_entrega}}</td>
                              </tr>
                              <hr>
                              <tr>
                                <th scope="col">Empleado Elabora</th>
                              </tr>
                              <tr>
                                <td>{{data_dos == null ? '' :data_dos.nombre_empleado_elabora}}</td>
                              </tr>
                              <hr>
                              <tr>
                                <th scope="col">Empleado Autoriza</th>
                              </tr>
                              <tr>
                                <td>{{data_dos == null ? '' :data_dos.nombre_empleado_autoriza}}</td>
                              </tr>
                            </table>
                          </div>
                          <div class="card-footer">
                            <small class="text-muted">Monto debe $&nbsp; {{monto_debe_dos}}</small><br>
                            <small class="text-muted">Monto autorizado $&nbsp; {{monto_autorizado_dos}}</small>
                          </div>
                        </div>
                      </div>


                      <div class="col-md-4">
                        <div class="card" v-show="!detalle_tres">
                          <div class="card-header">

                            <h5>{{meses[2]}}</h5>

                          </div>
                          <div class="card-body" >
                            <paginate name="tres" :list="registros[2]" :per="3">
                              <div v-for="lang in paginated('tres')">

                                <h6 class="card-title">&nbsp;<span class="badge badge-pill badge-info">{{getDayF(lang.fecha_probable_pago)}}</span>&nbsp;{{lang.folio}}</h6>
                                <template v-if='fecha_dos(lang.fecha_probable_pago) <= 0'>
                                  <span class="badge badge-pill badge-danger">Vencido</span>
                                </template>
                                <template v-if="fecha_dos(lang.fecha_probable_pago) > 0 && fecha_dos(lang.fecha_probable_pago) < 3">
                                  <span class="badge badge-pill badge-warning">Proxima a vencer</span>
                                </template>
                                <template v-if="fecha_dos(lang.fecha_probable_pago) >= 3">
                                  <span class="badge badge-pill badge-success">Normal</span>
                                </template><br>
                                <button type="button" class="btn btn-secondary" @click="detalleTres(lang)"><i class="fas fa-eye"></i>Detalles</button>
                                <template v-if="lang.condicion_pnr == 6">
                                  <button type="button" class="btn btn-primary" @click="autorizar(lang.pnr_id)">Autorizar</button>
                                </template>
                                <template v-if="lang.condicion_pnr == 4">
                                  <button type="button" class="btn btn-primary">Autorizado</button>
                                </template>
                                <hr>
                              </div>
                            </paginate>
                            <paginate-links for="tres"
                            :simple="{
                              prev: 'Anterior',
                              next: 'Siguiente'
                              }"
                              :classes="{
                                'ul': 'pagination',
                                '.next > a': 'next-link',
                                '.prev > a': ['prev-link', 'another-class'] // multiple classes
                                }"
                                ></paginate-links>
                              </div>
                              <div class="card-footer">
                                <small class="text-muted">Monto debe $&nbsp; {{monto_debe_tres}}</small><br>
                                <small class="text-muted">Monto autorizado $&nbsp; {{monto_autorizado_tres}}</small>
                              </div>
                            </div>
                            <div class="card" v-show="detalle_tres">
                              <div class="card-header">
                                {{data_tres == null ? '' :data_tres.folio}}
                                <h5>{{meses[2]}}</h5>
                                <button type="button" v-show="detalle_tres" @click="maestroTres()" class="btn btn-secondary float-sm-right">
                                  <i class="fas fa-arrow-left"></i>&nbsp;Atras
                                </button>
                              </div>
                              <div class="card-body">
                                <table>
                                  <tr>
                                    <th scope="col">Proveedor</th>
                                  </tr>
                                  <tr>
                                    <td>{{data_tres == null ? '' :data_tres.razon_social}}</td>
                                  </tr>
                                  <tr>
                                    <th scope="col">Urgente</th>
                                  </tr>
                                  <tr>
                                    <td>{{data_tres == null ? '' : data_tres.prioridad == '1' ? 'SI' : 'NO'}}</td>
                                  </tr>
                                  <hr>
                                  <tr>
                                    <th scope="col">Condición Pago</th>
                                    <th scope="col">Periodo Entrega</th>
                                  </tr>
                                  <tr>
                                    <td>{{data_tres == null ? '' :data_tres.condicion_pago_id == 1 ? 'Crédito' : 'Contado'}}</td>
                                    <td>{{data_tres == null ? '' :data_tres.periodo_entrega}}</td>
                                  </tr>
                                  <hr>
                                  <tr>
                                    <th scope="col">Moneda</th>
                                    <th scope="col">Monto</th>
                                  </tr>
                                  <tr>
                                    <td>{{data_tres == null ? '' :data_tres.moneda == 2 ? 'MXN' : 'USD'}}</td>
                                    <td>{{data_tres == null ? '' :data_tres.total}}</td>
                                  </tr>
                                  <hr>
                                  <tr>
                                    <th scope="col">Fecha Orden</th>
                                    <th scope="col">Luegar Entrega</th>
                                  </tr>
                                  <tr>
                                    <td>{{data_tres == null ? '' :data_tres.fecha_orden == 2 ? 'MXN' : 'USD'}}</td>
                                    <td>{{data_tres == null ? '' :data_tres.lugar_entrega}}</td>
                                  </tr>
                                  <hr>
                                  <tr>
                                    <th scope="col">Empleado Elabora</th>
                                  </tr>
                                  <tr>
                                    <td>{{data_tres == null ? '' :data_tres.nombre_empleado_elabora}}</td>
                                  </tr>
                                  <hr>
                                  <tr>
                                    <th scope="col">Empleado Autoriza</th>
                                  </tr>
                                  <tr>
                                    <td>{{data_tres == null ? '' :data_tres.nombre_empleado_autoriza}}</td>
                                  </tr>
                                </table>
                              </div>
                              <div class="card-footer">
                                <small class="text-muted">Monto debe $&nbsp; {{monto_debe_tres}}</small><br>
                                <small class="text-muted">Monto autorizado $&nbsp; {{monto_autorizado_tres}}</small>
                              </div>
                            </div>
                          </div>










                    </div>
                  </template>
                    <template v-if="vencidos != null ">
                  <div class="form-row">
                    <div class="col-md-6" >
                      <div class="card"  v-show="!detalle_cuatro">
                        <div class="card-header">
                          Compras Vencidas

                        </div>
                        <div class="card-body">
                          <paginate name="cuatro" :list="vencidos" :per="3">
                            <div v-for="lang in paginated('cuatro')">

                              <h6 class="card-title">&nbsp;<span class="badge badge-pill badge-info">{{lang.fecha_probable_pago}}</span>&nbsp;{{lang.folio}}</h6>
                              <template v-if='fecha_dos(lang.fecha_probable_pago) <= 0'>
                                <span class="badge badge-pill badge-danger">Vencido</span>
                              </template>
                              <template v-if="fecha_dos(lang.fecha_probable_pago) > 0 && fecha_dos(lang.fecha_probable_pago) < 3">
                                <span class="badge badge-pill badge-warning">Proxima a vencer</span>
                              </template>
                              <template v-if="fecha_dos(lang.fecha_probable_pago) >= 3">
                                <span class="badge badge-pill badge-success">Normal</span>
                              </template><br>
                              <button type="button" class="btn btn-secondary" @click="detalleCuatro(lang)"><i class="fas fa-eye"></i>Detalles</button>
                              <template v-if="lang.condicion_pnr == 6">
                                <button type="button" class="btn btn-primary" @click="autorizar(lang.pnr_id)">Autorizar</button>
                              </template>
                              <template v-if="lang.condicion_pnr == 4">
                                <button type="button" class="btn btn-primary">Autorizado</button>
                              </template>

                              <hr>
                            </div>
                          </paginate>
                          <paginate-links for="cuatro"
                          :simple="{
                            prev: 'Anterior',
                            next: 'Siguiente'
                            }"
                            :classes="{
                              'ul': 'pagination',
                              '.next > a': 'next-link',
                              '.prev > a': ['prev-link', 'another-class'] // multiple classes
                              }"
                              ></paginate-links>
                            </div>
                            <div class="card-footer">
                              <small class="text-muted">Monto debe $&nbsp; {{monto_debe_cuatro}}</small><br>
                              <small class="text-muted">Monto autorizado $&nbsp; {{monto_autorizado_cuatro}}</small>
                            </div>
                          </div>
                          <div class="card" v-show="detalle_cuatro">
                            <div class="card-header">
                              {{data_cuatro == null ? '' :data_cuatro.folio}}
                              Compras Vencidas
                              <button type="button" v-show="detalle_cuatro" @click="maestroCuatro()" class="btn btn-secondary float-sm-right">
                                <i class="fas fa-arrow-left"></i>&nbsp;Atras
                              </button>
                            </div>
                            <div class="card-body">
                              <table>
                                <tr>
                                  <th scope="col">Proveedor</th>
                                </tr>
                                <tr>
                                  <td>{{data_cuatro == null ? '' :data_cuatro.razon_social}}</td>
                                </tr>
                                <tr>
                                  <th scope="col">Urgente</th>
                                </tr>
                                <tr>
                                  <td>{{data_cuatro == null ? '' : data_cuatro.prioridad == '1' ? 'SI' : 'NO'}}</td>
                                </tr>
                                <hr>
                                <tr>
                                  <th scope="col">Condición Pago</th>
                                  <th scope="col">Periodo Entrega</th>
                                </tr>
                                <tr>
                                  <td>{{data_cuatro == null ? '' :data_cuatro.condicion_pago_id == 1 ? 'Crédito' : 'Contado'}}</td>
                                  <td>{{data_cuatro == null ? '' :data_cuatro.periodo_entrega}}</td>
                                </tr>
                                <hr>
                                <tr>
                                  <th scope="col">Moneda</th>
                                  <th scope="col">Monto</th>
                                </tr>
                                <tr>
                                  <td>{{data_cuatro == null ? '' :data_cuatro.moneda == 2 ? 'MXN' : 'USD'}}</td>
                                  <td>{{data_cuatro == null ? '' :data_cuatro.total}}</td>
                                </tr>
                                <hr>
                                <tr>
                                  <th scope="col">Fecha Orden</th>
                                  <th scope="col">Luegar Entrega</th>
                                </tr>
                                <tr>
                                  <td>{{data_cuatro == null ? '' :data_cuatro.fecha_orden == 2 ? 'MXN' : 'USD'}}</td>
                                  <td>{{data_cuatro == null ? '' :data_cuatro.lugar_entrega}}</td>
                                </tr>
                                <hr>
                                <tr>
                                  <th scope="col">Empleado Elabora</th>
                                </tr>
                                <tr>
                                  <td>{{data_cuatro == null ? '' :data_cuatro.nombre_empleado_elabora}}</td>
                                </tr>
                                <hr>
                                <tr>
                                  <th scope="col">Empleado Autoriza</th>
                                </tr>
                                <tr>
                                  <td>{{data_cuatro == null ? '' :data_cuatro.nombre_empleado_autoriza}}</td>
                                </tr>
                              </table>
                            </div>
                            <div class="card-footer">
                              <small class="text-muted">Monto debe $&nbsp; {{monto_debe_cuatro}}</small><br>
                              <small class="text-muted">Monto autorizado $&nbsp; {{monto_autorizado_cuatro}}</small>
                            </div>
                          </div>
                        </div>

                  </div>

                  </template>
                  <template v-if="registros == null">
                    <vue-element-loading :active="isLoading"/>

                  </template>

                </div>
              </div>
            </div>
          </main>
        </template>
        <script>
        import VuePaginate from 'vue-paginate'
        Vue.use(VuePaginate)
        import Utilerias from '../../Herramientas/utilerias.js';
        var config = require('../../Herramientas/config-vuetables-client').call(this);
        export default{
          data () {
            return {
              isLoading : true,
              meses : [],
              anios : [],
              num_meses : [],
              registros: null,
              vencidos : null,
              paginate: ['uno','dos','tres','cuatro'],
              fecha_now : '',
              detalle_uno : false,
              data_uno : null,
              detalle_dos : false,
              data_dos : null,
              detalle_tres : false,
              data_tres : null,
              detalle_cuatro : false,
              data_cuatro : null,
              monto_debe_uno : '',
              monto_debe_dos : '',
              monto_debe_tres : '',
              monto_debe_cuatro : '',
              monto_autorizado_uno : '',
              monto_autorizado_dos : '',
              monto_autorizado_tres : '',
              monto_autorizado_cuatro : '',
            }
          },
          methods : {

            getCompras(){
              var total_debe_uno = 0;
              var total_debe_dos = 0;
              var total_debe_tres = 0;
              var total_debe_cuatro = 0;
              var total_autorizado_uno = 0;
              var total_autorizado_dos = 0;
              var total_autorizado_tres = 0;
              var total_autorizado_cuatro = 0;
              this.fecha_now = this.fecha_uno();
              axios.get('pagosporautorizar').then(response => {
                this.registros = response.data.a;
                this.meses = response.data.mhy;
                  response.data.a[0].forEach((item, i) => {
                    if (item.condicion_pnr == 6) {
                      total_debe_uno = parseFloat(total_debe_uno) + parseFloat(item.monto);
                    }
                    if (item.condicion_pnr == 4) {
                      total_autorizado_uno = parseFloat(total_autorizado_uno) + parseFloat(item.monto);
                    }
                  });
                  response.data.a[1].forEach((item, i) => {
                    if (item.condicion_pnr == 6) {
                      total_debe_dos = parseFloat(total_debe_dos) + parseFloat(item.monto);
                    }
                    if (item.condicion_pnr == 4) {
                      total_autorizado_dos = parseFloat(total_autorizado_dos) + parseFloat(item.monto);
                    }
                  });
                  response.data.a[2].forEach((item, i) => {
                    if (item.condicion_pnr == 6) {
                      total_debe_tres = parseFloat(total_debe_tres) + parseFloat(item.monto);
                    }
                    if (item.condicion_pnr == 4) {
                      total_autorizado_tres = parseFloat(total_autorizado_tres) + parseFloat(item.monto);
                    }
                  });

                  this.monto_debe_uno = total_debe_uno.toFixed(2);
                  this.monto_debe_dos = total_debe_dos.toFixed(2);
                  this.monto_debe_tres = total_debe_tres.toFixed(2);
                  this.monto_autorizado_uno = total_autorizado_uno.toFixed(2);
                  this.monto_autorizado_dos = total_autorizado_dos.toFixed(2);
                  this.monto_autorizado_tres = total_autorizado_tres.toFixed(2);
              }).catch(e => {
                console.error(e);
              });

              axios.get('pagosvencidos').then(response => {
                this.vencidos = response.data;
                response.data.forEach((item, i) => {
                  if (item.condicion_pnr == 6) {
                    total_debe_cuatro = parseFloat(total_debe_cuatro) + parseFloat(item.monto);
                  }
                  if (item.condicion_pnr == 4) {
                    total_autorizado_cuatro = parseFloat(total_autorizado_cuatro) + parseFloat(item.monto);
                  }
                });
                this.monto_debe_cuatro = total_debe_cuatro.toFixed(2);
                this.monto_autorizado_cuatro = total_autorizado_cuatro.toFixed(2);
              }).catch(e => {
                console.error(e);
              })
            },

            fecha_uno(){
              var num_meses = ['01','02','03','04','05','06','07','08','09','10','11','12'];
              var f=new Date();
              var fecha_mes =num_meses[f.getMonth()];
              var fecha_anio =f.getFullYear();
              var dia = '';
              dia = f.getDay();
              if (dia < 10) {
                dia = '0'+dia;
              }
              return fecha_anio + '-' +fecha_mes + '-' + dia;
            },

            fecha_dos(date){
              let fecha1 = new Date();
              let fecha2 = new Date(date);
              let resta = fecha2.getTime() - fecha1.getTime();
              let r = Math.round(resta / (1000*60*60*24));
              return r;
            },

            getDayF(data){
              let dia = data.split('-');
              return dia[2];
            },

            detalle(data){
              this.data_uno = data;
              this.detalle_uno = true;
            },

            maestroUno(){
              this.detalle_uno = false;
            },

            detalleDos(data){
              this.data_dos = data;
              this.detalle_dos = true;
            },

            maestroDos(){
              this.detalle_dos = false;
            },

            detalleTres(data){
              this.data_tres = data;
              this.detalle_tres = true;
            },

            maestroTres(){
              this.detalle_tres = false;
            },

            detalleCuatro(data){
              this.data_cuatro = data;
              this.detalle_cuatro = true;
            },

            maestroCuatro(){
              this.detalle_cuatro = false;
            },

            autorizar(id){
              axios.get('autorizarpagos/'+id).then(response => {
                this.getCompras();
              }).catch(e => {
                console.error(e);
              });
            },

          },
          mounted() {
            this.getCompras();
          }
        }

        </script>
