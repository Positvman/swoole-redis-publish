<?php

if (!isset($_GET['uid'])) {
  exit('Invalid Query Params "uid"');
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Web Socket</title>
<style>
ul li{ font-size: 12px; list-style-type: decimal; font-family: Monaco; }
ul li.warning{ color: #f00; }
ul li.default{ color: #999; }
</style>
</head>
<body>
<button onclick="connect()">connect</button>
<button onclick="disconnect()">Disconnect</button>
<ul></ul>
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script src="//cdn.bootcss.com/reconnecting-websocket/1.0.0/reconnecting-websocket.min.js"></script>
<script>
var ws;
var ul = $('ul');

var connect = function() {
  ws = new ReconnectingWebSocket('ws://192.168.1.135:9501', null, {
    debug: true,
    reconnectInterval: 10000
  });

  ws.onopen = function (event) {
    var data = {
      uid: '<?php echo $_GET['uid']; ?>',
      type: 'bind'
    };
    ws.send(JSON.stringify(data));
  };

  ws.onmessage = function (event) {
    console.log(event.data, ul);
    ul.append('<li>'+ event.data +'</li>');
  };

  ws.onclose = function (event) {
    console.log('onclose');
  };

  ws.onerror = function (event, error) {
    console.log('onerror', event.data);
  };
}

connect();

var disconnect = function() {
  ws.close();
}
</script>
</body>
</html>