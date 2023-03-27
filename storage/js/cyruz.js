// BASE


// FULLSCREEN
$(".cz__fullscreen").on("click", function() {
  if (document.fullscreenElement) {
    document.exitFullscreen()
      .then(() => console.log("Document Exited from Full screen mode"))
      .catch((err) => console.error(err))
  } else {
    document.documentElement.requestFullscreen();
  }
});


// SHOW PASSWORD
function showPassowrd(id) {
  var type = $("#"+id).attr('type');
  if (type == 'text') {
    $("#"+id).attr('type', 'password');
  }else {
    $("#"+id).attr('type', 'text');
  }
}


/* CHART JS */
function cz__chart_color( opacity=1 ) {
  var color = {
    primary: "rgba(153, 102, 255, "+opacity+")",
    // secondary: "rgba(54, 162, 235, "+opacity+")",
    secondary: "rgba(150, 150, 150, "+opacity+")",
    danger: "rgba(255, 99, 132, "+opacity+")",
    warning: "rgba(255, 206, 86, "+opacity+")",
    success: "rgba(63, 194, 133, "+opacity+")",
    info: "rgba(75, 192, 192, "+opacity+")",
  };
  return color;
}
function cz__chart_options( type ) {
  var opt = {
    plugins: {
      filler: {
        smooth: true,
        propagate: true,
      },
      legend: {
        labels: {
          usePointStyle: false,
          color: 'rgb(200,200,200)',
          boxWidth: 3,
          boxHeight: 3
        }
      },
      tooltip: {
        usePointStyle: false,
        displayColors: false,
      }
    },
    interaction: {
      intersect: false
    },
    elements: {
      line: {
        backgroundColor: [
          cz__chart_color(0.2).primary,
          cz__chart_color(0.2).secondary,
          cz__chart_color(0.2).danger,
          cz__chart_color(0.2).warning,
          cz__chart_color(0.2).info,
          cz__chart_color(0.2).success
        ],
      }
    },
    datasets: {
      line: {
        pointBorderWidth: 0,
        borderWidth: 3,
        borderColor: [
          cz__chart_color(1).primary,
          cz__chart_color(1).secondary,
          cz__chart_color(1).danger,
          cz__chart_color(1).warning,
          cz__chart_color(1).info,
          cz__chart_color(1).success
        ],
        hoverBorderColor: 'rgba(250,250,250,0.5)',
      },
      bar: {
        borderWidth: 3,
        barPercentage: 0.2,
        borderColor: [
          cz__chart_color(1).primary,
          cz__chart_color(1).secondary,
          cz__chart_color(1).danger,
          cz__chart_color(1).warning,
          cz__chart_color(1).info,
          cz__chart_color(1).success
        ],
        backgroundColor: [
          cz__chart_color(0.2).primary,
          cz__chart_color(0.2).secondary,
          cz__chart_color(0.2).danger,
          cz__chart_color(0.2).warning,
          cz__chart_color(0.2).info,
          cz__chart_color(0.2).success
        ],
      },
      pie: {
        offset: 20,
        borderWidth: 3,
        weight: 0,
        borderColor: [
          cz__chart_color(1).primary,
          cz__chart_color(1).secondary,
          cz__chart_color(1).danger,
          cz__chart_color(1).warning,
          cz__chart_color(1).info,
          cz__chart_color(1).success
        ],
        backgroundColor: [
          cz__chart_color(0.2).primary,
          cz__chart_color(0.2).secondary,
          cz__chart_color(0.2).danger,
          cz__chart_color(0.2).warning,
          cz__chart_color(0.2).info,
          cz__chart_color(0.2).success
        ],
      },
      radar: {
        pointBorderWidth: 0,
        borderWidth: 3,
        borderColor: [
          cz__chart_color(1).primary,
          cz__chart_color(1).secondary,
          cz__chart_color(1).danger,
          cz__chart_color(1).warning,
          cz__chart_color(1).info,
          cz__chart_color(1).success
        ],
        backgroundColor: [
          cz__chart_color(0.2).primary,
          cz__chart_color(0.2).secondary,
          cz__chart_color(0.2).danger,
          cz__chart_color(0.2).warning,
          cz__chart_color(0.2).info,
          cz__chart_color(0.2).success
        ],
      }
    },
    scales: {
      
    }
  };

  if (type=='multi-area') {
    opt.elements.line.fill = 'start';
    opt.interaction.mode = 'index';
  }
  if (type=='area') {
    opt.elements.line.fill = 'start';
  }
  if (type=='multi') {
    opt.interaction.mode = 'index';
  }
  
  if (type=='radar') {
    opt.scales = {
      r: {
        id: "r",
        axis: "r",
        type: "radialLinear",
        pointLabels: {
          color: 'rgb(200,200,200)',
        },
        ticks: {
          color: 'rgb(200,200,200)',
          backdropColor: 'transparent'
        },
        angleLines: {
          lineWidth: 0.2,
          color: 'rgb(150,150,150)'
        },
        grid: {
          // drawTicks: false,
          // borderDash: [5],
          lineWidth: 0.2,
          borderColor: 'transparent',
          color: 'rgb(150,150,150)'
        }
      }
    };
  }
  if (type=='line' || type=='bar' || type=='pie' || type=='multi' || type=='area' || type=='multi-area') {
    opt.scales = {
      x: {
        ticks: {
          padding: 10,
          color: 'rgb(200,200,200)'
        },
        grid: {
          // drawTicks: false,
          // borderDash: [5],
          lineWidth: 0.2,
          borderColor: 'transparent',
          color: 'rgb(150,150,150)'
        }
      },
      y: {
        ticks: {
          padding: 10,
          color: 'rgb(200,200,200)'
        },
        grid: {
          // drawTicks: false,
          // borderDash: [5],
          lineWidth: 0.2,
          borderColor: 'transparent',
          color: 'rgb(150,150,150)'
        }
      }
    };
  }

  return opt;
}

// DATE TIME PICKER
$(".datetimepicker").each( function() {
  var format = $(this).children().attr("datetime");
  $(this).children().datetimepicker({
    "allowInputToggle": true,
    "showClose": true,
    "showClear": true,
    "showTodayButton": true,
    "format": format,
  });
});
// SELECT2
if (typeof $(document).select2 !== "undefined") {
  $('.select2').select2({
    theme: "bootstrap-5",
  });
  // Basic
  $("select").select2({
    theme: "bootstrap-5",
  });
  
  // Small using Select2 properties
  $("#form-select-sm").select2({
    theme: "bootstrap-5",
    containerCssClass: "select2--small", // For Select2 v4.0
    selectionCssClass: "select2--small", // For Select2 v4.1
    dropdownCssClass: "select2--small",
  });
  
  // Small using Bootstrap 5 classes
  $("#form-select-sm").select2({
    theme: "bootstrap-5",
    dropdownParent: $("#form-select-sm").parent(), // Required for dropdown styling
  });
  
  // Large using Bootstrap 5 classes
  $("#form-select-lg").select2({
    theme: "bootstrap-5",
    containerCssClass: "select2--large", // For Select2 v4.0
    selectionCssClass: "select2--large", // For Select2 v4.1
    dropdownCssClass: "select2--large",
    dropdownParent: $("#form-select-lg").parent(), // Required for dropdown styling
  });
}
// SETTINGS
const SETTING_KEY = 'CZ__SETTING';


// Todo save data to storage 
function saveSetting( datas ) {
  if (isStorageExist()) {
    const parsed = JSON.stringify(datas);
    localStorage.setItem('SETTING_KEY', parsed);
  }
}
// Todo tambah data ke storage
function insertSetting( name=null, data=null, callback=null ) {
  let datas = resultSetting();

  if (name !== null) {
    datas[name] = data;
  }else {
    datas = data;
  }
  saveSetting( datas );

  if (callback!==null) {
    callback();
  }
};
// Todo result data from storage
function resultSetting( name=null ) {
  const serializedData = localStorage.getItem('SETTING_KEY');
  const datas = JSON.parse(serializedData);
  
  if (datas !== null) {
    if (name !== null) {
      if (name in datas) {
        return datas[name];
      }else {
        return null;
      }
    }else {
      return datas;
    }
  }else {
    return {};
  }
};
// todo check localstorage
function isStorageExist() {
  if (typeof (Storage) === undefined) {
    alert('Browser kamu tidak mendukung local storage');
    return false;
  }
  return true;
}


if (resultSetting('background') !== null) {
  $("#cz__sidebar_background").css( "background-image", resultSetting('background') );
}
if (resultSetting('grayscale') !== null) {
  $("#cz__sidebar_background").css( "filter", resultSetting('grayscale') );
}
if (resultSetting('opacity') !== null) {
  $("#cz__sidebar_background").css( "opacity", resultSetting('opacity') );
}
if (resultSetting('color') !== null) {
  $("#cz__sidebar").removeClass( 'bg-white' );
  $("#cz__sidebar").removeClass( 'bg-black' );
  $("#cz__sidebar").removeClass( 'bg-dark' );
  $("#cz__sidebar").removeClass( 'bg-light' );
  $("#cz__sidebar").addClass( resultSetting('color') );
}



$("#settings ._set_sidebar ._set_image img").each( function() {
  var img = base_url+"/storage/images/sidebar-"+$(this).attr("sidebar-index")+".jpg";
  $(this).attr("src", img);
  $(this).on("click", function() {
    $("#cz__sidebar_background").css( "background-image", "url("+$(this).attr('src')+")" );
    insertSetting('background', "url("+$(this).attr('src')+")");
  });
});

$("#settings ._set_sidebar ._set_filter input[name='grayscale']").on("change", function() {
  $("#cz__sidebar_background").css( "filter", $(this).attr("name")+"("+$(this).val()+"%)" );
  insertSetting('grayscale', $(this).attr("name")+"("+$(this).val()+"%)");
});
$("#settings ._set_sidebar ._set_filter input[name='opacity']").on("change", function() {
  $("#cz__sidebar_background").css( "opacity", "0."+$(this).val() );
  insertSetting('opacity', "0."+$(this).val());
});

$("#settings ._set_sidebar ._set_color button").each( function() {
  $(this).on("click", function() {
    $("#cz__sidebar").removeClass( 'bg-white' );
    $("#cz__sidebar").removeClass( 'bg-black' );
    $("#cz__sidebar").removeClass( 'bg-dark' );
    $("#cz__sidebar").removeClass( 'bg-light' );
    $("#cz__sidebar").addClass( 'bg-'+$(this).attr('value') );
    insertSetting('color', 'bg-'+$(this).attr('value'));
  });
});
/* SIDEBAR */
// var sidebar = document.querySelector("#cz__sidebar");
var baropen = $(".__open_sidebar");
var sidebar = $("#cz__sidebar");
var sidebar_body = $("#cz__sidebar>#cz__sidebar_body");
var content = $("#cz__content");

// SIDEBAR btn icon fixed width
var sidebar_btn_icon = $("#cz__sidebar_body .fa-duotone");
sidebar_btn_icon.each( function(i, btn) {
  // $(btn).addClass("fa-fw");
});
var sidebar_icon_solid = $("#cz__sidebar_body .fa-solid");
// sidebar_icon_solid.each( function(i, btn) {
//   var span = $(btn).next();
//   $(btn).addClass("fa-fw");
//   $(btn).addClass("fa-"+span.html()[0].toLowerCase());
// });

// SIDEBAR button collapse icon
var sidebar_btn_collapse = $("#cz__sidebar_body .nav [data-bs-toggle='collapse']");
sidebar_btn_collapse.each( function(i, btn) {
  $("<b>+</b>").appendTo(btn);
  btn.addEventListener("click", function() {
    var aria = btn.getAttribute("aria-expanded");
    if (aria=='true'){
      $(btn).children("b").html("-");
    }
    if (aria=='false'){
      $(btn).children("b").html("+");
    }
  });
});

// SIDEBAR open & close
baropen.on("click", function() {
  if ( ! sidebar.hasClass("hide")) {
    sidebar.animate({ "width":"0%" }, 80, "swing", function() {
      sidebar_body.animate({ "opacity":"0" }, 80, "swing");
      sidebar.addClass("hide");
      sidebar.css("display", "none");
    });
    content.animate({"max-width":"100%"}, 80);
  }else {
    sidebar.css("display", "inline-block");
    sidebar.animate({ "width":"100%" }, 80, "swing", function() {
      sidebar_body.animate({ "opacity":"1" }, 80, "swing");
      sidebar.removeClass("hide");
    });
  }
});


// TOAST
var dataToggleToast = $("[data-bs-toggle='toast']");
dataToggleToast.each( function(i, toast) {
  $(toast).on("click", function() {
    var id = $(this).attr('data-bs-target');
    toast = new bootstrap.Toast($(id));
    toast.show();
  });
});