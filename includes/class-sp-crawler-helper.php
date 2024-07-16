<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://srdjan.icodes.rocks
 * @since      1.0.0
 *
 * @package    SP_Crawler
 * @subpackage SP_Crawler/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    SP_Crawler
 * @subpackage SP_Crawler/inludes
 * @author     Srki <stojanovicsrdjan27@gmail.com>
 */

class SP_Crawler_Helper {

    /**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

    public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}


    public static function getPageTitle($url) {

        $html = self::fetchUrl($url);
        if ($html === false) {
            return "Unknown Title";
        }
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $title = $dom->getElementsByTagName('title');
        if ($title->length > 0) {
            return $title->item(0)->textContent;
        } else {
            return "No Title Found";
        }

    }


    public static function getSpeedRecommendations($html, $loadTime) {
		$recommendations = [];
	
		// Add a recommendation for optimizing load time
		if ($loadTime > 2.0) {
			$recommendations[] = [
				"recommendation" => "Page load time is too high",
				"details" => "Page took $loadTime seconds to load. Aim for under 2 seconds.",
				"suggestions" => [
					"Optimize images",
					"Minify CSS and JavaScript files",
					"Enable caching",
					"Use a Content Delivery Network (CDN)"
				]
			];
		}
	
		// Check for unoptimized images
		$doc = new DOMDocument();
		libxml_use_internal_errors(true); // Suppress errors for invalid HTML
		$doc->loadHTML($html);
		libxml_clear_errors();
	
		$images = $doc->getElementsByTagName('img');
		foreach ($images as $img) {
			$src = $img->getAttribute('src');
			$alt = $img->getAttribute('alt');
			$recommendations[] = [
				"recommendation" => "Check image optimization",
				"details" => "Ensure images are optimized and have appropriate alt attributes.",
				"image" => $src,
				"alt_text" => $alt ? $alt : 'Missing alt attribute'
			];
		}
	
		// More checks can be added here, such as minifying CSS/JS, leveraging browser caching, etc.
	
		return $recommendations;
	}

    public static function getLinksFromHtml($html, $baseUrl) {
		$doc = new DOMDocument();
		libxml_use_internal_errors(true); // Suppress errors for invalid HTML
		$doc->loadHTML($html);
		libxml_clear_errors();
		
		$links = $doc->getElementsByTagName('a');
		$urls = [];
		foreach ($links as $link) {
			$href = $link->getAttribute('href');
			if (strpos($href, $baseUrl) !== false) {
				$urls[] = $href;
			}
		}
		return $urls;
	}

    public static function getImageRecommendations($html) {

      
        $recommendations = [];
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        libxml_clear_errors();
       
        $images = $doc->getElementsByTagName('img');
        
        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            $alt = $img->getAttribute('alt');
            $imageSize = self::getRemoteFileSize($src);
    
            if ($imageSize > 500000) { // Size in bytes (e.g., 500KB)
                $recommendations[] = [
                    "recommendation" => "Image Optimization",
                    "details" => "Image is too large. Consider optimizing the image.",
                    "image" => $src,
                    "size" => round($imageSize / 1024 / 1024, 2) . 'MB', // Convert size to MB
                    "alt_text" => $alt ? $alt : 'Missing alt attribute'
                ];
            }
        }
    
       
        return $recommendations;
    }

    public static function getScriptAndCssRecommendations($html) {
        $recommendations = [];
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        libxml_clear_errors();
    
        $scripts = $doc->getElementsByTagName('script');
        foreach ($scripts as $script) {
            $src = $script->getAttribute('src');
            if ($src) {
                $loadTime = self::getRemoteFileLoadTime($src);
                if ($loadTime > 2) { // Load time in seconds
                    $recommendations[] = [
                        "recommendation" => "JavaScript Optimization",
                        "details" => "JavaScript file is too slow. Consider minifying the file or using a cache.",
                        "script" => $src,
                        "load_time" => $loadTime . 's'
                    ];
                }
            }
        }
    
        $styles = $doc->getElementsByTagName('link');
        foreach ($styles as $style) {
            if (strtolower($style->getAttribute('rel')) === 'stylesheet') {
                $href = $style->getAttribute('href');
                $loadTime = self::getRemoteFileLoadTime($href);
                if ($loadTime > 2) { // Load time in seconds
                    $recommendations[] = [
                        "recommendation" => "CSS Optimization",
                        "details" => "CSS file is too slow. Consider minifying the file or using a cache.",
                        "stylesheet" => $href,
                        "load_time" => $loadTime . 's'
                    ];
                }
            }
        }
    
        return $recommendations;
    }

    public static function getRemoteFileSize($url) {
        $response = wp_remote_head($url);
    
        if (is_wp_error($response)) {
            return 0; // Handle error appropriately
        }
    
        $headers = wp_remote_retrieve_headers($response);
        $size = isset($headers['content-length']) ? (int) $headers['content-length'] : 0;
    
        return $size;
    }
    
    public static function getRemoteFileLoadTime($url) {
        $startTime = microtime(true);
        $response = wp_remote_get($url);
        if (is_wp_error($response)) {
            return 0; // Handle error appropriately
        }
        // Get the response time
        $endTime = microtime(true);
        $loadTime = round($endTime - $startTime, 2);
    
        return $loadTime;
    }


    public static function getSpeedPageTitle($html) {
		$doc = new DOMDocument();
		libxml_use_internal_errors(true); // Suppress errors for invalid HTML
		$doc->loadHTML($html);
		libxml_clear_errors();
		
		$titleTags = $doc->getElementsByTagName('title');
		return $titleTags->length > 0 ? $titleTags->item(0)->nodeValue : '';
	}


    public static function fetchUrl($url) {
        $args = array(
            'timeout' => 15, // Adjust timeout as needed
            'user-agent' => 'SEO-Performance-Crawler/1.0',
            'sslverify' => false, // Adjust SSL verification as needed
        );
    
        $response = wp_remote_get($url, $args);
    
        if (is_wp_error($response)) {
            return false; // Handle error appropriately
        }
    
        $httpCode = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
    
        if ($httpCode !== 200) {
            return false;
        }
    
        return $body;
    }


    public static function normalizeUrl($url) {

        //  var_dump($url);die;
        $parsedUrl = wp_parse_url($url);
    
        // Provjeri postoji li ključ 'path' u $parsedUrl prije pristupa
        if (!isset($parsedUrl['path'])) {
            // Ako nema 'path', koristimo prazan string
            $path = '';
        } else {
            $path = $parsedUrl['path'];
        }
    
        $normalizedUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $path;
        return rtrim($normalizedUrl, '/');
    }


   public static function getAllLinks($url) {
        $html = self::fetchUrl($url);
        if ($html === false) {
            return [];
        }
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $links = [];
    
        foreach ($dom->getElementsByTagName('a') as $link) {
            $href = $link->getAttribute('href');
            // Ignore mailto links, JavaScript links, and external links
            if (strpos($href, 'mailto:') === 0 || strpos($href, 'javascript:') === 0 || (strpos($href, 'http') === 0 && strpos($href, $url) !== 0)) {
                continue;
            }
            // Make relative links absolute
            if (strpos($href, 'http') !== 0) {
                $href = rtrim($url, '/') . '/' . ltrim($href, '/');
            }
            // Normalize the URL
            $href = self::normalizeUrl($href);
            $links[] = $href;
        }
    
        return array_unique($links);
    }


    public static function getImagesFromPage($url, $extensions) {
        $html = self::fetchUrl($url);
        if ($html === false) {
            return [];
        }
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $images = [];
    
        foreach ($dom->getElementsByTagName('img') as $img) {
            $src = $img->getAttribute('src');
            // Make relative image paths absolute
            if (strpos($src, 'http') !== 0) {
                $src = rtrim($url, '/') . '/' . ltrim($src, '/');
            }
            // Check if the image extension is in the allowed extensions
            $ext = pathinfo($src, PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), $extensions)) {
                $images[] = $src;
            }
        }
    
        return $images;
    }



    public static function validateLength($string, $min, $max) {
        $length = strlen($string);
        $valid = $length >= $min && $length <= $max;
        $reason = $valid ? '' : "Length is $length, which is not between $min and $max";
        return ['length' => $length, 'valid' => $valid, 'reason' => $reason];
    }


    public static function resolveUrl($base, $relative) {
        if (wp_parse_url($relative, PHP_URL_SCHEME) != '') {
            return $relative;
        }
        if ($relative[0] == '#' || $relative[0] == '?') {
            return $base . $relative;
        }
        extract(wp_parse_url($base));
        $path = preg_replace('#/[^/]*$#', '', $path);
        if ($relative[0] == '/') {
            $path = '';
        }
        $abs = "$host$path/$relative";
        $re = ['#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#'];
        for ($n = 1; $n > 0; $abs = preg_replace($re, '/', $abs, -1, $n)) {}
        return $scheme . '://' . $abs;
    }


    public static function displayResultsTable($jsonFile) {
        $output = '';
    
        if (!file_exists($jsonFile) || filesize($jsonFile) == 0) {
            return '<h2 class="section-title" id="tables-example">No data available yet. Please start crawling.</h2>';
        }

            // Load the JSON file content
        global $wp_filesystem;

        if (empty($wp_filesystem)) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            WP_Filesystem();
        }

        $jsonContent = $wp_filesystem->get_contents($jsonFile);
        if ($jsonContent === false) {
            wp_send_json_error('Error loading JSON file: ' . $jsonFile);
        }
    
        // Dekodiranje JSON sadržaja u PHP niz
        $data = json_decode($jsonContent, true);
        if ($data === null) {
            return "Error decoding JSON file: " . json_last_error_msg();
        }
    
        // Provjera postojanja podataka o stranicama
        if (!isset($data['pages']) || empty($data['pages'])) {
            return "No pages data found.";
        }

        
    
        // Prikaz tablice
        $output .= '<table id="results-table" class="display" style="width:100%">';
        $output .= '<thead><tr><th>Page Title</th><th>Page URL</th><th>Image URLs</th></tr></thead>';
        $output .= '<tbody>';
    
        foreach ($data['pages'] as $page) {
            $output .= '<tr>';
            $output .= '<td>' . htmlspecialchars($page['page_title']) . '</td>';
            $output .= '<td><a href="' . htmlspecialchars($page['page_url']) . '" target="_blank">' . htmlspecialchars($page['page_url']) . '</a></td>';
            $output .= '<td>';
            foreach ($page['image_urls'] as $imageUrl) {
                $output .= '<a href="' . htmlspecialchars($imageUrl) . '" target="_blank">' . htmlspecialchars($imageUrl) . '</a><br>';
            }
            $output .= '</td>';
            $output .= '</tr>';
        }
    
        $output .= '</tbody>';
        $output .= '</table>';
    
        // Skripta za inicijalizaciju jQuery DataTables
        $output .= '<script>
                    $(document).ready(function() {
                        $("#results-table").DataTable();
                    });
                  </script>';
    
        return $output;
    }

    
    

}
