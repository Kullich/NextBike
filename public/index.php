<?php

require_once __DIR__ . '/../src/database.php';
require_once __DIR__ . '/../src/bikestation.php';

$db = new Database();
$stationManager = new BikeStation($db);

$search = isset($_GET['search']) ? $_GET['search'] : '';
$minBikes = isset($_GET['min_bikes']) ? $_GET['min_bikes'] : 0;

$stations = $stationManager->getStations($search, $minBikes);

?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stanice sdílených kol Zlín</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body class="bg-gray-500 ">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-center my-4 py-4">Stanice sdílených kol ve Zlíně</h1> 
        <form id="filter-form" method="GET" action="" class="mb-4 flex flex-col sm:flex-row justify-center items-center">
            <input type="text" name="search" placeholder="Vyhledat stanici..." class="border p-2 rounded mb-2 sm:mb-0 sm:mr-2 w-full sm:w-auto" value="<?= htmlspecialchars($search); ?>">
            <input id="min-bikes" type="number" name="min_bikes" placeholder="Minimální počet kol" class="border p-2 rounded mb-2 sm:mb-0 sm:mr-2 w-full sm:w-auto" value="<?= htmlspecialchars($minBikes); ?>">
            <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full sm:w-auto">Vyhledat</button>
        </form>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 text-left border-solid border border-black">Název stanice</th>
                            <th class="py-2 px-4 text-left border-solid border border-black">Dostupná kola</th>
                            <th class="py-2 px-4 text-left border-solid border border-black">Zeměpisné souřadnice</th>
                            <th class="py-2 px-4 text-left border-solid border border-black">Čas aktualizace</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $stations->fetch_assoc()) : ?>
                        <tr>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['station_name']); ?></td>
                            <td class="border px-4 py-2 text-center"><?= htmlspecialchars($row['available_bikes']); ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['latitude']) . ', ' . htmlspecialchars($row['longitude']); ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars(date('Y:m:d H:i', strtotime($row['timestamp']))); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div id="map" style="height: 500px;" class="mb-6"></div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Inicializace mapy
        var map = L.map('map').setView([49.2235, 17.6658], 13);

        // Načtení dlaždic mapy z OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Funkce pro zobrazení značek na mapě
        function showStations(minBikes) {
            // Vyčistíme mapu od existujících značek
            map.eachLayer(function (layer) {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer);
                }
            });

            // Přidání značek podle filtru
            <?php
            $stations = $stationManager->getStations();
            while($row = $stations->fetch_assoc()) {
                echo "if ({$row['available_bikes']} >= minBikes) {
                    L.marker([{$row['latitude']}, {$row['longitude']}])
                        .addTo(map)
                        .bindPopup('<b>{$row['station_name']}</b><br>Dostupná kola: {$row['available_bikes']}');
                }";
            }
            ?>
        }

        // Při načtení stránky zobrazíme všechny stanice
        showStations(0);

        // Při odeslání filtru
            const minBikes = document.getElementById('min-bikes').value;
            showStations(minBikes);
    </script>
</script>

</body>
</html>


<?php
$db->close();


/*<script>
// Inicializace mapy
var map = L.map('map').setView([49.2235, 17.6658], 13); // Centrum Zlína

// Načtení dlaždic mapy (z OpenStreetMap)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

<?php 
$stations = $stationManager->getStations();
while($row = $stations->fetch_assoc()) : ?>
    // Přidání značky pro každou stanici
      L.marker([<?= htmlspecialchars($row['latitude']); ?>, <?= htmlspecialchars($row['longitude']); ?>])
        .addTo(map)
        .bindPopup("<b><?= htmlspecialchars($row['station_name']); ?></b><br>Dostupná kola: <?= htmlspecialchars($row['available_bikes']); ?>")
        .openPopup();
<?php endwhile; ?>*/