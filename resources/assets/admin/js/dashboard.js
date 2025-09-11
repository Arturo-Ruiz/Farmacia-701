if (typeof window.dashboardData !== "undefined") {
    const { dailySales, deliveryTypes, salesByLaboratory } =
        window.dashboardData;

    // Procesar datos para el gráfico de ventas
    const salesLabels = dailySales.map((item) => {
        const date = new Date(item.date);
        return date.toLocaleDateString("es-ES", {
            month: "short",
            day: "numeric",
        });
    });
    const salesValues = dailySales.map((item) => parseFloat(item.total));

    // Procesar datos para laboratorios - VARIABLES CORREGIDAS
    const laboratoryLabels = salesByLaboratory.map((item) => item.laboratory);
    const laboratoryValues = salesByLaboratory.map((item) =>
        parseFloat(item.total_sales)
    );
    const laboratoryQuantityLabels = salesByLaboratory.map(
        (item) => item.laboratory
    );
    const laboratoryQuantityValues = salesByLaboratory.map(
        (item) => item.quantity_sold
    );

    // Gráfico de ventas - siguiendo el patrón exacto que funciona
    var ctx1 = document.getElementById("chart-sales").getContext("2d");
    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, "rgba(94, 114, 228, 0.2)");
    gradientStroke1.addColorStop(0.2, "rgba(94, 114, 228, 0.0)");
    gradientStroke1.addColorStop(0, "rgba(94, 114, 228, 0)");

    new Chart(ctx1, {
        type: "line",
        data: {
            labels: salesLabels,
            datasets: [
                {
                    label: "Ventas ($)",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#5e72e4",
                    backgroundColor: gradientStroke1,
                    borderWidth: 3,
                    fill: true,
                    data: salesValues,
                    maxBarThickness: 6,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
            },
            interaction: {
                intersect: false,
                mode: "index",
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5],
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: "#fbfbfb",
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: "normal",
                            lineHeight: 2,
                        },
                    },
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        borderDash: [5, 5],
                    },
                    ticks: {
                        display: true,
                        color: "#ccc",
                        padding: 20,
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: "normal",
                            lineHeight: 2,
                        },
                    },
                },
            },
        },
    });

    // Gráfico de tipos de entrega
    var ctx2 = document.getElementById("chart-delivery-types");
    if (ctx2) {
        ctx2 = ctx2.getContext("2d");

        const deliveryLabels = deliveryTypes.map((item) =>
            item.delivery_type === "pickup" ? "Retiro en tienda" : "Delivery"
        );
        const deliveryValues = deliveryTypes.map((item) => item.count);

        new Chart(ctx2, {
            type: "doughnut",
            data: {
                labels: deliveryLabels,
                datasets: [
                    {
                        label: "Entregas",
                        weight: 9,
                        cutout: 60,
                        tension: 0.9,
                        pointRadius: 2,
                        borderWidth: 2,
                        backgroundColor: ["#5e72e4", "#11cdef"],
                        borderColor: ["#5e72e4", "#11cdef"],
                        data: deliveryValues,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: "bottom",
                    },
                },
            },
        });
    }

    // Gráfico de ventas por laboratorio (Barras verticales)
    var ctx3 = document.getElementById("chart-laboratory-sales");
    if (ctx3) {
        ctx3 = ctx3.getContext("2d");

        new Chart(ctx3, {
            type: "bar",
            data: {
                labels: laboratoryLabels,
                datasets: [
                    {
                        label: "Ventas ($)",
                        tension: 0.4,
                        borderWidth: 0,
                        borderRadius: 4,
                        borderSkipped: false,
                        backgroundColor: "#5e72e4",
                        data: laboratoryValues,
                        maxBarThickness: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                interaction: {
                    intersect: false,
                    mode: "index",
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            suggestedMin: 0,
                            suggestedMax: 500,
                            beginAtZero: true,
                            padding: 15,
                            font: {
                                size: 14,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                            color: "#fff",
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            display: true,
                            color: "#ccc",
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: "normal",
                                lineHeight: 2,
                            },
                        },
                    },
                },
            },
        });
    }

   
}

// Código del scrollbar - mantener igual que el dashboard original
var win = navigator.platform.indexOf("Win") > -1;
if (win && document.querySelector("#sidenav-scrollbar")) {
    var options = {
        damping: "0.5",
    };
    Scrollbar.init(document.querySelector("#sidenav-scrollbar"), options);
}

// Animaciones de entrada para las tarjetas de métricas
document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".card");
    cards.forEach((card, index) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(20px)";

        setTimeout(() => {
            card.style.transition = "all 0.5s ease";
            card.style.opacity = "1";
            card.style.transform = "translateY(0)";
        }, index * 100);
    });
});
