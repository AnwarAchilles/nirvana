
/* 
//  * NIRVANA JAVASCRIPT 
//  * ---- ---- ---- ---- */
const NIRVANA = new Framework({
  
  // Base class
  Base: {
    url: base_url
  },

  Auth: {
    url: base_url
  },

  Dashboard: {
    url: base_url
  },
  
  User: {
    url: base_url
  },

  Menu: {
    url: base_url
  },

  Role: {
    url: base_url
  },

  Product: {
    url: base_url
  },

  Playground: {
    url: base_url
  },

});


$( document ).ready( event => {
  if (typeof NIRVANA=='object') {
    $("#cz__loader").animate({ opacity: 0 }, 500, ()=> {
      $("#cz__loader").css("display", "none");
    });
  }else {
    window.location.assign("");
  }
});





/* USER Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "Base", ( Manifest ) => {

  /* USER:BASE Frontend */
  class Toasted extends Frontend {
    init() {
      this.load("toast");

      this.toast.container("Toasted");
      this.toast.use("Toasted");

      const SeentEvent = new EventSource(this.base.url+"/seent_event/toasted", {withCredentials:true});

      SeentEvent.addEventListener("success", (event)=> {
        let data = JSON.parse(event.data);
        this.toast.patch("icon", '<i class="fa-duotone fa-xl fa-'+data.icon+' : me-2"></i>');
        this.toast.patch("header", data.header);
        this.toast.patch("message", data.message);
        this.toast.show();
      });
    }
  }

  /* LOADER Frontend */
  return {
    Apps: {
      Toasted: new Toasted,
    },
    Clones: {},
  }
});