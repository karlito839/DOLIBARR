var map = L.map('map').setView([5.416, -3.99], 11);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap',
    referrerPolicy: 'no-referrer-when-downgrade'
}).addTo(map);
setTimeout(function(){ map.invalidateSize(); }, 400);