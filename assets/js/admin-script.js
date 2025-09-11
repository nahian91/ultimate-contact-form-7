jQuery(document).ready(function($){
    if(!document.getElementById('ucf7e-form-chart')) return;

    // Form Chart
    new Chart(document.getElementById('ucf7e-form-chart').getContext('2d'), {
        type:'bar',
        data:{
            labels: ucf7eAnalyticsData.top_forms_titles,
            datasets:[{
                label:'Submissions',
                data: ucf7eAnalyticsData.top_forms_count,
                backgroundColor:'#2271b1',
                borderRadius:4
            }]
        },
        options:{
            responsive:true,
            plugins:{legend:{display:false}},
            scales:{y:{beginAtZero:true}}
        }
    });

    // Daily Trend
    new Chart(document.getElementById('ucf7e-date-chart').getContext('2d'), {
        type:'line',
        data:{
            labels: ucf7eAnalyticsData.date_labels,
            datasets:[{
                label:'Submissions per Day',
                data: ucf7eAnalyticsData.date_values,
                borderColor:'#2271b1',
                backgroundColor:'rgba(34,113,177,0.1)',
                fill:true,
                tension:0.3,
                pointRadius:5
            }]
        },
        options:{
            responsive:true,
            plugins:{legend:{display:false}},
            scales:{y:{beginAtZero:true}}
        }
    });

    // Day of Week
    new Chart(document.getElementById('ucf7e-dow-chart').getContext('2d'), {
        type:'bar',
        data:{
            labels:['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],
            datasets:[{
                label:'Submissions',
                data: ucf7eAnalyticsData.day_of_week,
                backgroundColor:'#f39c12',
                borderRadius:4
            }]
        },
        options:{
            responsive:true,
            plugins:{legend:{display:false}},
            scales:{y:{beginAtZero:true}}
        }
    });

    // Hour Chart
    new Chart(document.getElementById('ucf7e-hour-chart').getContext('2d'), {
        type:'bar',
        data:{
            labels:[...Array(24).keys()],
            datasets:[{
                label:'Submissions',
                data: ucf7eAnalyticsData.hourly,
                backgroundColor:'#16a085',
                borderRadius:4
            }]
        },
        options:{
            responsive:true,
            plugins:{legend:{display:false}},
            scales:{y:{beginAtZero:true}}
        }
    });
});
