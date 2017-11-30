<div class="ls-box">
   <header class="ls-info-header">
      <h2 class="ls-title-3">Retrospecto das Oportunidades</h2>
   </header>
   <div>
      <canvas id="opportunities_chart" style='height: 500px'></canvas>
   </div>
</div>


<script>

var empty_array = [ @for ($i = sizeof($chart) - 1; $i > -1; $i--) {{ 0 }}, @endfor ];

var dataset_news = {
      label: "Novas",
      fill: false,
      borderColor: "rgb(75, 192, 192)",
      backgroundColor: "rgb(75, 192, 192)",
      data: empty_array };

var dataset_opened = {
      label: "Em aberto",
      fill: false,
      borderColor: "rgb(255, 205, 86)",
      backgroundColor: "rgb(255, 205, 86)",
      data: empty_array };

var dataset_won = {
      label: "Ganhamos",
      fill: false,
      borderColor: "rgb(54, 162, 235)",
      backgroundColor: "rgb(54, 162, 235)",
      data: empty_array };

var dataset_lost = {
      label: "Perdemos",
      fill: false,
      borderColor: "rgb(255, 99, 132)",
      backgroundColor: "rgb(255, 99, 132)",
      data: empty_array };


var data = {
      labels: [ @for ($i = sizeof($chart) - 1; $i > -1; $i--) '{{ $chart[$i]['month']  }}', @endfor ],
      datasets: [dataset_news, dataset_opened, dataset_won, dataset_lost] };

var char_options = {
      responsive: true,
      maintainAspectRatio: false,
      title:{ display:false, text:'Chart.js Line Chart' },
      tooltips: { mode: 'index', intersect: false },
      hover: { mode: 'nearest', intersect: true },
      scales: {
          xAxes: [ { display: true, scaleLabel: { display: true, labelString: 'Meses' } } ],
          yAxes: [{ display: true, scaleLabel: { display: true, labelString: 'Valores' } } ] } };

var chart_config = { type: 'line', data: data, options: char_options };

var real_values = {
      'Novas': [ @for ($i = sizeof($chart) - 1; $i > -1; $i--) {{ $chart[$i]['news']  }}, @endfor ],
      'Em aberto': [ @for ($i = sizeof($chart) - 1; $i > -1; $i--) {{ $chart[$i]['opened']  }}, @endfor ],
      'Ganhamos': [ @for ($i = sizeof($chart) - 1; $i > -1; $i--) {{ $chart[$i]['won']  }}, @endfor ],
      'Perdemos': [ @for ($i = sizeof($chart) - 1; $i > -1; $i--) {{ $chart[$i]['lost']  }}, @endfor ] };



function start_opportunities_chart() {
  var ctx = document.getElementById("opportunities_chart").getContext("2d");

  var update = function() {
    update_chart_info( chart_config['data']['datasets'], real_values );
  }

  window.myLine = new Chart(ctx, chart_config);

  setTimeout(update, 600);
}

function update_chart_info(datasets, new_data) {

  for( i=0; i<datasets.length; i++ )
    datasets[i]['data'] = new_data[datasets[i]['label']];

  window.myLine.update();
}
</script>
