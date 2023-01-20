




function content( name ) {
  $(".content-section").css("width", "100%");

  $(".collapse").removeClass("show");

  $.ajax({
    type: "GET",
    url: base_url+"/welcome/content/"+name,
    success: function(resp) {
      $(".content-box").html( resp );
      new bootstrap.Collapse( $(".content-box") , { toggle: false }).show();
    }
  });
};