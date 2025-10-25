
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Garage Boowal — Car Paint Studio</title>
<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
<style>


body{font-family:sans-serif;margin:0;padding:18px;background:#ffffff;color:ffffff}
.container {
  max-width: 1100px;
  margin-left: 18px; /* small spacing from the left edge */
  margin-right: 0;   /* optional */
}

.card{background:#ffffff;padding:14px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.0)}
.viewer-wrap{height:480px;border-radius:10px;overflow:hidden;border:1px solid #e6eefc;position:relative;background:#ffffff}
model-viewer{width:80%;height:100%;display:block}
.controls{display:flex;gap:12px;margin-top:12px;align-items:center}
.swatches{display:flex;gap:8px}
.swatch{display:flex;align-items:center;gap:8px;padding:8px;border-radius:8px;border:1px solid #eef2ff;cursor:pointer}
.swatch .dot{width:28px;height:28px;border-radius:6px}
label{font-size:14px}
select,input[type="color"]{padding:6px 10px;border-radius:6px;border:1px solid #ccc;cursor:pointer}
</style>
</head>
<body>
<div class="container">
  <section class="card">
    <h2>3D Car Paint Customizer</h2>
    <div class="viewer-wrap">
      <model-viewer id="carModel" src="<?php echo $modelFile; ?>" alt="Car model" auto-rotate camera-controls environment-image="neutral" exposure="1"></model-viewer>
    </div>
    <div class="controls">
      <div class="swatches" id="swatches"></div>
      <input type="color" id="colorPicker" value="#1e90ff">
      <select id="finishSelect">
        <option value="glossy">Glossy</option>
        <option value="matte">Matte</option>
        <option value="metallic">Metallic</option>
      </select>
    </div>
  </section>
</div>

<script>
// DOM elements
const modelViewer = document.getElementById('carModel');
const colorPicker = document.getElementById('colorPicker');
const finishSelect = document.getElementById('finishSelect');
const swatchesWrap = document.getElementById('swatches');

let carPaintMat = null;

// Predefined color swatches
const swatches = [
  {name:'Deep Blue', color:'#1e90ff'},
  {name:'Cherry Red', color:'#c53030'},
  {name:'Racing Green', color:'#007f5f'},
  {name:'Sunset Orange', color:'#ff6f3c'},
  {name:'Pearl White', color:'#f4f4f2'},
  {name:'Midnight Black', color:'#0f172a'}
];

// Create swatch buttons
swatches.forEach(s => {
  const btn = document.createElement('button');
  btn.className = 'swatch';
  btn.type = 'button';
  btn.innerHTML = `<span class="dot" style="background:${s.color}"></span>${s.name}`;
  btn.addEventListener('click', () => { applyColor(s.color); });
  swatchesWrap.appendChild(btn);
});

// Convert HEX to [r,g,b,1] array
function hexToRGBArray(hex){
  const r = parseInt(hex.substr(1,2),16)/255;
  const g = parseInt(hex.substr(3,2),16)/255;
  const b = parseInt(hex.substr(5,2),16)/255;
  return [r,g,b,1];
}

// Apply color to the car paint material
function applyColor(hex){
  if(!carPaintMat) return;
  carPaintMat.pbrMetallicRoughness.setBaseColorFactor(hexToRGBArray(hex));
}

// Apply finish (Glossy/Matte/Metallic)
function applyFinish(type){
  if(!carPaintMat) return;
  if(type==='glossy'){ carPaintMat.pbrMetallicRoughness.setRoughnessFactor(0.1); carPaintMat.pbrMetallicRoughness.setMetallicFactor(0.3);}
  if(type==='matte'){ carPaintMat.pbrMetallicRoughness.setRoughnessFactor(0.9); carPaintMat.pbrMetallicRoughness.setMetallicFactor(0);}
  if(type==='metallic'){ carPaintMat.pbrMetallicRoughness.setRoughnessFactor(0.2); carPaintMat.pbrMetallicRoughness.setMetallicFactor(1);}
}

<?php
// Detect selected car model from URL, default to 'jimny'
$model = isset($_GET['model']) ? $_GET['model'] : 'jimny';

// Map models to GLB files
$models = [
  "jimny" => "suzuki_jimny2.glb",
  "swift" => "swift2.glb",
  "dzire" => "dzire11.glb",
  "camri" => "camri.glb",
];

// Pick the correct model file
$modelFile = isset($models[$model]) ? $models[$model] : $models['jimny'];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Garage Boowal — Car Paint Studio</title>
<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
<style>


body{font-family:sans-serif;margin:0;padding:18px;background:#ffffff;color:ffffff}
.container {
  max-width: 1100px;
  margin-left: 18px; /* small spacing from the left edge */
  margin-right: 0;   /* optional */
}

.card{background:#ffffff;padding:14px;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.0)}
.viewer-wrap{height:480px;border-radius:10px;overflow:hidden;border:1px solid #e6eefc;position:relative;background:#ffffff}
model-viewer{width:80%;height:100%;display:block}
.controls{display:flex;gap:12px;margin-top:12px;align-items:center}
.swatches{display:flex;gap:8px}
.swatch{display:flex;align-items:center;gap:8px;padding:8px;border-radius:8px;border:1px solid #eef2ff;cursor:pointer}
.swatch .dot{width:28px;height:28px;border-radius:6px}
label{font-size:14px}
select,input[type="color"]{padding:6px 10px;border-radius:6px;border:1px solid #ccc;cursor:pointer}
</style>
</head>
<body>
<div class="container">
  <section class="card">
    <h2>3D Car Paint Customizer</h2>
    <div class="viewer-wrap">
      <model-viewer id="carModel" src="<?php echo $modelFile; ?>" alt="Car model" auto-rotate camera-controls environment-image="neutral" exposure="1"></model-viewer>
    </div>
    <div class="controls">
      <div class="swatches" id="swatches"></div>
      <input type="color" id="colorPicker" value="#1e90ff">
      <select id="finishSelect">
        <option value="glossy">Glossy</option>
        <option value="matte">Matte</option>
        <option value="metallic">Metallic</option>
      </select>
    </div>
  </section>
</div>

<script>
// DOM elements
const modelViewer = document.getElementById('carModel');
const colorPicker = document.getElementById('colorPicker');
const finishSelect = document.getElementById('finishSelect');
const swatchesWrap = document.getElementById('swatches');

let carPaintMat = null;

// Predefined color swatches
const swatches = [
  {name:'Deep Blue', color:'#1e90ff'},
  {name:'Cherry Red', color:'#c53030'},
  {name:'Racing Green', color:'#007f5f'},
  {name:'Sunset Orange', color:'#ff6f3c'},
  {name:'Pearl White', color:'#f4f4f2'},
  {name:'Midnight Black', color:'#0f172a'}
];

// Create swatch buttons
swatches.forEach(s => {
  const btn = document.createElement('button');
  btn.className = 'swatch';
  btn.type = 'button';
  btn.innerHTML = `<span class="dot" style="background:${s.color}"></span>${s.name}`;
  btn.addEventListener('click', () => { applyColor(s.color); });
  swatchesWrap.appendChild(btn);
});

// Convert HEX to [r,g,b,1] array
function hexToRGBArray(hex){
  const r = parseInt(hex.substr(1,2),16)/255;
  const g = parseInt(hex.substr(3,2),16)/255;
  const b = parseInt(hex.substr(5,2),16)/255;
  return [r,g,b,1];
}

// Apply color to the car paint material
function applyColor(hex){
  if(!carPaintMat) return;
  carPaintMat.pbrMetallicRoughness.setBaseColorFactor(hexToRGBArray(hex));
}

// Apply finish (Glossy/Matte/Metallic)
function applyFinish(type){
  if(!carPaintMat) return;
  if(type==='glossy'){ carPaintMat.pbrMetallicRoughness.setRoughnessFactor(0.1); carPaintMat.pbrMetallicRoughness.setMetallicFactor(0.3);}
  if(type==='matte'){ carPaintMat.pbrMetallicRoughness.setRoughnessFactor(0.9); carPaintMat.pbrMetallicRoughness.setMetallicFactor(0);}
  if(type==='metallic'){ carPaintMat.pbrMetallicRoughness.setRoughnessFactor(0.2); carPaintMat.pbrMetallicRoughness.setMetallicFactor(1);}
}

// When model is loaded, search for the car paint material
modelViewer.addEventListener('load', () => {
  carPaintMat = modelViewer.model.materials.find(mat =>
    mat.name.toLowerCase().includes("carpaint")
  );
  if(carPaintMat){
    applyColor(colorPicker.value);
    applyFinish(finishSelect.value);
  }
});

// Event listeners
colorPicker.addEventListener('input', () => applyColor(colorPicker.value));
finishSelect.addEventListener('change', () => applyFinish(finishSelect.value));
</script>
</body>
</html>


// Event listeners
colorPicker.addEventListener('input', () => applyColor(colorPicker.value));
finishSelect.addEventListener('change', () => applyFinish(finishSelect.value));
</script>
</body>
</html>
