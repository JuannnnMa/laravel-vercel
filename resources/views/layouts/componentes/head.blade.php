<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 Custom Styles */
        .select2-container .select2-selection--single {
            height: 42px !important;
            border: 1px solid #e5e5e5 !important;
            border-radius: 6px !important;
            display: flex;
            align-items: center;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
            color: #000;
        }
    </style>

    <title>@yield('title') - Colegio San Simón de Ayacucho</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            display: flex;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* Sidebar and Main Layout */
        .sidebar {
            width: 260px;
            background-color: #000000;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            border: none;
            outline: none;
            margin: 0;
            padding: 0;
        }

        .main-content {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #fafafa;
            width: calc(100% - 260px);
        }

        /* Enhanced Form Styles */
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #000000;
            font-size: 13px;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s;
            background-color: #fff;
            color: #000000;
        }

        .form-control:focus {
            outline: none;
            border-color: #000000;
        }

        /* Fix for select dropdowns */
        select.form-control {
            height: 42px !important;
            padding: 8px 15px !important;
            line-height: 1.5 !important;
            appearance: auto;
            -webkit-appearance: menulist;
            -moz-appearance: menulist;
        }

        select.form-control option {
            padding: 8px;
            line-height: 1.5;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
            font-size: 13px;
        }

        .btn-primary { background-color: #000000; color: white; }
        .btn-primary:hover { background-color: #333333; }
        
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-secondary:hover { background-color: #545b62; }

        .btn-success { background-color: #28a745; color: white; }
        .btn-warning { background-color: #ffc107; color: #212529; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-info { background-color: #17a2b8; color: white; }

        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-light {
             background-color: #f3f4f6;
             color: #1f2937;
        }

        /* Modal Custom Styles Enhanced */
        .modal-custom {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
            align-items: center;
            justify-content: center;
        }

        .modal-custom.active {
            display: flex;
        }

        .modal-custom-content {
            background-color: white;
            border-radius: 16px;
            width: 90%;
            max-width: 650px;
            max-height: 90vh;
            overflow-y: auto;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .modal-custom-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            border-radius: 16px 16px 0 0;
        }

        .modal-custom-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .modal-custom-body {
            padding: 24px;
        }

        .modal-custom-close {
            background: #f3f4f6;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.2s;
        }

        .modal-custom-close:hover {
            background: #e5e7eb;
            color: #111827;
        }

        .modal-footer {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        @keyframes modalPop {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* Topbar & Other styles maintained but cleaned up */
        .topbar {
            background-color: #ffffff;
            padding: 15px 30px;
            border-bottom: 1px solid #e5e5e5;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }
        
        .table-container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .table-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #111827;
        }

        /* DataTables without borders */
        table.dataTable {
            border-collapse: collapse !important;
        }
        
        table.dataTable thead th,
        table.dataTable thead td {
            border-bottom: 1px solid #e5e7eb !important;
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
        }
        
        table.dataTable tbody td {
            border: none !important;
        }
        
        table.dataTable.no-footer {
            border-bottom: none !important;
        }
    </style>
    @stack('styles')
</head>
