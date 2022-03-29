$(function(){
    $('.view[data-show=graella]').addClass('active');
    $(document).on("click",".export",function() {
        table = $(this).data('table');
        filename = $(this).data('filename');
        exportTableToExcel(table, filename);
    });

    $(document).on("click",".view",function() {
        var show = $(this).data('show');
        $('.view').removeClass('active');
        $(this).addClass('active');
        show == 'graella' ? $('.export-all').show() : $('.export-all').hide();

        $('#graella, #grafica').hide();
        $('#'+show).css('display', 'flex');
        $('.export-all').data('table', show);
        $('.export-all').data('filename', show);
    });

    $(document).on('click', '.linkGraph', function(){
        if ($(this).parent().parent().hasClass('graphPerDay')){
            if ($(this).children().hasClass('fa-angle-up')){
                $('.chart-container-total').hide();
                $(this).children().removeClass('fa-angle-up');
                $(this).children().addClass('fa-angle-down');
            }else{
                $(".chart-container-total").show();
                $(this).children().removeClass('fa-angle-down');
                $(this).children().addClass('fa-angle-up');
            }
        }
        if ($(this).parent().parent().hasClass('graphPerHour')){
            if ($(this).children().hasClass('fa-angle-up')){
                $('.chart-container-perHour').hide();
                $(this).children().removeClass('fa-angle-up');
                $(this).children().addClass('fa-angle-down');
            }else{
                $(".chart-container-perHour").show();
                $(this).children().removeClass('fa-angle-down');
                $(this).children().addClass('fa-angle-up');
            }
        }
        if ($(this).parent().parent().hasClass('graphPerAge')){
            if ($(this).children().hasClass('fa-angle-up')){
                $('.chart-container-perAge').hide();
                $(this).children().removeClass('fa-angle-up');
                $(this).children().addClass('fa-angle-down');
            }else{
                $(".chart-container-perAge").show();
                $(this).children().removeClass('fa-angle-down');
                $(this).children().addClass('fa-angle-up');
            }
        }
    });
});

function getGraella(statistics){
    console.log(statistics);
    $.each(statistics, function(fecha, stands){
        day = fecha.split("/")[0];
        date = fecha.replace(/\//g, "");
        $div = $('<div>', {class: 'col-md-12'});
        $table = $('<table>', {class: 'table text-center',id: date+'-table'});
        $thead = $('<thead>');
        $tbody = $('<tbody>');
        $tfoot = $('<tfoot>');
        $trfoot = $('<tr>').append($('<td>', {html: 'TOTALS'}));
        $trtipus = $('<tr>');
        $thead.append($('<th>', {html: 'DIA '+day}));
        tipus_stands = Array();
        for(i=11; i<=18; i++){
            $tr = $('<tr>').append($('<td>', {html:i}));
            
            $.each(stands, function(stand, horas){
                if (i == 11){ 
                    $linkexport = $('<a>', {'data-table': date+'-table', 'data-filename': date, class: 'export'}).append($('<i>', {class: 'fas fa-file-excel'}));
                    $exporttd = $('<td>').append($linkexport);
                    $trtipus.append($('<td>', {html: 'NEWS',class:'lborder'})).append($('<td>', {html: 'REPEAT'})).append($('<td>', {html: 'SOCI'}));
                    $thead.append($('<th>', {html: stand.split('_')[1], colspan: "3"}));
                    tipus_stands.push(stand.split('_')[0]);
                }
                
            });

            /* ANTIGUO CON EL FIRST
            $.each(stands, function(stand, horas){
                if (i == 11){ 
                    $linkexport = $('<a>', {'data-table': date+'-table', 'data-filename': date, class: 'export'}).append($('<i>', {class: 'fas fa-file-excel'}));
                    $exporttd = $('<td>').append($linkexport);
                    $trtipus.append($('<td>', {html: 'NEWS',class:'lborder'})).append($('<td>', {html: 'REPEAT'})).append($('<td>', {html: 'FIRST'})).append($('<td>', {html: 'SOCI'}));
                    $thead.append($('<th>', {html: stand.split('_')[1], colspan: "4"}));
                    tipus_stands.push(stand.split('_')[0]);
                }
                
            });
            */ 
            
            $.each(tipus_stands, function(){
                $tr.append($('<td>', {class: date+'-'+i+'-'+this+'-news lborder', html: 0}));
                $tr.append($('<td>', {class: date+'-'+i+'-'+this+'-repeat', html: 0}));
                /*$tr.append($('<td>', {class: date+'-'+i+'-'+this+'-pass', html: 0}));*/
                $tr.append($('<td>', {class: date+'-'+i+'-'+this+'-soci', html: 0}));
                if (i == 11){
                    $trfoot.append($('<td>', {class: 'foot-'+date+'-'+this+'-news lborder', html: 0}));
                    $trfoot.append($('<td>', {class: 'foot-'+date+'-'+this+'-repeat', html: 0}));
                    /*$trfoot.append($('<td>', {class: 'foot-'+date+'-'+this+'-pass', html: 0}));*/
                    $trfoot.append($('<td>', {class: 'foot-'+date+'-'+this+'-soci', html: 0}));
                }
            });
            $tbody.append($tr);
        } 
        $trtipus.prepend($exporttd);
        $thead.append($trtipus);
        $tfoot.append($trfoot);
        $table.append($thead).append($tbody).append($tfoot);
        $div.append($table);
        $('.statistics').append($div);
    });
    
    
    $.each(statistics, function(fecha, stands){
        date = fecha.replace(/\//g, "");
        $.each(stands, function(stand, horas){
            $.each(horas, function(hora, recount){
                $.each(recount, function(key, value){
                    total = parseInt($('.foot-'+date+'-'+stand.split('_')[0]+'-'+key).html())||0;
                    $('.'+date+'-'+hora+'-'+stand.split('_')[0]+'-'+key).html(value);
                    $('.foot-'+date+'-'+stand.split('_')[0]+'-'+key).html(total+parseInt(value));
                });
            });
        });	
    });
}

function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    filename = filename?filename+'.xls':'excel_data.xls';
    downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}
function graphTotal(graph){
    $div = $('<div>', { class: 'col-md-12 chart-container-total text-center'});
    $chart = $('<canvas>', { id: 'chart-total', height: 200});
    $div.append($chart);
    _labels = Array();
    _data = Array();
       
    $.each(graph, function(stand, total){
        _labels.push(stand);
        _data.push(total);
    });
    chartDay = new Chart($chart, {
        type: 'bar'
        , data: {
            labels: _labels
            , datasets: [{
                label: 'Comptatges totals'
                , data: _data
                , minBarLength: 1
                , backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(129, 98, 90, 0.2)',
                    'rgba(197, 194, 255, 0.2)',
                    'rgba(61, 61, 219, 0.2)',
                    'rgba(249, 200, 200, 0.2)',
                    'rgba(240, 92, 193, 0.2)',
                    'rgba(95, 37, 77, 0.2)'
                ]
                , borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(129, 98, 90, 1)',
                    'rgba(197, 194, 255, 1)',
                    'rgba(61, 61, 219, 1)',
                    'rgba(249, 200, 200, 1)',
                    'rgba(240, 92, 193, 1)',
                    'rgba(95, 37, 77, 1)'
                ]
                , borderWidth: 1
            }]
        }
        , options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
            , responsive: true
        }
    });
    $div.insertAfter($('.graphPerDay'));
}
function graphPerHour(graph){
    $div = $('<div>', { class: 'col-md-12 chart-container-perHour text-center'});
    $chart = $('<canvas>', { id: 'chart-perHour', height: 200});
    $div.append($chart);
    _labels = Array(11, 12, 13, 14, 15, 16, 17, 18);
    _datasets = [];
    $index = 0;
        
    $.each(graph, function(stand, horas){
        item = {};
        item['label'] = stand;
        item['data'] = Array();
        item['backgroundColor'] = colors[$index];
        $.each(horas, function(hora, comptatge){
            item['data'].push(comptatge);
        });
        _datasets.push(item);
        $index++;
    });
    chartHour = new Chart($chart, {
        type: 'line'
        , data: {
            labels: _labels
            , datasets: _datasets
        }
        , options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
            , responsive: true
            , fill: false
        }
    });
    $div.insertAfter($('.graphPerHour'));
}

function graphPerAge(graph){
    var colors = [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235,1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)'
    ];
    $div = $('<div>', { class: 'col-md-12 chart-container-perAge text-center'});
    $chart = $('<canvas>', { id: 'chart-perAge', height: 200});
    $div.append($chart);
    _labels = Array();
    _datasets = [];
    $.each(graph, function(stand, edats){
        $index = 0;
        item = {};
        item['label'] = stand;
        item['data'] = Array();
        item['backgroundColor'] = Array();
            
            
        $.each(edats, function(edat, percentatge){
            if ($.inArray(edat, _labels) == -1){
                _labels.push(edat);
            }
            item['data'].push(percentatge);
            item['backgroundColor'].push(colors[$index]);
            $index++;
        });
        _datasets.push(item);
    });
    chartAge = new Chart($chart, {
        type: 'pie'
        , data: {
            labels: _labels
            , datasets: _datasets
        }
        , options: {
            responsive: true
            ,  tooltips: {
                callbacks: {
                    label: function(item, data) {
                    return data.datasets[item.datasetIndex].label+ "("+ data.labels[item.index]+ "): "+ data.datasets[item.datasetIndex].data[item.index]+"%";
                    } 
                }
            }
        }
    });
    $div.insertAfter($('.graphPerAge'));
}

function exportGraphs(){
    dayGraphs = chartDay.toBase64Image();
    hourGraphs = chartHour.toBase64Image();
    ageGraphs = chartAge.toBase64Image();
    
    console.log(ageGraphs);
}