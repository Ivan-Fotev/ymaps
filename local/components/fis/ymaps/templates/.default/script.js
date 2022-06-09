BX.ready(function(){
    ymaps.ready(function () {
        if (typeof fisYmapsElements === 'undefined' || fisYmapsElements === null ) {
            return false;
        } else {
            myMap = new ymaps.Map('fis_ymap__map', {
                center: [55.45, 37.36],
                zoom: 10
            });
            objectManager = new ymaps.ObjectManager({
                clusterize: true,
                gridSize: 32,
            });

            myMap.geoObjects.add(objectManager);
            objectManager.add(fisYmapsElements);
            myMap.setBounds(myMap.geoObjects.getBounds(),{checkZoomRange:true, zoomMargin:8});
        }
    });
});
