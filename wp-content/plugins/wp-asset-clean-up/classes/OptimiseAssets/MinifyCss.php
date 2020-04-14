<?php
namespace WpAssetCleanUp\OptimiseAssets;

use WpAssetCleanUp\CleanUp;
use WpAssetCleanUp\Main;
use WpAssetCleanUp\Menu;
use WpAssetCleanUp\MetaBoxes;

/**
 * Class MinifyCss
 * @package WpAssetCleanUp\OptimiseAssets
 */
class MinifyCss
{
	/**
	 * @param $cssContent
	 * @param bool $forInlineStyle
	 *
	 * @return string
	 */
	public static function applyMinification($cssContent, $forInlineStyle = false)
	{
		if (class_exists('\MatthiasMullie\Minify\CSS')) {
				$minifier = new \MatthiasMullie\Minify\CSS( $cssContent );

				if ( $forInlineStyle ) {
					// If the minification is applied for inlined CSS (within STYLE)
					// Leave the background URLs unchanged as it sometimes lead to issues
					$minifier->setImportExtensions( array() );
				}

				return trim( $minifier->minify() );
			}

			return $cssContent;

		}

	/**
	 * @param $src
	 * @param string $handle
	 *
	 * @return bool
	 */
	public static function skipMinify($src, $handle = '')
	{
		// Things like WP Fastest Cache Toolbar CSS shouldn't be minified and take up space on the server
		if ($handle !== '' && in_array($handle, Main::instance()->skipAssets['styles'])) {
			return true;
		}

		// Some of these files (e.g. from Oxygen, WooCommerce) are already minified
		$regExps = array(
			'#/wp-content/plugins/wp-asset-clean-up(.*?).min.css#',

			// Formidable Forms
			'#/wp-content/plugins/formidable/css/formidableforms.css#',

			// Oxygen
			//'#/wp-content/plugins/oxygen/component-framework/oxygen.css#',

			// WooCommerce
			'#/wp-content/plugins/woocommerce/assets/css/woocommerce-layout.css#',
			'#/wp-content/plugins/woocommerce/assets/css/woocommerce.css#',
			'#/wp-content/plugins/woocommerce/assets/css/woocommerce-smallscreen.css#',
			'#/wp-content/plugins/woocommerce/assets/css/blocks/style.css#',
			'#/wp-content/plugins/woocommerce/packages/woocommerce-blocks/build/style.css#',

			// Other libraries from the core that end in .min.css
			'#/wp-includes/css/(.*?).min.css#',

			// Files within /wp-content/uploads/ or /wp-content/cache/
			// Could belong to plugins such as "Elementor, "Oxygen" etc.
			'#/wp-content/uploads/elementor/(.*?).css#',
			'#/wp-content/uploads/oxygen/(.*?).css#',
			'#/wp-content/cache/(.*?).css#'

			);

		if (Main::instance()->settings['minify_loaded_css_exceptions'] !== '') {
			$loadedCssExceptionsPatterns = trim(Main::instance()->settings['minify_loaded_css_exceptions']);

			if (strpos($loadedCssExceptionsPatterns, "\n")) {
				// Multiple values (one per line)
				foreach (explode("\n", $loadedCssExceptionsPatterns) as $loadedCssExceptionPattern) {
					$regExps[] = '#'.trim($loadedCssExceptionPattern).'#';
				}
			} else {
				// Only one value?
				$regExps[] = '#'.trim($loadedCssExceptionsPatterns).'#';
			}
		}

		foreach ($regExps as $regExp) {
			if ( preg_match( $regExp, $src ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $htmlSource
	 *
	 * @return mixed|string
	 */
	public static function minifyInlineStyleTags($htmlSource)
	{
		if (stripos($htmlSource, '<style') === false) {
			return $htmlSource; // no STYLE tags
		}

		// DOMDocument extension has to be enabled, otherwise return the HTML source as was (no changes)
		if (! (function_exists('libxml_use_internal_errors') && function_exists('libxml_clear_errors') && class_exists('\DOMDocument'))) {
			return $htmlSource;
		}

		$domTag = new \DOMDocument();
		libxml_use_internal_errors(true);
		$domTag->loadHTML($htmlSource);

		$styleTagsObj = $domTag->getElementsByTagName( 'style' );

		if ($styleTagsObj === null) {
			return $htmlSource;
		}

		$skipTagsContaining = array(
			'astra-theme-css-inline-css',
			'astra-edd-inline-css',
			'et-builder-module-design-cached-inline-styles',
			'fusion-stylesheet-inline-css',
			'woocommerce-general-inline-css',
			'woocommerce-inline-inline-css',
			'data-wpacu-own-inline-style', // Only shown to the admin, irrelevant for any optimization (save resources)
			'data-wpacu-inline-css-file' // already minified/optimized since the INLINE was generated from the cached file
		);

		foreach ($styleTagsObj as $styleTagObj) {
			$originalTag = CleanUp::getOuterHTML($styleTagObj);

			// No need to use extra resources as the tag is already minified
			if (preg_match('('.implode('|', $skipTagsContaining).')', $originalTag)) {
				continue;
			}

			$originalTagContents = (isset($styleTagObj->nodeValue) && trim($styleTagObj->nodeValue) !== '') ? $styleTagObj->nodeValue : false;

			if ($originalTagContents) {
				$newTagContents = OptimizeCss::maybeAlterCssContent($originalTagContents, true, true, array('just_minify'));

				// Only comments or no content added to the inline STYLE tag? Strip it completely to reduce the number of DOM elements
				if ($newTagContents === '/**/' || ! $newTagContents) {
					$htmlSource = str_ireplace('>'.$originalTagContents.'</style', '></style', $htmlSource);

					preg_match_all('#<style.*?>#si', $originalTag, $matchesFromStyle);

					if (isset($matchesFromStyle[0][0]) && $styleTagWithoutContent = $matchesFromStyle[0][0]) {
						$styleTagWithoutContentAlt = str_replace('"', '\'', $styleTagWithoutContent);
						$htmlSource = str_ireplace(array($styleTagWithoutContent.'</style>', $styleTagWithoutContentAlt.'</style>'), '', $htmlSource);
					}
				} else {
					// It has content; do the replacement
					$htmlSource = str_ireplace( '>' . $originalTagContents . '</style',
						'>' . $newTagContents . '</style', $htmlSource );
				}
				libxml_clear_errors();
			}
		}

		return $htmlSource;
	}

	/**
	 * @return bool
	 */
	public static function isMinifyCssEnabled()
	{
		if (defined('WPACU_IS_MINIFY_CSS_ENABLED')) {
			return WPACU_IS_MINIFY_CSS_ENABLED;
		}

		// Request Minify On The Fly
		// It will preview the page with CSS minified
		// Only if the admin is logged-in as it uses more resources (CPU / Memory)
		if (array_key_exists('wpacu_css_minify', $_GET) && Menu::userCanManageAssets()) {
			self::isMinifyCssEnabledChecked(true);
			return true;
		}

		if ( array_key_exists('wpacu_no_css_minify', $_GET) || // not on query string request (debugging purposes)
		     is_admin() || // not for Dashboard view
		     (! Main::instance()->settings['minify_loaded_css']) || // Minify CSS has to be Enabled
		     (Main::instance()->settings['test_mode'] && ! Menu::userCanManageAssets()) ) { // Does not trigger if "Test Mode" is Enabled
			self::isMinifyCssEnabledChecked(false);
			return false;
		}

		if (defined('WPACU_CURRENT_PAGE_ID') && WPACU_CURRENT_PAGE_ID > 0 && is_singular()) {
			// If "Do not minify CSS on this page" is checked in "Asset CleanUp: Options" side meta box
			$pageOptions = MetaBoxes::getPageOptions( WPACU_CURRENT_PAGE_ID );

			if ( isset( $pageOptions['no_css_minify'] ) && $pageOptions['no_css_minify'] ) {
				self::isMinifyCssEnabledChecked(false);
				return false;
			}
		}

		if (OptimizeCss::isOptimizeCssEnabledByOtherParty('if_enabled')) {
			self::isMinifyCssEnabledChecked(false);
			return false;
		}

		return true;
	}

	/**
	 * @param $value
	 */
	public static function isMinifyCssEnabledChecked($value)
	{
		if (! defined('WPACU_IS_MINIFY_CSS_ENABLED')) {
			define('WPACU_IS_MINIFY_CSS_ENABLED', $value);
		}
	}
}
