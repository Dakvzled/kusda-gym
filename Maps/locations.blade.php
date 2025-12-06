<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gym Locations</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-sA+e2H9f3k3f3h0jzQ+g8kzK1Qh9YyFvRkA+v0v0Xjw=" crossorigin=""/>

    <style>
        body { font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; margin:0; padding:0; background:#FDFDFC; color:#1b1b18 }
        .container { max-width:1100px; margin:24px auto; padding:20px; }
        .header { display:flex; align-items:center; justify-content:space-between; gap:12px; }
        .map-wrap { display:flex; gap:16px; margin-top:16px; }
        #map { height:640px; width:100%; border-radius:8px; }
        .sidebar { width:320px; max-height:640px; overflow:auto; padding:12px; background:#fff; border:1px solid #e3e3e0; border-radius:8px; }
        .location { padding:10px; border-bottom:1px solid #eee; cursor:pointer }
        .location:hover { background:#f6f6f6 }
        .location .title { font-weight:600 }
        .controls { margin-top:8px }
        .btn { display:inline-block; padding:8px 12px; background:#1b1b18; color:#fff; border-radius:6px; text-decoration:none }
        @media (max-width:900px){ .map-wrap{flex-direction:column} .sidebar{width:100%; max-height:240px} #map{height:420px} }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kusda Gym — Locations</h1>
            <div>
                <a href="/" class="btn">Back to home</a>
            </div>
        </div>

        <p style="color:#706f6c; margin-top:8px">Find Kusda Gym branches on the map and click a location to see details.</p>

        <div class="map-wrap">
            <div style="flex:1">
                <div id="map"></div>
            </div>
            <aside class="sidebar">
                <h3>Branches</h3>
                <div id="list">
                    <!-- populated by JS -->
                </div>
                <div class="controls">
                    <a id="fit" href="#" class="btn">Fit all</a>
                </div>
            </aside>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-V4Y6q6Gv6aGv6aGv6aGv6aGv6aGv6aGv6aGv6aGv6A=" crossorigin=""></script>
    <script>
        // Sample gym locations (id, name, address, lat, lng)
        const gyms = [
            { id: 1, name: 'Kusda Gym Central', address: 'Jl. Merdeka No.1, Jakarta', lat: -6.200000, lng: 106.816666 },
            { id: 2, name: 'Kusda Gym South', address: 'Jl. Sudirman No.88, Jakarta', lat: -6.230000, lng: 106.800000 },
            { id: 3, name: 'Kusda Gym North', address: 'Jl. Thamrin No.10, Jakarta', lat: -6.170000, lng: 106.822000 },
            { id: 4, name: 'Kusda Gym Bekasi', address: 'Bekasi Mall, Bekasi', lat: -6.240000, lng: 106.992000 },
            { id: 5, name: 'Kusda Gym Bandung', address: 'Braga Street, Bandung', lat: -6.914744, lng: 107.609810 }
        ];

        const map = L.map('map').setView([-6.2, 106.82], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const markers = {};
        const group = L.featureGroup().addTo(map);

        const listEl = document.getElementById('list');

        gyms.forEach(g => {
            const m = L.marker([g.lat, g.lng]).addTo(group);
            m.bindPopup(`<strong>${escapeHtml(g.name)}</strong><br>${escapeHtml(g.address)}`);
            markers[g.id] = m;

            const item = document.createElement('div');
            item.className = 'location';
            item.dataset.id = g.id;
            item.innerHTML = `<div class="title">${escapeHtml(g.name)}</div><div class="addr">${escapeHtml(g.address)}</div>`;
            item.addEventListener('click', () => {
                map.setView([g.lat, g.lng], 15, { animate: true });
                m.openPopup();
            });
            listEl.appendChild(item);
        });

        document.getElementById('fit').addEventListener('click', (e) => {
            e.preventDefault();
            map.fitBounds(group.getBounds(), { padding: [40,40] });
        });

        // Fit initial bounds
        if (gyms.length) {
            map.fitBounds(group.getBounds(), { padding: [40,40] });
        }

        // small helper
        function escapeHtml(s){ return String(s).replace(/[&<>\"]/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]; }); }
    </script>
</body>
</html>