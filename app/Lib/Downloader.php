<?php

namespace App\Lib;

/**
 * Class BlockModel
 * @package IWantThis
 */
class Downloader {

    // Custom post type
    const post_type = 'iwnt-block';
	
	public $download_images = true;
	public $download_fonts = true;

    // post ID
    public $id = null;

    // post title
    public $title;

    // other post properties stored as post_meta
    public $properties = array();

	
	public $relativeUrl;
    /**
     * Init new block
     * @param array|int $data
     */
    public function __construct($data=array())
    {
        if (is_numeric($data)) {
            $this->id = $data;
            $this->load();
        } else {
            if (is_array($data) && count($data)) {
                $this->setValues($data);
            }
        }
    }
	
	public function remote_get($url) {
		// initialisation de la session
		$ch = curl_init();

		// configuration des options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);

		// exécution de la session
		$ret = curl_exec($ch);

		// fermeture des ressources
		curl_close($ch);
		
		return $ret;
	}
	
	public function is_error($thing) {
		return ( $thing instanceof Execption );
	}

    /**
     * Magic get method for block properties
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        return null;
    }

    /**
     * Magic set method for block properties
     * @param $name
     * @param $value
     * @return mixed|null
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * Init block with information from DB
     */
    private function load()
    {
        if ($this->id) {
			//todo replace load function
            /*$post = get_post( $this->id );
            if (!$post) {
                $this->id = null;
                return;
            }
            $this->title = $post->post_title;
            $this->id = $post->ID;
            $meta = get_post_meta($post->ID);
            foreach ($meta as $key=>$item) {
                $newKey = substr($key, 1);
                $this->properties[$newKey] = $item[0];
            }
			*/
        }
    }

    /**
     * Save block information
     * @return int|\WP_Error
     */
    public function save()
    {
		//todo
		//replace save method
        if ( !$this->id ) {
			/*
            $postId = wp_insert_post(
                array(
                    'post_type' => self::post_type,
                    'post_status' => 'publish',
                    'post_title' => $this->title
                )
            );*/
        } else {
            // Delete post meta
            /*$meta = get_post_meta($this->id);
            foreach ($meta as $key=>$item) {
                delete_post_meta($this->id, $key);
            }

            $postId = wp_update_post(
                array(
                    'ID' => (int) $this->id,
                    'post_status' => 'publish',
                    'post_title' => $this->title,
                    'post_type' => self::post_type,
                )
            );*/

        }

        /*if ( $postId ) {
            $this->id = $postId;

            foreach ( $this->properties as $key => $value ) {
                if (!$key != 'images') {
                    update_post_meta( $postId, '_' . $key, $value );
                }
            }
        }*/

        return $postId;
    }

    /**
     * Delete block
     * @return bool
     */
    public function delete()
    {
		//todo
		//replace delete method
        if ($this->id) {
            /*$this->deleteRelatedFiles();
            if ( wp_delete_post( $this->id, true ) ) {
                $this->id = 0;

                return true;
            }*/
        }
        return false;
    }

    public function deleteRelatedFiles()
    {
		//todo
		//replace delete method
		/*
        $posts = get_posts( 'meta_key=_iwnt_parent&meta_value='.$this->id.'&post_type=attachment&post_status=any&posts_per_page=100' );

        if ( $posts && count($posts) ) {
            foreach ($posts as $post) {
                wp_delete_attachment( $post->ID, true );
            }
        }*/
    }

    /**
     * Set model properties
     * @param array $data
     */
    public function setValues($data)
    {
        if (isset($data['id']) && $data['id']) {
            $this->id = $data['id'];
        }

        if (isset($data['title']) && $data['title']) {
            $this->title = $data['title'];
        }

        $fields = array(
            'url', 'title', 'html', 'css', 'images',
            'download_images', 'download_fonts', 'download_js'
        );

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $this->properties[$field] = $data[$field];
            }
        }
    }
	
	public function is_ssl() {
		if ( isset( $_SERVER['HTTPS'] ) ) {
			if ( 'on' == strtolower( $_SERVER['HTTPS'] ) ) {
				return true;
			}
	 
			if ( '1' == $_SERVER['HTTPS'] ) {
				return true;
			}
		} elseif ( isset($_SERVER['SERVER_PORT'] ) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
			return true;
		}
		return false;
	}
	
	public function iwnt_plugin_url( $path = '' ) {
		$url = 'http://testtoto';

		if ( $this->is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
			$url = 'https:' . substr( $url, 5 );
		}

		return $url;
	}

    /**
     * Get page by URL
     */
    public function getPage($url, $downloader, $loadJS=false, $isResource=false)
    {
        try {
            if (!function_exists('file_get_html')) {
                require_once(dirname(__FILE__).'/simple_html_dom.php');
            }

            $parts = parse_url($url);
			if(empty($parts['scheme'])) {
				$parts['scheme'] = 'http';
			}
            $domain = $parts['scheme'].'://'.$parts['host'];

            if (isset($parts['port']) && $parts['port'] && ($parts['port'] != '80')) {
                $domain .= ':'.$parts['port'];
            }

            // Relative path URL
            $this->relativeUrl = $domain;
			$this->fullUrl = $domain.$parts['path'];
            if (isset($parts['path']) && $parts['path']) {
                $pathParts = explode('/', $parts['path']);
                if (count($pathParts)) {
                    unset($pathParts[count($pathParts)-1]);
                    $this->relativeUrl = $domain.'/'.implode('/',$pathParts);
                }
            }

            $content = $this->remote_get($this->fullUrl);
			//die(var_dump($this->fullUrl));
			if($isResource) die($content);
			
            if ($this->is_error($content) || ($content['response']['code'] != 200)) {
                $arrContextOptions=array(
                    "ssl"=>array(
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                    ),
                    'http'=>array(
                        'ignore_errors' => true,
                        'method'=>"GET",
                        'header'=>"Accept-language: en-US,en;q=0.5\r\n" .
                            "Cookie: foo=bar\r\n" .
                            "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/600.8.9 (KHTML, like Gecko) Version/8.0.8 Safari/600.8.9\r\n" // i.e. An iPad
                    )
                );
                $this->html = $html = file_get_html($url, false, stream_context_create($arrContextOptions));
            } else {
                $this->html = $html = str_get_html($content['body']);
            }

            if (!$html) return false;

            // Check if javascript is enabled
            if (!$loadJS) {
                foreach($html->find('script') as $element) {
                    $element->outertext = '';
                }
            }

            // Remove meta
            foreach($html->find('meta[http-equiv*=refresh]') as $meta) {
                $meta->outertext = '';
            }

            // Remove meta x-frame
            foreach($html->find('meta[http-equiv*=x-frame-options]') as $meta) {
                $meta->outertext = '';
            }

            // Modify image and CSS URL's adding domain name if needed
            foreach($html->find('img') as $element) {
                $src = trim($element->src);
                if (strlen($src)>2 && (substr($src, 0, 1) == '/') && ((substr($src, 0, 2) != '//'))) {
                    $src = $domain.$src;
                } elseif ((substr($src, 0, 4) != 'http') && (substr($src, 0, 2) != '//')) {
                    $src = $this->relativeUrl .'/'.$src;
                }

                if ($downloader) {
                    if (strpos($downloader, '?')) {
                        $element->src = $downloader.'&url='.base64_encode($src);
                    } else {
                        $element->src = $downloader.'?url='.base64_encode($src);
                    }
                } else {
                    $element->src = $src;
                }
            }

            // Modify links
            foreach($html->find('a') as $element) {
                $href = trim($element->href);
                if (strlen($href)>2 && (substr($href, 0, 1) == '/') && ((substr($href, 0, 2) != '//'))) {
                    $href = $domain.$href;
                } elseif (substr($href, 0, 4) != 'http') {
                    $href = $this->relativeUrl .'/'.$href;
                }
                $element->href = $href;
            }

            // Replace all styles URL’s
            foreach($html->find('link') as $element) {
                $src = trim($element->href);
                if (strlen($src)>2 && (substr($src, 0, 1) == '/') && ((substr($src, 0, 2) != '//'))) {
                    $src = $domain.$src;
                } elseif ((substr($src, 0, 4) != 'http') && (substr($src, 0, 2) != '//')) {
                    $src = $this->relativeUrl .'/'.$src;
                }
                $element->href = $src;
            }

            // Append our JavaScript and CSS
            $scripts = '<script type="text/javascript" src="'.$this->iwnt_plugin_url( 'admin/js/jquery.js' ).'"></script>';
            $scripts .= '<script type="text/javascript" src="'.$this->iwnt_plugin_url( 'admin/js/extractor.js' ).'?'.time().'"></script>';
            $scripts .= '<script type="text/javascript" src="'.$this->iwnt_plugin_url( 'admin/js/md5.min.js' ).'"></script>';
            $scripts .= '<script type="text/javascript" src="'.$this->iwnt_plugin_url( 'admin/js/tags.js' ).'"></script>';

            $scripts .= '<link rel="stylesheet" type="text/css" href="'.$this->iwnt_plugin_url( 'admin/css/extractor.css' ).'">';

            $html = str_replace('</body>', $scripts.'</body>', $html);

            return $html;
        } catch (PicoBlockException $e) {
            return false;
        }
    }

    public function processContent()
    {
        if ($this->download_images) {
            $this->downloadImages();
        }

        if ($this->download_fonts) {
            $this->downloadFonts();
        }
    }

    public function downloadImages()
    {
        // Download images
        if ($this->images) {
            $images = explode("\n", $this->images);

            foreach ($images as $im) {
                $origSrc = $src = trim($im);

                // parse url and check if it is an internal downloader! then decode and use real SRC
                $parts = parse_url($src);
                if (isset($parts['query']) && $parts['query']) {
                    parse_str($parts['query'], $query);
                    if (isset($query['action']) && ($query['action']=='downloader')) {
                        $src = base64_decode($query['url']);
                    }
                }

                if (substr($src, 0, 2) == '//') {
                    $src = 'http:'. $src;
                }

                // Download to temp folder
                $tmp = \download_url( $src );
                $file_array = array();
                $newSrc = '';

                preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $src, $matches);
                if (isset($matches[0]) && $matches[0]) {
                    $file_array['name'] = basename($matches[0]);
                    $file_array['tmp_name'] = $tmp;
                    if ( $this->is_error( $tmp ) ) {
                        @unlink($file_array['tmp_name']);
                        $file_array['tmp_name'] = '';
                    } else {
                        // do the validation and storage stuff
                        $imageId = \media_handle_sideload( $file_array, $this->id, '');

                        // If error storing permanently, unlink
                        if ( $this->is_error($imageId) ) {
                            @unlink($file_array['tmp_name']);
                        } else {
                            $newSrc = wp_get_attachment_url($imageId);
                            update_post_meta( $imageId, '_iwnt_parent', $this->id );
                        }
                    }
                } else {
                    @unlink($tmp);
                }

                // Replace images url in code
                if ($newSrc) {
                    $this->css = str_replace(htmlentities($origSrc), $newSrc, $this->css);
                    $this->html = str_replace(htmlentities($origSrc), $newSrc, $this->html);
                }

            }
        }
    }

    public function downloadFonts()
    {
        try {
            $fontsCSS = '';
			$html = $this->html;
			
            // Get font declarations
            foreach($html->find('link') as $element) {
                if (($element->rel == 'stylesheet') || ($element->type == 'text/css')) {
                    $src = trim($element->href);
                    if (strlen($src)>2 && (substr($src, 0, 1) == '/') && ((substr($src, 0, 2) != '//'))) {
                        $src = $domain.$src;
                    } elseif (substr($src, 0, 4) != 'http') {
                        $src = $this->relativeUrl .'/'.$src;
                    }

                    $content = $this->remote_get($src);

                    if ( $this->is_error($content) ) {
                        // ToDo: Try other download option?
                    } elseif (isset($content['body']) && $content['body']) {
                        $fontsCSS .= $this->parseFonts($src, $content['body']);
                    }
                }
            }

            // Get inline font declarations
            foreach($html->find('style') as $element) {
                $content = $element->plaintext;
                $src = $this->relativeUrl;
                if ($content) {
                    $fontsCSS .= $this->parseFonts($src, $content);
                }
            }

            $this->css .= $fontsCSS;
        } catch (PicoBlockException $e) {
            return false;
        }
    }

    private function parseFonts($url, $content)
    {
        $fonts = '';

        while (true) {
            $pos = strpos($content, '@font-face');
            if ($pos === false) break;

            $end = strpos($content, '}', $pos);
            if ($end === false) break;

            $font = substr($content, $pos, $end-$pos+1);
            $content = substr($content, $end);

            $urls = $this->getFontUrls($font);
            $font = $this->replaceFontUrls($url, $font, $urls);

            $fonts .= "\n".$font;
        }

        return $fonts;
    }

    private function getFontUrls($content)
    {
        if (preg_match_all("/(?:url(?:\s+|)\((?'url'.*?)\))/x", $content, $match )) {
            if (isset($match['url']) && count($match['url'])) {
                return $match['url'];
            }
        }
        return array();
    }

    private function replaceFontUrls($stylesheetUrl, $content, $urls)
    {
        $parts = parse_url($stylesheetUrl);
        $domain = $parts['scheme'].'://'.$parts['host'];

        if (isset($parts['port']) && $parts['port'] && ($parts['port'] != '80')) {
            $domain .= ':'.$parts['port'];
        }

        // Relative path URL
        $this->relativeUrl = $domain;
        if (isset($parts['path']) && $parts['path']) {
            $pathParts = explode('/', $parts['path']);
            if (count($pathParts)) {
                unset($pathParts[count($pathParts)-1]);
                $this->relativeUrl = $domain.'/'.implode('/',$pathParts);
            }
        }

        $from = array();
        $to = array();

        foreach ($urls as $url) {
            if (($url{0} == '"') || ($url{0} == "'")) {
                $url = substr($url, 1);
            }
            if (($url{strlen($url)-1} == '"') || ($url{strlen($url)-1} == "'")) {
                $url = substr($url, 0, strlen($url)-1);
            }

            // If font is embedded - just leave it as it is
            if (substr($url, 0, 5) == 'data:') {
                continue;
            }

            // If starts with // or http - leave as is
            if ((substr($url, 0, 2) == '//') || (substr($url, 0, 4) == 'http')) {
                $from[] = $url;
                $to[] = $this->canonicalize($url);
                continue;
            }

            // If starts with / - get domain of CSS
            elseif (substr($url, 0, 1) == '/') {
                //$content = str_replace($url, $domain.$url, $content);
                $from[] = $url;
                $to[] = $domain.$url;
                continue;
            }

            // If starts with ../../../ or anything else, append relative URL to CSS file
            else {
                $from[] = $url;
                $to[] = $this->canonicalize($this->relativeUrl.'/'.$url);
                //$content = str_replace($url, $relativeUrl.'/'.$url, $content);
            }
        }

        foreach ($to as $k=>$font) {
            // Download to temp folder
            $tmp = \download_url( $font );
            $file_array = array();
            $newSrc = '';

            $parts = explode('#', $font);
            if (count($parts)>1) {
                $font = $parts[0];
            }

            $parts = explode('/', $font);

            $fontName = isset($parts[count($parts)-1])?$parts[count($parts)-1]:'';

            if ($fontName) {
                $file_array['name'] = $fontName;
                $file_array['tmp_name'] = $tmp;

                if ( $this->is_error( $tmp ) ) {
                    @unlink($file_array['tmp_name']);
                    $file_array['tmp_name'] = '';
                } else {
                    // do the validation and storage stuff
                    $imageId = \media_handle_sideload( $file_array, $this->id, '');

                    // If error storing permanently, unlink
                    if ( $this->is_error($imageId) ) {
                        @unlink($file_array['tmp_name']);
                    } else {
                        $newSrc = wp_get_attachment_url($imageId);
                        if (!$this->id) {
                            update_post_meta( $imageId, '_is_temp', 1 );
                        } else {
                            update_post_meta( $imageId, '_iwnt_parent', $this->id );
                        }
                    }
                }
            } else {
                @unlink($tmp);
            }

            // Replace font urls
            if ($newSrc) {
                $to[$k] = $newSrc;
            }
        }

        $content = str_replace($from, $to, $content);

        return $content;
    }

    public function addCustomMimeTypes($existing_mimes)
    {
        $existing_mimes['woff'] = 'application/font-woff';
        $existing_mimes['ttf'] = 'application/font-ttf';
        $existing_mimes['eot'] = 'application/vnd.ms-fontobject';
        $existing_mimes['otf'] = 'application/font-otf';
        $existing_mimes['svg'] = 'image/svg+xml';

        return $existing_mimes;
    }

    private function canonicalize($address)
    {
        $address = explode('/', $address);
        $keys = array_keys($address, '..');

        foreach($keys AS $keypos => $key)
        {
            array_splice($address, $key - ($keypos * 2 + 1), 2);
        }

        $address = implode('/', $address);
        $address = str_replace('./', '', $address);

        return $address;
    }
}

?>