import {
    Chart,
    LineController,
    BarController,
    DoughnutController,
    PieController,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
    Filler
} from 'chart.js';

// Register only the controllers, elements, scales, and plugins used by the application
Chart.register(
    LineController,
    BarController,
    DoughnutController,
    PieController,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
    Filler
);

export default Chart;
