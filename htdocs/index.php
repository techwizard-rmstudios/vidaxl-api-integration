<?php
// session_start();
// if(!isset($_SESSION['valid'])) {
// 	header('Location: login.php');
// }
?>

<!DOCTYPE html>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <title>vidaXL API</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>

<body>
    <button class="btn btn-primary m-2" onclick="onSend()">SendData</button>
    <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Code</th>
                <th>Category Path</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Currency</th>
                <th>Create</th>
                <th>Update</th>
            </tr>
        </thead>
    </table>
    

    <!-- <button onclick="onEdit()">EditData</button> -->

    <script src="assets/js/jquery-3.5.1.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script>
        //edit Data
        // function onEdit(){
            
        // }
        // $('#example').on( 'click', 'tbody tr', function () {
        //         console.log(this);
        // } );

        //send Etsy
        function onSend() {
            var data = $('#example').DataTable().rows({ selected: true }).data();
            var newarray = [];
            for (var i = 0; i < data.length; i++) {
                console.log(data[i][0] + " " + data[i][1] + " " + data[i][2]);
                newarray.push(data[i][0]);
                newarray.push(data[i][1]);
                newarray.push(data[i][2]);
            }
            var sData = newarray.join();
            // console.log(sData)
        }


        $.fn.dataTable.pipeline = function(opts) {
            var conf = $.extend({
                    pages: 5,
                    url: '',
                    data: null,
                    method: 'GET',
                },
                opts
            );

            var cacheLower = -1;
            var cacheUpper = null;
            var cacheLastRequest = null;
            var cacheLastJson = null;

            return function(request, drawCallback, settings) {
                var ajax = false;
                var requestStart = request.start;
                var drawStart = request.start;
                var requestLength = request.length;
                var requestEnd = requestStart + requestLength;

                if (settings.clearCache) {
                    ajax = true;
                    settings.clearCache = false;
                } else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
                    ajax = true;
                } else if (
                    JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
                    JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                    JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
                ) {
                    ajax = true;
                }

                cacheLastRequest = $.extend(true, {}, request);

                if (ajax) {
                    if (requestStart < cacheLower) {
                        requestStart = requestStart - requestLength * (conf.pages - 1);
                        if (requestStart < 0) {
                            requestStart = 0;
                        }
                    }

                    cacheLower = requestStart;
                    cacheUpper = requestStart + requestLength * conf.pages;

                    request.start = requestStart;
                    request.length = requestLength * conf.pages;

                    if (typeof conf.data === 'function') {
                        var d = conf.data(request);
                        if (d) {
                            $.extend(request, d);
                        }
                    } else if ($.isPlainObject(conf.data)) {
                        $.extend(request, conf.data);
                    }

                    return $.ajax({
                        type: conf.method,
                        url: conf.url,
                        data: request,
                        dataType: 'json',
                        cache: false,
                        success: function(json) {
                            cacheLastJson = $.extend(true, {}, json);

                            if (cacheLower != drawStart) {
                                json.data.splice(0, drawStart - cacheLower);
                            }
                            if (requestLength >= -1) {
                                json.data.splice(requestLength, json.data.length);
                            }

                            drawCallback(json);
                        },
                    });
                } else {
                    json = $.extend(true, {}, cacheLastJson);
                    json.draw = request.draw;
                    json.data.splice(0, requestStart - cacheLower);
                    json.data.splice(requestLength, json.data.length);

                    drawCallback(json);
                }
            };
        };


        $.fn.dataTable.Api.register('clearPipeline()', function() {
            return this.iterator('table', function(settings) {
                settings.clearCache = true;
            });
        });

        $(document).ready(function() {
            $('#example').DataTable({
                processing: true,
                serverSide: true,
                select: {
                    style: 'multi'
                },
                ajax: $.fn.dataTable.pipeline({
                    url: 'scripts/server_processing.php',
                    pages: 5,
                }),
            });
        });
    </script>
</body>

</html>