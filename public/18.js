webpackJsonp([18],{

/***/ 1262:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(8)
/* script */
var __vue_script__ = __webpack_require__(1263)
/* template */
var __vue_template__ = __webpack_require__(1264)
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
Component.options.__file = "resources/assets/js/components/Contraloria/PagosAutorizar/PagosAutorizar.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-cbb77902", Component.options)
  } else {
    hotAPI.reload("data-v-cbb77902", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 1263:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Herramientas_utilerias_js__ = __webpack_require__(561);
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


var config = __webpack_require__(562).call(this);
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {};
  },

  methods: {}
});

/***/ }),

/***/ 1264:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm._m(0)
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("main", { staticClass: "main" }, [
      _c("div", { staticClass: "container-fluid" }, [
        _c("div", { staticClass: "card" }, [
          _c("div", { staticClass: "card-header" }, [
            _c("i", { staticClass: "fa fa-align-justify" }),
            _vm._v(" Compras\n\n      ")
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "card-body" })
        ])
      ])
    ])
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-cbb77902", module.exports)
  }
}

/***/ }),

/***/ 561:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

/* harmony default export */ __webpack_exports__["a"] = ({
    scrollMeTo: function scrollMeTo(tv, refName) {
        var element = tv.$refs[refName];
        var top = element.offsetTop;
        window.scrollTo(0, top);
    },
    resetObject: function resetObject(obj) {
        for (var key in obj) {
            if (typeof obj[key] === 'string') obj[key] = '';else if (typeof obj[key] === 'number') obj[key] = 0;else if (_typeof(obj[key]) === undefined) obj[key] = null;else if (_typeof(obj[key]) === 'object') obj[key] = null;
        }
    },
    colorPalette: function colorPalette(tipo) {
        switch (tipo) {
            case 0:
                // Hichcharts
                return ["#434348", "#7cb5ec", "#90ed7d", "#f7a35c", "#8085e9", "#f15c80", "#e4d354", "#2b908f", "#f45b5b", "#91e8e1"];

            case 1:
                // Colors Belize (light flavor) SAP
                return ['#597fe2', '#e29159', '#59e27c', '#e2597c', '#e2dc59', '#9159e2', '#525DF4', '#BF399E', '#6C8893', '#EE6868', '#2F6497'];

            case 2:
                // Highcharts 3.x
                return ['#2f7ed8', '#0d233a', '#8bbc21', '#910000', '#1aadce', '#492970', '#f28f43', '#77a1e5', '#c42525', '#a6c96a'];

            case 3:
                // Palette 2
                return ['#003f5c', '#2f4b7c', '#665191', '#a05195', '#d45087', '#f95d6a', '#ff7c43', '#ffa600'];

            case 4:
                // Sequencial Palette
                return ['#004c6d', '#086081', '#147596', '#208ba9', '#2ea1bc', '#3eb8ce', '#50cfdf', '#63e7f0', '#79ffff'];
        }
    },
    getRandomColor: function getRandomColor() {
        // var letters = '0123456789ABCDEF';
        // var color = '#';

        // for (var i = 0; i < 6; i++) {
        //   color += letters[Math.floor(Math.random() * 16)];
        // }


        // return color;
        var o = Math.round,
            r = Math.random,
            s = 255;
        return 'rgba(' + o(r() * s) + ',' + o(r() * s) + ',' + o(r() * s) + ',' + r().toFixed(1) + ')';
    },
    setRandomColor: function setRandomColor() {
        var array_colors = [];
        for (var index = 1; index < 60; index++) {
            array_colors.push(this.getRandomColor());
        }
        return array_colors;
    },
    random_rgba: function random_rgba() {},
    getCRUD: function getCRUD(ruta) {
        var formData = new FormData();
        formData.append('ruta', ruta);
        formData.append('identificador', 1);
        var permisos = {
            Create: false,
            Read: false,
            Update: false,
            Delete: false,
            Download: false,
            Upload: false
        };
        axios.post('/CRUD', formData).then(function (response) {
            for (var i = 0; i < response.data.length; i++) {
                if (response.data[i].control_id == 1) {
                    permisos.Create = true;
                }
                if (response.data[i].control_id == 2) {
                    permisos.Read = true;
                }
                if (response.data[i].control_id == 3) {
                    permisos.Update = true;
                }
                if (response.data[i].control_id == 4) {
                    permisos.Delete = true;
                }
                if (response.data[i].control_id == 5) {
                    permisos.Download = true;
                }
                if (response.data[i].control_id == 6) {
                    permisos.Upload = true;
                }
            }
        }).catch(function (error) {
            console.log(error);
        });
        return permisos;
    },
    descargarArc: function descargarArc(ruta_uno, ruta_dos, archivo) {
        var me = this;
        axios({
            url: ruta_uno + archivo,
            method: 'GET',
            responseType: 'blob' // importante
        }).then(function (response) {
            var url = window.URL.createObjectURL(new Blob([response.data]));
            var link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
            document.body.appendChild(link);
            link.click();
            //--Llama el metodo para borrar el archivo local una ves descargado--//
            axios.delete(ruta_dos + archivo).then(function (response) {}).catch(function (error) {
                console.log(error);
            });
            //--fin del metodo borrar--//
        });
    }
});

/***/ }),

/***/ 562:
/***/ (function(module, exports) {

module.exports = function () {
  return {
    dateColumns: [],
    listColumns: {},
    datepickerOptions: {
      locale: {
        cancelLabel: 'Clear'
      }
    },
    datepickerPerColumnOptions: {},
    initialPage: 1,
    perPage: 10,
    perPageValues: [10, 25, 50, 100],
    groupBy: false,
    collapseGroups: false,
    destroyEventBus: false,
    sendEmptyFilters: false,
    params: {},
    sortable: true,
    filterable: true,
    groupMeta: [],
    initFilters: {},
    customFilters: [],
    templates: {},
    debounce: 250,
    dateFormat: "DD/MM/YYYY",
    dateFormatPerColumn: {},
    toMomentFormat: false,
    skin: 'table table-striped table-bordered table-hover table-sm',
    skinBusqueda: 'table table-striped table-bordered table-hover table-sm busqueda',
    columnsDisplay: {},
    columnsDropdown: false,
    texts: {
      count: "Mostrando del {from} al  {to} de {count} registros|{count} registros|Un registro",
      first: 'Primero',
      last: 'Ultimo',
      filter: "Buscar:",
      filterPlaceholder: "Buscar...",
      limit: "Registros:",
      page: "Pagina:",
      noResults: "No se encontraron registros",
      filterBy: "Filtrar por {column}",
      loading: 'Cargando...',
      defaultOption: 'Seleccionar {column}',
      columns: 'Columnas'
    },
    sortIcon: {
      base: 'fa', up: 'fa-chevron-up', down: 'fa-chevron-down', is: 'fa-sort'
    },
    sortingAlgorithm: function sortingAlgorithm(data, column) {
      return data.sort(this.getSortFn(column));
    },

    customSorting: {},
    multiSorting: {},
    clientMultiSorting: true,
    serverMultiSorting: false,
    filterByColumn: false,
    highlightMatches: false,
    orderBy: false,
    descOrderColumns: [],
    footerHeadings: false,
    headings: {},
    headingsTooltips: {},
    pagination: {
      dropdown: false,
      chunk: 10,
      edge: false,
      align: 'center',
      nav: 'fixed'
    },
    childRow: false,
    childRowTogglerFirst: true,
    uniqueKey: 'id',
    requestFunction: false,
    requestAdapter: function requestAdapter(data) {
      return data;
    },
    responseAdapter: function responseAdapter(resp) {

      var data = this.getResponseData(resp);

      return {
        data: data.data,
        count: data.count
      };
    },
    requestKeys: {
      query: 'query',
      limit: 'limit',
      orderBy: 'orderBy',
      ascending: 'ascending',
      page: 'page',
      byColumn: 'byColumn'
    },
    rowClassCallback: false,
    preserveState: false,
    saveState: false,
    storage: 'local',
    columnsClasses: {},
    columnCondicion: [{
      id: 1,
      text: 'Activo'
    }, {
      id: 0,
      text: 'Desactivado'
    }]
  };
};

/***/ })

});