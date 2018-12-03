<!DOCTYPE html>
<!-- Route::get('/s', function () {
    return view('map', ['domain' => env('APP_URL')]);
});-->
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0,shrink-to-fit=no" />
    <title>subway</title>
</head>
<body>
    <div style="position:absolute; z-index:999;">
        <select id="selectcity">
            <option value="3100">上海</option>
            <option value="1100">北京</option><option value="1200">天津</option><option value="1301">石家庄</option><option value="2101">沈阳</option><option value="2102">大连</option><option value="2201">长春</option><option value="2301">哈尔滨</option><option value="3201">南京</option><option value="3202">无锡</option><option value="3205">苏州</option><option value="3301">杭州</option><option value="3302">宁波</option><option value="3401">合肥</option><option value="3501">福州</option><option value="3502">厦门</option><option value="3601">南昌</option><option value="3702">青岛</option><option value="4101">郑州</option><option value="4201">武汉</option><option value="4301">长沙</option><option value="4401">广州</option><option value="4403">深圳</option><option value="4406">佛山</option><option value="4419">东莞</option><option value="4501">南宁</option><option value="5000">重庆</option><option value="5101">成都</option><option value="5201">贵阳</option><option value="5301">昆明</option><option value="6101">西安</option><option value="6501">乌鲁木齐</option><option value="8100">香港</option>
        </select>
    </div>
    
    <div id="mybox"></div>

    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://webapi.amap.com/subway?v=1.0&key=c6a7fd65eb4df2dc7514e53a72ac67df&callback=cbk"></script>
    <script type="text/javascript">
    function GetRequest() {
         var url = location.search;
         var theRequest = new Object();
         if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            strs = str.split("&");
            for(var i = 0; i < strs.length; i++) {
               theRequest[strs[i].split("=")[0]]=decodeURI(strs[i].split("=")[1]);
            }
         }
         return theRequest;
    }

    $("#selectcity").change(function(){
        var val = $(this).val();
        window.location.href = '/s?code='+val;
    });

    window.cbk = function() {
        var code = 3100;
        var Request = new Object();
        Request = GetRequest();
        code = Request['code'] > 0 ? Request['code'] : code;
        $("#selectcity").val(code);

        var mySubway = subway("mybox", {
            adcode: code,
            theme: "colorful",
            client: 0,
            doubleclick: {
                switch: true
            }
        });

        // 获取当前中国已经开通地铁的城市列表
        // mySubway.getCityList(function(ev, info){
        //     $.each(ev, function(i, item){});
        // });

        //地铁加载完成，执行complete事件
        mySubway.event.on("subway.complete", function(ev, info) {
            var id = info.id;
        });

        //点击站点，显示此站点的信息窗体
        mySubway.event.on("station.touch", function(ev, info) {
            var id = info.id;
            mySubway.stopAnimation();
            mySubway.addInfoWindow(id, {});
            var center = mySubway.getStCenter(id);
            mySubway.setCenter(center);
        });

        //点击线路名，高亮此线路
        mySubway.event.on("lineName.touch", function(ev, info) {
            mySubway.showLine(info.id);
            var center = mySubway.getSelectedLineCenter();
            mySubway.setCenter(center);
        });

        //点击空白, 关闭infowindow
        mySubway.event.on("subway.touch", function() {
            mySubway.clearInfoWindow();
        });

        //设置起点
        mySubway.event.on("startStation.touch", function(ev, info) {
            mySubway.stopAnimation();
            mySubway.clearInfoWindow();
            mySubway.setStart(info.id, {});
            startInfo = info;
            route();
        });

        //设置终点
        mySubway.event.on("endStation.touch", function(ev, info) {
            mySubway.stopAnimation();
            mySubway.clearInfoWindow();
            mySubway.setEnd(info.id, {});
            endInfo = info;
            route();
        });

        //路线规划
        var startInfo = {},
            endInfo = {};
        function route() {
            if (startInfo.id && endInfo.id) {
                mySubway.route(startInfo.id, endInfo.id, {});
                startInfo = {};
                endInfo = {};
            }
        }        
    };
    </script>
</body>
</html>