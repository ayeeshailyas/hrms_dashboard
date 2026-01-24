<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid request.");
}

// Fetch holiday to edit
$result = $conn->query("SELECT * FROM holidays WHERE id = $id");
$holiday = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $query = "UPDATE holidays SET 
                event_name = '$event_name', 
                description = '$description', 
                start_date = '$start_date', 
                end_date = '$end_date' 
              WHERE id = $id";
    $conn->query($query);

    $redirectMonth = date('F', strtotime($start_date));
    header("Location: index.php?month=$redirectMonth&status=updated");

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../css/bootstrap.css">
    <link rel="stylesheet" href="../../../fontawesome/font-awesome-4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap-select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>HRMS</title>
</head>
<style>
    html, body {
        overflow-x: hidden;

    }

    .transition-max-height {

        overflow: hidden;
    }

    .a1 {
        background: linear-gradient(to right, #fe5d70, #fe909d);
    }

    .toggle-container {
        width: 26px;
        height: 16px;
        background-color: #444;
        border-radius: 9999px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        border: 2px solid white;
    }


    .toggle-circle {
        width: 10px;
        height: 10px;
        background-color: white;
        border-radius: 50%;
        position: absolute;
        top: 1px;
        left: 2px;
        transition: transform 0.3s ease;
    }

    .toggle-on .toggle-circle {
        transform: translateX(8px);
    }

    .toggle-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 0px;
        pointer-events: none;

    }

    .rotate-down {
        transform: rotate(90deg);
        transition: transform 0.3s ease;
    }

    .rotate-up {
        transform: rotate(0deg);
        transition: transform 0.3s ease;
    }


    /* Sidebar item left border when active */
    .sidebar-item.active {
        border-left: 3px solid #FE8A7D;
        padding-top: 4px;
        padding-bottom: 4px;
    }

    /* Entire dropdown container (ul) with vertical border */
    #sidebar ul[id^="dropdown"] {
        position: relative;
    }

    /* Long left border for dropdown as one element */
    #sidebar ul[id^="dropdown"]::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background-color: #FE8A7D;
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    /* When dropdown is opened */
    #sidebar ul[id^="dropdown"].open::before {
        opacity: 1;
    }

    /* Individual dropdown item */
    #sidebar .dropdown-item {
        display: block;
        padding-left: 0.75rem; /* pl-3 equivalent */
        position: relative;
        z-index: 1; /* Above the border */
    }

    .mobile-headermdsm {
        background-color: white;
        color: black;
        overflow: hidden;
        max-height: 0;
        transition: max-height 1s ease-in-out;
        /* Hide content during transition */

    }

    .mobile-headermdsm.open {
        max-height: 300px;

        transition: max-height 1s ease-in-out,
        visibility 0s,
        opacity 0.2s ease-in-out;
    }

    .header-content {
        padding: 10px;

    }

    .mobile-headermdsm.open .header-content {
        transform: translateY(0);
    }

    .toggle-header-btn {
        color: #fff;
        border: none;
        font-size: 25px;
    }


    /* Overlay Styling */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Adjust opacity as needed */
        z-index: 10; /* Ensure overlay is above content but below navbar */
        display: none;
    }

    /* Ensuring navbar is on top */
    #navbar {
        position: fixed;
        width: 100%;
        top: 0;
        left: 0;
        z-index: 1000; /* Ensure navbar is always on top */
    }

    /* Adjust content area to make room for fixed navbar */
    #mainContent {
        margin-top: 60px; /* Adjust this based on your navbar height */
        overflow-x: hidden;
    }

    /* Prevent overlay from covering sidebar */


    @media (max-width: 1024px) {
        .ml-64 {
            margin-left: 0;
        }
    }

    .self-start {
        align-self: flex-start;
    }


    /* Hide scrollbar but keep scroll functionality */
    .scrollbar-hidden {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE/Edge */
    }

    .scrollbar-hidden::-webkit-scrollbar {
        display: none; /* Chrome, Safari */
    }

    .active-dropdown-item {
        @apply text-[#FE8A7D] font-semibold;
    }

    /* 🔹 Input focus style */
    input:focus, textarea:focus {
        outline: none !important;
        box-shadow: none !important;
        border-color: #01a9ac !important;
    }


    /* 🔸 Dropdown button (closed state) */
    .bootstrap-select .dropdown-toggle {
        background-color: #01a9ac !important;
        color: white !important;
        border: 1px solid #aaa;
        border-radius: 0px;
    }

    #employeeDropdown {
        background-color: #01a9ac !important;
        color: white !important;
        border: 1px solid #aaa;
        border-radius: 0px;
    }

    #employeeDropdown option {
        background-color: white;
        color: black;
    }

    #employeeDropdown:hover {
        background-color: #01a9ac !important;
        color: white;
    }

    .mnu:hover {
        background-color: #01a9ac !important;
        color: white;
    }

    #employeeDropdown option:checked {
        background-color: darkgrey;
        color: white;
    }

    /* 🔸 Remove focus glow */
    .bootstrap-select .dropdown-toggle:focus {
        box-shadow: none !important;
    }


    /* 🔸 Dropdown menu (opened) */
    .bootstrap-select .dropdown-menu {
        background-color: white !important;
        overflow-y: visible;
        max-height: 150px;
        border-radius: 0px;
    }

    /* ✅ Hovered item (Zinc-like gray) */
    .bootstrap-select .dropdown-menu li a:hover {
        background-color: #01a9ac !important; /* Zinc-100 */
        color: white !important;
    }

    /* 🔹 Remove black focus ring for both */
    .bootstrap-select .dropdown-toggle:focus,
    .bootstrap-select .dropdown-toggle:active,
    .bootstrap-select .dropdown-toggle:focus-visible,
    #employeeDropdown:focus {
        box-shadow: none !important;
        outline: none !important;
    }

    /* ✅ Selected item in menu (light grey bg) */
    .bootstrap-select .dropdown-menu li.selected a {
        background-color: #cecece !important; /* Light grey */
        color: black !important;
    }

    /* Keep icons (arrows etc.) white */
    .bootstrap-select .dropdown-toggle::after {
        color: white !important;
    }

    /* 🔹 Change background color of first search match */
    .bootstrap-select .dropdown-menu li.active:not(.selected) a {
        background-color: #01a9ac !important;
        color: white !important;
    }


    /*calender calender calendar*/

    .hover-zone {
        position: relative;
    }

    .hover-icon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%) scale(0.8);
        opacity: 0;
        transition: all 0.2s ease;
        z-index: 10;
    }

    .hover-zone:hover .hover-icon {
        opacity: 1;
        transform: translateY(-50%) scale(1.2);
    }

    .zoom-text {
        transition: transform 0.2s ease;
        display: inline-block;
    }

    .hover-zone:hover .zoom-text {
        transform: scale(1.37);
    }

    .slide-text {
        display: inline-block;
        position: relative;
    }

    .slide-from-left {
        animation: slideFromLeft 0.25s ease-out forwards;
    }

    .slide-from-right {
        animation: slideFromRight 0.25s ease-out forwards;
    }

    @keyframes slideFromLeft {
        from {
            opacity: 0;
            transform: translateX(-80px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideFromRight {
        from {
            opacity: 0;
            transform: translateX(80px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .zoom-in {
        animation: zoomIn 0.3s ease forwards;
    }

    .zoom-out {
        animation: zoomOut 0.3s ease forwards;
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.6);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes zoomOut {
        from {
            transform: scale(1);
            opacity: 1;
        }
        to {
            transform: scale(0.6);
            opacity: 0;
        }
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    #gridView.grid-3rows {
        flex: 1;
        overflow-y: auto;
    }

    #calendar {
        border: 1px solid #1abc9c !important;
        box-shadow: 0 0 10px 0 rgba(0, 136, 204, 0.45) !important;
        border-radius: 8px !important;
    }

    #joiningCalendar {
        border: 1px solid #1abc9c !important;
        box-shadow: 0 0 10px 0 rgba(0, 136, 204, 0.45) !important;
        border-radius: 8px !important;
    }

    .b1 {
        border-left: 1px solid #1abc9c !important;
        border-top: 1px solid #1abc9c !important;
    }

    .scrollbar-thin::-webkit-scrollbar {
        width: 4px;
    }

    .scrollbar-thin::-webkit-scrollbar-thumb {
        background-color: #4fd1c5; /* Tailwind teal-300 */
        border-radius: 5px;
    }

    .no-scroll {
        /*overflow: hidden !important;*/
        overflow-y: hidden;
        padding-right: 17px; /* Yeh scroll bar ki jagah banayega */
    }


    .notification {
        position: fixed;
        top: 20px;
        right: -500px;
        background-color: #333;
        color: white;
        padding: 15px 25px;
        border-radius: 5px;
        z-index: 10000;
        transition: right 0.5s ease-in-out;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .notification.show {
        right: 20px;
    }

</style>
<body class="bg-[#F6F7FB]">


<!--white header-->
<div id="overlay" class="overlay"></div>

<div class="w-full bg-white shadow-md fixed top-0 z-[10]">
    <header class="hidden md:flex items-center justify-between px-6  bg-whitesticky top-0  ml-64 ">
        <div class="float-left py-[12px]">
            <!-- Search & Fullscreen Wrapper -->
            <div class="flex items-center relative transition-all duration-500 ease-in-out w-[300px]"
                 id="search-wrapper">

                <!-- Trigger Icon (Search Button) -->
                <button id="trigerIcon" class="absolute left-0 text-[#404E67] transition z-20">
                    <i class="fas fa-search text-[#404E67]"></i>
                </button>

                <!-- Search Box -->
                <div id="searchBox"
                     class="transform scale-x-0 origin-left transition-all duration-500 ease-in-out flex items-center bg-[#dcdcdc] rounded-full px-2 py-1 w-[260px] h-8 overflow-hidden z-10 pl-8 mr-2">
                    <button id="closeBtn" class="text-[#404E67] text-sm -ml-3">
                        <i class="fas font-[16px] fa-times text-[#404E67]"></i>
                    </button>
                    <input type="text"
                           class="bg-transparent text-[#404E67] px-2 focus:outline-none w-full text-sm"/>
                    <span class="text-[#404E67] text-sm px-2">
            <i class="fas fa-search font-[16px] text-[#404E67]"></i>
        </span>
                </div>

                <!-- Fullscreen Toggle Button -->
                <button id="fullscreen-toggle"
                        class="p-1 text-[#404E67]
            rounded-full transition-all  duration-500 ease-in-out -ml-2 relative"
                        style="margin-left: -220px;">
                    <i class="fas font-[16px] fa-expand"></i>
                </button>
            </div>


        </div>

        <!-- Right: Notification & User -->
        <div class="flex items-center gap-9">
            <!-- Bell -->
            <div class="relative inline-block text-left">
                <!-- Bell Icon -->
                <button id="bellButton" onclick="togglebellDopdown()" class="relative focus:outline-none">
                    <i class="fa fa-bell-o text-[#404E67] text-[16px]" aria-hidden="true"></i>
                    <span class="absolute -top-2 -right-2 a1 text-white text-[10px] rounded-full px-1.5">0</span>
                </button>

                <!-- Dropdown -->
                <div id="dopdownMenu"
                     class="hidden absolute top-[70px] -right-[15px] w-[23rem] bg-[#fff] border border-gray-200 rounded-md shadow-lg z-50">
                    <!-- Triangle (nok) -->
                    <div class="absolute -top-2.5 right-6 w-5 h-5 bg-white rotate-45 border-t border-l border-gray-200 shadow-sm"></div>

                    <!-- Dropdown Header -->
                    <div class="flex justify-between  relative items-center px-3 py-2 ">
                        <span class="text-[16px] font-semibold px-2 py-2 text-gray-700">Messages</span>
                        <span class="a1 text-white text-[12px] rounded-[4px] px-2 py-[2px]">New</span>
                    </div>

                    <!-- Dropdown Body -->
                    <div class="px-3 py-7 text-[14px] mb-[1rem] font-semibold text-center  hover:bg-gray-100 hover:text-gray-800 cursor-pointer transition-colors duration-200">
                        <p>There is no message</p>
                    </div>
                </div>
            </div>
            <!-- Flag -->
            <div class="relative">
                <i class="fa fa-flag-o  text-[#404E67]" aria-hidden="true"></i>
                <span class="absolute -top-2 -right-2 a1 text-white text-xs rounded-full px-1.5">0</span>
            </div>

            <div class="relative inline-block text-left pr-5">
                <!-- Profile Button -->
                <div id="profileButton" onclick="toggleProfileDopdown()" class="flex items-center gap-2 cursor-pointer">
                    <img src="../../../images/img_1.png" alt="user" class="w-8 h-8 rounded-[5px] object-cover">
                    <span class="text-[#404E67] text-[14px] font-medium">John Doe</span>
                    <i class="fas fa-chevron-down text-[10px] text-[#404E67]"></i>
                </div>

                <!-- Dropdown Menu -->
                <div id="profileDopdown"
                     class="hidden absolute right-[15px] top-[73px]  w-[15em] bg-white  rounded-md shadow-[0_4px_12px_rgba(0,0,0,0.1)] z-50">
                    <!-- Triangle (nok) -->
                    <div class="absolute -top-2.5 right-6 w-5 h-5 bg-white rotate-45 border-t border-l border-gray-200 shadow-sm"></div>

                    <ul class="text-[15px] text-[#666] py-2">
                        <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <i class="fas fa-cog text-[#666] text-[15px] mr-3"></i>
                            Settings
                        </li>
                        <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <i class="fas fa-user text-[#666] text-[15px] mr-3"></i>
                            Profile
                        </li>
                        <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <i class="fas fa-sign-out-alt text-[#666] text-[15px] mr-3"></i>
                            Logout
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
</div>


<!--sidenavbar header-->

<div class="fixed top-0 start-0 bg-[#404E67] lg:w-64 md:w-full w-full sm:w-full shadow-md lg:p-[20px] md:p-[10px] sm:p-[10px] p-[10px]  z-40">
    <div class="grid grid-cols-12 items-center">

        <!-- Left (lg: image | md/sm: sidebar toggle) -->
        <div class="col-span-4 flex lg:justify-start justify-start items-center">
            <!-- Show sidebar toggle only on md/sm -->
            <div class="lg:hidden block">
                <div id="toggleSidebarBtn" class="toggle-container flex">
                    <div class="toggle-circle"></div>
                    <i id="toggleIcon" class="fa fa-toggle-on toggle-icon"></i>
                </div>
            </div>

            <!-- Show image only on lg -->
            <div class=" hidden lg:block ">
                <img src="../../../images/img.png" class="absolute top-4" alt="">
            </div>
        </div>

        <!-- Center (image on md/sm) -->
        <div class="col-span-4 flex justify-center items-center">
            <img src="../../../images/img.png" class="block lg:hidden " alt="">
        </div>

        <!-- Right (lg: toggle | md/sm: header button ...) -->
        <div class="col-span-4 flex justify-end items-center">
            <!-- lg: sidebar toggle -->
            <div class="hidden lg:flex">
                <div id="toggleSidebarBtn1" class="toggle-container flex">
                    <div class="toggle-circle"></div>
                    <i id="toggleIcon1" class="fa fa-toggle-on toggle-icon"></i>
                </div>
            </div>

            <!-- md/sm: header button -->
            <div class="lg:hidden flex">
                <button class="toggle-header-btn ml-4" onclick="toggleHeadermdsm()">
                    <b>...</b>
                </button>
            </div>
        </div>

    </div>
</div>


<!--headermdsm-->
<div class="mobile-headermdsm d-lg-none block lg:hidden z-[60] fixed left-0 right-0 top-[60px]  bg-white shadow-md   max-h-0 overflow-hidden "
     id="mobileHeadermdsm">
    <div class="header-content flex items-center justify-end gap-9 pr-4">

        <!-- Bell -->
        <div class="relative inline-block text-left">
            <button class="relative focus:outline-none mobile-bell-trigger">
                <i class="fa fa-bell-o text-[#404E67] text-[16px]"></i>
                <span class="absolute -top-2 -right-2 a1 text-white text-[10px] rounded-full px-1.5">0</span>
            </button>

            <div class="mobile-bell-dropdown hidden absolute z-50 top-[70px] -right-[15px] w-[20rem] bg-white border border-gray-200 rounded-md shadow-lg ">
                <div class="absolute -top-2.5 right-6 w-5 h-5 bg-white rotate-45 border-t border-l border-gray-200 shadow-sm"></div>
                <div class="flex justify-between px-3 py-2">
                    <span class="text-[16px] font-semibold px-2 py-2 text-gray-700">Messages</span>
                    <span class="a1 text-white text-[12px] rounded-[4px] px-2 py-[2px]">New</span>
                </div>
                <div class="px-3 py-7 text-[14px] font-semibold text-center hover:bg-gray-100 hover:text-gray-800 cursor-pointer transition-colors duration-200">
                    <p>There is no message</p>
                </div>
            </div>
        </div>

        <!-- Profile -->
        <div class="relative inline-block text-left">
            <div class="flex items-center gap-2 cursor-pointer mobile-profile-trigger">
                <img src="../../../images/img.png" alt="user" class="w-8 h-8 rounded-[5px] object-cover">
                <span class="text-[#404E67] text-[14px] font-medium">John Doe</span>
                <i class="fas fa-chevron-down text-[10px] text-[#404E67]"></i>
            </div>

            <div class="mobile-profile-dropdown hidden absolute right-[15px] top-[73px] w-[15em] bg-white rounded-md shadow-[0_4px_12px_rgba(0,0,0,0.1)] z-50">
                <div class="absolute -top-2.5 right-6 w-5 h-5 bg-white rotate-45 border-t border-l border-gray-200 shadow-sm"></div>
                <ul class="text-[15px] text-[#666] py-2">
                    <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        <i class="fas fa-cog text-[#666] text-[15px] mr-3"></i> Settings
                    </li>
                    <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        <i class="fas fa-user text-[#666] text-[15px] mr-3"></i> Profile
                    </li>
                    <li class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        <i class="fas fa-sign-out-alt text-[#666] text-[15px] mr-3"></i> Logout
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


<!-- Sidebar -->

<div id="sidebar" class="fixed top-14 start-0 z-30 lg:w-64 md:w-64 sm:w-56 w-56 bg-[#404E67]
            overflow-y-auto scrollbar-hidden
            h-[calc(100vh-56px)] ">
    <div class="flex flex-col space-y-2">

        <p class="text-[#999] text-md pb-3 pl-6 pt-4 font-semibold">Navigation</p>

        <!-- Dropdown Item -->
        <ul class="pt-2">
            <li class="sidebar-item text-[dcdcdc]  " onclick="activateSingleItem(this)">
                <a href="http://localhost/project/hrms_dashboard/phpfile/dashboard.php" class="text-[#dcdcdc] pl-5"><i
                            class="fa fa-dashboard "></i></a>
                <a href="http://localhost/project/hrms_dashboard/phpfile/dashboard.php"
                   class="text-[#dcdcdc] text-[15px] font-semibold pl-2 hover:text-white "> Dashboard</a>
            </li>
        </ul>

        <!--              dropdown item 2-->
        <ul class="pt-3">
            <li class="sidebar-item">
                <a href="#" class="text-[#dcdcdc] pl-5"><i class="fa fa-cogs "></i></a>
                <a href="#" class="w-full  font-semibold pl-2 text-left text-[15px] hover:text-white text-[#dcdcdc] "
                   onclick="toggleDropdown('dropdown1', this)">
                    Settings
                    <i class="fa fa-angle-right lg:ml-[122px] md:ml-[122px] sm:ml-[96px] ml-[96px]  icon font-bold text-md
                         transition-transform duration-300"></i>
                </a>
            </li>
            <ul id="dropdown1" class="transition-all max-h-0   overflow-hidden">

                <li class="pb-2 lg:pl-7 md:pl-7 sm:pl-6 pl-6 pt-3"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/settings/settings(1).php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>General Setting</a></li>

                <li class="pb-2 lg:pl-7 md:pl-7 sm:pl-6 pl-6"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/settings/settings(2).php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Set Working Days</a></li>

                <li class="pb-2 lg:pl-7 md:pl-7 sm:pl-6 pl-6"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/settings/settings(3)/index.php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Holiday List</a></li>

                <li class="pb-2 lg:pl-7 md:pl-7 sm:pl-6 pl-6"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/settings/settings(4).php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Leave Category</a></li>

                <li class="pb-2 lg:pl-7 md:pl-7 sm:pl-6 pl-6"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/settings/settings(5)/index.php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Personal Event</a></li>

            </ul>
        </ul>


        <!-- Another Dropdown Item -->
        <ul class="pt-3">
            <li class="sidebar-item">
                <a href="#" class="text-[#dcdcdc] pl-5"><i class="fa fa-list "></i></a>
                <a href="#" class="w-full  font-semibold pl-2 text-left text-[15px] hover:text-white text-[#dcdcdc] "
                   onclick="toggleDropdown('dropdown2', this)">
                    Department
                    <i class="fa fa-angle-right  lg:ml-[98px] md:ml-[98px] sm:ml-[69px] ml-[69px]  icon font-bold text-md
                         transition-transform duration-300"></i>
                </a>
            </li>
            <ul id="dropdown2" class="transition-all max-h-0   overflow-hidden ">

                <li class="pb-2 lg:pl-6 md:pl-6 sm:pl-6 pl-6 pt-3"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/department/add_depart.php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Add Department</a></li>

                <li class="pb-2 lg:pl-6 md:pl-6 sm:pl-6 pl-6"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/department/department_list.php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Department List</a></li>

            </ul>
        </ul>

        <!-- Another Dropdown Item -->
        <ul class="pt-3">
            <li class="sidebar-item">
                <a href="#" class="text-[#dcdcdc] pl-5"><i class="fa fa-credit-card "></i></a>
                <a href="#" class="w-full  font-semibold pl-2 text-left text-[15px] hover:text-white text-[#dcdcdc] "
                   onclick="toggleDropdown('dropdown3', this)">
                    Mail Box
                    <i class="fa fa-angle-right lg:ml-[122px] md:ml-[122px] sm:ml-[90px] ml-[90px]  icon font-bold text-md
                         transition-transform duration-300"></i>
                </a>
            </li>
            <ul id="dropdown3" class="transition-all max-h-0   overflow-hidden  ">

                <li class="pb-2 pl-7 pt-3"><a href="http://localhost/project/hrms_dashboard/phpfile/mailbox/mailbox.php"
                                              class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Inbox</a></li>

            </ul>
        </ul>

        <!-- Another Dropdown Item -->
        <ul class="pt-3">
            <li class="sidebar-item">
                <a href="#" class="text-[#dcdcdc] pl-5"><i class="fa fa-user "></i></a>
                <a href="#" class="w-full  font-semibold pl-3 text-left text-[15px] hover:text-white text-[#dcdcdc] "
                   onclick="toggleDropdown('dropdown4', this)">
                    Employee
                    <i class="fa fa-angle-right lg:ml-[116px] md:ml-[116px] sm:ml-[83px] ml-[83px]   icon font-bold text-md
                         transition-transform duration-300"></i>
                </a>
            </li>
            <ul id="dropdown4" class="transition-all max-h-0   overflow-hidden">


                <li class="pb-2 pt-3"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/employee/add_employee.php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pl-6 pr-2"></i>Add Employee</a></li>
                <li class="pb-2"><a href="http://localhost/project/hrms_dashboard/phpfile/employee/list_employee.php"
                                    class=" dropdown-item text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2"><i
                                class="fa fa-angle-right font-bold pl-6 pr-2"></i>Employee List</a></li>
                <li class="pb-2"><a href="http://localhost/project/hrms_dashboard/phpfile/employee/employee_award.php"
                                    class=" dropdown-item text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2"><i
                                class="fa fa-angle-right font-bold pl-6 pr-2"></i>Employee Award</a></li>

            </ul>
        </ul>

        <!-- Another Dropdown Item -->
        <ul class="pt-3">
            <li class="sidebar-item">
                <a href="#" class="text-[#dcdcdc] pl-5"><i class="fa fa-file "></i></a>
                <a href="#" class="w-full  font-semibold pl-2 text-left text-[15px] hover:text-white text-[#dcdcdc] "
                   onclick="toggleDropdown('dropdown5', this)">
                    Attendance
                    <i class="fa fa-angle-right lg:ml-[105px] md:ml-[105px] sm:ml-[74px] ml-[74px]   icon font-bold text-md
                         transition-transform duration-300"></i>
                </a>
            </li>
            <ul id="dropdown5" class="transition-all max-h-0   overflow-hidden ">

                <li class="pb-2 pl-6 pt-3"><a href="#"
                                              class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Manage Attendance</a></li>
                <li class="pb-2 pl-6"><a href="#"
                                         class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2"><i
                                class="fa fa-angle-right font-bold pr-2"></i>Attendance Report</a></li>


            </ul>
        </ul>

        <!-- Another Dropdown Item -->
        <ul class="pt-3">
            <li class="sidebar-item">
                <a href="#" class="text-[#dcdcdc] pl-5"><i class="fa fa-usd "></i></a>
                <a href="#" class="w-full  font-semibold pl-2 text-left text-[15px] hover:text-white text-[#dcdcdc] "
                   onclick="toggleDropdown('dropdown6', this)">
                    Payroll
                    <i class="fa fa-angle-right lg:ml-[142px] md:ml-[142px] sm:ml-[110px] ml-[110px]   icon font-bold text-md
                         transition-transform duration-300"></i>
                </a>
            </li>
            <ul id="dropdown6" class="transition-all max-h-0   overflow-hidden  ">

                <li class="pb-2 pl-5 pt-3"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/payrolldemo/manage_salary.php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Manage Salary Details</a></li>
                <li class="pb-2 pl-5"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/payrolldemo/employee_salary_list.php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2"><i
                                class="fa fa-angle-right font-bold pr-2"></i>Employee Salary List</a></li>
                <li class="pb-2 pl-5"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/payrolldemo/payment_demo/make_payment.php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2"><i
                                class="fa fa-angle-right font-bold pr-2"></i>Make Payment</a></li>
                <li class="pb-2 pl-5"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/payrolldemo/generate_slip/generate_payslip.php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2"><i
                                class="fa fa-angle-right font-bold pr-2"></i>Generate Payslip</a></li>

            </ul>
        </ul>

        <!-- Another Dropdown Item -->
        <ul class="pt-3">
            <li class="sidebar-item">
                <a href="#" class="text-[#dcdcdc] pl-5"><i class="fa fa-money"></i></a>
                <a href="#" class="w-full  font-semibold pl-2 text-left text-[15px] hover:text-white text-[#dcdcdc] "
                   onclick="toggleDropdown('dropdown7', this)">
                    Expence
                    <i class="fa fa-angle-right lg:ml-[123px]  md:ml-[123px]  sm:ml-[91px]  ml-[91px]   icon font-bold text-md
                         transition-transform duration-300"></i>
                </a>
            </li>
            <ul id="dropdown7" class="transition-all max-h-0   overflow-hidden  ">


                <li class="pb-2 pl-7 pt-3"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/expense/add_expense.php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Add Expense</a></li>
                <li class="pb-2 pl-7"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/expense/expense_report.php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2"><i
                                class="fa fa-angle-right font-bold pr-2"></i>Expense Report</a></li>


            </ul>
        </ul>

        <!-- Another Dropdown Item -->
        <ul class="pt-3">
            <li class="sidebar-item">
                <a href="#" class="text-[#dcdcdc] pl-5"><i class="fa fa-list-alt"></i></a>
                <a href="#" class="w-full  font-semibold pl-2 text-left text-[15px] hover:text-white text-[#dcdcdc] "
                   onclick="toggleDropdown('dropdown8', this)">
                    Notice Board
                    <i class="fa fa-angle-right lg:ml-[92px] md:ml-[92px] sm:ml-[59px] ml-[59px]  icon font-bold text-md
                         transition-transform duration-300"></i>
                </a>
            </li>
            <ul id="dropdown8" class="transition-all max-h-0   overflow-hidden ">

                <li class="pb-2 pl-7 pt-3"><a
                            href="http://localhost/project/hrms_dashboard/phpfile/notice/add_notice.php"
                            class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Add Notice</a></li>
                <li class="pb-2 pl-7"><a href="http://localhost/project/hrms_dashboard/phpfile/notice/manage_notice.php"
                                         class=" dropdown-item text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2"><i
                                class="fa fa-angle-right font-bold pr-2"></i>Manage Notice</a></li>
            </ul>
        </ul>

    </div>
</div>

<!--main content-->

<div id="mainContent" class=" p-8 absolute top-1 ">
    <h1 class="font-semibold text-[24px]">Edit Holiday</h1>
    <span>
    <p class="text-[#919aa3] text-[14px] pt-2 float-left">Edit Holiday Information</p>
        <p class="float-right text-[#919aa3] text-[15px]"><a
                    href="http://localhost/project/hrms_dashboard/phpfile/dashboard.php"><i
                        class="fa fa-home text-[#4a6076]"></i></a> / <a
                    href="http://localhost/project/hrms_dashboard/phpfile/settings(1).php">Widget</a></p>
    </span>

    <div class="py-8"></div>
    <div class="w-full bg-white rounded p-6">


        <form method="post" class="space-y-4">

            <div class="lg:flex md:flex items-center lg:space-x-4 md:space-x-4">
                <label class="w-40 text-[15px]  text-gray-700 sm:pb-2 lg:pb-0 md:pb-0 pb-2">Event Name</label>
                <input type="text" placeholder="Enter your event name " name="event_name"
                       value="<?= $holiday['event_name'] ?>" required
                       class="form-control text-gray-700 w-full rounded-none border border-gray-300 focus:border-[#01a9ac]"/>
            </div>


            <div class="lg:flex md:flex items-center lg:space-x-4 md:space-x-4">
                <label class="w-40 text-[15px]  text-gray-700 sm:pb-2 lg:pb-0 md:pb-0 pb-2">Description</label>
                <textarea name="description" required
                          class="form-control text-gray-700 border border-gray-300 focus:border-[#01a9ac] w-full rounded-none"><?= $holiday['description'] ?></textarea>
            </div>


            <div class="lg:flex md:flex items-center lg:space-x-4 md:space-x-4">
                <label class="w-40 text-[15px]  text-gray-700 sm:pb-2 lg:pb-0 md:pb-0 pb-2">Start Date</label>
                <input type="date" name="start_date" value="<?= $holiday['start_date'] ?>" placeholder="Start Date "
                       required
                       class="form-control w-full text-gray-700 rounded-none border border-gray-300 focus:border-[#01a9ac]"/>
            </div>
            <div class="lg:flex md:flex items-center lg:space-x-4 md:space-x-4">
                <label class="w-40 text-[15px]  text-gray-700 sm:pb-2 lg:pb-0 md:pb-0 pb-2">End Date</label>
                <input type="date" name="end_date" value="<?= $holiday['end_date'] ?>" placeholder="End Date " required
                       class="form-control w-full text-gray-700 rounded-none border border-gray-300 focus:border-[#01a9ac]"/>
            </div>


            <div class="text-start pt-4 lg:pl-[9.4rem] md:pl-[9.4rem] sm:pl-0 pl-0">
                <button type="submit"
                        class="bg-[#01a9ac] hover:bg-[#018f91] text-white px-6 py-2 ">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    const toggleBtn = document.getElementById('toggleSidebarBtn');     // md/sm
    const toggleBtn1 = document.getElementById('toggleSidebarBtn1');   // lg
    const toggleIcon = document.getElementById('toggleIcon');
    const toggleIcon1 = document.getElementById('toggleIcon1');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const navbar = document.getElementById('navbar'); // Assuming your navbar has the ID 'navbar'
    const overlay = document.getElementById('overlay');
    const formOverlay = document.getElementById('boxOverlay'); // Assuming this is the overlay on the form box
    const formInputs = document.querySelectorAll('input, select, textarea'); // Assuming you have form inputs

    let isOpen = false;

    // Function to adjust the width of the main content
    function adjustMainContentWidth() {
        if (isOpen && window.innerWidth >= 1024) {
            mainContent.style.marginLeft = "256px"; // Sidebar is open (large screens)
            mainContent.style.width = "calc(100% - 256px)";
        } else {
            mainContent.style.marginLeft = "0"; // Sidebar is closed
            mainContent.style.width = "100%";
        }
    }

    // Remove overlay on the box itself (form)
    function removeBoxOverlay() {
        if (formOverlay) {
            formOverlay.style.display = 'none'; // Hide overlay on the form box
        }
    }

    // Function to handle overlay visibility for content
    function toggleOverlay() {
        const screenWidth = window.innerWidth;
        if (isOpen && screenWidth < 1024) {
            overlay.style.display = 'block'; // Show overlay on md/sm screens for content
        } else {
            overlay.style.display = 'none'; // Hide overlay for content on larger screens or if sidebar is closed
        }
    }

    // Check the saved sidebar state from localStorage
    document.addEventListener("DOMContentLoaded", () => {
        const savedState = localStorage.getItem('sidebarState');
        const screenWidth = window.innerWidth;

        if (savedState === 'open') {
            isOpen = true;
            if (screenWidth >= 1024) {
                // Large screen (lg): sidebar open
                sidebar.style.maxHeight = sidebar.scrollHeight + "px";
                sidebar.classList.add('min-h-screen');
                mainContent.classList.add('ml-64');
                toggleBtn1.classList.add('toggle-on');
                toggleIcon1.classList.remove('fa-toggle-off');
                toggleIcon1.classList.add('fa-toggle-on');
            } else {
                // Medium/Small screen (md/sm): sidebar open
                sidebar.style.maxHeight = sidebar.scrollHeight + "px";
                sidebar.classList.add('min-h-screen');
                mainContent.classList.add('ml-64');
                mainContent.classList.add('dim-bg');
                toggleBtn.classList.add('toggle-on');
                toggleIcon.classList.remove('fa-toggle-off');
                toggleIcon.classList.add('fa-toggle-on');
            }
        } else {
            // Medium/Small screen (md/sm): sidebar closed
            isOpen = false;
            sidebar.style.maxHeight = "0px";
            sidebar.classList.remove('min-h-screen');
            mainContent.classList.remove('ml-64');
            mainContent.classList.remove('dim-bg');
        }

        // Adjust main content width after page load
        adjustMainContentWidth();
        removeBoxOverlay(); // Remove overlay from the form box on page load
        toggleOverlay(); // Adjust content overlay visibility
    });

    // Window resize event to track screen size changes
    window.addEventListener("resize", () => {
        const screenWidth = window.innerWidth;

        if (screenWidth < 1024 && isOpen) {
            isOpen = false;
            sidebar.style.maxHeight = "0px";
            sidebar.classList.remove('min-h-screen');
            mainContent.classList.remove('ml-64');
            mainContent.classList.remove('dim-bg');
            toggleBtn?.classList.remove('toggle-on');
            toggleIcon?.classList.remove('fa-toggle-on');
            toggleIcon?.classList.add('fa-toggle-off');
            toggleBtn1?.classList.remove('toggle-on');
            toggleIcon1?.classList.remove('fa-toggle-on');
            toggleIcon1?.classList.add('fa-toggle-off');
        } else if (screenWidth >= 1024 && !isOpen) {
            // Auto open sidebar on large screens
            isOpen = true;
            sidebar.style.maxHeight = sidebar.scrollHeight + "px";
            sidebar.classList.add('min-h-screen');
            mainContent.classList.add('ml-64');
            toggleBtn1?.classList.add('toggle-on');
            toggleIcon1?.classList.remove('fa-toggle-off');
            toggleIcon1?.classList.add('fa-toggle-on');
        }

        // Adjust main content width on resize
        adjustMainContentWidth();
        removeBoxOverlay(); // Remove overlay from the form box
        toggleOverlay(); // Adjust content overlay visibility on resize
    });

    // Toggle sidebar state for medium and small screens
    toggleBtn?.addEventListener('click', () => {
        isOpen = !isOpen;
        localStorage.setItem('sidebarState', isOpen ? 'open' : 'closed');

        if (isOpen) {
            toggleBtn.classList.add('toggle-on');
            toggleIcon.classList.remove('fa-toggle-off');
            toggleIcon.classList.add('fa-toggle-on');
            sidebar.style.maxHeight = sidebar.scrollHeight + "px";
            sidebar.classList.add('min-h-screen');
            mainContent.classList.add('ml-64');
            if (window.innerWidth < 1024) {
                mainContent.classList.add('dim-bg');
            }
        } else {
            toggleBtn.classList.remove('toggle-on');
            toggleIcon.classList.remove('fa-toggle-on');
            toggleIcon.classList.add('fa-toggle-off');
            sidebar.style.maxHeight = "0px";
            sidebar.classList.remove('min-h-screen');
            mainContent.classList.remove('ml-64');
            mainContent.classList.remove('dim-bg');
        }

        // Adjust main content width when toggling
        adjustMainContentWidth();
        removeBoxOverlay(); // Remove overlay from the form box
        toggleOverlay(); // Adjust content overlay visibility
    });

    // Toggle sidebar state for large screens
    toggleBtn1?.addEventListener('click', () => {
        isOpen = !isOpen;
        localStorage.setItem('sidebarState', isOpen ? 'open' : 'closed');

        if (isOpen) {
            toggleBtn1.classList.add('toggle-on');
            toggleIcon1.classList.remove('fa-toggle-off');
            toggleIcon1.classList.add('fa-toggle-on');
            sidebar.style.maxHeight = sidebar.scrollHeight + "px";
            sidebar.classList.add('min-h-screen');
            mainContent.classList.add('ml-64');
        } else {
            toggleBtn1.classList.remove('toggle-on');
            toggleIcon1.classList.remove('fa-toggle-on');
            toggleIcon1.classList.add('fa-toggle-off');
            sidebar.style.maxHeight = "0px";
            sidebar.classList.remove('min-h-screen');
            mainContent.classList.remove('ml-64');
        }

        // Adjust main content width when toggling
        adjustMainContentWidth();
        removeBoxOverlay(); // Remove overlay from the form box
        toggleOverlay(); // Adjust content overlay visibility
    });

    // Dropdown and active item logic...
    // ✅ Dropdown logic
    function toggleDropdown(id, button) {
        const dropdown = document.getElementById(id);
        const icon = button.querySelector('.icon');
        const parentItem = button.closest('.sidebar-item');

        const isAlreadyOpen = dropdown.classList.contains('open');

        const allDropdowns = document.querySelectorAll('[id^="dropdown"]');
        const allIcons = document.querySelectorAll('.icon');
        const allItems = document.querySelectorAll('.sidebar-item');

        allDropdowns.forEach(d => {
            d.style.maxHeight = "0px";
            d.classList.remove('open');
        });
        allIcons.forEach(i => i.classList.remove('rotate-down', 'rotate-up'));
        allItems.forEach(item => item.classList.remove('active'));

        if (isAlreadyOpen) {
            dropdown.style.maxHeight = "0px";
            icon.classList.add('rotate-up');
            return;
        }

        dropdown.style.maxHeight = dropdown.scrollHeight + "px";
        dropdown.classList.add("open");
        icon.classList.add('rotate-down');
        parentItem.classList.add('active');
    }

    // ✅ Active state logic
    function activateSingleItem(item) {
        const allItems = document.querySelectorAll('.sidebar-item');
        const allDropdowns = document.querySelectorAll('[id^="dropdown"]');
        const allIcons = document.querySelectorAll('.icon');

        allItems.forEach(i => i.classList.remove('active'));
        allDropdowns.forEach(d => d.style.maxHeight = "0px");
        allIcons.forEach(icon => icon.classList.remove('rotate-down', 'rotate-up'));

        item.classList.add('active');
    }
</script>


<!--header-->
<!-- JavaScript -->
<script>
    const trigerIcon = document.getElementById("trigerIcon");
    const searchBox = document.getElementById("searchBox");
    const closeBtn = document.getElementById("closeBtn");
    const fullscreenToggle = document.getElementById("fullscreen-toggle");
    const fullscreenIcon = fullscreenToggle.querySelector('i');

    // Search open
    trigerIcon.addEventListener("click", () => {
        searchBox.classList.remove("scale-x-0");
        searchBox.classList.add("scale-x-100");

        trigerIcon.style.opacity = "0";
        trigerIcon.style.pointerEvents = "none";

        fullscreenToggle.style.transform = "translateX(260px)";
    });

    // Search close
    closeBtn.addEventListener("click", () => {
        searchBox.classList.remove("scale-x-100");
        searchBox.classList.add("scale-x-0");

        trigerIcon.style.opacity = "1";
        trigerIcon.style.pointerEvents = "auto";

        fullscreenToggle.style.transform = "translateX(0)";
        searchBox.querySelector("input").value = '';
    });

    // Fullscreen toggle
    fullscreenToggle.addEventListener("click", function () {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            fullscreenIcon.classList.replace('fa-expand', 'fa-compress');
        } else {
            document.exitFullscreen();
            fullscreenIcon.classList.replace('fa-compress', 'fa-expand');
        }
    });

    // Escape key for fullscreen exit
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && document.fullscreenElement) {
            fullscreenIcon.classList.replace('fa-compress', 'fa-expand');
        }
    });
</script>
<!--bell dropdown-->
<script>
    function togglebellDopdown() {
        const menu = document.getElementById('dopdownMenu');
        menu.classList.toggle('hidden');
    }

    // Optional: Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const bell = document.getElementById('bellButton');
        const menu = document.getElementById('dopdownMenu');
        if (!bell.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>
<!--profile dropdown-->
<script>
    function toggleProfileDopdown() {
        const dopdown = document.getElementById('profileDopdown');
        dopdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const profile = document.getElementById('profileButton');
        const dopdown = document.getElementById('profileDopdown');
        if (!profile.contains(event.target) && !dopdown.contains(event.target)) {
            dopdown.classList.add('hidden');
        }
    });
</script>


<script>
    function toggleHeadermdsm() {
        const headerr = document.getElementById('mobileHeadermdsm');
        headerr.classList.toggle('open');
    }
</script>


<script>
    // Mobile Bell Dropdown
    const bellTrigger = document.querySelector('.mobile-bell-trigger');
    const bellDropdown = document.querySelector('.mobile-bell-dropdown');

    if (bellTrigger && bellDropdown) {
        bellTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            bellDropdown.classList.toggle('hidden');
            profileDropdown?.classList.add('hidden'); // close other
        });
    }

    // Mobile Profile Dropdown
    const profileTrigger = document.querySelector('.mobile-profile-trigger');
    const profileDropdown = document.querySelector('.mobile-profile-dropdown');

    if (profileTrigger && profileDropdown) {
        profileTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
            bellDropdown?.classList.add('hidden'); // close other
        });
    }

    // Close both on outside click
    document.addEventListener('click', () => {
        bellDropdown?.classList.add('hidden');
        profileDropdown?.classList.add('hidden');
    });
</script>

</body>
</html>