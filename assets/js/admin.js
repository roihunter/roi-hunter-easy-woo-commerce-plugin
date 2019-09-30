const buildGoostavUrl = () => {
    const urlBase = 'https://goostav-fe-staging.roihunter.com/';
    const urlParams = {
        activeBeProfile: 'production',
        accessToken: goostavApplicationConfig.accessToken,
        wooCommerceApiUrl: goostavApplicationConfig.wooCommerceApiUrl,
        wooCommerceApiKey: goostavApplicationConfig.wooCommerceApiKey,
        wooCommerceApiSecret: goostavApplicationConfig.wooCommerceApiSecret,
        platform: 'WOOCOMMERCE'
    };

    const strigifiedParams = Object.keys(urlParams).map(key => `${encodeURIComponent(key)}=${encodeURIComponent(urlParams[key])}`).join('&');
    return urlBase + strigifiedParams;
};

document.addEventListener("DOMContentLoaded", function() {
    const button = document.getElementById('roi-goto-goostav');
    button.addEventListener('click', function () {
        window.open(buildGoostavUrl());
    });
});
