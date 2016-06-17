define(['jquery'], function ($) {
    return function (App) {
        var homeContainer = $('<div>Hello</div>').click(function () {
            console.log('Home');
        });
        App.router.route('/', function () {
            App.layout.renderContent({
                title: "Home",
                content:homeContainer
            });
        });
    };
});