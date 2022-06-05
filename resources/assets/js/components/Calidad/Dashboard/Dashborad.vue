<template>
<main class="main">
    <div class="container-fluid">
        <br>
        <div class="row">
            <div class="col-md-12">
                <facturasproveedores></facturasproveedores>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <equiposcalib></equiposcalib>
            </div>
        </div>

    </div>
</main>
</template>

<script>
import Equipos from '../../TI/Inventario/Equipos.vue';
import EquiposCalib from './EquiposCalib.vue';

const Facturasproveedores = r => require.ensure([], () => r(require('./DocsProvedores.vue')), 'conta');
const EquiposCalibracion = r => require.ensure([], () => r(require('./EquiposCalib.vue')), 'calib');
const modulo_id = 8;

export default
{
    data()
    {
        return {
            isLoading: false,
            listaPermisos: [],
        }
    },
    components:
    {
        'facturasproveedores': Facturasproveedores,
        'equiposcalib': EquiposCalibracion,
    },
    methods:
    {

    },
    mounted()
    {

    }
}
</script>
