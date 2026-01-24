<?php
$conn = new mysqli("localhost", "root", "", "hrms_dashboard");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT f_name, l_name, email FROM employee";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Mailbox UI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://cdn.ckeditor.com/4.9.2/full/ckeditor.js"></script>

    <!-- jQuery & Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <style>
        .cke_notification {
            display: none !important;
        }
        .active-tab {
            background-color: #4ffbff;
        }
        #notification {
            position: fixed;
            top: 20px;
            right: -100%;
            background-color: black;
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: right 0.5s ease-in-out;
            z-index: 9999;
        }
        #notification.show {
            right: 20px;
        }
        /* Custom dropdown styling for Select2 */
        /* Select2 Custom Styles */
        .select2-container--default .select2-selection--multiple {
            height: auto;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: white;
            font-size: 16px;
            transition: border-color 0.3s ease-in-out;
        }

        /* ✅ Focused border color */
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #01a9ac !important; /* Teal border on focus */
        }

        .select2-selection__choice {
            background-color: #01a9ac !important; /* Selected item background */
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            margin-right: 5px;
        }

        .select2-selection_choice_remove {
            color: white;
        }

        .select2-dropdown {
            max-height: 300px;
            overflow-y: auto;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        /* ✅ Force teal color on selected option in dropdown */
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #01a9ac !important;
            color: white !important;
        }

        /* ✅ Already selected option in dropdown */
        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #01a9ac !important;
            color: white !important;
        }

        /* Style the sent email list */
        #sentList {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }


        /* Style for each email row */
        #sentList li {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd; /* Border between emails */
            padding: 10px;
            background-color: #fff;
            transition: background-color 0.3s ease;
        }

        /* Hover effect for each email row */
        #sentList li:hover {
            background-color: #4ffbff; /* Light teal background on hover */
        }

        /* Checkbox style */
        #sentList li input[type="checkbox"] {
            margin-right: 10px;
            accent-color: #01a9ac; /* Change the color of the checkbox */
        }

        /* Star icon style */
        #sentList li .mr-2 {
            font-size: 18px;
            color: #01a9ac; /* Teal color for the star */
            margin-right: 10px;
        }

        /* Email subject style */
        #sentList li .w-1/4 {
            font-weight: bold;
            color: #333;
            width: 25%;
            padding-right: 10px;
            font-size: 14px;
        }

        /* Email message preview style */
        #sentList li .w-1/3 {
            color: #777;
            width: 33%;
            padding-right: 10px;
            font-size: 13px;
        }

        /* File attachment icon style */
        #sentList li .fas.fa-paperclip {
            color: #777;
            margin-right: 5px;
        }

        /* Timestamp style */
        #sentList li .ml-auto {
            font-size: 14px;
            color: black;
        }

        /* Add padding to the entire row */
        #sentList li span {
            display: flex;
            align-items: center;
        }

        /*#sentList li input[type="checkbox"] {*/
        /*    display: none;*/
        /*}*/

        /*!* Custom checkbox style *!*/
        /*#sentList li input[type="checkbox"] + label {*/
        /*    width: 20px; !* Size of the checkbox *!*/
        /*    height: 20px; !* Size of the checkbox *!*/
        /*    border: 3px solid #01a9ac; !* Thicker border with teal color *!*/
        /*    border-radius: 4px; !* Rounded corners *!*/
        /*    display: inline-block;*/
        /*    position: relative;*/
        /*    cursor: pointer;*/
        /*    transition: all 0.3s ease; !* Smooth transition *!*/
        /*}*/

        /*!* Add a tick when checked *!*/
        /*#sentList li input[type="checkbox"]:checked + label::before {*/
        /*    content: "✔"; !* Add checkmark symbol *!*/
        /*    position: absolute;*/
        /*    top: 50%; !* Center the checkmark vertically *!*/
        /*    left: 50%; !* Center the checkmark horizontally *!*/
        /*    transform: translate(-50%, -50%); !* Adjust to perfectly center *!*/
        /*    color: white; !* Checkmark color *!*/
        /*    font-size: 14px; !* Checkmark size *!*/
        /*}*/

        /*!* Hover effect for checkbox *!*/
        /*#sentList li input[type="checkbox"] + label:hover {*/
        /*    background-color: #4ffbff; !* Light teal background on hover *!*/
        /*}*/

        /*!* Adjust the checked state *!*/
        /*#sentList li input[type="checkbox"]:checked + label {*/
        /*    background-color: #01a9ac; !* Set the background to teal when checked *!*/
        /*    border-color: #01a9ac; !* Ensure border matches the background when checked *!*/
        /*}*/


        #sentList li input[type="checkbox"] {
            display: none; /* Hide native checkbox */
        }

        /* Custom checkbox container */
        #sentList li input[type="checkbox"] + label {
            width: 20px;
            height: 20px;
            background-color: white !important; /* Default background white */
            border: 2px solid #01a9ac !important;
            border-radius: 4px;
            display: inline-block;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        /* Show teal tick mark when checked */
        #sentList li input[type="checkbox"]:checked + label::before {
            content: "✔";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #01a9ac !important;            /* Tick color changed to teal */
            font-size: 14px;
            font-weight: bold;
        }

        /* Hover effect (optional soft teal glow) */
        #sentList li input[type="checkbox"] + label:hover {
            background-color: #e0fafa; /* subtle teal hover */

        }

        /* Keep border teal when checked */
        #sentList li input[type="checkbox"]:checked + label {
            background-color: white;     /* Keep white background even when checked */
            border-color: #01a9ac;

        }


        .a2{
            background: linear-gradient(to right, #01a9ac, #01dbdf);
        }
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


    </style>
</head>
<body class="bg-[#F6F7FB]">
<div id="notification">Email sent successfully!</div>
<div class="container-fluid">
    <!--white header-->
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
                <!-- Bell Icon -->
                <div class="relative inline-block text-left">
                    <button id="bellButton" onclick="togglebellDopdown()" class="relative focus:outline-none">
                        <i class="fa fa-bell-o text-[#404E67] text-[16px]" aria-hidden="true"></i>
                        <span id="unreadCount" class="absolute -top-2 -right-2 a1 text-white text-[10px] rounded-full px-1.5">0</span>
                    </button>

                    <!-- Dropdown -->
                    <div id="dopdownMenu" class="hidden absolute top-[70px] -right-[15px] w-[23rem] bg-[#fff] border border-gray-200 rounded-md shadow-lg z-50">
                        <!-- Triangle (nok) -->
                        <div class="absolute -top-2.5 right-6 w-5 h-5 bg-white rotate-45 border-t border-l border-gray-200 shadow-sm"></div>

                        <!-- Dropdown Header -->
                        <div class="flex justify-between relative items-center px-3 py-2">
                            <span class="text-[16px] font-semibold px-2 py-2 text-gray-700">Messages</span>
                            <span class="a1 text-white text-[12px] rounded-[4px] px-2 py-[2px]" id="newNotification">New</span>
                        </div>

                        <!-- Dropdown Body -->
                        <div id="notificationList" class="px-3 py-7 text-[14px] mb-[1rem] font-semibold text-center  cursor-pointer transition-colors duration-200">
<!--                            <p>No new messages</p>-->
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


    <!--header sm/md-->
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
                    <!--                    <div class="px-3 py-7 text-[14px] font-semibold text-center hover:bg-gray-100 hover:text-gray-800 cursor-pointer transition-colors duration-200">-->
                    <!--                        <p>There is no message</p>-->
                    <!--                    </div>-->
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

            <p class="text-[#999] text-md pb-3 pl-6 pt-5 font-semibold">Navigation</p>

            <!-- Dropdown Item -->
            <ul class="pt-3">
                <li class="sidebar-item text-[dcdcdc]  " onclick="activateSingleItem(this)">
                    <a href="http://localhost/project/hrms_dashboard/phpfile/dashboard.php" class="text-[#dcdcdc] pl-5"><i class="fa fa-dashboard "></i></a>
                    <a href="http://localhost/project/hrms_dashboard/phpfile/dashboard.php" class="text-[#dcdcdc] text-[15px] font-semibold pl-2 hover:text-white "> Dashboard</a>
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

                    <li class="pb-2 lg:pl-7 md:pl-7 sm:pl-6 pl-6 pt-3"><a href="http://localhost/project/hrms_dashboard/phpfile/settings/settings(1).php"
                                                                          class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                    class="fa fa-angle-right font-bold pr-2"></i>General Setting</a></li>

                    <li class="pb-2 lg:pl-7 md:pl-7 sm:pl-6 pl-6"><a href="http://localhost/project/hrms_dashboard/phpfile/settings/settings(2).php"
                                                                     class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2 "><i
                                    class="fa fa-angle-right font-bold pr-2"></i>Set Working Days</a></li>

                    <li class="pb-2 lg:pl-7 md:pl-7 sm:pl-6 pl-6"><a href="http://localhost/project/hrms_dashboard/phpfile/settings/settings(3)/index.php"
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
                                    class="fa fa-angle-right font-bold pr-2"></i>Inbox</a>
                    </li>

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
                    <!--                <li class="pb-2 pl-5"><a href="http://localhost/project/hrms_dashboard/phpfile/payrolldemo/make_payment.php"-->
                    <!--                                         class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2"><i-->
                    <!--                                class="fa fa-angle-right font-bold pr-2"></i>Make Payment</a></li>-->
                    <!--                <li class="pb-2 pl-5"><a href="http://localhost/project/hrms_dashboard/phpfile/payrolldemo/generate_payslip.php"-->
                    <!--                                         class="dropdown-item  text-[15px] hover:text-[#FE8A7D] text-[#dcdcdc] pb-2"><i-->
                    <!--                                class="fa fa-angle-right font-bold pr-2"></i>Generate Payslip</a></li>-->

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

    <!--    section2-->
    <div id="mainContent" class=" p-8 absolute -top-5 ">
        <div class="grid grid-cols-12 mt-11">
            <div class="col-span-12 lg:col-span-8">
                <h4 class=" text-[22px] font-[600] text-[#303548] text-center lg:text-left">Inbox</h4>
                <h4 class=" text-[15px] text-[#919aa3] mb-7 mt-2">Inbox</h4>
            </div>
            <div class="col-span-12 lg:col-span-4 mt-2 lg:mr-[32px]">
                <div class="flex justify-center lg:float-right"><a href="#"><i class="fa fa-home text-[#7e7e7e]" aria-hidden="true"></i></a>
                    <span class="text-[#7e7e7e] mt-[0.5px] text-[14px] px-2">/</span>
                    <a href="#" class="text-[#7e7e7e] mt-[0.5px] text-[14px]">Widget</a>
                </div>
            </div>
        </div>
        <div class="flex flex-col md:flex-row bg-white rounded-[2px] shadow-[0_1px_20px_0_rgba(69,90,100,0.08)] overflow-hidden">
            <!-- Sidebar -->
            <div class="w-full md:w-1/5  ">
                <ul class="text-sm pt-6 md:pt-11 text-[#404E67]">
                    <li>
                        <button onclick="showSection('compose', this)" class="w-full flex items-center px-4 py-3 hover:bg-[#4ffbff]">
                            <i class="fa fa-file-text mr-4 ml-2" aria-hidden="true"></i>
                            Compose
                        </button>
                    </li>
                    <li>
                        <button onclick="showSection('inbox', this)" class="w-full flex items-center px-4 py-3 hover:bg-[#4ffbff]">
                            <i class="fa-solid fa-inbox mr-4 ml-2"></i>
                            Inbox
                            <span class="ml-auto bg-cyan-500 text-white rounded-[4px] text-xs w-5 h-5 flex items-center justify-center">6</span>
                        </button>
                    </li>
                    <li>
                        <button onclick="showSection('sent', this)" class="w-full flex items-center px-4 py-3 hover:bg-[#4ffbff]">
                            <i class="fa fa-paper-plane mr-4 ml-2" aria-hidden="true"></i>
                            Sent Mail
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="w-full md:w-4/5 p-4 md:p-6 space-y-4">
                <!-- Compose Section -->
                <div id="compose" class="block">
                    <select id="emailTo" name="to[]" multiple class="w-full border border-[#ccc] text-[16px]">
<!--                        <option disabled>Select Email</option>-->
                        <?php while($row = $result->fetch_assoc()): ?>
                            <option value="<?= $row['email']; ?>">
                                <?= $row['f_name'] . " " . $row['l_name']; ?> (<?= $row['email']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <input id="emailSubject" type="text" placeholder="Subject" class="w-full border border-[#ccc] rounded-[4px] mt-4 mb-4 p-2 text-[16px] focus:outline-none focus:border-[#00B8D9]" />

                    <textarea id="editor1" class="w-full h-40 border border-[#ccc] rounded p-2">Put your things here</textarea>

                    <div class="mt-4">
                        <label for="file-upload" class="flex items-center cursor-pointer text-sm text-gray-700 font-medium">
                            <i class="fas fa-paperclip h-4 w-4 mr-1"></i> Attachment
                        </label>
                        <input type="file" id="file-upload" class="hidden" />
                        <p class="text-xs text-red-600 mt-1">Max. 15 MB</p>
                    </div>

                    <button onclick="sendEmail()" class="bg-[#01a9ac] text-white text-sm px-4 py-2 rounded mt-4 flex items-center">
                        <i class="fas fa-paper-plane h-4 w-4 mr-1"></i> Send
                    </button>
                </div>
                <div id="emailDetailContainer"></div> <!-- This is where the email details will appear -->

                <!-- Inbox Section -->
                <div id="inbox" class="hidden">
                    <div class="flex space-x-2 border-b border-gray-200 ">
                        <button class="bg-[#01a9ac] mb-4 text-white px-4 py-2 rounded-[2px]"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></button>
                        <button class="bg-[#0ac282] mb-4 text-white px-4 py-2 rounded-[2px]"><i class="fa-solid fa-inbox"></i></button>
                        <button class="bg-[#fe5d70]  mb-4 text-white px-4 py-2 rounded-[2px]"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                    </div>
                    <p class="text-sm hover:bg-[#4ffbff] transition-all ease-in duration-300 px-2 py-3"><strong>There is no email to display</strong></p>
                </div>

                <!-- Sent Section -->
                <div id="sent" class="hidden">
                    <div class="flex space-x-2 mb-4">
                        <button class="bg-[#01a9ac] text-white px-4 py-2 rounded-[2px]"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></button>
                        <button class="bg-[#0ac282] text-white px-4 py-2 rounded-[2px]"><i class="fa-solid fa-inbox"></i></button>
                        <button id="deleteBtn" class="bg-[#fe5d70] text-white px-4 py-2 rounded-[2px]"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                    </div>
                    <ul id="sentList">
                        <!-- Sent emails will appear here -->
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- CKEditor Full Toolbar Init and Email Script -->
<script>
    // Initialize CKEditor with full toolbar
    CKEDITOR.replace('editor1', {
        height: 303,
        toolbar: [
            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
            { name: 'editing', items: ['-', 'Scayt'] },
            { name: 'forms', items: ['Link', 'Unlink', 'Anchor'] },
            { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar', '-', 'Maximize'] },
            { name: 'document', items: ['Source'] },
            '/',
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
            { name: 'styles', items: ['Styles', 'Format'] },
            { name: 'about', items: ['About'] }
        ],
        removeButtons: '',
        resize_enabled: true
    });

    let unreadCount = 0;

    function togglebellDopdown() {
        const menu = document.getElementById('dopdownMenu');
        menu.classList.toggle('hidden');
        if (!menu.classList.contains('hidden')) {
            document.getElementById('unreadCount').textContent = '0';
            unreadCount = 0;
        }
    }

    document.addEventListener('click', function (event) {
        const bell = document.getElementById('bellButton');
        const menu = document.getElementById('dopdownMenu');
        if (!bell.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });

    function sendEmail() {
        const subject = document.getElementById('emailSubject').value || '';
        const message = CKEDITOR.instances.editor1.getData() || '';
        const recipient = $('#emailTo').val();

        const time = new Date().toISOString().replace('T', ' ').slice(0, 19);
        const sentEmails = JSON.parse(localStorage.getItem('sentEmails')) || [];
        const newId = Date.now();

        const email = {
            id: newId,
            email: recipient.join(', '),
            subject: subject,
            message: message,
            time: time,
            attachments: ['image1.jpg', 'image2.jpg']
        };

        sentEmails.unshift(email);
        localStorage.setItem('sentEmails', JSON.stringify(sentEmails));

        addEmailToBellDropdown(email);
        showNotification('Email sent successfully!', 'success');
        unreadCount++;
        document.getElementById('unreadCount').textContent = unreadCount;

        const inboxBtn = document.querySelectorAll('ul li button')[1];
        showSection('inbox', inboxBtn);
        loadSentEmails();
    }

    // function addEmailToBellDropdown(email) {
    //     const notificationList = document.getElementById('notificationList');
    //     console.log('Redirecting to read_index.php?emailId=' + email.id);
    //
    //     const emailItem = document.createElement('div');
    //     emailItem.className = 'flex items-center justify-between px-3 py-1 cursor-pointer hover:bg-gray-100';
    //     emailItem.onclick = () => {
    //         window.location.href = `read_index.php?emailId=${email.id}`;
    //     };
    //
    //     const timeAgoText = timeAgo(email.time);
    //     emailItem.innerHTML = `
    //         <div class="flex items-center space-x-3">
    //             <img src="../images/img.png" alt="user avatar" class="rounded-full w-12 h-12">
    //             <div class="flex-1">
    //                 <span class="font-semibold text-black text-sm">${email.subject}</span>
    //                 <div class="text-sm text-gray-500">${email.message.slice(0, 30)}...</div>
    //             </div>
    //             <div class="text-xs text-gray-400">${timeAgoText}</div>
    //         </div>
    //     `;
    //
    //     notificationList.prepend(emailItem);
    //     hideNoMessagesText();
    // }

    function addEmailToBellDropdown(email) {
        const notificationList = document.getElementById('notificationList');

        // ✅ Remove 'No new messages' BEFORE adding new email
        hideNoMessagesText();

        const emailItem = document.createElement('div');
        emailItem.className = 'flex items-center justify-between px-3 py-2 hover:bg-gray-100 transition duration-200';
        emailItem.onclick = () => {
            window.location.href = `read_index.php?emailId=${email.id}`;
        };

        const timeAgoText = timeAgo(email.time);
        emailItem.innerHTML = `
        <div class="flex items-center space-x-3 text-left">
            <img src="images/img.png" alt="user avatar" class="rounded-full w-10 h-10">
            <div class="flex-1">
                <p class="font-semibold text-sm text-gray-800">${email.subject}</p>
                <p class="text-xs text-gray-500">${email.message.slice(0, 30)}...</p>
            </div>
            <div class="text-xs text-gray-400 whitespace-nowrap">${timeAgoText}</div>
        </div>
    `;

        notificationList.prepend(emailItem);
    }


    function hideNoMessagesText() {
        const notificationList = document.getElementById('notificationList');
        const noMessagesText = document.getElementById('noMessagesText');
        if (notificationList.children.length > 0 && noMessagesText) {
            notificationList.removeChild(noMessagesText);
        }
    }

    // function showNoMessagesText() {
    //     const notificationList = document.getElementById('notificationList');
    //     if (notificationList.children.length === 0 && !document.getElementById('noMessagesText')) {
    //         const noMessagesDiv = document.createElement('div');
    //         noMessagesDiv.id = 'noMessagesText';
    //         noMessagesDiv.className = 'text-center text-gray-500 py-4';
    //         noMessagesDiv.textContent = 'No new messages';
    //         notificationList.appendChild(noMessagesDiv);
    //     }
    // }



    function showNoMessagesText() {
        const notificationList = document.getElementById('notificationList');
        const noMessagesText = document.getElementById('noMessagesText');

        if (notificationList.children.length === 0 && !noMessagesText) {
            const noMessagesDiv = document.createElement('div');
            noMessagesDiv.id = 'noMessagesText';
            noMessagesDiv.className = 'text-center text-gray-500 py-4';
            noMessagesDiv.textContent = 'No new messages';
            notificationList.appendChild(noMessagesDiv);
        }
    }


    function handleNoEmails() {
        showNoMessagesText();
    }

    function showNotification(message, type) {
        const notif = document.getElementById('notification');
        notif.textContent = message;
        if (type === 'success') {
            notif.style.backgroundColor = 'black';
        }
        notif.classList.add('show');
        setTimeout(() => {
            notif.classList.remove('show');
        }, 3000);
    }

    function showSection(id, btn) {
        const sections = ['compose', 'inbox', 'sent'];
        const buttons = document.querySelectorAll('ul li button');

        sections.forEach(sec => document.getElementById(sec).classList.add('hidden'));
        document.getElementById(id).classList.remove('hidden');

        buttons.forEach(b => b.classList.remove('active-tab'));
        btn.classList.add('active-tab');
    }

    function showDropdownMenu(emails) {
        dropdownMenu.innerHTML = "";

        emails.forEach((email, index) => {
            const emailItem = document.createElement("div");

            emailItem.className = 'flex items-center justify-between px-3 py-2 hover:bg-gray-200 transition duration-200';

            emailItem.innerHTML = `
            <span class="font-semibold">${email.name}</span>
            <span class="text-gray-500 text-sm">${email.email}</span>
        `;

            emailItem.addEventListener("click", () => {
                const option = document.createElement("option");
                option.value = email.email;
                option.textContent = `${email.name} (${email.email})`;
                option.selected = true;
                emailToSelectBox.appendChild(option);
                dropdownMenu.classList.add("hidden");
            });

            dropdownMenu.appendChild(emailItem);
        });

        dropdownMenu.classList.remove("hidden");
    }


    function loadSentEmails() {
        const sentEmails = JSON.parse(localStorage.getItem('sentEmails')) || [];
        const sentList = document.getElementById('sentList');
        sentList.innerHTML = '';

        if (sentEmails.length === 0) {
            sentList.innerHTML = "<p>No sent emails available.</p>";
        } else {
            sentEmails.sort((a, b) => new Date(b.time) - new Date(a.time));

            sentEmails.forEach(email => {
                const encodedId = btoa(email.email + email.time); // Encode email for safe ID usage

                const li = document.createElement('li');
                li.className = 'flex items-center border-b py-2';
                li.innerHTML = `
        <input type="checkbox" class="mr-2" id="checkbox-${encodedId}" data-email="${email.email}">
        <label for="checkbox-${encodedId}"></label>

        <span class="mr-2 text-[#01a9ac] pr-2 ml-7"><i class="fa text-sm fa-star" aria-hidden="true"></i></span>
        <span class="w-1/4">${email.subject}</span>
        <span class="w-1/3">${email.message.slice(0, 40)}</span>
        <span class="text-sm">
            <label class="flex items-center cursor-pointer text-sm text-gray-700 font-medium">
                <i class="fas fa-paperclip mr-1"></i>
            </label>
        </span>
        <span class="ml-auto text-xs text-black">${timeAgo(email.time)}</span>
    `;
                sentList.prepend(li);
            });

        }
    }

    function deleteSelectedEmails() {
        const checkboxes = document.querySelectorAll('#sentList input[type="checkbox"]:checked');
        const sentEmails = JSON.parse(localStorage.getItem('sentEmails')) || [];
        const emailsToDelete = Array.from(checkboxes).map(checkbox => checkbox.dataset.email);
        const updatedEmails = sentEmails.filter(email => !emailsToDelete.includes(email.email));
        localStorage.setItem('sentEmails', JSON.stringify(updatedEmails));

        checkboxes.forEach(checkbox => {
            const row = checkbox.closest('li');
            row.remove();
        });
    }

    document.getElementById('deleteBtn').addEventListener('click', deleteSelectedEmails);

    window.onload = function () {
        const inboxBtn = document.querySelectorAll('ul li button')[1];
        showSection('inbox', inboxBtn);
        loadSentEmails();
    };

    function timeAgo(time) {
        const now = new Date();
        const past = new Date(time);
        const diff = now.getTime() - past.getTime(); // in ms
        const seconds = Math.floor(diff / 1000);
        if (seconds < 60) return `${seconds} sec ago`;
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) return `${minutes} min ago`;
        const hours = Math.floor(minutes / 60);
        if (hours < 24) return `${hours} hrs ago`;

        const days = Math.floor(hours / 24);
        return `${days} day${days > 1 ? 's' : ''} ago`;
    }

</script>



<!-- Select2 Email Dropdown -->
<script>
    $(document).ready(function () {
        $('#emailTo').select2({
            placeholder: 'Select Email',
            allowClear: true,
            width: '100%',
            maximumSelectionLength: 5
        });
    });
</script>

<!--    java-->

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