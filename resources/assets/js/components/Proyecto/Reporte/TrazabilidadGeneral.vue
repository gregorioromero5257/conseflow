<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          Trazabilidad
        </div>
        <div class="card-body">
          <div class="form-group row">
            <label class="col-md-1 form-control-label" for="tipo_partida"><br>Proyecto</label>
            <div class="col-md-4">
              <label>&nbsp;</label>
              <v-select id="proyectos" label="name"
              :options="listaProyectos" v-model="proyecto">
            </v-select>
          </div>
          <!-- <label class="col-md-1 form-control-label" for="tipo_partida"><br>Proveedor</label>
          <div class="col-md-4">
          <label>&nbsp;</label>
          <v-select multiple id="proyectos" label="name"
          :options="listaProveedores" v-model="proveedor" >
        </v-select>
      </div> -->
      <div class="col-md-1"><br><button class="btn btn-dark" @click="listarCompras()">Buscar</button></div>
      <div class="col-md-2">

      </div>

      <!-- <div class="col-md-1"><br><button class="btn btn-success" @click="generarExcel()">Exportar</button></div> -->
      <!-- {{objeto}} -->
    </div>
    <div v-show="ver == 1">
      {{inputd}}
      <br>
      {{totolss}}
      <br>
      {{total_ope}}
      <br>
      {{total_final}}
      <br>
      {{total_io}}
    </div>

    <table class="table table-bordered table-sm">
      <tr>
        <td colspan="6" > <h6>{{proyecto.name}}</h6> </td>
      </tr>
      <tr>
        <th width="18%" class="table-active">Total USD</th>
        <th width="18%" class="table-active">Tipo de cambio</th>
        <th width="18%" class="table-active">Tipo USD/MNX</th>
        <th width="18%" class="table-active">Total MNX</th>
        <th width="18%" class="table-active">Total OC</th>
        <th width="10%" class="table-active"></th>
      </tr>
      <template v-if="PermisosCRUD.Create">
      <tr>
        <td >{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(parseFloat(tableData['usd']).toFixed(2))}}</td>
        <td >
          <input type="text" class="form-control" v-model="input">
        </td>
        <td >{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_usd_mnx).toFixed(2))}}</td>
        <td >{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(parseFloat(tableData['mnx']).toFixed(2))}}</td>
        <td ><b>{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((totales).toFixed(2))}}</b></td>
        <td></td>
      </tr>
      <tr>
        <td style="text-align: right;"><b>Ordenes de compra</b> </td>
        <td>  &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((totales).toFixed(2))}}</td>
        <td colspan="4"></td>
      </tr>
      <tr>
        <td style="text-align: right;"><b>Ordenes de compra sin IVA</b> </td>
        <td>  &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((totales/1.16).toFixed(2))}}</td>
        <td colspan="4"></td>
      </tr>
      <tr>
        <td style="text-align: right;"><b>Mano de obra</b> </td>
        <td>  &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_mano_obra * 1.56).toFixed(2))}}</td>
        <td colspan="4"></td>
      </tr>
      <tr>
        <td colspan="4" style="text-align: right;"><b>Viaticos</b> </td>
        <td>  &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_viaticos).toFixed(2))}}</td>
        <td colspan="4"></td>
      </tr>
      <tr>
        <td colspan="4"></td>
        <td  style="text-align: right;"><b>Total Operaciones</b> </td>
        <td> <b>&nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_operaciones).toFixed(2))}}</b> </td>
      </tr>
    </template>

    <template v-if="PermisosCRUD.Read">
      <tr>
        <td colspan="4" style="text-align: right;"><b>Sueldo Administrativos</b> </td>
        <td>  &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_admin *  1.56).toFixed(2))}}</td>
      </tr>
      <tr>
        <td colspan="4" style="text-align: right;"><b>Sueldo sin proyecto</b> </td>
        <td> &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_sin_pro * 1.56).toFixed(2))}}</td>
      </tr>
      <tr>
        <td colspan="4"></td>
        <td style="text-align: right;"><b>Total Indirectos Oficina</b> </td>
        <td><b>&nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_indirectos_oficina).toFixed(2))}}</b> </td>
      </tr>
    </template>

      <template v-if="PermisosCRUD.Update">
      <tr>
        <td colspan="4" style="text-align: right;"><b>Gastos</b> </td>
        <td>  &nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((total_gastos))}}</td>
      </tr>
      <tr>
        <td colspan="4"></td>
        <td style="text-align: right;"><b>Total General</b> </td>
        <td><b>&nbsp;{{new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format((resultado_final).toFixed(2))}}</b> </td>
      </tr>
    </template>
    </table>


  </div>
</div>

<div class="card">
  <div class="card-header">
    <button type="button" v-if="encabezado == 2" @click="maestro()" class="btn btn-secondary float-sm-right">
      <i class="fas fa-arrow-left"></i>&nbsp;Atras
    </button>
  </div>
  <div class="card-body">
    <template v-if="encabezado == 1">
      <v-server-table :options="options" :columns="columns" :url="'compras/busqueda/'+objeto">
        <template slot="descargar" slot-scope="props">
         <button class="btn btn-outline-dark" @click="cargardetalle(props.row)" >
            <i class="fas fa-list"></i>&nbsp;Partidas
          </button>
        </template>
        <template slot="format" slot-scope="props">
          <template v-if="props.row.condicion == 1 || props.row.condicion == 2 ">
            <button class="btn btn-outline-dark" @click="descargar(props.row)" >
              <i class="fas fa-file-pdf"></i>&nbsp;&nbsp;<i class="fas fa-download"></i>
            </button>
          </template>
        </template>

        <template slot="estado_id" slot-scope="props" >
          <template v-if="props.row.estado_id == 3">
            <span class="btn btn-outline-danger">No programado</span>
          </template>
          <template v-if="props.row.estado_id == 2">
            <span class="btn btn-outline-warning">Programada</span>
          </template>
          <template v-if="props.row.estado_id == 1">
            <span class="btn btn-outline-success">Pagado</span>
          </template>
        </template>
        <template slot="moneda" slot-scope="props">
          <template v-if="props.row.moneda == 1">
            <span class="btn btn-outline-success">USD</span>
          </template>
          <template v-if="props.row.moneda == 2">
            <span class="btn btn-outline-success">MXN</span>
          </template>
          <template v-if="props.row.moneda == 3">
            <span class="btn btn-outline-success">EUR</span>
          </template>
        </template>
      </v-server-table>
    </template>
    <template v-if="encabezado == 2">
      <template v-if="wpartidas">
        <v-client-table :options="optionscompras" :columns="columnscompras" :data="tableDataPartidasCompras">
        </v-client-table>
      </template>
      <template v-if="wpartidasdos">
        <v-client-table :options="optionscomprasdos" :columns="columnscomprasdos" :data="tableDataPartidasComprasdos">
        </v-client-table>
      </template>
    </template>
  </div>
</div>

</div>
</main>
</template>
<script>

import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      PermisosCRUD : {},
      total_viaticos : 0,
      total_indirectos_oficina : 0,
      total_operaciones : 0,
      total_mano_obra : 0,
      total_admin : 0,
      total_sin_pro : 0,
      total_gastos : 0,
      objeto : 0,
      ver : 0,
      datos_r : null,
      wpartidas : false,
      wpartidasdos : false,
      encabezado : 0,
      listaProyectos : [],
      tableDataPartidasCompras: [],
      tableDataPartidasComprasdos: [],
      listaProveedores : [],
      input : 0,
      totales : 0,
      resultado_final : 0,
      total_usd_mnx : 0,
      columnscompras: ['rf','rfs','proyecton','ad','cantidad','precio_unitario'],//
      columnscomprasdos: ['proyecton','ad','cantidad','precio_unitario','total'],//
      columns : ['descargar','folio','nombre_condicion_pago','periodo_entrega','lugar_entrega','total','moneda','proveedor_razon_social','format'],
      tableData : [],
      columns_partidas : [],
      tableDataPartidas : [],
      proyecto : '',
      proveedor : '',
      optionscomprasdos: {
        headings: {
          'id' : 'Acción',
          // 'requisicione_id' : '',
          'rf': 'Folio Requisición',
          'rfs': 'Fecha Solicitud Requisición',
          'proyecton': 'Proyecto',
          'ad': 'Artículo',
          'cantidad' : 'Cantidad'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['rf','rfs','proyecton','ad','cantidad','precio_unitario'],
        filterable: ['rf','rfs','proyecton','ad','cantidad','precio_unitario'],
        filterByColumn: true,
        listColumns: {
          condicion: [{
            id: 1,
            text: 'Activo'
          },
          {
            id: 0,
            text: 'Dado de Baja'
          }
        ]
      },
      texts:config.texts
    },
      options: {
        headings: {
          'id': 'Acciones',
          'folio': 'Folio',
          'nombre_condicion_pago': 'Condición de Pago',
          'periodo_entrega': 'Período de Entrega',
          'lugar_entrega': 'Lugar de Entrega',
          'total': 'Total',
          'proveedor_razon_social': 'Proveedor',
          'condicion' : 'Condición',
          'estado_id' : 'Estado',
          'descargar' : '',
          'format' : 'Descargar',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['folio','nombre_condicion_pago','periodo_entrega','lugar_entrega','total','moneda','proveedor_razon_social'],
        filterable: ['folio','nombre_condicion_pago','periodo_entrega','lugar_entrega','total','moneda','proveedor_razon_social'],
        filterByColumn: true,
        texts:config.texts,
        columnsDisplay : {},
        listColumns: {
          moneda: [{
            id: 1,
            text: 'Dolares'
          },
          {
            id: 2,
            text: 'Moneda Nacional'
          },
          {
            id: 3,
            text: 'Euros'
          }
        ],
        nombre_condicion_pago: [{
          id: 1,
          text: 'Crédito'
        },
        {
          id: 2,
          text: 'Contado'
        },
      ],
    },
    requestAdapter : function (data) {
      var arr = [];
      arr.push({
        'folio' : data.query.folio,
        'condicion_pago_id' : data.query.nombre_condicion_pago,
        'periodo_entrega' : data.query.periodo_entrega,
        'lugar_entrega' : data.query.lugar_entrega,
        'total': data.query.total,
        'moneda' : data.query.moneda,
        'p.razon_social': data.query.proveedor_razon_social,
      });
      data.query = arr[0];
      return data;
    },

  },
  optionscompras: {
    headings: {
      'id' : 'Acción',
      // 'requisicione_id' : '',
      'proyecton': 'Proyecto',
      'ad': 'Artículo',
      'cantidad' : 'Cantidad'
    },
    perPage: 10,
    perPageValues: [],
    skin: config.skin,
    sortIcon: config.sortIcon,
    sortable: ['proyecton','ad','cantidad','precio_unitario'],
    filterable: ['proyecton','ad','cantidad','precio_unitario'],
    filterByColumn: true,
    listColumns: {
      condicion: [{
        id: 1,
        text: 'Activo'
      },
      {
        id: 0,
        text: 'Dado de Baja'
      }
    ]
  },
  texts:config.texts
},
  options_partidas : {
    headings: {

    },
    perPage: 10,
    perPageValues: [],
    skin: config.skin,
    sortIcon: config.sortIcon,
    sortable: [],
    filterable: [],
    filterByColumn: true,
    texts:config.texts
  },


}
},
watch: {
  proyecto(nuevoValor, valorAnterior){
    if (nuevoValor != '') {
      this.proveedor = '';
    }
  },
  proveedor(nuevoValor, valorAnterior){
    if (nuevoValor != '') {
      this.proyecto = '';
    }
  },
},
computed : {
  inputd(){
    var resul = 0;
    resul = parseFloat(this.tableData['usd']) * this.input;
    return this.total_usd_mnx = resul;
  },
  totolss(){
    var resu=0;
    resu = parseFloat(this.total_usd_mnx) + parseFloat(this.tableData['mnx']);
    return this.totales = resu;
  },

  total_final(){
    var resultado = 0;
    resultado = parseFloat(this.totales/1.16) + parseFloat(this.total_mano_obra * 1.56) + parseFloat(this.total_admin * 1.56) + parseFloat(this.total_sin_pro * 1.56) +  parseFloat(this.total_gastos) + parseFloat(this.total_viaticos);
    return this.resultado_final = resultado;
  },

  total_ope(){
    var oper = 0;
    oper = parseFloat(this.totales/1.16) + parseFloat(this.total_mano_obra * 1.56) + parseFloat(this.total_viaticos);
    return this.total_operaciones = oper;
  },

   total_io(){
      var io = 0;
      io = parseFloat(this.total_admin * 1.56) + parseFloat(this.total_sin_pro * 1.56) ;
      return this.total_indirectos_oficina = io;
   }
},
methods:{
  maestro(){
    this.encabezado = 1;
    this.wpartidas = false;
    this.wpartidasdos = false;
  },

  descargar(data) {
    window.open('descargar-compran/' + data.id, '_blank');
    let me = this;
    me.getData(data.proyecto_id);
  },

  getData(){
    let me = this;
    axios.get('/proyecto-listar').then(response => {
      this.listaProyectos = [];
      response.data.forEach((item, i) => {
        this.listaProyectos.push({
          id : item.id,
          name : item.nombre_corto
        });
      });
    })
    .catch(function (error) {
      console.log(error);
    });
    // axios.get('/proveedores').then(response => {
    //   this.listaProveedores = [];
    //   response.data.forEach((item, i) => {
    //     this.listaProveedores.push({
    //       id : item.id,
    //       name : item.razon_social + ' ' + item.nombre
    //     });
    //   });
    // })
    // .catch(function (error) {
    //   console.log(error);
    // });

    this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);


  },

  listarCompras(){
    if (this.proyecto == '') {
      toastr.warning('Seleccione un proyecto');
    }else {
      axios.get(`trazabilidadcompras/costo/${this.proyecto.id}`).then(response => {
        this.tableData = response.data;
        // console.log(response);
      }).catch(error => {
        console.error(error);
      });
      this.objeto = this.proyecto.id;
      this.encabezado = 1;


      axios.get('obtener/mano/obra/operativo/'+ this.proyecto.id).then(response => {
      this.total_mano_obra = response.data;
      }).catch(e => {
      console.error();
      });

      axios.get('obtener/mano/obra/administrativos/'+ this.proyecto.id).then(response => {
      this.total_admin = response.data;
      }).catch(e => {
      console.error();
      });

      axios.get('obtener/mano/obra/sinproyecto/'+ this.proyecto.id).then(response => {
      this.total_sin_pro = response.data;
      }).catch(e => {
      console.error();
      });

      axios.get('obtener/total/gastos/'+ this.proyecto.id).then(response => {
      this.total_gastos = response.data;
      }).catch(e => {
      console.error();
      });

      axios.get('reportegastosviaticos/porproyecto/'+ this.proyecto.id).then(response => {
      this.total_viaticos = response.data;
      }).catch(e => {
      console.error();
      });
    }
  },

  cargardetalle(data){
    let me = this;
    me.encabezado = 2;
    if(data.conrequisicion === 0){
      me.wpartidas = true;
      // childpartidas.cargarpartidas(data);
      axios.get( '/compras/' + data.id + '/' +'compras').then(response => {
        me.tableDataPartidasCompras = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    }else if (data.conrequisicion === 1) {
      me.wpartidasdos = true;
      axios.get( '/compras/' + data.id + '/' +'compras').then(response => {
        me.tableDataPartidasComprasdos = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    }
  },

  generarExcel(){
    if (this.proyecto == '' && this.proveedor == '') {
      toastr.warning('Seleccione un proyecto o proveedor');
    }
    else {
      var tipo = this.proyecto == '' ? 'Proveedor' : 'Proyecto';
      var ids = '';
      if (this.proyecto != '') {
        this.proyecto.map(function (value) {
          ids += value.id + '&';
        });
      }else if (this.proveedor != '') {
        this.proveedor.map(function (value) {
          ids += value.id + '&';
        });
      }
      // window.open('descargar-excel-rh/'+this.date_one+'&'+this.date_two, '_blank');
      window.open(`descargar/excel/trazabilidadcomprasgeneral/totales/${tipo}&${ids}` , '_blank');
    }

  },


},
mounted(){
  this.getData();
}
}
</script>
