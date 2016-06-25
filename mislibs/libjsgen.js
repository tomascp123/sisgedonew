/* JavaScript Document */
var isNS4 = (navigator.appName=="Netscape")?1:0;

/*========================================================================================================================*/
String.prototype.lpad = function(pSize, pCharPad)
{
	var str = this;
	var dif = pSize - str.length;
	var ch = String(pCharPad).charAt(0);
	for (; dif>0; dif--) str = ch + str;
	return (str);
} //String.lpad
//var xx = 1
//alert(String(xx).lpad(8,"0")) convierte un numero en cadena y lo llena de 8 ceros ->00000001
/*========================================================================================================================*/
String.prototype.rpad = function(pSize, pCharPad)
{
	var str = this;
	var dif = pSize - str.length;
	var ch = String(pCharPad).charAt(0);
	for (; dif>0; dif--) str =  str+ch;
	return (str);
} 
/*========================================================================================================================*/
String.prototype.trim = function()
{
	return this.replace(/^\s*/, "").replace(/\s*$/, "");
} //String.trim

/*========================================================================================================================*/
/* FUNCION QUE OBLIGA A LLENAR CAMPOS EN EL FORMULARIO */
function ObligaCampos(objform) { 
/*---------------------------------------------------------------------
PARAMETROS 
	objform     ->(objeto) recibe el objeto formulario
LLAMADA
    <form name="form1" ......  onSubmit="return ObligaCampos(this)">
REFERENCIAS

FECHA DE CREACION
	07/10/2004
*----------------------------------------------------------------------*/
   var nErrTot=0; 
   var nPas=-1;
   var sError="Mensajes del sistema: "+"\n\n"; 

   // RECORRO LOS ELEMENTOS DEL FORMULARIO
   for (var j=0; j<objform.elements.length; j++){ 
     // OBTIENE EL NOMBRE DEL CAMPO
	 valorcheecked=0;
     var sNom=objform.elements[j].name; 
			 // VERIFICA SI EL CAMPO ES OBLIGATORIO

			 //SI ES CAMPO FILE
			 if (objform.elements[j].value=='' 
					&& sNom.substring(0,1).toUpperCase()=='A' 
					&& sNom.substring(1,2)=='r'
					&& objform.elements[j].type!="checkbox"){
					if (objform.elements[j-1].value=='') { 
					   sError+="Campo '"+objform.elements[j].id+"' es obligatorio"+"\n" 
					   nErrTot+=1;}
			  }
			 else
				 if (sNom.substring(1,2)=='r') {
					if (objform.elements[j].value=='') { 
					   sError+="Campo '"+objform.elements[j].id+"' es obligatorio"+"\n" 
					   nErrTot+=1;}
				 }

///			
			// VERIFICA SI EL CAMPO ES DEL TIPO OPTION BUTTON
			 if (sNom.substring(0,1).toUpperCase()=='O' && sNom.substring(1,2)=='r') {
					//recorro todos los elementos del option
					namecheck=objform.elements[j].name;
					do {
						if(objform.elements[j].checked)
							valorcheecked=1;
						j+=1;
					}while (j<objform.elements.length && objform.elements[j].name==namecheck)
				    j-=1;
					
				   if(!valorcheecked){
					   sError+="Selección de campo '"+objform.elements[j].id+"' es obligatorio"+"\n" ;
						nErrTot+=1;} 
				  }
///
			// VERIFICA SI EL CAMPO ES EMAIL
			 if (sNom.substring(0,1).toUpperCase()=='C' && objform.elements[j].type!='checkbox' && objform.elements[j].value!='') {
					if (/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(objform.elements[j].value)){}
					else{
					   sError+="Campo '"+objform.elements[j].id+"' no es valido"+"\n" ;
					   nErrTot+=1;}
					}

			// VERIFICA SI EL CAMPO ES IP
			 if (sNom.substring(0,1).toUpperCase()=='I' && objform.elements[j].value!='') {
					partes=objform.elements[j].value.split('.'); 
				    if (partes.length==4) { 
						for (i=0;i<4;i++) {  
							num=partes[i]; 
							if (num>255 || num<0 || num.length==0 || isNaN(num)){ 
							   sError+="Campo '"+objform.elements[j].id+"' no es valido"+"\n" ;
							   nErrTot+=1;
							   break;}
						}
					} 
					else{
					   sError+="Campo '"+objform.elements[j].id+"' no es valido"+"\n" ;
					   nErrTot+=1;}
				}

			// VERIFICA SI EL CAMPO ES FECHA
			 if (sNom.substring(0,1).toUpperCase()=='D')  
				 if (!isDate(objform.elements[j].value) && objform.elements[j].value!='')
				 {sError+="Valor de campo '"+objform.elements[j].id+"' no es valido (formato de fecha debe ser : dd/mm/yyyy)"+"\r" ;
				   nErrTot+=1;} 
		
			// FOCO EN EL PRIMER CAMPO OBLIGATORIO
			 if (nPas==-1 && nErrTot>0) { 
				nPas=j;  } 
    }//fin for

if (nErrTot>0) 
	{ alert(sError) ;
		if (objform.elements[nPas].type!='hidden'){
			objform.elements[nPas].focus();
		}
	   return false; }
else 
   {return mivalidacion(objform);}
}
/*========================================================================================================================*/
/* FUNCION QUE APLICA FORMATO DE ENTRADA DE DATOS A UN OBJETO */
function formato(event,objform,obj,pLength,pDecimal) { 
//expresion: ([0-1][0-9]|2[0-3]):[0-5][0-9]
    
//Description:  Validate an hour entry to be between 00:00 and 23:59  
//Matches:  [00:00], [13:59], [23:59]  [ More Details]  
//Non-Matches:  [24:00], [23:60]  

	// OBTENGO EL TIPO DE CAMPO		
	var sOne=obj.name.substring(0,1); 	 
    //SI ES ENTER SE PASA AL SIGUIENTE CAMPO

	if (isNS4) {
	    iKeyCode = event.which;	
		} else {		
	    iKeyCode = event.keyCode;
		}
	//E-> campo de edición
	//Z-> campo relleno de ceros
	if(iKeyCode==13 && sOne.toUpperCase()!='E' )
		{
			// OJO ESTAS LINEAS SE REPITEN EN LA FUNCION commaSplit
			if(sOne.toUpperCase()=='N'  || sOne.toUpperCase()=='Z'){
				/*
				var valpad=''			
				//me aseguro q sean solo numeros
				valor=obj.value+'.'
				pospunto=valor.indexOf('.')
				//primero saco los enteros
				entero=valor.substring(0,pospunto)
				//entero=borracero(entero,'r')
				//luego dejo solo la cantidad de enteros solicitado
				numdecimal=pDecimal>0?pDecimal+1:0
				entero=entero.substring(0,pLength-numdecimal)
				//saco el punto q agregue
				valor=obj.value
				fraccion=valor.substring(pospunto+1,pospunto+numdecimal)
				fraccion=fraccion.rpad(pDecimal,"0");				
				if(pDecimal>0) obj.value=entero+'.'+fraccion
				else obj.value=entero
				*/
			}

		if(sOne.toUpperCase()=='Z' && obj.value)
			{//obj.value=obj.value.lpad(pLength,"0")
			}
			

		tab(objform,obj);
		return false;
		}

	//SI EL CAMPO DEBE SER MAYUSCULAS
	if (sOne==sOne.toUpperCase())
	if(isNS4){}//mozilla
	else //ie
		event.keyCode=String.fromCharCode(iKeyCode).toUpperCase().charCodeAt();
		
	//CONTROLO LA LONGITUD DEL CAMPO
	if(obj.value.length>pLength) {
		if(iKeyCode!=8 && iKeyCode!=0)
		return false;
		}


	//NUMERICO ENTERO O  RELLENADO  CON ZEROS
	if ((sOne.toUpperCase()=='N' || sOne.toUpperCase()=='Z') && (pDecimal==undefined || pDecimal==0)){
		if((iKeyCode<48 || iKeyCode>59) && iKeyCode!=45 && iKeyCode!=8 && iKeyCode!=0)
			{return false;}
	}

	//DECIMAL
	if (sOne.toUpperCase()=='N' && pDecimal>0){
		if ((iKeyCode<48 || iKeyCode>59) && iKeyCode!=45 && iKeyCode!=8 && iKeyCode!=0 && (iKeyCode!=46  || obj.value.indexOf('.')>-1))
				return false;
				
	}


}

function tab(form,field)
{
var next=0, found=false
for(var i=0;i<form.length;i++)	{
if(field.name==form.elements[i].name){
		next=i+1;
		if(next < form.length)
			found=true
		else
			found=false
			
		break;
	}
}
if (found){
	while(found){
		if( form.elements[next].disabled==false &&  form.elements[next].type!='hidden'){
			form.elements[next].focus();
			break;
		}
		else{
			if(next<form.length-1)
				next=next+1;
			else
				break;
		}
	}
}
else{
		if( form.elements[0].disabled==false &&  form.elements[0].type!='hidden'){
			form.elements[0].focus();
		}
	}
}

//***************
function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   } 
   return this
}

function isDate(dtStr){
	var dtCh= "/"
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	var strDay=dtStr.substring(0,pos1)
	var strMonth=dtStr.substring(pos1+1,pos2)
	var strYear=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)
	if (pos1==-1 || pos2==-1){
		//alert("El formato de fecha debe ser : dd/mm/yyyy")
		return false
	}
	if (strMonth.length<1 || month<1 || month>12){
		//alert("registro de mes no valido")   
		return false
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		//alert("registro de día no valido")
		return false
	}
	if (strYear.length != 4 || year==0){
		//alert("el año debe contener cuatro digitos")
		return false
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		//alert("dato fecha no valida")
		return false
	}
return true
}
/*========================================================================================================================*/
/* Para deshabilitar cualquier objeto*/
/* recibe el idObj */
/* recibe el tiempo que permanecera oculto, ejm: 10 */
function ocultarObj(idObj,timeOutSecs){
	 	// luego de timeOutSecs segundos, el botón se habilitará de nuevo, 
		// para el caso de que el servidor deje de responder y el usuario 
		// necesite volver a submitir. 
	myID = document.getElementById(idObj);
	myID.style.display = 'none';
	document.body.style.cursor = 'wait'; // relojito
	setTimeout(function(){myID.style.display = 'inline';document.body.style.cursor = 'default';},timeOutSecs*1000)
}
/*========================================================================================================================*/
function commaSplit(obj,coma,pLength,pDecimal) {
var sOne=obj.name.substring(0,1); 	 
		if(sOne.toUpperCase()=='N'  || sOne.toUpperCase()=='Z'){
			var valpad=''			
			//me aseguro q sean solo numeros
			valor=obj.value+'.'
			pospunto=valor.indexOf('.')
			//primero saco los enteros
			entero=valor.substring(0,pospunto)
			//entero=borracero(entero,'r')
			//luego dejo solo la cantidad de enteros solicitado
			numdecimal=pDecimal>0?pDecimal+1:0
			entero=entero.substring(0,pLength-numdecimal)
			//saco el punto q agregue
			valor=obj.value
			fraccion=valor.substring(pospunto+1,pospunto+numdecimal)
			fraccion=fraccion.rpad(pDecimal,"0");				
			if(pDecimal>0) obj.value=entero+'.'+fraccion
			else obj.value=entero
		}

	if(sOne.toUpperCase()=='Z' )
		{
		if(obj.value)	
			obj.value=obj.value.lpad(pLength,"0")
		 return;
		}

if(coma){
	srcNumber=obj.value; 
	var txtNumber = '' + srcNumber;
	if (txtNumber != "") { // Si el campo tiene dato
		var rxSplit = new RegExp('([0-9])([0-9][0-9][0-9][,.])');
		var arrNumber = txtNumber.split('.');
		arrNumber[0] += '.';
		do {
		arrNumber[0] = arrNumber[0].replace(rxSplit, '$1,$2');
		} while (rxSplit.test(arrNumber[0]));
		if (arrNumber.length > 1) {
			valorff=arrNumber.join('');
			}
		else {
			valorff=arrNumber[0].split('.')[0];
			}
		}
	else{
		valorff=srcNumber;
	}
	obj.value=valorff
}}
/*========================================================================================================================*/
function replaceChars(obj,out,add) {
//out = caracter que será reemplazado
//add = caracter que reemplazará
entry=obj.value;
temp = "" + entry;

while (temp.indexOf(out)>-1) {
pos= temp.indexOf(out);
temp = "" + (temp.substring(0, pos) + add + 
temp.substring((pos + out.length), temp.length));
}
obj.value=temp;
obj.select();
obj.focus();

//document.datos.mitext.value=temp;
}
/*-------------------------------------------------------------------------------------------------------------------------*/
//funcion que quita los ceros a la derecha, izquierda o ambos lados de una cadena

function borracero(cad,mid)
{
var cero=''
if(mid.toUpperCase()=="R" ||  mid.toUpperCase()=="T" ){
	for(x=1;x<=cad.length;x++)
		if (cad.substring(0,x)!=cero.lpad(x,"0")) break
		cad=cad.substring(x-1,cad.length)
	}
if(mid.toUpperCase()=="L" ||  mid.toUpperCase()=="T" ){
	for(x=cad.length-1;x>0;x--)
		if (cad.substring(x,cad.length)!=cero.lpad(cad.length-x,"0")) break
		cad=cad.substring(0,x+1)
}
return(cad)
}

function MO(e,tipoobj)
{
if (!e)
var e=window.event;
if(isNS4){
	var S=e.target;
	while (S.tagName!=tipoobj)
	{S=S.parentNode;}
}
else {
	var S=e.srcElement;
	while (S.tagName!=tipoobj)
	{S=S.parentElement;}
	}
if(tipoobj=='TR')
	S.className="gridfilaon";   
else
	S.className="T";
}
/*-------------------------------------------------------------------------------------------------------------------------*/
function MU(e,tipoobj)
{
if (!e)
var e=window.event;
if(isNS4){
	var S=e.target;
	var W=e.target;
	while (S.tagName!=tipoobj)
	{S=S.parentNode;}
	}
else{
	var S=e.srcElement;
	while (S.tagName!=tipoobj)
	{S=S.parentElement;}
	}

if(tipoobj=='TR')
	S.className=S.id
	//S.className="gridfilaoff";
else
	S.className="P";
}


/* Mascara
Script creado por Tunait! (21/12/2004) http://javascript.tunait.com/

El script se encarga de colocar los separadores pertinentes en el lugar indicado determinado por una lista en un campo de texto mientras se está tecleando.
Sirve para más de un campo con distintos formatos de máscaras.
Configuración
Crear un Array que indique cantidad de digitos entre cada separador.
Por ejemplo, si queremos una fecha con formato dd/mm/aaaa:
var patron = new Array(2,2,4)
Si lo quisiéramos aaaa/mm/dd:
var patron = new Array(4,2,2)
...o un número de teléfono tipo 34-206-21-22:
var patron = new Array(2,3,2,2)
Si queremos usar el mismo script para distintos campos con distintas máscaras habermos de crear un array para cada uno
var patron2 = new Array(1,9)
Finalmente habremos de llamar al script desde el/los campo/s pasándole como parámetros a sí mismo, el patrón (array) a utilizar,
el separador que queramos aplicar y si queremos que sólo acepte números o no.
mascara(this,array a utilizar, separador, true si sólo números o false si cualquier caracter)
<input type="text" name = "fecha" onkeyup="mascara(this,'/',patron,true)" maxlength="10" />
<input type="text" name = "telefono" onkeyup="mascara(this,'-',patron2,true)" maxlength="12" />
*/

function mascara(d,sep,pat,nums){
	if(d.valant != d.value){
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]	
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
					val += sep + val3[q]
					}
			}
		}
		d.value = val
		d.valant = val
	}
}

/*========================================================================================================================*/
/* FUNCION QUE MUESTRA UN MENSAJE O UNA ANIMACION CUANDO SE EFECTUA UN PROCESO  
	txtwait --> Aquí recibo el texto o código html que deseo se muestre en el DIV 'procesando'
	Formas de llamar:
	wait('<img src="../img/ajax-loader.gif" />')  Para mostrar la animación
	.........
	.........
	wait('')  Para eliminar la animación, luego que termina el proceso 
*/

function wait(txtwait) {
	cmd = "parent.menu.document.getElementById('procesando').innerHTML = '<img src=\"../img/"+txtwait+"\"/>'"
	eval(cmd)
}
