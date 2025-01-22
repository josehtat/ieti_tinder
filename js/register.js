// Form validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const email = document.getElementById('email').value;
    const location = document.getElementById('location').value;
    
    if (password.length < 8) {
        e.preventDefault();
        alert('Password must be at least 8 characters long');
        return;
    }
    
    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        e.preventDefault();
        alert('Please enter a valid email address');
        return;
    }

    if (!location) {
        e.preventDefault();
        alert('Please select your location on the map');
        return;
    }
});

// Crear un ícono personalizado
const customIcon = L.icon({
    iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', // URL de la imagen del ícono
    iconSize: [30, 30], // Tamaño del ícono [ancho, alto]
    iconAnchor: [15, 30], // Punto de anclaje [x, y]
    popupAnchor: [0, -30] // Punto donde aparece el popup [x, y]
});

// Configurar el mapa
const map = L.map('map').setView([0, 0], 2); // Vista inicial del mapa

// Agregar un mapa base (OpenStreetMap)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
}).addTo(map);

// Crear marcador vacío
let marker;

// Detectar clics en el mapa
map.on('click', function (e) {
    const lat = e.latlng.lat.toFixed(5); // Redondear latitud a 5 decimales
    const lng = e.latlng.lng.toFixed(5); // Redondear longitud a 5 decimales

    // Mostrar las coordenadas en los campos de entrada individuales
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    // Combinar las coordenadas en un formato único y asignarlo al campo oculto
    document.getElementById('location').value = `${lat} ${lng}`;

    // Mover o crear el marcador con el ícono personalizado
    if (marker) {
        marker.setLatLng(e.latlng);
    } else {
        marker = L.marker(e.latlng, { icon: customIcon }).addTo(map);
    }
});