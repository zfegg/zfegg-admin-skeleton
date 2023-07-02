const {createProxyMiddleware} = require('http-proxy-middleware');

module.exports = function(app) {
    // process.exit()
    app.use(
        ['/api','/zfegg'],
        createProxyMiddleware({
            // target: 'http://localhost/zfegg/application-skeleton/backend/public',
            // target: 'http://172.31.226.255:8000',
            target: 'http://localhost:5000',
            changeOrigin: true,
            onProxyReq: function(proxyReq, req) {
                proxyReq.setHeader("host", req.get('host'));
            },
            // host: 'datalocal.zfegg.com',
        }),
    )
};
