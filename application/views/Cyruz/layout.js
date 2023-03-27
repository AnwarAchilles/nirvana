
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
    url: base_url,
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


/* BaseFrontend
 * ---- ---- ---- ---- */
class CyruzFrontend extends Frontend {
  
  // todo build toast
  buildToast( opt, response ) {
    this.toast.container("Default");
    this.toast.use("Default");
    
    if (opt=='success') {
      this.toast.patch("icon", '<i class="fa-duotone fa-2x fa-check-circle fa-beat : text-success me-3"></i>');
      this.toast.patch("text", '<strong class="me-auto">'+this.base.name+' Sucessfully</strong>');
    }
    if (opt=='failed') {
      this.toast.patch("icon", '<i class="fa-duotone fa-2x fa-times-circle fa-beat : text-danger me-3"></i>');
      this.toast.patch("text", '<strong class="me-auto">'+this.base.name+' Failed</strong>');
    }
    this.toast.show();
  }

  // todo set loading on button while processing
  buttonSubmit( type='', nextProcess ) {
    if (type=='enable') {
      this.patch("submit").removeAttr("disabled");
      this.patch("submit").html( this.buttonSubmitTemp );
    }
    if (type=='disable') {
      this.patch("submit").attr("disabled", "disabled");
      this.patch("submit").html('<i class="fa-duotone fa-fw fa-clock-rotate-left : me-1"></i> Loading');
    }
    if (type=='') {
      this.buttonSubmitTemp = this.patch("submit").html();
    }

    if (typeof nextProcess!=='undefined') {
      setTimeout(nextProcess, 500);
    }
  }
}



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
      // Toasted: new Toasted,
    },
    Clones: {},
  }
});