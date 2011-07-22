<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2010-2011
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @author     Leo Unglaub <leo.unglaub@iserv.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id$
 */


class Cufon extends Frontend
{
	
	public function injectStyles($objPage, &$objLayout, $objPageRegular)
	{
		$arrStylesheets = deserialize($objLayout->stylesheet);
		
		if (is_array($arrStylesheets) && count($arrStylesheets))
		{
			$arrCufon = array();
			$arrFontFace = array();
			$objStyles = $this->Database->execute("SELECT * FROM tl_style WHERE pid IN (" . implode(',', $arrStylesheets) . ") AND invisible='' AND cufon='1' AND cufon_font!=''");
			
			while( $objStyles->next() )
			{
				$arrFonts = array();
				$strCufon = false;
				
				$arrFiles = deserialize($objStyles->cufon_font, true);
				foreach( $arrFiles as $font )
				{
					if (is_file(TL_ROOT . '/' . $font))
					{
						switch( pathinfo($font, PATHINFO_EXTENSION) )
						{
							case 'woff':
								$arrFonts[] = 'url("'.$font.'") format("woff")';
								break;
								
							case 'ttf':
								$arrFonts[] = 'url("'.$font.'") format("truetype")';
								break;
								
							case 'otf':
								// @todo missing support for OTF files
								break;
								
							case 'eot':
								// Make sure eot definition is the topmost rule
								array_insert($arrFonts, 0, array('url("'.$font.'?iefix") format("embedded-opentype")'));
								break;
								
							case 'svg':
								$arrFonts[] = 'url("'.$font.'#'.$objStyles->cufon_fontFamily.'") format("svg")';
								break;
								
							case 'js':
								$strCufon = $font;
								break;
						}
					}
				}
				
				if ($objStyles->cufon_fontFamily != '' && count($arrFonts))
				{
					$arrFontFace[$objStyles->cufon_fontFamily] = '@font-face {
font-family: "' . $objStyles->cufon_fontFamily . '";
src: ' . implode(",\n", $arrFonts) . ';
}';
				}
				
				if ($strCufon !== false)
				{
					$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/cufon/html/cufon.js';
					$GLOBALS['TL_JAVASCRIPT'][] = $strCufon;
					
					$arrOptions = array();
					
					if (strlen($objStyles->cufon_fontFamily))
					{
						$arrOptions[] = "fontFamily: '" . $objStyles->cufon_fontFamily . "'";
					}
					
					if ($objStyles->cufon_hover != '')
					{
						$arrOptions[] = 'hover: true';
						$arrHover = trimsplit(',', $objStyles->cufon_hover);
						$total = count($arrHover);
						
						if ($total > 0 && ($total > 1 || !in_array('a', $arrHover)))
						{
							$arrOptions[] = 'hoverables: { ' . implode(': true, ', $arrHover) . ': true }';
						}
					}
					
					// add additional options
					$arrAdditionalOptions = deserialize($objStyles->cufon_options);
					if (is_array($arrAdditionalOptions) && count($arrAdditionalOptions))
					{
						foreach ($arrAdditionalOptions as $v)
						{
							$arrOptions[] = $v[0] . ': "' . $v[1] . '"';
						}
					}

					$arrCufon[$objStyles->cufon_fontFamily][] = "Cufon.replace('" . $objStyles->selector . "'" . (count($arrOptions) ? ', {'.implode(', ', $arrOptions).'}' : '') . ");";
				}
			}
			
			// Enable font face
			$blnFontFace = count($arrFontFace) ? true : false;
			
			if ($blnFontFace)
			{
				$GLOBALS['TL_HEAD'][] = '<style media="screen">
' . implode("\n", $arrFontFace) . '
</style>';
			}
			
			if (count($arrCufon))
			{
				if ($blnFontFace)
				{
					$strBuffer = '<script type="text/javascript">
<!--//--><![CDATA[//><!--
window.addEvent(\'load\', function() {';

					foreach( $arrCufon as $font => $arrValues )
					{
						$strBuffer .= '
if (!document.body.fontAvailable(\'' . $font . '\')) {
' . implode("\n", $arrValues) . '
}';
					}

					$strBuffer .= '});
//--><!]]>
</script>';

					$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/cufon/html/moo-fontavailable.js';
					$GLOBALS['TL_HEAD'][] = $strBuffer;
				}
				else
				{
					$strImplode = array();
					foreach( $arrCufon as $arrValues )
					{
						$arrImplode[] = implode("\n", $arrValues);
					}
					
					$GLOBALS['TL_HEAD'][] = '<script type="text/javascript">
<!--//--><![CDATA[//><!--
' . implode("\n", $arrImplode) . '
//--><!]]>
</script>';
				}
				
				// Using Cufon.now() does not make sense if we initialize on "load" event
				if (!$blnFontFace)
				{
					$arrMootools = deserialize($objLayout->mootools, true);
					array_insert($arrMootools, 0, array('cufonnow'));
					$objLayout->mootools = $arrMootools;
				}
			}
		}
	}
	
	
	public function listStyles($row)
	{
		$this->import('StyleSheets');
		
		if ($row['cufon'])
		{
			$icon = '<div style="' . (version_compare(VERSION, '2.9', '<') ? 'float:left; margin-top:-20px; ' : '') . 'line-height:20px; padding-left:20px; background:url(system/modules/cufon/html/cufon16.png) no-repeat left center">' . $GLOBALS['TL_LANG']['tl_style']['cufon'][0] . ' ' . (strlen($row['cufon_fontFamily']) ? '('.$GLOBALS['TL_LANG']['tl_style']['cufon_fontFamily'][0].': '.$row['cufon_fontFamily'].')' : '') . '</div>';
		}
		
		return $icon.$this->StyleSheets->compileDefinition($row);
	}
}

