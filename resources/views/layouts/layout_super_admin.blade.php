<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin_page.css') }}">
    <title>ระบบจองเข้าชมพิพิธภัณฑ์ | สำหรับผู้ดูแลระบบ</title>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside id="sidebar">
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="#">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="img-fluid">
                    </a>
                </div>

                <!-- Sidebar Navigation -->
                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        <h3 class="text-center">ยินดีต้อนรับ {{ Auth::user()->name }}</h3>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ url('/super_admin/dashboard') }}" class="sidebar-link">
                            <i class="fa-solid fa-list pe-2"></i>
                            แดชบอร์ด
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse"
                            data-bs-target="#dashboard" aria-expanded="false" aria-controls="dashboard">
                            <i class="fa-solid fa-sliders pe-2"></i>
                            จัดการบัญชีผู้ใช้งาน
                        </a>
                        <ul id="dashboard" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="{{ url('/all_users') }}" class="sidebar-link">รายชื่อบัญชีผู้ใช้งาน</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">ประวัติการใช้งาน</a>
                            </li>
                        </ul>
                    </li>
                    <li class="border-top my-3" style="border-top: 1px solid gray !important;"></li>
                    <div class="sidebar-footer">
                        <a href="{{ route('logout') }}" class="sidebar-link"
                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-right-from-bracket pe-2"></i>
                            <span>ออกจากระบบ</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </ul>
            </div>
        </aside>
        <!-- Main Component -->
        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
                <!-- Button for sidebar toggle -->
                <button class="btn" type="button" data-bs-theme="dark">
                    <span class="navbar-toggler-icon "></span>
                </button>
            </nav>
            <main class="content px-3 py-2">
                <div class="container-fluid">
                    <div class="mb-3">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/layout_admin.js') }}"></script>
</body>

</html>