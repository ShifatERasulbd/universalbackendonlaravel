@extends('backend.master')

@section('title', 'dashboard - NexusAdmin')
@section('page-title', 'dashboard')

@section('content')

    <!-- ============================= -->
    <!-- Stats Grid (Using Component) -->
    <!-- ============================= -->
    <div class="stats-grid">

        <x-backend.stat-card
            label="Total dashboard"
            value="2,847"
            icon="people"
            change="+15.3% from last month"
            changeType="success"
        />

        <x-backend.stat-card
            label="Active dashboard"
            value="2,134"
            icon="verified_user"
            change="+8.7% from last month"
            changeType="success"
        />

        <x-backend.stat-card
            label="New This Month"
            value="324"
            icon="group_add"
            change="+12.1% from last month"
            changeType="success"
        />

        <x-backend.stat-card
            label="Avg. Order Value"
            value="$156"
            icon="payments"
            change="-3.2% from last month"
            changeType="danger"
        />

    </div>


    <!-- ============================= -->
    <!-- dashboard Table Component -->
    <!-- ============================= -->
    @include('backend.dashboard.table')


    
@endsection



@push('scripts')
<script>

/* ========================================
   Modal Open / Close
======================================== */

function openCustomerModal() {
    document.getElementById('customerModal')?.classList.add('active');
}

function closeCustomerModal() {
    document.getElementById('customerModal')?.classList.remove('active');
}

// Close on overlay click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('customerModal');
    if (!modal) return;
    if (e.target === modal) closeCustomerModal();
});


/* ========================================
   Add Customer Form Submit
======================================== */

function handleAddCustomer(e) {
    e.preventDefault();

    const form = document.getElementById('addCustomerForm');
    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());

    console.log("Customer Data:", payload);

    // TODO: Replace with AJAX call

    if (typeof showToast === 'function') {
        showToast("Customer added successfully!", "success");
    } else {
        alert("Customer added successfully!");
    }

    form.reset();
    closeCustomerModal();
}


/* ========================================
   Table Action Buttons
======================================== */

function viewCustomer(id) {
    showToast?.(`Viewing customer ${id}`, 'default');
}

function editCustomer(id) {
    showToast?.(`Editing customer ${id}`, 'default');
}

function deleteCustomer(id) {
    if(confirm(`Are you sure you want to delete customer ${id}?`)) {
        showToast?.(`Customer ${id} deleted successfully`, 'success');
    }
}

</script>
@endpush
