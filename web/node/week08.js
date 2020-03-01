// var http = require('http');

// function onRequest(req, res) {
// 	if( req.url === '/home') {
// 		res.writeHead(200, {'Content-Type': 'text/html'});
// 		res.write("<h1>Welcome to the home page!</h1>");
// 	} else if (req.url === '/getData') {
// 		res.writeHead(200, {'Content-Type': 'application/json'});
// 		res.write('{"name":"Jim White","class":"cs313"}')
// 	} else {
// 		res.writeHead(404, {'Content-Type': 'text/html'});
// 		res.write('Page not found');
// 	}
// };

// http.createServer(onRequest (req, res) {
//   onRequest(req, res);
//   res.end();
// }).listen(8080);

var http = require('http');

function onRequest(req, res) {
	if( req.url === '/home') {
		res.writeHead(200, {'Content-Type': 'text/html'});
		res.write("<h1>Welcome to the home page!</h1>");
		res.end();
	} else if (req.url === '/getData') {
		res.writeHead(200, {'Content-Type': 'application/json'});
		res.write('{"name":"Jim White","class":"cs313"}');
		res.end();
	} else {
		res.writeHead(404, {'Content-Type': 'text/html'});
		res.write('Page not found');
		res.end();
	}
}

http.createServer(onRequest).listen(8080);