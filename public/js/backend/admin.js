

const chartData = [
    { label: 'Jan', value: 45 },
    { label: 'Feb', value: 52 },
    { label: 'Mar', value: 38 },
    { label: 'Apr', value: 65 },
    { label: 'May', value: 58 },
    { label: 'Jun', value: 82 },
    { label: 'Jul', value: 75 },
    { label: 'Aug', value: 90 },
    { label: 'Sep', value: 85 },
    { label: 'Oct', value: 95 },
    { label: 'Nov', value: 70 },
    { label: 'Dec', value: 60 },
];

// --- Initialization ---
document.addEventListener('DOMContentLoaded', () => {
    renderChart();
    renderOrders();
});

// --- Functions ---

// Render CSS Bar Chart
function renderChart() {
    const chartContainer = document.getElementById('revenueChart');
    if (!chartContainer) return;
    
    chartContainer.innerHTML = '';
    
    // Find max value to calculate percentages
    const maxValue = Math.max(...chartData.map(d => d.value));

    chartData.forEach((data, index) => {
        const heightPercentage = (data.value / maxValue) * 100;
        
        const group = document.createElement('div');
        group.className = 'chart-bar-group';
        
        // Add slight staggered animation delay
        const delay = index * 0.05;

        group.innerHTML = `
            <div class="chart-bar" style="height: ${heightPercentage}%; animation-delay: ${delay}s;" data-value="$${data.value}k"></div>
            <div class="chart-label">${data.label}</div>
        `;
        
        chartContainer.appendChild(group);
    });
}

// Render Orders Table
function renderOrders() {
    const tbody = document.getElementById('ordersTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '';

    ordersData.forEach(order => {
        const tr = document.createElement('tr');
        
        // Determine badge class
        let badgeClass = '';
        if(order.status === 'completed') badgeClass = 'status-completed';
        else if(order.status === 'pending') badgeClass = 'status-pending';
        else badgeClass = 'status-cancelled';

        tr.innerHTML = `
            <td style="font-weight: 500; color: var(--primary);">${order.id}</td>
            <td>
                <div class="product-cell">
                    <img src="https://picsum.photos/seed/${order.img}/40/40" class="product-img" alt="${order.product}">
                    <span>${order.product}</span>
                </div>
            </td>
            <td>${order.customer}</td>
            <td>${order.date}</td>
            <td style="font-weight: 600;">${order.amount}</td>
            <td><span class="status-badge ${badgeClass}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span></td>
            <td>
                <button class="action-btn" onclick="showToast('Viewing details for ${order.id}', 'default')">
                    <span class="material-icons-round">visibility</span>
                </button>
                <button class="action-btn" onclick="deleteOrder(this, '${order.id}')">
                    <span class="material-icons-round">delete</span>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Toggle Sidebar on Mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('open');
}

// Navigation Switcher
function switchTab(element, tabName) {
    // Update active state
    document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
    element.classList.add('active');

    // Update Page Title
    const titles = {
        'dashboard': 'Dashboard',
        'orders': 'Orders Management',
        'products': 'Product Inventory',
        'customers': 'Customer Database',
        'analytics': 'Analytics & Reports',
        'settings': 'System Settings'
    };
    document.getElementById('page-title').innerText = titles[tabName] || 'Dashboard';

    // On mobile, close sidebar after click
    if(window.innerWidth <= 768) {
        document.getElementById('sidebar').classList.remove('open');
    }

    // In a real app, this would route to a new view.
    // For this demo, we just update the title and show a toast.
    if(tabName !== 'dashboard') {
        showToast(`Navigated to ${titles[tabName]}`, 'default');
    }
}

// Toast Notification System
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    let icon = 'check_circle';
    if (type === 'error') icon = 'error';
    if (type === 'default') icon = 'info';

    toast.innerHTML = `
        <span class="material-icons-round" style="color: ${type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--danger)' : 'var(--primary)'}">${icon}</span>
        <span style="font-size: 0.9rem; font-weight: 500;">${message}</span>
    `;

    container.appendChild(toast);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease forwards';
        toast.addEventListener('animationend', () => {
            toast.remove();
        });
    }, 3000);
}

// Delete Order Interaction
function deleteOrder(btn, orderId) {
    // Visual feedback only
    const row = btn.closest('tr');
    row.style.background = '#fee2e2';

    setTimeout(() => {
        row.style.opacity = '0';
        setTimeout(() => {
            row.remove();
            showToast(`Order ${orderId} deleted`, 'success');
        }, 300);
    }, 500);
}



function closeProductModal() {
    document.getElementById('productModal').classList.remove('active');
}

function handleAddProduct(e) {
    e.preventDefault();
    // Here you would gather form data
    closeProductModal();
    showToast('New product added successfully!', 'success');
    e.target.reset(); // Reset form
}

function exportReport() {
    showToast('Generating CSV report... Download started.', 'default');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', () => {
    const productModal = document.getElementById('productModal');
    if (productModal) {
        productModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeProductModal();
            }
        });
    }
});

