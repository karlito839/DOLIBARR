<?php
require __DIR__.'/../../main.inc.php';
llxHeader('', 'Carte Terrain');
print load_fiche_titre('Carte Terrain - Territoires Réseau');

$sql = "SELECT rowid, ref, label, latitude, longitude FROM ".MAIN_DB_PREFIX."reseau_territoire WHERE latitude IS NOT NULL";
$resql = $db->query($sql);
$nb = $db->num_rows($resql);

echo '<div style="padding:10px;background:#d4edda">Territoires avec GPS trouvés: '.$nb.'</div>';

echo '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" /><script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>';
echo '<div id="map" style="height:75vh;width:100%;border:2px solid red"></div>';
?>
<script>
var map = L.map('map').setView([5.402, -3.983], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

<?php
if ($resql) {
  while($o = $db->fetch_object($resql)){
    echo "L.marker([".$o->latitude.",".$o->longitude."]).addTo(map).bindPopup(\"<b>".addslashes($o->label)."</b>\");\n";
  }
}
?>
</script>
<?php llxFooter(); ?>
