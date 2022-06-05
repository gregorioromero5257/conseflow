<template>
<div class="card" v-if="mostrar == 1">
    <!--Inicio del modal agregar inpuestos-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">

            <div class="modal-content">

                <!-- <vue-element-loading :active="isLoadingg"/> -->

                <div class="modal-header">
                    <h4 class="modal-title">Agregar impuestos</h4>
                    <button type="button" class="close" @click="cerrarModalInpuesto()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-row">

                        <div class="col-auto">
                            <label>Tipo de impuestos</label>
                            <div class="input-group mb-2">
                                <div class="input-group-addon">
                                    <div class="input-group-text">$</div>
                                </div>
                                <input type="txet" v-model="temporal.tipo" class="form-control" placeholder="Tipo de impuesto" v-validate="'required'">

                            </div>
                        </div>

                        <div class="col-auto">
                            <label>Porcentaje</label>
                            <div class="input-group mb-2">
                                <div class="input-group-addon">
                                    <div class="input-group-text">%</div>
                                </div>

                                <input type="number" min="0" max="100" v-model="temporal.porcentaje" pattern="^[0-9]+" class="form-control" placeholder="0" v-validate="'required'">
                            </div>
                        </div>

                        <div class="col-auto">
                            <div class="form-check mb-2">
                                <input class="ml-2" name="chkRetenido" type="checkbox" v-model="temporal.retenido">
                                <label class="form-check-label ml-2" for="chkRetenido">
                                    Retenido
                                </label>
                            </div>
                        </div>

                        <div class="col-auto">
                            <label></label>
                            <div class=" mb-2">
                                <div class="mt-2 ml-2">
                                    <button type="button" class="btn btn-sm btn-secondary" @click="crear()">
                                        <i class="fas fa-save"></i>&nbsp;Agregar
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-row mt-2 ml-3">
                        <div class="form-group col-md-3">
                            <p class="font-weight-bold">Tipo</p>
                        </div>
                        <div class="form-group col-md-2">
                            <p class="font-weight-bold">Porcentaje</p>
                        </div>
                        <div class="form-group col-md-2">
                            <p class="font-weight-bold">Retenido</p>
                        </div>
                        <div class="form-group col-md-2">
                            <label><b></b></label>
                        </div>
                    </div>

                    <li v-for="(vi, index) in impuestocatalogo" class="list-group-item">
                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label>{{vi.tipo}}</label>
                            </div>
                            <div class="form-group col-md-2">
                                <label>{{vi.porcentaje}}</label>
                            </div>
                            <div class="form-group col-md-2">
                                <template v-if="vi.retenido == 0">
                                    <label>No</label>
                                </template>
                                <template v-if="vi.retenido == 1">
                                    <label>Sí</label>
                                </template>
                            </div>
                            <div class="form-group col-md-2">
                                <a @click="eliminar(vi, index)">
                                    <span class="fas fa-trash" arial-hidden="true"></span>
                                </a>
                            </div>
                        </div>
                    </li>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModalInpuesto()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal agregar inpuestos-->

</div>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            id: 0,
            modal: 0,
            mostrar: 0,
            estadomodalimpuesto: null,
            temporal:
            {
                tipo: '',
                porcentaje: '',
                retenido: 0,
            },
            impuestocatalogo: [],
            nuevo: [],
        }
    },
    components:
    {},
    methods:
    {
        cargarinpuesto(num, id, imps)
        {
            this.mostrar = 1;
            this.modal = 1;
            // this.estadomodalimpuesto = num;
            this.id = id;
            let me = this;
            axios.get('comprabusquedaimpuesto/' + id).then(response =>
                {
                    me.impuestocatalogo = response.data;//.concat(imps);
                })
                .catch(function (error)
                {
                    console.log(error);
                });
        },

        cerrarModalInpuesto()
        {
            /*if (this.temporal.tipo != '' && this.temporal.porcentaje === '')
            {
                toastr.warning('Acompletar el porcentaje del impuesto para continuar o vaciar el campo');
            }
            else if (this.temporal.tipo === '' && this.temporal.porcentaje != '')
            {
                toastr.warning('Acompletar el tipo del impuesto para continuar o vaciar el campo');
            }
            else
            {
                this.mostrar = 1;
                this.modal = 0;
                this.$emit('modalimpuestos:click', this.nuevo);
                this.nuevo = [];
            }*/
            this.mostrar = 1;
            this.modal = 0;
            this.$emit('modalimpuestos:click', this.nuevo);
            this.nuevo = [];

            // Regresar impuestos ingresados
            this.$emit('update', this.impuestocatalogo);
        },

        vaciar()
        {
            this.temporal.tipo = '';
            this.temporal.porcentaje = '';
            this.temporal.retenido = 0;
        },

        /**
         * [crear description]
         * @return {[type]} [description]
         */
        crear()
        {
            this.$validator.validate().then(result =>
            {
                if (this.temporal.tipo == "")
                {
                    toastr.warning("Ingrese un tipo");
                    return;
                }
                if (this.temporal.porcentaje == "")
                {
                    toastr.warning("Ingrese un porcentaje");
                    return;
                }
                else
                {
                    if (this.temporal.porcentaje <= 0)
                    {
                        toastr.warning("Ingrese un porcentaje válido");
                        return;
                    }
                }

                if (result)
                {
                    this.impuestocatalogo.push(
                    {
                        id: '0',
                        tipo: this.temporal.tipo,
                        porcentaje: this.temporal.porcentaje,
                        retenido: (this.temporal.retenido == true ? 1 : 0)
                    });
                    this.nuevo.push(
                    {
                        id: '0',
                        tipo: this.temporal.tipo,
                        porcentaje: this.temporal.porcentaje,
                        retenido: (this.temporal.retenido == true ? 1 : 0)
                    });
                    this.vaciar();
                }
            });
        },

        /**
         * [eliminar description]
         * @param  {Array}  [data=[]] [description]
         * @return {[type]}           [description]
         */
        eliminar(data = [], index)
        {
            if (data.id == 0)
            {
                this.impuestocatalogo.splice(index, 1);
            }
            else
            {
                let me = this;
                var id = data['id'];
                axios.get('impuestoeliminar/' + id)
                    .then(function (response)
                    {
                        // me.modal = 0;
                        me.cargarinpuesto(0, me.id);
                    })
                    .catch(function (error)
                    {
                        console.log(error);
                    });
            }
        },
    },
    mounted()
    {

    }
}
</script>
