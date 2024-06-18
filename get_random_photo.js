document.addEventListener('DOMContentLoaded', () => {
    const photoContainer = document.getElementById('photo-container');
    const photo = document.getElementById('photo');
    const locationText = document.getElementById('location');
    const likeBtn = document.getElementById('like-btn');
    const notLikeBtn = document.getElementById('not-like-btn');
    const mapElement = document.getElementById('map');

    let map;
    let marker;
    let photoCount = 0;
    const maxPhotos = 10;
    let currentPhotoId;

    function initMap(lat, lng) {
        const location = { lat: lat, lng: lng };
        map = new google.maps.Map(mapElement, {
            zoom: 8,
            center: location,
        });
        marker = new google.maps.Marker({
            position: location,
            map: map,
        });
    }

    function updateMap(lat, lng) {
        if (isNaN(lat) || isNaN(lng)) {
            console.error('Invalid latitude or longitude:', lat, lng);
            return;
        }
        const location = { lat: lat, lng: lng };
        map.setCenter(location);
        marker.setPosition(location);
    }

    async function fetchRandomPhoto() {
        try {
            const response = await fetch('get_random_photo.php');
            const data = await response.json();
            console.log('Fetched data:', data);
            if (data && data.url) {
                currentPhotoId = data.id;
                photo.src = data.url;
                locationText.textContent = data.location;
                photoContainer.style.display = 'block';

                const lat = parseFloat(data.latitude);
                const lng = parseFloat(data.longitude);

                console.log('Latitude:', lat, 'Longitude:', lng);

                if (!map) {
                    initMap(lat, lng);
                } else {
                    updateMap(lat, lng);
                }
            } else {
                photoContainer.style.display = 'none';
                alert('No more photos available.');
            }
        } catch (error) {
            console.error('Error fetching photo:', error);
        }
    }

    async function saveVote(vote) {
        try {
            await fetch('get_random_photo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: currentPhotoId, vote: vote })
            });
        } catch (error) {
            console.error('Error saving vote:', error);
        }
    }

    async function handleVote(vote) {
        photoCount++;
        await saveVote(vote);
        if (photoCount >= maxPhotos) {
            photoCount = 0; // Reset local counter
            window.location.href = 'result.html';
            return;
        }
        fetchRandomPhoto();
    }

    likeBtn.addEventListener('click', () => handleVote('like'));
    notLikeBtn.addEventListener('click', () => handleVote('not_like'));

    // 初回の写真を取得
    fetchRandomPhoto();
});