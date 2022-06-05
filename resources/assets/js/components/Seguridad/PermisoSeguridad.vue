<template>
  <main class="main">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> PERMISO DE TRABAJO
          <button type="button" @click="abrirModal('analisis','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <v-client-table :data="tableData" :columns="columns" :options="options">
            <template slot="uno.id" slot-scope="props">

              <button class="btn btn-outline-dark" @click.prevent="abrirModal('analisis','actualizar',props.row)" >
                <i class="fas fa-pencil-alt"></i>&nbsp;
              </button>
              <button class="btn btn-outline-dark" @click.prevent="eliminar(props.row.uno.id)" >
                <i class="fas fa-trash"></i>&nbsp;
              </button>

            </template>

            <template slot="descarga" slot-scope="props">
              <button type="button" @click="descargar(props.row.uno.id)" class="btn btn-outline-dark" >
                <i class="fas fa-download"></i>&nbsp;<i class="fas fa-file-pdf"></i>
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
                <h4 class="modal-title">{{tituloModal}}</h4>
                <button type="button" class="close" @click="Cerrar()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <!-- <vue-element-loading :active="isLoading" /> -->
                <div class="form-row">
                  <div class="col-md-4 mb-3">
                    <label> Folio</label>
                    <input type="text" v-validate="'required'" class="form-control" v-model="uno.uno" data-vv-name="Folio">
                    <span class="text-danger">{{errors.first('Folio')}}</span>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label> Asociado</label>
                    <input type="text" class="form-control" v-model="uno.dos" data-vv-name="Asociado">
                    <span class="text-danger">{{errors.first('Asociado')}}</span>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label> Fecha Elaboración</label>
                    <input type="date" v-validate="'required'" class="form-control" v-model="uno.tres" data-vv-name="Fecha">
                    <span class="text-danger">{{errors.first('Fecha')}}</span>
                  </div>
                </div>

                <div class="form-row">
                  <div class="col-md-5 mb-3">
                    <label> Instalación</label>
                    <input type="text" v-validate="'required'" class="form-control" v-model="uno.cuatro" data-vv-name="Folio">
                    <span class="text-danger">{{errors.first('Folio')}}</span>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label> Ubicación</label>
                    <input type="text" class="form-control" v-model="uno.cinco" data-vv-name="Asociado">
                    <span class="text-danger">{{errors.first('Asociado')}}</span>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label> Nivel Riesgo</label>
                    <select class="form-control" v-model="uno.seis" v-validate="'required'" data-vv-name="Nivel Riesgo">
                      <option value="0">A</option>
                      <option value="1">B</option>
                    </select>
                    <span class="text-danger">{{errors.first('Nivel Riesgo')}}</span>
                  </div>
                </div>
                <hr>
                <div id="accordion">
                  <!-- Inicio DESCRIPCIÓN DEL TRABAJO -->
                  <div class="card">
                    <div class="card-header" id="headingOne">
                      <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          1. DESCRIPCIÓN DEL TRABAJO
                        </button>
                      </h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                      <div class="card-body">
                        <div class="form-row">
                          <div class="col-md-3 mb-2">
                            <label> Trabajo Caliente</label>
                            <select class="form-control" v-model="dos.uno" @click="llenarC()">
                              <option value=""></option>
                              <option value="1">SI</option>
                              <option value="0">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-2">
                            <label>  Espacios confinados</label>
                            <select class="form-control" v-model="dos.dos">
                              <option value=""></option>
                              <option value="1">SI</option>
                              <option value="0">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-2">
                            <label>  Altura</label>
                            <select class="form-control" v-model="dos.tres">
                              <option value=""></option>
                              <option value="1">SI</option>
                              <option value="0">NO</option>
                            </select>
                          </div>
                          <div class="col-md-3 mb-2">
                            <label> Eléctricos</label>
                            <select class="form-control" v-model="dos.cuatro" @click="llenarE()">
                              <option value=""></option>
                              <option value="1">SI</option>
                              <option value="0">NO</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-4">
                            <label>Otros Trabajos</label>
                            <textarea name="name" rows="2" cols="80" class="form-control" v-model="dos.cinco"></textarea>
                          </div>
                          <div class="col-md-8">
                            <label>Descripción de la tarea</label>
                            <textarea name="name" rows="2" cols="80" class="form-control" v-model="dos.seis"></textarea>
                          </div>
                        </div>
                        <br>

                      </div>
                    </div>

                  </div>
                  <!-- FIN DESCRIPCIÓN DEL TRABAJO -->
                  <!--  -->
                  <div class="card">
                    <div class="card-header" id="headingTwo">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                          2. FECHA DE INICIO Y TÉRMINO
                        </button>
                      </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                      <div class="card-body">
                        <div class="form-row">
                          <div class="col-md-3 mb-3">
                            <label> Fecha inicio</label>
                            <input type="date" class="form-control" v-model="tres.uno">
                          </div>
                          <div class="col-md-3 mb-3">
                            <label> Hora</label>
                            <input type="time" class="form-control" v-model="tres.dos">
                          </div>
                          <div class="col-md-3 mb-3">
                            <label> Fecha termino</label>
                            <input type="date" class="form-control" v-model="tres.tres">
                          </div>
                          <div class="col-md-3 mb-3">
                            <label> Hora</label>
                            <input type="time" class="form-control" v-model="tres.cuatro">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--  -->
                  <div class="card">
                    <div class="card-header" id="headingThree">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                          3. REQUISITOS A CUMPLIR ANTES DE LA EJECUCION DEL TRABAJO
                        </button>
                      </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                      <div class="card-body">
                        <!--  -->
                        <nav>
                          <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">RESPONSABLES DE OPERACIÓN </a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">EQUIPO DE PROTECCIÓN PERSONAL </a>
                            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">PRECAUCIONES CONTRA INCENDIO</a>
                          </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                          <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Los factores meteorológicos permiten realizar el trabajo.</label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.uno">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.uno">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.uno">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">La iluminación del área de trabajo es la correcta para realizar la actividad.</label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.dos">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.dos">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.dos">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">El área se encuentra con accesos rapidos para su entrada y salida.</label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.catorce">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.catorce">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.catorce">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Los equipos estan puestas a tierra.</label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.tres">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.tres">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.tres">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Se han instalado mamparas para proteger y aislar las personas y equipos de las áreas vecinas.</label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.cuatro">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.cuatro">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.cuatro">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Los andamios han sido inspeccionados</label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.cinco">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.cinco">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.cinco">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">La instalación y equipos eléctricos son antiexplosivos.</label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.seis">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.seis">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.seis">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Herramientas: estan en condiciones y acordes a las tareas.</label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.siete">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.siete">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.siete">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Se ha desenergizado el equipo </label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.ocho">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.ocho">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.ocho">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Esta limitado el acceso de personal al área de trabajo.</label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.nueve">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.nueve">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.nueve">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Los materiales combustibles se encuentren dentro de un radio de 11 metros (35 pies) del área de trabajo. </label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.diez">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.diez">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.diez">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">El equipo de soldadura ha sido inspeccionado y se encuentra en buen estado. </label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.once">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.once">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.once">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Los cilindros se encuentran en posición vertical, bien asegurados y libres de fugas.</label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.doce">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.doce">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.doce">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Los cilindros cumplen con el estándar de identificación (Nombre del producto y rotulación NFPA o UN).</label>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="1" v-model="cuatro.trece">
                                <label class="form-control-label">SI</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="2" v-model="cuatro.trece">
                                <label class="radio-inspeccion">NO</label>
                              </div>
                              <div class="form-group col-2">
                                <input class="" type="radio" value="3" v-model="cuatro.trece">
                                <label class="form-control-label">N/A</label>
                              </div>
                            </div>


                          </div>
                          <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Casco contraimpacto</label>
                              <div class="form-group col-2">
                                <input class="" type="checkbox" value="1" v-model="cinco.uno">
                                <label class="form-control-label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Ropa de trabajo</label>
                              <div class="form-group col-2">
                                <input class="" type="checkbox" value="1" v-model="cinco.dos">
                                <label class="form-control-label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Protección ocular</label>
                              <div class="form-group col-2">
                                <input class="" type="checkbox" value="1" v-model="cinco.tres">
                                <label class="form-control-label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Calzado de seguridad</label>
                              <div class="form-group col-2">
                                <input class="" type="checkbox" value="1" v-model="cinco.cuatro">
                                <label class="form-control-label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Protección auditiva</label>
                              <div class="form-group col-2">
                                <input class="" type="checkbox" value="1" v-model="cinco.cinco">
                                <label class="form-control-label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="form-control-label col-md-6">OTROS</label>
                              <div class="form-group col-6">
                                <input class="form-control" type="text" v-model="cinco.seis">
                              </div>
                            </div>

                          </div>
                          <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">

                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Ayudante contraincendio</label>
                              <div class="form-group col-2">
                                <input class="" type="checkbox" v-model="seis.uno">
                                <label class="form-control-label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Manguera Contraincendio</label>
                              <div class="form-group col-2">
                                <input class="" type="checkbox" v-model="seis.dos">
                                <label class="form-control-label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Cortina de agua</label>
                              <div class="form-group col-2">
                                <input class="" type="checkbox" v-model="seis.tres">
                                <label class="form-control-label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Barreras y letreros de seguridad </label>
                              <div class="form-group col-2">
                                <input class="" type="checkbox" v-model="seis.cuatro">
                                <label class="form-control-label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Colocar lonas para cubrir drenajes y equipos delicados y humedecerlas </label>
                              <div class="form-group col-2">
                                <input class="" type="checkbox" v-model="seis.cinco">
                                <label class="form-control-label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Extintor</label>
                              <div class="form-group col-2">
                                <input class="" type="checkbox" v-model="seis.seis">
                                <label class="form-control-label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Eliminar todo el material combustible</label>
                              <div class="form-group col-2">
                                <input class="" type="checkbox" v-model="seis.siete">
                                <label class="form-control-label"></label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="form-control-label col-md-6">Proteger Instrumentación e iluminación</label>
                              <div class="form-group col-2">
                                <input class="" type="checkbox" v-model="seis.ocho">
                                <label class="form-control-label"></label>
                              </div>
                            </div>

                          </div>
                        </div>
                        <!--  -->
                      </div>
                    </div>
                  </div>
                  <!--  -->
                  <!--  -->
                  <div class="card">
                    <div class="card-header" id="headingFour">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                          4. PRECAUCIONES ESPECIALES Y RIESGOS POTENCIALES
                        </button>
                      </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                      <div class="card-body">
                        <div class="form-row">
                          <div class="col-mb-8 md-3">
                            <label>PRECAUCIONES</label>
                            <textarea name="name" rows="8" cols="80" class="form-control" v-model="siete.uno"></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--  -->
                  <!--  -->
                  <div class="card">
                    <div class="card-header" id="headingFive">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                          5. VERIFICACION EN CAMPO DEL CUMPLIMIENTO DE LOS REQUISITOS SOLICITADOS
                        </button>
                      </h5>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                            <label>Residente de Obra</label>
                            <v-select :options="listaEmpleados" label="name" v-model="ocho.uno"></v-select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>Fecha</label>
                            <input type="date" class="form-control" v-model="ocho.dos" >
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                            <label>Supervisor de Trabajo</label>
                            <v-select :options="listaEmpleados" label="name" v-model="ocho.tres"></v-select>
                          </div>
                          <div class="col-md-3 md-3">
                            <label>Fecha</label>
                            <input type="date" class="form-control" v-model="ocho.cuatro">
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                            <label>Responsable de SHSO</label>
                            <v-select :options="listaEmpleados" label="name" v-model="ocho.cinco"></v-select>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>Fecha</label>
                            <input type="date" class="form-control" v-model="ocho.seis">
                          </div>
                        </div>


                      </div>
                    </div>
                  </div>
                  <!--  -->
                  <!--  -->
                  <div class="card">
                    <div class="card-header" id="headingSeis">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeis" aria-expanded="false" aria-controls="collapseSeis">
                          6. REVALIDACIONES
                        </button>
                      </h5>
                    </div>
                    <div id="collapseSeis" class="collapse" aria-labelledby="headingSeis" data-parent="#accordion">
                      <div class="card-body">
                        <!-- {{once}} -->
                        <div class="form-row">
                          <div class="col-md-12">
                            <label>VALIDACION /REVALIDACIÓN</label>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>Fecha</label>
                            <input type="date" class="form-control" v-model="temporal.uno">
                          </div>
                          <div class="col-md-2 mb-3">
                            <label>Desde</label>
                            <input type="time" class="form-control" v-model="temporal.dos">
                          </div>
                          <div class="col-md-2 mb-3">
                            <label>Hasta</label>
                            <input type="time" class="form-control" v-model="temporal.tres">
                          </div>
                          <div class="col-md-5 mb-3">
                            <label>Nombre</label>
                            <v-select :options="listaEmpleados" label="name" v-model="temporal.cuatro"></v-select>
                          </div>
                        </div>
                        <hr>
                        <div class="form-row">
                          <div class="col-md-12">
                            <label>ACEPTACIÓN DEL PERMISO</label>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>Fecha</label>
                            <input type="date" class="form-control" v-model="temporal.cinco">
                          </div>
                          <div class="col-md-2 mb-3">
                            <label>Desde</label>
                            <input type="time" class="form-control" v-model="temporal.seis">
                          </div>
                          <div class="col-md-2 mb-3">
                            <label>Hasta</label>
                            <input type="time" class="form-control" v-model="temporal.siete">
                          </div>
                          <div class="col-md-5 mb-3">
                            <label>Nombre</label>
                            <v-select :options="listaEmpleados" label="name" v-model="temporal.ocho"></v-select>
                          </div>
                        </div>
                        <hr>
                        <div class="form-row">
                          <div class="col-md-12">
                            <label>SUSPENSIÓN DEL PERMISO</label>
                          </div>
                          <div class="col-md-2 mb-3">
                            <label>Hora</label>
                            <input type="time" class="form-control" v-model="temporal.nueve">
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>Fecha</label>
                            <input type="date" class="form-control" v-model="temporal.diez">
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>&nbsp;</label>
                            <br>
                            <button type="button" class="btn btn-secondary" @click="Crear()"><i class="fas fa-plus"></i>&nbsp;Crear</button><!--Crear-->
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="form-group col-md-3">
                            <label><b>Nombre</b></label>
                          </div>
                          <div class="form-group col-md-2">
                            <label><b>Fecha</b></label>
                          </div>

                          <div class="form-group col-md-3">
                            <label><b>Nombre</b></label>
                          </div>

                          <div class="form-group col-md-2">
                            <label><b>Fecha</b></label>
                          </div>
                          <div class="form-group col-md-2">
                            <label><b>ACCIONES</b></label>
                          </div>
                        </div>
                        <li v-for="(vi, index) in once" class="list-group-item">
                          <div class="form-row">

                            <div class="form-group col-md-3">
                              <label>{{vi['cuatro'] != null ? vi['cuatro']['name'] : ''}}</label>
                            </div>
                            <div class="form-group col-md-2">
                              <label>{{vi['uno']}}</label>
                            </div>
                            <div class="form-group col-md-3">
                              <label>{{vi['ocho'] != null ? vi['ocho']['name'] : ''}}</label>
                            </div>
                            <div class="form-group col-md-2">
                              <label>{{vi['cinco']}}</label>
                            </div>
                            <div class="form-group col-md-2">
                              <a @click="deleteu(index)">
                                <span class="fas fa-trash" arial-hidden="true"></span>
                              </a>
                            </div>
                          </div>
                        </li>


                      </div>
                    </div>
                  </div>
                  <!--  -->
                  <!--  -->
                  <div class="card">
                    <div class="card-header" id="headingSeven">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                          7.CANCELACIÓN DEL TRABAJO
                        </button>
                      </h5>
                    </div>
                    <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-group row">
                          <label class="form-control-label col-md-6">Ha sido completado</label>
                          <div class="form-group col-2">
                            <input class="" type="checkbox" v-model="nueve.uno">
                            <label class="form-control-label"></label>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="form-control-label col-md-6">No ha sido iniciado </label>
                          <div class="form-group col-2">
                            <input class="" type="checkbox" v-model="nueve.dos">
                            <label class="form-control-label"></label>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="form-control-label col-md-6">Término de la jornada de trabajo.</label>
                          <div class="form-group col-2">
                            <input class="" type="checkbox" v-model="nueve.tres">
                            <label class="form-control-label"></label>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="form-control-label col-md-6">Suspendida o no iniciada por haberse realizado observaciones de seguridad. </label>
                          <div class="form-group col-2">
                            <input class="" type="checkbox" v-model="nueve.cuatro">
                            <label class="form-control-label"></label>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="form-control-label col-md-6">El lugar de trabajo ha quedado en condiciones de seguridad, orden y limpieza.</label>
                          <div class="form-group col-2">
                            <input class="" type="checkbox" v-model="nueve.cinco">
                            <label class="form-control-label"></label>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                  <!--  -->
                  <!--  -->
                  <div class="card">
                    <div class="card-header" id="headingOcho">
                      <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOcho" aria-expanded="false" aria-controls="collapseOcho">
                          8. TERMINACIÓN DE LOS TRABAJOS (Por el Resdidente)
                        </button>
                      </h5>
                    </div>
                    <div id="collapseOcho" class="collapse" aria-labelledby="headingOcho" data-parent="#accordion">
                      <div class="card-body">

                        <div class="form-row">
                          <div class="col-md-12 mb-3">
                            <label>CANCELACION DEL PERMISO (Trabajo completo)</label>
                          </div>
                          <div class="col-md-5 mb-3">
                            <label>Nombre</label>
                            <v-select :options="listaEmpleados" label="name" v-model="diez.uno" ></v-select>
                          </div>
                          <div class="col-md-2 mb-3">
                            <label>Hora</label>
                            <input type="time" class="form-control" v-model="diez.dos">
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>Fecha</label>
                            <input type="date" class="form-control" v-model="diez.tres">
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col-md-12 mb-3">
                            <label>CANCELACION DEL PERMISO (Trabajo no completo)</label>
                          </div>
                          <div class="col-md-5 mb-3">
                            <label>Nombre</label>
                            <v-select :options="listaEmpleados" label="name" v-model="diez.cuatro" ></v-select>
                          </div>
                          <div class="col-md-2 mb-3">
                            <label>Hora</label>
                            <input type="time" class="form-control" v-model="diez.cinco">
                          </div>
                          <div class="col-md-3 mb-3">
                            <label>Fecha</label>
                            <input type="date" class="form-control" v-model="diez.seis">
                          </div>
                        </div>


                      </div>
                    </div>
                  </div>
                  <!--  -->


                </div>


              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" @click="Cerrar()">
                  <i class="fas fa-window-close"></i>&nbsp;Cerrar
                </button>
                <button v-show="tipoAccion == 1" type="button" class="btn btn-secondary" @click="Guardar(1)">
                  Guardar
                </button>
                <button v-show="tipoAccion == 2" type="button" class="btn btn-secondary" @click="Guardar(0)">
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

export default {
  data(){
    return {
      tableData : [],
      columns : ['uno.id','uno.uno','uno.dos','uno.tres','descarga'],
      options : {
        headings: {
          'uno.id' :'ACCIONES',
          'uno.uno' : 'Folio',
          'uno.dos' : 'Asociado',
          'uno.tres' : 'Fecha Elaboración',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      id : 0,
      uno : {
        uno : '',
        dos : '',
        tres : '',
        cuatro : '',
        cinco : '',
        seis : '',
      },
      dos : {
        uno : '',
        dos : '',
        tres : '',
        cuatro : '',
        cinco : '',
        seis : '',
      },
      tres : {
        uno : '',
        dos : '',
        tres : '',
        cuatro : '',
      },
      cuatro : {
        uno : '',
        dos : '',
        tres : '',
        cuatro : '',
        cinco : '',
        seis : '',
        siete : '',
        ocho : '',
        nueve : '',
        diez : '',
        once : '',
        doce : '',
        trece : '',
      },
      cinco : {
        uno : false,
        dos : false,
        tres : false,
        cuatro : false,
        cinco : false,
        seis : '',
      },
      seis : {
        uno : false,
        dos : false,
        tres : false,
        cuatro :false,
        cinco : false,
        seis : false,
        siete : false,
        ocho : false,
      },
      siete : {
        uno : '',
      },
      ocho : {
        uno : '',
        dos : '',
        tres : '',
        cuatro : '',
        cinco : '',
        seis : '',
      },
      temporal : {
        uno : '',
        dos : '',
        tres : '',
        cuatro : '',
        cinco :'',
        seis : '',
        siete : '',
        ocho : '',
        nueve : '',
        diez : '',
      },
      nueve : {
        uno : '',
        dos : '',
        tres :'',
        cuatro : '',
        cinco : '',
      },
      diez : {
        uno : '',
        dos : '',
        tres : '',
        cuatro : '',
        cinco : '',
        seis : '',
      },
      listaEmpleados : [],
      once : [],

    }
  },
  methods : {
    getLista(){
      axios.get('get/permisos/seguridad').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    getData(){
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
    },

    abrirModal(modelo, accion, data = []){
      console.log(data);
      switch(modelo){
        case "analisis":
        {
          switch(accion){
            case 'registrar':
            {
              Utilerias.resetObject(this.uno);
              Utilerias.resetObject(this.dos);
              Utilerias.resetObject(this.tres);
              Utilerias.resetObject(this.cuatro);
              Utilerias.resetObject(this.cinco);
              Utilerias.resetObject(this.seis);
              Utilerias.resetObject(this.siete);
              Utilerias.resetObject(this.ocho);
              Utilerias.resetObject(this.nueve);
              Utilerias.resetObject(this.diez);
              Utilerias.resetObject(this.temporal);
              this.once = [];
              this.modal = 1;
              this.tituloModal = 'Registrar permiso';
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar':
            {
              // Utilerias.resetObject(this.uno);
              // Utilerias.resetObject(this.dos);
              // Utilerias.resetObject(this.tres);
              // Utilerias.resetObject(this.cuatro);
              // Utilerias.resetObject(this.cinco);
              // Utilerias.resetObject(this.seis);
              // Utilerias.resetObject(this.siete);
              // Utilerias.resetObject(this.ocho);
              // Utilerias.resetObject(this.nueve);
              // Utilerias.resetObject(this.diez);
              // Utilerias.resetObject(this.temporal);
              this.once = [];

              this.modal=1;
              this.tituloModal='Actualizar permiso';
              this.tipoAccion=2;
              this.uno = data.uno;
              this.dos = data.dos;
              this.tres = data.tres;
              this.cuatro = data.cuatro;
              this.cinco = data.cinco;
              this.seis = data.seis;
              this.siete = data.siete;
              this.ocho = data.ocho;
              this.ocho = {
                uno : {id : data.ocho.uno, name : data.ocho.residente},
                dos : data.ocho.dos,
                tres : {id : data.ocho.tres, name : data.ocho.supervisor},
                cuatro : this.ocho.cuatro,
                cinco : {id : data.ocho.cinco, name : data.ocho.shso},
                seis : data.ocho.seis,
              };
              this.nueve = data.nueve;
              // this.diez = data.diez;
              this.diez = {
                uno : {id : data.diez.uno, name : data.diez.e_uno},
                dos : data.diez.dos,
                tres : data.diez.tres,
                cuatro :  {id : data.diez.cuatro, name : data.diez.e_cuatro},
                cinco : data.diez.cinco,
                seis : data.diez.seis,
              };
              console.log(data.once,'once');
              // this.once = data.once;
              data.once.forEach((item, i) => {
                this.once.push(
                  {
                    uno : item.uno,
                    dos : item.dos,
                    tres : item.tres,
                    cuatro : {id : item.cuatro, name : item.e_cuatro},
                    cinco : item.cinco,
                    seis : item.seis,
                    siete : item.siete,
                    ocho : {id : item.ocho, name : item.e_ocho},
                    nueve : item.nueve,
                    diez : item.diez,
                  }
                );
              });
              console.log(this.once,'ocnce');

              // this.once =
              // {
              //   uno : data.once.uno,
              //   dos : data.once.dos,
              //   tres : data.once.tres,
              //   cuatro : {id : data.once.cuatro, name : data.once.e_cuatro},
              //   cinco : data.once.cinco,
              //   seis : data.once.seis,
              //   siete : data.once.siete,
              //   ocho : {id : data.once.ocho, name : data.once.e_ocho}
              //   nueve : data.once.nueve,
              //   diez : data.once.diez,
              // };

              this.id = data.uno.id;
              break;
            }
          }
        }
      }
    },

    Guardar(nuevo){

      axios({
        method : nuevo ? 'POST' : 'PUT',
        url : nuevo ? 'seguridad/permiso/general/guardar' : 'seguridad/permiso/general/actualizar',
        data : {
          uno : this.uno,
          dos : this.dos,
          tres : this.tres,
          cuatro : this.cuatro,
          cinco : this.cinco,
          seis : this.seis,
          siete : this.siete,
          ocho : this.ocho,
          nueve : this.nueve,
          diez : this.diez,
          once : this.once,
          id :  this.id,
        }
      }).then(response => {
        // console.log(response);
        this.modal = 0;
        this.getLista();
      }).catch(e => {
        console.error(e);
      });

    },

    Cerrar() {
      this.modal = 0;
    },

    Crear(){
      axios.post('abc',this.temporal).then(result => {
        this.once.push(
          {
            uno : result.data.uno,
            dos : result.data.dos,
            tres : result.data.tres,
            cuatro : result.data.cuatro,
            cinco : result.data.cinco,
            seis : result.data.seis,
            siete : result.data.siete,
            ocho : result.data.ocho,
            nueve : result.data.nueve,
            diez : result.data.diez,
          }
        );
        this.vaciado();
      }).catch(e => {
        console.error(e);
      });
      // this.once.push(this.temporal);
      // this.once.push(this.temporal);
      // console.log(this.once);
      // // setTimeout(() =>{
      // //   let me = this;
      //   this.temporal.uno = '';
      //   console.log(this.temporal);

      // // }, 1000);
      //   me.temporal.dos = '';
      //   me.temporal.tres = '';
      //   me.temporal.cuatro = '';
      //   me.temporal.cinco = '';
      //   me.temporal.seis = '';
      //   me.temporal.siete = '';
      //   me.temporal.ocho = '';
      //   me.temporal.nueve = '';
      //   me.temporal.diez = '';


    },

    vaciado(){
      Utilerias.resetObject(this.temporal);
    },

    deleteu(index){
      this.once.splice(index, 1);
    },

    llenarC(){
      if (this.dos.uno == 1) {
        this.cuatro.uno = '1';
        this.cuatro.dos = '1';
        this.cuatro.catorce = '1';
        this.cuatro.tres = '1';
        this.cuatro.cuatro = '1';
        this.cuatro.cinco = '2';
        this.cuatro.seis = '3';
        this.cuatro.siete = '1';
        this.cuatro.ocho = '1';
        this.cuatro.nueve = '1';
        this.cuatro.diez = '3';
        this.cuatro.once = '1';
        this.cuatro.doce = '1';
        this.cuatro.trece = '1';

        this.cinco.uno = true;
        this.cinco.dos = true;
        this.cinco.tres = true;
        this.cinco.cuatro = true;
        this.cinco.cinco = true;

        this.seis.uno = false;
        this.seis.dos = false;
        this.seis.tres = false;
        this.seis.cuatro = true;
        this.seis.cinco = true;
        this.seis.seis = true;
        this.seis.siete = true;
        this.seis.ocho = true;
      }
    },

    llenarE(){
      if (this.dos.cuatro == 1) {
        this.cuatro.uno = '1';
        this.cuatro.dos = '1';
        this.cuatro.catorce = '1';
        this.cuatro.tres = '1';
        this.cuatro.cuatro = '3';
        this.cuatro.cinco = '3';
        this.cuatro.seis = '1';
        this.cuatro.siete = '3';
        this.cuatro.ocho = '1';
        this.cuatro.nueve = '1';
        this.cuatro.diez = '1';
        this.cuatro.once = '3';
        this.cuatro.doce = '3';
        this.cuatro.trece = '3';

        this.cinco.uno = true;
        this.cinco.dos = true;
        this.cinco.tres = true;
        this.cinco.cuatro = true;
        this.cinco.cinco = true;

        this.seis.uno = false;
        this.seis.dos = false;
        this.seis.tres = false;
        this.seis.cuatro = true;
        this.seis.cinco = true;
        this.seis.seis = true;
        this.seis.siete = true;
        this.seis.ocho = true;
      }
    },

    descargar(data){
      window.open('seguridad/permiso/general/descargar/' + data, '_blank');

    },

    eliminar(id){
      Swal.fire({
        title: 'Esta seguro(a) de eliminar ?',
        text: "Esta opción no se podrá desahacer!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText : 'No',
      }).then(result => {
        if (result.value) {
          axios.get('seguridad/permiso/general/eliminar/' + id).then(response => {
            toastr.success('Eliminado Correctamente !!!');
            this.getLista();
          }).catch(e => {
            console.error(e);
          });
        }
      });
    }

  },
  mounted(){
    this.getData();
    this.getLista();
  },
}

</script>
