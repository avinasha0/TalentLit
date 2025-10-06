// Analytics Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    let charts = {};
    const tenant = window.location.pathname.split('/')[1];
    
    // Initialize the dashboard
    init();
    
    function init() {
        setupEventListeners();
        loadData();
    }
    
    function setupEventListeners() {
        // Apply filters button
        document.getElementById('apply-filters').addEventListener('click', loadData);
        
        // Preset buttons
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const days = this.dataset.days;
                setDateRange(days);
                loadData();
            });
        });
        
        // Export button
        document.getElementById('export-data').addEventListener('click', function(e) {
            e.preventDefault();
            exportData();
        });
    }
    
    function setDateRange(days) {
        const fromInput = document.getElementById('date-from');
        const toInput = document.getElementById('date-to');
        const today = new Date();
        
        switch(days) {
            case '30':
                fromInput.value = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                toInput.value = today.toISOString().split('T')[0];
                break;
            case '90':
                fromInput.value = new Date(today.getTime() - 90 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                toInput.value = today.toISOString().split('T')[0];
                break;
            case 'ytd':
                fromInput.value = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
                toInput.value = today.toISOString().split('T')[0];
                break;
            case 'all':
                fromInput.value = '2020-01-01';
                toInput.value = today.toISOString().split('T')[0];
                break;
        }
    }
    
    function showLoading() {
        document.getElementById('loading-state').classList.remove('hidden');
    }
    
    function hideLoading() {
        document.getElementById('loading-state').classList.add('hidden');
    }
    
    async function loadData() {
        showLoading();
        
        try {
            const from = document.getElementById('date-from').value;
            const to = document.getElementById('date-to').value;
            
            const response = await fetch(`/${tenant}/analytics/data?from=${from}&to=${to}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            updateKPIs(data);
            updateCharts(data);
            
        } catch (error) {
            console.error('Error loading analytics data:', error);
            alert('Error loading analytics data. Please try again.');
        } finally {
            hideLoading();
        }
    }
    
    function updateKPIs(data) {
        // Calculate totals
        const totalApplications = data.applications_over_time.reduce((sum, item) => sum + item.applications_count, 0);
        const totalHires = data.hires_over_time.reduce((sum, item) => sum + item.hires_count, 0);
        const activePipeline = data.pipeline_snapshot.reduce((sum, item) => sum + item.in_stage_count, 0);
        
        // Update KPI displays
        document.getElementById('kpi-total-applications').textContent = totalApplications.toLocaleString();
        document.getElementById('kpi-hires').textContent = totalHires.toLocaleString();
        
        // Check if time_to_hire_summary exists and has median_days
        if (data.time_to_hire_summary && data.time_to_hire_summary.median_days !== undefined) {
            document.getElementById('kpi-median-time').textContent = data.time_to_hire_summary.median_days + ' days';
        } else {
            document.getElementById('kpi-median-time').textContent = 'N/A';
        }
        
        document.getElementById('kpi-active-pipeline').textContent = activePipeline.toLocaleString();
    }
    
    function updateCharts(data) {
        // Destroy existing charts
        Object.values(charts).forEach(chart => {
            if (chart) chart.destroy();
        });
        charts = {};
        
        // Create new charts
        createApplicationsChart(data.applications_over_time);
        createHiresChart(data.hires_over_time);
        createStageFunnelChart(data.stage_funnel);
        createSourceEffectivenessChart(data.source_effectiveness);
        createOpenJobsChart(data.open_jobs_by_dept);
        createPipelineSnapshotChart(data.pipeline_snapshot);
    }
    
    function createApplicationsChart(data) {
        const ctx = document.getElementById('applications-chart').getContext('2d');
        charts.applications = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => formatDate(item.date)),
                datasets: [{
                    label: 'Applications',
                    data: data.map(item => item.applications_count),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    function createHiresChart(data) {
        const ctx = document.getElementById('hires-chart').getContext('2d');
        charts.hires = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => formatDate(item.date)),
                datasets: [{
                    label: 'Hires',
                    data: data.map(item => item.hires_count),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    function createStageFunnelChart(data) {
        const ctx = document.getElementById('stage-funnel-chart').getContext('2d');
        charts.stageFunnel = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(item => item.stage),
                datasets: [{
                    label: 'Applications',
                    data: data.map(item => item.applications_count),
                    backgroundColor: 'rgba(147, 51, 234, 0.8)',
                    borderColor: 'rgb(147, 51, 234)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    function createSourceEffectivenessChart(data) {
        const container = document.getElementById('source-effectiveness-container');
        const ctx = document.getElementById('source-effectiveness-chart').getContext('2d');
        
        // Hide if no source data
        if (data.length === 1 && data[0].applications === 0) {
            container.style.display = 'none';
            return;
        }
        
        container.style.display = 'block';
        
        charts.sourceEffectiveness = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(item => item.source),
                datasets: [{
                    label: 'Applications',
                    data: data.map(item => item.applications),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }, {
                    label: 'Hired',
                    data: data.map(item => item.hired),
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    function createOpenJobsChart(data) {
        const ctx = document.getElementById('open-jobs-chart').getContext('2d');
        charts.openJobs = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(item => item.department),
                datasets: [{
                    label: 'Open Jobs',
                    data: data.map(item => item.open_jobs),
                    backgroundColor: 'rgba(245, 158, 11, 0.8)',
                    borderColor: 'rgb(245, 158, 11)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    function createPipelineSnapshotChart(data) {
        const ctx = document.getElementById('pipeline-snapshot-chart').getContext('2d');
        charts.pipelineSnapshot = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.map(item => item.stage),
                datasets: [{
                    data: data.map(item => item.in_stage_count),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(147, 51, 234, 0.8)'
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                        'rgb(147, 51, 234)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    function formatDate(dateStr) {
        // Ensure dateStr is a string
        if (typeof dateStr !== 'string') {
            console.warn('formatDate received non-string value:', dateStr, typeof dateStr);
            dateStr = String(dateStr);
        }
        
        // Handle both YYYY-MM-DD and YYYY-WW formats
        if (dateStr.includes('-W')) {
            const [year, week] = dateStr.split('-W');
            return `W${week} ${year}`;
        }
        return new Date(dateStr).toLocaleDateString();
    }
    
    function exportData() {
        const from = document.getElementById('date-from').value;
        const to = document.getElementById('date-to').value;
        window.open(`/${tenant}/analytics/export?from=${from}&to=${to}`, '_blank');
    }
});
