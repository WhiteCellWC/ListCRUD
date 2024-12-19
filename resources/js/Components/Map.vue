<template>
    <div id="map" style="height: 500px;"></div>
    <div v-if="clickedLatLng">
        <p>Latitude: {{ clickedLatLng.latitude }}</p>
        <p>Longitude: {{ clickedLatLng.longitude }}</p>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import 'ol/ol.css';
import { Map, View } from 'ol';
import TileLayer from 'ol/layer/Tile';
import OSM from 'ol/source/OSM';
import { fromLonLat, toLonLat } from 'ol/proj';
import { Point } from 'ol/geom';
import { Feature } from 'ol';
import { Vector as VectorLayer } from 'ol/layer';
import { Vector as VectorSource } from 'ol/source';
import { Icon, Style } from 'ol/style';

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({ latitude: 37.7749, longitude: -122.4194 }) // Default to San Francisco
    }
});

const emit = defineEmits(['update:modelValue']);

const clickedLatLng = ref({ ...props.modelValue });

onMounted(() => {
    const vectorSource = new VectorSource();

    const marker = new Feature({
        geometry: new Point(fromLonLat([props.modelValue.longitude, props.modelValue.latitude]))
    });

    marker.setStyle(
        new Style({
            image: new Icon({
                src: 'https://www.pngall.com/wp-content/uploads/2017/05/Map-Marker-PNG-HD.png',
                scale: 0.025
            })
        })
    );

    vectorSource.addFeature(marker);

    const map = new Map({
        target: 'map',
        layers: [
            new TileLayer({
                source: new OSM()
            }),
            new VectorLayer({
                source: vectorSource
            })
        ],
        view: new View({
            center: fromLonLat([props.modelValue.longitude, props.modelValue.latitude]),
            zoom: 12
        })
    });

    map.on('click', (event) => {
        const coords = event.coordinate;
        const lonLat = toLonLat(coords);

        clickedLatLng.value = {
            latitude: lonLat[1],
            longitude: lonLat[0]
        };

        marker.setGeometry(new Point(coords));

        emit('update:modelValue', clickedLatLng.value);
    });
});

watch(() => props.modelValue, (newValue) => {
    clickedLatLng.value = { ...newValue };
});
</script>
