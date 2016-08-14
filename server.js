var http = require('http')
var url = require('url')
var fs = require('fs')

http.createServer(function (request, response) {
   var requestUrl = url.parse(request.url)    
   response.writeHead(200)
   fs.createReadStream(requestUrl.pathname).pipe(response)
}).listen(8080)