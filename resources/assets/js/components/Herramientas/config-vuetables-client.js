module.exports = function() {
    return {
     dateColumns:[],
     listColumns:{},
     datepickerOptions:{
      locale: {
        cancelLabel:'Clear'
      }
    },
    datepickerPerColumnOptions:{},
    initialPage:1,
    perPage:10,
    perPageValues:[10,25,50,100],
    groupBy:false,
    collapseGroups:false,
    destroyEventBus:false,
    sendEmptyFilters:false,
    params:{},
    sortable:true,
    filterable:true,
    groupMeta:[],
    initFilters:{},
    customFilters:[],
    templates:{},
    debounce:250,
    dateFormat:"DD/MM/YYYY",
    dateFormatPerColumn:{},  
    toMomentFormat:false,
    skin:'table table-striped table-bordered table-hover table-sm',
    skinBusqueda:'table table-striped table-bordered table-hover table-sm busqueda',
    columnsDisplay: {},
    columnsDropdown: false,  
    texts:{
        count:"Mostrando del {from} al  {to} de {count} registros|{count} registros|Un registro",
        first:'Primero',
        last:'Ultimo',
        filter:"Buscar:",
        filterPlaceholder:"Buscar...",
        limit:"Registros:",
        page:"Pagina:",
        noResults:"No se encontraron registros",
        filterBy:"Filtrar por {column}",
        loading:'Cargando...',
        defaultOption:'Seleccionar {column}',
        columns:'Columnas'
    },
   sortIcon:{
        base:'fa', up:'fa-chevron-up', down:'fa-chevron-down', is:'fa-sort' 
    },
  sortingAlgorithm(data, column) { 
    return data.sort(this.getSortFn(column));
  },
  customSorting:{},
  multiSorting:{},
  clientMultiSorting:true,
  serverMultiSorting:false,
  filterByColumn:false,
  highlightMatches:false,
  orderBy:false,
  descOrderColumns:[],
  footerHeadings:false,
  headings:{},
  headingsTooltips:{},
  pagination: {
    dropdown:false,
    chunk:10,
    edge:false,
    align:'center',
    nav:'fixed'
  },
  childRow: false,
  childRowTogglerFirst:true,
  uniqueKey:'id',
  requestFunction:false,
  requestAdapter: function(data) {
      return data;
  },
  responseAdapter: function(resp) {
  
    var data = this.getResponseData(resp);
  
    return {
      data: data.data,
      count: data.count
    }
  },
  requestKeys:{
   query:'query',
   limit:'limit',
   orderBy:'orderBy',
   ascending:'ascending',
   page:'page',
   byColumn:'byColumn'
  },
  rowClassCallback: false,
  preserveState: false,
  saveState: false,
  storage:'local',
  columnsClasses:{},
  columnCondicion: [{
        id: 1,
        text: 'Activo'
    },
    {
        id: 0,
        text: 'Desactivado'
    }
    ],
  }
  }
  