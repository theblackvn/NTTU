var map;

    var myLatLng = new google.maps.LatLng(10.760941, 106.710320);
    function initialize() {
        var mapOptions = {
            zoom: 15,
            center: myLatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById('g-map'),
            mapOptions);
        var $content = '<div class="map-info">' +
            '<ul type="none" style="margin:0;padding:0;">' +
            '<li><strong>ĐẠI HỌC NGUYỄN TẤT THÀNH</strong></li>' +
            '<li><span class="c-lime-a5">Địa chỉ</span> : 300A Nguyễn Tất Thành, P13, Q4, Tp Hồ Chí Minh</li>' +
            '<li><span class="c-lime-a5">Tel</span> : (08) 39 411 310 (Tổng Đài) - (08) 62 619 423 - (08) 39 411 189 </li>' +
            '<li><span class="c-lime-a5">Tel</span> : (08) 39 404 759</li>' +
            '</ul>' +
            '</div>';

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'ĐẠI HỌC NGUYỄN TẤT THÀNH'
        });

        var infowindow = new google.maps.InfoWindow({
                content: $content,
                position: myLatLng
            });
        infowindow.open(map);
        google.maps.event.addListener(marker, 'click', function () {
            infowindow.open(map, this);
        });

//        google.maps.event.addListener(map, 'zoom_changed', function() {
//            map.setCenter(myLatLng);
//        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);