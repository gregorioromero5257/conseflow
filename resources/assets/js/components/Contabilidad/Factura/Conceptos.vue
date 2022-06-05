<template >
  <div>
    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Conceptos de la factura {{ datos == null ? '' : datos.serie + ' ' + datos.folio}}
        <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
          <i class="fas fa-arrow-left"></i>&nbsp;Atras
        </button>
        <button type="button" class="btn btn-dark float-sm-right" @click="abrirModal('partidas','registrar')">
          <i class="fas fa-plus">&nbsp;</i>Nuevo
        </button>
        <button type="button" class="btn btn-success float-sm-right" @click="abrirModal('partidas','cargar')">
          <i class="fa fa-file-excel-o">&nbsp;</i>Cargar
        </button>
      </div>
      <div class="card-body">
        <v-server-table :url="'partidafactura/'+ id_factura" :columns="columns" :options="options" ref="Tablac">
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <!-- v-show="PermisosCrud.Update" -->
                  <button  type="button" :disabled="datos.timbrado == 1" @click="abrirModal('partidas','actualizar',props.row)" class="dropdown-item">
                    <i class="icon-pencil"></i>&nbsp; Actualizar Concepto
                  </button>
                  <button  type="button" :disabled="datos.timbrado == 1" @click="eliminar(props.row)" class="dropdown-item">
                    <i class="fas fa-list-ol"></i>&nbsp; Eliminar
                  </button>
                </div>
              </div>
            </div>
          </template>
        </v-server-table>
      </div>
    </div>

    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-row">
              <div class="form-group col-md-8">
                <label>Producto y servicios</label>
                <v-select :options="optionsvsps" id="productos servicios" v-validate="'required'" name="productos servicios"
                v-model="partidas.productos_servicios_id" label="name" data-vv-name="productos servicios" ></v-select>
                <span class="text-danger">{{errors.first('productos servicios')}}</span>
              </div>
              <div class="form-group col-md-4">
                <label>Clave unidad</label>
                <select class="form-control" v-model="partidas.unidad_id" data-vv-name="clave unidad" v-validate="'required'">
                  <option v-for="item in Unidad" :value="item.id">{{item.c_unidad}} {{item.nombre}}</option>
                </select>
                <span class="text-danger">{{errors.first('clave unidad')}}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Cantidad </label>
                <input type="text" class="form-control" v-model="partidas.cantidad"
                placeholder="Cantidad" @input="multiplicar()" v-validate="'required|decimal:2'" data-vv-name="cantidad">
                <span class="text-danger">{{errors.first('cantidad')}}</span>
              </div >
              <div class="form-group col-md-4">
                <label>Unidad </label>
                <input type="text" class="form-control" v-model="partidas.unidad"
                placeholder="Unidad" data-vv-name="unidad" v-validate="'required'">
                <span class="text-danger">{{errors.first('unidad')}}</span>
              </div>
              <div class="form-group col-md-4">
                <label>Número de identificación </label>
                <input type="text" class="form-control" v-model="partidas.numero_identificacion"
                placeholder="Número de identificación" data-vv-name="numero de identificacion" v-validate="'required'">
                <span class="text-danger">{{errors.first('numero de identificacion')}}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label>Descripción</label>
                <textarea rows="4" cols="50" class="form-control" v-model="partidas.descripcion"
                placeholder="Descripción" data-vv-name="descripcion" v-validate="'required'" maxlength="1000"/>
                <span class="text-danger">{{errors.first('descripcion')}}</span>
              </div>
              <div class="form-group col-md-12">
                <label>Comentario</label>
                <textarea rows="4" cols="50" class="form-control" v-model="partidas.comentario"
                placeholder="Comentario" data-vv-name="comentario" />
                <span class="text-danger">{{errors.first('comentario')}}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-3">
                <label>Valor Unitario</label>
                <input type="text" class="form-control" v-model="partidas.valor_unitario"
                placeholder="Valor unitario" @input="multiplicar()" data-vv-name="valor unitario" v-validate="'required|decimal:2'">
                <span class="text-danger">{{errors.first('valor unitario')}}</span>
              </div>
              <div class="form-group col-md-3">
                <label>Importe</label>
                <input type="text" class="form-control" v-model="partidas.importe"
                placeholder="Importe" readonly>
              </div>
              <div class="form-group col-md-3">
                <label>Descuento</label>
                <input type="text" class="form-control" v-model="partidas.descuento"
                placeholder="Descuento" data-vv-name="descuento" v-validate="'required|decimal:2'" @input="multiplicar()">
                <span class="text-danger">{{errors.first('descuento')}}</span>
              </div>
              <div class="form-group col-md-3">
                <label>Iva</label>
                <input type="text" class="form-control" v-model="partidas.impuesto_iva"
                placeholder="Iva" data-vv-name="iva" v-validate="'required'">
                <span class="text-danger">{{errors.first('descuento')}}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-2">
                <label>Retencion</label>
              </div>
              <div class="form-group col-md-1">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" class="form-check-input" id="observa" @change="retencion($event)">
              </div>
              <div class="form-group col-md-3">
                <input type="text" class="form-control" v-model="partidas.retencion"
                placeholder="Retencion" v-bind:disabled="reten == 0" >
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
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>



    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_cargar}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Cargar archivo Excel</h4>
            <button type="button" class="close" @click="cerrarModalCargar()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>&nbsp;Subir --</label>
                <input class="form-control" type="file" v-validate="'ext:xls,xlsx'" data-vv-as="Archivo"
                name="import_file_emp" @change="getArchivo" accept="*/*" id="archivo" >
                <span class="text-danger">{{ errors.first('import_file_emp') }}</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <button @click="update" class="btn btn-primary" >Subir Archivo</button>
              </div>
            </div>

          </div>
          <div class="modal-footer">
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

  </div>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      modal : 0,
      modal_cargar: 0,
      tituloModal : '',
      tipoAccion : 0,
      isLoading : false,
      reten : 0,
      id_factura : 0,
      partidas : {
        id : '',
        productos_servicios_id : '',
        unidad_id : '',
        cantidad : '',
        unidad : '',
        numero_identificacion : '',
        descripcion : '',
        comentario : '',
        valor_unitario : '',
        importe : '',
        descuento : '0',
        impuesto_iva : '',
        factura_id : '',
        retencion : 0,
      },
      archivo : null,
      ProductoServicio : [],
      optionsvsps : [],
      Unidad : [],
      datos : null,
      tableData : [],
      columns : ['id','scps_descripcion','scu_nombre','cantidad','unidad','numero_identificacion','descripcion','valor_unitario','importe','descuento'],
      options: {
        headings: {
          'id' : 'Acciones',
          'scps_descripcion' : 'Productos/Servicios',
          'scu_nombre' : 'Clave unidad',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts,
        requestAdapter : function (data) {
          var arr = [];
          arr.push({'SCPS.descripcion' : data.query.scps_descripcion,
          'SCU.nombre' : data.query.scu_nombre,
          'partidas_factura.cantidad' : data.query.cantidad,
          'partidas_factura.unidad' : data.query.unidad,
          'partidas_factura.numero_identificacion' : data.query.numero_identificacion,
          'partidas_factura.descripcion' : data.query.descripcion,
          'partidas_factura.comentario' : data.query.comentario,
          'partidas_factura.valor_unitario' : data.query.valor_unitario,
          'partidas_factura.importe' : data.query.importe,
          'partidas_factura.descuento' : data.query.descuento});
          data.query = arr[0];
          return data;
        },
      },
    }
  },
  methods : {
    getData(data, options){
      this.$refs.Tablac.refresh();
      this.datos = data;
      this.partidas.factura_id = data.id;
      this.id_factura = data.id;
      this.optionsvsps = options;
      axios.get('/satcatunidades').then(response => {
        this.Unidad = response.data;
      }).catch(error => {console.error(error);});
    },

    /**
    * [abrirModal description]
    * @param  {String} modelo    [description]
    * @param  {String} accion    [description]
    * @param  {Array}  [data=[]] [description]
    * @return {Response}           [description]
    */
    abrirModal(modelo, accion, data = [])
    {
      switch(modelo){
        case "partidas":
        {
          switch(accion){
            case 'registrar':
            {
              this.modal= 1;
              this.tituloModal = 'Registrar conceptos';
              this.tipoAccion=1;
              this.vaciar();
              this.datos.rfc_receptor == 'XEXX010101000' ? this.partidas.impuesto_iva = '0.000000' : this.partidas.impuesto_iva = '0.160000';
              break;
            }
            case 'actualizar':
            {
              this.vaciar();
              this.modal= 1;
              this.tituloModal = 'Actualizar conceptos';
              this.tipoAccion=2;
              this.partidas.id = data['id'];
              this.partidas.productos_servicios_id = {id: data['productos_servicios_id'], name : data['scps_clave']+' '+data['scps_descripcion'],};
              this.partidas.unidad_id = data['unidad_id'];
              this.partidas.cantidad = data['cantidad'];
              this.partidas.unidad = data['unidad'];
              this.partidas.numero_identificacion = data['numero_identificacion'];
              this.partidas.descripcion = data['descripcion'];
              this.partidas.comentario = data['comentario'];
              this.partidas.valor_unitario = data['valor_unitario'];
              this.partidas.importe = data['importe'];
              this.partidas.descuento = data['descuento'];
              // this.partidas.impuesto_iva = '0.160000';
              this.datos.rfc_receptor == 'XEXX010101000' ? this.partidas.impuesto_iva = '0.000000' : this.partidas.impuesto_iva = '0.160000';
              this.partidas.factura_id = data['factura_id'];
              this.partidas.retencion = data['retencion'];
              break;
            }
            case 'cargar':
            {
              this.modal_cargar = 1;

            }
          }
        }
      }
    },

    maestro(){
      this.$emit('maestro:conceptos');
    },

    cerrarModal(){
      this.modal = 0;
    },

    cerrarModalCargar(){
      this.modal_cargar = 0;
    },

    multiplicar(){
      this.partidas.importe = (this.partidas.cantidad * this.partidas.valor_unitario);
    },

    vaciar(){
      this.partidas.productos_servicios_id = '';
      this.partidas.unidad_id = '';
      this.partidas.cantidad = '';
      this.partidas.unidad = '';
      this.partidas.numero_identificacion = '';
      this.partidas.descripcion = '';
      this.partidas.comentario = '';
      this.partidas.valor_unitario = '';
      this.partidas.importe = '';
      this.partidas.retencion = '';
      // this.partidas.descuento = '';
      // this.partidas.factura_id = '';
    },

    eliminar(data){
      let me = this;
      axios.delete('/partidafactura/' + data.id ).then(response => {
        toastr.info('Registro eliminado correctamente','Atención');
        me.$refs.Tablac.refresh();
      }).catch(error => {
        console.error(error);
      });
    },

    guardar(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? '/partidafactura' : '/partidafactura/'+ this.partidas.id,
            data: {
              // id : this.factura.id,
              productos_servicios_id : this.partidas.productos_servicios_id.id,
              unidad_id : this.partidas.unidad_id,
              cantidad : this.partidas.cantidad,
              unidad : this.partidas.unidad,
              numero_identificacion : this.partidas.numero_identificacion,
              descripcion : this.partidas.descripcion,
              comentario : this.partidas.comentario,
              valor_unitario : this.partidas.valor_unitario,
              importe : this.partidas.importe,
              descuento : this.partidas.descuento,
              impuesto_iva : this.partidas.impuesto_iva,
              factura_id : this.partidas.factura_id,
              retencion : this.partidas.retencion,
            }
          }).then(function (response) {
            me.isLoading = false;
            me.cerrarModal();
            toastr.success(nuevo ? 'Concepto agregada correctamente' : 'Concepto actualizada correctamente','Correcto');
            me.$refs.Tablac.refresh();
          }).catch(function (error) {
            console.log(error);
          });
        }
      });
    },

    retencion(data){
      let me = this;
      if(data.target.checked == true){
        me.reten = 1;
      }else{
        this.reten = 0;
      }
    },

    getArchivo(event){
      //Asignamos el archivo a  nuestra data
      this.archivo = event.target.files[0];
    },

    update(){
          // this.isLoading = true;
          //Creamos el formData
          var data = new  FormData();
          //Añadimos la imagen seleccionada
          data.append('import_file', this.archivo);
          data.append('id',this.partidas.factura_id);
          //Añadimos el método PUT dentro del formData
          // Como lo hacíamos desde un formulario simple _(no ajax)_
          data.append('_method', 'PUT');
          //Enviamos la petición
          axios.post('/partidasfacturasexcel/upload',data)
          .then(response => {
            // console.log(response);
            this.archivo = null;
            // var field = this.$validator.fields.find({ name: 'email' });
            // field.reset();
            if (response.status == 200){
              swal(
                'agregado',
                //  'Registros agregados: ' + response.data.nuevos + '<br>Registros repetidos: ' + response.data.repetidos,
                'correcto!!!'
              );
              $('#archivo').val('');
              this.cerrarModalCargar();
              this.$refs.Tablac.refresh();
              // this.$refs.table.refresh();
            }
            else
            swal(
              'Articulos',
              'Ocurrio un error.',
              'error'
            );
          })
          .catch(function (error) {
            this.archivo = null;
            // this.isLoading = false;
            console.log(error);
            swal(
              'Articulos',
              'Ocurrio un error al leer el archivo.',
              'error'
            );
          });

    },
  }
}
</script>
