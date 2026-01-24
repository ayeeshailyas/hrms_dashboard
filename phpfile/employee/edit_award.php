<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $award_id = $_GET['id'];
    $sql = "SELECT a.*, e.designation, e.f_name, e.l_name 
        FROM employee_awards a 
        JOIN employee e ON a.employee_id = e.id 
        WHERE a.id = $award_id";

    $result = $conn->query($sql);
    $award = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $award_id = $_POST['id'];
    $employee_id = $_POST['employee_id'];
    $award_name = $_POST['award_name'];
    $gift_item = $_POST['gift_item'];
    $award_amount = $_POST['award_amount'];
    $award_month = $_POST['award_month'];

    $stmt = $conn->prepare("UPDATE employee_awards SET employee_id=?, award_name=?, gift_item=?, award_amount=?, award_month=? WHERE id=?");
    $stmt->bind_param("issdsi", $employee_id, $award_name, $gift_item, $award_amount, $award_month, $award_id);
    $stmt->execute();
    header("Location: employee_award.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--    <link rel="stylesheet" href="/css/bootstrap.css">-->
    <link rel="stylesheet" href="/fontawesome/font-awesome-4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap-select CSS -->
    <!-- Bootstrap-select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>employee_award</title>
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
        visibility 0s ,
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
        overflow-x:hidden ;
    }

    /* Prevent overlay from covering sidebar */


    @media (max-width: 1024px) {
        .ml-64 {
            margin-left: 0;
        }
    }

    .self-start {     align-self: flex-start; }


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
        background-color:#01a9ac;

    color: white  !important;
        border-color: #01a9ac !important;
        border-radius: 4px;
        font-size: 15px;
        height: 50px;
        outline: none;
    }
    .p1{
        background-color:#01a9ac;

    color: white  !important;
        border-color: #01a9ac !important;
        border-radius: 4px;
        font-size: 15px;
        height: 50px;
        outline: none;
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

    /* ✅ Selected item in menu (light grey bg) */
    .bootstrap-select .dropdown-menu li.selected a {
        background-color: #dee2e6 !important; /* Light grey */
        color: black !important;
    }
    .bootstrap-select .dropdown-menu li.selected a:hover{
        background-color: #01a9ac !important; /* Zinc-100 */
        color: white !important;
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
    /*/calender calender calendar/*/
    .hover-zone { position: relative; }
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
    .hover-zone:hover .zoom-text { transform: scale(1.37); }
    .slide-text { display: inline-block; position: relative; }
    .slide-from-left {
        animation: slideFromLeft 0.25s ease-out forwards;
    }
    .slide-from-right {
        animation: slideFromRight 0.25s ease-out forwards;
    }
    @keyframes slideFromLeft {
        from { opacity: 0; transform: translateX(-80px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideFromRight {
        from { opacity: 0; transform: translateX(80px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .zoom-in {
        animation: zoomIn 0.3s ease forwards;
    }
    .zoom-out {
        animation: zoomOut 0.3s ease forwards;
    }
    @keyframes zoomIn {
        from { transform: scale(0.6); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    @keyframes zoomOut {
        from { transform: scale(1); opacity: 1; }
        to { transform: scale(0.6); opacity: 0; }
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
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
    .b1{
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

    overflow-y: hidden;
        padding-right: 17px; /* Yeh scroll bar ki jagah banayega */
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
                    <img src="../../images/img_1.png" alt="user" class="w-8 h-8 rounded-[5px] object-cover">
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
                <img src="../../images/img.png" class="absolute top-4" alt="">
            </div>
        </div>

        <!-- Center (image on md/sm) -->
        <div class="col-span-4 flex justify-center items-center">
            <img src="../../images/img.png" class="block lg:hidden " alt="">
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
<div class="mobile-headermdsm d-lg-none block lg:hidden z-[60] fixed left-0 right-0 top-[60px]  bg-white shadow-md   max-h-0 overflow-hidden " id="mobileHeadermdsm">
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
                <img src="../../images/img_1.png" alt="user" class="w-8 h-8 rounded-[5px] object-cover">
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

                <li class="pb-2 lg:pl-7 md:pl-7 sm:pl-6 pl-6"><a href="http://localhost/project/hrms_dashboard/phpfile/settings/settings(4).php"
                                                                 class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Leave Category</a></li>

                <li class="pb-2 lg:pl-7 md:pl-7 sm:pl-6 pl-6"><a href="http://localhost/project/hrms_dashboard/phpfile/settings/settings(5)/index.php"
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

                <li class="pb-2 lg:pl-6 md:pl-6 sm:pl-6 pl-6 pt-3"><a href="http://localhost/project/hrms_dashboard/phpfile/department/add_depart.php"
                                                                      class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Add Department</a></li>

                <li class="pb-2 lg:pl-6 md:pl-6 sm:pl-6 pl-6"><a href="http://localhost/project/hrms_dashboard/phpfile/department/department_list.php"
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


                <li class="pb-2 pt-3"><a href="http://localhost/project/hrms_dashboard/phpfile/employee/add_employee.php"
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

                <li class="pb-2 pl-5 pt-3"><a href="http://localhost/project/hrms_dashboard/phpfile/payrolldemo/manage_salary.php"
                                              class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Manage Salary Details</a></li>
                <li class="pb-2 pl-5"><a href="http://localhost/project/hrms_dashboard/phpfile/payrolldemo/employee_salary_list.php"
                                         class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2"><i
                                class="fa fa-angle-right font-bold pr-2"></i>Employee Salary List</a></li>
                <li class="pb-2 pl-5"><a href="http://localhost/project/hrms_dashboard/phpfile/payrolldemo/payment_demo/make_payment.php"
                                         class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2"><i
                                class="fa fa-angle-right font-bold pr-2"></i>Make Payment</a></li>
                <li class="pb-2 pl-5"><a href="http://localhost/project/hrms_dashboard/phpfile/payrolldemo/generate_slip/generate_payslip.php"
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


                <li class="pb-2 pl-7 pt-3"><a href="http://localhost/project/hrms_dashboard/phpfile/expense/add_expense.php"
                                              class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                class="fa fa-angle-right font-bold pr-2"></i>Add Expense</a></li>
                <li class="pb-2 pl-7"><a href="http://localhost/project/hrms_dashboard/phpfile/expense/expense_report.php"
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

                <li class="pb-2 pl-7 pt-3"><a href="http://localhost/project/hrms_dashboard/phpfile/notice/add_notice.php"
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
    <!--    <h1 class="font-semibold text-[24px]">Holidays</h1>-->
    <!--    <span>-->
    <!--    <p class="text-[#919aa3] text-[14px] pt-2 float-left">Holiday List</p>-->
    <p class="float-right text-[#919aa3] text-[15px]"><a href="http://localhost/project/hrms_dashboard/phpfile/dashboard.php"><i class="fa fa-home text-[#4a6076]"></i></a> / <a
            href="http://localhost/project/hrms_dashboard/phpfile/settings(1).php">Widget</a></p>
    </span>

    <div class="max-w-7xl mx-auto ">
        <h1 class="text-lg font-semibold mb-11">Employee Award</h1>
        <div class="bg-white rounded-lg   border-t-4 border-[#01a9ac] shadow-[0_1px_20px_0_rgba(69,90,100,0.08)] p-6">
            <h2 class="text-gray-600 font-semibold mb-4">Add New Award to Employee</h2>
            <form method="POST" action="edit_award.php" class="max-w-3xl    p-6 rounded-lg  space-y-6">
                <input type="hidden" name="id" value="<?= $award['id'] ?>">

                <?php
                $conn = new mysqli("localhost", "root", "", "hrms_dashboard");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT department_name, designation_name FROM department_designation ORDER BY department_name";
                $result = $conn->query($sql);

                $grouped = [];
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $grouped[$row['department_name']][] = $row['designation_name'];
                    }
                }
                ?>

                <!-- Designation -->
                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                    <label class="text-gray-700 font-medium col-span-1">Select Designation <span class="text-red-500">*</span></label>
                    <select name="designation" id="designationDropdown" class="col-span-3 selectpicker form-control w-full px-6 py-1 rounded-md focus:outline-none  ">
                        <option disabled>Select Designation...</option>
                        <?php foreach ($grouped as $department => $designations): ?>
                            <option disabled class='bg-gray-100 font-bold text-black'><?= $department ?></option>
                            <?php foreach ($designations as $designation): ?>
                                <option value="<?= $designation ?>" <?= $award['designation'] == $designation ? 'selected' : '' ?>>
                                    &nbsp;&nbsp;&nbsp;<?= $designation ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Employee -->
                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                    <label class="text-gray-700 font-medium col-span-1">Employee <span class="text-red-500">*</span></label>
                    <select name="employee_id" id="employeeDropdown" class="col-span-3 w-full px-3 py-2.5 bg-[#01a9ac] text-white rounded-[4px] focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <!-- Initial selected employee -->
                        <option value="<?= $award['employee_id'] ?>" selected><?= $award['f_name'] . ' ' . $award['l_name'] ?></option>
                    </select>
                </div>

                <!-- Award Name -->
                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                    <label class="text-gray-700 font-medium col-span-1">Award Name / Title <span class="text-red-500">*</span></label>
                    <input type="text" name="award_name" value="<?= $award['award_name'] ?>" required class="col-span-3 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Gift Item -->
                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                    <label class="text-gray-700 font-medium col-span-1">Gift Item</label>
                    <input type="text" name="gift_item" required value="<?= $award['gift_item'] ?>" class="col-span-3 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Award Amount -->
                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4">
                    <label class="text-gray-700 font-medium col-span-1">Award Amount</label>
                    <input type="number" name="award_amount" required value="<?= $award['award_amount'] ?>" class="col-span-3 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>

                <!-- Award Month -->
                <div class="grid grid-cols-1 md:grid-cols-4 items-center gap-4 relative">
                    <label class="text-gray-700 font-medium col-span-1">Select Month <span class="text-red-500">*</span></label>
                    <div class="col-span-3 flex items-center w-full relative">
                        <input id="dobInput" name="award_month" value="<?= $award['award_month'] ?>" placeholder="Enter Month" readonly class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <div class="absolute right-2 text-teal-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <!-- Calendar UI (unchanged structure as per your logic) -->
                    <div id="calendar" class="hidden absolute top-full mt-2 left-[25%] w-[200px] h-[215px] bg-white rounded-xl shadow-xl z-50 pt-2 text-center flex flex-col">
                        <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 w-4 h-4 bg-white rotate-45"></div>

                        <div id="mainView">
                            <div class="border-b hover-zone py-1" onclick="toggleGrid('month')">
                                <div class="hover-icon cursor-pointer left-2 text-teal-500 text-xl font-bold" onclick="event.stopPropagation(); changeMonth(-1)">&#10094;</div>
                                <div id="monthScroll" class="text-[18px] font-bold text-black zoom-text cursor-pointer slide-text">Jul</div>
                                <div class="hover-icon cursor-pointer right-2 text-teal-500 text-xl font-bold" onclick="event.stopPropagation(); changeMonth(1)">&#10095;</div>
                            </div>
                            <div class="border-b hover-zone py-1" onclick="toggleGrid('date')">
                                <div class="hover-icon cursor-pointer left-2 text-teal-500 text-xl font-bold" onclick="event.stopPropagation(); changeDate(-1)">&#10094;</div>
                                <div id="dateScroll" class="text-4xl font-bold text-teal-500 zoom-text cursor-pointer slide-text">11</div>
                                <div id="dayName" class="text-xs font-bold mt-0.5">Friday</div>
                                <div class="hover-icon cursor-pointer right-2 text-teal-500 text-xl font-bold" onclick="event.stopPropagation(); changeDate(1)">&#10095;</div>
                            </div>
                            <div class="hover-zone py-1" onclick="toggleGrid('year')">
                                <div class="hover-icon cursor-pointer left-2 text-teal-500 text-xl font-bold" onclick="event.stopPropagation(); changeYear(-1)">&#10094;</div>
                                <div id="yearScroll" class="text-base text-gray-700 mt-3 zoom-text font-bold cursor-pointer slide-text">2025</div>
                                <div class="hover-icon cursor-pointer right-2 text-teal-500 text-xl font-bold" onclick="event.stopPropagation(); changeYear(1)">&#10095;</div>
                            </div>
                        </div>

                        <div id="gridView" class="hidden px-3 pb-3 max-h-[215px] no-scrollbar"></div>
                        <div id="actionBtn" class="bg-teal-500 w-full mt-auto rounded-b-lg py-2 text-white text-2xl font-bold cursor-pointer">✓</div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="bg-[#01a9ac] hover:bg-[#0dcaf0] text-white  transition-all ease-in duration-300 rounded-[2px] px-6 py-2 flex items-center ml-48 text-[17px] ">Update</button>
                </div>
            </form>
        </div>
    </div>



</div>

<!--js-->
<!--<script>-->
<!--    document.getElementById('designationDropdown').addEventListener('change', function () {-->
<!--        var designation = this.value;-->
<!--        fetch('get_employees.php?designation=' + designation)-->
<!--            .then(response => response.json())-->
<!--            .then(data => {-->
<!--                let dropdown = document.getElementById('employeeDropdown');-->
<!--                dropdown.innerHTML = '<option selected>Select Employee...</option>';-->
<!--                data.forEach(emp => {-->
<!--                    dropdown.innerHTML += <option value="${emp.id}">${emp.first_name} ${emp.last_name}</option>;-->
<!--                });-->
<!--            });-->
<!--    });-->
<!--</script>-->


<script>
    window.addEventListener('DOMContentLoaded', function () {
        // Trigger change on designation dropdown to populate employee list
        const designation = document.getElementById('designationDropdown').value;
        const selectedEmployeeId = "<?= $award['employee_id'] ?>";
        if (designation) {
            fetch('get_employees.php?designation=' + designation)
                .then(response => response.json())
                .then(data => {
                    let dropdown = document.getElementById('employeeDropdown');
                    dropdown.innerHTML = '<option>Select Employee...</option>';
                    data.forEach(emp => {
                        const isSelected = emp.id == selectedEmployeeId ? 'selected' : '';
                        dropdown.innerHTML += `<option value="${emp.id}" ${isSelected}>${emp.f_name} ${emp.l_name}</option>`;
                    });
                });
        }
    });
</script>


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
<script>
    const dobInput = document.getElementById("dobInput");
    const calendar = document.getElementById("calendar");
    const monthScroll = document.getElementById("monthScroll");
    const dateScroll = document.getElementById("dateScroll");
    const yearScroll = document.getElementById("yearScroll");
    const dayName = document.getElementById("dayName");
    const actionBtn = document.getElementById("actionBtn");
    const mainView = document.getElementById("mainView");
    const gridView = document.getElementById("gridView");

    let currentDate = new Date();
    let tempDate = new Date(currentDate);
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    let isAnimating = false;

    function updateCalendar() {
        monthScroll.innerText = months[tempDate.getMonth()];
        dateScroll.innerText = String(tempDate.getDate()).padStart(2, "0");
        yearScroll.innerText = tempDate.getFullYear();

        const weekday = tempDate.getDay();
        const weekdayStr = tempDate.toLocaleDateString("en-US", { weekday: "long" });
        dayName.innerText = weekdayStr;

        const isWeekend = weekday === 0 || weekday === 6;
        dayName.className = `text-xs font-bold mt-0.5 ${isWeekend ? "text-teal-500" : "text-gray-900"}`;
        dateScroll.className = `text-4xl font-bold zoom-text cursor-pointer slide-text ${isWeekend ? "text-teal-500" : "text-gray-900"}`;
    }

    function animateChange(el, newText, direction) {
        if (isAnimating) return;
        isAnimating = true;
        const animClass = direction === "right" ? "slide-from-right" : "slide-from-left";
        el.classList.remove("slide-from-left", "slide-from-right");
        void el.offsetWidth;
        el.classList.add(animClass);
        el.innerText = newText;
        setTimeout(() => { isAnimating = false; }, 250);
    }

    function changeMonth(offset) {
        const direction = offset > 0 ? "right" : "left";
        tempDate.setMonth(tempDate.getMonth() + offset);
        animateChange(monthScroll, months[tempDate.getMonth()], direction);
        updateCalendar();
    }

    function changeDate(offset) {
        const direction = offset > 0 ? "right" : "left";
        tempDate.setDate(tempDate.getDate() + offset);
        animateChange(dateScroll, String(tempDate.getDate()).padStart(2, "0"), direction);
        updateCalendar();
    }

    function changeYear(offset) {
        const direction = offset > 0 ? "right" : "left";
        tempDate.setFullYear(tempDate.getFullYear() + offset);
        animateChange(yearScroll, tempDate.getFullYear(), direction);
        updateCalendar();
    }

    dobInput.onclick = () => {
        calendar.classList.toggle("hidden");
        tempDate = new Date(currentDate);
        updateCalendar();
    };

    actionBtn.onclick = () => {
        if (gridView.classList.contains("hidden")) {
            currentDate = new Date(tempDate);
            dobInput.value = `${months[tempDate.getMonth()]} ${String(tempDate.getDate()).padStart(2, "0")}, ${tempDate.getFullYear()}`;
            calendar.classList.add("hidden");
        } else {
            closeGrid();
        }
    };

    function toggleGrid(type) {
        if (!gridView.classList.contains("hidden")) return;
        mainView.classList.add("hidden");
        gridView.innerHTML = "";
        gridView.classList.remove("hidden", "zoom-out");
        gridView.classList.add("zoom-in", "grid-3rows");
        actionBtn.innerText = "✕";

        let html = "";

        if (type === "month") {
            html += "<div class='grid grid-cols-2 gap-1'>";
            months.forEach((m, i) => {
                html += `<div onclick='selectGrid("month", ${i})' class='pt-1 border-b rounded cursor-pointer font-semibold text-sm text-black flex flex-col items-center'>${m}<span class="text-xs text-black">${String(i + 1).padStart(2, "0")}</span></div>`;
            });
            html += "</div>";
        } else if (type === "date") {
            const daysInMonth = new Date(tempDate.getFullYear(), tempDate.getMonth() + 1, 0).getDate();
            html += "<div class='grid grid-cols-2 gap-1'>";
            for (let i = 1; i <= daysInMonth; i++) {
                const dateObj = new Date(tempDate.getFullYear(), tempDate.getMonth(), i);
                const weekdayName = dateObj.toLocaleDateString("en-US", { weekday: "short" }).toUpperCase();
                const isWeekend = dateObj.getDay() === 0 || dateObj.getDay() === 6;
                const dayTextColor = isWeekend ? "text-teal-500" : "text-gray-900";

                html += `<div onclick='selectGrid("date", ${i})' class='border-b rounded cursor-pointer font-bold flex flex-col items-center text-sm ${dayTextColor}'>
            ${String(i).padStart(2, "0")}
            <span class="text-[10px] text-black">${weekdayName}</span>
          </div>`;
            }
            html += "</div>";
        } else if (type === "year") {
            html += "<div class='grid grid-cols-2 gap-1'>";
            for (let i = 1970; i <= 2020; i += 10) {
                html += `<div onclick='loadDecade(${i})' class='border-b rounded p-2 cursor-pointer font-semibold text-base text-gray-900 text-center'>${i}</div>`;
            }
            html += "</div>";
        }

        gridView.innerHTML = html;
    }

    function loadDecade(startYear) {
        let html = "<div class='grid grid-cols-2 gap-1'>";
        for (let i = startYear; i < startYear + 10; i++) {
            html += `<div onclick='selectGrid("year", ${i})' class='border-b p-2 rounded cursor-pointer font-semibold text-base text-gray-900 text-center'>${i}</div>`;
        }
        html += "</div>";
        gridView.innerHTML = html;
    }

    function selectGrid(type, value) {
        if (type === "month") tempDate.setMonth(value);
        if (type === "date") tempDate.setDate(value);
        if (type === "year") tempDate.setFullYear(value);
        updateCalendar();
        closeGrid();
    }

    function closeGrid() {
        gridView.classList.remove("zoom-in", "grid-3rows");
        gridView.classList.add("zoom-out");
        setTimeout(() => {
            gridView.classList.add("hidden");
            mainView.classList.remove("hidden");
            actionBtn.innerText = "✓";
        }, 250);
    }
</script>


<!--dropdown-->
<!-- Initialize Bootstrap Select -->
<script>
    $(function () {
        $('.selectpicker').selectpicker();
    });
</script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap-select JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
</body>
</html>