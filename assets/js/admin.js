const getUrlParams = () => {
    const defaultParams = {
        profile: goostavApplicationConfig.activeBeProfile,
        platform: 'WOO_COMMERCE',
        plugin_version: goostavApplicationConfig.pluginVersion
    };

    if (goostavApplicationConfig.accessToken !== '') {
        return {
            defaultParams,
            additionalParams: {
                accessToken: goostavApplicationConfig.accessToken,
            }
    }
    } else {
        return {
            defaultParams,
            additionalParams: {
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
            }
        }
    }
};

const buildGoostavUrl = () => {
    const urlBase = 'https://goostav-fe.roihunter.com/?payload=';
    const params = getUrlParams();
    const defaultParams = Object.keys(params.defaultParams).map(key => `${encodeURIComponent(key)}=${encodeURIComponent(params.defaultParams[key])}`).join('&');
    const additionalParams = JSON.stringify(params.additionalParams);
    return urlBase + window.btoa(unescape(encodeURIComponent(additionalParams))) + '&' + defaultParams;
};

document.addEventListener("DOMContentLoaded", function() {
    const button = document.getElementById('roi-goto-goostav');
    button.addEventListener('click', function () {
        window.open(buildGoostavUrl());
    });
});
