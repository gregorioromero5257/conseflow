<template>
    <main class="main">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                Historico del empleado
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-3 form-control-label" for="empleado_id" v-bind:hidden="hiden == 1">Empleado</label>
                    <div class="col-md-7">
                        <v-select :options="optionsvs" v-model="s_empleado" name="empleado_id" label="name" v-validate="'excluded:0'" data-vv-as="Empleado" v-bind:hidden="hiden == 1"></v-select>
                        <span class="text-danger">{{ errors.first('empleado_id') }}</span>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-dark" @click="buscar"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Empleado</div>
            <div class="card-body">                
                <vue-element-loading :active="isLoading"/>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td class="table-active">Nombre</td>
                        <td colspan="3">{{ empleado != null ? empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno : ''}}</td>
                    </tr>
                    <tr>
                        <td class="table-active">Puesto</td>
                        <td>{{ empleado != null ? empleado.puesto : ''}}</td>
                        <td class="table-active">Estado</td>
                        <td>
                            <template v-if="empleado!= null && empleado.condicion">
                                <span class="badge badge-success">Activo</span>
                            </template>
                            <template v-else>
                                <span class="badge badge-danger">Dado de Baja</span>
                            </template>
                        </td>
                    </tr>
                </table>
                <h3>Contratos</h3>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr class="table-active">
                            <th style="width:40%">Proyecto</th>
                            <th style="width:10%">Fecha</th>
                            <th style="width:10%"></th>
                            <th style="width:15%"></th>
                            <th style="width:10%">Sueldo</th>
                            <th style="width:15%"></th>
                        </tr>
                    </thead>
                    <tr v-for="contrato in contratos" :value="contrato.id"  :key="contrato.id">
                        <td>
                            {{ contrato.clave }} <br>
                            <span class="font-weight-bold">{{ contrato.nombre }} </span><br>
                            Inicio: {{ contrato.fecha_inicio | formatoFecha }} <br>
                            Fin {{ contrato.fecha_fin | formatoFecha }} <br>
                            <br>
                            <h5 style="  
                            background-color:#B09B9B;"
                            >OBSERVACIONES</h5><br>{{ contrato.observaciones }}<br><br>
                        </td>
                        <td>
                            Ingreso: <br>
                            {{ contrato.fecha_ingreso | formatoFecha }} <br><br><br><br>
                            <h6>Fecha de Baja:</h6> <br>
                            {{ contrato.fecha_baja | formatoFecha }}
                        </td>
                        <td class="table-active">
                            Contrato: <br>
                            Nómina: <br>
                            Horario: <br><br><br>
                            Formato renuncia:
                        </td>
                        <td>
                            {{ contrato.tipo_contrato }} <br>
                            {{ contrato.tipo_nomina }} <br>
                            {{ contrato.horario }} <br><br><br>
                            {{ contrato.format_renuncia }}
                        </td>
                        <td class="table-active">
                            Diario int.: <br>
                            Diario neto: <br>
                            Viaticos: <br>
                            Mensual: <br> <br>
                            Finiquito:
                        </td>
                        <td class="text-right">
                            {{ contrato.sueldo_diario_integral }} <br>
                            {{ contrato.sueldo_diario_neto }} <br>
                            {{ contrato.viaticos_mensuales }} <br>
                            {{ contrato.sueldo_mensual }} <br><br>
                            {{ contrato.finiquito }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    </main>
</template>
<script>
import moment from 'moment'
export default {
    data (){ 
            return {
                s_empleado: null,
                empleado: null,
                contratos: [],
                optionsvs: [],
                hiden: 0,
                isLoading: false
            }
    },
    methods: {
        listar(){
                this.isLoading = true;
                let me=this;
                axios.get('historico/' + this.s_empleado.id).then(response => {
                    me.empleado = response.data.empleado;
                    me.contratos = response.data.contratos;
                    me.isLoading = false;
                })
                .catch(function (error) {
                    console.log(error);
                    me.isLoading = false;
                });
        },
        listarEMpleados(){
            axios.get('/empleado').then(response => {
            for (var i = 0; i < response.data.length; i++) {
            this.optionsvs.push(
                {
                id:response.data[i].empleado.id,
                name:response.data[i].empleado.nombre+' '+response.data[i].empleado.ap_paterno+' '+response.data[i].empleado.ap_materno,
                puesto:response.data[i].puesto.nombre
                });
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        buscar(){
            if (this.s_empleado == null) {
                swal({
                        type: 'error',
                        html: 'Seleccionar empleado',
                    });
                return;
            }
            this.listar();
        }
    },
    mounted() {
            this.listarEMpleados();
    },
    filters: {
        formatoFecha: function (fecha) {
            if (fecha === null) return '';
            return moment(fecha).format('DD/MM/YYYY')
        }
    }
}
</script>

