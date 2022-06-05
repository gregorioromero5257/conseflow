webpackJsonp([21],{

/***/ 1458:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(8)
/* script */
var __vue_script__ = __webpack_require__(1459)
/* template */
var __vue_template__ = __webpack_require__(1460)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/js/components/Operaciones/CajaChica/RequisicionesAutorizar.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5bc0c268", Component.options)
  } else {
    hotAPI.reload("data-v-5bc0c268", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 1459:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Herramientas_utilerias_js__ = __webpack_require__(610);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


var config = __webpack_require__(611).call(this);
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      isLoading: false,
      solicitudes: [],
      detalle: false,
      nuevo: null,
      solicitud: [],
      detalles_ver: false,
      detalles_solicitudes: null,
      tipo_cambio: 0,
      moneda: 0,
      columns: ['id', 'folio', 'nombrep', 'fecha_solicitud', 'nombre_solicita', 'descripcion_uso', 'descargar', 'condicion'],
      dataTable: [],
      options: {
        headings: {
          'id': 'Acciones',
          'articulo_descripcion': 'Artículo',
          'nombrep': 'Proyecto',
          'nombre_solicita': 'Empleado que solicita',
          'descripcion_uso': 'Uso',
          'condicion': 'Validar'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
      },
      dataTableDetalle: [],
      columnsDetalle: ['req.descripcion', 'req.cantidades', 'req.cantidad_almacen', 'req.cantidad_compra', 'req.unidad_articulo', 'req.comentario_partida', 'req.frequerido'],
      optionsDetalle: {
        headings: {
          'req.descripcion': 'Articulo',
          'req.cantidades': 'Cantidad solicitada',
          'req.cantidad_compra': 'Cantidad a comprar',
          'req.cantidad_almacen': 'Cantidad en almacén',
          'req.unidad_articulo': 'Unidad',
          'req.comentario_partida': 'Comentario',
          'req.frequerido': 'Fecha requerida',
          'req.id': ''
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
      }
    };
  },

  methods: {
    getData: function getData() {
      this.isLoading = true;
      var me = this;
      axios.get('/requisicionesporautorizar').then(function (response) {
        me.solicitudes = response.data;
        me.dataTable = response.data;
        me.isLoading = false;
      }).catch(function (error) {
        console.log(error);
      });
    },
    cargarequisicion: function cargarequisicion() {
      this.getData();
    },
    cargardetalle: function cargardetalle(id) {
      this.isLoading = true;
      this.detalles_ver = true;
      this.detalle = true;
      var me = this;
      axios.get('/consultadashp/' + id).then(function (response) {
        me.detalles_solicitudes = response.data;
        me.dataTableDetalle = response.data;
        me.isLoading = false;
      }).catch(function (error) {
        console.log(error);
      });
    },
    autorizar: function autorizar(estado, id) {
      this.isLoading = true;
      var me = this;
      var cadena = ['Almacén', 'Supervisor'];
      var cadenaid = [12, 14];
      if (estado == 3) {
        swal({
          title: estado != 0 ? 'Esta seguro(a) autorizar la requisición?' : 'Esta seguro(a) de rechazar la requisición',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#4dbd74',
          cancelButtonColor: '#f86c6b',
          confirmButtonText: 'Aceptar!',
          cancelButtonText: 'Cancelar',
          reverseButtons: true
        }).then(function (result) {
          if (result.value) {
            axios.post('/autorizarequisicionproyectos', {
              id: id,
              estado: estado
            }).then(function (response) {
              me.isLoading = false;
              if (response.data == 3) {
                toastr.success('Correcto', 'Requisicion autorizada !!!');
              } else if (response.data == 0) {
                toastr.warning('Atención', 'Requisicion no autorizada !!!');
              }
              me.getData();
              me.isLoading = false;
            }).catch(function (error) {
              console.error(error);
            });
          } else {
            me.isLoading = false;
          }
        });
      } else {

        swal({
          title: 'Motivo del rechazo de la requisición',
          input: 'textarea',
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          confirmButtonText: 'Continuar',
          showLoaderOnConfirm: true
        }).then(function (result) {
          if (result.value) {

            axios.post('enviarcomentariorechazorequi', {
              id: id,
              data: result.value
            }).then(function (response) {
              console.log('Guardado correctamente');
            });
            swal({
              title: 'Direccionar a...',
              input: 'select',
              inputOptions: cadena,
              inputPlaceholder: 'Seleccionar Estado',
              confirmButtonText: 'Continuar <i class="fa fa-arrow-right></i>',
              showCancelButton: true,
              customClass: 'form-check form-check-inline',
              inputValidator: function inputValidator(result) {
                return !result && 'Se Requiere Elegir un Elemento';
              }
            }).then(function (result) {
              if (result.value) {
                axios.post('/autorizarequisicionproyectos', {
                  id: id,
                  estado: cadenaid[result.value]
                }).then(function (response) {
                  me.isLoading = false;
                  if (response == 3) {
                    toastr.success('Correcto', 'Requisición recibida correctamente');
                  } else {
                    toastr.warning('Atención', 'Requisición no recibida');
                  }
                  me.getData();
                }).catch(function (error) {
                  console.error(error);
                });
              } else {
                me.isLoading = false;
              }
            }).catch(function (e) {
              console.error(e);
            });
          }
          console.log(result);
          me.isLoading = false;
        });
      }
    },
    maestro: function maestro() {
      this.detalles_ver = false;
      this.detalle = false;
      this.detalles_solicitudes = null;
    },


    /**
    * [descargar description]
    * @param  {[type]} data [description]
    * @return {[type]}      [description]
    */
    descargar: function descargar(data) {
      window.open('descargar-requisicion/' + data.id, '_blank');
    },
    guardarcorrecion: function guardarcorrecion(e, data) {
      console.log(e);
      console.log(data);
      axios.post('agregar/correciones/partidas', {
        requisicion_id: data.id,
        pda: data.pda,
        articulo_servicio: data.articulo_id != null ? 1 : 0,
        articulo_servicio_id: data.articulo_id != null ? data.articulo_id : data.servicio_id,
        comentario: e.target.value
      }).then(function (response) {
        console.log(response);
      }).catch(function (e) {
        console.error(e);
      });
    }
  },
  mounted: function mounted() {}
});

/***/ }),

/***/ 1460:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c(
      "div",
      { staticClass: "card border-dark" },
      [
        _c("vue-element-loading", { attrs: { active: _vm.isLoading } }),
        _vm._v(" "),
        _c("div", { staticClass: "card-header bg-dark text-white" }, [
          _c("i", { staticClass: "fa fa-align-justify" }),
          _vm._v(" Requisiciones por autorizar:\n        "),
          _vm.detalles_ver
            ? _c(
                "button",
                {
                  staticClass: "btn btn-secondary float-sm-right",
                  attrs: { type: "button" },
                  on: {
                    click: function($event) {
                      return _vm.maestro()
                    }
                  }
                },
                [
                  _c("i", { staticClass: "icon-arrow-left" }),
                  _vm._v(" Atras\n        ")
                ]
              )
            : _vm._e()
        ]),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "card-body" },
          [
            _c("v-client-table", {
              directives: [
                {
                  name: "show",
                  rawName: "v-show",
                  value: !_vm.detalle,
                  expression: "!detalle"
                }
              ],
              ref: "myTable",
              attrs: {
                columns: _vm.columns,
                data: _vm.dataTable,
                options: _vm.options
              },
              scopedSlots: _vm._u(
                [
                  {
                    key: "id",
                    fn: function(props) {
                      return [
                        _c(
                          "div",
                          {
                            staticClass: "btn-group",
                            attrs: {
                              role: "group",
                              "aria-label": "Button group with nested dropdown"
                            }
                          },
                          [
                            _c(
                              "div",
                              {
                                staticClass: "btn-group dropup",
                                attrs: { role: "group" }
                              },
                              [
                                _c(
                                  "button",
                                  {
                                    staticClass:
                                      "btn btn-outline-dark dropdown-toggle",
                                    attrs: {
                                      id: "btnGroupDrop1",
                                      type: "button",
                                      "data-toggle": "dropdown",
                                      "aria-haspopup": "true",
                                      "aria-expanded": "false"
                                    }
                                  },
                                  [
                                    _c("i", {
                                      staticClass: "fas fa-grip-horizontal"
                                    }),
                                    _vm._v(" Acciones\n                ")
                                  ]
                                ),
                                _vm._v(" "),
                                _c(
                                  "div",
                                  {
                                    staticClass: "dropdown-menu",
                                    attrs: {
                                      "aria-labelledby": "btnGroupDrop1"
                                    }
                                  },
                                  [
                                    _c(
                                      "button",
                                      {
                                        staticClass: "dropdown-item",
                                        attrs: { type: "button" },
                                        on: {
                                          click: function($event) {
                                            return _vm.cargardetalle(
                                              props.row.id
                                            )
                                          }
                                        }
                                      },
                                      [
                                        _c("i", { staticClass: "fas fa-eye" }),
                                        _vm._v(
                                          "  Ver partidas\n                  "
                                        )
                                      ]
                                    )
                                  ]
                                )
                              ]
                            )
                          ]
                        )
                      ]
                    }
                  },
                  {
                    key: "descargar",
                    fn: function(props) {
                      return props.row.formato_requisiciones == null ||
                        props.row.formato_requisiciones == ""
                        ? [
                            _c(
                              "button",
                              {
                                staticClass: "btn btn-outline-dark",
                                on: {
                                  click: function($event) {
                                    return _vm.descargar(props.row)
                                  }
                                }
                              },
                              [
                                _c("i", { staticClass: "fas fa-file-pdf" }),
                                _vm._v(" "),
                                _c("i", { staticClass: "fas fa-download" })
                              ]
                            )
                          ]
                        : undefined
                    }
                  },
                  {
                    key: "condicion",
                    fn: function(props) {
                      return [
                        _c(
                          "button",
                          {
                            staticClass: "btn btn-sm btn-success",
                            on: {
                              click: function($event) {
                                return _vm.autorizar(3, props.row.id)
                              }
                            }
                          },
                          [
                            _c("i", { staticClass: "far fa-check-square" }),
                            _vm._v("  Si  ")
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "button",
                          {
                            directives: [
                              {
                                name: "show",
                                rawName: "v-show",
                                value: props.row.condicion == 1,
                                expression: "props.row.condicion == 1"
                              }
                            ],
                            staticClass: "btn btn-sm btn-danger",
                            on: {
                              click: function($event) {
                                return _vm.autorizar(0, props.row.id)
                              }
                            }
                          },
                          [
                            _c("i", { staticClass: "fas fa-window-close" }),
                            _vm._v("  No")
                          ]
                        )
                      ]
                    }
                  }
                ],
                null,
                true
              )
            }),
            _vm._v(" "),
            _c(
              "div",
              {
                directives: [
                  {
                    name: "show",
                    rawName: "v-show",
                    value: _vm.detalles_ver,
                    expression: "detalles_ver"
                  }
                ]
              },
              [
                _c("v-client-table", {
                  attrs: {
                    data: _vm.dataTableDetalle,
                    options: _vm.optionsDetalle,
                    columns: _vm.columnsDetalle
                  },
                  scopedSlots: _vm._u([
                    {
                      key: "req.id",
                      fn: function(props) {
                        return undefined
                      }
                    },
                    {
                      key: "req.descripcion",
                      fn: function(props) {
                        return [
                          _c("textarea", {
                            attrs: { name: "name", rows: "6", cols: "40" },
                            domProps: {
                              value:
                                props.row.correccion != null
                                  ? props.row.correccion.comentario
                                  : props.row.req.descripcion
                            },
                            on: {
                              keyup: function($event) {
                                if (
                                  !$event.type.indexOf("key") &&
                                  _vm._k(
                                    $event.keyCode,
                                    "enter",
                                    13,
                                    $event.key,
                                    "Enter"
                                  )
                                ) {
                                  return null
                                }
                                return _vm.guardarcorrecion(
                                  $event,
                                  props.row.req
                                )
                              }
                            }
                          })
                        ]
                      }
                    }
                  ])
                })
              ],
              1
            )
          ],
          1
        )
      ],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-5bc0c268", module.exports)
  }
}

/***/ }),

/***/ 1461:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(8)
/* script */
var __vue_script__ = __webpack_require__(1462)
/* template */
var __vue_template__ = __webpack_require__(1463)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/js/components/Operaciones/CajaChica/RequisicionesValidar.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-16583118", Component.options)
  } else {
    hotAPI.reload("data-v-16583118", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 1462:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Herramientas_utilerias_js__ = __webpack_require__(610);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


var config = __webpack_require__(611).call(this);
var ModalDocumentos = function ModalDocumentos(r) {
  return __webpack_require__.e/* require.ensure */(3).then((function () {
    return r(__webpack_require__(!(function webpackMissingModule() { var e = new Error("Cannot find module \"./ModalDocumentos.vue\""); e.code = 'MODULE_NOT_FOUND'; throw e; }())));
  }).bind(null, __webpack_require__)).catch(__webpack_require__.oe);
};

/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      columns: ['id', 'folio', 'nombrep', 'fecha_solicitud', 'nombre_solicita', 'descripcion_uso', 'condicion'],
      dataTable: [],
      options: {
        headings: {
          'id': 'Acciones',
          'articulo_descripcion': 'Artículo',
          'nombrep': 'Proyecto',
          'nombre_solicita': 'Empleado que solicita',
          'descripcion_uso': 'Uso',
          'condicion': 'Validar'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
      },
      modaldocumentos: false,
      isLoading: false,
      solicitudes: [],
      detalle: false,
      nuevo: null,
      solicitud: [],
      detalles_ver: false,
      detalles_solicitudes: null,
      tipo_cambio: 0,
      moneda: 0,
      dataTableDetalle: [],
      columnsDetalle: ['req.descripcion', 'req.cantidades', 'req.unidad_articulo', 'req.comentario_partida', 'req.frequerido', 'req.id'],
      optionsDetalle: {
        headings: {
          'req.descripcion': 'Articulo',
          'req.cantidades': 'Cantidad',
          'req.unidad_articulo': 'Unidad',
          'req.comentario_partida': 'Comentario',
          'req.frequerido': 'Fecha requerida',
          'req.id': ''
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
      }
    };
  },

  components: {
    'modaldocumentos': ModalDocumentos
  },
  methods: {
    emitirEventoHijo: function emitirEventoHijo() {
      this.$emit('validarequisicion:change');
    },
    getData: function getData() {
      this.isLoading = true;
      var me = this;
      axios.get('/requisicionesporvalidar').then(function (response) {
        me.solicitudes = response.data;
        me.dataTable = response.data;
        me.isLoading = false;
      }).catch(function (error) {
        console.log(error);
      });
    },
    cargarequisicion: function cargarequisicion() {
      this.getData();
    },
    cargardetalle: function cargardetalle(id) {
      this.isLoading = true;
      this.detalles_ver = true;
      this.detalle = true;
      var me = this;
      axios.get('/consultadashp/' + id).then(function (response) {
        me.detalles_solicitudes = response.data;
        me.dataTableDetalle = response.data;
        me.isLoading = false;
      }).catch(function (error) {
        console.log(error);
      });
    },
    autorizar: function autorizar(estado, id) {
      var _this = this;

      swal({
        title: estado != 0 ? 'Esta seguro(a) validar la requisición?' : 'Esta seguro(a) de rechazar la requisición',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4dbd74',
        cancelButtonColor: '#f86c6b',
        confirmButtonText: 'Aceptar!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
      }).then(function (result) {
        if (result.value) {
          _this.isLoading = true;
          var me = _this;
          axios.post('/autorizarequisicionproyectos', {
            id: id,
            estado: estado
          }).then(function (response) {
            me.emitirEventoHijo();
            if (response.data == 8) {
              toastr.success('Correcto', 'Requisicion validada !!!');
            } else if (response.data == 0) {
              toastr.warning('Atención', 'Requisicion no validada !!!');
            }
            me.getData();
            me.isLoading = false;
          }).catch(function (error) {
            console.error(error);
          });
        }
      });
    },
    maestro: function maestro() {
      this.detalles_ver = false;
      this.detalle = false;
      this.detalles_solicitudes = null;
    },
    verdocumentos: function verdocumentos(req, art, serv) {
      this.modaldocumentos = true;
      var childModalDocumentos = this.$refs.modaldocumentos;
      childModalDocumentos.abrirModal(req, art, serv);
    }
  },
  mounted: function mounted() {}
});

/***/ }),

/***/ 1463:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "card border-dark" },
    [
      _c("vue-element-loading", { attrs: { active: _vm.isLoading } }),
      _vm._v(" "),
      _c("div", { staticClass: "card-header bg-dark text-white" }, [
        _c("i", { staticClass: "fa fa-align-justify" }),
        _vm._v(" Requisiciones por validar:\n    "),
        _vm.detalles_ver
          ? _c(
              "button",
              {
                staticClass: "btn btn-secondary float-sm-right",
                attrs: { type: "button" },
                on: {
                  click: function($event) {
                    return _vm.maestro()
                  }
                }
              },
              [
                _c("i", { staticClass: "icon-arrow-left" }),
                _vm._v(" Atras\n    ")
              ]
            )
          : _vm._e()
      ]),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "card-body" },
        [
          _c("v-client-table", {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: !_vm.detalle,
                expression: "!detalle"
              }
            ],
            ref: "myTable",
            attrs: {
              columns: _vm.columns,
              data: _vm.dataTable,
              options: _vm.options
            },
            scopedSlots: _vm._u([
              {
                key: "id",
                fn: function(props) {
                  return [
                    _c(
                      "div",
                      {
                        staticClass: "btn-group",
                        attrs: {
                          role: "group",
                          "aria-label": "Button group with nested dropdown"
                        }
                      },
                      [
                        _c(
                          "div",
                          {
                            staticClass: "btn-group dropup",
                            attrs: { role: "group" }
                          },
                          [
                            _c(
                              "button",
                              {
                                staticClass:
                                  "btn btn-outline-dark dropdown-toggle",
                                attrs: {
                                  id: "btnGroupDrop1",
                                  type: "button",
                                  "data-toggle": "dropdown",
                                  "aria-haspopup": "true",
                                  "aria-expanded": "false"
                                }
                              },
                              [
                                _c("i", {
                                  staticClass: "fas fa-grip-horizontal"
                                }),
                                _vm._v(" Acciones\n            ")
                              ]
                            ),
                            _vm._v(" "),
                            _c(
                              "div",
                              {
                                staticClass: "dropdown-menu",
                                attrs: { "aria-labelledby": "btnGroupDrop1" }
                              },
                              [
                                _c(
                                  "button",
                                  {
                                    staticClass: "dropdown-item",
                                    attrs: { type: "button" },
                                    on: {
                                      click: function($event) {
                                        return _vm.cargardetalle(props.row.id)
                                      }
                                    }
                                  },
                                  [
                                    _c("i", { staticClass: "fas fa-eye" }),
                                    _vm._v("  Ver partidas\n              ")
                                  ]
                                )
                              ]
                            )
                          ]
                        )
                      ]
                    )
                  ]
                }
              },
              {
                key: "condicion",
                fn: function(props) {
                  return [
                    _c(
                      "button",
                      {
                        staticClass: "btn btn-sm btn-success",
                        on: {
                          click: function($event) {
                            return _vm.autorizar(2, props.row.id)
                          }
                        }
                      },
                      [
                        _c("i", { staticClass: "far fa-check-square" }),
                        _vm._v("  Si  ")
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "button",
                      {
                        directives: [
                          {
                            name: "show",
                            rawName: "v-show",
                            value: props.row.condicion == 1,
                            expression: "props.row.condicion == 1"
                          }
                        ],
                        staticClass: "btn btn-sm btn-danger",
                        on: {
                          click: function($event) {
                            return _vm.autorizar(0, props.row.id)
                          }
                        }
                      },
                      [
                        _c("i", { staticClass: "fas fa-window-close" }),
                        _vm._v("  No")
                      ]
                    )
                  ]
                }
              }
            ])
          }),
          _vm._v(" "),
          _c(
            "div",
            {
              directives: [
                {
                  name: "show",
                  rawName: "v-show",
                  value: _vm.detalles_ver,
                  expression: "detalles_ver"
                }
              ]
            },
            [
              _c("v-client-table", {
                attrs: {
                  data: _vm.dataTableDetalle,
                  options: _vm.optionsDetalle,
                  columns: _vm.columnsDetalle
                },
                scopedSlots: _vm._u([
                  {
                    key: "req.id",
                    fn: function(props) {
                      return [
                        _c(
                          "button",
                          {
                            staticClass: "btn btn-sm btn-danger",
                            on: {
                              click: function($event) {
                                return _vm.verdocumentos(
                                  props.row.req.id,
                                  props.row.req.articulo_id,
                                  props.row.req.servicio_id
                                )
                              }
                            }
                          },
                          [
                            _c("i", { staticClass: "fas fa-eye" }),
                            _vm._v(" Ver documentos")
                          ]
                        )
                      ]
                    }
                  }
                ])
              })
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        {
          directives: [
            {
              name: "show",
              rawName: "v-show",
              value: _vm.modaldocumentos,
              expression: "modaldocumentos"
            }
          ]
        },
        [_c("modaldocumentos", { ref: "modaldocumentos" })],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-16583118", module.exports)
  }
}

/***/ })

});