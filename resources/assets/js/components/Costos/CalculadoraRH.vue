<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Listado de requisiocnes -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Calculadora sueldo Neto/Bruto
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>
          <!-- <div class="form-row">
            <div class="form-group col-md-3">
              <label>Cantidad</label>

                <input type="text" class="form-control" v-model="data.inicial" name="" value="">
            </div>
            <div class="form-group col-md-3">
              <label>Convertir a</label>
              <select class="form-control" v-model="opciones.tipo" @change="resetValues()" v-validate="'required'" data-vv-name="Tipo">
                <option value="Neto">Neto</option>
                <option value="Bruto">Bruto</option>
              </select>
            <span class="text-danger">{{errors.first('Tipo')}}</span>
            </div>
            <div class="form-group col-md-3">
              <label>Periodo</label>
              <select class="form-control" v-model="opciones.periodo" @change="resetValues()" v-validate="'required'" data-vv-name="Periodo">
                <option value="Semanal">Semanal</option>
                <option value="Quincenal">Quincenal</option>
                <option value="Mensual">Mensual</option>
              </select>
              <span class="text-danger">{{errors.first('Periodo')}}</span>
            </div>

          </div> -->
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Dias total trabajados para calculo finiquito</label>
              <input type="text" v-model="data.dias_t" class="form-control" name="" value="" v-validate="'required|decimal'" data-vv-name="Dias" @input="cambio()">
              <span class="text-danger">{{errors.first('Dias')}}</span>
            </div>
      
            <div class="form-group col-md-4">
              <label>Sueldo diario bruto</label>
              <input type="text" v-model="data.sueldo_bruto_d" class="form-control" name="" value="" v-validate="'required|decimal'" data-vv-name="Sueldo" @input="cambio()">
              <span class="text-danger">{{errors.first('Sueldo')}}</span>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-2">
              <button type="button" class="form-control" name="button" @click="updateSalary()"><i class="fas fa-calculator"></i>Calcular</button>
            </div>
            <div class="form-group col-md-2">
              <button type="button" class="form-control" name="button" @click="resetValues()"><i class="fas fa-save"></i>Nuevo</button>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <span class="text"><h6>Sueldo&nbsp;{{opciones.tipo}}&nbsp;{{opciones.periodo}} </h6></span>
            </div>
            <div class="form-group col-md-4">
              <span class="text"><h6>$&nbsp;{{data.final}}</h6></span>
            </div>
          </div>
            <hr>
            <div class="form-row">
              <div class="form-group col-md-4">
                <span class="text"><h6>Couta Empresa&nbsp;</h6></span>
              </div>
              <div class="form-group col-md-4">
                <span class="text"><h6>$&nbsp;{{data.couta_patronal}}</h6></span>
              </div>
            </div>
              <hr>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <span class="text"><h6>Finiquito&nbsp;</h6></span>
                </div>
                <div class="form-group col-md-4">
                  <span class="text"><h6>$&nbsp;{{data.finiquito}}</h6></span>
                </div>
              </div>
                <hr>
            <div class="form-row">
              <div class="form-group col-md-4">
                <span class="text"><h6>Costo total trabajador&nbsp; </h6></span>
              </div>
              <div class="form-group col-md-4">
                <span class="text"><h6>$&nbsp;{{(parseFloat(data.final) + parseFloat(data.couta_patronal) +parseFloat(data.finiquito)).toFixed(2)}}</h6></span>
              </div>
            </div>
              <hr>



        </div>
      </div>
    </div>
  </main>
</template>

<script>

import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      url: '/entrada',
      contador : 0,
      PermisosCRUD:{},
       data : {
        inicial: 0,//
        limite: 0,
        excedente: 0,
        porcentaje: 0,
        marginal: 0,
        cuota: 0,
        isr: 0,
        imss: 0,
        subsidio: 0,
        final: 0,
        temporal: 0,
        diario: 0,
        uma: 86.88,
        factor: 0,
        factor_calculos : 0,
        couta_patronal : 0,
        costo_total_trabajador : 0,
        dias: 0,
        sbc: 0,
        sdi: 0,
        dias_t : '',
        aguinaldo : 0,
        vacaciones : 0,
        prima_vacacional :0,
        finiquito : 0,
        sueldo_bruto_d : 0,
      },
      opciones : {
        tipo: 'Neto',//Bruto-Neto
        periodo: 'Mensual',//Semanal, Quincenal, Mensual
        subsidio: true,
        imss: true,
      },
       tablaSemanal : [
        0.01, 133.22, 1130.65, 1987.03, 2309.80, 2765.43, 5577.54, 8790.96, 16783.35, 22377.75, 67133.23
      ],
       cuotaSemanal : [
        0.00, 2.59, 66.36, 159.53, 211.19, 292.88, 893.55, 1649.34, 4047.05, 5837.23, 21054.11
      ],
       porcSemanal : [
        1.92, 6.40, 10.88, 16.00, 17.92, 21.36, 23.52, 30.00, 32.00, 34.00, 35.00
      ],
       ingSubSemanal : [
        407.34, 610.97, 799.69, 814.67, 1023.76, 1086.20, 1228.58, 1433.33, 1638.08, 1699.89
      ],
       canSubSemanal : [
        93.73, 93.66, 93.66, 90.44, 88.06, 81.55, 74.83, 67.83, 58.38, 50.12, 0.00
      ],
       tablaQuincenal : [
        0.01, 285.46, 2422.81, 4257.91, 4949.56, 5925.91, 11951.86, 18837.76, 35964.31, 47952.31, 143856.91
      ],
       cuotaQuincenal : [
        0.00, 5.55, 142.20, 341.85, 425.55, 627.60, 1914.75, 3534.30, 8672.25, 12508.35, 45115.95
      ],
       porcQuincenal : [
        1.92, 6.40, 10.88, 16.00, 17.92, 21.36, 23.52, 30.00, 32.00, 34.00, 35.00
      ],
       ingSubQuincenal : [
        872.86, 1309.21, 1713.61, 1745.71, 2193.76, 2327.56, 2632.66, 3071.41, 3510.16, 3642.61
      ],
       canSubQuincenal : [
        200.85, 200.70, 200.70, 193.80, 188.70, 174.75, 160.35, 145.35, 125.10, 107.40, 0.00
      ],
       tablaMensual : [
        0.01, 578.53, 4910.29, 8629.21, 10031.08, 12009.95, 24222.32, 38177.70, 72887.51, 97183.34, 291550.01
      ],
       cuotaMensual : [
        0.00, 11.11, 288.33, 692.96, 917.26, 1271.87, 3880.44, 7162.74, 17575.68, 25350.35, 91435.02
      ],
       porcMensual : [
        1.92, 6.40, 10.88, 16.00, 17.92, 21.36, 23.52, 30.00, 32.00, 34.00, 35.00
      ],
       ingSubMensual : [
        1768.97, 2653.39, 3472.85, 3537.88, 4446.16, 4717.19, 5335.43, 6224.68, 7113.91, 7382.34
      ],
       canSubMensual : [
        407.02, 406.83, 406.62, 392.77, 382.46, 354.23, 324.87, 294.63, 253.54, 217.61, 0.00
      ],
      isLoading: false,
    }
  },
  computed:{
  },
  methods : {
    cambio(){
    this.data.inicial = (this.data.dias_t * this.data.sueldo_bruto_d).toString();
    },

    getTable(value) {

      if (value === "Semanal") {
        return this.tablaSemanal;
      }
      else if (value === "Quincenal") {
        return this.tablaQuincenal;
      }
      else if (value === "Mensual") {
        return this.tablaMensual;
      }
    },

    getCuota(value) {
      if (value === "Semanal") {
        return this.cuotaSemanal;
      }
      else if (value === "Quincenal") {
        return this.cuotaQuincenal;
      }
      else if (value === "Mensual") {
        return this.cuotaMensual;
      }
    },

    getPorcentaje(value) {
      if (value === "Semanal") {
        return this.porcSemanal;
      }
      else if (value === "Quincenal") {
        return this.porcQuincenal;
      }
      else if (value === "Mensual") {
        return this.porcMensual;
      }
    },

    getIngSub(value) {

      if (value === 'Semanal') {
        return this.ingSubSemanal;
      }
      else if (value === 'Quincenal') {
        return this.ingSubQuincenal;
      }
      else if (value === 'Mensual') {
        return this.ingSubMensual;
      }
    },

    getCanSub(value) {

      if (value === "Semanal") {
        return this.canSubSemanal;
      }
      else if (value === "Quincenal") {
        return this.canSubQuincenal;
      }
      else if (value === "Mensual") {
        return this.canSubMensual;
      }
    },

    factorintegracion(){
      var uno,dos,tres;
      uno = parseFloat(this.data.dias_t);
      dos = 6 * 0.25;//pendiente antiguedad
      if(parseFloat(this.data.dias_t) <= 365)
      {
        tres = 15;
      }else {
        tres = (parseFloat(this.data.dias_t) / 365) * 15
      }
      //console.log(uno+' '+dos+' '+tres);
      this.data.factor = (uno + dos + tres) / parseFloat(this.data.dias_t);

    },

    calcSBC(value) {
      var sbc;
      this.factorintegracion();
      if (value === "Semanal") {
        this.data.dias = 7,
        this.data.sdi = parseFloat(this.data.inicial)/this.data.dias;
        sbc = this.data.sdi * this.data.factor;
      }
      else if (value === "Quincenal") {
        this.data.dias = 15,
        this.data.sdi = parseFloat(this.data.inicial)/this.data.dias;
        sbc = this.data.sdi * this.data.factor;
      }
      else if (value === "Mensual") {
        this.data.dias = 30,
        this.data.sdi = parseFloat(this.data.inicial)/this.data.dias;
        sbc = this.data.sdi * this.data.factor;
      }
      if (sbc > 2112.25) {
        sbc = 2112.25;
      }
      return sbc.toFixed(2);
    },

    calcIMSS(value){
      var prestaciones,pensionados,invalidez,cesantia,imss,excedente;

      if (value > (3 * this.data.uma)) {
      excedente = (value - (3 * this.data.uma)) * .004 * this.data.dias;
      }
      else {
        excedente = 0;
      }
      // especie
      prestaciones = value * .0025 * this.data.dias;
      pensionados = value * .00375 * this.data.dias;
      invalidez = value * .00625 * this.data.dias;
      cesantia = value * 0.01125 * this.data.dias;
      imss = parseFloat(excedente) + parseFloat(prestaciones) + parseFloat(pensionados) + parseFloat(invalidez) + parseFloat(cesantia);


      return  imss.toFixed(2);
    },

    calcCuotaP(value){
      var riesgo_trabajo_patron,especie_patron,excendete_patron,pensionados_patron,prestaciones_patron,invalidez_patron,retiro_patron,
      cesantia_patron,guarderia_patron,infonavit_patron,couta_patronal;

      excendete_patron = (value - (3 * this.data.uma)) * .01100 * this.data.dias;

      riesgo_trabajo_patron = (value * this.data.dias) * 0.035888;
      especie_patron = this.data.dias * this.data.uma * 0.20400;
      pensionados_patron = value * this.data.dias * 0.01050;
      prestaciones_patron = value * this.data.dias * 0.00700;
      invalidez_patron = value * this.data.dias * 0.01750;
      retiro_patron =  value * this.data.dias * 0.0200;
      cesantia_patron = value * this.data.dias * 0.03150;
      guarderia_patron = value * this.data.dias * 0.01000;
      infonavit_patron = value * this.data.dias * 0.05000;

      couta_patronal = riesgo_trabajo_patron + especie_patron + excendete_patron + pensionados_patron + prestaciones_patron + invalidez_patron
      + retiro_patron + cesantia_patron + guarderia_patron + infonavit_patron;

      return couta_patronal.toFixed(2);
    },
// 11.63
    finiquito(){
      var finiquito;
      var factor_dia_trabajado = 15 / 365;
      var uno;
      uno = factor_dia_trabajado * parseFloat(this.data.dias_t);
      this.data.aguinaldo = uno * this.data.sdi;

      var factor_dia_trabajado_vac = 6 / 365;
      var dos;
      dos = factor_dia_trabajado_vac * parseFloat(this.data.dias_t);
      this.data.vacaciones = dos * this.data.sdi;

      this.data.prima_vacacional = this.data.vacaciones * 0.25;
      finiquito = this.data.aguinaldo  + this.data.vacaciones + this.data.prima_vacacional;
      return finiquito.toFixed(2);
    },

    calculateSalary(){
      var tabla, cuota, porcentaje, position, subsidioIng, subsidioCan, i;
      // this.factorintegracion();
      tabla = this.getTable(this.opciones.periodo);
      cuota = this.getCuota(this.opciones.periodo);
      porcentaje = this.getPorcentaje(this.opciones.periodo);
      subsidioIng = this.getIngSub(this.opciones.periodo);
      subsidioCan = this.getCanSub(this.opciones.periodo);
      for (i=0; i < tabla.length; i++) {
        if (parseFloat(this.data.inicial) >= tabla[i] && parseFloat(this.data.inicial) < tabla [i+1]) {
          position = i;
        }
        else if (parseFloat(this.data.inicial) >= tabla[tabla.length - 1]) {
          position = tabla.length - 1;
        }
      }
      this.data.limite = tabla[position];
      this.data.excedente = parseFloat(this.data.inicial) - this.data.limite;
      this.data.porcentaje = porcentaje[position];
      this.data.cuota = cuota[position];
      this.data.marginal = this.data.excedente * (this.data.porcentaje/100);
      this.data.isr = this.data.cuota + this.data.marginal;
      this.data.sbc = this.calcSBC(this.opciones.periodo);
      this.data.imss = this.calcIMSS(this.data.sbc);


        for (i=0; i < subsidioIng.length; i++) {
          if (parseFloat(this.data.inicial) < subsidioIng[i]) {
            position = i;
            break;
          }
          else if (parseFloat(this.data.inicial) >= subsidioIng[subsidioIng.length - 1]) {
            position = subsidioIng.length;
          }
        }

        this.data.subsidio = subsidioCan[position];
        this.data.final = (parseFloat(this.data.inicial) - this.data.imss - this.data.isr + this.data.subsidio).toFixed(2);

      // this.data.finiquito = this.finiquito();
      // this.data.couta_patronal = this.calcCuotaP(this.data.sbc);
      // this.data.costo_total_trabajador = parseFloat(this.data.final) + parseFloat(this.data.couta_patronal) + parseFloat(this.data.finiquito);
    },



    resetValues () {
      // this.data.inicial = 0;
      this.data.limite = 0;
      this.data.excedente = 0;
      this.data.porcentaje = 0;
      this.data.marginal = 0;
      this.data.cuota = 0;
      this.data.isr = 0;
      this.data.imss = 0;
      this.data.subsidio = 0;
      this.data.final = 0;
      this.data.temporal = 0;
      this.data.diario = 0;
      this.data.dias = 0;
      this.data.sbc = 0;
      this.data.sdi = 0;
      this.data.couta_patronal = 0;

      // this.opciones.tipo = '';
        // this.opciones.periodo = '';
    },


    updateSalary() {
          if (this.opciones.tipo === "Bruto") {
            this.isLoading = true;
              this.data.temporal= parseFloat(this.data.inicial);

              while (this.data.final < this.data.temporal) {
                this.calculateSalary();
                this.data.inicial = (parseFloat(this.data.inicial) + .01).toFixed(2);
              }
              while (this.data.final > this.data.temporal) {
                this.calculateSalary();
                this.data.inicial = (parseFloat(this.data.inicial) - .01).toFixed(2);
              }
              this.data.final = parseFloat(this.data.inicial).toFixed(2);
              this.data.inicial = this.data.temporal;

          }
          else {
            this.calculateSalary();
            // this.data.couta_patronal = this.calcCuotaP(this.data.sbc);

          }
          this.data.finiquito = this.finiquito();
          this.data.couta_patronal = this.calcCuotaP(this.data.sbc);
          this.data.costo_total_trabajador = parseFloat(this.data.final) + parseFloat(this.data.couta_patronal) + parseFloat(this.data.finiquito);
          this.isLoading = false;


    }

  },
  mounted() {

  }
}
</script>
<style>

.btn-greg {
  color: #fff;
  background-color: #e9b443;
  border-color: #e9b443; }

  </style>
