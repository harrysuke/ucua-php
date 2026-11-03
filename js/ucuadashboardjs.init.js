$(function() {
    var label = document.getElementById("label");
    var datas = document.getElementById("datas");
    var areaChartData = {
  labels  : ['label'],
  datasets: [
    {
      label               : 'Frequent Asks',
      backgroundColor     : 'rgba(81,86,190,0.9)',
      borderColor         : 'rgba(81,86,190,0.8)',
      pointRadius          : false,
      pointColor          : '#5156be',
      pointStrokeColor    : 'rgba(81,86,190,1)',
      pointHighlightFill  : '#fff',
      pointHighlightStroke: 'rgba(81,86,190,1)',
      data                : [datas]
    }
  ]
}

var areaChartOptions = {
  maintainAspectRatio : false,
  responsive : true,
  legend: {
    display: false
  },
  scales: {
    xAxes: [{
      gridLines : {
        display : false,
      }
    }],
    yAxes: [{
      gridLines : {
        display : false,
      }
    }]
  }
}
     //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    barChartData.datasets[0] = temp0
  console.log()

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    });

});