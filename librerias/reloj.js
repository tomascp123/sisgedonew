<!--
var tags_before_clock = ""
var tags_after_clock  = ""

if(navigator.appName == "Netscape") {
document.write('<layer id="clock"></layer><br>');
}

if (navigator.appVersion.indexOf("MSIE") != -1){
document.write('<span id="clock"></span><br>');
}

function upclock(){ 
var dte = new Date();
var hrs = dte.getHours();
var min = dte.getMinutes(); 
var sec = dte.getSeconds();
var col = ":";
var spc = " ";
var apm;

if (12 < hrs) { 
apm="PM";
hrs-=12;
}

else {
apm="AM";
}

if (hrs == 0) hrs=12;
if (min<=9) min="0"+min;
if (sec<=9) sec="0"+sec;

if(navigator.appName == "Netscape") {
document.clock.document.write(tags_before_clock
+hrs+col+min+col+sec+spc+apm+tags_after_clock);
document.clock.document.close();
}

if (navigator.appVersion.indexOf("MSIE") != -1){
clock.innerHTML = tags_before_clock+hrs
+col+min+col+sec+spc+apm+tags_after_clock;
}
} 

setInterval("upclock()",1000);
//-->