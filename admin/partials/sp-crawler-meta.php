<?php


require_once WP_PLUGIN_DIR . '/seo-performance-crawler/includes/class-sp-crawler-helper.php';

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
*  @link       https://github.com/srkis
 * @since      1.0.0
 *
 * @package    SP_Crawler
 * @subpackage SP_Crawler/partials
 */
 

// This file should primarily consist of HTML with a little bit of PHP.

function sp_crawler_meta() {
	
	echo '
	<!DOCTYPE html>
	<html lang="en">
 
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="'.SP_CRAWLER_DIR.'/admin/css/preload.min.css">
    <link rel="stylesheet" href="'.SP_CRAWLER_DIR.'/admin/css/plugins.min.css">
    <link rel="stylesheet" href="'.SP_CRAWLER_DIR.'/admin/css/style.light-blue-500.min.css">
    <link rel="stylesheet" href="'.SP_CRAWLER_DIR.'/admin/css/width-boxed.min.css">
    <link rel="stylesheet" href="'.SP_CRAWLER_DIR.'/admin/css/jquery.toast.min.css"> 

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.0.8/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/cr-2.0.3/date-1.5.2/fc-5.0.1/fh-4.0.1/kt-2.12.1/r-3.0.2/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.7.1/sp-2.3.1/sl-2.0.3/sr-1.4.1/datatables.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

</head>

<body>


<div id="ms-preload" class="ms-preload">
    <div id="status">
        <div class="spinner">
            <div class="dot1"></div>
            <div class="dot2"></div>
        </div>
    </div>
</div>

<div style="width: 100%;" class="ms-site-container">
    <!-- Modal -->


<header class="ms-header ms-header-primary"> <!--ms-header-primary-->
    <div class="header-title">
        <div class="ms-title">
            <a href="admin.php?page=sp-crawler">
                <!-- <img src="assets/img/demo/logo-header.png" alt=""> -->
                <span class="ms-logo animated zoomInDown animation-delay-5">SEO</span>
                <h1 class="animated fadeInRight animation-delay-6">Perfomance <span>Crawler</span></h1>
            </a>
            <small style="display: block; margin-left: 85px;margin-top: -20px;">Boost Your Performance and Rankings with Precision Crawling</small>
        </div>
       
    </div>
</header>
    <nav class="navbar navbar-expand-md  navbar-static ms-navbar ms-navbar-primary">
    <div class="container container-full">
        <div class="navbar-header">
            <a class="navbar-brand" href="admin.php?page=sp-crawler">
                <!-- <img src="assets/img/demo/logo-navbar.png" alt=""> -->
                <span class="ms-logo ms-logo-sm">SEO</span>
                <span class="ms-title">Perfomance <strong>Crawler</strong></span>
            </a>
        </div>


        <div class="collapse navbar-collapse" id="ms-navbar">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a href="admin.php?page=sp-crawler" class="nav-link dropdown-toggle animated fadeIn animation-delay-7">General</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="admin.php?page=sp-crawler-images" class="nav-link dropdown-toggle animated fadeIn animation-delay-7">Images</a>
                </li>

                <li class="nav-item dropdown is-active">
                    <a href="admin.php?page=sp-crawler-meta" class="nav-link dropdown-toggle animated fadeIn animation-delay-7">Meta Tags<i class="zmdi zmdi-chevron-down"></i></a>
                </li>

                <li class="nav-item">
                    <a href="admin.php?page=sp-crawler-broken-links" class="nav-link dropdown-toggle animated fadeIn animation-delay-7">Broken URLs <i class="zmdi zmdi-chevron-down"></i></a>
                </li>

                <li class="nav-item">
                    <a href="admin.php?page=sp-crawler-url-length" class="nav-link dropdown-toggle animated fadeIn animation-delay-7">URL Length Analyzer <i class="zmdi zmdi-chevron-down"></i></a>
                </li>

                <li class="nav-item dropdown">
                    <a href="admin.php?page=sp-crawler-speed-analyze" class="nav-link dropdown-toggle animated fadeIn animation-delay-7" >Speed Analyze <i class="zmdi zmdi-chevron-down"></i></a>
                </li>

                <li class="nav-item dropdown">
                    <a href="admin.php?page=sp-crawler-header-structure" class="nav-link dropdown-toggle animated fadeIn animation-delay-7" >Header Structure <i class="zmdi zmdi-chevron-down"></i></a>
                </li>

            </ul>
        </div>
        <a href="javascript:void(0)" class="ms-toggle-left btn-navbar-menu"><i class="zmdi zmdi-menu"></i></a>
    </div> <!-- container -->
</nav>

<!-- <div class="material-background"></div> -->

<div style="max-width:1600px;margin:0;" class="container container-full">
    <div class="ms-paper">
        <div class="row">

            <div class="col-lg-12 ms-paper-content-container">
                <div class="ms-paper-content">

                    <section class="ms-component-section">
                        <h2 class="section-title" style="color:#9C27B0;">Meta Tags Crawler</h2>

                        <div class="alert alert-royal  role="alert">
                            <strong><i style="font-size:24px" class="fa fa-info-circle" aria-hidden="true"></i> !</strong> This tool will help you to find all important Meta tags on your website and analyze them for best SEO performance.
                        </div>

                        <form class="form-horizontal" autocomplete="off">
                            <fieldset>
                                <legend>Crawling Params</legend>
                                <div class="form-group row is-empty">
                                    <label for="siteUrl" autocomplete="false" class="col-lg-2 control-label">Site Url</label>

                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" id="siteUrl" placeholder="Website Url">
                                    </div>
                                </div>

                               <div id="loader" style="display: none;">
                                <div class="show-spinner"></div>
                                <div class="loader-text">Crawling in progress, please wait...</div>
                            </div>


                                <div class="form-group row is-empty">
                                    <label for="maxPages" autocomplete="false" class="col-lg-2 control-label">Page Limit</label>

                                    <div class="col-lg-10">
                                        <input type="number" class="form-control" id="maxPages" placeholder="Limit">
                                    </div>
                                </div>
                          

                   <div class="form-group row justify-content-start">
                    <div class="col-lg-10">
                        <a href="#" onclick="startMetaCrawler(event)" id="startMetaCrawler" class="btn btn-raised btn-royal">Start Crawling<div class="ripple-container"></div></a>
                       <input type="hidden" name="crawlMetaAction" id="crawlMetaAction" value="startMeta">
                    </div>
                </div>
        </fieldset>
        </form>
     </section>
      <section class="ms-component-section">
                       
                        <div class="bs-example">
                        <div id="results-error"></div>
                        <div id="results-meta-container"></div>
                        </div> <!-- /example -->
                        </section>
                </div> <!-- ms-paper-content -->
            </div> <!-- col-lg-9 -->
        </div> <!-- row -->
    </div> <!-- ms-paper -->
</div> <!-- container -->

</div> <!-- ms-site-container -->


 <script src="'.SP_CRAWLER_DIR.'/admin/js/plugins.min.js"></script> 

<script src="'.SP_CRAWLER_DIR.'/admin/js/app.min.js"></script>

<script src="'.SP_CRAWLER_DIR.'/admin/js/configurator.min.js"></script>
     
<script src="'.SP_CRAWLER_DIR.'/admin/js/jquery.toast.min.js"></script>

 <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>


</body>


</html>


	';
}

