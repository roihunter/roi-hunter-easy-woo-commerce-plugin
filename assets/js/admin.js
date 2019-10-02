const getUrlParams = () => {
    const defaultParams = {
        profile: goostavApplicationConfig.activeBeProfile,
        platform: 'WOO_COMMERCE',
        plugin_version: goostavApplicationConfig.pluginVersion
    };

    if (goostavApplicationConfig.accessToken !== '') {
        return {
            ...defaultParams,
            payload: {
                accessToken: goostavApplicationConfig.accessToken,
            }
        }
    } else {
        return {
            ...defaultParams,
            payload: {
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

const preparePayload = (payloadObject) => {
    return window.btoa(
            unescape(
                encodeURIComponent(
                    JSON.stringify(payloadObject)
                )
            )
        )
};

const buildGoostavUrl = () => {
    const urlBase = 'https://goostav-fe.roihunter.com/?';
    const urlParams = new URLSearchParams();
    const params = getUrlParams();

    for(let param in params) {
        urlParams.append(param, (typeof params[param] === 'string') ? params[param] : preparePayload(params[param]));
    }

    return urlBase + urlParams;
};

document.addEventListener("DOMContentLoaded", function() {
    const button = document.getElementById('roi-goto-goostav');
    button.addEventListener('click', function () {
        window.open(buildGoostavUrl());
    });
});
