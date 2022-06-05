<template>
<main class="main">
    <div class="container-fluid">
        <br>
        <vue-element-loading :active="isLoading" />
        <div class="row">
            <div class="col col-12" v-show="widgets.solicitudeserp">
                <solicitudeserp ref="solicitudeserp"></solicitudeserp>
            </div>
        </div>
        <vue-element-loading :active="isLoading" />
        <div class="row">
            <div class="col col-12" v-show="widgets.solicitudessoporte">
                <solicitudessoporte ref="solicitudessoporte"></solicitudessoporte>
            </div>
        </div>
    </div>
</main>
</template>

<script>
const SolicitudesSoporte = r => require.ensure([], () => r(require('./SolicitudesSoporte.vue')), 'ti');
const SolicitudesERP = r => require.ensure([], () => r(require('./SolicitudesERP.vue')), 'ti');
export default
{
    data()
    {
        return {
            isLoading: false,
            listaPermisos: [],
            widgets:
            {
                solicitudessoporte: false,
                solicitudeserp: false,
            }
        }
    },
    components:
    {
        'solicitudeserp': SolicitudesERP,
        'solicitudessoporte': SolicitudesSoporte,
    },
    methods:
    {
        getListaPermisos()
        {
            let me = this;
            axios.post('/permisosdashboard/porusuariomodulo',
                {
                    modulo_id: 7
                }).then(response =>
                {
                    me.listaPermisos = response.data;
                    me.isLoading = false;

                    if (me.listaPermisos.indexOf('solicitudeserp') >= 0)
                    {
                        me.widgets.solicitudeserp = true;
                    }
                    if (me.listaPermisos.indexOf('solicitudessoporte') >= 0)
                    {
                        me.widgets.solicitudessoporte = true;
                    }
                })
                .catch(function (error)
                {
                    console.log(error);
                });
        }

    },
    mounted()
    {
        this.getListaPermisos();
    }
}
</script>
