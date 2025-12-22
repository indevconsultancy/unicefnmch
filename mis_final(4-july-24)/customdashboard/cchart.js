var customChartOptions = {
        colors: ['#7c502f','#43f67e','#7fc4ba','#820416','#4fea10','#c021fd','#f1ae68','#8f9b6c','#2360f9'],
        chart: {
            type: 'column' // line, bar, column, area
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            title:{},
            type: 'category',
        },
        yAxis: {
            title: {
                text: 'Total '
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: 'Total: <b>{point.y:.0f} </b>'
        },
        series: [{
            name: 'Total',
            data: [ ['Zone-1 (UP)', 27],['Zone-2 (UP)', 34],['Zone-3 (UP)', 39],['Zone-4 (UP)', 38],['Zone-5 (UP)', 42],['Zone-6 (UP)', 60],['Zone-7 (Bihar)', 29],['Zone-8 (Bihar)', 37],['Zone-9 (Bihar)', 43], ],
        }]
    }