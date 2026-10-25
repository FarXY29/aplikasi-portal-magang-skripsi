/**
 * SIMAGANG Dynamic Vendor Loader
 * De-CDN client libraries into on-demand Vite dynamic chunks.
 */

let chartPromise = null;
export function loadChart() {
    if (!chartPromise) {
        chartPromise = import('./chart-bundle').then(m => {
            const Chart = m.default || m.Chart || m;
            window.Chart = Chart;
            return Chart;
        });
    }
    return chartPromise;
}

let leafletPromise = null;
export function loadLeaflet() {
    if (!leafletPromise) {
        leafletPromise = Promise.all([
            import('leaflet'),
            import('leaflet/dist/leaflet.css'),
            import('leaflet/dist/images/marker-icon.png'),
            import('leaflet/dist/images/marker-icon-2x.png'),
            import('leaflet/dist/images/marker-shadow.png'),
        ]).then(([leafletMod, _, iconUrl, icon2xUrl, shadowUrl]) => {
            const L = leafletMod.default || leafletMod;
            if (L.Icon && L.Icon.Default) {
                delete L.Icon.Default.prototype._getIconUrl;
                L.Icon.Default.mergeOptions({
                    iconUrl: iconUrl.default || iconUrl,
                    iconRetinaUrl: icon2xUrl.default || icon2xUrl,
                    shadowUrl: shadowUrl.default || shadowUrl,
                });
            }
            window.L = L;
            return L;
        });
    }
    return leafletPromise;
}

let html5QrcodePromise = null;
export function loadHtml5Qrcode() {
    if (!html5QrcodePromise) {
        html5QrcodePromise = import('html5-qrcode').then(m => {
            const Html5Qrcode = m.Html5Qrcode || m.default?.Html5Qrcode || m;
            window.Html5Qrcode = Html5Qrcode;
            if (m.Html5QrcodeScanner) window.Html5QrcodeScanner = m.Html5QrcodeScanner;
            return Html5Qrcode;
        });
    }
    return html5QrcodePromise;
}

let pdfjsPromise = null;
export function loadPdfjs() {
    if (!pdfjsPromise) {
        pdfjsPromise = Promise.all([
            import('pdfjs-dist'),
            import('pdfjs-dist/build/pdf.worker.min.mjs?url')
        ]).then(([pdfjsLibMod, workerUrl]) => {
            const lib = pdfjsLibMod.default || pdfjsLibMod;
            if (lib.GlobalWorkerOptions && !lib.GlobalWorkerOptions.workerSrc) {
                lib.GlobalWorkerOptions.workerSrc = workerUrl.default || workerUrl;
            }
            window.pdfjsLib = lib;
            return lib;
        });
    }
    return pdfjsPromise;
}

window.SimagangVendor = {
    chart: loadChart,
    leaflet: loadLeaflet,
    html5Qrcode: loadHtml5Qrcode,
    pdfjs: loadPdfjs,
};
