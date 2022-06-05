<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <button type="button" @click="abrirModal(1)" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
        <v-client-table :data="tableData" :columns="columns" :options="options">

          <!-- 'fecha_entrega','unidad','placas' -->

          <template slot="data.id" slot-scope="props">
            <div class='btn-group' role='group'>
                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                    <i class='fas fa-grip-horizontal'></i>&nbsp;
                </button>
                <div class='dropdown-menu'>
                    <template>
                        <button v-show="props.row.data.condicion == 1" type='button' @click='abrirModal(2, props.row.data)' class='dropdown-item'>
                            <i class='fas fa-pencil-alt'></i>&nbsp;Actualizar Entrega
                        </button>
                        <button type='button' v-show="props.row.data.condicion == 1" @click='abrirModal(3, props.row.data)' class='dropdown-item'>
                            <i class='fas fa-file'></i>&nbsp;Recepción
                        </button>
                        <button type='button' v-show="props.row.data.condicion == 2" @click='abrirModal(4, props.row.data)' class='dropdown-item'>
                            <i class='fas fa-file'></i>&nbsp;Actualizar Recepción
                        </button>
                        <!-- <button type='button' @click='eliminar(props.row)' class='dropdown-item'>
                            <i class='fas fa-trash'></i>&nbsp;Eliminar
                        </button> -->
                    </template>
                </div>
            </div>
          </template>
          <template slot="condicion" slot-scope="props">
            <template v-if="props.row.data.condicion == 1">
             <button type="button" class="btn btn-outline-success">Entrega</button>
            </template>
            <template v-if="props.row.data.condicion == 2">
             <button type="button" class="btn btn-outline-primary">Entrega / Recepción</button>
            </template>
          </template>
          <template slot="descargar" slot-scope="props">
            <button type='button' @click='descargar(props.row.data)' class='btn btn-outline-dark'>
                <i class='fas fa-download'></i>&nbsp;
            </button>
          </template>
        </v-client-table>
        </div>
      </div>

      <div class="modal fade" tabindex="-1" :class="{ mostrar: modal }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div>
              <div class="modal-header">
                <h4 class="modal-title">Entrega / Recepción</h4>
                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">

                <div id="accordion">
                  <div class="card">
                    <div class="card-header" id="headingOne">
                      <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          DATOS DE LA UNIDAD
                        </button>
                      </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                      <div class="card-body">
                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                            <label>Unidad</label>
                            <v-select :options="listaCatalogo" v-model="datos.unidad" label="placas" v-validate="'required'" data-vv-name="unidad" >
                            </v-select>
                            <span class="text-danger">{{errors.first('unidad')}}</span>
                          </div>
                          <div class="col-md-6 mb-3">
                            <span>{{datos.unidad == '' ? '' : datos.unidad == null ? '' : datos.unidad.unidad + ' ' + datos.unidad.modelo + ' ' + datos.unidad.marca}}</span>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>{{edo < 3 ? 'Fecha Entrega' : edo > 2 ? 'Fecha Recepción' : ''}}</label>
                            <input type="date" class="form-control" v-model="datos.fecha_entrega" v-validate="'required'" data-vv-name="fecha_entrega">
                            <span class="text-danger">{{errors.first('fecha_entrega')}}</span>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>{{edo < 3 ? 'Kilometraje Entrega' : edo > 2 ?  'Kilometraje Recepción' : ''}}</label>
                            <input type="text" class="form-control" v-model="datos.kilometraje_entrega" v-validate="'required'" data-vv-name="kilometraje_entrega">
                            <span class="text-danger">{{errors.first('kilometraje_entrega')}}</span>
                          </div>
                          <div class="col-md-6 mb-3">
                            <label>{{edo < 3 ? 'Lugar de entrega' : edo > 2 ? 'Lugar de recepción' : ''}}</label>
                            <input type="text" class="form-control" v-model="datos.lugar_entrega" v-validate="'required'" data-vv-name="lugar_entrega">
                            <span class="text-danger">{{errors.first('lugar_entrega')}}</span>
                          </div>

                        </div>
                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                            <label>Proyecto</label>
                            <v-select :options="listaProyectos" v-model="datos.proyecto" label="name" name="name" data-vv-name="Proyecto" v-validate="'required'"></v-select>
                            <span class="text-danger">{{errors.first('Proyecto')}}</span>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                            <label>Destinatario</label>
                            <input type="text" class="form-control" v-model="datos.destinatario" v-validate="'required'" data-vv-name="destinatario">
                            <span class="text-danger">{{errors.first('destinatario')}}</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-header" id="headingTwo">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                          DATOS DEL USUARIO
                        </button>
                      </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                      <div class="card-body">
                        <div class="form-row">
                          <div class="col-md-5 mb-3">
                            <label>Nombre</label>
                            <v-select :options="listaEmpleados" label="name" v-model="usuario.nombre" v-validate="'required'" data-vv-name="nombre"></v-select>
                            <span class="text-danger">{{errors.first('nombre')}}</span>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;</label>
                            <span></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-header" id="headingThree">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                          ACCESORIOS EXTERNOS
                        </button>
                      </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;ESPEJO LATERAL DERECHO</label>
                            <select class="form-control" v-model="accesorios_e.uno">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;ESPEJO LATERAL IZQUIERDO</label>
                            <select class="form-control" v-model="accesorios_e.dos">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;PARABRISAS</label>
                            <select class="form-control" v-model="accesorios_e.tres">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;MEDALLÓN TRASERO</label>
                            <select class="form-control" v-model="accesorios_e.cuatro">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CRISTALES DE PUERTAS (LATERALES)</label>
                            <select class="form-control" v-model="accesorios_e.cinco">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;TAPÓN DE GASOLINA</label>
                            <select class="form-control" v-model="accesorios_e.seis">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;LIMPIADORES</label>
                            <select class="form-control" v-model="accesorios_e.siete">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;FAROS Y LUCES</label>
                            <select class="form-control" v-model="accesorios_e.ocho">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;BIRLOS DE LLANTAS</label>
                            <select class="form-control" v-model="accesorios_e.nueve">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;MOLDURAS</label>
                            <select class="form-control" v-model="accesorios_e.diez">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CALAVERAS</label>
                            <select class="form-control" v-model="accesorios_e.once">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;MATA CHISPAS</label>
                            <select class="form-control" v-model="accesorios_e.doce">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;ANTENA</label>
                            <select class="form-control" v-model="accesorios_e.trece">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;DEFENSAS</label>
                            <select class="form-control" v-model="accesorios_e.catorce">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;PARILLA</label>
                            <select class="form-control" v-model="accesorios_e.quince">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;PORTA LLANTAS</label>
                            <select class="form-control" v-model="accesorios_e.dieciseis">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;PLACA DELANTERA / TRASERA</label>
                            <select class="form-control" v-model="accesorios_e.diecisiete">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;DEFENSAS</label>
                            <select class="form-control" v-model="accesorios_e.dieciocho">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;TAPÓNES DE RUEDAS</label>
                            <select class="form-control" v-model="accesorios_e.diecinueve">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;LLANTA DE REFACCION</label>
                            <select class="form-control" v-model="accesorios_e.veinte">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;PLUMAS</label>
                            <select class="form-control" v-model="accesorios_e.ventiuno">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;ROLLE CAGE</label>
                            <select class="form-control" v-model="accesorios_e.ventidos">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>





                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="headingFive">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                          ACCESORIOS INTERNOS
                        </button>
                      </h5>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;ESPEJO RETROVISOR</label>
                            <select class="form-control" v-model="accesorios_i.uno">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;TAPETES</label>
                            <select class="form-control" v-model="accesorios_i.dos">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;ENCENDEDOR</label>
                            <select class="form-control" v-model="accesorios_i.tres">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CLAXON</label>
                            <select class="form-control" v-model="accesorios_i.cuatro">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;VISERAS</label>
                            <select class="form-control" v-model="accesorios_i.cinco">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CINTURONES DE SEGURIDAD</label>
                            <select class="form-control" v-model="accesorios_i.seis">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;MANIJAS</label>
                            <select class="form-control" v-model="accesorios_i.siete">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;RADIO</label>
                            <select class="form-control" v-model="accesorios_i.ocho">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;RADIO / CD</label>
                            <select class="form-control" v-model="accesorios_i.nueve">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CLIMA</label>
                            <select class="form-control" v-model="accesorios_i.diez">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>

                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="headingSix">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                          NIVELES
                        </button>
                      </h5>
                    </div>
                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;GASOLINA</label>
                            <select class="form-control" v-model="niveles.uno">
                              <option value="FULL">Full</option>
                              <option value="3/4">3/4</option>
                              <option value="1/2">1/2</option>
                              <option value="1/4">1/4</option>
                              <option value="VACIO">Vacio</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;LIQUIDO DE FRENOS</label>
                            <select class="form-control" v-model="niveles.dos">
                              <option value="FULL">Full</option>
                              <option value="3/4">3/4</option>
                              <option value="1/2">1/2</option>
                              <option value="1/4">1/4</option>
                              <option value="VACIO">Vacio</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;ACEITE DE MOTOR</label>
                            <select class="form-control" v-model="niveles.tres">
                              <option value="FULL">Full</option>
                              <option value="3/4">3/4</option>
                              <option value="1/2">1/2</option>
                              <option value="1/4">1/4</option>
                              <option value="VACIO">Vacio</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;ANTICONGELANTE</label>
                            <select class="form-control" v-model="niveles.cuatro">
                              <option value="FULL">Full</option>
                              <option value="3/4">3/4</option>
                              <option value="1/2">1/2</option>
                              <option value="1/4">1/4</option>
                              <option value="VACIO">Vacio</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;DIRECCIÓN HIDRAULICA</label>
                            <select class="form-control" v-model="niveles.cinco">
                              <option value="FULL">Full</option>
                              <option value="3/4">3/4</option>
                              <option value="1/2">1/2</option>
                              <option value="1/4">1/4</option>
                              <option value="VACIO">Vacio</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;LIQUIDO LIMPIA PARABRISAS</label>
                            <select class="form-control" v-model="niveles.seis">
                              <option value="FULL">Full</option>
                              <option value="3/4">3/4</option>
                              <option value="1/2">1/2</option>
                              <option value="1/4">1/4</option>
                              <option value="VACIO">Vacio</option>
                            </select>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="headingSeven">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                          CARROCERÍA E INTERIORES
                        </button>
                      </h5>
                    </div>
                    <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;COSTADO DERECHO</label>
                            <select class="form-control" v-model="carroceria.uno">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CAJUELA</label>
                            <select class="form-control" v-model="carroceria.dos">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;COSTADO IZQUIERDO</label>
                            <select class="form-control" v-model="carroceria.tres">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;PINTURA</label>
                            <select class="form-control" v-model="carroceria.cuatro">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;COFRE</label>
                            <select class="form-control" v-model="carroceria.cinco">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;SISTEMA DE ALARMA</label>
                            <select class="form-control" v-model="carroceria.seis">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;TOLDO</label>
                            <select class="form-control" v-model="carroceria.siete">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;VESTIDURAS</label>
                            <select class="form-control" v-model="carroceria.ocho">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;ASIENTOS ()</label>
                            <select class="form-control" v-model="carroceria.nueve">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CARROCERIA</label>
                            <select class="form-control" v-model="carroceria.diez">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;VENTANAS</label>
                            <select class="form-control" v-model="carroceria.once">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CHASIS</label>
                            <select class="form-control" v-model="carroceria.doce">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;EXTERIOR</label>
                            <select class="form-control" v-model="carroceria.trece">
                              <option value="LIMPIO">LIMPIO</option>
                              <option value="SUCIO">SUCIO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;INTERIOR</label>
                            <select class="form-control" v-model="carroceria.catorce">
                              <option value="LIMPIO">LIMPIO</option>
                              <option value="SUCIO">SUCIO</option>
                            </select>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="headingEight">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                          DETALLES DE FUNCIONAMIENTO
                        </button>
                      </h5>
                    </div>
                    <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;MOTOR</label>
                            <select class="form-control" v-model="funcionamiento.uno">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                              <option value="N/A">N/A</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CLUTCH</label>
                            <select class="form-control" v-model="funcionamiento.dos">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                              <option value="N/A">N/A</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;DIRECCIÓN HIDRAULICA</label>
                            <select class="form-control" v-model="funcionamiento.tres">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                              <option value="N/A">N/A</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;LIMPIA PARA PARABRISAS</label>
                            <select class="form-control" v-model="funcionamiento.cuatro">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                              <option value="N/A">N/A</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CAJA DE VELOCIDADES</label>
                            <select class="form-control" v-model="funcionamiento.cinco">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                              <option value="N/A">N/A</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;FRENOS</label>
                            <select class="form-control" v-model="funcionamiento.seis">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                              <option value="N/A">N/A</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;GPS</label>
                            <select class="form-control" v-model="funcionamiento.siete">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                              <option value="N/A">N/A</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CAMARA REVERSA</label>
                            <select class="form-control" v-model="funcionamiento.ocho">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                              <option value="N/A">N/A</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;REVERSERO</label>
                            <select class="form-control" v-model="funcionamiento.nueve">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                              <option value="N/A">N/A</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;OTRO</label>
                            <select class="form-control" v-model="funcionamiento.diez">
                              <option value="BUENO">Bueno</option>
                              <option value="REGULAR">Regular</option>
                              <option value="MALO">Malo</option>
                              <option value="N/A">N/A</option>
                            </select>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="headingNine">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                          ACCESORIOS Y HERRAMIENTAS AUXILIARES
                        </button>
                      </h5>
                    </div>
                    <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;EXTINTOR</label>
                            <select class="form-control" v-model="accesorios.uno">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;BOTIQUIN DE PRIMEROS AUXILIOS</label>
                            <select class="form-control" v-model="accesorios.dos">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;GATO HIDRAULICO</label>
                            <select class="form-control" v-model="accesorios.tres">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;FANTASMAS O SEÑALAMIENTOS REVERSA</label>
                            <select class="form-control" v-model="accesorios.cuatro">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;LLAVE DE CRUZ O ELE</label>
                            <select class="form-control" v-model="accesorios.cinco">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;LLAVES, DESAMADORES, PINZAS</label>
                            <select class="form-control" v-model="accesorios.seis">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;BIRLO DE SEGURIDAD</label>
                            <select class="form-control" v-model="accesorios.siete">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;EXTENSIONES Y GANCHOS ( )</label>
                            <select class="form-control" v-model="accesorios.ocho">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CABLES PARA CORRIENTE</label>
                            <select class="form-control" v-model="accesorios.nueve">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;LAMPARA, IMPERMEABLE</label>
                            <select class="form-control" v-model="accesorios.diez">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>


                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="headingTen">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                          COFRE
                        </button>
                      </h5>
                    </div>
                    <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;TAPÓN DE RADIADOR</label>
                            <select class="form-control" v-model="cofre.uno">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;TAPÓN DE ACEITE</label>
                            <select class="form-control" v-model="cofre.dos">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;BAYONETA ACEITE</label>
                            <select class="form-control" v-model="cofre.tres">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;TAPÓN DE DIRECCIÓN</label>
                            <select class="form-control" v-model="cofre.cuatro">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;SUJETADOR DE BATERIA</label>
                            <select class="form-control" v-model="cofre.cinco">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;TAPÓN LIMPIA PARABRISAS</label>
                            <select class="form-control" v-model="cofre.seis">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>

                        </div>


                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="headingOnce">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOnce" aria-expanded="false" aria-controls="collapseOnce">
                          LLANTAS
                        </button>
                      </h5>
                    </div>
                    <div id="collapseOnce" class="collapse" aria-labelledby="headingOnce" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;DELANTERA DERECHA</label>
                            <select class="form-control" v-model="llantas.uno">
                              <option value="NUEVA">Nueva</option>
                              <option value="1/ 2 VIDA">1/2 Vida</option>
                              <option value="LISA">Lisa</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;DELANTERA IZQUIERDA</label>
                            <select class="form-control" v-model="llantas.dos">
                              <option value="NUEVA">Nueva</option>
                              <option value="1/2 VIDA">1/2 Vida</option>
                              <option value="LISA">Lisa</option>
                            </select>
                          </div>

                          <template v-if="datos.unidad != '' ">
                            <template v-if="datos.unidad.clase_tipo > 2">
                            <div class="col-md-3 mb-3">
                              <label>&nbsp;TRASERA DERECHA</label>
                              <select class="form-control" v-model="llantas.tres">
                                <option value="NUEVA">Nueva</option>
                                <option value="1/2 VIDA">1/2 Vida</option>
                                <option value="LISA">Lisa</option>
                              </select>
                            </div>
                            <div class="col-md-3 mb-3">
                              <label>&nbsp;TRASERA IZQUIERDA</label>
                              <select class="form-control" v-model="llantas.cuatro">
                                <option value="NUEVA">Nueva</option>
                                <option value="1/2 VIDA">1/2 Vida</option>
                                <option value="LISA">Lisa</option>
                              </select>
                            </div>
                            </template>
                          </template>

                        </div>

                        <template v-if="datos.unidad != ''">
                          <template v-if="datos.unidad.clase_tipo == 1">

                            <div class="form-row">
                              <div class="col-md-3 mb-3">
                                <label>&nbsp;CENTRAL DERECHA 1</label>
                                <select class="form-control" v-model="llantas.seis">
                                  <option value="NUEVA">Nueva</option>
                                  <option value="1/2 VIDA">1/2 Vida</option>
                                  <option value="LISA">Lisa</option>
                                </select>
                              </div>
                              <div class="col-md-3 mb-3">
                                <label>&nbsp;CENTRAL DERECHA 2</label>
                                <select class="form-control" v-model="llantas.siete">
                                  <option value="NUEVA">Nueva</option>
                                  <option value="1/2 VIDA">1/2 Vida</option>
                                  <option value="LISA">Lisa</option>
                                </select>
                              </div>
                              <div class="col-md-3 mb-3">
                                <label>&nbsp;CENTRAL IZQUIERDA 1</label>
                                <select class="form-control" v-model="llantas.ocho">
                                  <option value="NUEVA">Nueva</option>
                                  <option value="1/2 VIDA">1/2 Vida</option>
                                  <option value="LISA">Lisa</option>
                                </select>
                              </div>
                              <div class="col-md-3 mb-3">
                                <label>&nbsp;CENTRAL IZQUIERDA 2</label>
                                <select class="form-control" v-model="llantas.nueve">
                                  <option value="NUEVA">Nueva</option>
                                  <option value="1/2 VIDA">1/2 Vida</option>
                                  <option value="LISA">Lisa</option>
                                </select>
                              </div>
                            </div>

                            <div class="form-row">
                              <div class="col-md-3 mb-3">
                                <label>&nbsp;TRASERA DERECHA 1</label>
                                <select class="form-control" v-model="llantas.diez">
                                  <option value="NUEVA">Nueva</option>
                                  <option value="1/2 VIDA">1/2 Vida</option>
                                  <option value="LISA">Lisa</option>
                                </select>
                              </div>
                              <div class="col-md-3 mb-3">
                                <label>&nbsp;TRASERA DERECHA 2</label>
                                <select class="form-control" v-model="llantas.once">
                                  <option value="NUEVA">Nueva</option>
                                  <option value="1/2 VIDA">1/2 Vida</option>
                                  <option value="LISA">Lisa</option>
                                </select>
                              </div>
                              <div class="col-md-3 mb-3">
                                <label>&nbsp;TRASERA IZQUIERDA 1</label>
                                <select class="form-control" v-model="llantas.doce">
                                  <option value="NUEVA">Nueva</option>
                                  <option value="1/2 VIDA">1/2 Vida</option>
                                  <option value="LISA">Lisa</option>
                                </select>
                              </div>
                              <div class="col-md-3 mb-3">
                                <label>&nbsp;TRASERA IZQUIERDA 2</label>
                                <select class="form-control" v-model="llantas.trece">
                                  <option value="NUEVA">Nueva</option>
                                  <option value="1/2 VIDA">1/2 Vida</option>
                                  <option value="LISA">Lisa</option>
                                </select>
                              </div>
                            </div>

                          </template>
                          <template v-if="datos.unidad != ''">

                          <template v-if="datos.unidad.clase_tipo == 2">

                            <div class="form-row">
                              <div class="col-md-3 mb-3">
                                <label>&nbsp;TRASERA DERECHA 1</label>
                                <select class="form-control" v-model="llantas.diez">
                                  <option value="NUEVA">Nueva</option>
                                  <option value="1/2 VIDA">1/2 Vida</option>
                                  <option value="LISA">Lisa</option>
                                </select>
                              </div>
                              <div class="col-md-3 mb-3">
                                <label>&nbsp;TRASERA DERECHA 2</label>
                                <select class="form-control" v-model="llantas.once">
                                  <option value="NUEVA">Nueva</option>
                                  <option value="1/2 VIDA">1/2 Vida</option>
                                  <option value="LISA">Lisa</option>
                                </select>
                              </div>
                              <div class="col-md-3 mb-3">
                                <label>&nbsp;TRASERA IZQUIERDA 1</label>
                                <select class="form-control" v-model="llantas.doce">
                                  <option value="NUEVA">Nueva</option>
                                  <option value="1/2 VIDA">1/2 Vida</option>
                                  <option value="LISA">Lisa</option>
                                </select>
                              </div>
                              <div class="col-md-3 mb-3">
                                <label>&nbsp;TRASERA IZQUIERDA 2</label>
                                <select class="form-control" v-model="llantas.trece">
                                  <option value="NUEVA">Nueva</option>
                                  <option value="1/2 VIDA">1/2 Vida</option>
                                  <option value="LISA">Lisa</option>
                                </select>
                              </div>
                            </div>

                          </template>
                          </template>
                        </template>


                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;REFACCIÓN</label>
                            <select class="form-control" v-model="llantas.cinco">
                              <option value="NUEVA">Nueva</option>
                              <option value="1/2 VIDA">1/2 Vida</option>
                              <option value="LISA">Lisa</option>
                            </select>
                          </div>
                        </div>


                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="headingDoce">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseDoce" aria-expanded="false" aria-controls="collapseDoce">
                          LUCES
                        </button>
                      </h5>
                    </div>
                    <div id="collapseDoce" class="collapse" aria-labelledby="headingDoce" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CARRETERA (LARGA)</label>
                            <select class="form-control" v-model="luces.uno">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;CRUCE (CORTA)</label>
                            <select class="form-control" v-model="luces.dos">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;DIRECCIÓNALES</label>
                            <select class="form-control" v-model="luces.tres">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;STOP</label>
                            <select class="form-control" v-model="luces.cuatro">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;REVERSA</label>
                            <select class="form-control" v-model="luces.cinco">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;NIEBLA</label>
                            <select class="form-control" v-model="luces.seis">
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="headingTrece">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTrece" aria-expanded="false" aria-controls="collapseTrece">
                          FOTOS
                        </button>
                      </h5>
                    </div>
                    <div id="collapseTrece" class="collapse" aria-labelledby="headingTrece" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <div class="col-md-3 mb-3">
                              <label>&nbsp;Foto</label>
                              <input type="file" accept="image/*" ref="adjunto" capture="camera" @change="cargarImg()"/>
                            </div>
                          </div>
                        </div>

                        <div class="form-row">

                          <div class="form-group col-md-2">
                            <label><b>#</b></label>
                          </div>
                          <div class="form-group col-md-6">
                            <label><b>Preview</b></label>
                          </div>
                          <div class="form-group col-md-1">
                            <label><b>.</b></label>
                          </div>
                        </div>
                        <li v-for="(vi, index) in img" class="list-group-item">
                          <div class="form-row">

                            <div class="form-group col-md-2">
                              <label>{{index + 1}}</label>
                            </div>
                            <div class="form-group col-md-6">
                              <label></label>
                              <img :src="vi.name" width="200px" height="150px">
                            </div>
                            <div class="form-group col-md-1">
                              <a @click="deleteu(vi, index)">
                                <span class="fas fa-trash" arial-hidden="true"></span>
                              </a>
                            </div>
                          </div>
                        </li>


                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="headingCatorce">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseCatorce" aria-expanded="false" aria-controls="collapseCatorce">
                          OBSERVACIONES
                        </button>
                      </h5>
                    </div>
                    <div id="collapseCatorce" class="collapse" aria-labelledby="headingCatorce" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-12 mb-3">
                            <label>&nbsp;OBSERVACIONES</label>
                            <textarea name="name" class="form-control" rows="4" cols="80" v-model="observaciones"></textarea>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="card">
                    <div class="card-header" id="headingQuince">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseQuince" aria-expanded="false" aria-controls="collapseQuince">
                          PERSONAL
                        </button>
                      </h5>
                    </div>
                    <div id="collapseQuince" class="collapse" aria-labelledby="headingQuince" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-5 mb-3">
                            <label>&nbsp;PERSONA QUE ENTREGA</label>
                            <v-select :options="listaEmpleados" label="name" v-model="personal.entrega" v-validate="'required'" data-vv-name="entrega"></v-select>
                            <span class="text-danger">{{errors.first('entrega')}}</span>
                          </div>
                          <div class="col-md-5 mb-3">
                            <label>&nbsp;PERSONA QUE RECIBE</label>
                            <v-select :options="listaEmpleados" label="name" v-model="personal.recibe" v-validate="'required'" data-vv-name="recibe"></v-select>
                            <span class="text-danger">{{errors.first('recibe')}}</span>
                          </div>
                        </div>

                        <!-- <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;REVERSA</label>
                            <select class="form-control" >
                              <option value="1">SI</option>
                              <option value="0">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;NIEBLA</label>
                            <select class="form-control" >
                              <option value="1">SI</option>
                              <option value="0">NO</option>
                            </select>
                          </div>
                        </div> -->

                      </div>
                    </div>
                  </div>

                </div>





              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" @click="cerrarModal()">
                  <i class="fas fa-window-close"></i>&nbsp;Cerrar
                </button>
                <button v-show="tipoAccion == 1" type="button" class="btn btn-secondary" @click="Guardar(1)">
                  Guardar
                </button>
                <button v-show="tipoAccion == 3" type="button" class="btn btn-secondary" @click="GuardarRep(1)">
                  Guardar
                </button>
                <button v-show="tipoAccion == 2" type="button" class="btn btn-secondary" @click="Guardar(0)">
                  Actualizar
                </button>
                <button v-show="tipoAccion == 4" type="button" class="btn btn-secondary" @click="GuardarRep(0)">
                  Actualizar
                </button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal agregar documentos-->

    </div>
  </main>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default{
  data(){
    return {
      img : [],
      modal : 0,
      tipoAccion : 0,

      listaCatalogo : [],
      listaEmpleados : [],
      listaProyectos : [],

      id : 0,
      id_rel : 0,
      edo : '',

      datos : {
        unidad : '',
        fecha_entrega : '',
        kilometraje_entrega : '',
        lugar_entrega : '',
        proyecto  : '',
        destinatario : '',
      },

      usuario : {
        nombre : '',
      },

      personal : {
        entrega : '',
        recibe : '',
      },

      accesorios_e : {
        uno : 'SI',
        dos : 'SI',
        tres : 'SI',
        cuatro : 'SI',
        cinco : 'SI',
        seis : 'SI',
        siete : 'SI',
        ocho : 'SI',
        nueve : 'SI',
        diez : 'SI',
        once : 'SI',
        doce : 'SI',
        trece : 'SI',
        catorce : 'SI',
        quince : 'SI',
        dieciseis : 'SI',
        diecisiete : 'SI',
        dieciocho : 'SI',
        diecinueve : 'SI',
        veinte : 'SI',
        ventiuno : 'SI',
        ventidos : 'SI',
      },

      accesorios_i : {
        uno : 'SI',
        dos : 'SI',
        tres : 'SI',
        cuatro : 'SI',
        cinco : 'SI',
        seis : 'SI',
        siete : 'SI',
        ocho : 'SI',
        nueve : 'SI',
        diez : 'SI',
      },

      niveles : {
        uno : 'FULL',
        dos : 'FULL',
        tres : 'FULL',
        cuatro : 'FULL',
        cinco : 'FULL',
        seis : 'FULL',
      },

      carroceria : {
        uno : 'BUENO',
        dos : 'BUENO',
        tres : 'BUENO',
        cuatro : 'BUENO',
        cinco : 'BUENO',
        seis : 'BUENO',
        siete : 'BUENO',
        ocho : 'BUENO',
        nueve : 'BUENO',
        diez : 'BUENO',
        once : 'BUENO',
        doce : 'BUENO',
        trece : 'LIMPIO',
        catorce : 'LIMPIO',
      },

      funcionamiento : {
        uno : 'BUENO',
        dos : 'BUENO',
        tres : 'BUENO',
        cuatro : 'BUENO',
        cinco : 'BUENO',
        seis : 'BUENO',
        siete : 'BUENO',
        ocho : 'BUENO',
        nueve : 'BUENO',
        diez : 'BUENO',
      },

      accesorios : {
        uno : 'SI',
        dos : 'SI',
        tres : 'SI',
        cuatro : 'SI',
        cinco : 'SI',
        seis : 'SI',
        siete : 'SI',
        ocho : 'SI',
        nueve : 'SI',
        diez : 'SI',
      },

      cofre : {
        uno : 'SI',
        dos : 'SI',
        tres : 'SI',
        cuatro : 'SI',
        cinco : 'SI',
        seis : 'SI',
      },

      llantas : {
        uno : 'NUEVA',
        dos : 'NUEVA',
        tres : 'NUEVA',
        cuatro : 'NUEVA',
        cinco : 'NUEVA',
        seis : 'NUEVA',
        siete : 'NUEVA',
        ocho : 'NUEVA',
        nueve : 'NUEVA',
        diez : 'NUEVA',
        once : 'NUEVA',
        doce : 'NUEVA',
        trece : 'NUEVA',
      },

      luces : {
        uno : 'SI',
        dos : 'SI',
        tres : 'SI',
        cuatro : 'SI',
        cinco : 'SI',
        seis : 'SI',
      },

      observaciones : '',

      tableData : [],
      columns : ['data.id','fecha_entrega','unidad','placas','proyecto','descargar','condicion'],
      options :
      {
        headings:
        {
          'data.id': 'Acciones',

        }, // Headings,
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
      }, //options

    }
  },
  methods : {
    getData(){
      axios.get('UnidadesStore').then(response => {
        this.listaCatalogo = response.data;
      }).catch(e => {
        console.error(e);
      });

      axios.get('entrega/vehiculos/get/').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });

    },

    getLista(){
      this.listaEmpleados = [];
      axios.get('/vertodosempleados').then(response =>{
        response.data.forEach(data =>{
          this.listaEmpleados.push({
            id: data.id,
            name: data.nombre + ' ' + data.ap_paterno + ' ' + data.ap_materno
          });
        });
      })
      .catch(function (error)
      {
        console.log(error);
      });

      axios.get('/proyecto-listar-todos').then(response => {
        response.data.forEach((item, i) => {
          this.listaProyectos.push({
            id : item.id,
            name : item.nombre_corto,
            ubicacion : item.ciudad,
          });
        });
      }).catch(e => {
        console.error(e);
      });

    },

    abrirModal(edo, data = []){
      if (edo == 1) {
        this.edo = edo;
        Utilerias.resetObject(this.datos);
        this.datos.unidad = '';
        Utilerias.resetObject(this.usuario);
        Utilerias.resetObject(this.personal);
        this.observaciones = '';
        this.img = [];
        this.modal = 1;
        this.tipoAccion = 1;
      }else if (edo == 2) {

        this.edo = edo;
        Utilerias.resetObject(this.datos);
        this.datos.unidad = '';
        Utilerias.resetObject(this.usuario);
        Utilerias.resetObject(this.personal);
        this.observaciones = '';
        this.img = [];
        this.modal = 1;
        this.tipoAccion = 2;
        this.id = data['id'];
        this.datos = JSON.parse(data['datos']);
        this.usuario.nombre = {id : data['usuario'], name : data['nombre_usuario']};
        this.personal = {
          entrega : {id : data['entrega'], name : data['nombre_entrega']},
          recibe : {id : data['recibe'], name : data['nombre_recibe']}
        };
        this.accesorios_e = JSON.parse(data['accesorios_e']);
        this.accesorios_i = JSON.parse(data['accesorios_i']);
        this.niveles = JSON.parse(data['niveles']);
        this.carroceria = JSON.parse(data['carroceria']);
        this.funcionamiento = JSON.parse(data['funcionamiento']);
        this.accesorios = JSON.parse(data['accesorios']);
        this.cofre = JSON.parse(data['cofre']);
        this.llantas = JSON.parse(data['llantas']);
        this.luces = JSON.parse(data['luces']);
        this.observaciones = data['observaciones'];
        this.getImages(data['id']);
      }
      else if (edo == 3) {
        this.edo = edo;
        Utilerias.resetObject(this.datos);
        this.datos.unidad = '';
        Utilerias.resetObject(this.usuario);
        Utilerias.resetObject(this.personal);
        this.observaciones = '';
        this.modal = 1;
        this.tipoAccion = 3;
        this.img = [];
        this.id_rel = data['id'];
        this.datos = JSON.parse(data['datos']);
        this.usuario.nombre = {id : data['usuario'], name : data['nombre_usuario']};
        this.personal = {
          entrega : {id : data['entrega'], name : data['nombre_entrega']},
          recibe : {id : data['recibe'], name : data['nombre_recibe']}
        };
        this.accesorios_e = JSON.parse(data['accesorios_e']);
        this.accesorios_i = JSON.parse(data['accesorios_i']);
        this.niveles = JSON.parse(data['niveles']);
        this.carroceria = JSON.parse(data['carroceria']);
        this.funcionamiento = JSON.parse(data['funcionamiento']);
        this.accesorios = JSON.parse(data['accesorios']);
        this.cofre = JSON.parse(data['cofre']);
        this.llantas = JSON.parse(data['llantas']);
        this.luces = JSON.parse(data['luces']);
        this.observaciones = data['observaciones'];
        // this.getImages(data['id']);
      }
      else if (edo == 4) {
        this.edo = edo;
        Utilerias.resetObject(this.datos);
        this.datos.unidad = '';
        Utilerias.resetObject(this.usuario);
        Utilerias.resetObject(this.personal);
        this.observaciones = '';
        this.modal = 1;
        this.tipoAccion = 4;

        axios.get('get/recepcion/trafico/' + data['id']).then(response => {

          this.id = response.data['id'];
          this.datos = JSON.parse(response.data['datos']);
          this.usuario.nombre = {id : response.data['usuario'], name : response.data['nombre_usuario']};
          this.personal = {
            entrega : {id : response.data['entrega'], name : response.data['nombre_entrega']},
            recibe : {id : response.data['recibe'], name : response.data['nombre_recibe']}
          };
          this.accesorios_e = JSON.parse(response.data['accesorios_e']);
          this.accesorios_i = JSON.parse(response.data['accesorios_i']);
          this.niveles = JSON.parse(response.data['niveles']);
          this.carroceria = JSON.parse(response.data['carroceria']);
          this.funcionamiento = JSON.parse(response.data['funcionamiento']);
          this.accesorios = JSON.parse(response.data['accesorios']);
          this.cofre = JSON.parse(response.data['cofre']);
          this.llantas = JSON.parse(response.data['llantas']);
          this.luces = JSON.parse(response.data['luces']);
          this.observaciones = response.data['observaciones'];
          this.getImages(response.data['id']);

        }).catch(e => {
          console.error(e);
        });



      }
    },

    getImages(id){
      this.img = [];
      axios.get('get/imagenes/entrega/vehiculos/' + id).then(response => {
        response.data.forEach((item, i) => {
          this.img.push({id : item.id,id_rel : item.id_rel,name : item.img});
        });
      }).catch(e => {
        console.error(e);
      });
    },

    cerrarModal(){
      this.modal = 0;
      this.edo = 0;
    },

    cargarImg(){
      const selectedImage = this.$refs.adjunto.files[0];
      this.imageToBase64(selectedImage);
    },

    imageToBase64 (file) {
      var reader = new FileReader()
      reader.readAsDataURL(file)
      reader.onload = () => {
        this.img.push({id : 0,id_rel : 0,name : reader.result});
      }
      reader.onerror = function (error) {
        console.log('Error: ', error)
      }
    },

    deleteu(data, index){
      if (data.id == 0) {
        this.img.splice(index, 1);
      }else if (data.id != 0) {
        axios.get('delete/imagenes/entrega/vehiculos/' + data.id).then(response => {
          this.getImages(this.id);
        }).catch(e => {
          console.error(e);
        });
      }
    },

    Guardar(nuevo){
      this.$validator.validate().then(result => {
        if(result){
          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? 'entrega/vehiculos/guardar/' : 'entrega/vehiculos/actualizar/',
            data : {
              id : this.id,
              datos : this.datos,
              usuario : this.usuario,
              personal : this.personal,
              accesorios_e : this.accesorios_e,
              accesorios_i : this.accesorios_i,
              niveles : this.niveles,
              carroceria : this.carroceria,
              funcionamiento : this.funcionamiento,
              accesorios : this.accesorios,
              cofre : this.cofre,
              llantas : this.llantas,
              luces : this.luces,
              imagenes : this.img,
              observaciones : this.observaciones,
            }
          }).then(response => {
            toastr.success(nuevo ? 'Guardado Correctamente' : 'Actualizado Correctamente');
            this.modal = 0;
            this.getData();
          }).catch(e => {
            console.error(e);
          });
        }else {
          toastr.warning('Complete os campos obligatorios !!!');
        }
      });
    },

    GuardarRep(nuevo){
      this.$validator.validate().then(result => {
        if(result){
          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? 'recepcion/vehiculos/guardar/' : 'recepcion/vehiculos/actualizar/',
            data : {
              id : this.id,
              id_rel : this.id_rel,
              datos : this.datos,
              usuario : this.usuario,
              personal : this.personal,
              accesorios_e : this.accesorios_e,
              accesorios_i : this.accesorios_i,
              niveles : this.niveles,
              carroceria : this.carroceria,
              funcionamiento : this.funcionamiento,
              accesorios : this.accesorios,
              cofre : this.cofre,
              llantas : this.llantas,
              luces : this.luces,
              imagenes : this.img,
              observaciones : this.observaciones,
            }
          }).then(response => {
            toastr.success(nuevo ? 'Guardado Correctamente' : 'Actualizado Correctamente');
            this.modal = 0;
            this.getData();
          }).catch(e => {
            console.error(e);
          });
        }else {
          toastr.warning('Complete os campos obligatorios !!!');
        }
      });
    },

    descargar(data){
      window.open('trafico/descargar/entrega/recepcion/' + data['id'], '_blank');
    },

  },
  mounted(){
    this.getData();
    this.getLista();
  }
}
</script>
