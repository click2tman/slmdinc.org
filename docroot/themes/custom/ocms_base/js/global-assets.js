/**
 * @file
 * For loading external assets such as header and footer.
 */

$(function() {
  // The base path to the asset pages.
  var baseAssetOrigin = _IRS.baseAssetOrigin;
  // The base path used in replacing relative paths.
  var baseDomain = _IRS.baseDomain;
  // Assets to prepend.
  var prependAssets = _IRS.prependAssets;
  var includeStyles = _IRS.includeStyles;
  // Available assets.
  var globalAssets = {
    header: {
      assetPath: baseAssetOrigin + '/assets/universal/header',
      assetElement: '#navbar'
    },
    footer: {
      assetPath: baseAssetOrigin + '/assets/universal/footer',
      assetElement: 'footer'
    }
  };
  var stylePaths = [
    '/themes/custom/ocms_base/css/style.min.css',
    '/core/modules/system/css/components/hidden.module.css'
  ];
  var scriptPaths = [
    // Scripts array to append.
  ];

  // Include scripts and stylesheets.
  if (includeStyles) {
    // Stylesheets.
    for (var i = 0; i < stylePaths.length; i++) {
      var styleElement = document.createElement('link');
      styleElement.type = 'text/css';
      styleElement.rel = 'stylesheet';
      styleElement.href = baseAssetOrigin + stylePaths[i];
      $('head').append(styleElement);
    }
    // Scripts.
    for (var j = 0; j < scriptPaths.length; j++) {
      var scriptElement = document.createElement('script');
      scriptElement.type = 'text/javascript';
      scriptElement.src = baseAssetOrigin + scriptPaths[j];
      $('head').append(scriptElement);
    }
  }

  // Prepend assets.
  Object.keys(prependAssets).forEach(function(asset) {
    if (globalAssets[asset]) {
      $.ajax({
        url: globalAssets[asset].assetPath
      })
        .done(function(content) {
          var element = $(content).filter(globalAssets[asset].assetElement);
          $(prependAssets[asset]).prepend(element);
          $(globalAssets[asset].assetElement + ' a[href^="/"]').attr('href', addBaseDomain);
          $(globalAssets[asset].assetElement + ' img[src^="/"]').attr('src', addBaseDomain);
        });
    }

  });

  // Add base domain to relative paths.
  function addBaseDomain(_idx, oldHref) {
    return oldHref.replace(/^\//, baseDomain + '/');
  }
});
