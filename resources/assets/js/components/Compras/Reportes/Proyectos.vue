<template>
    <main class="main">
        <div class="container-fluid">
            <br>
            <div class="row">
                <div class="col-12">
                    <div class="card" v-show="!detalle">
                        <div class="card-header">Proyectos</div>
                        <div class="card-body">
                            <vue-element-loading :active="isLoading"/>
                            <div class="form-group row">
                                <label class="col-md-1 form-control-label" for="tipo_partida"><br>Proyecto</label>
                                <div class="col-md-3">
                                  <label>&nbsp;</label>
                                  <v-select id="proyectos" name="proyectos" label="nombre_corto" 
                                    :options="listaProyectos" v-model="proyecto">
                                  </v-select>
                                  <!--
                                    <select class="form-control" id="tipo_partida"  name="tipo_partida" v-model="proyecto_id" >
                                        <option value="0" selected>---</option>
                                        <option v-for="item in listaProyectos" :value="item.proyecto.id"
                                         :key="item.proyecto.id">{{ item.proyecto.nombre_corto }}</option>
                                    </select>
                                <!-- <span class="text-danger">{{ errors.first('tipo_partida') }}</span> -->
                                </div>
                                <div class="col-md-2">
                                  <label>Fecha inicial</label>
                                  <input type="date" class="form-control" v-model="date_one" ref="date_one">
                                </div>
                                <div class="col-md-2">
                                  <label>Fecha final</label>
                                  <input type="date" class="form-control" v-model="date_two"  ref="date_two">
                                </div>
                                <div class="col-md-1"><br><button class="btn btn-dark" @click="listarCompras()">Buscar</button></div>
                                <div class="col-md-1"><br><button class="btn btn-success" @click="generarExcel()">Exportar</button></div>

                            </div>

                            <table class="VueTables__table table table-hover table-sm table-bordered">
                                <thead class="table-active">
                                    <tr>
                                        <th scope="col">Folio</th>
                                        <th scope="col">Fecha Orden</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Condición pago</th>
                                        <th scope="col">Proveedor</th>
                                        <th scope="col">Proyecto</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in registros" :value="item.id" :key="item.id">
                                        <td>{{ item.folio }}</td>
                                        <td>{{ item.fecha_orden }}</td>
                                        <td>{{ item.estado_compra }}</td>
                                        <td>{{ item.condicion_pago }}</td>
                                        <td>{{ item.proveedor }}</td>
                                        <td>{{ item.proyecto }}</td>
                                        <th scope="col"><button class="btn btn-outline-dark" @click="detalleCompra(item)"><i class="fa fa-eye"></i> Ver compra</button></th>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card" v-show="detalle">
                        <vue-element-loading :active="isLoadingDetalle"/>
                        <div class="card-header">
                            {{ compra.folio }}
                            <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
                                <i class="fas fa-arrow-left"></i>&nbsp;Atras
                            </button>
                        </div>
                        <table class="table table-sm">
                            <tr>
                                <td class="col-2">Fecha orden</td>
                                <td class="col-4">{{ compra.fecha_orden }}</td>
                                <td class="col-2">Total</td>
                                <td class="col-4">{{ compra.total }}</td>
                            </tr>
                            <tr>
                                <td>Proveedor</td>
                                <td>{{ compra.proveedor }}</td>
                                <td>Descuento %</td>
                                <td>{{ compra.descuento }}</td>
                            </tr>
                            <tr>
                                <td>Estado</td>
                                <td>{{ compra.estado_compra }}</td>
                                <td>Moneda</td>
                                <td>{{ compra.moneda == 1 ? 'USD' : compra.moneda == 2 ? 'MXN' : compra.moneda ==3 ? 'EUR' : '' }}</td>
                            </tr>
                            <tr>
                                <td>Proyecto</td>
                                <td>{{ compra.proyecto }}</td>
                                <td>Tipo cambio</td>
                                <td>{{ compra.tipo_cambio }}</td>
                            </tr>
                            <tr>
                                <td>Condición pago</td>
                                <td>{{ compra.condicion_pago }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <table class="table table-sm table-stripped">
                            <thead class="table-active">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Marca</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in partidas" :value="item.id" :key="item.id">
                                    <td>{{ item.nombre }}</td>
                                    <td>{{ item.descripcion }}</td>
                                    <td>{{ item.marca }}</td>
                                    <td>{{ item.precio_unitario }}</td>
                                    <td>{{ item.cantidad }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';

export default {
  data() {
    return {
        listaProyectos: [],
        proyecto:{},
        proyecto_id: 0,
        registros: [],
        compra: { },
        partidas: [],
        isLoading: false,
        isLoadingDetalle: false,
        detalle: false,
        date_one : '',
        date_two : '',
    }
  },
    methods: {
        listarCompras() {
            if (this.proyecto.id == 0)
            {
                toastr.error('Seleccione un proyecto');
            }

            let me=this;
            this.isLoading = true;
            axios.get('/compras-por-proyecto/' + this.proyecto.id).then(response => {
                me.registros =  response.data;
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });

        },
        getData() {
            let me=this;
            this.isLoading = true;
            axios.get('/proyecto').then(response => {
                let aux={id:0,nombre_corto:"Seleccione proyecto"};
                me.listaProyectos.push(aux);
                me.proyecto=aux;
                response.data.forEach(p=>
                {
                    me.listaProyectos.push
                    ({
                        id:p.proyecto.id,
                        nombre_corto:p.proyecto.nombre_corto,
                    });
                });
                
                me.isLoading = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        detalleCompra(data) {
            this.compra = data;
            this.detalle = true;
            let me=this;
            this.isLoadingDetalle = true;
            axios.get('/compraspartidasproyecto/' + this.compra.id).then(response => {
                me.partidas = response.data;
                me.isLoadingDetalle = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        maestro() {
            this.detalle = false;
        },

        generarExcel(){
          if (this.$refs.date_one.value == '') {
            toastr.warning('Ingrese fecha inicial');
          }else if (this.$refs.date_two.value == '') {
            toastr.warning('Ingrese fecha final');
          }else {
            // window.open('descargar-excel-rh/'+this.date_one+'&'+this.date_two, '_blank');
            window.open('descargar-excel-compras/'+this.proyecto.id + '&' + this.date_one + '&' + this.date_two, '_blank');
          }

        }
    },
    mounted() {
        this.getData();
    }
}
</script>
