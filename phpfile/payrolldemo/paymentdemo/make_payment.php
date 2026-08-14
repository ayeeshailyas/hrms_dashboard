<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$dd = $conn->query("SELECT department_name, designation_name FROM department_designation ORDER BY department_name");
$grouped = [];
while ($r = $dd->fetch_assoc()) {
    $grouped[$r['department_name']][] = $r['designation_name'];
}
// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_id = $_POST['employee'];
    $payment_month = $_POST['award_month'];
    $enrollment_type = $_POST['enrollment_type'];
    $fine_deduction = floatval($_POST['fine_deduction']);
    $comments = $_POST['comments'];

    // Get salary details
    $salary_stmt = $conn->prepare("SELECT * FROM salary_details WHERE emp_id = ? LIMIT 1");
    $salary_stmt->bind_param("i", $emp_id);
    $salary_stmt->execute();
    $salary_query = $salary_stmt->get_result();
    $salary_data = $salary_query->fetch_assoc();

    $gross_salary = $salary_data['gross_salary'];
    $total_deduction = $salary_data['total_deduction'];
    $net_salary = $salary_data['net_salary'];

    // Calculate payment amount
    $payment_amount = $net_salary - $fine_deduction;

    // Insert payment record
    $insert_query = $conn->prepare("INSERT INTO salary_payments 
        (emp_id, payment_month, payment_date, gross_salary, total_deduction, net_salary, fine_deduction, payment_amount, enrollment_type, comments) 
        VALUES (?, ?, CURDATE(), ?, ?, ?, ?, ?, ?, ?)");
    $insert_query->bind_param("ssdddddss",
        $emp_id,
        $payment_month,
        $gross_salary,
        $total_deduction,
        $net_salary,
        $fine_deduction,
        $payment_amount,
        $enrollment_type,
        $comments);

    if ($insert_query->execute()) {
        echo "<script>alert('Payment recorded successfully!');</script>";
    } else {
        echo "<script>alert('Error recording payment: " . $conn->error . "');</script>";
    }
}

// Function to fetch payment history
function getPaymentHistory($conn, $emp_id) {
    $history = [];
    $stmt = $conn->prepare("SELECT * FROM salary_payments WHERE emp_id = ? ORDER BY payment_month DESC");
    $stmt->bind_param("i", $emp_id);
    $stmt->execute();
    $query = $stmt->get_result();
    while ($row = $query->fetch_assoc()) {
        $history[] = $row;
    }
    return $history;
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
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
    <h1 class="font-semibold text-[24px]">Holidays</h1>
    <span>
    <p class="text-[#919aa3] text-[14px] pt-2 float-left">Holiday List</p>
        <p class="float-right text-[#919aa3] text-[15px]"><a
                    href="http://localhost/project/hrms_dashboard/phpfile/dashboard.php"><i
                        class="fa fa-home text-[#4a6076]"></i></a> / <a
                    href="http://localhost/pro ject/hrms_dashboard/phpfile/settings(1).php">Widget</a></p>
    </span>

    <div class="py-4"></div>


    <form method="POST" id="paymentForm">
        <div class="w-full mx-auto bg-white shadow p-6 rounded-md border-t-4 border-teal-500">
            <h3 class="mb-4 font-semibold">Manage Salary Details</h3>
            <div class="space-y-4">
                <div class="flex flex-wrap items-center gap-2">
                    <label class="w-64 text-[15px] mt-2 text-gray-700">Designation<span
                                class="text-red-500">*</span></label>
                    <div class="w-[380px]">
                        <select name="designation_combined" id="designationDropdown" class="selectpicker form-control"
                                data-live-search="true" required>
                            <option selected>Select Designation...</option>
                            <?php foreach ($grouped as $department => $designations): ?>
                                <option disabled class="bg-gray-100 font-medium text-black"><?= $department ?></option>
                                <?php foreach ($designations as $desig): ?>
                                    <option value="<?= $department ?>||<?= $desig ?>">
                                        &nbsp;&nbsp;&nbsp;<?= $desig ?></option>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <label class="w-64 text-[15px] lg:-mt-5 md:-mt-5  text-gray-700">Employee<span
                                class="text-red-500">*</span></label>
                    <div class="w-[380px]">
                        <select name="employee" id="employeeDropdown" class="form-control" required>
                            <option value="">Select Employee...</option>
                        </select>
                        <span class="relative -top-8 float-right right-3"><i
                                    class="fa fa-caret-down text-white text-[12px]"></i></span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center  relative">
                    <label class="text-gray-700 w-64 ">Select Month <span class="text-red-500">*</span></label>
                    <div class="w-[380px] flex items-center  lg:ml-2 md:ml-2 sm:ml-0 ml-0 relative">
                        <input id="dobInput" name="award_month" placeholder="YYYY-MM" readonly
                               class="w-full px-3 py-2 border  focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <div class="absolute right-2 text-teal-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <!-- Calendar UI (unchanged structure as per your logic) -->
                    <div id="calendar"
                         class="hidden absolute top-full mt-2 left-[35%] w-[200px] h-[215px] bg-white rounded-xl shadow-xl z-50 pt-2 text-center flex flex-col">
                        <div class="absolute -top-2 left-1/2 transform b1 -translate-x-1/2 w-4 h-4 bg-white rotate-45"></div>

                        <div id="mainView">
                            <div class="border-b hover-zone py-1" onclick="toggleGrid('month')">
                                <div class="hover-icon cursor-pointer left-2 text-teal-500 text-xl font-bold"
                                     onclick="event.stopPropagation(); changeMonth(-1)">&#10094;
                                </div>
                                <div id="monthScroll"
                                     class="text-[18px] font-bold text-black zoom-text cursor-pointer slide-text">Jul
                                </div>
                                <div class="hover-icon cursor-pointer right-2 text-teal-500 text-xl font-bold"
                                     onclick="event.stopPropagation(); changeMonth(1)">&#10095;
                                </div>
                            </div>
                            <div class="border-b hover-zone py-1" onclick="toggleGrid('date')">
                                <div class="hover-icon cursor-pointer left-2 text-teal-500 text-xl font-bold"
                                     onclick="event.stopPropagation(); changeDate(-1)">&#10094;
                                </div>
                                <div id="dateScroll"
                                     class="text-4xl font-bold text-teal-500 zoom-text cursor-pointer slide-text">11
                                </div>
                                <div id="dayName" class="text-xs font-bold mt-0.5">Friday</div>
                                <div class="hover-icon cursor-pointer right-2 text-teal-500 text-xl font-bold"
                                     onclick="event.stopPropagation(); changeDate(1)">&#10095;
                                </div>
                            </div>
                            <div class="hover-zone py-1" onclick="toggleGrid('year')">
                                <div class="hover-icon cursor-pointer left-2 text-teal-500 text-xl font-bold"
                                     onclick="event.stopPropagation(); changeYear(-1)">&#10094;
                                </div>
                                <div id="yearScroll"
                                     class="text-base text-gray-700 mt-3 zoom-text font-bold cursor-pointer slide-text">
                                    2025
                                </div>
                                <div class="hover-icon cursor-pointer right-2 text-teal-500 text-xl font-bold"
                                     onclick="event.stopPropagation(); changeYear(1)">&#10095;
                                </div>
                            </div>
                        </div>

                        <div id="gridView" class="hidden px-3 pb-3 max-h-[215px] no-scrollbar"></div>
                        <div id="actionBtn"
                             class="bg-teal-500 w-full mt-auto rounded-b-lg py-2 text-white text-2xl font-bold cursor-pointer">
                            ✓
                        </div>
                    </div>
                </div>


                <div class="pt-2 lg:pl-[16.5rem] md:pl-[16.5rem] sm:pl-0 pl-0">
                    <button type="button" id="goBtn"
                            class="py-2 hover:bg-[#07e4e8] bg-[#01a9ac] w-[380px] text-white transition duration-300">
                        Go
                    </button>
                </div>
                <div class="py-4"></div>
            </div>
        </div>

        <div class="py-4"></div>

        <div id="notification" class="notification"></div>

        <!-- Modify the form section -->
        <div class="grid grid-cols-12 gap-3" id="paymentSection" style="display: none;">
            <div class="col-span-3">
                <div class="w-full mx-auto bg-white shadow p-6 rounded-md border-t-4 border-teal-500">
                    <h1 class="font-medium pb-3" id="paymentMonthTitle">Payment for July, 2025</h1>
                    <div class="space-y-2">
                        <label class="text-[15px] text-gray-700">Gross Salary</label>
                        <input type="text"
                               class="form-control mb-2 text-gray-400 rounded-none bg-[#F6F7FB] focus:bg-[#F6F7FB] cursor-not-allowed"
                               name="gross_salary" id="gross_salary" readonly>
                        <label class="text-[15px] text-gray-700">Total Deductions</label>
                        <input type="text"
                               class="form-control mb-2 text-gray-400 rounded-none bg-[#F6F7FB] focus:bg-[#F6F7FB] cursor-not-allowed"
                               name="total_deduction" id="total_deduction" readonly>
                        <label class="text-[15px] text-gray-700">Net Salary</label>
                        <input type="text"
                               class="form-control rounded-none text-gray-400 bg-[#F6F7FB] focus:bg-[#F6F7FB] cursor-not-allowed"
                               name="net_salary" id="net_salary" readonly>
                        <label class="text-[15px] text-gray-700">Fine Deduction</label>
                        <input type="number" name="fine_deduction" id="fine_deduction"
                               class="form-control mb-2 allow rounded-none">

                        <label class="w-64 text-[15px] mt-2 text-gray-700">Payment Type<span
                                    class="text-red-500">*</span></label>
                        <div>
                            <select name="enrollment_type" id="enrollment_type" class="selectpicker form-control "
                                    data-live-search="true" required>
                                <option value="">Select Payment Type</option>
                                <option value="cash">Cash Payment</option>
                                <option value="cheque">Cheque Payment</option>
                                <option value="bank">Bank Account</option>
                            </select>
                        </div>

                        <label class="text-[15px] text-gray-700">Payment Amount</label>
                        <input type="number" name="payment_amount" id="payment_amount"
                               class="form-control text-gray-400 rounded-none bg-[#F6F7FB] focus:bg-[#F6F7FB] cursor-not-allowed " readonly>

                        <label class="text-[15px] text-gray-700">Comments</label>
                        <input type="text" name="comments" id="comments"
                               class="form-control mb-2 allow rounded-none">

                        <div class="py-1"></div>

                        <button type="submit"
                                class="w-full btn bg-[#01a9ac] hover:bg-[#07e4e8]  text-white rounded-1">
                            Save
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-span-9">
                <div class="w-full mx-auto bg-white shadow p-6 rounded-md border-t-4 border-teal-500">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-md font-medium text-gray-800">Payment History</h1>
                        <div>
                            <a href="#" onclick="generatePDF()"
                               class="bg-[#01a9ac] text-white text-[14px] px-3 py-2.5  hover:bg-[#07e4e8] transition-all">
                                <i class="fa fa-file-pdf-o"></i></a>
                            <a href="#" onclick="window.print()"
                               class="bg-[#01a9ac] text-white text-[14px] px-3 py-2.5  hover:bg-[#07e4e8] transition-all ml-2">
                                <i class="fa fa-print"></i></a>
                        </div>
                    </div>

                    <div class="max-h-[500px] overflow-y-auto scrollbar-thin">
                        <table class="table-bordered border-[#ececec] w-full text-sm" cellpadding="10">
                            <thead class="thead-dark sticky top-0 bg-white">
                            <tr>
                                <th class="text-center">Payment Month</th>
                                <th class="text-center">Payment Date</th>
                                <th class="text-center">Gross Salary</th>
                                <th class="text-center">Total Deductions</th>
                                <th class="text-center">Net Salary</th>
                                <th class="text-center">Fine Deductions</th>
                                <th class="text-center">Payment Amount</th>
                                <th class="text-center">Comments</th>
                            </tr>
                            </thead>
                            <tbody id="paymentHistoryBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('.selectpicker').selectpicker();

        $('#designationDropdown').change(function () {
            let designation = $(this).val();
            $.get('get_employees.php?designation=' + encodeURIComponent(designation), function (data) {
                $('#employeeDropdown').html('<option value="">Select Employee...</option>' + data);
            });
        });

        $('#goBtn').on('click', function () {
            const empId = $('#employeeDropdown').val();
            const paymentMonth = $('#dobInput').val();

            if (!empId || !paymentMonth) {
                showNotification('Please select both employee and month', 'error');
                return;
            }

            // Show the payment section with animation
            $('#paymentSection').slideDown('slow');

            // Scroll to the payment section smoothly
            $('html, body').animate({
                scrollTop: $('#paymentSection').offset().top - 100
            }, 800);

            // Fetch salary details
            $.ajax({
                url: 'fetch_salary.php',
                method: 'GET',
                data: { emp_id: empId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#gross_salary').val(response.data.gross_salary);
                        $('#total_deduction').val(response.data.total_deduction);
                        $('#net_salary').val(response.data.net_salary);
                        $('#payment_amount').val(response.data.net_salary);

                        // Update payment month display
                        $('#paymentMonthTitle').text(`Payment for ${paymentMonth}`);

                        // Fetch payment history (always show all history)
                        fetchPaymentHistory(empId);

                        // Check if payment exists for SELECTED MONTH ONLY
                        checkExistingPayment(empId, paymentMonth);
                    } else {
                        showNotification(response.message, 'error');
                    }
                }
            });
        });

        // Clear left form when month/year changes
        $('#dobInput').on('change', function() {
            $('#fine_deduction').val('');
            $('#payment_amount').val($('#net_salary').val()); // Reset to net salary
            $('#enrollment_type').val('');
            $('#comments').val('');
            $('.selectpicker').selectpicker('refresh');
        });

        // Auto-calculate payment amount when fine deduction changes
        $('#fine_deduction').on('input', function() {
            const netSalary = parseFloat($('#net_salary').val()) || 0;
            const fineDeduction = parseFloat($(this).val()) || 0;
            $('#payment_amount').val(netSalary - fineDeduction);
        });

        // Form submission handler
        $('#paymentForm').on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
                url: 'save_payment.php',
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        // Refresh payment history
                        const empId = $('#employeeDropdown').val();
                        fetchPaymentHistory(empId);
                    } else {
                        showNotification(response.message, 'error');
                    }
                },
                error: function() {
                    showNotification('Error processing your request', 'error');
                }
            });
        });
    });

    function checkExistingPayment(empId, paymentMonth) {
        $.ajax({
            url: 'check_existing_payment.php',
            method: 'GET',
            data: {
                emp_id: empId,
                payment_month: paymentMonth
            },
            dataType: 'json',
            success: function(response) {
                if (response.exists) {
                    // Fill the form with existing data
                    $('#fine_deduction').val(response.data.fine_deduction);
                    $('#payment_amount').val(response.data.payment_amount);
                    $('#enrollment_type').val(response.data.enrollment_type);
                    $('#comments').val(response.data.comments);

                    // Update the selectpicker
                    // $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    }

    function fetchPaymentHistory(empId) {
        $.ajax({
            url: 'fetch_payment_history.php',
            method: 'GET',
            data: { emp_id: empId },
            dataType: 'json',
            success: function(response) {
                const tbody = $('#paymentHistoryBody');
                tbody.empty();

                if (response.success) {
                    if (response.data.length === 0) {
                        tbody.append('<tr><td colspan="8" class="text-center">No payment history found</td></tr>');
                    } else {
                        response.data.forEach(payment => {
                            tbody.append(`
                                <tr>
                                    <td class="text-center">${payment.payment_month}</td>
                                    <td class="text-center">${payment.payment_date}</td>
                                    <td class="text-center">${payment.gross_salary}</td>
                                    <td class="text-center">${payment.total_deduction}</td>
                                    <td class="text-center">${payment.net_salary}</td>
                                    <td class="text-center">${payment.fine_deduction}</td>
                                    <td class="text-center">${payment.payment_amount}</td>
                                    <td class="text-center">${payment.comments || '-'}</td>
                                </tr>
                            `);
                        });
                    }
                }
            }
        });
    }

    function showNotification(message, type) {
        const notification = $('#notification');
        notification.text(message);

        // Set background color based on type
        if (type === 'success') {
            notification.css('background-color', '#333');
        } else if (type === 'error') {
            notification.css('background-color', '#333');
        } else {
            notification.css('background-color', '#333');
        }

        // Show notification with animation
        notification.addClass('show');

        // Hide after 3 seconds
        setTimeout(() => {
            notification.removeClass('show');
        }, 3000);
    }
</script>
<script>
    function handleFileChange(event, field) {
        const fileInput = event.target;
        const label = document.getElementById(${field}-label);
        const fileInfo = document.getElementById(${field}-file - info);
        const fileName = document.getElementById(${field}-file - name);

        if (fileInput.files.length > 0) {
            const name = fileInput.files[0].name;
            label.textContent = "Change";
            fileName.textContent = name;
            fileInfo.classList.remove("hidden");
        } else {
            removeFile(field);
        }
    }

    function removeFile(field) {
        const fileInput = document.getElementById(${field}-upload);
        const label = document.getElementById(${field}-label);
        const fileInfo = document.getElementById(${field}-file - info);
        const fileName = document.getElementById(${field}-file - name);

        // Reset the file input
        fileInput.value = "";
        label.textContent = "Select File";
        fileInfo.classList.add("hidden");
        fileName.textContent = "";
    }
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
        const weekdayStr = tempDate.toLocaleDateString("en-US", {weekday: "long"});
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
        setTimeout(() => {
            isAnimating = false;
        }, 250);
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

        // Apply animation classes to month, date, and year
        animateRollingEntry(monthScroll);
        animateRollingEntry(dateScroll);
        animateRollingEntry(yearScroll);
    };

    // Reusable animation trigger
    function animateRollingEntry(el) {
        el.classList.remove("rolling-text"); // Reset
        void el.offsetWidth; // Force reflow
        el.classList.add("rolling-text");
    }


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
                const weekdayName = dateObj.toLocaleDateString("en-US", {weekday: "short"}).toUpperCase();
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

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Bootstrap-select JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    const dobInput = document.getElementById("dobInput");
    const calendar = document.getElementById("calendar");

    dobInput.onclick = () => {
        calendar.classList.toggle("hidden");
        tempDate = new Date(currentDate);
        updateCalendar();
    };
</script>
</body>
</html>