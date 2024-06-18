document.addEventListener('DOMContentLoaded', () => {
    const photoContainer = document.getElementById('photo-container');
    const likeButton = document.getElementById('like');
    const notLikeButton = document.getElementById('not-like');
    const showRouteButton = document.getElementById('show-route');

    let currentPhotos = [];
    let likedPlaces = [];

    function loadRandomPhotos() {
        fetch('get_random_photo.php')
            .then(response => response.json())
            .then(data => {
                currentPhotos = data;
                photoContainer.innerHTML = '';
                data.forEach(photo => {
                    const imgElement = document.createElement('img');
                    imgElement.src = photo.url;
                    imgElement.alt = "観光スポット";
                    photoContainer.appendChild(imgElement);
                });
            });
    }

    function saveLike(photo) {
        fetch('save_like.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ photoId: photo.id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                likedPlaces.push(photo);
            }
        });
    }

    likeButton.addEventListener('click', () => {
        const firstPhoto = photoContainer.firstChild;
        saveLike(currentPhotos[0]);
        firstPhoto.remove();
        currentPhotos.shift();
        if (currentPhotos.length === 0) {
            loadRandomPhotos();
        }
    });

    notLikeButton.addEventListener('click', () => {
        const firstPhoto = photoContainer.firstChild;
        firstPhoto.remove();
        currentPhotos.shift();
        if (currentPhotos.length === 0) {
            loadRandomPhotos();
        }
    });

    showRouteButton.addEventListener('click', () => {
        if (likedPlaces.length > 0) {
            const waypoints = likedPlaces.map(place => ({
                location: new google.maps.LatLng(place.latitude, place.longitude),
                stopover: true
            }));
            const start = waypoints.shift().location;
            const end = waypoints.pop().location;

            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer();

            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 7,
                center: start
            });
            directionsRenderer.setMap(map);

            directionsService.route({
                origin: start,
                destination: end,
                waypoints: waypoints,
                optimizeWaypoints: true,
                travelMode: google.maps.TravelMode.DRIVING
            }, (response, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(response);
                } else {
                    alert('ルート表示に失敗しました: ' + status);
                }
            });
        } else {
            alert('ルートを表示するために写真を「気に入った」にしてください。');
        }
    });

    loadRandomPhotos();
});