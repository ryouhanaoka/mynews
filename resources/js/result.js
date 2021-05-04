function initMap() {
    map = document.getElementById("map");
    console.log("initMap");
    let tokyoTower = {lat: 35.6585769, lng: 139.7454506};
    opt = {
        zoom:13,
        center: tokyoTower,
    };
    mapObj = new google.maps.Map(map, opt);

    marker = new google.maps.Marker({
        position: tokyoTower,
        map: mapObj,
        title: 'tokyoTower'
    })
}