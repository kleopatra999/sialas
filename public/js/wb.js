function activo (vis,ocu,bot,img){
  var host = "http://"+window.location.host;
  if (document.getElementById(vis).style.display == "none") {
    document.getElementById(vis).style.display = "table";
    document.getElementById(ocu).style.display = "none";
    document.getElementById(bot).innerHTML = "Papelera";
    document.getElementById(img).src = host+"/sialas/public/img/WB/pre.svg";
    document.getElementById('txt').innerHTML = "|Activos";
    document.getElementById('act').style.display = "block";
    document.getElementById('inc').style.display = "none";
  }else{
    document.getElementById(vis).style.display = "none";
    document.getElementById(ocu).style.display = "table";
    document.getElementById(bot).innerHTML = "Activos";
    document.getElementById(img).src = host+"/sialas/public/img/WB/dat.svg";
    document.getElementById('txt').innerHTML = "|Papelera";
    document.getElementById('act').style.display = "none";
    document.getElementById('inc').style.display = "block";
  }
}


function ver ()
{
  
  if(document.getElementsByName('vradio').value == 'Efectivo')
  {
    document.getElementsByName('caja_id').style.display='block';
    document.getElementById('lmobiliario').style.display='block';
    document.getElementById('lmonto').style.display='block';
    document.getElementById('ldetalle').style.display='block';

    document.getElementById('lbanco').style.display='none';
    document.getElementById('lmobiliarios').style.display='none';
    document.getElementById('lmontos').style.display='none';
    document.getElementById('ldetalles').style.display='none';


  }
  else
  {
    document.getElementsByName('caja_id').style.display='none';
    document.getElementById('lmobiliario').style.display='none';
    document.getElementById('lmonto').style.display='none';
    document.getElementById('ldetalle').style.display='none';


    document.getElementById('lbanco').style.display='block';
    document.getElementById('lmobiliarios').style.display='block';
    document.getElementById('lmontos').style.display='block';
    document.getElementById('ldetalles').style.display='block';

  }

}