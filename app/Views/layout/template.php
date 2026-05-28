<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema POS - Sirvalo Pues">
    <meta name="author" content="">

    <title><?= $title ?? 'Sirvalo Pues - POS' ?></title>

    <!-- FontAwesome -->
    <link href="<?= base_url('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="<?= base_url('css/sb-admin-2.min.css') ?>" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            font-family: 'Nunito', sans-serif;
            background: #f4f7fb;
            overflow-x: hidden;
        }

        /* =========================================
            SIDEBAR
        ========================================= */

        .sidebar{
            background: linear-gradient(180deg,#0f172a 0%, #1e293b 100%) !important;
            min-height: 100vh;
            box-shadow: 5px 0 30px rgba(0,0,0,.08);
        }

        .sidebar-brand{
            height: 85px;
            font-size: 1.3rem;
            font-weight: 800;
            color: white !important;
            letter-spacing: .5px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-brand-icon{
            font-size: 28px;
            color: #fb923c;
        }

        .sidebar-brand-text{
            margin-left: 10px;
        }

        .sidebar-heading{
            color: rgba(255,255,255,.4) !important;
            font-size: 11px;
            letter-spacing: 1.5px;
            margin-top: 15px;
            padding-left: 1rem;
        }

        .sidebar .nav-item{
            margin: 6px 12px;
        }

        .sidebar .nav-item .nav-link{
            color: rgba(255,255,255,.75);
            border-radius: 14px;
            padding: 13px 18px;
            font-size: 15px;
            font-weight: 600;
            transition: all .25s ease;
        }

        .sidebar .nav-item .nav-link i{
            margin-right: 10px;
            font-size: 16px;
        }

        .sidebar .nav-item .nav-link:hover{
            background: rgba(255,255,255,.08);
            color: white;
            transform: translateX(4px);
        }

        .sidebar .nav-item.active .nav-link{
            background: linear-gradient(135deg,#fb923c,#f97316);
            color: white !important;
            box-shadow: 0 6px 18px rgba(249,115,22,.35);
        }

        hr.sidebar-divider{
            border-top: 1px solid rgba(255,255,255,.08);
        }

        #sidebarToggle{
            background: rgba(255,255,255,.1);
        }

        /* =========================================
            TOPBAR
        ========================================= */

        .topbar{
            height: 78px;
            background: rgba(255,255,255,.85) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,.05);
            box-shadow: 0 4px 20px rgba(0,0,0,.03);
        }

        .page-title{
            font-size: 26px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 0;
        }

        .topbar .nav-item .nav-link{
            color: #374151 !important;
        }

        .topbar-divider{
            border-right: 1px solid #ddd;
        }

        /* =========================================
            USER AVATAR
        ========================================= */

        .user-avatar{
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg,#fb923c,#f97316);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 18px;
            box-shadow: 0 5px 15px rgba(249,115,22,.3);
        }

        /* =========================================
            CONTENT
        ========================================= */

        #content{
            background: #f4f7fb;
        }

        .container-fluid{
            padding: 25px;
        }

        /* =========================================
            CARDS
        ========================================= */

        .card{
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,.05);
            transition: all .3s ease;
        }

        .card:hover{
            transform: translateY(-2px);
        }

        .card-header{
            background: white;
            border-bottom: 1px solid #f1f5f9;
            padding: 20px 24px;
            font-weight: 800;
            color: #111827;
        }

        .card-body{
            padding: 24px;
        }

        /* =========================================
            TABLES - LETRAS EN NEGRO
        ========================================= */

        .table{
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table thead th{
            border: none !important;
            background: #f8fafc;
            color: #374151 !important;  /* NEGRO SUAVE */
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .table tbody tr{
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,.03);
        }

        .table td{
            border-top: none;
            vertical-align: middle;
            padding: 16px;
            color: #1f2937 !important;  /* NEGRO PARA EL CONTENIDO */
            font-weight: 500;
        }

        /* Enlaces dentro de tablas */
        .table td a{
            color: #f97316;
            font-weight: 600;
            text-decoration: none;
        }

        .table td a:hover{
            color: #fb923c;
            text-decoration: underline;
        }

        /* =========================================
            BUTTONS
        ========================================= */

        .btn{
            border-radius: 12px;
            font-weight: 700;
            padding: 10px 18px;
        }

        .btn-primary{
            background: linear-gradient(135deg,#fb923c,#f97316);
            border: none;
        }

        .btn-primary:hover{
            opacity: .92;
        }

        /* =========================================
            ALERTS
        ========================================= */

        .alert{
            border: none;
            border-radius: 16px;
            padding: 16px 20px;
            box-shadow: 0 4px 18px rgba(0,0,0,.04);
        }

        /* =========================================
            DROPDOWN
        ========================================= */

        .dropdown-menu{
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
            padding: 10px;
        }

        .dropdown-item{
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 600;
        }

        .dropdown-item:hover{
            background: #f3f4f6;
        }

        /* =========================================
            FOOTER
        ========================================= */

        .sticky-footer{
            background: transparent !important;
            padding-bottom: 20px;
        }

        .copyright{
            color: #6b7280;
            font-weight: 600;
        }

        /* =========================================
            DATATABLE
        ========================================= */

        .dataTables_wrapper{
            padding: 10px 0;
        }

        .dataTables_filter input{
            border-radius: 12px !important;
            border: 1px solid #d1d5db !important;
            padding: 8px 14px !important;
            margin-left: 10px !important;
        }

        .dataTables_length select{
            border-radius: 12px !important;
            border: 1px solid #d1d5db !important;
        }

        .paginate_button{
            border-radius: 10px !important;
        }

        /* DataTables texto en negro */
        .dataTables_info,
        .dataTables_length label,
        .dataTables_filter label{
            color: #374151 !important;
        }

        /* =========================================
            SCROLLBAR
        ========================================= */

        ::-webkit-scrollbar{
            width: 8px;
        }

        ::-webkit-scrollbar-thumb{
            background: #cbd5e1;
            border-radius: 20px;
        }

        /* =========================================
            MOBILE
        ========================================= */

        @media(max-width:768px){

            .container-fluid{
                padding: 15px;
            }

            .page-title{
                font-size: 20px;
            }

        }

    </style>

</head>

<body id="page-top">

<div id="wrapper">

    <!-- =========================================
        SIDEBAR
    ========================================= -->

    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- LOGO -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center"
           href="<?= base_url('/dashboard') ?>">

            <div class="sidebar-brand-icon">
                <i class="fas fa-cocktail"></i>
            </div>

            <div class="sidebar-brand-text">
                Sirvalo Pues
            </div>

        </a>

        <hr class="sidebar-divider my-0">

        <!-- DASHBOARD -->
        <?php if (session()->get('role') == 'admin'): ?>

        <li class="nav-item <?= (current_url() == base_url('/dashboard')) ? 'active' : '' ?>">

            <a class="nav-link" href="<?= base_url('/dashboard') ?>">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

        </li>

        <?php endif; ?>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            GESTIÓN
        </div>

        <!-- PRODUCTOS -->
        <li class="nav-item <?= (strpos(current_url(), '/productos') !== false) ? 'active' : '' ?>">

            <a class="nav-link" href="<?= base_url('/productos') ?>">
                <i class="fas fa-box-open"></i>
                <span>Productos</span>
            </a>

        </li>

        <!-- COMPRAS -->
        <?php if (session()->get('role') == 'admin'): ?>

        <li class="nav-item <?= (strpos(current_url(), '/compras') !== false) ? 'active' : '' ?>">

            <a class="nav-link" href="<?= base_url('/compras') ?>">
                <i class="fas fa-shopping-basket"></i>
                <span>Compras</span>
            </a>

        </li>

        <?php endif; ?>

        <!-- VENTAS -->
        <li class="nav-item <?= (strpos(current_url(), '/ventas') !== false) ? 'active' : '' ?>">

            <a class="nav-link" href="<?= base_url('/ventas') ?>">
                <i class="fas fa-cash-register"></i>
                <span>Ventas</span>
            </a>

        </li>

        <!-- GASTOS -->
        <?php if (session()->get('role') == 'admin'): ?>

        <li class="nav-item <?= (strpos(current_url(), '/gastos') !== false) ? 'active' : '' ?>">

            <a class="nav-link" href="<?= base_url('/gastos') ?>">
                <i class="fas fa-wallet"></i>
                <span>Gastos</span>
            </a>

        </li>

        <?php endif; ?>

        <!-- MESAS -->
        <li class="nav-item <?= (strpos(current_url(), '/mesas') !== false) ? 'active' : '' ?>">

            <a class="nav-link" href="<?= base_url('/ventas/mesas') ?>">
                <i class="fas fa-chair"></i>
                <span>Mesas</span>
            </a>

        </li>

        <hr class="sidebar-divider d-none d-md-block">

        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>

    <!-- =========================================
        CONTENT
    ========================================= -->

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">

            <!-- TOPBAR -->

            <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top">

                <button id="sidebarToggleTop"
                        class="btn btn-link d-md-none rounded-circle mr-3">

                    <i class="fa fa-bars"></i>

                </button>

                <!-- TITLE -->

                <h1 class="page-title">
                    <?= $title ?? 'Dashboard' ?>
                </h1>

                <ul class="navbar-nav ml-auto">

                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- USER -->

                    <li class="nav-item dropdown no-arrow">

                        <a class="nav-link dropdown-toggle"
                           href="#"
                           id="userDropdown"
                           role="button"
                           data-toggle="dropdown">

                            <span class="mr-3 d-none d-lg-inline text-gray-700 small font-weight-bold">

                                <?= session()->get('username') ?>

                            </span>

                            <div class="user-avatar">
                                <?= strtoupper(substr(session()->get('username'),0,1)) ?>
                            </div>

                        </a>

                        <div class="dropdown-menu dropdown-menu-right animated--grow-in">

                            <a class="dropdown-item"
                               href="#"
                               data-toggle="modal"
                               data-target="#logoutModal">

                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Cerrar sesión

                            </a>

                        </div>

                    </li>

                </ul>

            </nav>

            <!-- CONTENT -->

            <div class="container-fluid">

                <!-- ALERT SUCCESS -->

                <?php if(session()->getFlashdata('success')): ?>

                    <div class="alert alert-success alert-dismissible fade show" role="alert">

                        <?= session()->getFlashdata('success') ?>

                        <button type="button"
                                class="close"
                                data-dismiss="alert">

                            <span>&times;</span>

                        </button>

                    </div>

                <?php endif; ?>

                <!-- ALERT ERROR -->

                <?php if(session()->getFlashdata('error')): ?>

                    <div class="alert alert-danger alert-dismissible fade show" role="alert">

                        <?= session()->getFlashdata('error') ?>

                        <button type="button"
                                class="close"
                                data-dismiss="alert">

                            <span>&times;</span>

                        </button>

                    </div>

                <?php endif; ?>

                <!-- PAGE CONTENT -->

                <?= $this->renderSection('content') ?>

            </div>

        </div>

        <!-- FOOTER -->

        <footer class="sticky-footer">

            <div class="container my-auto">

                <div class="copyright text-center my-auto">

                    <span>
                        © <?= date('Y') ?> Sirvalo Pues - Sistema POS
                    </span>

                </div>

            </div>

        </footer>

    </div>

</div>

<!-- SCROLL TOP -->

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- =========================================
    LOGOUT MODAL
========================================= -->

<div class="modal fade"
     id="logoutModal"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0">

                <h5 class="modal-title font-weight-bold">
                    ¿Cerrar sesión?
                </h5>

                <button class="close"
                        type="button"
                        data-dismiss="modal">

                    <span>×</span>

                </button>

            </div>

            <div class="modal-body">

                ¿Deseas finalizar tu sesión actual?

            </div>

            <div class="modal-footer border-0">

                <button class="btn btn-secondary"
                        type="button"
                        data-dismiss="modal">

                    Cancelar

                </button>

                <a class="btn btn-primary"
                   href="<?= base_url('/logout') ?>">

                    Cerrar sesión

                </a>

            </div>

        </div>

    </div>

</div>

<!-- =========================================
    SCRIPTS
========================================= -->

<script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>

<script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<script src="<?= base_url('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

<script src="<?= base_url('js/sb-admin-2.min.js') ?>"></script>

<script src="<?= base_url('vendor/chart.js/Chart.min.js') ?>"></script>

<!-- DATATABLE -->

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- EXTRA SCRIPTS -->

<?= $this->renderSection('scripts') ?>

</body>
</html>