<?php
define('NOTOKENRENEWAL',1);
require '../../main.inc.php';
llxHeader('', 'Territoire');
print load_fiche_titre('Nouveau Territoire', '', 'globe');
if (!empty($_POST['label'])) {
  $label=$db->escape(trim($_POST['label']));
  print '<tr><td>Latitude</td><td><input type="text" name="latitude" id="lat" value="'.($object->latitude??'').'"></td></tr>';
  print '<tr><td>Longitude</td><td><input type="text" name="longitude" id="lon" value="'.($object->longitude??'').'"></td></tr>';
  print '<tr><td colspan="2"><div id="minimap" style="height:350px;width:100%"></div><small>Clique sur la carte pour remplir</small></td></tr>';
  print '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" /><script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
  var mmap = L.map("minimap").setView([5.36, -4.00], 12);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(mmap);
  var mk; mmap.on("click", function(e){ if(mk) mmap.removeLayer(mk); mk=L.marker(e.latlng).addTo(mmap); document.getElementById("lat").value=e.latlng.lat.toFixed(6); document.getElementById("lon").value=e.latlng.lng.toFixed(6); });
  </script>';
  $ref='TERR-'.time();
  $db->query("INSERT INTO ".MAIN_DB_PREFIX."reseau_territoire (ref,label,status,datec) VALUES ('$ref','$label',1,NOW())");
  header('Location: territoire_list.php?idmenu=52&mainmenu=reseau&leftmenu='); exit;
}
print '<div style="background:#fff;padding:20px;max-width:500px;border:1px solid #ddd"><form method="POST"><input type="text" name="label" required placeholder="Ex: Cocody" style="width:70%;padding:8px"> <input type="submit" class="button" style="background:#9b7eb8" value="CREER"></form></div>';
llxFooter();
