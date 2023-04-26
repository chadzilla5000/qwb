
function puTS(o){
var hn = "h"+o.id;
var	h=document.getElementById(hn);
//if(!h.value){
	o.innerHTML=new Date(Date.now()); 
	h.value=Date.now();
//	}
}


function putnote(i){

txtareacloseall();
var	d=document.getElementById('notediv_'+i);
	d.style.display = "block";
}

function txtareaclose(i){ ///// Not used now; txtareacloseall() is used instead ///// 
var	d=document.getElementById('notediv_'+i);
	d.style.display = "none";
}

function txtareacloseall(){
for(ii=1;ii<50;ii++){
var	d=document.getElementById('notediv_'+ii);
	if (d) {
		d.style.display = "none";
		}
	}
}

function deletedata() {
    if (confirm("Do you really want to delete this data?")) { return true; }
	return false;
}