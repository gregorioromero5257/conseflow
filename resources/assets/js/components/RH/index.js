const Contratos = r => require.ensure([], () => r(require('./Empleados/Contratos.vue')), 'rh')
const CVInterno = r => require.ensure([], () => r(require('./Empleados/CVInterno.vue')), 'rh')
const FormatosE = r => require.ensure([], () => r(require('./Empleados/FormatosE.vue')), 'rh')
const AltaConsultas = r => require.ensure([], () => r(require('./Empleados/Consultas.vue')), 'rh')
const Empleados = r => require.ensure([], () => r(require('./Empleados/Empleados.vue')), 'rh')
const DireccionEmpleado = r => require.ensure([], () => r(require('./Empleados/Direccion-Empleado.vue')), 'rh')
const NominaQuincenal = r => require.ensure([], () => r(require('./Nomina/NomQuincenal.vue')), 'rh')
const Recibos = r => require.ensure([], () => r(require('./Nomina/Recibos.vue')), 'rh')
const NominaSemanal = r => require.ensure([], () => r(require('./Nomina/NomSemanal.vue')), 'rh')
const NominaConsultas = r => require.ensure([], () => r(require('./Nomina/Consultas.vue')), 'rh')
const Aguinaldo = r => require.ensure([], () => r(require('./Nomina/Aguinaldo.vue')), 'rh')
const Finiquito = r => require.ensure([], () => r(require('./Nomina/Finiquitos.vue')), 'rh')
const Formatos = r => require.ensure([], () => r(require('./Prestamos/Formatos.vue')), 'rh')
const Prestamos = r => require.ensure([], () => r(require('./Prestamos/Prestamos.vue')), 'rh')
const PrestamosPagos = r => require.ensure([], () => r(require('./Prestamos/Pagos.vue')), 'rh')
const PrestamosConsultas = r => require.ensure([], () => r(require('./Prestamos/Consultas.vue')), 'rh')
const Vacaciones = r => require.ensure([], () => r(require('./Vacaciones/Solicitudes.vue')), 'rh')
const Reportes = r => require.ensure([], () => r(require('./Asistencias/Reportes.vue')), 'rh')
const AsistenciasConsultas = r => require.ensure([], () => r(require('./Asistencias/Consultas.vue')), 'rh')
const HorasExtra = r => require.ensure([], () => r(require('./Asistencias/Horas-extra.vue')), 'rh')
const Registro = r => require.ensure([], () => r(require('./Asistencias/Asistencia.vue')), 'rh')
const Manual = r => require.ensure([], () => r(require('./Manual/Manual.vue')), 'rh')
const Respaldos = r => require.ensure([], () => r(require('./Admin/Respaldos.vue')), 'rh')
const ControlUsuarios = r => require.ensure([], () => r(require('./Admin/Control-usuarios.vue')), 'rh')
const TipoNomina = r => require.ensure([], () => r(require('./Catalogos/Tipo-nomina.vue')), 'rh')
const TipoUbicacion = r => require.ensure([], () => r(require('./Catalogos/Tipo-ubicacion.vue')), 'rh')
const TipoContrato = r => require.ensure([], () => r(require('./Catalogos/Tipo-contrato.vue')), 'rh')
const DireccionAdministrativa = r => require.ensure([], () => r(require('./Catalogos/Direccion-administrativa.vue')), 'rh')
const TipoDescuento = r => require.ensure([], () => r(require('./Catalogos/Tipo-descuento.vue')), 'rh')
const TipoPercepcion = r => require.ensure([], () => r(require('./Catalogos/Tipo-Percepcion.vue')), 'rh')
const TipoDeduccion = r => require.ensure([], () => r(require('./Catalogos/Tipo-Deduccion.vue')), 'rh')
const TipoHorario = r => require.ensure([], () => r(require('./Catalogos/Tipo-Horario.vue')), 'rh')
const TipoFinalizacion = r => require.ensure([], () => r(require('./Catalogos/Tipo-Finalizacion.vue')), 'rh')
const EstadoCivil = r => require.ensure([], () => r(require('./Catalogos/Estado-Civil.vue')), 'rh')
const Grados = r => require.ensure([], () => r(require('./Catalogos/Grados.vue')), 'rh')
const Bancos = r => require.ensure([], () => r(require('./Catalogos/Bancos.vue')), 'rh')
const Expedientes = r => require.ensure([], () => r(require('./Empleados/Expedientes.vue')), 'rh')
const Departamento = r => require.ensure([], () => r(require('./Catalogos/Departamento.vue')), 'rh')
const Puesto = r => require.ensure([], () => r(require('./Catalogos/Puesto.vue')), 'rh')
const Correos = r => require.ensure([], () => r(require('./Empleados/Correos.vue')), 'rh')
const Telefonos = r => require.ensure([], () => r(require('./Empleados/Telefonos.vue')), 'rh')
const Beneficiario = r => require.ensure([], () => r(require('./Beneficiarios/Beneficiarios.vue')), 'rh')
const Familiares = r => require.ensure([], () => r(require('./Familiares/Familiares.vue')), 'rh')
const Horario = r => require.ensure([], () => r(require('./Catalogos/Horarios.vue')), 'rh')
const Escolaridades = r => require.ensure([], () => r(require('./Empleados/Escolaridades.vue')), 'rh')
const Referencias = r => require.ensure([], () => r(require('./Empleados/Referencias.vue')), 'rh')
const ExperienciasLaborales = r => require.ensure([], () => r(require('./Empleados/Exp-laborales.vue')), 'rh')
const Contactos = r => require.ensure([], () => r(require('./Empleados/Contactos.vue')), 'rh')
const Conocidos = r => require.ensure([], () => r(require('./Empleados/Conocidos.vue')), 'rh')
const DatosBancariosEmpleados = r => require.ensure([], () => r(require('./Empleados/Datos-ban-emp.vue')), 'rh')
const Percepciones = r => require.ensure([], () => r(require('./Nomina/Percepciones.vue')), 'rh')
const Deducciones = r => require.ensure([], () => r(require('./Nomina/Deducciones.vue')), 'rh')
    // const Sueldos = r => require.ensure([], () => r(require('./Nomina/Sueldos.vue')), 'rh')
const Solicitudes = r => require.ensure([], () => r(require('./Permisos/Solicitud.vue')), 'rh')
const Bajas = r => require.ensure([], () => r(require('./Empleados/Bajas.vue')), 'rh')
const Descuento = r => require.ensure([], () => r(require('./Descuentos/Descuentos.vue')), 'rh')
const PagosDescuento = r => require.ensure([], () => r(require('./Descuentos/Pagos.vue')), 'rh')
const OrganigramaGeneral = r => require.ensure([], () => r(require('./Información de la empresa/ChartOrg.vue')), 'rh')
    //const OrganigramaGeneral = r => require.ensure([], () => r(require('./Organigrama/ChartOrg.vue')), 'rh')
const ExtInt = r => require.ensure([], () => r(require('./Información de la empresa/ExtInt.vue')), 'rh')
const Dashboard = r => require.ensure([], () => r(require('./Dashboard/Dashboard.vue')), 'rh')
const Cumpleaños = r => require.ensure([], () => r(require('./Empleados/Cumpleaños.vue')), 'rh')
const Empresas = r => require.ensure([], () => r(require('./Catalogos/Empresas.vue')), 'rh')
    // const TipoFinalizacion = r => require.ensure([], () => r(require('./Catalogos/Tipo-Finalizacion.vue')), 'rh')
const ReporteFiniquito = r => require.ensure([], () => r(require('./Reportes/Finiquitos.vue')), 'rh')
const ReporteNomina = r => require.ensure([], () => r(require('./Reportes/Nomina.vue')), 'rh')
const ReporteBancarios = r => require.ensure([], () => r(require('./Reportes/Bancarios.vue')), 'rh')
const ReportePrestamos = r => require.ensure([], () => r(require('./Reportes/Prestamos.vue')), 'rh')
const ReporteResguardos = r => require.ensure([], () => r(require('./Reportes/Resguardos.vue')), 'rh')
const Retardos = r => require.ensure([], () => r(require('./Asistencias/Retardo.vue')), 'rh')
const ReportePermisos = r => require.ensure([], () => r(require('./Reportes/ReporteEmpleado.vue')),'rh')
const ControlTiempo = r => require.ensure([], () => r (require('./Reportes/ControlTiempo')),'rh')
const ControlTiempoHE = r => require.ensure([], () => r (require('./Reportes/ControlTiempoHE')),'rh')
const AsignarTiempo = r => require.ensure([], () => r (require('./Reportes/AsignarTiempo')), 'rh')
const Directorio = r => require.ensure([], () => r (require('./Información de la empresa/Directorio')),'rh')
const DatosGenerales = r => require.ensure([], () => r (require('./Información de la empresa/DatosGenerales.vue')),'rh')
// const ControlNomina = r => require.ensure([], () => r (require('./Nomina/ControlNomina.vue')), 'rh')
const Plan = r => require.ensure([], () => r (require('./PlanCapacitacion.vue')), 'rh')
const Calculadora = r => require.ensure([], () => r(require('./Nomina/CalculadoraRH.vue')), 'rh')
const CalculadoraF = r => require.ensure([], () => r(require('./Nomina/CalculadoraFiniquitos.vue')), 'rh')
// const PruebaPrestamo = r => require.ensure([], () => r (require('./Prestamos/PruebaPrestamo.vue')),'rh')
const Cuadrilla = r => require.ensure([], () => r(require('./Reportes/Cuadrilla.vue')),'rh')
const Documentos = r => require.ensure([], () => r(require('./Empleados/Documentos.vue')),'rh')

const ControlCB = r => require.ensure([], () => r (require('./ControlCB.vue')), 'rh')
const ControlQR = r => require.ensure([], () => r (require('./ControlTiempoRH.vue')), 'rh')
// Resguardos
const Resguardos = r => require.ensure([], () => r (require('./Empleados/Resguardos.vue')), 'rh')
// Credenciales
const Credenciales = r => require.ensure([], () => r (require('./Credenciales/Credenciales.vue')), 'rh')
// const ControlTiempoHoras = r => require.ensure([], () => r (require('./ControlTiempoHoras.vue')), 'rh')

// Control Tiempo empleados
const TiempoEmpleados = r => require.ensure([], () => r (require('./ControlTiempo/TiempoEmpleados.vue')), 'rh')
const RegistroAsisteciaSitio = r => require.ensure([], () => r (require('./AsistenciaSitio.vue')), 'rh')


const routes = [
    { path: '/rh/empleados/contratos', component: Contratos },
    { path: '/rh/empleados/cv', component: CVInterno },
    { path: '/rh/empleados/formatose', component: FormatosE },
    { path: '/rh/empleados/consultas', component: AltaConsultas },
    { path: '/rh/empleados/alta', component: Empleados },
    { path: '/rh/empleados/direcciones', component: DireccionEmpleado },
    { path: '/rh/empleados/referencias', component: Referencias },
    { path: '/rh/nomina/recibos', component: Recibos },
    { path: '/rh/nomina/semanal/', component: NominaSemanal },
    { path: '/rh/nomina/quincenal/', component: NominaQuincenal },
    { path: '/rh/nomina/consultas', component: NominaConsultas },
    { path: '/rh/nomina/aguinaldo', component: Aguinaldo },
    { path: '/rh/nomina/finiquitos', component: Finiquito },
    { path: '/rh/prestamos/formatos', component: Formatos },
    { path: '/rh/prestamos/prestamos', component: Prestamos },
    { path: '/rh/prestamos/pagos', component: PrestamosPagos },
    { path: '/rh/prestamos/consultas', component: PrestamosConsultas },
    { path: '/rh/vacaciones/solicitudes', component: Vacaciones },
    { path: '/rh/asistencias/reportes', component: Reportes },
    { path: '/rh/asistencias/asistencias', component: AsistenciasConsultas },
    { path: '/rh/asistencias/horas-extra', component: HorasExtra },
    { path: '/rh/asistencias/asistencia', component: Registro },
    { path: '/rh/manual', component: Manual },
    { path: '/rh/admin/respaldos', component: Respaldos },
    { path: '/rh/admin/control-usuarios', component: ControlUsuarios },
    { path: '/rh/tipo-nomina', component: TipoNomina },
    { path: '/rh/tipo-ubicacion', component: TipoUbicacion },
    { path: '/rh/tipo-contrato', component: TipoContrato },
    { path: '/rh/dir-administrativa', component: DireccionAdministrativa },
    { path: '/rh/tipo-descuento', component: TipoDescuento },
    { path: '/rh/catalogos/tipo-percepciones', component: TipoPercepcion },
    { path: '/rh/catalogos/tipo-deducciones', component: TipoDeduccion },
    { path: '/rh/catalogos/tipo-horarios', component: TipoHorario },
    { path: '/rh/catalogos/finalizacion', component: TipoFinalizacion },
    { path: '/rh/catalogos/grados', component: Grados },
    { path: '/rh/catalogos/bancos', component: Bancos },
    { path: '/rh/estado-civil', component: EstadoCivil },
    // { path: '/rh/extensiones', component: Extension },
    { path: '/rh/expedientes', component: Expedientes },
    { path: '/rh/catalogos/departamentos', component: Departamento },
    { path: '/rh/catalogos/puestos', component: Puesto },
    { path: '/rh/beneficiarios/beneficiarios', component: Beneficiario },
    { path: '/rh/familiare/familiare', component: Familiares },
    { path: '/rh/catalogos/horarios', component: Horario },
    { path: '/rh/escolaridades', component: Escolaridades },
    { path: '/rh/exp-laborales', component: ExperienciasLaborales },
    { path: '/rh/contactos', component: Contactos },
    { path: '/rh/conocidos', component: Conocidos },
    { path: '/rh/datos-ban-emp', component: DatosBancariosEmpleados },
    { path: '/rh/nomina/percepcion', component: Percepciones },
    { path: '/rh/nomina/deduccion', component: Deducciones },
    { path: '/rh/nomina/calculadora', component: Calculadora },
    { path: '/rh/nomina/calculadorafiniquitos', component: CalculadoraF },
    // { path: '/rh/nomina/sueldos', component: Sueldos },
    { path: '/rh/empleados/correos/', component: Correos },
    { path: '/rh/empleados/telefonos/', component: Telefonos },
    { path: '/rh/permisos/solicitud/', component: Solicitudes },
    { path: '/rh/empleados/bajas', component: Bajas },
    { path: '/rh/descuento/descuento', component: Descuento },
    { path: '/rh/descuento/pagos', component: PagosDescuento },
    { path: '/rh/organigrama/general/', component: OrganigramaGeneral },
    { path: '/rh/info/extint/', component: ExtInt },
    { path: '/rh/dashboard', component: Dashboard },
    { path: '/rh/empleados/cumpleaños/', component: Cumpleaños },
    { path: '/rh/catalogos/empresas/', component: Empresas },
    { path: '/rh/reportes/finiquitos/', component: ReporteFiniquito },
    { path: '/rh/reportes/nomina/', component: ReporteNomina },
    { path: '/rh/reportes/bancarios/', component: ReporteBancarios },
    { path: '/rh/reportes/prestamos/', component: ReportePrestamos },
    { path: '/rh/reportes/resguardos/', component: ReporteResguardos },
    { path: '/rh/asistencias/retardos/', component: Retardos },
    { path: '/rh/reportes/permisosemp/', component: ReportePermisos },
    { path: '/rh/reportes/controltiempo', component: ControlTiempo },
    { path: '/rh/reportes/controltiempo/he', component: ControlTiempoHE },
    { path: '/rh/reportes/asignartiempo/', component : AsignarTiempo },
    { path: '/rh/info/directorio', component: Directorio },
    { path: '/rh/info/datosgenerales', component: DatosGenerales },
 // { path: '/rh/nomina/controlnomina/', component: ControlNomina },
    { path: '/plan', component: Plan },
    // { path: '/rh/prestamos/pruebaprestamo/', PruebaPrestamo},
    { path: '/rh/reportes/cuadrilla', component: Cuadrilla},
    { path: '/rh/reportes/documentos', component: Documentos},
    { path: '/rh/control/cb', component: ControlCB},
    { path: '/rh/control/tiempo/qr', component: ControlQR},
    { path: '/rh/resguardos', component: Resguardos},
    { path: '/rh/credenciales', component: Credenciales},
    { path: '/rh/tiempoempleados', component: TiempoEmpleados},
    { path: '/rh/asistencia/sitio', component: RegistroAsisteciaSitio},

]


export default routes
