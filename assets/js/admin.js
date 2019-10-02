const getUrlParams = () => {
    const defaultParams = {
        profile: goostavApplicationConfig.activeBeProfile,
        platform: 'WOO_COMMERCE',
        plugin_version: goostavApplicationConfig.pluginVersion
    };

    if (goostavApplicationConfig.accessToken !== '') {
        return {
            accessToken: goostavApplicationConfig.accessToken,
            ...defaultParams
        }
    } else {
        return {
            client_token: goostavApplicationConfig.clientToken,
            website_url: goostavApplicationConfig.storeUrl,
            release_platform: 'WOO_COMMERCE',
            shop_id: window.location.hostname,
            // shop_id: goostavApplicationConfig.customerId,
            source_feed_url: goostavApplicationConfig.previewUrl,
            source_feed_username: goostavApplicationConfig.wooCommerceApiKey,
            source_feed_password: goostavApplicationConfig.wooCommerceApiSecret,
            currency: goostavApplicationConfig.storeCurrency,
            website_name: goostavApplicationConfig.storeName,
            api_base_url: goostavApplicationConfig.rhStateApiBaseUrl,
            language: goostavApplicationConfig.storeLanguage,
            country_code: goostavApplicationConfig.storeCountry,
            ...defaultParams
        }
    }
};

const buildGoostavUrl = () => {
    const urlBase = 'https://goostav-fe.roihunter.com/?';
    const urlParams = getUrlParams();

    console.log(urlParams);

    const stringifiedParams = Object.keys(urlParams).map(key => `${encodeURIComponent(key)}=${encodeURIComponent(urlParams[key])}`).join('&');
    return urlBase + stringifiedParams;
};

document.addEventListener("DOMContentLoaded", function() {
    const button = document.getElementById('roi-goto-goostav');
    button.addEventListener('click', function () {
        window.open(buildGoostavUrl());
    });
});
