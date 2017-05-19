<?php namespace App\Http\Controllers;
use Illuminate\Contracts\Filesystem\Filesystem;
use NodejsPhpFallback\Uglify;
use Illuminate\Support\Facades\Route;

  use Session;
  use Request;
  use DB;
  use CRUDBooster;

  class AdminBouncerController extends \crocodicstudio\crudbooster\controllers\CBController {

      public function cbInit() {

      # START CONFIGURATION DO NOT REMOVE THIS LINE
      $this->title_field = "id";
      $this->limit = "20";
      $this->orderby = "id,desc";
      $this->global_privilege = false;
      $this->button_table_action = true;
      $this->button_action_style = "button_icon";
      $this->button_add = true;
      $this->button_edit = true;
      $this->button_delete = true;
      $this->button_detail = true;
      $this->button_show = true;
      $this->button_filter = true;
      $this->button_import = false;
      $this->button_export = false;
      $this->table = "bouncer.bouncer";
      # END CONFIGURATION DO NOT REMOVE THIS LINE

      # START COLUMNS DO NOT REMOVE THIS LINE
      $this->col = [];
      $this->col[] = ["label"=>"Name","name"=>"name"];
      $this->col[] = ["label"=>"Js Url","name"=>"js_url"];
      $this->col[] = ["label"=>"Company","name"=>"company_id","join"=>"bouncer.company,name"];
      # END COLUMNS DO NOT REMOVE THIS LINE

      # START FORM DO NOT REMOVE THIS LINE
      $this->form = [];
      $this->form[] = array (
  'style' => '',
  'help' => '',
  'placeholder' => '',
  'readonly' => '',
  'disabled' => '',
  'label' => 'Name',
  'name' => 'name',
  'type' => 'text',
  'validation' => 'required',
  'width' => 'col-sm-10',
);
      $this->form[] = array (
  'style' => '',
  'help' => '',
  'placeholder' => '',
  'readonly' => 'true',
  'disabled' => '',
  'label' => 'Js Url',
  'name' => 'js_url',
  'type' => 'text',
  'validation' => 'url|min:3|max:255',
  'width' => 'col-sm-10',
);
 $this->form[] = array (
  'dataenum' => '',
  'datatable' => 'bouncer.company,name',
  'style' => '',
  'help' => '',
  'datatable_where' => '',
  'datatable_format' => '',
  'datatable_exception' => '',
  'minimumInputLength' => '',
  'label' => 'Company',
  'name' => 'company_id',
  'type' => 'select2',
  'validation' => 'required|integer|min:0',
  'width' => 'col-sm-10',
);

      $this->form[] = array (
  'placeholder' => '',
  'schema' => '{
  "title" : "Rules",
  "type": "array",
  "format": "table_row",
  "items" : {
    "title": "Rule",
    "type": "object",
    "properties": {
      "events" : {
        "type": "object",
        "title": "Events",
        "properties": {
          "onOutsideWindow" : {
            "title": "When the cursor is outside the window",
            "type": "boolean",
            "format": "checkbox"
          },
          "onLoad" : {
            "title": "On load",
            "type": "boolean",
            "format": "checkbox"
          },
          "onClickButton" : {
            "title": "On click button",
            "type": "boolean",
            "format": "checkbox"
          }
        }
      },
      "restriction" : {
        "title": "Restriction",
        "type": "object",
        "properties": {
          "referrer" : {
            "title": "Restriction based on referrer",
            "properties": {
              "referrer_type" : {
              "title": "Referrer type will include or exclude the the referrer below",
              "type": "string",
              "enum": [
                "onlyIf",
                "exclude"
              ]
            },
            "referrers": {
              "type" : "array", 
              "format": "table",
              "items" : {
                "type": "object",
                "properties": {
                  "method" : {
                    "title": "Method",
                    "type": "string",
                    "enum": [
                      "content",
                      "strict",
                      "startWith",
                      "endWith",
                      "dontContent"
                    ]
                  },
                  "referrer" : {
                    "title": "Referrer url",
                    "type": "string"
                  }
                }
              }
            }
            }
          },
          "url" : {
            "title": "Restriction based on url tags (?vca=123)",
            "type": "object",
            "properties" : {
              "tags_type" : {
                "title": "Tags type : if one of tags or all of tags are considerate as inclusive",
                "type": "string",
                "enum": [
                  "oneOfThem",
                  "allOfThem"
                ]
              },
              "tags" : {
                "type" : "array", 
                "format": "table",
                "items" : {
                  "properties": {
                    "tag" : {
                      "title": "Tag (vca)",
                      "type": "string"
                    },
                    "value" : {
                      "title": "Value (123)",
                      "type": "string"
                    },
                    "method" : {
                    "title": "Method",
                    "type": "string",
                    "enum": [
                      "content",
                      "strict",
                      "startWith",
                      "endWith",
                      "dontContent"
                    ]
                  }
                  }
                }
              }
            }
          },
          "dom" : {
            "title": "Restriction based on javascript dom",
            "type": "object",
            "properties" : {
              "doms_type" : {
                "title": "Doms type : if one of doms or all of doms are considerate as inclusive",
                "type": "string",
                "enum": [
                  "oneOfThem",
                  "allOfThem"
                ]
              },
              "doms" : {
                "type" : "array", 
                "format": "table",
                "items" : {
                  "type": "object",
                  "properties": {
                    "dom" : {
                      "type": "string"
                    },
                    "contentFrom" : {
                      "type": "string",
                      "enum": [
                        "html",
                        "text"
                      ]
                    },
                    "value": {
                      "type":"string"
                    },
                    "method" : {
                      "type": "string",
                      "enum": [
                        "content",
                        "strict",
                        "startWith",
                        "endWith",
                        "dontContent"
                      ]
                    }
                  }
                }
              }
            }
          },
          "isMobile" : {
            "title": "Only Mobile",
            "type": "boolean",
            "format": "checkbox",
            "default": true
          },
          "isTablet" : {
            "title": "Only Tablet",
            "type": "boolean",
            "format": "checkbox",
            "default": true
          },
          "isDesktop" : {
            "title": "Only Desktop",
            "type": "boolean",
            "format": "checkbox",
            "default": true
          },
          "languages" : {
            "title": "Languages (Only for : FR,EN,ES)",
            "type": "string"
          }
        }
      },
      "targeting" : {
        "title": "Data Targeting",
        "type": "object",
        "properties": {
          "product" : {
            "title": "Product (Automatic mode or Manual with css query)",
            "properties": {
              "type" : {
                "title" : "Mode",
                "type": "string",
                "enum": [
                  "auto",
                  "manual"
                ]
              },
              "query": {
                "title" : "CSS Query",
                "type": "string"
              }
            }
          },
          "price" : {
            "title": "Price (Automatic mode or Manual with css query)",
            "properties": {
              "type" : {
                "title" : "Mode",
                "type": "string",
                "enum": [
                  "auto",
                  "manual"
                ]
              },
              "query": {
                "title" : "CSS Query",
                "type": "string"
              }
            }
          },
          "category" : {
            "title": "Category (Automatic mode or Manual with css query)",
            "properties": {
              "type" : {
                "title" : "Mode",
                "type": "string",
                "enum": [
                  "auto",
                  "manual"
                ]
              },
              "query": {
                "title" : "CSS Query",
                "type": "string"
              }
            }
          }
        }
      },
      "displayButton" : {
        "type": "object",
        "title": "Display Button",
        "properties": {
          "title": {
            "title" : "Title",
            "type": "string"
          },
          "color" : {
            "title": "Color",
            "type": "string",
            "format" : "color"
          },
          "fontColor" : {
            "title": "Font color",
            "type": "string",
            "format" : "color"
          },
          "icon" : {
            "title": "FontAwsome Icon",
            "type": "string",
            "enum" : ["glass", "music", "search", "envelope-o", "heart", "star", "star-o", "user", "film", "th-large", "th", "th-list", "check", "remove", "close", "times", "search-plus", "search-minus", "power-off", "signal", "gear", "cog", "trash-o", "home", "file-o", "clock-o", "road", "download", "arrow-circle-o-down", "arrow-circle-o-up", "inbox", "play-circle-o", "rotate-right", "repeat", "refresh", "list-alt", "lock", "flag", "headphones", "volume-off", "volume-down", "volume-up", "qrcode", "barcode", "tag", "tags", "book", "bookmark", "print", "camera", "font", "bold", "italic", "text-height", "text-width", "align-left", "align-center", "align-right", "align-justify", "list", "dedent", "outdent", "indent", "video-camera", "photo", "image", "picture-o", "pencil", "map-marker", "adjust", "tint", "edit", "pencil-square-o", "share-square-o", "check-square-o", "arrows", "step-backward", "fast-backward", "backward", "play", "pause", "stop", "forward", "fast-forward", "step-forward", "eject", "chevron-left", "chevron-right", "plus-circle", "minus-circle", "times-circle", "check-circle", "question-circle", "info-circle", "crosshairs", "times-circle-o", "check-circle-o", "ban", "arrow-left", "arrow-right", "arrow-up", "arrow-down", "mail-forward", "share", "expand", "compress", "plus", "minus", "asterisk", "exclamation-circle", "gift", "leaf", "fire", "eye", "eye-slash", "warning", "exclamation-triangle", "plane", "calendar", "random", "comment", "magnet", "chevron-up", "chevron-down", "retweet", "shopping-cart", "folder", "folder-open", "arrows-v", "arrows-h", "bar-chart-o", "bar-chart", "twitter-square", "facebook-square", "camera-retro", "key", "gears", "cogs", "comments", "thumbs-o-up", "thumbs-o-down", "star-half", "heart-o", "sign-out", "linkedin-square", "thumb-tack", "external-link", "sign-in", "trophy", "github-square", "upload", "lemon-o", "phone", "square-o", "bookmark-o", "phone-square", "twitter", "facebook-f", "facebook", "github", "unlock", "credit-card", "feed", "rss", "hdd-o", "bullhorn", "bell", "certificate", "hand-o-right", "hand-o-left", "hand-o-up", "hand-o-down", "arrow-circle-left", "arrow-circle-right", "arrow-circle-up", "arrow-circle-down", "globe", "wrench", "tasks", "filter", "briefcase", "arrows-alt", "group", "users", "chain", "link", "cloud", "flask", "cut", "scissors", "copy", "files-o", "paperclip", "save", "floppy-o", "square", "navicon", "reorder", "bars", "list-ul", "list-ol", "strikethrough", "underline", "table", "magic", "truck", "pinterest", "pinterest-square", "google-plus-square", "google-plus", "money", "caret-down", "caret-up", "caret-left", "caret-right", "columns", "unsorted", "sort", "sort-down", "sort-desc", "sort-up", "sort-asc", "envelope", "linkedin", "rotate-left", "undo", "legal", "gavel", "dashboard", "tachometer", "comment-o", "comments-o", "flash", "bolt", "sitemap", "umbrella", "paste", "clipboard", "lightbulb-o", "exchange", "cloud-download", "cloud-upload", "user-md", "stethoscope", "suitcase", "bell-o", "coffee", "cutlery", "file-text-o", "building-o", "hospital-o", "ambulance", "medkit", "fighter-jet", "beer", "h-square", "plus-square", "angle-double-left", "angle-double-right", "angle-double-up", "angle-double-down", "angle-left", "angle-right", "angle-up", "angle-down", "desktop", "laptop", "tablet", "mobile-phone", "mobile", "circle-o", "quote-left", "quote-right", "spinner", "circle", "mail-reply", "reply", "github-alt", "folder-o", "folder-open-o", "smile-o", "frown-o", "meh-o", "gamepad", "keyboard-o", "flag-o", "flag-checkered", "terminal", "code", "mail-reply-all", "reply-all", "star-half-empty", "star-half-full", "star-half-o", "location-arrow", "crop", "code-fork", "unlink", "chain-broken", "question", "info", "exclamation", "superscript", "subscript", "eraser", "puzzle-piece", "microphone", "microphone-slash", "shield", "calendar-o", "fire-extinguisher", "rocket", "maxcdn", "chevron-circle-left", "chevron-circle-right", "chevron-circle-up", "chevron-circle-down", "html5", "css3", "anchor", "unlock-alt", "bullseye", "ellipsis-h", "ellipsis-v", "rss-square", "play-circle", "ticket", "minus-square", "minus-square-o", "level-up", "level-down", "check-square", "pencil-square", "external-link-square", "share-square", "compass", "toggle-down", "caret-square-o-down", "toggle-up", "caret-square-o-up", "toggle-right", "caret-square-o-right", "euro", "eur", "gbp", "dollar", "usd", "rupee", "inr", "cny", "rmb", "yen", "jpy", "ruble", "rouble", "rub", "won", "krw", "bitcoin", "btc", "file", "file-text", "sort-alpha-asc", "sort-alpha-desc", "sort-amount-asc", "sort-amount-desc", "sort-numeric-asc", "sort-numeric-desc", "thumbs-up", "thumbs-down", "youtube-square", "youtube", "xing", "xing-square", "youtube-play", "dropbox", "stack-overflow", "instagram", "flickr", "adn", "bitbucket", "bitbucket-square", "tumblr", "tumblr-square", "long-arrow-down", "long-arrow-up", "long-arrow-left", "long-arrow-right", "apple", "windows", "android", "linux", "dribbble", "skype", "foursquare", "trello", "female", "male", "gittip", "gratipay", "sun-o", "moon-o", "archive", "bug", "vk", "weibo", "renren", "pagelines", "stack-exchange", "arrow-circle-o-right", "arrow-circle-o-left", "toggle-left", "caret-square-o-left", "dot-circle-o", "wheelchair", "vimeo-square", "turkish-lira", "try", "plus-square-o", "space-shuttle", "slack", "envelope-square", "wordpress", "openid", "institution", "bank", "university", "mortar-board", "graduation-cap", "yahoo", "google", "reddit", "reddit-square", "stumbleupon-circle", "stumbleupon", "delicious", "digg", "pied-piper", "pied-piper-alt", "drupal", "joomla", "language", "fax", "building", "child", "paw", "spoon", "cube", "cubes", "behance", "behance-square", "steam", "steam-square", "recycle", "automobile", "car", "cab", "taxi", "tree", "spotify", "deviantart", "soundcloud", "database", "file-pdf-o", "file-word-o", "file-excel-o", "file-powerpoint-o", "file-photo-o", "file-picture-o", "file-image-o", "file-zip-o", "file-archive-o", "file-sound-o", "file-audio-o", "file-movie-o", "file-video-o", "file-code-o", "vine", "codepen", "jsfiddle", "life-bouy", "life-buoy", "life-saver", "support", "life-ring", "circle-o-notch", "ra", "rebel", "ge", "empire", "git-square", "git", "y-combinator-square", "yc-square", "hacker-news", "tencent-weibo", "qq", "wechat", "weixin", "send", "paper-plane", "send-o", "paper-plane-o", "history", "circle-thin", "header", "paragraph", "sliders", "share-alt", "share-alt-square", "bomb", "soccer-ball-o", "futbol-o", "tty", "binoculars", "plug", "slideshare", "twitch", "yelp", "newspaper-o", "wifi", "calculator", "paypal", "google-wallet", "cc-visa", "cc-mastercard", "cc-discover", "cc-amex", "cc-paypal", "cc-stripe", "bell-slash", "bell-slash-o", "trash", "copyright", "at", "eyedropper", "paint-brush", "birthday-cake", "area-chart", "pie-chart", "line-chart", "lastfm", "lastfm-square", "toggle-off", "toggle-on", "bicycle", "bus", "ioxhost", "angellist", "cc", "shekel", "sheqel", "ils", "meanpath", "buysellads", "connectdevelop", "dashcube", "forumbee", "leanpub", "sellsy", "shirtsinbulk", "simplybuilt", "skyatlas", "cart-plus", "cart-arrow-down", "diamond", "ship", "user-secret", "motorcycle", "street-view", "heartbeat", "venus", "mars", "mercury", "intersex", "transgender", "transgender-alt", "venus-double", "mars-double", "venus-mars", "mars-stroke", "mars-stroke-v", "mars-stroke-h", "neuter", "genderless", "facebook-official", "pinterest-p", "whatsapp", "server", "user-plus", "user-times", "hotel", "bed", "viacoin", "train", "subway", "medium", "yc", "y-combinator", "optin-monster", "opencart", "expeditedssl", "battery-4", "battery-full", "battery-3", "battery-three-quarters", "battery-2", "battery-half", "battery-1", "battery-quarter", "battery-0", "battery-empty", "mouse-pointer", "i-cursor", "object-group", "object-ungroup", "sticky-note", "sticky-note-o", "cc-jcb", "cc-diners-club", "clone", "balance-scale", "hourglass-o", "hourglass-1", "hourglass-start", "hourglass-2", "hourglass-half", "hourglass-3", "hourglass-end", "hourglass", "hand-grab-o", "hand-rock-o", "hand-stop-o", "hand-paper-o", "hand-scissors-o", "hand-lizard-o", "hand-spock-o", "hand-pointer-o", "hand-peace-o", "trademark", "registered", "creative-commons", "gg", "gg-circle", "tripadvisor", "odnoklassniki", "odnoklassniki-square", "get-pocket", "wikipedia-w", "safari", "chrome", "firefox", "opera", "internet-explorer", "tv", "television", "contao", "500px", "amazon", "calendar-plus-o", "calendar-minus-o", "calendar-times-o", "calendar-check-o", "industry", "map-pin", "map-signs", "map-o", "map", "commenting", "commenting-o", "houzz", "vimeo", "black-tie", "fonticons", "reddit-alien", "edge", "credit-card-alt", "codiepie", "modx", "fort-awesome", "usb", "product-hunt", "mixcloud", "scribd", "pause-circle", "pause-circle-o", "stop-circle", "stop-circle-o", "shopping-bag", "shopping-basket", "hashtag", "bluetooth", "bluetooth-b", "percent"]
          },
          "placement": {
            "title" : "Placement",
            "type": "string",
            "enum" : [
              "topRight",
              "topLeft",
              "bottomRight",
              "bottomLeft"
            ]
          },
          "class" : {
            "title": "Css class",
            "type": "string"
          }
        }
      },
      "actions" : {
        "type": "object",
        "title": "Actions",
        "properties": {
          "addUrlInHistory" : {
            "title": "Bounce (add url in history)",
            "type": "object",
            "properties": {
              "activate" : {
                "title": "Active",
                "type": "boolean",
                "format": "checkbox"
              },
              "url" : {
                "type": "string"
              },
              "title": {
                "type": "string"
              }
            }
          },
          "addNotification" : {
            "type": "object",
            "title": "Notification",
            "properties": {
              "activate" : {
                "title": "Active",
                "type": "boolean",
                "format": "checkbox"
              },
              "serviceWorker": {
                "title": "Service Worker Url",
                "type": "string"
              }
            }
          },
          "displayPopin" : {
            "title": "Display popin",
            "type": "object",
            "properties": {
              "activate" : {
                "title": "Active",
                "type": "boolean",
                "format": "checkbox"
              },
              "title" : {
                "title": "Content",
                "type": "string",
                "format": "html",
                "options": {
                  "wysiwyg": true
                }
              },
              "class" : {
                "title": "Css class",
                "type": "string"
              }
            }
          }
        }
      },
      "style" : {
        "type": "object",
        "title": "Style",
        "properties": {
          "value": {
            "title" : "Css",
            "type":"string",
            "format": "textarea",
            "options": {
              "input_height" : "200px"
            }
          }
        }
      }
    }
  }
}',
  'label' => 'Rules',
  'name' => 'json',
  'type' => 'json',
  'validation' => 'required|string|min:5',
  'width' => 'col-sm-10',
);
      # END FORM DO NOT REMOVE THIS LINE

/* 
          | ---------------------------------------------------------------------- 
          | Sub Module
          | ----------------------------------------------------------------------     
      | @label          = Label of action 
      | @path           = Path of sub module
      | @button_color   = Bootstrap Class (primary,success,warning,danger)
      | @button_icon    = Font Awesome Class  
      | @parent_columns = Sparate with comma, e.g : name,created_at
          | 
          */
          $this->sub_module = array();


          /* 
          | ---------------------------------------------------------------------- 
          | Add More Action Button / Menu
          | ----------------------------------------------------------------------     
          | @label       = Label of action 
          | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
          | @icon        = Font awesome class icon. e.g : fa fa-bars
          | @color     = Default is primary. (primary, warning, succecss, info)     
          | @showIf      = If condition when action show. Use field alias. e.g : [id] == 1
          | 
          */
          $this->addaction = array();


          /* 
          | ---------------------------------------------------------------------- 
          | Add More Button Selected
          | ----------------------------------------------------------------------     
          | @label       = Label of action 
          | @icon      = Icon from fontawesome
          | @name      = Name of button 
          | Then about the action, you should code at actionButtonSelected method 
          | 
          */
          $this->button_selected = array();

                  
          /* 
          | ---------------------------------------------------------------------- 
          | Add alert message to this module at overheader
          | ----------------------------------------------------------------------     
          | @message = Text of message 
          | @type    = warning,success,danger,info        
          | 
          */
          $this->alert        = array();
                  

          
          /* 
          | ---------------------------------------------------------------------- 
          | Add more button to header button 
          | ----------------------------------------------------------------------     
          | @label = Name of button 
          | @url   = URL Target
          | @icon  = Icon from Awesome.
          | 
          */
          $this->index_button = array();



          /* 
          | ---------------------------------------------------------------------- 
          | Customize Table Row Color
          | ----------------------------------------------------------------------     
          | @condition = If condition. You may use field alias. E.g : [id] == 1
          | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
          | 
          */
          $this->table_row_color = array();                 

          
          /*
          | ---------------------------------------------------------------------- 
          | You may use this bellow array to add statistic at dashboard 
          | ---------------------------------------------------------------------- 
          | @label, @count, @icon, @color 
          |
          */
          $this->index_statistic = array();



          /*
          | ---------------------------------------------------------------------- 
          | Add javascript at body 
          | ---------------------------------------------------------------------- 
          | javascript code in the variable 
          | $this->script_js = "function() { ... }";
          |
          */
          $this->script_js = NULL;



          /*
          | ---------------------------------------------------------------------- 
          | Include Javascript File 
          | ---------------------------------------------------------------------- 
          | URL of your javascript each array 
          | $this->load_js[] = asset("myfile.js");
          |
          */
          $this->load_js = array();
      }


      /*
      | ---------------------------------------------------------------------- 
      | Hook for button selected
      | ---------------------------------------------------------------------- 
      | @id_selected = the id selected
      | @button_name = the name of button
      |
      */
      public function actionButtonSelected($id_selected,$button_name) {
          //Your code here
              
      }


      /*
      | ---------------------------------------------------------------------- 
      | Hook for manipulate query of index result 
      | ---------------------------------------------------------------------- 
      | @query = current sql query 
      |
      */
      public function hook_query_index(&$query) {
          //Your code here
              
      }

      /*
      | ---------------------------------------------------------------------- 
      | Hook for manipulate row of index table html 
      | ---------------------------------------------------------------------- 
      |
      */    
      public function hook_row_index($column_index,&$column_value) {          
        //Your code here
      }

      /*
      | ---------------------------------------------------------------------- 
      | Hook for manipulate data input before add data is execute
      | ---------------------------------------------------------------------- 
      | @arr
      |
      */
      public function hook_before_add(&$postdata) {
          /*if(empty($postdata['city_id'])) {
                $postdata['city_id'] = null;
            }
            if(empty($postdata['city_lat'])) {
                $postdata['city_lat'] = 0;
            }
            if(empty($postdata['city_lng'])) {
                $postdata['city_lng'] = 0;
            }*/

            //$postdata['published_at'] = date('Y-m-d H:i:s');
            //$postdata['ended_at'] = date('Y-m-d H:i:s');


            $json = $postdata['json'];
            $company = $postdata['company_id'];
            $this->make($json, $company, uniqid(rand(), true));
      }

      /* 
      | ---------------------------------------------------------------------- 
      | Hook for execute command after add public static function called 
      | ---------------------------------------------------------------------- 
      | @id = last insert id
      | 
      */
      public function hook_after_add($id) {        
          //Your code here

      }

      /* 
      | ---------------------------------------------------------------------- 
      | Hook for manipulate data input before update data is execute
      | ---------------------------------------------------------------------- 
      | @postdata = input post data 
      | @id       = current id 
      | 
      */
      public function hook_before_edit(&$postdata,$id) {        
          //Your code here
          date_default_timezone_set('UTC');
          $url = $postdata['js_url'];
          $json = $postdata['json'];
          $company = $postdata['company_id'];

          $postdata['js_url'] = $this->make($json, $company, $url);
      }

      public static function rebuiltAllFile() {
        $t = new AdminBouncerController();

        date_default_timezone_set('UTC');

        $datas = DB::table('bouncer')->get();
        foreach($datas as $data) {
          $url = $data->js_url;
          $json = $data->json;
          $company = $data->company_id;

          $url = $t->make($json, $company, $url);
          echo "done : ".$url."\n";
        }

      }

      public function make($json, $company, $url) {
          $data = implode("\r\n", file(__DIR__."/../../../source/bouncer.js"));

          \Config::set('filesystems.disks.s3.bucket', env('S3_BUCKET')); 
          \Config::set('filesystems.disks.s3.region', env('S3_REGION')); 
          \Config::set('filesystems.disks.s3.key', env('S3_KEY')); 
          \Config::set('filesystems.disks.s3.secret', env('S3_SECRET')); 

          $s3 = \Storage::disk('s3');
          if(empty($url)) {
            $url = uniqid();
          }
          $uniqid = basename($url, '.js');
          $filePath = $uniqid.".js";
          $data = str_replace('{!! data !!}', $json, $data);
          $data = str_replace('{!! debug !!}', 'true', $data);
          $data = str_replace('{!! saveinfoUrl !!}', env('SAVEINFO_URL'), $data);
          $data = str_replace('{!! configNotification !!}', json_encode(array(
            "apiKey" => env('FIREBASE_APIKEY'),
            "authDomain" => env('FIREBASE_AUTHDOMAIN'),
            "databaseURL" => env('FIREBASE_DATABASEURL'),
            "storageBucket" => env('FIREBASE_STORAGEBUCKET'),
            "messagingSenderId" => env('FIREBASE_MESSAGINGSENDERID'))), $data);

          $data = str_replace('{!! key !!}', $uniqid, $data);

          // Fill These In!
          //define('S3_BUCKET', 'chevroux-fr');
          define('S3_KEY',    env('S3_KEY'));
          define('S3_SECRET', env('S3_SECRET'));
          define('S3_REGION', 'eu-west-1');        // S3 region name: http://amzn.to/1FtPG6r
          define('S3_ACL',    'private'); // File permissions: http://amzn.to/18s9Gv7
          // Stop Here

          $algorithm = "AWS4-HMAC-SHA256";
          $service = "s3";
          $date = gmdate('Ymd\THis\Z');
          $shortDate = gmdate('Ymd');
          $requestType = "aws4_request";
          $expires = '86400'; // 24 Hours
          $successStatus = '201';

          $scope = [
              S3_KEY,
              $shortDate,
              S3_REGION,
              $service,
              $requestType
          ];
          $credentials = implode('/', $scope);

          $policy = [
              'expiration' => gmdate('Y-m-d\TG:i:s\Z', strtotime('+6 days')),
              'conditions' => [
                  /*['bucket' => S3_BUCKET],*/
                  [
                      'starts-with',
                      '$bucket',
                      ''
                  ],
                  ['acl' => S3_ACL],
                  [
                      'starts-with',
                      '$key',
                      ''
                  ],
                  ['success_action_status' => $successStatus],
                  ['x-amz-credential' => $credentials],
                  ['x-amz-algorithm' => $algorithm],
                  ['x-amz-date' => $date],
                  ['x-amz-expires' => $expires],
              ]
          ];
          $base64Policy = base64_encode(json_encode($policy));

          // Signing Keys
          $dateKey = hash_hmac('sha256', $shortDate, 'AWS4' . S3_SECRET, true);
          $dateRegionKey = hash_hmac('sha256', S3_REGION, $dateKey, true);
          $dateRegionServiceKey = hash_hmac('sha256', $service, $dateRegionKey, true);
          $signingKey = hash_hmac('sha256', $requestType, $dateRegionServiceKey, true);

          // Signature
          $signature = hash_hmac('sha256', $base64Policy, $signingKey);

          $data = str_replace('{!! awssignaure !!}', $signature, $data);
          $data = str_replace('{!! awspolicy !!}', $base64Policy, $data);
          $data = str_replace('{!! awscredential !!}', $credentials, $data);
          $data = str_replace('{!! awsdate !!}', $date, $data);
          $data = str_replace('{!! awsexpires !!}', $expires, $data);
          $data = str_replace('{!! buckets !!}', json_encode(array('chevroux-fr', 'kfc-fr')), $data);


          $execptions = DB::table("company")->where("id", $company)->first();
          $data = str_replace('{!! execption_directory !!}', json_encode(explode("\n",$execptions->widget_directory_execption)), $data);


          //die($data);
          file_put_contents($filePath, $data);


          $uglify = new Uglify(array(
            $filePath
          ));

          $s3->put($filePath, $uglify->getMinifiedJs(), 'public');

          $url = \Storage::cloud()->url($filePath);

          unlink($filePath);
          return $url;
      }

      /* 
      | ---------------------------------------------------------------------- 
      | Hook for execute command after edit public static function called
      | ----------------------------------------------------------------------     
      | @id       = current id 
      | 
      */
      public function hook_after_edit($id) {
          //Your code here 
          
      }

      /* 
      | ---------------------------------------------------------------------- 
      | Hook for execute command before delete public static function called
      | ----------------------------------------------------------------------     
      | @id       = current id 
      | 
      */
      public function hook_before_delete($id) {
          //Your code here

      }

      /* 
      | ---------------------------------------------------------------------- 
      | Hook for execute command after delete public static function called
      | ----------------------------------------------------------------------     
      | @id       = current id 
      | 
      */
      public function hook_after_delete($id) {
          //Your code here

      }

      public function getEdit($id){
        $this->cbLoader();
        $row             = DB::table($this->table)->where($this->primary_key,$id)->first();

        if(!CRUDBooster::isRead() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {
          CRUDBooster::insertLog(trans("crudbooster.log_try_edit",['name'=>$row->{$this->title_field},'module'=>CRUDBooster::getCurrentModule()->name]));
          CRUDBooster::redirect(CRUDBooster::adminPath(),trans('crudbooster.denied_access'));
        }


        $page_menu       = Route::getCurrentRoute()->getActionName();
        $page_title    = trans("crudbooster.edit_data_page_title",['module'=>CRUDBooster::getCurrentModule()->name,'name'=>$row->{$this->title_field}]);
        $command     = 'edit';
        Session::put('current_row_id',$id);
        return view('editBouncer',compact('id','row','page_menu','page_title','command'));
      }



      //By the way, you can still create your own method in here... :) 




  }
