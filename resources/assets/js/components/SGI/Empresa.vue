<template>
<main class="main">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Datos generales
            </div>
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <h5 class="ml-5 mt-2">Misión</h5>
                            <p class="mt-3 mx-5">
                                Crear, innovar e implementar soluciones integrales en materia de ingeniería
                                y metrología, generando valor a nuestros cliente y colaboradores a través de
                                la mejora continua y el desarrollo sustentable.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <h5 class="ml-5 mt-2">Visión</h5>
                            <p class="mt-3 mx-5">
                                Ser la empresa líder en soluciones tecnológicas en el área de
                                la industria del proceso mecánica de instrumentación y automatización.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <h5 class="ml-5 mt-2">Política Integral</h5>
                            <p class="mt-3 mx-5">
                                En CONSERFLOW proporcionamos productos y servicios que cumplen
                                los requisitos de nuestros clientes y los legales aplicables de la
                                industria de proceso. Para ello implementamos una cultura organizacional
                                comprometida con la seguridad, cuidado del medio ambiente y mejora continua.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <h5 class="ml-5 mt-2">Valores</h5>
                            <div class="container mx-5">
                                <p class="mr-3">
                                    <br>
                                    <span class="font-weight-bold">Respeto:</span>
                                    Tratar a todas las personas por igual, valorar su trabajo
                                    y corregir los errores, es un trato de respeto. Cuando las
                                    personas son respetadas se sienten aceptadas y motivadas por
                                    seguir con sus trabajos y desarrollando sus capacidades
                                    personales y laborales.
                                </p>
                                <p class="mr-3">
                                    <span class="font-weight-bold">Disciplina:</span>
                                    La disciplina de las personas se refleja en los resultados
                                    de su trabajo. Ser disciplinado se relaciona con la puntualidad,
                                    seguir las normas, ser proactivo, proponerse alcanzar objetivos y
                                    ser exigente. Por medio de la disciplina también se logran las metas.
                                </p>

                                <p class="mr-3">
                                    <span class="font-weight-bold">Integridad:</span>
                                    Ser íntegro se refiere a ser auténtico y honesto durante las relaciones
                                    laborales. Exponer una situación o caso tal cual como es, no hacer
                                    estafas ni falsas promesas a otras personas, clientes o proveedores.
                                </p>

                                <p class="mr-3">
                                    <span class="font-weight-bold">Responsabilidad laboral:</span>
                                    La responsabilidad se puede apreciar a partir de la puntualidad,
                                    la disciplina, el cumplimento de las labores asignadas, la integridad
                                    y la honestidad entre todos aquellos que trabajan en una misma empresa.
                                </p>

                                <p class="mr-3">
                                    La responsabilidad es una parte fundamental de la gestión empresarial,
                                    supone un orden y una serie de normativas a cumplir en beneficio de todas
                                    las personas que se relacionan con la empresa, también busca establecer cuáles
                                    contribuciones se pueden hacer en beneficio de la sociedad, la comunidad y
                                    el medio ambiente, de allí que se establezcan diversos proyectos con estas finalidades.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card" style="width: 30rem;">
                            <h5 class="ml-5 mt-3">Logo empresarial</h5>
                            <div class="container ml-5">
                                <img style="cursor:pointer" @click="VerLogo" src="img/conserflow.png" height="120px" alt="CONSEFLOW">
                            </div>
                            <div class="card-body">
                                <button class="float-sm-right btn btn-secondary" @click="VerLogo">
                                    <i class="fas fa-eye mr-1"></i> Mostrar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card" style="width: 30rem;">
                            <h5 class="ml-5 mt-3">Hoja membretada</h5>
                            <div class="container ml-5">
                                <a class="btn btn-sm btn-secondary" href="procedimientos/Hoja_membretada_Conserflow.docx">
                                <i class="fas fa-file-download mr-1"></i>
                                Hoja menbratada - Conserflow</a>
                                <br>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</main>
</template>

<script>
export default
{
    data()
    {
        return {

        }
    },
    methods:
    {
        VerLogo()
        {
            window.open("../img/conserflow.png")
        },
        DescargarHoja()
        {

        }
    },
    mounted()
    {

    }
}
</script>
