
/* 
//  * NIRVANA JAVASCRIPT 
//  * ---- ---- ---- ---- */
const NIRVANA = new Framework({

});


/* LOADER
 * ---- ---- ---- ---- */
$( document ).ready( event => {
  if (typeof NIRVANA=='object') {
    $("#cz__loader").animate({ opacity: 0 }, 500, ()=> {
      $("#cz__loader").css("display", "none");
    });
  }else {
    window.location.assign("");
  }
});