<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Contrato de empleado...</title>
  <style>
    body {
      font-size: 12px;
    }

    .borde {
      margin: 60px 60px 60px 60px;
    }

    .notification {
      font-family: Arial, Helvetica, sans-serif;
      font-weight: normal;
      text-align: center;
    }

    .notificationx {
      font-family: Arial, Helvetica, sans-serif;
      font-weight: normal;
      text-align: right;
    }

    .pr {
      font-family: Arial, Helvetica, sans-serif;
      font-weight: normal;
      font-size: 12px;
      text-align: center;
    }

    .titul {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      text-align: left;
    }

    .textoo {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      text-align: justify;
      text-transform: uppercase;
    }

    ul.a {
      list-style-type: circle;
    }

    ul.b {
      list-style-type: square;
    }

    ​ol.c {
      list-style-type: upper-roman;
    }

    ol.d {
      list-style-type: lower-alpha;
    }

    .letratablapieuno {
      font-size: 12px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: center;
      font-weight: normal;
      padding-top: 0px;
      border-bottom: 1px solid black;
    }

    .letratablapiedos {
      font-size: 12px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: center;
      font-weight: normal;
      text-transform: uppercase;
    }

    div.page_break+div.page_break {
      page-break-before: always;
    }
  </style>
</head>

@if($contratopdf->tipo_contrato_id==1)

<body class="borde">
  <div class="notification">
    <div class="notificationx">
      <img src="img/CSCT.png" width="120">
    </div>
    <table class="pr">
      <h2> CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V. </h2>
      <p></p>
      <p></p>
    </table>

    <b>CONTRATO INDIVIDUAL DE TRABAJO POR TIEMPO DETERMINADO.</b>
    <table class="textoo">
      <p>CONTRATO INDIVIDUAL DE TRABAJO POR TIEMPO DETERMINADO QUE CELEBRAN POR UNA PARTE <u> CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V.</u>
        REPRESENTADA EN ESTE ACTO POR EL <u>C. DIEGO CRUZ MARTINEZ</u> A QUIEN EN LO SUCESIVO SE LE DENOMINARA “EL EMPLEADOR” Y POR LA OTRA PARTE
        EL <u> C. {{$contratopdf->entrega}}</u> POR SU PROPIO DERECHO, A QUIEN EN ADELANTE SE LE DENOMINARA COMO “EL TRABAJADOR”, DE CONFORMIDAD CON
        LAS SIGUIENTES:
      </p>
    </table>

    <table class="titul">
      <p><b>DECLARACIONES</b></p>
    </table>
    <table class="textoo">
      <ol ol type="I">
        <li>Declara el EMPLEADOR ser una sociedad debidamente constituida de conformidad con las leyes mexicanas, con registro Federal de Contribuyente <b>CSC050609LF7</b>,
          que tiene su domicilio fiscal en Avenida Francisco I. Madero No. 1000 Colonia Maria de la piedad C.P. 96410 en Coatzacoalcos, Veracruz, la cual se encuentra
          debidamente representada en este acto por el C. DIEGO CRUZ MARTINEZ.</li>
        <p></p>
        <li>Declara el TRABAJADOR <u> C. {{$contratopdf->entrega}},</u> ser de nacionalidad MEXICANA con <u>{{$edad}}</u> años de edad, estado civil <u>{{$contratopdf->civil}}</u> con Registro Federal de
          Contribuyente <u>{{$contratopdf->erfc}}</u> con clave única de registro de población <u>{{$contratopdf->ecur}}</u> y con domicilio en Calle: <u>{{$contratopdf->calle}}</u>, Colonia: <u>{{$contratopdf->colonia}}</u>,
          Numero Exterior:<u>{{$contratopdf->numero_exterior}}</u>, C.p: <u>{{$contratopdf->codigo_postal}}</u>, <u>{{$contratopdf->localidad}}, {{$contratopdf->entidad_federativa}}.</u> </li>
        <p></p>
        <li>EL EMPLEADOR manifiesta que tiene la necesidad temporal de contratar a un TRABAJADOR que labore en el puesto de: <u><b>{{$contratopdf->areadmin}}</u></b> Para Proyecto: <b><u>{{$contratopdf->proyn}}.</b></u></li>
        <p></p>
        <li>EL TRABAJADOR manifiesta expresamente que tiene conocimiento de la necesidad temporal del EMPLEADOR y que posee los conocimientos necesarios y aptitudes para prestar sus servicios en el puesto mencionado en la declaración anterior.</li>
        <p></p>
        <li>Ambas partes de conformidad con las anteriores declaraciones están conformes en celebrar el presente contrato al tenor de las siguientes:</li>
        <p></p>
      </ol>
    </table>

    <table class="titul">
      <p><b>CLAUSULAS</b></p>
    </table>
    <table class="textoo">
      <ol ol type="I">
        <li>TIPO DE TRABAJO</li>
        <p>El TRABAJADOR se obliga a prestar sus servicios temporales por tiempo determinado en el puesto de: <u><b>{{$contratopdf->areadmin}}</b></u>Para Proyecto: <b><u>{{$contratopdf->proyn}}.</u> </b>Debiendo ejecutar
          su trabajo de conformidad con las instrucciones del EMPLEADOR con esmero y eficiencia, quedado obligado a desempeñar todas las labores anexas o conexas con su obligación
          principal y las demás que se le ordene y que sean compatibles con sus conocimientos y aptitudes, debiendo observar todos los lineamientos, disposiciones, circulares y todos los ordenamientos legales que sean aplicables.</p>
        &nbsp;
        <li>LUGAR DE TRABAJO</li>
        <p>EL TRABAJADOR deberá desempeñar su trabajo en las instalaciones del EMPLEADOR, así como en cualquier otra planta, establecimiento que tenga el EMPLEADOR o que llegara a establecer en el estado de: VERACRUZ o en la república Mexicana.</p>
        &nbsp;
        <li>TIEMPO DE DURACION DEL TRABAJO, DE LA JORNADA Y HORARIO.</li>
        <ol type="A">
          <p></p>
          <li>Los servicios encomendados tendrán un periodo con fecha de inicio el día <u>{{$dia_ingreso}} de {{$mes_inicio_letra}} del {{$anio_ingreso}}</u> y fecha de terminación el día <u>{{$dia_fin}} de {{$mes_fin_letra}} del {{$anio_fin}}</u>, vigencia que ampara el presente contrato, a cuyo efecto “el patrón” pondrá a disposición del “trabajador”,
            durante todo el tiempo de la prestación de sus servicios, los materiales, herramientas, y útiles necesarios para la realización de los trabajos en la obra, y otros que se refieran. <p>Por lo anterior, una vez culminado el término de la obra antes citado, se dará por terminada la relación de trabajo,
              automáticamente, sin necesidad de aviso, ni de ningún otro requisito y cesaran sus efectos de acuerdo con la fracción del artículo 53 de la ley federal del trabajo.</li>
          <li>Si por cuestiones ajenas al EMPLEADOR se llegara a concluir anticipadamente el proyecto del tiempo determinado, se dará por terminado el presente contrato sin responsabilidad alguna para el EMPLEADOR.</li>
          <p></p>
          <li>La duración máxima de la jornada de trabajo será de: 8 horas, o <u>48</u> horas semanales de lunes a Sábado según se trate de jornadas diurna, mixta o nocturna, respectivamente, quedando distribuidas de acuerdo en las instalaciones del EMPLEADOR,
            pudiendo ser modificados los horarios con las propias necesidades del EMPLEADOR. De acuerdo con lo que establece el artículo 59 de la Ley Federal del trabajo. Quedando distribuida en el siguiente horario de Lunes a Viernes de 8:00am a 18:00pm Sábado de 8:00am a 13:00pm.
            *Disfrutando el trabajador de 1 hora diaria de lunes a viernes. Para tomar sus alimentos y descansar.</li>
          <p></p>
          <li>El TRABAJADOR acepta que cuando por razones convenientes para el EMPLEADOR este modifique el horario de trabajo, deberá desempeñar su jornada en el que quede establecido. Las PARTES podrán acordar variaciones a los horarios de trabajo. Además contará el “trabajador”,
            con un día de descanso por cada semana de labores.</li>
        </ol>
        <p></p>
        <li>SALARIO Y PRESTACIONES.</li>
        <ol type="A">
          <p></p>

          <li>El TRABAJADOR recibirá por la prestación de los servicios a que se refiere este contrato, un salario diario la cantidad de $ <u>{{$sueldo_actual}} ({{$cambio}})</u>
            Cifra que será cubierta de forma @if($contratopdf->tipo_nomina_id==1)semanal @elseif($contratopdf->tipo_nomina_id==2)quincenal @endif el cual incluye la parte correspondiente al séptimo día o descanso semanal, así como los días de descanso obligatorio. El TRABAJADOR se obliga a otorgar recibo a

            favor del EMPLEADOR por la totalidad de las percepciones devengadas, implicando su firma autógrafa y/o electrónica su conformidad con el importe depositado y cualquier aclaración sobre el pago deberá hacerla
            precisamente al firmar el recibo respectivo.</li>
          &nbsp;
          <li>Del salario mencionado en el inciso anterior, el TRABAJADOR está de acuerdo en que el EMPLEADOR haga las deducciones legales correspondientes, particularmente las que se refieren al impuesto Sobre la Renta, aportaciones al IMSS, INFONAVIT, SAR, así como cualquier otras deducciones
            que sea procedente por las diversas prestaciones que otorga el EMPLEADOR y que haya sido acordadas y aceptadas por el TRABAJADOR. </li>
          <p></p>

          <li>EL TRABAJADOR será inscrito en el Instituto Mexicano del Seguro social y sus faltas de asistencia solo serán justificadas contra la exhibición del certificado de incapacidades correspondiente expedido por el IMSS. Con la finalidad de que el EMPLEADOR pueda cubrir la posible inasistencia del TRABAJADOR,
            se compromete a informar al EMPLEADOR a partir del momento en que se tenga conocimiento de dicha situación y más tardar en la primera hora laborable, bajo pena de incurrir en una desobediencia de órdenes.</li>
          <p></p>
          <li>EL TRABAJADOR disfrutara de los días de descanso obligatorio establecidos en el artículo 74 de la ley Federal del Trabajo.</li>
          <p></p>
          <li>El TRABAJADOR tendrá derecho al pago de la parte proporcional de vacaciones al tiempo laborado, así como la prima vacacional del 25% correspondiente, de acuerdo a lo que establecen los artículos 76 y 80 de la ley Federal del Trabajo.</li>
          <p></p>
          <li>EL TRABAJADOR recibirá un aguinaldo en forma proporcional al periodo de servicios pactados en los términos del artículo 87 de la Ley Federal de Trabajo.</li>
        </ol>
        <p></p>
        <p>&nbsp;</p>
        <li>MATERIALES, EQUIPO, INTRUMENTOS, UTILES Y HERRAMIENTAS PARA TRABAJAR.</li>
        <p>EL EMPLEADOR se obliga a proporcionar al TRABAJADOR los materiales, equipos instrumentos, útiles, herramientas y demás medios y facilidades necesarias para realizar su trabajo.</p>
        <p></p>
        <li>CAPACITACION, SEGURIDAD, HIGIENE Y MEDIO AMBIENTE EN EL TRABAJO</li>
        <ol type="A">
          <p></p>
          <li>EL TRABAJADOR conviene en someterse a los reconocimientos médicos que ordene el EMPLEADOR, en los términos de la fracción X del artículo 134 de la ley Federal del trabajo.</li>
          <p></p>
          <li>Las PARTES acuerdan que el TRABAJADOR será capacitado y/o adiestrado en los términos del programa de capacitación y adiestramiento inicial establecido o que establezca el EMPLEADOR y que el TRABAJADOR estará obligado a cumplir, así como tener su total disposición y empeño para capacitarse y adiestrarse, para el mejor desarrollo de sus aptitudes y conocimientos.</li>
          <p></p>
          <li>Las PARTES acuerdan seguir los programas de seguridad e higiene y protección del medio ambiente que tiene establecidos el EMPLEADOR para evitar accidentes y enfermedades, tomando el TRABAJADOR parte activa dentro de los mismos, adoptando medidas preventivas de riesgo de trabajo y cuidado del medio ambiente.</li>
          <p>&nbsp;</p>
        </ol>

        <li>LINEAMIENTOS DE TRABAJO</li>
        <p></p>
        <ol type="A">
          <p></p>
          <li>El TRABAJADOR se obliga a conocer, observar y cumplir todo lo dispuesto en los lineamientos de trabajo del EMPLEADOR, respetando los horarios que le son asignados, las medidas de seguridad e higiene, mantener siempre el orden y limpieza de su área de trabajo, desempeñar leal y honradamente su trabajo, bajo la dirección del EMPLEADOR o de su representante.</li>
          <p></p>
          <li>Así mismo el TRABAJADOR se obliga a realizar el trabajo con la intensidad, cuidado y esmero apropiados y en la forma, tiempo y lugar convenidos, así como observar buenas costumbres durante la prestación de servicios.</li>
          <p></p>
          <li>EL TRABAJADOR, se obliga a conocer y cumplir el reglamento interior de trabajo vigente, entregando el día de la firma de este contrato.</li>
          <p></p>
        </ol>
        <p></p>
        <li>INFORMACIÓN CONFIDENCIAL Y PROPIEDAD INTELECTUAL</li>
        <p></p>
        <ol type="A">
          <p></p>
          <li>El TRABAJADOR se obliga a guardar estrictamente reserva de la información confidencial, procedimiento y todos aquellos hechos y actos que con motivo de su trabajo sean de su conocimiento y por lo tanto se obliga a no utilizar en su beneficio o de terceras personas ya sea directa o indirecta la información y cualquier diseño y/o proceso que sea de su conocimiento y a no
            divulgar ninguno de los aspectos técnicos y de los negocios el EMPLEADOR en cualquier tipo de actividad. En especial deberá guardar estricta reserva de toda aquella información, procedimientos, secretos comerciales e industriales que se encuentren protegidos por la ley incluso después de que se extinga la relación de trabajo, estando sujeto a las sanciones previas en las leyes penales y/o
            de propiedad intelectual aplicables en caso de incumplimiento. </li>
        </ol>
        <p></p>
        <li>DATOS PERSONALES EN POSESION DE PARTICULARES.</li>
        <p></p>
        <ol type="A">
          <p></p>
          <li>Ambas PARTES se obligan a cumplir con lo establecido por la Ley Federal de Protección de Datos personales en posesión de Particulares y su reglamento.</li>
          <p></p>
          <li>EL TRABAJADOR reconoce y acuerda que derivado de la relación laboral que tiene con el EMPELADOR, será necesario que este último tenga acceso a sus datos personales y/o datos personales sensibles y con la finalidad de llevar a cabo un adecuado manejo y protección de los mismos, ambas PARTES se comprometen a lo siguiente:</li>
          <p></p>
          <li>EL EMPLEADOR recabara directa e indirectamente los datos personales y/o datos sensibles del TRABAJADOR de conformidad con el objeto del presente contrato. Así mismo estará en contacto con documentación diversa que pudiera contener datos personales y/o datos personales sensibles del TRABAJADOR.</li>
          <p></p>
          <li>EL TRABAJADOR proporcionara todos los datos personales y/o datos personales sensibles que sean necesarios para el cumplimiento de las obligaciones y las prestaciones laborales que ambas partes contraen en virtud del presente contrato.</li>
          <p></p>
          <li>EL TRABAJADOR otorga su consentimiento expreso en virtud de la celebración del presente contrato para que el EMPLEADOR utilice sus datos personales y/o datos personales sensibles para los efectos del cumplimiento del presente contrato y relación de trabajo y por su parte el EMPLEADOR por escrito en documento por separado y que firma de conformidad conociendo íntegramente su contenido.</li>
          <p></p>
        </ol>
        <p></p>
        <li>TERMINOS TRABAJADOR Y/O TRABAJADORA.</li>
        <p></p>
        <p>Las PARTES convienen en que en el presente contrato se establece la palabra TRABAJADOR en forma de género e incluyo los términos TRABAJADOR y/o TRABAJADORA, sin que esto implique discriminación alguna.</p>
        <p>&nbsp;</p>
        <li>CONFLICTO DE TRABAJO E INTERPRETACION DEL CONTRATO.</li>
        <p></p>

        <p>Las PARTES convienen en que lo no previsto en este contrato se regirá por las disposiciones de la ley Federal del trabajo.</p>
        &nbsp;

        <p>El presente contrato se firma en la ciudad de Tehuacán, Puebla a los <u> </u> <u>{{$fecha_final_ingreso}}.</u><br></p>
        <br>
        <p>&nbsp;</p>
        <p align="center">Representante de Constructora y Servicios Calderón Torres S.A de C.V.</p>
      </ol>

    </table>
    <table style="border-collapse: collapse; padding-top: 80px; padding-left: 50px;">
      <tr>
        <td class="letratablapieuno" width="125"><b>LA EMPRESA</b><br><br><br></td>
        <td width="127">&nbsp;</td>
        <td class="letratablapieuno" width="135"><b>EL TRABAJADOR</b><br><br><br></td>
        <p></p>
        <td width="41">&nbsp;</td>
      </tr>
      <tr>
        <td class="letratablapiedos">C. DIEGO CRUZ MARTÍNEZ</td>
        <td>&nbsp;</td>
        <td class="letratablapiedos">C. {{$contratopdf->entrega}}</td>
      </tr>
    </table>

    <table style="border-collapse: collapse; padding-top: 80px; padding-left: 40px;">
      @if($n_testigos==0)

      <tr>
        <td width="100">&nbsp;</td>
      </tr>

      @elseif($n_testigos==1)

      <tr>
        <td class="letratablapieuno" width="147"><b>TESTIGO</b><br><br><br></td>
        <td width="100">&nbsp;</td>
      </tr>
      <tr>
        <td class="letratablapiedos">{{$testigos[0]['nombre_testigo']}}</td>
      </tr>

      @else

      <tr>
        <td class="letratablapieuno" width="147"><b>TESTIGO</b><br><br><br></td>
        <td width="100">&nbsp;</td>
        <td class="letratablapieuno" width="163"><b>TESTIGO<br><br><br></td>
        <p></p>
        <td width="41">&nbsp;</td>
      </tr>
      <tr>
        <td class="letratablapiedos">{{$testigos[0]['nombre_testigo']}}</td>
        <td>&nbsp;</td>
        <td class="letratablapiedos">{{$testigos[1]['nombre_testigo']}}</td>
      </tr>
      @endif
    </table>
  </div>

  @elseif($contratopdf->tipo_contrato_id==2)

  <body class="borde">
    <div class="notification">
      <div class="notificationx">
        <img src="img/CSCT.png" width="120">
      </div>

      <table class="pr">

        <h2> CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V. </h2>
      </table> <br>
      <b>CONTRATO INDIVIDUAL DE TRABAJO POR TIEMPO INDETERMINADO.</b>
      <table class="textoo">
        <p>CONTRATO INDIVIDUAL DE TRABAJO POR TIEMPO INDETERMINADO QUE CELEBRAN POR UNA PARTE <u> CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V.,</u>
          REPRESENTADA EN ESTE ACTO POR EL <u>C. DIEGO CRUZ MARTINEZ</u> A QUIEN EN LO SUCESIVO SE LE DENOMINARA “EL EMPLEADOR” Y POR LA OTRA PARTE
          EL<u> C. {{$contratopdf->entrega}}</u> POR SU PROPIO DERECHO, A QUIEN EN ADELANTE SE LE DENOMINARA COMO “EL TRABAJADOR”, DE CONFORMIDAD CON
          LAS SIGUIENTES:
        </p>
      </table>
      <table class="titul">
        <p><b>DECLARACIONES</b></p>
      </table>
      <table class="textoo">
        <ol ol type="I">
          <li>Declara el EMPLEADOR ser una sociedad debidamente constituida de conformidad con las leyes mexicanas, con registro Federal de Contribuyente <b>CSC050609LF7</b>,
            que tiene su domicilio fiscal en Avenida Francisco I. Madero No. 1000 Colonia Maria de la piedad C.P. 96410 en Coatzacoalcos, Veracruz,, la cual se encuentra
            debidamente representada en este acto por el C. DIEGO CRUZ MARTINEZ.</li>
          <p></p>
          <li>Declara el TRABAJADOR <u>C. {{$contratopdf->entrega}},</u> ser de nacionalidad MEXICANA con <u>{{$edad}}</u> años de edad, estado civil <u>{{$contratopdf->civil}}</u> con Registro Federal de
            Contribuyente <u>{{$contratopdf->erfc}}</u> con clave única de registro de población <u>{{$contratopdf->ecur}}</u> y con domicilio en Calle: <u>{{$contratopdf->calle}}</u>, Colonia: <u>{{$contratopdf->colonia}}</u>,
            Numero Exterior:<u>{{$contratopdf->numero_exterior}}</u>, C.p: <u>{{$contratopdf->codigo_postal}}</u>, <u>{{$contratopdf->localidad}}, {{$contratopdf->entidad_federativa}}.</u> </li>
          <p></p>
          <li>EL EMPLEADOR manifiesta que tiene la necesidad temporal de contratar a un TRABAJADOR que labore en el puesto de: <u><b>{{$contratopdf->areadmin}}</u></b> Para Proyecto: <b><u>{{$contratopdf->proyn}}.</b></u></li>
          <p></p>
          <li>EL TRABAJADOR manifiesta expresamente que tiene conocimiento de la necesidad temporal del EMPLEADOR y que posee los conocimientos necesarios y aptitudes para prestar sus servicios en el puesto mencionado en la declaración anterior.</li>
          <p></p>
          <li>Ambas partes de conformidad con las anteriores declaraciones están conformes en celebrar el presente contrato al tenor de las siguientes:</li>
          <p></p>
        </ol>
      </table>

      <table class="titul">
        <p><b>CLAUSULAS</b></p>
      </table>

      <table class="textoo">
        <ol ol type="I">
          <li>TIPO DE TRABAJO</li>
          <p>El TRABAJADOR se obliga a prestar sus servicios temporales por tiempo determinado en el puesto de: <u><b>{{$contratopdf->areadmin}}.</b></u> Debiendo ejecutar
            su trabajo de conformidad con las instrucciones del EMPLEADOR con esmero y eficiencia, quedado obligado a desempeñar todas las labores anexas o conexas con su obligación
            principal y las demás que se le ordene y que sean compatibles con sus conocimientos y aptitudes, debiendo observar todos los lineamientos, disposiciones, circulares y todos los ordenamientos legales que sean aplicables.</p>
          &nbsp;
          <li>LUGAR DE TRABAJO</li>
          <p>EL TRABAJADOR deberá desempeñar su trabajo en las instalaciones del EMPLEADOR, así como en cualquier otra planta, establecimiento que tenga el EMPLEADOR o que llegara a establecer en el estado de: VERACRUZ o en la república Mexicana.</p>

          <li>TIEMPO DE DURACION DEL TRABAJO, DE LA JORNADA Y HORARIO.</li>
          <ol type="A">
            <p></p>
            <li>Las PARTES acuerdan que el presente contrato se celebra por TIEMPO INDETERMINADO y reconocen una fecha de ingreso a partir del <u>{{$dia_ingreso}} de {{$mes_inicio_letra}} del {{$anio_ingreso}}</u> para todos efectos legales a que haya lugar.</li>
            <p></p>
            <li>B. La duración máxima de la jornada de trabajo será de 48 horas, semanales de lunes a sábado según se trate de jornada diurna, mixta o nocturna, respectivamente, quedando distribuida de acuerdo a las necesidades del EMPLEADOR, que serán repartidas de conformidad con la costumbre establecida en las instalaciones del EMPLEADOR, pudiendo ser modificados los horarios de conformidad con las propias necesidades del EMPLEADOR. De acuerdo con lo que establece el artículo 59 de la Ley Federal del trabajo. Quedando distribuida en el siguiente horario de Lunes a Viernes de 8:00am a 18:00pm Sábado de 8:00am a 13:00pm.
              <br> <br> *Disfrutando el trabajador de 1 hora diaria de lunes a viernes. Para tomar sus alimentos y descansar*
            </li>
            <p></p>
            <li>EI TRABAJADOR acepta que cuando por razones convenientes para el EMPLEADOR este modifique el horario de trabajo, deberá desempeñar su jornada en el que quede establecido.</li>
          </ol>
          <p>Por cada seis días de trabajo, tendrá el TRABAJADOR un descanso semanal de un día con pago de salario íntegro conviniéndose en que dicho descanso se determinara de conformidad con las costumbres y las necesidades
            del trabajo y podrá ser variable en la semana. Las PARTES podrán acordar variaciones los horarios de trabajo.</p>
          <p></p>
          <li>SALARIO Y PRESTACIONES.</li>

          <ol type="A">
            <p></p>

            <li>El TRABAJADOR recibirá por la prestación de los servicios a que se refiere este contrato, un salario diario de $ <u>{{$sueldo_actual}} ({{$cambio}})</u>
              Cifra que será cubierta de forma quincenal el cual incluye la parte correspondiente al séptimo día o descanso semanal, así como los días de descanso obligatorio. <p></p>

              <br>El TRABAJADOR se obliga a otorgar recibo a favor del EMPLEADOR por la totalidad de las percepciones devengadas, implicando su firma autógrafa y/o electrónica su conformidad con el importe depositado y cualquier aclaración sobre el pago deberá hacerla
              precisamente al firmar el recibo respectivo.
            </li>&nbsp;
            <li>Del salario mencionado en el inciso anterior, el TRABAJADOR está de acuerdo en que el EMPLEADOR haga las deducciones legales correspondientes, particularmente las que se refieren al impuesto Sobre la Renta, aportaciones al IMSS, INFONAVIT, SAR, así como cualquier otras deducciones
              que sea procedente por las diversas prestaciones que otorga el EMPLEADOR y que haya sido acordadas y aceptadas por el TRABAJADOR.</li>
            <li>EL TRABAJADOR será inscrito en el Instituto Mexicano del Seguro social y sus faltas de asistencia solo serán justificadas contra la exhibición del certificado de incapacidades correspondiente expedido por el IMSS. Con la finalidad de que el EMPLEADOR pueda cubrir la posible inasistencia del TRABAJADOR,
              se compromete a informar al EMPLEADOR a partir del momento en que se tenga conocimiento de dicha situación y más tardar en la primera hora laborable, bajo pena de incurrir en una desobediencia de órdenes.</li>
            <p></p>
            <li>EI TRABAJADOR disfrutara de los días de descanso obligatorio establecidos en el artículo 74 de la Ley Federal del Trabajo, así como los días adicionales especificados en el Calendario de Días Laborables que se determine anualmente.</li>
            <p></p>
            <li>El TRABAJADOR tendrá derecho al pago de la parte proporcional de vacaciones al tiempo laborado, así como la prima vacacional del 25% correspondiente, de acuerdo a lo que establecen los artículos 76 y 80 de la ley Federal del Trabajo.</li>
            <p></p>
            <li>EL TRABAJADOR recibirá un aguinaldo en forma proporcional al periodo de servicios pactados en los términos del artículo 87 de la Ley Federal de Trabajo.</li>
          </ol>
          &nbsp;
          <li>MATERIALES, EQUIPO, INTRUMENTOS, UTILES Y HERRAMIENTAS PARA TRABAJAR.</li>
          <p>EL EMPLEADOR se obliga a proporcionar al TRABAJADOR los materiales, equipos instrumentos, útiles, herramientas y demás medios y facilidades necesarias para realizar su trabajo.</p>
          <p></p>
          <li>CAPACITACION, SEGURIDAD, HIGIENE Y MEDIO AMBIENTE EN EL TRABAJO</li>
          <ol type="A">
            <p></p>
            <li>EL TRABAJADOR conviene en someterse a los reconocimientos médicos que ordene el EMPLEADOR, en los términos de la fracción X del artículo 134 de la ley Federal del trabajo.</li>
            <p></p>
            <li>Las PARTES acuerdan que el TRABAJADOR será capacitado y/o adiestrado en los términos del programa de capacitación y adiestramiento inicial establecido o que establezca el EMPLEADOR y que el TRABAJADOR estará obligado a cumplir, así como tener su total disposición y empeño para capacitarse y adiestrarse, para el mejor desarrollo de sus aptitudes y conocimientos.</li>
            <p></p>
            <li>Las PARTES acuerdan seguir los programas de seguridad e higiene y protección del medio ambiente que tiene establecidos el EMPLEADOR para evitar accidentes y enfermedades, tomando el TRABAJADOR parte activa dentro de los mismos, adoptando medidas preventivas de riesgo de trabajo y cuidado del medio ambiente.</li>
            <p></p>
          </ol>
          <p></p>
          <li>LINEAMIENTOS DE TRABAJO</li>
          <ol type="A">
            <p></p>
            <li>El TRABAJADOR se obliga a conocer, observar y cumplir todo lo dispuesto en los lineamientos de trabajo del EMPLEADOR, respetando los horarios que le
              son asignados, las medidas de seguridad e higiene, mantener siempre el orden y limpieza de su área de trabajo, desempeñar leal y honradamente su trabajo, bajo la dirección del EMPLEADOR o de su representante.</li>&nbsp;
            <li>Así mismo el TRABAJADOR se obliga a realizar el trabajo con la intensidad, cuidado y esmero apropiados y en la forma, tiempo y lugar convenidos, así como observar buenas costumbres durante la prestación de servicios.</li>
            <p></p>
            <li>EL TRABAJADOR, se obliga a conocer y cumplir el reglamento interior de trabajo vigente, entregando el día de la firma de este contrato.</li>
            <p></p>
          </ol>

          <li>INFORMACIÓN CONFIDENCIAL Y PROPIEDAD INTELECTUAL</li>
          <ol type="A">
            <p></p>
            <li>El TRABAJADOR se obliga a guardar estrictamente reserva de la información confidencial, procedimiento y todos aquellos hechos y actos que con motivo de su trabajo sean de su conocimiento y por lo tanto se obliga a no utilizar en su beneficio o de terceras personas ya sea directa o indirecta la información y cualquier diseño y/o proceso que sea de su conocimiento y a no
              divulgar ninguno de los aspectos técnicos y de los negocios el EMPLEADOR en cualquier tipo de actividad. En especial deberá guardar estricta reserva de toda aquella información, procedimientos, secretos comerciales e industriales que se encuentren protegidos por la ley incluso después de que se extinga la relación de trabajo, estando sujeto a las sanciones previas en las leyes penales y/o
              de propiedad intelectual aplicables en caso de incumplimiento. </li>
            <p></p>
            <li>EI TRABAJADOR acepta que cualquier invento, técnica, idea, procedimiento que desarrolle por su trabajo será propiedad del EMPLEADOR.</li>
            <p></p>
          </ol>
          <p></p>
          <li>DATOS PERSONALES EN POSESION DE PARTICULARES.</li>
          <p></p>
          <ol type="A">
            &nbsp;
            <li>Ambas PARTES se obligan a cumplir con lo establecido por la Ley Federal de Protección de Datos personales en posesión de Particulares y su reglamento.</li>
            <p></p>
            <li>EL TRABAJADOR reconoce y acuerda que derivado de la relación laboral que tiene con el EMPELADOR, será necesario que este último tenga acceso a sus datos personales y/o datos personales sensibles y con la finalidad de llevar a cabo un adecuado manejo y protección de los mismos, ambas PARTES se comprometen a lo siguiente:</li>
            <p></p>
            <li>EL EMPLEADOR recabara directa e indirectamente los datos personales y/o datos sensibles del TRABAJADOR de conformidad con el objeto del presente contrato. Así mismo estará en contacto con documentación diversa que pudiera contener datos personales y/o datos personales sensibles del TRABAJADOR.</li>
            <p></p>
            <li>EL TRABAJADOR proporcionara todos los datos personales y/o datos personales sensibles que sean necesarios para el cumplimiento de las obligaciones y las prestaciones laborales que ambas partes contraen en virtud del presente contrato.</li>
            <p></p>
            <li>EL TRABAJADOR otorga su consentimiento expreso en virtud de la celebración del presente contrato para que el EMPLEADOR utilice sus datos personales y/o datos personales sensibles para los efectos del cumplimiento del presente contrato y relación de trabajo y por su parte el EMPLEADOR por escrito en documento por separado y que firma de conformidad conociendo íntegramente su contenido.</li>
            <p></p>
          </ol>
          <p></p>
          <li>TERMINOS TRABAJADOR Y/O TRABAJADORA.</li>
          <p></p>
          <p>Las PARTES convienen en que en el presente contrato se establece la palabra TRABAJADOR en forma de género e incluyo los términos TRABAJADOR y/o TRABAJADORA, sin que esto implique discriminación alguna.</p>
          <li>CONFLICTO DE TRABAJO E INTERPRETACION DEL CONTRATO.</li>
          <p></p>
          <p>Las PARTES convienen en que lo no previsto en este contrato se regirá por las disposiciones de la ley Federal del trabajo.</p>
          <br>
          <br>
          <p>El presente contrato se firma en la ciudad de Tehuacán, Puebla a los <u> </u> <u>{{$fecha_final_ingreso}}.</u>
          </p>
          <br>

          <p align="center">Representante de Constructora y Servicios Calderón Torres S.A de C.V.</p>
        </ol>


      </table>
      <table style="border-collapse: collapse; padding-top: 80px; padding-left: 50px;">
        <tr>
          <td class="letratablapieuno" width="125"><b>LA EMPRESA</b><br><br><br></td>
          <td width="127">&nbsp;</td>
          <td class="letratablapieuno" width="135"><b>EL TRABAJADOR</b><br><br><br></td>
          <p></p>
          <td width="41">&nbsp;</td>
        </tr>
        <tr>
          <td class="letratablapiedos">C. DIEGO CRUZ MARTÍNEZ</td>
          <td>&nbsp;</td>
          <td class="letratablapiedos">C. {{$contratopdf->entrega}}</td>
        </tr>
      </table>

      <table style="border-collapse: collapse; padding-top: 80px; padding-left: 40px;">
        @if($n_testigos==0)

        <tr>
          <td width="100">&nbsp;</td>
        </tr>

        @elseif($n_testigos==1)

        <tr>
          <td class="letratablapieuno" width="147"><b>TESTIGO</b><br><br><br></td>
          <td width="100">&nbsp;</td>
        </tr>
        <tr>
          <td class="letratablapiedos">{{$testigos[0]['nombre_testigo']}}</td>
        </tr>

        @else

        <tr>
          <td class="letratablapieuno" width="147"><b>TESTIGO</b><br><br><br></td>
          <td width="100">&nbsp;</td>
          <td class="letratablapieuno" width="163"><b>TESTIGO<br><br><br></td>
          <p></p>
          <td width="41">&nbsp;</td>
        </tr>
        <tr>
          <td class="letratablapiedos">{{$testigos[0]['nombre_testigo']}}</td>
          <td>&nbsp;</td>
          <td class="letratablapiedos">{{$testigos[1]['nombre_testigo']}}</td>
        </tr>
        @endif
      </table>



    </div>


    @elseif($contratopdf->tipo_contrato_id==3)

    <body class="borde">
      <div class="notification">
        <div class="notificationx">
          <img src="img/CSCT.png" width="120">
        </div>
        <table class="pr">
          <h2> CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V. </h2>
        </table>

        <b>CONTRATO INDIVIDUAL POR TIEMPO DETERMINADO.</b>
        <table class="textoo">
          <p>CONTRATO INDIVIDUAL DE TRABAJO POR TIEMPO DETERMINADO QUE CELEBRAN POR UNA PARTE <u> CONSTRUCTORA Y SERVICIOS CALDERON TORRES S.A. DE C.V.</u>
            REPRESENTADA EN ESTE ACTO POR EL <u>C. DIEGO CRUZ MARTINEZ</u> A QUIEN EN LO SUCESIVO SE LE DENOMINARA “EL EMPLEADOR” Y POR LA OTRA PARTE
            EL&nbsp;<u>C. {{$contratopdf->entrega}}</u> POR SU PROPIO DERECHO, A QUIEN EN ADELANTE SE LE DENOMINARA COMO “EL TRABAJADOR”, DE CONFORMIDAD CON
            LAS SIGUIENTES:
          </p>
        </table>
        <table class="titul">
          <p><b>DECLARACIONES</b></p>
        </table>
        <table class="textoo">
          <ol ol type="I">
            <li>Declara el EMPLEADOR ser una sociedad debidamente constituida de conformidad con las leyes mexicanas, con registro Federal de Contribuyente <b>CSC050609LF7</b>,
              que tiene su domicilio fiscal en Avenida Francisco I. Madero No. 1000 Colonia Maria de la piedad C.P. 96410 en Coatzacoalcos, Veracruz, la cual se encuentra
              debidamente representada en este acto por el C. DIEGO CRUZ MARTINEZ.</li>&nbsp;
            <li>Declara el TRABAJADOR <u> C. {{$contratopdf->entrega}},</u> ser de nacionalidad MEXICANA con <u>{{$edad}}</u> años de edad, estado civil <u>{{$contratopdf->civil}}</u> con Registro Federal de
              Contribuyente <u>{{$contratopdf->erfc}}</u> con clave única de registro de población <u>{{$contratopdf->ecur}}</u> y con domicilio en Calle: <u>{{$contratopdf->calle}}</u>, Colonia: <u>{{$contratopdf->colonia}}</u>,
              Numero Exterior:<u>{{$contratopdf->numero_exterior}}</u>, C.P: <u>{{$contratopdf->codigo_postal}}</u>, <u>{{$contratopdf->localidad}}, {{$contratopdf->entidad_federativa}}.</u> </li>&nbsp;
            <li>EL EMPLEADOR manifiesta que tiene la necesidad temporal de contratar a un TRABAJADOR que labore en el puesto de: <u><b>{{$contratopdf->areadmin}}</u></b> Para Proyecto: <b><u>{{$contratopdf->proyn}}.</b></u></li>
            <p></p>
            <li>EL TRABAJADOR manifiesta expresamente que tiene conocimiento de la necesidad temporal del EMPLEADOR y que posee los conocimientos necesarios y aptitudes para prestar sus servicios en el puesto mencionado en la declaración anterior.</li>&nbsp;
            <li>Ambas partes de conformidad con las anteriores declaraciones están conformes en celebrar el presente contrato al tenor de las siguientes:</li>
          </ol>
        </table>


        <table class="titul">
          <p><b>CLAUSULAS</b></p>
        </table>
        <table class="textoo">
          <ol ol type="I">

            <li>TIPO DE TRABAJO</li>
            <p>El TRABAJADOR se obliga a prestar sus servicios temporales por tiempo determinado en el puesto de: <u><b>{{$contratopdf->areadmin}}.</b></u> Debiendo ejecutar
              su trabajo de conformidad con las instrucciones del EMPLEADOR con esmero y eficiencia, quedado obligado a desempeñar todas las labores anexas o conexas con su obligación
              principal y las demás que se le ordene y que sean compatibles con sus conocimientos y aptitudes, debiendo observar todos los lineamientos, disposiciones, circulares y todos los ordenamientos legales que sean aplicables.
            </p>

            &nbsp;

            <li>LUGAR DE TRABAJO</li>
            <p>EL TRABAJADOR deberá desempeñar su trabajo en las instalaciones del EMPLEADOR, así como en cualquier otra planta, establecimiento que tenga el
              EMPLEADOR o que llegara a establecer en el estado de VERACRUZ o en la república Mexicana.</p>

            <li>TIEMPO DE DURACION DEL TRABAJO, DE LA JORNADA Y HORARIO.</li>

            <ol type="A">
              <br>
              <li>Los servicios encomendados tendrán un periodo con fecha de inicio el día <u>{{$dia_ingreso}} de {{$mes_inicio_letra}} del {{$anio_ingreso}}</u> y fecha de terminación el día <u>{{$dia_fin}} de {{$mes_fin_letra}} del {{$anio_fin}}</u>, vigencia que ampara el presente contrato, a cuyo efecto “el patrón” pondrá a disposición del “trabajador”,
                durante todo el tiempo de la prestación de sus servicios, los materiales, herramientas, y útiles necesarios para la realización de los trabajos en la obra, y otros que se refieran. <p>Por lo anterior, una vez culminado el término de la obra antes citado, se dará por terminada la relación de trabajo,
                  automáticamente, sin necesidad de aviso, ni de ningún otro requisito y cesaran sus efectos de acuerdo con la fracción del artículo 53 de la ley federal del trabajo.</li>
              <li>Si por cuestiones ajenas al EMPLEADOR se llegara a concluir anticipadamente el proyecto del tiempo determinado, se dará por terminado el presente contrato sin responsabilidad alguna para el EMPLEADOR.</li>
              <p></p>
              <li>La duración máxima de la jornada de trabajo será de: 8 horas, o <u>48</u> horas semanales de lunes a Sábado según se trate de jornadas diurna, mixta o nocturna, respectivamente, quedando distribuidas de acuerdo en las instalaciones del EMPLEADOR,
                pudiendo ser modificados los horarios con las propias necesidades del EMPLEADOR. De acuerdo con lo que establece el artículo 59 de la Ley Federal del trabajo. Quedando distribuida en el siguiente horario de Lunes a Viernes de 8:00am a 18:00pm Sábado de 8:00am a 13:00pm.
                *Disfrutando el trabajador de 1 hora diaria de lunes a viernes. Para tomar sus alimentos y descansar.</li>
              <p></p>
              <li>El TRABAJADOR acepta que cuando por razones convenientes para el EMPLEADOR este modifique el horario de trabajo, deberá desempeñar su jornada en el que quede establecido. Las PARTES podrán acordar variaciones a los horarios de trabajo. Además contará el “trabajador”,
                con un día de descanso por cada semana de labores.</li>
            </ol>
            <br>
            <li>SALARIO Y PRESTACIONES.</li>
            <ol type="A">
              <br>

              <li>El TRABAJADOR recibirá por la prestación de los servicios a que se refiere este contrato, un salario diario la cantidad de $ <u>{{$sueldo_actual}} ({{$cambio}})</u>
                Cifra que será cubierta de forma @if($contratopdf->tipo_nomina_id==1)semanal @elseif($contratopdf->tipo_nomina_id==2)quincenal @endif el cual incluye la parte correspondiente al séptimo día o descanso semanal, así como los días de descanso obligatorio. El TRABAJADOR se obliga a otorgar recibo a

                favor del EMPLEADOR por la totalidad de las percepciones devengadas, implicando su firma autógrafa y/o electrónica su conformidad con el importe depositado y cualquier aclaración sobre el pago deberá hacerla
                precisamente al firmar el recibo respectivo.</li>
              <p><br></p>
              <li>Del salario mencionado en el inciso anterior, el TRABAJADOR está de acuerdo en que el EMPLEADOR haga las deducciones legales correspondientes, particularmente las que se refieren al impuesto Sobre la Renta, aportaciones al IMSS, INFONAVIT, SAR, así como cualquier otras deducciones
                que sea procedente por las diversas prestaciones que otorga el EMPLEADOR y que haya sido acordadas y aceptadas por el TRABAJADOR.</li>
              <p></p>


              <li>EL TRABAJADOR será inscrito en el Instituto Mexicano del Seguro social y sus faltas de asistencia solo serán justificadas contra la exhibición del certificado de incapacidades correspondiente expedido por el IMSS. Con la finalidad de que el EMPLEADOR pueda cubrir la posible inasistencia del TRABAJADOR,
                se compromete a informar al EMPLEADOR a partir del momento en que se tenga conocimiento de dicha situación y más tardar en la primera hora laborable, bajo pena de incurrir en una desobediencia de órdenes.</li>
              <p></p>
              <li>EL TRABAJADOR disfrutara de los días de descanso obligatorio establecidos en el artículo 74 de la ley Federal del Trabajo.</li>
              <p></p>
              <li>El TRABAJADOR tendrá derecho al pago de la parte proporcional de vacaciones al tiempo laborado, así como la prima vacacional del 25% correspondiente, de acuerdo a lo que establecen los artículos 76 y 80 de la ley Federal del Trabajo.</li>
              <p></p>

              <li>EL TRABAJADOR recibirá un aguinaldo en forma proporcional al periodo de servicios pactados en los términos del artículo 87 de la Ley Federal de Trabajo.</li>
            </ol>
            &nbsp;
            <li>MATERIALES, EQUIPO, INTRUMENTOS, UTILES Y HERRAMIENTAS PARA TRABAJAR.</li>
            <p>EL EMPLEADOR se obliga a proporcionar al TRABAJADOR los materiales, equipos instrumentos, útiles, herramientas y demás medios y facilidades necesarias para realizar su trabajo.</p>
            <p></p>
            <li>CAPACITACION, SEGURIDAD, HIGIENE Y MEDIO AMBIENTE EN EL TRABAJO</li>
            <ol type="A">
              <p></p>
              <li>EL TRABAJADOR conviene en someterse a los reconocimientos médicos que ordene el EMPLEADOR, en los términos de la fracción X del artículo 134 de la ley Federal del trabajo.</li>
              <p></p>
              <li>Las PARTES acuerdan que el TRABAJADOR será capacitado y/o adiestrado en los términos del programa de capacitación y adiestramiento inicial establecido o que establezca el EMPLEADOR y que el TRABAJADOR estará obligado a cumplir, así como tener su total disposición y empeño para capacitarse y adiestrarse, para el mejor desarrollo de sus aptitudes y conocimientos.</li>
              <p></p>
              <li>Las PARTES acuerdan seguir los programas de seguridad e higiene y protección del medio ambiente que tiene establecidos el EMPLEADOR para evitar accidentes y enfermedades, tomando el TRABAJADOR parte activa dentro de los mismos, adoptando medidas preventivas de riesgo de trabajo y cuidado del medio ambiente.</li>
              <p></p>
            </ol>
            <p></p>
            <li>LINEAMIENTOS DE TRABAJO</li>
            <p></p>
            <ol type="A">
              <p></p>
              <li>El TRABAJADOR se obliga a conocer, observar y cumplir todo lo dispuesto en los lineamientos de trabajo del EMPLEADOR, respetando los horarios que le son asignados,
                las medidas de seguridad e higiene, mantener siempre el orden y limpieza de su área de trabajo, desempeñar leal y honradamente su trabajo, bajo la dirección del EMPLEADOR o de su representante.</li>
              <p></p>
              <li>Así mismo el TRABAJADOR se obliga a realizar el trabajo con la intensidad, cuidado y esmero apropiados y en la forma, tiempo y lugar convenidos, así como observar buenas costumbres durante la prestación de servicios.</li>
              <p></p>
              <li>EL TRABAJADOR, se obliga a conocer y cumplir el reglamento interior de trabajo vigente, entregando el día de la firma de este contrato.</li>
              <p></p>
            </ol>
            <li>INFORMACIÓN CONFIDENCIAL Y PROPIEDAD INTELECTUAL</li>
            <p></p>
            <ol type="A">
              <p></p>
              <li>El TRABAJADOR se obliga a guardar estrictamente reserva de la información confidencial, procedimiento y todos aquellos hechos y actos que con motivo de su trabajo sean de su conocimiento y por lo tanto se obliga a no utilizar en su beneficio o de terceras personas ya sea directa o indirecta la información y cualquier diseño y/o proceso que sea de su conocimiento y a no
                divulgar ninguno de los aspectos técnicos y de los negocios del EMPLEADOR en cualquier tipo de actividad. En especial deberá guardar estricta reserva de toda aquella información, procedimientos, secretos comerciales e industriales que se encuentren protegidos por la ley incluso después de que se extinga la relación de trabajo, estando sujeto a las sanciones previas en las leyes penales y/o
                de propiedad intelectual aplicables en caso de incumplimiento. </li>
            </ol>
            <p></p>
            <li>DATOS PERSONALES EN POSESION DE PARTICULARES.</li>
            <p></p>
            <ol type="A">
              <p></p>
              <li>Ambas PARTES se obligan a cumplir con lo establecido por la Ley Federal de Protección de Datos personales en posesión de Particulares y su reglamento.</li>
              <p></p>
              <li>EL TRABAJADOR reconoce y acuerda que derivado de la relación laboral que tiene con el EMPELADOR, será necesario que este último tenga acceso a sus datos personales y/o datos personales sensibles y con la finalidad de llevar a cabo un adecuado manejo y protección de los mismos, ambas PARTES se comprometen a lo siguiente:</li>
              <p></p>
              <li>EL EMPLEADOR recabara directa e indirectamente los datos personales y/o datos sensibles del TRABAJADOR de conformidad con el objeto del presente contrato. Así mismo estará en contacto con documentación diversa que pudiera contener datos personales y/o datos personales sensibles del TRABAJADOR.</li>
              <p></p>
              <li>EL TRABAJADOR proporcionara todos los datos personales y/o datos personales sensibles que sean necesarios para el cumplimiento de las obligaciones y las prestaciones laborales que ambas partes contraen en virtud del presente contrato.</li>
              <p></p>
              <li>EL TRABAJADOR otorga su consentimiento expreso en virtud de la celebración del presente contrato para que el EMPLEADOR utilice sus datos personales y/o datos personales sensibles para los efectos del cumplimiento del presente contrato y relación de trabajo y por su parte el EMPLEADOR por escrito en documento por separado y que firma de conformidad conociendo íntegramente su contenido.</li>
              <p></p>
            </ol>
            <p></p>
            <br>
            <li>TERMINOS TRABAJADOR Y/O TRABAJADORA.</li>
            <p></p>
            <p>Las PARTES convienen en que en el presente contrato se establece la palabra TRABAJADOR en forma de género e incluyo los términos TRABAJADOR y/o TRABAJADORA, sin que esto implique discriminación alguna.</p>
            <br>
            <br>

            <li>CONFLICTO DE TRABAJO E INTERPRETACION DEL CONTRATO.</li>
            <p></p>
            <p>Las PARTES convienen en que lo no previsto en este contrato se regirá por las disposiciones de la ley Federal del trabajo.</p>
            <br>
            <br>
            <br>
            <p></p>

            <p>El presente contrato se firma en la ciudad de Tehuacán, Puebla a los <u> {{$fecha_final_ingreso}}.</u>
            </p>
            <br>
            <br>
            <p align="center">Representante de Constructora y Servicios Calderón Torres S.A de C.V.</p>
          </ol>
          <br>
          <br>
          <br>
          <p></p>

        </table>
        <table style="border-collapse: collapse; padding-top: 80px; padding-left: 50px;">
          <tr>
            <td class="letratablapieuno" width="125"><b>LA EMPRESA</b><br><br><br></td>
            <td width="127">&nbsp;</td>
            <td class="letratablapieuno" width="135"><b>EL TRABAJADOR</b><br><br><br></td>
            <p></p>
            <td width="41">&nbsp;</td>
          </tr>
          <tr>
            <td class="letratablapiedos">C. DIEGO CRUZ MARTÍNEZ</td>
            <td>&nbsp;</td>
            <td class="letratablapiedos">C. {{$contratopdf->entrega}}</td>
          </tr>
        </table>

        <table style="border-collapse: collapse; padding-top: 80px; padding-left: 40px;">
          @if($n_testigos==0)

          <tr>
            <td width="100">&nbsp;</td>
          </tr>

          @elseif($n_testigos==1)

          <tr>
            <td class="letratablapieuno" width="147"><b>TESTIGO</b><br><br><br></td>
            <td width="100">&nbsp;</td>
          </tr>
          <tr>
            <td class="letratablapiedos">{{$testigos[0]['nombre_testigo']}}</td>
          </tr>

          @else

          <tr>
            <td class="letratablapieuno" width="147"><b>TESTIGO</b><br><br><br></td>
            <td width="100">&nbsp;</td>
            <td class="letratablapieuno" width="163"><b>TESTIGO<br><br><br></td>
            <p></p>
            <td width="41">&nbsp;</td>
          </tr>
          <tr>
            <td class="letratablapiedos">{{$testigos[0]['nombre_testigo']}}</td>
            <td>&nbsp;</td>
            <td class="letratablapiedos">{{$testigos[1]['nombre_testigo']}}</td>
          </tr>
          @endif
        </table>

      </div>
    </body>

</html>
@endif
</table>
