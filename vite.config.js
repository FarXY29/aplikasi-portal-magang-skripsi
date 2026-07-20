import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import os from 'os';

function getLocalIp() {
    const interfaces = os.networkInterfaces();
    
    // 1. Prioritize Wi-Fi / WLAN / Wireless adapters (most common when connecting from mobile phone)
    for (const name of Object.keys(interfaces)) {
        const lowerName = name.toLowerCase();
        if (lowerName.includes('wi-fi') || lowerName.includes('wlan') || lowerName.includes('wireless')) {
            for (const iface of interfaces[name]) {
                if (iface.family === 'IPv4' && !iface.internal && !iface.address.startsWith('169.254.')) {
                    return iface.address;
                }
            }
        }
    }

    // 2. Check standard physical Ethernet (excluding virtual, hyper-v, vethernet, vpn, etc.)
    for (const name of Object.keys(interfaces)) {
        const lowerName = name.toLowerCase();
        if (lowerName.includes('ethernet') && !lowerName.includes('vethernet') && !lowerName.includes('virtual') && !lowerName.includes('vmware') && !lowerName.includes('vbox') && !lowerName.includes('hyper-v')) {
            for (const iface of interfaces[name]) {
                if (iface.family === 'IPv4' && !iface.internal && !iface.address.startsWith('169.254.')) {
                    return iface.address;
                }
            }
        }
    }

    // 3. Fallback: Any other non-virtual IPv4 address
    for (const name of Object.keys(interfaces)) {
        const lowerName = name.toLowerCase();
        if (lowerName.includes('virtual') || lowerName.includes('vmware') || lowerName.includes('vbox') || lowerName.includes('wsl') || lowerName.includes('tailscale') || lowerName.includes('zerotier') || lowerName.includes('vethernet') || lowerName.includes('hyper-v') || lowerName.includes('radmin') || lowerName.includes('hamachi')) {
            continue;
        }
        for (const iface of interfaces[name]) {
            if (iface.family === 'IPv4' && !iface.internal && !iface.address.startsWith('169.254.')) {
                return iface.address;
            }
        }
    }

    return 'localhost';
}

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const localIp = env.VITE_HOST || getLocalIp();

    return {
        server: {
            host: '0.0.0.0',
            hmr: {
                host: localIp,
            },
        },
        plugins: [
            laravel({
                input: [
                    'resources/css/app.css',
                    'resources/css/peserta.css',
                    'resources/js/app.js',
                ],
                refresh: true,
            }),
        ],
    };
});
