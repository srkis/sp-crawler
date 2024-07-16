<?php

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

function sp_crawler_page() {
	
	echo '

		<!DOCTYPE html>
	<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

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

<div class="ms-site-container">
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
                <li class="nav-item dropdown is-active">
                    <a href="#" class="nav-link dropdown-toggle animated fadeIn animation-delay-7" data-bs-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false" data-name="home">General</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="admin.php?page=sp-crawler-images" class="nav-link dropdown-toggle animated fadeIn animation-delay-7">Images </a>
                </li>

                <li class="nav-item dropdown">
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
                        <h2 class="section-title">Introduction</h2>
        
                        <div class="jumbotron">
                          <h1>SEO Performance Crawler: Enhancing Website Optimization</h1>
                            <p class="lead lead-lg">The SEO Performance Crawler is a powerful tool designed to help website owners and developers improve their site\'s performance and search engine optimization (SEO). By thoroughly analyzing various aspects of your website, the crawler identifies potential issues and provides actionable insights to enhance your site\'s visibility and efficiency.</p>

                          <p class="lead lead-lg"> This is an indispensable tool for anyone looking to improve their website\'s performance and SEO. With its comprehensive suite of functionalities, the crawler provides detailed insights and actionable recommendations to enhance various aspects of your site. Whether you\'re a website owner, developer, or SEO specialist, the SEO Performance Crawler equips you with the tools needed to achieve optimal site performance and search engine visibility.</p>
                           <!-- <p>
                                <a href="javascript:void(0)" class="btn btn-primary btn-raised btn-lg" role="button"><i class="fa fa-download"></i> Get Now</a>
                                <a href="javascript:void(0)" class="btn btn-success btn-lg" role="button"><i class="fa fa-info"></i>More info</a>
                            </p>
                            -->
                        </div>

                        </section>
                  
                    <section class="ms-component-section">
                     <a href="admin.php?page=sp-crawler-images" class="nav-link dropdown-toggle animated fadeIn animation-delay-7"><h2 class="section-title">Image Crawler</h2> </a>
                        <p>
                            One of the core functionalities of the SEO Performance Crawler is the Image Crawler. This feature scans your website for images with specific file extensions such as jpg, png, jpeg, gif, and more. These formats are known to be less favorable by Google due to their potential impact on loading speed. To utilize this functionality, you need to input parameters like the URL of the site or page, the image extensions you want to search for, and the number of pages to crawl simultaneously. The Image Crawler helps you identify and address image-related issues, contributing to faster load times and improved SEO.
                        </p>

                    </section>

                 <section class="ms-component-section">
                    <a href="admin.php?page=sp-crawler-meta" class="nav-link dropdown-toggle animated fadeIn animation-delay-7"> <h2 class="section-title no-margin-top">Meta Crawler</h2> </a>
                        <p>The Meta Crawler function is essential for checking the presence and quality of meta tags on your webpages. This tool verifies if meta tags such as title, description, and keywords are present, whether their lengths are appropriate, and if they meet SEO standards. It also examines whether images have alt tags, ensuring accessibility and SEO compliance. Additionally, the Meta Crawler checks for multiple H1 tags per page and the presence of canonical URLs. By ensuring your meta tags are well-optimized, this functionality helps improve your site\'s search engine rankings and user experience.</p>
                 </section>

                <section class="ms-component-section">
                  <a href="admin.php?page=sp-crawler-broken-links" class="nav-link dropdown-toggle animated fadeIn animation-delay-7"> <h2 class="section-title no-margin-top">Broken Link Checker</h2> </a>
                        <p>Broken links can negatively impact your site\'s SEO and user experience. The SEO Performance Crawler includes a Broken Link Checker that scans each page of your website to identify any broken links. By entering the URL of your site and setting the limit for the number of pages to scan, the tool provides a detailed report of all broken links found. This allows you to quickly address and fix these issues, maintaining the integrity and credibility of your website.</p>
                 </section>

                 <section class="ms-component-section">
                  <a href="admin.php?page=sp-crawler-url-length" class="nav-link dropdown-toggle animated fadeIn animation-delay-7"> <h2 class="section-title no-margin-top">URL Length Analyzer</h2> </a>
                        <p>Long URLs can be detrimental to both SEO and user experience. The URL Length Analyzer feature of the SEO Performance Crawler examines your site\'s URLs to ensure they are concise and SEO-friendly. By scanning your website, the tool identifies URLs that are excessively long and provides recommendations for shortening them. This not only improves your site\'s SEO but also makes it more user-friendly and easier to navigate.</p>
                 </section>

                 <section class="ms-component-section">
                 <a href="admin.php?page=sp-crawler-header-structure" class="nav-link dropdown-toggle animated fadeIn animation-delay-7"> <h2 class="section-title no-margin-top">Header Structure Validator</h2> </a>
                        <p>Proper header structure is crucial for SEO and readability. The Header Structure Validator checks the hierarchy and organization of headers (H1, H2, H3, etc.) on your webpages. This feature ensures that your headers are properly nested and logically arranged, enhancing both SEO and user experience. By validating your header structure, the SEO Performance Crawler helps you create well-organized content that is easier for search engines to index and for users to read.</p>
                 </section>

                 <section class="ms-component-section">
                  <a href="admin.php?page=sp-crawler-speed-analyze" class="nav-link dropdown-toggle animated fadeIn animation-delay-7"> <h2 class="section-title no-margin-top">Page Speed Analyzer</h2> </a>
                        

                        <p>Page speed is a critical factor in both SEO and user satisfaction. The Page Speed Analyzer feature of the SEO Performance Crawler measures the loading times of your webpages and identifies elements that may be slowing them down. By providing a detailed report on your page speed performance, this tool offers actionable insights to optimize your site\'s speed. Faster loading times lead to better SEO rankings and a more positive user experience.</p>
                 </section>

                 <section class="ms-component-section">
                        <h2 class="section-title no-margin-top">Usage and Benefits</h2>

                        <p>Using the SEO Performance Crawler is straightforward. For each functionality, you need to input specific parameters such as the URL of your site, the types of elements you want to search for, and the number of pages to scan. The tool then generates a comprehensive report highlighting areas for improvement. By addressing the issues identified by the crawler, you can significantly enhance your site\'s SEO, performance, and overall user experience.
                        </p>
                 </section>

                 <section class="ms-component-section">
                        <h2 class="section-title no-margin-top">How It Helps</h2>
                        <p>The SEO Performance Crawler helps in several ways:</p>

                        <p>1. Improving Load Times: By identifying heavy image files and other elements that slow down your site, the crawler helps you optimize load times, leading to better user experience and higher SEO rankings.
                        </p> 
                        <p>2. Improving Load Times: From ensuring proper meta tags to checking header structures and URL lengths, the tool covers all aspects of on-page SEO, helping your site rank higher on search engines.
                        </p>
                        <p>3. Maintaining Site Integrity: The Broken Link Checker ensures all links on your site are functioning correctly, maintaining the credibility and usability of your website.
                        </p>
                         <p>4. Optimizing User Experience::By improving various aspects of your site, the SEO Performance Crawler enhances the overall user experience, leading to increased engagement and lower bounce rates.
                        </p>
                 </section>


                </div> <!-- ms-paper-content -->
            </div> <!-- col-lg-9 -->
        </div> <!-- row -->
    </div> <!-- ms-paper -->
</div> <!-- container -->



</div> <!-- ms-site-container -->


</body>


</html>


	';
}


