(function( $ ) {
	'use strict';

	$(document).ready(function() {

	if ($(".nav-item").hasClass("is-active")) {
 	 //$(this).addClass('nav-active ');
 	  $(this).find('.is-active').addClass('nav-active');
	}
    

});



})( jQuery );

  function startImageCrawler(e) {

      e.preventDefault();

      let action = jQuery("#crawlImageAction").val();
      let siteUrl =  jQuery("#siteUrl").val();
      let maxPages =  jQuery("#maxPages").val();
      let imgExtensions =  jQuery("#imagesExt").val();
      let sp_crawler_nonce_field =  jQuery("#sp_crawler_nonce_field").val();
      var params = {};
      
      if (!jQuery.isArray(imgExtensions) || imgExtensions.length === 0) {
        jQuery('#results-error').html('<p>Please select at least one image extension!</p>');
        return;
      }

      if (!isValidUrl(siteUrl)) {
          jQuery('#results-error').html('<p>Not valid URL! Please use following format: https://example.com</p>');
          return;
      }
              
        params.action = 'sp_crawler_fetch_images';
        params.siteUrl = siteUrl;
        params.maxPages = maxPages;
        params.imgExtensions = imgExtensions;
        params.sp_crawler_nonce_field = sp_crawler_nonce_field;
    

        $('#loader').show();

        jQuery.ajax({
          type: 'GET',
          dataType : "json",
          url: getBaseURL()+"wp-admin/admin-ajax.php", 
          data:params,  

          success: function(response) {

            console.log('res:',response);
           
            if(response.success) {
                console.log('Crawling started.');
                setTimeout(fetchResults('click'), 1000); // Adjust the delay as needed

            }else{
                jQuery('#results-error').html('<p>Error fetching data: ' + response.data + '</p>');
            }

            },
            complete: function() {
                $('#loader').hide();
            }
        });
    }



    function fetchResults(check) {
      var params = {};
      var table;
      params.action = 'sp_crawler_fetch_img_data';

        jQuery.ajax({
          url: getBaseURL()+"wp-admin/admin-ajax.php",
          method: 'GET',
          data:params,  
            
          success: function(response) {
        
            if (response.success) {
                var data = response.data;
                // Kreirajte tabelu sa podacima
                var table = '<table id="results-table" class="display" style="width:100%">';
                table += '<thead><tr><th>Page Title</th><th>Page URL</th><th>Image URLs</th></tr></thead>';
                table += '<tbody>';

                data.pages.forEach(function(page) {
                    table += '<tr>';
                    table += '<td>' + htmlspecialchars(page.page_title) + '</td>';
                    table += '<td><a href="' + htmlspecialchars(page.page_url) + '" target="_blank">' + htmlspecialchars(page.page_url) + '</a></td>';
                    table += '<td>';
                    page.image_urls.forEach(function(imageUrl) {
                        table += '<a href="' + htmlspecialchars(imageUrl) + '" target="_blank">' + htmlspecialchars(imageUrl) + '</a><br>';
                    });
                    table += '</td>';
                    table += '</tr>';
                });

                table += '</tbody>';
                table += '</table>';

                jQuery('#results-container').html(table);

                loadScripts(scripts, function() {
                  jQuery('#results-table').DataTable({
                    layout: {
                          topStart: {
                          buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                       }
                     }, 
                  });

                  if (check === 'click') {
                    $.toast({
                        heading: 'Crawling images successfully finished',
                        text: 'You can see the result in the table below.',
                        position: 'bottom-right',
                        icon: 'success',
                        hideAfter: 5000
                    });
                }

             }); 


            } else {
                console.log("udje ovde", response.data);
              jQuery('#results-container').html('<p>' + response.data + '</p>');
            }
        },
        error: function(xhr, status, error) {
            console.log("ili ovde", error);
          jQuery('#results-error').html('<p>Error fetching data: ' + error + '</p>');
     
          $.toast({
            heading: 'Error',
            text: 'An unexpected error occured while trying to create playlist! Please try again later or contact our support.',
            position: 'bottom-right',
            icon: 'error',
            hideAfter: 5000
         });
         
        }
        });
    }




    jQuery(document).ready(function() {

      switch(getCurrentPage() ) {
          case 'sp-crawler-images':
            fetchResults('load');
          break;
          case 'sp-crawler-meta':
            fetchMetaResults('load');
          break;
          case 'sp-crawler-broken-links':
            fetchBrokenLinksResults('load');
          case 'sp-crawler-url-length':
            fetchUrlLengthAnalyzer('load');
          break;
          case 'sp-crawler-speed-analyze':
            fetchSpeedAnalyzer('load');
          break;
          case 'sp-crawler-header-structure':
            fetchHeaderStructure('load');
          break;
          

      }
  });
 

  /* Meta Crawler */

  function startMetaCrawler(e) {

    e.preventDefault();

    let action = jQuery("#crawlMetaAction").val();
    let siteUrl =  jQuery("#siteUrl").val();
    let maxPages =  jQuery("#maxPages").val();
    let sp_crawler_nonce_field =  jQuery("#sp_crawler_nonce_field").val();
    var params = {};

    if (!isValidUrl(siteUrl)) {
        jQuery('#results-error').html('<p>Not valid URL! Please use following format: https://example.com</p>');
        return;
    }
      
    params.action = 'sp_crawler_fetch_meta';
    params.siteUrl = siteUrl;
    params.maxPages = maxPages;
    params.sp_crawler_nonce_field = sp_crawler_nonce_field;

      $('#loader').show();

      jQuery.ajax({
        type: 'GET',
        dataType : "json",
        url: getBaseURL()+"wp-admin/admin-ajax.php", 
        data:params,  

        success: function(response) {

            if(response.success) {
                console.log('Crawling started.');
                setTimeout(fetchMetaResults('click'), 1000); // Adjust the delay as needed

            }else{
                jQuery('#results-error').html('<p>Error fetching data: ' + response.data + '</p>');
            }
         
          },
          complete: function() {
              $('#loader').hide();
          }
      });
  }


  function fetchMetaResults(check) {
    var params = {};
    params.action = 'sp_crawler_fetch_meta_data';


    jQuery.ajax({
        url: getBaseURL() + "wp-admin/admin-ajax.php",
        method: 'GET',
        data: params,
        success: function(response) {
            if (response.success) {
                var data = response.data;
                // Kreirajte tabelu sa podacima
                var table = '<table id="results-meta-table" class="display" style="width:100%">';
                table += '<thead><tr><th>Page Title</th><th>Page URL</th><th>Meta Title</th><th>Meta Description</th><th>Meta Keywords</th><th>H1 Tags</th><th>Images without ALT</th><th>Canonical URL</th></tr></thead>';
                table += '<tbody>';

                data.pages.forEach(function(page) {
                    table += '<tr>';
                    table += '<td>' + htmlspecialchars(page.page_title) + '</td>';
                    table += '<td><a href="' + htmlspecialchars(page.page_url) + '" target="_blank">' + htmlspecialchars(page.page_url) + '</a></td>';
                    table += '<td>' + htmlspecialchars(page.meta.Title.content) + ' (' + (page.meta.Title.valid ? 'Valid' : 'Invalid: ' + page.meta.Title.reason) + ')</td>';
                    table += '<td>' + htmlspecialchars(page.meta.Description.content) + ' (' + (page.meta.Description.valid ? 'Valid' : 'Invalid: ' + page.meta.Description.reason) + ')</td>';
                    table += '<td>' + htmlspecialchars(page.meta.Keywords.content) + ' (' + (page.meta.Keywords.valid ? 'Valid' : 'Invalid: ' + page.meta.Keywords.reason) + ')</td>';
                    table += '<td>' + page.meta['H1 tags'].count + ' (' + (page.meta['H1 tags'].valid ? 'Valid' : 'Invalid: ' + page.meta['H1 tags'].reason) + ')</td>';
                    table += '<td>' + page.meta['Images without ALT'].count + ' (' + (page.meta['Images without ALT'].valid ? 'Valid' : 'Invalid: ' + page.meta['Images without ALT'].reason) + ')<br>';
                    page.meta['Images without ALT'].images.forEach(function(imgUrl) {
                        table += '<a href="' + htmlspecialchars(imgUrl) + '" target="_blank">' + htmlspecialchars(imgUrl) + '</a><br>';
                    });
                    table += '</td>';
                    table += '<td>' + (page.meta['Canonical URL'].valid ? '<a href="' + htmlspecialchars(page.meta['Canonical URL'].url) + '" target="_blank">' + htmlspecialchars(page.meta['Canonical URL'].url) + '</a>' : 'Missing') + '</td>';
                    table += '</tr>';
                });

                table += '</tbody>';
                table += '</table>';

                jQuery('#results-meta-container').html(table);

                loadScripts(scripts, function() {
                  jQuery('#results-meta-table').DataTable({
                    layout: {
                          topStart: {
                          buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                       }
                     }, 
                  });

                  if (check === 'click') {
                    $.toast({
                        heading: 'Crawling meta data successfully finished',
                        text: 'You can see the result in the table below.',
                        position: 'bottom-right',
                        icon: 'success',
                        hideAfter: 5000
                    });
                }

             }); 

            } else {
                jQuery('#results-meta-container').html('<p>' + response.data + '</p>');
            }
        },
        error: function(xhr, status, error) {
            jQuery('#results-container').html('<p>Error fetching data: ' + error + '</p>');

            $.toast({
                heading: 'Error',
                text: 'An unexpected error occurred while trying to fetch the meta report! Please try again later or contact our support.',
                position: 'bottom-right',
                icon: 'error',
                hideAfter: 5000
            });
        }
    });
}


  /* Broken Links Crawler */

  function startBrokenLinksCrawler(e) {

    e.preventDefault();

    let action = jQuery("#crawlBrokenLinksAction").val();
    let siteUrl =  jQuery("#siteUrl").val();
    let maxPages =  jQuery("#maxPages").val();
    let sp_crawler_nonce_field =  jQuery("#sp_crawler_nonce_field").val();
    var params = {};

    if (!isValidUrl(siteUrl)) {
        jQuery('#results-error').html('<p>Not valid URL! Please use following format: https://example.com</p>');
        return;
    }
      
    params.action = 'sp_crawler_fetch_broken_urls';
    params.siteUrl = siteUrl;
    params.maxPages = maxPages;
    params.sp_crawler_nonce_field = sp_crawler_nonce_field;

      $('#loader').show();

      jQuery.ajax({
        type: 'GET',
        dataType : "json",
        url: getBaseURL()+"wp-admin/admin-ajax.php", 
        data:params,  

        success: function(response) {

            if(response.success) {
                console.log('Crawling started.');
                setTimeout(fetchBrokenLinksResults('click'), 1000);

            }else{
                jQuery('#results-error').html('<p>Error fetching data: ' + response.data + '</p>');
            }
          },
          complete: function() {
              $('#loader').hide();
          }
      });
  }



  function fetchBrokenLinksResults(check) {
    var params = {};
    params.action = 'sp_crawler_fetch_broken_urls_data'; // Update action to fetch broken URLs


    jQuery.ajax({
        url: getBaseURL() + "wp-admin/admin-ajax.php",
        method: 'GET',
        data: params,
        success: function(response) {
            if (response.success) {
                var data = response.data;

                // Create table with data
                var table = '<table id="results-broken-links-table" class="display" style="width:100%">';
                table += '<thead><tr><th>Page Title</th><th>Page URL</th><th>Broken Links</th></tr></thead>';
                table += '<tbody>';

                data.page_reports.forEach(function(page) {
                    var brokenLinks = '';
                    page.broken_links.forEach(function(link) {
                        brokenLinks += '<a href="' + htmlspecialchars(link.url) + '" target="_blank">' + htmlspecialchars(link.url) + '</a> (' + htmlspecialchars(link.reason) + ')<br>';
                    });

                    table += '<tr>';
                    table += '<td>' + htmlspecialchars(page.page_title) + '</td>';
                    table += '<td><a href="' + htmlspecialchars(page.page_url) + '" target="_blank">' + htmlspecialchars(page.page_url) + '</a></td>';
                    table += '<td>' + brokenLinks + '</td>';
                    table += '</tr>';
                });

                table += '</tbody>';
                table += '</table>';

                jQuery('#results-broken-links-container').html(table);

                loadScripts(scripts, function() {
                  jQuery('#results-broken-links-table').DataTable({
                    layout: {
                          topStart: {
                          buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                       }
                     }, 
                  });

                  if (check === 'click') {
                    $.toast({
                        heading: 'Fetching broken URLs data successful',
                        text: 'You can see the result in the table below.',
                        position: 'bottom-right',
                        icon: 'success',
                        hideAfter: 5000
                    });
                }

             }); 


            } else {
                jQuery('#results-broken-links-container').html('<p>' + response.data + '</p>');
            }
        },
        error: function(xhr, status, error) {
            jQuery('#results-broken-links-container').html('<p>Error fetching data: ' + error + '</p>');

            $.toast({
                heading: 'Error',
                text: 'An unexpected error occurred while trying to fetch the broken URLs report! Please try again later or contact support.',
                position: 'bottom-right',
                icon: 'error',
                hideAfter: 5000
            });
        }
    });
}



    /** URL Lenght Analyzer */

    function startUrlLenghtCrawler(e) {

      e.preventDefault();
  
      let action = jQuery("#startUrlLenghtAction").val();
      let siteUrl =  jQuery("#siteUrl").val();
      let maxPages =  jQuery("#maxPages").val();
      let sp_crawler_nonce_field =  jQuery("#sp_crawler_nonce_field").val();
      var params = {};

      if (!isValidUrl(siteUrl)) {
         jQuery('#results-error').html('<p>Not valid URL! Please use following format: https://example.com</p>');
         return;
      }
      
      params.action = 'sp_crawler_fetch_url_length';
      params.siteUrl = siteUrl;
      params.maxPages = maxPages;
      params.sp_crawler_nonce_field = sp_crawler_nonce_field;
  
        $('#loader').show();
  
        jQuery.ajax({
          type: 'GET',
          dataType : "json",
          url: getBaseURL()+"wp-admin/admin-ajax.php", 
          data:params,  
  
          success: function(response) {

            if(response.success) {
                console.log('Crawling started.');
                setTimeout(fetchUrlLengthAnalyzer('click'), 1000);

            }else{
                jQuery('#results-error').html('<p>Error fetching data: ' + response.data + '</p>');
            }

            },
            complete: function() {
                $('#loader').hide();
            }
        });
    }



    function fetchUrlLengthAnalyzer(check) {
      var params = {};
      params.action = 'sp_crawler_fetch_url_length_data'; // Update action to fetch URL length data
  
      jQuery.ajax({
          url: getBaseURL() + "wp-admin/admin-ajax.php",
          method: 'GET',
          data: params,
          success: function(response) {
              if (response.success) {
                  var data = response.data;
  
                  // Create table with data
                  var table = '<table id="results-url-length-table" class="display" style="width:100%">';
                  table += '<thead><tr><th>Page Title</th><th>Page URL</th><th>URL Length</th><th>Valid</th><th>Recommendation</th></tr></thead>';
                  table += '<tbody>';
  
                  data.pages.forEach(function(page) {
                      table += '<tr>';
                      table += '<td>' + htmlspecialchars(page.page_title) + '</td>';
                      table += '<td><a href="' + htmlspecialchars(page.page_url) + '" target="_blank">' + htmlspecialchars(page.page_url) + '</a></td>';
                      table += '<td>' + htmlspecialchars(page.url_length) + '</td>';
                      table += '<td>' + (page.valid ? 'Yes' : 'No') + '</td>';
                      table += '<td>' + htmlspecialchars(page.recommendation) + '</td>';
                      table += '</tr>';
                  });
  
                  table += '</tbody>';
                  table += '</table>';
  
                  jQuery('#results-url-length-container').html(table);
  
                  // Dynamically load scripts 

                    loadScripts(scripts, function() {
                      jQuery('#results-url-length-table').DataTable({
                        layout: {
                              topStart: {
                              buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                           }
                         }, 
                      });

                      if (check === 'click') {
                        $.toast({
                            heading: 'Fetching URL Length data successful',
                            text: 'You can see the result in the table below.',
                            position: 'bottom-right',
                            icon: 'success',
                            hideAfter: 5000
                        });
                    }

                 });                  
  
              } else {
                  jQuery('#results-url-length-container').html('<p>' + response.data + '</p>');
              }
          },
          error: function(xhr, status, error) {
              jQuery('#results-url-length-container').html('<p>Error fetching data: ' + error + '</p>');
  
              $.toast({
                  heading: 'Error',
                  text: 'An unexpected error occurred while trying to fetch the URL Length report! Please try again later or contact support.',
                  position: 'bottom-right',
                  icon: 'error',
                  hideAfter: 5000
              });
          }
      });
  }



     /** Speed Analyzer Crawler */

     function startSpeedAnalyzerCrawler(e) {

      e.preventDefault();
  
      let action = jQuery("#startSpeedAnalyzertAction").val();
      let siteUrl =  jQuery("#siteUrl").val();
      let maxPages =  jQuery("#maxPages").val();
      let sp_crawler_nonce_field =  jQuery("#sp_crawler_nonce_field").val();
      var params = {};

      if (!isValidUrl(siteUrl)) {
        jQuery('#results-error').html('<p>Not valid URL! Please use following format: https://example.com</p>');
        return;
      }
      
      params.action = 'sp_crawler_fetch_speed_analysis';
      params.siteUrl = siteUrl;
      params.maxPages = maxPages;
      params.sp_crawler_nonce_field = sp_crawler_nonce_field;
  
        $('#loader').show();
  
        jQuery.ajax({
          type: 'GET',
          dataType : "json",
          url: getBaseURL()+"wp-admin/admin-ajax.php", 
          data:params,  
  
          success: function(response) {

            if(response.success) {
                console.log('Crawling started123.');
                setTimeout(fetchSpeedAnalyzer('click'), 1000);

            }else{
                jQuery('#results-error').html('<p>Error fetching data: ' + response.data + '</p>');
            }
               
            },
            complete: function() {
                $('#loader').hide();
            }
        });
    }



    function fetchSpeedAnalyzer(check) {
      var params = {};
      params.action = 'sp_crawler_fetch_speed_analysis_data'; // Update action to fetch speed analysis data
  
      console.log('fetchSpeedAnalyzer started.');
  
      jQuery.ajax({
          url: getBaseURL() + "wp-admin/admin-ajax.php",
          method: 'GET',
          data: params,
          success: function(response) {
              if (response.success) {
                  var data = response.data;
  
                  // Create table with data
                  var table = '<table id="results-speed-analyzer-table" class="display" style="width:100%">';
                  table += '<thead><tr><th>Page Title</th><th>Page URL</th><th>Load Time (s)</th><th>Recommendations</th></tr></thead>';
                  table += '<tbody>';
  
                  data.page_reports.forEach(function(page) {
                      var recommendations = '';
                      page.recommendations.forEach(function(rec) {
                          recommendations += '<strong>' + rec.recommendation + ':</strong> ' + htmlspecialchars(rec.details);
                          if (rec.image) {
                              recommendations += '<br><img src="' + htmlspecialchars(rec.image) + '" alt="' + htmlspecialchars(rec.alt_text) + '" style="max-width:100px;"> (' + htmlspecialchars(rec.size) + ')';
                          } else if (rec.script) {
                              recommendations += '<br><a href="' + htmlspecialchars(rec.script) + '" target="_blank">' + htmlspecialchars(rec.script) + '</a> (' + htmlspecialchars(rec.load_time) + ')';
                          } else if (rec.stylesheet) {
                              recommendations += '<br><a href="' + htmlspecialchars(rec.stylesheet) + '" target="_blank">' + htmlspecialchars(rec.stylesheet) + '</a> (' + htmlspecialchars(rec.load_time) + ')';
                          }
                          recommendations += '<br>';
                      });
  
                      table += '<tr>';
                      table += '<td>' + htmlspecialchars(page.page_title) + '</td>';
                      table += '<td><a href="' + htmlspecialchars(page.page_url) + '" target="_blank">' + htmlspecialchars(page.page_url) + '</a></td>';
                      table += '<td>' + htmlspecialchars(page.load_time) + '</td>';
                      table += '<td>' + recommendations + '</td>';
                      table += '</tr>';
                  });
  
                  table += '</tbody>';
                  table += '</table>';
  
                  jQuery('#results-speed-analyzer-container').html(table);
  
                  loadScripts(scripts, function() {
                      jQuery('#results-speed-analyzer-table').DataTable({
                          layout: {
                              topStart: {
                                  buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                              }
                          }
                      });
  
                      if (check === 'click') {
                          $.toast({
                              heading: 'Fetching speed analysis data successful',
                              text: 'You can see the result in the table below.',
                              position: 'bottom-right',
                              icon: 'success',
                              hideAfter: 5000
                          });
                      }
                  });
  
              } else {
                  jQuery('#results-speed-analyzer-container').html('<p>' + response.data + '</p>');
              }
          },
          error: function(xhr, status, error) {
              jQuery('#results-speed-analyzer-container').html('<p>Error fetching data: ' + error + '</p>');
  
              $.toast({
                  heading: 'Error',
                  text: 'An unexpected error occurred while trying to fetch the speed analysis report! Please try again later or contact support.',
                  position: 'bottom-right',
                  icon: 'error',
                  hideAfter: 5000
              });
          }
      });
  }


     /** Header Structure Analyzer Crawler */

     function startHeaderStructureCrawler(e) {

      e.preventDefault();
  
      let action = jQuery("#startHeaderStructureAction").val();
      let siteUrl =  jQuery("#siteUrl").val();
      let maxPages =  jQuery("#maxPages").val();
      let sp_crawler_nonce_field =  jQuery("#sp_crawler_nonce_field").val();
      var params = {};

      if (!isValidUrl(siteUrl)) {
        jQuery('#results-error').html('<p>Not valid URL! Please use following format: https://example.com</p>');
        return;
      }
      
      params.action = 'sp_crawler_fetch_header_structure';
      params.siteUrl = siteUrl;
      params.maxPages = maxPages;
      params.sp_crawler_nonce_field = sp_crawler_nonce_field;
  
        $('#loader').show();
  
        jQuery.ajax({
          type: 'GET',
          dataType : "json",
          url: getBaseURL()+"wp-admin/admin-ajax.php", 
          data:params,  
  
          success: function(response) {

            if(response.success) {
                setTimeout(fetchHeaderStructure('click'), 1000);

            }else{
                jQuery('#results-error').html('<p>Error fetching data: ' + response.data + '</p>');
            }
          
            },
            complete: function() {
                $('#loader').hide();
            }
        });
    }


    
    
    
    function fetchHeaderStructure(check) {
          var params = {};
          params.action = 'sp_crawler_fetch_header_structure_data'; // Update action to fetch header structure data
      
          console.log('fetchHeaderStructure started.');
      
          jQuery.ajax({
              url: getBaseURL() + "wp-admin/admin-ajax.php",
              method: 'GET',
              data: params,
              success: function(response) {
                  if (response.success) {
                      var data = response.data;
      
                      // Create table with data
                      var table = '<table id="results-header-structure-table" class="display" style="width:100%">';
                      table += '<thead><tr><th>Page URL</th><th>Headers</th><th>Errors</th><th>Recommendations</th></tr></thead>';
                      table += '<tbody>';
      
                      data.forEach(function(page) {
                          var headers = '';
                          page.headers.forEach(function(header) {
                              headers += '<strong>' + htmlspecialchars(header.tag) + ':</strong> ' + htmlspecialchars(header.text) + '<br>';
                          });
      
                          var errors = '';
                          page.errors.forEach(function(error) {
                              errors += error + '<br>';
                          });
      
                          var recommendations = '';
                          page.recommendations.forEach(function(rec) {
                              recommendations += rec + '<br>';
                          });
      
                          table += '<tr>';
                          table += '<td><a href="' + htmlspecialchars(page.page_url) + '" target="_blank">' + htmlspecialchars(page.page_url) + '</a></td>';
                          table += '<td>' + headers + '</td>';
                          table += '<td>' + errors + '</td>';
                          table += '<td>' + recommendations + '</td>';
                          table += '</tr>';
                      });
      
                      table += '</tbody>';
                      table += '</table>';
      
                      jQuery('#results-header-structure-container').html(table);
      
                      loadScripts(scripts, function() {
                          jQuery('#results-header-structure-table').DataTable({
                              layout: {
                                  topStart: {
                                      buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                                  }
                              }
                          });
      
                          if (check === 'click') {
                              $.toast({
                                  heading: 'Fetching header structure data successful',
                                  text: 'You can see the result in the table below.',
                                  position: 'bottom-right',
                                  icon: 'success',
                                  hideAfter: 5000
                              });
                          }
                      });
      
                  } else {
                      jQuery('#results-header-structure-container').html('<p>' + response.data + '</p>');
                  }
              },
              error: function(xhr, status, error) {
                  jQuery('#results-header-structure-container').html('<p>Error fetching data: ' + error + '</p>');
      
                  $.toast({
                      heading: 'Error',
                      text: 'An unexpected error occurred while trying to fetch the header structure report! Please try again later or contact support.',
                      position: 'bottom-right',
                      icon: 'error',
                      hideAfter: 5000
                  });
              }
          });
      }
  

  function htmlspecialchars(str) {
    if (typeof str !== 'string') {
        return str; // If the input is not a string, return it as is
    }
    return str.replace(/&/g, '&amp;')
              .replace(/</g, '&lt;')
              .replace(/>/g, '&gt;')
              .replace(/"/g, '&quot;')
              .replace(/'/g, '&#039;');
}

function getCurrentPage() {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get('page');
}



function getBaseURL() {
    var url = location.href;
    var baseURL = url.substring(0, url.indexOf('/', 14));

    if (baseURL.indexOf('http://localhost') != -1) {

      var url = location.href;
      var pathname = location.pathname;
      var index1 = url.indexOf(pathname);
      var index2 = url.indexOf("/", index1 + 1);
      var baseLocalUrl = url.substr(0, index2);

      return baseLocalUrl + "/";
    }
    else {

      return baseURL + "/";
      }

    }


    function loadScripts(scripts, callback) {
      var index = 0;
  
      function next() {
          if (index < scripts.length) {
              var script = document.createElement("script");
              script.src = scripts[index];
              script.onload = function() {
                  index++;
                  next();
              };
              document.head.appendChild(script);
          } else if (callback) {
              callback();
          }
      }
  
      next();
  }


  function isValidUrl(url) {
    const urlPattern = /^(https?:\/\/)(www\.)?[a-zA-Z\d-]+(\.[a-zA-Z]{2,})(\.[a-zA-Z]{2,})?(\/[a-zA-Z\d-]*)*\/?$/;
    return urlPattern.test(url);
}


  
  // List of scripts to load
  var scripts = [
      "https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.0.8/b-3.0.2/b-colvis-3.0.2/b-html5-3.0.2/b-print-3.0.2/cr-2.0.3/date-1.5.2/fc-5.0.1/fh-4.0.1/kt-2.12.1/r-3.0.2/rg-1.5.0/rr-1.5.0/sc-2.4.3/sb-1.7.1/sp-2.3.1/sl-2.0.3/sr-1.4.1/datatables.min.js",
      "https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.js"
  ];
