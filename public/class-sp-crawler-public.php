<?php
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-sp-crawler-helper.php';
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://srdjan.icodes.rocks
 * @since      1.0.0
 *
 * @package    SP_Crawler
 * @subpackage SP_Crawler/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    SP_Crawler
 * @subpackage SP_Crawler/public
 * @author     Srki <stojanovicsrdjan27@gmail.com>
 */

class SP_Crawler_Public
{

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
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/sp-crawler-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/sp-crawler-public.js', array('jquery'), $this->version, false);
	}




	public function sp_crawler_fetch_images()
	{
		$url = $_GET['siteUrl'];
		$extensions = $_GET['imgExtensions'];

		$extensions = (is_array($extensions) && count($extensions) > 0) ? array_map('trim', $extensions) : ['png', 'jpg', 'jpeg', 'gif'];

		// Pretvorite sve ekstenzije u mala slova i filtrirajte nevažeće ekstenzije
		$extensions = array_map('strtolower', $extensions);
		$extensions = array_filter($extensions, function ($ext) {
			return preg_match('/^[a-z0-9]+$/', $ext);
		});

		// Izbacite duplikate ako je potrebno
		$extensions = array_unique($extensions);

		$maxPages = isset($_GET['maxPages']) ? (int)$_GET['maxPages'] : 5;
		$dataDir = plugin_dir_path(dirname(__FILE__)) . 'data/';
		$jsonFile = $dataDir . 'images_results.json';

		// Proverite da li postoji direktorijum, ako ne kreirajte ga
		if (!is_dir($dataDir)) {
			mkdir($dataDir, 0755, true);
		}

		// Proverite da li postoji fajl, ako ne kreirajte ga
		if (!file_exists($jsonFile)) {
			touch($jsonFile);
		}

		$visited = [];
		$toVisit = [SP_Crawler_Helper::normalizeUrl($url)];

		// Otvaranje JSON datoteke za pisanje
		$fileHandle = fopen($jsonFile, 'w');
		if ($fileHandle === false) {

			wp_send_json_error("Error opening JSON file for writing: $jsonFile");
		}

		// Početak JSON-a
		fwrite($fileHandle, '{ "pages": [' . PHP_EOL);

		$firstPage = true;

		while ($toVisit && count($visited) < $maxPages) {
			$batch = array_splice($toVisit, 0, 10); // Process 10 URLs at a time

			foreach ($batch as $currentUrl) {
				if (in_array($currentUrl, $visited) || count($visited) >= $maxPages) {
					continue;
				}

				$visited[] = $currentUrl;
				$images = SP_Crawler_Helper::getImagesFromPage($currentUrl, $extensions);

				// Formatiranje podataka u JSON format
				$pageData = [
					'page_title' => SP_Crawler_Helper::getPageTitle($currentUrl), // Funkcija za dobivanje naslova stranice
					'page_url' => $currentUrl,
					'image_urls' => $images
				];

				// Dodavanje zarezova između objekata, osim za prvi objekt
				if (!$firstPage) {
					fwrite($fileHandle, ',' . PHP_EOL);
				} else {
					$firstPage = false;
				}

				// Upisivanje podataka u JSON datoteku
				fwrite($fileHandle, json_encode($pageData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
			}

			foreach ($batch as $currentUrl) {
				// Prikupljanje svih linkova sa stranice koje treba posjetiti
				$links = SP_Crawler_Helper::getAllLinks($currentUrl);
				foreach ($links as $link) {
					$normalizedLink = SP_Crawler_Helper::normalizeUrl($link);
					if (!in_array($normalizedLink, $visited) && !in_array($normalizedLink, $toVisit)) {
						$toVisit[] = $normalizedLink;
					}
				}
			}
		}

		// Završetak JSON formata
		fwrite($fileHandle, PHP_EOL . ']' . PHP_EOL . '}' . PHP_EOL);

		// Zatvaranje datoteke
		fclose($fileHandle);

		$result = array(
			'siteUrl' => $$url,
			'extensions' => $extensions
		);

		$msg = ["success" => true];
		//$this->jsonResponse($msg, $result);
		wp_send_json_success($result);
	}


	public function sp_crawler_fetch_img_data() {

		$jsonFile = plugin_dir_path(dirname(__FILE__)) . 'data/images_results.json';

		if (!file_exists($jsonFile) || filesize($jsonFile) == 0) {
			wp_send_json_error('No data available yet. Please start crawling.');
		}

		// Učitavanje sadržaja JSON datoteke
		$jsonContent = file_get_contents($jsonFile);
		if ($jsonContent === false) {
			wp_send_json_error('Error loading JSON file: ' . $jsonFile);
		}

		// Dekodiranje JSON sadržaja u PHP niz
		$data = json_decode($jsonContent, true);
		if ($data === null) {
			wp_send_json_error('Error decoding JSON file: ' . json_last_error_msg());
		}

		// Provjera postojanja podataka o stranicama
		if (!isset($data['pages']) || empty($data['pages'])) {
			wp_send_json_error('No pages data found.');
		}

		wp_send_json_success($data);
	}


	public	function sp_crawler_fetch_meta() {

		$siteUrl = $_GET['siteUrl'];
		$maxPages = $_GET['maxPages'];

		$pages = [];
	

		$dataDir = plugin_dir_path(dirname(__FILE__)) . 'data/';
		$jsonFile = $dataDir . 'results_meta.json';

		$fileHandle = fopen($jsonFile, 'w');
			if ($fileHandle === false) {
				wp_send_json_error("Error opening JSON file for writing: $jsonFile");
			}

		// Normalize site URL
		$siteUrl = rtrim($siteUrl, '/') . '/';
		$visitedUrls = [];
		$urlsToVisit = [$siteUrl];

		while (count($visitedUrls) < $maxPages && !empty($urlsToVisit)) {
			$url = array_shift($urlsToVisit);

			if (in_array($url, $visitedUrls)) {
				continue;
			}

			$html = SP_Crawler_Helper::fetchUrl($url);

			if ($html === false) {
				continue;
			}

			$doc = new DOMDocument();
			libxml_use_internal_errors(true); // Suppress errors for invalid HTML

			// Load HTML content into DOMDocument
			$doc->loadHTML($html);
			libxml_clear_errors();

			$pageTitle = '';
			$metaDescription = '';
			$metaKeywords = '';
			$h1Tags = [];
			$imagesWithoutAlt = [];
			$canonicalUrl = '';

			// Get page title
			$titleTags = $doc->getElementsByTagName('title');
			if ($titleTags->length > 0) {
				$pageTitle = $titleTags->item(0)->nodeValue;
			}

			// Get meta description and keywords
			$metaTags = $doc->getElementsByTagName('meta');
			foreach ($metaTags as $tag) {
				if (strtolower($tag->getAttribute('name')) === 'description') {
					$metaDescription = $tag->getAttribute('content');
				}
				if (strtolower($tag->getAttribute('name')) === 'keywords') {
					$metaKeywords = $tag->getAttribute('content');
				}
			}

			// Get H1 tags
			$h1TagsElements = $doc->getElementsByTagName('h1');
			foreach ($h1TagsElements as $h1) {
				$h1Tags[] = $h1->nodeValue;
			}

			// Check for images without ALT attribute
			$imageTags = $doc->getElementsByTagName('img');
			foreach ($imageTags as $img) {
				if (!$img->hasAttribute('alt') || empty($img->getAttribute('alt'))) {
					$imagesWithoutAlt[] = $img->getAttribute('src');
				}
			}

			// Check for canonical URL
			$linkTags = $doc->getElementsByTagName('link');
			foreach ($linkTags as $link) {
				if (strtolower($link->getAttribute('rel')) === 'canonical') {
					$canonicalUrl = $link->getAttribute('href');
					break;
				}
			}

			// Validate lengths
			$titleValidation = SP_Crawler_Helper::validateLength($pageTitle, 50, 60);
			$descriptionValidation = SP_Crawler_Helper::validateLength($metaDescription, 50, 160);
			$keywordsValidation = SP_Crawler_Helper::validateLength($metaKeywords, 0, 255); // Keywords are less strict
			$h1Count = count($h1Tags);
			$h1Validation = $h1Count === 1;

			$meta = [
				"Title" => [
					"content" => $pageTitle,
					"length" => $titleValidation['length'],
					"valid" => $titleValidation['valid'],
					"reason" => $titleValidation['reason']
				],
				"Description" => [
					"content" => $metaDescription,
					"length" => $descriptionValidation['length'],
					"valid" => $descriptionValidation['valid'],
					"reason" => $descriptionValidation['reason']
				],
				"Keywords" => [
					"content" => $metaKeywords,
					"length" => $keywordsValidation['length'],
					"valid" => $keywordsValidation['valid'],
					"reason" => $keywordsValidation['reason']
				],
				"H1 tags" => [
					"count" => $h1Count,
					"valid" => $h1Validation,
					"reason" => $h1Validation ? '' : 'More than one H1 tag'
				],
				"Images without ALT" => [
					"count" => count($imagesWithoutAlt),
					"images" => $imagesWithoutAlt,
					"valid" => count($imagesWithoutAlt) === 0,
					"reason" => count($imagesWithoutAlt) === 0 ? '' : 'Some images are missing ALT attributes'
				],
				"Canonical URL" => [
					"url" => $canonicalUrl,
					"valid" => !empty($canonicalUrl),
					"reason" => !empty($canonicalUrl) ? '' : 'Missing canonical URL'
				]
			];

			// Remove slashes from URL
			$cleanedUrl = stripslashes($url);

			$pageData = [
				"page_title" => $pageTitle,
				"page_url" => $cleanedUrl,
				"meta" => $meta
			];

			$pages[] = $pageData;
			$visitedUrls[] = $url;

			// Find and queue new URLs to visit
			$links = $doc->getElementsByTagName('a');
			foreach ($links as $link) {
				$href = $link->getAttribute('href');
				if (strpos($href, $siteUrl) !== false && !in_array($href, $visitedUrls) && !in_array($href, $urlsToVisit)) {
					$urlsToVisit[] = $href;
				}
			}
		}

		// Prepare report structure
		$report = [
			"pages" => $pages
		];

		// Ensure the data directory exists
		$dataDir = plugin_dir_path(dirname(__FILE__)) . 'data/';
		if (!is_dir($dataDir)) {
			mkdir($dataDir, 0755, true);
		}

		// Save report to JSON file
		$jsonFile = $dataDir . 'results_meta.json';
		$jsonReport = json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		file_put_contents($jsonFile, $jsonReport);

		wp_send_json_success($report);
	}


	// Fetch Meta Report Function
	public function sp_crawler_fetch_meta_data() {
		$jsonFile = plugin_dir_path(dirname(__FILE__)) . 'data/results_meta.json';

		if (!file_exists($jsonFile) || filesize($jsonFile) == 0) {
			wp_send_json_error('No data available yet. Please start crawling.');
		}

		$jsonContent = file_get_contents($jsonFile);
		if ($jsonContent === false) {
			return json_encode(['error' => "Error loading JSON file: $jsonFile"]);
		}

		$data = json_decode($jsonContent, true);
		if ($data === null) {
			return json_encode(['error' => "Error decoding JSON file: " . json_last_error_msg()]);
		}

		wp_send_json_success($data);

		// return json_encode($data);
	}


	public function sp_crawler_fetch_broken_urls() {

		$siteUrl = $_GET['siteUrl'];
		$maxPages = $_GET['maxPages'];

		$dataDir = plugin_dir_path(dirname(__FILE__)) . 'data/';
		$jsonFile = $dataDir . 'broken_links.json';

		$fileHandle = fopen($jsonFile, 'w');
		if ($fileHandle === false) {
			wp_send_json_error("Error opening JSON file for writing: $jsonFile");
		}

		$brokenLinks = [];
		$visitedUrls = [];
		$urlsToVisit = [rtrim($siteUrl, '/') . '/'];
		$pageReports = [];
	
		while (count($visitedUrls) < $maxPages && !empty($urlsToVisit)) {
			$url = array_shift($urlsToVisit);
	
			if (in_array($url, $visitedUrls)) {
				continue;
			}
	
			$visitedUrls[] = $url;
	
			$html = SP_Crawler_Helper::fetchUrl($url);
	
			if ($html === false) {
				$brokenLinks[] = [
					'url' => $url,
					'reason' => 'Failed to fetch URL'
				];
				continue;
			}
	
			$doc = new DOMDocument();
			libxml_use_internal_errors(true); // Suppress errors for invalid HTML
			$doc->loadHTML($html);
			libxml_clear_errors();
	
			$title = $doc->getElementsByTagName('title')->item(0)->nodeValue ?? 'No Title Found';
	
			$pageReports[] = [
				'page_title' => $title,
				'page_url' => $url,
				'broken_links' => []
			];
	
			$links = $doc->getElementsByTagName('a');
			foreach ($links as $link) {
				$href = $link->getAttribute('href');
	
				if (empty($href) || strpos($href, 'mailto:') !== false || strpos($href, 'tel:') !== false) {
					continue;
				}
	
				$absoluteUrl = SP_Crawler_Helper::resolveUrl($siteUrl, $href);
	
				if (!in_array($absoluteUrl, $visitedUrls) && !in_array($absoluteUrl, $urlsToVisit)) {
					$urlsToVisit[] = $absoluteUrl;
				}
	
				$headers = @get_headers($absoluteUrl);
				if ($headers === false || strpos($headers[0], '200') === false) {
					$brokenLinks[] = [
						'url' => $absoluteUrl,
						'reason' => $headers === false ? 'No response' : 'HTTP Status: ' . $headers[0]
					];
	
					// Add broken link to current page report
					end($pageReports);
					$pageIndex = key($pageReports);
					$pageReports[$pageIndex]['broken_links'][] = [
						'url' => $absoluteUrl,
						'reason' => $headers === false ? 'No response' : 'HTTP Status: ' . $headers[0]
					];
				}
			}
		}
	
		$fullReport = [
		//	"broken_links" => $brokenLinks,
			"page_reports" => $pageReports
		];
	
		$dataDir = plugin_dir_path(dirname(__FILE__)) . 'data/';
		if (!is_dir($dataDir)) {
			mkdir($dataDir, 0755, true);
		}
	
		$jsonFile = $dataDir . 'broken_links.json';
		$jsonReport = json_encode($fullReport, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		file_put_contents($jsonFile, $jsonReport);
	
		wp_send_json_success($fullReport);
		//echo "JSON report has been created successfully.";
	}



	// Function to get broken_links.json file
	public function sp_crawler_fetch_broken_urls_data() {

		$jsonFile = plugin_dir_path(dirname(__FILE__)) . 'data/broken_links.json';

		if (!file_exists($jsonFile) || filesize($jsonFile) == 0) {
			wp_send_json_error("No data available yet. Please start crawling.");
			//return json_encode(['error' => 'No data available yet. Please start crawling.']);
		}

		$jsonContent = file_get_contents($jsonFile);
		if ($jsonContent === false) {
			wp_send_json_error("Error loading JSON file: $jsonFile");
			//return json_encode(['error' => "Error loading JSON file: $jsonFile"]);
		}

		$data = json_decode($jsonContent, true);
		if ($data === null) {
			wp_send_json_error("Error decoding JSON file: " . json_last_error_msg());
			//return json_encode(['error' => "Error decoding JSON file: " . json_last_error_msg()]);
		}

		wp_send_json_success($data);
}
	


    public function sp_crawler_fetch_url_length() {

		$siteUrl = $_GET['siteUrl'];
		$maxPages = $_GET['maxPages'];

		$dataDir = plugin_dir_path(dirname(__FILE__)) . 'data/';
		$jsonFile = $dataDir . 'url_length.json';

		$fileHandle = fopen($jsonFile, 'w');
		if ($fileHandle === false) {
			wp_send_json_error("Error opening JSON file for writing: $jsonFile");
		}

		// Normalize site URL
		$siteUrl = rtrim($siteUrl, '/') . '/';
		$visitedUrls = [];
		$urlsToVisit = [$siteUrl];
		$pages = [];
	
		while (count($visitedUrls) < $maxPages && !empty($urlsToVisit)) {
			$url = array_shift($urlsToVisit);
	
			if (in_array($url, $visitedUrls)) {
				continue;
			}

			$html = SP_Crawler_Helper::fetchUrl($url);
			
			if ($html === false) {
				continue;
			}
	
			$doc = new DOMDocument();
			libxml_use_internal_errors(true); // Suppress errors for invalid HTML
	
			// Load HTML content into DOMDocument
			$doc->loadHTML($html);
			libxml_clear_errors();
	
			$pageTitle = '';
			$titleTags = $doc->getElementsByTagName('title');
			if ($titleTags->length > 0) {
				$pageTitle = $titleTags->item(0)->nodeValue;
			}
	
			$urlLength = strlen($url);
			$urlRecommendation = '';
			$urlValid = true;
	
			if ($urlLength < 50) {
				$urlValid = false;
				$urlRecommendation = 'URL is too short. Ideally, URLs should be between 50-60 characters long.';
			} elseif ($urlLength > 60) {
				$urlValid = false;
				$urlRecommendation = 'URL is too long. Ideally, URLs should be between 50-60 characters long.';
			} else {
				$urlRecommendation = 'URL length is optimal.';
			}
	
			$pageData = [
				'page_title' => $pageTitle,
				'page_url' => stripslashes($url),
				'url_length' => $urlLength,
				'valid' => $urlValid,
				'recommendation' => $urlRecommendation
			];
	
			$pages[] = $pageData;
			$visitedUrls[] = $url;
		
			// Find and queue new URLs to visit
			$links = $doc->getElementsByTagName('a');
			foreach ($links as $link) {
				$href = $link->getAttribute('href');
				if (strpos($href, $siteUrl) !== false && !in_array($href, $visitedUrls) && !in_array($href, $urlsToVisit)) {
					$urlsToVisit[] = $href;
				}
			}
		}

		
		// Prepare report structure
		$report = [
			'pages' => $pages
		];
	
		// Ensure the data directory exists
		$dataDir = plugin_dir_path(dirname(__FILE__)) . 'data/';
		if (!is_dir($dataDir)) {
			if (!mkdir($dataDir, 0755, true)) {
				return 'Error: Cannot create data directory.';
			}
		}

		// Save report to JSON file
		$jsonFile = $dataDir . 'url_length.json';
		$jsonReport = json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		if (file_put_contents($jsonFile, $jsonReport) === false) {
			return 'Error: Cannot write to JSON file.';
		}

		wp_send_json_success($report);
	
    }


		// Function to get broken_links.json file
	public function sp_crawler_fetch_url_length_data() {

			$jsonFile = plugin_dir_path(dirname(__FILE__)) . 'data/url_length.json';
	
			if (!file_exists($jsonFile) || filesize($jsonFile) == 0) {
				wp_send_json_error("No data available yet. Please start crawling.");
			}
	
			$jsonContent = file_get_contents($jsonFile);
			if ($jsonContent === false) {
				wp_send_json_error("Error loading JSON file: $jsonFile");
			}
	
			$data = json_decode($jsonContent, true);
			if ($data === null) {
				wp_send_json_error("Error decoding JSON file: " . json_last_error_msg());
			}
	
			wp_send_json_success($data);
	}



	/** Page Speed Analyzer */

	function sp_crawler_fetch_speed_analysis() {

		$siteUrl = $_GET['siteUrl'];
		$maxPages = $_GET['maxPages'];
		// Normalize site URL
		
		$siteUrl = rtrim($siteUrl, '/') . '/';
		$visitedUrls = [];
		$urlsToVisit = [$siteUrl];
		$pageReports = [];


		$dataDir = plugin_dir_path(dirname(__FILE__)) . 'data/';
		$jsonFile = $dataDir . 'speed_analyze.json';

		$fileHandle = fopen($jsonFile, 'w');
		if ($fileHandle === false) {
			wp_send_json_error("Error opening JSON file for writing: $jsonFile");
		}	
	
		while (count($visitedUrls) < $maxPages && !empty($urlsToVisit)) {
			$url = array_shift($urlsToVisit);
	
			if (in_array($url, $visitedUrls)) {
				continue;
			}
			
			$startTime = microtime(true);
			$html = SP_Crawler_Helper::fetchUrl($url);
			$endTime = microtime(true);
			
			$loadTime = round($endTime - $startTime, 2); // Format load time to two decimal places
	
			if ($html === false) {
				continue;
			}

			$pageTitle = SP_Crawler_Helper::getSpeedPageTitle($html);
			
			$recommendations = array_merge(
				SP_Crawler_Helper::getImageRecommendations($html),
				SP_Crawler_Helper::getScriptAndCssRecommendations($html)
			);

			$pageReports[] = [
				"page_title" => $pageTitle,
				"page_url" => stripslashes($url),
				"load_time" => $loadTime, // Use formatted load time
				"recommendations" => $recommendations
			];
	
			$visitedUrls[] = $url;

			$newUrls = SP_Crawler_Helper::getLinksFromHtml($html, $siteUrl);
			foreach ($newUrls as $newUrl) {
				if (!in_array($newUrl, $visitedUrls) && !in_array($newUrl, $urlsToVisit)) {
					$urlsToVisit[] = $newUrl;
				}
			}
		}
	
		// Prepare report structure
		$report = [
			"page_reports" => $pageReports
		];
	
		// Ensure the data directory exists
		$dataDir = plugin_dir_path(dirname(__FILE__)) . 'data/';
		if (!is_dir($dataDir)) {
			if (!mkdir($dataDir, 0755, true)) {
				return "Error: Unable to create data directory.";
			}
		}
	
		// Save report to JSON file
		$jsonFile = $dataDir . 'speed_analyze.json';
		$jsonReport = json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		if (!is_writable($dataDir) || file_put_contents($jsonFile, $jsonReport) === false) {
			return "Error: Unable to write to data directory.";
		}
	
		wp_send_json_success($report);
	}
	


			// Function to get broken_links.json file
		public function sp_crawler_fetch_speed_analysis_data() {

				$jsonFile = plugin_dir_path(dirname(__FILE__)) . 'data/speed_analyze.json';
		
				if (!file_exists($jsonFile) || filesize($jsonFile) == 0) {
					wp_send_json_error("No data available yet. Please start crawling.");
				}
		
				$jsonContent = file_get_contents($jsonFile);
				if ($jsonContent === false) {
					wp_send_json_error("Error loading JSON file: $jsonFile");
				}
		
				$data = json_decode($jsonContent, true);
				if ($data === null) {
					wp_send_json_error("Error decoding JSON file: " . json_last_error_msg());
				}
		
				wp_send_json_success($data);
		}


		public function sp_crawler_fetch_header_structure() {
			$url = $_GET['siteUrl'];
			$limit = (int) $_GET['maxPages'];
		
			$crawledPages = 0;
			$results = [];
			$toCrawl = [$url];
			$crawled = [];


			$dataDir = plugin_dir_path(dirname(__FILE__)) . 'data/';
			$jsonFile = $dataDir . 'header_structure.json';

			$fileHandle = fopen($jsonFile, 'w');
			if ($fileHandle === false) {
				wp_send_json_error("Error opening JSON file for writing: $jsonFile");
			}
		
			while (!empty($toCrawl) && $crawledPages < $limit) {
				$currentUrl = array_shift($toCrawl);
		
				if (in_array($currentUrl, $crawled)) {
					continue;
				}
		
				$htmlContent = SP_Crawler_Helper::fetchUrl($currentUrl);
				if ($htmlContent === false) {
					$results[] = [
						'page_url' => $currentUrl,
						'headers' => [],
						'errors' => ['Failed to fetch page'],
						'recommendations' => []
					];
					$crawled[] = $currentUrl;
					$crawledPages++;
					continue;
				}
		
				$dom = new DOMDocument();
				@$dom->loadHTML($htmlContent);
				$xpath = new DOMXPath($dom);
		
				$headers = [];
				foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $header) {
					$nodes = $xpath->query("//{$header}");
					foreach ($nodes as $node) {
						$headers[] = [
							'tag' => $header,
							'text' => trim($node->textContent)
						];
					}
				}
		
				// Validate header structure
				$errors = [];
				$recommendations = [];
				$lastLevel = 0;
				$h1Count = 0;
		
				foreach ($headers as $header) {
					$currentLevel = (int) substr($header['tag'], 1);
		
					// Check for multiple H1 tags
					if ($currentLevel === 1) {
						$h1Count++;
						if ($h1Count > 1) {
							$errors[] = "Multiple H1 tags found. There should only be one H1 tag.";
						}
					}
		
					// Check for hierarchy issues
					if ($currentLevel > $lastLevel + 1) {
						$errors[] = "Header {$header['tag']} should not follow header h" . ($lastLevel);
					}
		
					// Check for empty headers
					if (empty($header['text'])) {
						$errors[] = "{$header['tag']} is empty. Consider adding text.";
					}
		
					// Check for header text length
					$textLength = strlen($header['text']);
					if ($textLength < 10) {
						$recommendations[] = "{$header['tag']} is too short. Consider adding more descriptive text.";
					} elseif ($textLength > 60) {
						$recommendations[] = "{$header['tag']} is too long. Consider shortening the text.";
					}
		
					$lastLevel = $currentLevel;
				}
		
				if ($h1Count === 0) {
					$recommendations[] = 'No H1 tag found. Ensure to include an H1 tag for the main title.';
				} elseif ($lastLevel != 1) {
					$recommendations[] = 'Ensure to start with an H1 tag for the main title.';
				}
		
				$results[] = [
					'page_url' => $currentUrl,
					'headers' => $headers,
					'errors' => $errors,
					'recommendations' => $recommendations
				];
		
				$crawled[] = $currentUrl;
				$crawledPages++;
		
				// Extract and add links within the same domain to the toCrawl list
				$links = $xpath->query("//a[contains(@href, 'http')]");
				foreach ($links as $link) {
					$href = $link->getAttribute('href');
					// Normalize relative URLs
					if (strpos($href, 'http') !== 0) {
						$href = rtrim($currentUrl, '/') . '/' . ltrim($href, '/');
					}
					if (strpos($href, $url) === 0 && !in_array($href, $crawled) && !in_array($href, $toCrawl)) {
						$toCrawl[] = $href;
					}
				}
			}
		
			// Ensure the data directory exists
			$dataDir = plugin_dir_path(dirname(__FILE__)) . 'data/';
			if (!is_dir($dataDir)) {
				if (!mkdir($dataDir, 0755, true)) {
					return "Error: Unable to create data directory.";
				}
			}
		
			// Save report to JSON file
			$jsonFile = $dataDir . 'header_structure.json';
			$jsonReport = json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			if (!is_writable($dataDir) || file_put_contents($jsonFile, $jsonReport) === false) {
				return "Error: Unable to write to data directory.";
			}
		
			wp_send_json_success($results);
		}
		
		
		public function sp_crawler_fetch_header_structure_data() {

			$jsonFile = plugin_dir_path(dirname(__FILE__)) . 'data/header_structure.json';
		
			if (!file_exists($jsonFile) || filesize($jsonFile) == 0) {
				wp_send_json_error("No data available yet. Please start crawling.");
			}
	
			$jsonContent = file_get_contents($jsonFile);
			if ($jsonContent === false) {
				wp_send_json_error("Error loading JSON file: $jsonFile");
			}
	
			$data = json_decode($jsonContent, true);
			if ($data === null) {
				wp_send_json_error("Error decoding JSON file: " . json_last_error_msg());
			}
	
			wp_send_json_success($data);

		}

	



	private function jsonResponse($msg, $result)
	{
		echo json_encode(array($msg, 'result' => $result));
		die;
	}
}
