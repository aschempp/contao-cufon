<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2010
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id$
 */


class Cufon extends Frontend
{
	
	public function injectStyles($objPage, $objLayout, $objPageRegular)
	{
		$arrStylesheets = deserialize($objLayout->stylesheet);
		
		if (is_array($arrStylesheets) && count($arrStylesheets))
		{
			$arrStyles = array();
			$objStyles = $this->Database->execute("SELECT * FROM tl_style WHERE pid IN (" . implode(',', $arrStylesheets) . ") AND cufon='1' AND cufon_font!=''");
			
			while( $objStyles->next() )
			{
				if (is_file(TL_ROOT . '/' . $objStyles->cufon_font))
				{
					$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/cufon/html/cufon.js';
					$GLOBALS['TL_JAVASCRIPT'][] = $objStyles->cufon_font;
					
					$arrStyles[] = "Cufon.replace('" . $objStyles->selector . "'" . (strlen($objStyles->cufon_fontFamily) ? (", { fontFamily: '" . $objStyles->cufon_fontFamily . "' }") : '') . ")";
				}
			}
			
			if (count($arrStyles))
			{
				$GLOBALS['TL_HEAD'][] = '<script type="text/javascript">
<!--//--><![CDATA[//><!--
	' . implode("\n\t", $arrStyles) . ';
//--><!]]>
</script>';
				
				$GLOBALS['TL_MOOTOOLS'][] = '<script type="text/javascript">
<!--//--><![CDATA[//><!--
	Cufon.now()
//--><!]]>
</script>';
			}
		}
	}
	
	
	public function listStyles($row)
	{
		$this->import('StyleSheets');
		
		if ($row['cufon'])
		{
			$icon = '<div style="float:left; margin-top:-20px; padding: 2px 0 2px 20px; background:url(system/modules/cufon/html/cufon16.png) no-repeat left center">' . $GLOBALS['TL_LANG']['tl_style']['cufon'][0] . ' ' . (strlen($row['cufon_fontFamily']) ? '('.$GLOBALS['TL_LANG']['tl_style']['cufon_fontFamily'][0].': '.$row['cufon_fontFamily'].')' : '') . '</div>';
		}
		
		return $icon.$this->StyleSheets->compileDefinition($row);
	}
}

