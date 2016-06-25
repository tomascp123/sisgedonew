/* JavaScript Document */
var marcados // Se usa en Actionbtngrid y la declaro como pública para poder cambiarla en libjsgen_extend.js
var isNS4 = (navigator.appName=="Netscape")?1:0;
var bShow2 = true;
//document.onkeydown = function(event){ 
//if(window.event.keyCode == 8){return false}
//}

//if (document.layers) { document.captureEvents(Event.KEYPRESS); }
//document.onkeypress = getKey;
//function getKey(keyStroke) {
//   var keyCode = (document.layers) ? keyStroke.which : event.keyCode;
//   var keyString = String.fromCharCode(keyCode).toLowerCase();
//}

var message="";
///////////////////////////////////
//function clickIE() {if (document.all) {(message);return false;}}
//function clickNS(e) {if (document.layers||(document.getElementById&&!document.all)) {
//if (e.which==2||e.which==3) {(message);return false;}}}
//if (document.layers) {document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS}
//else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;}

//document.oncontextmenu=new Function("return false")

/*========================================================================================================================*/
/* FUNCION QUE ABRE UNA VENTANA SIN BARRAS DE HERRAMIENTAS Y SIN MENUS */

function AbreVentana(sURL, Handle){
  var w=640, h=480;

  if (window.screen && window.screen.availHeight) {
    h = window.screen.availHeight - 58; // 58
    w = window.screen.availWidth - 4;
  }

  var ventana=window.open(sURL, Handle, "status=yes,resizable=yes,toolbar=no,scrollbars=yes,top=0,left=0,width=" + w + ",height=" + h, 1 );
  ventana.focus();
}

function AbreMyVentana(sURL, Handle, w, h){

  var ventana=window.open(sURL, Handle, "status=yes,resizable=yes,toolbar=no,scrollbars=yes,top=0,left=0,width=" + w + ",height=" + h, 1 );
  ventana.focus();
}


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
function ObligaCampos(objform,filtro) { 
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
     var sNom=objform.elements[j].name; 
	 if((!filtro && sNom.substring(2,4)!='XX') || (filtro && sNom.substring(2,7)==filtro)) {
			 // VERIFICA SI EL CAMPO ES OBLIGATORIO
			 if (sNom.substring(1,2)=='r')  
				{if (objform.elements[j].value=='') { 
				   sError+="Campo '"+objform.elements[j].id+"' es obligatorio"+"\n" 
				   nErrTot+=1;}}
		
			// VERIFICA SI EL CAMPO ES EMAIL
			 if (sNom.substring(0,1).toUpperCase()=='C' && objform.elements[j].value!='') {
					if (/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(objform.elements[j].value)){}
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
	  }//fin if
    }//fin for

if (nErrTot>0) 
{ alert(sError) ;
	objform.elements[nPas].focus();
   return false; }
   else 
   {
	return mivalidacion(objform);
	}
}

function ObligaCamposyDisabled(objform,filtro){ 
	v_obliga=ObligaCampos(objform,filtro)
	if(v_obliga){
		disable(objform)
		return true		
	}else{
		return false
	}
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

	if(iKeyCode==13 && sOne.toUpperCase()!='E')
		{
			// OJO ESTAS LINEAS SE REPITEN EN LA FUNCION commaSplit
			if(sOne.toUpperCase()=='N'  || sOne.toUpperCase()=='Z'){
				var valpad=''			
				//me aseguro q sean solo numeros
				valor=obj.value+'.'
				pospunto=valor.indexOf('.')
				//primero saco los enteros
				entero=valor.substring(0,pospunto)
				entero=borracero(entero,'r')
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

		if(sOne.toUpperCase()=='Z' && obj.value)
			{obj.value=obj.value.lpad(pLength,"0")}
		//
		tab(objform,obj);
		return false;
		}

	//SI EL CAMPO DEBE SER MAYUSCULAS
	if (sOne==sOne.toUpperCase()) event.keyCode=String.fromCharCode(iKeyCode).toUpperCase().charCodeAt();
		
	//CONTROLO LA LONGITUD DEL CAMPO
	if(obj.value.length>pLength) {
		if(iKeyCode!=8 && iKeyCode!=0)
		return false;
		}


	//NUMERICO ENTERO O  RELLENADO  CON ZEROS
	if ((sOne.toUpperCase()=='N' || sOne.toUpperCase()=='Z') && (pDecimal==undefined || pDecimal==0))
		if((iKeyCode<48 || iKeyCode>59) && iKeyCode!=8 && iKeyCode!=0)
			{return false;}

	//DECIMAL

	if (sOne.toUpperCase()=='N' && pDecimal>0){
		
		if ((iKeyCode<48 || iKeyCode>59) && iKeyCode!=8 && iKeyCode!=0 && (iKeyCode!=46  || obj.value.indexOf('.')>-1))
				return false;
				
	}


}

function tab(form,field)
{
var next=0, found=false
for(var i=0;i<form.length;i++)	{
if(field.name==form.elements[i].name){
		next=i+1;
		found=true
		break;
	}
}

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

function ValidateForm(){
	var dt=document.frmSample.txtDate
	if (isDate(dt.value)==false){
		dt.focus()
		return false
	}
    return true
 }

//***************
/*========================================================================================================================*/
// FUNCION QUE PRESENTA UN MENSAJE DE CONFIRMACION PARA EL USUARIO
//===================================================================
function confirmar(cMensaje) { 
if (confirm(cMensaje)) 
return true; 
else { 
return false;}} 

/*========================================================================================================================*/
/* FUNCION QUE GENERA UN ARRAY DE ELEMENTOS */
function Item(){
/*-----------------------------------------------------------------------
LLAMADA
  var ndia = new Item('Dom','Lun','Mar','Mie','Jue','Vie','Sab')
		   
FECHA DE CREACION
	12/10/2004
*-----------------------------------------------------------------------*/
this.length = Item.arguments.length 
for (var i = 0; i < this.length; i++)
  this[i] = Item.arguments[i]
}

/*========================================================================================================================*/
/* FUNCION QUE DEVUELVE LA FECHA DEL DIA */
function Fecha(pCiudad) {
/*-----------------------------------------------------------------------
PARAMETROS 
	pCiudad    ->(cadena) recibe el nombre de la Ciudad

LLAMADA
  <script language=JavaScript> document.write(Fecha("Chiclayo,")); </script>
		   
FECHA DE CREACION
	12/10/2004
*-----------------------------------------------------------------------*/

var ndia = new Item('Dom','Lun','Mar','Mie','Jue','Vie','Sab')
var nmes = new Item('Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic')
var ahora
var fecha = new Date()
var ano = fecha.getYear()
var mes = fecha.getMonth()
var dia = fecha.getDay()
var aux = "" + fecha

if (ano<10) {
 ano2 = "200" + eval(ano)
}
else if (ano<80) {// ano tiene 2 dígitos 19xx (más de 80)
 ano2 = "20" + ano
} 
else if (ano<=99) {// ano tiene 2 dígitos 20xx (menor de 80)
 ano2 = "19" + ano
}
else if (ano<1000) {// ano tiene 3 dígitos (100 es 2000)
 ano2 = eval(ano) + eval(1900)
}
else {// ano tiene 4 dígitos
 ano2 = ano
}
ahora = pCiudad+ " " + ndia[dia] + ", " + eval(aux.substring(7, 10)) + " " + nmes[mes] + " " + ano2
return ahora
}


/*========================================================================================================================*/
/* FUNCIONES  QUE SE UTILIZAN PARA GENERAN UN MENU DE OPCIONES */
function mOvr(src,clrOver) {
if (!src.contains(event.fromElement)) {
src.style.cursor = 'hand';
src.bgColor = clrOver;
}
}
function mOut(src,clrIn) {
if (!src.contains(event.toElement)) {
src.style.cursor = 'default';
src.bgColor = clrIn;
}
}
function mClk(src) {
if(event.srcElement.tagName=='TD'){
src.children.tags('A')[0].click();
}
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
			entero=borracero(entero,'r')
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

/*========================================================================================================================*/
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
/*========================================================================================================================*/
function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
/*========================================================================================================================*/
function NextLayers(objform,numlayer) { //v6.0
	var layerx='layer'+numlayer;
	var layery='layer'+(numlayer+1);	
	if(ObligaCampos(objform,String(numlayer))){
		MM_showHideLayers(layerx,'','hide',layery,'','show');
		objform._numlayer.value=numlayer;
		}
	else
	   return false;
}
/*========================================================================================================================*/
function PrevLayers(objform,numlayer) { //v6.0
	var layer1='layer'+numlayer;
	var layer2='layer'+(numlayer-1);	
	MM_showHideLayers(layer1,'','hide',layer2,'','show');
	objform._numlayer.value=numlayer-1;		
   return true;
}
/*========================================================================================================================*/
function editaccion(tipoedicion,type,url,op,pagina,orden,where,flag,npop){
	ir='location="'+url+"?_tipoedicion="+tipoedicion+"&_op="+op+"&_type="+type+"&_flag="+flag+"&pagina="+pagina+"&orden="+orden+"&_where="+where+"&_npop="+npop+'"'
	eval(ir)

//	objform.action="";
//	objform.submit();
}
/*========================================================================================================================*/
function valida(objform){
   if (ObligaCampos(objform)) //grabar
	{
		objform.action="";
		objform.submit();
	}
   else return false; 
}
/*========================================================================================================================*/
function MM_jumpMenu(form,obj,url){ 
if(fullopen(url)==4){
	form._setfocus.value=obj.name;		
	form.action="";
	form.submit();
	}
else
	return false
}
/*========================================================================================================================*/
function setfocus(objform,nameobj){ 
var next=0, found=false;
window.onerror = new Function("return true") 
if(nameobj && !eval("document."+objform.name+"."+nameobj+".disabled") && !eval("document."+objform.name+"."+nameobj+".hidden"))	{
	//UBICO LA POSCION DEL OBJETO
	for(var i=0;i<objform.length;i++){
	if(nameobj==objform.elements[i].name){
			next=i+1;
			found=true;
			break;
		}}
	//COLOCO EL FOCO EN UN OBJETO POSTERIOR AL ACTUAL

	if((next+10)<objform.length) nvonext=next+10;
	else nvonext=next+(objform.length-next-1);
	while(1){
		if( objform.elements[nvonext].disabled==false &&  objform.elements[nvonext].type!='hidden'){
			objform.elements[nvonext].focus();
			break;
		}
		else{
			if(nvonext<objform.length-1)
				nvonext=nvonext+1;
			else
				break;
		}
	}


	if(nameobj.substring(0,2).toUpperCase()=="OP"){
		eval("document."+objform.name+"."+nameobj+"[0].focus()")
		}
	else {eval("document."+objform.name+"."+nameobj+".focus()");}

}
else
	//COLOCO EL FOCO EN EL PRIMER OBJETO
	for(var i=0;i<objform.length;i++)
		if(objform.elements[i].name.substring(0,4)!="btn_" && objform.elements[i].disabled==false &&  objform.elements[i].type!='hidden'){
			objform.elements[i].focus();
			break;
		}

}

/*========================================================================================================================*/

function LoadPagesIframes(destino1,page1,destino2,page2,destino3,page3) {
/*---------------------------------------------------------------------
PARAMETROS 
LLAMADA
FECHA DE CREACION
*----------------------------------------------------------------------*/
//parent.ifSuperior.location.href=page1;

eval('parent.'+destino1+'.location.href="'+page1+'"');
if(destino2){
		eval('parent.'+destino2+'.location.href="'+page2+'"');
	}
	if(destino3){
		eval('parent.'+destino3+'.location.href="'+page3+'"');
	}
}

/*-------------------------------------------------------------------------------------------------------------------------*/
function eventaccion(objform,value,url) 
{
	if(fullopen(url)==4){
		objform._action.value=value;				
		objform.action="";		
		objform.submit();
		return true;
		}
	else
		return false;
}
/*-------------------------------------------------------------------------------------------------------------------------*/
function evenlink(objform,objname,valor,url) 
{	
	if(fullopen(url)==4){
		eval(objform.name+"."+objname+".value='"+valor+"'")
		objform.action="";				
		objform.submit();
		return true; }
	else
		return false;

}
/*-------------------------------------------------------------------------------------------------------------------------*/
function actionbtngrid(objform,tipoedicion,tipocontrol,type,url,op,pagina,orden,_npop,where,mensaje)
{
flag=1; 
/*
tipocontrol:
	1 --> Un simple submit sin verificar nada (Ejm. Búsqueda)
	2 --> Procesar uno o varios checks activados (Ejm. Eliminar) // Si no envio el mensaje para que el usuario confirme, 
																	entonces el sistema no pedirà confirmaciòn y envia los datos directamente al URL correspondiente
	3 --> Solo Procesar un check activado  (Ejm. Editar)
	4 --> Solo Procesar un check activado pregunta antes de proceder
	5 --> se utiliza desde popup para retornar un solo valor
*/
switch(tipocontrol) { 
    case 1:{ //para nuevo en el grid
		tabactivo=(tipoedicion==1)?2:1;
		ir='location="'+url+"?_tipoedicion="+tipoedicion+"&_op="+op+"&_type="+type+"&_flag="+flag+"&_tabactivo="+tabactivo+"&_npop="+_npop+'"'
		eval(ir)
		break;}
    case 2:{ //para eliminacion en el grid
		// Envio de todos los ckecks marcados
		// recorro y guardo los check marcados
		marcados = '';
		var max = window.frames['igrid'].document.frmgrid.elements.length;
		for (var idx = 0; idx < max; idx++){ 
			var e = window.frames['igrid'].document.frmgrid.elements[idx]
			if (e.type=='checkbox' && e.name=='ckbox' && e.checked == true) 
			marcados = marcados+e.value+",";
		}
		if (!marcados)	
			alert("Seleccione registro");
		else{
				nConfirmado=0			
				if(mensaje){ // Si existe mensaje de confirmaciòn, entonces el sistema pregunta.
					if(confirmar(mensaje)) 	
						nConfirmado=1
				}else // Si no hay mensaje, directamente cargo la web donde mostrarè o procesarè los registros seleccionados
					nConfirmado=1					

			if(valida_actionbtngrid(op,tipoedicion,marcados)){
				if(nConfirmado){
					ir='location="'+url+"?_tipoedicion="+tipoedicion+"&_op="+op+"&_type="+type+"&_flag="+flag+"&pagina="+pagina+"&orden="+orden+"&_where="+where+"&_npop="+_npop+"&_mydato="+marcados.substring(0,marcados.length-1)+'"'
					eval(ir)
					return true;
				}
			}
		}

		break }
    case 3:{ //para edita o ver detalle en el grid
		// Me aseguro que solo exista un registro marcado
		// Verifico que se haya seleccionado un registro, los demás los desmarco 
		var checkmarcado = -1;
		var max = window.frames['igrid'].document.frmgrid.elements.length;
		for (var idx = 0; idx < max; idx++) {
			var e = window.frames['igrid'].document.frmgrid.elements[idx]
			if (e.type=='checkbox' && e.name=='ckbox' && checkmarcado==-1){
				if (e.checked == true) {
					checkmarcado = idx;
					_valorcheck = e.value;
				}
			}else{
				e.checked = false;
				e.parentNode.parentNode.style.backgroundColor = '';
			}
		}
		
		if (checkmarcado==-1){	
			alert("Seleccione registro");
		}else{
			if(valida_actionbtngrid(op,tipoedicion,_valorcheck)) {
				ir='location="'+url+"?_tipoedicion="+tipoedicion+"&_op="+op+"&_type="+type+"&_flag="+flag+"&pagina="+pagina+"&orden="+orden+"&_where="+where+"&_npop="+_npop+"&_mydato="+_valorcheck+'"'
				eval(ir)						
			}
			return true;
			}
		break} 
		
    case 4:{ 
		// Me aseguro que solo exista un registro marcado
		// Verifico que se haya seleccionado un registro, los demás los desmarco 
		//activar suscripciones
		var checkmarcado = -1;
		var max = window.frames['igrid'].document.frmgrid.elements.length;
		for (var idx = 0; idx < max; idx++) {
			var e = window.frames['igrid'].document.frmgrid.elements[idx]
			if (e.type=='checkbox' && e.name=='ckbox' && checkmarcado==-1){
				if (e.checked == true) {
					checkmarcado = idx;
					_valorcheck = e.value;
				}
			}else{
				e.checked = false;
				e.parentNode.parentNode.style.backgroundColor = '';
			}
		}
		
		if (checkmarcado==-1){	
			alert("Seleccione registro");
		}else{
			if(confirmar(mensaje)){
				ir='location="'+url+"?_tipoedicion="+tipoedicion+"&_op="+op+"&_type="+type+"&_flag="+flag+"&pagina="+pagina+"&orden="+orden+"&_where="+where+"&_npop="+_npop+"&_mydato="+_valorcheck+'"'
				eval(ir)										
				return true;
			}
		}
		break} 

    case 5:{ 
		// Llamo desde popup búsqueda
		// Me aseguro que solo exista un registro marcado
		// Verifico que se haya seleccionado un registro, los demás los desmarco 
		var checkmarcado = -1;
		var max = window.frames['igrid'].document.frmgrid.elements.length;
		for (var idx = 0; idx < max; idx++) {
			var e = window.frames['igrid'].document.frmgrid.elements[idx]
			if (e.type=='checkbox' && e.name=='ckbox' && checkmarcado==-1){
				if (e.checked == true) {
					checkmarcado = idx;
					_valorcheck = e.value;
				}
			}else{
				e.checked = false;
				e.parentNode.parentNode.style.backgroundColor = '';
			}
		}
		
		if (checkmarcado==-1){	
			alert("Seleccione registro");
		}else{
			return _valorcheck;
			}
		break} 

	case 6: //para impresion
		tabactivo=(tipoedicion==1)?2:1;
		ir='location="'+url+"?_tipoedicion="+tipoedicion+"&_op="+op+"&_type="+type+"&_flag="+flag+"&_tabactivo="+tabactivo+"&_npop="+_npop+"&_where="+where+'"'
		eval(ir)
		break;
	} 
}

/*-------------------------------------------------------------------------------------------------------------------------*/
function checkform(objform,objckeck)
{
	var max = objform.elements.length;
	if (objckeck.name=='ckboxall'){
		for (var idx = 0; idx < max; idx++) {
			var e = objform.elements[idx]
			if (e.type=='checkbox' && e.name=='ckbox'){
				e.checked=objckeck.checked;		
				_color=objckeck.checked==true?"#FFFFCC":"";
				e.parentNode.parentNode.style.backgroundColor = _color;
			}
		}
	}else{
		checkBoxAll = true;
		for (var idx = 0; idx < max; idx++) {
			var e = objform.elements[idx]
			if (e.type=='checkbox' && e.name=='ckbox'){
				if (e.checked==false){
					e.parentNode.parentNode.style.backgroundColor = '';
					checkBoxAll = false;
				}else{
					e.parentNode.parentNode.style.backgroundColor = '#FFFFCC';
				}
			}
		}
		frmgrid.ckboxall.checked=checkBoxAll;
	}
}

/*-------------------------------------------------------------------------------------------------------------------------*/
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
	while (S.tagName!=tipoobj)
	{S=S.parentNode;}
	}
else{
	var S=e.srcElement;
	while (S.tagName!=tipoobj)
	{S=S.parentElement;}
	}
if(tipoobj=='TR')
	S.className="gridfilaoff";
else
	S.className="P";
}
/*-------------------------------------------------------------------------------------------------------------------------*/
function fullopen(url){
	var oHttp=false;
	/*@cc_on @*/
	/*@if (@_jscript_version>=5)
	{
		var asParsers=['Msxml2.XMLHTTP.5.0', 'Msxml2.XMLHTTP.4.0', 
		'Msxml2.XMLHTTP.3.0', 'Msxml2.XMLHTTP', 'Microsoft.XMLHTTP'];
		for (var iCont=0; ((!oHttp) && (iCont<asParsers.length)); iCont++)
		{
			try
			{
				oHttp=new ActiveXObject(asParsers[iCont]);
			}
		    catch(e)
			{
				oHttp=false;
			}
		}
	}
	@end @*/
	if ((!oHttp) && (typeof XMLHttpRequest!='undefined'))
	{
		oHttp=new XMLHttpRequest();
	}
	oHttp.open('GET', url, false);
	oHttp.send(null);
	return(oHttp.readyState);
}
/*-------------------------------------------------------------------------------------------------------------------------*/
function saca_char(cadena,car)
{
s=''
for(x=0;x<=cadena.length;x++)
{
if(cadena.substring(x,x+1)!=car)
	{s=s+cadena.substring(x,x+1)}
}
return s
}
/*-------------------------------------------------------------------------------------------------------------------------*/

/*-------------------------------------------------------------------------------------------------------------------------*/
/// Para popup busqueda

function Open(frm,_npop,objname,type)
{
	URL="seekpopup.php?_npop="+_npop+"&_objname="+objname+"&_frm="+frm+"&_type="+type
	//control para el foco
	eval(frm+"._setfocus.value='"+objname+"'");		

	var w=640, h=480, r=0, len=600;
	  if (window.screen && window.screen.availHeight) {
		h = window.screen.availHeight - 58; 
		r = window.screen.availWidth-len
		w = window.screen.availWidth - 4;
	  }

	

	document.onclick = function hidecal2 () { 		
	
		if (!bShow2)
		{
			newwindow.close()

		}
		bShow2 = false
	}
	
	bShow2 = true;
	newwindow=window.open(URL, "popup", "status=yes,resizable=yes,toolbar=no,scrollbars=yes,top=0,left="+ r +",width="+ len + ",height=" + h ,1);
	newwindow.focus();

}

// Para buscar dato en un array
function buscarItem(lista, valor){
var ind, pos;
for(ind=0; ind<lista.length; ind++)
   {
    if (lista[ind][0] == valor)
      break;
    }
pos = (ind < lista.length)? ind : -1;
return (pos);
} 

function busItemArray(listax, valor){
var ind, cdeta;
//cdeta="Dato no Encontrado !!!"
cdeta=""
for(ind=0; ind<listax.length; ind++)
    if (listax[ind][0] == valor){
		return (listax[ind][1])
	    break;
    }
return (cdeta);
} 

// Busco dato en Array y lo muestro en el campo detalle
function busymues(campo,valorid,colmuestra){
	// Busco en Array (el nombre del array es el nombre el campo id)
	straeval = 'bus=buscarItem('+campo+','+'valorid'+')'
	
	eval(straeval)	
	if(bus>-1){
		straeval = 'dato_det='+campo+'['+bus+']['+colmuestra+']'
		eval(straeval)
	}else{
		dato_det=''
	}

	// Muestro el dato 
	// El nombre del campo detalle se forma con el nombre del campoid + '_detalle'
	camp='document.formregistro.'+campo+'_detalle.value="'+dato_det+'"'
	eval(camp);
}

function recibe(frm,valorid,_npop){
	obj=eval('document.'+frm+'.'+_npop+'.value')
	camp='document.'+frm+'.'+obj+'.value="'+valorid+'"'
	eval(camp);
	obj=obj.substr(4,50)
	
	cambiacampo(frm,obj,valorid)
}

function refreshform(frm){
	comando='window.opener.'+frm+'.action=""'
	eval(comando)
	comando='window.opener.'+frm+'.submit()'
	eval(comando);
}

function cambiacampo(frm,campo,dato,size){
	// Muestro el dato en el campo
	if(size>0 && dato && campo.substr(0,1).toUpperCase()=='Z'){
		dato = dato.lpad(size,"0")
	}
	camp='document.'+frm+'.'+campo+'.value="'+dato+'"'
	eval(camp)
	if(campo.indexOf('seek')>-1)
		camp='document.'+frm+'.'+campo+'.value="'+dato+'"'	
	else{
	//	nJump=1
		camp='document.'+frm+'.seek'+campo+'.value="'+dato+'"'}

	eval(camp)

//	if(nJump){
//		window.opener.formregistro.submit()
//		comando='window.opener.'+frm+'.submit()'
//		alert(comando)
//		eval(comando)
//		}
	
//		refreshform(frm)
	
}

function jumplabelpopup(frm,form,obj,url,campo,dato,jump){
cambiacampo(frm,campo,dato)
if(jump){
	MM_jumpMenu(form,obj,url)	
}
}

function popupregresa(_procede,frm,_npop,_altertable,valor){
	if(_procede){
		// Cambio campo seek
		if(_procede==1){
			if(valor){
				window.opener.recibe(frm,valor,_npop)	
			}else{
				window.opener.recibe(frm,actionbtngrid(frmgriprin,0,5),_npop)	
			}
		}
		window.close()
	}
	
	if(_altertable){
		// Refresco el formulario
		refreshform(frm)
	}	

}
		
function mover(dest) { 
	if(dest==1){ // Centrar
		iz=(screen.width-document.body.clientWidth) / 2; 
		de=(screen.height-document.body.clientHeight) / 2; 
		moveTo(iz,de); 
	}
	if(dest==2){ // Derecha
		iz=(screen.width-document.body.clientWidth); 
		de=(screen.height-document.body.clientHeight)-75;  // El 75 es para cuadrar en Internet Explorer, no afecta a Firefox
		moveTo(iz,de); 
	}
}     
		


/*-------------------------------------------------------------------------------------------------------------------------*/
//////  Fin Para popup busqueda

function refresh_field(obj,valor){
	obj.value=valor
	}

/*-------------------------------------------------------------------------------------------------------------------------*/
///inicio de funciones para tablas dinamicas
function regenerateTable(tableContent,objTable,frmName,arrayName)
 {
cImg="edit.gif"	 
  while (objTable.rows.length>1) objTable.deleteRow(1);
  //inicializo mis variables ocultas
for (col=0;col<tableContent[0].length-1;col++) 
		eval(frmName+'.'+tableContent[0][col].substr(2,tableContent[0][col].length)+'.value=""')
//recorrro el array de datos	 
  for (row=0;row<tableContent.length;row++) {
  	if (tableContent [row][tableContent[row].length-1]=="1") {
		var objRow = objTable.insertRow(objTable.rows.length);
		var objCell;
		for (col=0;col<tableContent[row].length-1;col++) {
			 if(tableContent[0][col].substr(0,1)!='H')
			  objCell = objRow.insertCell(objRow.cells.length);

		objvalue=tableContent[row][col]
			//si es un combo
		  if(tableContent[0][col].substr(0,1)=='t'){
				for (var i=0; i<eval(frmName+'.'+tableContent[0][col]+'.options.length'); i++) 
					if(tableContent[row][col]==eval(frmName+'.'+tableContent[0][col]+'.options['+i+'].value')){
						  objCell.innerHTML = eval(frmName+'.'+tableContent[0][col]+'.options['+i+'].text');						
						  break
						}
				}
		  else 
				if(tableContent[0][col].substr(0,1)=='a'){
					cDatoArray=busItemArray(eval(tableContent[0][col]),tableContent[row][col])
					objCell.innerHTML = cDatoArray;	
					cImg="delete.gif"
				} 
				else if(tableContent[0][col].substr(0,1)!='H') // Si no es campo oculto
					objCell.innerHTML = objvalue;
						
		//actualizo mis variables ocultas
		  actual=frmName+'.'+tableContent[0][col].substr(2,tableContent[0][col].length)+'.value="'+eval(frmName+'.'+tableContent[0][col].substr(2,tableContent[0][col].length)+'.value')+objvalue+'®"'
		  eval(actual)
		}//for
			//solo si el boton añadir es visible
			if (document.getElementById(objTable.id+'btn_add')) {
//			if (eval(frmName+'.'+objTable.id+"btn_add.style.visibility")=='visible') {		
				objCell = objRow.insertCell(objRow.cells.length);
				if(cImg=="edit.gif"){
					eval(frmName+'.'+objTable.id+"btn_remove.style.visibility='visible'");
					objCell.innerHTML = '<center><img src="../librerias/imagenes/'+cImg+'" onClick="editTableDynamic('+frmName+','+row+','+arrayName+','+objTable.id+')" alt="Modificar fila">';
				}
				else{
					objCell.innerHTML = '<center><img src="../librerias/imagenes/'+cImg+'" onClick="removeTableDynamic2('+frmName+','+row+','+arrayName+','+objTable.id+",'"+frmName+"','"+arrayName+"'"+')" alt="Eliminar Fila">';			
				}
			}//fin si el boton añadir es visible
}//if
  }//for
  //si no existen datos el sistema le asigna *
 for (col=0;col<tableContent[0].length-1;col++) {
 	if(!eval(frmName+'.'+tableContent[0][col].substr(2,tableContent[0][col].length)+'.value')){
	 	eval(frmName+'.'+tableContent[0][col].substr(2,tableContent[0][col].length)+'.value="*"')}
	 }
	
}//function


function editTableDynamic(frm,rowIdx,tableContent,objTable) {
  with (frm) {
    _focus=0; 
	 z=0;
	for(var i=0;i<frm.length;i++){
		if((elements[i].type!="hidden" || elements[i].name.substr(0,1)=='H') && elements[i].name.substr(0,3)!='btn' &&  elements[i].name.substr(2,5)==objTable.id){
			if(elements[i].name.substr(0,1)!='H') {// Si no es campo oculto			
				elements[i].value=tableContent[rowIdx][z];
				if(!_focus) _focus=i;			
				}
			z++
			}
	}

    eval(objTable.id+"rowIndex.value = rowIdx");
    elements[_focus].focus();

    eval(objTable.id+"btn_add.value = 'Guardar'");
    eval(objTable.id+"btn_remove.disabled = false");    
	
  }  
}


function addTableDynamic(frm,tableContent,objTable,frmName,arrayName) {
 if(ObligaCampos(frm,objTable.id))	{
	with (frm) {	 
		//si es añadir
		if(eval(objTable.id+"btn_add.value")=='Añadir') {
				  tableContent[tableContent.length] = new Array()
					 z=0;
					_focus=0;
					for(var i=0;i<frm.length;i++){
						if(elements[i].type=="checkbox" &&  elements[i].name.substr(2,5)==objTable.id){
							idxArray=tableContent.length-1;
							if (elements[i].checked==true)
								tableContent[idxArray][z]='1';
							else
								tableContent[idxArray][z]='0';
								
							if(!_focus) _focus=i;
							z++;
						}
						else
							if((elements[i].type!="hidden" || elements[i].name.substr(0,1)=='H') && elements[i].name.substr(0,3)!='btn' &&  elements[i].name.substr(2,5)==objTable.id){	
								idxArray=tableContent.length-1;
								tableContent[idxArray][z]=elements[i].value;
								if(!_focus) _focus=i;
								z++;
								}
						}
					idxArray=tableContent.length-1;
					tableContent[idxArray][z]="1";  
		}//fin añadir
	   else
		 {
			z=0;
			for(var i=0;i<length;i++){
				if(elements[i].type!="hidden" && elements[i].name.substr(0,3)!='btn' &&  elements[i].name.substr(2,5)==objTable.id){	
					tableContent[eval(objTable.id+"rowIndex.value")][z]=elements[i].value;
					z++;
					}
				}
			tableContent[eval(objTable.id+"rowIndex.value")][z]="1";  
		   }//fin modificar
	}//fin with
	clearFormTableDynamic(frm,objTable)
	regenerateTable(tableContent,objTable,frmName,arrayName);
 }//fin obliga campos
}//fin funcion


function saveTableDynamic(frm,tableContent,objTable,frmName,arrayName) {
if(ObligaCampos(frm,objTable.id))	{	
  with (frm) {
	z=0;
	for(var i=0;i<frm.length;i++){
		if(elements[i].type!="hidden" && elements[i].name.substr(0,3)!='btn' &&  elements[i].name.substr(2,5)==objTable.id){	
			tableContent[eval(objTable.id+"rowIndex.value")][z]=elements[i].value;
			z++;
			}
		}
	tableContent[eval(objTable.id+"rowIndex.value")][z]="1";  
  }
  clearFormTableDynamic(frm,objTable)
  regenerateTable(tableContent,objTable,frmName,arrayName);
}
}


function removeTableDynamic(frm,tableContent,objTable,frmName,arrayName) {

  with (frm) {
	tableContent[eval(objTable.id+"rowIndex.value")][tableContent[eval(objTable.id+"rowIndex.value")].length-1]="0";
  }
  clearFormTableDynamic(frm,objTable)
  regenerateTable(tableContent,objTable,frmName,arrayName);
}

// Función que se usa para cuando la tabla Dinàmica aparece con la opciòn Eliminar y no Modificar.
function removeTableDynamic2(frm,row,tableContent,objTable,frmName,arrayName) {
  with (frm) {
	tableContent[row][tableContent[row].length-1]="0";
  }
  clearFormTableDynamic(frm,objTable)
  regenerateTable(tableContent,objTable,frmName,arrayName);
}


function clearFormTableDynamic(frm,objTable) {
  with (frm) {
    _focus=0;
	for(var i=0;i<frm.length;i++){
		if((elements[i].type!="hidden" && elements[i].name.substr(0,3)!='btn' &&  elements[i].name.substr(2,5)==objTable.id) || (elements[i].name.substr(0,4)=='seek' && elements[i].name.indexOf(objTable.id)>-1 )){	
			if(elements[i].type=="checkbox")
				elements[i].checked=false;			
			else
				elements[i].value="";
			if(!_focus) _focus=i;
			}
		}
    elements[_focus].focus();


    eval(objTable.id+"btn_add.value = 'Añadir'");	
    eval(objTable.id+"btn_remove.disabled = true");    
  }
}
/*-------------------------------------------------------------------------------------------------------------------------*/
///fin de funciones para tablas dinamicas

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

function borrafilas(countrows){
for(x=0;x<=countrows;x++)
	tabl.deleteRow((tabl.rows.length-countrows-2)+x);
}

/*-------------------------------------------------------------------------------------------------------------------------*/
//función que se usa en la clase myformreporte.php
// Para hacer submit a un formulario
function Form_Submit(form){ 
	form.action=""
	form.submit()
}
/*-------------------------------------------------------------------------------------------------------------------------*/
function ShowDiv(ObjName)
	{
	dObj=document.getElementById(ObjName);
	dObj.style.top = document.body.scrollTop
	dObj.style.left= document.body.scrollWidth - parseInt(dObj.style.width);
	setTimeout("ShowDiv('"+ObjName+"')",1);
	}



/*-------------------------------------------------------------------------------------------------------------------------*/
/* Para deshabilitar los botones al hacer submit */
function disable(e){ // asigna función al submit y pasa el evento como argumento
	
	autoDetect = false;
	timeOutSecs = 10; 	// luego de 5 segundos, el botón se habilitará de nuevo, 
					// para el caso de que el servidor deje de responder y el usuario 
					// necesite volver a submitir. 

	if (document.getElementById) { // chekea que el navegador soporte. Sino lo hace, se ignora el efecto.
		if (autoDetect){ // toma el objeto FORM desde el evento
			if (!e) {e = document.parentWindow.event;} // mozilla pasa (e) pero IE no, así que también usamos su forma de obtener el evento
			var el = e.target || e.srcElement; // obtener el elemento de donde salió el evento, para mozilla o explorer
		} else { // toma el objeto FORM si se pasa manualmente
			el = e;
		}
		while (el.tagName != "FORM"){ el = el.parentNode;} // mozilla pasa el input como source del submit. busco entonces el form de ese input.
		for (var b=0;b<el.elements.length;b++){ // por cada elemento del form
			var formEl = el.elements[b];
			// si el elemento es un botón de submit
			if ((formEl.tagName == "INPUT") && (formEl.getAttribute("type") != null) && ((formEl.getAttribute("type").toLowerCase() == "submit") )) {
				formEl.disabled = true; // desactivar botón
				document.body.style.cursor = 'wait'; // relojito
				setTimeout(function(){formEl.disabled = false;document.body.style.cursor = 'default';},timeOutSecs*1000)
			}
		}
	}
	return true;
}


