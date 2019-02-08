<template>
    <div class="container">
        <div class="card-header">Moving Map</div>
        <button class="btn btn-success" @click="updateLocation">Update Position</button>
        <h2 v-if="error.length">{{error}}</h2>
        <div id="movingmap" style="height:400px; width:400px; margin: 0px auto;"></div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                map: null,
                marker: null,
                center: {lat: 10, lng: 10},
                data: null,
                lineCoordinates: [],
                lat: '',
                long: '',
                error: '',
                position: null,
                positions: {}
            }
        },
        methods: {
            mapInit(){
                this.map = new google.maps.Map(document.getElementById('movingmap'), {
                center: this.center,
                zoom: 8
                });
                this.marker = new google.maps.Marker({
                    map: this.map,
                    position: this.center,
                    animation: "bounce",
                });
            },
            updateMap(){
                let newPosition = {lat: this.data.lat, lng:this.data.long};
                this.map.setCenter(newPosition);
                this.marker.setPosition(newPosition);
                this.lineCoordinates.push(new google.maps.LatLng(newPosition.lat, newPosition.lng));

                var lineCoordinatesPath = new google.maps.Polyline({
                    path: this.lineCoordinates,
                    geodesic: true,
                    map: this.map,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                });
            },
           
            updateLocation(){
               
                if(navigator.geolocation)
                {                    
                    navigator.geolocation.watchPosition(this.showPosition, this.geoOption);
                    
                }else{
                    this.error = "Geolocation is not supported."; 
                        
                }
            },
            showPosition(position) {
                
                this.lat = position.coords.latitude;
                this.long = position.coords.longitude;
                
                let positions = {
                        lat: this.lat,
                        long: this.long
                };
                axios.post('api/map', positions)
                .then(res => {
                    console.log(res.data);
                    
                });                        
            },
            geoOption(){
                return {                    
                    enableHighAccuracy: true, 
                    maximumAge        : 60000, 
                    timeout           : 60000
                }
            }
                
        },
        mounted() {
            console.log('Component mounted.');
            this.mapInit();
        },
        created(){
            Echo.channel('location')
                .listen('SendLocation', (e) => {
                    this.data = e.location;
                    this.updateMap();
                    console.log(this.data);
            });
        }
        
    }
    
</script>
