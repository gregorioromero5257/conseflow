<template>
    <main>
    <vue-element-loading :active="isLoadingExistencias"/>



<ul class="nav nav-tabs" role="tablist">
<li class="nav-item">
<a class="nav-link active show" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Almacén</a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Lotes</a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#messages" role="tab" aria-controls="messages" aria-selected="false">Stocks</a>
</li>
</ul>
<div class="tab-content">
<div class="tab-pane active show" id="home" role="tabpanel">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Almacén</th>
                <th>Stand</th>
                <th>Nivel</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in existencias" :value="item.id" :key="item.id">
                <td>{{ item.almacen }}</td>
                <td>{{ item.stand }}</td>
                <td>{{ item.nivel }}</td>
                <td class="text-right">{{ item.cantidad }}</td>
            </tr>
            </tbody>
        <tfoot>
            <th></th>
            <th></th>
            <th>Total</th>
            <th class="text-right">{{ totalExistencias }}</th>
        </tfoot>
    </table>
</div>
<div class="tab-pane" id="profile" role="tabpanel">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Lote</th>
                <th>Caducidad</th>
                <th>Comentario</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in lotes" :value="item.id" >
                <td>{{ item.id }}</td>
                <td>{{ item.caducidad }}</td>
                <td>{{ item.comentario }}</td>
                <td class="text-right">{{ item.cantidad }}</td>
            </tr>
            <tr>
                <td>{{ defectuoso_lote}}</td>
                <td></td>
                <td>Dañado o defectuoso</td>
                <td class="text-right">{{defectuoso_total}}</td>
            </tr>
        </tbody>
        <tfoot>
            <th></th>
            <th></th>
            <th>Total</th>
            <th class="text-right">{{ totalLotes }}</th>
        </tfoot>
    </table>
</div>
<div class="tab-pane" id="messages" role="tabpanel">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Sotck</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in stocks" :value="item.id" :key="item.id">
                <td>{{ item.nombre }}</td>
                <td class="text-right">{{ item.cantidad }}</td>
            </tr>
            <tr>
                <td>Defectuos o dañado</td>
                <td class="text-right">{{defectuoso_total}}</td>
            </tr>
        </tbody>
        <tfoot>
            <th>Total</th>
            <th class="text-right">{{ totalStocks }}</th>
        </tfoot>
    </table>
</div>
</div>



    </main>
</template>
<script>
export default {
    data() {
        return {
            isLoadingExistencias: false,
            existencias: [],
            lotes: [],
            stocks: [],
            defectuoso_lote:"",
            defectuoso_total:0,
        }
    },
    methods: {
        cargarExistencias(data) {
            let me = this;
            this.isLoadingExistencias = true;
            axios.post('/articulo/existencias', {
                articulo_id: data.id
            }).then(response => {
               console.log(response.data.defectuosos);
                me.existencias = response.data.existencias;
                me.lotes = response.data.lotes;
                me.stocks =  response.data.stocks;
                let total=0;
                response.data.defectuosos.forEach(d=>
                {
                    total+=d.cantidad;
                });

                let n=response.data.defectuosos.lenght;

                me.defectuoso_lote=n>0?response.data.defectuosos[0].lote:"";
                me.defectuoso_total=total;
                me.isLoadingExistencias = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    },
    mounted() {
    },
    computed: {
        totalExistencias() {
            return this.existencias.reduce((total, item, n) => {
                return Number(total) + (Number(item.cantidad));
            }, 0);
        },
        totalLotes() {
            return this.lotes.reduce((total, item, n) => {
                return Number(total) + (Number(item.cantidad));
            }, 0)+this.defectuoso_total;
        },
        totalStocks() {
            return this.stocks.reduce((total, item, n) => {
                return Number(total) + (Number(item.cantidad));
            }, 0)+this.defectuoso_total;
        },
    }
}
</script>
