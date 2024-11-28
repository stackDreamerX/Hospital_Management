(function(networkId) {
var automaticIntegrations = {"googleAnalytics":{"paramName":"g_cid"}};

var cacheLifetimeDays = 30;

var customDataWaitForConfig = [
  { on: function() { return Invoca.Client.parseCustomDataField("calling_page", "Last", "JavascriptDataLayer", "location.href"); }, paramName: "calling_page", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("calling_url", "Last", "JavascriptDataLayer", "window.location.hostname + window.location.pathname"); }, paramName: "calling_url", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("customer_journey", "Multi", "JavascriptDataLayer", "location.pathname"); }, paramName: "customer_journey", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("gclid", "Last", "URLParam", ""); }, paramName: "gclid", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("invoca_destination_friendly_name", "Last", "URLParam", ""); }, paramName: "invoca_destination_friendly_name", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("landing_page", "First", "JavascriptDataLayer", "window.location.href"); }, paramName: "landing_page", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("landing_page_exp", "Last", "URLParam", ""); }, paramName: "landing_page_exp", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("msclkid", "Last", "URLParam", ""); }, paramName: "msclkid", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("ttd_id", "Last", "JavascriptDataLayer", "window._TTDId"); }, paramName: "ttd_id", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("utm_campaign", "Last", "URLParam", ""); }, paramName: "utm_campaign", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("utm_content", "Last", "URLParam", ""); }, paramName: "utm_content", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("utm_inv", "Last", "URLParam", ""); }, paramName: "utm_inv", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("utm_medium", "Last", "URLParam", ""); }, paramName: "utm_medium", fallbackValue: function() { return Invoca.PNAPI.currentPageSettings.poolParams.utm_medium || null; } },
  { on: function() { return Invoca.Client.parseCustomDataField("utm_source", "Last", "URLParam", ""); }, paramName: "utm_source", fallbackValue: function() { return Invoca.PNAPI.currentPageSettings.poolParams.utm_source || null; } },
  { on: function() { return Invoca.Client.parseCustomDataField("utm_term", "Last", "URLParam", ""); }, paramName: "utm_term", fallbackValue: null }
];

var customDataWaitForConfigAnonymousFunctions = [
  { on: function() { return Invoca.Client.parseCustomDataField("calling_page", "Last", "JavascriptDataLayer", function() { return (location.href); }) }, paramName: "calling_page", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("calling_url", "Last", "JavascriptDataLayer", function() { return (window.location.hostname + window.location.pathname); }) }, paramName: "calling_url", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("customer_journey", "Multi", "JavascriptDataLayer", function() { return (location.pathname); }) }, paramName: "customer_journey", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("landing_page", "First", "JavascriptDataLayer", function() { return (window.location.href); }) }, paramName: "landing_page", fallbackValue: null },
  { on: function() { return Invoca.Client.parseCustomDataField("ttd_id", "Last", "JavascriptDataLayer", function() { return (window._TTDId); }) }, paramName: "ttd_id", fallbackValue: null }
];

var defaultCampaignId = "975235";

var destinationSettings = {
  paramName: "invoca_detected_destination",
  matchLocalNumbers: false,
  matchTollFreeNumbers: false
};

var numbersToReplace = {
  "+12164421914": "1003116"
};

var organicSources = true;

var reRunAfter = 1000;

var requiredParams = {"utm_inv":"*"};

var resetCacheOn = [];

var waitFor = 0;

var customCodeIsSet = (function() {
  Invoca.Client.customCode = function(options) {
    options.integrations.googleAnalytics = true;

if (window.OpenId) {
    console.log("OpenId already defined!");
} else {
    window.OpenID = {
        getIds: function(data) {
            console.log("Setting data from adsrvr", data);
           window._TTDId = data.TDID;
        }
   };
}
window.getTTDId = function() {
    return window._TTDId;
};
var ttdUrl = 'https://match.adsrvr.org/track/rid?v=1.0.0&ttd_pid=i6t6mlg&type=javascript';
function noop()  { console.log("SUCCESS") };
function noop2() { console.log("FAIL") };
Invoca.PNAPI.loadScript(ttdUrl, noop, noop2);
return options;
  };

  return true;
})();

var generatedOptions = {
  active:              true,
  autoSwap:            true,
  cookieDays:          cacheLifetimeDays,
  country:             "US",
  dataSilo:            "us",
  defaultCampaignId:   defaultCampaignId,
  destinationSettings: destinationSettings,
  disableUrlParams:    [],
  doNotSwap:           ["216-587-8043"],
  integrations:        automaticIntegrations,
  maxWaitFor:          waitFor,
  networkId:           networkId || null,
  numberToReplace:     numbersToReplace,
  organicSources:      organicSources,
  poolParams:          {},
  reRunAfter:          reRunAfter,
  requiredParams:      requiredParams,
  resetCacheOn:        resetCacheOn,
  waitForData:         customDataWaitForConfig,
  waitForDataAnonymousFunctions:  customDataWaitForConfigAnonymousFunctions
};

Invoca.Client.startFromWizard(generatedOptions);

})(1811);
