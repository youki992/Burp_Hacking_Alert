<?php

function __($message) {
	$messages = array(
		'User Agent' => '访客 UA',
		'GeoIP' => '直连 IP',
		'Time' => '当前时间',
		
	);
	if (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) === 'zh') {
		print isset($messages[$message]) ? $messages[$message] : $message;
	} else {
		print $message;
	}
}

function get_protocol(){
    return $_SERVER["SERVER_PROTOCOL"];
}

function get_method(){
    return $_SERVER["REQUEST_METHOD"];
}

function get_remote_addr()
{
	if (isset($_SERVER["HTTP_X_REAL_IP"])) {
		return $_SERVER["HTTP_X_REAL_IP"];
	} else if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		return preg_replace('/^.+,\s*/', '', $_SERVER["HTTP_X_FORWARDED_FOR"]);
	} else {
		return $_SERVER["REMOTE_ADDR"];
	}
}

function get_user_agent()
{
   return $_SERVER["HTTP_USER_AGENT"]; 
}

function my_json_encode($a=false)
{
	if (is_null($a))
		return 'null';
	if ($a === false)
		return 'false';
	if ($a === true)
		return 'true';
	if (is_scalar($a)) {
		if (is_float($a)) {
			return floatval(str_replace(',', '.', strval($a)));
		}
		if (is_string($a)) {
			static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
			return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
		}
		else
			return $a;
	}
	$isList = true;
	for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
		if (key($a) !== $i) {
			$isList = false;
			break;
		}
	}
	$result = array();
	if ($isList) {
		foreach ($a as $v)
			$result[] = my_json_encode($v);
		return '[' . join(',', $result) . ']';
	} else {
		foreach ($a as $k => $v)
			$result[] = my_json_encode($k).':'.my_json_encode($v);
		return '{' . join(',', $result) . '}';
	}
}

if (!function_exists('json_encode')) {
	function json_encode($a) {
		return my_json_encode($a);
	}
}

$stime = date('Y年m月d日 H:i:s');
$protocol = get_protocol();
$method = get_method();
$remote_addr = get_remote_addr();
$ua = get_user_agent();

@header("Content-Type: text/html; charset=utf-8");

?><!DOCTYPE html>
<meta charset="utf-8">
<title>请求信息</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<meta name="keywords" content="获取 UA,User Agent,获取 IP">
<meta name="description" content="查看自己的 IP 和 UA。">
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon"   href="favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css"> 
<style>
html {
	scroll-behavior: smooth;
}
body {
	margin: 0;
	font-family: "Helvetica Neue", "Luxi Sans", "DejaVu Sans", Tahoma, "Hiragino Sans GB", "Microsoft Yahei", sans-serif;
}
h2 {
	text-align: center;
}
.container {
	padding-right: 25px;
	padding-left: 25px;
	margin-right: auto;
	margin-left: auto;
}
@media(min-width:768px) {
	.container {
		max-width: 750px;
	}
}
@media(min-width:992px) {
	.container {
		max-width: 970px;
	}
}
@media(min-width:1200px) {
	.container {
		max-width: 1170px;
	}
}
</style>
<div class="header d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 border-bottom shadow-sm">
      <h5 class="container my-0 mr-md-auto font-weight-normal">请求信息</h5></div>
<h2 >请求信息</h2>
<div class="container">

<table>
	<tr>
	<th colspan="6"><?php __('请求信息'); ?></th>
	</tr>
	<tr>
	<td nowrap><?php __('Time'); ?></td>
	<td colspan="4"><span id="stime"><?php echo $stime;?></span></td>
	</tr>
	<tr>
	<td nowrap><?php __('请求方法'); ?></td>
	<td colspan="4"><?php echo $method; file_put_contents("ip.txt",$method,FILE_APPEND);?>
	</td>
	</tr>
    <tr>
	<td nowrap><?php __('请求协议'); ?></td>
	<td colspan="4"><?php echo $protocol; ?>
	</td>
	</tr>
	<tr>
	<td nowrap><?php __('GeoIP'); ?></td>
	<td colspan="4">
		<span id="remoteip"><?php echo $remote_addr; file_put_contents("ip.txt","\n--------Burp hacker\n",FILE_APPEND); file_put_contents("ip.txt",$remote_addr,FILE_APPEND);?></span>
		<span id="IPloc"></span>
	</td>
	</tr>
	<tr>
	<td nowrap>内网 IP</td>
	<td colspan="4">
		<span id="NatIP"></span>
	</td>
	</tr>

	<tr>
	<td nowrap>代理 IP</td>
	<td colspan="4">
		<span id="ipp"></span>
	</td>
	</tr>	
	<tr>
	<td nowrap>出国 IP</td>
	<td colspan="4">
    <span id="ipw"></span>
	</td>
	</tr>	
	<tr>
	<td nowrap><?php __('User Agent'); ?></td>
	<td colspan="4"><?php echo $ua; file_put_contents("ip.txt",$ua,FILE_APPEND);?>
	</td>
	</tr>
	
</table>

</div>

    <div class="footer">
    <div class="container">
        <b>© 2021 <a href="http://www.nange.cn" style="color: #4caf50;" target="_blank">楠格</a>
    </div>
</div>
    <script>
      var userAgent = navigator.userAgent;
      if (userAgent.indexOf("Edge") > -1) {
        document.getElementById("NW_IP").style.display="none";
      }
      // NOTE: window.RTCPeerConnection is "not a constructor" in FF22/23
      var RTCPeerConnection = /*window.RTCPeerConnection ||*/ window.webkitRTCPeerConnection || window.mozRTCPeerConnection;

      if (RTCPeerConnection) (function () {
        var rtc = new RTCPeerConnection({iceServers:[]});
        if (1 || window.mozRTCPeerConnection) {      // FF [and now Chrome!] needs a channel/stream to proceed
          rtc.createDataChannel('', {reliable:false});
        };

        rtc.onicecandidate = function (evt) {
          // convert the candidate to SDP so we can run it through our general parser
          // see https://twitter.com/lancestout/status/525796175425720320 for details
          if (evt.candidate) grepSDP("a="+evt.candidate.candidate);
        };
        rtc.createOffer(function (offerDesc) {
          grepSDP(offerDesc.sdp);
          rtc.setLocalDescription(offerDesc);
        }, function (e) { console.warn("offer failed", e); });

        var addrs = Object.create(null);
        addrs["0.0.0.0"] = false;
        function updateDisplay(newAddr) {
          if (newAddr in addrs) return;
          else addrs[newAddr] = true;
          var displayAddrs = Object.keys(addrs).filter(function (k) { return addrs[k]; });
          document.getElementById('NatIP').textContent = displayAddrs.join(" or perhaps ") || "n/a";
        }

        function grepSDP(sdp) {
          var hosts = [];
          sdp.split('\r\n').forEach(function (line) { // c.f. http://tools.ietf.org/html/rfc4566#page-39
            if (~line.indexOf("a=candidate")) {     // http://tools.ietf.org/html/rfc4566#section-5.13
              var parts = line.split(' '),        // http://tools.ietf.org/html/rfc5245#section-15.1
                addr = parts[4],
                type = parts[7];
              if (type === 'host') updateDisplay(addr);
            } else if (~line.indexOf("c=")) {       // http://tools.ietf.org/html/rfc4566#section-5.7
              var parts = line.split(' '),
                addr = parts[2];
              updateDisplay(addr);
            }
          });
        }
      })(); else {
        document.getElementById("NW_IP").style.display="none";
        //document.getElementById('Nat_IP').innerHTML = "<code>ifconfig | grep inet | grep -v inet6 | cut -d\" \" -f2 | tail -n1</code>";
        //document.getElementById('Nat_IP').nextSibling.textContent = "In Chrome and Firefox your IP should display automatically, by the power of WebRTCskull.";
      }
  
    </script>


<style>
table {
	width: 100%;
	max-width: 100%;
	border: 1px solid #5cb85c;
	padding: 0;
	border-collapse: collapse;
}
table th {
	font-size: 15px;
}
table tr {
	border: 1px solid #5cb85c;
	padding: 5px;
}
table th {
	background: #5cb85c
}
table th, table td {
	border: 1px solid #5cb85c;
	font-size: 15px;
	line-height: 20px;
	padding: 5px;
	text-align: left;
}
table.table-hover > tbody > tr:hover > td,
table.table-hover > tbody > tr:hover > th {
	background-color: #f5f5f5;
}

}
</style>

<script type="text/javascript">
var dom = {
	element: null,
	get: function (o) {
		function F() { }
		F.prototype = this
		obj = new F()
		obj.element = (typeof o == "object") ? o : document.createElement(o)
		return obj
	},
	width: function (w) {
		if (!this.element)
			return
		this.element.style.width = w
		return this
	},
	html: function (h) {
		if (!this.element)
			return
		this.element.innerHTML = h
		return this
	}
};

$ = function(s) {
	return dom.get(document.getElementById(s.substring(1)))
};

$.getData = function (url, f) {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', url, true)
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			f(xhr.responseText)
		}
	}
	xhr.send()
}

$.getJSON = function (url, f) {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', url, true)
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			if (window.JSON) {
				f(JSON.parse(xhr.responseText))
			} else {
				f((new Function('return ' + xhr.responseText))())
			}
		}
	}
	xhr.send()
}

$.postJSON = function (url, f) {
	var xhr = new XMLHttpRequest();
	xhr.open('POST', url, true)
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			if (window.JSON) {
				f(JSON.parse(xhr.responseText))
			} else {
				f((new Function('return ' + xhr.responseText))())
			}
		}
	}
	xhr.send()
}
/*text 数据类型*/
function getIPloc() {
	$.getData('https://myip.ipip.net/', function (data) {
		remoteip = document.getElementById('remoteip').innerText
		ip = data.match(/\d+\.\d+\.\d+\.\d+/)[0]
		iploc = data.substring(ip.length+12)
		if (ip !== remoteip) {
			$("#IPloc").html('（检测到分流代理，真实 IP '+ ip + ' | '+ iploc+'）')
		} else {
			$("#IPloc").html('| '+iploc)
		}
	})
}

/*json 数据类型*/
/*
function getIPloc() {
	$.getJSON('https://myip.ipip.net/json', function (data) {
		remoteip = document.getElementById('remoteip').innerText
		ip = data.data.ip
		iploc = data.data.location
		if (ip !== remoteip) {
			$("#IPloc").html('（检测到分流代理，真实 IP '+ ip + ' | '+ iploc+'）')
		} else {
			$("#IPloc").html('| '+iploc)
		}
	})
}
*/
/*
function getIPp() {
	$.getJSON('https://extreme-ip-lookup.com/json', function (data) {
			$("#ipp").html(data.query+' | '+data.countryCode+' | '+data.city+' | '+data.org)
	})
}
*/
function getIPp() {
	$.getJSON('https://ipapi.co/json', function (data) {
			$("#ipp").html(data.ip+' | '+data.country_code+' | '+data.region+' | '+data.city+' | '+data.asn+' '+data.org)	
	})
}

function getIPw() {
    var token='1632d503de4a10';
	$.getJSON('https://ipinfo.io/json?token='+token, function (data) {
			$("#ipw").html(data.ip+' | '+data.country+' | '+data.region+' | '+data.city+' | '+data.org)
	})
}
/*
function getIPw() {
    var token='1632d503de4a10';
	$.getJSON('https://ipwhois.app/json', function (data) {
			$("#ipw").html(data.ip+' | '+data.country_flag+' | '+data.country_code+' | '+data.city+' | '+data.org)
	})
}
*/
function getSysinfo() {
	$.getJSON('?method=sysinfo', function (data) {
		$('#stime').html(data.stime)
	});
}

window.onload = function() {
	setTimeout(getIPloc, 0)
	setTimeout(getIPw, 0)
	setTimeout(getIPp, 0)
	/*setInterval(getSysinfo, 1000)*/
}

</script>
