<?
$_tipoedicion
  case 1: // NUEVO
  case 2: //MODIFICACION
  case 3: // Objeto dehabilitado
  case 4: //ELIMINACION
  case 5: // BUSCAR
  
$formavisual
1->aparece en todo
2->aparece en todo excepto en la busqueda
3->no aparece solo en el ingreso,  (Aparece en Detalle, Editar y Buscar )
5->aparece solo en la b�squeda,  

$_type en MODULO.PHP
M <- MODULO CON VERIFICACION DE PERMISOS
L <- MODULO LIBRE SIN VERIFICACION DE PERMISOS
G <- TIPO GRABAR O FUNCION CON VERIFICACION DE PERMISOS
GL <- TIPO GRABAR O FUNCION SIN VERIFICACION DE PERMISOS


//NUMERICO ENTERO O  RELLENADO  CON ZEROS
Objeto Campo Password --> El nombre del campo debe ser 'password'
1 caracter
	c --> Correo
	S --> Caracter may�scula
	z --> Rellena de ceros
	n --> Numerico

2 caracter
	s --> READONLY
	r --> Obligatorio

Los 2 primeros caracteres
	zn --> Campo num�rico que se rellene de ceros (Se usa en N�mero del doc. en Sisgedo, para cuando deseamos que pueda dejarse en blanco el dato y sea tomado como n�mero 0 en PHPGRABAR.PHP)
	nn --> CAmpo num�rico que va a guardarse como NULL si no se edita ning�n dato.


?>