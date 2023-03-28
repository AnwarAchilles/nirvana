/* DASHBOARD Frontend 
 * ---- ---- ---- ---- */
NIRVANA.build( "Dashboard", (Manifest)=> {

  /* Base Frontend */
  class Base extends Frontend {
    
    // initialize
    init() {
      const dataChartPie = [];

      Object.entries( dataset ).forEach( Entry=> {
        const [name, row] = Entry;
        
        // create chart area on card
        this.chart(name, row.color, [0, 11, 2, 17, 13, 11, 21]);

        // create pie
        let dataset = this.api("GET", "menu/"+row.id_menu).responseJSON.data;
        let datacount = this.api("GET", name+"/count").responseJSON.data['count'];
        dataChartPie.push([name, datacount, dataset.color]);
      });

      this.chartPie( dataChartPie );
    }

    // chart setup from cyruz
    chart( select, color, data ) {
      var options = cz__chart_options('area');
      options.elements.line.tension = 0.3;
      options.plugins.tooltip = false;
      options.plugins.legend.display = false;
      options.scales.x.grid.display = false;
      options.scales.x.ticks.display = false;
      options.scales.y.grid.display = false;
      options.scales.y.ticks.display = false;

      new Chart( $(".cz__dashboard_card."+select+" canvas"), {
        type: "line",
        data: {
          labels: [1,2,3,4,5,6,7],
          datasets: [
            {
              borderColor: cz__chart_color(0.4)[color],
              backgroundColor: cz__chart_color(0.05)[color],
              label: 'Total Project',
              data: data
            }
          ]
        },
        options: options
      });
    }

    chartPie( dataCount ) {
      var options = cz__chart_options('pie');
      options.scales.x.grid.display = false;
      options.scales.x.ticks.display = false;
      options.scales.y.grid.display = false;
      options.scales.y.ticks.display = false;
      
      let labels = [];
      let borders = [];
      let backgrounds = [];
      let datas = [];
      
      dataCount.forEach( entry => {
        labels.push( entry[0] );
        datas.push( entry[1] );
        borders.push( cz__chart_color(1)[ entry[2] ] );
        backgrounds.push( cz__chart_color(0.2)[ entry[2] ] );
      });

      options.datasets.pie.borderColor = borders,
      options.datasets.pie.backgroundColor = backgrounds,

      new Chart( $(".cz__dashboard_asset.pie canvas"), {
        type: "pie",
        data: {
          labels: labels,
          datasets: [
            {
              label: 'Dataset',
              data: datas
            }
          ]
        },
        options: options
      });
    }
  }

  /* LOADER Frontend */
  return {
    Apps: {
      Base: new Base,
    },
    Clones: {}
  }
});