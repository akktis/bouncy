(function() {
    var data = {!! data !!};
    var key = '{!! key !!}';

	var bouncer = function(data, key, debug) { 
        this.key = key;
        this.init(data);
        this.DEBUG = debug;
        this.buckets = {!! buckets !!};
        this.currentBucket = this.buckets[0];
        this.dblk = false;
        this.dablk();
    };

	bouncer.prototype.log = function() {
		if(this.DEBUG) {
			try {
				console.log.apply(this, arguments);
			} catch(e) {}
		}
	};

	bouncer.prototype.parseUri = function(href) {
		var l = document.createElement("a");
		l.href = href;
		return l;
	};

	bouncer.prototype.getQuery = function(query, variable) {
		query = query.substring(1);
		var vars = query.split('&');
		for (var i = 0, l = vars.length; i < l; i++) {
			var pair = vars[i].split('=');
			if (decodeURIComponent(pair[0]) == variable) {
				return decodeURIComponent(pair[1]);
			}
		}
		return false;
	};

	bouncer.prototype.addEvent = function(obj, evt, fn, scope) {
		if(scope != undefined) {
			fn = fn.bind(scope);
		}
		if (obj.addEventListener) {
			obj.addEventListener(evt, fn, false);
		}
		else if (obj.attachEvent) {
			obj.attachEvent("on" + evt, fn);
		}
	};

	bouncer.prototype.ready = function(fn, scope) {
		if (document.readyState === "complete") {
			fn.call(scope)
		} else {
			this.addEvent(window, "load", fn.bind(scope));
		}
	};

	bouncer.prototype.content = function(txt, search) {
		return (txt ? txt.toLowerCase().indexOf(search.toLowerCase()) > -1 : false);
	};

	bouncer.prototype.strict = function(txt, search) {
		return (txt ? txt.toLowerCase() == search.toLowerCase() : false);
	};

	bouncer.prototype.startWith = function(txt, search) {
		return (txt ? txt.toLowerCase().indexOf(search.toLowerCase()) == 0 : false);
	};

	bouncer.prototype.endWith = function(txt, search) {
		return (txt ? txt.toLowerCase().length == txt.lastIndexOf(search.toLowerCase()) + search.length : false);
	};

	bouncer.prototype.dontContent = function(txt, search) {
		return (txt ? txt.toLowerCase().indexOf(search.toLowerCase()) == -1 : false);
	};

	bouncer.prototype.mobileDetectRules = {
		"phones": {
			"iPhone": "\\biPhone\\b|\\biPod\\b",
			"BlackBerry": "BlackBerry|\\bBB10\\b|rim[0-9]+",
			"HTC": "HTC|HTC.*(Sensation|Evo|Vision|Explorer|6800|8100|8900|A7272|S510e|C110e|Legend|Desire|T8282)|APX515CKT|Qtek9090|APA9292KT|HD_mini|Sensation.*Z710e|PG86100|Z715e|Desire.*(A8181|HD)|ADR6200|ADR6400L|ADR6425|001HT|Inspire 4G|Android.*\\bEVO\\b|T-Mobile G1|Z520m",
			"Nexus": "Nexus One|Nexus S|Galaxy.*Nexus|Android.*Nexus.*Mobile|Nexus 4|Nexus 5|Nexus 6",
			"Dell": "Dell.*Streak|Dell.*Aero|Dell.*Venue|DELL.*Venue Pro|Dell Flash|Dell Smoke|Dell Mini 3iX|XCD28|XCD35|\\b001DL\\b|\\b101DL\\b|\\bGS01\\b",
			"Motorola": "Motorola|DROIDX|DROID BIONIC|\\bDroid\\b.*Build|Android.*Xoom|HRI39|MOT-|A1260|A1680|A555|A853|A855|A953|A955|A956|Motorola.*ELECTRIFY|Motorola.*i1|i867|i940|MB200|MB300|MB501|MB502|MB508|MB511|MB520|MB525|MB526|MB611|MB612|MB632|MB810|MB855|MB860|MB861|MB865|MB870|ME501|ME502|ME511|ME525|ME600|ME632|ME722|ME811|ME860|ME863|ME865|MT620|MT710|MT716|MT720|MT810|MT870|MT917|Motorola.*TITANIUM|WX435|WX445|XT300|XT301|XT311|XT316|XT317|XT319|XT320|XT390|XT502|XT530|XT531|XT532|XT535|XT603|XT610|XT611|XT615|XT681|XT701|XT702|XT711|XT720|XT800|XT806|XT860|XT862|XT875|XT882|XT883|XT894|XT901|XT907|XT909|XT910|XT912|XT928|XT926|XT915|XT919|XT925|XT1021|\\bMoto E\\b",
			"Samsung": "\\bSamsung\\b|SM-G9250|GT-19300|SGH-I337|BGT-S5230|GT-B2100|GT-B2700|GT-B2710|GT-B3210|GT-B3310|GT-B3410|GT-B3730|GT-B3740|GT-B5510|GT-B5512|GT-B5722|GT-B6520|GT-B7300|GT-B7320|GT-B7330|GT-B7350|GT-B7510|GT-B7722|GT-B7800|GT-C3010|GT-C3011|GT-C3060|GT-C3200|GT-C3212|GT-C3212I|GT-C3262|GT-C3222|GT-C3300|GT-C3300K|GT-C3303|GT-C3303K|GT-C3310|GT-C3322|GT-C3330|GT-C3350|GT-C3500|GT-C3510|GT-C3530|GT-C3630|GT-C3780|GT-C5010|GT-C5212|GT-C6620|GT-C6625|GT-C6712|GT-E1050|GT-E1070|GT-E1075|GT-E1080|GT-E1081|GT-E1085|GT-E1087|GT-E1100|GT-E1107|GT-E1110|GT-E1120|GT-E1125|GT-E1130|GT-E1160|GT-E1170|GT-E1175|GT-E1180|GT-E1182|GT-E1200|GT-E1210|GT-E1225|GT-E1230|GT-E1390|GT-E2100|GT-E2120|GT-E2121|GT-E2152|GT-E2220|GT-E2222|GT-E2230|GT-E2232|GT-E2250|GT-E2370|GT-E2550|GT-E2652|GT-E3210|GT-E3213|GT-I5500|GT-I5503|GT-I5700|GT-I5800|GT-I5801|GT-I6410|GT-I6420|GT-I7110|GT-I7410|GT-I7500|GT-I8000|GT-I8150|GT-I8160|GT-I8190|GT-I8320|GT-I8330|GT-I8350|GT-I8530|GT-I8700|GT-I8703|GT-I8910|GT-I9000|GT-I9001|GT-I9003|GT-I9010|GT-I9020|GT-I9023|GT-I9070|GT-I9082|GT-I9100|GT-I9103|GT-I9220|GT-I9250|GT-I9300|GT-I9305|GT-I9500|GT-I9505|GT-M3510|GT-M5650|GT-M7500|GT-M7600|GT-M7603|GT-M8800|GT-M8910|GT-N7000|GT-S3110|GT-S3310|GT-S3350|GT-S3353|GT-S3370|GT-S3650|GT-S3653|GT-S3770|GT-S3850|GT-S5210|GT-S5220|GT-S5229|GT-S5230|GT-S5233|GT-S5250|GT-S5253|GT-S5260|GT-S5263|GT-S5270|GT-S5300|GT-S5330|GT-S5350|GT-S5360|GT-S5363|GT-S5369|GT-S5380|GT-S5380D|GT-S5560|GT-S5570|GT-S5600|GT-S5603|GT-S5610|GT-S5620|GT-S5660|GT-S5670|GT-S5690|GT-S5750|GT-S5780|GT-S5830|GT-S5839|GT-S6102|GT-S6500|GT-S7070|GT-S7200|GT-S7220|GT-S7230|GT-S7233|GT-S7250|GT-S7500|GT-S7530|GT-S7550|GT-S7562|GT-S7710|GT-S8000|GT-S8003|GT-S8500|GT-S8530|GT-S8600|SCH-A310|SCH-A530|SCH-A570|SCH-A610|SCH-A630|SCH-A650|SCH-A790|SCH-A795|SCH-A850|SCH-A870|SCH-A890|SCH-A930|SCH-A950|SCH-A970|SCH-A990|SCH-I100|SCH-I110|SCH-I400|SCH-I405|SCH-I500|SCH-I510|SCH-I515|SCH-I600|SCH-I730|SCH-I760|SCH-I770|SCH-I830|SCH-I910|SCH-I920|SCH-I959|SCH-LC11|SCH-N150|SCH-N300|SCH-R100|SCH-R300|SCH-R351|SCH-R400|SCH-R410|SCH-T300|SCH-U310|SCH-U320|SCH-U350|SCH-U360|SCH-U365|SCH-U370|SCH-U380|SCH-U410|SCH-U430|SCH-U450|SCH-U460|SCH-U470|SCH-U490|SCH-U540|SCH-U550|SCH-U620|SCH-U640|SCH-U650|SCH-U660|SCH-U700|SCH-U740|SCH-U750|SCH-U810|SCH-U820|SCH-U900|SCH-U940|SCH-U960|SCS-26UC|SGH-A107|SGH-A117|SGH-A127|SGH-A137|SGH-A157|SGH-A167|SGH-A177|SGH-A187|SGH-A197|SGH-A227|SGH-A237|SGH-A257|SGH-A437|SGH-A517|SGH-A597|SGH-A637|SGH-A657|SGH-A667|SGH-A687|SGH-A697|SGH-A707|SGH-A717|SGH-A727|SGH-A737|SGH-A747|SGH-A767|SGH-A777|SGH-A797|SGH-A817|SGH-A827|SGH-A837|SGH-A847|SGH-A867|SGH-A877|SGH-A887|SGH-A897|SGH-A927|SGH-B100|SGH-B130|SGH-B200|SGH-B220|SGH-C100|SGH-C110|SGH-C120|SGH-C130|SGH-C140|SGH-C160|SGH-C170|SGH-C180|SGH-C200|SGH-C207|SGH-C210|SGH-C225|SGH-C230|SGH-C417|SGH-C450|SGH-D307|SGH-D347|SGH-D357|SGH-D407|SGH-D415|SGH-D780|SGH-D807|SGH-D980|SGH-E105|SGH-E200|SGH-E315|SGH-E316|SGH-E317|SGH-E335|SGH-E590|SGH-E635|SGH-E715|SGH-E890|SGH-F300|SGH-F480|SGH-I200|SGH-I300|SGH-I320|SGH-I550|SGH-I577|SGH-I600|SGH-I607|SGH-I617|SGH-I627|SGH-I637|SGH-I677|SGH-I700|SGH-I717|SGH-I727|SGH-i747M|SGH-I777|SGH-I780|SGH-I827|SGH-I847|SGH-I857|SGH-I896|SGH-I897|SGH-I900|SGH-I907|SGH-I917|SGH-I927|SGH-I937|SGH-I997|SGH-J150|SGH-J200|SGH-L170|SGH-L700|SGH-M110|SGH-M150|SGH-M200|SGH-N105|SGH-N500|SGH-N600|SGH-N620|SGH-N625|SGH-N700|SGH-N710|SGH-P107|SGH-P207|SGH-P300|SGH-P310|SGH-P520|SGH-P735|SGH-P777|SGH-Q105|SGH-R210|SGH-R220|SGH-R225|SGH-S105|SGH-S307|SGH-T109|SGH-T119|SGH-T139|SGH-T209|SGH-T219|SGH-T229|SGH-T239|SGH-T249|SGH-T259|SGH-T309|SGH-T319|SGH-T329|SGH-T339|SGH-T349|SGH-T359|SGH-T369|SGH-T379|SGH-T409|SGH-T429|SGH-T439|SGH-T459|SGH-T469|SGH-T479|SGH-T499|SGH-T509|SGH-T519|SGH-T539|SGH-T559|SGH-T589|SGH-T609|SGH-T619|SGH-T629|SGH-T639|SGH-T659|SGH-T669|SGH-T679|SGH-T709|SGH-T719|SGH-T729|SGH-T739|SGH-T746|SGH-T749|SGH-T759|SGH-T769|SGH-T809|SGH-T819|SGH-T839|SGH-T919|SGH-T929|SGH-T939|SGH-T959|SGH-T989|SGH-U100|SGH-U200|SGH-U800|SGH-V205|SGH-V206|SGH-X100|SGH-X105|SGH-X120|SGH-X140|SGH-X426|SGH-X427|SGH-X475|SGH-X495|SGH-X497|SGH-X507|SGH-X600|SGH-X610|SGH-X620|SGH-X630|SGH-X700|SGH-X820|SGH-X890|SGH-Z130|SGH-Z150|SGH-Z170|SGH-ZX10|SGH-ZX20|SHW-M110|SPH-A120|SPH-A400|SPH-A420|SPH-A460|SPH-A500|SPH-A560|SPH-A600|SPH-A620|SPH-A660|SPH-A700|SPH-A740|SPH-A760|SPH-A790|SPH-A800|SPH-A820|SPH-A840|SPH-A880|SPH-A900|SPH-A940|SPH-A960|SPH-D600|SPH-D700|SPH-D710|SPH-D720|SPH-I300|SPH-I325|SPH-I330|SPH-I350|SPH-I500|SPH-I600|SPH-I700|SPH-L700|SPH-M100|SPH-M220|SPH-M240|SPH-M300|SPH-M305|SPH-M320|SPH-M330|SPH-M350|SPH-M360|SPH-M370|SPH-M380|SPH-M510|SPH-M540|SPH-M550|SPH-M560|SPH-M570|SPH-M580|SPH-M610|SPH-M620|SPH-M630|SPH-M800|SPH-M810|SPH-M850|SPH-M900|SPH-M910|SPH-M920|SPH-M930|SPH-N100|SPH-N200|SPH-N240|SPH-N300|SPH-N400|SPH-Z400|SWC-E100|SCH-i909|GT-N7100|GT-N7105|SCH-I535|SM-N900A|SGH-I317|SGH-T999L|GT-S5360B|GT-I8262|GT-S6802|GT-S6312|GT-S6310|GT-S5312|GT-S5310|GT-I9105|GT-I8510|GT-S6790N|SM-G7105|SM-N9005|GT-S5301|GT-I9295|GT-I9195|SM-C101|GT-S7392|GT-S7560|GT-B7610|GT-I5510|GT-S7582|GT-S7530E|GT-I8750|SM-G9006V|SM-G9008V|SM-G9009D|SM-G900A|SM-G900D|SM-G900F|SM-G900H|SM-G900I|SM-G900J|SM-G900K|SM-G900L|SM-G900M|SM-G900P|SM-G900R4|SM-G900S|SM-G900T|SM-G900V|SM-G900W8|SHV-E160K|SCH-P709|SCH-P729|SM-T2558|GT-I9205|SM-G9350|SM-J120F",
			"LG": "\\bLG\\b;|LG[- ]?(C800|C900|E400|E610|E900|E-900|F160|F180K|F180L|F180S|730|855|L160|LS740|LS840|LS970|LU6200|MS690|MS695|MS770|MS840|MS870|MS910|P500|P700|P705|VM696|AS680|AS695|AX840|C729|E970|GS505|272|C395|E739BK|E960|L55C|L75C|LS696|LS860|P769BK|P350|P500|P509|P870|UN272|US730|VS840|VS950|LN272|LN510|LS670|LS855|LW690|MN270|MN510|P509|P769|P930|UN200|UN270|UN510|UN610|US670|US740|US760|UX265|UX840|VN271|VN530|VS660|VS700|VS740|VS750|VS910|VS920|VS930|VX9200|VX11000|AX840A|LW770|P506|P925|P999|E612|D955|D802|MS323)",
			"Sony": "SonyST|SonyLT|SonyEricsson|SonyEricssonLT15iv|LT18i|E10i|LT28h|LT26w|SonyEricssonMT27i|C5303|C6902|C6903|C6906|C6943|D2533",
			"Asus": "Asus.*Galaxy|PadFone.*Mobile",
			"NokiaLumia": "Lumia [0-9]{3,4}",
			"Micromax": "Micromax.*\\b(A210|A92|A88|A72|A111|A110Q|A115|A116|A110|A90S|A26|A51|A35|A54|A25|A27|A89|A68|A65|A57|A90)\\b",
			"Palm": "PalmSource|Palm",
			"Vertu": "Vertu|Vertu.*Ltd|Vertu.*Ascent|Vertu.*Ayxta|Vertu.*Constellation(F|Quest)?|Vertu.*Monika|Vertu.*Signature",
			"Pantech": "PANTECH|IM-A850S|IM-A840S|IM-A830L|IM-A830K|IM-A830S|IM-A820L|IM-A810K|IM-A810S|IM-A800S|IM-T100K|IM-A725L|IM-A780L|IM-A775C|IM-A770K|IM-A760S|IM-A750K|IM-A740S|IM-A730S|IM-A720L|IM-A710K|IM-A690L|IM-A690S|IM-A650S|IM-A630K|IM-A600S|VEGA PTL21|PT003|P8010|ADR910L|P6030|P6020|P9070|P4100|P9060|P5000|CDM8992|TXT8045|ADR8995|IS11PT|P2030|P6010|P8000|PT002|IS06|CDM8999|P9050|PT001|TXT8040|P2020|P9020|P2000|P7040|P7000|C790",
			"Fly": "IQ230|IQ444|IQ450|IQ440|IQ442|IQ441|IQ245|IQ256|IQ236|IQ255|IQ235|IQ245|IQ275|IQ240|IQ285|IQ280|IQ270|IQ260|IQ250",
			"Wiko": "KITE 4G|HIGHWAY|GETAWAY|STAIRWAY|DARKSIDE|DARKFULL|DARKNIGHT|DARKMOON|SLIDE|WAX 4G|RAINBOW|BLOOM|SUNSET|GOA(?!nna)|LENNY|BARRY|IGGY|OZZY|CINK FIVE|CINK PEAX|CINK PEAX 2|CINK SLIM|CINK SLIM 2|CINK +|CINK KING|CINK PEAX|CINK SLIM|SUBLIM",
			"iMobile": "i-mobile (IQ|i-STYLE|idea|ZAA|Hitz)",
			"SimValley": "\\b(SP-80|XT-930|SX-340|XT-930|SX-310|SP-360|SP60|SPT-800|SP-120|SPT-800|SP-140|SPX-5|SPX-8|SP-100|SPX-8|SPX-12)\\b",
			"Wolfgang": "AT-B24D|AT-AS50HD|AT-AS40W|AT-AS55HD|AT-AS45q2|AT-B26D|AT-AS50Q",
			"Alcatel": "Alcatel",
			"Nintendo": "Nintendo 3DS",
			"Amoi": "Amoi",
			"INQ": "INQ",
			"GenericPhone": "Tapatalk|PDA;|SAGEM|\\bmmp\\b|pocket|\\bpsp\\b|symbian|Smartphone|smartfon|treo|up.browser|up.link|vodafone|\\bwap\\b|nokia|Series40|Series60|S60|SonyEricsson|N900|MAUI.*WAP.*Browser"
		},
		"tablets": {
			"iPad": "iPad|iPad.*Mobile",
			"NexusTablet": "Android.*Nexus[\\s]+(7|9|10)",
			"SamsungTablet": "SAMSUNG.*Tablet|Galaxy.*Tab|SC-01C|GT-P1000|GT-P1003|GT-P1010|GT-P3105|GT-P6210|GT-P6800|GT-P6810|GT-P7100|GT-P7300|GT-P7310|GT-P7500|GT-P7510|SCH-I800|SCH-I815|SCH-I905|SGH-I957|SGH-I987|SGH-T849|SGH-T859|SGH-T869|SPH-P100|GT-P3100|GT-P3108|GT-P3110|GT-P5100|GT-P5110|GT-P6200|GT-P7320|GT-P7511|GT-N8000|GT-P8510|SGH-I497|SPH-P500|SGH-T779|SCH-I705|SCH-I915|GT-N8013|GT-P3113|GT-P5113|GT-P8110|GT-N8010|GT-N8005|GT-N8020|GT-P1013|GT-P6201|GT-P7501|GT-N5100|GT-N5105|GT-N5110|SHV-E140K|SHV-E140L|SHV-E140S|SHV-E150S|SHV-E230K|SHV-E230L|SHV-E230S|SHW-M180K|SHW-M180L|SHW-M180S|SHW-M180W|SHW-M300W|SHW-M305W|SHW-M380K|SHW-M380S|SHW-M380W|SHW-M430W|SHW-M480K|SHW-M480S|SHW-M480W|SHW-M485W|SHW-M486W|SHW-M500W|GT-I9228|SCH-P739|SCH-I925|GT-I9200|GT-P5200|GT-P5210|GT-P5210X|SM-T311|SM-T310|SM-T310X|SM-T210|SM-T210R|SM-T211|SM-P600|SM-P601|SM-P605|SM-P900|SM-P901|SM-T217|SM-T217A|SM-T217S|SM-P6000|SM-T3100|SGH-I467|XE500|SM-T110|GT-P5220|GT-I9200X|GT-N5110X|GT-N5120|SM-P905|SM-T111|SM-T2105|SM-T315|SM-T320|SM-T320X|SM-T321|SM-T520|SM-T525|SM-T530NU|SM-T230NU|SM-T330NU|SM-T900|XE500T1C|SM-P605V|SM-P905V|SM-T337V|SM-T537V|SM-T707V|SM-T807V|SM-P600X|SM-P900X|SM-T210X|SM-T230|SM-T230X|SM-T325|GT-P7503|SM-T531|SM-T330|SM-T530|SM-T705|SM-T705C|SM-T535|SM-T331|SM-T800|SM-T700|SM-T537|SM-T807|SM-P907A|SM-T337A|SM-T537A|SM-T707A|SM-T807A|SM-T237|SM-T807P|SM-P607T|SM-T217T|SM-T337T|SM-T807T|SM-T116NQ|SM-P550|SM-T350|SM-T550|SM-T9000|SM-P9000|SM-T705Y|SM-T805|GT-P3113|SM-T710|SM-T810|SM-T815|SM-T360|SM-T533|SM-T113|SM-T335|SM-T715|SM-T560|SM-T670|SM-T677|SM-T377|SM-T567|SM-T357T|SM-T555|SM-T561|SM-T713|SM-T719|SM-T813|SM-T819|SM-T580|SM-T355Y|SM-T280",
			"Kindle": "Kindle|Silk.*Accelerated|Android.*\\b(KFOT|KFTT|KFJWI|KFJWA|KFOTE|KFSOWI|KFTHWI|KFTHWA|KFAPWI|KFAPWA|WFJWAE|KFSAWA|KFSAWI|KFASWI|KFARWI)\\b",
			"SurfaceTablet": "Windows NT [0-9.]+; ARM;.*(Tablet|ARMBJS)",
			"HPTablet": "HP Slate (7|8|10)|HP ElitePad 900|hp-tablet|EliteBook.*Touch|HP 8|Slate 21|HP SlateBook 10",
			"AsusTablet": "^.*PadFone((?!Mobile).)*$|Transformer|TF101|TF101G|TF300T|TF300TG|TF300TL|TF700T|TF700KL|TF701T|TF810C|ME171|ME301T|ME302C|ME371MG|ME370T|ME372MG|ME172V|ME173X|ME400C|Slider SL101|\\bK00F\\b|\\bK00C\\b|\\bK00E\\b|\\bK00L\\b|TX201LA|ME176C|ME102A|\\bM80TA\\b|ME372CL|ME560CG|ME372CG|ME302KL| K010 | K011 | K017 | K01E |ME572C|ME103K|ME170C|ME171C|\\bME70C\\b|ME581C|ME581CL|ME8510C|ME181C|P01Y|PO1MA|P01Z",
			"BlackBerryTablet": "PlayBook|RIM Tablet",
			"HTCtablet": "HTC_Flyer_P512|HTC Flyer|HTC Jetstream|HTC-P715a|HTC EVO View 4G|PG41200|PG09410",
			"MotorolaTablet": "xoom|sholest|MZ615|MZ605|MZ505|MZ601|MZ602|MZ603|MZ604|MZ606|MZ607|MZ608|MZ609|MZ615|MZ616|MZ617",
			"NookTablet": "Android.*Nook|NookColor|nook browser|BNRV200|BNRV200A|BNTV250|BNTV250A|BNTV400|BNTV600|LogicPD Zoom2",
			"AcerTablet": "Android.*; \\b(A100|A101|A110|A200|A210|A211|A500|A501|A510|A511|A700|A701|W500|W500P|W501|W501P|W510|W511|W700|G100|G100W|B1-A71|B1-710|B1-711|A1-810|A1-811|A1-830)\\b|W3-810|\\bA3-A10\\b|\\bA3-A11\\b|\\bA3-A20\\b|\\bA3-A30",
			"ToshibaTablet": "Android.*(AT100|AT105|AT200|AT205|AT270|AT275|AT300|AT305|AT1S5|AT500|AT570|AT700|AT830)|TOSHIBA.*FOLIO",
			"LGTablet": "\\bL-06C|LG-V909|LG-V900|LG-V700|LG-V510|LG-V500|LG-V410|LG-V400|LG-VK810\\b",
			"FujitsuTablet": "Android.*\\b(F-01D|F-02F|F-05E|F-10D|M532|Q572)\\b",
			"PrestigioTablet": "PMP3170B|PMP3270B|PMP3470B|PMP7170B|PMP3370B|PMP3570C|PMP5870C|PMP3670B|PMP5570C|PMP5770D|PMP3970B|PMP3870C|PMP5580C|PMP5880D|PMP5780D|PMP5588C|PMP7280C|PMP7280C3G|PMP7280|PMP7880D|PMP5597D|PMP5597|PMP7100D|PER3464|PER3274|PER3574|PER3884|PER5274|PER5474|PMP5097CPRO|PMP5097|PMP7380D|PMP5297C|PMP5297C_QUAD|PMP812E|PMP812E3G|PMP812F|PMP810E|PMP880TD|PMT3017|PMT3037|PMT3047|PMT3057|PMT7008|PMT5887|PMT5001|PMT5002",
			"LenovoTablet": "Lenovo TAB|Idea(Tab|Pad)( A1|A10| K1|)|ThinkPad([ ]+)?Tablet|YT3-X90L|YT3-X90F|YT3-X90X|Lenovo.*(S2109|S2110|S5000|S6000|K3011|A3000|A3500|A1000|A2107|A2109|A1107|A5500|A7600|B6000|B8000|B8080)(-|)(FL|F|HV|H|)",
			"DellTablet": "Venue 11|Venue 8|Venue 7|Dell Streak 10|Dell Streak 7",
			"YarvikTablet": "Android.*\\b(TAB210|TAB211|TAB224|TAB250|TAB260|TAB264|TAB310|TAB360|TAB364|TAB410|TAB411|TAB420|TAB424|TAB450|TAB460|TAB461|TAB464|TAB465|TAB467|TAB468|TAB07-100|TAB07-101|TAB07-150|TAB07-151|TAB07-152|TAB07-200|TAB07-201-3G|TAB07-210|TAB07-211|TAB07-212|TAB07-214|TAB07-220|TAB07-400|TAB07-485|TAB08-150|TAB08-200|TAB08-201-3G|TAB08-201-30|TAB09-100|TAB09-211|TAB09-410|TAB10-150|TAB10-201|TAB10-211|TAB10-400|TAB10-410|TAB13-201|TAB274EUK|TAB275EUK|TAB374EUK|TAB462EUK|TAB474EUK|TAB9-200)\\b",
			"MedionTablet": "Android.*\\bOYO\\b|LIFE.*(P9212|P9514|P9516|S9512)|LIFETAB",
			"ArnovaTablet": "97G4|AN10G2|AN7bG3|AN7fG3|AN8G3|AN8cG3|AN7G3|AN9G3|AN7dG3|AN7dG3ST|AN7dG3ChildPad|AN10bG3|AN10bG3DT|AN9G2",
			"IntensoTablet": "INM8002KP|INM1010FP|INM805ND|Intenso Tab|TAB1004",
			"IRUTablet": "M702pro",
			"MegafonTablet": "MegaFon V9|\\bZTE V9\\b|Android.*\\bMT7A\\b",
			"EbodaTablet": "E-Boda (Supreme|Impresspeed|Izzycomm|Essential)",
			"AllViewTablet": "Allview.*(Viva|Alldro|City|Speed|All TV|Frenzy|Quasar|Shine|TX1|AX1|AX2)",
			"ArchosTablet": "\\b(101G9|80G9|A101IT)\\b|Qilive 97R|Archos5|\\bARCHOS (70|79|80|90|97|101|FAMILYPAD|)(b|)(G10| Cobalt| TITANIUM(HD|)| Xenon| Neon|XSK| 2| XS 2| PLATINUM| CARBON|GAMEPAD)\\b",
			"AinolTablet": "NOVO7|NOVO8|NOVO10|Novo7Aurora|Novo7Basic|NOVO7PALADIN|novo9-Spark",
			"NokiaLumiaTablet": "Lumia 2520",
			"SonyTablet": "Sony.*Tablet|Xperia Tablet|Sony Tablet S|SO-03E|SGPT12|SGPT13|SGPT114|SGPT121|SGPT122|SGPT123|SGPT111|SGPT112|SGPT113|SGPT131|SGPT132|SGPT133|SGPT211|SGPT212|SGPT213|SGP311|SGP312|SGP321|EBRD1101|EBRD1102|EBRD1201|SGP351|SGP341|SGP511|SGP512|SGP521|SGP541|SGP551|SGP621|SGP612|SOT31",
			"PhilipsTablet": "\\b(PI2010|PI3000|PI3100|PI3105|PI3110|PI3205|PI3210|PI3900|PI4010|PI7000|PI7100)\\b",
			"CubeTablet": "Android.*(K8GT|U9GT|U10GT|U16GT|U17GT|U18GT|U19GT|U20GT|U23GT|U30GT)|CUBE U8GT",
			"CobyTablet": "MID1042|MID1045|MID1125|MID1126|MID7012|MID7014|MID7015|MID7034|MID7035|MID7036|MID7042|MID7048|MID7127|MID8042|MID8048|MID8127|MID9042|MID9740|MID9742|MID7022|MID7010",
			"MIDTablet": "M9701|M9000|M9100|M806|M1052|M806|T703|MID701|MID713|MID710|MID727|MID760|MID830|MID728|MID933|MID125|MID810|MID732|MID120|MID930|MID800|MID731|MID900|MID100|MID820|MID735|MID980|MID130|MID833|MID737|MID960|MID135|MID860|MID736|MID140|MID930|MID835|MID733|MID4X10",
			"MSITablet": "MSI \\b(Primo 73K|Primo 73L|Primo 81L|Primo 77|Primo 93|Primo 75|Primo 76|Primo 73|Primo 81|Primo 91|Primo 90|Enjoy 71|Enjoy 7|Enjoy 10)\\b",
			"SMiTTablet": "Android.*(\\bMID\\b|MID-560|MTV-T1200|MTV-PND531|MTV-P1101|MTV-PND530)",
			"RockChipTablet": "Android.*(RK2818|RK2808A|RK2918|RK3066)|RK2738|RK2808A",
			"FlyTablet": "IQ310|Fly Vision",
			"bqTablet": "Android.*(bq)?.*(Elcano|Curie|Edison|Maxwell|Kepler|Pascal|Tesla|Hypatia|Platon|Newton|Livingstone|Cervantes|Avant|Aquaris [E|M]10)|Maxwell.*Lite|Maxwell.*Plus",
			"HuaweiTablet": "MediaPad|MediaPad 7 Youth|IDEOS S7|S7-201c|S7-202u|S7-101|S7-103|S7-104|S7-105|S7-106|S7-201|S7-Slim",
			"NecTablet": "\\bN-06D|\\bN-08D",
			"PantechTablet": "Pantech.*P4100",
			"BronchoTablet": "Broncho.*(N701|N708|N802|a710)",
			"VersusTablet": "TOUCHPAD.*[78910]|\\bTOUCHTAB\\b",
			"ZyncTablet": "z1000|Z99 2G|z99|z930|z999|z990|z909|Z919|z900",
			"PositivoTablet": "TB07STA|TB10STA|TB07FTA|TB10FTA",
			"NabiTablet": "Android.*\\bNabi",
			"KoboTablet": "Kobo Touch|\\bK080\\b|\\bVox\\b Build|\\bArc\\b Build",
			"DanewTablet": "DSlide.*\\b(700|701R|702|703R|704|802|970|971|972|973|974|1010|1012)\\b",
			"TexetTablet": "NaviPad|TB-772A|TM-7045|TM-7055|TM-9750|TM-7016|TM-7024|TM-7026|TM-7041|TM-7043|TM-7047|TM-8041|TM-9741|TM-9747|TM-9748|TM-9751|TM-7022|TM-7021|TM-7020|TM-7011|TM-7010|TM-7023|TM-7025|TM-7037W|TM-7038W|TM-7027W|TM-9720|TM-9725|TM-9737W|TM-1020|TM-9738W|TM-9740|TM-9743W|TB-807A|TB-771A|TB-727A|TB-725A|TB-719A|TB-823A|TB-805A|TB-723A|TB-715A|TB-707A|TB-705A|TB-709A|TB-711A|TB-890HD|TB-880HD|TB-790HD|TB-780HD|TB-770HD|TB-721HD|TB-710HD|TB-434HD|TB-860HD|TB-840HD|TB-760HD|TB-750HD|TB-740HD|TB-730HD|TB-722HD|TB-720HD|TB-700HD|TB-500HD|TB-470HD|TB-431HD|TB-430HD|TB-506|TB-504|TB-446|TB-436|TB-416|TB-146SE|TB-126SE",
			"PlaystationTablet": "Playstation.*(Portable|Vita)",
			"TrekstorTablet": "ST10416-1|VT10416-1|ST70408-1|ST702xx-1|ST702xx-2|ST80208|ST97216|ST70104-2|VT10416-2|ST10216-2A|SurfTab",
			"PyleAudioTablet": "\\b(PTBL10CEU|PTBL10C|PTBL72BC|PTBL72BCEU|PTBL7CEU|PTBL7C|PTBL92BC|PTBL92BCEU|PTBL9CEU|PTBL9CUK|PTBL9C)\\b",
			"AdvanTablet": "Android.* \\b(E3A|T3X|T5C|T5B|T3E|T3C|T3B|T1J|T1F|T2A|T1H|T1i|E1C|T1-E|T5-A|T4|E1-B|T2Ci|T1-B|T1-D|O1-A|E1-A|T1-A|T3A|T4i)\\b ",
			"DanyTechTablet": "Genius Tab G3|Genius Tab S2|Genius Tab Q3|Genius Tab G4|Genius Tab Q4|Genius Tab G-II|Genius TAB GII|Genius TAB GIII|Genius Tab S1",
			"GalapadTablet": "Android.*\\bG1\\b",
			"MicromaxTablet": "Funbook|Micromax.*\\b(P250|P560|P360|P362|P600|P300|P350|P500|P275)\\b",
			"KarbonnTablet": "Android.*\\b(A39|A37|A34|ST8|ST10|ST7|Smart Tab3|Smart Tab2)\\b",
			"AllFineTablet": "Fine7 Genius|Fine7 Shine|Fine7 Air|Fine8 Style|Fine9 More|Fine10 Joy|Fine11 Wide",
			"PROSCANTablet": "\\b(PEM63|PLT1023G|PLT1041|PLT1044|PLT1044G|PLT1091|PLT4311|PLT4311PL|PLT4315|PLT7030|PLT7033|PLT7033D|PLT7035|PLT7035D|PLT7044K|PLT7045K|PLT7045KB|PLT7071KG|PLT7072|PLT7223G|PLT7225G|PLT7777G|PLT7810K|PLT7849G|PLT7851G|PLT7852G|PLT8015|PLT8031|PLT8034|PLT8036|PLT8080K|PLT8082|PLT8088|PLT8223G|PLT8234G|PLT8235G|PLT8816K|PLT9011|PLT9045K|PLT9233G|PLT9735|PLT9760G|PLT9770G)\\b",
			"YONESTablet": "BQ1078|BC1003|BC1077|RK9702|BC9730|BC9001|IT9001|BC7008|BC7010|BC708|BC728|BC7012|BC7030|BC7027|BC7026",
			"ChangJiaTablet": "TPC7102|TPC7103|TPC7105|TPC7106|TPC7107|TPC7201|TPC7203|TPC7205|TPC7210|TPC7708|TPC7709|TPC7712|TPC7110|TPC8101|TPC8103|TPC8105|TPC8106|TPC8203|TPC8205|TPC8503|TPC9106|TPC9701|TPC97101|TPC97103|TPC97105|TPC97106|TPC97111|TPC97113|TPC97203|TPC97603|TPC97809|TPC97205|TPC10101|TPC10103|TPC10106|TPC10111|TPC10203|TPC10205|TPC10503",
			"GUTablet": "TX-A1301|TX-M9002|Q702|kf026",
			"PointOfViewTablet": "TAB-P506|TAB-navi-7-3G-M|TAB-P517|TAB-P-527|TAB-P701|TAB-P703|TAB-P721|TAB-P731N|TAB-P741|TAB-P825|TAB-P905|TAB-P925|TAB-PR945|TAB-PL1015|TAB-P1025|TAB-PI1045|TAB-P1325|TAB-PROTAB[0-9]+|TAB-PROTAB25|TAB-PROTAB26|TAB-PROTAB27|TAB-PROTAB26XL|TAB-PROTAB2-IPS9|TAB-PROTAB30-IPS9|TAB-PROTAB25XXL|TAB-PROTAB26-IPS10|TAB-PROTAB30-IPS10",
			"OvermaxTablet": "OV-(SteelCore|NewBase|Basecore|Baseone|Exellen|Quattor|EduTab|Solution|ACTION|BasicTab|TeddyTab|MagicTab|Stream|TB-08|TB-09)",
			"HCLTablet": "HCL.*Tablet|Connect-3G-2.0|Connect-2G-2.0|ME Tablet U1|ME Tablet U2|ME Tablet G1|ME Tablet X1|ME Tablet Y2|ME Tablet Sync",
			"DPSTablet": "DPS Dream 9|DPS Dual 7",
			"VistureTablet": "V97 HD|i75 3G|Visture V4( HD)?|Visture V5( HD)?|Visture V10",
			"CrestaTablet": "CTP(-)?810|CTP(-)?818|CTP(-)?828|CTP(-)?838|CTP(-)?888|CTP(-)?978|CTP(-)?980|CTP(-)?987|CTP(-)?988|CTP(-)?989",
			"MediatekTablet": "\\bMT8125|MT8389|MT8135|MT8377\\b",
			"ConcordeTablet": "Concorde([ ]+)?Tab|ConCorde ReadMan",
			"GoCleverTablet": "GOCLEVER TAB|A7GOCLEVER|M1042|M7841|M742|R1042BK|R1041|TAB A975|TAB A7842|TAB A741|TAB A741L|TAB M723G|TAB M721|TAB A1021|TAB I921|TAB R721|TAB I720|TAB T76|TAB R70|TAB R76.2|TAB R106|TAB R83.2|TAB M813G|TAB I721|GCTA722|TAB I70|TAB I71|TAB S73|TAB R73|TAB R74|TAB R93|TAB R75|TAB R76.1|TAB A73|TAB A93|TAB A93.2|TAB T72|TAB R83|TAB R974|TAB R973|TAB A101|TAB A103|TAB A104|TAB A104.2|R105BK|M713G|A972BK|TAB A971|TAB R974.2|TAB R104|TAB R83.3|TAB A1042",
			"ModecomTablet": "FreeTAB 9000|FreeTAB 7.4|FreeTAB 7004|FreeTAB 7800|FreeTAB 2096|FreeTAB 7.5|FreeTAB 1014|FreeTAB 1001 |FreeTAB 8001|FreeTAB 9706|FreeTAB 9702|FreeTAB 7003|FreeTAB 7002|FreeTAB 1002|FreeTAB 7801|FreeTAB 1331|FreeTAB 1004|FreeTAB 8002|FreeTAB 8014|FreeTAB 9704|FreeTAB 1003",
			"VoninoTablet": "\\b(Argus[ _]?S|Diamond[ _]?79HD|Emerald[ _]?78E|Luna[ _]?70C|Onyx[ _]?S|Onyx[ _]?Z|Orin[ _]?HD|Orin[ _]?S|Otis[ _]?S|SpeedStar[ _]?S|Magnet[ _]?M9|Primus[ _]?94[ _]?3G|Primus[ _]?94HD|Primus[ _]?QS|Android.*\\bQ8\\b|Sirius[ _]?EVO[ _]?QS|Sirius[ _]?QS|Spirit[ _]?S)\\b",
			"ECSTablet": "V07OT2|TM105A|S10OT1|TR10CS1",
			"StorexTablet": "eZee[_']?(Tab|Go)[0-9]+|TabLC7|Looney Tunes Tab",
			"VodafoneTablet": "SmartTab([ ]+)?[0-9]+|SmartTabII10|SmartTabII7|VF-1497",
			"EssentielBTablet": "Smart[ ']?TAB[ ]+?[0-9]+|Family[ ']?TAB2",
			"RossMoorTablet": "RM-790|RM-997|RMD-878G|RMD-974R|RMT-705A|RMT-701|RME-601|RMT-501|RMT-711",
			"iMobileTablet": "i-mobile i-note",
			"TolinoTablet": "tolino tab [0-9.]+|tolino shine",
			"AudioSonicTablet": "\\bC-22Q|T7-QC|T-17B|T-17P\\b",
			"AMPETablet": "Android.* A78 ",
			"SkkTablet": "Android.* (SKYPAD|PHOENIX|CYCLOPS)",
			"TecnoTablet": "TECNO P9",
			"JXDTablet": "Android.* \\b(F3000|A3300|JXD5000|JXD3000|JXD2000|JXD300B|JXD300|S5800|S7800|S602b|S5110b|S7300|S5300|S602|S603|S5100|S5110|S601|S7100a|P3000F|P3000s|P101|P200s|P1000m|P200m|P9100|P1000s|S6600b|S908|P1000|P300|S18|S6600|S9100)\\b",
			"iJoyTablet": "Tablet (Spirit 7|Essentia|Galatea|Fusion|Onix 7|Landa|Titan|Scooby|Deox|Stella|Themis|Argon|Unique 7|Sygnus|Hexen|Finity 7|Cream|Cream X2|Jade|Neon 7|Neron 7|Kandy|Scape|Saphyr 7|Rebel|Biox|Rebel|Rebel 8GB|Myst|Draco 7|Myst|Tab7-004|Myst|Tadeo Jones|Tablet Boing|Arrow|Draco Dual Cam|Aurix|Mint|Amity|Revolution|Finity 9|Neon 9|T9w|Amity 4GB Dual Cam|Stone 4GB|Stone 8GB|Andromeda|Silken|X2|Andromeda II|Halley|Flame|Saphyr 9,7|Touch 8|Planet|Triton|Unique 10|Hexen 10|Memphis 4GB|Memphis 8GB|Onix 10)",
			"FX2Tablet": "FX2 PAD7|FX2 PAD10",
			"XoroTablet": "KidsPAD 701|PAD[ ]?712|PAD[ ]?714|PAD[ ]?716|PAD[ ]?717|PAD[ ]?718|PAD[ ]?720|PAD[ ]?721|PAD[ ]?722|PAD[ ]?790|PAD[ ]?792|PAD[ ]?900|PAD[ ]?9715D|PAD[ ]?9716DR|PAD[ ]?9718DR|PAD[ ]?9719QR|PAD[ ]?9720QR|TelePAD1030|Telepad1032|TelePAD730|TelePAD731|TelePAD732|TelePAD735Q|TelePAD830|TelePAD9730|TelePAD795|MegaPAD 1331|MegaPAD 1851|MegaPAD 2151",
			"ViewsonicTablet": "ViewPad 10pi|ViewPad 10e|ViewPad 10s|ViewPad E72|ViewPad7|ViewPad E100|ViewPad 7e|ViewSonic VB733|VB100a",
			"OdysTablet": "LOOX|XENO10|ODYS[ -](Space|EVO|Xpress|NOON)|\\bXELIO\\b|Xelio10Pro|XELIO7PHONETAB|XELIO10EXTREME|XELIOPT2|NEO_QUAD10",
			"CaptivaTablet": "CAPTIVA PAD",
			"IconbitTablet": "NetTAB|NT-3702|NT-3702S|NT-3702S|NT-3603P|NT-3603P|NT-0704S|NT-0704S|NT-3805C|NT-3805C|NT-0806C|NT-0806C|NT-0909T|NT-0909T|NT-0907S|NT-0907S|NT-0902S|NT-0902S",
			"TeclastTablet": "T98 4G|\\bP80\\b|\\bX90HD\\b|X98 Air|X98 Air 3G|\\bX89\\b|P80 3G|\\bX80h\\b|P98 Air|\\bX89HD\\b|P98 3G|\\bP90HD\\b|P89 3G|X98 3G|\\bP70h\\b|P79HD 3G|G18d 3G|\\bP79HD\\b|\\bP89s\\b|\\bA88\\b|\\bP10HD\\b|\\bP19HD\\b|G18 3G|\\bP78HD\\b|\\bA78\\b|\\bP75\\b|G17s 3G|G17h 3G|\\bP85t\\b|\\bP90\\b|\\bP11\\b|\\bP98t\\b|\\bP98HD\\b|\\bG18d\\b|\\bP85s\\b|\\bP11HD\\b|\\bP88s\\b|\\bA80HD\\b|\\bA80se\\b|\\bA10h\\b|\\bP89\\b|\\bP78s\\b|\\bG18\\b|\\bP85\\b|\\bA70h\\b|\\bA70\\b|\\bG17\\b|\\bP18\\b|\\bA80s\\b|\\bA11s\\b|\\bP88HD\\b|\\bA80h\\b|\\bP76s\\b|\\bP76h\\b|\\bP98\\b|\\bA10HD\\b|\\bP78\\b|\\bP88\\b|\\bA11\\b|\\bA10t\\b|\\bP76a\\b|\\bP76t\\b|\\bP76e\\b|\\bP85HD\\b|\\bP85a\\b|\\bP86\\b|\\bP75HD\\b|\\bP76v\\b|\\bA12\\b|\\bP75a\\b|\\bA15\\b|\\bP76Ti\\b|\\bP81HD\\b|\\bA10\\b|\\bT760VE\\b|\\bT720HD\\b|\\bP76\\b|\\bP73\\b|\\bP71\\b|\\bP72\\b|\\bT720SE\\b|\\bC520Ti\\b|\\bT760\\b|\\bT720VE\\b|T720-3GE|T720-WiFi",
			"OndaTablet": "\\b(V975i|Vi30|VX530|V701|Vi60|V701s|Vi50|V801s|V719|Vx610w|VX610W|V819i|Vi10|VX580W|Vi10|V711s|V813|V811|V820w|V820|Vi20|V711|VI30W|V712|V891w|V972|V819w|V820w|Vi60|V820w|V711|V813s|V801|V819|V975s|V801|V819|V819|V818|V811|V712|V975m|V101w|V961w|V812|V818|V971|V971s|V919|V989|V116w|V102w|V973|Vi40)\\b[\\s]+",
			"JaytechTablet": "TPC-PA762",
			"BlaupunktTablet": "Endeavour 800NG|Endeavour 1010",
			"DigmaTablet": "\\b(iDx10|iDx9|iDx8|iDx7|iDxD7|iDxD8|iDsQ8|iDsQ7|iDsQ8|iDsD10|iDnD7|3TS804H|iDsQ11|iDj7|iDs10)\\b",
			"EvolioTablet": "ARIA_Mini_wifi|Aria[ _]Mini|Evolio X10|Evolio X7|Evolio X8|\\bEvotab\\b|\\bNeura\\b",
			"LavaTablet": "QPAD E704|\\bIvoryS\\b|E-TAB IVORY|\\bE-TAB\\b",
			"AocTablet": "MW0811|MW0812|MW0922|MTK8382|MW1031|MW0831|MW0821|MW0931|MW0712",
			"MpmanTablet": "MP11 OCTA|MP10 OCTA|MPQC1114|MPQC1004|MPQC994|MPQC974|MPQC973|MPQC804|MPQC784|MPQC780|\\bMPG7\\b|MPDCG75|MPDCG71|MPDC1006|MP101DC|MPDC9000|MPDC905|MPDC706HD|MPDC706|MPDC705|MPDC110|MPDC100|MPDC99|MPDC97|MPDC88|MPDC8|MPDC77|MP709|MID701|MID711|MID170|MPDC703|MPQC1010",
			"CelkonTablet": "CT695|CT888|CT[\\s]?910|CT7 Tab|CT9 Tab|CT3 Tab|CT2 Tab|CT1 Tab|C820|C720|\\bCT-1\\b",
			"WolderTablet": "miTab \\b(DIAMOND|SPACE|BROOKLYN|NEO|FLY|MANHATTAN|FUNK|EVOLUTION|SKY|GOCAR|IRON|GENIUS|POP|MINT|EPSILON|BROADWAY|JUMP|HOP|LEGEND|NEW AGE|LINE|ADVANCE|FEEL|FOLLOW|LIKE|LINK|LIVE|THINK|FREEDOM|CHICAGO|CLEVELAND|BALTIMORE-GH|IOWA|BOSTON|SEATTLE|PHOENIX|DALLAS|IN 101|MasterChef)\\b",
			"MiTablet": "\\bMI PAD\\b|\\bHM NOTE 1W\\b",
			"NibiruTablet": "Nibiru M1|Nibiru Jupiter One",
			"NexoTablet": "NEXO NOVA|NEXO 10|NEXO AVIO|NEXO FREE|NEXO GO|NEXO EVO|NEXO 3G|NEXO SMART|NEXO KIDDO|NEXO MOBI",
			"LeaderTablet": "TBLT10Q|TBLT10I|TBL-10WDKB|TBL-10WDKBO2013|TBL-W230V2|TBL-W450|TBL-W500|SV572|TBLT7I|TBA-AC7-8G|TBLT79|TBL-8W16|TBL-10W32|TBL-10WKB|TBL-W100",
			"UbislateTablet": "UbiSlate[\\s]?7C",
			"PocketBookTablet": "Pocketbook",
			"KocasoTablet": "\\b(TB-1207)\\b",
			"HisenseTablet": "\\b(F5281|E2371)\\b",
			"Hudl": "Hudl HT7S3|Hudl 2",
			"TelstraTablet": "T-Hub2",
			"GenericTablet": "Android.*\\b97D\\b|Tablet(?!.*PC)|BNTV250A|MID-WCDMA|LogicPD Zoom2|\\bA7EB\\b|CatNova8|A1_07|CT704|CT1002|\\bM721\\b|rk30sdk|\\bEVOTAB\\b|M758A|ET904|ALUMIUM10|Smartfren Tab|Endeavour 1010|Tablet-PC-4|Tagi Tab|\\bM6pro\\b|CT1020W|arc 10HD|\\bTP750\\b"
		},
		"detectMobileBrowsers" : {
			fullPattern: /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i,
			shortPattern: /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i,
			tabletPattern: /android|ipad|playbook|silk/i
		}
	};

	bouncer.prototype.convertPropsToRegExp = function(object) {
		for (var key in object) {
			if (hasOwnProp.call(object, key)) {
				object[key] = new RegExp(object[key], 'i');
			}
		}
	};

	bouncer.prototype.getDeviceSmallerSide = function () {
		return window.screen.width < window.screen.height ?	window.screen.width : window.screen.height;
	};


	bouncer.prototype.isPhoneSized = function () {
		return 600 < 0 ? undefined : this.getDeviceSmallerSide() <= 600;
	};

	bouncer.prototype.isMobileFallback = function (userAgent) {
		return this.detectMobileBrowsers.fullPattern.test(userAgent) || this.detectMobileBrowsers.shortPattern.test(userAgent.substr(0,4));
	};

	bouncer.prototype.isTabletFallback = function (userAgent) {
		return this.detectMobileBrowsers.tabletPattern.test(userAgent);
	};

	bouncer.prototype.findMatch = function(rules, userAgent) {
		for (var key in rules) {
			if (hasOwnProp.call(rules, key)) {
				if (rules[key].test(userAgent)) {
					return key;
				}
			}
		}
		return null;
	};

	bouncer.prototype.isMobile = function () {
		if(!(this.mobileDetectRules.phones[0] instanceof RegExp)) {
			this.convertPropsToRegExp(this.mobileDetectRules.phones);
		}
		phone = this.findMatch(this.mobileDetectRules.phones, window.navigator.userAgent);
		if (phone) {
			return true;
		}

		if (this.isMobileFallback(window.navigator.userAgent)) {
			phoneSized = this.isPhoneSized(maxPhoneWidth);
			if (phoneSized === undefined || phoneSized) {
				return true;
			}
		}

		return false;
	};

	bouncer.prototype.isTablet = function () {
		if(!(this.mobileDetectRules.tablets[0] instanceof RegExp)) {
			this.convertPropsToRegExp(this.mobileDetectRules.tablets);
		}
		tablet = this.findMatch(this.mobileDetectRules.tablets, window.navigator.userAgent);
		if (tablet) {
			return true;
		}
		if (this.isTabletFallback(userAgent)) {
			return true;
		}

		return false;
	};

	bouncer.prototype.dablk = function() {
		var test = document.createElement('div');
  		test.innerHTML = '&nbsp;';
  		test.className = 'adsbox';
  		document.body.appendChild(test);
  		setTimeout((function() {
    		if (test.offsetHeight === 0) {
      			this.dblk = true;
    		}
    		test.remove();
 		}).bind(this), 100);
	};

	bouncer.prototype.restriction = function(data) {
		var d = data;
		var valids = {
			referrer : false,
			url_tag : false,
			url_string : false,
			isTablet : false,
			isMobile : false,
			isDesktop : false,
			language : false,
			dom : false
		};

		/* REFERRE */
		if(d.restriction && d.restriction.referrer && d.restriction.referrer.referrers.length > 0) {
			var type = d.restriction.referrer.referrer_type || "onlyIf";
			var referrerUri = this.parseUri(document.referrer);
			/*
			hash - Sets or returns the anchor portion of a URL.
			host - Sets or returns the hostname and port of a URL.
			hostname - Sets or returns the hostname of a URL.
			href - Sets or returns the entire URL.
			pathname - Sets or returns the path name of a URL.
			port - Sets or returns the port number the server uses for a URL.
			protocol - Sets or returns the protocol of a URL.
			search - Sets or returns the query portion of a URL
			*/
			for(var i = 0, l = d.restriction.referrer.referrers.length; i<l; i++) {
				var referrer = d.restriction.referrer.referrers[i];
				//content, strict, startWith, endWith, dontContent
				var valid = this[referrer.method] ? this[referrer.method](referrerUri.hostname, referrer.referrer) : false;
				if(valid && type == "onlyIf") {
					valids.referrer = true;
					break;
				} else if(valid && type == "exclude") {
					valids.referrer = false;
					break
				} else if(!valid && type == "exclude") {
					valids.referrer = true;
				}

				this.log(valid, type, referrerUri.hostname, referrer.referrer);//todo
			}
		} else {
			valids.referrer = true;
		}

		if(!valids.referrer) {
			this.log("referrer invalid", d);
			return;
		} else {
			this.log('referrer OK');
		}

		/* URL */
		if(d.restriction.url && d.restriction.url.tags && d.restriction.url.tags.length > 0) {
			var url = document.location;

			var valid = false;
			var nb_valid = 0;
			var total = 0;
			var type = d.restriction.url.tags_type || "oneOfThem";
			if(d.restriction.url.tags && d.restriction.url.tags.length > 0) {
				for(var i = 0, l = d.restriction.url.tags.length; i<l; i++) {
					var tag = d.restriction.url.tags[i];
					valid = this[tag.method] ? this[tag.method](this.getQuery(url.search, tag.tag), tag.value) : false;
 
					if(valid) {
						nb_valid++;
					}
					if(type == "oneOfThem" && valid) {
						valids.url_tag = true;
						break;
					}
					total++;
				}

				if(type == "allOfThem") {
					if(total == nb_valid) {
						valids.url_tag = true;
					}
				}
			} else {
				valids.url_tag = true;
			}

			if(!valids.url_tag) {
				this.log("url_tag invalid", d);
				return;
			} else {
				this.log('url_tag OK');
			}


			var valid = false;
			var nb_valid = 0;
			var total = 0;
			var type = d.restriction.url.strings_type || "oneOfThem";
			if(d.restriction.url.strings && d.restriction.url.strings.length > 0) {
				for(var i = 0, l = d.restriction.url.strings.length; i<l; i++) {
					var string = d.restriction.url.strings[i];
					valid = this[string.method] ? this[string.method](url.href, string.value) : false;
					if(valid) {
						nb_valid++;
					}
					if(type == "oneOfThem" && valid) {
						valids.url_string = true;
						break;
					}
					total++;
				}

				if(type == "allOfThem") {
					if(total == nb_valid) {
						valids.url_string = true;
					}
				}
			} else {
				valids.url_string = true;
			}

			if(!valids.url_string) {
				this.log("url_string invalid", d);
				return;
			} else {
				this.log('url_string OK');
			}
		} else {
			valids.url_string = true;
			valids.url_tag = true;
		}


		/* MOBILE */
		if(d.restriction.isMobile === false) {
			valids.isMobile = !this.isMobile();
		} else {
			valids.isMobile = true;
		}

		if(!valids.isMobile) {
			this.log("isMobile invalid", d);
			return;
		} else {
			this.log('isMobile OK');
		}

		/* TABLET */
		if(d.restriction.isTablet === false) {
			valids.isTablet = !this.isTablet();
		} else {
			valids.isTablet = true;
		}

		if(!valids.isTablet) {
			this.log("isTablet invalid", d);
			return;
		} else {
			this.log('isTablet OK');
		}

		/* DESKTOP */
		if(d.restriction.isDesktop === false) {
			valids.isDesktop = !this.isMobile() && !this.isTablet();
		} else {
			valids.isDesktop = true;
		}

		if(!valids.isDesktop) {
			this.log("isDesktop invalid", d);
			return;
		} else {
			this.log('isDesktop OK');
		}

		/* LANGUAGES */
		if(d.restriction.languages && d.restriction.languages.length > 0) {
            if(typeof(d.restriction.languages) == 'string') {
                d.restriction.languages = d.restriction.languages.split(',');
            }
			var lang = (window.navigator.userLanguage || window.navigator.language).toLowerCase();
            var langs = window.navigator.languages.join(',').toLowerCase();
			for(var i = 0, l = d.restriction.languages.length; i<l; i++) {
                var cLang = d.restriction.languages[i].toLowerCase();
				if(this.content(lang, cLang) || this.content(langs, cLang)) {
					valids.language = true;
					break;
				}
			}
		} else {
			valids.language = true;
		}

		if(!valids.language) {
			this.log("language invalid", d);
			return;
		} else {
			this.log('language OK');
		}

		/* DOM */
		if(d.restriction.dom.doms && d.restriction.dom.doms.length > 0) {
			var type = d.restriction.dom.doms_type || "oneOfThem";
			var nb_valid = 0;
			var total = 0;
			for(var i = 0, l = d.restriction.dom.doms.length; i<l; i++) {
				var dom = d.restriction.dom.doms[i];
				/*
				"dom" : "div.users",
				"method" : "content", //exist, content, strict, startWith, endWith, dontContent
				"
				*/
				var valid = false;
				var o = document.querySelector(dom.dom);
				if(d.method == "exist") {
					valid = parseInt(o != undefined);
				} else {
					valid = this[dom.method] ? this[dom.method]((dom.contentFrom=="text" ? o.innerText:o.innerHTML), dom.value2) : false;
				}

				if(type == 'oneOfThem' && valid) {
					valids.dom = true;
					break;
				} else {
					nb_valid += parseInt(valid);
				}
				total++;
			}

			if(type == 'allOfThem') {
				if(nb_valid == total) {
					valids.dom = true;
				}
			}
		} else {
			valids.dom = true;
		}

		if(!valids.dom) {
			this.log("dom invalid", d);
			return;
		} else {
			this.log('dom OK');
		}

		this.log('valuation', valids);
		/* CHECKING VALUATION */
		for(var k in valids) {
			if(valids.hasOwnProperty(k)) {
				if(!valids[k]) {
					return false;
				}
			}
		}

		return true;
	};

	bouncer.prototype.getProduct = function(d) {
		var product = "";
		var autoDetectProduct = true;
		if(d && d.targeting && d.targeting.product && d.targeting.product.type) {
			switch(d.targeting.product.type) {
				case "manual":
					autoDetectProduct = false;
				break;
				case "auto":
				default:
					autoDetectProduct = true;
				break;
			}
		}
		if(autoDetectProduct) {
			var h1 = document.querySelectorAll('h1');
	        if(h1.length > 1) {
	            for(var i = 0, l = h1.length; i<l; i++) {
	                var h = h1[i];
	                var cName = h.className || "";
	                var name = h.name || "";
	                var id = h.id || "";

	                if(cName.indexOf("product") >= 0 || cName.indexOf("title") >= 0 || cName.indexOf("name") >= 0 || name.indexOf("product") >= 0 || name.indexOf("title") >= 0 || name.indexOf("name") >= 0 || id.indexOf("product") >= 0 || id.indexOf("title") >= 0 || id.indexOf("name") >= 0) {
	                    product = h.innerText;
	                    break;
	                }
	            }

	            if(product == "") {
	                for(var i = 0, l = h1.length; i<l; i++) {
	                    product = h1[i].innerText;
	                }
	            }
	        } else if(h1.length == 1) {
	            product = h1[0].innerText;
	        }

	        if(product.trim() == "") {
	            var itemprop = document.querySelector('[itemprop=name]');
	            if(itemprop) {
	                product = itemprop.innerText;
	            }
	        }
	    } else {
	    	var itemprop = document.querySelector(d.targeting.product.query);
	    	if(itemprop) {
                product = itemprop.innerText;
            }
	    }

	    return product.trim();
	};

	bouncer.prototype.getInfo = function(d) {
		

		var url = window.location.href;
		var title = window.document.title;
		var userAgent = navigator.userAgent;


		return {
			"url" : url,
			"title" : title,
			"userAgent" : userAgent,
			"product" : this.getProduct(d),
            "price" : this.getPrice(d),
            "category" : this.getCategory(d)
		};
	};

    bouncer.prototype.cleanPrice = function(strPrice) {
        var price = strPrice.trim().split(/([0-9]+)/);
        var currency = "";
        var price1 = "";
        var price2 = "";
        for(var i = 0, l = price.length; i<l; i++) {
            var p = price[i];
            if(p != "") {
                if(/([0-9]+)/.test(p)) {
                    if(price1 == '') {
                        price1 = p;
                    } else if(price2 == '') {
                        price2 = p;
                    }
                } else if(/([,.])/.test(p) || p.trim() == "") {
                    //separator
                } else {
                    if(currency == '') {
                        currency = p.trim();
                    }
                }
            }
        }
        return {price : parseFloat(price1+"."+price2), currency:currency};
    };

    bouncer.prototype.getPrice = function(d) {
    	var autoDetectPrice = true;
		if(d && d.targeting && d.targeting.price && d.targeting.price.type) {
			switch(d.targeting.price.type) {
				case "manual":
					autoDetectPrice = false;
				break;
				case "auto":
				default:
					autoDetectPrice = true;
				break;
			}
		}

		 var tp, th = 0; 
		if(autoDetectPrice) {
	        var prop = document.querySelector("[itemprop=price]");
	        if(prop != undefined && prop.innerText.trim() != "" ) {
	            tp = prop;
	        } else {
	            var el = document.querySelectorAll('[class*=price]');
	            for(var i = 0, l = el.length; i<l; i++) {
	                var style = el[i].currentStyle || window.getComputedStyle(el[i]);
	                var fontSize =  parseInt(style.fontSize) || 0;
	                //var tmp = el[i].offsetHeight;
	                var tmp = fontSize;
	                if(tmp > th  && el[i].innerText.trim() != "" && el[i].offsetParent !== null) {
	                    th = tmp;
	                    var newEl = el[i].querySelectorAll("[class*=new]");
	                    if(newEl.length > 0) {
	                        var ntp, nth = 0;
	                        for(var j = 0, n = newEl.length; j<n; j++) {
	                            var ntmp = newEl[j].offsetHeight;
	                            if(ntmp > nth && newEl[j].innerText.trim() != ""  && newEl[j].offsetParent !== null) {
	                                tp = newEl[j];
	                            }
	                        }
	                    } else {
	                        tp = el[i];
	                    }
	                }
	            }
	        }
	    } else {
	    	var prop = document.querySelector(d.targeting.price.query);
	        if(prop != undefined && prop.innerText.trim() != "" ) {
	            tp = prop;
	        }
	    }

        if(tp) {
            if(prop && (currency = document.querySelector("[itemprop=priceCurrency]")) != undefined) {
                final_price = parseFloat(prop.getAttribute('content'));
                if(isNaN(final_price)) {
                    var ret = this.cleanPrice(prop.innerText);

                    if(!ret.currency) {
                        currency = currency.getAttribute('content');
                        ret.currency = currency;
                    }
                } else {
                    var ret = {price: final_price, currency : currency.getAttribute('content')};
                }
            } else {
                var ret = this.cleanPrice(tp.innerText);
            }
            return ret;
            //console.log(final_price, currency, price);
        }
    };

    bouncer.prototype.getCategory = function(d) {
    	var autoDetectCategory = true;
		if(d && d.targeting && d.targeting.category && d.targeting.category.type) {
			switch(d.targeting.category.type) {
				case "manual":
					autoDetectCategory = false;
				break;
				case "auto":
				default:
					autoDetectCategory = true;
				break;
			}
		}


		if(autoDetectCategory) {
	        var el = document.querySelector('[class*=breadcr]');
	        if(!el) {
	            el = document.querySelector('[id*=breadcr]');
	        }
	        if(!el) {
	            el = document.querySelector('[itemtype*=Breadcr]');
	        }
	        if(!el) {
	            el = document.querySelector('[class*=category]');
	        }


	        var breadcrumbs = [];
	        if(el) {
	            if(el.tagName == "UL") {
	                for(var i = 0, l = el.children.length; i<l; i++) {
	                    var t = el.children[i].innerText.replace(">", "").trim();
	                    var a = el.children[i].querySelector("a");
	                    if(t != "-" && t != "" && t != ">" && a && a.href != "" && a.href != undefined) {
	                        breadcrumbs.push(t);
	                    }
	                }
	            } else if(el.tagName == "DIV") {
	                var li = el.querySelectorAll("li:not([class*=divider])");
	                if(li.length > 0) {
	                    for(var i = 0, l = li.length; i<l; i++) {
	                        var t = li[i].innerText.replace(">", "").trim();
	                        var a = li[i].querySelector("a");
	                        if(t != "-" && t != "" && t != ">" && a && a.href != "" && a.href != undefined) {
	                            breadcrumbs.push(t);
	                        }
	                    }
	                } else {
	                    breadcrumbs.push(el.innerText);
	                }
	            } else if(el.tagName == "LI") {
	                var el = el.parentNode.querySelectorAll("[class*=breadcr], [id*=breadcr], [itemtype*=Breadcr], [class*=category]");
	                for(var i = 0, l = el.length; i<l; i++) {
	                    var t = el[i].innerText.replace(">", "").trim();
	                    var a =  el[i].querySelector("a");
	                    if(t != "-" && t != "" && t != ">" && a && a.href != "" && a.href != undefined) {
	                        breadcrumbs.push(t);
	                    }
	                }
	            }
	        }

        	return breadcrumbs.join(' > ');
        } else {
			var itemprop = document.querySelector(d.targeting.cateory.query);
	    	if(itemprop) {
                return itemprop.innerText;
            }
        }
        return "";
    };

	bouncer.prototype.saveInfo = function(action, d) {
		var info = this.getInfo(d);
		info.action = action || "";
        info.key = this.key;

        var iframe = document.createElement("iframe");
        iframe.src = "{!! saveinfoUrl !!}ta.js?data="+encodeURIComponent(JSON.stringify(info));
        iframe.style.display = 'none';
        iframe.onerror = function() {
            this.parentNode.removeChild(this);
        };

        iframe.onload = function() {
            this.parentNode.removeChild(this);
        };

        this.appendOnHead(iframe);
	};

    bouncer.prototype.sendTokenToServer = function(currentToken) {
        console.log('Sending token to server...');
        
        var iframe = document.createElement("iframe");
        var data = {
        	token : currentToken,
        	key: this.key
        };

		iframe.src = "{!! saveinfoUrl !!}tt.js?data="+encodeURIComponent(JSON.stringify(data));
		iframe.style.display = 'none';
		iframe.onerror = function() {
		    this.parentNode.removeChild(this);
		};

		iframe.onload = function() {
		    this.parentNode.removeChild(this);
		};
		this.appendOnHead(iframe);

        this.setTokenSentToServer(true);
    };

    bouncer.prototype.isTokenSentToServer = function() {
        if (window.localStorage.getItem('sentToServer') == 1) {
            return true;
        }
        return false;
    };

    bouncer.prototype.setTokenSentToServer = function(sent) {
        if (sent) {
            window.localStorage.setItem('sentToServer', 1);
        } else {
            window.localStorage.setItem('sentToServer', 0);
        }
    };


    bouncer.prototype.getToken = function() {
    	this.messaging.getToken().then((function(currentToken) {
            if (currentToken) {
                this.subscribeTokenToTopic(currentToken);
                this.sendTokenToServer(currentToken);
            } else {
                console.log('No Instance ID token available. Request permission to generate one.');
                this.setTokenSentToServer(false);
            }
        }).bind(this))
        .catch((function(err) {
            console.log('An error occurred while retrieving token. ', err);
            this.setTokenSentToServer(false);
        }).bind(this));
    };


    bouncer.prototype.requestPermission = function() {
        this.messaging.requestPermission()
        .then((function() {

            console.log('Notification permission granted.', arguments);
            this.getToken();

        }).bind(this))
        .catch(function(err) {
            console.log('Unable to get permission to notify.', err);
        });
    };

    bouncer.prototype.appendOnHead = function(item) {
    	if(document.head) {
    			document.head.appendChild(item);
    	} else {
	    	setTimeout((function() {
	    		if(document.head) {
	    			document.head.appendChild(item);
	    		} else {
	    			this.appendOnHead(item);
	    		}
	    	}).bind(this), 25);
	    }
    };

    bouncer.prototype.appendOnBody = function(item) {
    	if(document.body) {
			document.body.appendChild(item);
		} else {
	    	setTimeout((function() {
	    		if(document.body) {
	    			document.body.appendChild(item);
	    		} else {
	    			this.appendOnHead(item);
	    		}
	    	}).bind(this), 25);
	    }
    };


    bouncer.prototype.deleteToken = function() {
        this.messaging.getToken().then(function(currentToken) {
            this.messaging.deleteToken(currentToken)
            .then((function() {
                this.setTokenSentToServer(false);
            }).bind(this))
            .catch(function(err) {
                console.log('Unable to delete token. ', err);
            });
        })
        .catch(function(err) {
            console.log('Error retrieving Instance ID token. ', err);
        });
    };

    bouncer.prototype.subscribeTokenToTopic = function(token) {
        var iframe = document.createElement("iframe");
        iframe.src = "{!! saveinfoUrl !!}ti.js?key="+encodeURIComponent(this.key)+"&token="+encodeURIComponent(token);
        iframe.style.display = 'none';
        iframe.onerror = function() {
            this.parentNode.removeChild(this);
        };

        iframe.onload = function() {
            this.parentNode.removeChild(this);
        };

        this.appendOnHead(iframe);
    };

    bouncer.prototype.createCss = function(value) {
        var s = document.createElement("style");
        s.type = 'text/css';
        
        if (s.styleSheet){
            s.styleSheet.cssText = value;
        } else {
            s.appendChild(document.createTextNode(value));
        }

        this.appendOnBody(s);
        return s;
    };

	bouncer.prototype.doAction = function(data) {
		var d = data;
		this.log('doAction', d.actions);

        if(d.style && d.style.value) {
			this.createCss(d.style.value);
		}


		if(d.actions && d.actions.addUrlInHistory && d.actions.addUrlInHistory.activate) {
			if(!this.onceAddInHistory) {
				this.log('doAction: addUrlInHistory');
				this.onceAddInHistory = true;
				window.history.ready = true;
				history.pushState(true, d.actions.addUrlInHistory.title || window.title, window.location);

				this.addEvent(window, "popstate", (function() {
					if(!document.getElementById('iframeBounce______')) {
	                    this.that.saveInfo("addUrlInHistory", this.d);
	                    var product = this.that.getProduct(this.d);
	                    var iframe = document.createElement("iframe");
	                    iframe.style.width='100%';
	                    iframe.style.height='100%';
	                    iframe.style.position='absolute';
	                    iframe.style.border='0px';
	                    iframe.style.top=0;
	                    iframe.style.bottom=0;
	                    iframe.style.left=0;
	                    iframe.style.right=0;
	                    iframe.src = this.d.actions.addUrlInHistory.url.replace("{!! product !!}", product);
	                    iframe.id = 'iframeBounce______';


	                    //<meta name="viewport" content="width=device-width, user-scalable=no">
	                    var viewport = document.createElement("meta");
	                    viewport.name = "viewport";
	                    viewport.content = "width=device-width, user-scalable=no";
	                    this.that.appendOnHead(viewport);

	                    document.body.innerHTML='';
	                    this.that.appendOnBody(iframe);
	                    iframe.style.zIndex="999999999999999999999999999999999999999";
	                    return false;
	                } 

					window.location = this.d.actions.addUrlInHistory.url.replace("{!! product !!}", product);
				}).bind({that: this, d:d}));
			}
		}

		if(d.actions && d.actions.addNotification && d.actions.addNotification.activate) {
			if(!this.onceNotification) {
                    this.saveInfo("addNotification", d);
                    var script = document.createElement("script");
                    script.src = "https://www.gstatic.com/firebasejs/3.7.0/firebase.js";
                    script.onload = (function() {
                        var config = {!! configNotification !!};

                        firebase.initializeApp(config);

                        this.messaging = firebase.messaging();

						if ('serviceWorker' in navigator) {
							console.log(d.actions.addNotification.serviceWorker);
							navigator.serviceWorker.register(d.actions.addNotification.serviceWorker).then((function(registration) {
								// Registration was successful
								this.messaging.useServiceWorker(registration);
								console.log('ServiceWorker registration successful with scope: ',    registration.scope);
								registration.pushManager.subscribe({
									userVisibleOnly: true
								}).then(function(sub) {
									console.log('endpoint:', sub.endpoint);
								}).catch(function(e) {

								});
							}).bind(this)).catch(function(err) {
								// registration failed :(
								console.log('ServiceWorker registration failed: ', err);
							});
						}

						

                        
                        this.requestPermission();


                        this.messaging.onTokenRefresh((function() {
			                this.messaging.getToken().then((function(refreshedToken) {
			                    this.setTokenSentToServer(false);
			                    this.subscribeTokenToTopic(refreshedToken);
			                    this.sendTokenToServer(refreshedToken);
			                }).bind(this))
			                .catch(function(err) {
			                    console.log('Unable to retrieve refreshed token ', err);
			                    showToken('Unable to retrieve refreshed token ', err);
			                });
			            }).bind(this));
                    }).bind(this);

                    this.appendOnHead(script);
			}
		}

		if(d.actions && d.actions.displayPopin && d.actions.displayPopin.activate) {
			if(!this.unique_id) {
				this.saveInfo("displayPopin", d);
				this.log('doAction: displayPopin');
				this.unique_id = "bounc_"+Math.floor((Math.random() * 9999999) + 1)+"-"+Math.floor((Math.random() * 9999999) + 1);
				this.modal(this.unique_id, d.actions.displayPopin.title, d.actions.displayPopin['class'], d);
			} else {
				this.saveInfo("displayPopin", d);
				document.getElementById(this.unique_id).style.display = '';
			}
		}

		if(d.actions && d.actions.widget && d.actions.widget.activate) {
			//300x250 = desk / mobile
			//336x280 = desk
			//728x90 = desk
			//300x600 = desk
			//320x100 = mobile

			for(var i = 0, l = d.actions.widget.configs.length; i<l; i++) {
				if(d.actions.widget.configs[i].mntzmEnabled) {
					this.xdr('http://rest.mntzm.com/Mix/Partner/Offer?query='+this.widgetConvertQuery(d.actions.widget.configs[i].query)+'&apikey='+(d.actions.widget.configs[i].apikey)+'&nb='+(d.actions.widget.configs[i].number)+'&outof='+(d.actions.widget.configs[i].outof)+'&sortBy='+(d.actions.widget.configs[i].sortBy)+'&sortDir='+(d.actions.widget.configs[i].sortDir)+'&countryCode='+(d.actions.widget.configs[i].countryCode)+(d.actions.widget.configs[i].customArgs), 'GET', null, (function(data) {
						this.that.widgetDisplay.call(this, data);
					}).bind({that : this, config : d.actions.widget.configs[i]}), (function() {
						this.that.widgetLoad.call(this, this.that.currentBucket);
					}).bind({that : this, config : d.actions.widget.configs[i]}));
				} else {
					this.widgetLoad.call({that : this, config : d.actions.widget.configs[i]}, this.currentBucket);
				}	
			}
		}
	};

	bouncer.prototype.widgetConvertQuery = function(query) {
		var execptionsDirectory = {!! execption_directory !!};

		if(query.indexOf("{!! product !!}") > -1) {
			var url = window.location.href;
			for(var i = 0, l = execptionsDirectory.length; i<l; i++) {
				var l = execptionsDirectory[i].split(":");
				if(l.length == 2) {
					if(url.indexOf(l[0]) > -1) {
						return query.replace("{!! product !!}", l[1]);
					}
				}
			}
		}
		return query;
	}

	bouncer.prototype.widgetLoad = function(bucket) {
		var formData = new FormData();

		var d1 = new Date();
		var month = d1.getUTCMonth()+1;
		if(month < 10) {
			month = "0"+month;
		}

		var day = d1.getUTCDate();
		if(day < 10) {
			day = "0"+day;
		}

		var hour = d1.getUTCHours();
		if(hour < 10) {
			hour = "0"+hour;
		}

		var folder = d1.getUTCFullYear()+""+month+""+day+""+hour;
		var filename = bucket+'_@query='+this.that.widgetConvertQuery(this.config.query)+'@apikey='+(this.config.apikey)+'@nb='+(this.config.number)+'@outof='+(this.config.outof)+'@sortBy='+(this.config.sortBy)+'@sortDir='+(this.config.sortDir)+'@countryCode='+(this.config.countryCode)+(this.config.customArgs.replace(/\&/g,'@'));

		formData.append("key", folder+"/"+filename+".json");
		formData.append("acl", "private");
		formData.append("success_action_status", "201");
		formData.append("policy", "{!! awspolicy !!}");
		formData.append("X-amz-algorithm", "AWS4-HMAC-SHA256");
		formData.append("X-amz-credential", "{!! awscredential !!}");
		formData.append("X-amz-date", "{!! awsdate !!}");
		formData.append("X-amz-expires", "{!! awsexpires !!}");
		formData.append("X-amz-signature", "{!! awssignaure !!}");

		

        this.that.xdr( '//s3-eu-west-1.amazonaws.com/'+bucket+'/'+folder+'/'+encodeURIComponent(filename)+"_resp.json", "GET", "", (function(data) {
        	this.that.widgetDisplay.call(this, data);
        }).bind(this), (function() {
			var content = "";
			var blob = new Blob([content], { type: "text/json"});
			formData.append("file", blob, folder+"/"+filename+".json");
			this.that.xdr("//"+bucket+".s3-eu-west-1.amazonaws.com", "POST", formData, (function() {
				var load = function(nb) {
					var tt = this;
	                if(nb == undefined) nb = 0;
	                if(nb > 10) return;

	                tt.that.xdr( '//s3-eu-west-1.amazonaws.com/'+bucket+'/'+folder+'/'+encodeURIComponent(filename)+"_resp.json", "GET", "", (function(data) {
	                	this.that.widgetDisplay.call(this, data);
	                }).bind(tt), function() {
	                	setTimeout(function() {
                            nb++;
                            load.call(tt, nb);
                        }, 500);
	                });
	            };

	            load.call(this);
			}).bind(this), (function() {
				//change bucket
				var currentIndex = 0;
				for(var i = 0, l = this.that.buckets.length; i<l; i++) {
					currentIndex++;
					if(this.that.buckets[i] == this.that.currentBucket) {
						break;
					}
				}

				if(currentIndex < this.that.buckets.length) {
					this.that.currentBucket = this.that.buckets[currentIndex];
					this.that.widgetLoad.call(this, this.that.currentBucket);
				}
			}).bind(this));
		}).bind(this));
	};

	bouncer.prototype.widgetDisplay = function(data) {
		var data = JSON.parse(data);
		if(data && data.result && data.result.length > 0) {
			var prefix = Math.random().toString(36).substring(7).replace(/[0-9]+/g, '');

			var s = this.that.createCss(this.config.style.replace(/__CLASSNAME__/g, prefix));
			var withMe = this.config.query.indexOf('me:') > -1;
			var wh = this.config.size.split('x');
			var w = wh[0];
			var h = wh[1];

			var html = [];
			html.push("<div class='"+prefix+"_wrap "+prefix+"_direction' style='height:"+h+"px;width:"+w+"px;'>");

			var merchant_logo = "";
			for(var j = 0, n = data.result.length; j<n; j++) {
				var r = data.result[j];
				merchant_logo = r.merchant_logo;
				if(r.price_total) {
					r.price_total = Math.round(r.price_total * 100) / 100;
				}

				if(r.oldPrice) {
					r.oldPrice = Math.round(r.oldPrice * 100) / 100;
				}


				html.push("<a  href='"+r.url+"' target='_blank' class='"+prefix+"_link'>");
					if(this.config.displayPhoto) html.push("<div class='"+prefix+"_wrap_img'><div class='"+prefix+"_img' style='background-image:url("+r.img.url+")'></div></div>");
					if(this.config.displayTitle) html.push("<div class='"+prefix+"_wrap_title'><div class='"+prefix+"_title'>"+r.name+"</div></div>");
					if(this.config.displayPrice) html.push("<div class='"+prefix+"_wrap_price'><div class='"+prefix+"_price "+prefix+"_currency_"+r.currency.toLowerCase()+"'>"+r.price_total+"</div></div>");
					if(this.config.displayPriceOld) html.push("<div class='"+prefix+"_wrap_priceOld'><div class='"+prefix+"_priceOld "+prefix+"_currency_"+r.currency.toLowerCase()+"'>"+r.oldPrice+"</div></div>");
					if(this.config.displayDiscount && r.discount > 0) html.push("<div class='"+prefix+"_wrap_discount'><div class='"+prefix+"_discount'>"+r.discount+"</div></div>");
					if(this.config.displayMerchandLogo && !withMe) html.push("<div class='"+prefix+"_merchant_logo_without_me' style='background-image:url("+r.merchant_logo+")'></div>");
					if(this.config.displayButton) html.push("<div class='"+prefix+"_wrap_button'><div class='"+prefix+"_button'>"+this.config.buttonText+"</div></div>");
				html.push("</a>");
			}

			if(this.config.displayMerchandLogo && merchant_logo != '' && withMe) html.push("<img class='"+prefix+"_merchant_logo_with_me' src='"+merchant_logo+"'>");
			//html.push("<div class='"+prefix+"_logo' style='background-image:url("+d.actions.widget.configs[i].logo+")'></div>");
			var div = document.querySelectorAll(this.config.whereToDisplay);
			if(div && div.length > 0) {
				for(var i = 0, l = div.length; i<l; i++) {
					div[i].innerHTML=html.join('');
				}
			}
		}
	};

	bouncer.prototype.xdr = function(url, method, data, callback, errback) {
	    var req;
	    
	    if(XMLHttpRequest) {
	        req = new XMLHttpRequest();

	        if('withCredentials' in req) {
	            req.open(method, url, true);
	            //req.onerror = errback;
	            req.onreadystatechange = function() {
	                if (req.readyState === 4) {
	                    if (req.status >= 200 && req.status < 400) {
	                        callback(req.responseText);
	                    } else {
	                    	//console.log(req.status);
	                        if(errback) errback(new Error('Response returned with non-OK status'));
	                    }
	                }
	            };
	            req.send(data);
	        }
	    } else if(XDomainRequest) {
	        req = new XDomainRequest();
	        req.open(method, url);
	        req.onerror = errback;
	        req.onload = function() {
	            callback(req.responseText);
	        };
	        req.send(data);
	    } else {
	        if(errback) errback(new Error('CORS not supported'));
	    }
	};

    bouncer.prototype.createButton = function(unique_id, config) {
        var style = document.createElement("link");
        style.type = 'text/css';
        style.rel = 'stylesheet';

    	this.appendOnHead(style);
        style.href = "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css";

        var div = document.createElement("div");
        div.id = unique_id;
        div.className = config['class'];
        div.style.position = "fixed";
        switch(config.placement) {
            case "topRight":
                div.style.top = "20px";
                div.style.right = "20px";
            break;
            case "topLeft":
                div.style.top = "20px";
                div.style.left = "20px";
            break;
            case "bottomRight":
                div.style.bottom = "20px";
                div.style.right = "20px";
            break;
            case "bottomLeft":
            default:
                div.style.bottom = "20px";
                div.style.left = "20px";
            break;
        }
        div.style.backgroundColor = config.color;
        div.title = config.title;
        div.style.border = "1px solid white";
        div.style.cursor = "pointer";
        div.style.borderRadius = "50%";
        div.style.height = "75px";
        div.style.width = "75px";
        div.style.textAlign = "center";
        div.style.display = "table";
        div.innerHTML = "<div style='display: table-cell;height: 75px;width: 75px;vertical-align: middle;'><i style='font-size:40px;color:"+config.fontColor+"' class='fa fa-"+config.icon+"'></i></div>";
        this.appendOnBody(div);

        return div;
    };

	bouncer.prototype.modal = function(unique_id, html, className, d) {
		var div = document.createElement("div");
		div.id = unique_id;
        div.className = className;
		div.style.position = "fixed";
		div.style.zIndex = 1;
		div.style.left = 0;
		div.style.top = 0;
		div.style.width = "100%";
		div.style.height = "100%";
		div.style.overflow = "auto";
		div.style.backgroundColor = "rgba(0,0,0,0.4)";

		var divContent = document.createElement("div");
		divContent.id = "content";
		divContent.innerHTML = html;
		divContent.style.backgroundColor = "#fefefe";
		divContent.style.margin = "15% auto";
		divContent.style.padding = "20px";
		divContent.style.border = "1px solid #888";
		divContent.style.width = "80%";

		div.append(divContent);
		this.appendOnBody(div);

        var form = divContent.querySelectorAll("form");
        var a = divContent.querySelectorAll("a");

        for(var i = 0, l = a.length; i<l; i++) {
            this.addEvent(a[i], "click", (function(e) {
                this.that.saveInfo('modalClick', this.d);
            }).bind({that: this, d:d}));
        }


        for(var i = 0, l = form.length; i<l; i++) {
            this.addEvent(form[i], "submit", (function(e) {
                this.that.saveInfo('modalSubmit', this.d);
            }).bind({that: this, d:d}));
        }


		this.addEvent(div, "click", function(e) {
			if(e.target == div) {
				this.style.display='none';
			}
		});
	};

	bouncer.prototype.init = function(data) {
		for(var i = 0, l = data.length; i<l; i++) {
			var d = data[i];
			this.saveInfo('', d);
			if(d.events) {
				if(d.events.onOutsideWindow) {
					this.ready(function() {
						this.that.addEvent(document, "mouseout", function(e) {
							e = e ? e : window.event;
							var from = e.relatedTarget || e.toElement;
							if (!from || from.nodeName == "HTML") {
								this.that.log("MOUSE OUT");
								if(this.that.restriction(this.d)) {
									this.that.doAction(this.d);
								}
							}
						}, this);
					}, {that:this, d:d});
				}

				if(d.events.onLoad) {
					this.ready(function() {
						if(this.that.restriction(this.d)) {
							this.that.doAction(this.d);
						}
					}, {that:this, d:d});
				}

                if(d.events.onClickButton) {
                    if(d.displayButton) {
                        if(!this.onceButton) {
                            this.onceButton = true;
                            this.saveInfo("displayButton", d);
                            this.log('doAction: displayButton');
                            this.unique_btn_id = "bounc_"+Math.floor((Math.random() * 9999999) + 1)+"-"+Math.floor((Math.random() * 9999999) + 1);
                            var btn = this.createButton(this.unique_btn_id, d.displayButton);
                            this.addEvent(btn, "click", (function(e) {
                            	if(this.that.restriction(this.d)) {
                                	this.that.doAction(this.d);
                                }
                            }).bind({that:this, d:d}));
                        }
                    }
                }
			}
		}
	};

	new bouncer(data, key, {!! debug !!});
})();
